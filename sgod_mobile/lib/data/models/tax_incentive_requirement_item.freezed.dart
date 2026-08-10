// coverage:ignore-file
// GENERATED CODE - DO NOT MODIFY BY HAND
// ignore_for_file: type=lint
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'tax_incentive_requirement_item.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

T _$identity<T>(T value) => value;

final _privateConstructorUsedError = UnsupportedError(
  'It seems like you constructed your class using `MyClass._()`. This constructor is only meant to be used by freezed and you are not supposed to need it nor use it.\nPlease check the documentation here for more information: https://github.com/rrousselGit/freezed#adding-getters-and-methods-to-our-models',
);

TaxIncentiveRequirementItem _$TaxIncentiveRequirementItemFromJson(
  Map<String, dynamic> json,
) {
  return _TaxIncentiveRequirementItem.fromJson(json);
}

/// @nodoc
mixin _$TaxIncentiveRequirementItem {
  String get id => throw _privateConstructorUsedError;
  String get donationId => throw _privateConstructorUsedError;
  String get requirement => throw _privateConstructorUsedError;
  String get status => throw _privateConstructorUsedError;
  String get remarks => throw _privateConstructorUsedError;

  /// Serializes this TaxIncentiveRequirementItem to a JSON map.
  Map<String, dynamic> toJson() => throw _privateConstructorUsedError;

  /// Create a copy of TaxIncentiveRequirementItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  $TaxIncentiveRequirementItemCopyWith<TaxIncentiveRequirementItem>
  get copyWith => throw _privateConstructorUsedError;
}

/// @nodoc
abstract class $TaxIncentiveRequirementItemCopyWith<$Res> {
  factory $TaxIncentiveRequirementItemCopyWith(
    TaxIncentiveRequirementItem value,
    $Res Function(TaxIncentiveRequirementItem) then,
  ) =
      _$TaxIncentiveRequirementItemCopyWithImpl<
        $Res,
        TaxIncentiveRequirementItem
      >;
  @useResult
  $Res call({
    String id,
    String donationId,
    String requirement,
    String status,
    String remarks,
  });
}

/// @nodoc
class _$TaxIncentiveRequirementItemCopyWithImpl<
  $Res,
  $Val extends TaxIncentiveRequirementItem
>
    implements $TaxIncentiveRequirementItemCopyWith<$Res> {
  _$TaxIncentiveRequirementItemCopyWithImpl(this._value, this._then);

  // ignore: unused_field
  final $Val _value;
  // ignore: unused_field
  final $Res Function($Val) _then;

  /// Create a copy of TaxIncentiveRequirementItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? donationId = null,
    Object? requirement = null,
    Object? status = null,
    Object? remarks = null,
  }) {
    return _then(
      _value.copyWith(
            id: null == id
                ? _value.id
                : id // ignore: cast_nullable_to_non_nullable
                      as String,
            donationId: null == donationId
                ? _value.donationId
                : donationId // ignore: cast_nullable_to_non_nullable
                      as String,
            requirement: null == requirement
                ? _value.requirement
                : requirement // ignore: cast_nullable_to_non_nullable
                      as String,
            status: null == status
                ? _value.status
                : status // ignore: cast_nullable_to_non_nullable
                      as String,
            remarks: null == remarks
                ? _value.remarks
                : remarks // ignore: cast_nullable_to_non_nullable
                      as String,
          )
          as $Val,
    );
  }
}

/// @nodoc
abstract class _$$TaxIncentiveRequirementItemImplCopyWith<$Res>
    implements $TaxIncentiveRequirementItemCopyWith<$Res> {
  factory _$$TaxIncentiveRequirementItemImplCopyWith(
    _$TaxIncentiveRequirementItemImpl value,
    $Res Function(_$TaxIncentiveRequirementItemImpl) then,
  ) = __$$TaxIncentiveRequirementItemImplCopyWithImpl<$Res>;
  @override
  @useResult
  $Res call({
    String id,
    String donationId,
    String requirement,
    String status,
    String remarks,
  });
}

/// @nodoc
class __$$TaxIncentiveRequirementItemImplCopyWithImpl<$Res>
    extends
        _$TaxIncentiveRequirementItemCopyWithImpl<
          $Res,
          _$TaxIncentiveRequirementItemImpl
        >
    implements _$$TaxIncentiveRequirementItemImplCopyWith<$Res> {
  __$$TaxIncentiveRequirementItemImplCopyWithImpl(
    _$TaxIncentiveRequirementItemImpl _value,
    $Res Function(_$TaxIncentiveRequirementItemImpl) _then,
  ) : super(_value, _then);

  /// Create a copy of TaxIncentiveRequirementItem
  /// with the given fields replaced by the non-null parameter values.
  @pragma('vm:prefer-inline')
  @override
  $Res call({
    Object? id = null,
    Object? donationId = null,
    Object? requirement = null,
    Object? status = null,
    Object? remarks = null,
  }) {
    return _then(
      _$TaxIncentiveRequirementItemImpl(
        id: null == id
            ? _value.id
            : id // ignore: cast_nullable_to_non_nullable
                  as String,
        donationId: null == donationId
            ? _value.donationId
            : donationId // ignore: cast_nullable_to_non_nullable
                  as String,
        requirement: null == requirement
            ? _value.requirement
            : requirement // ignore: cast_nullable_to_non_nullable
                  as String,
        status: null == status
            ? _value.status
            : status // ignore: cast_nullable_to_non_nullable
                  as String,
        remarks: null == remarks
            ? _value.remarks
            : remarks // ignore: cast_nullable_to_non_nullable
                  as String,
      ),
    );
  }
}

/// @nodoc
@JsonSerializable()
class _$TaxIncentiveRequirementItemImpl
    implements _TaxIncentiveRequirementItem {
  const _$TaxIncentiveRequirementItemImpl({
    required this.id,
    this.donationId = '',
    this.requirement = '',
    this.status = 'Pending',
    this.remarks = '',
  });

  factory _$TaxIncentiveRequirementItemImpl.fromJson(
    Map<String, dynamic> json,
  ) => _$$TaxIncentiveRequirementItemImplFromJson(json);

  @override
  final String id;
  @override
  @JsonKey()
  final String donationId;
  @override
  @JsonKey()
  final String requirement;
  @override
  @JsonKey()
  final String status;
  @override
  @JsonKey()
  final String remarks;

  @override
  String toString() {
    return 'TaxIncentiveRequirementItem(id: $id, donationId: $donationId, requirement: $requirement, status: $status, remarks: $remarks)';
  }

  @override
  bool operator ==(Object other) {
    return identical(this, other) ||
        (other.runtimeType == runtimeType &&
            other is _$TaxIncentiveRequirementItemImpl &&
            (identical(other.id, id) || other.id == id) &&
            (identical(other.donationId, donationId) ||
                other.donationId == donationId) &&
            (identical(other.requirement, requirement) ||
                other.requirement == requirement) &&
            (identical(other.status, status) || other.status == status) &&
            (identical(other.remarks, remarks) || other.remarks == remarks));
  }

  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  int get hashCode =>
      Object.hash(runtimeType, id, donationId, requirement, status, remarks);

  /// Create a copy of TaxIncentiveRequirementItem
  /// with the given fields replaced by the non-null parameter values.
  @JsonKey(includeFromJson: false, includeToJson: false)
  @override
  @pragma('vm:prefer-inline')
  _$$TaxIncentiveRequirementItemImplCopyWith<_$TaxIncentiveRequirementItemImpl>
  get copyWith =>
      __$$TaxIncentiveRequirementItemImplCopyWithImpl<
        _$TaxIncentiveRequirementItemImpl
      >(this, _$identity);

  @override
  Map<String, dynamic> toJson() {
    return _$$TaxIncentiveRequirementItemImplToJson(this);
  }
}

abstract class _TaxIncentiveRequirementItem
    implements TaxIncentiveRequirementItem {
  const factory _TaxIncentiveRequirementItem({
    required final String id,
    final String donationId,
    final String requirement,
    final String status,
    final String remarks,
  }) = _$TaxIncentiveRequirementItemImpl;

  factory _TaxIncentiveRequirementItem.fromJson(Map<String, dynamic> json) =
      _$TaxIncentiveRequirementItemImpl.fromJson;

  @override
  String get id;
  @override
  String get donationId;
  @override
  String get requirement;
  @override
  String get status;
  @override
  String get remarks;

  /// Create a copy of TaxIncentiveRequirementItem
  /// with the given fields replaced by the non-null parameter values.
  @override
  @JsonKey(includeFromJson: false, includeToJson: false)
  _$$TaxIncentiveRequirementItemImplCopyWith<_$TaxIncentiveRequirementItemImpl>
  get copyWith => throw _privateConstructorUsedError;
}
