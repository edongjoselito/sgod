import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../data/models/user_profile.dart';
import '../../accomplishments/views/accomplishments_view.dart';
import '../../issues/views/issues_view.dart';
import '../../memos/views/memos_view.dart';
import '../../schools/views/schools_view.dart';
import '../../section_users/views/section_users_view.dart';
import '../../whereabouts/views/whereabouts_view.dart';

/// iOS-style slide-out sidebar (drawer) with all navigation items.
///
/// Opens via the hamburger icon in the nav bar leading slot. Tapping an
/// item pushes the corresponding page onto the current tab's navigator.
class AppSidebar extends StatelessWidget {
  const AppSidebar({
    super.key,
    required this.profile,
    required this.onNavigate,
    required this.onClose,
  });

  final UserProfile profile;
  final void Function(Widget page) onNavigate;
  final VoidCallback onClose;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 280,
      decoration: const BoxDecoration(
        color: AppColors.surface,
        borderRadius: BorderRadius.only(
          topRight: Radius.circular(20),
          bottomRight: Radius.circular(20),
        ),
      ),
      child: SafeArea(
        right: false,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // ── Header ──────────────────────────────────────────────────
            Padding(
              padding: const EdgeInsets.fromLTRB(20, 16, 20, 16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Container(
                        width: 40,
                        height: 40,
                        decoration: BoxDecoration(
                          color: AppColors.primary.withOpacity(0.12),
                          borderRadius: BorderRadius.circular(10),
                        ),
                        child: const Icon(
                          CupertinoIcons.person_fill,
                          color: AppColors.primary,
                          size: 22,
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              profile.fullName,
                              style: const TextStyle(
                                fontSize: 16,
                                fontWeight: FontWeight.w700,
                                color: AppColors.label,
                              ),
                              maxLines: 1,
                              overflow: TextOverflow.ellipsis,
                            ),
                            const SizedBox(height: 2),
                            Text(
                              profile.section,
                              style: const TextStyle(
                                fontSize: 12,
                                color: AppColors.secondaryLabel,
                              ),
                              maxLines: 1,
                              overflow: TextOverflow.ellipsis,
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
            Container(height: 0.5, color: AppColors.separator),
            // ── Menu items ──────────────────────────────────────────────
            Expanded(
              child: ListView(
                padding: const EdgeInsets.symmetric(vertical: 8),
                children: [
                  _section(context, 'Main', [
                    _item(
                      context,
                      icon: PhosphorIconsRegular.house,
                      iconColor: AppColors.primary,
                      title: 'Dashboard',
                      page: null, // close drawer, stay on dashboard
                    ),
                    _item(
                      context,
                      icon: PhosphorIconsRegular.bell,
                      iconColor: AppColors.warning,
                      title: 'Memos',
                      page: const MemosView(),
                    ),
                    _item(
                      context,
                      icon: PhosphorIconsRegular.buildings,
                      iconColor: AppColors.info,
                      title: 'Schools',
                      page: const SchoolsView(),
                    ),
                  ]),
                  _section(context, 'Section Tools', [
                    _item(
                      context,
                      icon: PhosphorIconsRegular.checkSquare,
                      iconColor: AppColors.success,
                      title: 'Accomplishments',
                      page: AccomplishmentsView(section: profile.section),
                    ),
                    _item(
                      context,
                      icon: PhosphorIconsRegular.mapPin,
                      iconColor: AppColors.warning,
                      title: 'Whereabouts',
                      page: const WhereaboutsView(),
                    ),
                    _item(
                      context,
                      icon: PhosphorIconsRegular.warningCircle,
                      iconColor: AppColors.danger,
                      title: 'Issues / Concerns',
                      page: const IssuesView(),
                    ),
                  ]),
                  _section(context, 'Management', [
                    _item(
                      context,
                      icon: PhosphorIconsRegular.users,
                      iconColor: AppColors.primary,
                      title: 'Section Users',
                      page: const SectionUsersView(),
                    ),
                  ]),
                ],
              ),
            ),
            Container(height: 0.5, color: AppColors.separator),
            // ── Footer ──────────────────────────────────────────────────
            Padding(
              padding: const EdgeInsets.fromLTRB(20, 12, 20, 16),
              child: Row(
                children: [
                  const Icon(PhosphorIconsRegular.graduationCap,
                      size: 20, color: AppColors.tertiaryLabel),
                  const SizedBox(width: 8),
                  const Text(
                    'DepEd ONE',
                    style: TextStyle(
                      fontSize: 14,
                      fontWeight: FontWeight.w600,
                      color: AppColors.tertiaryLabel,
                    ),
                  ),
                  const Spacer(),
                  Text(
                    'v1.0.0',
                    style: TextStyle(
                      fontSize: 12,
                      color: AppColors.tertiaryLabel.withOpacity(0.6),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _section(BuildContext context, String label, List<Widget> children) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.fromLTRB(20, 12, 20, 4),
          child: Text(
            label.toUpperCase(),
            style: const TextStyle(
              fontSize: 11,
              fontWeight: FontWeight.w700,
              color: AppColors.tertiaryLabel,
              letterSpacing: 0.5,
            ),
          ),
        ),
        ...children,
        const SizedBox(height: 4),
      ],
    );
  }

  Widget _item(
    BuildContext context, {
    required IconData icon,
    required Color iconColor,
    required String title,
    required Widget? page,
  }) {
    return CupertinoButton(
      onPressed: () {
        if (page != null) {
          onNavigate(page);
        } else {
          onClose();
        }
      },
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
      minSize: 0,
      child: Row(
        children: [
          Icon(icon, size: 20, color: iconColor),
          const SizedBox(width: 12),
          Expanded(
            child: Text(
              title,
              style: const TextStyle(
                fontSize: 15,
                fontWeight: FontWeight.w500,
                color: AppColors.label,
              ),
            ),
          ),
          const Icon(CupertinoIcons.chevron_right,
              size: 14, color: AppColors.tertiaryLabel),
        ],
      ),
    );
  }
}
