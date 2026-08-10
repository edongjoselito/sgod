# Brigada Eskwela module

Mobile implementation of the four reports under the web sidebar's **Brigada
Eskwela** group. Self-contained under `lib/brigada/` — it borrows only
`DI.api` and `DI.connectivity` from the app, and owns its own storage,
repository and sync engine so it can evolve without touching the core.

## Screens

| Web page | Mobile screen |
|---|---|
| `Brigada/spc_districts` | `ui/preparedness/spc_districts_view.dart` → schools → checklist |
| `Brigada/spc_admin_report` | `ui/report/spc_report_view.dart` → responses |
| `Brigada/brigada_summary_v2` | `ui/summary/brigada_summary_view.dart` → school → details |
| `Page/satisfaction_survey_results` | `ui/survey/survey_results_view.dart` |
| — | `ui/hub/brigada_hub_view.dart` (module home + offline controls) |

Each screen is built for a phone rather than shrunk from the web: the SPC
report's three count columns become tappable counters, and the summary's
school × date pivot becomes a ranked school list that opens a per-day
breakdown.

## Backend

`application/controllers/Api_brigada.php` — separate from `Api.php` so the two
API surfaces never collide. Same `{ok, message, data}` envelope, so the shared
`ApiClient` parses it unchanged. Reuses the `Api_auth` library for bearer
tokens.

Access requires a valid token **and** `section == "Social Mobilization and
Networking"` — the same gate the web sidebar uses to render the menu.

Endpoints: `meta`, `spc_districts`, `spc_district_schools`,
`spc_school_checklist`, `spc_checklists`, `spc_report`,
`spc_report_responses`, `summary`, `summary_details`, `summary_periods`,
`survey_results`.

## Offline

These reports are read-only, so offline is a document cache, not a relational
mirror — and sync is a one-way pull with no write outbox.

- `data/brigada_store.dart` — one JSON file per response under
  `<appDocs>/brigada_cache/`, plus an index holding freshness and the owning
  account. Switching accounts wipes it. Falls back to memory on web.
- `data/brigada_repository.dart` — screens paint from cache first, then
  revalidate. Offline with nothing stored raises `BrigadaOfflineException`; a
  failed refresh serves cache and reports the error. A 401 is never masked.
- `data/brigada_sync.dart` — `refresh()` re-pulls what is cached (auto-runs
  when connectivity returns and the cache is stale);
  `downloadForOffline()` walks every district, checklist bundle, the report,
  the survey and the last 6 months of contributions with progress.

Every screen shows where its numbers came from and how old they are.

## Notes on the data

Two upstream quirks this module works around, both verified against the
division database:

- `schools.schoolID` is not unique (a few IDs repeat), so joining it into an
  aggregate inflates counts. The API aggregates first and resolves names via a
  lookup. The web summary page does join, and reports 2,514 records for June
  2026 where the table holds 2,506.
- `brigada_spc_feedback` q-columns default to `0`, which means "unanswered" —
  only 1/2/3 are ratings. The API counts accordingly.

The web's `Brigada/spc_feedback` drill-down builds its column as
`'q' . category_id . item_id`, which only lines up for the first category.
`spc_report_responses` resolves the column from the item's ordinal within its
category instead, which is how answers are actually stored.

## Tests

```bash
flutter test test/brigada/
```

- `brigada_models_test.dart` — parsing against fixtures captured from the live
  API, with cross-checks that tallies reconcile.
- `brigada_offline_test.dart` — store round-trips, on-disk persistence across a
  simulated restart, and repository cache policy.
- `brigada_sync_test.dart` — refresh/download passes and failure handling.
- `brigada_widget_test.dart` — every screen rendered against the fixtures,
  online and offline.

Refresh the fixtures with `test/brigada/fixtures/` regenerated from a running
local server if the API changes shape.

> Widget tests must do store setup inside `tester.runAsync` — `path_provider`
> and file I/O never complete inside the fake-async zone `testWidgets` uses.
