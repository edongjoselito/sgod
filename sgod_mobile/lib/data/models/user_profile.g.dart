// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'user_profile.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_$UserProfileImpl _$$UserProfileImplFromJson(Map<String, dynamic> json) =>
    _$UserProfileImpl(
      id: (json['id'] as num).toInt(),
      username: json['username'] as String,
      role: $enumDecode(_$RoleEnumMap, json['role']),
      fname: json['fname'] as String,
      lname: json['lname'] as String,
      email: json['email'] as String?,
      avatar: json['avatar'] as String?,
      section: json['section'] as String? ?? '',
      secGroup: json['secGroup'] as String? ?? '',
      loginSource: json['loginSource'] as String? ?? '',
    );

Map<String, dynamic> _$$UserProfileImplToJson(_$UserProfileImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'username': instance.username,
      'role': _$RoleEnumMap[instance.role]!,
      'fname': instance.fname,
      'lname': instance.lname,
      'email': instance.email,
      'avatar': instance.avatar,
      'section': instance.section,
      'secGroup': instance.secGroup,
      'loginSource': instance.loginSource,
    };

const _$RoleEnumMap = {
  Role.sgod: 'sgod',
  Role.shns: 'shns',
  Role.school: 'school',
  Role.sned: 'sned',
  Role.smme: 'smme',
  Role.district: 'district',
  Role.unknown: 'unknown',
};
