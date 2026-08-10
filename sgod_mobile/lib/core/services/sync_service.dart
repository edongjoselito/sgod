import 'dart:async';
import 'dart:convert';

import 'package:drift/drift.dart';
import 'package:flutter/foundation.dart';

import '../network/api_client.dart';
import '../network/api_exception.dart';
import 'app_database.dart';
import 'connectivity_service.dart';

/// Orchestrates offline sync: pulls changed rows from the server and
/// flushes the local outbox when the device comes back online.
///
/// Strategy:
///  - **Pull**: calls `/api/sync/manifest?since=<watermark>` to learn which
///    tables changed, then fetches per-table deltas and upserts them.
///  - **Push**: drains [SyncOutboxTable] FIFO. On 200 → dequeue; on 409 →
///    flag conflict; on 4xx → mark failed; on 5xx/network → leave for retry.
class SyncService extends ChangeNotifier {
  SyncService({
    required this.api,
    required this.db,
    required this.connectivity,
  }) {
    _onlineSub = connectivity.stream.listen(_onConnectivityChanged);
  }

  final ApiClient api;
  final AppDatabase db;
  final ConnectivityService connectivity;

  bool _syncing = false;
  bool get syncing => _syncing;

  DateTime? _lastSync;
  DateTime? get lastSync => _lastSync;

  int _pendingCount = 0;
  int get pendingCount => _pendingCount;

  late final StreamSubscription<bool> _onlineSub;

  /// Refresh the pending-outbox count — call after login and after writes.
  Future<void> refreshPendingCount() async {
    _pendingCount = await db.countPendingOutbox();
    notifyListeners();
  }

  /// Run a full sync cycle (pull then push). Safe to call repeatedly.
  Future<void> sync() async {
    if (_syncing) return;
    if (!connectivity.isOnline) return;
    _syncing = true;
    notifyListeners();
    try {
      await _pull();
      await _push();
      _lastSync = DateTime.now();
    } catch (e) {
      debugPrint('SyncService.sync error: $e');
    } finally {
      _syncing = false;
      await refreshPendingCount();
      notifyListeners();
    }
  }

  Future<void> _onConnectivityChanged(bool online) async {
    if (online) await sync();
  }

  // ── Pull ────────────────────────────────────────────────────────────────
  Future<void> _pull() async {
    // Phase 1 stub: the manifest endpoint returns per-table last-modified
    // timestamps. Full per-table delta fetching is wired in Phase 3.
    try {
      await api.get('api/sync_manifest');
    } on ApiException catch (e) {
      debugPrint('SyncService._pull manifest error: $e');
    }
  }

  // ── Push ────────────────────────────────────────────────────────────────
  Future<void> _push() async {
    final pending = await db.getPendingOutbox();
    for (final entry in pending) {
      try {
        await _flushEntry(entry);
        await db.dequeueOutbox(entry.id);
      } on ApiException catch (e) {
        if (e.isConflict) {
          await db.markOutboxConflict(entry.id, e.message);
        } else if (e.isValidation) {
          await db.markOutboxFailed(entry.id, e.message);
        }
        // 5xx / network → leave in outbox for next cycle.
      } catch (e) {
        debugPrint('SyncService._push entry ${entry.id} error: $e');
      }
    }
  }

  Future<void> _flushEntry(SyncOutboxRow entry) async {
    final payload = jsonDecode(entry.payload) as Map<String, dynamic>;
    final path = 'api/${entry.entity}';
    switch (entry.operation) {
      case 'create':
        await api.post(path, body: payload);
        break;
      case 'update':
        await api.put('$path/${entry.localId}', body: payload);
        break;
      case 'delete':
        await api.delete('$path/${entry.localId}');
        break;
      default:
        throw ApiException('Unknown outbox operation: ${entry.operation}');
    }
  }

  /// Enqueue a write — used by repositories when offline.
  Future<void> enqueue({
    required String operation,
    required String entity,
    int? localId,
    required Map<String, dynamic> payload,
  }) async {
    await db.enqueueOutbox(SyncOutboxTableCompanion.insert(
      operation: operation,
      entity: entity,
      localId: Value(localId),
      payload: jsonEncode(payload),
    ));
    await refreshPendingCount();
  }

  @override
  void dispose() {
    _onlineSub.cancel();
    super.dispose();
  }
}
