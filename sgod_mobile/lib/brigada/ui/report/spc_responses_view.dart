import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../core/theme/app_colors.dart';
import '../../data/brigada_models.dart';
import '../../data/brigada_repository.dart';
import '../brigada_screen.dart';
import '../brigada_ui.dart';

/// The schools behind one SPC report count, with the remarks they recorded.
class SpcResponsesView extends StatefulWidget {
  const SpcResponsesView({
    super.key,
    required this.sy,
    required this.itemId,
    required this.value,
    required this.itemDescription,
    required this.categoryName,
  });

  final String sy;
  final int itemId;

  /// 1 = Fully, 2 = Partially, 3 = Not prepared.
  final int value;
  final String itemDescription;
  final String categoryName;

  @override
  State<SpcResponsesView> createState() => _SpcResponsesViewState();
}

class _SpcResponsesViewState extends State<SpcResponsesView> {
  late final BrigadaLoader<SpcResponseList> _loader = BrigadaLoader(
    request: BrigadaRepository.responsesRequest(
        widget.sy, widget.itemId, widget.value),
    parse: SpcResponseList.fromJson,
  );

  String _query = '';

  @override
  void dispose() {
    _loader.dispose();
    super.dispose();
  }

  List<SpcResponseSchool> _visible(SpcResponseList data) {
    final q = _query.trim().toLowerCase();
    if (q.isEmpty) return data.schools;
    return data.schools
        .where((s) =>
            s.schoolName.toLowerCase().contains(q) ||
            s.remark.toLowerCase().contains(q))
        .toList();
  }

  @override
  Widget build(BuildContext context) {
    final color = BrigadaTokens.forRating(widget.value);

    return BrigadaScreen<SpcResponseList>(
      title: BrigadaTokens.labelForRating(widget.value),
      previousPageTitle: 'Report',
      loader: _loader,
      emptyCheck: (data) => data.schools.isEmpty,
      emptyIcon: PhosphorIconsRegular.buildings,
      emptyTitle: 'No schools',
      emptyMessage:
          'No school gave this answer for SY ${widget.sy}.',
      header: (context, data) => Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          BrigadaCard(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  data.categoryName.isEmpty
                      ? widget.categoryName
                      : data.categoryName,
                  style: const TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.w700,
                    letterSpacing: 0.4,
                    color: AppColors.secondaryLabel,
                  ),
                ),
                const SizedBox(height: 6),
                Text(
                  data.itemDescription.isEmpty
                      ? widget.itemDescription
                      : data.itemDescription,
                  style: const TextStyle(
                    fontSize: 15,
                    height: 1.3,
                    fontWeight: FontWeight.w500,
                    color: AppColors.label,
                  ),
                ),
                const SizedBox(height: 12),
                Row(
                  children: [
                    BrigadaPill(
                      label: data.ratingLabel.isEmpty
                          ? BrigadaTokens.labelForRating(widget.value)
                          : data.ratingLabel,
                      color: color,
                      compact: true,
                    ),
                    const SizedBox(width: 8),
                    Text(
                      '${formatCount(data.schools.length)} school'
                      '${data.schools.length == 1 ? '' : 's'}',
                      style: const TextStyle(
                        fontSize: 12.5,
                        color: AppColors.secondaryLabel,
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
          if (data.schools.length > 8) ...[
            const SizedBox(height: 14),
            BrigadaSearchField(
              placeholder: 'Search schools or remarks',
              onChanged: (value) => setState(() => _query = value),
            ),
          ] else
            const SizedBox(height: 14),
        ],
      ),
      builder: (context, data) {
        final schools = _visible(data);
        if (schools.isEmpty) {
          return const BrigadaEmpty(
            icon: PhosphorIconsRegular.magnifyingGlass,
            title: 'No matches',
            message: 'No school matches your search.',
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
                _row(schools[i], color),
              ],
            ],
          ),
        );
      },
    );
  }

  Widget _row(SpcResponseSchool school, Color color) {
    final remark = school.remark.trim();
    return Padding(
      padding: const EdgeInsets.fromLTRB(14, 12, 14, 12),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: 4,
            height: 30,
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
                  school.schoolName,
                  style: const TextStyle(
                    fontSize: 15,
                    fontWeight: FontWeight.w500,
                    color: AppColors.label,
                  ),
                ),
                const SizedBox(height: 3),
                Text(
                  remark.isEmpty ? 'No remarks' : remark,
                  style: TextStyle(
                    fontSize: 12.5,
                    fontStyle: remark.isEmpty ? FontStyle.normal : FontStyle.italic,
                    color: remark.isEmpty
                        ? AppColors.tertiaryLabel
                        : AppColors.secondaryLabel,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
