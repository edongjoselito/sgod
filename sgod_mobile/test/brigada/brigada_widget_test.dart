import 'dart:convert';
import 'dart:io';

import 'package:connectivity_plus/connectivity_plus.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:sgod_mobile/brigada/brigada_module.dart';
import 'package:sgod_mobile/brigada/data/brigada_repository.dart';
import 'package:sgod_mobile/brigada/data/brigada_store.dart';
import 'package:sgod_mobile/brigada/data/brigada_sync.dart';
import 'package:sgod_mobile/brigada/ui/hub/brigada_hub_view.dart';
import 'package:sgod_mobile/brigada/ui/preparedness/spc_checklist_view.dart';
import 'package:sgod_mobile/brigada/ui/preparedness/spc_districts_view.dart';
import 'package:sgod_mobile/brigada/ui/preparedness/spc_schools_view.dart';
import 'package:sgod_mobile/brigada/ui/report/spc_report_view.dart';
import 'package:sgod_mobile/brigada/ui/report/spc_responses_view.dart';
import 'package:sgod_mobile/brigada/ui/summary/brigada_summary_view.dart';
import 'package:sgod_mobile/brigada/ui/summary/contribution_details_view.dart';
import 'package:sgod_mobile/brigada/ui/survey/survey_results_view.dart';
import 'package:sgod_mobile/core/network/api_client.dart';
import 'package:sgod_mobile/core/services/connectivity_service.dart';

/// Replays the fixtures captured from the live division API, so the screens
/// are exercised against the same shapes and volumes production returns
/// (June 2026 alone is 2,506 contribution records across 201 schools).
class _FixtureApi extends ApiClient {
  _FixtureApi({this.offline = false});

  final bool offline;

  static Map<String, dynamic> _load(String name) =>
      jsonDecode(File('test/brigada/fixtures/$name.json').readAsStringSync())
          as Map<String, dynamic>;

  @override
  Future<dynamic> get(String path, {Map<String, dynamic>? query}) async {
    if (offline) throw const SocketException('offline');
    switch (path.replaceFirst('api_brigada/', '')) {
      case 'meta':
        return _load('meta');
      case 'spc_districts':
        return _load('spc_districts');
      case 'spc_district_schools':
        return _load('spc_district_schools');
      case 'spc_school_checklist':
        return _load('spc_checklist');
      case 'spc_report':
        return _load('spc_report');
      case 'spc_report_responses':
        return _load('spc_responses');
      case 'summary_periods':
        return _load('summary_periods');
      case 'summary':
        return _load('summary');
      case 'summary_details':
        return _load('summary_details');
      case 'survey_results':
        return _load('survey_results');
      default:
        return const <String, dynamic>{};
    }
  }
}

class _FakeConnectivity extends ConnectivityService {
  _FakeConnectivity(this.online) : super(connectivity: Connectivity());

  final bool online;

  @override
  bool get isOnline => online;
}

void main() {
  TestWidgetsFlutterBinding.ensureInitialized();

  Future<void> install({bool offline = false, bool online = true}) async {
    final store = BrigadaStore();
    await store.init();
    await store.clear();
    final connectivity = _FakeConnectivity(online);
    final repo = BrigadaRepository(
      api: _FixtureApi(offline: offline),
      store: store,
      connectivity: connectivity,
    );
    BrigadaModule.installForTests(
      testStore: store,
      testRepository: repo,
      testSync: BrigadaSyncService(
        repository: repo,
        store: store,
        connectivity: connectivity,
      ),
    );
  }

  Future<void> pump(WidgetTester tester, Widget page) async {
    await tester.pumpWidget(CupertinoApp(home: page));
    // Settle the two-phase load (cache miss → network) without waiting on
    // the sliver refresh control's animations.
    await tester.pump();
    await tester.pump(const Duration(milliseconds: 100));
    await tester.pump(const Duration(milliseconds: 100));
  }

  group('renders with live-shaped data', () {
    testWidgets('hub', (tester) async {
      await install();
      await pump(tester, const BrigadaHubView(username: '1301260'));

      expect(find.text('Brigada Eskwela'), findsWidgets);
      expect(find.text('School Preparedness'), findsOneWidget);
      expect(find.text('SPC Report'), findsOneWidget);
      expect(find.text('Summary Report'), findsOneWidget);
      expect(find.text('Survey Results'), findsOneWidget);
      expect(find.text('Download for offline'), findsOneWidget);
      expect(tester.takeException(), isNull);
    });

    testWidgets('school preparedness districts', (tester) async {
      await install();
      await pump(tester, const SpcDistrictsView(sy: '2026-2027'));

      expect(find.text('Baganga North'), findsOneWidget);
      expect(find.text('SY 2026-2027'), findsOneWidget);
      expect(find.textContaining('of schools have submitted'), findsOneWidget);
      expect(tester.takeException(), isNull);
    });

    testWidgets('district school list', (tester) async {
      await install();
      await pump(
        tester,
        const SpcSchoolsView(
          districtId: 1,
          districtName: 'Baganga North',
          sy: '2026-2027',
        ),
      );

      expect(find.text('BAN-AO ES'), findsOneWidget);
      expect(find.text('Submitted'), findsWidgets);
      expect(tester.takeException(), isNull);
    });

    testWidgets('school checklist', (tester) async {
      await install();
      await pump(
        tester,
        const SpcChecklistView(
          schoolId: '129152',
          schoolName: 'BAN-AO ES',
          sy: '2026-2027',
        ),
      );

      expect(find.text('Facilities and Infrastructure'), findsOneWidget);
      expect(find.textContaining('of answers fully prepared'), findsOneWidget);
      expect(find.textContaining('6 of 29 answered'), findsOneWidget);
      expect(tester.takeException(), isNull);
    });

    testWidgets('spc report', (tester) async {
      await install();
      await pump(tester, const SpcReportView(sy: '2026-2027'));

      expect(find.text('Learning Resources'), findsOneWidget);
      expect(find.textContaining('checklists submitted'), findsOneWidget);
      expect(tester.takeException(), isNull);
    });

    testWidgets('spc report expands a category into tappable counters',
        (tester) async {
      await install();
      await pump(tester, const SpcReportView(sy: '2026-2027'));

      await tester.tap(find.text('Learning Resources'));
      await tester.pump();
      await tester.pump(const Duration(milliseconds: 100));

      expect(find.text('Fully'), findsWidgets);
      expect(find.text('Not prep.'), findsWidgets);
      expect(tester.takeException(), isNull);
    });

    testWidgets('report responses', (tester) async {
      await install();
      await pump(
        tester,
        const SpcResponsesView(
          sy: '2026-2027',
          itemId: 1,
          value: 1,
          itemDescription: 'Classrooms are clean',
          categoryName: 'Facilities and Infrastructure',
        ),
      );

      expect(find.text('Fully Prepared'), findsWidgets);
      expect(tester.takeException(), isNull);
    });

    testWidgets('summary report with 201 schools', (tester) async {
      await install();
      await pump(tester, const BrigadaSummaryView());

      expect(find.text('Total Records'), findsOneWidget);
      expect(find.text('Total Resources'), findsOneWidget);
      expect(find.text('Total Volunteers'), findsOneWidget);
      expect(find.text('Reporting Days'), findsOneWidget);
      expect(find.text('Private Sector'), findsOneWidget);
      expect(tester.takeException(), isNull);
    });

    testWidgets('contribution details', (tester) async {
      await install();
      await pump(
        tester,
        const ContributionDetailsView(
          year: 2026,
          month: 6,
          scope: 'general',
          type: 'International',
          title: 'International',
        ),
      );

      expect(find.text('total resources'), findsOneWidget);
      expect(find.text('STA. FILOMENA ES'), findsWidgets);
      expect(tester.takeException(), isNull);
    });

    testWidgets('survey results shows the not-started state', (tester) async {
      await install();
      await pump(tester, const SurveyResultsView());

      // The backing table is created on first submission, so this is the
      // real current state — it must read as "none yet", not as an error.
      expect(find.text('No surveys submitted yet'), findsOneWidget);
      expect(tester.takeException(), isNull);
    });
  });

  group('offline behaviour', () {
    testWidgets('with nothing cached, offers a clear recovery path',
        (tester) async {
      await install(offline: true, online: false);
      await pump(tester, const SpcReportView(sy: '2026-2027'));

      expect(find.text('Not available offline'), findsOneWidget);
      expect(find.text('Try again'), findsOneWidget);
      expect(tester.takeException(), isNull);
    });

    testWidgets('serves cached data and labels it as saved', (tester) async {
      // Warm the cache while "online"...
      await install();
      await BrigadaModule.repository.fetch(
        BrigadaRepository.reportRequest('2026-2027'),
        (json) => json,
      );
      final warmStore = BrigadaModule.store;

      // ...then go offline against the same store.
      final connectivity = _FakeConnectivity(false);
      final repo = BrigadaRepository(
        api: _FixtureApi(offline: true),
        store: warmStore,
        connectivity: connectivity,
      );
      BrigadaModule.installForTests(
        testStore: warmStore,
        testRepository: repo,
        testSync: BrigadaSyncService(
          repository: repo,
          store: warmStore,
          connectivity: connectivity,
        ),
      );

      await pump(tester, const SpcReportView(sy: '2026-2027'));

      expect(find.text('Learning Resources'), findsOneWidget);
      expect(find.textContaining('Offline — showing data saved'),
          findsOneWidget);
      expect(tester.takeException(), isNull);
    });
  });
}
