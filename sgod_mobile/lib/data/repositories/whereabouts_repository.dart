import '../../core/network/api_client.dart';
import '../../core/services/cache_service.dart';
import '../models/whereabouts_item.dart';

/// Fetches the list of employee whereabouts from the API.
class WhereaboutsRepository {
  WhereaboutsRepository(this._api, [this._cache]);

  final ApiClient _api;
  final CacheService? _cache;

  static const _key = 'whereabouts';

  /// Fetches a page of whereabouts records from `api/whereabouts_index`.
  Future<List<WhereaboutsItem>> fetch({int limit = 50, int offset = 0}) async {
    try {
      final data = await _api.get(
        'api/whereabouts_index',
        query: {'limit': limit, 'offset': offset},
      );
      final items = data == null
          ? const <WhereaboutsItem>[]
          : (data as List)
              .map((e) => WhereaboutsItem.fromJson(e as Map<String, dynamic>))
              .toList();
      _cache?.set(_key, items);
      return items;
    } catch (e) {
      final cached = _cache?.get<List<WhereaboutsItem>>(_key);
      if (cached != null) return cached;
      rethrow;
    }
  }

  /// Delete a whereabouts record by id.
  Future<void> delete(String id) async {
    await _api.post('api/whereabouts_delete', body: {'id': id});
    _cache?.remove(_key);
  }

  /// Save (create or update) a whereabouts record.
  /// If [id] is non-empty, updates; otherwise creates.
  Future<String> save(Map<String, dynamic> fields, {String id = ''}) async {
    final body = <String, dynamic>{...fields};
    if (id.isNotEmpty) body['id'] = id;
    final data = await _api.post('api/whereabouts_save', body: body);
    _cache?.remove(_key);
    return (data?['id'] ?? 0).toString();
  }
}
