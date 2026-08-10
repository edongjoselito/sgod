// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'whereabouts_item.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_$WhereaboutsItemImpl _$$WhereaboutsItemImplFromJson(
  Map<String, dynamic> json,
) => _$WhereaboutsItemImpl(
  id: json['id'] as String,
  fName: json['fName'] as String,
  lName: json['lName'] as String,
  section: json['section'] as String? ?? '',
  date: json['date'] as String? ?? '',
  location: json['location'] as String? ?? '',
  activity: json['activity'] as String? ?? '',
  status: json['status'] as String? ?? 'In Office',
);

Map<String, dynamic> _$$WhereaboutsItemImplToJson(
  _$WhereaboutsItemImpl instance,
) => <String, dynamic>{
  'id': instance.id,
  'fName': instance.fName,
  'lName': instance.lName,
  'section': instance.section,
  'date': instance.date,
  'location': instance.location,
  'activity': instance.activity,
  'status': instance.status,
};
