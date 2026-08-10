import 'package:connectivity_plus/connectivity_plus.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:sgod_mobile/brigada/data/brigada_repository.dart';
import 'package:sgod_mobile/brigada/data/brigada_store.dart';
import 'package:sgod_mobile/brigada/data/brigada_sync.dart';
import 'package:sgod_mobile/core/network/api_client.dart';
import 'package:sgod_mobile/core/network/api_exception.dart';
import 'package:sgod_mobile/core/services/connectivity_service.dart';

/// Serves a canned payload per endpoint and records every request, so a test
/// can assert exactly which endpoints a sync pass touched.
class _RoutingApi extends ApiClient {
  final requests = <String>[];
  final Set<String> failingEndpoints = {};

  @override
  Future<dynamic> get(String path, {Map<String, dynamic>? query}) async {
    final endpoint = path.replaceFirst('api_brigada/', '');
    final key = query == null || query.isEmpty
        ? endpoint
        : '$endpoint?${(query.keys.toList()..sort()).map((k) => '$k=${query[k]}').join('&')}';
    requests.add(key);

    if (failingEndpoints.contains(endpoint)) {
      throw ApiException('boom', statusCode: 500);
    }

    switch (endpoint) {
      case 'meta':
        return {
          'current_sy': '2026-2027',
          'school_years': ['2026-2027'],
          'section': 'Social Mobilization and Networking',
        };
      case 'spc_districts':
        return {
          'sy': '2026-2027',
          'districts': [
            {
              'id': 1,
              'name': 'Baganga North',
              'school_count': 19,
              'submitted_count': 4,
            },
            {
              'id': 2,
              'name': 'Baganga South',
              'school_count': 26,
              'submitted_count': 10,
            },
          ],
        };
      case 'summary_periods':
        return {
          'periods': [
            {'year': 2026, 'month': 6, 'label': 'June 2026', 'records': 2506},
            {'year': 2026, 'month': 5, 'label': 'May 2026', 'records': 22},
          ],
        };
      case 'spc_checklists':
        return {
          'sy': '2026-2027',
          'district_id': query?['district_id'],
          'checklists': [
            {
              'sy': '2026-2027',
              'school_id': 'S${query?['district_id']}',
              'school_name': 'School ${query?['district_id']}',
              'submitted': true,
              'item_count': 29,
              'answered': 6,
              'fully': 6,
              'partially': 0,
              'not_prepared': 0,
              'categories': const [],
            },
          ],
        };
      default:
        return {'endpoint': endpoint};
    }
  }
}

class _FakeConnectivity extends ConnectivityService {
  _FakeConnectivity() : super(connectivity: Connectivity());

  bool online = true;

  @override
  bool get isOnline => online;
}

void main() {
  TestWidgetsFlutterBinding.ensureInitialized();

  late BrigadaStore store;
  late _RoutingApi api;
  late _FakeConnectivity connectivity;
  late BrigadaRepository repo;
  late BrigadaSyncService sync;

  setUp(() async {
    store = BrigadaStore();
    await store.init();
    api = _RoutingApi();
    connectivity = _FakeConnectivity();
    repo = BrigadaRepository(
      api: api,
      store: store,
      connectivity: connectivity,
    );
    sync = BrigadaSyncService(
      repository: repo,
      store: store,
      connectivity: connectivity,
    );
  });

  tearDown(() => sync.dispose());

  group('refresh', () {
    test('re-pulls every cached request, preserving its query', () async {
      await store.write('spc_report?sy=2026-2027', {'sy': '2026-2027'});
      await store.write('summary?month=6&year=2026', {'month': 6});
      api.requests.clear();

      await sync.refresh();

      // Cache keys must round-trip back to the same keys, or every sync would
      // quietly create duplicate cache entries instead of refreshing.
      expect(
        api.requests,
        containsAll(['spc_report?sy=2026-2027', 'summary?month=6&year=2026']),
      );
      expect(store.entryCount, 2);
      expect(sync.lastError, isNull);
      expect(sync.isBusy, isFalse);
    });

    test('skips per-school checklists, which the district bundle re-hydrates',
        () async {
      await store.write('spc_school_checklist?school_id=129152&sy=2026-2027',
          {'school_id': '129152'});
      await store.write('spc_report?sy=2026-2027', {'sy': '2026-2027'});
      api.requests.clear();

      await sync.refresh();

      expect(api.requests, ['spc_report?sy=2026-2027']);
    });

    test('reports partial failures without losing the good data', () async {
      await store.write('spc_report?sy=2026-2027', {'sy': '2026-2027'});
      await store.write('survey_results', {'total_surveys': 0});
      api.failingEndpoints.add('survey_results');

      await sync.refresh();

      expect(sync.lastError, isNotNull);
      expect(sync.lastError.toString(), contains('1 of 2'));
      // The entry that did refresh is still cached and readable.
      expect(await store.read('spc_report?sy=2026-2027'), isNotNull);
    });

    test('offline refuses to sync and says why', () async {
      await store.write('spc_report?sy=2026-2027', {'sy': '2026-2027'});
      connectivity.online = false;
      api.requests.clear();

      await sync.refresh();

      expect(api.requests, isEmpty);
      expect(sync.lastError.toString(), contains('offline'));
    });

    test('an empty cache seeds the core reports instead', () async {
      expect(sync.isEmpty, isTrue);

      await sync.refresh();

      // Falls through to a download that skips the heavy checklist bundles.
      expect(api.requests, contains('meta'));
      expect(api.requests, contains('spc_districts?sy=2026-2027'));
      expect(api.requests, contains('survey_results'));
      expect(
        api.requests.where((r) => r.startsWith('spc_checklists')),
        isEmpty,
      );
    });
  });

  group('downloadForOffline', () {
    test('walks districts, checklists and recent periods', () async {
      await sync.downloadForOffline();

      expect(api.requests, contains('meta'));
      expect(api.requests, contains('spc_districts?sy=2026-2027'));
      expect(api.requests, contains('summary_periods'));
      expect(api.requests, contains('spc_report?sy=2026-2027'));
      expect(api.requests, contains('survey_results'));

      // One school list and one checklist bundle per district.
      expect(api.requests,
          contains('spc_district_schools?district_id=1&sy=2026-2027'));
      expect(api.requests,
          contains('spc_checklists?district_id=2&sy=2026-2027'));

      // Every period that has records.
      expect(api.requests, contains('summary?month=6&year=2026'));
      expect(api.requests, contains('summary?month=5&year=2026'));

      // The bundle fanned out into individually readable school entries.
      expect(store.has('spc_school_checklist?school_id=S1&sy=2026-2027'),
          isTrue);
      expect(store.has('spc_school_checklist?school_id=S2&sy=2026-2027'),
          isTrue);

      expect(sync.lastError, isNull);
      expect(sync.isBusy, isFalse);
      expect(sync.lastSyncedAt, isNotNull);
      expect(sync.isEmpty, isFalse);
    });

    test('progress ends at 100% and resets when done', () async {
      final progressSeen = <double>[];
      sync.addListener(() {
        final p = sync.progress;
        if (p != null) progressSeen.add(p);
      });

      await sync.downloadForOffline();

      expect(progressSeen, isNotEmpty);
      expect(progressSeen.reduce((a, b) => a > b ? a : b), 1.0);
      expect(sync.progress, isNull, reason: 'progress clears when idle');
    });

    test('honours an explicit school year', () async {
      await sync.downloadForOffline(sy: '2025-2026');
      expect(api.requests, contains('spc_districts?sy=2025-2026'));
      expect(api.requests, contains('spc_report?sy=2025-2026'));
    });

    test('offline refuses to download', () async {
      connectivity.online = false;
      await sync.downloadForOffline();
      expect(api.requests, isEmpty);
      expect(sync.lastError.toString(), contains('offline'));
    });

    test('a failing endpoint is counted but does not abort the pass', () async {
      api.failingEndpoints.add('survey_results');

      await sync.downloadForOffline();

      expect(sync.lastError.toString(), contains('could not be downloaded'));
      // The rest of the walk still happened.
      expect(api.requests, contains('summary?month=6&year=2026'));
    });
  });

  group('staleness', () {
    test('a fresh cache is not stale; an empty one is not either', () async {
      expect(sync.isStale, isFalse);
      await sync.downloadForOffline();
      expect(sync.isStale, isFalse);
      expect(sync.cachedScreens, greaterThan(0));
      expect(sync.cachedBytes, greaterThan(0));
    });

    test('clearCache empties the store', () async {
      await sync.downloadForOffline();
      expect(sync.isEmpty, isFalse);

      await sync.clearCache();

      expect(sync.isEmpty, isTrue);
      expect(sync.cachedScreens, 0);
      expect(sync.lastSyncedAt, isNull);
    });
  });
}
