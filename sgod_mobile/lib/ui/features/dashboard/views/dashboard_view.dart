import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';
import 'package:provider/provider.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../data/models/dashboard_data.dart';
import '../../../../data/models/user_profile.dart';
import '../../../core/di.dart';
import '../view_models/dashboard_view_model.dart';

/// iOS-native dashboard — designed for mobile, not a shrunk web page.
///
/// Layout:
/// - Hero gradient header card (greeting, name, section, key metric)
/// - Horizontal scrollable KPI row (2–3 cards, like iOS Weather/Health)
/// - Compact horizontal bar chart (top 4 sections only)
/// - Grouped inset lists for recent memos, accomplishments, whereabouts
class DashboardView extends StatefulWidget {
  const DashboardView({
    super.key,
    required this.role,
    required this.profile,
    this.onMenuTap,
  });

  final Role role;
  final UserProfile profile;
  final VoidCallback? onMenuTap;

  @override
  State<DashboardView> createState() => _DashboardViewState();
}

class _DashboardViewState extends State<DashboardView> {
  late DashboardViewModel _vm;

  @override
  void initState() {
    super.initState();
    _vm = DashboardViewModel(DI.dashboard);
    _vm.load();
  }

  @override
  Widget build(BuildContext context) {
    final accent = AppColors.forRole(widget.role.name);
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
              largeTitle: const Text('Dashboard'),
              backgroundColor: AppColors.background,
              border: const Border(
                  bottom: BorderSide(color: AppColors.separator, width: 0.5)),
              leading: widget.onMenuTap != null
                  ? CupertinoButton(
                      padding: EdgeInsets.zero,
                      onPressed: widget.onMenuTap,
                      child: const Icon(CupertinoIcons.line_horizontal_3,
                          size: 26, color: AppColors.label),
                    )
                  : null,
              trailing: GestureDetector(
                onTap: () {},
                child: Container(
                  width: 32,
                  height: 32,
                  decoration: BoxDecoration(
                    color: accent.withOpacity(0.12),
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: Icon(CupertinoIcons.person_fill,
                      color: accent, size: 16),
                ),
              ),
            ),
            CupertinoSliverRefreshControl(
              onRefresh: () => _vm.load(),
            ),
            SliverSafeArea(
              minimum: const EdgeInsets.fromLTRB(12, 2, 12, 20),
              sliver: SliverToBoxAdapter(
                child: Consumer<DashboardViewModel>(
                  builder: (context, vm, _) {
                    if (vm.isLoading && vm.data == null) {
                      return _buildLoading(context);
                    }
                    if (vm.error != null && vm.data == null) {
                      return _buildError(context, vm);
                    }
                    return _buildContent(context, vm, accent);
                  },
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  // ── Loading ──────────────────────────────────────────────────────────────
  Widget _buildLoading(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(top: 80),
      child: Center(
        child: Column(
          children: const [
            CupertinoActivityIndicator(radius: 16),
            SizedBox(height: 16),
            Text('Loading dashboard...',
                style: TextStyle(color: AppColors.secondaryLabel, fontSize: 15)),
          ],
        ),
      ),
    );
  }

  // ── Error ────────────────────────────────────────────────────────────────
  Widget _buildError(BuildContext context, DashboardViewModel vm) {
    return Padding(
      padding: const EdgeInsets.only(top: 60),
      child: Center(
        child: Column(
          children: [
            const Icon(CupertinoIcons.wifi_exclamationmark,
                size: 48, color: AppColors.tertiaryLabel),
            const SizedBox(height: 16),
            Text('Could not load data',
                style: const TextStyle(
                    fontSize: 17, fontWeight: FontWeight.w600, color: AppColors.label)),
            const SizedBox(height: 4),
            Text(vm.error!,
                style: const TextStyle(fontSize: 13, color: AppColors.secondaryLabel),
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

  // ── Content ──────────────────────────────────────────────────────────────
  Widget _buildContent(BuildContext context, DashboardViewModel vm, Color accent) {
    final data = vm.data!;
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // ── Hero with KPIs integrated ─────────────────────────────────
        _heroHeader(data, accent),
        const SizedBox(height: 12),

        // ── Section breakdown (compact, top 4) ────────────────────────
        if (data.sectionBreakdown.isNotEmpty) ...[
          _sectionLabel('Accomplishments by Section'),
          _breakdownChart(data.sectionBreakdown, accent),
          const SizedBox(height: 12),
        ],

        // ── Recent memos ──────────────────────────────────────────────
        if (data.recentMemos.isNotEmpty) ...[
          _sectionLabel('Recent Memos'),
          _memosSection(data.recentMemos),
          const SizedBox(height: 12),
        ],

        // ── Recent accomplishments ───────────────────────────────────
        if (data.recentAccomplishments.isNotEmpty) ...[
          _sectionLabel('Recent Accomplishments'),
          _accomplishmentsSection(data.recentAccomplishments),
          const SizedBox(height: 12),
        ],

        // ── Recent whereabouts ────────────────────────────────────────
        if (data.recentWhereabouts.isNotEmpty) ...[
          _sectionLabel('Employee Whereabouts'),
          _whereaboutsSection(data.recentWhereabouts),
        ],
      ],
    );
  }

  // ── Hero header with integrated KPIs ──────────────────────────────────────
  Widget _heroHeader(DashboardData data, Color accent) {
    final sectionLabel = widget.profile.section.isNotEmpty
        ? widget.profile.section
        : widget.profile.secGroup.isNotEmpty
            ? widget.profile.secGroup
            : _roleLabel(widget.role);

    // Pick top 3 stats for the hero
    final picked = _pickTopStats(data.stats, 3);

    return Container(
      width: double.infinity,
      padding: const EdgeInsets.fromLTRB(16, 16, 16, 14),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(16),
        gradient: LinearGradient(
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
          colors: [
            accent,
            Color.lerp(accent, const Color(0xFF000000), 0.30)!,
          ],
        ),
        boxShadow: [
          BoxShadow(
            color: accent.withOpacity(0.25),
            blurRadius: 12,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // ── Top row: name + section pill ────────────────────────────
          Row(
            children: [
              Expanded(
                child: Text(
                  widget.profile.fullName,
                  style: const TextStyle(
                    fontSize: 20,
                    fontWeight: FontWeight.w700,
                    color: CupertinoColors.white,
                    height: 1.1,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
              ),
              const SizedBox(width: 8),
              Flexible(
                child: Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                  decoration: BoxDecoration(
                    color: CupertinoColors.white.withOpacity(0.18),
                    borderRadius: BorderRadius.circular(100),
                  ),
                  child: Text(
                    _shortSection(sectionLabel),
                    style: const TextStyle(
                      fontSize: 11,
                      fontWeight: FontWeight.w600,
                      color: CupertinoColors.white,
                    ),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          // ── KPI row (3 stats) ───────────────────────────────────────
          if (picked.isNotEmpty) ...[
            Container(
              height: 0.5,
              color: CupertinoColors.white.withOpacity(0.2),
            ),
            const SizedBox(height: 10),
            Row(
              children: [
                for (int i = 0; i < picked.length; i++) ...[
                  if (i > 0) ...[
                    Container(
                      width: 0.5,
                      height: 32,
                      color: CupertinoColors.white.withOpacity(0.15),
                    ),
                    const SizedBox(width: 0),
                  ],
                  Expanded(
                    child: _heroStat(
                      picked[i].value,
                      picked[i].label,
                      _iconFor(picked[i].icon),
                    ),
                  ),
                ],
              ],
            ),
          ],
        ],
      ),
    );
  }

  Widget _heroStat(String value, String label, IconData icon) {
    return Column(
      children: [
        Icon(icon, size: 16, color: CupertinoColors.white.withOpacity(0.7)),
        const SizedBox(height: 4),
        Text(
          value,
          style: const TextStyle(
            fontSize: 18,
            fontWeight: FontWeight.w700,
            color: CupertinoColors.white,
          ),
        ),
        const SizedBox(height: 1),
        Text(
          label,
          style: TextStyle(
            fontSize: 10,
            color: CupertinoColors.white.withOpacity(0.7),
          ),
          textAlign: TextAlign.center,
          maxLines: 1,
          overflow: TextOverflow.ellipsis,
        ),
      ],
    );
  }

  // ── Stat picker ───────────────────────────────────────────────────────────
  List<StatItem> _pickTopStats(List<StatItem> stats, int count) {
    final lower = stats.map((s) => s.label.toLowerCase()).toList();
    final priorities = ['school', 'section user', 'activity design', 'total school', 'public school', 'private school'];
    final picked = <StatItem>[];
    for (final p in priorities) {
      final idx = lower.indexWhere((l) => l.contains(p));
      if (idx != -1 && !picked.contains(stats[idx])) {
        picked.add(stats[idx]);
      }
      if (picked.length >= count) break;
    }
    // Fill remaining slots from the front.
    for (final s in stats) {
      if (picked.length >= count) break;
      if (!picked.contains(s)) picked.add(s);
    }
    return picked.take(count).toList();
  }

  // ── Section label ─────────────────────────────────────────────────────────
  Widget _sectionLabel(String text) => Padding(
        padding: const EdgeInsets.only(left: 4, bottom: 6),
        child: Text(
          text.toUpperCase(),
          style: const TextStyle(
            fontSize: 12,
            fontWeight: FontWeight.w600,
            color: AppColors.secondaryLabel,
            letterSpacing: 0.4,
          ),
        ),
      );

  // ── Compact breakdown chart (top 4) ───────────────────────────────────────
  Widget _breakdownChart(List<BreakdownItem> items, Color accent) {
    final top4 = [...items]
      ..sort((a, b) => b.value.compareTo(a.value));
    final shown = top4.take(4).toList();
    final maxVal = shown.fold<int>(0, (m, i) => i.value > m ? i.value : m);

    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: AppColors.secondaryBackground,
        borderRadius: BorderRadius.circular(12),
      ),
      child: Column(
        children: [
          for (int i = 0; i < shown.length; i++) ...[
            _breakdownRow(shown[i], maxVal, accent),
            if (i < shown.length - 1) const SizedBox(height: 8),
          ],
        ],
      ),
    );
  }

  Widget _breakdownRow(BreakdownItem item, int maxVal, Color accent) {
    final pct = maxVal > 0 ? item.value / maxVal : 0.0;
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Expanded(
              child: Text(
                _shortSection(item.label),
                style: const TextStyle(
                  fontSize: 13,
                  fontWeight: FontWeight.w500,
                  color: AppColors.label,
                ),
                maxLines: 1,
                overflow: TextOverflow.ellipsis,
              ),
            ),
            const SizedBox(width: 8),
            Text(
              '${item.value}',
              style: TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w700,
                color: accent,
              ),
            ),
          ],
        ),
        const SizedBox(height: 6),
        ClipRRect(
          borderRadius: BorderRadius.circular(4),
          child: SizedBox(
            height: 6,
            child: Stack(
              children: [
                Container(
                  decoration: BoxDecoration(
                    color: accent.withOpacity(0.1),
                    borderRadius: BorderRadius.circular(4),
                  ),
                ),
                FractionallySizedBox(
                  widthFactor: pct.clamp(0.0, 1.0),
                  child: Container(
                    decoration: BoxDecoration(
                      color: accent,
                      borderRadius: BorderRadius.circular(4),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
      ],
    );
  }

  // ── Compact sections (no CupertinoListSection margins) ───────────────────
  Widget _compactSection(List<Widget> tiles) {
    return Container(
      decoration: BoxDecoration(
        color: AppColors.surface,
        borderRadius: BorderRadius.circular(12),
      ),
      clipBehavior: Clip.antiAlias,
      child: Column(
        children: [
          for (int i = 0; i < tiles.length; i++) ...[
            tiles[i],
            if (i < tiles.length - 1)
              Container(height: 0.5, color: AppColors.separator),
          ],
        ],
      ),
    );
  }

  Widget _memosSection(List<RecentMemo> memos) {
    return _compactSection(
      memos.map((m) => _MemoTile(memo: m)).toList(),
    );
  }

  Widget _accomplishmentsSection(List<RecentAccomplishment> items) {
    return _compactSection(
      items.map((a) => _AccomplishmentTile(item: a)).toList(),
    );
  }

  Widget _whereaboutsSection(List<RecentWhereabouts> items) {
    return _compactSection(
      items.map((w) => _WhereaboutsTile(item: w)).toList(),
    );
  }

  // ── Helpers ───────────────────────────────────────────────────────────────
  String _roleLabel(Role role) {
    switch (role) {
      case Role.sgod:
        return 'SGOD';
      case Role.shns:
        return 'SHNS';
      case Role.school:
        return 'School';
      case Role.sned:
        return 'SNED';
      case Role.smme:
        return 'SMME';
      case Role.district:
        return 'District';
      case Role.unknown:
        return 'Staff';
    }
  }

  IconData _iconFor(String name) {
    switch (name) {
      case 'check_square':
        return PhosphorIconsRegular.checkSquare;
      case 'clipboard_text':
        return PhosphorIconsRegular.clipboardText;
      case 'bell':
        return PhosphorIconsRegular.bell;
      case 'map_pin':
        return PhosphorIconsRegular.mapPin;
      case 'buildings':
        return PhosphorIconsRegular.buildings;
      case 'school':
        return PhosphorIconsRegular.graduationCap;
      case 'broom':
        return PhosphorIconsRegular.broom;
      case 'file_text':
        return PhosphorIconsRegular.fileText;
      case 'users':
        return PhosphorIconsRegular.users;
      case 'heartbeat':
        return PhosphorIconsRegular.heartbeat;
      case 'fork_knife':
        return PhosphorIconsRegular.forkKnife;
      case 'hand_heart':
        return PhosphorIconsRegular.handHeart;
      case 'chat_circle':
        return PhosphorIconsRegular.chatCircle;
      case 'warning':
        return PhosphorIconsRegular.warning;
      case 'clipboard':
        return PhosphorIconsRegular.clipboard;
      case 'user_list':
        return PhosphorIconsRegular.userList;
      case 'graduation_cap':
        return PhosphorIconsRegular.graduationCap;
      default:
        return PhosphorIconsRegular.squaresFour;
    }
  }

  String _shortSection(String name) {
    const map = {
      'Social Mobilization and Networking': 'Social Mobilization',
      'School Management Monitoring and Evaluation': 'SMME',
      'Disaster Risk Reduction Management (DRRM) Section': 'DRRM',
      'School Health and Nutrition Section': 'SHNS',
      'Human Resource Development Section': 'HRD',
      'Physical Education and Schools Sports': 'PESS',
      'Youth Formation Program': 'YFP',
      'Chief - SGOD': 'Chief SGOD',
    };
    return map[name] ?? name;
  }
}

// ── Memo tile (CupertinoListTile-compatible) ────────────────────────────────
class _MemoTile extends StatelessWidget {
  const _MemoTile({required this.memo});
  final RecentMemo memo;

  @override
  Widget build(BuildContext context) {
    return CupertinoButton(
      onPressed: () {},
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      minSize: 0,
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          Container(
            width: 30,
            height: 30,
            decoration: BoxDecoration(
              color: AppColors.primary.withOpacity(0.1),
              borderRadius: BorderRadius.circular(7),
            ),
            child: const Icon(PhosphorIconsRegular.bell,
                size: 16, color: AppColors.primary),
          ),
          const SizedBox(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  memo.title,
                  style: const TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.w500,
                    color: AppColors.label,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
                const SizedBox(height: 2),
                Text(
                  memo.memoNo.isNotEmpty
                      ? memo.memoNo
                      : (memo.addedBy.isNotEmpty ? 'By ${memo.addedBy}' : ''),
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
          const Icon(CupertinoIcons.chevron_right,
              size: 14, color: AppColors.tertiaryLabel),
        ],
      ),
    );
  }
}

// ── Accomplishment tile ─────────────────────────────────────────────────────
class _AccomplishmentTile extends StatelessWidget {
  const _AccomplishmentTile({required this.item});
  final RecentAccomplishment item;

  @override
  Widget build(BuildContext context) {
    final pct =
        double.tryParse(item.percentageAccom.replaceAll('%', '')) ?? 0;
    return CupertinoButton(
      onPressed: () {},
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      minSize: 0,
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.center,
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
                    Flexible(
                      child: Text(
                        item.section,
                        style: const TextStyle(
                          fontSize: 12,
                          color: AppColors.secondaryLabel,
                        ),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                    ),
                    if (pct > 0) ...[
                      const SizedBox(width: 6),
                      Text(
                        '${pct.toStringAsFixed(0)}%',
                        style: const TextStyle(
                          fontSize: 13,
                          fontWeight: FontWeight.w600,
                          color: AppColors.success,
                        ),
                      ),
                    ],
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

// ── Whereabouts tile ────────────────────────────────────────────────────────
class _WhereaboutsTile extends StatelessWidget {
  const _WhereaboutsTile({required this.item});
  final RecentWhereabouts item;

  @override
  Widget build(BuildContext context) {
    final isOnField = item.status.toLowerCase().contains('field');
    final statusColor = isOnField ? AppColors.warning : AppColors.success;
    return CupertinoButton(
      onPressed: () {},
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      minSize: 0,
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          Container(
            width: 30,
            height: 30,
            decoration: BoxDecoration(
              color: statusColor.withOpacity(0.1),
              borderRadius: BorderRadius.circular(7),
            ),
            child: Icon(
              isOnField
                  ? PhosphorIconsRegular.mapPin
                  : PhosphorIconsRegular.building,
              size: 16,
              color: statusColor,
            ),
          ),
          const SizedBox(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  '${item.fName} ${item.lName}',
                  style: const TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.w600,
                    color: AppColors.label,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
                const SizedBox(height: 2),
                Text(
                  item.activity,
                  style: const TextStyle(
                    fontSize: 12,
                    color: AppColors.secondaryLabel,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
                const SizedBox(height: 4),
                Row(
                  children: [
                    if (item.date.isNotEmpty) ...[
                      Text(
                        item.date,
                        style: const TextStyle(
                          fontSize: 11,
                          color: AppColors.tertiaryLabel,
                        ),
                      ),
                      const SizedBox(width: 6),
                    ],
                    Container(
                      padding: const EdgeInsets.symmetric(
                          horizontal: 6, vertical: 2),
                      decoration: BoxDecoration(
                        color: statusColor.withOpacity(0.12),
                        borderRadius: BorderRadius.circular(5),
                      ),
                      child: Text(
                        item.status,
                        style: TextStyle(
                          fontSize: 10,
                          fontWeight: FontWeight.w600,
                          color: statusColor,
                        ),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
