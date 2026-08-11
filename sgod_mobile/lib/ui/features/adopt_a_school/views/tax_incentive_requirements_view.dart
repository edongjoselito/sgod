import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart' show Material;
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../core/widgets/app_dialogs.dart';
import '../../../../data/models/tax_incentive_requirement_item.dart';
import '../../../core/di.dart';

/// Tax Incentive Requirements view — lists requirements for a donation,
/// allows add/edit/delete and status changes.
class TaxIncentiveRequirementsView extends StatefulWidget {
  const TaxIncentiveRequirementsView({
    super.key,
    required this.donationId,
    this.onMenuTap,
  });

  final String donationId;
  final VoidCallback? onMenuTap;

  @override
  State<TaxIncentiveRequirementsView> createState() =>
      _TaxIncentiveRequirementsViewState();
}

class _TaxIncentiveRequirementsViewState
    extends State<TaxIncentiveRequirementsView> {
  List<TaxIncentiveRequirementItem> _items = [];
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
      _items = await DI.adoptASchool.fetchTaxRequirements(widget.donationId);
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
        middle: const Text('Tax Requirements'),
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
          onPressed: _showAddSheet,
          child: const Icon(CupertinoIcons.add, size: 28, color: AppColors.primary),
        ),
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
            Text(_error!, style: const TextStyle(fontSize: 14, color: AppColors.secondaryLabel)),
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
            const Icon(PhosphorIconsRegular.certificate,
                size: 48, color: AppColors.tertiaryLabel),
            const SizedBox(height: 16),
            const Text('No requirements yet',
                style: TextStyle(
                    fontSize: 17,
                    fontWeight: FontWeight.w600,
                    color: AppColors.label)),
            const SizedBox(height: 6),
            const Text('Tap + to add a tax incentive requirement.',
                style: TextStyle(fontSize: 14, color: AppColors.secondaryLabel)),
          ],
        ),
      );
    }
    return RefreshIndicator(
      onRefresh: _load,
      child: ListView.separated(
        padding: const EdgeInsets.fromLTRB(16, 16, 16, 32),
        itemCount: _items.length,
        separatorBuilder: (_, __) => const SizedBox(height: 8),
        itemBuilder: (context, i) => _RequirementCard(
          item: _items[i],
          onTap: () => _showEditSheet(_items[i]),
          onDelete: () => _confirmDelete(_items[i]),
          onStatusChange: () => _showStatusPicker(_items[i]),
        ),
      ),
    );
  }

  void _showAddSheet() {
    _showFormSheet(null);
  }

  void _showEditSheet(TaxIncentiveRequirementItem item) {
    _showFormSheet(item);
  }

  void _showFormSheet(TaxIncentiveRequirementItem? existing) {
    final reqController =
        TextEditingController(text: existing?.requirement ?? '');
    final remarksController =
        TextEditingController(text: existing?.remarks ?? '');
    String status = existing?.status ?? 'Pending';
    bool saving = false;

    AppDialogs.showSheet(
      context,
      (ctx) => StatefulBuilder(
        builder: (ctx, setSheetState) {
          return Material(
            color: AppColors.surface,
            borderRadius: const BorderRadius.only(
              topLeft: Radius.circular(20),
              topRight: Radius.circular(20),
            ),
            child: Container(
              height: MediaQuery.of(context).size.height * 0.65,
              decoration: const BoxDecoration(
                color: AppColors.surface,
                borderRadius: BorderRadius.only(
                  topLeft: Radius.circular(20),
                  topRight: Radius.circular(20),
                ),
              ),
              child: Column(
                children: [
                  // Handle
                  Container(
                    margin: const EdgeInsets.only(top: 8),
                    width: 36,
                    height: 5,
                    decoration: BoxDecoration(
                      color: AppColors.tertiaryLabel.withValues(alpha: 0.3),
                      borderRadius: BorderRadius.circular(3),
                    ),
                  ),
                  Padding(
                    padding: const EdgeInsets.all(16),
                    child: Text(
                      existing == null ? 'Add Requirement' : 'Edit Requirement',
                      style: const TextStyle(
                        fontSize: 17,
                        fontWeight: FontWeight.w700,
                        color: AppColors.label,
                      ),
                    ),
                  ),
                  Container(height: 0.5, color: AppColors.separator),
                  Expanded(
                    child: SingleChildScrollView(
                      padding: const EdgeInsets.all(20),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text('Requirement *',
                              style: TextStyle(
                                  fontSize: 13,
                                  fontWeight: FontWeight.w600,
                                  color: AppColors.secondaryLabel)),
                          const SizedBox(height: 6),
                          CupertinoTextField(
                            controller: reqController,
                            placeholder: 'e.g. Deed of Donation',
                            decoration: BoxDecoration(
                              color: AppColors.secondaryBackground,
                              borderRadius: BorderRadius.circular(8),
                            ),
                            padding: const EdgeInsets.symmetric(
                                horizontal: 12, vertical: 12),
                            style: const TextStyle(
                                fontSize: 15, color: AppColors.label),
                          ),
                          const SizedBox(height: 16),
                          const Text('Status',
                              style: TextStyle(
                                  fontSize: 13,
                                  fontWeight: FontWeight.w600,
                                  color: AppColors.secondaryLabel)),
                          const SizedBox(height: 6),
                          CupertinoButton(
                            padding: EdgeInsets.zero,
                            onPressed: () {
                              AppDialogs.showOptions<String>(
                                ctx,
                                title: 'Select Status',
                                options: [
                                  for (final s in [
                                    'Pending',
                                    'Submitted',
                                    'Approved',
                                    'Rejected'
                                  ])
                                    (s, s),
                                ],
                                cancelText: 'Cancel',
                              ).then((selected) {
                                if (selected != null) {
                                  setSheetState(() => status = selected);
                                }
                              });
                            },
                            child: Container(
                              width: double.infinity,
                              padding: const EdgeInsets.symmetric(
                                  horizontal: 12, vertical: 12),
                              decoration: BoxDecoration(
                                color: AppColors.secondaryBackground,
                                borderRadius: BorderRadius.circular(8),
                              ),
                              child: Row(
                                children: [
                                  Expanded(
                                    child: Text(status,
                                        style: const TextStyle(
                                            fontSize: 15, color: AppColors.label)),
                                  ),
                                  const Icon(CupertinoIcons.chevron_down,
                                      size: 16, color: AppColors.tertiaryLabel),
                                ],
                              ),
                            ),
                          ),
                          const SizedBox(height: 16),
                          const Text('Remarks',
                              style: TextStyle(
                                  fontSize: 13,
                                  fontWeight: FontWeight.w600,
                                  color: AppColors.secondaryLabel)),
                          const SizedBox(height: 6),
                          CupertinoTextField(
                            controller: remarksController,
                            placeholder: 'Enter remarks',
                            maxLines: 3,
                            decoration: BoxDecoration(
                              color: AppColors.secondaryBackground,
                              borderRadius: BorderRadius.circular(8),
                            ),
                            padding: const EdgeInsets.symmetric(
                                horizontal: 12, vertical: 10),
                            style: const TextStyle(
                                fontSize: 15, color: AppColors.label),
                          ),
                        ],
                      ),
                    ),
                  ),
                  Container(height: 0.5, color: AppColors.separator),
                  Padding(
                    padding: const EdgeInsets.all(16),
                    child: Row(
                      children: [
                        Expanded(
                          child: CupertinoButton(
                            onPressed: () => Navigator.pop(ctx),
                            color: AppColors.secondaryBackground,
                            borderRadius: BorderRadius.circular(10),
                            child: const Text('Cancel',
                                style: TextStyle(
                                    fontSize: 15,
                                    fontWeight: FontWeight.w600,
                                    color: AppColors.label)),
                          ),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: CupertinoButton(
                            onPressed: saving
                                ? null
                                : () async {
                                    if (reqController.text.trim().isEmpty) {
                                      AppDialogs.alert(ctx, 'Notice',
                                          'Requirement text is required.');
                                      return;
                                    }
                                    setSheetState(() => saving = true);
                                    try {
                                      final fields = <String, dynamic>{
                                        'donation_id': widget.donationId,
                                        'requirement': reqController.text.trim(),
                                        'status': status,
                                        'remarks': remarksController.text.trim(),
                                      };
                                      await DI.adoptASchool.saveTaxRequirement(
                                        fields,
                                        id: existing?.id ?? '',
                                      );
                                      if (ctx.mounted) Navigator.pop(ctx);
                                      _load();
                                    } catch (e) {
                                      if (ctx.mounted) {
                                        AppDialogs.alert(ctx, 'Error', '$e');
                                      }
                                    } finally {
                                      if (ctx.mounted) {
                                        setSheetState(() => saving = false);
                                      }
                                    }
                                  },
                            color: AppColors.primary,
                            borderRadius: BorderRadius.circular(10),
                            child: saving
                                ? const CupertinoActivityIndicator(
                                    radius: 12, color: CupertinoColors.white)
                                : const Text('Save',
                                    style: TextStyle(
                                        fontSize: 15,
                                        fontWeight: FontWeight.w600,
                                        color: CupertinoColors.white)),
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          );
        },
      ),
    );
  }

  void _showStatusPicker(TaxIncentiveRequirementItem item) {
    AppDialogs.showOptions<String>(
      context,
      title: 'Change Status',
      options: [
        for (final s in ['Pending', 'Submitted', 'Approved', 'Rejected']) (s, s),
      ],
      cancelText: 'Cancel',
    ).then((selected) async {
      if (selected == null) return;
      try {
        await DI.adoptASchool.saveTaxRequirement({
          'donation_id': item.donationId,
          'requirement': item.requirement,
          'status': selected,
          'remarks': item.remarks,
        }, id: item.id);
        _load();
      } catch (e) {
        if (mounted) {
          AppDialogs.alert(context, 'Error', '$e');
        }
      }
    });
  }

  void _confirmDelete(TaxIncentiveRequirementItem item) {
    AppDialogs.confirm(
      context,
      title: 'Delete Requirement',
      message: 'Remove "${item.requirement}"?',
      confirmText: 'Delete',
      destructive: true,
    ).then((ok) async {
      if (ok != true) return;
      try {
        await DI.adoptASchool.deleteTaxRequirement(item.id);
        _load();
      } catch (e) {
        if (mounted) {
          AppDialogs.alert(context, 'Error', '$e');
        }
      }
    });
  }
}

// ── Requirement card ───────────────────────────────────────────────────────
class _RequirementCard extends StatelessWidget {
  const _RequirementCard({
    required this.item,
    required this.onTap,
    required this.onDelete,
    required this.onStatusChange,
  });

  final TaxIncentiveRequirementItem item;
  final VoidCallback onTap;
  final VoidCallback onDelete;
  final VoidCallback onStatusChange;

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onLongPress: onDelete,
      child: CupertinoButton(
        onPressed: onTap,
        padding: EdgeInsets.zero,
        child: Container(
          decoration: BoxDecoration(
            color: AppColors.surface,
            borderRadius: BorderRadius.circular(12),
          ),
          padding: const EdgeInsets.all(14),
          child: Row(
            children: [
              Container(
                width: 36,
                height: 36,
                decoration: BoxDecoration(
                  color: _statusColor().withValues(alpha: 0.12),
                  borderRadius: BorderRadius.circular(8),
                ),
                child: Icon(PhosphorIconsRegular.certificate,
                    size: 18, color: _statusColor()),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      item.requirement,
                      style: const TextStyle(
                        fontSize: 15,
                        fontWeight: FontWeight.w600,
                        color: AppColors.label,
                      ),
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                    if (item.remarks.isNotEmpty) ...[
                      const SizedBox(height: 2),
                      Text(
                        item.remarks,
                        style: const TextStyle(
                          fontSize: 12,
                          color: AppColors.tertiaryLabel,
                        ),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                    ],
                  ],
                ),
              ),
              const SizedBox(width: 8),
              GestureDetector(
                onTap: onStatusChange,
                child: Container(
                  padding:
                      const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                  decoration: BoxDecoration(
                    color: _statusColor().withValues(alpha: 0.12),
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: Text(
                    item.status,
                    style: TextStyle(
                      fontSize: 12,
                      fontWeight: FontWeight.w600,
                      color: _statusColor(),
                    ),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Color _statusColor() {
    switch (item.status.toLowerCase()) {
      case 'approved':
      case 'completed':
        return AppColors.success;
      case 'submitted':
        return AppColors.info;
      case 'rejected':
        return AppColors.danger;
      default:
        return AppColors.warning;
    }
  }
}

// ── Simple RefreshIndicator wrapper ────────────────────────────────────────
class RefreshIndicator extends StatelessWidget {
  const RefreshIndicator({
    super.key,
    required this.onRefresh,
    required this.child,
  });

  final Future<void> Function() onRefresh;
  final Widget child;

  @override
  Widget build(BuildContext context) {
    return CustomScrollView(
      slivers: [
        CupertinoSliverRefreshControl(onRefresh: onRefresh),
        SliverToBoxAdapter(child: child),
      ],
    );
  }
}
