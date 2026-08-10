// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'partner_item.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_$PartnerItemImpl _$$PartnerItemImplFromJson(Map<String, dynamic> json) =>
    _$PartnerItemImpl(
      id: json['id'] as String,
      name: json['name'] as String? ?? '',
      address: json['address'] as String? ?? '',
      contactPerson: json['contactPerson'] as String? ?? '',
      contact: json['contact'] as String? ?? '',
      generalType: json['generalType'] as String? ?? '',
      specificType: json['specificType'] as String? ?? '',
      file: json['file'] as String? ?? '',
    );

Map<String, dynamic> _$$PartnerItemImplToJson(_$PartnerItemImpl instance) =>
    <String, dynamic>{
      'id': instance.id,
      'name': instance.name,
      'address': instance.address,
      'contactPerson': instance.contactPerson,
      'contact': instance.contact,
      'generalType': instance.generalType,
      'specificType': instance.specificType,
      'file': instance.file,
    };
