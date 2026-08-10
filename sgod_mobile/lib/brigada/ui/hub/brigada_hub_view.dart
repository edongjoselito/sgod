import 'dart:async';

import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../core/theme/app_colors.dart';
import '../../brigada_module.dart';
import '../../data/brigada_models.dart';
import '../../data/brigada_repository.dart';
import '../../data/brigada_sync.dart';
import '../brigada_ui.dart';
import '../preparedness/spc_districts_view.dart';
import '../report/spc_report_view.dart';
import '../summary/brigada_summary_view.dart';
import '../survey/survey_results_view.dart';

/// Brigada Eskwela home — the mobile counterpart of the web sidebar group.
///
/// Lists the four reports, and owns the module's offline controls: sync
/// status, a full "download for offline" pass, and cache management.
class BrigadaHubView extends StatefulWidget {
  const BrigadaHubView({super.key, this.username, this.previousPageTitle});

  /// Scopes the offline cache to the signed-in account.
  final String? username;
  final String? previousPageTitle;

  @override
  State<BrigadaHubView> createState() => _BrigadaHubViewState();
}

class _BrigadaHubViewState extends State<BrigadaHubView> {
  late final Future<void> _ready;
  BrigadaMeta _meta = BrigadaMeta.fallback();
  String _sy = BrigadaMeta.fallback().currentSy;
  bool _metaLoaded = false;

  @override
  void initState() {
    super.initState();
    _ready = _bootstrap();
  }

  Future<void> _bootstrap() async {
    await BrigadaModule.ensureInitialized(username: widget.username);
    if (!mounted) return;
    BrigadaModule.sync.addListener(_onSyncChanged);
    setState(() {});
    await _loadMeta();
    // Quietly bring stale reports up to date in the background.
    unawaited(BrigadaModule.sync.refreshIfStale());
  }

  Future<void> _loadMeta() async {
    try {
      final result = await BrigadaModule.repository
          .fetch(BrigadaRepository.metaRequest(), BrigadaMeta.fromJson);
      if (!mounted) return;
      setState(() {
        _meta = result.data;
        _sy = result.data.currentSy;
        _metaLoaded = true;
      });
    } catch (_) {
      // Falls back to the computed current school year; the reports still work.
      if (mounted) setState(() => _metaLoaded = true);
    }
  }

  @override
  void dispose() {
    if (BrigadaModule.isInitialized) {
      BrigadaModule.sync.removeListener(_onSyncChanged);
    }
    super.dispose();
  }

  void _onSyncChanged() {
    if (mounted) setState(() {});
  }

  // ── Actions ──────────────────────────────────────────────────────────────

  Future<void> _pickSchoolYear() async {
    final years = _meta.schoolYears.isEmpty ? [_sy] : _meta.schoolYears;
    final index = await brigadaPickIndex(
      context,
      title: 'School Year',
      options: years,
      initialIndex: years.contains(_sy) ? years.indexOf(_sy) : 0,
    );
    if (index == null || !mounted) return;
    setState(() => _sy = years[index]);
  }

  Future<void> _download() async {
    final confirmed = await showCupertinoModalPopup<bool>(
      context: context,
      builder: (sheetContext) => CupertinoActionSheet(
        title: const Text('Download for offline'),
        message: Text(
          'Saves every district, school checklist, the preparedness report, '
          'survey results and the last 6 months of contributions for SY $_sy. '
          'Best done on Wi-Fi.',
        ),
        actions: [
          CupertinoActionSheetAction(
            onPressed: () => Navigator.of(sheetContext).pop(true),
            child: const Text('Download everything'),
          ),
        ],
        cancelButton: CupertinoActionSheetAction(
          isDefaultAction: true,
          onPressed: () => Navigator.of(sheetContext).pop(false),
          child: const Text('Cancel'),
        ),
      ),
    );
    if (confirmed != true) return;
    await BrigadaModule.sync.downloadForOffline(sy: _sy);
  }

  Future<void> _clearCache() async {
    final confirmed = await showCupertinoModalPopup<bool>(
      context: context,
      builder: (sheetContext) => CupertinoActionSheet(
        title: const Text('Clear offline data?'),
        message: const Text(
          'Removes every saved Brigada report from this device. You will need '
          'a connection to view them again.',
        ),
        actions: [
          CupertinoActionSheetAction(
            isDestructiveAction: true,
            onPressed: () => Navigator.of(sheetContext).pop(true),
            child: const Text('Clear'),
          ),
        ],
        cancelButton: CupertinoActionSheetAction(
          isDefaultAction: true,
          onPressed: () => Navigator.of(sheetContext).pop(false),
          child: const Text('Cancel'),
        ),
      ),
    );
    if (confirmed != true) return;
    await BrigadaModule.sync.clearCache();
  }

  void _open(Widget page) {
    Navigator.of(context).push(CupertinoPageRoute(builder: (_) => page));
  }

  // ── Build ────────────────────────────────────────────────────────────────

  @override
  Widget build(BuildContext context) {
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      child: FutureBuilder<void>(
        future: _ready,
        builder: (context, snapshot) {
          if (snapshot.hasError) {
            return SafeArea(
              child: BrigadaEmpty(
                icon: PhosphorIconsRegular.warningCircle,
                title: 'Brigada Eskwela is unavailable',
                message: '${snapshot.error}',
              ),
            );
          }
          if (!BrigadaModule.isInitialized) {
            return const Center(child: CupertinoActivityIndicator(radius: 14));
          }
          return _content();
        },
      ),
    );
  }

  Widget _content() {
    final sync = BrigadaModule.sync;

    return CustomScrollView(
      slivers: [
        CupertinoSliverNavigationBar(
          largeTitle: const Text('Brigada Eskwela'),
          previousPageTitle: widget.previousPageTitle,
          backgroundColor: AppColors.surface,
          border: const Border(
            bottom: BorderSide(color: AppColors.separator, width: 0.5),
          ),
          trailing: BrigadaFilterButton(
            label: _sy,
            onPressed: _metaLoaded ? _pickSchoolYear : () {},
          ),
        ),
        CupertinoSliverRefreshControl(onRefresh: sync.refresh),
        SliverSafeArea(
          top: false,
          minimum: const EdgeInsets.only(bottom: 32),
          sliver: SliverToBoxAdapter(
            child: Padding(
              padding: BrigadaTokens.pagePadding,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  _syncCard(sync),
                  const BrigadaSectionHeader('Reports'),
                  _reportTile(
                    icon: PhosphorIconsRegular.clipboardText,
                    color: BrigadaTokens.accent,
                    title: 'School Preparedness',
                    subtitle: 'Checklist status by district and school',
                    onTap: () => _open(SpcDistrictsView(sy: _sy)),
                  ),
                  const SizedBox(height: 10),
                  _reportTile(
                    icon: PhosphorIconsRegular.chartBar,
                    color: BrigadaTokens.fully,
                    title: 'SPC Report',
                    subtitle: 'Division-wide preparedness tallies',
                    onTap: () => _open(SpcReportView(sy: _sy)),
                  ),
                  const SizedBox(height: 10),
                  _reportTile(
                    icon: PhosphorIconsRegular.handHeart,
                    color: BrigadaTokens.volunteers,
                    title: 'Summary Report',
                    subtitle: 'Resources, volunteers and partners',
                    onTap: () => _open(const BrigadaSummaryView()),
                  ),
                  const SizedBox(height: 10),
                  _reportTile(
                    icon: PhosphorIconsRegular.star,
                    color: BrigadaTokens.days,
                    title: 'Survey Results',
                    subtitle: 'Partner satisfaction ratings',
                    onTap: () => _open(const SurveyResultsView()),
                  ),
                  const BrigadaSectionHeader('Offline'),
                  _offlineCard(sync),
                ],
              ),
            ),
          ),
        ),
      ],
    );
  }

  // ── Sync status ──────────────────────────────────────────────────────────

  Widget _syncCard(BrigadaSyncService sync) {
    final online = sync.isOnline;
    final busy = sync.isBusy;
    final error = sync.lastError;

    final Color color;
    final IconData icon;
    final String title;
    final String subtitle;

    if (busy) {
      color = BrigadaTokens.accent;
      icon = PhosphorIconsRegular.arrowsClockwise;
      title = sync.stage == BrigadaSyncStage.downloading
          ? 'Downloading for offline'
          : 'Refreshing reports';
      subtitle = sync.statusLabel;
    } else if (!online) {
      color = AppColors.warning;
      icon = PhosphorIconsRegular.wifiSlash;
      title = 'Offline';
      subtitle = sync.isEmpty
          ? 'No reports saved on this device yet'
          : 'Showing reports saved ${formatRelative(sync.lastSyncedAt)}';
    } else if (error != null) {
      color = AppColors.danger;
      icon = PhosphorIconsRegular.warningCircle;
      title = 'Last sync had problems';
      subtitle = '$error';
    } else if (sync.isEmpty) {
      color = AppColors.info;
      icon = PhosphorIconsRegular.cloudArrowDown;
      title = 'Nothing saved yet';
      subtitle = 'Download the reports to use them without a connection';
    } else {
      color = BrigadaTokens.fully;
      icon = PhosphorIconsRegular.checkCircle;
      title = sync.isStale ? 'Reports may be out of date' : 'Reports are current';
      subtitle = 'Last synced ${formatRelative(sync.lastSyncedAt)}';
    }

    return BrigadaCard(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                width: 38,
                height: 38,
                decoration: BoxDecoration(
                  color: color.withValues(alpha: 0.13),
                  borderRadius: BorderRadius.circular(11),
                ),
                child: busy
                    ? const Center(child: CupertinoActivityIndicator(radius: 9))
                    : Icon(icon, size: 19, color: color),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      title,
                      style: const TextStyle(
                        fontSize: 15.5,
                        fontWeight: FontWeight.w600,
                        color: AppColors.label,
                      ),
                    ),
                    if (subtitle.isNotEmpty) ...[
                      const SizedBox(height: 2),
                      Text(
                        subtitle,
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                        style: const TextStyle(
                          fontSize: 13,
                          color: AppColors.secondaryLabel,
                        ),
                      ),
                    ],
                  ],
                ),
              ),
              if (!busy && online)
                CupertinoButton(
                  padding: const EdgeInsets.symmetric(horizontal: 10),
                  minimumSize: Size.zero,
                  onPressed: sync.refresh,
                  child: const Text('Sync',
                      style: TextStyle(
                          fontSize: 15, fontWeight: FontWeight.w600)),
                ),
            ],
          ),
          if (busy && sync.progress != null) ...[
            const SizedBox(height: 14),
            BrigadaMeter(fraction: sync.progress!, color: color),
            const SizedBox(height: 6),
            Text(
              '${sync.stepsDone} of ${sync.stepsTotal}',
              style: const TextStyle(
                fontSize: 12,
                color: AppColors.tertiaryLabel,
              ),
            ),
          ],
        ],
      ),
    );
  }

  Widget _offlineCard(BrigadaSyncService sync) {
    return BrigadaCard(
      padding: EdgeInsets.zero,
      child: Column(
        children: [
          _actionRow(
            icon: PhosphorIconsRegular.cloudArrowDown,
            color: BrigadaTokens.accent,
            title: 'Download for offline',
            subtitle: 'All districts, checklists and recent months',
            enabled: sync.isOnline && !sync.isBusy,
            onTap: _download,
          ),
          const _RowDivider(),
          _infoRow(
            icon: PhosphorIconsRegular.database,
            title: 'Saved on this device',
            value: sync.cachedScreens == 0
                ? 'Nothing saved'
                : '${sync.cachedScreens} report${sync.cachedScreens == 1 ? '' : 's'} · ${formatBytes(sync.cachedBytes)}',
          ),
          if (sync.cachedScreens > 0) ...[
            const _RowDivider(),
            _actionRow(
              icon: PhosphorIconsRegular.trash,
              color: AppColors.danger,
              title: 'Clear offline data',
              subtitle: null,
              enabled: !sync.isBusy,
              onTap: _clearCache,
              destructive: true,
            ),
          ],
        ],
      ),
    );
  }

  // ── Rows ─────────────────────────────────────────────────────────────────

  Widget _reportTile({
    required IconData icon,
    required Color color,
    required String title,
    required String subtitle,
    required VoidCallback onTap,
  }) {
    return BrigadaCard(
      onTap: onTap,
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 14),
      child: Row(
        children: [
          Container(
            width: 40,
            height: 40,
            decoration: BoxDecoration(
              color: color.withValues(alpha: 0.13),
              borderRadius: BorderRadius.circular(11),
            ),
            child: Icon(icon, size: 20, color: color),
          ),
          const SizedBox(width: 13),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: const TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.w600,
                    color: AppColors.label,
                  ),
                ),
                const SizedBox(height: 2),
                Text(
                  subtitle,
                  style: const TextStyle(
                    fontSize: 13,
                    color: AppColors.secondaryLabel,
                  ),
                ),
              ],
            ),
          ),
          const Icon(CupertinoIcons.chevron_right,
              size: 15, color: AppColors.tertiaryLabel),
        ],
      ),
    );
  }

  Widget _actionRow({
    required IconData icon,
    required Color color,
    required String title,
    required String? subtitle,
    required bool enabled,
    required VoidCallback onTap,
    bool destructive = false,
  }) {
    final content = Padding(
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 13),
      child: Row(
        children: [
          Icon(icon, size: 19, color: enabled ? color : AppColors.tertiaryLabel),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: TextStyle(
                    fontSize: 15.5,
                    fontWeight: FontWeight.w500,
                    color: !enabled
                        ? AppColors.tertiaryLabel
                        : destructive
                            ? AppColors.danger
                            : AppColors.label,
                  ),
                ),
                if (subtitle != null) ...[
                  const SizedBox(height: 2),
                  Text(
                    subtitle,
                    style: const TextStyle(
                      fontSize: 12.5,
                      color: AppColors.secondaryLabel,
                    ),
                  ),
                ],
              ],
            ),
          ),
          if (enabled && !destructive)
            const Icon(CupertinoIcons.chevron_right,
                size: 15, color: AppColors.tertiaryLabel),
        ],
      ),
    );

    return enabled
        ? CupertinoButton(
            padding: EdgeInsets.zero,
            onPressed: onTap,
            child: content,
          )
        : Opacity(opacity: 0.55, child: content);
  }

  Widget _infoRow({
    required IconData icon,
    required String title,
    required String value,
  }) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 13),
      child: Row(
        children: [
          Icon(icon, size: 19, color: AppColors.secondaryLabel),
          const SizedBox(width: 12),
          Expanded(
            child: Text(
              title,
              style: const TextStyle(fontSize: 15.5, color: AppColors.label),
            ),
          ),
          Text(
            value,
            style: const TextStyle(
              fontSize: 13.5,
              color: AppColors.secondaryLabel,
            ),
          ),
        ],
      ),
    );
  }
}

class _RowDivider extends StatelessWidget {
  const _RowDivider();

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.only(left: 45),
      height: 0.5,
      color: AppColors.separator,
    );
  }
}
