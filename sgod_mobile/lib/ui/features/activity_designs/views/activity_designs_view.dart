import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';
import 'package:provider/provider.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../data/models/activity_design_item.dart';
import '../../../../data/repositories/activity_designs_repository.dart';
import '../../../core/di.dart';
import '../view_models/activity_designs_view_model.dart';
import 'activity_design_detail_view.dart';
import 'activity_design_edit_view.dart';

/// iOS-style Activity Designs list.
class ActivityDesignsView extends StatefulWidget {
  const ActivityDesignsView({super.key, this.onMenuTap});

  final VoidCallback? onMenuTap;

  @override
  State<ActivityDesignsView> createState() => _ActivityDesignsViewState();
}

class _ActivityDesignsViewState extends State<ActivityDesignsView> {
  late ActivityDesignsViewModel _vm;

  @override
  void initState() {
    super.initState();
    _vm = ActivityDesignsViewModel(ActivityDesignsRepository(DI.api, DI.cache));
    _vm.load();
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
        child: CustomScrollView(
          physics: const BouncingScrollPhysics(
            parent: AlwaysScrollableScrollPhysics(),
          ),
          slivers: [
            CupertinoSliverNavigationBar(
              largeTitle: const Text('Activity Designs'),
              backgroundColor: AppColors.surface,
              border: const Border(
                bottom: BorderSide(color: AppColors.separator, width: 0.5),
              ),
              leading: widget.onMenuTap != null
                  ? CupertinoButton(
                      padding: EdgeInsets.zero,
                      onPressed: widget.onMenuTap,
                      child: const Icon(CupertinoIcons.line_horizontal_3,
                          size: 26, color: AppColors.label),
                    )
                  : null,
              trailing: CupertinoButton(
                padding: EdgeInsets.zero,
                onPressed: () {
                  Navigator.push(
                    context,
                    CupertinoPageRoute(
                      builder: (_) => ActivityDesignEditView(
                        onSaved: () => _vm.load(),
                      ),
                    ),
                  );
                },
                child: const Icon(CupertinoIcons.add,
                    size: 28, color: AppColors.primary),
              ),
            ),
            CupertinoSliverRefreshControl(
              onRefresh: () => _vm.load(),
            ),
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.fromLTRB(12, 6, 12, 8),
                child: CupertinoSearchTextField(
                  placeholder: 'Search activity designs',
                  onChanged: (value) => _vm.query = value,
                  style: const TextStyle(
                    fontSize: 16,
                    color: AppColors.label,
                  ),
                ),
              ),
            ),
            Consumer<ActivityDesignsViewModel>(
              builder: (context, vm, _) {
                if (vm.isLoading && vm.all.isEmpty) {
                  return const SliverFillRemaining(
                    hasScrollBody: false,
                    child: Center(
                      child: Column(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          CupertinoActivityIndicator(radius: 16),
                          SizedBox(height: 16),
                          Text('Loading activity designs...',
                              style: TextStyle(
                                  color: AppColors.secondaryLabel,
                                  fontSize: 15)),
                        ],
                      ),
                    ),
                  );
                }
                if (vm.error != null && vm.all.isEmpty) {
                  return SliverFillRemaining(
                    hasScrollBody: false,
                    child: Center(
                      child: Column(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          const Icon(CupertinoIcons.wifi_exclamationmark,
                              size: 48, color: AppColors.tertiaryLabel),
                          const SizedBox(height: 16),
                          const Text('Could not load activity designs',
                              style: TextStyle(
                                  fontSize: 17,
                                  fontWeight: FontWeight.w600,
                                  color: AppColors.label)),
                          const SizedBox(height: 16),
                          CupertinoButton(
                            onPressed: vm.load,
                            child: const Text('Retry'),
                          ),
                        ],
                      ),
                    ),
                  );
                }
                if (vm.items.isEmpty) {
                  return const SliverFillRemaining(
                    hasScrollBody: false,
                    child: Center(
                      child: Column(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Icon(PhosphorIconsRegular.pencilSimple,
                              size: 48, color: AppColors.tertiaryLabel),
                          SizedBox(height: 16),
                          Text('No activity designs found',
                              style: TextStyle(
                                  fontSize: 17,
                                  fontWeight: FontWeight.w600,
                                  color: AppColors.label)),
                          SizedBox(height: 6),
                          Text('Pull down to refresh.',
                              style: TextStyle(
                                  fontSize: 15,
                                  color: AppColors.secondaryLabel)),
                        ],
                      ),
                    ),
                  );
                }
                return SliverPadding(
                  padding: const EdgeInsets.fromLTRB(12, 0, 12, 32),
                  sliver: SliverToBoxAdapter(
                    child: Container(
                      decoration: BoxDecoration(
                        color: AppColors.surface,
                        borderRadius: BorderRadius.circular(12),
                      ),
                      clipBehavior: Clip.antiAlias,
                      child: Column(
                        children: [
                          for (int i = 0; i < vm.items.length; i++) ...[
                            _DesignRow(
                              item: vm.items[i],
                              onTap: () {
                                Navigator.push(
                                  context,
                                  CupertinoPageRoute(
                                    builder: (_) => ActivityDesignDetailView(
                                      item: vm.items[i],
                                      onChanged: () {},
                                    ),
                                  ),
                                ).then((_) {
                                if (mounted) _vm.load();
                              });
                              },
                            ),
                            if (i < vm.items.length - 1)
                              Container(height: 0.5, color: AppColors.separator),
                          ],
                        ],
                      ),
                    ),
                  ),
                );
              },
            ),
          ],
        ),
      ),
    );
  }
}

class _DesignRow extends StatelessWidget {
  const _DesignRow({required this.item, this.onTap});
  final ActivityDesignItem item;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    return CupertinoButton(
      onPressed: onTap,
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      minimumSize: Size.zero,
      child: Row(
        children: [
          Container(
            width: 30,
            height: 30,
            decoration: BoxDecoration(
              color: AppColors.danger.withValues(alpha: 0.1),
              borderRadius: BorderRadius.circular(7),
            ),
            child: const Icon(PhosphorIconsRegular.pencilSimple,
                size: 16, color: AppColors.danger),
          ),
          const SizedBox(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  item.activityDesignNo,
                  style: const TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.w600,
                    color: AppColors.danger,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
                const SizedBox(height: 2),
                Text(
                  item.title,
                  style: const TextStyle(
                    fontSize: 13,
                    color: AppColors.secondaryLabel,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
                if (item.activityDate.isNotEmpty || item.venue.isNotEmpty) ...[
                  const SizedBox(height: 2),
                  Text(
                    [
                      if (item.activityDate.isNotEmpty) item.activityDate,
                      if (item.venue.isNotEmpty) item.venue,
                    ].join(' • '),
                    style: const TextStyle(
                      fontSize: 11,
                      color: AppColors.tertiaryLabel,
                    ),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                ],
              ],
            ),
          ),
          const Icon(CupertinoIcons.chevron_right,
              size: 14, color: AppColors.tertiaryLabel),
        ],
      ),
    );
  }
}
