import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../core/widgets/app_dialogs.dart';
import '../../../../data/models/issue_item.dart';
import '../../../core/di.dart';
import 'issue_edit_view.dart';

/// Full-screen issue detail page with all info + action buttons.
///
/// Actions: Edit, Delete.
class IssueDetailView extends StatefulWidget {
  const IssueDetailView({
    super.key,
    required this.item,
    this.onChanged,
  });

  final IssueItem item;
  final VoidCallback? onChanged;

  @override
  State<IssueDetailView> createState() => _IssueDetailViewState();
}

class _IssueDetailViewState extends State<IssueDetailView> {
  bool _busy = false;
  String? _busyLabel;

  IssueItem get item => widget.item;

  @override
  Widget build(BuildContext context) {
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: const CupertinoNavigationBar(
        middle: Text('Issue Details'),
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
                            item.title,
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
                              _PriorityBadge(priority: item.priority),
                              const SizedBox(width: 6),
                              _StatusBadge(status: item.status),
                            ],
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 16),
                    // ── Description card ─────────────────────────────────
                    if (item.description.isNotEmpty) ...[
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
                              'Description',
                              style: TextStyle(
                                fontSize: 13,
                                fontWeight: FontWeight.w600,
                                color: AppColors.secondaryLabel,
                              ),
                            ),
                            const SizedBox(height: 8),
                            Text(
                              item.description,
                              style: const TextStyle(
                                fontSize: 15,
                                color: AppColors.label,
                                height: 1.4,
                              ),
                            ),
                          ],
                        ),
                      ),
                      const SizedBox(height: 16),
                    ],
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
                          _detailRow('Section',
                              item.section.isNotEmpty ? item.section : '—'),
                          _divider(),
                          _detailRow('Date Raised',
                              item.dateOnly.isNotEmpty ? item.dateOnly : '—'),
                          _divider(),
                          _detailRow('Raised By',
                              item.username.isNotEmpty ? item.username : '—'),
                          _divider(),
                          _detailRow('Priority', item.priority),
                          _divider(),
                          _detailRow('Status', item.status),
                          _divider(),
                          _detailRow('Year',
                              item.year.isNotEmpty ? item.year : '—'),
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
                child: Row(
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
                              builder: (_) => IssueEditView(
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
              ),
            ),
          ],
        ),
      ),
    );
  }

  // ── Helpers ───────────────────────────────────────────────────────────────

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
      color: color.withValues(alpha: 0.1),
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

  void _confirmDelete() {
    AppDialogs.confirm(
      context,
      title: 'Delete Issue',
      message: 'Are you sure you want to delete "${item.title}"?',
      confirmText: 'Delete',
      destructive: true,
    ).then((ok) {
      if (ok == true) _doDelete();
    });
  }

  void _doDelete() {
    _setBusy('Delete', () async {
      await DI.issues.delete(item.id);
    }).then((_) {
      if (mounted) {
        Navigator.pop(context);
      }
    });
  }
}

// ── Badges ──────────────────────────────────────────────────────────────────
class _PriorityBadge extends StatelessWidget {
  const _PriorityBadge({required this.priority});

  final String priority;

  @override
  Widget build(BuildContext context) {
    final Color color;
    final Color bg;
    switch (priority.toLowerCase()) {
      case 'high':
        color = AppColors.danger;
        bg = AppColors.danger.withValues(alpha: 0.12);
        break;
      case 'medium':
      case 'normal':
        color = AppColors.warning;
        bg = AppColors.warning.withValues(alpha: 0.12);
        break;
      case 'low':
        color = AppColors.success;
        bg = AppColors.success.withValues(alpha: 0.12);
        break;
      default:
        color = AppColors.tertiaryLabel;
        bg = AppColors.tertiaryLabel.withValues(alpha: 0.12);
    }
    return _Badge(label: priority, color: color, background: bg);
  }
}

class _StatusBadge extends StatelessWidget {
  const _StatusBadge({required this.status});

  final String status;

  @override
  Widget build(BuildContext context) {
    final Color color;
    final Color bg;
    switch (status.toLowerCase()) {
      case 'open':
        color = AppColors.info;
        bg = AppColors.info.withValues(alpha: 0.12);
        break;
      case 'in progress':
        color = AppColors.warning;
        bg = AppColors.warning.withValues(alpha: 0.12);
        break;
      case 'resolved':
        color = AppColors.success;
        bg = AppColors.success.withValues(alpha: 0.12);
        break;
      case 'closed':
        color = AppColors.tertiaryLabel;
        bg = AppColors.tertiaryLabel.withValues(alpha: 0.12);
        break;
      default:
        color = AppColors.tertiaryLabel;
        bg = AppColors.tertiaryLabel.withValues(alpha: 0.12);
    }
    return _Badge(label: status, color: color, background: bg);
  }
}

class _Badge extends StatelessWidget {
  const _Badge({
    required this.label,
    required this.color,
    required this.background,
  });

  final String label;
  final Color color;
  final Color background;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
      decoration: BoxDecoration(
        color: background,
        borderRadius: BorderRadius.circular(8),
      ),
      child: Text(
        label,
        style: TextStyle(
          fontSize: 12,
          fontWeight: FontWeight.w600,
          color: color,
        ),
      ),
    );
  }
}
