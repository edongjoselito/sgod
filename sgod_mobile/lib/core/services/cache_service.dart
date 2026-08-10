/// Simple in-memory cache with TTL. Stores API responses so the app
/// can show cached data when offline.
class CacheService {
  final _cache = <String, _CacheEntry>{};

  T? get<T>(String key) {
    final entry = _cache[key];
    if (entry == null) return null;
    if (DateTime.now().isAfter(entry.expiry)) {
      _cache.remove(key);
      return null;
    }
    return entry.value as T?;
  }

  void set<T>(String key, T value,
      {Duration ttl = const Duration(minutes: 5)}) {
    _cache[key] = _CacheEntry(value, DateTime.now().add(ttl));
  }

  void remove(String key) => _cache.remove(key);

  void clear() => _cache.clear();
}

class _CacheEntry {
  final dynamic value;
  final DateTime expiry;
  _CacheEntry(this.value, this.expiry);
}
