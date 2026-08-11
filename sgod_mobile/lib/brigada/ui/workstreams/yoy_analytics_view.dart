import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../core/theme/app_colors.dart';
import '../../../ui/core/di.dart';
import '../brigada_ui.dart';

/// Workstream D — Year-over-Year Analytics.
///
/// SMN/SGOD users view comparative analytics between two school years,
/// including totals, district breakdowns, contribution type splits, and
/// top schools/stakeholders rankings.
class YoyAnalyticsView extends StatefulWidget {
  const YoyAnalyticsView({super.key});

  @override
  State<YoyAnalyticsView> createState() => _YoyAnalyticsViewState();
}

class _YoyAnalyticsViewState extends State<YoyAnalyticsView> {
  bool _loading = true;
  String? _error;
  Map<String, dynamic>? _data;
  String _syA = '';
  String _syB = '';

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() {
      _loading = true;
      _error = null;
    });
    try {
      final query = <String, dynamic>{};
      if (_syA.isNotEmpty) query['sy_a'] = _syA;
      if (_syB.isNotEmpty) query['sy_b'] = _syB;
      final data = await DI.api.get('api_brigada/yoy', query: query);
      if (mounted) {
        final map = data as Map<String, dynamic>?;
        setState(() {
          _data = map;
          if (map != null) {
            _syA = (map['sy_a'] ?? '').toString();
            _syB = (map['sy_b'] ?? '').toString();
          }
          _loading = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _error = '$e';
          _loading = false;
        });
      }
    }
  }

  List<String> get _schoolYears {
    if (_data == null) return [];
    final list = _data!['school_years'];
    if (list == null) return [];
    return (list as List).map((e) => e.toString()).toList();
  }

  @override
  Widget build(BuildContext context) {
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: const CupertinoNavigationBar(
        middle: Text('Year-over-Year'),
        backgroundColor: AppColors.surface,
        border: Border(
          bottom: BorderSide(color: AppColors.separator, width: 0.5),
        ),
      ),
      child: SafeArea(
        top: false,
        child: _buildBody(),
      ),
    );
  }

  Widget _buildBody() {
    if (_loading) {
      return const Center(child: CupertinoActivityIndicator(radius: 16));
    }
    if (_error != null) {
      return BrigadaEmpty(
        icon: PhosphorIconsRegular.warningCircle,
        title: 'Could not load',
        message: _error!,
      );
    }
    if (_data == null) {
      return BrigadaEmpty(
        icon: PhosphorIconsRegular.chartBar,
        title: 'No data',
        message: 'No year-over-year data is available.',
      );
    }
    return CustomScrollView(
      slivers: [
        CupertinoSliverRefreshControl(onRefresh: _load),
        SliverToBoxAdapter(
          child: Padding(
            padding: const EdgeInsets.fromLTRB(16, 12, 16, 24),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // ── Year selector ──────────────────────────────────────
                _yearSelector(),
                const SizedBox(height: 16),
                // ── Totals comparison ──────────────────────────────────
                _totalsSection(),
                const SizedBox(height: 16),
                // ── District breakdown ─────────────────────────────────
                _districtSection(),
                const SizedBox(height: 16),
                // ── Contribution types ─────────────────────────────────
                _contributionTypesSection(),
                const SizedBox(height: 16),
                // ── Top schools ────────────────────────────────────────
                _topSchoolsSection(),
                const SizedBox(height: 16),
                // ── Top stakeholders ───────────────────────────────────
                _topStakeholdersSection(),
              ],
            ),
          ),
        ),
      ],
    );
  }

  Widget _yearSelector() {
    return Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: AppColors.surface,
        borderRadius: BorderRadius.circular(12),
      ),
      child: Row(
        children: [
          Expanded(
            child: _yearPicker('Year A', _syA, (v) {
              setState(() => _syA = v);
              _load();
            }),
          ),
          const Padding(
            padding: EdgeInsets.symmetric(horizontal: 8),
            child: Icon(CupertinoIcons.arrow_right_arrow_left,
                size: 18, color: AppColors.tertiaryLabel),
          ),
          Expanded(
            child: _yearPicker('Year B', _syB, (v) {
              setState(() => _syB = v);
              _load();
            }),
          ),
        ],
      ),
    );
  }

  Widget _yearPicker(
      String label, String value, ValueChanged<String> onChanged) {
    return CupertinoButton(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 6),
      color: AppColors.background,
      borderRadius: BorderRadius.circular(8),
      onPressed: () {
        showCupertinoModalPopup(
          context: context,
          builder: (ctx) => CupertinoActionSheet(
            title: Text(label),
            actions: _schoolYears
                .map((y) => CupertinoActionSheetAction(
                      onPressed: () {
                        onChanged(y);
                        Navigator.pop(ctx);
                      },
                      child: Text(y),
                    ))
                .toList(),
            cancelButton: CupertinoActionSheetAction(
              isDefaultAction: true,
              onPressed: () => Navigator.pop(ctx),
              child: const Text('Cancel'),
            ),
          ),
        );
      },
      child: Column(
        children: [
          Text(label,
              style: const TextStyle(
                fontSize: 11,
                color: AppColors.tertiaryLabel,
              )),
          const SizedBox(height: 2),
          Text(value.isEmpty ? 'Select' : value,
              style: const TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.w600,
                color: AppColors.primary,
              )),
        ],
      ),
    );
  }

  Widget _totalsSection() {
    final totals = (_data!['totals'] as List?) ?? [];
    return _sectionCard('Totals', [
      Row(
        children: [
          for (final t in totals)
            Expanded(
              child: _totalCard(
                (t as Map)['sy']?.toString() ?? '',
                int.tryParse('${t['record_count']}') ?? 0,
                double.tryParse('${t['total_amount']}') ?? 0,
                int.tryParse('${t['school_count']}') ?? 0,
              ),
            ),
        ],
      ),
    ]);
  }

  Widget _totalCard(
      String sy, int records, double amount, int schools) {
    return Padding(
      padding: const EdgeInsets.all(12),
      child: Column(
        children: [
          Text(sy,
              style: const TextStyle(
                fontSize: 14,
                fontWeight: FontWeight.w700,
                color: AppColors.primary,
              )),
          const SizedBox(height: 8),
          _statRow('Records', '$records'),
          _statRow('Amount', '₱${_formatAmount(amount)}'),
          _statRow('Schools', '$schools'),
        ],
      ),
    );
  }

  Widget _districtSection() {
    final districtsA = (_data!['districts_a'] as List?) ?? [];
    final districtsB = (_data!['districts_b'] as List?) ?? [];
    return _sectionCard('District Breakdown', [
      _comparisonTable(
        ['District', 'Records', 'Amount'],
        districtsA.map((d) => [
          (d as Map)['district']?.toString() ?? '',
          '${d['record_count'] ?? 0}',
          '₱${_formatAmount(double.tryParse('${d['total_amount']}') ?? 0)}',
        ]).toList(),
        districtsB.map((d) => [
          (d as Map)['district']?.toString() ?? '',
          '${d['record_count'] ?? 0}',
          '₱${_formatAmount(double.tryParse('${d['total_amount']}') ?? 0)}',
        ]).toList(),
      ),
    ]);
  }

  Widget _contributionTypesSection() {
    final typesA = (_data!['contribution_types_a'] as List?) ?? [];
    final typesB = (_data!['contribution_types_b'] as List?) ?? [];
    return _sectionCard('Contribution Types', [
      _comparisonTable(
        ['Type', 'Records', 'Amount'],
        typesA.map((t) => [
          (t as Map)['contribution_type']?.toString() ?? '',
          '${t['record_count'] ?? 0}',
          '₱${_formatAmount(double.tryParse('${t['total_amount']}') ?? 0)}',
        ]).toList(),
        typesB.map((t) => [
          (t as Map)['contribution_type']?.toString() ?? '',
          '${t['record_count'] ?? 0}',
          '₱${_formatAmount(double.tryParse('${t['total_amount']}') ?? 0)}',
        ]).toList(),
      ),
    ]);
  }

  Widget _topSchoolsSection() {
    final top = (_data!['top_schools'] as List?) ?? [];
    return _sectionCard('Top Schools', [
      if (top.isEmpty)
        const Padding(
          padding: EdgeInsets.all(16),
          child: Text('No data',
              style: TextStyle(color: AppColors.tertiaryLabel)),
        )
      else
        ...top.map((s) => _rankingTile(
              (s as Map)['schoolName']?.toString() ?? '',
              (s)['district']?.toString() ?? '',
              '${s['record_count'] ?? 0}',
              '₱${_formatAmount(double.tryParse('${s['total_amount']}') ?? 0)}',
            )),
    ]);
  }

  Widget _topStakeholdersSection() {
    final top = (_data!['top_stakeholders'] as List?) ?? [];
    return _sectionCard('Top Stakeholders', [
      if (top.isEmpty)
        const Padding(
          padding: EdgeInsets.all(16),
          child: Text('No data',
              style: TextStyle(color: AppColors.tertiaryLabel)),
        )
      else
        ...top.map((s) => _rankingTile(
              (s as Map)['partner_name']?.toString() ?? '',
              (s)['general_type']?.toString() ?? '',
              '${s['record_count'] ?? 0}',
              '₱${_formatAmount(double.tryParse('${s['total_amount']}') ?? 0)}',
            )),
    ]);
  }

  // ── Helpers ───────────────────────────────────────────────────────────────

  Widget _sectionCard(String title, List<Widget> children) {
    return Container(
      width: double.infinity,
      decoration: BoxDecoration(
        color: AppColors.surface,
        borderRadius: BorderRadius.circular(14),
      ),
      clipBehavior: Clip.antiAlias,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 12, 16, 8),
            child: Text(title,
                style: const TextStyle(
                  fontSize: 14,
                  fontWeight: FontWeight.w700,
                  color: AppColors.secondaryLabel,
                )),
          ),
          ...children,
        ],
      ),
    );
  }

  Widget _statRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 3),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label,
              style: const TextStyle(
                fontSize: 13,
                color: AppColors.secondaryLabel,
              )),
          Text(value,
              style: const TextStyle(
                fontSize: 14,
                fontWeight: FontWeight.w600,
                color: AppColors.label,
              )),
        ],
      ),
    );
  }

  Widget _comparisonTable(
    List<String> headers,
    List<List<String>> rowsA,
    List<List<String>> rowsB,
  ) {
    return Column(
      children: [
        _tableHeader(headers),
        if (_syA.isNotEmpty) ...[
          _tableSubheader(_syA),
          ...rowsA.map((r) => _tableRow(r)),
        ],
        if (_syB.isNotEmpty) ...[
          _tableSubheader(_syB),
          ...rowsB.map((r) => _tableRow(r)),
        ],
      ],
    );
  }

  Widget _tableHeader(List<String> headers) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      decoration: const BoxDecoration(
        border: Border(
            bottom: BorderSide(color: AppColors.separator, width: 0.5)),
      ),
      child: Row(
        children: [
          for (var i = 0; i < headers.length; i++)
            Expanded(
              flex: i == 0 ? 2 : 1,
              child: Text(headers[i],
                  style: const TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.w700,
                    color: AppColors.tertiaryLabel,
                  )),
            ),
        ],
      ),
    );
  }

  Widget _tableSubheader(String label) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
      color: AppColors.background,
      child: Text(label,
          style: const TextStyle(
            fontSize: 12,
            fontWeight: FontWeight.w600,
            color: AppColors.primary,
          )),
    );
  }

  Widget _tableRow(List<String> cells) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
      decoration: const BoxDecoration(
        border: Border(
            bottom: BorderSide(color: AppColors.separator, width: 0.3)),
      ),
      child: Row(
        children: [
          for (var i = 0; i < cells.length; i++)
            Expanded(
              flex: i == 0 ? 2 : 1,
              child: Text(cells[i],
                  style: TextStyle(
                    fontSize: 13,
                    color: i == 0 ? AppColors.label : AppColors.secondaryLabel,
                  )),
            ),
        ],
      ),
    );
  }

  Widget _rankingTile(
      String name, String subtitle, String records, String amount) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
      child: Row(
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(name,
                    style: const TextStyle(
                      fontSize: 14,
                      fontWeight: FontWeight.w600,
                      color: AppColors.label,
                    ),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis),
                if (subtitle.isNotEmpty)
                  Text(subtitle,
                      style: const TextStyle(
                        fontSize: 12,
                        color: AppColors.tertiaryLabel,
                      ),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis),
              ],
            ),
          ),
          Column(
            crossAxisAlignment: CrossAxisAlignment.end,
            children: [
              Text(records,
                  style: const TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.w600,
                    color: AppColors.primary,
                  )),
              Text(amount,
                  style: const TextStyle(
                    fontSize: 12,
                    color: AppColors.tertiaryLabel,
                  )),
            ],
          ),
        ],
      ),
    );
  }

  String _formatAmount(double amount) {
    if (amount == 0) return '0';
    if (amount >= 1000000) {
      return '${(amount / 1000000).toStringAsFixed(1)}M';
    }
    if (amount >= 1000) {
      return '${(amount / 1000).toStringAsFixed(1)}K';
    }
    return amount.toStringAsFixed(0);
  }
}
