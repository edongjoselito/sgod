// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'memo_item.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_$MemoItemImpl _$$MemoItemImplFromJson(Map<String, dynamic> json) =>
    _$MemoItemImpl(
      id: json['id'] as String,
      title: json['title'] as String? ?? '',
      memoNo: json['memoNo'] as String? ?? '',
      addedBy: json['added_by'] as String? ?? '',
      secGroup: json['sec_group'] as String? ?? '',
    );

Map<String, dynamic> _$$MemoItemImplToJson(_$MemoItemImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'title': instance.title,
      'memoNo': instance.memoNo,
      'added_by': instance.addedBy,
      'sec_group': instance.secGroup,
    };
