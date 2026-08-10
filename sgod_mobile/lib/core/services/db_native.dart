import 'dart:io';

import 'package:drift/drift.dart';
import 'package:drift/native.dart';
import 'package:path_provider/path_provider.dart';

/// Opens a native SQLite database file (Android, iOS, macOS, Windows, Linux).
Future<QueryExecutor> openDb() async {
  final dir = await getApplicationDocumentsDirectory();
  final file = File('${dir.path}/sgod_mobile.sqlite');
  return NativeDatabase.createInBackground(file);
}
