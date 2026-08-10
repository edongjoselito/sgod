import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../core/theme/app_colors.dart';
import '../../data/brigada_models.dart';
import '../../data/brigada_repository.dart';
import '../brigada_screen.dart';
import '../brigada_ui.dart';

/// Partner satisfaction survey results.
///
/// Mirrors `Page/satisfaction_survey_results`: six rating averages with their
/// wording, then every submission. The backing table is created on the first
/// partner submission, so "not started yet" is a normal state here, not a
/// failure.
class SurveyResultsView extends StatefulWidget {
  const SurveyResultsView({super.key});

  @override
  State<SurveyResultsView> createState() => _SurveyResultsViewState();
}

class _SurveyResultsViewState extends State<SurveyResultsView> {
  late final BrigadaLoader<SurveyResults> _loader = BrigadaLoader(
    request: BrigadaRepository.surveyRequest(),
    parse: SurveyResults.fromJson,
  );

  String _query = '';

  @override
  void dispose() {
    _loader.dispose();
    super.dispose();
  }

  /// Same 5-point wording the web results page uses.
  static String describe(double rating) {
    if (rating >= 4.5) return 'Excellent';
    if (rating >= 3.5) return 'Very Good';
    if (rating >= 2.5) return 'Good';
    if (rating >= 1.5) return 'Fair';
    return 'Poor';
  }

  static Color colorFor(double rating) {
    if (rating >= 3.5) return BrigadaTokens.fully;
    if (rating >= 2.5) return BrigadaTokens.partially;
    return BrigadaTokens.notPrepared;
  }

  List<SurveyResponse> _visible(SurveyResults data) {
    final q = _query.trim().toLowerCase();
    if (q.isEmpty) return data.responses;
    return data.responses
        .where((r) =>
            r.partnerName.toLowerCase().contains(q) ||
            r.contactPerson.toLowerCase().contains(q) ||
            r.comments.toLowerCase().contains(q))
        .toList();
  }

  @override
  Widget build(BuildContext context) {
    return BrigadaScreen<SurveyResults>(
      title: 'Survey Results',
      previousPageTitle: 'Brigada',
      loader: _loader,
      builder: (context, data) {
        if (data.totalSurveys == 0) {
          return const BrigadaEmpty(
            icon: PhosphorIconsRegular.star,
            title: 'No surveys submitted yet',
            message:
                'Partner satisfaction ratings will appear here once partners '
                'start submitting the survey.',
          );
        }

        final responses = _visible(data);
        return Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            _overallCard(data),
            const BrigadaSectionHeader('Average ratings'),
            BrigadaCard(
              child: Column(
                children: [
                  for (var i = 0; i < data.averages.length; i++) ...[
                    if (i > 0) const SizedBox(height: 16),
                    _averageRow(data.averages[i]),
                  ],
                ],
              ),
            ),
            BrigadaSectionHeader(
              'Responses · ${formatCount(data.totalSurveys)}',
            ),
            if (data.responses.length > 6)
              BrigadaSearchField(
                placeholder: 'Search partners or comments',
                onChanged: (value) => setState(() => _query = value),
              ),
            if (responses.isEmpty)
              const BrigadaEmpty(
                icon: PhosphorIconsRegular.magnifyingGlass,
                title: 'No matches',
                message: 'No response matches your search.',
              )
            else
              for (var i = 0; i < responses.length; i++) ...[
                if (i > 0) const SizedBox(height: 10),
                _responseCard(responses[i]),
              ],
          ],
        );
      },
    );
  }

  Widget _overallCard(SurveyResults data) {
    final overall = data.overall;
    final color = colorFor(overall);

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
                    Row(
                      crossAxisAlignment: CrossAxisAlignment.baseline,
                      textBaseline: TextBaseline.alphabetic,
                      children: [
                        Text(
                          overall.toStringAsFixed(2),
                          style: const TextStyle(
                            fontSize: 34,
                            fontWeight: FontWeight.w700,
                            letterSpacing: -1,
                            color: AppColors.label,
                          ),
                        ),
                        const SizedBox(width: 4),
                        const Text(
                          '/ 5',
                          style: TextStyle(
                            fontSize: 16,
                            fontWeight: FontWeight.w500,
                            color: AppColors.tertiaryLabel,
                          ),
                        ),
                      ],
                    ),
                    const Text(
                      'overall satisfaction',
                      style: TextStyle(
                        fontSize: 13.5,
                        color: AppColors.secondaryLabel,
                      ),
                    ),
                  ],
                ),
              ),
              BrigadaPill(label: describe(overall), color: color),
            ],
          ),
          const SizedBox(height: 14),
          _stars(overall),
          const SizedBox(height: 12),
          Text(
            'Based on ${formatCount(data.totalSurveys)} partner response'
            '${data.totalSurveys == 1 ? '' : 's'}',
            style: const TextStyle(
              fontSize: 12.5,
              color: AppColors.tertiaryLabel,
            ),
          ),
        ],
      ),
    );
  }

  Widget _stars(double rating) {
    return Row(
      children: [
        for (var i = 1; i <= 5; i++)
          Padding(
            padding: const EdgeInsets.only(right: 4),
            child: Icon(
              rating >= i
                  ? PhosphorIconsFill.star
                  : rating >= i - 0.5
                      ? PhosphorIconsFill.starHalf
                      : PhosphorIconsRegular.star,
              size: 20,
              color: rating >= i - 0.5
                  ? BrigadaTokens.partially
                  : AppColors.separator,
            ),
          ),
      ],
    );
  }

  Widget _averageRow(SurveyScore score) {
    final color = colorFor(score.value);
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          children: [
            Expanded(
              child: Text(
                score.label,
                style: const TextStyle(
                  fontSize: 14.5,
                  fontWeight: FontWeight.w500,
                  color: AppColors.label,
                ),
              ),
            ),
            Text(
              score.value.toStringAsFixed(2),
              style: TextStyle(
                fontSize: 15,
                fontWeight: FontWeight.w700,
                color: color,
              ),
            ),
            const SizedBox(width: 8),
            SizedBox(
              width: 74,
              child: Text(
                score.description.isEmpty
                    ? describe(score.value)
                    : score.description,
                textAlign: TextAlign.right,
                style: const TextStyle(
                  fontSize: 12,
                  color: AppColors.secondaryLabel,
                ),
              ),
            ),
          ],
        ),
        const SizedBox(height: 7),
        BrigadaMeter(fraction: score.fraction, color: color),
      ],
    );
  }

  Widget _responseCard(SurveyResponse response) {
    final color = colorFor(response.overall);
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
                      response.partnerName,
                      style: const TextStyle(
                        fontSize: 15.5,
                        fontWeight: FontWeight.w600,
                        color: AppColors.label,
                      ),
                    ),
                    if (response.contactPerson.isNotEmpty) ...[
                      const SizedBox(height: 2),
                      Text(
                        response.contactPerson,
                        style: const TextStyle(
                          fontSize: 13,
                          color: AppColors.secondaryLabel,
                        ),
                      ),
                    ],
                  ],
                ),
              ),
              const SizedBox(width: 10),
              BrigadaPill(
                label: response.overall.toStringAsFixed(1),
                color: color,
                icon: PhosphorIconsFill.star,
              ),
            ],
          ),
          const SizedBox(height: 12),
          Wrap(
            spacing: 7,
            runSpacing: 6,
            children: [
              for (final score in response.scores)
                _scoreChip(score),
            ],
          ),
          if (response.comments.trim().isNotEmpty) ...[
            const SizedBox(height: 12),
            Container(
              padding: const EdgeInsets.all(11),
              decoration: BoxDecoration(
                color: AppColors.background,
                borderRadius: BorderRadius.circular(10),
              ),
              child: Text(
                response.comments.trim(),
                style: const TextStyle(
                  fontSize: 13.5,
                  height: 1.35,
                  fontStyle: FontStyle.italic,
                  color: AppColors.secondaryLabel,
                ),
              ),
            ),
          ],
          if (response.submittedAt.isNotEmpty) ...[
            const SizedBox(height: 10),
            Text(
              'Submitted ${formatDate(response.submittedAt.split(' ').first)}',
              style: const TextStyle(
                fontSize: 12,
                color: AppColors.tertiaryLabel,
              ),
            ),
          ],
        ],
      ),
    );
  }

  Widget _scoreChip(SurveyScore score) {
    final color = colorFor(score.value);
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 9, vertical: 5),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.11),
        borderRadius: BorderRadius.circular(8),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Text(
            score.label,
            style: const TextStyle(
              fontSize: 11.5,
              color: AppColors.secondaryLabel,
            ),
          ),
          const SizedBox(width: 5),
          Text(
            score.value.toStringAsFixed(0),
            style: TextStyle(
              fontSize: 12.5,
              fontWeight: FontWeight.w700,
              color: color,
            ),
          ),
        ],
      ),
    );
  }
}
