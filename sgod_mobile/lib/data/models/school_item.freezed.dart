// coverage:ignore-file
// GENERATED CODE - DO NOT MODIFY BY HAND
// ignore_for_file: type=lint
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'school_item.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

T _$identity<T>(T value) => value;

final _privateConstructorUsedError = UnsupportedError(
  'It seems like you constructed your class using `MyClass._()`. This constructor is only meant to be used by freezed and you are not supposed to need it nor use it.\nPlease check the documentation here for more information: https://github.com/rrousselGit/freezed#adding-getters-and-methods-to-our-models',
);

SchoolItem _$SchoolItemFromJson(Map<String, dynamic> json) {
  return _SchoolItem.fromJson(json);
}

/// @nodoc
mixin _$SchoolItem {
  String get recID => throw _privateConstructorUsedError;
  String get schoolID => throw _privateConstructorUsedError;
  String get schoolName => throw _privateConstructorUsedError;
  String get division => throw _privateConstructorUsedError;
  String get district => throw _privateConstructorUsedError;
  String get course => throw _privateConstructorUsedError;
  String get schoolType => throw _privateConstructorUsedError;
  String get schoolEmail => throw _privateConstructorUsedError;

  /// Serializes this SchoolItem to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of SchoolItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $SchoolItemCopyWith<SchoolItem> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $SchoolItemCopyWith<$Res> {
  factory $SchoolItemCopyWith(
    SchoolItem value,
    $Res Function(SchoolItem) then,
  ) = _$SchoolItemCopyWithImpl<$Res, SchoolItem>;
  @useResult
  $Res call({
    String recID,
    String schoolID,
    String schoolName,
    String division,
    String district,
    String course,
    String schoolType,
    String schoolEmail,
  });
}

/// @nodoc
class _$SchoolItemCopyWithImpl<$Res, $Val extends SchoolItem>
    implements $SchoolItemCopyWith<$Res> {
  _$SchoolItemCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of SchoolItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? recID = null,
    Object? schoolID = null,
    Object? schoolName = null,
    Object? division = null,
    Object? district = null,
    Object? course = null,
    Object? schoolType = null,
    Object? schoolEmail = null,
  }) {
    return _then(
      _value.copyWith(
            recID: null == recID
                ? _value.recID
                : recID // ignore: cast_nullable_to_non_nullable
                      as String,
            schoolID: null == schoolID
                ? _value.schoolID
                : schoolID // ignore: cast_nullable_to_non_nullable
                      as String,
            schoolName: null == schoolName
                ? _value.schoolName
                : schoolName // ignore: cast_nullable_to_non_nullable
                      as String,
            division: null == division
                ? _value.division
                : division // ignore: cast_nullable_to_non_nullable
                      as String,
            district: null == district
                ? _value.district
                : district // ignore: cast_nullable_to_non_nullable
                      as String,
            course: null == course
                ? _value.course
                : course // ignore: cast_nullable_to_non_nullable
                      as String,
            schoolType: null == schoolType
                ? _value.schoolType
                : schoolType // ignore: cast_nullable_to_non_nullable
                      as String,
            schoolEmail: null == schoolEmail
                ? _value.schoolEmail
                : schoolEmail // ignore: cast_nullable_to_non_nullable
                      as String,
          )
          as $Val,
    );
  }
}

/// @nodoc
abstract class _$$SchoolItemImplCopyWith<$Res>
    implements $SchoolItemCopyWith<$Res> {
  factory _$$SchoolItemImplCopyWith(
    _$SchoolItemImpl value,
    $Res Function(_$SchoolItemImpl) then,
  ) = __$$SchoolItemImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call({
    String recID,
    String schoolID,
    String schoolName,
    String division,
    String district,
    String course,
    String schoolType,
    String schoolEmail,
  });
}

/// @nodoc
class __$$SchoolItemImplCopyWithImpl<$Res>
    extends _$SchoolItemCopyWithImpl<$Res, _$SchoolItemImpl>
    implements _$$SchoolItemImplCopyWith<$Res> {
  __$$SchoolItemImplCopyWithImpl(
    _$SchoolItemImpl _value,
    $Res Function(_$SchoolItemImpl) _then,
  ) : super(_value, _then);

  /// Create a copy of SchoolItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? recID = null,
    Object? schoolID = null,
    Object? schoolName = null,
    Object? division = null,
    Object? district = null,
    Object? course = null,
    Object? schoolType = null,
    Object? schoolEmail = null,
  }) {
    return _then(
      _$SchoolItemImpl(
        recID: null == recID
            ? _value.recID
            : recID // ignore: cast_nullable_to_non_nullable
                  as String,
        schoolID: null == schoolID
            ? _value.schoolID
            : schoolID // ignore: cast_nullable_to_non_nullable
                  as String,
        schoolName: null == schoolName
            ? _value.schoolName
            : schoolName // ignore: cast_nullable_to_non_nullable
                  as String,
        division: null == division
            ? _value.division
            : division // ignore: cast_nullable_to_non_nullable
                  as String,
        district: null == district
            ? _value.district
            : district // ignore: cast_nullable_to_non_nullable
                  as String,
        course: null == course
            ? _value.course
            : course // ignore: cast_nullable_to_non_nullable
                  as String,
        schoolType: null == schoolType
            ? _value.schoolType
            : schoolType // ignore: cast_nullable_to_non_nullable
                  as String,
        schoolEmail: null == schoolEmail
            ? _value.schoolEmail
            : schoolEmail // ignore: cast_nullable_to_non_nullable
                  as String,
      ),
    );
  }
}

/// @nodoc
@JsonSerializable()
class _$SchoolItemImpl implements _SchoolItem {
  const _$SchoolItemImpl({
    required this.recID,
    required this.schoolID,
    required this.schoolName,
    this.division = '',
    this.district = '',
    this.course = '',
    this.schoolType = '',
    this.schoolEmail = '',
  });

  factory _$SchoolItemImpl.fromJson(Map<String, dynamic> json) =>
      _$$SchoolItemImplFromJson(json);

  @override
  final String recID;
  @override
  final String schoolID;
  @override
  final String schoolName;
  @override
  @JsonKey()
  final String division;
  @override
  @JsonKey()
  final String district;
  @override
  @JsonKey()
  final String course;
  @override
  @JsonKey()
  final String schoolType;
  @override
  @JsonKey()
  final String schoolEmail;

  @override
  String toString() {
    return 'SchoolItem(recID: $recID, schoolID: $schoolID, schoolName: $schoolName, division: $division, district: $district, course: $course, schoolType: $schoolType, schoolEmail: $schoolEmail)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$SchoolItemImpl &&
            (identical(other.recID, recID) || other.recID == recID) &&
            (identical(other.schoolID, schoolID) ||
                other.schoolID == schoolID) &&
            (identical(other.schoolName, schoolName) ||
                other.schoolName == schoolName) &&
            (identical(other.division, division) ||
                other.division == division) &&
            (identical(other.district, district) ||
                other.district == district) &&
            (identical(other.course, course) || other.course == course) &&
            (identical(other.schoolType, schoolType) ||
                other.schoolType == schoolType) &&
            (identical(other.schoolEmail, schoolEmail) ||
                other.schoolEmail == schoolEmail));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(
    runtimeType,
    recID,
    schoolID,
    schoolName,
    division,
    district,
    course,
    schoolType,
    schoolEmail,
  );

  /// Create a copy of SchoolItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$SchoolItemImplCopyWith<_$SchoolItemImpl> get copyWith =>
      __$$SchoolItemImplCopyWithImpl<_$SchoolItemImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$SchoolItemImplToJson(this);
  }
}

abstract class _SchoolItem implements SchoolItem {
  const factory _SchoolItem({
    required final String recID,
    required final String schoolID,
    required final String schoolName,
    final String division,
    final String district,
    final String course,
    final String schoolType,
    final String schoolEmail,
  }) = _$SchoolItemImpl;

  factory _SchoolItem.fromJson(Map<String, dynamic> json) =
      _$SchoolItemImpl.fromJson;

  @override
  String get recID;
  @override
  String get schoolID;
  @override
  String get schoolName;
  @override
  String get division;
  @override
  String get district;
  @override
  String get course;
  @override
  String get schoolType;
  @override
  String get schoolEmail;

  /// Create a copy of SchoolItem
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$SchoolItemImplCopyWith<_$SchoolItemImpl> get copyWith =>
      throw _privateConstructorUsedError;
}
