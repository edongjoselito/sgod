import 'package:flutter/cupertino.dart';
import 'package:provider/provider.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../core/widgets/app_dialogs.dart';
import '../../../../data/models/issue_item.dart';
import '../../../core/di.dart';
import '../view_models/issues_view_model.dart';
import 'issue_detail_view.dart';
import 'issue_edit_view.dart';

/// iOS-style Issues / Concerns screen.
///
/// - List of issue cards with priority + status badges
/// - Pull-to-refresh
/// - Add button navigates to a full-screen edit form
/// - Tap a card to open the detail view
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
    _vm = IssuesViewModel(DI.issues);
    _vm.load(year: widget.year);
  }

  @override
  void dispose() {
    _vm.dispose();
    super.dispose();
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
            onPressed: _openAddView,
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
                          onTap: () => _openDetail(context, item),
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

  // ── Navigation ────────────────────────────────────────────────────────────
  void _openAddView() {
    Navigator.push(
      context,
      CupertinoPageRoute(
        builder: (_) => IssueEditView(
          onSaved: () => _vm.load(),
        ),
      ),
    );
  }

  void _openDetail(BuildContext context, IssueItem item) {
    Navigator.push(
      context,
      CupertinoPageRoute(
        builder: (_) => IssueDetailView(
          item: item,
          onChanged: () {},
        ),
      ),
    ).then((_) {
    if (mounted) _vm.load();
  });
  }

  // ── Delete confirm ────────────────────────────────────────────────────────
  void _confirmDelete(BuildContext context, IssueItem item) {
    AppDialogs.confirm(
      context,
      title: 'Delete Issue',
      message: 'Delete "${item.title}"? This cannot be undone.',
      confirmText: 'Delete',
      destructive: true,
    ).then((ok) {
      if (ok == true) _vm.deleteIssue(item.id);
    });
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
              maxLines: 2,
              overflow: TextOverflow.ellipsis,
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
      case 'medium':
      case 'normal':
        color = AppColors.warning;
        bg = AppColors.warning.withOpacity(0.12);
        break;
      case 'low':
        color = AppColors.success;
        bg = AppColors.success.withOpacity(0.12);
        break;
      default:
        color = AppColors.tertiaryLabel;
        bg = AppColors.tertiaryLabel.withOpacity(0.12);
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
        bg = AppColors.info.withOpacity(0.12);
        break;
      case 'in progress':
        color = AppColors.warning;
        bg = AppColors.warning.withOpacity(0.12);
        break;
      case 'resolved':
        color = AppColors.success;
        bg = AppColors.success.withOpacity(0.12);
        break;
      case 'closed':
        color = AppColors.tertiaryLabel;
        bg = AppColors.tertiaryLabel.withOpacity(0.12);
        break;
      default:
        color = AppColors.tertiaryLabel;
        bg = AppColors.tertiaryLabel.withOpacity(0.12);
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
