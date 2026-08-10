import '../../core/network/api_client.dart';
import '../../core/services/cache_service.dart';
import '../models/school_item.dart';

/// Fetches the list of schools from the API and supports client-side
/// filtering by name, district, division, course, or school type.
class SchoolsRepository {
  SchoolsRepository(this._api, [this._cache]);

  final ApiClient _api;
  final CacheService? _cache;

  static const _key = 'schools';

  /// Fetches a page of schools from `api/schools_index`.
  Future<List<SchoolItem>> fetch({int limit = 100, int offset = 0}) async {
    try {
      final data = await _api.get(
        'api/schools_index',
        query: {'limit': limit, 'offset': offset},
      );
      final items = data == null
          ? const <SchoolItem>[]
          : (data as List)
              .map((e) => SchoolItem.fromJson(e as Map<String, dynamic>))
              .toList();
      _cache?.set(_key, items);
      return items;
    } catch (e) {
      final cached = _cache?.get<List<SchoolItem>>(_key);
      if (cached != null) return cached;
      rethrow;
    }
  }

  /// Client-side search across the most useful text fields. Callers should
  /// pass the already-loaded [items] to avoid an extra network round trip.
  List<SchoolItem> search(List<SchoolItem> items, String query) {
    final q = query.trim().toLowerCase();
    if (q.isEmpty) return items;
    return items.where((s) {
      return s.schoolName.toLowerCase().contains(q) ||
          s.district.toLowerCase().contains(q) ||
          s.division.toLowerCase().contains(q) ||
          s.course.toLowerCase().contains(q) ||
          s.schoolType.toLowerCase().contains(q) ||
          s.schoolID.toLowerCase().contains(q);
    }).toList();
  }

  /// Delete a school by recID.
  Future<void> delete(String recID) async {
    await _api.post('api/schools_delete', body: {'recID': recID});
    _cache?.remove(_key);
  }

  /// Save (update) a school record. [recID] is required.
  Future<String> save(Map<String, dynamic> fields, {required String recID}) async {
    final body = <String, dynamic>{...fields, 'recID': recID};
    final data = await _api.post('api/schools_save', body: body);
    _cache?.remove(_key);
    return (data?['recID'] ?? recID).toString();
  }
}
