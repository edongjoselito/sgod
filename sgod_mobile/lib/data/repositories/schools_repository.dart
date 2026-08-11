import '../../core/network/api_client.dart';
import '../../core/services/app_database.dart';
import '../../core/services/cache_service.dart';
import '../../ui/core/di.dart';
import '../models/school_item.dart';

/// Fetches the list of schools from the API with persistent offline cache.
class SchoolsRepository {
  SchoolsRepository(this._api, [this._cache, this._db]);

  final ApiClient _api;
  final CacheService? _cache;
  final AppDatabase? _db;

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
      if (_db != null) {
        try {
          final cached = await _db.getCachedList(_key);
          if (cached != null && cached.isNotEmpty) {
            return cached.map((e) => SchoolItem.fromJson(e)).toList();
          }
        } catch (_) {}
      }
      final memCached = _cache?.get<List<SchoolItem>>(_key);
      if (memCached != null) return memCached;
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

  /// Delete a school by recID. Queues offline.
  Future<void> delete(String recID) async {
    await DI.write(
      endpoint: 'schools_delete',
      entity: 'schools',
      operation: 'delete',
      payload: {'recID': recID},
    );
    _cache?.remove(_key);
  }

  /// Save (update) a school record. [recID] is required.
  /// Returns the recID, or the passed-in recID if queued offline.
  Future<String> save(Map<String, dynamic> fields, {required String recID}) async {
    final body = <String, dynamic>{...fields, 'recID': recID};
    final data = await DI.write(
      endpoint: 'schools_save',
      entity: 'schools',
      operation: 'update',
      payload: body,
    );
    _cache?.remove(_key);
    return (data?['recID'] ?? recID).toString();
  }
}
