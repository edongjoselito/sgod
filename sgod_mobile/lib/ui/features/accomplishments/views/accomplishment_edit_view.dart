import 'package:flutter/cupertino.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../data/models/accomplishment_item.dart';
import '../../../../data/repositories/accomplishments_repository.dart';
import '../../../core/di.dart';

/// Edit or add an accomplishment.
///
/// If [item] is null → create mode. If provided → edit mode.
class AccomplishmentEditView extends StatefulWidget {
  const AccomplishmentEditView({
    super.key,
    this.item,
    this.onSaved,
  });

  final AccomplishmentItem? item;
  final VoidCallback? onSaved;

  @override
  State<AccomplishmentEditView> createState() => _AccomplishmentEditViewState();
}

class _AccomplishmentEditViewState extends State<AccomplishmentEditView> {
  late final TextEditingController _activity;
  late final TextEditingController _particulars;
  late final TextEditingController _venue;
  late final TextEditingController _resources;
  late final TextEditingController _notes;
  late final TextEditingController _remarks;
  late final TextEditingController _target;
  late final TextEditingController _achieved;
  late final TextEditingController _percentage;
  late final TextEditingController _dateFrom;
  late final TextEditingController _dateTo;

  String _category = 'Accomplishment';
  String _scope = 'section';
  bool _saving = false;

  @override
  void initState() {
    super.initState();
    final item = widget.item;
    _activity = TextEditingController(text: item?.activity ?? '');
    _particulars = TextEditingController(text: item?.particulars ?? '');
    _venue = TextEditingController(text: item?.venue ?? '');
    _resources = TextEditingController(text: item?.resources ?? '');
    _notes = TextEditingController(text: item?.notes ?? '');
    _remarks = TextEditingController(text: item?.remarks ?? '');
    _target = TextEditingController(text: item?.target ?? '');
    _achieved = TextEditingController(text: item?.achieved ?? '');
    _percentage = TextEditingController(text: item?.percentageAccom ?? '');
    _dateFrom = TextEditingController(text: item?.targetDate ?? '');
    _dateTo = TextEditingController(text: '');
    _category = item?.activityCategory ?? 'Accomplishment';
    _scope = item?.accomplishmentScope ?? 'section';

    // If editing and dateConducted has a range, split it
    if (item != null && item.dateConducted.contains(' to ')) {
      final parts = item.dateConducted.split(' to ');
      _dateFrom.text = _parseDate(parts[0].trim());
      _dateTo.text = _parseDate(parts[1].trim());
    } else if (item != null && item.dateConducted.isNotEmpty) {
      _dateFrom.text = item.targetDate;
    }
  }

  String _parseDate(String displayDate) {
    // Try to parse "July 12, 2026" → "2026-07-12"
    try {
      final dt = DateTime.tryParse(displayDate);
      if (dt != null) return dt.toIso8601String().split('T')[0];
    } catch (_) {}
    return displayDate;
  }

  @override
  void dispose() {
    _activity.dispose();
    _particulars.dispose();
    _venue.dispose();
    _resources.dispose();
    _notes.dispose();
    _remarks.dispose();
    _target.dispose();
    _achieved.dispose();
    _percentage.dispose();
    _dateFrom.dispose();
    _dateTo.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final isEdit = widget.item != null;
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: CupertinoNavigationBar(
        middle: Text(isEdit ? 'Edit Accomplishment' : 'Add Accomplishment'),
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
              _sectionLabel('Activity Details'),
              _card([
                _textField('Activity *', _activity, placeholder: 'Enter activity title'),
                _divider(),
                _textField('Particulars', _particulars, placeholder: 'Enter particulars'),
                _divider(),
                _segmentedField('Category', _category, ['Accomplishment', 'Activity', 'Meeting', 'Report'], (v) => setState(() => _category = v)),
                _divider(),
                _textField('Venue', _venue, placeholder: 'Enter venue'),
              ]),
              const SizedBox(height: 16),
              _sectionLabel('Schedule'),
              _card([
                _dateField('Date From *', _dateFrom),
                _divider(),
                _dateField('Date To *', _dateTo),
              ]),
              const SizedBox(height: 16),
              _sectionLabel('Performance'),
              _card([
                _textField('Target', _target, placeholder: 'e.g. 100', keyboardType: const TextInputType.numberWithOptions(decimal: true)),
                _divider(),
                _textField('Achieved', _achieved, placeholder: 'e.g. 85', keyboardType: const TextInputType.numberWithOptions(decimal: true)),
                _divider(),
                _textField('Percentage (%)', _percentage, placeholder: 'e.g. 85', keyboardType: const TextInputType.numberWithOptions(decimal: true)),
              ]),
              const SizedBox(height: 16),
              _sectionLabel('Additional'),
              _card([
                _textField('Resources', _resources, placeholder: 'Enter resources/links'),
                _divider(),
                _textField('Notes', _notes, placeholder: 'Enter notes', maxLines: 3),
                _divider(),
                _textField('Remarks', _remarks, placeholder: 'Enter remarks', maxLines: 2),
                _divider(),
                _segmentedField('Scope', _scope, ['section', 'division', 'school'], (v) => setState(() => _scope = v)),
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
                  const Icon(CupertinoIcons.calendar, size: 16, color: AppColors.tertiaryLabel),
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

  void _pickDate(TextEditingController controller) {
    DateTime initial = DateTime.now();
    if (controller.text.isNotEmpty) {
      final parsed = DateTime.tryParse(controller.text);
      if (parsed != null) initial = parsed;
    }
    showCupertinoModalPopup(
      context: context,
      builder: (ctx) => Container(
        height: 280,
        color: AppColors.surface,
        child: CupertinoDatePicker(
          mode: CupertinoDatePickerMode.date,
          initialDateTime: initial,
          onDateTimeChanged: (dt) {
            controller.text = dt.toIso8601String().split('T')[0];
          },
        ),
      ),
    );
  }

  Widget _segmentedField(
    String label,
    String value,
    List<String> options,
    ValueChanged<String> onChanged,
  ) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Padding(
            padding: const EdgeInsets.only(bottom: 6),
            child: Text(
              label,
              style: const TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w600,
                color: AppColors.secondaryLabel,
              ),
            ),
          ),
          SizedBox(
            width: double.infinity,
            child: CupertinoSlidingSegmentedControl<String>(
              groupValue: value,
              thumbColor: AppColors.surface,
              children: {
                for (final o in options)
                  o: Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 6),
                    child: Text(
                      o[0].toUpperCase() + o.substring(1),
                      style: const TextStyle(fontSize: 12),
                    ),
                  ),
              },
              onValueChanged: (v) => onChanged(v ?? value),
            ),
          ),
        ],
      ),
    );
  }

  // ── Save ──────────────────────────────────────────────────────────────────
  Future<void> _save() async {
    if (_activity.text.trim().isEmpty) {
      _alert('Activity is required');
      return;
    }
    if (_dateFrom.text.trim().isEmpty || _dateTo.text.trim().isEmpty) {
      _alert('Both dates are required');
      return;
    }

    setState(() => _saving = true);
    try {
      final fields = <String, dynamic>{
        'activity': _activity.text.trim(),
        'particulars': _particulars.text.trim(),
        'activityCategory': _category,
        'venue': _venue.text.trim(),
        'activityDateFrom': _dateFrom.text.trim(),
        'activityDateTo': _dateTo.text.trim(),
        'resources': _resources.text.trim(),
        'notes': _notes.text.trim(),
        'remarks': _remarks.text.trim(),
        'target': _target.text.trim(),
        'achieved': _achieved.text.trim(),
        'percentageAccom': _percentage.text.trim(),
        'accomplishmentScope': _scope,
      };

      await DI.accomplishments.save(fields, id: widget.item?.id ?? '');
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
    showCupertinoDialog(
      context: context,
      builder: (c) => CupertinoAlertDialog(
        title: const Text('Notice'),
        content: Text(message),
        actions: [
          CupertinoDialogAction(
            isDefaultAction: true,
            onPressed: () => Navigator.pop(c),
            child: const Text('OK'),
          ),
        ],
      ),
    );
  }
}
