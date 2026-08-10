import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../core/theme/app_colors.dart';
import '../../brigada_module.dart';
import '../../data/brigada_models.dart';
import '../../data/brigada_repository.dart';
import '../brigada_screen.dart';
import '../brigada_ui.dart';
import 'contribution_details_view.dart';
import 'summary_school_view.dart';

/// Summary Report — resources and volunteers for a month.
///
/// Mirrors `Brigada/brigada_summary_v2`. The web pivots schools against dates
/// in a very wide table; on a phone that becomes a ranked list of schools,
/// each opening its own per-date breakdown. Stat and partner tiles drill into
/// the same detail view the web links to.
class BrigadaSummaryView extends StatefulWidget {
  const BrigadaSummaryView({super.key});

  @override
  State<BrigadaSummaryView> createState() => _BrigadaSummaryViewState();
}

enum _SchoolSort { resources, volunteers, records, name }

class _BrigadaSummaryViewState extends State<BrigadaSummaryView> {
  late int _year = DateTime.now().year;
  late int _month = DateTime.now().month;

  late final BrigadaLoader<BrigadaSummary> _loader = BrigadaLoader(
    request: BrigadaRepository.summaryRequest(_year, _month),
    parse: BrigadaSummary.fromJson,
  );

  List<BrigadaPeriod> _periods = const [];
  _SchoolSort _sort = _SchoolSort.resources;
  String _query = '';

  @override
  void initState() {
    super.initState();
    _loadPeriods();
  }

  @override
  void dispose() {
    _loader.dispose();
    super.dispose();
  }

  /// Periods that actually hold records, so the picker never offers an
  /// empty month. Falls back silently to the current month when unavailable.
  Future<void> _loadPeriods() async {
    try {
      final result = await BrigadaModule.repository.fetch(
        BrigadaRepository.periodsRequest(),
        BrigadaRepository.parsePeriods,
      );
      if (!mounted || result.data.isEmpty) return;
      final hasCurrent =
          result.data.any((p) => p.sameAs(_year, _month));
      setState(() => _periods = result.data);
      // Land on the newest period with data when this month has none.
      if (!hasCurrent) {
        final newest = result.data.first;
        setState(() {
          _year = newest.year;
          _month = newest.month;
        });
        await _loader.retarget(
          BrigadaRepository.summaryRequest(_year, _month),
        );
      }
    } catch (_) {
      // Picker stays limited to the current month; the report still loads.
    }
  }

  Future<void> _pickPeriod() async {
    if (_periods.isEmpty) return;
    final labels = _periods.map((p) => '${p.label}  ·  ${formatCount(p.records)}').toList();
    final current = _periods.indexWhere((p) => p.sameAs(_year, _month));
    final index = await brigadaPickIndex(
      context,
      title: 'Period',
      options: labels,
      initialIndex: current < 0 ? 0 : current,
    );
    if (index == null || !mounted) return;
    final period = _periods[index];
    if (period.sameAs(_year, _month)) return;
    setState(() {
      _year = period.year;
      _month = period.month;
    });
    await _loader.retarget(BrigadaRepository.summaryRequest(_year, _month));
  }

  Future<void> _pickSort() async {
    const labels = [
      'Resources (highest first)',
      'Volunteers (highest first)',
      'Records (most first)',
      'School name (A–Z)',
    ];
    final index = await brigadaPickIndex(
      context,
      title: 'Sort schools by',
      options: labels,
      initialIndex: _SchoolSort.values.indexOf(_sort),
    );
    if (index == null || !mounted) return;
    setState(() => _sort = _SchoolSort.values[index]);
  }

  List<SummarySchool> _visibleSchools(BrigadaSummary data) {
    final q = _query.trim().toLowerCase();
    final schools = data.schools
        .where((s) => q.isEmpty || s.schoolName.toLowerCase().contains(q))
        .toList();
    switch (_sort) {
      case _SchoolSort.resources:
        schools.sort((a, b) => b.totalResources.compareTo(a.totalResources));
      case _SchoolSort.volunteers:
        schools.sort((a, b) => b.totalVolunteers.compareTo(a.totalVolunteers));
      case _SchoolSort.records:
        schools.sort((a, b) => b.totalRecords.compareTo(a.totalRecords));
      case _SchoolSort.name:
        schools.sort((a, b) =>
            a.schoolName.toLowerCase().compareTo(b.schoolName.toLowerCase()));
    }
    return schools;
  }

  String get _periodLabel {
    final match = _periods.where((p) => p.sameAs(_year, _month));
    if (match.isNotEmpty) return match.first.label;
    const months = [
      'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
      'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
    ];
    final name = (_month >= 1 && _month <= 12) ? months[_month - 1] : '$_month';
    return '$name $_year';
  }

  void _openDetails({
    String card = '',
    String scope = '',
    String type = '',
    required String title,
  }) {
    Navigator.of(context).push(
      CupertinoPageRoute(
        builder: (_) => ContributionDetailsView(
          year: _year,
          month: _month,
          card: card,
          scope: scope,
          type: type,
          title: title,
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return BrigadaScreen<BrigadaSummary>(
      title: 'Summary Report',
      previousPageTitle: 'Brigada',
      loader: _loader,
      trailing: BrigadaFilterButton(
        label: _periodLabel,
        onPressed: _periods.isEmpty ? () {} : _pickPeriod,
      ),
      emptyCheck: (data) => data.records == 0,
      emptyIcon: PhosphorIconsRegular.calendarBlank,
      emptyTitle: 'No contributions',
      emptyMessage:
          'No Brigada Eskwela contributions were recorded for this period.',
      header: (context, data) => _statsSection(data),
      builder: (context, data) {
        final schools = _visibleSchools(data);
        return Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            _partnerSection(
              'General Partner Type',
              data.generalPartnerTypes,
              'general',
            ),
            _partnerSection(
              'Specific Partner Type',
              data.specificPartnerTypes,
              'specific',
            ),
            BrigadaSectionHeader(
              'Schools · ${formatCount(data.schools.length)}',
              trailing: CupertinoButton(
                padding: EdgeInsets.zero,
                minimumSize: Size.zero,
                onPressed: _pickSort,
                child: const Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Icon(CupertinoIcons.arrow_up_arrow_down,
                        size: 13, color: BrigadaTokens.accent),
                    SizedBox(width: 4),
                    Text('Sort',
                        style: TextStyle(
                          fontSize: 13,
                          fontWeight: FontWeight.w600,
                          color: BrigadaTokens.accent,
                        )),
                  ],
                ),
              ),
            ),
            if (data.schools.length > 8)
              BrigadaSearchField(
                placeholder: 'Search schools',
                onChanged: (value) => setState(() => _query = value),
              ),
            if (schools.isEmpty)
              const BrigadaEmpty(
                icon: PhosphorIconsRegular.magnifyingGlass,
                title: 'No matches',
                message: 'No school matches your search.',
              )
            else
              BrigadaCard(
                padding: EdgeInsets.zero,
                child: Column(
                  children: [
                    for (var i = 0; i < schools.length; i++) ...[
                      if (i > 0)
                        Container(
                          margin: const EdgeInsets.only(left: 14),
                          height: 0.5,
                          color: AppColors.separator,
                        ),
                      _schoolRow(schools[i], data),
                    ],
                  ],
                ),
              ),
          ],
        );
      },
    );
  }

  Widget _statsSection(BrigadaSummary data) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.stretch,
      children: [
        BrigadaTileGrid(
          tiles: [
            BrigadaStatTile(
              value: formatCount(data.records),
              label: 'Total Records',
              icon: PhosphorIconsRegular.listChecks,
              accent: BrigadaTokens.records,
              onTap: () =>
                  _openDetails(card: 'records', title: 'All Records'),
            ),
            BrigadaStatTile(
              value: formatCompact(data.resources, peso: true),
              label: 'Total Resources',
              icon: PhosphorIconsRegular.currencyCircleDollar,
              accent: BrigadaTokens.resources,
              onTap: () =>
                  _openDetails(card: 'resources', title: 'Total Resources'),
            ),
            BrigadaStatTile(
              value: formatCompact(data.volunteers),
              label: 'Total Volunteers',
              icon: PhosphorIconsRegular.users,
              accent: BrigadaTokens.volunteers,
              onTap: () =>
                  _openDetails(card: 'volunteers', title: 'Total Volunteers'),
            ),
            BrigadaStatTile(
              value: formatCount(data.days),
              label: 'Reporting Days',
              icon: PhosphorIconsRegular.calendarCheck,
              accent: BrigadaTokens.days,
              onTap: () => _openDetails(card: 'days', title: 'Reporting Days'),
            ),
          ],
        ),
        const SizedBox(height: 4),
      ],
    );
  }

  Widget _partnerSection(
    String title,
    List<PartnerTypeCount> types,
    String scope,
  ) {
    if (types.isEmpty) return const SizedBox.shrink();
    const palette = [
      BrigadaTokens.records,
      BrigadaTokens.resources,
      BrigadaTokens.volunteers,
      BrigadaTokens.days,
    ];

    return Column(
      crossAxisAlignment: CrossAxisAlignment.stretch,
      children: [
        BrigadaSectionHeader(title),
        BrigadaCard(
          padding: EdgeInsets.zero,
          child: Column(
            children: [
              for (var i = 0; i < types.length; i++) ...[
                if (i > 0)
                  Container(
                    margin: const EdgeInsets.only(left: 14),
                    height: 0.5,
                    color: AppColors.separator,
                  ),
                _partnerRow(
                  types[i],
                  palette[i % palette.length],
                  scope,
                ),
              ],
            ],
          ),
        ),
      ],
    );
  }

  Widget _partnerRow(PartnerTypeCount type, Color color, String scope) {
    final enabled = type.count > 0;
    return CupertinoButton(
      padding: EdgeInsets.zero,
      onPressed: enabled
          ? () => _openDetails(
                scope: scope,
                type: type.key,
                title: type.label,
              )
          : null,
      child: Padding(
        padding: const EdgeInsets.fromLTRB(14, 12, 12, 12),
        child: Row(
          children: [
            Container(
              width: 8,
              height: 8,
              decoration: BoxDecoration(
                color: enabled ? color : AppColors.separator,
                shape: BoxShape.circle,
              ),
            ),
            const SizedBox(width: 11),
            Expanded(
              child: Text(
                type.label,
                style: TextStyle(
                  fontSize: 15,
                  color: enabled ? AppColors.label : AppColors.tertiaryLabel,
                ),
              ),
            ),
            Text(
              formatCount(type.count),
              style: TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.w700,
                color: enabled ? AppColors.label : AppColors.tertiaryLabel,
              ),
            ),
            const SizedBox(width: 6),
            Icon(
              CupertinoIcons.chevron_right,
              size: 14,
              color: enabled ? AppColors.tertiaryLabel : AppColors.separator,
            ),
          ],
        ),
      ),
    );
  }

  Widget _schoolRow(SummarySchool school, BrigadaSummary data) {
    return CupertinoButton(
      padding: EdgeInsets.zero,
      onPressed: () => Navigator.of(context).push(
        CupertinoPageRoute(
          builder: (_) => SummarySchoolView(
            school: school,
            year: data.year,
            month: data.month,
            periodLabel: data.periodLabel,
          ),
        ),
      ),
      child: Padding(
        padding: const EdgeInsets.fromLTRB(14, 12, 12, 12),
        child: Row(
          children: [
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    school.schoolName,
                    style: const TextStyle(
                      fontSize: 15,
                      fontWeight: FontWeight.w500,
                      color: AppColors.label,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Row(
                    children: [
                      _metric(
                        PhosphorIconsRegular.users,
                        formatCompact(school.totalVolunteers),
                        BrigadaTokens.volunteers,
                      ),
                      const SizedBox(width: 12),
                      _metric(
                        PhosphorIconsRegular.listChecks,
                        formatCount(school.totalRecords),
                        BrigadaTokens.records,
                      ),
                      const SizedBox(width: 12),
                      _metric(
                        PhosphorIconsRegular.calendarBlank,
                        '${school.entries.length}d',
                        BrigadaTokens.days,
                      ),
                    ],
                  ),
                ],
              ),
            ),
            const SizedBox(width: 10),
            Column(
              crossAxisAlignment: CrossAxisAlignment.end,
              children: [
                Text(
                  formatCompact(school.totalResources, peso: true),
                  style: const TextStyle(
                    fontSize: 15.5,
                    fontWeight: FontWeight.w700,
                    color: BrigadaTokens.resources,
                  ),
                ),
                const SizedBox(height: 2),
                const Text(
                  'resources',
                  style: TextStyle(
                    fontSize: 11,
                    color: AppColors.tertiaryLabel,
                  ),
                ),
              ],
            ),
            const SizedBox(width: 4),
            const Icon(CupertinoIcons.chevron_right,
                size: 14, color: AppColors.tertiaryLabel),
          ],
        ),
      ),
    );
  }

  Widget _metric(IconData icon, String value, Color color) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Icon(icon, size: 12, color: color),
        const SizedBox(width: 3),
        Text(
          value,
          style: const TextStyle(
            fontSize: 12,
            color: AppColors.secondaryLabel,
          ),
        ),
      ],
    );
  }
}
