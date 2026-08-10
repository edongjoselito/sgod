import 'package:flutter/cupertino.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../core/widgets/app_dialogs.dart';
import '../../../../data/models/whereabouts_item.dart';
import '../../../core/di.dart';

/// Edit or add a whereabouts record.
///
/// If [item] is null → create mode. If provided → edit mode.
class WhereaboutsEditView extends StatefulWidget {
  const WhereaboutsEditView({
    super.key,
    this.item,
    this.onSaved,
  });

  final WhereaboutsItem? item;
  final VoidCallback? onSaved;

  @override
  State<WhereaboutsEditView> createState() => _WhereaboutsEditViewState();
}

class _WhereaboutsEditViewState extends State<WhereaboutsEditView> {
  late final TextEditingController _location;
  late final TextEditingController _activity;
  late final TextEditingController _notes;
  late final TextEditingController _date;

  String _status = 'In Office';
  bool _saving = false;

  static const _statusOptions = [
    'In Office',
    'On Field Work',
    'On Official Business',
    'Out of Office',
    'On Leave',
  ];

  @override
  void initState() {
    super.initState();
    final item = widget.item;
    _location = TextEditingController(text: item?.location ?? '');
    _activity = TextEditingController(text: item?.activity ?? '');
    _notes = TextEditingController(text: item?.notes ?? '');
    _date = TextEditingController(text: item?.date ?? '');
    // Use the item's status if it's one of our options, otherwise default
    final rawStatus = item?.status ?? 'In Office';
    _status = _statusOptions.contains(rawStatus) ? rawStatus : 'In Office';
  }

  @override
  void dispose() {
    _location.dispose();
    _activity.dispose();
    _notes.dispose();
    _date.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final isEdit = widget.item != null;
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: CupertinoNavigationBar(
        middle: Text(isEdit ? 'Edit Whereabouts' : 'Add Whereabouts'),
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
              _sectionLabel('Details'),
              _card([
                _dateField('Date *', _date),
                _divider(),
                _statusField('Status *'),
                _divider(),
                _textField('Location', _location, placeholder: 'Enter location'),
                _divider(),
                _textField('Activity *', _activity, placeholder: 'Enter activity'),
              ]),
              const SizedBox(height: 16),
              _sectionLabel('Additional'),
              _card([
                _textField('Notes', _notes, placeholder: 'Enter notes', maxLines: 4),
              ]),
            ],
          ),
        ),
      ),
    );
  }

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
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 12),
              decoration: BoxDecoration(
                color: AppColors.secondaryBackground,
                borderRadius: BorderRadius.circular(8),
              ),
              child: Row(
                children: [
                  const Icon(CupertinoIcons.calendar,
                      size: 16, color: AppColors.tertiaryLabel),
                  const SizedBox(width: 8),
                  Text(
                    controller.text.isEmpty ? 'Select date' : controller.text,
                    style: TextStyle(
                      fontSize: 15,
                      color: controller.text.isEmpty
                          ? AppColors.tertiaryLabel
                          : AppColors.label,
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

  void _pickDate(TextEditingController controller) async {
    DateTime initial = DateTime.now();
    if (controller.text.isNotEmpty) {
      final parsed = DateTime.tryParse(controller.text);
      if (parsed != null) initial = parsed;
    }
    final picked = await AppDialogs.pickDate(context, initialDate: initial);
    if (picked != null) {
      controller.text = picked.toIso8601String().split('T')[0];
      setState(() {});
    }
  }

  Widget _statusField(String label) {
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
            onPressed: _pickStatus,
            child: Container(
              width: double.infinity,
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 12),
              decoration: BoxDecoration(
                color: AppColors.secondaryBackground,
                borderRadius: BorderRadius.circular(8),
              ),
              child: Row(
                children: [
                  Expanded(
                    child: Text(
                      _status,
                      style: const TextStyle(
                        fontSize: 15,
                        color: AppColors.label,
                      ),
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

  void _pickStatus() async {
    final selected = await AppDialogs.showOptions<String>(
      context,
      title: 'Select Status',
      options: _statusOptions.map((s) => (s, s)).toList(),
      cancelText: 'Cancel',
    );
    if (selected != null) {
      setState(() => _status = selected);
    }
  }

  // ── Save ──────────────────────────────────────────────────────────────────
  Future<void> _save() async {
    if (_date.text.trim().isEmpty) {
      _alert('Date is required');
      return;
    }
    if (_activity.text.trim().isEmpty) {
      _alert('Activity is required');
      return;
    }

    setState(() => _saving = true);
    try {
      final fields = <String, dynamic>{
        'date': _date.text.trim(),
        'status': _status,
        'location': _location.text.trim(),
        'activity': _activity.text.trim(),
        'notes': _notes.text.trim(),
      };

      await DI.whereabouts.save(fields, id: widget.item?.id ?? '');
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
