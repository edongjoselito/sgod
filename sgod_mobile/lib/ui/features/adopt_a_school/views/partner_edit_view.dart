import 'package:flutter/cupertino.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../core/widgets/app_dialogs.dart';
import '../../../../data/models/partner_item.dart';
import '../../../core/di.dart';

/// Edit or add a partner.
///
/// If [item] is null → create mode. If provided → edit mode.
class PartnerEditView extends StatefulWidget {
  const PartnerEditView({
    super.key,
    this.item,
    this.onSaved,
  });

  final PartnerItem? item;
  final VoidCallback? onSaved;

  @override
  State<PartnerEditView> createState() => _PartnerEditViewState();
}

class _PartnerEditViewState extends State<PartnerEditView> {
  late final TextEditingController _name;
  late final TextEditingController _address;
  late final TextEditingController _contactPerson;
  late final TextEditingController _contact;
  late final TextEditingController _specificType;

  String _generalType = 'Private_Sector';
  bool _saving = false;

  static const _generalTypes = [
    'Private_Sector',
    'Public_Sector',
    'Civil_Society',
    'International',
    'Others',
  ];

  @override
  void initState() {
    super.initState();
    final item = widget.item;
    _name = TextEditingController(text: item?.name ?? '');
    _address = TextEditingController(text: item?.address ?? '');
    _contactPerson = TextEditingController(text: item?.contactPerson ?? '');
    _contact = TextEditingController(text: item?.contact ?? '');
    _specificType = TextEditingController(text: item?.specificType ?? '');
    _generalType = item?.generalType.isNotEmpty == true
        ? item!.generalType
        : 'Private_Sector';
  }

  @override
  void dispose() {
    _name.dispose();
    _address.dispose();
    _contactPerson.dispose();
    _contact.dispose();
    _specificType.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final isEdit = widget.item != null;
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: CupertinoNavigationBar(
        middle: Text(isEdit ? 'Edit Partner' : 'Add Partner'),
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
              _sectionLabel('Partner Details'),
              _card([
                _textField('Name *', _name, placeholder: 'Enter partner name'),
                _divider(),
                _textField('Address', _address,
                    placeholder: 'Enter address', maxLines: 3),
                _divider(),
                _textField('Contact Person', _contactPerson,
                    placeholder: 'Enter contact person'),
                _divider(),
                _textField('Contact', _contact,
                    placeholder: 'Enter contact number/email'),
              ]),
              const SizedBox(height: 16),
              _sectionLabel('Classification'),
              _card([
                _pickerField('General Type', _generalType.replaceAll('_', ' '),
                    () => _showGeneralTypePicker(context)),
                _divider(),
                _textField('Specific Type', _specificType,
                    placeholder: 'Enter specific type'),
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

  Widget _pickerField(String label, String value, VoidCallback onTap) {
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
                  Text(
                    value,
                    style: const TextStyle(
                        fontSize: 15, color: AppColors.label),
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

  void _showGeneralTypePicker(BuildContext context) {
    AppDialogs.showOptions<String>(
      context,
      title: 'Select General Type',
      options: [
        for (final type in _generalTypes) (type.replaceAll('_', ' '), type),
      ],
      cancelText: 'Cancel',
    ).then((selected) {
      if (selected != null) {
        setState(() => _generalType = selected);
      }
    });
  }

  // ── Save ──────────────────────────────────────────────────────────────────
  Future<void> _save() async {
    if (_name.text.trim().isEmpty) {
      _alert('Name is required');
      return;
    }

    setState(() => _saving = true);
    try {
      final fields = <String, dynamic>{
        'name': _name.text.trim(),
        'address': _address.text.trim(),
        'contactPerson': _contactPerson.text.trim(),
        'contact': _contact.text.trim(),
        'generalType': _generalType,
        'specificType': _specificType.text.trim(),
      };

      await DI.adoptASchool.savePartner(fields, id: widget.item?.id ?? '');
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
