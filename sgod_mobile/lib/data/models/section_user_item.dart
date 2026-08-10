import 'package:freezed_annotation/freezed_annotation.dart';

part 'section_user_item.freezed.dart';
part 'section_user_item.g.dart';

/// One row from the section users listing. PHP returns all values as
/// strings, so every field is typed as String.
@freezed
class SectionUserItem with _$SectionUserItem {
  const SectionUserItem._();

  const factory SectionUserItem({
    required String username,
    @JsonKey(name: 'fName') @Default('') String fName,
    @JsonKey(name: 'lName') @Default('') String lName,
    @Default('') String section,
    @Default('') String secGroup,
    @JsonKey(name: 'acctStat') @Default('Active') String acctStat,
  }) = _SectionUserItem;

  factory SectionUserItem.fromJson(Map<String, dynamic> json) =>
      _$SectionUserItemFromJson(json);

  /// Display name — last name first for a more formal listing.
  String get fullName => '$fName $lName'.trim();

  /// Up to two uppercase initials for the avatar circle.
  String get initials {
    final f = fName.isNotEmpty ? fName[0] : '';
    final l = lName.isNotEmpty ? lName[0] : '';
    final raw = '$f$l'.toUpperCase();
    return raw.isEmpty ? '?' : raw;
  }
}
