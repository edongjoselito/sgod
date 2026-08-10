import 'dart:async';

import 'package:flutter/foundation.dart';

import '../../core/network/api_exception.dart';
import '../../core/services/connectivity_service.dart';
import 'brigada_models.dart';
import 'brigada_repository.dart';
import 'brigada_store.dart';

/// What a sync pass is currently doing.
enum BrigadaSyncStage { idle, refreshing, downloading }

/// Keeps the Brigada offline cache current.
///
/// Two modes:
///  - **Refresh** ([refresh]) re-pulls everything already in the cache, so the
///    screens the user actually visits stay current. Runs automatically when
///    the device comes back online and when the module is opened with a stale
///    cache.
///  - **Download** ([downloadForOffline]) walks a full prefetch plan — every
///    district, every school checklist, the report, the survey and the recent
///    summary periods — so the whole module works with no connection.
///
/// These reports are read-only, so there is no write outbox here: sync is a
/// one-way pull. (Writes elsewhere in the app go through the core
/// `SyncService` outbox.)
class BrigadaSyncService extends ChangeNotifier {
  BrigadaSyncService({
    required this.repository,
    required this.store,
    required this.connectivity,
  }) {
    _onlineSub = connectivity.stream.listen(_onConnectivityChanged);
  }

  final BrigadaRepository repository;
  final BrigadaStore store;
  final ConnectivityService connectivity;

  /// Cache older than this is refreshed automatically when the module opens.
  static const staleAfter = Duration(minutes: 15);

  /// How many recent months of summary data a full download covers.
  static const _summaryPeriodsToDownload = 6;

  late final StreamSubscription<bool> _onlineSub;

  BrigadaSyncStage _stage = BrigadaSyncStage.idle;
  BrigadaSyncStage get stage => _stage;
  bool get isBusy => _stage != BrigadaSyncStage.idle;

  int _done = 0;
  int _total = 0;
  int get stepsDone => _done;
  int get stepsTotal => _total;

  /// 0..1, or null when no bounded work is running.
  double? get progress => _total == 0 ? null : (_done / _total).clamp(0.0, 1.0);

  String _statusLabel = '';
  String get statusLabel => _statusLabel;

  Object? _lastError;
  Object? get lastError => _lastError;

  DateTime? _lastSyncedAt;

  /// When the cache was last written — survives restarts via the store index.
  DateTime? get lastSyncedAt => _lastSyncedAt ?? store.newestFetch;

  int get cachedScreens => store.entryCount;
  int get cachedBytes => store.totalBytes;

  bool get isOnline => connectivity.isOnline;

  /// True when there is cached data and it has aged past [staleAfter].
  bool get isStale {
    final newest = lastSyncedAt;
    if (newest == null) return false;
    return DateTime.now().difference(newest) > staleAfter;
  }

  /// True when nothing has ever been downloaded.
  bool get isEmpty => store.entryCount == 0;

  // ── Entry points ─────────────────────────────────────────────────────────

  /// Called when the module opens. Refreshes quietly if the cache is stale.
  Future<void> refreshIfStale() async {
    if (!connectivity.isOnline || isBusy) return;
    if (isEmpty || isStale) await refresh();
  }

  /// Re-pull every cached request, newest-viewed first.
  Future<void> refresh() async {
    if (isBusy) return;
    if (!connectivity.isOnline) {
      _fail('You are offline — connect to sync.');
      return;
    }

    final keys = store.keys();
    if (keys.isEmpty) {
      // Nothing browsed yet: seed the always-useful screens.
      await downloadForOffline(includeChecklists: false);
      return;
    }

    _begin(BrigadaSyncStage.refreshing, keys.length, 'Refreshing reports');
    var failures = 0;
    for (final key in keys) {
      final request = _requestFromKey(key);
      if (request == null) {
        _step(_statusLabel);
        continue;
      }
      try {
        await repository.refreshRaw(request);
      } on ApiException catch (e) {
        if (e.isUnauthorized) {
          _finish(error: e);
          return;
        }
        failures++;
      } catch (_) {
        failures++;
      }
      _step('Refreshing reports');
    }
    _finish(
      error: failures > 0
          ? '$failures of ${keys.length} reports could not be refreshed.'
          : null,
    );
  }

  /// Download the whole module for offline use.
  ///
  /// [includeChecklists] pulls every school's preparedness checklist — the
  /// bulk of the payload — one bundled request per district.
  Future<void> downloadForOffline({
    String? sy,
    bool includeChecklists = true,
  }) async {
    if (isBusy) return;
    if (!connectivity.isOnline) {
      _fail('You are offline — connect to download.');
      return;
    }

    _begin(BrigadaSyncStage.downloading, 5, 'Preparing');
    var failures = 0;

    try {
      // ── Step 1: metadata, so we know which school year to walk.
      _setLabel('Checking school years');
      final meta = await repository
          .fetch(BrigadaRepository.metaRequest(), BrigadaMeta.fromJson);
      final schoolYear = sy ?? meta.data.currentSy;
      _step('Checking school years');

      // ── Step 2: districts — also tells us how much work remains.
      _setLabel('Districts');
      final districts = await repository.fetch(
        BrigadaRepository.districtsRequest(schoolYear),
        SpcDistrictList.fromJson,
      );
      _step('Districts');

      // ── Step 3: contribution periods.
      _setLabel('Report periods');
      final periods = await repository.fetch(
        BrigadaRepository.periodsRequest(),
        BrigadaRepository.parsePeriods,
      );
      _step('Report periods');

      // Now the total is knowable: districts (×2 when checklists are
      // included) plus one request per summary period, plus report+survey.
      final districtList = districts.data.districts;
      final periodList =
          periods.data.take(_summaryPeriodsToDownload).toList(growable: false);
      _total = 5 +
          districtList.length * (includeChecklists ? 2 : 1) +
          periodList.length;
      notifyListeners();

      // ── Step 4: division-wide preparedness report.
      _setLabel('Preparedness report');
      try {
        await repository.refreshRaw(BrigadaRepository.reportRequest(schoolYear));
      } catch (_) {
        failures++;
      }
      _step('Preparedness report');

      // ── Step 5: partner satisfaction survey.
      _setLabel('Survey results');
      try {
        await repository.refreshRaw(BrigadaRepository.surveyRequest());
      } catch (_) {
        failures++;
      }
      _step('Survey results');

      // ── Per-district school lists and checklists.
      for (final district in districtList) {
        _setLabel(district.name);
        try {
          await repository.refreshRaw(
            BrigadaRepository.districtSchoolsRequest(district.id, schoolYear),
          );
        } catch (_) {
          failures++;
        }
        _step(district.name);

        if (!includeChecklists) continue;
        try {
          await repository.cacheDistrictChecklists(district.id, schoolYear);
        } catch (_) {
          failures++;
        }
        _step(district.name);
      }

      // ── Recent summary periods.
      for (final period in periodList) {
        _setLabel(period.label);
        try {
          await repository.refreshRaw(
            BrigadaRepository.summaryRequest(period.year, period.month),
          );
        } catch (_) {
          failures++;
        }
        _step(period.label);
      }

      _finish(
        error: failures > 0
            ? '$failures item${failures == 1 ? '' : 's'} could not be downloaded. Try again when the connection is steadier.'
            : null,
      );
    } on ApiException catch (e) {
      _finish(error: e.isUnauthorized ? e : e.message);
    } catch (e) {
      _finish(error: e);
    }
  }

  /// Drop every cached Brigada report.
  Future<void> clearCache() async {
    await store.clear();
    _lastSyncedAt = null;
    _lastError = null;
    notifyListeners();
  }

  // ── Internals ────────────────────────────────────────────────────────────

  /// Rebuild a [BrigadaRequest] from a cache key (`endpoint?a=1&b=2`).
  ///
  /// Per-school checklist entries are skipped: they are re-hydrated in bulk by
  /// the district bundle, so refreshing them one by one would issue hundreds
  /// of requests for the same data.
  BrigadaRequest? _requestFromKey(String key) {
    final split = key.indexOf('?');
    final endpoint = split == -1 ? key : key.substring(0, split);
    if (endpoint == 'spc_school_checklist') return null;
    if (split == -1) return BrigadaRequest(endpoint);

    final query = <String, dynamic>{};
    for (final pair in key.substring(split + 1).split('&')) {
      if (pair.isEmpty) continue;
      final eq = pair.indexOf('=');
      if (eq == -1) continue;
      query[pair.substring(0, eq)] = pair.substring(eq + 1);
    }
    return BrigadaRequest(endpoint, query);
  }

  Future<void> _onConnectivityChanged(bool online) async {
    notifyListeners();
    if (online && !isEmpty && isStale) {
      await refresh();
    }
  }

  void _begin(BrigadaSyncStage stage, int total, String label) {
    _stage = stage;
    _done = 0;
    _total = total;
    _statusLabel = label;
    _lastError = null;
    notifyListeners();
  }

  void _setLabel(String label) {
    _statusLabel = label;
    notifyListeners();
  }

  void _step(String label) {
    _done++;
    _statusLabel = label;
    notifyListeners();
  }

  void _fail(Object error) {
    _lastError = error;
    notifyListeners();
  }

  void _finish({Object? error}) {
    _stage = BrigadaSyncStage.idle;
    _done = 0;
    _total = 0;
    _statusLabel = '';
    _lastError = error;
    if (error == null) _lastSyncedAt = DateTime.now();
    notifyListeners();
  }

  @override
  void dispose() {
    _onlineSub.cancel();
    super.dispose();
  }
}
