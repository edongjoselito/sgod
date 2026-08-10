import 'package:flutter/cupertino.dart';
import 'package:provider/provider.dart';

import 'core/theme/app_theme.dart';
import 'ui/core/di.dart';
import 'ui/core/router.dart';
import 'ui/features/auth/view_models/auth_view_model.dart';

/// Root widget — CupertinoApp with iOS theme and router.
class SgodApp extends StatelessWidget {
  const SgodApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthViewModel(DI.auth)),
      ],
      child: Consumer<AuthViewModel>(
        builder: (context, auth, _) {
          return CupertinoApp.router(
            title: 'DepEd ONE',
            debugShowCheckedModeBanner: false,
            theme: AppTheme.build(),
            routerConfig: buildRouter(auth),
          );
        },
      ),
    );
  }
}
