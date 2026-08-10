// coverage:ignore-file
// GENERATED CODE - DO NOT MODIFY BY HAND
// ignore_for_file: type=lint
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'memo_item.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

T _$identity<T>(T value) => value;

final _privateConstructorUsedError = UnsupportedError(
  'It seems like you constructed your class using `MyClass._()`. This constructor is only meant to be used by freezed and you are not supposed to need it nor use it.\nPlease check the documentation here for more information: https://github.com/rrousselGit/freezed#adding-getters-and-methods-to-our-models',
);

MemoItem _$MemoItemFromJson(Map<String, dynamic> json) {
  return _MemoItem.fromJson(json);
}

/// @nodoc
mixin _$MemoItem {
  String get id => throw _privateConstructorUsedError;
  String get title => throw _privateConstructorUsedError;
  String get memoNo => throw _privateConstructorUsedError;
  @JsonKey(name: 'added_by')
  String get addedBy => throw _privateConstructorUsedError;
  @JsonKey(name: 'sec_group')
  String get secGroup => throw _privateConstructorUsedError;

  /// Serializes this MemoItem to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of MemoItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $MemoItemCopyWith<MemoItem> get copyWith =>
      throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $MemoItemCopyWith<$Res> {
  factory $MemoItemCopyWith(MemoItem value, $Res Function(MemoItem) then) =
      _$MemoItemCopyWithImpl<$Res, MemoItem>;
  @useResult
  $Res call({
    String id,
    String title,
    String memoNo,
    @JsonKey(name: 'added_by') String addedBy,
    @JsonKey(name: 'sec_group') String secGroup,
  });
}

/// @nodoc
class _$MemoItemCopyWithImpl<$Res, $Val extends MemoItem>
    implements $MemoItemCopyWith<$Res> {
  _$MemoItemCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of MemoItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? title = null,
    Object? memoNo = null,
    Object? addedBy = null,
    Object? secGroup = null,
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
            secGroup: null == secGroup
                ? _value.secGroup
                : secGroup // ignore: cast_nullable_to_non_nullable
                      as String,
          )
          as $Val,
    );
  }
}

/// @nodoc
abstract class _$$MemoItemImplCopyWith<$Res>
    implements $MemoItemCopyWith<$Res> {
  factory _$$MemoItemImplCopyWith(
    _$MemoItemImpl value,
    $Res Function(_$MemoItemImpl) then,
  ) = __$$MemoItemImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call({
    String id,
    String title,
    String memoNo,
    @JsonKey(name: 'added_by') String addedBy,
    @JsonKey(name: 'sec_group') String secGroup,
  });
}

/// @nodoc
class __$$MemoItemImplCopyWithImpl<$Res>
    extends _$MemoItemCopyWithImpl<$Res, _$MemoItemImpl>
    implements _$$MemoItemImplCopyWith<$Res> {
  __$$MemoItemImplCopyWithImpl(
    _$MemoItemImpl _value,
    $Res Function(_$MemoItemImpl) _then,
  ) : super(_value, _then);

  /// Create a copy of MemoItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? title = null,
    Object? memoNo = null,
    Object? addedBy = null,
    Object? secGroup = null,
  }) {
    return _then(
      _$MemoItemImpl(
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
        secGroup: null == secGroup
            ? _value.secGroup
            : secGroup // ignore: cast_nullable_to_non_nullable
                  as String,
      ),
    );
  }
}

/// @nodoc
@JsonSerializable()
class _$MemoItemImpl implements _MemoItem {
  const _$MemoItemImpl({
    required this.id,
    this.title = '',
    this.memoNo = '',
    @JsonKey(name: 'added_by') this.addedBy = '',
    @JsonKey(name: 'sec_group') this.secGroup = '',
  });

  factory _$MemoItemImpl.fromJson(Map<String, dynamic> json) =>
      _$$MemoItemImplFromJson(json);

  @override
  final String id;
  @override
  @JsonKey()
  final String title;
  @override
  @JsonKey()
  final String memoNo;
  @override
  @JsonKey(name: 'added_by')
  final String addedBy;
  @override
  @JsonKey(name: 'sec_group')
  final String secGroup;

  @override
  String toString() {
    return 'MemoItem(id: $id, title: $title, memoNo: $memoNo, addedBy: $addedBy, secGroup: $secGroup)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$MemoItemImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.title, title) || other.title == title) &&
            (identical(other.memoNo, memoNo) || other.memoNo == memoNo) &&
            (identical(other.addedBy, addedBy) || other.addedBy == addedBy) &&
            (identical(other.secGroup, secGroup) ||
                other.secGroup == secGroup));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode =>
      Object.hash(runtimeType, id, title, memoNo, addedBy, secGroup);

  /// Create a copy of MemoItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$MemoItemImplCopyWith<_$MemoItemImpl> get copyWith =>
      __$$MemoItemImplCopyWithImpl<_$MemoItemImpl>(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$MemoItemImplToJson(this);
  }
}

abstract class _MemoItem implements MemoItem {
  const factory _MemoItem({
    required final String id,
    final String title,
    final String memoNo,
    @JsonKey(name: 'added_by') final String addedBy,
    @JsonKey(name: 'sec_group') final String secGroup,
  }) = _$MemoItemImpl;

  factory _MemoItem.fromJson(Map<String, dynamic> json) =
      _$MemoItemImpl.fromJson;

  @override
  String get id;
  @override
  String get title;
  @override
  String get memoNo;
  @override
  @JsonKey(name: 'added_by')
  String get addedBy;
  @override
  @JsonKey(name: 'sec_group')
  String get secGroup;

  /// Create a copy of MemoItem
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$MemoItemImplCopyWith<_$MemoItemImpl> get copyWith =>
      throw _privateConstructorUsedError;
}
