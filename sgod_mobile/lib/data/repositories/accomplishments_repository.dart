import 'dart:typed_data';

import 'package:http/http.dart' as http;

import '../../core/network/api_client.dart';
import '../../core/services/app_database.dart';
import '../../core/services/cache_service.dart';
import '../../ui/core/di.dart';
import '../models/accomplishment_item.dart';

/// Fetches accomplishments from the API with persistent offline cache.
class AccomplishmentsRepository {
  AccomplishmentsRepository(this._api, [this._cache, this._db]);

  final ApiClient _api;
  final CacheService? _cache;
  final AppDatabase? _db;

  static const _key = 'accomplishments';

  /// Fetches a page of accomplishments, optionally filtered by section.
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
            return cached.map((e) => AccomplishmentItem.fromJson(e)).toList();
          }
        } catch (_) {}
      }
      final memCached = _cache?.get<List<AccomplishmentItem>>(_key);
      if (memCached != null) return memCached;
      rethrow;
    }
  }

  /// Delete an accomplishment by id. Queues offline.
  Future<void> delete(String id) async {
    await DI.write(
      endpoint: 'accomplishments_delete',
      entity: 'accomplishments',
      operation: 'delete',
      payload: {'id': id},
    );
    _cache?.remove(_key);
  }

  /// Copy (duplicate) an accomplishment, returns the new id.
  Future<String> copy(String id) async {
    final data = await DI.write(
      endpoint: 'accomplishments_copy',
      entity: 'accomplishments',
      operation: 'create',
      payload: {'id': id},
    );
    _cache?.remove(_key);
    return (data?['id'] ?? 0).toString();
  }

  /// Save (create or update) an accomplishment.
  /// If [id] is non-empty, updates; otherwise creates.
  /// Returns the server-assigned id, or '0' if queued offline.
  Future<String> save(Map<String, dynamic> fields, {String id = ''}) async {
    final body = <String, dynamic>{...fields};
    if (id.isNotEmpty) body['id'] = id;
    final data = await DI.write(
      endpoint: 'accomplishments_save',
      entity: 'accomplishments',
      operation: id.isNotEmpty ? 'update' : 'create',
      payload: body,
    );
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

  /// Upload an attachment report (multipart POST).
  ///
  /// [accId] is the accomplishment ID, [documentName] is the display label,
  /// and [fileBytes]/[fileName] are the selected file's contents and name.
  Future<void> uploadReport({
    required String accId,
    required String documentName,
    required Uint8List fileBytes,
    required String fileName,
  }) async {
    final file = http.MultipartFile.fromBytes(
      'attachment_file',
      fileBytes,
      filename: fileName,
    );
    await _api.upload(
      'api/accomplishment_reports_upload',
      fields: {
        'acc_id': accId,
        'document_name': documentName,
      },
      fileField: 'attachment_file',
      file: file,
    );
  }
}
