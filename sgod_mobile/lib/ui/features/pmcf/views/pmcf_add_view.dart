import 'package:flutter/cupertino.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../core/widgets/app_dialogs.dart';
import '../../../../data/models/user_profile.dart';
import '../../../core/di.dart';

/// PMCF add form.
///
/// Mirrors the web app's pmcf_add.php form: 17 user-facing fields with
/// cascading district → school dropdowns. On save, POSTs to
/// `api/pmcf_create`.
class PmcfAddView extends StatefulWidget {
  const PmcfAddView({super.key, required this.profile});

  final UserProfile profile;

  @override
  State<PmcfAddView> createState() => _PmcfAddViewState();
}

class _PmcfAddViewState extends State<PmcfAddView> {
  final _teacherObserved = TextEditingController();
  final _sectionCtrl = TextEditingController();
  final _timeObserved = TextEditingController();
  final _subjectArea = TextEditingController();
  final _supervisor = TextEditingController();
  final _designation = TextEditingController();
  final _incidents = TextEditingController();
  final _impact = TextEditingController();
  final _coachingOthers = TextEditingController();
  final _feedback = TextEditingController();
  final _progress = TextEditingController();

  String _gradeLevel = '';
  String _district = '';
  String _school = '';
  String _quarter = '';
  String _coachingMechanism = '';
  DateTime _dateObserved = DateTime.now();

  List<Map<String, dynamic>> _districts = [];
  List<Map<String, dynamic>> _schools = [];
  bool _loadingDistricts = true;
  bool _loadingSchools = false;
  bool _saving = false;

  static const _gradeLevels = [
    'Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4',
    'Grade 5', 'Grade 6', 'Grade 7', 'Grade 8', 'Grade 9',
    'Grade 10', 'Grade 11', 'Grade 12', 'SPED',
  ];
  static const _quarters = ['Term 1', 'Term 2', 'Term 3'];
  static const _coachingMechanisms = [
    'Meeting (Group)', 'One on One', 'Learning Action Cell Sessions',
    'Walk-through Observations', 'Workshop', 'Others',
  ];

  @override
  void initState() {
    super.initState();
    _loadDistricts();
  }

  @override
  void dispose() {
    _teacherObserved.dispose();
    _sectionCtrl.dispose();
    _timeObserved.dispose();
    _subjectArea.dispose();
    _supervisor.dispose();
    _designation.dispose();
    _incidents.dispose();
    _impact.dispose();
    _coachingOthers.dispose();
    _feedback.dispose();
    _progress.dispose();
    super.dispose();
  }

  Future<void> _loadDistricts() async {
    try {
      final data = await DI.api.get('api/pmcf_districts');
      if (mounted) {
        setState(() {
          _districts = data == null
              ? <Map<String, dynamic>>[]
              : (data as List)
                  .map((e) => e as Map<String, dynamic>)
                  .toList();
          _loadingDistricts = false;
        });
      }
    } catch (e) {
      if (mounted) setState(() => _loadingDistricts = false);
    }
  }

  Future<void> _loadSchools(String district) async {
    setState(() {
      _loadingSchools = true;
      _schools = [];
      _school = '';
    });
    if (district.isEmpty) return;
    try {
      final data = await DI.api.get(
        'api/pmcf_schools',
        query: {'district': district},
      );
      if (mounted) {
        setState(() {
          _schools = data == null
              ? <Map<String, dynamic>>[]
              : (data as List)
                  .map((e) => e as Map<String, dynamic>)
                  .toList();
          _loadingSchools = false;
        });
      }
    } catch (e) {
      if (mounted) setState(() => _loadingSchools = false);
    }
  }

  Future<void> _save() async {
    if (_teacherObserved.text.trim().isEmpty) {
      AppDialogs.alert(context, 'Validation', 'Teacher observed is required.');
      return;
    }

    setState(() => _saving = true);
    try {
      final body = <String, dynamic>{
        'teacher_observed': _teacherObserved.text.trim(),
        'grade_level': _gradeLevel,
        'section': _sectionCtrl.text.trim(),
        'district': _district,
        'school': _school,
        'quarter': _quarter,
        'date_observed':
            '${_dateObserved.year}-${_dateObserved.month.toString().padLeft(2, '0')}-${_dateObserved.day.toString().padLeft(2, '0')}',
        'time_observed': _timeObserved.text.trim(),
        'subject_area': _subjectArea.text.trim(),
        'instructional_supervisor': _supervisor.text.trim(),
        'designation': _designation.text.trim(),
        'significant_incidents_description': _incidents.text.trim(),
        'impact_on_job': _impact.text.trim(),
        'coaching_mechanisms': _coachingMechanism,
        'coaching_mechanisms_others': _coachingOthers.text.trim(),
        'feedback_recommendation': _feedback.text.trim(),
        'progress_to_date': _progress.text.trim(),
      };
      await DI.write(
        endpoint: 'pmcf_create',
        entity: 'pmcf',
        operation: 'create',
        payload: body,
      );
      if (mounted) {
        Navigator.pop(context);
      }
    } catch (e) {
      if (mounted) {
        AppDialogs.alert(context, 'Save Failed', '$e');
      }
    } finally {
      if (mounted) setState(() => _saving = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: CupertinoNavigationBar(
        middle: const Text('New PMCF Record'),
        backgroundColor: AppColors.surface,
        border: const Border(
          bottom: BorderSide(color: AppColors.separator, width: 0.5),
        ),
        trailing: CupertinoButton(
          padding: EdgeInsets.zero,
          onPressed: _saving ? null : _save,
          child: _saving
              ? const CupertinoActivityIndicator(radius: 12)
              : const Text(
                  'Save',
                  style: TextStyle(
                    fontWeight: FontWeight.w600,
                    color: AppColors.primary,
                  ),
                ),
        ),
      ),
      child: SafeArea(
        top: false,
        child: SingleChildScrollView(
          padding: const EdgeInsets.fromLTRB(16, 16, 16, 32),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // ── Basic Information ───────────────────────────────────────
              _section('Basic Information'),
              _textField('Teacher Observed *', _teacherObserved,
                  placeholder: 'Name of teacher'),
              _pickerField(
                'Grade Level',
                value: _gradeLevel,
                options: _gradeLevels,
                onChanged: (v) => setState(() => _gradeLevel = v),
              ),
              _textField('Section', _sectionCtrl, placeholder: 'Class section'),
              _pickerField(
                'District',
                value: _district,
                options: _districts
                    .map((d) => (d['district'] ?? '').toString())
                    .where((s) => s.isNotEmpty)
                    .toList(),
                loading: _loadingDistricts,
                onChanged: (v) {
                  setState(() => _district = v);
                  _loadSchools(v);
                },
              ),
              _pickerField(
                'School',
                value: _school,
                options: _schools
                    .map((s) => (s['schoolName'] ?? '').toString())
                    .where((s) => s.isNotEmpty)
                    .toList(),
                loading: _loadingSchools,
                enabled: _district.isNotEmpty,
                onChanged: (v) => setState(() => _school = v),
              ),
              _pickerField(
                'Term',
                value: _quarter,
                options: _quarters,
                onChanged: (v) => setState(() => _quarter = v),
              ),
              _dateField(),
              _textField('Time Observed', _timeObserved,
                  placeholder: 'e.g. 09:30 AM'),
              _textField('Subject Area', _subjectArea,
                  placeholder: 'e.g. Mathematics'),

              // ── Supervisor Information ──────────────────────────────────
              _section('Supervisor Information'),
              _textField('Instructional Supervisor', _supervisor,
                  placeholder: 'Name of supervisor'),
              _textField('Designation', _designation,
                  placeholder: 'e.g. Head Teacher III'),

              // ── Observation Details ─────────────────────────────────────
              _section('Observation Details'),
              _textArea('Significant Incidents Description', _incidents,
                  placeholder: 'Describe significant incidents observed...'),
              _textArea('Impact on Job', _impact,
                  placeholder: 'Describe the impact on the teacher\'s job...'),

              // ── Coaching & Progress ─────────────────────────────────────
              _section('Coaching & Progress'),
              _pickerField(
                'Coaching Mechanism',
                value: _coachingMechanism,
                options: _coachingMechanisms,
                onChanged: (v) => setState(() => _coachingMechanism = v),
              ),
              if (_coachingMechanism == 'Others')
                _textField('Others (please specify)', _coachingOthers,
                    placeholder: 'Specify other coaching mechanism'),
              _textArea('Feedback / Recommendation', _feedback,
                  placeholder: 'Provide feedback and recommendations...'),
              _textArea('Progress to Date', _progress,
                  placeholder: 'Describe progress to date...'),

              const SizedBox(height: 16),
              SizedBox(
                width: double.infinity,
                child: CupertinoButton(
                  onPressed: _saving ? null : _save,
                  color: AppColors.primary,
                  borderRadius: BorderRadius.circular(12),
                  child: _saving
                      ? const CupertinoActivityIndicator(
                          radius: 14, color: CupertinoColors.white)
                      : const Text(
                          'Save Record',
                          style: TextStyle(
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

  // ── Form helpers ──────────────────────────────────────────────────────────

  Widget _section(String title) {
    return Padding(
      padding: const EdgeInsets.only(top: 20, bottom: 8),
      child: Text(
        title,
        style: const TextStyle(
          fontSize: 15,
          fontWeight: FontWeight.w700,
          color: AppColors.label,
        ),
      ),
    );
  }

  Widget _textField(String label, TextEditingController controller,
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
            style: const TextStyle(color: AppColors.label, fontSize: 15),
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 12),
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
            maxLines: 5,
            style: const TextStyle(color: AppColors.label, fontSize: 15),
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 12),
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
    bool loading = false,
    bool enabled = true,
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
              onPressed: !enabled || loading
                  ? null
                  : () => _showPickerSheet(label, options, value, onChanged),
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 14),
              child: Row(
                children: [
                  Expanded(
                    child: Text(
                      loading
                          ? 'Loading...'
                          : (value.isEmpty ? 'Select $label' : value),
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

  void _showPickerSheet(
    String title,
    List<String> options,
    String current,
    ValueChanged<String> onChanged,
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

  Widget _dateField() {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text('Date Observed *',
              style: TextStyle(
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
              onPressed: () => _showDatePicker(),
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 14),
              child: Row(
                children: [
                  Expanded(
                    child: Text(
                      '${_dateObserved.year}-${_dateObserved.month.toString().padLeft(2, '0')}-${_dateObserved.day.toString().padLeft(2, '0')}',
                      style: const TextStyle(
                        fontSize: 15,
                        color: AppColors.label,
                      ),
                    ),
                  ),
                  const Icon(CupertinoIcons.calendar,
                      size: 16, color: AppColors.tertiaryLabel),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  void _showDatePicker() {
    showCupertinoModalPopup(
      context: context,
      builder: (ctx) => Container(
        height: 280,
        color: AppColors.surface,
        child: Column(
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                CupertinoButton(
                  child: const Text('Cancel'),
                  onPressed: () => Navigator.pop(ctx),
                ),
                CupertinoButton(
                  child: const Text('Done'),
                  onPressed: () => Navigator.pop(ctx),
                ),
              ],
            ),
            Expanded(
              child: CupertinoDatePicker(
                mode: CupertinoDatePickerMode.date,
                initialDateTime: _dateObserved,
                maximumDate: DateTime.now(),
                onDateTimeChanged: (dt) {
                  setState(() => _dateObserved = dt);
                },
              ),
            ),
          ],
        ),
      ),
    );
  }
}
