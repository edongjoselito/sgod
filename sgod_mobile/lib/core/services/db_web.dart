import 'package:drift/drift.dart';
import 'package:drift/web.dart';

/// Opens a web-based SQLite database using drift's legacy web backend
/// (sql.js). The sql.js library is loaded via a <script> tag in
/// web/index.html. Data persists in IndexedDB.
Future<QueryExecutor> openDb() async {
  return WebDatabase('sgod_mobile');
}
