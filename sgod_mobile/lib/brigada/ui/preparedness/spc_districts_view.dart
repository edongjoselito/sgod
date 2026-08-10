import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../core/theme/app_colors.dart';
import '../../brigada_module.dart';
import '../../data/brigada_models.dart';
import '../../data/brigada_repository.dart';
import '../brigada_screen.dart';
import '../brigada_ui.dart';
import 'spc_schools_view.dart';

/// School Preparedness — district roster with checklist submission progress.
///
/// Mirrors `Brigada/spc_districts`, but replaces the web's four-column table
/// with rows that carry their own progress bar, so the state of a district
/// reads at a glance on a phone.
class SpcDistrictsView extends StatefulWidget {
  const SpcDistrictsView({super.key, this.sy});

  /// School year to show. When omitted the screen resolves the division's
  /// current SY itself, so it can be opened straight from the sidebar.
  final String? sy;

  @override
  State<SpcDistrictsView> createState() => _SpcDistrictsViewState();
}

class _SpcDistrictsViewState extends State<SpcDistrictsView> {
  late String _sy = widget.sy ?? BrigadaMeta.fallback().currentSy;

  late final BrigadaLoader<SpcDistrictList> _loader = BrigadaLoader(
    request: BrigadaRepository.districtsRequest(_sy),
    parse: SpcDistrictList.fromJson,
  );

  String _query = '';

  @override
  void initState() {
    super.initState();
    if (widget.sy == null) _resolveSchoolYear();
  }

  Future<void> _resolveSchoolYear() async {
    final resolved = await BrigadaModule.resolveSchoolYear();
    if (!mounted || resolved == _sy) return;
    setState(() => _sy = resolved);
    await _loader.retarget(BrigadaRepository.districtsRequest(resolved));
  }

  @override
  void dispose() {
    _loader.dispose();
    super.dispose();
  }

  List<SpcDistrict> _visible(SpcDistrictList data) {
    final q = _query.trim().toLowerCase();
    if (q.isEmpty) return data.districts;
    return data.districts
        .where((d) => d.name.toLowerCase().contains(q))
        .toList();
  }

  @override
  Widget build(BuildContext context) {
    return BrigadaScreen<SpcDistrictList>(
      title: 'School Preparedness',
      previousPageTitle: 'Brigada',
      loader: _loader,
      emptyCheck: (data) => data.districts.isEmpty,
      emptyIcon: PhosphorIconsRegular.mapPin,
      emptyTitle: 'No districts',
      emptyMessage: 'No districts are configured for this division.',
      header: (context, data) => Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          _overviewCard(data),
          const SizedBox(height: 14),
          BrigadaSearchField(
            placeholder: 'Search districts',
            onChanged: (value) => setState(() => _query = value),
          ),
        ],
      ),
      builder: (context, data) {
        final districts = _visible(data);
        if (districts.isEmpty) {
          return const BrigadaEmpty(
            icon: PhosphorIconsRegular.magnifyingGlass,
            title: 'No matches',
            message: 'No district matches your search.',
          );
        }
        return Column(
          children: [
            for (var i = 0; i < districts.length; i++) ...[
              if (i > 0) const SizedBox(height: 10),
              _districtCard(districts[i], data.sy),
            ],
          ],
        );
      },
    );
  }

  Widget _overviewCard(SpcDistrictList data) {
    final pending = data.totalSchools - data.totalSubmitted;
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
                      '${(data.progress * 100).toStringAsFixed(0)}%',
                      style: const TextStyle(
                        fontSize: 32,
                        fontWeight: FontWeight.w700,
                        letterSpacing: -1,
                        color: AppColors.label,
                      ),
                    ),
                    const Text(
                      'of schools have submitted',
                      style: TextStyle(
                        fontSize: 13.5,
                        color: AppColors.secondaryLabel,
                      ),
                    ),
                  ],
                ),
              ),
              BrigadaPill(
                label: 'SY ${data.sy}',
                color: BrigadaTokens.accent,
              ),
            ],
          ),
          const SizedBox(height: 14),
          BrigadaStackedBar(
            segments: [
              BrigadaSegment(
                  data.totalSubmitted, BrigadaTokens.fully, 'Submitted'),
              BrigadaSegment(
                  pending < 0 ? 0 : pending, BrigadaTokens.unanswered, 'Pending'),
            ],
            showLegend: true,
          ),
        ],
      ),
    );
  }

  Widget _districtCard(SpcDistrict district, String sy) {
    final complete =
        district.schoolCount > 0 && district.submittedCount >= district.schoolCount;

    return BrigadaCard(
      padding: const EdgeInsets.fromLTRB(14, 13, 12, 14),
      onTap: () => Navigator.of(context).push(
        CupertinoPageRoute(
          builder: (_) => SpcSchoolsView(
            districtId: district.id,
            districtName: district.name,
            sy: sy,
          ),
        ),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Expanded(
                child: Text(
                  district.name,
                  style: const TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.w600,
                    color: AppColors.label,
                  ),
                ),
              ),
              const SizedBox(width: 8),
              BrigadaPill(
                label: complete ? 'Complete' : '${district.submittedCount}/${district.schoolCount}',
                color: complete
                    ? BrigadaTokens.fully
                    : district.submittedCount == 0
                        ? BrigadaTokens.notPrepared
                        : BrigadaTokens.partially,
                compact: true,
              ),
              const SizedBox(width: 4),
              const Icon(CupertinoIcons.chevron_right,
                  size: 14, color: AppColors.tertiaryLabel),
            ],
          ),
          const SizedBox(height: 10),
          BrigadaMeter(
            fraction: district.progress,
            color: complete
                ? BrigadaTokens.fully
                : district.submittedCount == 0
                    ? BrigadaTokens.unanswered
                    : BrigadaTokens.partially,
          ),
          const SizedBox(height: 8),
          Text(
            district.schoolCount == 0
                ? 'No schools assigned to this district'
                : '${formatCount(district.schoolCount)} school${district.schoolCount == 1 ? '' : 's'} · '
                    '${formatCount(district.pendingCount)} pending',
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
