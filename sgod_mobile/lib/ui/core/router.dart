import 'package:go_router/go_router.dart';

import '../features/auth/view_models/auth_view_model.dart';
import '../features/auth/views/login_view.dart';
import '../features/shell/views/app_shell.dart';

/// App router — redirects to `/login` when there is no session.
GoRouter buildRouter(AuthViewModel auth) {
  return GoRouter(
    initialLocation: '/login',
    refreshListenable: auth,
    redirect: (context, state) {
      final loggedIn = auth.isLoggedIn;
      final onLogin = state.matchedLocation == '/login';
      if (loggedIn && onLogin) return '/app';
      if (!loggedIn && !onLogin) return '/login';
      return null;
    },
    routes: [
      GoRoute(
        path: '/login',
        builder: (context, state) => const LoginView(),
      ),
      GoRoute(
        path: '/app',
        builder: (context, state) => const AppShell(),
      ),
    ],
  );
}
