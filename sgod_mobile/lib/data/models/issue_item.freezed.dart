// coverage:ignore-file
// GENERATED CODE - DO NOT MODIFY BY HAND
// ignore_for_file: type=lint
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'issue_item.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

T _$identity<T>(T value) => value;

final _privateConstructorUsedError = UnsupportedError(
  'It seems like you constructed your class using `MyClass._()`. This constructor is only meant to be used by freezed and you are not supposed to need it nor use it.\nPlease check the documentation here for more information: https://github.com/rrousselGit/freezed#adding-getters-and-methods-to-our-models',
);

IssueItem _$IssueItemFromJson(Map<String, dynamic> json) {
  return _IssueItem.fromJson(json);
}

/// @nodoc
mixin _$IssueItem {
  String get id => throw _privateConstructorUsedError;
  String get section => throw _privateConstructorUsedError;
  String get secGroup => throw _privateConstructorUsedError;
  String get username => throw _privateConstructorUsedError;
  String get title => throw _privateConstructorUsedError;
  String get description => throw _privateConstructorUsedError;
  String get priority => throw _privateConstructorUsedError;
  String get status => throw _privateConstructorUsedError;
  String get year => throw _privateConstructorUsedError;
  @JsonKey(name: 'created_at')
  String get createdAt => throw _privateConstructorUsedError;

  /// Serializes this IssueItem to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of IssueItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $IssueItemCopyWith<IssueItem> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $IssueItemCopyWith<$Res> {
  factory $IssueItemCopyWith(IssueItem value, $Res Function(IssueItem) then) =
      _$IssueItemCopyWithImpl<$Res, IssueItem>;
  @useResult
  $Res call({
    String id,
    String section,
    String secGroup,
    String username,
    String title,
    String description,
    String priority,
    String status,
    String year,
    @JsonKey(name: 'created_at') String createdAt,
  });
}

/// @nodoc
class _$IssueItemCopyWithImpl<$Res, $Val extends IssueItem>
    implements $IssueItemCopyWith<$Res> {
  _$IssueItemCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of IssueItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? section = null,
    Object? secGroup = null,
    Object? username = null,
    Object? title = null,
    Object? description = null,
    Object? priority = null,
    Object? status = null,
    Object? year = null,
    Object? createdAt = null,
  }) {
    return _then(
      _value.copyWith(
            id: null == id
                ? _value.id
                : id // ignore: cast_nullable_to_non_nullable
                      as String,
            section: null == section
                ? _value.section
                : section // ignore: cast_nullable_to_non_nullable
                      as String,
            secGroup: null == secGroup
                ? _value.secGroup
                : secGroup // ignore: cast_nullable_to_non_nullable
                      as String,
            username: null == username
                ? _value.username
                : username // ignore: cast_nullable_to_non_nullable
                      as String,
            title: null == title
                ? _value.title
                : title // ignore: cast_nullable_to_non_nullable
                      as String,
            description: null == description
                ? _value.description
                : description // ignore: cast_nullable_to_non_nullable
                      as String,
            priority: null == priority
                ? _value.priority
                : priority // ignore: cast_nullable_to_non_nullable
                      as String,
            status: null == status
                ? _value.status
                : status // ignore: cast_nullable_to_non_nullable
                      as String,
            year: null == year
                ? _value.year
                : year // ignore: cast_nullable_to_non_nullable
                      as String,
            createdAt: null == createdAt
                ? _value.createdAt
                : createdAt // ignore: cast_nullable_to_non_nullable
                      as String,
          )
          as $Val,
    );
  }
}

/// @nodoc
abstract class _$$IssueItemImplCopyWith<$Res>
    implements $IssueItemCopyWith<$Res> {
  factory _$$IssueItemImplCopyWith(
    _$IssueItemImpl value,
    $Res Function(_$IssueItemImpl) then,
  ) = __$$IssueItemImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call({
    String id,
    String section,
    String secGroup,
    String username,
    String title,
    String description,
    String priority,
    String status,
    String year,
    @JsonKey(name: 'created_at') String createdAt,
  });
}

/// @nodoc
class __$$IssueItemImplCopyWithImpl<$Res>
    extends _$IssueItemCopyWithImpl<$Res, _$IssueItemImpl>
    implements _$$IssueItemImplCopyWith<$Res> {
  __$$IssueItemImplCopyWithImpl(
    _$IssueItemImpl _value,
    $Res Function(_$IssueItemImpl) _then,
  ) : super(_value, _then);

  /// Create a copy of IssueItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? section = null,
    Object? secGroup = null,
    Object? username = null,
    Object? title = null,
    Object? description = null,
    Object? priority = null,
    Object? status = null,
    Object? year = null,
    Object? createdAt = null,
  }) {
    return _then(
      _$IssueItemImpl(
        id: null == id
            ? _value.id
            : id // ignore: cast_nullable_to_non_nullable
                  as String,
        section: null == section
            ? _value.section
            : section // ignore: cast_nullable_to_non_nullable
                  as String,
        secGroup: null == secGroup
            ? _value.secGroup
            : secGroup // ignore: cast_nullable_to_non_nullable
                  as String,
        username: null == username
            ? _value.username
            : username // ignore: cast_nullable_to_non_nullable
                  as String,
        title: null == title
            ? _value.title
            : title // ignore: cast_nullable_to_non_nullable
                  as String,
        description: null == description
            ? _value.description
            : description // ignore: cast_nullable_to_non_nullable
                  as String,
        priority: null == priority
            ? _value.priority
            : priority // ignore: cast_nullable_to_non_nullable
                  as String,
        status: null == status
            ? _value.status
            : status // ignore: cast_nullable_to_non_nullable
                  as String,
        year: null == year
            ? _value.year
            : year // ignore: cast_nullable_to_non_nullable
                  as String,
        createdAt: null == createdAt
            ? _value.createdAt
            : createdAt // ignore: cast_nullable_to_non_nullable
                  as String,
      ),
    );
  }
}

/// @nodoc
@JsonSerializable()
class _$IssueItemImpl extends _IssueItem {
  const _$IssueItemImpl({
    required this.id,
    this.section = '',
    this.secGroup = '',
    this.username = '',
    required this.title,
    this.description = '',
    this.priority = 'Normal',
    this.status = 'Open',
    this.year = '',
    @JsonKey(name: 'created_at') this.createdAt = '',
  }) : super._();

  factory _$IssueItemImpl.fromJson(Map<String, dynamic> json) =>
      _$$IssueItemImplFromJson(json);

  @override
  final String id;
  @override
  @JsonKey()
  final String section;
  @override
  @JsonKey()
  final String secGroup;
  @override
  @JsonKey()
  final String username;
  @override
  final String title;
  @override
  @JsonKey()
  final String description;
  @override
  @JsonKey()
  final String priority;
  @override
  @JsonKey()
  final String status;
  @override
  @JsonKey()
  final String year;
  @override
  @JsonKey(name: 'created_at')
  final String createdAt;

  @override
  String toString() {
    return 'IssueItem(id: $id, section: $section, secGroup: $secGroup, username: $username, title: $title, description: $description, priority: $priority, status: $status, year: $year, createdAt: $createdAt)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$IssueItemImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.section, section) || other.section == section) &&
            (identical(other.secGroup, secGroup) ||
                other.secGroup == secGroup) &&
            (identical(other.username, username) ||
                other.username == username) &&
            (identical(other.title, title) || other.title == title) &&
            (identical(other.description, description) ||
                other.description == description) &&
            (identical(other.priority, priority) ||
                other.priority == priority) &&
            (identical(other.status, status) || other.status == status) &&
            (identical(other.year, year) || other.year == year) &&
            (identical(other.createdAt, createdAt) ||
                other.createdAt == createdAt));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode => Object.hash(
    runtimeType,
    id,
    section,
    secGroup,
    username,
    title,
    description,
    priority,
    status,
    year,
    createdAt,
  );

  /// Create a copy of IssueItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$IssueItemImplCopyWith<_$IssueItemImpl> get copyWith =>
      __$$IssueItemImplCopyWithImpl<_$IssueItemImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$IssueItemImplToJson(this);
  }
}

abstract class _IssueItem extends IssueItem {
  const factory _IssueItem({
    required final String id,
    final String section,
    final String secGroup,
    final String username,
    required final String title,
    final String description,
    final String priority,
    final String status,
    final String year,
    @JsonKey(name: 'created_at') final String createdAt,
  }) = _$IssueItemImpl;
  const _IssueItem._() : super._();

  factory _IssueItem.fromJson(Map<String, dynamic> json) =
      _$IssueItemImpl.fromJson;

  @override
  String get id;
  @override
  String get section;
  @override
  String get secGroup;
  @override
  String get username;
  @override
  String get title;
  @override
  String get description;
  @override
  String get priority;
  @override
  String get status;
  @override
  String get year;
  @override
  @JsonKey(name: 'created_at')
  String get createdAt;

  /// Create a copy of IssueItem
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$IssueItemImplCopyWith<_$IssueItemImpl> get copyWith =>
      throw _privateConstructorUsedError;
}
