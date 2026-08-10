import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';
import 'package:provider/provider.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../data/models/accomplishment_item.dart';
import '../../../../data/repositories/accomplishments_repository.dart';
import '../../../core/di.dart';
import '../view_models/accomplishments_view_model.dart';
import 'accomplishment_detail_view.dart';
import 'accomplishment_edit_view.dart';

/// iOS-style Accomplishments list with full-screen detail page.
class AccomplishmentsView extends StatefulWidget {
  const AccomplishmentsView({super.key, this.section = '', this.onMenuTap});

  final String section;
  final VoidCallback? onMenuTap;

  @override
  State<AccomplishmentsView> createState() => _AccomplishmentsViewState();
}

class _AccomplishmentsViewState extends State<AccomplishmentsView> {
  late AccomplishmentsViewModel _vm;

  @override
  void initState() {
    super.initState();
    _vm = AccomplishmentsViewModel(
      AccomplishmentsRepository(DI.api, DI.cache),
      section: widget.section,
    );
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
              largeTitle: const Text('Accomplishments'),
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
                onPressed: () => _showAddSheet(context),
                child: const Icon(CupertinoIcons.add, size: 24),
              ),
            ),
            CupertinoSliverRefreshControl(
              onRefresh: () => _vm.load(),
            ),
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.fromLTRB(12, 6, 12, 8),
                child: CupertinoSearchTextField(
                  placeholder: 'Search accomplishments',
                  onChanged: (value) => _vm.query = value,
                  style: const TextStyle(
                    fontSize: 16,
                    color: AppColors.label,
                  ),
                ),
              ),
            ),
            SliverToBoxAdapter(
              child: Consumer<AccomplishmentsViewModel>(
                builder: (context, vm, _) {
                  if (vm.all.isEmpty) return const SizedBox.shrink();
                  return Padding(
                    padding: const EdgeInsets.fromLTRB(16, 0, 16, 4),
                    child: Text(
                      '${vm.items.length} accomplishment${vm.items.length == 1 ? '' : 's'}',
                      style: const TextStyle(
                        fontSize: 12,
                        color: AppColors.tertiaryLabel,
                      ),
                    ),
                  );
                },
              ),
            ),
            Consumer<AccomplishmentsViewModel>(
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
                          Text('Loading accomplishments...',
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
                          const Text('Could not load accomplishments',
                              style: TextStyle(
                                  fontSize: 17,
                                  fontWeight: FontWeight.w600,
                                  color: AppColors.label)),
                          const SizedBox(height: 4),
                          Text(vm.error!,
                              style: const TextStyle(
                                  fontSize: 13,
                                  color: AppColors.secondaryLabel),
                              textAlign: TextAlign.center),
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
                          Icon(CupertinoIcons.check_mark_circled,
                              size: 48, color: AppColors.tertiaryLabel),
                          SizedBox(height: 16),
                          Text('No accomplishments found',
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
                            _AccomplishmentRow(
                              item: vm.items[i],
                              onTap: () {
                                Navigator.push(
                                  context,
                                  CupertinoPageRoute(
                                    builder: (_) => AccomplishmentDetailView(
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

  void _showAddSheet(BuildContext context) {
    Navigator.push(
      context,
      CupertinoPageRoute(
        builder: (_) => AccomplishmentEditView(
          onSaved: () => _vm.load(),
        ),
      ),
    );
  }
}

// ── Compact accomplishment row ─────────────────────────────────────────────
class _AccomplishmentRow extends StatelessWidget {
  const _AccomplishmentRow({required this.item, required this.onTap});
  final AccomplishmentItem item;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    final pct = double.tryParse(
          item.percentageAccom.replaceAll('%', '').trim(),
        ) ??
        0;
    return CupertinoButton(
      onPressed: onTap,
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      minSize: 0,
      child: Row(
        children: [
          Container(
            width: 30,
            height: 30,
            decoration: BoxDecoration(
              color: AppColors.success.withOpacity(0.1),
              borderRadius: BorderRadius.circular(7),
            ),
            child: const Icon(PhosphorIconsRegular.checkSquare,
                size: 16, color: AppColors.success),
          ),
          const SizedBox(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  item.activity,
                  style: const TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.w500,
                    color: AppColors.label,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
                const SizedBox(height: 2),
                Row(
                  children: [
                    if (item.dateConducted.isNotEmpty) ...[
                      Text(
                        item.dateConducted,
                        style: const TextStyle(
                          fontSize: 11,
                          color: AppColors.tertiaryLabel,
                        ),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                      const SizedBox(width: 6),
                    ],
                    if (item.quarter.isNotEmpty) ...[
                      Text(
                        'Q${item.quarter}',
                        style: const TextStyle(
                          fontSize: 11,
                          color: AppColors.tertiaryLabel,
                        ),
                      ),
                      const SizedBox(width: 6),
                    ],
                    if (item.year.isNotEmpty)
                      Text(
                        item.year,
                        style: const TextStyle(
                          fontSize: 11,
                          color: AppColors.tertiaryLabel,
                        ),
                      ),
                  ],
                ),
              ],
            ),
          ),
          if (pct > 0) ...[
            const SizedBox(width: 8),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
              decoration: BoxDecoration(
                color: AppColors.success.withOpacity(0.12),
                borderRadius: BorderRadius.circular(6),
              ),
              child: Text(
                '${pct.toStringAsFixed(0)}%',
                style: const TextStyle(
                  fontSize: 12,
                  fontWeight: FontWeight.w700,
                  color: AppColors.success,
                ),
              ),
            ),
          ],
          const SizedBox(width: 6),
          const Icon(CupertinoIcons.chevron_right,
              size: 14, color: AppColors.tertiaryLabel),
        ],
      ),
    );
  }
}
