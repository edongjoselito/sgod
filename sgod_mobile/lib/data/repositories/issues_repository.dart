import '../../core/network/api_client.dart';
import '../../core/services/cache_service.dart';
import '../models/issue_item.dart';

/// Reads and mutates `section_issues_concerns` via the REST API.
class IssuesRepository {
  IssuesRepository(this._api, [this._cache]);

  final ApiClient _api;
  final CacheService? _cache;

  static const _key = 'issues';

  /// Fetches all issues for the given [year]. Pass an empty string to
  /// let the server pick the default (current) year.
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
      return items;
    } catch (e) {
      final cached = _cache?.get<List<IssueItem>>(_key);
      if (cached != null) return cached;
      rethrow;
    }
  }

  /// Creates a new issue. Returns the inserted row id (>0) on success.
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
    final data = await _api.post('api/issues_concerns_save', body: body);
    if (data is Map<String, dynamic>) {
      final id = data['id'];
      if (id is int) return id;
      return int.tryParse(id?.toString() ?? '') ?? 0;
    }
    if (data is int) return data;
    return int.tryParse(data?.toString() ?? '') ?? 0;
  }

  /// Deletes the issue with the given [id]. Returns true on success.
  Future<bool> delete(String id) async {
    await _api.post('api/issues_concerns_delete', body: {'id': id});
    return true;
  }
}
