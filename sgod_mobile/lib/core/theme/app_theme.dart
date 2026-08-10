import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';

import 'app_colors.dart';

/// Theme for the e-Brigada mobile app.
class AppTheme {
  AppTheme._();

  static const String fontFamily = 'InstrumentSans';

  /// Material theme — needed so Material dialogs (AlertDialog,
  /// showModalBottomSheet, showDatePicker, etc.) render correctly.
  static ThemeData buildMaterialTheme() {
    return ThemeData(
      useMaterial3: true,
      fontFamily: fontFamily,
      colorScheme: ColorScheme.light(
        primary: AppColors.primary,
        onPrimary: AppColors.surface,
        secondary: AppColors.info,
        error: AppColors.danger,
        surface: AppColors.surface,
        onSurface: AppColors.label,
      ),
      scaffoldBackgroundColor: AppColors.background,
      appBarTheme: AppBarTheme(
        backgroundColor: AppColors.surface,
        foregroundColor: AppColors.label,
        elevation: 0,
      ),
      dialogBackgroundColor: AppColors.surface,
      dialogTheme: DialogThemeData(
        backgroundColor: AppColors.surface,
        titleTextStyle: TextStyle(
          fontFamily: fontFamily,
          fontSize: 18,
          fontWeight: FontWeight.w700,
          color: AppColors.label,
        ),
        contentTextStyle: TextStyle(
          fontFamily: fontFamily,
          fontSize: 14,
          color: AppColors.label,
        ),
      ),
      bottomSheetTheme: BottomSheetThemeData(
        backgroundColor: AppColors.surface,
        modalBackgroundColor: AppColors.surface,
        shape: const RoundedRectangleBorder(
          borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
        ),
      ),
      textButtonTheme: TextButtonThemeData(
        style: TextButton.styleFrom(
          foregroundColor: AppColors.primary,
        ),
      ),
      filledButtonTheme: FilledButtonThemeData(
        style: FilledButton.styleFrom(
          backgroundColor: AppColors.primary,
          foregroundColor: AppColors.surface,
        ),
      ),
    );
  }

  static CupertinoThemeData build() {
    return CupertinoThemeData(
      primaryColor: AppColors.primary,
      scaffoldBackgroundColor: AppColors.background,
      barBackgroundColor: AppColors.surface,
      textTheme: CupertinoTextThemeData(
        primaryColor: AppColors.label,
        textStyle: TextStyle(
          fontFamily: fontFamily,
          fontSize: 17,
          color: AppColors.label,
        ),
        actionTextStyle: TextStyle(
          fontFamily: fontFamily,
          fontSize: 17,
          fontWeight: FontWeight.w400,
          color: AppColors.primary,
        ),
        tabLabelTextStyle: TextStyle(
          fontFamily: fontFamily,
          fontSize: 10,
          color: AppColors.label,
        ),
        navTitleTextStyle: TextStyle(
          fontFamily: fontFamily,
          fontSize: 17,
          fontWeight: FontWeight.w600,
          color: AppColors.label,
        ),
        navLargeTitleTextStyle: TextStyle(
          fontFamily: fontFamily,
          fontSize: 34,
          fontWeight: FontWeight.w700,
          color: AppColors.label,
        ),
        navActionTextStyle: TextStyle(
          fontFamily: fontFamily,
          fontSize: 17,
          fontWeight: FontWeight.w400,
          color: AppColors.primary,
        ),
        pickerTextStyle: TextStyle(
          fontFamily: fontFamily,
          fontSize: 21,
          color: AppColors.label,
        ),
        dateTimePickerTextStyle: TextStyle(
          fontFamily: fontFamily,
          fontSize: 21,
          fontWeight: FontWeight.w400,
          color: AppColors.label,
        ),
      ),
    );
  }
}
