import 'package:flutter/cupertino.dart';

import '../../../../core/theme/app_colors.dart';

/// Read-only PMCF detail view.
///
/// Shows all fields of a single PMCF record. The web app does not support
/// edit/delete, so this view is display-only.
class PmcfDetailView extends StatelessWidget {
  const PmcfDetailView({super.key, required this.record});

  final Map<String, dynamic> record;

  @override
  Widget build(BuildContext context) {
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: const CupertinoNavigationBar(
        middle: Text('PMCF Details'),
        backgroundColor: AppColors.surface,
        border: Border(
          bottom: BorderSide(color: AppColors.separator, width: 0.5),
        ),
      ),
      child: SafeArea(
        top: false,
        child: SingleChildScrollView(
          padding: const EdgeInsets.fromLTRB(16, 16, 16, 24),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // ── Title card ──────────────────────────────────────────────
              _titleCard(),
              const SizedBox(height: 16),
              // ── Observation details ─────────────────────────────────────
              _sectionCard('Observation', [
                _row('Teacher Observed', 'teacher_observed'),
                _row('Grade Level', 'grade_level'),
                _row('Section', 'section'),
                _row('District', 'district'),
                _row('School', 'school'),
                _row('Term', 'quarter'),
                _row('Date Observed', 'date_observed'),
                _row('Time Observed', 'time_observed'),
                _row('Subject Area', 'subject_area'),
              ]),
              const SizedBox(height: 16),
              // ── Supervisor ──────────────────────────────────────────────
              _sectionCard('Supervisor', [
                _row('Instructional Supervisor', 'instructional_supervisor'),
                _row('Designation', 'designation'),
              ]),
              const SizedBox(height: 16),
              // ── Coaching & Feedback ─────────────────────────────────────
              _sectionCard('Coaching & Feedback', [
                _row('Coaching Mechanism', 'coaching_mechanisms'),
                _row('Others (if specified)', 'coaching_mechanisms_others'),
                _longRow('Significant Incidents', 'significant_incidents_description'),
                _longRow('Impact on Job', 'impact_on_job'),
                _longRow('Feedback / Recommendation', 'feedback_recommendation'),
                _longRow('Progress to Date', 'progress_to_date'),
              ]),
            ],
          ),
        ),
      ),
    );
  }

  Widget _titleCard() {
    final teacher = (record['teacher_observed'] ?? 'Unknown').toString();
    final school = (record['school'] ?? '').toString();
    final date = (record['date_observed'] ?? '').toString();

    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppColors.surface,
        borderRadius: BorderRadius.circular(14),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            teacher,
            style: const TextStyle(
              fontSize: 20,
              fontWeight: FontWeight.w700,
              color: AppColors.label,
              height: 1.3,
            ),
          ),
          if (school.isNotEmpty) ...[
            const SizedBox(height: 4),
            Text(
              school,
              style: const TextStyle(
                fontSize: 14,
                color: AppColors.secondaryLabel,
              ),
            ),
          ],
          if (date.isNotEmpty) ...[
            const SizedBox(height: 4),
            Text(
              date,
              style: const TextStyle(
                fontSize: 13,
                color: AppColors.tertiaryLabel,
              ),
            ),
          ],
        ],
      ),
    );
  }

  Widget _sectionCard(String title, List<Widget> children) {
    return Container(
      width: double.infinity,
      decoration: BoxDecoration(
        color: AppColors.surface,
        borderRadius: BorderRadius.circular(14),
      ),
      clipBehavior: Clip.antiAlias,
      child: Column(
        children: [
          Container(
            width: double.infinity,
            padding: const EdgeInsets.fromLTRB(16, 12, 16, 8),
            child: Text(
              title,
              style: const TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w700,
                color: AppColors.secondaryLabel,
              ),
            ),
          ),
          ...children,
        ],
      ),
    );
  }

  Widget _row(String label, String key) {
    final value = (record[key] ?? '').toString();
    return Column(
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              SizedBox(
                width: 140,
                child: Text(
                  label,
                  style: const TextStyle(
                    fontSize: 14,
                    color: AppColors.secondaryLabel,
                  ),
                ),
              ),
              Expanded(
                child: Text(
                  value.isNotEmpty ? value : '—',
                  style: const TextStyle(
                    fontSize: 15,
                    color: AppColors.label,
                  ),
                ),
              ),
            ],
          ),
        ),
        Container(height: 0.5, color: AppColors.separator),
      ],
    );
  }

  Widget _longRow(String label, String key) {
    final value = (record[key] ?? '').toString();
    return Column(
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                label,
                style: const TextStyle(
                  fontSize: 14,
                  fontWeight: FontWeight.w600,
                  color: AppColors.secondaryLabel,
                ),
              ),
              const SizedBox(height: 4),
              Text(
                value.isNotEmpty ? value : '—',
                style: const TextStyle(
                  fontSize: 15,
                  color: AppColors.label,
                  height: 1.4,
                ),
              ),
            ],
          ),
        ),
        Container(height: 0.5, color: AppColors.separator),
      ],
    );
  }
}
