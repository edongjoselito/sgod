import 'package:flutter/cupertino.dart';
import 'package:provider/provider.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../data/models/accomplishment_item.dart';
import '../../../../data/repositories/accomplishments_repository.dart';
import '../../../core/di.dart';
import '../view_models/accomplishments_view_model.dart';

/// iOS-style Accomplishments list.
///
/// Features:
/// - [CupertinoPageScaffold] with a [CupertinoNavigationBar] (middle:
///   "Accomplishments")
/// - [CupertinoSearchTextField] for filtering by activity, section, or date
/// - [CupertinoSliverRefreshControl] for pull-to-refresh
/// - [CupertinoListSection.insetGrouped] rows: activity title, section
///   subtitle, date + percentage badge when available
/// - Loading / empty / error states
class AccomplishmentsView extends StatefulWidget {
  const AccomplishmentsView({super.key, this.section = ''});

  /// Optional section filter passed to the API.
  final String section;

  @override
  State<AccomplishmentsView> createState() => _AccomplishmentsViewState();
}

class _AccomplishmentsViewState extends State<AccomplishmentsView> {
  late AccomplishmentsViewModel _vm;

  @override
  void initState() {
    super.initState();
    _vm = AccomplishmentsViewModel(
      AccomplishmentsRepository(DI.api),
      section: widget.section,
    );
    _vm.load();
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
            const CupertinoSliverNavigationBar(
              largeTitle: Text('Accomplishments'),
              backgroundColor: AppColors.surface,
              border: Border(
                bottom: BorderSide(color: AppColors.separator, width: 0.5),
              ),
            ),
            CupertinoSliverRefreshControl(
              onRefresh: () => _vm.load(),
            ),
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.fromLTRB(20, 8, 20, 12),
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
            SliverFillRemaining(
              hasScrollBody: false,
              child: Consumer<AccomplishmentsViewModel>(
                builder: (context, vm, _) {
                  if (vm.isLoading && vm.all.isEmpty) {
                    return _buildLoading();
                  }
                  if (vm.error != null && vm.all.isEmpty) {
                    return _buildError(vm);
                  }
                  if (vm.items.isEmpty) {
                    return _buildEmpty();
                  }
                  return _buildList(vm.items);
                },
              ),
            ),
          ],
        ),
      ),
    );
  }

  // ── States ───────────────────────────────────────────────────────────────

  Widget _buildLoading() {
    return const Padding(
      padding: EdgeInsets.only(top: 80),
      child: Center(
        child: Column(
          children: [
            CupertinoActivityIndicator(radius: 16),
            SizedBox(height: 16),
            Text(
              'Loading accomplishments...',
              style: TextStyle(color: AppColors.secondaryLabel, fontSize: 15),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildError(AccomplishmentsViewModel vm) {
    return Padding(
      padding: const EdgeInsets.only(top: 60),
      child: Center(
        child: Column(
          children: [
            const Icon(
              CupertinoIcons.wifi_exclamationmark,
              size: 48,
              color: AppColors.tertiaryLabel,
            ),
            const SizedBox(height: 16),
            const Text(
              'Could not load accomplishments',
              style: TextStyle(
                fontSize: 17,
                fontWeight: FontWeight.w600,
                color: AppColors.label,
              ),
            ),
            const SizedBox(height: 4),
            Text(
              vm.error!,
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
                color: AppColors.success.withOpacity(0.1),
                borderRadius: BorderRadius.circular(16),
              ),
              child: const Icon(
                CupertinoIcons.check_mark_circled,
                size: 28,
                color: AppColors.success,
              ),
            ),
            const SizedBox(height: 20),
            const Text(
              'No accomplishments found',
              style: TextStyle(
                fontSize: 17,
                fontWeight: FontWeight.w600,
                color: AppColors.label,
              ),
            ),
            const SizedBox(height: 6),
            const Text(
              'Pull down to refresh.',
              style: TextStyle(
                fontSize: 15,
                color: AppColors.secondaryLabel,
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildList(List<AccomplishmentItem> items) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(20, 0, 20, 32),
      child: CupertinoListSection.insetGrouped(
        hasLeading: false,
        children: items.map(_AccomplishmentTile.new).toList(),
      ),
    );
  }
}

// ── Accomplishment tile ────────────────────────────────────────────────────
class _AccomplishmentTile extends StatelessWidget {
  const _AccomplishmentTile(this.item);
  final AccomplishmentItem item;

  @override
  Widget build(BuildContext context) {
    final pct = double.tryParse(
          item.percentageAccom.replaceAll('%', '').trim(),
        ) ??
        0;
    return CupertinoListTile.notched(
      title: Text(
        item.activity,
        style: const TextStyle(
          fontSize: 15,
          fontWeight: FontWeight.w500,
          color: AppColors.label,
        ),
        maxLines: 2,
        overflow: TextOverflow.ellipsis,
      ),
      subtitle: Text(
        item.section,
        style: const TextStyle(
          fontSize: 13,
          color: AppColors.secondaryLabel,
        ),
        maxLines: 1,
        overflow: TextOverflow.ellipsis,
      ),
      additionalInfo: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          if (item.dateConducted.isNotEmpty)
            Flexible(
              child: Text(
                item.dateConducted,
                style: const TextStyle(
                  fontSize: 13,
                  color: AppColors.tertiaryLabel,
                ),
                overflow: TextOverflow.ellipsis,
              ),
            ),
          if (pct > 0) ...[
            const SizedBox(width: 8),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
              decoration: BoxDecoration(
                color: AppColors.success.withOpacity(0.12),
                borderRadius: BorderRadius.circular(6),
              ),
              child: Text(
                '${pct.toStringAsFixed(0)}%',
                style: const TextStyle(
                  fontSize: 12,
                  fontWeight: FontWeight.w600,
                  color: AppColors.success,
                ),
              ),
            ),
          ],
        ],
      ),
      onTap: () {},
    );
  }
}
