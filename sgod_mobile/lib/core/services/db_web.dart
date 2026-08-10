import 'package:drift/drift.dart';
import 'package:drift/web.dart';

/// Opens a web-based SQLite database using drift's legacy web backend
/// (sql.js). The sql.js library must be loaded via a <script> tag in
/// web/index.html — see the DriftWebStorage docs.
Future<QueryExecutor> openDb() async {
  return WebDatabase('sgod_mobile');
}
