import 'dart:async';
import 'dart:convert';
import 'dart:io';

import 'package:flutter/foundation.dart';
import 'package:path_provider/path_provider.dart';

/// One cached API response plus the metadata the UI needs to describe it.
class BrigadaCacheEntry {
  const BrigadaCacheEntry({
    required this.key,
    required this.fetchedAt,
    required this.pinned,
    required this.bytes,
  });

  final String key;
  final DateTime fetchedAt;

  /// Pinned entries are always refreshed by a sync, even if the user has not
  /// opened that screen recently.
  final bool pinned;
  final int bytes;

  Duration get age => DateTime.now().difference(fetchedAt);
}

/// File-backed offline store for the Brigada Eskwela module.
///
/// Every Brigada screen is a read-only report, so the offline model is a
/// document cache rather than a relational mirror: each API response is
/// written to its own JSON file keyed by endpoint + query string, with a
/// small index holding freshness metadata.
///
/// This is deliberately independent of the app-wide Drift database — the
/// Brigada module owns its own storage so it can be synced, inspected and
/// cleared on its own, and so it never contends with the core schema.
///
/// On web (no filesystem) it degrades to an in-memory cache for the session.
class BrigadaStore {
  static const _dirName = 'brigada_cache';
  static const _indexFile = 'index.json';

  Directory? _dir;
  bool _ready = false;

  /// key → metadata. Mirrors `index.json`.
  final Map<String, BrigadaCacheEntry> _index = {};

  /// Used on web, and as a read-through buffer elsewhere.
  final Map<String, Map<String, dynamic>> _memory = {};

  /// Username the cached data belongs to; a different user wipes the cache.
  String _owner = '';

  Future<void>? _pendingIndexWrite;
  bool _indexDirty = false;

  bool get isReady => _ready;
  int get entryCount => _index.length;

  /// Newest fetch timestamp across all entries — the module's "last synced".
  DateTime? get newestFetch {
    DateTime? newest;
    for (final entry in _index.values) {
      if (newest == null || entry.fetchedAt.isAfter(newest)) {
        newest = entry.fetchedAt;
      }
    }
    return newest;
  }

  /// Oldest fetch timestamp — how stale the least-fresh screen is.
  DateTime? get oldestFetch {
    DateTime? oldest;
    for (final entry in _index.values) {
      if (oldest == null || entry.fetchedAt.isBefore(oldest)) {
        oldest = entry.fetchedAt;
      }
    }
    return oldest;
  }

  int get totalBytes =>
      _index.values.fold<int>(0, (sum, entry) => sum + entry.bytes);

  List<BrigadaCacheEntry> get entries => _index.values.toList(growable: false);

  /// Prepare the cache directory and read the index. Safe to call repeatedly.
  Future<void> init() async {
    if (_ready) return;
    if (kIsWeb) {
      _ready = true;
      return;
    }
    try {
      final docs = await getApplicationDocumentsDirectory();
      final dir = Directory('${docs.path}/$_dirName');
      if (!await dir.exists()) {
        await dir.create(recursive: true);
      }
      _dir = dir;
      await _loadIndex();
    } catch (e) {
      debugPrint('BrigadaStore.init failed, falling back to memory: $e');
      _dir = null;
    }
    _ready = true;
  }

  /// Scope the cache to a user. Switching accounts discards everything so one
  /// user never sees another's cached division data.
  Future<void> bindUser(String username) async {
    await init();
    final next = username.trim();
    if (_owner == next) return;
    if (_owner.isNotEmpty) {
      await clear();
    }
    _owner = next;
    _indexDirty = true;
    await _flushIndex();
  }

  /// Cached payload for [key], or null when nothing is stored.
  Future<Map<String, dynamic>?> read(String key) async {
    await init();
    final memo = _memory[key];
    if (memo != null) return memo;

    final entry = _index[key];
    if (entry == null) return null;
    final dir = _dir;
    if (dir == null) return null;

    try {
      final file = File('${dir.path}/${_fileNameFor(key)}');
      if (!await file.exists()) {
        _index.remove(key);
        _indexDirty = true;
        return null;
      }
      final decoded = jsonDecode(await file.readAsString());
      if (decoded is! Map<String, dynamic>) return null;
      _memory[key] = decoded;
      return decoded;
    } catch (e) {
      debugPrint('BrigadaStore.read($key) failed: $e');
      return null;
    }
  }

  /// Freshness metadata for [key] without touching the payload.
  BrigadaCacheEntry? peek(String key) => _index[key];

  bool has(String key) => _index.containsKey(key);

  /// Persist [payload] under [key].
  Future<void> write(
    String key,
    Map<String, dynamic> payload, {
    bool pinned = false,
  }) async {
    await init();
    _memory[key] = payload;

    final encoded = jsonEncode(payload);
    final now = DateTime.now();
    _index[key] = BrigadaCacheEntry(
      key: key,
      fetchedAt: now,
      pinned: pinned || (_index[key]?.pinned ?? false),
      bytes: encoded.length,
    );
    _indexDirty = true;

    final dir = _dir;
    if (dir != null) {
      try {
        await File('${dir.path}/${_fileNameFor(key)}').writeAsString(encoded);
      } catch (e) {
        debugPrint('BrigadaStore.write($key) failed: $e');
      }
    }
    _scheduleIndexWrite();
  }

  /// Every cached key, newest first — the refresh set for a sync.
  List<String> keys() {
    final all = _index.values.toList()
      ..sort((a, b) => b.fetchedAt.compareTo(a.fetchedAt));
    return all.map((e) => e.key).toList();
  }

  /// Drop everything. Used on logout, account switch, and "Clear offline data".
  Future<void> clear() async {
    await init();
    _index.clear();
    _memory.clear();
    _indexDirty = true;
    final dir = _dir;
    if (dir != null) {
      try {
        if (await dir.exists()) {
          await dir.delete(recursive: true);
        }
        await dir.create(recursive: true);
      } catch (e) {
        debugPrint('BrigadaStore.clear failed: $e');
      }
    }
    await _flushIndex();
  }

  // ── Index persistence ──────────────────────────────────────────────────

  String _fileNameFor(String key) =>
      '${base64Url.encode(utf8.encode(key))}.json';

  Future<void> _loadIndex() async {
    final dir = _dir;
    if (dir == null) return;
    final file = File('${dir.path}/$_indexFile');
    if (!await file.exists()) return;
    try {
      final decoded = jsonDecode(await file.readAsString());
      if (decoded is! Map<String, dynamic>) return;
      _owner = decoded['owner'] as String? ?? '';
      final items = decoded['entries'];
      if (items is! Map<String, dynamic>) return;
      items.forEach((key, value) {
        if (value is! Map) return;
        final fetchedAt = DateTime.tryParse(value['t'] as String? ?? '');
        if (fetchedAt == null) return;
        _index[key] = BrigadaCacheEntry(
          key: key,
          fetchedAt: fetchedAt,
          pinned: value['p'] == true,
          bytes: (value['b'] as num?)?.toInt() ?? 0,
        );
      });
    } catch (e) {
      debugPrint('BrigadaStore._loadIndex failed: $e');
    }
  }

  /// Coalesce index writes so a 40-request prefetch does not rewrite the
  /// index 40 times.
  void _scheduleIndexWrite() {
    if (_pendingIndexWrite != null) return;
    _pendingIndexWrite = Future<void>.delayed(
      const Duration(milliseconds: 400),
      () async {
        _pendingIndexWrite = null;
        await _flushIndex();
      },
    );
  }

  Future<void> _flushIndex() async {
    if (!_indexDirty) return;
    final dir = _dir;
    if (dir == null) {
      _indexDirty = false;
      return;
    }
    _indexDirty = false;
    try {
      final payload = <String, dynamic>{
        'owner': _owner,
        'entries': _index.map(
          (key, entry) => MapEntry(key, {
            't': entry.fetchedAt.toIso8601String(),
            'p': entry.pinned,
            'b': entry.bytes,
          }),
        ),
      };
      await File('${dir.path}/$_indexFile').writeAsString(jsonEncode(payload));
    } catch (e) {
      debugPrint('BrigadaStore._flushIndex failed: $e');
    }
  }
}
