import '../../core/network/api_client.dart';
import '../../core/services/cache_service.dart';
import '../models/dashboard_data.dart';

/// Fetches dashboard data from the API.
class DashboardRepository {
  DashboardRepository(this._api, [this._cache]);

  final ApiClient _api;
  final CacheService? _cache;

  static const _key = 'dashboard';

  Future<DashboardData> fetch() async {
    try {
      final data = await _api.get('api/dashboard');
      final result = data == null
          ? const DashboardData()
          : DashboardData.fromJson(data as Map<String, dynamic>);
      _cache?.set(_key, result);
      return result;
    } catch (e) {
      final cached = _cache?.get<DashboardData>(_key);
      if (cached != null) return cached;
      rethrow;
    }
  }
}
