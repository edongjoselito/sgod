import '../../core/network/api_client.dart';
import '../../core/services/cache_service.dart';
import '../models/memo_item.dart';

/// Fetches memos from the API.
class MemosRepository {
  MemosRepository(this._api, [this._cache]);

  final ApiClient _api;
  final CacheService? _cache;

  static const _key = 'memos';

  /// Fetches a page of memos. The API returns `{ ok, data: [...] }`; the
  /// [ApiClient] unwraps the envelope so we receive the `data` array.
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
      _cache?.set(_key, items);
      return items;
    } catch (e) {
      final cached = _cache?.get<List<MemoItem>>(_key);
      if (cached != null) return cached;
      rethrow;
    }
  }
}
