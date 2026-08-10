import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../data/models/donation_item.dart';
import '../../../core/di.dart';
import 'donation_detail_view.dart';
import 'donation_edit_view.dart';

/// iOS-style Donations list for the Adopt-A-School feature.
///
/// If [partnerId] is provided, the list is filtered to that partner.
class DonationsView extends StatefulWidget {
  const DonationsView({
    super.key,
    this.partnerId = '',
    this.partnerName = '',
    this.onMenuTap,
  });

  final String partnerId;
  final String partnerName;
  final VoidCallback? onMenuTap;

  @override
  State<DonationsView> createState() => _DonationsViewState();
}

class _DonationsViewState extends State<DonationsView> {
  List<DonationItem> _all = [];
  List<DonationItem> _filtered = [];
  String _search = '';
  bool _isLoading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() {
      _isLoading = true;
      _error = null;
    });
    try {
      final items = await DI.adoptASchool.fetchDonations(
        partnerId: widget.partnerId,
      );
      _all = items;
      _applyFilter();
    } catch (e) {
      _error = '$e';
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  void _applyFilter() {
    if (_search.isEmpty) {
      _filtered = _all;
    } else {
      final q = _search.toLowerCase();
      _filtered = _all.where((d) {
        return d.partnerName.toLowerCase().contains(q) ||
            d.spicificContribution.toLowerCase().contains(q) ||
            d.projectName.toLowerCase().contains(q) ||
            d.projectCategory.toLowerCase().contains(q);
      }).toList();
    }
  }

  bool get _isFiltered => widget.partnerId.isNotEmpty;

  @override
  Widget build(BuildContext context) {
    final title = _isFiltered && widget.partnerName.isNotEmpty
        ? widget.partnerName
        : 'Donations';
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      child: CustomScrollView(
        physics: const BouncingScrollPhysics(
          parent: AlwaysScrollableScrollPhysics(),
        ),
        slivers: [
          if (_isFiltered)
            CupertinoSliverNavigationBar(
              largeTitle: Text(title),
              backgroundColor: AppColors.surface,
              border: const Border(
                bottom: BorderSide(color: AppColors.separator, width: 0.5),
              ),
              trailing: CupertinoButton(
                padding: EdgeInsets.zero,
                onPressed: () => _showAdd(context),
                child: const Icon(CupertinoIcons.add, size: 24),
              ),
            )
          else
            CupertinoSliverNavigationBar(
              largeTitle: const Text('Donations'),
              backgroundColor: AppColors.surface,
              border: const Border(
                bottom: BorderSide(color: AppColors.separator, width: 0.5),
              ),
              leading: widget.onMenuTap != null
                  ? CupertinoButton(
                      padding: EdgeInsets.zero,
                      onPressed: widget.onMenuTap,
                      child: const Icon(CupertinoIcons.line_horizontal_3,
                          size: 26, color: AppColors.label),
                    )
                  : null,
              trailing: CupertinoButton(
                padding: EdgeInsets.zero,
                onPressed: () => _showAdd(context),
                child: const Icon(CupertinoIcons.add, size: 24),
              ),
            ),
          CupertinoSliverRefreshControl(
            onRefresh: _load,
          ),
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.fromLTRB(12, 6, 12, 8),
              child: CupertinoSearchTextField(
                placeholder: 'Search donations',
                onChanged: (value) {
                  _search = value;
                  _applyFilter();
                  setState(() {});
                },
                style: const TextStyle(
                  fontSize: 16,
                  color: AppColors.label,
                ),
              ),
            ),
          ),
          SliverToBoxAdapter(
            child: _filtered.isNotEmpty
                ? Padding(
                    padding: const EdgeInsets.fromLTRB(16, 0, 16, 4),
                    child: Text(
                      '${_filtered.length} donation${_filtered.length == 1 ? '' : 's'}',
                      style: const TextStyle(
                        fontSize: 12,
                        color: AppColors.tertiaryLabel,
                      ),
                    ),
                  )
                : const SizedBox.shrink(),
          ),
          if (_isLoading && _all.isEmpty)
            const SliverFillRemaining(
              hasScrollBody: false,
              child: Center(
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    CupertinoActivityIndicator(radius: 16),
                    SizedBox(height: 16),
                    Text('Loading donations...',
                        style: TextStyle(
                            color: AppColors.secondaryLabel, fontSize: 15)),
                  ],
                ),
              ),
            )
          else if (_error != null && _all.isEmpty)
            SliverFillRemaining(
              hasScrollBody: false,
              child: Center(
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    const Icon(CupertinoIcons.wifi_exclamationmark,
                        size: 48, color: AppColors.tertiaryLabel),
                    const SizedBox(height: 16),
                    const Text('Could not load donations',
                        style: TextStyle(
                            fontSize: 17,
                            fontWeight: FontWeight.w600,
                            color: AppColors.label)),
                    const SizedBox(height: 4),
                    Text(_error!,
                        style: const TextStyle(
                            fontSize: 13, color: AppColors.secondaryLabel),
                        textAlign: TextAlign.center),
                    const SizedBox(height: 16),
                    CupertinoButton(
                      onPressed: _load,
                      child: const Text('Retry'),
                    ),
                  ],
                ),
              ),
            )
          else if (_filtered.isEmpty)
            const SliverFillRemaining(
              hasScrollBody: false,
              child: Center(
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Icon(CupertinoIcons.gift,
                        size: 48, color: AppColors.tertiaryLabel),
                    SizedBox(height: 16),
                    Text('No donations found',
                        style: TextStyle(
                            fontSize: 17,
                            fontWeight: FontWeight.w600,
                            color: AppColors.label)),
                    SizedBox(height: 6),
                    Text('Pull down to refresh.',
                        style: TextStyle(
                            fontSize: 15, color: AppColors.secondaryLabel)),
                  ],
                ),
              ),
            )
          else
            SliverPadding(
              padding: const EdgeInsets.fromLTRB(12, 0, 12, 32),
              sliver: SliverToBoxAdapter(
                child: Container(
                  decoration: BoxDecoration(
                    color: AppColors.surface,
                    borderRadius: BorderRadius.circular(12),
                  ),
                  clipBehavior: Clip.antiAlias,
                  child: Column(
                    children: [
                      for (int i = 0; i < _filtered.length; i++) ...[
                        _DonationRow(
                          item: _filtered[i],
                          showPartner: !_isFiltered,
                          onTap: () {
                            Navigator.push(
                              context,
                              CupertinoPageRoute(
                                builder: (_) => DonationDetailView(
                                  item: _filtered[i],
                                  onChanged: () {},
                                ),
                              ),
                            ).then((_) {
                            if (mounted) _load();
                          });
                          },
                        ),
                        if (i < _filtered.length - 1)
                          Container(height: 0.5, color: AppColors.separator),
                      ],
                    ],
                  ),
                ),
              ),
            ),
        ],
      ),
    );
  }

  void _showAdd(BuildContext context) {
    Navigator.push(
      context,
      CupertinoPageRoute(
        builder: (_) => DonationEditView(
          partnerId: widget.partnerId,
          partnerName: widget.partnerName,
          onSaved: _load,
        ),
      ),
    );
  }
}

// ── Compact donation row ─────────────────────────────────────────────────────
class _DonationRow extends StatelessWidget {
  const _DonationRow({
    required this.item,
    required this.showPartner,
    required this.onTap,
  });
  final DonationItem item;
  final bool showPartner;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    final hasTax = item.taxIncentiveApplicable == 'Yes' ||
        item.taxIncentiveApplicable == '1' ||
        item.taxIncentiveApplicable == 'true';
    return CupertinoButton(
      onPressed: onTap,
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
      minSize: 0,
      child: Row(
        children: [
          Container(
            width: 30,
            height: 30,
            decoration: BoxDecoration(
              color: AppColors.info.withOpacity(0.1),
              borderRadius: BorderRadius.circular(7),
            ),
            child: const Icon(PhosphorIconsRegular.currencyCircleDollar,
                size: 16, color: AppColors.info),
          ),
          const SizedBox(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  item.spicificContribution.isNotEmpty
                      ? item.spicificContribution
                      : 'Donation',
                  style: const TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.w600,
                    color: AppColors.label,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
                const SizedBox(height: 2),
                Row(
                  children: [
                    if (item.cDate.isNotEmpty) ...[
                      Text(
                        item.cDate,
                        style: const TextStyle(
                          fontSize: 11,
                          color: AppColors.tertiaryLabel,
                        ),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                      const SizedBox(width: 6),
                    ],
                    if (showPartner && item.partnerName.isNotEmpty) ...[
                      Text(
                        item.partnerName,
                        style: const TextStyle(
                          fontSize: 11,
                          color: AppColors.tertiaryLabel,
                        ),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                      const SizedBox(width: 6),
                    ],
                    if (item.amount.isNotEmpty)
                      Text(
                        item.amount,
                        style: const TextStyle(
                          fontSize: 11,
                          color: AppColors.tertiaryLabel,
                        ),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                  ],
                ),
              ],
            ),
          ),
          if (hasTax) ...[
            const SizedBox(width: 8),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
              decoration: BoxDecoration(
                color: AppColors.success.withOpacity(0.12),
                borderRadius: BorderRadius.circular(6),
              ),
              child: const Text(
                'Tax',
                style: TextStyle(
                  fontSize: 11,
                  fontWeight: FontWeight.w700,
                  color: AppColors.success,
                ),
              ),
            ),
          ],
          const SizedBox(width: 6),
          const Icon(CupertinoIcons.chevron_right,
              size: 14, color: AppColors.tertiaryLabel),
        ],
      ),
    );
  }
}
