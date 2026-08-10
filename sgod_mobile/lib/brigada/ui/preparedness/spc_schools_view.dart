import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../core/theme/app_colors.dart';
import '../../data/brigada_models.dart';
import '../../data/brigada_repository.dart';
import '../brigada_screen.dart';
import '../brigada_ui.dart';
import 'spc_checklist_view.dart';

/// Schools inside one district, filterable by submission status.
///
/// The web opens this list in a new tab and shows only public schools; here
/// every school in the district is listed so the counts agree with the
/// district screen, with a segmented filter doing the narrowing.
class SpcSchoolsView extends StatefulWidget {
  const SpcSchoolsView({
    super.key,
    required this.districtId,
    required this.districtName,
    required this.sy,
  });

  final int districtId;
  final String districtName;
  final String sy;

  @override
  State<SpcSchoolsView> createState() => _SpcSchoolsViewState();
}

enum _SchoolFilter { all, submitted, pending }

class _SpcSchoolsViewState extends State<SpcSchoolsView> {
  late final BrigadaLoader<SpcSchoolList> _loader = BrigadaLoader(
    request:
        BrigadaRepository.districtSchoolsRequest(widget.districtId, widget.sy),
    parse: SpcSchoolList.fromJson,
  );

  _SchoolFilter _filter = _SchoolFilter.all;
  String _query = '';

  @override
  void dispose() {
    _loader.dispose();
    super.dispose();
  }

  List<SpcSchool> _visible(SpcSchoolList data) {
    final q = _query.trim().toLowerCase();
    return data.schools.where((school) {
      switch (_filter) {
        case _SchoolFilter.submitted:
          if (!school.submitted) return false;
        case _SchoolFilter.pending:
          if (school.submitted) return false;
        case _SchoolFilter.all:
          break;
      }
      if (q.isEmpty) return true;
      return school.schoolName.toLowerCase().contains(q) ||
          school.schoolId.toLowerCase().contains(q);
    }).toList();
  }

  @override
  Widget build(BuildContext context) {
    return BrigadaScreen<SpcSchoolList>(
      title: widget.districtName,
      previousPageTitle: 'Districts',
      loader: _loader,
      emptyCheck: (data) => data.schools.isEmpty,
      emptyIcon: PhosphorIconsRegular.buildings,
      emptyTitle: 'No schools',
      emptyMessage: 'No schools are assigned to ${widget.districtName}.',
      header: (context, data) {
        final submitted = data.submittedCount;
        final pending = data.schools.length - submitted;
        return Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            CupertinoSlidingSegmentedControl<_SchoolFilter>(
              groupValue: _filter,
              onValueChanged: (value) {
                if (value != null) setState(() => _filter = value);
              },
              children: {
                _SchoolFilter.all: _segment('All', data.schools.length),
                _SchoolFilter.submitted: _segment('Submitted', submitted),
                _SchoolFilter.pending: _segment('Pending', pending),
              },
            ),
            const SizedBox(height: 12),
            BrigadaSearchField(
              placeholder: 'Search schools',
              onChanged: (value) => setState(() => _query = value),
            ),
          ],
        );
      },
      builder: (context, data) {
        final schools = _visible(data);
        if (schools.isEmpty) {
          return BrigadaEmpty(
            icon: PhosphorIconsRegular.magnifyingGlass,
            title: 'No matches',
            message: _query.isEmpty
                ? 'No schools in this filter.'
                : 'No school matches your search.',
          );
        }
        return BrigadaCard(
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
                _schoolRow(schools[i], data.sy),
              ],
            ],
          ),
        );
      },
    );
  }

  Widget _segment(String label, int count) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 7, horizontal: 4),
      child: Text(
        '$label  $count',
        style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w600),
      ),
    );
  }

  Widget _schoolRow(SpcSchool school, String sy) {
    return CupertinoButton(
      padding: EdgeInsets.zero,
      onPressed: () => Navigator.of(context).push(
        CupertinoPageRoute(
          builder: (_) => SpcChecklistView(
            schoolId: school.schoolId,
            schoolName: school.schoolName,
            sy: sy,
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
                      fontSize: 15.5,
                      fontWeight: FontWeight.w500,
                      color: AppColors.label,
                    ),
                  ),
                  const SizedBox(height: 3),
                  Text(
                    school.schoolType.isEmpty
                        ? school.schoolId
                        : '${school.schoolId} · ${school.schoolType}',
                    style: const TextStyle(
                      fontSize: 12.5,
                      color: AppColors.secondaryLabel,
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(width: 8),
            BrigadaPill(
              label: school.submitted ? 'Submitted' : 'Pending',
              color: school.submitted
                  ? BrigadaTokens.fully
                  : BrigadaTokens.unanswered,
              compact: true,
            ),
            const SizedBox(width: 4),
            const Icon(CupertinoIcons.chevron_right,
                size: 14, color: AppColors.tertiaryLabel),
          ],
        ),
      ),
    );
  }
}
