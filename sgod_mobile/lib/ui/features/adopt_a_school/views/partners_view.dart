import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../data/models/partner_item.dart';
import '../../../core/di.dart';
import 'partner_detail_view.dart';
import 'partner_edit_view.dart';

/// iOS-style Partners list for the Adopt-A-School feature.
class PartnersView extends StatefulWidget {
  const PartnersView({super.key, this.onMenuTap});

  final VoidCallback? onMenuTap;

  @override
  State<PartnersView> createState() => _PartnersViewState();
}

class _PartnersViewState extends State<PartnersView> {
  List<PartnerItem> _all = [];
  List<PartnerItem> _filtered = [];
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
      final items = await DI.adoptASchool.fetchPartners();
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
      _filtered = _all.where((p) {
        return p.name.toLowerCase().contains(q) ||
            p.contactPerson.toLowerCase().contains(q) ||
            p.contact.toLowerCase().contains(q) ||
            p.generalType.toLowerCase().contains(q);
      }).toList();
    }
  }

  Color _typeColor(String type) {
    switch (type) {
      case 'Private_Sector':
        return AppColors.primary;
      case 'Public_Sector':
        return AppColors.success;
      case 'Civil_Society':
        return AppColors.warning;
      case 'International':
        return AppColors.district;
      default:
        return AppColors.tertiaryLabel;
    }
  }

  @override
  Widget build(BuildContext context) {
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      child: CustomScrollView(
        physics: const BouncingScrollPhysics(
          parent: AlwaysScrollableScrollPhysics(),
        ),
        slivers: [
          CupertinoSliverNavigationBar(
            largeTitle: const Text('Partners'),
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
                placeholder: 'Search partners',
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
                      '${_filtered.length} partner${_filtered.length == 1 ? '' : 's'}',
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
                    Text('Loading partners...',
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
                    const Text('Could not load partners',
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
                    Icon(CupertinoIcons.building_2_fill,
                        size: 48, color: AppColors.tertiaryLabel),
                    SizedBox(height: 16),
                    Text('No partners found',
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
                        _PartnerRow(
                          item: _filtered[i],
                          typeColor: _typeColor(_filtered[i].generalType),
                          onTap: () {
                            Navigator.push(
                              context,
                              CupertinoPageRoute(
                                builder: (_) => PartnerDetailView(
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
        builder: (_) => PartnerEditView(
          onSaved: _load,
        ),
      ),
    );
  }
}

// ── Compact partner row ──────────────────────────────────────────────────────
class _PartnerRow extends StatelessWidget {
  const _PartnerRow({
    required this.item,
    required this.typeColor,
    required this.onTap,
  });
  final PartnerItem item;
  final Color typeColor;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
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
              color: typeColor.withOpacity(0.1),
              borderRadius: BorderRadius.circular(7),
            ),
            child: Icon(PhosphorIconsRegular.handshake, size: 16, color: typeColor),
          ),
          const SizedBox(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  item.name.isNotEmpty ? item.name : 'Unnamed Partner',
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
                    if (item.contactPerson.isNotEmpty) ...[
                      Text(
                        item.contactPerson,
                        style: const TextStyle(
                          fontSize: 11,
                          color: AppColors.tertiaryLabel,
                        ),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                      const SizedBox(width: 6),
                    ],
                    if (item.contact.isNotEmpty)
                      Text(
                        item.contact,
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
          if (item.generalType.isNotEmpty) ...[
            const SizedBox(width: 8),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
              decoration: BoxDecoration(
                color: typeColor.withOpacity(0.12),
                borderRadius: BorderRadius.circular(6),
              ),
              child: Text(
                item.generalType.replaceAll('_', ' '),
                style: TextStyle(
                  fontSize: 11,
                  fontWeight: FontWeight.w600,
                  color: typeColor,
                ),
                maxLines: 1,
                overflow: TextOverflow.ellipsis,
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
