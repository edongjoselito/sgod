import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../core/widgets/app_dialogs.dart';
import '../../../../data/models/accomplishment_item.dart';
import '../../../../data/repositories/accomplishments_repository.dart';
import '../../../core/di.dart';
import 'accomplishment_attachments_view.dart';
import 'accomplishment_edit_view.dart';
import 'accomplishment_report_view.dart';

/// Full-screen accomplishment detail page with all info + action buttons.
///
/// Actions (matching web): Report, Attach, Copy, Edit, Delete.
class AccomplishmentDetailView extends StatefulWidget {
  const AccomplishmentDetailView({
    super.key,
    required this.item,
    this.onChanged,
  });

  final AccomplishmentItem item;
  final VoidCallback? onChanged;

  @override
  State<AccomplishmentDetailView> createState() =>
      _AccomplishmentDetailViewState();
}

class _AccomplishmentDetailViewState extends State<AccomplishmentDetailView> {
  bool _busy = false;
  String? _busyLabel;

  AccomplishmentItem get item => widget.item;
  AccomplishmentsRepository get _repo => DI.accomplishments;

  @override
  Widget build(BuildContext context) {
    final pct = double.tryParse(
          item.percentageAccom.replaceAll('%', '').trim(),
        ) ??
        0;

    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: const CupertinoNavigationBar(
        middle: Text('Details'),
        backgroundColor: AppColors.surface,
        border: Border(
          bottom: BorderSide(color: AppColors.separator, width: 0.5),
        ),
      ),
      child: SafeArea(
        top: false,
        child: Column(
          children: [
            Expanded(
              child: SingleChildScrollView(
                padding: const EdgeInsets.fromLTRB(16, 16, 16, 24),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // ── Title card ──────────────────────────────────────
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
                          Text(
                            item.activity,
                            style: const TextStyle(
                              fontSize: 18,
                              fontWeight: FontWeight.w700,
                              color: AppColors.label,
                              height: 1.3,
                            ),
                          ),
                          const SizedBox(height: 8),
                          Row(
                            children: [
                              if (item.section.isNotEmpty) ...[
                                _chip(item.section, AppColors.primary),
                                const SizedBox(width: 6),
                              ],
                              if (pct > 0)
                                _chip('${pct.toStringAsFixed(0)}%', AppColors.success),
                            ],
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 16),
                    // ── Details card ─────────────────────────────────────
                    Container(
                      width: double.infinity,
                      decoration: BoxDecoration(
                        color: AppColors.surface,
                        borderRadius: BorderRadius.circular(14),
                      ),
                      clipBehavior: Clip.antiAlias,
                      child: Column(
                        children: [
                          _detailRow('Section', item.section),
                          _divider(),
                          _detailRow('Date Conducted',
                              item.dateConducted.isNotEmpty ? item.dateConducted : '—'),
                          _divider(),
                          _detailRow('Quarter',
                              item.quarter.isNotEmpty ? item.quarter : '—'),
                          _divider(),
                          _detailRow('Year',
                              item.year.isNotEmpty ? item.year : '—'),
                          _divider(),
                          _detailRow('Target',
                              item.target.isNotEmpty ? item.target : '—'),
                          _divider(),
                          _detailRow('Achieved',
                              item.achieved.isNotEmpty ? item.achieved : '—'),
                          _divider(),
                          _detailRow(
                              'Percentage',
                              item.percentageAccom.isNotEmpty
                                  ? '${item.percentageAccom}%'
                                  : '—'),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
            ),
            // ── Action buttons ──────────────────────────────────────────
            Container(
              decoration: const BoxDecoration(
                color: AppColors.surface,
                border: Border(
                  top: BorderSide(color: AppColors.separator, width: 0.5),
                ),
              ),
              padding: const EdgeInsets.fromLTRB(16, 12, 16, 16),
              child: SafeArea(
                top: false,
                child: Column(
                  children: [
                    Row(
                      children: [
                        Expanded(
                          child: _actionButton(
                            icon: PhosphorIconsRegular.fileText,
                            label: 'Report',
                            color: AppColors.info,
                            onTap: () {
                              Navigator.push(
                                context,
                                CupertinoPageRoute(
                                  builder: (_) =>
                                      AccomplishmentReportView(item: item),
                                ),
                              );
                            },
                          ),
                        ),
                        const SizedBox(width: 8),
                        Expanded(
                          child: _actionButton(
                            icon: PhosphorIconsRegular.paperclip,
                            label: 'Attach',
                            color: AppColors.warning,
                            onTap: () {
                              Navigator.push(
                                context,
                                CupertinoPageRoute(
                                  builder: (_) =>
                                      AccomplishmentAttachmentsView(item: item),
                                ),
                              );
                            },
                          ),
                        ),
                        const SizedBox(width: 8),
                        Expanded(
                          child: _actionButton(
                            icon: PhosphorIconsRegular.copy,
                            label: 'Copy',
                            color: AppColors.primary,
                            onTap: _doCopy,
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 8),
                    Row(
                      children: [
                        Expanded(
                          child: _actionButton(
                            icon: PhosphorIconsRegular.pencilSimple,
                            label: 'Edit',
                            color: AppColors.success,
                            onTap: () {
                              Navigator.push(
                                context,
                                CupertinoPageRoute(
                                  builder: (_) => AccomplishmentEditView(
                                    item: item,
                                    onSaved: () {
                                      Navigator.pop(context);
                                    },
                                  ),
                                ),
                              );
                            },
                          ),
                        ),
                        const SizedBox(width: 8),
                        Expanded(
                          child: _actionButton(
                            icon: PhosphorIconsRegular.trash,
                            label: 'Delete',
                            color: AppColors.danger,
                            onTap: _confirmDelete,
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  // ── Helpers ───────────────────────────────────────────────────────────────

  Widget _chip(String text, Color color) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
      decoration: BoxDecoration(
        color: color.withOpacity(0.12),
        borderRadius: BorderRadius.circular(6),
      ),
      child: Text(
        text,
        style: TextStyle(
          fontSize: 11,
          fontWeight: FontWeight.w600,
          color: color,
        ),
        maxLines: 1,
        overflow: TextOverflow.ellipsis,
      ),
    );
  }

  Widget _detailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 120,
            child: Text(
              label,
              style: const TextStyle(
                fontSize: 14,
                fontWeight: FontWeight.w600,
                color: AppColors.secondaryLabel,
              ),
            ),
          ),
          Expanded(
            child: Text(
              value,
              style: const TextStyle(fontSize: 15, color: AppColors.label),
            ),
          ),
        ],
      ),
    );
  }

  Widget _divider() => Container(height: 0.5, color: AppColors.separator);

  Widget _actionButton({
    required IconData icon,
    required String label,
    required Color color,
    required VoidCallback onTap,
  }) {
    return CupertinoButton(
      onPressed: _busy ? null : onTap,
      color: color.withOpacity(0.1),
      borderRadius: BorderRadius.circular(10),
      minimumSize: const Size(0, 48),
      padding: const EdgeInsets.symmetric(vertical: 10),
      child: _busy && label == _busyLabel
          ? const CupertinoActivityIndicator(radius: 10)
          : Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Icon(icon, size: 20, color: color),
                const SizedBox(height: 4),
                Text(
                  label,
                  style: TextStyle(
                    fontSize: 13,
                    fontWeight: FontWeight.w600,
                    color: color,
                  ),
                ),
              ],
            ),
    );
  }

  Future<void> _setBusy(String label, Future<void> Function() fn) async {
    setState(() {
      _busy = true;
      _busyLabel = label;
    });
    try {
      await fn();
    } catch (e) {
      if (mounted) {
        AppDialogs.alert(context, '$label Failed', '$e');
      }
    } finally {
      if (mounted) {
        setState(() {
          _busy = false;
          _busyLabel = null;
        });
      }
    }
  }

  // ── Actions ───────────────────────────────────────────────────────────────

  void _doCopy() {
    _setBusy('Copy', () async {
      await _repo.copy(item.id);
    }).then((_) {
      if (mounted) {
        Navigator.pop(context);
      }
    });
  }

  void _confirmDelete() {
    AppDialogs.confirm(
      context,
      title: 'Delete Accomplishment',
      message: 'Are you sure you want to delete "${item.activity}"?',
      destructive: true,
      confirmText: 'Delete',
    ).then((confirmed) {
      if (confirmed == true) _doDelete();
    });
  }

  void _doDelete() {
    _setBusy('Delete', () async {
      await _repo.delete(item.id);
    }).then((_) {
      if (mounted) {
        Navigator.pop(context);
      }
    });
  }
}
