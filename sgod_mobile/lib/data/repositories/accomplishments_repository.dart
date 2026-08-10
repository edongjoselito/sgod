import '../../core/network/api_client.dart';
import '../../core/services/cache_service.dart';
import '../models/accomplishment_item.dart';

/// Fetches accomplishments from the API.
class AccomplishmentsRepository {
  AccomplishmentsRepository(this._api, [this._cache]);

  final ApiClient _api;
  final CacheService? _cache;

  static const _key = 'accomplishments';

  /// Fetches a page of accomplishments, optionally filtered by section.
  /// The API returns `{ ok, data: [...] }`; the [ApiClient] unwraps the
  /// envelope so we receive the `data` array.
  Future<List<AccomplishmentItem>> fetch({
    String section = '',
    int limit = 50,
    int offset = 0,
  }) async {
    final query = <String, dynamic>{
      'limit': limit,
      'offset': offset,
    };
    if (section.isNotEmpty) query['section'] = section;
    try {
      final data = await _api.get('api/accomplishments_index', query: query);
      final items = data == null
          ? const <AccomplishmentItem>[]
          : (data as List<dynamic>)
              .map((e) =>
                  AccomplishmentItem.fromJson(e as Map<String, dynamic>))
              .toList();
      _cache?.set(_key, items);
      return items;
    } catch (e) {
      final cached = _cache?.get<List<AccomplishmentItem>>(_key);
      if (cached != null) return cached;
      rethrow;
    }
  }
}
