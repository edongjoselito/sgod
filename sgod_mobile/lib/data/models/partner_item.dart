import 'package:freezed_annotation/freezed_annotation.dart';

part 'partner_item.freezed.dart';
part 'partner_item.g.dart';

/// A partner record from `api/partners_index` (table: brigada_partners).
@freezed
class PartnerItem with _$PartnerItem {
  const factory PartnerItem({
    required String id,
    @Default('') String name,
    @Default('') String address,
    @Default('') String contactPerson,
    @Default('') String contact,
    @Default('') String generalType,
    @Default('') String specificType,
    @Default('') String file,
  }) = _PartnerItem;

  factory PartnerItem.fromJson(Map<String, dynamic> json) =>
      _$PartnerItemFromJson(json);
}
