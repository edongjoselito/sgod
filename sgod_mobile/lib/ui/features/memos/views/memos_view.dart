import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart' show DataTable, DataColumn, DataRow, DataCell, Material, Colors, WidgetStateProperty, WidgetState, TableBorder;
import 'package:provider/provider.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../data/models/memo_item.dart';
import '../../../../data/repositories/memos_repository.dart';
import '../../../core/di.dart';
import '../view_models/memos_view_model.dart';
import 'memo_detail_view.dart';
import 'memo_edit_view.dart';

/// iOS-style Memos page with list, table, and pagination.
class MemosView extends StatefulWidget {
  const MemosView({super.key, this.onMenuTap});

  final VoidCallback? onMenuTap;

  @override
  State<MemosView> createState() => _MemosViewState();
}

class _MemosViewState extends State<MemosView> {
  late MemosViewModel _vm;
  int _viewMode = 0; // 0 = list, 1 = table
  final _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    _vm = MemosViewModel(MemosRepository(DI.api, DI.cache));
    _vm.load();
    _scrollController.addListener(_onScroll);
  }

  @override
  void dispose() {
    _scrollController.removeListener(_onScroll);
    _scrollController.dispose();
    super.dispose();
  }

  void _onScroll() {
    if (_scrollController.position.pixels >=
        _scrollController.position.maxScrollExtent - 300) {
      _vm.loadMore();
    }
  }

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider.value(
      value: _vm,
      child: CupertinoPageScaffold(
        backgroundColor: AppColors.background,
        child: CustomScrollView(
          controller: _scrollController,
          physics: const BouncingScrollPhysics(
            parent: AlwaysScrollableScrollPhysics(),
          ),
          slivers: [
            CupertinoSliverNavigationBar(
              largeTitle: const Text('Memos'),
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
                      builder: (_) => MemoEditView(
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
            // ── Search + toggle ──────────────────────────────────────────
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.fromLTRB(12, 6, 12, 8),
                child: Row(
                  children: [
                    Expanded(
                      child: CupertinoSearchTextField(
                        placeholder: 'Search memos',
                        onChanged: (value) => _vm.query = value,
                        style: const TextStyle(
                          fontSize: 16,
                          color: AppColors.label,
                        ),
                      ),
                    ),
                    const SizedBox(width: 8),
                    CupertinoSlidingSegmentedControl<int>(
                      groupValue: _viewMode,
                      thumbColor: AppColors.surface,
                      children: {
                        0: const Padding(
                          padding: EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                          child: Icon(CupertinoIcons.list_bullet, size: 18),
                        ),
                        1: const Padding(
                          padding: EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                          child: Icon(CupertinoIcons.square_grid_2x2, size: 18),
                        ),
                      },
                      onValueChanged: (v) => setState(() => _viewMode = v ?? 0),
                    ),
                  ],
                ),
              ),
            ),
            // ── Count label ──────────────────────────────────────────────
            SliverToBoxAdapter(
              child: Consumer<MemosViewModel>(
                builder: (context, vm, _) {
                  if (vm.all.isEmpty) return const SizedBox.shrink();
                  return Padding(
                    padding: const EdgeInsets.fromLTRB(16, 0, 16, 4),
                    child: Text(
                      '${vm.items.length} of ${vm.totalCount} memos',
                      style: const TextStyle(
                        fontSize: 12,
                        color: AppColors.tertiaryLabel,
                      ),
                    ),
                  );
                },
              ),
            ),
            // ── Content (state-driven) ───────────────────────────────────
            Consumer<MemosViewModel>(
              builder: (context, vm, _) {
                if (vm.isLoading && vm.all.isEmpty) {
                  return const SliverFillRemaining(
                    hasScrollBody: false,
                    child: _LoadingState(label: 'Loading memos...'),
                  );
                }
                if (vm.error != null && vm.all.isEmpty) {
                  return SliverFillRemaining(
                    hasScrollBody: false,
                    child: _ErrorState(error: vm.error!, onRetry: vm.load),
                  );
                }
                if (vm.items.isEmpty) {
                  return const SliverFillRemaining(
                    hasScrollBody: false,
                    child: _EmptyState(),
                  );
                }
                // ── List or table slivers ────────────────────────────────
                return SliverMainAxisGroup(
                  slivers: [
                    if (_viewMode == 0)
                      _buildListSliver(vm.items)
                    else
                      _buildTableSliver(vm.items),
                    // ── Pagination footer ────────────────────────────────
                    if (vm.isLoadingMore)
                      const SliverToBoxAdapter(
                        child: Padding(
                          padding: EdgeInsets.all(12),
                          child: Center(
                            child: CupertinoActivityIndicator(radius: 12),
                          ),
                        ),
                      ),
                    if (vm.hasMore && !vm.isLoadingMore)
                      SliverToBoxAdapter(
                        child: Padding(
                          padding: const EdgeInsets.fromLTRB(12, 8, 12, 16),
                          child: CupertinoButton(
                            onPressed: vm.loadMore,
                            color: AppColors.secondaryBackground,
                            borderRadius: BorderRadius.circular(10),
                            minSize: 36,
                            child: const Text(
                              'Load More',
                              style: TextStyle(
                                fontSize: 14,
                                fontWeight: FontWeight.w600,
                                color: AppColors.label,
                              ),
                            ),
                          ),
                        ),
                      ),
                    if (!vm.hasMore && vm.items.isNotEmpty)
                      const SliverToBoxAdapter(
                        child: Padding(
                          padding: EdgeInsets.all(12),
                          child: Center(
                            child: Text(
                              '— End of list —',
                              style: TextStyle(
                                fontSize: 12,
                                color: AppColors.tertiaryLabel,
                              ),
                            ),
                          ),
                        ),
                      ),
                  ],
                );
              },
            ),
          ],
        ),
      ),
    );
  }

  // ── List sliver (compact rows) ───────────────────────────────────────────
  Widget _buildListSliver(List<MemoItem> items) {
    return SliverPadding(
      padding: const EdgeInsets.fromLTRB(12, 0, 12, 0),
      sliver: SliverToBoxAdapter(
        child: Container(
          decoration: BoxDecoration(
            color: AppColors.surface,
            borderRadius: BorderRadius.circular(12),
          ),
          clipBehavior: Clip.antiAlias,
          child: Column(
            children: [
              for (int i = 0; i < items.length; i++) ...[
                _MemoRow(
                  memo: items[i],
                  onTap: () {
                    Navigator.push(
                      context,
                      CupertinoPageRoute(
                        builder: (_) => MemoDetailView(
                          memo: items[i],
                          onChanged: () {},
                        ),
                      ),
                    ).then((_) {
                    if (mounted) _vm.load();
                  });
                  },
                ),
                if (i < items.length - 1)
                  Container(height: 0.5, color: AppColors.separator),
              ],
            ],
          ),
        ),
      ),
    );
  }

  // ── Table sliver (DataTable) ─────────────────────────────────────────────
  Widget _buildTableSliver(List<MemoItem> items) {
    return SliverPadding(
      padding: const EdgeInsets.fromLTRB(8, 0, 8, 0),
      sliver: SliverToBoxAdapter(
        child: SingleChildScrollView(
          scrollDirection: Axis.horizontal,
          child: Material(
            color: Colors.transparent,
            child: DataTable(
              columnSpacing: 16,
              horizontalMargin: 10,
              dataRowMinHeight: 44,
              dataRowMaxHeight: 52,
              headingRowColor: WidgetStateProperty.all(
                AppColors.secondaryBackground,
              ),
              dataRowColor: WidgetStateProperty.all(AppColors.surface),
              border: TableBorder(
                borderRadius: BorderRadius.circular(10),
                horizontalInside: BorderSide(
                  color: AppColors.separator,
                  width: 0.5,
                ),
              ),
              columns: const [
                DataColumn(
                  label: Text(
                    'Memo No.',
                    style: TextStyle(
                      fontSize: 13,
                      fontWeight: FontWeight.w700,
                      color: AppColors.label,
                    ),
                  ),
                ),
                DataColumn(
                  label: Text(
                    'Title',
                    style: TextStyle(
                      fontSize: 13,
                      fontWeight: FontWeight.w700,
                      color: AppColors.label,
                    ),
                  ),
                ),
                DataColumn(
                  label: Text(
                    'Added By',
                    style: TextStyle(
                      fontSize: 13,
                      fontWeight: FontWeight.w700,
                      color: AppColors.label,
                    ),
                  ),
                ),
              ],
              rows: items.map((m) {
                return DataRow(
                  cells: [
                    DataCell(
                      Text(
                        m.memoNo,
                        style: const TextStyle(
                          fontSize: 13,
                          fontWeight: FontWeight.w600,
                          color: AppColors.primary,
                        ),
                      ),
                    ),
                    DataCell(
                      ConstrainedBox(
                        constraints: const BoxConstraints(maxWidth: 280),
                        child: Text(
                          m.title,
                          style: const TextStyle(
                            fontSize: 13,
                            color: AppColors.label,
                          ),
                          maxLines: 2,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ),
                    ),
                    DataCell(
                      Text(
                        m.addedBy,
                        style: const TextStyle(
                          fontSize: 13,
                          color: AppColors.secondaryLabel,
                        ),
                      ),
                    ),
                  ],
                );
              }).toList(),
            ),
          ),
        ),
      ),
    );
  }
}

// ── Compact memo row ───────────────────────────────────────────────────────
class _MemoRow extends StatelessWidget {
  const _MemoRow({required this.memo, this.onTap});
  final MemoItem memo;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    return CupertinoButton(
      onPressed: onTap,
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      minSize: 0,
      child: Row(
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  memo.memoNo,
                  style: const TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.w600,
                    color: AppColors.primary,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
                const SizedBox(height: 2),
                Text(
                  memo.title,
                  style: const TextStyle(
                    fontSize: 13,
                    color: AppColors.secondaryLabel,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
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

// ── State widgets ──────────────────────────────────────────────────────────
class _LoadingState extends StatelessWidget {
  const _LoadingState({required this.label});
  final String label;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(top: 80),
      child: Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const CupertinoActivityIndicator(radius: 16),
            const SizedBox(height: 16),
            Text(label,
                style: const TextStyle(
                    color: AppColors.secondaryLabel, fontSize: 15)),
          ],
        ),
      ),
    );
  }
}

class _ErrorState extends StatelessWidget {
  const _ErrorState({required this.error, required this.onRetry});
  final String error;
  final VoidCallback onRetry;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(top: 60),
      child: Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Icon(CupertinoIcons.wifi_exclamationmark,
                size: 48, color: AppColors.tertiaryLabel),
            const SizedBox(height: 16),
            const Text('Could not load memos',
                style: TextStyle(
                    fontSize: 17,
                    fontWeight: FontWeight.w600,
                    color: AppColors.label)),
            const SizedBox(height: 4),
            Text(error,
                style: const TextStyle(
                    fontSize: 13, color: AppColors.secondaryLabel),
                textAlign: TextAlign.center),
            const SizedBox(height: 16),
            CupertinoButton(
              onPressed: onRetry,
              child: const Text('Retry'),
            ),
          ],
        ),
      ),
    );
  }
}

class _EmptyState extends StatelessWidget {
  const _EmptyState();

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(top: 80),
      child: Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              width: 64,
              height: 64,
              decoration: BoxDecoration(
                color: AppColors.primary.withOpacity(0.1),
                borderRadius: BorderRadius.circular(16),
              ),
              child: const Icon(CupertinoIcons.doc_text,
                  size: 28, color: AppColors.primary),
            ),
            const SizedBox(height: 20),
            const Text('No memos found',
                style: TextStyle(
                    fontSize: 17,
                    fontWeight: FontWeight.w600,
                    color: AppColors.label)),
            const SizedBox(height: 6),
            const Text('Pull down to refresh.',
                style: TextStyle(
                    fontSize: 15, color: AppColors.secondaryLabel)),
          ],
        ),
      ),
    );
  }
}
