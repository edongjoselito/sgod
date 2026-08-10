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
};
