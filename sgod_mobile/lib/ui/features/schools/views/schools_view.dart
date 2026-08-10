import 'package:flutter/cupertino.dart';
import 'package:provider/provider.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../data/models/school_item.dart';
import '../../../../data/repositories/schools_repository.dart';
import '../../../core/di.dart';
import '../view_models/schools_view_model.dart';

/// iOS-style schools list — grouped by district with a search bar and
/// pull-to-refresh.
///
/// Features:
/// - [CupertinoPageScaffold] with [CupertinoNavigationBar] (middle: "Schools")
/// - [CupertinoSearchTextField] for client-side filtering
/// - Schools grouped by district using [CupertinoListSection.insetGrouped]
/// - School name as title, course + schoolType as subtitle
/// - Count shown in the nav bar trailing ("343 schools")
/// - Pull-to-refresh, loading and empty states
class SchoolsView extends StatefulWidget {
  const SchoolsView({super.key});

  @override
  State<SchoolsView> createState() => _SchoolsViewState();
}

class _SchoolsViewState extends State<SchoolsView> {
  late SchoolsViewModel _vm;

  @override
  void initState() {
    super.initState();
    _vm = SchoolsViewModel(SchoolsRepository(DI.api));
    _vm.load();
  }

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider.value(
      value: _vm,
      child: CupertinoPageScaffold(
        backgroundColor: AppColors.background,
        navigationBar: CupertinoNavigationBar(
          middle: const Text('Schools'),
          trailing: Consumer<SchoolsViewModel>(
            builder: (context, vm, _) {
              final count = vm.totalCount;
              if (count == 0) return const SizedBox.shrink();
              return Text(
                '$count school${count == 1 ? '' : 's'}',
                style: const TextStyle(
                  fontSize: 13,
                  color: AppColors.secondaryLabel,
                ),
              );
            },
          ),
          backgroundColor: AppColors.surface,
          border: const Border(
            bottom: BorderSide(color: AppColors.separator, width: 0.5),
          ),
        ),
        child: SafeArea(
          child: Column(
            children: [
              // ── Search bar ──────────────────────────────────────────────
              Padding(
                padding: const EdgeInsets.fromLTRB(20, 12, 20, 8),
                child: CupertinoSearchTextField(
                  placeholder: 'Search schools, districts...',
                  onChanged: (value) => _vm.search(value),
                  style: const TextStyle(
                    color: AppColors.label,
                    fontSize: 17,
                  ),
                  backgroundColor: AppColors.secondaryBackground,
                  borderRadius: BorderRadius.circular(10),
                ),
              ),
              // ── List ────────────────────────────────────────────────────
              Expanded(
                child: Consumer<SchoolsViewModel>(
                  builder: (context, vm, _) {
                    if (vm.isLoading && vm.items.isEmpty) {
                      return _buildLoading();
                    }
                    if (vm.error != null && vm.items.isEmpty) {
                      return _buildError(vm);
                    }
                    final filtered = vm.filteredItems;
                    if (filtered.isEmpty) {
                      return _buildEmpty(vm.searchQuery.isNotEmpty);
                    }
                    return CustomScrollView(
                      physics: const BouncingScrollPhysics(
                        parent: AlwaysScrollableScrollPhysics(),
                      ),
                      slivers: [
                        CupertinoSliverRefreshControl(
                          onRefresh: () => vm.load(silent: true),
                        ),
                        SliverToBoxAdapter(
                          child: _buildGroupedList(filtered),
                        ),
                      ],
                    );
                  },
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  // ── Grouped list ────────────────────────────────────────────────────────
  Widget _buildGroupedList(List<SchoolItem> items) {
    // Group by district, preserving first-seen order. Schools with an empty
    // district are bucketed under "Unassigned".
    final groups = <String, List<SchoolItem>>{};
    for (final s in items) {
      final key = s.district.trim().isEmpty ? 'Unassigned' : s.district;
      groups.putIfAbsent(key, () => []).add(s);
    }
    final districts = groups.keys.toList()..sort();

    return Padding(
      padding: const EdgeInsets.only(top: 4, bottom: 32),
      child: Column(
        children: [
          for (final district in districts) ...[
            CupertinoListSection.insetGrouped(
              header: Text(
                '$district  ·  ${groups[district]!.length} school${groups[district]!.length == 1 ? '' : 's'}',
              ),
              children: [
                for (final school in groups[district]!)
                  CupertinoListTile.notched(
                    title: Text(
                      school.schoolName,
                      style: const TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.w500,
                      ),
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                    subtitle: Text(
                      _subtitle(school),
                      style: const TextStyle(
                        fontSize: 13,
                        color: AppColors.secondaryLabel,
                      ),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                    trailing: const Icon(
                      CupertinoIcons.chevron_right,
                      size: 18,
                      color: AppColors.tertiaryLabel,
                    ),
                    onTap: () {},
                  ),
              ],
            ),
            const SizedBox(height: 8),
          ],
        ],
      ),
    );
  }

  String _subtitle(SchoolItem s) {
    final parts = <String>[];
    if (s.course.trim().isNotEmpty) parts.add(s.course);
    if (s.schoolType.trim().isNotEmpty) parts.add(s.schoolType);
    return parts.join(' • ');
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
            'Loading schools...',
            style: TextStyle(color: AppColors.secondaryLabel, fontSize: 15),
          ),
        ],
      ),
    );
  }

  Widget _buildError(SchoolsViewModel vm) {
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
              'Could not load schools',
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

  Widget _buildEmpty(bool isSearching) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 32),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(
              isSearching
                  ? CupertinoIcons.search
                  : CupertinoIcons.building_2_fill,
              size: 48,
              color: AppColors.tertiaryLabel,
            ),
            const SizedBox(height: 16),
            Text(
              isSearching ? 'No matching schools' : 'No schools yet',
              style: const TextStyle(
                fontSize: 17,
                fontWeight: FontWeight.w600,
                color: AppColors.label,
              ),
            ),
            const SizedBox(height: 4),
            Text(
              isSearching
                  ? 'Try a different search term.'
                  : 'Pull down to refresh.',
              style: const TextStyle(
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
