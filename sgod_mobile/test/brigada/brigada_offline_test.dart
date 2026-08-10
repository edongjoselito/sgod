import 'dart:io';

import 'package:connectivity_plus/connectivity_plus.dart';
import 'package:flutter/services.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:sgod_mobile/brigada/data/brigada_models.dart';
import 'package:sgod_mobile/brigada/data/brigada_repository.dart';
import 'package:sgod_mobile/brigada/data/brigada_store.dart';
import 'package:sgod_mobile/core/network/api_client.dart';
import 'package:sgod_mobile/core/network/api_exception.dart';
import 'package:sgod_mobile/core/services/connectivity_service.dart';

/// Stands in for the HTTP layer: records calls, can be told to fail, and
/// returns whatever payload the test sets.
class _FakeApi extends ApiClient {
  int calls = 0;
  bool shouldFail = false;
  int failStatus = 500;
  Map<String, dynamic> payload = const {};

  @override
  Future<dynamic> get(String path, {Map<String, dynamic>? query}) async {
    calls++;
    if (shouldFail) {
      throw ApiException('network down', statusCode: failStatus);
    }
    return payload;
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
  late _FakeApi api;
  late _FakeConnectivity connectivity;
  late BrigadaRepository repo;

  Map<String, dynamic> reportPayload(int submissions) => {
        'sy': '2026-2027',
        'submission_count': submissions,
        'categories': const [],
        'totals': {'fully': 1, 'partially': 2, 'not_prepared': 3},
      };

  setUp(() async {
    // In the test environment path_provider has no platform implementation,
    // so the store runs in its in-memory fallback mode — which is exactly the
    // degraded path we want covered too.
    store = BrigadaStore();
    await store.init();
    api = _FakeApi();
    connectivity = _FakeConnectivity();
    repo = BrigadaRepository(
      api: api,
      store: store,
      connectivity: connectivity,
    );
  });

  group('BrigadaStore', () {
    test('round-trips a payload and tracks freshness', () async {
      await store.write('spc_report?sy=2026-2027', {'sy': '2026-2027'});

      expect(store.has('spc_report?sy=2026-2027'), isTrue);
      expect(await store.read('spc_report?sy=2026-2027'),
          {'sy': '2026-2027'});
      expect(store.entryCount, 1);
      expect(store.totalBytes, greaterThan(0));
      expect(store.newestFetch, isNotNull);
      expect(store.peek('spc_report?sy=2026-2027')!.age.inSeconds, lessThan(5));
    });

    test('returns null for keys that were never written', () async {
      expect(await store.read('nope'), isNull);
      expect(store.peek('nope'), isNull);
    });

    test('keys() lists newest first', () async {
      await store.write('a', {'v': 1});
      await Future<void>.delayed(const Duration(milliseconds: 5));
      await store.write('b', {'v': 2});
      expect(store.keys().first, 'b');
    });

    test('clear() empties the cache', () async {
      await store.write('a', {'v': 1});
      await store.clear();
      expect(store.entryCount, 0);
      expect(await store.read('a'), isNull);
    });

    test('switching accounts discards the previous user\'s data', () async {
      await store.bindUser('1301260');
      await store.write('spc_report?sy=2026-2027', {'sy': '2026-2027'});
      expect(store.entryCount, 1);

      await store.bindUser('9999999');
      expect(store.entryCount, 0);
      expect(await store.read('spc_report?sy=2026-2027'), isNull);
    });

    test('rebinding the same account keeps the cache', () async {
      await store.bindUser('1301260');
      await store.write('a', {'v': 1});
      await store.bindUser('1301260');
      expect(store.entryCount, 1);
    });
  });

  group('BrigadaRepository', () {
    test('a successful fetch caches the payload', () async {
      api.payload = reportPayload(90);

      final result = await repo.fetch(
        BrigadaRepository.reportRequest('2026-2027'),
        SpcReport.fromJson,
      );

      expect(result.fromCache, isFalse);
      expect(result.data.submissionCount, 90);
      expect(result.refreshError, isNull);
      expect(store.has('spc_report?sy=2026-2027'), isTrue);
    });

    test('offline serves the cached copy without touching the network',
        () async {
      api.payload = reportPayload(90);
      await repo.fetch(
          BrigadaRepository.reportRequest('2026-2027'), SpcReport.fromJson);
      final callsAfterWarmup = api.calls;

      connectivity.online = false;
      final result = await repo.fetch(
          BrigadaRepository.reportRequest('2026-2027'), SpcReport.fromJson);

      expect(api.calls, callsAfterWarmup, reason: 'must not hit the network');
      expect(result.fromCache, isTrue);
      expect(result.data.submissionCount, 90);
      expect(result.fetchedAt, isNotNull);
    });

    test('offline with nothing cached raises BrigadaOfflineException',
        () async {
      connectivity.online = false;
      expect(
        () => repo.fetch(
            BrigadaRepository.reportRequest('2026-2027'), SpcReport.fromJson),
        throwsA(isA<BrigadaOfflineException>()),
      );
    });

    test('a failed refresh falls back to cache and reports the error',
        () async {
      api.payload = reportPayload(90);
      await repo.fetch(
          BrigadaRepository.reportRequest('2026-2027'), SpcReport.fromJson);

      api.shouldFail = true;
      final result = await repo.fetch(
          BrigadaRepository.reportRequest('2026-2027'), SpcReport.fromJson);

      expect(result.fromCache, isTrue);
      expect(result.data.submissionCount, 90);
      expect(result.refreshError, isA<ApiException>());
    });

    test('a failed request with no cache rethrows', () async {
      api.shouldFail = true;
      expect(
        () => repo.fetch(
            BrigadaRepository.reportRequest('2026-2027'), SpcReport.fromJson),
        throwsA(isA<ApiException>()),
      );
    });

    test('401 is never masked by the cache — the session must be re-checked',
        () async {
      api.payload = reportPayload(90);
      await repo.fetch(
          BrigadaRepository.reportRequest('2026-2027'), SpcReport.fromJson);

      api.shouldFail = true;
      api.failStatus = 401;
      expect(
        () => repo.fetch(
            BrigadaRepository.reportRequest('2026-2027'), SpcReport.fromJson),
        throwsA(isA<ApiException>()
            .having((e) => e.isUnauthorized, 'isUnauthorized', isTrue)),
      );
    });

    test('cached() reads without any network call', () async {
      api.payload = reportPayload(90);
      await repo.fetch(
          BrigadaRepository.reportRequest('2026-2027'), SpcReport.fromJson);
      final before = api.calls;

      final cached = await repo.cached(
          BrigadaRepository.reportRequest('2026-2027'), SpcReport.fromJson);

      expect(api.calls, before);
      expect(cached, isNotNull);
      expect(cached!.fromCache, isTrue);
      expect(cached.data.submissionCount, 90);
    });

    test('a district bundle hydrates one cache entry per school', () async {
      api.payload = {
        'sy': '2026-2027',
        'district_id': 1,
        'checklists': [
          {
            'sy': '2026-2027',
            'school_id': '129152',
            'school_name': 'BAN-AO ES',
            'submitted': true,
            'item_count': 29,
            'answered': 6,
            'fully': 6,
            'partially': 0,
            'not_prepared': 0,
            'categories': const [],
          },
          {
            'sy': '2026-2027',
            'school_id': '129153',
            'school_name': 'CAMPAWAN ES',
            'submitted': false,
            'item_count': 29,
            'answered': 0,
            'fully': 0,
            'partially': 0,
            'not_prepared': 0,
            'categories': const [],
          },
        ],
      };

      final count = await repo.cacheDistrictChecklists(1, '2026-2027');
      expect(count, 2);

      // Each school is now individually readable offline.
      connectivity.online = false;
      final one = await repo.fetch(
        BrigadaRepository.checklistRequest('129152', '2026-2027'),
        SpcChecklist.fromJson,
      );
      expect(one.fromCache, isTrue);
      expect(one.data.schoolName, 'BAN-AO ES');
      expect(one.data.fully, 6);

      final two = await repo.fetch(
        BrigadaRepository.checklistRequest('129153', '2026-2027'),
        SpcChecklist.fromJson,
      );
      expect(two.data.submitted, isFalse);
    });

    test('pinned endpoints are marked for every sync', () async {
      api.payload = reportPayload(90);
      await repo.fetch(
          BrigadaRepository.reportRequest('2026-2027'), SpcReport.fromJson);
      expect(store.peek('spc_report?sy=2026-2027')!.pinned, isTrue);

      api.payload = {'sy': '2026-2027', 'district_id': 1, 'schools': const []};
      await repo.fetch(
        BrigadaRepository.districtSchoolsRequest(1, '2026-2027'),
        SpcSchoolList.fromJson,
      );
      expect(
        store.peek('spc_district_schools?district_id=1&sy=2026-2027')!.pinned,
        isFalse,
      );
    });
  });

  // The groups above run through the in-memory fallback because path_provider
  // has no test implementation. These mock the channel so the real on-disk
  // path — the one that has to survive an app restart — is exercised too.
  group('BrigadaStore on disk', () {
    late Directory tempDir;

    setUp(() async {
      tempDir = await Directory.systemTemp.createTemp('brigada_store_test');
      TestDefaultBinaryMessengerBinding.instance.defaultBinaryMessenger
          .setMockMethodCallHandler(
        const MethodChannel('plugins.flutter.io/path_provider'),
        (call) async => tempDir.path,
      );
    });

    tearDown(() async {
      TestDefaultBinaryMessengerBinding.instance.defaultBinaryMessenger
          .setMockMethodCallHandler(
        const MethodChannel('plugins.flutter.io/path_provider'),
        null,
      );
      if (await tempDir.exists()) await tempDir.delete(recursive: true);
    });

    test('writes payloads to disk under the cache directory', () async {
      final disk = BrigadaStore();
      await disk.init();
      await disk.write('spc_report?sy=2026-2027', {'sy': '2026-2027'});

      final dir = Directory('${tempDir.path}/brigada_cache');
      expect(await dir.exists(), isTrue);
      final files = dir.listSync().whereType<File>().toList();
      expect(files, isNotEmpty);
    });

    test('a fresh store reloads the index and payloads after a restart',
        () async {
      final first = BrigadaStore();
      await first.init();
      await first.bindUser('1301260');
      await first.write('spc_report?sy=2026-2027', {'sy': '2026-2027'},
          pinned: true);
      await first.write('summary?month=6&year=2026', {'month': 6});
      // Let the debounced index write land.
      await Future<void>.delayed(const Duration(milliseconds: 600));

      // Simulate relaunching the app: brand new store, same directory.
      final second = BrigadaStore();
      await second.init();

      expect(second.entryCount, 2);
      expect(second.peek('spc_report?sy=2026-2027')!.pinned, isTrue);
      expect(second.newestFetch, isNotNull);
      expect(
        await second.read('spc_report?sy=2026-2027'),
        {'sy': '2026-2027'},
      );
      expect(await second.read('summary?month=6&year=2026'), {'month': 6});

      // And the reloaded cache still belongs to the same account.
      await second.bindUser('1301260');
      expect(second.entryCount, 2);
    });

    test('clear() removes the files as well as the index', () async {
      final disk = BrigadaStore();
      await disk.init();
      await disk.write('a', {'v': 1});
      await Future<void>.delayed(const Duration(milliseconds: 600));

      await disk.clear();
      expect(disk.entryCount, 0);

      final reopened = BrigadaStore();
      await reopened.init();
      expect(reopened.entryCount, 0);
      expect(await reopened.read('a'), isNull);
    });

    test('a payload deleted underneath the index reads as a miss', () async {
      final disk = BrigadaStore();
      await disk.init();
      await disk.write('a', {'v': 1});
      await Future<void>.delayed(const Duration(milliseconds: 600));

      // Drop the file but leave the index entry behind.
      final reopened = BrigadaStore();
      await reopened.init();
      final dir = Directory('${tempDir.path}/brigada_cache');
      for (final file in dir.listSync().whereType<File>()) {
        if (!file.path.endsWith('index.json')) file.deleteSync();
      }

      expect(await reopened.read('a'), isNull);
      expect(reopened.has('a'), isFalse);
    });
  });

  group('BrigadaRepository pinning', () {
    test('marks sync-critical endpoints', () async {
      api.payload = reportPayload(90);
      await repo.fetch(
          BrigadaRepository.reportRequest('2026-2027'), SpcReport.fromJson);
      expect(store.peek('spc_report?sy=2026-2027')!.pinned, isTrue);

      api.payload = {'sy': '2026-2027', 'district_id': 1, 'schools': const []};
      await repo.fetch(
        BrigadaRepository.districtSchoolsRequest(1, '2026-2027'),
        SpcSchoolList.fromJson,
      );
      expect(
        store.peek('spc_district_schools?district_id=1&sy=2026-2027')!.pinned,
        isFalse,
      );
    });
  });
}
