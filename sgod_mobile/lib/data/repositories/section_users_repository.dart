import '../../core/network/api_client.dart';
import '../../core/services/app_database.dart';
import '../../core/services/cache_service.dart';
import '../models/section_user_item.dart';

/// Fetches the list of users grouped by section.
class SectionUsersRepository {
  SectionUsersRepository(this._api, [this._cache, this._db]);

  final ApiClient _api;
  final CacheService? _cache;
  final AppDatabase? _db;

  static const _key = 'section_users';

  Future<List<SectionUserItem>> fetch() async {
    try {
      final data = await _api.get('api/section_users_index');
      final items = data == null
          ? const <SectionUserItem>[]
          : (data as List)
              .map((e) => SectionUserItem.fromJson(e as Map<String, dynamic>))
              .toList(growable: false);
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
            return cached.map((e) => SectionUserItem.fromJson(e)).toList();
          }
        } catch (_) {}
      }
      final memCached = _cache?.get<List<SectionUserItem>>(_key);
      if (memCached != null) return memCached;
      rethrow;
    }
  }
}
