import 'package:flutter/cupertino.dart';

/// iOS-style design system for the ONE DepED mobile app.
///
/// Leads with the official DepEd navy as the primary brand color for an
/// authoritative, government-grade feel. DepEd gold is reserved as a refined
/// accent (active states, highlights, the seal) so it never reads as garish.
/// Uses SF Pro-style typography (Instrument Sans as the closest available
/// geometric sans) and iOS conventions throughout.
class AppColors {
  AppColors._();

  // ── Brand ───────────────────────────────────────────────────────────────
  /// Official DepEd navy — the app's primary brand color. Used for headers,
  /// primary buttons, the sidebar gradient, and active/selected states.
  static const Color primary = Color(0xFF003F88);
  /// A deeper navy for gradient endpoints and pressed states.
  static const Color primaryDark = Color(0xFF002A5C);
  /// DepEd gold — accent only. Use sparingly for the seal, active tab
  /// indicators, and key highlights.
  static const Color accent = Color(0xFFFCD116);
  /// Subtle gold tint for accent backgrounds (12% opacity equivalent).
  static const Color accentTint = Color(0x1EFCD116);

  // ── Surfaces ────────────────────────────────────────────────────────────
  static const Color background = CupertinoColors.systemGroupedBackground;
  static const Color secondaryBackground = CupertinoColors.secondarySystemGroupedBackground;
  static const Color surface = Color(0xFFFFFFFF);

  // ── Text ────────────────────────────────────────────────────────────────
  static const Color label = CupertinoColors.label;
  static const Color secondaryLabel = CupertinoColors.secondaryLabel;
  static const Color tertiaryLabel = CupertinoColors.tertiaryLabel;
  static const Color separator = CupertinoColors.separator;

  // ── Semantic ────────────────────────────────────────────────────────────
  static const Color success = CupertinoColors.systemGreen;
  static const Color warning = CupertinoColors.systemOrange;
  static const Color danger = CupertinoColors.systemRed;
  static const Color info = CupertinoColors.systemBlue;

  // ── Role accents ────────────────────────────────────────────────────────
  /// SGOD wears the brand navy; other divisions keep distinct hues so the
  /// role is recognizable at a glance without breaking the palette.
  static const Color sgod = Color(0xFF003F88);       // DepEd navy
  static const Color shns = Color(0xFF34C759);       // systemGreen
  static const Color school = Color(0xFF5856D6);     // systemIndigo
  static const Color sned = Color(0xFF32ADE6);       // systemTeal
  static const Color smme = Color(0xFFFF9500);       // systemOrange
  static const Color district = Color(0xFFAF52DE);   // systemPurple

  static Color forRole(String role) {
    switch (role.toLowerCase()) {
      case 'sgod': return sgod;
      case 'shns': return shns;
      case 'school': return school;
      case 'sned': return sned;
      case 'smme': return smme;
      case 'district': return district;
      default: return primary;
    }
  }
}
