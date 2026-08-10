import 'package:freezed_annotation/freezed_annotation.dart';

part 'donation_item.freezed.dart';
part 'donation_item.g.dart';

/// A donation/contribution record from `api/donations_index`.
@freezed
class DonationItem with _$DonationItem {
  const factory DonationItem({
    required String id,
    @Default('') String cDate,
    @Default('') String partnersId,
    @Default('') String partnerName,
    @Default('') String spicificContribution,
    @Default('') String unitOfContribution,
    @Default('') String quantity,
    @Default('') String amount,
    @Default('') String noBeneficiaryLearnes,
    @Default('') String noBeneficiaryPersonnel,
    @Default('') String formOfAgreement,
    @Default('') String agreementStarted,
    @Default('') String agreementEnd,
    @Default('') String projectCategory,
    @Default('') String projectName,
    @Default('') String statusAgreement,
    @Default('') String taxIncentiveApplicable,
    @Default('') String initiatedBy,
    @Default('') String remarks,
    @Default('') String sy,
    @Default([]) List<DonationBreakdownItem> breakdown,
  }) = _DonationItem;

  factory DonationItem.fromJson(Map<String, dynamic> json) =>
      _$DonationItemFromJson(json);
}

@freezed
class DonationBreakdownItem with _$DonationBreakdownItem {
  const factory DonationBreakdownItem({
    required String id,
    @Default('') String reportId,
    @Default('') String itemDescription,
    @Default('') String quantity,
    @Default('') String unit,
    @Default('') String unitPrice,
    @Default('') String amount,
    @Default('') String remarks,
  }) = _DonationBreakdownItem;

  factory DonationBreakdownItem.fromJson(Map<String, dynamic> json) =>
      _$DonationBreakdownItemFromJson(json);
}
