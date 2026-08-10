import '../../core/network/api_client.dart';
import '../../core/services/cache_service.dart';
import '../models/section_user_item.dart';

/// Fetches the list of users grouped by section.
class SectionUsersRepository {
  SectionUsersRepository(this._api, [this._cache]);

  final ApiClient _api;
  final CacheService? _cache;

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
      return items;
    } catch (e) {
      final cached = _cache?.get<List<SectionUserItem>>(_key);
      if (cached != null) return cached;
      rethrow;
    }
  }
}
