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

  /// Delete an accomplishment by id.
  Future<void> delete(String id) async {
    await _api.post('api/accomplishments_delete', body: {'id': id});
    _cache?.remove(_key);
  }

  /// Copy (duplicate) an accomplishment, returns the new id.
  Future<String> copy(String id) async {
    final data = await _api.post('api/accomplishments_copy', body: {'id': id});
    _cache?.remove(_key);
    return (data?['id'] ?? 0).toString();
  }

  /// Save (create or update) an accomplishment.
  /// If [id] is non-empty, updates; otherwise creates.
  Future<String> save(Map<String, dynamic> fields, {String id = ''}) async {
    final body = <String, dynamic>{...fields};
    if (id.isNotEmpty) body['id'] = id;
    final data = await _api.post('api/accomplishments_save', body: body);
    _cache?.remove(_key);
    return (data?['id'] ?? 0).toString();
  }

  /// List attachment reports for an accomplishment.
  Future<List<Map<String, dynamic>>> listReports(String accId) async {
    final data = await _api.get('api/accomplishment_reports', query: {'acc_id': accId});
    if (data == null) return const [];
    return (data as List<dynamic>).cast<Map<String, dynamic>>();
  }

  /// Delete an attachment report.
  Future<void> deleteReport(String id) async {
    await _api.post('api/accomplishment_reports_delete', body: {'id': id});
  }
}
