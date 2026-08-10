// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'dashboard_data.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_$DashboardDataImpl _$$DashboardDataImplFromJson(
  Map<String, dynamic> json,
) => _$DashboardDataImpl(
  stats:
      (json['stats'] as List<dynamic>?)
          ?.map((e) => StatItem.fromJson(e as Map<String, dynamic>))
          .toList() ??
      const [],
  sectionBreakdown:
      (json['sectionBreakdown'] as List<dynamic>?)
          ?.map((e) => BreakdownItem.fromJson(e as Map<String, dynamic>))
          .toList() ??
      const [],
  recentMemos:
      (json['recentMemos'] as List<dynamic>?)
          ?.map((e) => RecentMemo.fromJson(e as Map<String, dynamic>))
          .toList() ??
      const [],
  recentAccomplishments:
      (json['recentAccomplishments'] as List<dynamic>?)
          ?.map((e) => RecentAccomplishment.fromJson(e as Map<String, dynamic>))
          .toList() ??
      const [],
  recentWhereabouts:
      (json['recentWhereabouts'] as List<dynamic>?)
          ?.map((e) => RecentWhereabouts.fromJson(e as Map<String, dynamic>))
          .toList() ??
      const [],
);

Map<String, dynamic> _$$DashboardDataImplToJson(_$DashboardDataImpl instance) =>
    <String, dynamic>{
      'stats': instance.stats,
      'sectionBreakdown': instance.sectionBreakdown,
      'recentMemos': instance.recentMemos,
      'recentAccomplishments': instance.recentAccomplishments,
      'recentWhereabouts': instance.recentWhereabouts,
    };

_$StatItemImpl _$$StatItemImplFromJson(Map<String, dynamic> json) =>
    _$StatItemImpl(
      label: json['label'] as String,
      value: json['value'] as String,
      icon: json['icon'] as String,
    );

Map<String, dynamic> _$$StatItemImplToJson(_$StatItemImpl instance) =>
    <String, dynamic>{
      'label': instance.label,
      'value': instance.value,
      'icon': instance.icon,
    };

_$BreakdownItemImpl _$$BreakdownItemImplFromJson(Map<String, dynamic> json) =>
    _$BreakdownItemImpl(
      label: json['label'] as String,
      value: const IntConverter().fromJson(json['value']),
    );

Map<String, dynamic> _$$BreakdownItemImplToJson(_$BreakdownItemImpl instance) =>
    <String, dynamic>{
      'label': instance.label,
      'value': const IntConverter().toJson(instance.value),
    };

_$RecentMemoImpl _$$RecentMemoImplFromJson(Map<String, dynamic> json) =>
    _$RecentMemoImpl(
      id: json['id'] as String,
      title: json['title'] as String,
      memoNo: json['memoNo'] as String? ?? '',
      addedBy: json['added_by'] as String? ?? '',
    );

Map<String, dynamic> _$$RecentMemoImplToJson(_$RecentMemoImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'title': instance.title,
      'memoNo': instance.memoNo,
      'added_by': instance.addedBy,
    };

_$RecentAccomplishmentImpl _$$RecentAccomplishmentImplFromJson(
  Map<String, dynamic> json,
) => _$RecentAccomplishmentImpl(
  id: json['id'] as String,
  activity: json['activity'] as String,
  section: json['section'] as String? ?? '',
  dateConducted: json['dateConducted'] as String? ?? '',
  percentageAccom: json['percentageAccom'] as String? ?? '',
);

Map<String, dynamic> _$$RecentAccomplishmentImplToJson(
  _$RecentAccomplishmentImpl instance,
) => <String, dynamic>{
  'id': instance.id,
  'activity': instance.activity,
  'section': instance.section,
  'dateConducted': instance.dateConducted,
  'percentageAccom': instance.percentageAccom,
};

_$RecentWhereaboutsImpl _$$RecentWhereaboutsImplFromJson(
  Map<String, dynamic> json,
) => _$RecentWhereaboutsImpl(
  id: json['id'] as String,
  fName: json['fName'] as String,
  lName: json['lName'] as String,
  section: json['section'] as String? ?? '',
  date: json['date'] as String? ?? '',
  location: json['location'] as String? ?? '',
  activity: json['activity'] as String? ?? '',
  status: json['status'] as String? ?? 'In Office',
);

Map<String, dynamic> _$$RecentWhereaboutsImplToJson(
  _$RecentWhereaboutsImpl instance,
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
