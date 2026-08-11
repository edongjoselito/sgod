import '../../core/network/api_client.dart';
import '../../core/services/app_database.dart';
import '../../core/services/cache_service.dart';
import '../../ui/core/di.dart';
import '../models/memo_item.dart';

/// Fetches memos from the API with persistent offline cache via Drift.
class MemosRepository {
  MemosRepository(this._api, [this._cache, this._db]);

  final ApiClient _api;
  final CacheService? _cache;
  final AppDatabase? _db;

  static const _key = 'memos';

  /// Fetches a page of memos. The API returns `{ ok, data: [...] }`; the
  /// [ApiClient] unwraps the envelope so we receive the `data` array.
  ///
  /// Offline strategy: try API → cache to Drift + memory → on failure,
  /// read from Drift → if empty, try memory cache → rethrow if all empty.
  Future<List<MemoItem>> fetch({int limit = 50, int offset = 0}) async {
    try {
      final data = await _api.get(
        'api/memos_index',
        query: {'limit': limit, 'offset': offset},
      );
      final items = data == null
          ? const <MemoItem>[]
          : (data as List<dynamic>)
              .map((e) => MemoItem.fromJson(e as Map<String, dynamic>))
              .toList();
      // Cache in both memory and Drift for persistent offline access
      _cache?.set(_key, items);
      if (_db != null && items.isNotEmpty) {
        try {
          await _db.cacheList(
            _key,
            items.map((e) => e.toJson()).toList(),
          );
        } catch (_) {}
      }
      return items;
    } catch (e) {
      // Try Drift persistent cache first
      if (_db != null) {
        try {
          final cached = await _db.getCachedList(_key);
          if (cached != null && cached.isNotEmpty) {
            return cached.map((e) => MemoItem.fromJson(e)).toList();
          }
        } catch (_) {}
      }
      // Fall back to in-memory cache
      final memCached = _cache?.get<List<MemoItem>>(_key);
      if (memCached != null) return memCached;
      rethrow;
    }
  }

  /// Delete a memo by id. Queues offline.
  Future<void> delete(String id) async {
    await DI.write(
      endpoint: 'memos_delete',
      entity: 'memos',
      operation: 'delete',
      payload: {'id': id},
    );
    _cache?.remove(_key);
  }

  /// Save (create or update) a memo.
  /// If [id] is non-empty, updates; otherwise creates.
  /// Returns the server-assigned id, or '0' if queued offline.
  Future<String> save(Map<String, dynamic> fields, {String id = ''}) async {
    final body = <String, dynamic>{...fields};
    if (id.isNotEmpty) body['id'] = id;
    final data = await DI.write(
      endpoint: 'memos_save',
      entity: 'memos',
      operation: id.isNotEmpty ? 'update' : 'create',
      payload: body,
    );
    _cache?.remove(_key);
    return (data?['id'] ?? 0).toString();
  }
}
