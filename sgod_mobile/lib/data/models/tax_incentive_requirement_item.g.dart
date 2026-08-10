// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'tax_incentive_requirement_item.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_$TaxIncentiveRequirementItemImpl _$$TaxIncentiveRequirementItemImplFromJson(
  Map<String, dynamic> json,
) => _$TaxIncentiveRequirementItemImpl(
  id: json['id'] as String,
  donationId: json['donationId'] as String? ?? '',
  requirement: json['requirement'] as String? ?? '',
  status: json['status'] as String? ?? 'Pending',
  remarks: json['remarks'] as String? ?? '',
);

Map<String, dynamic> _$$TaxIncentiveRequirementItemImplToJson(
  _$TaxIncentiveRequirementItemImpl instance,
) => <String, dynamic>{
  'id': instance.id,
  'donationId': instance.donationId,
  'requirement': instance.requirement,
  'status': instance.status,
  'remarks': instance.remarks,
};
