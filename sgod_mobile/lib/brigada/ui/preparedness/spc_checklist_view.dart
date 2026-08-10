import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../core/theme/app_colors.dart';
import '../../data/brigada_models.dart';
import '../../data/brigada_repository.dart';
import '../brigada_screen.dart';
import '../brigada_ui.dart';

/// One school's preparedness checklist.
///
/// The web renders this as a wide form; on mobile it is a collapsible list of
/// categories, each item showing its rating and the remark the school left.
class SpcChecklistView extends StatefulWidget {
  const SpcChecklistView({
    super.key,
    required this.schoolId,
    required this.schoolName,
    required this.sy,
  });

  final String schoolId;
  final String schoolName;
  final String sy;

  @override
  State<SpcChecklistView> createState() => _SpcChecklistViewState();
}

class _SpcChecklistViewState extends State<SpcChecklistView> {
  late final BrigadaLoader<SpcChecklist> _loader = BrigadaLoader(
    request: BrigadaRepository.checklistRequest(widget.schoolId, widget.sy),
    parse: SpcChecklist.fromJson,
  );

  /// Category ids the user has collapsed; everything starts expanded.
  final Set<int> _collapsed = <int>{};

  @override
  void dispose() {
    _loader.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return BrigadaScreen<SpcChecklist>(
      title: widget.schoolName,
      previousPageTitle: 'Schools',
      loader: _loader,
      builder: (context, data) {
        if (!data.submitted) {
          return BrigadaEmpty(
            icon: PhosphorIconsRegular.clipboardText,
            title: 'No checklist submitted',
            message:
                '${data.schoolName} has not submitted a preparedness checklist '
                'for SY ${data.sy}.',
          );
        }
        return Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            _summaryCard(data),
            for (final category in data.categories) ...[
              const SizedBox(height: 10),
              _categoryCard(category),
            ],
          ],
        );
      },
    );
  }

  Widget _summaryCard(SpcChecklist data) {
    return BrigadaCard(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      '${(data.readiness * 100).toStringAsFixed(0)}%',
                      style: const TextStyle(
                        fontSize: 32,
                        fontWeight: FontWeight.w700,
                        letterSpacing: -1,
                        color: AppColors.label,
                      ),
                    ),
                    const Text(
                      'of answers fully prepared',
                      style: TextStyle(
                        fontSize: 13.5,
                        color: AppColors.secondaryLabel,
                      ),
                    ),
                  ],
                ),
              ),
              Column(
                crossAxisAlignment: CrossAxisAlignment.end,
                children: [
                  BrigadaPill(
                    label: 'SY ${data.sy}',
                    color: BrigadaTokens.accent,
                    compact: true,
                  ),
                  const SizedBox(height: 6),
                  Text(
                    '${data.answered} of ${data.itemCount} answered',
                    style: const TextStyle(
                      fontSize: 12,
                      color: AppColors.tertiaryLabel,
                    ),
                  ),
                ],
              ),
            ],
          ),
          const SizedBox(height: 14),
          BrigadaStackedBar(
            segments: [
              BrigadaSegment(data.fully, BrigadaTokens.fully, 'Fully'),
              BrigadaSegment(
                  data.partially, BrigadaTokens.partially, 'Partially'),
              BrigadaSegment(
                  data.notPrepared, BrigadaTokens.notPrepared, 'Not prepared'),
            ],
            showLegend: true,
          ),
          if (data.district.isNotEmpty) ...[
            const SizedBox(height: 12),
            Text(
              '${data.district} · School ID ${data.schoolId}',
              style: const TextStyle(
                fontSize: 12.5,
                color: AppColors.secondaryLabel,
              ),
            ),
          ],
        ],
      ),
    );
  }

  Widget _categoryCard(SpcChecklistCategory category) {
    final expanded = !_collapsed.contains(category.id);
    final fully = category.items.where((i) => i.value == 1).length;
    final partially = category.items.where((i) => i.value == 2).length;
    final notPrepared = category.items.where((i) => i.value == 3).length;

    return BrigadaCard(
      padding: EdgeInsets.zero,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          CupertinoButton(
            padding: EdgeInsets.zero,
            onPressed: () => setState(() {
              if (expanded) {
                _collapsed.add(category.id);
              } else {
                _collapsed.remove(category.id);
              }
            }),
            child: Padding(
              padding: const EdgeInsets.fromLTRB(14, 13, 12, 13),
              child: Row(
                children: [
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          category.name,
                          style: const TextStyle(
                            fontSize: 15.5,
                            fontWeight: FontWeight.w600,
                            color: AppColors.label,
                          ),
                        ),
                        const SizedBox(height: 8),
                        BrigadaStackedBar(
                          height: 5,
                          segments: [
                            BrigadaSegment(fully, BrigadaTokens.fully, 'Fully'),
                            BrigadaSegment(
                                partially, BrigadaTokens.partially, 'Partially'),
                            BrigadaSegment(notPrepared,
                                BrigadaTokens.notPrepared, 'Not prepared'),
                          ],
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(width: 10),
                  Icon(
                    expanded
                        ? CupertinoIcons.chevron_up
                        : CupertinoIcons.chevron_down,
                    size: 14,
                    color: AppColors.tertiaryLabel,
                  ),
                ],
              ),
            ),
          ),
          if (expanded)
            for (final item in category.items) ...[
              Container(
                margin: const EdgeInsets.only(left: 14),
                height: 0.5,
                color: AppColors.separator,
              ),
              _itemRow(item),
            ],
        ],
      ),
    );
  }

  Widget _itemRow(SpcChecklistItem item) {
    final color = BrigadaTokens.forRating(item.value);
    return Padding(
      padding: const EdgeInsets.fromLTRB(14, 12, 14, 12),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: 4,
            height: 34,
            margin: const EdgeInsets.only(top: 2, right: 11),
            decoration: BoxDecoration(
              color: color,
              borderRadius: BorderRadius.circular(2),
            ),
          ),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  item.description,
                  style: const TextStyle(
                    fontSize: 14.5,
                    height: 1.3,
                    color: AppColors.label,
                  ),
                ),
                const SizedBox(height: 7),
                Wrap(
                  spacing: 8,
                  runSpacing: 6,
                  crossAxisAlignment: WrapCrossAlignment.center,
                  children: [
                    BrigadaPill(
                      label: item.label ?? BrigadaTokens.labelForRating(null),
                      color: color,
                      compact: true,
                    ),
                    if (item.remark.trim().isNotEmpty)
                      Text(
                        item.remark.trim(),
                        style: const TextStyle(
                          fontSize: 12.5,
                          fontStyle: FontStyle.italic,
                          color: AppColors.secondaryLabel,
                        ),
                      ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
