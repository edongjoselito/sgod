import 'dart:async';

import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';
import 'package:provider/provider.dart';

import '../../../../data/models/user_profile.dart';
import '../../../core/di.dart';
import '../../../../core/widgets/connectivity_banner.dart';
import '../../auth/view_models/auth_view_model.dart';
import '../../dashboard/views/dashboard_view.dart';
import '../../memos/views/memos_view.dart';
import '../../schools/views/schools_view.dart';
import 'more_view.dart';

/// iOS-style app shell — CupertinoTabBar with 5 tabs.
///
/// Tabs: Home, Memos, Schools, More (accomplishments, whereabouts,
/// issues, section users, profile, settings).
///
/// A [ConnectivityBanner] is shown above the tab scaffold whenever the
/// device is offline, a sync is in progress, or there are pending outbox
/// writes waiting to be flushed.
class AppShell extends StatefulWidget {
  const AppShell({super.key});

  @override
  State<AppShell> createState() => _AppShellState();
}

class _AppShellState extends State<AppShell> {
  int _index = 0;

  bool _isOnline = true;
  bool _isSyncing = false;
  int _pendingCount = 0;
  StreamSubscription<bool>? _connectivitySub;

  @override
  void initState() {
    super.initState();
    _isOnline = DI.connectivity.isOnline;
    _isSyncing = DI.sync.syncing;
    _pendingCount = DI.sync.pendingCount;
    DI.sync.addListener(_onSyncChanged);
    _connectivitySub = DI.connectivity.stream.listen(_onConnectivityChanged);
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
    });
  }

  @override
  Widget build(BuildContext context) {
    final profile = context.watch<AuthViewModel>().profile;
    if (profile == null) return const SizedBox.shrink();

    final tabs = _tabsFor(profile);

    return Column(
      children: [
        ConnectivityBanner(
          isOnline: _isOnline,
          isSyncing: _isSyncing,
          pendingCount: _pendingCount,
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
              return CupertinoTabView(
                builder: (context) => t.page,
              );
            },
          ),
        ),
      ],
    );
  }

  List<_Tab> _tabsFor(UserProfile profile) {
    return [
      _Tab(
        icon: PhosphorIconsRegular.house,
        activeIcon: PhosphorIconsFill.house,
        label: 'Home',
        page: DashboardView(role: profile.role, profile: profile),
      ),
      _Tab(
        icon: PhosphorIconsRegular.bell,
        activeIcon: PhosphorIconsFill.bell,
        label: 'Memos',
        page: const MemosView(),
      ),
      _Tab(
        icon: PhosphorIconsRegular.buildings,
        activeIcon: PhosphorIconsFill.buildings,
        label: 'Schools',
        page: const SchoolsView(),
      ),
      _Tab(
        icon: PhosphorIconsRegular.dotsThreeOutline,
        activeIcon: PhosphorIconsFill.dotsThreeOutline,
        label: 'More',
        page: MoreView(profile: profile),
      ),
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
