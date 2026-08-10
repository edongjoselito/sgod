import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../core/widgets/app_dialogs.dart';
import '../../../../data/models/donation_item.dart';
import '../../../core/di.dart';
import 'donation_edit_view.dart';
import 'tax_incentive_requirements_view.dart';

/// Full-screen donation detail page with all info + action buttons.
class DonationDetailView extends StatefulWidget {
  const DonationDetailView({
    super.key,
    required this.item,
    this.onChanged,
  });

  final DonationItem item;
  final VoidCallback? onChanged;

  @override
  State<DonationDetailView> createState() => _DonationDetailViewState();
}

class _DonationDetailViewState extends State<DonationDetailView> {
  bool _busy = false;
  String? _busyLabel;

  DonationItem get item => widget.item;

  bool get _hasTax =>
      item.taxIncentiveApplicable == 'Yes' ||
      item.taxIncentiveApplicable == '1' ||
      item.taxIncentiveApplicable == 'true';

  @override
  Widget build(BuildContext context) {
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: const CupertinoNavigationBar(
        middle: Text('Donation Details'),
        backgroundColor: AppColors.surface,
        border: Border(
          bottom: BorderSide(color: AppColors.separator, width: 0.5),
        ),
      ),
      child: SafeArea(
        top: false,
        child: Column(
          children: [
            Expanded(
              child: SingleChildScrollView(
                padding: const EdgeInsets.fromLTRB(16, 16, 16, 24),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // ── Title card ──────────────────────────────────────
                    Container(
                      width: double.infinity,
                      padding: const EdgeInsets.all(16),
                      decoration: BoxDecoration(
                        color: AppColors.surface,
                        borderRadius: BorderRadius.circular(14),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            item.spicificContribution.isNotEmpty
                                ? item.spicificContribution
                                : 'Donation',
                            style: const TextStyle(
                              fontSize: 18,
                              fontWeight: FontWeight.w700,
                              color: AppColors.label,
                              height: 1.3,
                            ),
                          ),
                          const SizedBox(height: 8),
                          Row(
                            children: [
                              if (item.partnerName.isNotEmpty) ...[
                                _chip(item.partnerName, AppColors.primary),
                                const SizedBox(width: 6),
                              ],
                              if (_hasTax)
                                _chip('Tax Incentive', AppColors.success),
                            ],
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 16),
                    // ── Details card ─────────────────────────────────────
                    Container(
                      width: double.infinity,
                      decoration: BoxDecoration(
                        color: AppColors.surface,
                        borderRadius: BorderRadius.circular(14),
                      ),
                      clipBehavior: Clip.antiAlias,
                      child: Column(
                        children: [
                          _detailRow('Date',
                              item.cDate.isNotEmpty ? item.cDate : '—'),
                          _divider(),
                          _detailRow('Partner',
                              item.partnerName.isNotEmpty ? item.partnerName : '—'),
                          _divider(),
                          _detailRow('Contribution Type',
                              item.spicificContribution.isNotEmpty ? item.spicificContribution : '—'),
                          _divider(),
                          _detailRow('Amount',
                              item.amount.isNotEmpty ? item.amount : '—'),
                          _divider(),
                          _detailRow('Quantity',
                              item.quantity.isNotEmpty ? item.quantity : '—'),
                          _divider(),
                          _detailRow('Unit',
                              item.unitOfContribution.isNotEmpty ? item.unitOfContribution : '—'),
                          _divider(),
                          _detailRow('Beneficiary Learners',
                              item.noBeneficiaryLearnes.isNotEmpty ? item.noBeneficiaryLearnes : '—'),
                          _divider(),
                          _detailRow('Beneficiary Personnel',
                              item.noBeneficiaryPersonnel.isNotEmpty ? item.noBeneficiaryPersonnel : '—'),
                          _divider(),
                          _detailRow('Form of Agreement',
                              item.formOfAgreement.isNotEmpty ? item.formOfAgreement : '—'),
                          _divider(),
                          _detailRow('Agreement Start',
                              item.agreementStarted.isNotEmpty ? item.agreementStarted : '—'),
                          _divider(),
                          _detailRow('Agreement End',
                              item.agreementEnd.isNotEmpty ? item.agreementEnd : '—'),
                          _divider(),
                          _detailRow('Project Category',
                              item.projectCategory.isNotEmpty ? item.projectCategory : '—'),
                          _divider(),
                          _detailRow('Project Name',
                              item.projectName.isNotEmpty ? item.projectName : '—'),
                          _divider(),
                          _detailRow('Status',
                              item.statusAgreement.isNotEmpty ? item.statusAgreement : '—'),
                          _divider(),
                          _detailRow('Tax Incentive',
                              _hasTax ? 'Applicable' : 'Not Applicable'),
                          _divider(),
                          _detailRow('Initiated By',
                              item.initiatedBy.isNotEmpty ? item.initiatedBy : '—'),
                          _divider(),
                          _detailRow('Remarks',
                              item.remarks.isNotEmpty ? item.remarks : '—'),
                        ],
                      ),
                    ),
                    // ── Breakdown items ──────────────────────────────────
                    if (item.breakdown.isNotEmpty) ...[
                      const SizedBox(height: 16),
                      _sectionLabel('Breakdown'),
                      Container(
                        width: double.infinity,
                        decoration: BoxDecoration(
                          color: AppColors.surface,
                          borderRadius: BorderRadius.circular(14),
                        ),
                        clipBehavior: Clip.antiAlias,
                        child: Column(
                          children: [
                            for (int i = 0; i < item.breakdown.length; i++) ...[
                              _breakdownRow(item.breakdown[i]),
                              if (i < item.breakdown.length - 1)
                                _divider(),
                            ],
                          ],
                        ),
                      ),
                    ],
                  ],
                ),
              ),
            ),
            // ── Action buttons ──────────────────────────────────────────
            Container(
              decoration: const BoxDecoration(
                color: AppColors.surface,
                border: Border(
                  top: BorderSide(color: AppColors.separator, width: 0.5),
                ),
              ),
              padding: const EdgeInsets.fromLTRB(16, 12, 16, 16),
              child: SafeArea(
                top: false,
                child: Row(
                  children: [
                    Expanded(
                      child: _actionButton(
                        icon: PhosphorIconsRegular.pencilSimple,
                        label: 'Edit',
                        color: AppColors.success,
                        onTap: () {
                          Navigator.push(
                            context,
                            CupertinoPageRoute(
                              builder: (_) => DonationEditView(
                                item: item,
                                onSaved: () {
                                  Navigator.pop(context);
                                },
                              ),
                            ),
                          );
                        },
                      ),
                    ),
                    const SizedBox(width: 8),
                    Expanded(
                      child: _actionButton(
                        icon: PhosphorIconsRegular.trash,
                        label: 'Delete',
                        color: AppColors.danger,
                        onTap: _confirmDelete,
                      ),
                    ),
                    const SizedBox(width: 8),
                    Expanded(
                      child: _actionButton(
                        icon: PhosphorIconsRegular.receipt,
                        label: 'Tax Reqs',
                        color: AppColors.info,
                        onTap: () {
                          Navigator.push(
                            context,
                            CupertinoPageRoute(
                              builder: (_) => TaxIncentiveRequirementsView(
                                donationId: item.id,
                              ),
                            ),
                          );
                        },
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  // ── Helpers ───────────────────────────────────────────────────────────────

  Widget _sectionLabel(String text) {
    return Padding(
      padding: const EdgeInsets.only(left: 4, bottom: 6),
      child: Text(
        text.toUpperCase(),
        style: const TextStyle(
          fontSize: 11,
          fontWeight: FontWeight.w700,
          color: AppColors.tertiaryLabel,
          letterSpacing: 0.5,
        ),
      ),
    );
  }

  Widget _chip(String text, Color color) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
      decoration: BoxDecoration(
        color: color.withOpacity(0.12),
        borderRadius: BorderRadius.circular(6),
      ),
      child: Text(
        text,
        style: TextStyle(
          fontSize: 11,
          fontWeight: FontWeight.w600,
          color: color,
        ),
        maxLines: 1,
        overflow: TextOverflow.ellipsis,
      ),
    );
  }

  Widget _detailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 130,
            child: Text(
              label,
              style: const TextStyle(
                fontSize: 14,
                fontWeight: FontWeight.w600,
                color: AppColors.secondaryLabel,
              ),
            ),
          ),
          Expanded(
            child: Text(
              value,
              style: const TextStyle(fontSize: 15, color: AppColors.label),
            ),
          ),
        ],
      ),
    );
  }

  Widget _breakdownRow(DonationBreakdownItem b) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            b.itemDescription.isNotEmpty ? b.itemDescription : 'Item',
            style: const TextStyle(
              fontSize: 14,
              fontWeight: FontWeight.w600,
              color: AppColors.label,
            ),
          ),
          const SizedBox(height: 4),
          Row(
            children: [
              if (b.quantity.isNotEmpty)
                Text(
                  'Qty: ${b.quantity} ${b.unit}',
                  style: const TextStyle(
                      fontSize: 12, color: AppColors.tertiaryLabel),
                ),
              if (b.quantity.isNotEmpty && b.amount.isNotEmpty)
                const SizedBox(width: 12),
              if (b.amount.isNotEmpty)
                Text(
                  'Amount: ${b.amount}',
                  style: const TextStyle(
                      fontSize: 12, color: AppColors.tertiaryLabel),
                ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _divider() => Container(height: 0.5, color: AppColors.separator);

  Widget _actionButton({
    required IconData icon,
    required String label,
    required Color color,
    required VoidCallback onTap,
  }) {
    return CupertinoButton(
      onPressed: _busy ? null : onTap,
      color: color.withOpacity(0.1),
      borderRadius: BorderRadius.circular(10),
      minimumSize: const Size(0, 48),
      padding: const EdgeInsets.symmetric(vertical: 10),
      child: _busy && label == _busyLabel
          ? const CupertinoActivityIndicator(radius: 10)
          : Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Icon(icon, size: 20, color: color),
                const SizedBox(height: 4),
                Text(
                  label,
                  style: TextStyle(
                    fontSize: 13,
                    fontWeight: FontWeight.w600,
                    color: color,
                  ),
                ),
              ],
            ),
    );
  }

  Future<void> _setBusy(String label, Future<void> Function() fn) async {
    setState(() {
      _busy = true;
      _busyLabel = label;
    });
    try {
      await fn();
    } catch (e) {
      if (mounted) {
        AppDialogs.alert(context, '$label Failed', '$e');
      }
    } finally {
      if (mounted) {
        setState(() {
          _busy = false;
          _busyLabel = null;
        });
      }
    }
  }

  // ── Actions ───────────────────────────────────────────────────────────────

  void _confirmDelete() {
    AppDialogs.confirm(
      context,
      title: 'Delete Donation',
      message: 'Are you sure you want to delete this donation?',
      confirmText: 'Delete',
      destructive: true,
    ).then((ok) {
      if (ok == true) _doDelete();
    });
  }

  void _doDelete() {
    _setBusy('Delete', () async {
      await DI.adoptASchool.deleteDonation(item.id);
    }).then((_) {
      if (mounted) {
        Navigator.pop(context);
      }
    });
  }
}
