import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';

import '../theme/app_colors.dart';
import '../theme/app_theme.dart';

/// Centralized Material Design dialog helpers that work inside a
/// `CupertinoApp`.  Each dialog is wrapped in its own `Material` +
/// `Theme` context so it renders correctly without a `MaterialApp`
/// ancestor.
class AppDialogs {
  AppDialogs._();

  /// The Material theme used by all dialogs.
  static ThemeData _theme(BuildContext context) {
    return AppTheme.buildMaterialTheme();
  }

  /// Shows a Material alert dialog with an OK button.
  static Future<void> alert(
    BuildContext context,
    String title,
    String message,
  ) {
    return showDialog(
      context: context,
      builder: (c) => Theme(
        data: _theme(c),
        child: AlertDialog(
          title: Text(title),
          content: Text(message),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(c),
              child: const Text('OK'),
            ),
          ],
        ),
      ),
    );
  }

  /// Shows a Material confirmation dialog.
  /// Returns `true` if the user confirmed, `false` (or null) otherwise.
  static Future<bool?> confirm(
    BuildContext context, {
    required String title,
    required String message,
    String confirmText = 'Confirm',
    String cancelText = 'Cancel',
    bool destructive = false,
  }) {
    return showDialog<bool>(
      context: context,
      builder: (c) => Theme(
        data: _theme(c),
        child: AlertDialog(
          title: Text(title),
          content: Text(message),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(c, false),
              child: Text(cancelText),
            ),
            FilledButton(
              onPressed: () => Navigator.pop(c, true),
              style: destructive
                  ? FilledButton.styleFrom(
                      backgroundColor: _theme(c).colorScheme.error,
                    )
                  : null,
              child: Text(confirmText),
            ),
          ],
        ),
      ),
    );
  }

  /// Shows a Material bottom sheet (replaces iOS-style bottom sheets).
  static Future<T?> showSheet<T>(
    BuildContext context,
    WidgetBuilder builder, {
    bool isScrollControlled = true,
  }) {
    return showModalBottomSheet<T>(
      context: context,
      isScrollControlled: isScrollControlled,
      backgroundColor: AppColors.surface,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (c) => Theme(
        data: _theme(c),
        child: Material(
          color: AppColors.surface,
          borderRadius: const BorderRadius.vertical(top: Radius.circular(20)),
          child: builder(c),
        ),
      ),
    );
  }

  /// Shows a Material action picker (replaces CupertinoActionSheet).
  /// [options] is a list of (label, value) pairs. Returns the selected value.
  static Future<T?> showOptions<T>(
    BuildContext context, {
    String? title,
    required List<(String, T)> options,
    String? cancelText,
  }) {
    return showModalBottomSheet<T>(
      context: context,
      backgroundColor: AppColors.surface,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (c) => Theme(
        data: _theme(c),
        child: Material(
          color: AppColors.surface,
          borderRadius: const BorderRadius.vertical(top: Radius.circular(20)),
          child: SafeArea(
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                if (title != null)
                  Padding(
                    padding: const EdgeInsets.fromLTRB(24, 20, 24, 8),
                    child: Text(
                      title,
                      style: const TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.w600,
                        color: AppColors.label,
                      ),
                    ),
                  ),
                ...options.map((o) {
                  return ListTile(
                    title: Text(o.$1),
                    onTap: () => Navigator.pop(c, o.$2),
                  );
                }),
                if (cancelText != null) ...[
                  const Divider(height: 1),
                  ListTile(
                    title: Center(
                      child: Text(cancelText,
                          style: const TextStyle(
                            color: AppColors.danger,
                          )),
                    ),
                    onTap: () => Navigator.pop(c),
                  ),
                ],
                const SizedBox(height: 8),
              ],
            ),
          ),
        ),
      ),
    );
  }

  /// Shows a Material date picker (replaces CupertinoDatePicker).
  static Future<DateTime?> pickDate(
    BuildContext context, {
    DateTime? initialDate,
    DateTime? firstDate,
    DateTime? lastDate,
  }) {
    final now = DateTime.now();
    return showDatePicker(
      context: context,
      initialDate: initialDate ?? now,
      firstDate: firstDate ?? DateTime(2000),
      lastDate: lastDate ?? DateTime(now.year + 5),
      builder: (c, child) => Theme(
        data: _theme(c),
        child: child!,
      ),
    );
  }
}
