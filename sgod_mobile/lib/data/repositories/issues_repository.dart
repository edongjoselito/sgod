import '../../core/network/api_client.dart';
import '../../core/services/app_database.dart';
import '../../core/services/cache_service.dart';
import '../../ui/core/di.dart';
import '../models/issue_item.dart';

/// Reads and mutates `section_issues_concerns` via the REST API.
class IssuesRepository {
  IssuesRepository(this._api, [this._cache, this._db]);

  final ApiClient _api;
  final CacheService? _cache;
  final AppDatabase? _db;

  static const _key = 'issues';

  Future<List<IssueItem>> fetch({String year = ''}) async {
    final query = <String, dynamic>{};
    if (year.isNotEmpty) query['year'] = year;
    try {
      final data = await _api.get('api/issues_concerns_index', query: query);
      final items = data == null
          ? const <IssueItem>[]
          : (data as List)
              .map((e) => IssueItem.fromJson(e as Map<String, dynamic>))
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
            return cached.map((e) => IssueItem.fromJson(e)).toList();
          }
        } catch (_) {}
      }
      final memCached = _cache?.get<List<IssueItem>>(_key);
      if (memCached != null) return memCached;
      rethrow;
    }
  }

  /// Creates a new issue. Returns the inserted row id (>0) on success,
  /// or 0 if queued offline.
  Future<int> create({
    required String title,
    required String description,
    String priority = 'Normal',
    String year = '',
  }) async {
    final body = <String, dynamic>{
      'title': title,
      'description': description,
      'priority': priority,
      if (year.isNotEmpty) 'year': year,
    };
    final data = await DI.write(
      endpoint: 'issues_concerns_save',
      entity: 'issues',
      operation: 'create',
      payload: body,
    );
    return _extractId(data);
  }

  /// Updates an existing issue identified by [id] with the given [fields].
  /// The `issues_concerns_save` endpoint handles both create (id == 0) and
  /// update (id > 0), so we simply include the id in the request body.
  /// Returns the id, or 0 if queued offline.
  Future<int> update(String id, Map<String, dynamic> fields) async {
    final body = <String, dynamic>{...fields, 'id': id};
    final data = await DI.write(
      endpoint: 'issues_concerns_save',
      entity: 'issues',
      operation: 'update',
      payload: body,
    );
    _cache?.remove(_key);
    return _extractId(data);
  }

  /// Deletes the issue with the given [id]. Queues offline.
  Future<bool> delete(String id) async {
    await DI.write(
      endpoint: 'issues_concerns_delete',
      entity: 'issues',
      operation: 'delete',
      payload: {'id': id},
    );
    _cache?.remove(_key);
    return true;
  }

  int _extractId(Map<String, dynamic>? data) {
    if (data == null) return 0;
    if (data is Map<String, dynamic>) {
      final id = data['id'];
      if (id is int) return id;
      return int.tryParse(id?.toString() ?? '') ?? 0;
    }
    return 0;
  }
}
