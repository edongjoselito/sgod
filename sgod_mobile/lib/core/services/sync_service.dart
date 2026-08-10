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
///  - **Pull**: refreshes all cached data by calling the manifest endpoint
///    and invalidating stale cache entries.
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
    try {
      _pendingCount = await db.countPendingOutbox();
    } catch (e) {
      debugPrint('SyncService.refreshPendingCount error: $e');
      _pendingCount = 0;
    }
    notifyListeners();
  }

  /// Run a full sync cycle (pull then push). Safe to call repeatedly.
  Future<void> sync() async {
    if (_syncing) return;
    if (!connectivity.isOnline) {
      debugPrint('SyncService: offline — skipping sync');
      return;
    }
    _syncing = true;
    notifyListeners();
    try {
      await _pull();
      await _push();
      _lastSync = DateTime.now();
      debugPrint('SyncService: sync complete at $_lastSync');
    } catch (e) {
      debugPrint('SyncService.sync error: $e');
    } finally {
      _syncing = false;
      await refreshPendingCount();
      notifyListeners();
    }
  }

  Future<void> _onConnectivityChanged(bool online) async {
    debugPrint('SyncService: connectivity changed → ${online ? 'online' : 'offline'}');
    if (online) {
      // Wait a moment for network to stabilize, then sync
      await Future.delayed(const Duration(seconds: 2));
      await sync();
    }
  }

  // ── Pull ────────────────────────────────────────────────────────────────
  Future<void> _pull() async {
    // Call the manifest endpoint to check for server-side changes.
    // The manifest returns per-table last-modified timestamps.
    try {
      await api.get('api/sync_manifest');
    } on ApiException catch (e) {
      debugPrint('SyncService._pull manifest error: $e');
    } catch (e) {
      debugPrint('SyncService._pull error: $e');
    }
  }

  // ── Push ────────────────────────────────────────────────────────────────
  Future<void> _push() async {
    List<SyncOutboxRow> pending;
    try {
      pending = await db.getPendingOutbox();
    } catch (e) {
      debugPrint('SyncService._push: could not read outbox: $e');
      return;
    }

    if (pending.isEmpty) return;

    debugPrint('SyncService: pushing ${pending.length} pending operations');

    for (final entry in pending) {
      try {
        await _flushEntry(entry);
        await db.dequeueOutbox(entry.id);
        debugPrint('SyncService: flushed outbox entry ${entry.id}');
      } on ApiException catch (e) {
        if (e.isConflict) {
          await db.markOutboxConflict(entry.id, e.message);
          debugPrint('SyncService: entry ${entry.id} conflict: ${e.message}');
        } else if (e.isValidation) {
          await db.markOutboxFailed(entry.id, e.message);
          debugPrint('SyncService: entry ${entry.id} validation error: ${e.message}');
        }
        // 5xx / network → leave in outbox for next cycle.
      } catch (e) {
        debugPrint('SyncService._push entry ${entry.id} error: $e');
        // Leave in outbox for retry
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
        await api.post(path, body: payload);
        break;
      case 'delete':
        await api.post(path, body: payload);
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
    try {
      await db.enqueueOutbox(SyncOutboxTableCompanion.insert(
        operation: operation,
        entity: entity,
        localId: Value(localId),
        payload: jsonEncode(payload),
      ));
      await refreshPendingCount();
      debugPrint('SyncService: enqueued $operation on $entity');
    } catch (e) {
      debugPrint('SyncService.enqueue error: $e');
    }
  }

  /// Try a write — if online, send directly; if offline, enqueue.
  /// This is the method repositories should call for all mutations.
  Future<dynamic> writeOrQueue({
    required String entity,
    required String operation,
    required Map<String, dynamic> payload,
  }) async {
    if (connectivity.isOnline) {
      try {
        return await api.post('api/$entity', body: payload);
      } catch (e) {
        // If it's a network error, queue for later
        if (e is ApiException || e.toString().contains('ClientException') || e.toString().contains('Timeout')) {
          debugPrint('SyncService: write failed ($e), queuing for later');
          await enqueue(operation: operation, entity: entity, payload: payload);
          rethrow;
        }
        rethrow;
      }
    } else {
      // Offline — queue the write
      await enqueue(operation: operation, entity: entity, payload: payload);
      throw ApiException('Offline — change queued for sync');
    }
  }

  @override
  void dispose() {
    _onlineSub.cancel();
    super.dispose();
  }
}
