// coverage:ignore-file
// GENERATED CODE - DO NOT MODIFY BY HAND
// ignore_for_file: type=lint
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'activity_design_item.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

T _$identity<T>(T value) => value;

final _privateConstructorUsedError = UnsupportedError(
  'It seems like you constructed your class using `MyClass._()`. This constructor is only meant to be used by freezed and you are not supposed to need it nor use it.\nPlease check the documentation here for more information: https://github.com/rrousselGit/freezed#adding-getters-and-methods-to-our-models',
);

ActivityDesignItem _$ActivityDesignItemFromJson(Map<String, dynamic> json) {
  return _ActivityDesignItem.fromJson(json);
}

/// @nodoc
mixin _$ActivityDesignItem {
  String get id => throw _privateConstructorUsedError;
  String get username => throw _privateConstructorUsedError;
  String get title => throw _privateConstructorUsedError;
  @JsonKey(name: 'activity_date')
  String get activityDate => throw _privateConstructorUsedError;
  String get venue => throw _privateConstructorUsedError;
  @JsonKey(name: 'activity_design_no')
  String get activityDesignNo => throw _privateConstructorUsedError;

  /// Serializes this ActivityDesignItem to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of ActivityDesignItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $ActivityDesignItemCopyWith<ActivityDesignItem> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $ActivityDesignItemCopyWith<$Res> {
  factory $ActivityDesignItemCopyWith(
    ActivityDesignItem value,
    $Res Function(ActivityDesignItem) then,
  ) = _$ActivityDesignItemCopyWithImpl<$Res, ActivityDesignItem>;
  @useResult
  $Res call({
    String id,
    String username,
    String title,
    @JsonKey(name: 'activity_date') String activityDate,
    String venue,
    @JsonKey(name: 'activity_design_no') String activityDesignNo,
  });
}

/// @nodoc
class _$ActivityDesignItemCopyWithImpl<$Res, $Val extends ActivityDesignItem>
    implements $ActivityDesignItemCopyWith<$Res> {
  _$ActivityDesignItemCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of ActivityDesignItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? username = null,
    Object? title = null,
    Object? activityDate = null,
    Object? venue = null,
    Object? activityDesignNo = null,
  }) {
    return _then(
      _value.copyWith(
            id: null == id
                ? _value.id
                : id // ignore: cast_nullable_to_non_nullable
                      as String,
            username: null == username
                ? _value.username
                : username // ignore: cast_nullable_to_non_nullable
                      as String,
            title: null == title
                ? _value.title
                : title // ignore: cast_nullable_to_non_nullable
                      as String,
            activityDate: null == activityDate
                ? _value.activityDate
                : activityDate // ignore: cast_nullable_to_non_nullable
                      as String,
            venue: null == venue
                ? _value.venue
                : venue // ignore: cast_nullable_to_non_nullable
                      as String,
            activityDesignNo: null == activityDesignNo
                ? _value.activityDesignNo
                : activityDesignNo // ignore: cast_nullable_to_non_nullable
                      as String,
          )
          as $Val,
    );
  }
}

/// @nodoc
abstract class _$$ActivityDesignItemImplCopyWith<$Res>
    implements $ActivityDesignItemCopyWith<$Res> {
  factory _$$ActivityDesignItemImplCopyWith(
    _$ActivityDesignItemImpl value,
    $Res Function(_$ActivityDesignItemImpl) then,
  ) = __$$ActivityDesignItemImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call({
    String id,
    String username,
    String title,
    @JsonKey(name: 'activity_date') String activityDate,
    String venue,
    @JsonKey(name: 'activity_design_no') String activityDesignNo,
  });
}

/// @nodoc
class __$$ActivityDesignItemImplCopyWithImpl<$Res>
    extends _$ActivityDesignItemCopyWithImpl<$Res, _$ActivityDesignItemImpl>
    implements _$$ActivityDesignItemImplCopyWith<$Res> {
  __$$ActivityDesignItemImplCopyWithImpl(
    _$ActivityDesignItemImpl _value,
    $Res Function(_$ActivityDesignItemImpl) _then,
  ) : super(_value, _then);

  /// Create a copy of ActivityDesignItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? username = null,
    Object? title = null,
    Object? activityDate = null,
    Object? venue = null,
    Object? activityDesignNo = null,
  }) {
    return _then(
      _$ActivityDesignItemImpl(
        id: null == id
            ? _value.id
            : id // ignore: cast_nullable_to_non_nullable
                  as String,
        username: null == username
            ? _value.username
            : username // ignore: cast_nullable_to_non_nullable
                  as String,
        title: null == title
            ? _value.title
            : title // ignore: cast_nullable_to_non_nullable
                  as String,
        activityDate: null == activityDate
            ? _value.activityDate
            : activityDate // ignore: cast_nullable_to_non_nullable
                  as String,
        venue: null == venue
            ? _value.venue
            : venue // ignore: cast_nullable_to_non_nullable
                  as String,
        activityDesignNo: null == activityDesignNo
            ? _value.activityDesignNo
            : activityDesignNo // ignore: cast_nullable_to_non_nullable
                  as String,
      ),
    );
  }
}

/// @nodoc
@JsonSerializable()
class _$ActivityDesignItemImpl implements _ActivityDesignItem {
  const _$ActivityDesignItemImpl({
    required this.id,
    required this.username,
    required this.title,
    @JsonKey(name: 'activity_date') required this.activityDate,
    required this.venue,
    @JsonKey(name: 'activity_design_no') required this.activityDesignNo,
  });

  factory _$ActivityDesignItemImpl.fromJson(Map<String, dynamic> json) =>
      _$$ActivityDesignItemImplFromJson(json);

  @override
  final String id;
  @override
  final String username;
  @override
  final String title;
  @override
  @JsonKey(name: 'activity_date')
  final String activityDate;
  @override
  final String venue;
  @override
  @JsonKey(name: 'activity_design_no')
  final String activityDesignNo;

  @override
  String toString() {
    return 'ActivityDesignItem(id: $id, username: $username, title: $title, activityDate: $activityDate, venue: $venue, activityDesignNo: $activityDesignNo)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$ActivityDesignItemImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.username, username) ||
                other.username == username) &&
            (identical(other.title, title) || other.title == title) &&
            (identical(other.activityDate, activityDate) ||
                other.activityDate == activityDate) &&
            (identical(other.venue, venue) || other.venue == venue) &&
            (identical(other.activityDesignNo, activityDesignNo) ||
                other.activityDesignNo == activityDesignNo));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(
    runtimeType,
    id,
    username,
    title,
    activityDate,
    venue,
    activityDesignNo,
  );

  /// Create a copy of ActivityDesignItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$ActivityDesignItemImplCopyWith<_$ActivityDesignItemImpl> get copyWith =>
      __$$ActivityDesignItemImplCopyWithImpl<_$ActivityDesignItemImpl>(
        this,
        _$identity,
      );

  @override
  Map<String, dynamic> toJson() {
    return _$$ActivityDesignItemImplToJson(this);
  }
}

abstract class _ActivityDesignItem implements ActivityDesignItem {
  const factory _ActivityDesignItem({
    required final String id,
    required final String username,
    required final String title,
    @JsonKey(name: 'activity_date') required final String activityDate,
    required final String venue,
    @JsonKey(name: 'activity_design_no') required final String activityDesignNo,
  }) = _$ActivityDesignItemImpl;

  factory _ActivityDesignItem.fromJson(Map<String, dynamic> json) =
      _$ActivityDesignItemImpl.fromJson;

  @override
  String get id;
  @override
  String get username;
  @override
  String get title;
  @override
  @JsonKey(name: 'activity_date')
  String get activityDate;
  @override
  String get venue;
  @override
  @JsonKey(name: 'activity_design_no')
  String get activityDesignNo;

  /// Create a copy of ActivityDesignItem
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$ActivityDesignItemImplCopyWith<_$ActivityDesignItemImpl> get copyWith =>
      throw _privateConstructorUsedError;
}
