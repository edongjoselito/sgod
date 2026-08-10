import 'package:flutter/material.dart';

import 'app.dart';
import 'ui/core/di.dart';

Future<void> main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await DI.init();
  runApp(const SgodApp());
}
