import 'package:flutter/cupertino.dart';
import 'package:flutter/services.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../data/models/user_profile.dart';
import '../../accomplishments/views/accomplishments_view.dart';
import '../../issues/views/issues_view.dart';
import '../../memos/views/memos_view.dart';
import '../../schools/views/schools_view.dart';
import '../../section_users/views/section_users_view.dart';
import '../../whereabouts/views/whereabouts_view.dart';
import '../../activity_designs/views/activity_designs_view.dart';
import '../../adopt_a_school/views/partners_view.dart';
import '../../adopt_a_school/views/donations_view.dart';
import '../../adopt_a_school/views/asp_tracking_view.dart';
import '../../school_profile/views/school_profile_view.dart';
import '../../personnel/views/personnel_view.dart';
import '../../pmcf/views/pmcf_list_view.dart';
import '../../enrollment/views/enrollment_view.dart';
// Brigada Eskwela module — self-contained under lib/brigada/.
import '../../../../brigada/ui/hub/brigada_hub_view.dart';
import '../../../../brigada/ui/preparedness/spc_districts_view.dart';
import '../../../../brigada/ui/report/spc_report_view.dart';
import '../../../../brigada/ui/summary/brigada_summary_view.dart';
import '../../../../brigada/ui/survey/survey_results_view.dart';

/// iOS-style slide-out sidebar matching the web sidebar.
///
/// Items (matching web section head sidebar):
/// - Dashboard, PMCF, Accomplishments, Memo, Activity Design,
///   Issues/Concerns, Brigada Eskwela, Adopt-A-School, Schools,
///   Manage Users
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
      width: 290,
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
            // ── Header with gradient ────────────────────────────────────
            _buildHeader(),
            Container(height: 0.5, color: AppColors.separator),
            // ── Menu items (role-based) ─────────────────────────────────
            Expanded(
              child: ListView(
                padding: const EdgeInsets.symmetric(vertical: 6),
                children: _buildMenuItems(),
              ),
            ),
            _buildFooter(),
          ],
        ),
      ),
    );
  }

  // ── Role-based menu ──────────────────────────────────────────────────────

  /// Build sidebar menu items based on the user's role, mirroring the web
  /// app's role-specific sidebars.
  List<Widget> _buildMenuItems() {
    final role = profile.role;

    // Common items available to all SGOD-side roles
    final mainSection = <Widget>[
      _item(
        icon: PhosphorIconsRegular.house,
        iconColor: AppColors.primary,
        title: 'Dashboard',
        page: null,
      ),
      _item(
        icon: PhosphorIconsRegular.fileText,
        iconColor: AppColors.info,
        title: 'PMCF',
        page: PmcfListView(profile: profile),
      ),
    ];

    final recordsSection = <Widget>[
      _item(
        icon: PhosphorIconsRegular.checkSquare,
        iconColor: AppColors.success,
        title: 'Accomplishments',
        page: AccomplishmentsView(section: profile.section),
      ),
      _item(
        icon: PhosphorIconsRegular.bell,
        iconColor: AppColors.warning,
        title: 'Memos',
        page: const MemosView(),
      ),
      _item(
        icon: PhosphorIconsRegular.pencilSimple,
        iconColor: AppColors.danger,
        title: 'Activity Design',
        page: const ActivityDesignsView(),
      ),
      _item(
        icon: PhosphorIconsRegular.warningCircle,
        iconColor: AppColors.danger,
        title: 'Issues / Concerns',
        page: const IssuesView(),
      ),
    ];

    final managementSection = <Widget>[
      _item(
        icon: PhosphorIconsRegular.buildings,
        iconColor: AppColors.info,
        title: 'Schools',
        page: const SchoolsView(),
      ),
      _item(
        icon: PhosphorIconsRegular.users,
        iconColor: AppColors.primary,
        title: 'Manage Users',
        page: const SectionUsersView(),
      ),
      _item(
        icon: PhosphorIconsRegular.mapPin,
        iconColor: AppColors.warning,
        title: 'Whereabouts',
        page: const WhereaboutsView(),
      ),
    ];

    final programsSection = <Widget>[
      _expandable(
        icon: PhosphorIconsRegular.broom,
        iconColor: AppColors.success,
        title: 'Brigada Eskwela',
        children: [
          _subItem('School Preparedness', const SpcDistrictsView()),
          _subItem('SPC Report', const SpcReportView()),
          _subItem('Summary Report', const BrigadaSummaryView()),
          _subItem('Survey Results', const SurveyResultsView()),
          _subItem('Overview & Offline',
              BrigadaHubView(username: profile.username)),
        ],
      ),
      _expandable(
        icon: PhosphorIconsRegular.handshake,
        iconColor: AppColors.info,
        title: 'Adopt-A-School',
        children: [
          _subItem('Partners', const PartnersView()),
          _subItem('Tax Incentive Requirements', const DonationsView()),
          _subItem('ASP Tracking', const AspTrackingView()),
        ],
      ),
    ];

    // School-specific items (web: School Profile, Personnel, Enrollment, PBEI)
    final schoolSection = <Widget>[
      _item(
        icon: PhosphorIconsRegular.graduationCap,
        iconColor: AppColors.primary,
        title: 'School Profile',
        page: SchoolProfileView(profile: profile),
      ),
      _item(
        icon: PhosphorIconsRegular.usersThree,
        iconColor: AppColors.info,
        title: 'Personnel',
        page: PersonnelView(profile: profile),
      ),
      _item(
        icon: PhosphorIconsRegular.chartBar,
        iconColor: AppColors.success,
        title: 'Enrollment Details',
        page: EnrollmentView(profile: profile),
      ),
    ];

    switch (role) {
      case Role.school:
        // School users: Dashboard, PMCF, School Profile, Personnel,
        // Enrollment, PBEI — no SGOD management items
        return [
          _section('Main', mainSection),
          _section('School', schoolSection),
          _section('Records', [
            recordsSection[0], // Accomplishments
            recordsSection[1], // Memos
            recordsSection[2], // Activity Design
          ]),
        ];

      case Role.sgod:
      case Role.shns:
      case Role.sned:
      case Role.smme:
      case Role.district:
      case Role.unknown:
        // SGOD section users: full sidebar with all sections
        // SMN section additionally gets Brigada + Adopt-A-School
        final isSmn = profile.section
            .toLowerCase()
            .contains('social mobilization');

        return [
          _section('Main', mainSection),
          _section('Records', recordsSection),
          if (isSmn) _section('Programs', programsSection),
          _section('Management', managementSection),
        ];
    }
  }

  // ── Header ───────────────────────────────────────────────────────────────
  Widget _buildHeader() {
    return AnnotatedRegion<SystemUiOverlayStyle>(
      value: SystemUiOverlayStyle.light.copyWith(
        statusBarColor: AppColors.primary,
      ),
      child: Container(
        padding: const EdgeInsets.fromLTRB(20, 16, 20, 18),
        decoration: BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
            colors: [AppColors.primary, AppColors.primaryDark],
          ),
          borderRadius: const BorderRadius.only(
            topRight: Radius.circular(20),
          ),
        ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Brand row — seal + ONE DepED wordmark
          Row(
            children: [
              Image.asset(
                'assets/icons/DepEd-ONE.png',
                width: 30,
                height: 30,
                fit: BoxFit.contain,
              ),
              const SizedBox(width: 8),
              const Text(
                'ONE DepED',
                style: TextStyle(
                  fontSize: 15,
                  fontWeight: FontWeight.w700,
                  color: CupertinoColors.white,
                  letterSpacing: 0.3,
                ),
              ),
            ],
          ),
          const SizedBox(height: 10),
          // Gold accent rule under the brand — mirrors the login header.
          Container(
            width: 36,
            height: 3,
            decoration: BoxDecoration(
              color: AppColors.accent,
              borderRadius: BorderRadius.circular(2),
            ),
          ),
          const SizedBox(height: 14),
          // User row
          Row(
            children: [
              Container(
                width: 42,
                height: 42,
                decoration: BoxDecoration(
                  color: CupertinoColors.white.withValues(alpha: 0.2),
                  borderRadius: BorderRadius.circular(10),
                ),
                child: const Icon(
                  CupertinoIcons.person_fill,
                  color: CupertinoColors.white,
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
                        color: CupertinoColors.white,
                      ),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                    const SizedBox(height: 2),
                    Text(
                      profile.section,
                      style: TextStyle(
                        fontSize: 12,
                        color: CupertinoColors.white.withValues(alpha: 0.8),
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
    );
  }

  // ── Footer ───────────────────────────────────────────────────────────────
  Widget _buildFooter() {
    return Column(
      children: [
        Container(height: 0.5, color: AppColors.separator),
        Padding(
          padding: const EdgeInsets.fromLTRB(20, 12, 20, 16),
          child: Row(
            children: [
              const Icon(PhosphorIconsRegular.graduationCap,
                  size: 18, color: AppColors.tertiaryLabel),
              const SizedBox(width: 8),
              const Text(
                'ONE DepED',
                style: TextStyle(
                  fontSize: 13,
                  fontWeight: FontWeight.w600,
                  color: AppColors.tertiaryLabel,
                ),
              ),
              const Spacer(),
              Text(
                'v1.0.0',
                style: TextStyle(
                  fontSize: 11,
                  color: AppColors.tertiaryLabel.withValues(alpha: 0.6),
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }

  // ── Section header ───────────────────────────────────────────────────────
  Widget _section(String label, List<Widget> children) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.fromLTRB(20, 10, 20, 2),
          child: Text(
            label.toUpperCase(),
            style: const TextStyle(
              fontSize: 10,
              fontWeight: FontWeight.w700,
              color: AppColors.tertiaryLabel,
              letterSpacing: 0.6,
            ),
          ),
        ),
        ...children,
        const SizedBox(height: 2),
      ],
    );
  }

  // ── Menu item ────────────────────────────────────────────────────────────
  Widget _item({
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
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 9),
      minimumSize: Size.zero,
      child: Row(
        children: [
          Container(
            width: 28,
            height: 28,
            decoration: BoxDecoration(
              color: iconColor.withValues(alpha: 0.12),
              borderRadius: BorderRadius.circular(7),
            ),
            child: Icon(icon, size: 16, color: iconColor),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Text(
              title,
              style: const TextStyle(
                fontSize: 14,
                fontWeight: FontWeight.w500,
                color: AppColors.label,
              ),
            ),
          ),
          const Icon(CupertinoIcons.chevron_right,
              size: 12, color: AppColors.tertiaryLabel),
        ],
      ),
    );
  }

  // ── Expandable menu item (with submenu) ──────────────────────────────────
  Widget _expandable({
    required IconData icon,
    required Color iconColor,
    required String title,
    required List<_SubItem> children,
  }) {
    return _ExpandableItem(
      icon: icon,
      iconColor: iconColor,
      title: title,
      children: children,
      onNavigate: onNavigate,
    );
  }

  // ── Sub-item (for expandable menus) ──────────────────────────────────────
  _SubItem _subItem(String title, Widget page) {
    return _SubItem(title: title, page: page);
  }
}

// ── Sub-item data ──────────────────────────────────────────────────────────
class _SubItem {
  const _SubItem({required this.title, required this.page});
  final String title;
  final Widget page;
}

// ── Expandable item with state ─────────────────────────────────────────────
class _ExpandableItem extends StatefulWidget {
  const _ExpandableItem({
    required this.icon,
    required this.iconColor,
    required this.title,
    required this.children,
    required this.onNavigate,
  });

  final IconData icon;
  final Color iconColor;
  final String title;
  final List<_SubItem> children;
  final void Function(Widget page) onNavigate;

  @override
  State<_ExpandableItem> createState() => _ExpandableItemState();
}

class _ExpandableItemState extends State<_ExpandableItem> {
  bool _expanded = false;

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        CupertinoButton(
          onPressed: () => setState(() => _expanded = !_expanded),
          padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 9),
          minimumSize: Size.zero,
          child: Row(
            children: [
              Container(
                width: 28,
                height: 28,
                decoration: BoxDecoration(
                  color: widget.iconColor.withValues(alpha: 0.12),
                  borderRadius: BorderRadius.circular(7),
                ),
                child: Icon(widget.icon, size: 16, color: widget.iconColor),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Text(
                  widget.title,
                  style: const TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.w500,
                    color: AppColors.label,
                  ),
                ),
              ),
              Icon(
                _expanded
                    ? CupertinoIcons.chevron_up
                    : CupertinoIcons.chevron_down,
                size: 12,
                color: AppColors.tertiaryLabel,
              ),
            ],
          ),
        ),
        if (_expanded)
          Padding(
            padding: const EdgeInsets.only(left: 52),
            child: Column(
              children: widget.children.map((s) {
                return CupertinoButton(
                  onPressed: () => widget.onNavigate(s.page),
                  padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 7),
                  minimumSize: Size.zero,
                  child: Row(
                    children: [
                      Container(
                        width: 5,
                        height: 5,
                        decoration: BoxDecoration(
                          color: AppColors.tertiaryLabel,
                          shape: BoxShape.circle,
                        ),
                      ),
                      const SizedBox(width: 10),
                      Expanded(
                        child: Text(
                          s.title,
                          style: const TextStyle(
                            fontSize: 13,
                            color: AppColors.secondaryLabel,
                          ),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ),
                    ],
                  ),
                );
              }).toList(),
            ),
          ),
      ],
    );
  }
}
