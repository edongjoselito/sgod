import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart' show LinearProgressIndicator;
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../core/di.dart';
import 'tax_incentive_requirements_view.dart';

/// ASP Tracking — shows all tax-incentive-applicable donations with
/// their requirement completion status.
class AspTrackingView extends StatefulWidget {
  const AspTrackingView({super.key, this.onMenuTap});

  final VoidCallback? onMenuTap;

  @override
  State<AspTrackingView> createState() => _AspTrackingViewState();
}

class _AspTrackingViewState extends State<AspTrackingView> {
  List<Map<String, dynamic>> _items = [];
  bool _loading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() {
      _loading = true;
      _error = null;
    });
    try {
      final data = await DI.api.get('api/asp_tracking_index');
      if (data != null) {
        _items = (data as List<dynamic>).cast<Map<String, dynamic>>();
      }
    } catch (e) {
      _error = '$e';
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: CupertinoNavigationBar(
        middle: const Text('ASP Tracking'),
        leading: widget.onMenuTap != null
            ? CupertinoButton(
                padding: EdgeInsets.zero,
                onPressed: widget.onMenuTap,
                child: const Icon(CupertinoIcons.line_horizontal_3,
                    size: 26, color: AppColors.label),
              )
            : null,
        backgroundColor: AppColors.surface,
        border: const Border(
          bottom: BorderSide(color: AppColors.separator, width: 0.5),
        ),
      ),
      child: SafeArea(
        child: _buildBody(),
      ),
    );
  }

  Widget _buildBody() {
    if (_loading) {
      return const Center(child: CupertinoActivityIndicator(radius: 16));
    }
    if (_error != null) {
      return Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Icon(CupertinoIcons.wifi_exclamationmark,
                size: 40, color: AppColors.tertiaryLabel),
            const SizedBox(height: 12),
            Text(_error!,
                style: const TextStyle(
                    fontSize: 14, color: AppColors.secondaryLabel)),
            const SizedBox(height: 12),
            CupertinoButton(onPressed: _load, child: const Text('Retry')),
          ],
        ),
      );
    }
    if (_items.isEmpty) {
      return Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Icon(PhosphorIconsRegular.handshake,
                size: 48, color: AppColors.tertiaryLabel),
            const SizedBox(height: 16),
            const Text('No tax-incentive donations',
                style: TextStyle(
                    fontSize: 17,
                    fontWeight: FontWeight.w600,
                    color: AppColors.label)),
            const SizedBox(height: 6),
            const Text(
                'Donations marked as tax-incentive applicable\nwill appear here for tracking.',
                textAlign: TextAlign.center,
                style: TextStyle(
                    fontSize: 14, color: AppColors.secondaryLabel)),
          ],
        ),
      );
    }
    return CustomScrollView(
      slivers: [
        CupertinoSliverRefreshControl(onRefresh: _load),
        SliverPadding(
          padding: const EdgeInsets.fromLTRB(16, 16, 16, 32),
          sliver: SliverList(
            delegate: SliverChildBuilderDelegate(
              (context, i) => _AspTrackingCard(
                item: _items[i],
                onTap: () {
                  Navigator.push(
                    context,
                    CupertinoPageRoute(
                      builder: (_) => TaxIncentiveRequirementsView(
                        donationId: _items[i]['donation_id']?.toString() ?? '',
                      ),
                    ),
                  );
                },
              ),
              childCount: _items.length,
            ),
          ),
        ),
      ],
    );
  }
}

// ── ASP Tracking Card ──────────────────────────────────────────────────────
class _AspTrackingCard extends StatelessWidget {
  const _AspTrackingCard({required this.item, required this.onTap});

  final Map<String, dynamic> item;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    final partnerName = item['partner_name']?.toString() ?? 'Unknown Partner';
    final date = item['c_date']?.toString() ?? '';
    final contribution = item['spicific_contribution']?.toString() ?? '';
    final amount = item['amount']?.toString() ?? '0';
    final projectName = item['project_name']?.toString() ?? '';
    final total = int.tryParse(item['total_requirements']?.toString() ?? '0') ?? 0;
    final completed =
        int.tryParse(item['completed_requirements']?.toString() ?? '0') ?? 0;
    final progress = total > 0 ? completed / total : 0.0;

    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: CupertinoButton(
        onPressed: onTap,
        padding: EdgeInsets.zero,
        child: Container(
          decoration: BoxDecoration(
            color: AppColors.surface,
            borderRadius: BorderRadius.circular(14),
          ),
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // ── Header row ────────────────────────────────────────────
              Row(
                children: [
                  Container(
                    width: 40,
                    height: 40,
                    decoration: BoxDecoration(
                      color: AppColors.info.withValues(alpha: 0.12),
                      borderRadius: BorderRadius.circular(10),
                    ),
                    child: const Icon(PhosphorIconsRegular.handshake,
                        size: 20, color: AppColors.info),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          partnerName,
                          style: const TextStyle(
                            fontSize: 15,
                            fontWeight: FontWeight.w700,
                            color: AppColors.label,
                          ),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                        const SizedBox(height: 2),
                        Text(
                          [if (date.isNotEmpty) date, if (contribution.isNotEmpty) contribution]
                              .join(' • '),
                          style: const TextStyle(
                            fontSize: 13,
                            color: AppColors.secondaryLabel,
                          ),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ],
                    ),
                  ),
                  const Icon(CupertinoIcons.chevron_right,
                      size: 18, color: AppColors.tertiaryLabel),
                ],
              ),
              // ── Project name ──────────────────────────────────────────
              if (projectName.isNotEmpty) ...[
                const SizedBox(height: 10),
                Text(
                  projectName,
                  style: const TextStyle(
                    fontSize: 14,
                    color: AppColors.label,
                  ),
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                ),
              ],
              // ── Amount + progress ─────────────────────────────────────
              const SizedBox(height: 12),
              Row(
                children: [
                  Text(
                    '₱$amount',
                    style: const TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.w700,
                      color: AppColors.success,
                    ),
                  ),
                  const Spacer(),
                  Text(
                    '$completed/$total requirements',
                    style: const TextStyle(
                      fontSize: 13,
                      fontWeight: FontWeight.w600,
                      color: AppColors.secondaryLabel,
                    ),
                  ),
                ],
              ),
              if (total > 0) ...[
                const SizedBox(height: 8),
                ClipRRect(
                  borderRadius: BorderRadius.circular(4),
                  child: SizedBox(
                    height: 6,
                    child: LinearProgressIndicator(
                      value: progress.clamp(0, 1),
                      backgroundColor: AppColors.secondaryBackground,
                      valueColor: AlwaysStoppedAnimation(
                        progress >= 1 ? AppColors.success : AppColors.warning,
                      ),
                    ),
                  ),
                ),
              ],
            ],
          ),
        ),
      ),
    );
  }
}
