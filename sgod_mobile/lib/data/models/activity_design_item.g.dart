// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'activity_design_item.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_$ActivityDesignItemImpl _$$ActivityDesignItemImplFromJson(
  Map<String, dynamic> json,
) => _$ActivityDesignItemImpl(
  id: json['id'] as String,
  username: json['username'] as String,
  title: json['title'] as String,
  activityDate: json['activity_date'] as String,
  venue: json['venue'] as String,
  activityDesignNo: json['activity_design_no'] as String,
);

Map<String, dynamic> _$$ActivityDesignItemImplToJson(
  _$ActivityDesignItemImpl instance,
) => <String, dynamic>{
  'id': instance.id,
  'username': instance.username,
  'title': instance.title,
  'activity_date': instance.activityDate,
  'venue': instance.venue,
  'activity_design_no': instance.activityDesignNo,
};
