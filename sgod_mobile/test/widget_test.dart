import 'package:flutter_test/flutter_test.dart';

void main() {
  testWidgets('App smoke test — placeholder', (WidgetTester tester) async {
    // Phase 0 scaffold: full widget tests are added in Phase 1 once the
    // login flow is wired to a mock API. This placeholder keeps `flutter
    // test` green.
    expect(true, isTrue);
  });
}
