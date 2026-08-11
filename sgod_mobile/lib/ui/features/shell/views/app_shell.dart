import 'dart:async';

import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';
import 'package:provider/provider.dart';

import '../../../../core/widgets/connectivity_banner.dart';
import '../../../../data/models/user_profile.dart';
import '../../../core/di.dart';
import '../../auth/view_models/auth_view_model.dart';
import '../../dashboard/views/dashboard_view.dart';
import '../../memos/views/memos_view.dart';
import '../../schools/views/schools_view.dart';
import '../../school_profile/views/school_profile_view.dart';
import '../../personnel/views/personnel_view.dart';
import 'app_sidebar.dart';
import 'more_view.dart';

/// iOS-style app shell — CupertinoTabBar + slide-out sidebar.
///
/// Tabs: Home, Memos, Schools, More.
/// A hamburger menu in each tab's nav bar opens the [AppSidebar] drawer
/// with quick access to all features.
class AppShell extends StatefulWidget {
  const AppShell({super.key});

  @override
  State<AppShell> createState() => _AppShellState();
}

class _AppShellState extends State<AppShell> {
  int _index = 0;
  bool _sidebarOpen = false;
  final _navigatorKeys = <int, GlobalKey<NavigatorState>>{};

  bool _isOnline = true;
  bool _isSyncing = false;
  int _pendingCount = 0;
  DateTime? _lastSync;
  StreamSubscription<bool>? _connectivitySub;

  @override
  void initState() {
    super.initState();
    _isOnline = DI.connectivity.isOnline;
    _isSyncing = DI.sync.syncing;
    _pendingCount = DI.sync.pendingCount;
    _lastSync = DI.sync.lastSync;
    DI.sync.addListener(_onSyncChanged);
    _connectivitySub = DI.connectivity.stream.listen(_onConnectivityChanged);
    // Trigger initial sync on app launch
    WidgetsBinding.instance.addPostFrameCallback((_) {
      DI.sync.refreshPendingCount();
      DI.sync.sync();
    });
  }

  @override
  void dispose() {
    _connectivitySub?.cancel();
    DI.sync.removeListener(_onSyncChanged);
    super.dispose();
  }

  void _onConnectivityChanged(bool online) {
    if (!mounted) return;
    setState(() => _isOnline = online);
  }

  void _onSyncChanged() {
    if (!mounted) return;
    setState(() {
      _isSyncing = DI.sync.syncing;
      _pendingCount = DI.sync.pendingCount;
      _lastSync = DI.sync.lastSync;
    });
  }

  void _openSidebar() => setState(() => _sidebarOpen = true);
  void _closeSidebar() => setState(() => _sidebarOpen = false);

  void _navigateFromSidebar(Widget page) {
    _closeSidebar();
    // Push onto the current tab's navigator
    final navKey = _navigatorKeys[_index];
    final nav = navKey?.currentState;
    if (nav != null) {
      Future.delayed(const Duration(milliseconds: 200), () {
        nav.push(CupertinoPageRoute(builder: (_) => page));
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final profile = context.watch<AuthViewModel>().profile;
    if (profile == null) return const SizedBox.shrink();

    final tabs = _tabsFor(profile);

    return Stack(
      children: [
        // ── Main content ────────────────────────────────────────────────
        Column(
          children: [
            ConnectivityBanner(
              isOnline: _isOnline,
              isSyncing: _isSyncing,
              pendingCount: _pendingCount,
              lastSync: _lastSync,
            ),
            Expanded(
              child: CupertinoTabScaffold(
                tabBar: CupertinoTabBar(
                  currentIndex: _index.clamp(0, tabs.length - 1),
                  onTap: (i) => setState(() => _index = i),
                  items: tabs
                      .map((t) => BottomNavigationBarItem(
                            icon: Icon(t.icon, size: 24),
                            activeIcon: Icon(t.activeIcon, size: 24),
                            label: t.label,
                          ))
                      .toList(),
                ),
                tabBuilder: (context, index) {
                  final t = tabs[index];
                  _navigatorKeys[index] ??= GlobalKey<NavigatorState>();
                  return CupertinoTabView(
                    navigatorKey: _navigatorKeys[index],
                    builder: (context) => t.page,
                  );
                },
              ),
            ),
          ],
        ),
        // ── Sidebar overlay ─────────────────────────────────────────────
        if (_sidebarOpen) ...[
          // Scrim
          GestureDetector(
            onTap: _closeSidebar,
            child: Container(
              color: CupertinoColors.black.withValues(alpha: 0.4),
            ),
          ),
          // Sidebar with slide animation
          Align(
            alignment: Alignment.centerLeft,
            child: AppSidebar(
              profile: profile,
              onNavigate: _navigateFromSidebar,
              onClose: _closeSidebar,
            ),
          ),
        ],
      ],
    );
  }

  List<_Tab> _tabsFor(UserProfile profile) {
    // Common Home tab for all roles
    final homeTab = _Tab(
      icon: PhosphorIconsRegular.house,
      activeIcon: PhosphorIconsFill.house,
      label: 'Home',
      page: DashboardView(
        role: profile.role,
        profile: profile,
        onMenuTap: _openSidebar,
      ),
    );

    final moreTab = _Tab(
      icon: PhosphorIconsRegular.dotsThreeOutline,
      activeIcon: PhosphorIconsFill.dotsThreeOutline,
      label: 'More',
      page: MoreView(profile: profile, onMenuTap: _openSidebar),
    );

    // School users get different tabs (no Schools management tab)
    if (profile.role == Role.school) {
      return [
        homeTab,
        _Tab(
          icon: PhosphorIconsRegular.graduationCap,
          activeIcon: PhosphorIconsFill.graduationCap,
          label: 'Profile',
          page: SchoolProfileView(
            profile: profile,
            onMenuTap: _openSidebar,
          ),
        ),
        _Tab(
          icon: PhosphorIconsRegular.usersThree,
          activeIcon: PhosphorIconsFill.usersThree,
          label: 'Personnel',
          page: PersonnelView(
            profile: profile,
            onMenuTap: _openSidebar,
          ),
        ),
        moreTab,
      ];
    }

    // SGOD-side roles: Home, Memos, Schools, More
    return [
      homeTab,
      _Tab(
        icon: PhosphorIconsRegular.bell,
        activeIcon: PhosphorIconsFill.bell,
        label: 'Memos',
        page: MemosView(onMenuTap: _openSidebar),
      ),
      _Tab(
        icon: PhosphorIconsRegular.buildings,
        activeIcon: PhosphorIconsFill.buildings,
        label: 'Schools',
        page: SchoolsView(onMenuTap: _openSidebar),
      ),
      moreTab,
    ];
  }
}

class _Tab {
  const _Tab({
    required this.icon,
    required this.activeIcon,
    required this.label,
    required this.page,
  });
  final IconData icon;
  final IconData activeIcon;
  final String label;
  final Widget page;
}
