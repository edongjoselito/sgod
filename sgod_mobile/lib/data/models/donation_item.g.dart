// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'donation_item.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_$DonationItemImpl _$$DonationItemImplFromJson(Map<String, dynamic> json) =>
    _$DonationItemImpl(
      id: json['id'] as String,
      cDate: json['cDate'] as String? ?? '',
      partnersId: json['partnersId'] as String? ?? '',
      partnerName: json['partnerName'] as String? ?? '',
      spicificContribution: json['spicificContribution'] as String? ?? '',
      unitOfContribution: json['unitOfContribution'] as String? ?? '',
      quantity: json['quantity'] as String? ?? '',
      amount: json['amount'] as String? ?? '',
      noBeneficiaryLearnes: json['noBeneficiaryLearnes'] as String? ?? '',
      noBeneficiaryPersonnel: json['noBeneficiaryPersonnel'] as String? ?? '',
      formOfAgreement: json['formOfAgreement'] as String? ?? '',
      agreementStarted: json['agreementStarted'] as String? ?? '',
      agreementEnd: json['agreementEnd'] as String? ?? '',
      projectCategory: json['projectCategory'] as String? ?? '',
      projectName: json['projectName'] as String? ?? '',
      statusAgreement: json['statusAgreement'] as String? ?? '',
      taxIncentiveApplicable: json['taxIncentiveApplicable'] as String? ?? '',
      initiatedBy: json['initiatedBy'] as String? ?? '',
      remarks: json['remarks'] as String? ?? '',
      sy: json['sy'] as String? ?? '',
      breakdown:
          (json['breakdown'] as List<dynamic>?)
              ?.map(
                (e) =>
                    DonationBreakdownItem.fromJson(e as Map<String, dynamic>),
              )
              .toList() ??
          const [],
    );

Map<String, dynamic> _$$DonationItemImplToJson(_$DonationItemImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'cDate': instance.cDate,
      'partnersId': instance.partnersId,
      'partnerName': instance.partnerName,
      'spicificContribution': instance.spicificContribution,
      'unitOfContribution': instance.unitOfContribution,
      'quantity': instance.quantity,
      'amount': instance.amount,
      'noBeneficiaryLearnes': instance.noBeneficiaryLearnes,
      'noBeneficiaryPersonnel': instance.noBeneficiaryPersonnel,
      'formOfAgreement': instance.formOfAgreement,
      'agreementStarted': instance.agreementStarted,
      'agreementEnd': instance.agreementEnd,
      'projectCategory': instance.projectCategory,
      'projectName': instance.projectName,
      'statusAgreement': instance.statusAgreement,
      'taxIncentiveApplicable': instance.taxIncentiveApplicable,
      'initiatedBy': instance.initiatedBy,
      'remarks': instance.remarks,
      'sy': instance.sy,
      'breakdown': instance.breakdown,
    };

_$DonationBreakdownItemImpl _$$DonationBreakdownItemImplFromJson(
  Map<String, dynamic> json,
) => _$DonationBreakdownItemImpl(
  id: json['id'] as String,
  reportId: json['reportId'] as String? ?? '',
  itemDescription: json['itemDescription'] as String? ?? '',
  quantity: json['quantity'] as String? ?? '',
  unit: json['unit'] as String? ?? '',
  unitPrice: json['unitPrice'] as String? ?? '',
  amount: json['amount'] as String? ?? '',
  remarks: json['remarks'] as String? ?? '',
);

Map<String, dynamic> _$$DonationBreakdownItemImplToJson(
  _$DonationBreakdownItemImpl instance,
) => <String, dynamic>{
  'id': instance.id,
  'reportId': instance.reportId,
  'itemDescription': instance.itemDescription,
  'quantity': instance.quantity,
  'unit': instance.unit,
  'unitPrice': instance.unitPrice,
  'amount': instance.amount,
  'remarks': instance.remarks,
};
