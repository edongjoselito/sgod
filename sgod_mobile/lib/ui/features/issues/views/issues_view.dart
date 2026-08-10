import 'package:flutter/cupertino.dart';
import 'package:provider/provider.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../data/models/issue_item.dart';
import '../../../../data/repositories/issues_repository.dart';
import '../../../core/di.dart';
import '../view_models/issues_view_model.dart';

/// iOS-style Issues / Concerns screen.
///
/// - List of issue cards with priority + status badges
/// - Pull-to-refresh
/// - Add button opens a modal form (CupertinoAlertDialog)
/// - Long-press an item to confirm deletion
/// - Empty state with icon
class IssuesView extends StatefulWidget {
  const IssuesView({super.key, this.year = ''});

  final String year;

  @override
  State<IssuesView> createState() => _IssuesViewState();
}

class _IssuesViewState extends State<IssuesView> {
  late IssuesViewModel _vm;

  @override
  void initState() {
    super.initState();
    _vm = IssuesViewModel(IssuesRepository(DI.api));
    _vm.load(year: widget.year);
  }

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider.value(
      value: _vm,
      child: CupertinoPageScaffold(
        backgroundColor: AppColors.background,
        navigationBar: CupertinoNavigationBar(
          middle: const Text('Issues / Concerns'),
          backgroundColor: AppColors.surface,
          border: const Border(
              bottom: BorderSide(color: AppColors.separator, width: 0.5)),
          trailing: CupertinoButton(
            padding: EdgeInsets.zero,
            onPressed: _showAddDialog,
            child: const Icon(CupertinoIcons.add, size: 24),
          ),
        ),
        child: SafeArea(
          child: Consumer<IssuesViewModel>(
            builder: (context, vm, _) {
              if (vm.isLoading && vm.items.isEmpty) {
                return _buildLoading();
              }
              if (vm.error != null && vm.items.isEmpty) {
                return _buildError(vm);
              }
              if (vm.items.isEmpty) {
                return _buildEmpty();
              }
              return CustomScrollView(
                physics: const BouncingScrollPhysics(
                  parent: AlwaysScrollableScrollPhysics(),
                ),
                slivers: [
                  CupertinoSliverRefreshControl(
                    onRefresh: () => vm.load(),
                  ),
                  SliverPadding(
                    padding: const EdgeInsets.fromLTRB(20, 12, 20, 32),
                    sliver: SliverList.separated(
                      itemCount: vm.items.length,
                      itemBuilder: (context, i) {
                        final item = vm.items[i];
                        return GestureDetector(
                          onLongPress: () => _confirmDelete(context, item),
                          child: _IssueCard(item: item),
                        );
                      },
                      separatorBuilder: (_, __) =>
                          const SizedBox(height: 12),
                    ),
                  ),
                ],
              );
            },
          ),
        ),
      ),
    );
  }

  // ── States ────────────────────────────────────────────────────────────────
  Widget _buildLoading() {
    return const Center(
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          CupertinoActivityIndicator(radius: 16),
          SizedBox(height: 16),
          Text('Loading issues...',
              style: TextStyle(color: AppColors.secondaryLabel, fontSize: 15)),
        ],
      ),
    );
  }

  Widget _buildError(IssuesViewModel vm) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 32),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Icon(CupertinoIcons.wifi_exclamationmark,
                size: 48, color: AppColors.tertiaryLabel),
            const SizedBox(height: 16),
            const Text('Could not load issues',
                style: TextStyle(
                    fontSize: 17,
                    fontWeight: FontWeight.w600,
                    color: AppColors.label)),
            const SizedBox(height: 4),
            Text(vm.error!,
                style: const TextStyle(
                    fontSize: 13, color: AppColors.secondaryLabel),
                textAlign: TextAlign.center),
            const SizedBox(height: 16),
            CupertinoButton(
              onPressed: _vm.load,
              child: const Text('Retry'),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildEmpty() {
    return Center(
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 32),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Icon(CupertinoIcons.tray,
                size: 56, color: AppColors.tertiaryLabel),
            const SizedBox(height: 16),
            const Text('No issues reported yet',
                style: TextStyle(
                    fontSize: 17,
                    fontWeight: FontWeight.w600,
                    color: AppColors.label)),
            const SizedBox(height: 4),
            const Text('Tap + to report a new concern.',
                style: TextStyle(
                    fontSize: 13, color: AppColors.secondaryLabel),
                textAlign: TextAlign.center),
          ],
        ),
      ),
    );
  }

  // ── Add dialog ────────────────────────────────────────────────────────────
  void _showAddDialog() {
    showCupertinoDialog(
      context: context,
      barrierDismissible: false,
      builder: (ctx) => _AddIssueDialog(
        onSubmit: ({required title, required description, required priority}) async {
          final ok = await _vm.addIssue(
            title: title,
            description: description,
            priority: priority,
          );
          if (ctx.mounted) Navigator.pop(ctx);
          if (!ok && ctx.mounted) {
            showCupertinoDialog(
              context: ctx,
              builder: (c) => CupertinoAlertDialog(
                title: const Text('Error'),
                content: Text(_vm.error ?? 'Could not save the issue.'),
                actions: [
                  CupertinoDialogAction(
                    isDefaultAction: true,
                    onPressed: () => Navigator.pop(c),
                    child: const Text('OK'),
                  ),
                ],
              ),
            );
          }
        },
      ),
    );
  }

  // ── Delete confirm ────────────────────────────────────────────────────────
  void _confirmDelete(BuildContext context, IssueItem item) {
    showCupertinoDialog(
      context: context,
      builder: (ctx) => CupertinoAlertDialog(
        title: const Text('Delete Issue'),
        content: Text('Delete "${item.title}"? This cannot be undone.'),
        actions: [
          CupertinoDialogAction(
            isDefaultAction: true,
            onPressed: () => Navigator.pop(ctx),
            child: const Text('Cancel'),
          ),
          CupertinoDialogAction(
            isDestructiveAction: true,
            onPressed: () {
              Navigator.pop(ctx);
              _vm.deleteIssue(item.id);
            },
            child: const Text('Delete'),
          ),
        ],
      ),
    );
  }
}

// ── Issue card ──────────────────────────────────────────────────────────────
class _IssueCard extends StatelessWidget {
  const _IssueCard({required this.item});

  final IssueItem item;

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        color: AppColors.surface,
        borderRadius: BorderRadius.circular(14),
      ),
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Expanded(
                child: Text(
                  item.title,
                  style: const TextStyle(
                    fontSize: 17,
                    fontWeight: FontWeight.w600,
                    color: AppColors.label,
                  ),
                ),
              ),
              const SizedBox(width: 8),
              _PriorityBadge(priority: item.priority),
            ],
          ),
          if (item.description.isNotEmpty) ...[
            const SizedBox(height: 8),
            Text(
              item.description,
              style: const TextStyle(
                fontSize: 14,
                color: AppColors.secondaryLabel,
                height: 1.35,
              ),
            ),
          ],
          const SizedBox(height: 12),
          Container(height: 0.5, color: AppColors.separator),
          const SizedBox(height: 12),
          Row(
            children: [
              _StatusBadge(status: item.status),
              const Spacer(),
              if (item.dateOnly.isNotEmpty)
                Text(
                  item.dateOnly,
                  style: const TextStyle(
                    fontSize: 12,
                    color: AppColors.tertiaryLabel,
                  ),
                ),
            ],
          ),
        ],
      ),
    );
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
        bg = AppColors.danger.withOpacity(0.12);
        break;
      case 'low':
        color = AppColors.tertiaryLabel;
        bg = AppColors.tertiaryLabel.withOpacity(0.12);
        break;
      default:
        color = AppColors.info;
        bg = AppColors.info.withOpacity(0.12);
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
      case 'resolved':
      case 'closed':
        color = AppColors.success;
        bg = AppColors.success.withOpacity(0.12);
        break;
      default:
        color = AppColors.warning;
        bg = AppColors.warning.withOpacity(0.12);
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

// ── Add dialog ──────────────────────────────────────────────────────────────
class _AddIssueDialog extends StatefulWidget {
  const _AddIssueDialog({required this.onSubmit});

  final Future<void> Function({
    required String title,
    required String description,
    required String priority,
  }) onSubmit;

  @override
  State<_AddIssueDialog> createState() => _AddIssueDialogState();
}

class _AddIssueDialogState extends State<_AddIssueDialog> {
  final _titleController = TextEditingController();
  final _descController = TextEditingController();
  String _priority = 'Normal';
  bool _submitting = false;

  @override
  void dispose() {
    _titleController.dispose();
    _descController.dispose();
    super.dispose();
  }

  bool get _canSubmit =>
      _titleController.text.trim().isNotEmpty &&
      _descController.text.trim().isNotEmpty &&
      !_submitting;

  Future<void> _submit() async {
    if (!_canSubmit) return;
    setState(() => _submitting = true);
    await widget.onSubmit(
      title: _titleController.text.trim(),
      description: _descController.text.trim(),
      priority: _priority,
    );
    if (mounted) setState(() => _submitting = false);
  }

  @override
  Widget build(BuildContext context) {
    return CupertinoAlertDialog(
      title: const Text('New Issue'),
      content: Padding(
        padding: const EdgeInsets.only(top: 8),
        child: Column(
          children: [
            CupertinoTextField(
              controller: _titleController,
              placeholder: 'Title',
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
              decoration: BoxDecoration(
                color: AppColors.secondaryBackground,
                borderRadius: BorderRadius.circular(10),
              ),
              onChanged: (_) => setState(() {}),
            ),
            const SizedBox(height: 10),
            CupertinoTextField(
              controller: _descController,
              placeholder: 'Description',
              minLines: 3,
              maxLines: 5,
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
              decoration: BoxDecoration(
                color: AppColors.secondaryBackground,
                borderRadius: BorderRadius.circular(10),
              ),
              onChanged: (_) => setState(() {}),
            ),
            const SizedBox(height: 12),
            SizedBox(
              width: double.infinity,
              child: CupertinoSlidingSegmentedControl<String>(
                groupValue: _priority,
                children: const {
                  'Low': Text('Low'),
                  'Normal': Text('Normal'),
                  'High': Text('High'),
                },
                onValueChanged: (v) {
                  if (v != null) setState(() => _priority = v);
                },
              ),
            ),
          ],
        ),
      ),
      actions: [
        CupertinoDialogAction(
          isDefaultAction: true,
          onPressed: _submitting ? null : () => Navigator.pop(context),
          child: const Text('Cancel'),
        ),
        CupertinoDialogAction(
          isDestructiveAction: false,
          onPressed: _canSubmit ? _submit : null,
          child: _submitting
              ? const CupertinoActivityIndicator(radius: 10)
              : const Text('Save'),
        ),
      ],
    );
  }
}
