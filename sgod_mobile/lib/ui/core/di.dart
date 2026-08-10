import 'package:flutter/foundation.dart';

import '../../core/network/api_client.dart';
import '../../core/services/app_database.dart';
import '../../core/services/cache_service.dart';
import '../../core/services/connectivity_service.dart';
import '../../core/services/secure_storage_service.dart';
import '../../core/services/sync_service.dart';
import '../../data/repositories/accomplishments_repository.dart';
import '../../data/repositories/activity_designs_repository.dart';
import '../../data/repositories/auth_repository.dart';
import '../../data/repositories/dashboard_repository.dart';
import '../../data/repositories/issues_repository.dart';
import '../../data/repositories/memos_repository.dart';
import '../../data/repositories/schools_repository.dart';
import '../../data/repositories/section_users_repository.dart';
import '../../data/repositories/whereabouts_repository.dart';
import '../../data/repositories/adopt_a_school_repository.dart';
import '../../core/services/db_native.dart' if (dart.library.html) '../../core/services/db_web.dart' as platform;

/// Dependency injection container — single place where services and
/// repositories are constructed and shared across the app.
class DI {
  DI._();

  static late final ApiClient api;
  static late final SecureStorageService storage;
  static late final CacheService cache;
  static late final ConnectivityService connectivity;
  static late AppDatabase db;
  static late final SyncService sync;
  static late final AuthRepository auth;
  static late final DashboardRepository dashboard;
  static late final MemosRepository memos;
  static late final AccomplishmentsRepository accomplishments;
  static late final SchoolsRepository schools;
  static late final WhereaboutsRepository whereabouts;
  static late final IssuesRepository issues;
  static late final SectionUsersRepository sectionUsers;
  static late final ActivityDesignsRepository activityDesigns;
  static late final AdoptASchoolRepository adoptASchool;

  /// Whether the device is currently online.
  static bool get isOnline => connectivity.isOnline;

  /// Execute a write operation — sends directly if online, queues if offline.
  /// All repositories should use this for mutations.
  static Future<dynamic> writeOrQueue({
    required String entity,
    required String operation,
    required Map<String, dynamic> payload,
  }) async {
    return sync.writeOrQueue(entity: entity, operation: operation, payload: payload);
  }

  static Future<void> init() async {
    api = ApiClient();
    storage = SecureStorageService();
    cache = CacheService();
    connectivity = ConnectivityService();

    try {
      db = AppDatabase();
    } catch (e) {
      debugPrint('DI: database init failed (offline cache disabled): $e');
      try {
        db = AppDatabase.forTesting(await platform.openDb());
      } catch (e2) {
        debugPrint('DI: fallback database also failed: $e2');
        db = AppDatabase.forTesting(await platform.openDb());
      }
    }

    try {
      sync = SyncService(api: api, db: db, connectivity: connectivity);
    } catch (e) {
      debugPrint('DI: sync service init failed: $e');
    }

    auth = AuthRepository(api: api, storage: storage, db: db);
    dashboard = DashboardRepository(api, cache);
    memos = MemosRepository(api, cache);
    accomplishments = AccomplishmentsRepository(api, cache);
    schools = SchoolsRepository(api, cache);
    whereabouts = WhereaboutsRepository(api, cache);
    issues = IssuesRepository(api, cache);
    sectionUsers = SectionUsersRepository(api, cache);
    activityDesigns = ActivityDesignsRepository(api, cache);
    adoptASchool = AdoptASchoolRepository(api, cache);

    api.onUnauthorized = () {
      debugPrint('ApiClient: 401 received — session invalidated.');
    };

    try {
      final baseUrl = await storage.getBaseUrl();
      final token = await storage.getToken();
      api.configure(baseUrl: baseUrl ?? 'https://one.depedmis.com');
      if (token != null) api.configure(token: token);
    } catch (e) {
      debugPrint('DI: storage restore failed, using defaults: $e');
      api.configure(baseUrl: 'https://one.depedmis.com');
    }
  }

  static void dispose() {
    try {
      connectivity.dispose();
      sync.dispose();
      db.close();
    } catch (_) {}
  }
}
