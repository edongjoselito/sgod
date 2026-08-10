import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../data/models/user_profile.dart';
import '../../accomplishments/views/accomplishments_view.dart';
import '../../issues/views/issues_view.dart';
import '../../section_users/views/section_users_view.dart';
import '../../whereabouts/views/whereabouts_view.dart';
import '../../profile/views/profile_view.dart';
import '../../settings/views/settings_view.dart';

/// "More" tab — iOS-style grouped list linking to secondary features.
class MoreView extends StatelessWidget {
  const MoreView({super.key, required this.profile});

  final UserProfile profile;

  @override
  Widget build(BuildContext context) {
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      child: CustomScrollView(
        slivers: [
          const CupertinoSliverNavigationBar(
            largeTitle: Text('More'),
            backgroundColor: AppColors.surface,
            border: Border(
                bottom: BorderSide(color: AppColors.separator, width: 0.5)),
          ),
          SliverSafeArea(
            minimum: const EdgeInsets.only(top: 8),
            sliver: SliverList(
              delegate: SliverChildListDelegate([
                // ── Section Tools ─────────────────────────────────────
                CupertinoListSection.insetGrouped(
                  header: const Text('Section Tools'),
                  children: [
                    _navTile(
                      context,
                      icon: PhosphorIconsRegular.checkSquare,
                      iconColor: AppColors.success,
                      title: 'Accomplishments',
                      page: AccomplishmentsView(section: profile.section),
                    ),
                    _navTile(
                      context,
                      icon: PhosphorIconsRegular.mapPin,
                      iconColor: AppColors.warning,
                      title: 'Whereabouts',
                      page: const WhereaboutsView(),
                    ),
                    _navTile(
                      context,
                      icon: PhosphorIconsRegular.warningCircle,
                      iconColor: AppColors.danger,
                      title: 'Issues / Concerns',
                      page: const IssuesView(),
                    ),
                  ],
                ),
                const SizedBox(height: 24),

                // ── Management ────────────────────────────────────────
                CupertinoListSection.insetGrouped(
                  header: const Text('Management'),
                  children: [
                    _navTile(
                      context,
                      icon: PhosphorIconsRegular.users,
                      iconColor: AppColors.primary,
                      title: 'Section Users',
                      page: const SectionUsersView(),
                    ),
                  ],
                ),
                const SizedBox(height: 24),

                // ── Account ───────────────────────────────────────────
                CupertinoListSection.insetGrouped(
                  header: const Text('Account'),
                  children: [
                    _navTile(
                      context,
                      icon: PhosphorIconsRegular.user,
                      iconColor: AppColors.primary,
                      title: 'Profile',
                      page: ProfileView(profile: profile),
                    ),
                    _navTile(
                      context,
                      icon: PhosphorIconsRegular.gear,
                      iconColor: AppColors.tertiaryLabel,
                      title: 'Settings',
                      page: const SettingsView(),
                    ),
                  ],
                ),
                const SizedBox(height: 32),
              ]),
            ),
          ),
        ],
      ),
    );
  }

  Widget _navTile(
    BuildContext context, {
    required IconData icon,
    required Color iconColor,
    required String title,
    required Widget page,
  }) {
    return CupertinoListTile.notched(
      leading: Icon(icon, color: iconColor, size: 22),
      title: Text(title),
      trailing: const CupertinoListTileChevron(),
      onTap: () => Navigator.of(context).push(
        CupertinoPageRoute(builder: (_) => page),
      ),
    );
  }
}
