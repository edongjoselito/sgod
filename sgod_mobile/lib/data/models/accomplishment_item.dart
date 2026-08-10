import 'package:freezed_annotation/freezed_annotation.dart';

part 'accomplishment_item.freezed.dart';
part 'accomplishment_item.g.dart';

/// An accomplishment record returned by `api/accomplishments_index`.
///
/// PHP returns all values as strings, so every field is a [String].
@freezed
class AccomplishmentItem with _$AccomplishmentItem {
  const factory AccomplishmentItem({
    required String id,
    @Default('') String activity,
    @Default('') String section,
    @JsonKey(name: 'dateConducted') @Default('') String dateConducted,
    @JsonKey(name: 'percentageAccom') @Default('') String percentageAccom,
    @Default('') String quarter,
    @Default('') String year,
    @Default('') String achieved,
    @Default('') String target,
    @Default('') String monthAcc,
    @Default('') String weekAcc,
    @Default('') String activityCategory,
    @Default('') String particulars,
    @Default('') String venue,
    @Default('') String targetDate,
    @Default('') String encoder,
    @Default('') String accomplishmentScope,
    @Default('') String resources,
    @Default('') String notes,
    @Default('') String perIndicators,
    @Default('') String remarks,
    @Default('') String secGroup,
  }) = _AccomplishmentItem;

  factory AccomplishmentItem.fromJson(Map<String, dynamic> json) =>
      _$AccomplishmentItemFromJson(json);
}
