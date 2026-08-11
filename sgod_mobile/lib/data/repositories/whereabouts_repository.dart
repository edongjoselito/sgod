import '../../core/network/api_client.dart';
import '../../core/services/app_database.dart';
import '../../core/services/cache_service.dart';
import '../../ui/core/di.dart';
import '../models/whereabouts_item.dart';

/// Fetches whereabouts from the API with persistent offline cache.
class WhereaboutsRepository {
  WhereaboutsRepository(this._api, [this._cache, this._db]);

  final ApiClient _api;
  final CacheService? _cache;
  final AppDatabase? _db;

  static const _key = 'whereabouts';

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
      if (_db != null && items.isNotEmpty) {
        try {
          await _db.cacheList(_key, items.map((e) => e.toJson()).toList());
        } catch (_) {}
      }
      return items;
    } catch (e) {
      if (_db != null) {
        try {
          final cached = await _db.getCachedList(_key);
          if (cached != null && cached.isNotEmpty) {
            return cached.map((e) => WhereaboutsItem.fromJson(e)).toList();
          }
        } catch (_) {}
      }
      final memCached = _cache?.get<List<WhereaboutsItem>>(_key);
      if (memCached != null) return memCached;
      rethrow;
    }
  }

  /// Delete a whereabouts record by id. Queues offline.
  Future<void> delete(String id) async {
    await DI.write(
      endpoint: 'whereabouts_delete',
      entity: 'whereabouts',
      operation: 'delete',
      payload: {'id': id},
    );
    _cache?.remove(_key);
  }

  /// Save (create or update) a whereabouts record.
  /// If [id] is non-empty, updates; otherwise creates.
  /// Returns the server-assigned id, or '0' if queued offline.
  Future<String> save(Map<String, dynamic> fields, {String id = ''}) async {
    final body = <String, dynamic>{...fields};
    if (id.isNotEmpty) body['id'] = id;
    final data = await DI.write(
      endpoint: 'whereabouts_save',
      entity: 'whereabouts',
      operation: id.isNotEmpty ? 'update' : 'create',
      payload: body,
    );
    _cache?.remove(_key);
    return (data?['id'] ?? 0).toString();
  }
}
