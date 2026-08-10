import 'package:flutter/cupertino.dart';

/// iOS-style design system for the SGOD mobile app.
///
/// Uses Cupertino colors, SF Pro-style typography (Instrument Sans as the
/// closest available geometric sans), and iOS conventions throughout.
class AppColors {
  AppColors._();

  // ── iOS system colors ───────────────────────────────────────────────────
  static const Color primary = CupertinoColors.systemBlue;
  static const Color primaryDark = Color(0xFF003F88); // DepEd navy for brand moments
  static const Color accent = Color(0xFFFCD116);      // DepEd gold

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
  static const Color sgod = Color(0xFF007AFF);       // systemBlue
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
