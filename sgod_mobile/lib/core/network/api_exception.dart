/// Standard exception raised by the API client for any non-2xx response.
class ApiException implements Exception {
  ApiException(this.message, {this.statusCode, this.body});

  final String message;
  final int? statusCode;
  final Map<String, dynamic>? body;

  bool get isUnauthorized => statusCode == 401;
  bool get isNotFound => statusCode == 404;
  bool get isValidation => statusCode != null && statusCode! >= 400 && statusCode! < 500;
  bool get isServer => statusCode != null && statusCode! >= 500;
  bool get isConflict => statusCode == 409;

  @override
  String toString() => 'ApiException($statusCode): $message';
}
