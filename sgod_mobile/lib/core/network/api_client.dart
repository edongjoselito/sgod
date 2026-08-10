import 'dart:async';
import 'dart:convert';

import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;

import 'api_exception.dart';

/// Thin HTTP client for the SGOD mobile API.
///
/// Owns the base URL and bearer token, parses the standard `{ok, message,
/// data}` envelope, and surfaces failures as [ApiException]. GET requests
/// are retried up to 3 times for transient network failures; POST/PUT/DELETE
/// are never replayed automatically because the server may have completed
/// the write before the connection dropped.
class ApiClient {
  ApiClient({http.Client? httpClient})
      : _http = httpClient ?? http.Client();

  /// The one and only backend. The server is not user-configurable — every
  /// build talks to production.
  static const String defaultBaseUrl = 'http://one.depedmis.com';

  static const Duration _requestTimeout = Duration(seconds: 15);
  static const Duration _uploadTimeout = Duration(seconds: 120);
  static const int _maxRetries = 3;
  static const Duration _retryBaseDelay = Duration(milliseconds: 500);

  final http.Client _http;

  String _baseUrl = '';
  String? _token;

  /// Fired when the server rejects the token (401), so the app can drop
  /// the session and return to login.
  void Function()? onUnauthorized;

  String get baseUrl => _baseUrl;
  bool get hasToken => (_token ?? '').isNotEmpty;

  void configure({String? baseUrl, String? token}) {
    if (baseUrl != null) _baseUrl = normalizeBaseUrl(baseUrl);
    if (token != null) _token = token;
  }

  void clearToken() => _token = null;

  static String normalizeBaseUrl(String value) {
    var v = value.trim();
    if (v.isEmpty) return '';
    if (!v.startsWith('http://') && !v.startsWith('https://')) {
      v = 'http://$v';
    }
    return v.replaceFirst(RegExp(r'/+$'), '');
  }

  Uri _uri(String path, [Map<String, dynamic>? query]) {
    // Strip leading slashes so the path is appended to the base URL,
    // preserving any base path (e.g. /sgod).
    final cleanPath = path.replaceFirst(RegExp(r'^/+'), '');
    final uri = Uri.parse('$_baseUrl/$cleanPath');
    if (query == null || query.isEmpty) return uri;
    return uri.replace(
      queryParameters: query.map((k, v) => MapEntry(k, v.toString())),
    );
  }

  /// Shared hosting running PHP as CGI/FastCGI drops the `Authorization`
  /// header before PHP sees it, so the token is mirrored into `X-Api-Token`,
  /// which passes through untouched. The server accepts either.
  Map<String, String> get _headers => {
        'Accept': 'application/json',
        if (_token != null && _token!.isNotEmpty) ...{
          'Authorization': 'Bearer $_token',
          'X-Api-Token': _token!,
        },
      };

  /// GET — returns the `data` field from the envelope, or null if absent.
  Future<dynamic> get(
    String path, {
    Map<String, dynamic>? query,
  }) async {
    int attempt = 0;
    while (true) {
      try {
        final response = await _http
            .get(_uri(path, query), headers: _headers)
            .timeout(_requestTimeout);
        return _parse(response);
      } on http.ClientException {
        attempt++;
        if (attempt >= _maxRetries) rethrow;
        await Future.delayed(_retryBaseDelay * attempt);
      } on TimeoutException {
        attempt++;
        if (attempt >= _maxRetries) rethrow;
        await Future.delayed(_retryBaseDelay * attempt);
      }
    }
  }

  /// POST — never retried automatically.
  Future<dynamic> post(
    String path, {
    Map<String, dynamic>? body,
    Map<String, dynamic>? query,
  }) async {
    final response = await _http
        .post(
          _uri(path, query),
          headers: {
            ..._headers,
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: body?.map((k, v) => MapEntry(k, v.toString())),
        )
        .timeout(_requestTimeout);
    return _parse(response);
  }

  /// POST multipart — for file uploads.
  Future<dynamic> upload(
    String path, {
    required Map<String, String> fields,
    required String fileField,
    required http.MultipartFile file,
  }) async {
    final request = http.MultipartRequest('POST', _uri(path))
      ..headers.addAll(_headers)
      ..fields.addAll(fields)
      ..files.add(file);
    final streamed = await request.send().timeout(_uploadTimeout);
    final response = await http.Response.fromStream(streamed);
    return _parse(response);
  }

  /// PUT — never retried automatically.
  Future<dynamic> put(
    String path, {
    Map<String, dynamic>? body,
  }) async {
    final response = await _http
        .put(
          _uri(path),
          headers: {
            ..._headers,
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: body?.map((k, v) => MapEntry(k, v.toString())),
        )
        .timeout(_requestTimeout);
    return _parse(response);
  }

  /// DELETE — never retried automatically.
  Future<dynamic> delete(
    String path, {
    Map<String, dynamic>? body,
  }) async {
    final response = await _http
        .delete(
          _uri(path),
          headers: {
            ..._headers,
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: body?.map((k, v) => MapEntry(k, v.toString())),
        )
        .timeout(_requestTimeout);
    return _parse(response);
  }

  /// Resolve an uploaded asset URL against the configured base URL so that
  /// `localhost` paths from the server become reachable from the device.
  String resolveAssetUrl(String value) {
    final raw = value.trim();
    if (raw.isEmpty) return raw;
    final uri = Uri.tryParse(raw);
    if (uri == null) return raw;
    if (!uri.hasScheme) return _uri(raw).toString();
    final base = Uri.tryParse(_baseUrl);
    if (base == null || base.host.isEmpty) return raw;
    final isLocal = uri.host == 'localhost' ||
        uri.host == '127.0.0.1' ||
        uri.host == '::1';
    if (!isLocal || uri.host == base.host) return raw;
    return uri.replace(host: base.host, port: base.port).toString();
  }

  dynamic _parse(http.Response response) {
    final body = response.body.trim();
    debugPrint('ApiClient: ${response.request?.method} ${response.request?.url} → ${response.statusCode} (${body.length} bytes)');

    Map<String, dynamic>? json;
    if (body.isNotEmpty) {
      try {
        final decoded = jsonDecode(body);
        if (decoded is Map<String, dynamic>) json = decoded;
      } on FormatException {
        json = null;
      }
    }

    // A 401 from the login endpoint means "wrong credentials", not "your
    // session lapsed" — surface the server's own wording so a rejected
    // login is never mistaken for an expired one, and don't drop a session
    // that the login attempt never established.
    if (response.statusCode == 401) {
      final isLogin =
          response.request?.url.path.contains('auth_login') ?? false;
      if (!isLogin) onUnauthorized?.call();
      final message = (json?['message'] as String?)?.trim() ?? '';
      throw ApiException(
        message.isNotEmpty ? message : 'Session expired. Please log in again.',
        statusCode: 401,
        body: json,
      );
    }

    if (body.isEmpty) {
      if (response.statusCode >= 200 && response.statusCode < 300) return null;
      throw ApiException('Empty response', statusCode: response.statusCode);
    }

    if (json == null) {
      if (response.statusCode >= 400) {
        throw ApiException('Request failed (${response.statusCode})',
            statusCode: response.statusCode);
      }
      return body;
    }

    final ok = json['ok'] as bool? ?? (response.statusCode < 400);
    final message = json['message'] as String? ?? '';
    final data = json['data'];

    if (!ok) {
      throw ApiException(
        message.isNotEmpty ? message : 'Request failed (${response.statusCode})',
        statusCode: response.statusCode,
        body: json,
      );
    }
    return data;
  }
}
