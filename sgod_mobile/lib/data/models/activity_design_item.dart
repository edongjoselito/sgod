import 'package:freezed_annotation/freezed_annotation.dart';

part 'activity_design_item.freezed.dart';
part 'activity_design_item.g.dart';

@freezed
class ActivityDesignItem with _$ActivityDesignItem {
  const factory ActivityDesignItem({
    required String id,
    required String username,
    required String title,
    @JsonKey(name: 'activity_date') required String activityDate,
    required String venue,
    @JsonKey(name: 'activity_design_no') required String activityDesignNo,
  }) = _ActivityDesignItem;

  factory ActivityDesignItem.fromJson(Map<String, dynamic> json) =>
      _$ActivityDesignItemFromJson(json);
}
