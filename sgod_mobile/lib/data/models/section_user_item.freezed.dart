// coverage:ignore-file
// GENERATED CODE - DO NOT MODIFY BY HAND
// ignore_for_file: type=lint
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'section_user_item.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

T _$identity<T>(T value) => value;

final _privateConstructorUsedError = UnsupportedError(
  'It seems like you constructed your class using `MyClass._()`. This constructor is only meant to be used by freezed and you are not supposed to need it nor use it.\nPlease check the documentation here for more information: https://github.com/rrousselGit/freezed#adding-getters-and-methods-to-our-models',
);

SectionUserItem _$SectionUserItemFromJson(Map<String, dynamic> json) {
  return _SectionUserItem.fromJson(json);
}

/// @nodoc
mixin _$SectionUserItem {
  String get username => throw _privateConstructorUsedError;
  @JsonKey(name: 'fName')
  String get fName => throw _privateConstructorUsedError;
  @JsonKey(name: 'lName')
  String get lName => throw _privateConstructorUsedError;
  String get section => throw _privateConstructorUsedError;
  String get secGroup => throw _privateConstructorUsedError;
  @JsonKey(name: 'acctStat')
  String get acctStat => throw _privateConstructorUsedError;

  /// Serializes this SectionUserItem to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of SectionUserItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $SectionUserItemCopyWith<SectionUserItem> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $SectionUserItemCopyWith<$Res> {
  factory $SectionUserItemCopyWith(
    SectionUserItem value,
    $Res Function(SectionUserItem) then,
  ) = _$SectionUserItemCopyWithImpl<$Res, SectionUserItem>;
  @useResult
  $Res call({
    String username,
    @JsonKey(name: 'fName') String fName,
    @JsonKey(name: 'lName') String lName,
    String section,
    String secGroup,
    @JsonKey(name: 'acctStat') String acctStat,
  });
}

/// @nodoc
class _$SectionUserItemCopyWithImpl<$Res, $Val extends SectionUserItem>
    implements $SectionUserItemCopyWith<$Res> {
  _$SectionUserItemCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of SectionUserItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? username = null,
    Object? fName = null,
    Object? lName = null,
    Object? section = null,
    Object? secGroup = null,
    Object? acctStat = null,
  }) {
    return _then(
      _value.copyWith(
            username: null == username
                ? _value.username
                : username // ignore: cast_nullable_to_non_nullable
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
            secGroup: null == secGroup
                ? _value.secGroup
                : secGroup // ignore: cast_nullable_to_non_nullable
                      as String,
            acctStat: null == acctStat
                ? _value.acctStat
                : acctStat // ignore: cast_nullable_to_non_nullable
                      as String,
          )
          as $Val,
    );
  }
}

/// @nodoc
abstract class _$$SectionUserItemImplCopyWith<$Res>
    implements $SectionUserItemCopyWith<$Res> {
  factory _$$SectionUserItemImplCopyWith(
    _$SectionUserItemImpl value,
    $Res Function(_$SectionUserItemImpl) then,
  ) = __$$SectionUserItemImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call({
    String username,
    @JsonKey(name: 'fName') String fName,
    @JsonKey(name: 'lName') String lName,
    String section,
    String secGroup,
    @JsonKey(name: 'acctStat') String acctStat,
  });
}

/// @nodoc
class __$$SectionUserItemImplCopyWithImpl<$Res>
    extends _$SectionUserItemCopyWithImpl<$Res, _$SectionUserItemImpl>
    implements _$$SectionUserItemImplCopyWith<$Res> {
  __$$SectionUserItemImplCopyWithImpl(
    _$SectionUserItemImpl _value,
    $Res Function(_$SectionUserItemImpl) _then,
  ) : super(_value, _then);

  /// Create a copy of SectionUserItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? username = null,
    Object? fName = null,
    Object? lName = null,
    Object? section = null,
    Object? secGroup = null,
    Object? acctStat = null,
  }) {
    return _then(
      _$SectionUserItemImpl(
        username: null == username
            ? _value.username
            : username // ignore: cast_nullable_to_non_nullable
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
        secGroup: null == secGroup
            ? _value.secGroup
            : secGroup // ignore: cast_nullable_to_non_nullable
                  as String,
        acctStat: null == acctStat
            ? _value.acctStat
            : acctStat // ignore: cast_nullable_to_non_nullable
                  as String,
      ),
    );
  }
}

/// @nodoc
@JsonSerializable()
class _$SectionUserItemImpl extends _SectionUserItem {
  const _$SectionUserItemImpl({
    required this.username,
    @JsonKey(name: 'fName') this.fName = '',
    @JsonKey(name: 'lName') this.lName = '',
    this.section = '',
    this.secGroup = '',
    @JsonKey(name: 'acctStat') this.acctStat = 'Active',
  }) : super._();

  factory _$SectionUserItemImpl.fromJson(Map<String, dynamic> json) =>
      _$$SectionUserItemImplFromJson(json);

  @override
  final String username;
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
  final String secGroup;
  @override
  @JsonKey(name: 'acctStat')
  final String acctStat;

  @override
  String toString() {
    return 'SectionUserItem(username: $username, fName: $fName, lName: $lName, section: $section, secGroup: $secGroup, acctStat: $acctStat)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$SectionUserItemImpl &&
            (identical(other.username, username) ||
                other.username == username) &&
            (identical(other.fName, fName) || other.fName == fName) &&
            (identical(other.lName, lName) || other.lName == lName) &&
            (identical(other.section, section) || other.section == section) &&
            (identical(other.secGroup, secGroup) ||
                other.secGroup == secGroup) &&
            (identical(other.acctStat, acctStat) ||
                other.acctStat == acctStat));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(
    runtimeType,
    username,
    fName,
    lName,
    section,
    secGroup,
    acctStat,
  );

  /// Create a copy of SectionUserItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$SectionUserItemImplCopyWith<_$SectionUserItemImpl> get copyWith =>
      __$$SectionUserItemImplCopyWithImpl<_$SectionUserItemImpl>(
        this,
        _$identity,
      );

  @override
  Map<String, dynamic> toJson() {
    return _$$SectionUserItemImplToJson(this);
  }
}

abstract class _SectionUserItem extends SectionUserItem {
  const factory _SectionUserItem({
    required final String username,
    @JsonKey(name: 'fName') final String fName,
    @JsonKey(name: 'lName') final String lName,
    final String section,
    final String secGroup,
    @JsonKey(name: 'acctStat') final String acctStat,
  }) = _$SectionUserItemImpl;
  const _SectionUserItem._() : super._();

  factory _SectionUserItem.fromJson(Map<String, dynamic> json) =
      _$SectionUserItemImpl.fromJson;

  @override
  String get username;
  @override
  @JsonKey(name: 'fName')
  String get fName;
  @override
  @JsonKey(name: 'lName')
  String get lName;
  @override
  String get section;
  @override
  String get secGroup;
  @override
  @JsonKey(name: 'acctStat')
  String get acctStat;

  /// Create a copy of SectionUserItem
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$SectionUserItemImplCopyWith<_$SectionUserItemImpl> get copyWith =>
      throw _privateConstructorUsedError;
}
