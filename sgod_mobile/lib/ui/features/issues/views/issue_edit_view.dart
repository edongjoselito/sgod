import 'package:flutter/cupertino.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../core/widgets/app_dialogs.dart';
import '../../../../data/models/issue_item.dart';
import '../../../core/di.dart';
import '../view_models/issues_view_model.dart';

/// Edit or add an issue.
///
/// If [item] is null → create mode. If provided → edit mode.
class IssueEditView extends StatefulWidget {
  const IssueEditView({
    super.key,
    this.item,
    this.onSaved,
  });

  final IssueItem? item;
  final VoidCallback? onSaved;

  @override
  State<IssueEditView> createState() => _IssueEditViewState();
}

class _IssueEditViewState extends State<IssueEditView> {
  late final TextEditingController _title;
  late final TextEditingController _description;

  String _priority = 'Normal';
  String _status = 'Open';
  bool _saving = false;

  @override
  void initState() {
    super.initState();
    final item = widget.item;
    _title = TextEditingController(text: item?.title ?? '');
    _description = TextEditingController(text: item?.description ?? '');
    _priority = item?.priority.isNotEmpty == true ? item!.priority : 'Normal';
    _status = item?.status.isNotEmpty == true ? item!.status : 'Open';
  }

  @override
  void dispose() {
    _title.dispose();
    _description.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final isEdit = widget.item != null;
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: CupertinoNavigationBar(
        middle: Text(isEdit ? 'Edit Issue' : 'Add Issue'),
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
              _sectionLabel('Issue Details'),
              _card([
                _textField('Title *', _title, placeholder: 'Enter issue title'),
                _divider(),
                _textField('Description *', _description,
                    placeholder: 'Describe the issue or concern', maxLines: 5),
              ]),
              const SizedBox(height: 16),
              _sectionLabel('Classification'),
              _card([
                _segmentedField('Priority', _priority, ['Low', 'Normal', 'High'],
                    (v) => setState(() => _priority = v)),
                _divider(),
                _segmentedField(
                    'Status',
                    _status,
                    ['Open', 'In Progress', 'Resolved', 'Closed'],
                    (v) => setState(() => _status = v)),
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
                    padding:
                        const EdgeInsets.symmetric(horizontal: 8, vertical: 6),
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
    if (_title.text.trim().isEmpty) {
      _alert('Title is required');
      return;
    }
    if (_description.text.trim().isEmpty) {
      _alert('Description is required');
      return;
    }

    setState(() => _saving = true);
    try {
      final vm = IssuesViewModel(DI.issues);
      final item = widget.item;
      bool ok;
      if (item != null) {
        ok = await vm.updateIssue(
          id: item.id,
          title: _title.text.trim(),
          description: _description.text.trim(),
          priority: _priority,
          status: _status,
        );
      } else {
        ok = await vm.addIssue(
          title: _title.text.trim(),
          description: _description.text.trim(),
          priority: _priority,
        );
      }

      if (!ok) {
        if (mounted) _alert(vm.error ?? 'Could not save the issue.');
        return;
      }

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
