import 'package:flutter/cupertino.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../core/widgets/app_dialogs.dart';
import '../../../../data/models/school_item.dart';
import '../../../core/di.dart';

/// Edit a school record (edit mode only — no create).
class SchoolEditView extends StatefulWidget {
  const SchoolEditView({
    super.key,
    required this.item,
    this.onSaved,
  });

  final SchoolItem item;
  final VoidCallback? onSaved;

  @override
  State<SchoolEditView> createState() => _SchoolEditViewState();
}

class _SchoolEditViewState extends State<SchoolEditView> {
  late final TextEditingController _schoolName;
  late final TextEditingController _district;
  late final TextEditingController _division;
  late final TextEditingController _schoolType;
  late final TextEditingController _course;

  bool _saving = false;

  @override
  void initState() {
    super.initState();
    final item = widget.item;
    _schoolName = TextEditingController(text: item.schoolName);
    _district = TextEditingController(text: item.district);
    _division = TextEditingController(text: item.division);
    _schoolType = TextEditingController(text: item.schoolType);
    _course = TextEditingController(text: item.course);
  }

  @override
  void dispose() {
    _schoolName.dispose();
    _district.dispose();
    _division.dispose();
    _schoolType.dispose();
    _course.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: CupertinoNavigationBar(
        middle: const Text('Edit School'),
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
              _sectionLabel('School Information'),
              _card([
                _textField('School Name *', _schoolName,
                    placeholder: 'Enter school name'),
                _divider(),
                _textField('District', _district,
                    placeholder: 'Enter district'),
                _divider(),
                _textField('Division', _division,
                    placeholder: 'Enter division'),
                _divider(),
                _textField('School Type', _schoolType,
                    placeholder: 'Enter school type'),
                _divider(),
                _textField('Course', _course, placeholder: 'Enter course'),
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

  // ── Save ──────────────────────────────────────────────────────────────────
  Future<void> _save() async {
    if (_schoolName.text.trim().isEmpty) {
      _alert('School Name is required');
      return;
    }

    setState(() => _saving = true);
    try {
      final fields = <String, dynamic>{
        'schoolName': _schoolName.text.trim(),
        'district': _district.text.trim(),
        'division': _division.text.trim(),
        'schoolType': _schoolType.text.trim(),
        'course': _course.text.trim(),
      };

      await DI.schools.save(fields, recID: widget.item.recID);
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
