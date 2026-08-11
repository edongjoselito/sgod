import '../../core/network/api_client.dart';
import '../../core/services/app_database.dart';
import '../../core/services/cache_service.dart';
import '../models/dashboard_data.dart';

/// Fetches dashboard data from the API with persistent offline cache.
class DashboardRepository {
  DashboardRepository(this._api, [this._cache, this._db]);

  final ApiClient _api;
  final CacheService? _cache;
  final AppDatabase? _db;

  static const _key = 'dashboard';

  Future<DashboardData> fetch() async {
    try {
      final data = await _api.get('api/dashboard');
      final result = data == null
          ? const DashboardData()
          : DashboardData.fromJson(data as Map<String, dynamic>);
      _cache?.set(_key, result);
      if (_db != null) {
        try {
          await _db.cacheObject(_key, result.toJson());
        } catch (_) {}
      }
      return result;
    } catch (e) {
      if (_db != null) {
        try {
          final cached = await _db.getCachedObject(_key);
          if (cached != null) {
            return DashboardData.fromJson(cached);
          }
        } catch (_) {}
      }
      final memCached = _cache?.get<DashboardData>(_key);
      if (memCached != null) return memCached;
      rethrow;
    }
  }
}
