import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart' show LinearProgressIndicator;

import '../../../../core/theme/app_colors.dart';
import '../../../../data/models/accomplishment_item.dart';

/// Accomplishment report view — shows a formatted summary of the record.
class AccomplishmentReportView extends StatelessWidget {
  const AccomplishmentReportView({super.key, required this.item});

  final AccomplishmentItem item;

  @override
  Widget build(BuildContext context) {
    final pct = double.tryParse(
          item.percentageAccom.replaceAll('%', '').trim(),
        ) ??
        0;

    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: const CupertinoNavigationBar(
        middle: Text('Accomplishment Report'),
        backgroundColor: AppColors.surface,
        border: Border(
          bottom: BorderSide(color: AppColors.separator, width: 0.5),
        ),
      ),
      child: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.fromLTRB(16, 16, 16, 32),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // ── Header card ──────────────────────────────────────────
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                    colors: [
                      AppColors.primary,
                      Color.lerp(AppColors.primary, const Color(0xFF000000), 0.25)!,
                    ],
                  ),
                  borderRadius: BorderRadius.circular(16),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'ACCOMPLISHMENT REPORT',
                      style: TextStyle(
                        fontSize: 11,
                        fontWeight: FontWeight.w700,
                        color: Color(0xB3FFFFFF),
                        letterSpacing: 1.2,
                      ),
                    ),
                    const SizedBox(height: 8),
                    Text(
                      item.activity,
                      style: const TextStyle(
                        fontSize: 20,
                        fontWeight: FontWeight.w700,
                        color: CupertinoColors.white,
                        height: 1.2,
                      ),
                    ),
                    const SizedBox(height: 12),
                    Wrap(
                      spacing: 8,
                      runSpacing: 6,
                      children: [
                        _pill('Q${item.quarter}', CupertinoColors.white.withValues(alpha: 0.2)),
                        if (item.year.isNotEmpty)
                          _pill(item.year, CupertinoColors.white.withValues(alpha: 0.2)),
                        if (item.section.isNotEmpty)
                          _pill(item.section, CupertinoColors.white.withValues(alpha: 0.2)),
                      ],
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 16),
              // ── Details ───────────────────────────────────────────────
              _card('Activity Information', [
                _row('Activity', item.activity),
                _row('Category', item.activityCategory.isNotEmpty ? item.activityCategory : '—'),
                _row('Particulars', item.particulars.isNotEmpty ? item.particulars : '—'),
                _row('Venue', item.venue.isNotEmpty ? item.venue : '—'),
              ]),
              const SizedBox(height: 12),
              _card('Schedule', [
                _row('Date Conducted', item.dateConducted.isNotEmpty ? item.dateConducted : '—'),
                _row('Target Date', item.targetDate.isNotEmpty ? item.targetDate : '—'),
                _row('Month', item.monthAcc.isNotEmpty ? item.monthAcc : '—'),
                _row('Quarter', item.quarter.isNotEmpty ? 'Quarter ${item.quarter}' : '—'),
                _row('Year', item.year.isNotEmpty ? item.year : '—'),
              ]),
              const SizedBox(height: 12),
              // ── Performance card with progress ────────────────────────
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: AppColors.surface,
                  borderRadius: BorderRadius.circular(14),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'Performance',
                      style: TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.w700,
                        color: AppColors.label,
                      ),
                    ),
                    const SizedBox(height: 16),
                    if (pct > 0) ...[
                      Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Text(
                            '${pct.toStringAsFixed(0)}%',
                            style: const TextStyle(
                              fontSize: 36,
                              fontWeight: FontWeight.w800,
                              color: AppColors.success,
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 8),
                      ClipRRect(
                        borderRadius: BorderRadius.circular(6),
                        child: SizedBox(
                          height: 8,
                          child: LinearProgressIndicator(
                            value: (pct / 100).clamp(0, 1),
                            backgroundColor: AppColors.secondaryBackground,
                            valueColor: const AlwaysStoppedAnimation(AppColors.success),
                          ),
                        ),
                      ),
                      const SizedBox(height: 16),
                    ],
                    _row('Target', item.target.isNotEmpty ? item.target : '—'),
                    _divider(),
                    _row('Achieved', item.achieved.isNotEmpty ? item.achieved : '—'),
                    _divider(),
                    _row('Percentage', item.percentageAccom.isNotEmpty ? '${item.percentageAccom}%' : '—'),
                  ],
                ),
              ),
              const SizedBox(height: 12),
              _card('Additional Information', [
                _row('Resources', item.resources.isNotEmpty ? item.resources : '—'),
                _row('Notes', item.notes.isNotEmpty ? item.notes : '—'),
                _row('Remarks', item.remarks.isNotEmpty ? item.remarks : '—'),
                _row('Scope', item.accomplishmentScope.isNotEmpty ? item.accomplishmentScope : '—'),
                _row('Encoder', item.encoder.isNotEmpty ? item.encoder : '—'),
              ]),
            ],
          ),
        ),
      ),
    );
  }

  Widget _pill(String text, Color bg) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
      decoration: BoxDecoration(
        color: bg,
        borderRadius: BorderRadius.circular(100),
      ),
      child: Text(
        text,
        style: const TextStyle(
          fontSize: 12,
          fontWeight: FontWeight.w600,
          color: CupertinoColors.white,
        ),
      ),
    );
  }

  Widget _card(String title, List<Widget> children) {
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
            padding: const EdgeInsets.fromLTRB(16, 14, 16, 8),
            child: Text(
              title,
              style: const TextStyle(
                fontSize: 14,
                fontWeight: FontWeight.w700,
                color: AppColors.label,
              ),
            ),
          ),
          ...children,
        ],
      ),
    );
  }

  Widget _row(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 100,
            child: Text(
              label,
              style: const TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w600,
                color: AppColors.secondaryLabel,
              ),
            ),
          ),
          Expanded(
            child: Text(
              value,
              style: const TextStyle(fontSize: 14, color: AppColors.label),
            ),
          ),
        ],
      ),
    );
  }

  Widget _divider() => Container(height: 0.5, color: AppColors.separator);
}
