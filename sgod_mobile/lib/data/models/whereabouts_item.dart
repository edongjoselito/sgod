import 'package:freezed_annotation/freezed_annotation.dart';

part 'whereabouts_item.freezed.dart';
part 'whereabouts_item.g.dart';

/// A single whereabouts record returned by `api/whereabouts_index`.
///
/// PHP returns every value as a string, so all fields are [String].
@freezed
class WhereaboutsItem with _$WhereaboutsItem {
  const factory WhereaboutsItem({
    required String id,
    @JsonKey(name: 'fName') required String fName,
    @JsonKey(name: 'lName') required String lName,
    @Default('') String section,
    @Default('') String date,
    @Default('') String location,
    @Default('') String activity,
    @Default('') String status,
    @Default('') String notes,
  }) = _WhereaboutsItem;

  factory WhereaboutsItem.fromJson(Map<String, dynamic> json) =>
      _$WhereaboutsItemFromJson(json);
}
