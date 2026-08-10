import 'package:flutter/cupertino.dart';
import 'package:provider/provider.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../data/models/section_user_item.dart';
import '../../../core/di.dart';
import '../view_models/section_users_view_model.dart';
import 'section_user_detail_view.dart';

/// iOS-style Section Users screen.
///
/// - Inset grouped list of users with avatar circles + status badges
/// - Count shown in the nav bar trailing
/// - Pull-to-refresh
/// - Search bar filtering by name or username
/// - Tap a user to open the read-only detail view
/// - Loading and empty states
class SectionUsersView extends StatefulWidget {
  const SectionUsersView({super.key});

  @override
  State<SectionUsersView> createState() => _SectionUsersViewState();
}

class _SectionUsersViewState extends State<SectionUsersView> {
  late SectionUsersViewModel _vm;
  final _searchController = TextEditingController();
  String _query = '';

  @override
  void initState() {
    super.initState();
    _vm = SectionUsersViewModel(DI.sectionUsers);
    _vm.load();
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  List<SectionUserItem> get _filtered {
    if (_query.isEmpty) return _vm.items;
    final q = _query.toLowerCase();
    return _vm.items.where((u) {
      return u.fullName.toLowerCase().contains(q) ||
          u.username.toLowerCase().contains(q);
    }).toList(growable: false);
  }

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider.value(
      value: _vm,
      child: CupertinoPageScaffold(
        backgroundColor: AppColors.background,
        navigationBar: const CupertinoNavigationBar(
          middle: Text('Section Users'),
          backgroundColor: AppColors.surface,
          border: Border(
              bottom: BorderSide(color: AppColors.separator, width: 0.5)),
        ),
        child: SafeArea(
          child: Consumer<SectionUsersViewModel>(
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
              final items = _filtered;
              return CustomScrollView(
                physics: const BouncingScrollPhysics(
                  parent: AlwaysScrollableScrollPhysics(),
                ),
                slivers: [
                  CupertinoSliverRefreshControl(
                    onRefresh: () => vm.load(),
                  ),
                  // ── Search bar ──────────────────────────────────────
                  SliverToBoxAdapter(
                    child: Padding(
                      padding: const EdgeInsets.fromLTRB(20, 12, 20, 4),
                      child: CupertinoSearchTextField(
                        controller: _searchController,
                        placeholder: 'Search by name or username',
                        onChanged: (v) => setState(() => _query = v),
                        style: const TextStyle(
                            color: AppColors.label, fontSize: 15),
                        decoration: BoxDecoration(
                          color: AppColors.secondaryBackground,
                          borderRadius: BorderRadius.circular(10),
                        ),
                      ),
                    ),
                  ),
                  SliverToBoxAdapter(
                    child: Padding(
                      padding: const EdgeInsets.fromLTRB(20, 12, 20, 8),
                      child: Row(
                        children: [
                          const Text(
                            'Total Users',
                            style: TextStyle(
                              fontSize: 13,
                              fontWeight: FontWeight.w600,
                              color: AppColors.secondaryLabel,
                              letterSpacing: 0.5,
                            ),
                          ),
                          const SizedBox(width: 8),
                          Container(
                            padding: const EdgeInsets.symmetric(
                                horizontal: 10, vertical: 3),
                            decoration: BoxDecoration(
                              color: AppColors.primary.withOpacity(0.12),
                              borderRadius: BorderRadius.circular(8),
                            ),
                            child: Text(
                              '${items.length}',
                              style: const TextStyle(
                                fontSize: 13,
                                fontWeight: FontWeight.w700,
                                color: AppColors.primary,
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                  if (items.isEmpty)
                    const SliverToBoxAdapter(
                      child: Padding(
                        padding: EdgeInsets.only(top: 48),
                        child: Center(
                          child: Text(
                            'No users match your search.',
                            style: TextStyle(
                              fontSize: 15,
                              color: AppColors.secondaryLabel,
                            ),
                          ),
                        ),
                      ),
                    )
                  else
                    SliverToBoxAdapter(
                      child: CupertinoListSection.insetGrouped(
                        margin: const EdgeInsets.fromLTRB(20, 8, 20, 32),
                        children: items
                            .map((u) => _UserTile(
                                  user: u,
                                  onTap: () => _openDetail(context, u),
                                ))
                            .toList(growable: false),
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

  // ── Navigation ────────────────────────────────────────────────────────────
  void _openDetail(BuildContext context, SectionUserItem user) {
    Navigator.push(
      context,
      CupertinoPageRoute(
        builder: (_) => SectionUserDetailView(user: user),
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
          Text('Loading users...',
              style: TextStyle(color: AppColors.secondaryLabel, fontSize: 15)),
        ],
      ),
    );
  }

  Widget _buildError(SectionUsersViewModel vm) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 32),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Icon(CupertinoIcons.wifi_exclamationmark,
                size: 48, color: AppColors.tertiaryLabel),
            const SizedBox(height: 16),
            const Text('Could not load users',
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
    return const Center(
      child: Padding(
        padding: EdgeInsets.symmetric(horizontal: 32),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(CupertinoIcons.person_2, size: 56, color: AppColors.tertiaryLabel),
            SizedBox(height: 16),
            Text('No users found',
                style: TextStyle(
                    fontSize: 17,
                    fontWeight: FontWeight.w600,
                    color: AppColors.label)),
            SizedBox(height: 4),
            Text('Pull down to refresh.',
                style: TextStyle(
                    fontSize: 13, color: AppColors.secondaryLabel),
                textAlign: TextAlign.center),
          ],
        ),
      ),
    );
  }
}

// ── User tile ───────────────────────────────────────────────────────────────
class _UserTile extends StatelessWidget {
  const _UserTile({required this.user, this.onTap});

  final SectionUserItem user;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    final isActive = user.acctStat.toLowerCase() == 'active';
    final badgeColor = isActive ? AppColors.success : AppColors.tertiaryLabel;
    return CupertinoListTile.notched(
      leading: _Avatar(initials: user.initials),
      title: Text(
        user.fullName,
        style: const TextStyle(
          fontSize: 16,
          fontWeight: FontWeight.w600,
          color: AppColors.label,
        ),
      ),
      subtitle: Text(
        user.username,
        style: const TextStyle(
          fontSize: 13,
          color: AppColors.secondaryLabel,
        ),
      ),
      trailing: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
            decoration: BoxDecoration(
              color: badgeColor.withOpacity(0.12),
              borderRadius: BorderRadius.circular(8),
            ),
            child: Text(
              user.acctStat,
              style: TextStyle(
                fontSize: 12,
                fontWeight: FontWeight.w600,
                color: badgeColor,
              ),
            ),
          ),
          const SizedBox(width: 4),
          const Icon(
            CupertinoIcons.chevron_right,
            size: 16,
            color: AppColors.tertiaryLabel,
          ),
        ],
      ),
      onTap: onTap,
    );
  }
}

// ── Avatar ──────────────────────────────────────────────────────────────────
class _Avatar extends StatelessWidget {
  const _Avatar({required this.initials});

  final String initials;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 40,
      height: 40,
      decoration: BoxDecoration(
        color: AppColors.primary.withOpacity(0.14),
        shape: BoxShape.circle,
      ),
      alignment: Alignment.center,
      child: Text(
        initials,
        style: const TextStyle(
          fontSize: 15,
          fontWeight: FontWeight.w700,
          color: AppColors.primary,
        ),
      ),
    );
  }
}
