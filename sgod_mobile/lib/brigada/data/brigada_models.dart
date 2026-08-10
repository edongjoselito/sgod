/// Plain data models for the Brigada Eskwela module.
///
/// Hand-written `fromJson` rather than freezed/json_serializable so the module
/// carries no build_runner dependency of its own.
library;

// ── Shared parsing helpers ───────────────────────────────────────────────────

int _int(dynamic v) {
  if (v is int) return v;
  if (v is num) return v.toInt();
  return int.tryParse('${v ?? ''}') ?? 0;
}

int? _intOrNull(dynamic v) {
  if (v == null) return null;
  if (v is int) return v;
  if (v is num) return v.toInt();
  final parsed = int.tryParse('$v');
  return parsed;
}

double _double(dynamic v) {
  if (v is double) return v;
  if (v is num) return v.toDouble();
  return double.tryParse('${v ?? ''}') ?? 0;
}

String _str(dynamic v) => v == null ? '' : '$v';

bool _bool(dynamic v) => v == true || v == 1 || v == '1';

List<Map<String, dynamic>> _list(dynamic v) {
  if (v is! List) return const [];
  return v.whereType<Map>().map((e) => e.cast<String, dynamic>()).toList();
}

// ── Module metadata ──────────────────────────────────────────────────────────

/// Available school years for the preparedness screens.
class BrigadaMeta {
  const BrigadaMeta({
    required this.currentSy,
    required this.schoolYears,
    required this.section,
  });

  final String currentSy;
  final List<String> schoolYears;
  final String section;

  factory BrigadaMeta.fromJson(Map<String, dynamic> json) => BrigadaMeta(
        currentSy: _str(json['current_sy']),
        schoolYears:
            (json['school_years'] as List?)?.map((e) => '$e').toList() ??
                const [],
        section: _str(json['section']),
      );

  static BrigadaMeta fallback() {
    final year = DateTime.now().year;
    final sy = '$year-${year + 1}';
    return BrigadaMeta(currentSy: sy, schoolYears: [sy], section: '');
  }
}

// ── 1. School Preparedness ───────────────────────────────────────────────────

/// A district row on the School Preparedness screen.
class SpcDistrict {
  const SpcDistrict({
    required this.id,
    required this.name,
    required this.schoolCount,
    required this.submittedCount,
  });

  final int id;
  final String name;
  final int schoolCount;
  final int submittedCount;

  /// Submission rate 0..1; 0 when the district has no schools.
  double get progress =>
      schoolCount == 0 ? 0 : (submittedCount / schoolCount).clamp(0, 1);

  int get pendingCount =>
      schoolCount - submittedCount < 0 ? 0 : schoolCount - submittedCount;

  factory SpcDistrict.fromJson(Map<String, dynamic> json) => SpcDistrict(
        id: _int(json['id']),
        name: _str(json['name']),
        schoolCount: _int(json['school_count']),
        submittedCount: _int(json['submitted_count']),
      );
}

/// Response of `spc_districts`.
class SpcDistrictList {
  const SpcDistrictList({required this.sy, required this.districts});

  final String sy;
  final List<SpcDistrict> districts;

  int get totalSchools =>
      districts.fold(0, (sum, d) => sum + d.schoolCount);
  int get totalSubmitted =>
      districts.fold(0, (sum, d) => sum + d.submittedCount);
  double get progress =>
      totalSchools == 0 ? 0 : (totalSubmitted / totalSchools).clamp(0, 1);

  factory SpcDistrictList.fromJson(Map<String, dynamic> json) =>
      SpcDistrictList(
        sy: _str(json['sy']),
        districts:
            _list(json['districts']).map(SpcDistrict.fromJson).toList(),
      );
}

/// A school row inside a district.
class SpcSchool {
  const SpcSchool({
    required this.schoolId,
    required this.schoolName,
    required this.schoolType,
    required this.district,
    required this.submitted,
  });

  final String schoolId;
  final String schoolName;
  final String schoolType;
  final String district;
  final bool submitted;

  factory SpcSchool.fromJson(Map<String, dynamic> json) => SpcSchool(
        schoolId: _str(json['school_id']),
        schoolName: _str(json['school_name']),
        schoolType: _str(json['school_type']),
        district: _str(json['district']),
        submitted: _bool(json['submitted']),
      );
}

/// Response of `spc_district_schools`.
class SpcSchoolList {
  const SpcSchoolList({
    required this.sy,
    required this.districtId,
    required this.districtName,
    required this.schools,
  });

  final String sy;
  final int districtId;
  final String districtName;
  final List<SpcSchool> schools;

  int get submittedCount => schools.where((s) => s.submitted).length;

  factory SpcSchoolList.fromJson(Map<String, dynamic> json) => SpcSchoolList(
        sy: _str(json['sy']),
        districtId: _int(json['district_id']),
        districtName: _str(json['district_name']),
        schools: _list(json['schools']).map(SpcSchool.fromJson).toList(),
      );
}

/// One checklist question and the school's answer.
class SpcChecklistItem {
  const SpcChecklistItem({
    required this.id,
    required this.ordinal,
    required this.description,
    required this.value,
    required this.label,
    required this.remark,
  });

  final int id;
  final int ordinal;
  final String description;

  /// 1 = Fully, 2 = Partially, 3 = Not prepared, null = unanswered.
  final int? value;
  final String? label;
  final String remark;

  factory SpcChecklistItem.fromJson(Map<String, dynamic> json) =>
      SpcChecklistItem(
        id: _int(json['id']),
        ordinal: _int(json['ordinal']),
        description: _str(json['description']),
        value: _intOrNull(json['value']),
        label: json['label'] == null ? null : _str(json['label']),
        remark: _str(json['remark']),
      );
}

/// A checklist category and its items.
class SpcChecklistCategory {
  const SpcChecklistCategory({
    required this.id,
    required this.name,
    required this.items,
  });

  final int id;
  final String name;
  final List<SpcChecklistItem> items;

  int get answered => items.where((i) => i.value != null).length;

  factory SpcChecklistCategory.fromJson(Map<String, dynamic> json) =>
      SpcChecklistCategory(
        id: _int(json['id']),
        name: _str(json['name']),
        items: _list(json['items']).map(SpcChecklistItem.fromJson).toList(),
      );
}

/// Response of `spc_school_checklist` (and each element of `spc_checklists`).
class SpcChecklist {
  const SpcChecklist({
    required this.sy,
    required this.schoolId,
    required this.schoolName,
    required this.district,
    required this.submitted,
    required this.itemCount,
    required this.answered,
    required this.fully,
    required this.partially,
    required this.notPrepared,
    required this.categories,
  });

  final String sy;
  final String schoolId;
  final String schoolName;
  final String district;
  final bool submitted;
  final int itemCount;
  final int answered;
  final int fully;
  final int partially;
  final int notPrepared;
  final List<SpcChecklistCategory> categories;

  /// Share of answered items rated Fully Prepared, 0..1.
  double get readiness => answered == 0 ? 0 : (fully / answered).clamp(0, 1);

  factory SpcChecklist.fromJson(Map<String, dynamic> json) => SpcChecklist(
        sy: _str(json['sy']),
        schoolId: _str(json['school_id']),
        schoolName: _str(json['school_name']),
        district: _str(json['district']),
        submitted: _bool(json['submitted']),
        itemCount: _int(json['item_count']),
        answered: _int(json['answered']),
        fully: _int(json['fully']),
        partially: _int(json['partially']),
        notPrepared: _int(json['not_prepared']),
        categories: _list(json['categories'])
            .map(SpcChecklistCategory.fromJson)
            .toList(),
      );
}

// ── 2. SPC Report ────────────────────────────────────────────────────────────

/// One checklist item's division-wide tally.
class SpcReportItem {
  const SpcReportItem({
    required this.id,
    required this.ordinal,
    required this.description,
    required this.fully,
    required this.partially,
    required this.notPrepared,
    required this.responses,
  });

  final int id;
  final int ordinal;
  final String description;
  final int fully;
  final int partially;
  final int notPrepared;
  final int responses;

  factory SpcReportItem.fromJson(Map<String, dynamic> json) => SpcReportItem(
        id: _int(json['id']),
        ordinal: _int(json['ordinal']),
        description: _str(json['description']),
        fully: _int(json['fully']),
        partially: _int(json['partially']),
        notPrepared: _int(json['not_prepared']),
        responses: _int(json['responses']),
      );
}

/// A report category with its items and roll-up counts.
class SpcReportCategory {
  const SpcReportCategory({
    required this.id,
    required this.name,
    required this.items,
    required this.fully,
    required this.partially,
    required this.notPrepared,
  });

  final int id;
  final String name;
  final List<SpcReportItem> items;
  final int fully;
  final int partially;
  final int notPrepared;

  int get total => fully + partially + notPrepared;

  factory SpcReportCategory.fromJson(Map<String, dynamic> json) =>
      SpcReportCategory(
        id: _int(json['id']),
        name: _str(json['name']),
        items: _list(json['items']).map(SpcReportItem.fromJson).toList(),
        fully: _int(json['fully']),
        partially: _int(json['partially']),
        notPrepared: _int(json['not_prepared']),
      );
}

/// Response of `spc_report`.
class SpcReport {
  const SpcReport({
    required this.sy,
    required this.submissionCount,
    required this.categories,
    required this.fully,
    required this.partially,
    required this.notPrepared,
  });

  final String sy;
  final int submissionCount;
  final List<SpcReportCategory> categories;
  final int fully;
  final int partially;
  final int notPrepared;

  int get total => fully + partially + notPrepared;

  factory SpcReport.fromJson(Map<String, dynamic> json) {
    final totals = (json['totals'] as Map?)?.cast<String, dynamic>() ?? const {};
    return SpcReport(
      sy: _str(json['sy']),
      submissionCount: _int(json['submission_count']),
      categories:
          _list(json['categories']).map(SpcReportCategory.fromJson).toList(),
      fully: _int(totals['fully']),
      partially: _int(totals['partially']),
      notPrepared: _int(totals['not_prepared']),
    );
  }
}

/// A school behind one SPC report cell.
class SpcResponseSchool {
  const SpcResponseSchool({
    required this.schoolId,
    required this.schoolName,
    required this.remark,
  });

  final String schoolId;
  final String schoolName;
  final String remark;

  factory SpcResponseSchool.fromJson(Map<String, dynamic> json) =>
      SpcResponseSchool(
        schoolId: _str(json['school_id']),
        schoolName: _str(json['school_name']),
        remark: _str(json['remark']),
      );
}

/// Response of `spc_report_responses`.
class SpcResponseList {
  const SpcResponseList({
    required this.sy,
    required this.itemId,
    required this.value,
    required this.ratingLabel,
    required this.itemDescription,
    required this.categoryName,
    required this.schools,
  });

  final String sy;
  final int itemId;
  final int value;
  final String ratingLabel;
  final String itemDescription;
  final String categoryName;
  final List<SpcResponseSchool> schools;

  factory SpcResponseList.fromJson(Map<String, dynamic> json) =>
      SpcResponseList(
        sy: _str(json['sy']),
        itemId: _int(json['item_id']),
        value: _int(json['value']),
        ratingLabel: _str(json['rating_label']),
        itemDescription: _str(json['item_description']),
        categoryName: _str(json['category_name']),
        schools:
            _list(json['schools']).map(SpcResponseSchool.fromJson).toList(),
      );
}

// ── 3. Summary Report ────────────────────────────────────────────────────────

/// A month that actually holds contribution records.
class BrigadaPeriod {
  const BrigadaPeriod({
    required this.year,
    required this.month,
    required this.label,
    required this.records,
  });

  final int year;
  final int month;
  final String label;
  final int records;

  bool sameAs(int y, int m) => year == y && month == m;

  factory BrigadaPeriod.fromJson(Map<String, dynamic> json) => BrigadaPeriod(
        year: _int(json['year']),
        month: _int(json['month']),
        label: _str(json['label']),
        records: _int(json['records']),
      );
}

/// A partner-type bucket tile on the summary screen.
class PartnerTypeCount {
  const PartnerTypeCount({
    required this.key,
    required this.label,
    required this.count,
  });

  final String key;
  final String label;
  final int count;

  factory PartnerTypeCount.fromJson(Map<String, dynamic> json) =>
      PartnerTypeCount(
        key: _str(json['key']),
        label: _str(json['label']),
        count: _int(json['count']),
      );
}

/// One date's contribution totals for a school.
class SummaryEntry {
  const SummaryEntry({
    required this.date,
    required this.resources,
    required this.volunteers,
    required this.records,
  });

  final String date;
  final double resources;
  final int volunteers;
  final int records;

  factory SummaryEntry.fromJson(Map<String, dynamic> json) => SummaryEntry(
        date: _str(json['date']),
        resources: _double(json['resources']),
        volunteers: _int(json['volunteers']),
        records: _int(json['records']),
      );
}

/// A school's roll-up for the selected period.
class SummarySchool {
  const SummarySchool({
    required this.schoolId,
    required this.schoolName,
    required this.totalResources,
    required this.totalVolunteers,
    required this.totalRecords,
    required this.entries,
  });

  final String schoolId;
  final String schoolName;
  final double totalResources;
  final int totalVolunteers;
  final int totalRecords;
  final List<SummaryEntry> entries;

  factory SummarySchool.fromJson(Map<String, dynamic> json) => SummarySchool(
        schoolId: _str(json['school_id']),
        schoolName: _str(json['school_name']),
        totalResources: _double(json['total_resources']),
        totalVolunteers: _int(json['total_volunteers']),
        totalRecords: _int(json['total_records']),
        entries: _list(json['entries']).map(SummaryEntry.fromJson).toList(),
      );
}

/// Response of `summary`.
class BrigadaSummary {
  const BrigadaSummary({
    required this.month,
    required this.year,
    required this.monthLabel,
    required this.dates,
    required this.records,
    required this.resources,
    required this.volunteers,
    required this.days,
    required this.generalPartnerTypes,
    required this.specificPartnerTypes,
    required this.schools,
  });

  final int month;
  final int year;
  final String monthLabel;
  final List<String> dates;
  final int records;
  final double resources;
  final int volunteers;
  final int days;
  final List<PartnerTypeCount> generalPartnerTypes;
  final List<PartnerTypeCount> specificPartnerTypes;
  final List<SummarySchool> schools;

  String get periodLabel => '$monthLabel $year';

  factory BrigadaSummary.fromJson(Map<String, dynamic> json) {
    final totals = (json['totals'] as Map?)?.cast<String, dynamic>() ?? const {};
    return BrigadaSummary(
      month: _int(json['month']),
      year: _int(json['year']),
      monthLabel: _str(json['month_label']),
      dates: (json['dates'] as List?)?.map((e) => '$e').toList() ?? const [],
      records: _int(totals['records']),
      resources: _double(totals['resources']),
      volunteers: _int(totals['volunteers']),
      days: _int(totals['days']),
      generalPartnerTypes: _list(json['general_partner_types'])
          .map(PartnerTypeCount.fromJson)
          .toList(),
      specificPartnerTypes: _list(json['specific_partner_types'])
          .map(PartnerTypeCount.fromJson)
          .toList(),
      schools: _list(json['schools']).map(SummarySchool.fromJson).toList(),
    );
  }
}

/// One contribution record on the details screen.
class ContributionRecord {
  const ContributionRecord({
    required this.id,
    required this.date,
    required this.schoolId,
    required this.schoolName,
    required this.partnerName,
    required this.generalType,
    required this.specificType,
    required this.contributionType,
    required this.contribution,
    required this.unit,
    required this.quantity,
    required this.amount,
    required this.volunteers,
    required this.projectName,
    required this.projectCategory,
    required this.remarks,
  });

  final int id;
  final String date;
  final String schoolId;
  final String schoolName;
  final String partnerName;
  final String generalType;
  final String specificType;
  final String contributionType;
  final String contribution;
  final String unit;
  final double quantity;
  final double amount;
  final int volunteers;
  final String projectName;
  final String projectCategory;
  final String remarks;

  factory ContributionRecord.fromJson(Map<String, dynamic> json) =>
      ContributionRecord(
        id: _int(json['id']),
        date: _str(json['date']),
        schoolId: _str(json['school_id']),
        schoolName: _str(json['school_name']),
        partnerName: _str(json['partner_name']),
        generalType: _str(json['general_type']),
        specificType: _str(json['specific_type']),
        contributionType: _str(json['contribution_type']),
        contribution: _str(json['contribution']),
        unit: _str(json['unit']),
        quantity: _double(json['quantity']),
        amount: _double(json['amount']),
        volunteers: _int(json['volunteers']),
        projectName: _str(json['project_name']),
        projectCategory: _str(json['project_category']),
        remarks: _str(json['remarks']),
      );
}

/// Response of `summary_details`.
class ContributionDetails {
  const ContributionDetails({
    required this.title,
    required this.month,
    required this.year,
    required this.records,
    required this.totalRecords,
    required this.totalResources,
    required this.totalVolunteers,
    required this.totalDays,
  });

  final String title;
  final int month;
  final int year;
  final List<ContributionRecord> records;
  final int totalRecords;
  final double totalResources;
  final int totalVolunteers;
  final int totalDays;

  factory ContributionDetails.fromJson(Map<String, dynamic> json) {
    final totals = (json['totals'] as Map?)?.cast<String, dynamic>() ?? const {};
    return ContributionDetails(
      title: _str(json['title']),
      month: _int(json['month']),
      year: _int(json['year']),
      records:
          _list(json['records']).map(ContributionRecord.fromJson).toList(),
      totalRecords: _int(totals['records']),
      totalResources: _double(totals['resources']),
      totalVolunteers: _int(totals['volunteers']),
      totalDays: _int(totals['days']),
    );
  }
}

// ── 4. Partner satisfaction survey ───────────────────────────────────────────

/// One rating metric — an average, or one partner's score.
class SurveyScore {
  const SurveyScore({
    required this.key,
    required this.label,
    required this.value,
    this.description = '',
  });

  final String key;
  final String label;
  final double value;
  final String description;

  /// Position on the 1-5 scale, 0..1.
  double get fraction => (value / 5).clamp(0, 1);

  factory SurveyScore.fromJson(Map<String, dynamic> json) => SurveyScore(
        key: _str(json['key']),
        label: _str(json['label']),
        value: _double(json['value']),
        description: _str(json['description']),
      );
}

/// One partner's submitted survey.
class SurveyResponse {
  const SurveyResponse({
    required this.id,
    required this.partnerName,
    required this.contactPerson,
    required this.scores,
    required this.overall,
    required this.comments,
    required this.submittedAt,
  });

  final int id;
  final String partnerName;
  final String contactPerson;
  final List<SurveyScore> scores;
  final double overall;
  final String comments;
  final String submittedAt;

  factory SurveyResponse.fromJson(Map<String, dynamic> json) => SurveyResponse(
        id: _int(json['id']),
        partnerName: _str(json['partner_name']),
        contactPerson: _str(json['contact_person']),
        scores: _list(json['scores']).map(SurveyScore.fromJson).toList(),
        overall: _double(json['overall']),
        comments: _str(json['comments']),
        submittedAt: _str(json['submitted_at']),
      );
}

/// Response of `survey_results`.
class SurveyResults {
  const SurveyResults({
    required this.totalSurveys,
    required this.ready,
    required this.averages,
    required this.responses,
  });

  final int totalSurveys;

  /// False when no partner has ever submitted — the backing table is created
  /// lazily on first submission, so this is "not started yet", not an error.
  final bool ready;
  final List<SurveyScore> averages;
  final List<SurveyResponse> responses;

  /// Mean of the six metric averages.
  double get overall {
    if (averages.isEmpty) return 0;
    final sum = averages.fold<double>(0, (s, a) => s + a.value);
    return sum / averages.length;
  }

  factory SurveyResults.fromJson(Map<String, dynamic> json) => SurveyResults(
        totalSurveys: _int(json['total_surveys']),
        ready: _bool(json['ready']),
        averages: _list(json['averages']).map(SurveyScore.fromJson).toList(),
        responses:
            _list(json['surveys']).map(SurveyResponse.fromJson).toList(),
      );
}
