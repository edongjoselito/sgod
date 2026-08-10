// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'accomplishment_item.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_$AccomplishmentItemImpl _$$AccomplishmentItemImplFromJson(
  Map<String, dynamic> json,
) => _$AccomplishmentItemImpl(
  id: json['id'] as String,
  activity: json['activity'] as String? ?? '',
  section: json['section'] as String? ?? '',
  dateConducted: json['dateConducted'] as String? ?? '',
  percentageAccom: json['percentageAccom'] as String? ?? '',
  quarter: json['quarter'] as String? ?? '',
  year: json['year'] as String? ?? '',
  achieved: json['achieved'] as String? ?? '',
  target: json['target'] as String? ?? '',
  monthAcc: json['monthAcc'] as String? ?? '',
  weekAcc: json['weekAcc'] as String? ?? '',
  activityCategory: json['activityCategory'] as String? ?? '',
  particulars: json['particulars'] as String? ?? '',
  venue: json['venue'] as String? ?? '',
  targetDate: json['targetDate'] as String? ?? '',
  encoder: json['encoder'] as String? ?? '',
  accomplishmentScope: json['accomplishmentScope'] as String? ?? '',
  resources: json['resources'] as String? ?? '',
  notes: json['notes'] as String? ?? '',
  perIndicators: json['perIndicators'] as String? ?? '',
  remarks: json['remarks'] as String? ?? '',
  secGroup: json['secGroup'] as String? ?? '',
);

Map<String, dynamic> _$$AccomplishmentItemImplToJson(
  _$AccomplishmentItemImpl instance,
) => <String, dynamic>{
  'id': instance.id,
  'activity': instance.activity,
  'section': instance.section,
  'dateConducted': instance.dateConducted,
  'percentageAccom': instance.percentageAccom,
  'quarter': instance.quarter,
  'year': instance.year,
  'achieved': instance.achieved,
  'target': instance.target,
  'monthAcc': instance.monthAcc,
  'weekAcc': instance.weekAcc,
  'activityCategory': instance.activityCategory,
  'particulars': instance.particulars,
  'venue': instance.venue,
  'targetDate': instance.targetDate,
  'encoder': instance.encoder,
  'accomplishmentScope': instance.accomplishmentScope,
  'resources': instance.resources,
  'notes': instance.notes,
  'perIndicators': instance.perIndicators,
  'remarks': instance.remarks,
  'secGroup': instance.secGroup,
};
