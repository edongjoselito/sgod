import 'package:flutter/foundation.dart';

import '../ui/core/di.dart';
import 'data/brigada_models.dart';
import 'data/brigada_repository.dart';
import 'data/brigada_store.dart';
import 'data/brigada_sync.dart';

/// Composition root for the Brigada Eskwela module.
///
/// The module owns its own store, repository and sync service, and borrows
/// only the shared [DI.api] client and [DI.connectivity] service from the app.
/// It initialises lazily the first time a Brigada screen opens, so nothing has
/// to change in `main.dart` or the app-wide DI container.
class BrigadaModule {
  BrigadaModule._();

  static BrigadaStore store = BrigadaStore();

  static late BrigadaRepository repository;
  static late BrigadaSyncService sync;

  static bool _initialized = false;
  static Future<void>? _initializing;

  static bool get isInitialized => _initialized;

  /// Swap in test doubles so screens can be pumped without a live backend.
  @visibleForTesting
  static void installForTests({
    required BrigadaStore testStore,
    required BrigadaRepository testRepository,
    required BrigadaSyncService testSync,
  }) {
    store = testStore;
    repository = testRepository;
    sync = testSync;
    _initialized = true;
    _initializing = null;
  }

  /// Prepare the module. Safe to call from every screen's initState —
  /// concurrent callers await the same initialisation.
  static Future<void> ensureInitialized({String? username}) {
    if (_initialized) {
      return username == null
          ? Future<void>.value()
          : store.bindUser(username);
    }
    return _initializing ??= _init(username);
  }

  static Future<void> _init(String? username) async {
    try {
      await store.init();
      if (username != null && username.isNotEmpty) {
        await store.bindUser(username);
      }
      repository = BrigadaRepository(
        api: DI.api,
        store: store,
        connectivity: DI.connectivity,
      );
      sync = BrigadaSyncService(
        repository: repository,
        store: store,
        connectivity: DI.connectivity,
      );
      _initialized = true;
    } catch (e) {
      debugPrint('BrigadaModule.init failed: $e');
      rethrow;
    } finally {
      _initializing = null;
    }
  }

  /// Connectivity that is safe to read before initialisation completes.
  static bool get isOnline =>
      _initialized ? sync.isOnline : DI.connectivity.isOnline;

  /// The school year the reports should default to.
  ///
  /// Prefers what the server reports (cache-first, so it works offline) and
  /// falls back to the same `currentYear-nextYear` rule the web uses.
  static Future<String> resolveSchoolYear() async {
    final fallback = BrigadaMeta.fallback().currentSy;
    try {
      await ensureInitialized();
      final cached = await repository.cached(
        BrigadaRepository.metaRequest(),
        BrigadaMeta.fromJson,
      );
      if (cached != null && cached.data.currentSy.isNotEmpty) {
        return cached.data.currentSy;
      }
      final fresh = await repository.fetch(
        BrigadaRepository.metaRequest(),
        BrigadaMeta.fromJson,
      );
      return fresh.data.currentSy.isEmpty ? fallback : fresh.data.currentSy;
    } catch (_) {
      return fallback;
    }
  }

  /// Discard cached division data. Call on logout if the module is wired into
  /// the app's sign-out path; harmless if it never runs, because
  /// [BrigadaStore.bindUser] also clears the cache when the account changes.
  static Future<void> clearForLogout() async {
    if (!_initialized) return;
    await sync.clearCache();
  }
}
