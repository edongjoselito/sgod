import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../../core/theme/app_colors.dart';

/// Placeholder page for features that don't have API endpoints yet.
/// Shows a clean "coming soon" state with the feature name and icon.
class PlaceholderView extends StatelessWidget {
  const PlaceholderView({
    super.key,
    required this.title,
    this.subtitle,
    required this.icon,
    this.onMenuTap,
  });

  final String title;
  final String? subtitle;
  final IconData icon;
  final VoidCallback? onMenuTap;

  @override
  Widget build(BuildContext context) {
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: CupertinoNavigationBar(
        middle: Text(title),
        backgroundColor: AppColors.surface,
        border: const Border(
          bottom: BorderSide(color: AppColors.separator, width: 0.5),
        ),
        leading: onMenuTap != null
            ? CupertinoButton(
                padding: EdgeInsets.zero,
                onPressed: onMenuTap,
                child: const Icon(CupertinoIcons.line_horizontal_3, size: 22),
              )
            : null,
      ),
      child: SafeArea(
        child: Center(
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 32),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Container(
                  width: 72,
                  height: 72,
                  decoration: BoxDecoration(
                    color: AppColors.primary.withOpacity(0.1),
                    borderRadius: BorderRadius.circular(18),
                  ),
                  child: Icon(
                    icon,
                    size: 32,
                    color: AppColors.primary,
                  ),
                ),
                const SizedBox(height: 20),
                Text(
                  title,
                  style: const TextStyle(
                    fontSize: 20,
                    fontWeight: FontWeight.w700,
                    color: AppColors.label,
                  ),
                  textAlign: TextAlign.center,
                ),
                if (subtitle != null) ...[
                  const SizedBox(height: 6),
                  Text(
                    subtitle!,
                    style: const TextStyle(
                      fontSize: 14,
                      color: AppColors.secondaryLabel,
                    ),
                    textAlign: TextAlign.center,
                  ),
                ],
                const SizedBox(height: 16),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                  decoration: BoxDecoration(
                    color: AppColors.warning.withOpacity(0.12),
                    borderRadius: BorderRadius.circular(100),
                  ),
                  child: const Text(
                    'Coming Soon',
                    style: TextStyle(
                      fontSize: 12,
                      fontWeight: FontWeight.w600,
                      color: AppColors.warning,
                    ),
                  ),
                ),
                const SizedBox(height: 12),
                const Text(
                  'This feature will be available in a future update.',
                  style: TextStyle(
                    fontSize: 13,
                    color: AppColors.tertiaryLabel,
                  ),
                  textAlign: TextAlign.center,
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
