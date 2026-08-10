import 'package:flutter/cupertino.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../core/widgets/app_dialogs.dart';
import '../../../../data/models/activity_design_item.dart';
import '../../../core/di.dart';

/// Edit or add an activity design.
///
/// If [item] is null → create mode. If provided → edit mode.
class ActivityDesignEditView extends StatefulWidget {
  const ActivityDesignEditView({
    super.key,
    this.item,
    this.onSaved,
  });

  final ActivityDesignItem? item;
  final VoidCallback? onSaved;

  @override
  State<ActivityDesignEditView> createState() => _ActivityDesignEditViewState();
}

class _ActivityDesignEditViewState extends State<ActivityDesignEditView> {
  late final TextEditingController _title;
  late final TextEditingController _activityDate;
  late final TextEditingController _venue;
  late final TextEditingController _rationale;
  late final TextEditingController _objectives;
  late final TextEditingController _fundSource;

  bool _saving = false;

  @override
  void initState() {
    super.initState();
    final item = widget.item;
    _title = TextEditingController(text: item?.title ?? '');
    _activityDate = TextEditingController(text: item?.activityDate ?? '');
    _venue = TextEditingController(text: item?.venue ?? '');
    _rationale = TextEditingController(text: item?.rationale ?? '');
    _objectives = TextEditingController(text: item?.objectives ?? '');
    _fundSource = TextEditingController(text: item?.fundSource ?? '');
  }

  @override
  void dispose() {
    _title.dispose();
    _activityDate.dispose();
    _venue.dispose();
    _rationale.dispose();
    _objectives.dispose();
    _fundSource.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final isEdit = widget.item != null;
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: CupertinoNavigationBar(
        middle: Text(isEdit ? 'Edit Activity Design' : 'Add Activity Design'),
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
                _textField('Title *', _title,
                    placeholder: 'Enter activity title'),
                _divider(),
                _dateField('Activity Date', _activityDate),
                _divider(),
                _textField('Venue', _venue, placeholder: 'Enter venue'),
              ]),
              const SizedBox(height: 16),
              _sectionLabel('Additional'),
              _card([
                _textField('Rationale', _rationale,
                    placeholder: 'Enter rationale', maxLines: 3),
                _divider(),
                _textField('Objectives', _objectives,
                    placeholder: 'Enter objectives', maxLines: 3),
                _divider(),
                _textField('Fund Source', _fundSource,
                    placeholder: 'Enter fund source'),
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

  Future<void> _pickDate(TextEditingController controller) async {
    DateTime initial = DateTime.now();
    if (controller.text.isNotEmpty) {
      final parsed = DateTime.tryParse(controller.text);
      if (parsed != null) initial = parsed;
    }
    final picked = await AppDialogs.pickDate(context, initialDate: initial);
    if (picked != null) {
      controller.text = picked.toIso8601String().split('T')[0];
    }
  }

  // ── Save ──────────────────────────────────────────────────────────────────
  Future<void> _save() async {
    if (_title.text.trim().isEmpty) {
      _alert('Title is required');
      return;
    }

    setState(() => _saving = true);
    try {
      final fields = <String, dynamic>{
        'title': _title.text.trim(),
        'activityDate': _activityDate.text.trim(),
        'venue': _venue.text.trim(),
        'rationale': _rationale.text.trim(),
        'objectives': _objectives.text.trim(),
        'fundSource': _fundSource.text.trim(),
      };

      await DI.activityDesigns.save(fields, id: widget.item?.id ?? '');
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
