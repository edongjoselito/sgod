import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../core/theme/app_colors.dart';
import '../../data/brigada_models.dart';
import '../../data/brigada_repository.dart';
import '../brigada_screen.dart';
import '../brigada_ui.dart';

/// The contribution records behind a summary tile, partner bucket, or school.
///
/// Mirrors `Brigada/brigada_summary_v2_details`. Each record is a card rather
/// than a table row, and long lists are paged in on scroll so a month with
/// thousands of records stays smooth on a phone.
class ContributionDetailsView extends StatefulWidget {
  const ContributionDetailsView({
    super.key,
    required this.year,
    required this.month,
    required this.title,
    this.card = '',
    this.scope = '',
    this.type = '',
    this.schoolId = '',
    this.previousPageTitle = 'Summary',
  });

  final int year;
  final int month;
  final String title;
  final String card;
  final String scope;
  final String type;
  final String schoolId;
  final String previousPageTitle;

  @override
  State<ContributionDetailsView> createState() =>
      _ContributionDetailsViewState();
}

class _ContributionDetailsViewState extends State<ContributionDetailsView> {
  static const _pageSize = 40;

  late final BrigadaLoader<ContributionDetails> _loader = BrigadaLoader(
    request: BrigadaRepository.detailsRequest(
      year: widget.year,
      month: widget.month,
      card: widget.card,
      scope: widget.scope,
      type: widget.type,
      schoolId: widget.schoolId,
    ),
    parse: ContributionDetails.fromJson,
  );

  int _visibleCount = _pageSize;
  String _query = '';

  @override
  void dispose() {
    _loader.dispose();
    super.dispose();
  }

  List<ContributionRecord> _filtered(ContributionDetails data) {
    final q = _query.trim().toLowerCase();
    if (q.isEmpty) return data.records;
    return data.records
        .where((r) =>
            r.schoolName.toLowerCase().contains(q) ||
            r.partnerName.toLowerCase().contains(q) ||
            r.contribution.toLowerCase().contains(q) ||
            r.projectName.toLowerCase().contains(q))
        .toList();
  }

  @override
  Widget build(BuildContext context) {
    return BrigadaScreen<ContributionDetails>(
      title: widget.title,
      previousPageTitle: widget.previousPageTitle,
      loader: _loader,
      emptyCheck: (data) => data.records.isEmpty,
      emptyIcon: PhosphorIconsRegular.tray,
      emptyTitle: 'No records',
      emptyMessage: 'No contribution records match this selection.',
      header: (context, data) => Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          _totalsCard(data),
          if (data.records.length > 8) ...[
            const SizedBox(height: 14),
            BrigadaSearchField(
              placeholder: 'Search partner, school or item',
              onChanged: (value) => setState(() {
                _query = value;
                _visibleCount = _pageSize;
              }),
            ),
          ] else
            const SizedBox(height: 14),
        ],
      ),
      builder: (context, data) {
        final records = _filtered(data);
        if (records.isEmpty) {
          return const BrigadaEmpty(
            icon: PhosphorIconsRegular.magnifyingGlass,
            title: 'No matches',
            message: 'No record matches your search.',
          );
        }
        final shown = records.take(_visibleCount).toList();
        return Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            for (var i = 0; i < shown.length; i++) ...[
              if (i > 0) const SizedBox(height: 10),
              _recordCard(shown[i]),
            ],
            if (shown.length < records.length) ...[
              const SizedBox(height: 14),
              CupertinoButton(
                onPressed: () =>
                    setState(() => _visibleCount += _pageSize),
                child: Text(
                  'Show more · ${formatCount(records.length - shown.length)} remaining',
                  style: const TextStyle(fontSize: 15),
                ),
              ),
            ],
          ],
        );
      },
    );
  }

  Widget _totalsCard(ContributionDetails data) {
    return BrigadaCard(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            formatPeso(data.totalResources),
            style: const TextStyle(
              fontSize: 30,
              fontWeight: FontWeight.w700,
              letterSpacing: -1,
              color: AppColors.label,
            ),
          ),
          const Text(
            'total resources',
            style: TextStyle(
              fontSize: 13.5,
              color: AppColors.secondaryLabel,
            ),
          ),
          const SizedBox(height: 14),
          Row(
            children: [
              _totalChip(
                PhosphorIconsRegular.listChecks,
                formatCount(data.totalRecords),
                'records',
                BrigadaTokens.records,
              ),
              const SizedBox(width: 10),
              _totalChip(
                PhosphorIconsRegular.users,
                formatCompact(data.totalVolunteers),
                'volunteers',
                BrigadaTokens.volunteers,
              ),
              const SizedBox(width: 10),
              _totalChip(
                PhosphorIconsRegular.calendarBlank,
                formatCount(data.totalDays),
                'days',
                BrigadaTokens.days,
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _totalChip(IconData icon, String value, String label, Color color) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 9, horizontal: 8),
        decoration: BoxDecoration(
          color: color.withValues(alpha: 0.1),
          borderRadius: BorderRadius.circular(10),
        ),
        child: Column(
          children: [
            Icon(icon, size: 14, color: color),
            const SizedBox(height: 4),
            Text(
              value,
              style: TextStyle(
                fontSize: 15,
                fontWeight: FontWeight.w700,
                color: color,
              ),
            ),
            Text(
              label,
              style: const TextStyle(
                fontSize: 11,
                color: AppColors.secondaryLabel,
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _recordCard(ContributionRecord record) {
    final quantity = record.quantity > 0
        ? '${formatCount(record.quantity)}${record.unit.isEmpty ? '' : ' ${record.unit}'}'
        : '';

    return BrigadaCard(
      padding: const EdgeInsets.fromLTRB(14, 13, 14, 14),
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
                      record.partnerName.isEmpty
                          ? 'Unnamed partner'
                          : record.partnerName,
                      style: const TextStyle(
                        fontSize: 15.5,
                        fontWeight: FontWeight.w600,
                        color: AppColors.label,
                      ),
                    ),
                    const SizedBox(height: 2),
                    Text(
                      record.schoolName,
                      style: const TextStyle(
                        fontSize: 13,
                        color: AppColors.secondaryLabel,
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(width: 10),
              Column(
                crossAxisAlignment: CrossAxisAlignment.end,
                children: [
                  Text(
                    formatPeso(record.amount),
                    style: const TextStyle(
                      fontSize: 15.5,
                      fontWeight: FontWeight.w700,
                      color: BrigadaTokens.resources,
                    ),
                  ),
                  const SizedBox(height: 2),
                  Text(
                    formatShortDate(record.date),
                    style: const TextStyle(
                      fontSize: 12,
                      color: AppColors.tertiaryLabel,
                    ),
                  ),
                ],
              ),
            ],
          ),
          const SizedBox(height: 10),
          Wrap(
            spacing: 7,
            runSpacing: 6,
            children: [
              if (record.generalType.isNotEmpty)
                BrigadaPill(
                  label: record.generalType.replaceAll('_', ' '),
                  color: BrigadaTokens.records,
                  compact: true,
                ),
              if (record.contributionType.isNotEmpty)
                BrigadaPill(
                  label: record.contributionType,
                  color: BrigadaTokens.accent,
                  compact: true,
                ),
              if (record.volunteers > 0)
                BrigadaPill(
                  label: '${formatCount(record.volunteers)} beneficiaries',
                  color: BrigadaTokens.volunteers,
                  icon: PhosphorIconsRegular.users,
                  compact: true,
                ),
            ],
          ),
          if (record.contribution.isNotEmpty ||
              record.projectName.isNotEmpty ||
              quantity.isNotEmpty) ...[
            const SizedBox(height: 10),
            Container(height: 0.5, color: AppColors.separator),
            const SizedBox(height: 8),
            if (record.contribution.isNotEmpty)
              BrigadaDetailRow(label: 'Contribution', value: record.contribution),
            if (quantity.isNotEmpty)
              BrigadaDetailRow(label: 'Quantity', value: quantity),
            if (record.projectName.isNotEmpty)
              BrigadaDetailRow(label: 'Project', value: record.projectName),
            if (record.remarks.isNotEmpty && record.remarks != 'None')
              BrigadaDetailRow(label: 'Remarks', value: record.remarks),
          ],
        ],
      ),
    );
  }
}
