import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../core/widgets/app_dialogs.dart';
import '../../../../data/models/whereabouts_item.dart';
import '../../../../data/repositories/whereabouts_repository.dart';
import '../../../core/di.dart';
import 'whereabouts_edit_view.dart';

/// Full-screen whereabouts detail page with all info + action buttons.
///
/// Actions: Edit, Delete.
class WhereaboutsDetailView extends StatefulWidget {
  const WhereaboutsDetailView({
    super.key,
    required this.item,
    this.onChanged,
  });

  final WhereaboutsItem item;
  final VoidCallback? onChanged;

  @override
  State<WhereaboutsDetailView> createState() => _WhereaboutsDetailViewState();
}

class _WhereaboutsDetailViewState extends State<WhereaboutsDetailView> {
  bool _busy = false;
  String? _busyLabel;

  WhereaboutsItem get item => widget.item;
  WhereaboutsRepository get _repo => DI.whereabouts;

  @override
  Widget build(BuildContext context) {
    final statusColor = _statusColor(item.status);
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
                            _fullName(),
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
                              _chip(item.status, statusColor),
                              if (item.section.isNotEmpty) ...[
                                const SizedBox(width: 6),
                                _chip(item.section, AppColors.primary),
                              ],
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
                          _detailRow('Section',
                              item.section.isNotEmpty ? item.section : '—'),
                          _divider(),
                          _detailRow('Date',
                              item.date.isNotEmpty ? _formatDate(item.date) : '—'),
                          _divider(),
                          _detailRow('Location',
                              item.location.isNotEmpty ? item.location : '—'),
                          _divider(),
                          _detailRow('Activity',
                              item.activity.isNotEmpty ? item.activity : '—'),
                          _divider(),
                          _detailRow('Status',
                              item.status.isNotEmpty ? item.status : '—'),
                          _divider(),
                          _detailRow('Notes',
                              item.notes.isNotEmpty ? item.notes : '—'),
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
                              builder: (_) => WhereaboutsEditView(
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

  String _fullName() {
    final f = item.fName.trim();
    final l = item.lName.trim();
    if (f.isEmpty && l.isEmpty) return 'Unknown';
    if (f.isEmpty) return l;
    if (l.isEmpty) return f;
    return '$f $l';
  }

  /// Formats an ISO date (yyyy-MM-dd) into a friendlier "MMM d, yyyy" form.
  /// Falls back to the raw string if parsing fails.
  String _formatDate(String raw) {
    final trimmed = raw.trim();
    if (trimmed.isEmpty) return trimmed;
    try {
      final parsed = DateTime.tryParse(trimmed);
      if (parsed == null) return trimmed;
      const months = [
        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
      ];
      return '${months[parsed.month - 1]} ${parsed.day}, ${parsed.year}';
    } catch (_) {
      return trimmed;
    }
  }

  Color _statusColor(String status) {
    final s = status.trim().toLowerCase();
    if (s.contains('field')) return AppColors.success;
    if (s.contains('leave')) return AppColors.warning;
    if (s.contains('official') || s.contains('business')) return AppColors.info;
    if (s.contains('office') || s.contains('out')) return AppColors.info;
    return AppColors.tertiaryLabel;
  }

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

  void _confirmDelete() {
    AppDialogs.confirm(
      context,
      title: 'Delete Whereabouts',
      message:
          'Are you sure you want to delete the whereabouts for "${_fullName()}"?',
      confirmText: 'Delete',
      destructive: true,
    ).then((ok) {
      if (ok == true) _doDelete();
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
