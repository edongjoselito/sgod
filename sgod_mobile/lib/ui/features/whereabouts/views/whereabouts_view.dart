import 'package:flutter/cupertino.dart';
import 'package:provider/provider.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../data/models/whereabouts_item.dart';
import '../../../../data/repositories/whereabouts_repository.dart';
import '../../../core/di.dart';
import '../view_models/whereabouts_view_model.dart';

/// iOS-style whereabouts list — card-style entries with color-coded status
/// badges, location with a map pin, and pull-to-refresh.
///
/// Features:
/// - [CupertinoPageScaffold] with [CupertinoNavigationBar] (middle: "Whereabouts")
/// - Each item: employee name, status badge, activity, location (map pin), date
/// - Card-style layout (white rounded containers, not flat list tiles)
/// - Pull-to-refresh, loading and empty states
class WhereaboutsView extends StatefulWidget {
  const WhereaboutsView({super.key});

  @override
  State<WhereaboutsView> createState() => _WhereaboutsViewState();
}

class _WhereaboutsViewState extends State<WhereaboutsView> {
  late WhereaboutsViewModel _vm;

  @override
  void initState() {
    super.initState();
    _vm = WhereaboutsViewModel(WhereaboutsRepository(DI.api));
    _vm.load();
  }

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider.value(
      value: _vm,
      child: CupertinoPageScaffold(
        backgroundColor: AppColors.background,
        navigationBar: const CupertinoNavigationBar(
          middle: Text('Whereabouts'),
          backgroundColor: AppColors.surface,
          border: Border(
            bottom: BorderSide(color: AppColors.separator, width: 0.5),
          ),
        ),
        child: SafeArea(
          child: Consumer<WhereaboutsViewModel>(
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
                    onRefresh: () => vm.load(silent: true),
                  ),
                  SliverPadding(
                    padding: const EdgeInsets.fromLTRB(20, 12, 20, 32),
                    sliver: SliverList(
                      delegate: SliverChildBuilderDelegate(
                        (context, index) {
                          final item = vm.items[index];
                          return Padding(
                            padding: const EdgeInsets.only(bottom: 12),
                            child: _WhereaboutsCard(item: item),
                          );
                        },
                        childCount: vm.items.length,
                      ),
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

  // ── States ──────────────────────────────────────────────────────────────
  Widget _buildLoading() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: const [
          CupertinoActivityIndicator(radius: 16),
          SizedBox(height: 16),
          Text(
            'Loading whereabouts...',
            style: TextStyle(color: AppColors.secondaryLabel, fontSize: 15),
          ),
        ],
      ),
    );
  }

  Widget _buildError(WhereaboutsViewModel vm) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 32),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(
              CupertinoIcons.wifi_exclamationmark,
              size: 48,
              color: AppColors.tertiaryLabel,
            ),
            const SizedBox(height: 16),
            const Text(
              'Could not load whereabouts',
              style: TextStyle(
                fontSize: 17,
                fontWeight: FontWeight.w600,
                color: AppColors.label,
              ),
            ),
            const SizedBox(height: 4),
            Text(
              vm.error ?? '',
              style: const TextStyle(
                fontSize: 13,
                color: AppColors.secondaryLabel,
              ),
              textAlign: TextAlign.center,
            ),
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
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(
              CupertinoIcons.location_slash,
              size: 48,
              color: AppColors.tertiaryLabel,
            ),
            const SizedBox(height: 16),
            const Text(
              'No whereabouts records',
              style: TextStyle(
                fontSize: 17,
                fontWeight: FontWeight.w600,
                color: AppColors.label,
              ),
            ),
            const SizedBox(height: 4),
            const Text(
              'Pull down to refresh.',
              style: TextStyle(
                fontSize: 13,
                color: AppColors.secondaryLabel,
              ),
              textAlign: TextAlign.center,
            ),
          ],
        ),
      ),
    );
  }
}

/// A single card-style whereabouts entry.
class _WhereaboutsCard extends StatelessWidget {
  const _WhereaboutsCard({required this.item});

  final WhereaboutsItem item;

  @override
  Widget build(BuildContext context) {
    final statusColor = _statusColor(item.status);
    return Container(
      decoration: BoxDecoration(
        color: AppColors.surface,
        borderRadius: BorderRadius.circular(14),
      ),
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // ── Header: name + status badge ──────────────────────────────
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Expanded(
                child: Text(
                  _fullName(),
                  style: const TextStyle(
                    fontSize: 17,
                    fontWeight: FontWeight.w600,
                    color: AppColors.label,
                  ),
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                ),
              ),
              const SizedBox(width: 8),
              _StatusBadge(label: item.status, color: statusColor),
            ],
          ),
          if (item.section.trim().isNotEmpty) ...[
            const SizedBox(height: 4),
            Text(
              item.section,
              style: const TextStyle(
                fontSize: 13,
                color: AppColors.secondaryLabel,
              ),
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
            ),
          ],
          // ── Divider ──────────────────────────────────────────────────
          const Padding(
            padding: EdgeInsets.symmetric(vertical: 12),
            child: SizedBox(
              height: 0.5,
              child: ColoredBox(color: AppColors.separator, child: SizedBox.expand()),
            ),
          ),
          // ── Activity ────────────────────────────────────────────────
          if (item.activity.trim().isNotEmpty)
            Text(
              item.activity,
              style: const TextStyle(
                fontSize: 14,
                color: AppColors.label,
                height: 1.35,
              ),
              maxLines: 3,
              overflow: TextOverflow.ellipsis,
            ),
          // ── Location + date ─────────────────────────────────────────
          const SizedBox(height: 12),
          if (item.location.trim().isNotEmpty)
            Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Icon(
                  CupertinoIcons.location_solid,
                  size: 15,
                  color: AppColors.secondaryLabel,
                ),
                const SizedBox(width: 6),
                Expanded(
                  child: Text(
                    item.location,
                    style: const TextStyle(
                      fontSize: 13,
                      color: AppColors.secondaryLabel,
                    ),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                ),
              ],
            ),
          if (item.date.trim().isNotEmpty) ...[
            const SizedBox(height: 6),
            Row(
              children: [
                const Icon(
                  CupertinoIcons.calendar,
                  size: 15,
                  color: AppColors.tertiaryLabel,
                ),
                const SizedBox(width: 6),
                Text(
                  _formatDate(item.date),
                  style: const TextStyle(
                    fontSize: 13,
                    color: AppColors.tertiaryLabel,
                  ),
                ),
              ],
            ),
          ],
        ],
      ),
    );
  }

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
    if (s.contains('field') || s.contains('on field')) {
      return AppColors.warning;
    }
    if (s.contains('office') || s.contains('in office')) {
      return AppColors.success;
    }
    if (s.contains('leave')) {
      return AppColors.danger;
    }
    return AppColors.info;
  }
}

/// A rounded status badge with a colored background and matching text.
class _StatusBadge extends StatelessWidget {
  const _StatusBadge({required this.label, required this.color});

  final String label;
  final Color color;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
      decoration: BoxDecoration(
        color: color.withOpacity(0.14),
        borderRadius: BorderRadius.circular(8),
      ),
      child: Text(
        label,
        style: TextStyle(
          fontSize: 12,
          fontWeight: FontWeight.w600,
          color: color,
        ),
        maxLines: 1,
        overflow: TextOverflow.ellipsis,
      ),
    );
  }
}
