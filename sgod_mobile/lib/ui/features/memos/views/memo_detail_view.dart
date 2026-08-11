import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../core/widgets/app_dialogs.dart';
import '../../../../data/models/memo_item.dart';
import '../../../core/di.dart';
import 'memo_edit_view.dart';

/// Full-screen memo detail page with all info + action buttons.
///
/// Actions: Edit, Delete.
class MemoDetailView extends StatefulWidget {
  const MemoDetailView({
    super.key,
    required this.memo,
    this.onChanged,
  });

  final MemoItem memo;
  final VoidCallback? onChanged;

  @override
  State<MemoDetailView> createState() => _MemoDetailViewState();
}

class _MemoDetailViewState extends State<MemoDetailView> {
  bool _busy = false;
  String? _busyLabel;

  MemoItem get memo => widget.memo;

  @override
  Widget build(BuildContext context) {
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
                            memo.title.isNotEmpty ? memo.title : 'Untitled Memo',
                            style: const TextStyle(
                              fontSize: 18,
                              fontWeight: FontWeight.w700,
                              color: AppColors.label,
                              height: 1.3,
                            ),
                          ),
                          const SizedBox(height: 8),
                          if (memo.memoNo.isNotEmpty)
                            _chip(memo.memoNo, AppColors.primary),
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
                          _detailRow('Memo No.',
                              memo.memoNo.isNotEmpty ? memo.memoNo : '—'),
                          _divider(),
                          _detailRow('Title',
                              memo.title.isNotEmpty ? memo.title : '—'),
                          _divider(),
                          _detailRow('Added By',
                              memo.addedBy.isNotEmpty ? memo.addedBy : '—'),
                          _divider(),
                          _detailRow('Section Group',
                              memo.secGroup.isNotEmpty ? memo.secGroup : '—'),
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
                              builder: (_) => MemoEditView(
                                memo: memo,
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

  Widget _chip(String text, Color color) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.12),
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

  Future<void> _confirmDelete() async {
    final ok = await AppDialogs.confirm(
      context,
      title: 'Delete Memo',
      message:
          'Are you sure you want to delete "${memo.title.isNotEmpty ? memo.title : memo.memoNo}"?',
      confirmText: 'Delete',
      cancelText: 'Cancel',
      destructive: true,
    );
    if (ok == true) {
      _doDelete();
    }
  }

  void _doDelete() {
    _setBusy('Delete', () async {
      await DI.memos.delete(memo.id);
    }).then((_) {
      if (mounted) {
        Navigator.pop(context);
      }
    });
  }
}
