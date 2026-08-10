import 'package:drift/drift.dart';
import 'package:drift/web.dart';

/// Opens a web-based SQLite database using sql.js (auto-loaded from CDN by
/// drift). Used only on the web platform.
Future<QueryExecutor> openDb() async {
  return WebDatabase('sgod_mobile');
}
