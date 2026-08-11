import 'dart:convert';

import 'package:drift/drift.dart';

import 'db_native.dart' if (dart.library.html) 'db_web.dart' as platform;

part 'app_database.g.dart';

/// On-device SQLite database for offline read cache + write outbox.
///
/// Each cached table mirrors a server table scoped to the logged-in user.
/// The [SyncOutbox] table queues writes created while offline; the
/// [SyncMetadata] table tracks the per-table sync watermark.
@DataClassName('UserProfileRow')
class UserProfileTable extends Table {
  IntColumn get id => integer().withDefault(const Constant(0))();
  TextColumn get username => text()();
  TextColumn get position => text().withDefault(const Constant(''))();
  TextColumn get fname => text().withDefault(const Constant(''))();
  TextColumn get lname => text().withDefault(const Constant(''))();
  TextColumn get email => text().nullable()();
  TextColumn get avatar => text().nullable()();
  TextColumn get section => text().withDefault(const Constant(''))();
  TextColumn get secGroup => text().withDefault(const Constant(''))();
  TextColumn get loginSource => text().withDefault(const Constant(''))();
  DateTimeColumn get updatedAt => dateTime().withDefault(currentDateAndTime)();

  @override
  Set<Column> get primaryKey => {id};
}

@DataClassName('MemoRow')
class MemoTable extends Table {
  IntColumn get id => integer().autoIncrement()();
  IntColumn get serverId => integer().nullable()();
  TextColumn get title => text()();
  TextColumn get body => text().withDefault(const Constant(''))();
  TextColumn get attachment => text().nullable()();
  DateTimeColumn get createdAt => dateTime().nullable()();
  DateTimeColumn get updatedAt => dateTime().withDefault(currentDateAndTime)();
  BoolColumn get isDirty => boolean().withDefault(const Constant(false))();
  BoolColumn get isDeleted => boolean().withDefault(const Constant(false))();

  @override
  Set<Column> get primaryKey => {id};
}

@DataClassName('AccomplishmentRow')
class AccomplishmentTable extends Table {
  IntColumn get id => integer().autoIncrement()();
  IntColumn get serverId => integer().nullable()();
  TextColumn get title => text()();
  TextColumn get section => text().withDefault(const Constant(''))();
  TextColumn get status => text().withDefault(const Constant('draft'))();
  TextColumn get details => text().withDefault(const Constant(''))();
  DateTimeColumn get date => dateTime().nullable()();
  DateTimeColumn get updatedAt => dateTime().withDefault(currentDateAndTime)();
  BoolColumn get isDirty => boolean().withDefault(const Constant(false))();
  BoolColumn get isDeleted => boolean().withDefault(const Constant(false))();
}

@DataClassName('SchoolRow')
class SchoolTable extends Table {
  IntColumn get id => integer().autoIncrement()();
  TextColumn get schoolId => text()();
  TextColumn get schoolName => text()();
  TextColumn get district => text().withDefault(const Constant(''))();
  TextColumn get division => text().withDefault(const Constant(''))();
  TextColumn get schoolEmail => text().nullable()();
  TextColumn get schoolType => text().withDefault(const Constant(''))();
  DateTimeColumn get updatedAt => dateTime().withDefault(currentDateAndTime)();
}

@DataClassName('SchoolPersonnelRow')
class SchoolPersonnelTable extends Table {
  IntColumn get id => integer().autoIncrement()();
  IntColumn get serverId => integer().nullable()();
  TextColumn get schoolId => text()();
  TextColumn get fullName => text()();
  TextColumn get positionTitle => text().nullable()();
  TextColumn get personnelType => text().withDefault(const Constant('Teaching'))();
  TextColumn get email => text().nullable()();
  TextColumn get mobileNo => text().nullable()();
  DateTimeColumn get updatedAt => dateTime().withDefault(currentDateAndTime)();
  BoolColumn get isDirty => boolean().withDefault(const Constant(false))();
  BoolColumn get isDeleted => boolean().withDefault(const Constant(false))();
}

/// Pending writes created while offline. Drained FIFO by [SyncService].
@DataClassName('SyncOutboxRow')
class SyncOutboxTable extends Table {
  IntColumn get id => integer().autoIncrement()();
  TextColumn get operation => text()(); // 'create' | 'update' | 'delete'
  TextColumn get entity => text()(); // 'memo' | 'accomplishment' | ...
  IntColumn get localId => integer().nullable()();
  TextColumn get payload => text()(); // JSON-encoded body
  DateTimeColumn get createdAt => dateTime().withDefault(currentDateAndTime)();
  IntColumn get attempts => integer().withDefault(const Constant(0))();
  BoolColumn get failed => boolean().withDefault(const Constant(false))();
  BoolColumn get conflict => boolean().withDefault(const Constant(false))();
  TextColumn get errorMessage => text().nullable()();
}

/// Per-table sync watermark — the last `updated_at` the server sent us.
@DataClassName('SyncMetadataRow')
class SyncMetadataTable extends Table {
  TextColumn get tableKey => text()();
  DateTimeColumn get lastSyncedAt => dateTime().nullable()();
  TextColumn get etag => text().nullable()();

  @override
  Set<Column> get primaryKey => {tableKey};
}

/// Generic JSON document cache for API list responses. Each row stores
/// a full JSON-encoded list response keyed by the cache key (e.g.
/// `memos`, `accomplishments`, `schools`). This provides persistent
/// offline reads across app restarts without needing per-entity tables.
@DataClassName('ApiCacheRow')
class ApiCacheTable extends Table {
  TextColumn get cacheKey => text()();
  TextColumn get payload => text()(); // JSON-encoded list
  DateTimeColumn get cachedAt => dateTime().withDefault(currentDateAndTime)();

  @override
  Set<Column> get primaryKey => {cacheKey};
}

@DriftDatabase(tables: [
  UserProfileTable,
  MemoTable,
  AccomplishmentTable,
  SchoolTable,
  SchoolPersonnelTable,
  SyncOutboxTable,
  SyncMetadataTable,
  ApiCacheTable,
])
class AppDatabase extends _$AppDatabase {
  AppDatabase() : super(_open());

  AppDatabase.forTesting(super.e);

  @override
  int get schemaVersion => 2;

  @override
  MigrationStrategy get migration => MigrationStrategy(
        onCreate: (m) => m.createAll(),
        onUpgrade: (m, from, to) async {
          if (from < 2) {
            await m.createTable(apiCacheTable);
          }
        },
      );

  // ── UserProfile ────────────────────────────────────────────────────────
  Future<void> upsertProfile(UserProfileTableCompanion entry) =>
      into(userProfileTable).insertOnConflictUpdate(entry);

  Future<UserProfileRow?> getProfile() =>
      (select(userProfileTable)..limit(1)).getSingleOrNull();

  Future<void> clearProfile() => delete(userProfileTable).go();

  // ── Memos ──────────────────────────────────────────────────────────────
  Future<List<MemoRow>> getMemos() =>
      (select(memoTable)..where((t) => t.isDeleted.equals(false))..orderBy([(t) => OrderingTerm.desc(t.createdAt)]))
          .get();

  Future<int> addLocalMemo(MemoTableCompanion entry) =>
      into(memoTable).insert(entry);

  Future<void> upsertMemo(MemoTableCompanion entry) =>
      into(memoTable).insertOnConflictUpdate(entry);

  // ── Accomplishments ────────────────────────────────────────────────────
  Future<List<AccomplishmentRow>> getAccomplishments() =>
      (select(accomplishmentTable)..where((t) => t.isDeleted.equals(false))..orderBy([(t) => OrderingTerm.desc(t.updatedAt)]))
          .get();

  Future<int> addLocalAccomplishment(AccomplishmentTableCompanion entry) =>
      into(accomplishmentTable).insert(entry);

  Future<void> upsertAccomplishment(AccomplishmentTableCompanion entry) =>
      into(accomplishmentTable).insertOnConflictUpdate(entry);

  // ── Schools ────────────────────────────────────────────────────────────
  Future<List<SchoolRow>> getSchools() =>
      (select(schoolTable)..orderBy([(t) => OrderingTerm.asc(t.schoolName)]))
          .get();

  Future<void> upsertSchool(SchoolTableCompanion entry) =>
      into(schoolTable).insertOnConflictUpdate(entry);

  // ── School Personnel ───────────────────────────────────────────────────
  Future<List<SchoolPersonnelRow>> getPersonnelForSchool(String schoolId) =>
      (select(schoolPersonnelTable)
            ..where((t) => t.schoolId.equals(schoolId) & t.isDeleted.equals(false))
            ..orderBy([(t) => OrderingTerm.asc(t.fullName)]))
          .get();

  Future<int> addLocalPersonnel(SchoolPersonnelTableCompanion entry) =>
      into(schoolPersonnelTable).insert(entry);

  Future<void> upsertPersonnel(SchoolPersonnelTableCompanion entry) =>
      into(schoolPersonnelTable).insertOnConflictUpdate(entry);

  // ── Sync outbox ────────────────────────────────────────────────────────
  Future<List<SyncOutboxRow>> getPendingOutbox() =>
      (select(syncOutboxTable)
            ..where((t) => t.failed.equals(false) & t.conflict.equals(false))
            ..orderBy([(t) => OrderingTerm.asc(t.createdAt)]))
          .get();

  Future<int> countPendingOutbox() async {
    final count = syncOutboxTable.id.count();
    final query = selectOnly(syncOutboxTable)
      ..addColumns([count])
      ..where(syncOutboxTable.failed.equals(false) &
          syncOutboxTable.conflict.equals(false));
    final row = await query.getSingle();
    return row.read(count) ?? 0;
  }

  Future<int> enqueueOutbox(SyncOutboxTableCompanion entry) =>
      into(syncOutboxTable).insert(entry);

  Future<void> dequeueOutbox(int id) =>
      (delete(syncOutboxTable)..where((t) => t.id.equals(id))).go();

  Future<void> markOutboxFailed(int id, String error) =>
      (update(syncOutboxTable)..where((t) => t.id.equals(id))).write(
        SyncOutboxTableCompanion(
          failed: const Value(true),
          errorMessage: Value(error),
        ),
      );

  Future<void> markOutboxConflict(int id, String error) =>
      (update(syncOutboxTable)..where((t) => t.id.equals(id))).write(
        SyncOutboxTableCompanion(
          conflict: const Value(true),
          errorMessage: Value(error),
        ),
      );

  // ── Sync metadata ──────────────────────────────────────────────────────
  Future<SyncMetadataRow?> getMetadata(String table) =>
      (select(syncMetadataTable)..where((t) => t.tableKey.equals(table)))
          .getSingleOrNull();

  Future<void> upsertMetadata(SyncMetadataTableCompanion entry) =>
      into(syncMetadataTable).insertOnConflictUpdate(entry);

  // ── API cache (generic JSON document cache) ────────────────────────────

  /// Reads a cached JSON payload by key. Returns `null` if not cached.
  Future<ApiCacheRow?> getCached(String key) =>
      (select(apiCacheTable)..where((t) => t.cacheKey.equals(key)))
          .getSingleOrNull();

  /// Stores or replaces a cached JSON payload.
  Future<void> upsertCache(String key, String payload) =>
      into(apiCacheTable).insertOnConflictUpdate(
        ApiCacheTableCompanion.insert(
          cacheKey: key,
          payload: payload,
        ),
      );

  /// Removes a cached entry by key.
  Future<void> removeCache(String key) =>
      (delete(apiCacheTable)..where((t) => t.cacheKey.equals(key))).go();

  /// Reads a cached JSON list as `List<Map<String, dynamic>>`.
  /// Returns `null` if the key is not cached.
  Future<List<Map<String, dynamic>>?> getCachedList(String key) async {
    final row = await getCached(key);
    if (row == null) return null;
    try {
      final decoded = jsonDecode(row.payload);
      if (decoded is List) {
        return decoded
            .map((e) => e is Map<String, dynamic>
                ? e
                : Map<String, dynamic>.from(e as Map))
            .toList();
      }
    } catch (_) {}
    return null;
  }

  /// Caches a list of JSON-serializable items.
  Future<void> cacheList(String key, List<Map<String, dynamic>> items) =>
      upsertCache(key, jsonEncode(items));

  /// Reads a cached JSON object as `Map<String, dynamic>`.
  /// Returns `null` if the key is not cached.
  Future<Map<String, dynamic>?> getCachedObject(String key) async {
    final row = await getCached(key);
    if (row == null) return null;
    try {
      final decoded = jsonDecode(row.payload);
      if (decoded is Map<String, dynamic>) return decoded;
      if (decoded is Map) return Map<String, dynamic>.from(decoded);
    } catch (_) {}
    return null;
  }

  /// Caches a single JSON-serializable object.
  Future<void> cacheObject(String key, Map<String, dynamic> obj) =>
      upsertCache(key, jsonEncode(obj));

  /// Wipe all cached data — used on logout.
  Future<void> clearAll() async {
    await batch((b) {
      b.deleteAll(userProfileTable);
      b.deleteAll(memoTable);
      b.deleteAll(accomplishmentTable);
      b.deleteAll(schoolTable);
      b.deleteAll(schoolPersonnelTable);
      b.deleteAll(syncOutboxTable);
      b.deleteAll(syncMetadataTable);
      b.deleteAll(apiCacheTable);
    });
  }
}

LazyDatabase _open() {
  return LazyDatabase(() => platform.openDb());
}
