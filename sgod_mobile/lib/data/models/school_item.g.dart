// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'school_item.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_$SchoolItemImpl _$$SchoolItemImplFromJson(Map<String, dynamic> json) =>
    _$SchoolItemImpl(
      recID: json['recID'] as String,
      schoolID: json['schoolID'] as String,
      schoolName: json['schoolName'] as String,
      division: json['division'] as String? ?? '',
      district: json['district'] as String? ?? '',
      course: json['course'] as String? ?? '',
      schoolType: json['schoolType'] as String? ?? '',
      schoolEmail: json['schoolEmail'] as String? ?? '',
    );

Map<String, dynamic> _$$SchoolItemImplToJson(_$SchoolItemImpl instance) =>
    <String, dynamic>{
      'recID': instance.recID,
      'schoolID': instance.schoolID,
      'schoolName': instance.schoolName,
      'division': instance.division,
      'district': instance.district,
      'course': instance.course,
      'schoolType': instance.schoolType,
      'schoolEmail': instance.schoolEmail,
    };
