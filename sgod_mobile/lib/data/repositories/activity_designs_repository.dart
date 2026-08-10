import '../../core/network/api_client.dart';
import '../../core/services/cache_service.dart';
import '../models/activity_design_item.dart';

class ActivityDesignsRepository {
  ActivityDesignsRepository(this._api, [this._cache]);

  final ApiClient _api;
  final CacheService? _cache;

  static const _key = 'activity_designs';

  Future<List<ActivityDesignItem>> fetch({
    int limit = 50,
    int offset = 0,
  }) async {
    try {
      final data = await _api.get(
        'api/activity_designs_index',
        query: {'limit': limit, 'offset': offset},
      );
      final items = data == null
          ? const <ActivityDesignItem>[]
          : (data as List<dynamic>)
              .map((e) =>
                  ActivityDesignItem.fromJson(e as Map<String, dynamic>))
              .toList();
      _cache?.set(_key, items);
      return items;
    } catch (e) {
      final cached = _cache?.get<List<ActivityDesignItem>>(_key);
      if (cached != null) return cached;
      rethrow;
    }
  }

  /// Delete an activity design by id.
  Future<void> delete(String id) async {
    await _api.post('api/activity_designs_delete', body: {'id': id});
    _cache?.remove(_key);
  }

  /// Save (create or update) an activity design.
  /// If [id] is non-empty, updates; otherwise creates.
  Future<String> save(Map<String, dynamic> fields, {String id = ''}) async {
    final body = <String, dynamic>{...fields};
    if (id.isNotEmpty) body['id'] = id;
    final data = await _api.post('api/activity_designs_save', body: body);
    _cache?.remove(_key);
    return (data?['id'] ?? 0).toString();
  }
}
