import 'package:flutter/cupertino.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../core/widgets/app_dialogs.dart';
import '../../../../data/models/donation_item.dart';
import '../../../../data/models/partner_item.dart';
import '../../../core/di.dart';

/// Edit or add a donation.
///
/// If [item] is null → create mode. If provided → edit mode.
class DonationEditView extends StatefulWidget {
  const DonationEditView({
    super.key,
    this.item,
    this.partnerId = '',
    this.partnerName = '',
    this.onSaved,
  });

  final DonationItem? item;
  final String partnerId;
  final String partnerName;
  final VoidCallback? onSaved;

  @override
  State<DonationEditView> createState() => _DonationEditViewState();
}

class _DonationEditViewState extends State<DonationEditView> {
  late final TextEditingController _amount;
  late final TextEditingController _quantity;
  late final TextEditingController _unit;
  late final TextEditingController _date;
  late final TextEditingController _agreementStart;
  late final TextEditingController _agreementEnd;
  late final TextEditingController _projectCategory;
  late final TextEditingController _projectName;
  late final TextEditingController _remarks;

  String _selectedPartnerId = '';
  String _selectedPartnerName = '';
  String _contributionType = '';
  String _formOfAgreement = 'MOA';
  String _status = 'Active';
  bool _taxIncentive = false;
  bool _saving = false;

  List<PartnerItem> _partners = [];
  List<String> _contributionTypes = [];
  bool _partnersLoading = true;

  static const _formOfAgreementOptions = ['MOA', 'MOU', 'Deed of Donation', 'Others'];
  static const _statusOptions = ['Active', 'Completed', 'Terminated', 'Pending'];

  @override
  void initState() {
    super.initState();
    final item = widget.item;
    _amount = TextEditingController(text: item?.amount ?? '');
    _quantity = TextEditingController(text: item?.quantity ?? '');
    _unit = TextEditingController(text: item?.unitOfContribution ?? '');
    _date = TextEditingController(text: item?.cDate ?? '');
    _agreementStart = TextEditingController(text: item?.agreementStarted ?? '');
    _agreementEnd = TextEditingController(text: item?.agreementEnd ?? '');
    _projectCategory = TextEditingController(text: item?.projectCategory ?? '');
    _projectName = TextEditingController(text: item?.projectName ?? '');
    _remarks = TextEditingController(text: item?.remarks ?? '');

    _contributionType = item?.spicificContribution ?? '';
    _formOfAgreement = item?.formOfAgreement.isNotEmpty == true
        ? item!.formOfAgreement
        : 'MOA';
    _status = item?.statusAgreement.isNotEmpty == true
        ? item!.statusAgreement
        : 'Active';
    _taxIncentive = item?.taxIncentiveApplicable == 'Yes' ||
        item?.taxIncentiveApplicable == '1' ||
        item?.taxIncentiveApplicable == 'true';

    if (item != null && item.partnersId.isNotEmpty) {
      _selectedPartnerId = item.partnersId;
      _selectedPartnerName = item.partnerName;
    } else if (widget.partnerId.isNotEmpty) {
      _selectedPartnerId = widget.partnerId;
      _selectedPartnerName = widget.partnerName;
    }

    _loadPartners();
    _loadContributionTypes();
  }

  @override
  void dispose() {
    _amount.dispose();
    _quantity.dispose();
    _unit.dispose();
    _date.dispose();
    _agreementStart.dispose();
    _agreementEnd.dispose();
    _projectCategory.dispose();
    _projectName.dispose();
    _remarks.dispose();
    super.dispose();
  }

  Future<void> _loadPartners() async {
    try {
      _partners = await DI.adoptASchool.fetchPartners();
      // If editing and partner name is empty, look it up
      if (_selectedPartnerId.isNotEmpty && _selectedPartnerName.isEmpty) {
        final match = _partners
            .where((p) => p.id == _selectedPartnerId)
            .firstOrNull;
        if (match != null) _selectedPartnerName = match.name;
      }
    } catch (e) {
      debugPrint('DonationEdit: loadPartners failed (non-fatal): $e');
    }
    if (mounted) setState(() => _partnersLoading = false);
  }

  Future<void> _loadContributionTypes() async {
    try {
      _contributionTypes = await DI.adoptASchool.fetchContributionTypes();
    } catch (e) {
      debugPrint('DonationEdit: loadContributionTypes failed (non-fatal): $e');
    }
    if (mounted) setState(() {});
  }

  @override
  Widget build(BuildContext context) {
    final isEdit = widget.item != null;
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: CupertinoNavigationBar(
        middle: Text(isEdit ? 'Edit Donation' : 'Add Donation'),
        backgroundColor: AppColors.surface,
        border: const Border(
          bottom: BorderSide(color: AppColors.separator, width: 0.5),
        ),
        trailing: CupertinoButton(
          padding: EdgeInsets.zero,
          onPressed: _saving ? null : _save,
          child: _saving
              ? const CupertinoActivityIndicator(radius: 12)
              : const Text('Save',
                  style: TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.w600,
                    color: AppColors.primary,
                  )),
        ),
      ),
      child: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.fromLTRB(16, 16, 16, 32),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _sectionLabel('Donation Details'),
              _card([
                _pickerField(
                  'Partner *',
                  _selectedPartnerName.isNotEmpty
                      ? _selectedPartnerName
                      : 'Select partner',
                  _partnersLoading ? null : () => _showPartnerPicker(context),
                ),
                _divider(),
                _dateField('Date', _date),
                _divider(),
                _pickerField(
                  'Contribution Type',
                  _contributionType.isNotEmpty
                      ? _contributionType
                      : 'Select type',
                  () => _showContributionTypePicker(context),
                ),
                _divider(),
                _textField('Amount', _amount,
                    placeholder: 'e.g. 50000',
                    keyboardType:
                        const TextInputType.numberWithOptions(decimal: true)),
                _divider(),
                _textField('Quantity', _quantity,
                    placeholder: 'e.g. 10',
                    keyboardType:
                        const TextInputType.numberWithOptions(decimal: true)),
                _divider(),
                _textField('Unit', _unit, placeholder: 'e.g. boxes, pcs'),
              ]),
              const SizedBox(height: 16),
              _sectionLabel('Agreement'),
              _card([
                _pickerField('Form of Agreement', _formOfAgreement,
                    () => _showOptionPicker(
                        context, 'Form of Agreement', _formOfAgreementOptions,
                        (v) => setState(() => _formOfAgreement = v)),
                ),
                _divider(),
                _dateField('Agreement Start', _agreementStart),
                _divider(),
                _dateField('Agreement End', _agreementEnd),
              ]),
              const SizedBox(height: 16),
              _sectionLabel('Project'),
              _card([
                _textField('Project Category', _projectCategory,
                    placeholder: 'Enter project category'),
                _divider(),
                _textField('Project Name', _projectName,
                    placeholder: 'Enter project name'),
                _divider(),
                _pickerField('Status', _status,
                    () => _showOptionPicker(context, 'Status', _statusOptions,
                        (v) => setState(() => _status = v)),
                ),
              ]),
              const SizedBox(height: 16),
              _sectionLabel('Additional'),
              _card([
                _switchField('Tax Incentive Applicable', _taxIncentive, (v) {
                  setState(() => _taxIncentive = v);
                }),
                _divider(),
                _textField('Remarks', _remarks,
                    placeholder: 'Enter remarks', maxLines: 3),
              ]),
            ],
          ),
        ),
      ),
    );
  }

  // ── Widgets ───────────────────────────────────────────────────────────────

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

  Widget _card(List<Widget> children) {
    return Container(
      decoration: BoxDecoration(
        color: AppColors.surface,
        borderRadius: BorderRadius.circular(12),
      ),
      clipBehavior: Clip.antiAlias,
      child: Column(children: children),
    );
  }

  Widget _divider() => Container(height: 0.5, color: AppColors.separator);

  Widget _textField(
    String label,
    TextEditingController controller, {
    String placeholder = '',
    int maxLines = 1,
    TextInputType? keyboardType,
  }) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Padding(
            padding: const EdgeInsets.only(bottom: 4),
            child: Text(
              label,
              style: const TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w600,
                color: AppColors.secondaryLabel,
              ),
            ),
          ),
          CupertinoTextField(
            controller: controller,
            placeholder: placeholder,
            maxLines: maxLines,
            keyboardType: keyboardType,
            decoration: BoxDecoration(
              color: AppColors.secondaryBackground,
              borderRadius: BorderRadius.circular(8),
            ),
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
            style: const TextStyle(fontSize: 15, color: AppColors.label),
          ),
        ],
      ),
    );
  }

  Widget _pickerField(String label, String value, VoidCallback? onTap) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Padding(
            padding: const EdgeInsets.only(bottom: 4),
            child: Text(
              label,
              style: const TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w600,
                color: AppColors.secondaryLabel,
              ),
            ),
          ),
          CupertinoButton(
            padding: EdgeInsets.zero,
            onPressed: onTap,
            child: Container(
              width: double.infinity,
              padding:
                  const EdgeInsets.symmetric(horizontal: 12, vertical: 12),
              decoration: BoxDecoration(
                color: AppColors.secondaryBackground,
                borderRadius: BorderRadius.circular(8),
              ),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Expanded(
                    child: Text(
                      value,
                      style: const TextStyle(
                          fontSize: 15, color: AppColors.label),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                  ),
                  const Icon(CupertinoIcons.chevron_down,
                      size: 16, color: AppColors.tertiaryLabel),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _dateField(String label, TextEditingController controller) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Padding(
            padding: const EdgeInsets.only(bottom: 4),
            child: Text(
              label,
              style: const TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w600,
                color: AppColors.secondaryLabel,
              ),
            ),
          ),
          CupertinoButton(
            padding: EdgeInsets.zero,
            onPressed: () => _pickDate(controller),
            child: Container(
              width: double.infinity,
              padding:
                  const EdgeInsets.symmetric(horizontal: 12, vertical: 12),
              decoration: BoxDecoration(
                color: AppColors.secondaryBackground,
                borderRadius: BorderRadius.circular(8),
              ),
              child: Row(
                children: [
                  const Icon(CupertinoIcons.calendar,
                      size: 16, color: AppColors.tertiaryLabel),
                  const SizedBox(width: 8),
                  Expanded(
                    child: Text(
                      controller.text.isEmpty ? 'Select date' : controller.text,
                      style: TextStyle(
                        fontSize: 15,
                        color: controller.text.isEmpty
                            ? AppColors.tertiaryLabel
                            : AppColors.label,
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _switchField(
      String label, bool value, ValueChanged<bool> onChanged) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Expanded(
            child: Text(
              label,
              style: const TextStyle(
                fontSize: 15,
                fontWeight: FontWeight.w500,
                color: AppColors.label,
              ),
            ),
          ),
          CupertinoSwitch(
            value: value,
            onChanged: onChanged,
            activeTrackColor: AppColors.primary,
          ),
        ],
      ),
    );
  }

  // ── Pickers ───────────────────────────────────────────────────────────────

  void _pickDate(TextEditingController controller) {
    DateTime initial = DateTime.now();
    if (controller.text.isNotEmpty) {
      final parsed = DateTime.tryParse(controller.text);
      if (parsed != null) initial = parsed;
    }
    AppDialogs.pickDate(context, initialDate: initial).then((dt) {
      if (dt != null) {
        controller.text = dt.toIso8601String().split('T')[0];
      }
    });
  }

  void _showPartnerPicker(BuildContext context) {
    AppDialogs.showOptions<String>(
      context,
      title: 'Select Partner',
      options: [
        for (final p in _partners)
          (p.name.isNotEmpty ? p.name : 'Unnamed', p.name.isNotEmpty ? p.name : 'Unnamed'),
      ],
      cancelText: 'Cancel',
    ).then((selected) {
      if (selected != null) {
        final match = _partners.where((p) =>
            (p.name.isNotEmpty ? p.name : 'Unnamed') == selected).firstOrNull;
        if (match != null) {
          setState(() {
            _selectedPartnerId = match.id;
            _selectedPartnerName = match.name;
          });
        }
      }
    });
  }

  void _showContributionTypePicker(BuildContext context) {
    final options = _contributionTypes.isNotEmpty
        ? _contributionTypes
        : ['Cash', 'Goods', 'Services', 'Others'];
    _showOptionPicker(context, 'Contribution Type', options, (v) {
      setState(() => _contributionType = v);
    });
  }

  void _showOptionPicker(
    BuildContext context,
    String title,
    List<String> options,
    ValueChanged<String> onSelect,
  ) {
    AppDialogs.showOptions<String>(
      context,
      title: 'Select $title',
      options: [for (final o in options) (o, o)],
      cancelText: 'Cancel',
    ).then((selected) {
      if (selected != null) onSelect(selected);
    });
  }

  // ── Save ──────────────────────────────────────────────────────────────────
  Future<void> _save() async {
    if (_selectedPartnerId.isEmpty) {
      _alert('Partner is required');
      return;
    }

    setState(() => _saving = true);
    try {
      final fields = <String, dynamic>{
        'partnersId': _selectedPartnerId,
        'partnerName': _selectedPartnerName,
        'cDate': _date.text.trim(),
        'spicificContribution': _contributionType,
        'unitOfContribution': _unit.text.trim(),
        'quantity': _quantity.text.trim(),
        'amount': _amount.text.trim(),
        'formOfAgreement': _formOfAgreement,
        'agreementStarted': _agreementStart.text.trim(),
        'agreementEnd': _agreementEnd.text.trim(),
        'projectCategory': _projectCategory.text.trim(),
        'projectName': _projectName.text.trim(),
        'statusAgreement': _status,
        'taxIncentiveApplicable': _taxIncentive ? 'Yes' : 'No',
        'remarks': _remarks.text.trim(),
      };

      await DI.adoptASchool.saveDonation(fields, id: widget.item?.id ?? '');
      if (mounted) {
        widget.onSaved?.call();
        Navigator.pop(context);
      }
    } catch (e) {
      if (mounted) _alert('Save failed: $e');
    } finally {
      if (mounted) setState(() => _saving = false);
    }
  }

  void _alert(String message) {
    AppDialogs.alert(context, 'Notice', message);
  }
}
