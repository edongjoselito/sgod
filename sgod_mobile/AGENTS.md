# SGOD Mobile — Development Notes

## Project
Native Flutter mobile app for the DepEd SGOD system. One app, role-based UI
for 6 positions: sgod, shns, school, sned, smme, district. Shares the same
MySQL database (`depedmis_one`) as the web app via a new REST API layer.

## Stack
- Flutter 3.41.x / Dart 3.11.x
- State: MVVM with `ChangeNotifier` + `provider`
- DI: `get_it` (static `DI` class in `lib/ui/core/di.dart`)
- Routing: `go_router` with auth redirect
- Local DB: Drift (SQLite) for offline cache + write outbox
- HTTP: `http` package with bearer-token `ApiClient`
- Design: Material 3, DepEd navy (#003F88) + gold (#FCD116), squircle shapes

## Commands
```bash
# Install dependencies
flutter pub get

# Code generation (run after changing models or drift schema)
flutter pub run build_runner build --delete-conflicting-outputs

# Analyze
flutter analyze

# Test
flutter test

# Run (connect device or emulator first)
flutter run
```

## Architecture
```
lib/
├── main.dart              → entry point, calls DI.init()
├── app.dart               → MaterialApp.router, theme, providers
├── core/
│   ├── theme/             → AppColors, AppTheme (squircle design system)
│   ├── network/           → ApiClient (bearer token, retry), ApiException
│   ├── services/          → Drift DB, SyncService, Connectivity, SecureStorage
│   └── widgets/           → PrimaryButton, SectionCard, StatCard, EmptyState, etc.
├── data/
│   ├── models/            → freezed models (UserProfile, AuthResult)
│   └── repositories/      → AuthRepository (login, logout, session restore)
├── domain/
│   └── use_cases/         → (Phase 3: sync, offline submit)
└── ui/
    ├── core/              → di.dart, router.dart
    └── features/
        ├── auth/          → LoginView, AuthViewModel
        ├── shell/         → AppShell (role-aware bottom nav)
        ├── dashboard/     → DashboardView (per-role content)
        ├── profile/       → ProfileView
        └── settings/      → SettingsView (sync status, logout)
```

## Backend API
- New controller: `application/controllers/Api.php` (all routes under `/api/*`)
- Auth library: `application/libraries/Api_auth.php` (bearer token validation)
- Model: `application/models/Api_model.php`
- DB table: `api_tokens` (migration in `sql/create_api_tokens.sql`)
- JSON envelope: `{ "ok": bool, "message": string, "data": any }`
- Auth: bearer token in `Authorization` header. Two login sources:
  - `deped_mis` → validates against `users` table (bcrypt/sha1/md5/plain)
  - `sgod` → validates against `one_sgod_users` table (sha1)

## Offline Strategy
- Drift database caches read data (memos, accomplishments, schools, personnel)
- `SyncOutboxTable` queues writes created while offline
- `SyncService` drains outbox FIFO when connectivity returns
- Conflict policy: last-write-wins on `updated_at`; conflicts flagged for manual review

## Covered Positions
| Position | Role enum | Source table |
|----------|-----------|-------------|
| sgod | Role.sgod | one_sgod_users |
| shns | Role.shns | users |
| school | Role.school | users |
| sned | Role.sned | users |
| smme | Role.smme | users |
| district | Role.district | users |

## Phases
- **Phase 0 (DONE)**: Scaffold, theme, API client, Drift DB, auth, shell, dashboards
- **Phase 1**: Wire SGOD dashboard to live API data
- **Phase 2**: Remaining 5 dashboards
- **Phase 3**: CRUD modules (accomplishments, memos, schools, personnel, brigada, ipcrf)
- **Phase 4**: Sync hardening, push notifications, biometric login
- **Phase 5**: Polish, tablet layout, launcher icons, splash
