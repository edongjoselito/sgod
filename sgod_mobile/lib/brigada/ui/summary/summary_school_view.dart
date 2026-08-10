import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../core/theme/app_colors.dart';
import '../../data/brigada_models.dart';
import '../brigada_ui.dart';
import 'contribution_details_view.dart';

/// One school's day-by-day contributions for the selected period.
///
/// This is the mobile replacement for a row of the web's school × date pivot:
/// the dates that school actually reported on, each with its own totals.
class SummarySchoolView extends StatelessWidget {
  const SummarySchoolView({
    super.key,
    required this.school,
    required this.year,
    required this.month,
    required this.periodLabel,
  });

  final SummarySchool school;
  final int year;
  final int month;
  final String periodLabel;

  @override
  Widget build(BuildContext context) {
    final entries = [...school.entries]
      ..sort((a, b) => b.date.compareTo(a.date));
    final peak = entries.fold<double>(
      0,
      (max, e) => e.resources > max ? e.resources : max,
    );

    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      child: CustomScrollView(
        slivers: [
          CupertinoSliverNavigationBar(
            largeTitle: Text(school.schoolName),
            previousPageTitle: 'Summary',
            backgroundColor: AppColors.surface,
            border: const Border(
              bottom: BorderSide(color: AppColors.separator, width: 0.5),
            ),
          ),
          SliverSafeArea(
            top: false,
            minimum: const EdgeInsets.only(bottom: 32),
            sliver: SliverToBoxAdapter(
              child: Padding(
                padding: BrigadaTokens.pagePadding,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.stretch,
                  children: [
                    _totalsCard(context),
                    BrigadaSectionHeader(
                      'Daily breakdown · ${entries.length} day'
                      '${entries.length == 1 ? '' : 's'}',
                    ),
                    if (entries.isEmpty)
                      const BrigadaEmpty(
                        icon: PhosphorIconsRegular.calendarBlank,
                        title: 'No daily records',
                      )
                    else
                      BrigadaCard(
                        padding: EdgeInsets.zero,
                        child: Column(
                          children: [
                            for (var i = 0; i < entries.length; i++) ...[
                              if (i > 0)
                                Container(
                                  margin: const EdgeInsets.only(left: 14),
                                  height: 0.5,
                                  color: AppColors.separator,
                                ),
                              _entryRow(entries[i], peak),
                            ],
                          ],
                        ),
                      ),
                  ],
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _totalsCard(BuildContext context) {
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
                      formatPeso(school.totalResources),
                      style: const TextStyle(
                        fontSize: 30,
                        fontWeight: FontWeight.w700,
                        letterSpacing: -1,
                        color: AppColors.label,
                      ),
                    ),
                    const Text(
                      'resources generated',
                      style: TextStyle(
                        fontSize: 13.5,
                        color: AppColors.secondaryLabel,
                      ),
                    ),
                  ],
                ),
              ),
              BrigadaPill(label: periodLabel, color: BrigadaTokens.accent),
            ],
          ),
          const SizedBox(height: 14),
          Row(
            children: [
              _chip(
                PhosphorIconsRegular.users,
                formatCount(school.totalVolunteers),
                'beneficiaries',
                BrigadaTokens.volunteers,
              ),
              const SizedBox(width: 10),
              _chip(
                PhosphorIconsRegular.listChecks,
                formatCount(school.totalRecords),
                'records',
                BrigadaTokens.records,
              ),
            ],
          ),
          const SizedBox(height: 14),
          SizedBox(
            width: double.infinity,
            child: CupertinoButton(
              padding: const EdgeInsets.symmetric(vertical: 11),
              color: BrigadaTokens.accent.withValues(alpha: 0.12),
              borderRadius: BorderRadius.circular(11),
              onPressed: () => Navigator.of(context).push(
                CupertinoPageRoute(
                  builder: (_) => ContributionDetailsView(
                    year: year,
                    month: month,
                    schoolId: school.schoolId,
                    title: school.schoolName,
                    previousPageTitle: 'School',
                  ),
                ),
              ),
              child: const Text(
                'View all contribution records',
                style: TextStyle(
                  fontSize: 15,
                  fontWeight: FontWeight.w600,
                  color: BrigadaTokens.accent,
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _chip(IconData icon, String value, String label, Color color) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 10, horizontal: 10),
        decoration: BoxDecoration(
          color: color.withValues(alpha: 0.1),
          borderRadius: BorderRadius.circular(10),
        ),
        child: Row(
          children: [
            Icon(icon, size: 15, color: color),
            const SizedBox(width: 8),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
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
          ],
        ),
      ),
    );
  }

  Widget _entryRow(SummaryEntry entry, double peak) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(14, 12, 14, 12),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Expanded(
                child: Text(
                  formatDate(entry.date),
                  style: const TextStyle(
                    fontSize: 15,
                    fontWeight: FontWeight.w500,
                    color: AppColors.label,
                  ),
                ),
              ),
              Text(
                formatPeso(entry.resources),
                style: const TextStyle(
                  fontSize: 15,
                  fontWeight: FontWeight.w700,
                  color: BrigadaTokens.resources,
                ),
              ),
            ],
          ),
          const SizedBox(height: 8),
          BrigadaMeter(
            fraction: peak <= 0 ? 0 : entry.resources / peak,
            color: BrigadaTokens.resources,
            height: 5,
          ),
          const SizedBox(height: 7),
          Text(
            '${formatCount(entry.volunteers)} beneficiaries · '
            '${formatCount(entry.records)} record${entry.records == 1 ? '' : 's'}',
            style: const TextStyle(
              fontSize: 12.5,
              color: AppColors.secondaryLabel,
            ),
          ),
        ],
      ),
    );
  }
}
