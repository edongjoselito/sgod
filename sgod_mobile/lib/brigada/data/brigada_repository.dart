import 'package:flutter/foundation.dart';

import '../../core/network/api_client.dart';
import '../../core/network/api_exception.dart';
import '../../core/services/connectivity_service.dart';
import 'brigada_models.dart';
import 'brigada_store.dart';

/// Raised when a screen has no cached copy and the device cannot reach the
/// server — the only situation where a Brigada screen has nothing to show.
class BrigadaOfflineException implements Exception {
  const BrigadaOfflineException([
    this.message =
        'You are offline and this report has not been downloaded yet.',
  ]);

  final String message;

  @override
  String toString() => message;
}

/// A payload plus where it came from, so every screen can honestly label
/// whether the user is looking at live or cached numbers.
class BrigadaResult<T> {
  const BrigadaResult({
    required this.data,
    required this.fromCache,
    required this.fetchedAt,
    this.refreshError,
  });

  final T data;

  /// True when served from the offline store rather than the network.
  final bool fromCache;

  /// When the underlying payload was pulled from the server.
  final DateTime? fetchedAt;

  /// Set when a refresh was attempted, failed, and cached data was served
  /// instead. Screens surface this as a soft warning, not a blocking error.
  final Object? refreshError;
}

/// One addressable Brigada endpoint, and the cache key it maps to.
@immutable
class BrigadaRequest {
  const BrigadaRequest(this.endpoint, [this.query = const {}]);

  final String endpoint;
  final Map<String, dynamic> query;

  String get path => 'api_brigada/$endpoint';

  /// Stable cache key — endpoint plus its query in sorted order, so two
  /// equivalent requests always resolve to the same stored document.
  String get cacheKey {
    if (query.isEmpty) return endpoint;
    final keys = query.keys.toList()..sort();
    final pairs = keys.map((k) => '$k=${query[k]}').join('&');
    return '$endpoint?$pairs';
  }

  @override
  bool operator ==(Object other) =>
      other is BrigadaRequest && other.cacheKey == cacheKey;

  @override
  int get hashCode => cacheKey.hashCode;
}

/// Cache-first data access for the Brigada Eskwela module.
///
/// Screens use it in two phases (see `BrigadaLoader`):
///   1. [cached] paints instantly from the offline store, if anything is
///      stored, so navigation never blocks on the network.
///   2. [fetch] revalidates against the server and replaces the payload.
///
/// When offline, phase 2 is skipped and phase 1 is all there is; when nothing
/// was ever downloaded, [fetch] raises [BrigadaOfflineException].
class BrigadaRepository {
  BrigadaRepository({
    required this.api,
    required this.store,
    required this.connectivity,
  });

  final ApiClient api;
  final BrigadaStore store;
  final ConnectivityService connectivity;

  /// Requests kept fresh by every sync, regardless of what the user browsed.
  static const _pinnedEndpoints = {
    'meta',
    'spc_districts',
    'spc_report',
    'survey_results',
    'summary_periods',
  };

  // ── Core access ──────────────────────────────────────────────────────────

  /// Whatever is in the offline store for [request], or null.
  Future<BrigadaResult<T>?> cached<T>(
    BrigadaRequest request,
    T Function(Map<String, dynamic>) parse,
  ) async {
    final key = request.cacheKey;
    final json = await store.read(key);
    if (json == null) return null;
    try {
      return BrigadaResult<T>(
        data: parse(json),
        fromCache: true,
        fetchedAt: store.peek(key)?.fetchedAt,
      );
    } catch (e) {
      debugPrint('BrigadaRepository: cached payload for $key is unreadable: $e');
      return null;
    }
  }

  /// Fetch [request] from the server, caching the response.
  ///
  /// Falls back to the cached copy when the request fails (attaching the
  /// error), and raises [BrigadaOfflineException] when the device is offline
  /// with nothing stored.
  Future<BrigadaResult<T>> fetch<T>(
    BrigadaRequest request,
    T Function(Map<String, dynamic>) parse,
  ) async {
    if (!connectivity.isOnline) {
      final fallback = await cached(request, parse);
      if (fallback != null) return fallback;
      throw const BrigadaOfflineException();
    }

    try {
      final json = await refreshRaw(request);
      return BrigadaResult<T>(
        data: parse(json),
        fromCache: false,
        fetchedAt: DateTime.now(),
      );
    } catch (e) {
      if (e is ApiException && e.isUnauthorized) rethrow;
      final fallback = await cached(request, parse);
      if (fallback != null) {
        return BrigadaResult<T>(
          data: fallback.data,
          fromCache: true,
          fetchedAt: fallback.fetchedAt,
          refreshError: e,
        );
      }
      rethrow;
    }
  }

  /// Fetch and persist a request without parsing it. Used by sync, and by
  /// [cacheDistrictChecklists] which fans one response into many entries.
  Future<Map<String, dynamic>> refreshRaw(BrigadaRequest request) async {
    final data = await api.get(request.path, query: request.query);
    if (data is! Map) {
      throw ApiException('Unexpected response for ${request.endpoint}.');
    }
    final json = data.cast<String, dynamic>();
    await store.write(
      request.cacheKey,
      json,
      pinned: _pinnedEndpoints.contains(request.endpoint),
    );
    return json;
  }

  // ── Request builders ─────────────────────────────────────────────────────

  static BrigadaRequest metaRequest() => const BrigadaRequest('meta');

  static BrigadaRequest districtsRequest(String sy) =>
      BrigadaRequest('spc_districts', {'sy': sy});

  static BrigadaRequest districtSchoolsRequest(int districtId, String sy) =>
      BrigadaRequest('spc_district_schools', {
        'district_id': districtId,
        'sy': sy,
      });

  static BrigadaRequest checklistRequest(String schoolId, String sy) =>
      BrigadaRequest('spc_school_checklist', {
        'school_id': schoolId,
        'sy': sy,
      });

  static BrigadaRequest checklistBundleRequest(int districtId, String sy) =>
      BrigadaRequest('spc_checklists', {'district_id': districtId, 'sy': sy});

  static BrigadaRequest reportRequest(String sy) =>
      BrigadaRequest('spc_report', {'sy': sy});

  static BrigadaRequest responsesRequest(String sy, int itemId, int value) =>
      BrigadaRequest('spc_report_responses', {
        'sy': sy,
        'item_id': itemId,
        'value': value,
      });

  static BrigadaRequest periodsRequest() =>
      const BrigadaRequest('summary_periods');

  static BrigadaRequest summaryRequest(int year, int month) =>
      BrigadaRequest('summary', {'year': year, 'month': month});

  static BrigadaRequest detailsRequest({
    required int year,
    required int month,
    String card = '',
    String scope = '',
    String type = '',
    String schoolId = '',
  }) {
    final query = <String, dynamic>{'year': year, 'month': month};
    if (card.isNotEmpty) query['card'] = card;
    if (scope.isNotEmpty) query['scope'] = scope;
    if (type.isNotEmpty) query['type'] = type;
    if (schoolId.isNotEmpty) query['school_id'] = schoolId;
    return BrigadaRequest('summary_details', query);
  }

  static BrigadaRequest surveyRequest() =>
      const BrigadaRequest('survey_results');

  // ── Parsers, paired with their requests ──────────────────────────────────

  static List<BrigadaPeriod> parsePeriods(Map<String, dynamic> json) =>
      (json['periods'] as List? ?? const [])
          .whereType<Map>()
          .map((e) => BrigadaPeriod.fromJson(e.cast<String, dynamic>()))
          .toList();

  // ── Composite operations ─────────────────────────────────────────────────

  /// Pull every checklist in a district in one request and fan the result out
  /// into per-school cache entries, so the checklist screen works offline
  /// without issuing one request per school.
  ///
  /// Returns the number of checklists cached.
  Future<int> cacheDistrictChecklists(int districtId, String sy) async {
    final json = await refreshRaw(checklistBundleRequest(districtId, sy));
    final checklists = (json['checklists'] as List? ?? const [])
        .whereType<Map>()
        .map((e) => e.cast<String, dynamic>())
        .toList();
    for (final entry in checklists) {
      final schoolId = '${entry['school_id'] ?? ''}';
      if (schoolId.isEmpty) continue;
      await store.write(checklistRequest(schoolId, sy).cacheKey, entry);
    }
    return checklists.length;
  }
}
