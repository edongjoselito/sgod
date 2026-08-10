import 'package:freezed_annotation/freezed_annotation.dart';

part 'dashboard_data.freezed.dart';
part 'dashboard_data.g.dart';

/// Converts dynamic values (PHP returns everything as strings) to int.
class IntConverter implements JsonConverter<int, dynamic> {
  const IntConverter();

  @override
  int fromJson(dynamic value) {
    if (value is int) return value;
    return int.tryParse(value?.toString() ?? '') ?? 0;
  }

  @override
  dynamic toJson(int value) => value;
}

@freezed
class DashboardData with _$DashboardData {
  const DashboardData._();

  const factory DashboardData({
    @Default([]) List<StatItem> stats,
    @Default([]) List<BreakdownItem> sectionBreakdown,
    @Default([]) List<RecentMemo> recentMemos,
    @Default([]) List<RecentAccomplishment> recentAccomplishments,
    @Default([]) List<RecentWhereabouts> recentWhereabouts,
  }) = _DashboardData;

  factory DashboardData.fromJson(Map<String, dynamic> json) =>
      _$DashboardDataFromJson(json);
}

@freezed
class StatItem with _$StatItem {
  const factory StatItem({
    required String label,
    required String value,
    required String icon,
  }) = _StatItem;

  factory StatItem.fromJson(Map<String, dynamic> json) =>
      _$StatItemFromJson(json);
}

@freezed
class BreakdownItem with _$BreakdownItem {
  const factory BreakdownItem({
    required String label,
    @IntConverter() required int value,
  }) = _BreakdownItem;

  factory BreakdownItem.fromJson(Map<String, dynamic> json) =>
      _$BreakdownItemFromJson(json);
}

@freezed
class RecentMemo with _$RecentMemo {
  const factory RecentMemo({
    required String id,
    required String title,
    @Default('') String memoNo,
    @JsonKey(name: 'added_by') @Default('') String addedBy,
  }) = _RecentMemo;

  factory RecentMemo.fromJson(Map<String, dynamic> json) =>
      _$RecentMemoFromJson(json);
}

@freezed
class RecentAccomplishment with _$RecentAccomplishment {
  const factory RecentAccomplishment({
    required String id,
    required String activity,
    @Default('') String section,
    @JsonKey(name: 'dateConducted') @Default('') String dateConducted,
    @JsonKey(name: 'percentageAccom') @Default('') String percentageAccom,
  }) = _RecentAccomplishment;

  factory RecentAccomplishment.fromJson(Map<String, dynamic> json) =>
      _$RecentAccomplishmentFromJson(json);
}

@freezed
class RecentWhereabouts with _$RecentWhereabouts {
  const factory RecentWhereabouts({
    required String id,
    @JsonKey(name: 'fName') required String fName,
    @JsonKey(name: 'lName') required String lName,
    @Default('') String section,
    @Default('') String date,
    @Default('') String location,
    @Default('') String activity,
    @Default('In Office') String status,
  }) = _RecentWhereabouts;

  factory RecentWhereabouts.fromJson(Map<String, dynamic> json) =>
      _$RecentWhereaboutsFromJson(json);
}
