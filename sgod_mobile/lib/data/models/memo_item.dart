import 'package:freezed_annotation/freezed_annotation.dart';

part 'memo_item.freezed.dart';
part 'memo_item.g.dart';

/// A memo record returned by `api/memos_index`.
///
/// PHP returns all values as strings, so every field is a [String].
@freezed
class MemoItem with _$MemoItem {
  const factory MemoItem({
    required String id,
    @Default('') String title,
    @Default('') String memoNo,
    @JsonKey(name: 'added_by') @Default('') String addedBy,
    @JsonKey(name: 'sec_group') @Default('') String secGroup,
  }) = _MemoItem;

  factory MemoItem.fromJson(Map<String, dynamic> json) =>
      _$MemoItemFromJson(json);
}
