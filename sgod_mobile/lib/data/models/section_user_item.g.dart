// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'section_user_item.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_$SectionUserItemImpl _$$SectionUserItemImplFromJson(
  Map<String, dynamic> json,
) => _$SectionUserItemImpl(
  username: json['username'] as String,
  fName: json['fName'] as String? ?? '',
  lName: json['lName'] as String? ?? '',
  section: json['section'] as String? ?? '',
  secGroup: json['secGroup'] as String? ?? '',
  acctStat: json['acctStat'] as String? ?? 'Active',
);

Map<String, dynamic> _$$SectionUserItemImplToJson(
  _$SectionUserItemImpl instance,
) => <String, dynamic>{
  'username': instance.username,
  'fName': instance.fName,
  'lName': instance.lName,
  'section': instance.section,
  'secGroup': instance.secGroup,
  'acctStat': instance.acctStat,
};
