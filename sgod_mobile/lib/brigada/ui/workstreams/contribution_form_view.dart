import 'package:flutter/cupertino.dart';

import '../../../core/theme/app_colors.dart';
import '../../../core/widgets/app_dialogs.dart';
import '../../../ui/core/di.dart';

/// Workstream F — Contribution create/edit form.
///
/// School users create or edit their own contribution records. Validated
/// records are locked. Uses `api_brigada/contribution_create` and
/// `api_brigada/contribution_update`.
class ContributionFormView extends StatefulWidget {
  const ContributionFormView({
    super.key,
    this.username,
    required this.sy,
    this.existing,
  });

  final String? username;
  final String sy;
  final Map<String, dynamic>? existing;

  @override
  State<ContributionFormView> createState() => _ContributionFormViewState();
}

class _ContributionFormViewState extends State<ContributionFormView> {
  final _controllers = <String, TextEditingController>{};
  List<Map<String, dynamic>> _partners = [];
  List<Map<String, dynamic>> _contributionTypes = [];
  bool _loading = true;
  bool _saving = false;
  String _selectedPartner = '';
  String _selectedContributionType = '';
  String _formOfAgreement = '';
  String _projectCategory = '';
  String _statusAgreement = '';
  String _initiatedBy = '';
  bool _taxIncentive = false;

  static const _formOfAgreements = [
    'MOA', 'MOU', 'Deed of Donation', 'Letter of Intent', 'Others',
  ];
  static const _projectCategories = [
    'Infrastructure', 'Learning Resources', 'Health & Nutrition',
    'Staff Development', 'School Improvement', 'Others',
  ];
  static const _statusAgreements = [
    'Ongoing', 'Completed', 'Pending', 'Terminated',
  ];
  static const _initiatedByOptions = ['School', 'Partner', 'Division Office'];

  @override
  void initState() {
    super.initState();
    _initControllers();
    _loadReferenceData();
  }

  void _initControllers() {
    final e = widget.existing;
    _controllers['c_date'] = TextEditingController(text: e?['c_date'] ?? '');
    _controllers['spicific_contribution'] =
        TextEditingController(text: e?['spicific_contribution'] ?? '');
    _controllers['unit_of_contribution'] =
        TextEditingController(text: e?['unit_of_contribution'] ?? '');
    _controllers['quantity'] =
        TextEditingController(text: '${e?['quantity_of_conftribution'] ?? ''}');
    _controllers['amount'] =
        TextEditingController(text: '${e?['amount'] ?? ''}');
    _controllers['no_beneficiary_learnes'] =
        TextEditingController(text: '${e?['no_beneficiary_learnes'] ?? '0'}');
    _controllers['no_beneficiary_personnel'] =
        TextEditingController(text: '${e?['no_beneficiary_personnel'] ?? '0'}');
    _controllers['agreement_started'] =
        TextEditingController(text: e?['agreement_started'] ?? '');
    _controllers['agreement_end'] =
        TextEditingController(text: e?['agreement_end'] ?? '');
    _controllers['project_name'] =
        TextEditingController(text: e?['project_name'] ?? '');
    _controllers['remarks'] =
        TextEditingController(text: e?['remarks'] ?? '');

    _selectedPartner = '${e?['partners_id'] ?? ''}';
    _selectedContributionType = '${e?['contribution_id'] ?? ''}';
    _formOfAgreement = (e?['form_of_agreement'] ?? '').toString();
    _projectCategory = (e?['project_category'] ?? '').toString();
    _statusAgreement = (e?['status_agreement'] ?? '').toString();
    _initiatedBy = (e?['initiated_by'] ?? '').toString();
    _taxIncentive = (e?['tax_incentive_applicable'] ?? 0) == 1 ||
        (e?['tax_incentive_applicable'] ?? 0) == true;
  }

  @override
  void dispose() {
    for (final c in _controllers.values) {
      c.dispose();
    }
    super.dispose();
  }

  Future<void> _loadReferenceData() async {
    try {
      final partnerData = await DI.api.get('api_brigada/partners', query: {
        'school_id': widget.username ?? '',
      });
      final typeData = await DI.api.get('api_brigada/contribution_types');
      if (mounted) {
        setState(() {
          _partners = partnerData == null
              ? []
              : ((partnerData as Map)['partners'] as List?)
                      ?.map((e) => e as Map<String, dynamic>)
                      .toList() ??
                  [];
          _contributionTypes = typeData == null
              ? []
              : ((typeData as Map)['contribution_types'] as List?)
                      ?.map((e) => e as Map<String, dynamic>)
                      .toList() ??
                  [];
          _loading = false;
        });
      }
    } catch (e) {
      if (mounted) setState(() => _loading = false);
    }
  }

  Future<void> _save() async {
    if (_selectedPartner.isEmpty) {
      AppDialogs.alert(context, 'Validation', 'Please select a partner.');
      return;
    }
    if (_selectedContributionType.isEmpty) {
      AppDialogs.alert(context, 'Validation',
          'Please select a contribution type.');
      return;
    }

    setState(() => _saving = true);
    try {
      final body = <String, dynamic>{
        'c_date': _controllers['c_date']!.text.trim(),
        'partners_id': _selectedPartner,
        'contribution_id': _selectedContributionType,
        'spicific_contribution': _controllers['spicific_contribution']!.text.trim(),
        'unit_of_contribution': _controllers['unit_of_contribution']!.text.trim(),
        'quantity_of_conftribution': _controllers['quantity']!.text.trim(),
        'amount': _controllers['amount']!.text.trim(),
        'no_beneficiary_learnes': _controllers['no_beneficiary_learnes']!.text.trim(),
        'no_beneficiary_personnel': _controllers['no_beneficiary_personnel']!.text.trim(),
        'form_of_agreement': _formOfAgreement,
        'agreement_started': _controllers['agreement_started']!.text.trim(),
        'agreement_end': _controllers['agreement_end']!.text.trim(),
        'project_category': _projectCategory,
        'project_name': _controllers['project_name']!.text.trim(),
        'status_agreement': _statusAgreement,
        'initiated_by': _initiatedBy,
        'remarks': _controllers['remarks']!.text.trim(),
        'sy': widget.sy,
        'tax_incentive_applicable': _taxIncentive ? 1 : 0,
      };

      if (widget.existing != null) {
        body['id'] = widget.existing!['id'];
        body['expected_updated_at'] = widget.existing!['updated_at'] ?? '';
        final result = await DI.write(
          endpoint: 'contribution_update',
          entity: 'brigada_contributions',
          operation: 'update',
          payload: body,
          prefix: 'api_brigada',
        );
        if (result == null && mounted) {
          AppDialogs.alert(context, 'Queued',
              'You are offline. This contribution has been queued and will sync automatically when you reconnect.');
        }
      } else {
        final result = await DI.write(
          endpoint: 'contribution_create',
          entity: 'brigada_contributions',
          operation: 'create',
          payload: body,
          prefix: 'api_brigada',
        );
        if (result == null && mounted) {
          AppDialogs.alert(context, 'Queued',
              'You are offline. This contribution has been queued and will sync automatically when you reconnect.');
        }
      }
      if (mounted) Navigator.pop(context);
    } catch (e) {
      if (mounted) AppDialogs.alert(context, 'Save Failed', '$e');
    } finally {
      if (mounted) setState(() => _saving = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final isEdit = widget.existing != null;
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: CupertinoNavigationBar(
        middle: Text(isEdit ? 'Edit Contribution' : 'New Contribution'),
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
                    fontWeight: FontWeight.w600,
                    color: AppColors.primary,
                  )),
        ),
      ),
      child: SafeArea(
        top: false,
        child: _loading
            ? const Center(child: CupertinoActivityIndicator(radius: 16))
            : SingleChildScrollView(
                padding: const EdgeInsets.fromLTRB(16, 16, 16, 32),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _section('Partner & Contribution'),
                    _pickerField(
                      'Partner *',
                      value: _partnerName(),
                      options: _partners
                          .map((p) =>
                              '${p['id']}|${p['name']}')
                          .toList(),
                      display: (val) => val.split('|').last,
                      onChanged: (val) =>
                          setState(() => _selectedPartner = val.split('|').first),
                    ),
                    _pickerField(
                      'Contribution Type *',
                      value: _contributionTypeName(),
                      options: _contributionTypes
                          .map((t) => '${t['id']}|${t['name']}')
                          .toList(),
                      display: (val) => val.split('|').last,
                      onChanged: (val) => setState(
                          () => _selectedContributionType = val.split('|').first),
                    ),
                    _textField('Specific Contribution',
                        _controllers['spicific_contribution']!,
                        placeholder: 'Describe the specific contribution'),
                    _textField('Unit', _controllers['unit_of_contribution']!,
                        placeholder: 'e.g. pieces, boxes, hours'),
                    _textField('Quantity', _controllers['quantity']!,
                        placeholder: '0', keyboardType: TextInputType.number),
                    _textField('Amount (₱)', _controllers['amount']!,
                        placeholder: '0.00',
                        keyboardType:
                            const TextInputType.numberWithOptions(decimal: true)),

                    _section('Beneficiaries'),
                    _textField('Learner Beneficiaries',
                        _controllers['no_beneficiary_learnes']!,
                        placeholder: '0', keyboardType: TextInputType.number),
                    _textField('Personnel Beneficiaries',
                        _controllers['no_beneficiary_personnel']!,
                        placeholder: '0', keyboardType: TextInputType.number),

                    _section('Agreement Details'),
                    _pickerField(
                      'Form of Agreement',
                      value: _formOfAgreement,
                      options: _formOfAgreements,
                      onChanged: (v) => setState(() => _formOfAgreement = v),
                    ),
                    _textField('Agreement Start Date',
                        _controllers['agreement_started']!,
                        placeholder: 'YYYY-MM-DD'),
                    _textField('Agreement End Date',
                        _controllers['agreement_end']!,
                        placeholder: 'YYYY-MM-DD'),
                    _pickerField(
                      'Project Category',
                      value: _projectCategory,
                      options: _projectCategories,
                      onChanged: (v) => setState(() => _projectCategory = v),
                    ),
                    _textField('Project Name',
                        _controllers['project_name']!,
                        placeholder: 'Name of the project'),
                    _pickerField(
                      'Agreement Status',
                      value: _statusAgreement,
                      options: _statusAgreements,
                      onChanged: (v) => setState(() => _statusAgreement = v),
                    ),
                    _pickerField(
                      'Initiated By',
                      value: _initiatedBy,
                      options: _initiatedByOptions,
                      onChanged: (v) => setState(() => _initiatedBy = v),
                    ),

                    _section('Dates & Remarks'),
                    _textField('Contribution Date',
                        _controllers['c_date']!,
                        placeholder: 'YYYY-MM-DD'),
                    _textArea('Remarks', _controllers['remarks']!,
                        placeholder: 'Additional notes...'),

                    // ── Tax incentive toggle ────────────────────────────
                    Padding(
                      padding: const EdgeInsets.only(top: 12, bottom: 8),
                      child: Container(
                        padding: const EdgeInsets.symmetric(
                            horizontal: 14, vertical: 12),
                        decoration: BoxDecoration(
                          color: AppColors.surface,
                          borderRadius: BorderRadius.circular(10),
                          border: Border.all(
                              color: AppColors.separator, width: 0.5),
                        ),
                        child: Row(
                          children: [
                            const Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text('Tax Incentive Applicable',
                                      style: TextStyle(
                                        fontSize: 15,
                                        fontWeight: FontWeight.w600,
                                        color: AppColors.label,
                                      )),
                                  Text(
                                      'Toggle if this contribution is eligible for tax incentives',
                                      style: TextStyle(
                                        fontSize: 12,
                                        color: AppColors.tertiaryLabel,
                                      )),
                                ],
                              ),
                            ),
                            CupertinoSwitch(
                              value: _taxIncentive,
                              onChanged: (v) =>
                                  setState(() => _taxIncentive = v),
                            ),
                          ],
                        ),
                      ),
                    ),

                    const SizedBox(height: 20),
                    SizedBox(
                      width: double.infinity,
                      child: CupertinoButton(
                        onPressed: _saving ? null : _save,
                        color: AppColors.primary,
                        borderRadius: BorderRadius.circular(12),
                        child: _saving
                            ? const CupertinoActivityIndicator(
                                radius: 14, color: CupertinoColors.white)
                            : Text(
                                isEdit ? 'Update Contribution' : 'Save Contribution',
                                style: const TextStyle(
                                  fontSize: 16,
                                  fontWeight: FontWeight.w600,
                                  color: CupertinoColors.white,
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

  String _partnerName() {
    if (_selectedPartner.isEmpty) return '';
    final match = _partners.where(
        (p) => '${p['id']}' == _selectedPartner);
    return match.isNotEmpty ? match.first['name'].toString() : '';
  }

  String _contributionTypeName() {
    if (_selectedContributionType.isEmpty) return '';
    final match = _contributionTypes.where(
        (t) => '${t['id']}' == _selectedContributionType);
    return match.isNotEmpty ? match.first['name'].toString() : '';
  }

  // ── Form helpers ──────────────────────────────────────────────────────────

  Widget _section(String title) => Padding(
        padding: const EdgeInsets.only(top: 20, bottom: 8),
        child: Text(title,
            style: const TextStyle(
              fontSize: 15,
              fontWeight: FontWeight.w700,
              color: AppColors.label,
            )),
      );

  Widget _textField(String label, TextEditingController controller,
      {String placeholder = '', TextInputType keyboardType = TextInputType.text}) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label,
              style: const TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w600,
                color: AppColors.secondaryLabel,
              )),
          const SizedBox(height: 6),
          CupertinoTextField(
            controller: controller,
            placeholder: placeholder,
            keyboardType: keyboardType,
            style: const TextStyle(color: AppColors.label, fontSize: 15),
            padding:
                const EdgeInsets.symmetric(horizontal: 12, vertical: 12),
            decoration: BoxDecoration(
              color: AppColors.surface,
              borderRadius: BorderRadius.circular(10),
              border: Border.all(color: AppColors.separator, width: 0.5),
            ),
          ),
        ],
      ),
    );
  }

  Widget _textArea(String label, TextEditingController controller,
      {String placeholder = ''}) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label,
              style: const TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w600,
                color: AppColors.secondaryLabel,
              )),
          const SizedBox(height: 6),
          CupertinoTextField(
            controller: controller,
            placeholder: placeholder,
            maxLines: 4,
            style: const TextStyle(color: AppColors.label, fontSize: 15),
            padding:
                const EdgeInsets.symmetric(horizontal: 12, vertical: 12),
            decoration: BoxDecoration(
              color: AppColors.surface,
              borderRadius: BorderRadius.circular(10),
              border: Border.all(color: AppColors.separator, width: 0.5),
            ),
          ),
        ],
      ),
    );
  }

  Widget _pickerField(
    String label, {
    required String value,
    required List<String> options,
    required ValueChanged<String> onChanged,
    String Function(String)? display,
  }) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label,
              style: const TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w600,
                color: AppColors.secondaryLabel,
              )),
          const SizedBox(height: 6),
          Container(
            decoration: BoxDecoration(
              color: AppColors.surface,
              borderRadius: BorderRadius.circular(10),
              border: Border.all(color: AppColors.separator, width: 0.5),
            ),
            child: CupertinoButton(
              onPressed: () =>
                  _showPicker(label, options, value, onChanged, display),
              padding:
                  const EdgeInsets.symmetric(horizontal: 12, vertical: 14),
              child: Row(
                children: [
                  Expanded(
                    child: Text(
                      value.isEmpty ? 'Select $label' : value,
                      style: TextStyle(
                        fontSize: 15,
                        color: value.isEmpty
                            ? AppColors.tertiaryLabel
                            : AppColors.label,
                      ),
                    ),
                  ),
                  const Icon(CupertinoIcons.chevron_down,
                      size: 14, color: AppColors.tertiaryLabel),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  void _showPicker(
    String title,
    List<String> options,
    String current,
    ValueChanged<String> onChanged,
    String Function(String)? display,
  ) {
    showCupertinoModalPopup(
      context: context,
      builder: (ctx) => CupertinoActionSheet(
        title: Text(title),
        actions: options.map((opt) {
          return CupertinoActionSheetAction(
            onPressed: () {
              onChanged(opt);
              Navigator.pop(ctx);
            },
            child: Text(display != null ? display(opt) : opt),
          );
        }).toList(),
        cancelButton: CupertinoActionSheetAction(
          isDefaultAction: true,
          onPressed: () => Navigator.pop(ctx),
          child: const Text('Cancel'),
        ),
      ),
    );
  }
}
