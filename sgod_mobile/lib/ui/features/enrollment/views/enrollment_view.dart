import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../core/widgets/app_dialogs.dart';
import '../../../../data/models/user_profile.dart';
import '../../../core/di.dart';

/// Enrollment Details screen for school-role users.
///
/// Shows enrollment counts by grade level, gender, and school year.
/// Supports add, edit, and delete via `api/enrollment_*` endpoints.
class EnrollmentView extends StatefulWidget {
  const EnrollmentView({
    super.key,
    required this.profile,
    this.onMenuTap,
  });

  final UserProfile profile;
  final VoidCallback? onMenuTap;

  @override
  State<EnrollmentView> createState() => _EnrollmentViewState();
}

class _EnrollmentViewState extends State<EnrollmentView> {
  bool _loading = true;
  String? _error;
  List<Map<String, dynamic>> _records = [];
  String _selectedYear = '';

  static const _gradeLevels = [
    'Preschool', 'Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3',
    'Grade 4', 'Grade 5', 'Grade 6', 'Grade 7', 'Grade 8',
    'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12',
  ];

  static const _schoolYears = [
    '2026-2027', '2025-2026', '2024-2025', '2023-2024',
  ];

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
      final query = <String, dynamic>{};
      if (_selectedYear.isNotEmpty) query['school_year'] = _selectedYear;
      final data = await DI.api.get('api/enrollment_index', query: query);
      if (mounted) {
        setState(() {
          _records = data == null
              ? <Map<String, dynamic>>[]
              : (data as List)
                  .map((e) => e as Map<String, dynamic>)
                  .toList();
          _loading = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _error = '$e';
          _loading = false;
        });
      }
    }
  }

  int get _totalMale =>
      _records.fold(0, (sum, r) => sum + (int.tryParse('${r['male_count']}') ?? 0));
  int get _totalFemale =>
      _records.fold(0, (sum, r) => sum + (int.tryParse('${r['female_count']}') ?? 0));
  int get _total => _totalMale + _totalFemale;

  List<String> get _availableYears {
    final years = _records
        .map((r) => (r['school_year'] ?? '').toString())
        .where((s) => s.isNotEmpty)
        .toSet()
        .toList();
    years.sort((a, b) => b.compareTo(a));
    return years;
  }

  @override
  Widget build(BuildContext context) {
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: CupertinoNavigationBar(
        middle: const Text('Enrollment Details'),
        backgroundColor: AppColors.surface,
        border: const Border(
          bottom: BorderSide(color: AppColors.separator, width: 0.5),
        ),
        leading: widget.onMenuTap != null
            ? CupertinoButton(
                padding: EdgeInsets.zero,
                onPressed: widget.onMenuTap,
                child: const Icon(CupertinoIcons.line_horizontal_3, size: 22),
              )
            : null,
        trailing: CupertinoButton(
          padding: EdgeInsets.zero,
          onPressed: () => _showEditDialog(null),
          child: const Icon(CupertinoIcons.add, size: 26),
        ),
      ),
      child: SafeArea(
        top: false,
        child: _buildBody(),
      ),
    );
  }

  Widget _buildBody() {
    if (_loading) {
      return const Center(child: CupertinoActivityIndicator(radius: 16));
    }
    if (_error != null) {
      return _CenteredMessage(
        icon: CupertinoIcons.wifi_exclamationmark,
        title: 'Could not load',
        message: _error!,
        actionLabel: 'Retry',
        onAction: _load,
      );
    }
    return Column(
      children: [
        // ── Year filter ─────────────────────────────────────────────
        Padding(
          padding: const EdgeInsets.fromLTRB(16, 12, 16, 4),
          child: Row(
            children: [
              const Text(
                'School Year: ',
                style: TextStyle(
                  fontSize: 14,
                  fontWeight: FontWeight.w600,
                  color: AppColors.secondaryLabel,
                ),
              ),
              Expanded(
                child: CupertinoButton(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 6),
                  onPressed: () => _showYearPicker(),
                  child: Row(
                    children: [
                      Text(
                        _selectedYear.isEmpty ? 'All Years' : _selectedYear,
                        style: const TextStyle(
                          fontSize: 15,
                          color: AppColors.primary,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                      const SizedBox(width: 4),
                      const Icon(CupertinoIcons.chevron_down,
                          size: 14, color: AppColors.primary),
                    ],
                  ),
                ),
              ),
              CupertinoButton(
                padding: EdgeInsets.zero,
                onPressed: _load,
                child: const Icon(CupertinoIcons.refresh, size: 20),
              ),
            ],
          ),
        ),
        // ── Stats cards ─────────────────────────────────────────────
        if (_records.isNotEmpty) ...[
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16),
            child: Row(
              children: [
                _statCard('Total', _total, AppColors.primary),
                const SizedBox(width: 8),
                _statCard('Male', _totalMale, AppColors.info),
                const SizedBox(width: 8),
                _statCard('Female', _totalFemale, AppColors.warning),
              ],
            ),
          ),
          const SizedBox(height: 12),
        ],
        // ── List or empty ───────────────────────────────────────────
        if (_records.isEmpty)
          Expanded(
            child: _CenteredMessage(
              icon: PhosphorIconsRegular.chartBar,
              title: 'No enrollment records',
              message: 'Tap + to add enrollment counts for a grade level.',
            ),
          )
        else
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.fromLTRB(16, 0, 16, 20),
              itemCount: _records.length,
              itemBuilder: (context, i) {
                final r = _records[i];
                return _EnrollmentTile(
                  data: r,
                  onEdit: () => _showEditDialog(r),
                  onDelete: () => _confirmDelete(r),
                );
              },
            ),
          ),
      ],
    );
  }

  Widget _statCard(String label, int value, Color color) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 14),
        decoration: BoxDecoration(
          color: AppColors.surface,
          borderRadius: BorderRadius.circular(12),
        ),
        child: Column(
          children: [
            Text(
              '$value',
              style: TextStyle(
                fontSize: 24,
                fontWeight: FontWeight.w700,
                color: color,
              ),
            ),
            const SizedBox(height: 2),
            Text(
              label,
              style: const TextStyle(
                fontSize: 12,
                color: AppColors.secondaryLabel,
              ),
            ),
          ],
        ),
      ),
    );
  }

  void _showYearPicker() {
    final options = <String>{'All Years', ..._availableYears, ..._schoolYears}
        .toList();
    showCupertinoModalPopup(
      context: context,
      builder: (ctx) => CupertinoActionSheet(
        title: const Text('Filter by School Year'),
        actions: options.map((opt) {
          return CupertinoActionSheetAction(
            onPressed: () {
              setState(() => _selectedYear = opt == 'All Years' ? '' : opt);
              Navigator.pop(ctx);
              _load();
            },
            child: Text(opt),
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

  void _showEditDialog(Map<String, dynamic>? existing) {
    final isEdit = existing != null;
    final gradeCtrl = TextEditingController(
      text: isEdit ? (existing['grade_level'] ?? '') : '',
    );
    final yearCtrl = TextEditingController(
      text: isEdit ? (existing['school_year'] ?? '') : _schoolYears.first,
    );
    final maleCtrl = TextEditingController(
      text: isEdit ? '${existing['male_count'] ?? 0}' : '0',
    );
    final femaleCtrl = TextEditingController(
      text: isEdit ? '${existing['female_count'] ?? 0}' : '0',
    );
    String selectedGrade = gradeCtrl.text;
    String selectedYear = yearCtrl.text;

    showCupertinoModalPopup(
      context: context,
      builder: (ctx) => CupertinoActionSheet(
        title: Text(isEdit ? 'Edit Enrollment' : 'Add Enrollment'),
        message: const Text('Record enrollee counts by grade level and sex.'),
        actions: [
          CupertinoActionSheetAction(
            onPressed: () {},
            child: _EditForm(
              gradeLevels: _gradeLevels,
              schoolYears: _schoolYears,
              gradeCtrl: gradeCtrl,
              yearCtrl: yearCtrl,
              maleCtrl: maleCtrl,
              femaleCtrl: femaleCtrl,
              selectedGrade: selectedGrade,
              selectedYear: selectedYear,
              onGradeChanged: (v) => selectedGrade = v,
              onYearChanged: (v) => selectedYear = v,
            ),
          ),
          CupertinoActionSheetAction(
            isDefaultAction: true,
            onPressed: () async {
              Navigator.pop(ctx);
              await _save(
                id: isEdit ? int.tryParse('${existing['id']}') ?? 0 : 0,
                gradeLevel: selectedGrade,
                schoolYear: selectedYear,
                maleCount: int.tryParse(maleCtrl.text) ?? 0,
                femaleCount: int.tryParse(femaleCtrl.text) ?? 0,
              );
            },
            child: Text(isEdit ? 'Update' : 'Save'),
          ),
        ],
        cancelButton: CupertinoActionSheetAction(
          onPressed: () => Navigator.pop(ctx),
          child: const Text('Cancel'),
        ),
      ),
    );
  }

  Future<void> _save({
    required int id,
    required String gradeLevel,
    required String schoolYear,
    required int maleCount,
    required int femaleCount,
  }) async {
    if (gradeLevel.isEmpty || schoolYear.isEmpty) {
      AppDialogs.alert(context, 'Validation',
          'Grade level and school year are required.');
      return;
    }
    try {
      final body = <String, dynamic>{
        'school_year': schoolYear,
        'grade_level': gradeLevel,
        'male_count': maleCount,
        'female_count': femaleCount,
      };
      if (id > 0) body['id'] = id;
      final result = await DI.write(
        endpoint: 'enrollment_save',
        entity: 'enrollment',
        operation: id > 0 ? 'update' : 'create',
        payload: body,
      );
      if (result == null) {
        // Queued offline — inform the user
        if (mounted) {
          AppDialogs.alert(context, 'Queued',
              'You are offline. This change has been queued and will sync automatically when you reconnect.');
        }
      }
      _load();
    } catch (e) {
      if (mounted) AppDialogs.alert(context, 'Save Failed', '$e');
    }
  }

  Future<void> _confirmDelete(Map<String, dynamic> record) async {
    final ok = await AppDialogs.confirm(
      context,
      title: 'Delete Record',
      message:
          'Delete enrollment for ${record['grade_level']} (${record['school_year']})?',
    );
    if (ok != true) return;
    try {
      final result = await DI.write(
        endpoint: 'enrollment_delete',
        entity: 'enrollment',
        operation: 'delete',
        payload: {'id': '${record['id']}'},
      );
      if (result == null && mounted) {
        AppDialogs.alert(context, 'Queued',
            'You are offline. This deletion has been queued and will sync automatically when you reconnect.');
      }
      _load();
    } catch (e) {
      if (mounted) AppDialogs.alert(context, 'Delete Failed', '$e');
    }
  }
}

/// Inline edit form rendered inside the action sheet.
class _EditForm extends StatelessWidget {
  const _EditForm({
    required this.gradeLevels,
    required this.schoolYears,
    required this.gradeCtrl,
    required this.yearCtrl,
    required this.maleCtrl,
    required this.femaleCtrl,
    required this.selectedGrade,
    required this.selectedYear,
    required this.onGradeChanged,
    required this.onYearChanged,
  });

  final List<String> gradeLevels;
  final List<String> schoolYears;
  final TextEditingController gradeCtrl;
  final TextEditingController yearCtrl;
  final TextEditingController maleCtrl;
  final TextEditingController femaleCtrl;
  final String selectedGrade;
  final String selectedYear;
  final ValueChanged<String> onGradeChanged;
  final ValueChanged<String> onYearChanged;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      color: AppColors.background,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          _label('Grade Level'),
          _PickerButton(
            value: selectedGrade.isEmpty ? 'Select Grade' : selectedGrade,
            onTap: () => _showPicker(context, 'Grade Level', gradeLevels,
                onGradeChanged),
          ),
          const SizedBox(height: 12),
          _label('School Year'),
          _PickerButton(
            value: selectedYear.isEmpty ? 'Select Year' : selectedYear,
            onTap: () => _showPicker(context, 'School Year', schoolYears,
                onYearChanged),
          ),
          const SizedBox(height: 12),
          Row(
            children: [
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _label('Male'),
                    CupertinoTextField(
                      controller: maleCtrl,
                      keyboardType: TextInputType.number,
                      padding: const EdgeInsets.symmetric(
                          horizontal: 12, vertical: 12),
                      decoration: _inputDecoration(),
                      style: const TextStyle(color: AppColors.label),
                    ),
                  ],
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _label('Female'),
                    CupertinoTextField(
                      controller: femaleCtrl,
                      keyboardType: TextInputType.number,
                      padding: const EdgeInsets.symmetric(
                          horizontal: 12, vertical: 12),
                      decoration: _inputDecoration(),
                      style: const TextStyle(color: AppColors.label),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _label(String text) => Padding(
        padding: const EdgeInsets.only(bottom: 6),
        child: Text(text,
            style: const TextStyle(
              fontSize: 13,
              fontWeight: FontWeight.w600,
              color: AppColors.secondaryLabel,
            )),
      );

  BoxDecoration _inputDecoration() => BoxDecoration(
        color: AppColors.surface,
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: AppColors.separator, width: 0.5),
      );

  void _showPicker(BuildContext context, String title, List<String> options,
      ValueChanged<String> onChanged) {
    showCupertinoModalPopup(
      context: context,
      builder: (ctx) => CupertinoActionSheet(
        title: Text(title),
        actions: options
            .map((opt) => CupertinoActionSheetAction(
                  onPressed: () {
                    onChanged(opt);
                    Navigator.pop(ctx);
                  },
                  child: Text(opt),
                ))
            .toList(),
        cancelButton: CupertinoActionSheetAction(
          isDefaultAction: true,
          onPressed: () => Navigator.pop(ctx),
          child: const Text('Cancel'),
        ),
      ),
    );
  }
}

class _PickerButton extends StatelessWidget {
  const _PickerButton({required this.value, required this.onTap});
  final String value;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return CupertinoButton(
      onPressed: onTap,
      color: AppColors.surface,
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 14),
      borderRadius: BorderRadius.circular(8),
      child: Row(
        children: [
          Expanded(
            child: Text(value,
                style: const TextStyle(
                  fontSize: 15,
                  color: AppColors.label,
                )),
          ),
          const Icon(CupertinoIcons.chevron_down,
              size: 14, color: AppColors.tertiaryLabel),
        ],
      ),
    );
  }
}

/// A single enrollment record row.
class _EnrollmentTile extends StatelessWidget {
  const _EnrollmentTile({
    required this.data,
    required this.onEdit,
    required this.onDelete,
  });

  final Map<String, dynamic> data;
  final VoidCallback onEdit;
  final VoidCallback onDelete;

  @override
  Widget build(BuildContext context) {
    final gradeLevel = (data['grade_level'] ?? 'Unknown').toString();
    final schoolYear = (data['school_year'] ?? '').toString();
    final male = int.tryParse('${data['male_count']}') ?? 0;
    final female = int.tryParse('${data['female_count']}') ?? 0;
    final total = male + female;

    return Container(
      margin: const EdgeInsets.only(bottom: 8),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: AppColors.surface,
        borderRadius: BorderRadius.circular(12),
      ),
      child: Row(
        children: [
          Container(
            width: 44,
            height: 44,
            decoration: BoxDecoration(
              color: AppColors.success,
              borderRadius: BorderRadius.circular(10),
            ),
            child: Center(
              child: Text(
                '$total',
                style: const TextStyle(
                  color: CupertinoColors.white,
                  fontSize: 16,
                  fontWeight: FontWeight.w700,
                ),
              ),
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  gradeLevel,
                  style: const TextStyle(
                    fontSize: 15,
                    fontWeight: FontWeight.w600,
                    color: AppColors.label,
                  ),
                ),
                const SizedBox(height: 2),
                Text(
                  schoolYear,
                  style: const TextStyle(
                    fontSize: 13,
                    color: AppColors.secondaryLabel,
                  ),
                ),
                const SizedBox(height: 4),
                Row(
                  children: [
                    _genderTag('M', male, AppColors.info),
                    const SizedBox(width: 6),
                    _genderTag('F', female, AppColors.warning),
                  ],
                ),
              ],
            ),
          ),
          // ── Edit / Delete actions ──────────────────────────────────
          CupertinoButton(
            padding: EdgeInsets.zero,
            onPressed: onEdit,
            child: const Icon(CupertinoIcons.pencil,
                size: 18, color: AppColors.primary),
          ),
          CupertinoButton(
            padding: EdgeInsets.zero,
            onPressed: onDelete,
            child: const Icon(CupertinoIcons.trash,
                size: 18, color: AppColors.danger),
          ),
        ],
      ),
    );
  }

  Widget _genderTag(String label, int count, Color color) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
      decoration: BoxDecoration(
        color: color,
        borderRadius: BorderRadius.circular(6),
      ),
      child: Text(
        '$label: $count',
        style: const TextStyle(
          fontSize: 11,
          fontWeight: FontWeight.w600,
          color: CupertinoColors.white,
        ),
      ),
    );
  }
}

/// Reusable centered empty/error state.
class _CenteredMessage extends StatelessWidget {
  const _CenteredMessage({
    required this.icon,
    required this.title,
    required this.message,
    this.actionLabel,
    this.onAction,
  });

  final IconData icon;
  final String title;
  final String message;
  final String? actionLabel;
  final VoidCallback? onAction;

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 32),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, size: 48, color: AppColors.tertiaryLabel),
            const SizedBox(height: 16),
            Text(
              title,
              style: const TextStyle(
                fontSize: 17,
                fontWeight: FontWeight.w600,
                color: AppColors.label,
              ),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 4),
            Text(
              message,
              style: const TextStyle(
                fontSize: 13,
                color: AppColors.secondaryLabel,
              ),
              textAlign: TextAlign.center,
            ),
            if (actionLabel != null && onAction != null) ...[
              const SizedBox(height: 16),
              CupertinoButton(onPressed: onAction, child: Text(actionLabel!)),
            ],
          ],
        ),
      ),
    );
  }
}
