// coverage:ignore-file
// GENERATED CODE - DO NOT MODIFY BY HAND
// ignore_for_file: type=lint
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'dashboard_data.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

T _$identity<T>(T value) => value;

final _privateConstructorUsedError = UnsupportedError(
  'It seems like you constructed your class using `MyClass._()`. This constructor is only meant to be used by freezed and you are not supposed to need it nor use it.\nPlease check the documentation here for more information: https://github.com/rrousselGit/freezed#adding-getters-and-methods-to-our-models',
);

DashboardData _$DashboardDataFromJson(Map<String, dynamic> json) {
  return _DashboardData.fromJson(json);
}

/// @nodoc
mixin _$DashboardData {
  List<StatItem> get stats => throw _privateConstructorUsedError;
  List<BreakdownItem> get sectionBreakdown =>
      throw _privateConstructorUsedError;
  List<RecentMemo> get recentMemos => throw _privateConstructorUsedError;
  List<RecentAccomplishment> get recentAccomplishments =>
      throw _privateConstructorUsedError;
  List<RecentWhereabouts> get recentWhereabouts =>
      throw _privateConstructorUsedError;

  /// Serializes this DashboardData to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of DashboardData
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $DashboardDataCopyWith<DashboardData> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $DashboardDataCopyWith<$Res> {
  factory $DashboardDataCopyWith(
    DashboardData value,
    $Res Function(DashboardData) then,
  ) = _$DashboardDataCopyWithImpl<$Res, DashboardData>;
  @useResult
  $Res call({
    List<StatItem> stats,
    List<BreakdownItem> sectionBreakdown,
    List<RecentMemo> recentMemos,
    List<RecentAccomplishment> recentAccomplishments,
    List<RecentWhereabouts> recentWhereabouts,
  });
}

/// @nodoc
class _$DashboardDataCopyWithImpl<$Res, $Val extends DashboardData>
    implements $DashboardDataCopyWith<$Res> {
  _$DashboardDataCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of DashboardData
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? stats = null,
    Object? sectionBreakdown = null,
    Object? recentMemos = null,
    Object? recentAccomplishments = null,
    Object? recentWhereabouts = null,
  }) {
    return _then(
      _value.copyWith(
            stats: null == stats
                ? _value.stats
                : stats // ignore: cast_nullable_to_non_nullable
                      as List<StatItem>,
            sectionBreakdown: null == sectionBreakdown
                ? _value.sectionBreakdown
                : sectionBreakdown // ignore: cast_nullable_to_non_nullable
                      as List<BreakdownItem>,
            recentMemos: null == recentMemos
                ? _value.recentMemos
                : recentMemos // ignore: cast_nullable_to_non_nullable
                      as List<RecentMemo>,
            recentAccomplishments: null == recentAccomplishments
                ? _value.recentAccomplishments
                : recentAccomplishments // ignore: cast_nullable_to_non_nullable
                      as List<RecentAccomplishment>,
            recentWhereabouts: null == recentWhereabouts
                ? _value.recentWhereabouts
                : recentWhereabouts // ignore: cast_nullable_to_non_nullable
                      as List<RecentWhereabouts>,
          )
          as $Val,
    );
  }
}

/// @nodoc
abstract class _$$DashboardDataImplCopyWith<$Res>
    implements $DashboardDataCopyWith<$Res> {
  factory _$$DashboardDataImplCopyWith(
    _$DashboardDataImpl value,
    $Res Function(_$DashboardDataImpl) then,
  ) = __$$DashboardDataImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call({
    List<StatItem> stats,
    List<BreakdownItem> sectionBreakdown,
    List<RecentMemo> recentMemos,
    List<RecentAccomplishment> recentAccomplishments,
    List<RecentWhereabouts> recentWhereabouts,
  });
}

/// @nodoc
class __$$DashboardDataImplCopyWithImpl<$Res>
    extends _$DashboardDataCopyWithImpl<$Res, _$DashboardDataImpl>
    implements _$$DashboardDataImplCopyWith<$Res> {
  __$$DashboardDataImplCopyWithImpl(
    _$DashboardDataImpl _value,
    $Res Function(_$DashboardDataImpl) _then,
  ) : super(_value, _then);

  /// Create a copy of DashboardData
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? stats = null,
    Object? sectionBreakdown = null,
    Object? recentMemos = null,
    Object? recentAccomplishments = null,
    Object? recentWhereabouts = null,
  }) {
    return _then(
      _$DashboardDataImpl(
        stats: null == stats
            ? _value._stats
            : stats // ignore: cast_nullable_to_non_nullable
                  as List<StatItem>,
        sectionBreakdown: null == sectionBreakdown
            ? _value._sectionBreakdown
            : sectionBreakdown // ignore: cast_nullable_to_non_nullable
                  as List<BreakdownItem>,
        recentMemos: null == recentMemos
            ? _value._recentMemos
            : recentMemos // ignore: cast_nullable_to_non_nullable
                  as List<RecentMemo>,
        recentAccomplishments: null == recentAccomplishments
            ? _value._recentAccomplishments
            : recentAccomplishments // ignore: cast_nullable_to_non_nullable
                  as List<RecentAccomplishment>,
        recentWhereabouts: null == recentWhereabouts
            ? _value._recentWhereabouts
            : recentWhereabouts // ignore: cast_nullable_to_non_nullable
                  as List<RecentWhereabouts>,
      ),
    );
  }
}

/// @nodoc
@JsonSerializable()
class _$DashboardDataImpl extends _DashboardData {
  const _$DashboardDataImpl({
    final List<StatItem> stats = const [],
    final List<BreakdownItem> sectionBreakdown = const [],
    final List<RecentMemo> recentMemos = const [],
    final List<RecentAccomplishment> recentAccomplishments = const [],
    final List<RecentWhereabouts> recentWhereabouts = const [],
  }) : _stats = stats,
       _sectionBreakdown = sectionBreakdown,
       _recentMemos = recentMemos,
       _recentAccomplishments = recentAccomplishments,
       _recentWhereabouts = recentWhereabouts,
       super._();

  factory _$DashboardDataImpl.fromJson(Map<String, dynamic> json) =>
      _$$DashboardDataImplFromJson(json);

  final List<StatItem> _stats;
  @override
  @JsonKey()
  List<StatItem> get stats {
    if (_stats is EqualUnmodifiableListView) return _stats;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_stats);
  }

  final List<BreakdownItem> _sectionBreakdown;
  @override
  @JsonKey()
  List<BreakdownItem> get sectionBreakdown {
    if (_sectionBreakdown is EqualUnmodifiableListView)
      return _sectionBreakdown;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_sectionBreakdown);
  }

  final List<RecentMemo> _recentMemos;
  @override
  @JsonKey()
  List<RecentMemo> get recentMemos {
    if (_recentMemos is EqualUnmodifiableListView) return _recentMemos;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_recentMemos);
  }

  final List<RecentAccomplishment> _recentAccomplishments;
  @override
  @JsonKey()
  List<RecentAccomplishment> get recentAccomplishments {
    if (_recentAccomplishments is EqualUnmodifiableListView)
      return _recentAccomplishments;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_recentAccomplishments);
  }

  final List<RecentWhereabouts> _recentWhereabouts;
  @override
  @JsonKey()
  List<RecentWhereabouts> get recentWhereabouts {
    if (_recentWhereabouts is EqualUnmodifiableListView)
      return _recentWhereabouts;
    // ignore: implicit_dynamic_type
    return EqualUnmodifiableListView(_recentWhereabouts);
  }

  @override
  String toString() {
    return 'DashboardData(stats: $stats, sectionBreakdown: $sectionBreakdown, recentMemos: $recentMemos, recentAccomplishments: $recentAccomplishments, recentWhereabouts: $recentWhereabouts)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$DashboardDataImpl &&
            const DeepCollectionEquality().equals(other._stats, _stats) &&
            const DeepCollectionEquality().equals(
              other._sectionBreakdown,
              _sectionBreakdown,
            ) &&
            const DeepCollectionEquality().equals(
              other._recentMemos,
              _recentMemos,
            ) &&
            const DeepCollectionEquality().equals(
              other._recentAccomplishments,
              _recentAccomplishments,
            ) &&
            const DeepCollectionEquality().equals(
              other._recentWhereabouts,
              _recentWhereabouts,
            ));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(
    runtimeType,
    const DeepCollectionEquality().hash(_stats),
    const DeepCollectionEquality().hash(_sectionBreakdown),
    const DeepCollectionEquality().hash(_recentMemos),
    const DeepCollectionEquality().hash(_recentAccomplishments),
    const DeepCollectionEquality().hash(_recentWhereabouts),
  );

  /// Create a copy of DashboardData
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$DashboardDataImplCopyWith<_$DashboardDataImpl> get copyWith =>
      __$$DashboardDataImplCopyWithImpl<_$DashboardDataImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$DashboardDataImplToJson(this);
  }
}

abstract class _DashboardData extends DashboardData {
  const factory _DashboardData({
    final List<StatItem> stats,
    final List<BreakdownItem> sectionBreakdown,
    final List<RecentMemo> recentMemos,
    final List<RecentAccomplishment> recentAccomplishments,
    final List<RecentWhereabouts> recentWhereabouts,
  }) = _$DashboardDataImpl;
  const _DashboardData._() : super._();

  factory _DashboardData.fromJson(Map<String, dynamic> json) =
      _$DashboardDataImpl.fromJson;

  @override
  List<StatItem> get stats;
  @override
  List<BreakdownItem> get sectionBreakdown;
  @override
  List<RecentMemo> get recentMemos;
  @override
  List<RecentAccomplishment> get recentAccomplishments;
  @override
  List<RecentWhereabouts> get recentWhereabouts;

  /// Create a copy of DashboardData
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$DashboardDataImplCopyWith<_$DashboardDataImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

StatItem _$StatItemFromJson(Map<String, dynamic> json) {
  return _StatItem.fromJson(json);
}

/// @nodoc
mixin _$StatItem {
  String get label => throw _privateConstructorUsedError;
  String get value => throw _privateConstructorUsedError;
  String get icon => throw _privateConstructorUsedError;

  /// Serializes this StatItem to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of StatItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $StatItemCopyWith<StatItem> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $StatItemCopyWith<$Res> {
  factory $StatItemCopyWith(StatItem value, $Res Function(StatItem) then) =
      _$StatItemCopyWithImpl<$Res, StatItem>;
  @useResult
  $Res call({String label, String value, String icon});
}

/// @nodoc
class _$StatItemCopyWithImpl<$Res, $Val extends StatItem>
    implements $StatItemCopyWith<$Res> {
  _$StatItemCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of StatItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({Object? label = null, Object? value = null, Object? icon = null}) {
    return _then(
      _value.copyWith(
            label: null == label
                ? _value.label
                : label // ignore: cast_nullable_to_non_nullable
                      as String,
            value: null == value
                ? _value.value
                : value // ignore: cast_nullable_to_non_nullable
                      as String,
            icon: null == icon
                ? _value.icon
                : icon // ignore: cast_nullable_to_non_nullable
                      as String,
          )
          as $Val,
    );
  }
}

/// @nodoc
abstract class _$$StatItemImplCopyWith<$Res>
    implements $StatItemCopyWith<$Res> {
  factory _$$StatItemImplCopyWith(
    _$StatItemImpl value,
    $Res Function(_$StatItemImpl) then,
  ) = __$$StatItemImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call({String label, String value, String icon});
}

/// @nodoc
class __$$StatItemImplCopyWithImpl<$Res>
    extends _$StatItemCopyWithImpl<$Res, _$StatItemImpl>
    implements _$$StatItemImplCopyWith<$Res> {
  __$$StatItemImplCopyWithImpl(
    _$StatItemImpl _value,
    $Res Function(_$StatItemImpl) _then,
  ) : super(_value, _then);

  /// Create a copy of StatItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({Object? label = null, Object? value = null, Object? icon = null}) {
    return _then(
      _$StatItemImpl(
        label: null == label
            ? _value.label
            : label // ignore: cast_nullable_to_non_nullable
                  as String,
        value: null == value
            ? _value.value
            : value // ignore: cast_nullable_to_non_nullable
                  as String,
        icon: null == icon
            ? _value.icon
            : icon // ignore: cast_nullable_to_non_nullable
                  as String,
      ),
    );
  }
}

/// @nodoc
@JsonSerializable()
class _$StatItemImpl implements _StatItem {
  const _$StatItemImpl({
    required this.label,
    required this.value,
    required this.icon,
  });

  factory _$StatItemImpl.fromJson(Map<String, dynamic> json) =>
      _$$StatItemImplFromJson(json);

  @override
  final String label;
  @override
  final String value;
  @override
  final String icon;

  @override
  String toString() {
    return 'StatItem(label: $label, value: $value, icon: $icon)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$StatItemImpl &&
            (identical(other.label, label) || other.label == label) &&
            (identical(other.value, value) || other.value == value) &&
            (identical(other.icon, icon) || other.icon == icon));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(runtimeType, label, value, icon);

  /// Create a copy of StatItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$StatItemImplCopyWith<_$StatItemImpl> get copyWith =>
      __$$StatItemImplCopyWithImpl<_$StatItemImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$StatItemImplToJson(this);
  }
}

abstract class _StatItem implements StatItem {
  const factory _StatItem({
    required final String label,
    required final String value,
    required final String icon,
  }) = _$StatItemImpl;

  factory _StatItem.fromJson(Map<String, dynamic> json) =
      _$StatItemImpl.fromJson;

  @override
  String get label;
  @override
  String get value;
  @override
  String get icon;

  /// Create a copy of StatItem
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$StatItemImplCopyWith<_$StatItemImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

BreakdownItem _$BreakdownItemFromJson(Map<String, dynamic> json) {
  return _BreakdownItem.fromJson(json);
}

/// @nodoc
mixin _$BreakdownItem {
  String get label => throw _privateConstructorUsedError;
  @IntConverter()
  int get value => throw _privateConstructorUsedError;

  /// Serializes this BreakdownItem to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of BreakdownItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $BreakdownItemCopyWith<BreakdownItem> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $BreakdownItemCopyWith<$Res> {
  factory $BreakdownItemCopyWith(
    BreakdownItem value,
    $Res Function(BreakdownItem) then,
  ) = _$BreakdownItemCopyWithImpl<$Res, BreakdownItem>;
  @useResult
  $Res call({String label, @IntConverter() int value});
}

/// @nodoc
class _$BreakdownItemCopyWithImpl<$Res, $Val extends BreakdownItem>
    implements $BreakdownItemCopyWith<$Res> {
  _$BreakdownItemCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of BreakdownItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({Object? label = null, Object? value = null}) {
    return _then(
      _value.copyWith(
            label: null == label
                ? _value.label
                : label // ignore: cast_nullable_to_non_nullable
                      as String,
            value: null == value
                ? _value.value
                : value // ignore: cast_nullable_to_non_nullable
                      as int,
          )
          as $Val,
    );
  }
}

/// @nodoc
abstract class _$$BreakdownItemImplCopyWith<$Res>
    implements $BreakdownItemCopyWith<$Res> {
  factory _$$BreakdownItemImplCopyWith(
    _$BreakdownItemImpl value,
    $Res Function(_$BreakdownItemImpl) then,
  ) = __$$BreakdownItemImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call({String label, @IntConverter() int value});
}

/// @nodoc
class __$$BreakdownItemImplCopyWithImpl<$Res>
    extends _$BreakdownItemCopyWithImpl<$Res, _$BreakdownItemImpl>
    implements _$$BreakdownItemImplCopyWith<$Res> {
  __$$BreakdownItemImplCopyWithImpl(
    _$BreakdownItemImpl _value,
    $Res Function(_$BreakdownItemImpl) _then,
  ) : super(_value, _then);

  /// Create a copy of BreakdownItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({Object? label = null, Object? value = null}) {
    return _then(
      _$BreakdownItemImpl(
        label: null == label
            ? _value.label
            : label // ignore: cast_nullable_to_non_nullable
                  as String,
        value: null == value
            ? _value.value
            : value // ignore: cast_nullable_to_non_nullable
                  as int,
      ),
    );
  }
}

/// @nodoc
@JsonSerializable()
class _$BreakdownItemImpl implements _BreakdownItem {
  const _$BreakdownItemImpl({
    required this.label,
    @IntConverter() required this.value,
  });

  factory _$BreakdownItemImpl.fromJson(Map<String, dynamic> json) =>
      _$$BreakdownItemImplFromJson(json);

  @override
  final String label;
  @override
  @IntConverter()
  final int value;

  @override
  String toString() {
    return 'BreakdownItem(label: $label, value: $value)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$BreakdownItemImpl &&
            (identical(other.label, label) || other.label == label) &&
            (identical(other.value, value) || other.value == value));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(runtimeType, label, value);

  /// Create a copy of BreakdownItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$BreakdownItemImplCopyWith<_$BreakdownItemImpl> get copyWith =>
      __$$BreakdownItemImplCopyWithImpl<_$BreakdownItemImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$BreakdownItemImplToJson(this);
  }
}

abstract class _BreakdownItem implements BreakdownItem {
  const factory _BreakdownItem({
    required final String label,
    @IntConverter() required final int value,
  }) = _$BreakdownItemImpl;

  factory _BreakdownItem.fromJson(Map<String, dynamic> json) =
      _$BreakdownItemImpl.fromJson;

  @override
  String get label;
  @override
  @IntConverter()
  int get value;

  /// Create a copy of BreakdownItem
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$BreakdownItemImplCopyWith<_$BreakdownItemImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

RecentMemo _$RecentMemoFromJson(Map<String, dynamic> json) {
  return _RecentMemo.fromJson(json);
}

/// @nodoc
mixin _$RecentMemo {
  String get id => throw _privateConstructorUsedError;
  String get title => throw _privateConstructorUsedError;
  String get memoNo => throw _privateConstructorUsedError;
  @JsonKey(name: 'added_by')
  String get addedBy => throw _privateConstructorUsedError;

  /// Serializes this RecentMemo to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of RecentMemo
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $RecentMemoCopyWith<RecentMemo> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $RecentMemoCopyWith<$Res> {
  factory $RecentMemoCopyWith(
    RecentMemo value,
    $Res Function(RecentMemo) then,
  ) = _$RecentMemoCopyWithImpl<$Res, RecentMemo>;
  @useResult
  $Res call({
    String id,
    String title,
    String memoNo,
    @JsonKey(name: 'added_by') String addedBy,
  });
}

/// @nodoc
class _$RecentMemoCopyWithImpl<$Res, $Val extends RecentMemo>
    implements $RecentMemoCopyWith<$Res> {
  _$RecentMemoCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of RecentMemo
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? title = null,
    Object? memoNo = null,
    Object? addedBy = null,
  }) {
    return _then(
      _value.copyWith(
            id: null == id
                ? _value.id
                : id // ignore: cast_nullable_to_non_nullable
                      as String,
            title: null == title
                ? _value.title
                : title // ignore: cast_nullable_to_non_nullable
                      as String,
            memoNo: null == memoNo
                ? _value.memoNo
                : memoNo // ignore: cast_nullable_to_non_nullable
                      as String,
            addedBy: null == addedBy
                ? _value.addedBy
                : addedBy // ignore: cast_nullable_to_non_nullable
                      as String,
          )
          as $Val,
    );
  }
}

/// @nodoc
abstract class _$$RecentMemoImplCopyWith<$Res>
    implements $RecentMemoCopyWith<$Res> {
  factory _$$RecentMemoImplCopyWith(
    _$RecentMemoImpl value,
    $Res Function(_$RecentMemoImpl) then,
  ) = __$$RecentMemoImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call({
    String id,
    String title,
    String memoNo,
    @JsonKey(name: 'added_by') String addedBy,
  });
}

/// @nodoc
class __$$RecentMemoImplCopyWithImpl<$Res>
    extends _$RecentMemoCopyWithImpl<$Res, _$RecentMemoImpl>
    implements _$$RecentMemoImplCopyWith<$Res> {
  __$$RecentMemoImplCopyWithImpl(
    _$RecentMemoImpl _value,
    $Res Function(_$RecentMemoImpl) _then,
  ) : super(_value, _then);

  /// Create a copy of RecentMemo
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? title = null,
    Object? memoNo = null,
    Object? addedBy = null,
  }) {
    return _then(
      _$RecentMemoImpl(
        id: null == id
            ? _value.id
            : id // ignore: cast_nullable_to_non_nullable
                  as String,
        title: null == title
            ? _value.title
            : title // ignore: cast_nullable_to_non_nullable
                  as String,
        memoNo: null == memoNo
            ? _value.memoNo
            : memoNo // ignore: cast_nullable_to_non_nullable
                  as String,
        addedBy: null == addedBy
            ? _value.addedBy
            : addedBy // ignore: cast_nullable_to_non_nullable
                  as String,
      ),
    );
  }
}

/// @nodoc
@JsonSerializable()
class _$RecentMemoImpl implements _RecentMemo {
  const _$RecentMemoImpl({
    required this.id,
    required this.title,
    this.memoNo = '',
    @JsonKey(name: 'added_by') this.addedBy = '',
  });

  factory _$RecentMemoImpl.fromJson(Map<String, dynamic> json) =>
      _$$RecentMemoImplFromJson(json);

  @override
  final String id;
  @override
  final String title;
  @override
  @JsonKey()
  final String memoNo;
  @override
  @JsonKey(name: 'added_by')
  final String addedBy;

  @override
  String toString() {
    return 'RecentMemo(id: $id, title: $title, memoNo: $memoNo, addedBy: $addedBy)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$RecentMemoImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.title, title) || other.title == title) &&
            (identical(other.memoNo, memoNo) || other.memoNo == memoNo) &&
            (identical(other.addedBy, addedBy) || other.addedBy == addedBy));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(runtimeType, id, title, memoNo, addedBy);

  /// Create a copy of RecentMemo
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$RecentMemoImplCopyWith<_$RecentMemoImpl> get copyWith =>
      __$$RecentMemoImplCopyWithImpl<_$RecentMemoImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$RecentMemoImplToJson(this);
  }
}

abstract class _RecentMemo implements RecentMemo {
  const factory _RecentMemo({
    required final String id,
    required final String title,
    final String memoNo,
    @JsonKey(name: 'added_by') final String addedBy,
  }) = _$RecentMemoImpl;

  factory _RecentMemo.fromJson(Map<String, dynamic> json) =
      _$RecentMemoImpl.fromJson;

  @override
  String get id;
  @override
  String get title;
  @override
  String get memoNo;
  @override
  @JsonKey(name: 'added_by')
  String get addedBy;

  /// Create a copy of RecentMemo
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$RecentMemoImplCopyWith<_$RecentMemoImpl> get copyWith =>
      throw _privateConstructorUsedError;
}

RecentAccomplishment _$RecentAccomplishmentFromJson(Map<String, dynamic> json) {
  return _RecentAccomplishment.fromJson(json);
}

/// @nodoc
mixin _$RecentAccomplishment {
  String get id => throw _privateConstructorUsedError;
  String get activity => throw _privateConstructorUsedError;
  String get section => throw _privateConstructorUsedError;
  @JsonKey(name: 'dateConducted')
  String get dateConducted => throw _privateConstructorUsedError;
  @JsonKey(name: 'percentageAccom')
  String get percentageAccom => throw _privateConstructorUsedError;

  /// Serializes this RecentAccomplishment to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of RecentAccomplishment
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $RecentAccomplishmentCopyWith<RecentAccomplishment> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $RecentAccomplishmentCopyWith<$Res> {
  factory $RecentAccomplishmentCopyWith(
    RecentAccomplishment value,
    $Res Function(RecentAccomplishment) then,
  ) = _$RecentAccomplishmentCopyWithImpl<$Res, RecentAccomplishment>;
  @useResult
  $Res call({
    String id,
    String activity,
    String section,
    @JsonKey(name: 'dateConducted') String dateConducted,
    @JsonKey(name: 'percentageAccom') String percentageAccom,
  });
}

/// @nodoc
class _$RecentAccomplishmentCopyWithImpl<
  $Res,
  $Val extends RecentAccomplishment
>
    implements $RecentAccomplishmentCopyWith<$Res> {
  _$RecentAccomplishmentCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of RecentAccomplishment
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? activity = null,
    Object? section = null,
    Object? dateConducted = null,
    Object? percentageAccom = null,
  }) {
    return _then(
      _value.copyWith(
            id: null == id
                ? _value.id
                : id // ignore: cast_nullable_to_non_nullable
                      as String,
            activity: null == activity
                ? _value.activity
                : activity // ignore: cast_nullable_to_non_nullable
                      as String,
            section: null == section
                ? _value.section
                : section // ignore: cast_nullable_to_non_nullable
                      as String,
            dateConducted: null == dateConducted
                ? _value.dateConducted
                : dateConducted // ignore: cast_nullable_to_non_nullable
                      as String,
            percentageAccom: null == percentageAccom
                ? _value.percentageAccom
                : percentageAccom // ignore: cast_nullable_to_non_nullable
                      as String,
          )
          as $Val,
    );
  }
}

/// @nodoc
abstract class _$$RecentAccomplishmentImplCopyWith<$Res>
    implements $RecentAccomplishmentCopyWith<$Res> {
  factory _$$RecentAccomplishmentImplCopyWith(
    _$RecentAccomplishmentImpl value,
    $Res Function(_$RecentAccomplishmentImpl) then,
  ) = __$$RecentAccomplishmentImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call({
    String id,
    String activity,
    String section,
    @JsonKey(name: 'dateConducted') String dateConducted,
    @JsonKey(name: 'percentageAccom') String percentageAccom,
  });
}

/// @nodoc
class __$$RecentAccomplishmentImplCopyWithImpl<$Res>
    extends _$RecentAccomplishmentCopyWithImpl<$Res, _$RecentAccomplishmentImpl>
    implements _$$RecentAccomplishmentImplCopyWith<$Res> {
  __$$RecentAccomplishmentImplCopyWithImpl(
    _$RecentAccomplishmentImpl _value,
    $Res Function(_$RecentAccomplishmentImpl) _then,
  ) : super(_value, _then);

  /// Create a copy of RecentAccomplishment
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? activity = null,
    Object? section = null,
    Object? dateConducted = null,
    Object? percentageAccom = null,
  }) {
    return _then(
      _$RecentAccomplishmentImpl(
        id: null == id
            ? _value.id
            : id // ignore: cast_nullable_to_non_nullable
                  as String,
        activity: null == activity
            ? _value.activity
            : activity // ignore: cast_nullable_to_non_nullable
                  as String,
        section: null == section
            ? _value.section
            : section // ignore: cast_nullable_to_non_nullable
                  as String,
        dateConducted: null == dateConducted
            ? _value.dateConducted
            : dateConducted // ignore: cast_nullable_to_non_nullable
                  as String,
        percentageAccom: null == percentageAccom
            ? _value.percentageAccom
            : percentageAccom // ignore: cast_nullable_to_non_nullable
                  as String,
      ),
    );
  }
}

/// @nodoc
@JsonSerializable()
class _$RecentAccomplishmentImpl implements _RecentAccomplishment {
  const _$RecentAccomplishmentImpl({
    required this.id,
    required this.activity,
    this.section = '',
    @JsonKey(name: 'dateConducted') this.dateConducted = '',
    @JsonKey(name: 'percentageAccom') this.percentageAccom = '',
  });

  factory _$RecentAccomplishmentImpl.fromJson(Map<String, dynamic> json) =>
      _$$RecentAccomplishmentImplFromJson(json);

  @override
  final String id;
  @override
  final String activity;
  @override
  @JsonKey()
  final String section;
  @override
  @JsonKey(name: 'dateConducted')
  final String dateConducted;
  @override
  @JsonKey(name: 'percentageAccom')
  final String percentageAccom;

  @override
  String toString() {
    return 'RecentAccomplishment(id: $id, activity: $activity, section: $section, dateConducted: $dateConducted, percentageAccom: $percentageAccom)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$RecentAccomplishmentImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.activity, activity) ||
                other.activity == activity) &&
            (identical(other.section, section) || other.section == section) &&
            (identical(other.dateConducted, dateConducted) ||
                other.dateConducted == dateConducted) &&
            (identical(other.percentageAccom, percentageAccom) ||
                other.percentageAccom == percentageAccom));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(
    runtimeType,
    id,
    activity,
    section,
    dateConducted,
    percentageAccom,
  );

  /// Create a copy of RecentAccomplishment
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$RecentAccomplishmentImplCopyWith<_$RecentAccomplishmentImpl>
  get copyWith =>
      __$$RecentAccomplishmentImplCopyWithImpl<_$RecentAccomplishmentImpl>(
        this,
        _$identity,
      );

  @override
  Map<String, dynamic> toJson() {
    return _$$RecentAccomplishmentImplToJson(this);
  }
}

abstract class _RecentAccomplishment implements RecentAccomplishment {
  const factory _RecentAccomplishment({
    required final String id,
    required final String activity,
    final String section,
    @JsonKey(name: 'dateConducted') final String dateConducted,
    @JsonKey(name: 'percentageAccom') final String percentageAccom,
  }) = _$RecentAccomplishmentImpl;

  factory _RecentAccomplishment.fromJson(Map<String, dynamic> json) =
      _$RecentAccomplishmentImpl.fromJson;

  @override
  String get id;
  @override
  String get activity;
  @override
  String get section;
  @override
  @JsonKey(name: 'dateConducted')
  String get dateConducted;
  @override
  @JsonKey(name: 'percentageAccom')
  String get percentageAccom;

  /// Create a copy of RecentAccomplishment
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$RecentAccomplishmentImplCopyWith<_$RecentAccomplishmentImpl>
  get copyWith => throw _privateConstructorUsedError;
}

RecentWhereabouts _$RecentWhereaboutsFromJson(Map<String, dynamic> json) {
  return _RecentWhereabouts.fromJson(json);
}

/// @nodoc
mixin _$RecentWhereabouts {
  String get id => throw _privateConstructorUsedError;
  @JsonKey(name: 'fName')
  String get fName => throw _privateConstructorUsedError;
  @JsonKey(name: 'lName')
  String get lName => throw _privateConstructorUsedError;
  String get section => throw _privateConstructorUsedError;
  String get date => throw _privateConstructorUsedError;
  String get location => throw _privateConstructorUsedError;
  String get activity => throw _privateConstructorUsedError;
  String get status => throw _privateConstructorUsedError;

  /// Serializes this RecentWhereabouts to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of RecentWhereabouts
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $RecentWhereaboutsCopyWith<RecentWhereabouts> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $RecentWhereaboutsCopyWith<$Res> {
  factory $RecentWhereaboutsCopyWith(
    RecentWhereabouts value,
    $Res Function(RecentWhereabouts) then,
  ) = _$RecentWhereaboutsCopyWithImpl<$Res, RecentWhereabouts>;
  @useResult
  $Res call({
    String id,
    @JsonKey(name: 'fName') String fName,
    @JsonKey(name: 'lName') String lName,
    String section,
    String date,
    String location,
    String activity,
    String status,
  });
}

/// @nodoc
class _$RecentWhereaboutsCopyWithImpl<$Res, $Val extends RecentWhereabouts>
    implements $RecentWhereaboutsCopyWith<$Res> {
  _$RecentWhereaboutsCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of RecentWhereabouts
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? fName = null,
    Object? lName = null,
    Object? section = null,
    Object? date = null,
    Object? location = null,
    Object? activity = null,
    Object? status = null,
  }) {
    return _then(
      _value.copyWith(
            id: null == id
                ? _value.id
                : id // ignore: cast_nullable_to_non_nullable
                      as String,
            fName: null == fName
                ? _value.fName
                : fName // ignore: cast_nullable_to_non_nullable
                      as String,
            lName: null == lName
                ? _value.lName
                : lName // ignore: cast_nullable_to_non_nullable
                      as String,
            section: null == section
                ? _value.section
                : section // ignore: cast_nullable_to_non_nullable
                      as String,
            date: null == date
                ? _value.date
                : date // ignore: cast_nullable_to_non_nullable
                      as String,
            location: null == location
                ? _value.location
                : location // ignore: cast_nullable_to_non_nullable
                      as String,
            activity: null == activity
                ? _value.activity
                : activity // ignore: cast_nullable_to_non_nullable
                      as String,
            status: null == status
                ? _value.status
                : status // ignore: cast_nullable_to_non_nullable
                      as String,
          )
          as $Val,
    );
  }
}

/// @nodoc
abstract class _$$RecentWhereaboutsImplCopyWith<$Res>
    implements $RecentWhereaboutsCopyWith<$Res> {
  factory _$$RecentWhereaboutsImplCopyWith(
    _$RecentWhereaboutsImpl value,
    $Res Function(_$RecentWhereaboutsImpl) then,
  ) = __$$RecentWhereaboutsImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call({
    String id,
    @JsonKey(name: 'fName') String fName,
    @JsonKey(name: 'lName') String lName,
    String section,
    String date,
    String location,
    String activity,
    String status,
  });
}

/// @nodoc
class __$$RecentWhereaboutsImplCopyWithImpl<$Res>
    extends _$RecentWhereaboutsCopyWithImpl<$Res, _$RecentWhereaboutsImpl>
    implements _$$RecentWhereaboutsImplCopyWith<$Res> {
  __$$RecentWhereaboutsImplCopyWithImpl(
    _$RecentWhereaboutsImpl _value,
    $Res Function(_$RecentWhereaboutsImpl) _then,
  ) : super(_value, _then);

  /// Create a copy of RecentWhereabouts
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? fName = null,
    Object? lName = null,
    Object? section = null,
    Object? date = null,
    Object? location = null,
    Object? activity = null,
    Object? status = null,
  }) {
    return _then(
      _$RecentWhereaboutsImpl(
        id: null == id
            ? _value.id
            : id // ignore: cast_nullable_to_non_nullable
                  as String,
        fName: null == fName
            ? _value.fName
            : fName // ignore: cast_nullable_to_non_nullable
                  as String,
        lName: null == lName
            ? _value.lName
            : lName // ignore: cast_nullable_to_non_nullable
                  as String,
        section: null == section
            ? _value.section
            : section // ignore: cast_nullable_to_non_nullable
                  as String,
        date: null == date
            ? _value.date
            : date // ignore: cast_nullable_to_non_nullable
                  as String,
        location: null == location
            ? _value.location
            : location // ignore: cast_nullable_to_non_nullable
                  as String,
        activity: null == activity
            ? _value.activity
            : activity // ignore: cast_nullable_to_non_nullable
                  as String,
        status: null == status
            ? _value.status
            : status // ignore: cast_nullable_to_non_nullable
                  as String,
      ),
    );
  }
}

/// @nodoc
@JsonSerializable()
class _$RecentWhereaboutsImpl implements _RecentWhereabouts {
  const _$RecentWhereaboutsImpl({
    required this.id,
    @JsonKey(name: 'fName') required this.fName,
    @JsonKey(name: 'lName') required this.lName,
    this.section = '',
    this.date = '',
    this.location = '',
    this.activity = '',
    this.status = 'In Office',
  });

  factory _$RecentWhereaboutsImpl.fromJson(Map<String, dynamic> json) =>
      _$$RecentWhereaboutsImplFromJson(json);

  @override
  final String id;
  @override
  @JsonKey(name: 'fName')
  final String fName;
  @override
  @JsonKey(name: 'lName')
  final String lName;
  @override
  @JsonKey()
  final String section;
  @override
  @JsonKey()
  final String date;
  @override
  @JsonKey()
  final String location;
  @override
  @JsonKey()
  final String activity;
  @override
  @JsonKey()
  final String status;

  @override
  String toString() {
    return 'RecentWhereabouts(id: $id, fName: $fName, lName: $lName, section: $section, date: $date, location: $location, activity: $activity, status: $status)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$RecentWhereaboutsImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.fName, fName) || other.fName == fName) &&
            (identical(other.lName, lName) || other.lName == lName) &&
            (identical(other.section, section) || other.section == section) &&
            (identical(other.date, date) || other.date == date) &&
            (identical(other.location, location) ||
                other.location == location) &&
            (identical(other.activity, activity) ||
                other.activity == activity) &&
            (identical(other.status, status) || other.status == status));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(
    runtimeType,
    id,
    fName,
    lName,
    section,
    date,
    location,
    activity,
    status,
  );

  /// Create a copy of RecentWhereabouts
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$RecentWhereaboutsImplCopyWith<_$RecentWhereaboutsImpl> get copyWith =>
      __$$RecentWhereaboutsImplCopyWithImpl<_$RecentWhereaboutsImpl>(
        this,
        _$identity,
      );

  @override
  Map<String, dynamic> toJson() {
    return _$$RecentWhereaboutsImplToJson(this);
  }
}

abstract class _RecentWhereabouts implements RecentWhereabouts {
  const factory _RecentWhereabouts({
    required final String id,
    @JsonKey(name: 'fName') required final String fName,
    @JsonKey(name: 'lName') required final String lName,
    final String section,
    final String date,
    final String location,
    final String activity,
    final String status,
  }) = _$RecentWhereaboutsImpl;

  factory _RecentWhereabouts.fromJson(Map<String, dynamic> json) =
      _$RecentWhereaboutsImpl.fromJson;

  @override
  String get id;
  @override
  @JsonKey(name: 'fName')
  String get fName;
  @override
  @JsonKey(name: 'lName')
  String get lName;
  @override
  String get section;
  @override
  String get date;
  @override
  String get location;
  @override
  String get activity;
  @override
  String get status;

  /// Create a copy of RecentWhereabouts
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$RecentWhereaboutsImplCopyWith<_$RecentWhereaboutsImpl> get copyWith =>
      throw _privateConstructorUsedError;
}
