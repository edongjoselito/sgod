import 'package:flutter/cupertino.dart';

import 'app_colors.dart';

/// iOS-style Cupertino theme for the SGOD mobile app.
class AppTheme {
  AppTheme._();

  static const String fontFamily = 'InstrumentSans';

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
        actionTextStyle: TextStyle(
          fontFamily: fontFamily,
          fontSize: 17,
          fontWeight: FontWeight.w400,
          color: AppColors.primary,
        ),
      ),
    );
  }
}
