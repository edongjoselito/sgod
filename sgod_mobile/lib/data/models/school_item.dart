import 'package:freezed_annotation/freezed_annotation.dart';

part 'school_item.freezed.dart';
part 'school_item.g.dart';

/// A single school record returned by `api/schools_index`.
///
/// PHP returns every value as a string, so all fields are [String].
@freezed
class SchoolItem with _$SchoolItem {
  const factory SchoolItem({
    required String recID,
    required String schoolID,
    required String schoolName,
    @Default('') String division,
    @Default('') String district,
    @Default('') String course,
    @Default('') String schoolType,
    @Default('') String schoolEmail,
  }) = _SchoolItem;

  factory SchoolItem.fromJson(Map<String, dynamic> json) =>
      _$SchoolItemFromJson(json);
}
