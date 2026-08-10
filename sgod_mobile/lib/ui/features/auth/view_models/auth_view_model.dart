import 'package:flutter/foundation.dart';

import '../../../../data/models/user_profile.dart';
import '../../../../data/repositories/auth_repository.dart';

/// Manages login state and exposes the current [UserProfile].
///
/// Extends [ChangeNotifier] so the router and views can observe auth state
/// changes (login, logout, session restore).
class AuthViewModel extends ChangeNotifier {
  AuthViewModel(this._auth);

  final AuthRepository _auth;

  UserProfile? _profile;
  UserProfile? get profile => _profile;

  bool _isLoading = false;
  bool get isLoading => _isLoading;

  String? _error;
  String? get error => _error;

  bool get isLoggedIn => _profile != null;

  /// Restore a saved session at app startup.
  Future<void> restore() async {
    _isLoading = true;
    _error = null;
    notifyListeners();
    try {
      _profile = await _auth.restoreSession();
    } catch (e) {
      _profile = null;
      _error = '$e';
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  /// Log in with credentials. The server auto-detects the account source.
  Future<bool> login({
    required String username,
    required String password,
  }) async {
    _isLoading = true;
    _error = null;
    notifyListeners();
    try {
      _profile = await _auth.login(
        username: username,
        password: password,
      );
      return true;
    } catch (e) {
      _error = '$e';
      return false;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> logout() async {
    await _auth.logout();
    _profile = null;
    notifyListeners();
  }

  void clearError() {
    _error = null;
    notifyListeners();
  }
}
