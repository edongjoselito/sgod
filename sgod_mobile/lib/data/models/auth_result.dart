import 'package:freezed_annotation/freezed_annotation.dart';

part 'auth_result.freezed.dart';
part 'auth_result.g.dart';

@freezed
class AuthResult with _$AuthResult {
  const factory AuthResult({
    required String token,
    required Map<String, dynamic> profile,
  }) = _AuthResult;

  factory AuthResult.fromJson(Map<String, dynamic> json) =>
      _$AuthResultFromJson(json);
}
