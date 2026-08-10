import 'package:drift/drift.dart';
import 'package:flutter/foundation.dart';

import '../../core/network/api_client.dart';
import '../../core/network/api_exception.dart';
import '../../core/services/app_database.dart';
import '../../core/services/secure_storage_service.dart';
import '../models/user_profile.dart';

/// Handles login, logout, session restore, and profile persistence.
class AuthRepository {
  AuthRepository({
    required this.api,
    required this.storage,
    required this.db,
  });

  final ApiClient api;
  final SecureStorageService storage;
  final AppDatabase db;

  /// Log in with username + password. The server auto-detects whether the
  /// account lives in `users` (DepEd MIS) or `one_sgod_users` (SGOD ONE).
  Future<UserProfile> login({
    required String username,
    required String password,
  }) async {
    debugPrint('AuthRepository: login attempt for "$username"');

    // Clear any stale token before logging in.
    api.clearToken();
    try {
      await storage.clearToken();
    } catch (_) {}

    final data = await api.post('api/auth_login', body: {
      'username': username,
      'password': password,
    });

    debugPrint('AuthRepository: login response received: $data');

    final token = (data['token'] as String?) ?? '';
    if (token.isEmpty) {
      throw ApiException('Server did not return a session token.');
    }

    final profileJson = (data['profile'] as Map<String, dynamic>?) ?? {};
    final loginSource = (profileJson['loginSource'] as String?) ?? 'deped_mis';
    final profile = _profileFromJson(profileJson, loginSource);

    // Save token + profile — these may fail on web, don't block login.
    try {
      await storage.saveToken(token);
      await storage.saveProfile(profileJson);
    } catch (e) {
      debugPrint('AuthRepository: storage save failed (non-fatal): $e');
    }
    api.configure(token: token);

    // Cache profile in Drift — may fail on web, don't block login.
    try {
      await db.upsertProfile(UserProfileTableCompanion(
        id: Value(profile.id),
        username: Value(profile.username),
        position: Value(profile.role.name),
        fname: Value(profile.fname),
        lname: Value(profile.lname),
        email: Value(profile.email),
        avatar: Value(profile.avatar),
        section: Value(profile.section),
        secGroup: Value(profile.secGroup),
        loginSource: Value(loginSource),
      ));
    } catch (e) {
      debugPrint('AuthRepository: db cache failed (non-fatal): $e');
    }

    debugPrint('AuthRepository: login success for ${profile.fullName}');
    return profile;
  }

  /// Restore a previously saved session, if any.
  Future<UserProfile?> restoreSession() async {
    try {
      final token = await storage.getToken();
      final baseUrl = await storage.getBaseUrl();
      if (token == null || token.isEmpty) return null;
      if (baseUrl != null && baseUrl.isNotEmpty) api.configure(baseUrl: baseUrl);
      api.configure(token: token);

      final cached = await db.getProfile();
      if (cached != null) {
        final profile = UserProfile(
          id: cached.id,
          username: cached.username,
          role: Role.fromString(cached.position),
          fname: cached.fname,
          lname: cached.lname,
          email: cached.email,
          avatar: cached.avatar,
          section: cached.section,
          secGroup: cached.secGroup,
          loginSource: cached.loginSource,
        );
        try {
          await api.get('api/auth_me');
        } catch (e) {
          // Token is invalid/expired — clear it so login starts fresh.
          api.clearToken();
          try {
            await storage.clearToken();
          } catch (_) {}
          return null;
        }
        return profile;
      }
    } catch (e) {
      debugPrint('AuthRepository: session restore failed: $e');
    }
    return null;
  }

  Future<void> logout() async {
    try {
      await api.post('api/auth_logout');
    } catch (_) {}
    api.clearToken();
    try {
      await storage.clearAll();
      await db.clearAll();
    } catch (_) {}
  }

  UserProfile _profileFromJson(Map<String, dynamic> json, String loginSource) {
    final id = int.tryParse('${json['id'] ?? json['user_id'] ?? 0}') ?? 0;
    final position = (json['position'] as String?) ?? '';
    return UserProfile(
      id: id,
      username: (json['username'] as String?) ?? '',
      role: Role.fromString(position),
      fname: (json['fname'] as String?) ?? (json['fName'] as String?) ?? '',
      lname: (json['lname'] as String?) ?? (json['lName'] as String?) ?? '',
      email: json['email'] as String?,
      avatar: json['avatar'] as String? ?? json['image'] as String?,
      section: (json['section'] as String?) ?? '',
      secGroup: (json['secGroup'] as String?) ?? '',
      loginSource: loginSource,
    );
  }
}
