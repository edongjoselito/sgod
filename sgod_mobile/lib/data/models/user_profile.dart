import 'package:freezed_annotation/freezed_annotation.dart';

part 'user_profile.freezed.dart';
part 'user_profile.g.dart';

/// Normalized role keys — lowercase, matching the server-side `position`
/// column after normalization in `Api.php`.
enum Role {
  sgod,
  shns,
  school,
  sned,
  smme,
  district,
  unknown;

  static Role fromString(String? value) {
    switch (value?.toLowerCase().trim()) {
      case 'sgod':
        return Role.sgod;
      case 'shns':
        return Role.shns;
      case 'school':
        return Role.school;
      case 'sned':
        return Role.sned;
      case 'smme':
        return Role.smme;
      case 'district':
        return Role.district;
      default:
        return Role.unknown;
    }
  }
}

@freezed
class UserProfile with _$UserProfile {
  const UserProfile._();

  const factory UserProfile({
    required int id,
    required String username,
    required Role role,
    required String fname,
    required String lname,
    String? email,
    String? avatar,
    @Default('') String section,
    @Default('') String secGroup,
    @Default('') String loginSource,
  }) = _UserProfile;

  factory UserProfile.fromJson(Map<String, dynamic> json) =>
      _$UserProfileFromJson(json);

  /// Convenience getter for the display name.
  String get fullName => '$fname $lname'.trim();
}
