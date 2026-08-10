import 'package:freezed_annotation/freezed_annotation.dart';

part 'issue_item.freezed.dart';
part 'issue_item.g.dart';

/// One row from `section_issues_concerns`. All values arrive as strings
/// from the PHP API, so every field is typed as String.
@freezed
class IssueItem with _$IssueItem {
  const IssueItem._();

  const factory IssueItem({
    required String id,
    @Default('') String section,
    @Default('') String secGroup,
    @Default('') String username,
    required String title,
    @Default('') String description,
    @Default('Normal') String priority,
    @Default('Open') String status,
    @Default('') String year,
    @JsonKey(name: 'created_at') @Default('') String createdAt,
  }) = _IssueItem;

  factory IssueItem.fromJson(Map<String, dynamic> json) =>
      _$IssueItemFromJson(json);

  /// `created_at` is stored as `YYYY-MM-DD HH:MM:SS` — return just the date.
  String get dateOnly =>
      createdAt.isEmpty ? '' : createdAt.split(' ').first;
}
