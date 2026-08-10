import 'package:freezed_annotation/freezed_annotation.dart';

part 'tax_incentive_requirement_item.freezed.dart';
part 'tax_incentive_requirement_item.g.dart';

/// A tax incentive requirement from `api/tax_incentive_requirements_index`.
@freezed
class TaxIncentiveRequirementItem with _$TaxIncentiveRequirementItem {
  const factory TaxIncentiveRequirementItem({
    required String id,
    @Default('') String donationId,
    @Default('') String requirement,
    @Default('Pending') String status,
    @Default('') String remarks,
  }) = _TaxIncentiveRequirementItem;

  factory TaxIncentiveRequirementItem.fromJson(Map<String, dynamic> json) =>
      _$TaxIncentiveRequirementItemFromJson(json);
}
