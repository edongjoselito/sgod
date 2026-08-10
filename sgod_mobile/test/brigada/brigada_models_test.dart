import 'dart:convert';
import 'dart:io';

import 'package:flutter_test/flutter_test.dart';
import 'package:sgod_mobile/brigada/data/brigada_models.dart';
import 'package:sgod_mobile/brigada/data/brigada_repository.dart';

/// Parses fixtures captured from the live `api_brigada` endpoints against the
/// division database, so a change to either side surfaces here rather than as
/// a blank screen on a phone.
Map<String, dynamic> _fixture(String name) {
  final file = File('test/brigada/fixtures/$name.json');
  return jsonDecode(file.readAsStringSync()) as Map<String, dynamic>;
}

void main() {
  group('BrigadaMeta', () {
    test('parses school years', () {
      final meta = BrigadaMeta.fromJson(_fixture('meta'));
      expect(meta.currentSy, '2026-2027');
      expect(meta.schoolYears, contains('2026-2027'));
      expect(meta.section, 'Social Mobilization and Networking');
    });

    test('fallback derives the current school year', () {
      final year = DateTime.now().year;
      expect(BrigadaMeta.fallback().currentSy, '$year-${year + 1}');
    });
  });

  group('School Preparedness', () {
    test('district list totals and progress', () {
      final list = SpcDistrictList.fromJson(_fixture('spc_districts'));
      expect(list.sy, '2026-2027');
      expect(list.districts, isNotEmpty);
      expect(list.totalSchools, greaterThan(0));

      // Aggregates are the sum of their parts.
      expect(
        list.totalSubmitted,
        list.districts.fold<int>(0, (sum, d) => sum + d.submittedCount),
      );
      expect(list.progress, inInclusiveRange(0, 1));

      // A district with no schools must not produce a NaN progress bar.
      final empty = list.districts.where((d) => d.schoolCount == 0);
      for (final district in empty) {
        expect(district.progress, 0);
        expect(district.pendingCount, 0);
      }
    });

    test('district schools carry submission status', () {
      final list = SpcSchoolList.fromJson(_fixture('spc_district_schools'));
      expect(list.districtName, 'Baganga North');
      expect(list.schools, isNotEmpty);
      expect(
        list.submittedCount,
        list.schools.where((s) => s.submitted).length,
      );
      // schoolID is duplicated for a few rows upstream; the API dedupes.
      final ids = list.schools.map((s) => s.schoolId).toList();
      expect(ids.toSet().length, ids.length);
    });

    test('checklist maps every category, item and rating', () {
      final checklist = SpcChecklist.fromJson(_fixture('spc_checklist'));
      expect(checklist.submitted, isTrue);
      expect(checklist.categories, hasLength(8));

      final items =
          checklist.categories.expand((c) => c.items).toList(growable: false);
      expect(items, hasLength(checklist.itemCount));
      expect(items.every((i) => i.description.isNotEmpty), isTrue);

      // Ordinals restart at 1 inside each category — this is what resolves the
      // qN/rN column, so a regression here would silently mis-read answers.
      for (final category in checklist.categories) {
        expect(
          category.items.map((i) => i.ordinal),
          List.generate(category.items.length, (i) => i + 1),
        );
      }

      final answered = items.where((i) => i.value != null);
      expect(checklist.answered, answered.length);
      expect(
        checklist.fully + checklist.partially + checklist.notPrepared,
        answered.length,
      );
      expect(answered.every((i) => i.value! >= 1 && i.value! <= 3), isTrue);
      expect(checklist.readiness, inInclusiveRange(0, 1));
    });
  });

  group('SPC Report', () {
    test('category and item tallies reconcile with the totals', () {
      final report = SpcReport.fromJson(_fixture('spc_report'));
      expect(report.submissionCount, greaterThan(0));
      expect(report.categories, hasLength(8));

      var fully = 0, partially = 0, notPrepared = 0;
      for (final category in report.categories) {
        expect(
          category.fully,
          category.items.fold<int>(0, (sum, i) => sum + i.fully),
        );
        fully += category.fully;
        partially += category.partially;
        notPrepared += category.notPrepared;

        for (final item in category.items) {
          expect(
            item.responses,
            item.fully + item.partially + item.notPrepared,
          );
          // No item can have more answers than there are submissions.
          expect(item.responses, lessThanOrEqualTo(report.submissionCount));
        }
      }
      expect(report.fully, fully);
      expect(report.partially, partially);
      expect(report.notPrepared, notPrepared);
      expect(report.total, fully + partially + notPrepared);
    });

    test('responses list resolves school names', () {
      final list = SpcResponseList.fromJson(_fixture('spc_responses'));
      expect(list.value, 1);
      expect(list.ratingLabel, 'Fully Prepared');
      expect(list.itemDescription, isNotEmpty);
      expect(list.schools, isNotEmpty);
      expect(list.schools.every((s) => s.schoolName.isNotEmpty), isTrue);
    });
  });

  group('Summary Report', () {
    test('school roll-ups reconcile with the headline totals', () {
      final summary = BrigadaSummary.fromJson(_fixture('summary'));
      expect(summary.month, 6);
      expect(summary.year, 2026);
      expect(summary.monthLabel, 'June');
      expect(summary.periodLabel, 'June 2026');
      expect(summary.schools, isNotEmpty);

      final records =
          summary.schools.fold<int>(0, (sum, s) => sum + s.totalRecords);
      final volunteers =
          summary.schools.fold<int>(0, (sum, s) => sum + s.totalVolunteers);
      final resources = summary.schools
          .fold<double>(0, (sum, s) => sum + s.totalResources);

      expect(records, summary.records);
      expect(volunteers, summary.volunteers);
      expect(resources, closeTo(summary.resources, 0.01));

      // Reporting days is the distinct date count.
      expect(summary.days, summary.dates.length);
      expect(summary.dates.toSet().length, summary.dates.length);

      // Each school's per-date entries must sum to its own totals.
      for (final school in summary.schools) {
        expect(
          school.entries.fold<int>(0, (sum, e) => sum + e.records),
          school.totalRecords,
        );
        expect(
          school.entries.fold<double>(0, (sum, e) => sum + e.resources),
          closeTo(school.totalResources, 0.01),
        );
      }
    });

    test('partner buckets are always present, even at zero', () {
      final summary = BrigadaSummary.fromJson(_fixture('summary'));
      expect(
        summary.generalPartnerTypes.map((t) => t.key),
        containsAll([
          'Private_Sector',
          'Public_Sector',
          'International',
          'Civil_Society_Organizations',
        ]),
      );
      expect(summary.specificPartnerTypes, hasLength(3));
      expect(
        summary.generalPartnerTypes.every((t) => t.label.isNotEmpty),
        isTrue,
      );
    });

    test('details records and totals', () {
      final details = ContributionDetails.fromJson(_fixture('summary_details'));
      expect(details.records, isNotEmpty);
      expect(details.totalRecords, details.records.length);
      expect(
        details.records.fold<double>(0, (sum, r) => sum + r.amount),
        closeTo(details.totalResources, 0.01),
      );
      expect(
        details.records.fold<int>(0, (sum, r) => sum + r.volunteers),
        details.totalVolunteers,
      );
      expect(
        details.records.every((r) => r.generalType == 'International'),
        isTrue,
      );
    });

    test('periods are ordered newest first', () {
      final periods =
          BrigadaRepository.parsePeriods(_fixture('summary_periods'));
      expect(periods, isNotEmpty);
      for (var i = 1; i < periods.length; i++) {
        final prev = periods[i - 1].year * 12 + periods[i - 1].month;
        final curr = periods[i].year * 12 + periods[i].month;
        expect(prev, greaterThan(curr));
      }
      expect(periods.every((p) => p.records > 0), isTrue);
    });
  });

  group('Survey results', () {
    test('an uncreated survey table reads as "no responses yet"', () {
      final results = SurveyResults.fromJson(_fixture('survey_results'));
      expect(results.totalSurveys, 0);
      expect(results.ready, isFalse);
      expect(results.averages, isEmpty);
      expect(results.responses, isEmpty);
      // Must not divide by zero when nothing has been submitted.
      expect(results.overall, 0);
    });

    test('averages and responses parse when submissions exist', () {
      final results = SurveyResults.fromJson({
        'total_surveys': 2,
        'ready': true,
        'averages': [
          {
            'key': 'responsiveness',
            'label': 'Responsiveness',
            'value': 4.5,
            'description': 'Excellent',
          },
          {
            'key': 'communication',
            'label': 'Communication',
            'value': 3.5,
            'description': 'Very Good',
          },
        ],
        'surveys': [
          {
            'id': 1,
            'partner_name': 'Acme Foundation',
            'contact_person': 'J. Cruz',
            'scores': [
              {'key': 'responsiveness', 'label': 'Responsiveness', 'value': 5},
              {'key': 'communication', 'label': 'Communication', 'value': 4},
            ],
            'overall': 4.5,
            'comments': 'Smooth coordination.',
            'submitted_at': '2026-07-01 09:30:00',
          },
        ],
      });

      expect(results.ready, isTrue);
      expect(results.overall, 4.0);
      expect(results.averages.first.fraction, closeTo(0.9, 0.0001));
      expect(results.responses.single.partnerName, 'Acme Foundation');
      expect(results.responses.single.scores, hasLength(2));
    });
  });

  group('Cache keys', () {
    test('are stable regardless of query order', () {
      const a = BrigadaRequest('summary', {'year': 2026, 'month': 6});
      const b = BrigadaRequest('summary', {'month': 6, 'year': 2026});
      expect(a.cacheKey, b.cacheKey);
      expect(a, b);
      expect(a.hashCode, b.hashCode);
    });

    test('distinguish different filters', () {
      expect(
        BrigadaRepository.summaryRequest(2026, 6).cacheKey,
        isNot(BrigadaRepository.summaryRequest(2026, 7).cacheKey),
      );
      expect(
        BrigadaRepository.detailsRequest(year: 2026, month: 6, card: 'records')
            .cacheKey,
        isNot(
          BrigadaRepository.detailsRequest(
            year: 2026,
            month: 6,
            scope: 'general',
            type: 'International',
          ).cacheKey,
        ),
      );
    });

    test('map to the api_brigada path', () {
      expect(
        BrigadaRepository.reportRequest('2026-2027').path,
        'api_brigada/spc_report',
      );
    });
  });
}
