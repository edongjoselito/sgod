import 'dart:convert';

import 'package:flutter_secure_storage/flutter_secure_storage.dart';

/// Stores the bearer token and cached user profile securely on-device.
///
/// Uses Keychain (iOS) / EncryptedSharedPreferences (Android) so the token
/// survives app restarts but is not readable by other apps.
class SecureStorageService {
  SecureStorageService({FlutterSecureStorage? storage})
      : _storage = storage ?? const FlutterSecureStorage();

  final FlutterSecureStorage _storage;

  static const _keyToken = 'api_token';
  static const _keyProfile = 'user_profile';
  static const _keyBaseUrl = 'api_base_url';
  static const _keyBiometricEnabled = 'biometric_enabled';

  Future<String?> getToken() => _storage.read(key: _keyToken);
  Future<void> saveToken(String token) =>
      _storage.write(key: _keyToken, value: token);
  Future<void> clearToken() => _storage.delete(key: _keyToken);

  Future<String?> getBaseUrl() => _storage.read(key: _keyBaseUrl);
  Future<void> saveBaseUrl(String url) =>
      _storage.write(key: _keyBaseUrl, value: url);

  Future<Map<String, dynamic>?> getProfile() async {
    final raw = await _storage.read(key: _keyProfile);
    if (raw == null) return null;
    try {
      return jsonDecode(raw) as Map<String, dynamic>;
    } catch (_) {
      return null;
    }
  }

  Future<void> saveProfile(Map<String, dynamic> profile) =>
      _storage.write(key: _keyProfile, value: jsonEncode(profile));

  Future<void> clearProfile() => _storage.delete(key: _keyProfile);

  Future<bool> isBiometricEnabled() async {
    final v = await _storage.read(key: _keyBiometricEnabled);
    return v == '1';
  }

  Future<void> setBiometricEnabled(bool enabled) =>
      _storage.write(key: _keyBiometricEnabled, value: enabled ? '1' : '0');

  /// Wipe everything — used on logout.
  Future<void> clearAll() async {
    await _storage.deleteAll();
  }
}
