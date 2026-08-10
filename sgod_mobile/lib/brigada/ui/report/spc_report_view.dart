import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../core/theme/app_colors.dart';
import '../../brigada_module.dart';
import '../../data/brigada_models.dart';
import '../../data/brigada_repository.dart';
import '../brigada_screen.dart';
import '../brigada_ui.dart';
import 'spc_responses_view.dart';

/// SPC Report — division-wide preparedness tallies.
///
/// Mirrors `Brigada/spc_admin_report`. The web shows an accordion of tables
/// with three count columns; here each item is a row of three tappable
/// counters that drill into the schools behind the number.
class SpcReportView extends StatefulWidget {
  const SpcReportView({super.key, this.sy});

  /// School year to tally. When omitted the screen resolves the division's
  /// current SY itself, so it can be opened straight from the sidebar.
  final String? sy;

  @override
  State<SpcReportView> createState() => _SpcReportViewState();
}

class _SpcReportViewState extends State<SpcReportView> {
  late String _sy = widget.sy ?? BrigadaMeta.fallback().currentSy;

  late final BrigadaLoader<SpcReport> _loader = BrigadaLoader(
    request: BrigadaRepository.reportRequest(_sy),
    parse: SpcReport.fromJson,
  );

  final Set<int> _expanded = <int>{};

  @override
  void initState() {
    super.initState();
    if (widget.sy == null) _resolveSchoolYear();
  }

  Future<void> _resolveSchoolYear() async {
    final resolved = await BrigadaModule.resolveSchoolYear();
    if (!mounted || resolved == _sy) return;
    setState(() => _sy = resolved);
    await _loader.retarget(BrigadaRepository.reportRequest(resolved));
  }

  @override
  void dispose() {
    _loader.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return BrigadaScreen<SpcReport>(
      title: 'SPC Report',
      previousPageTitle: 'Brigada',
      loader: _loader,
      emptyCheck: (data) => data.categories.isEmpty,
      emptyIcon: PhosphorIconsRegular.chartBar,
      emptyTitle: 'No checklist categories',
      builder: (context, data) => Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          _overviewCard(data),
          const BrigadaSectionHeader('Categories'),
          for (var i = 0; i < data.categories.length; i++) ...[
            if (i > 0) const SizedBox(height: 10),
            _categoryCard(data.categories[i], i + 1, data.sy),
          ],
        ],
      ),
    );
  }

  Widget _overviewCard(SpcReport data) {
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
                      formatCount(data.submissionCount),
                      style: const TextStyle(
                        fontSize: 32,
                        fontWeight: FontWeight.w700,
                        letterSpacing: -1,
                        color: AppColors.label,
                      ),
                    ),
                    Text(
                      'checklist${data.submissionCount == 1 ? '' : 's'} submitted',
                      style: const TextStyle(
                        fontSize: 13.5,
                        color: AppColors.secondaryLabel,
                      ),
                    ),
                  ],
                ),
              ),
              BrigadaPill(label: 'SY ${data.sy}', color: BrigadaTokens.accent),
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
          const SizedBox(height: 12),
          Text(
            'Across ${formatCount(data.total)} answered checklist item'
            '${data.total == 1 ? '' : 's'}',
            style: const TextStyle(
              fontSize: 12.5,
              color: AppColors.tertiaryLabel,
            ),
          ),
        ],
      ),
    );
  }

  Widget _categoryCard(SpcReportCategory category, int number, String sy) {
    final expanded = _expanded.contains(category.id);

    return BrigadaCard(
      padding: EdgeInsets.zero,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          CupertinoButton(
            padding: EdgeInsets.zero,
            onPressed: () => setState(() {
              if (expanded) {
                _expanded.remove(category.id);
              } else {
                _expanded.add(category.id);
              }
            }),
            child: Padding(
              padding: const EdgeInsets.fromLTRB(14, 13, 12, 13),
              child: Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Container(
                    width: 24,
                    height: 24,
                    margin: const EdgeInsets.only(top: 1, right: 11),
                    decoration: BoxDecoration(
                      color: BrigadaTokens.accent.withValues(alpha: 0.12),
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: Center(
                      child: Text(
                        '$number',
                        style: const TextStyle(
                          fontSize: 12,
                          fontWeight: FontWeight.w700,
                          color: BrigadaTokens.accent,
                        ),
                      ),
                    ),
                  ),
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
                            BrigadaSegment(
                                category.fully, BrigadaTokens.fully, 'Fully'),
                            BrigadaSegment(category.partially,
                                BrigadaTokens.partially, 'Partially'),
                            BrigadaSegment(category.notPrepared,
                                BrigadaTokens.notPrepared, 'Not prepared'),
                          ],
                        ),
                        const SizedBox(height: 7),
                        Text(
                          '${category.items.length} item'
                          '${category.items.length == 1 ? '' : 's'} · '
                          '${formatCount(category.total)} response'
                          '${category.total == 1 ? '' : 's'}',
                          style: const TextStyle(
                            fontSize: 12.5,
                            color: AppColors.secondaryLabel,
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(width: 8),
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
              _itemRow(category, item, sy),
            ],
        ],
      ),
    );
  }

  Widget _itemRow(SpcReportCategory category, SpcReportItem item, String sy) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(14, 12, 14, 13),
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
          const SizedBox(height: 10),
          Row(
            children: [
              Expanded(
                child: _counter(
                  count: item.fully,
                  label: 'Fully',
                  color: BrigadaTokens.fully,
                  onTap: () => _openResponses(item, 1, category.name, sy),
                ),
              ),
              const SizedBox(width: 8),
              Expanded(
                child: _counter(
                  count: item.partially,
                  label: 'Partially',
                  color: BrigadaTokens.partially,
                  onTap: () => _openResponses(item, 2, category.name, sy),
                ),
              ),
              const SizedBox(width: 8),
              Expanded(
                child: _counter(
                  count: item.notPrepared,
                  label: 'Not prep.',
                  color: BrigadaTokens.notPrepared,
                  onTap: () => _openResponses(item, 3, category.name, sy),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _counter({
    required int count,
    required String label,
    required Color color,
    required VoidCallback onTap,
  }) {
    final enabled = count > 0;
    return CupertinoButton(
      padding: EdgeInsets.zero,
      minimumSize: Size.zero,
      onPressed: enabled ? onTap : null,
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 8),
        decoration: BoxDecoration(
          color: color.withValues(alpha: enabled ? 0.12 : 0.055),
          borderRadius: BorderRadius.circular(10),
        ),
        child: Column(
          children: [
            Text(
              formatCount(count),
              style: TextStyle(
                fontSize: 17,
                fontWeight: FontWeight.w700,
                color: enabled ? color : color.withValues(alpha: 0.45),
              ),
            ),
            const SizedBox(height: 1),
            Text(
              label,
              style: TextStyle(
                fontSize: 11,
                fontWeight: FontWeight.w500,
                color: enabled
                    ? color.withValues(alpha: 0.85)
                    : color.withValues(alpha: 0.4),
              ),
            ),
          ],
        ),
      ),
    );
  }

  void _openResponses(
    SpcReportItem item,
    int value,
    String categoryName,
    String sy,
  ) {
    Navigator.of(context).push(
      CupertinoPageRoute(
        builder: (_) => SpcResponsesView(
          sy: sy,
          itemId: item.id,
          value: value,
          itemDescription: item.description,
          categoryName: categoryName,
        ),
      ),
    );
  }
}
