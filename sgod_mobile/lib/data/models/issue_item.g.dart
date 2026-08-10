// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'issue_item.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_$IssueItemImpl _$$IssueItemImplFromJson(Map<String, dynamic> json) =>
    _$IssueItemImpl(
      id: json['id'] as String,
      section: json['section'] as String? ?? '',
      secGroup: json['secGroup'] as String? ?? '',
      username: json['username'] as String? ?? '',
      title: json['title'] as String,
      description: json['description'] as String? ?? '',
      priority: json['priority'] as String? ?? 'Normal',
      status: json['status'] as String? ?? 'Open',
      year: json['year'] as String? ?? '',
      createdAt: json['created_at'] as String? ?? '',
    );

Map<String, dynamic> _$$IssueItemImplToJson(_$IssueItemImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'section': instance.section,
      'secGroup': instance.secGroup,
      'username': instance.username,
      'title': instance.title,
      'description': instance.description,
      'priority': instance.priority,
      'status': instance.status,
      'year': instance.year,
      'created_at': instance.createdAt,
    };
