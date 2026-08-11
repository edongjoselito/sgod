import 'package:flutter/cupertino.dart';

import '../theme/app_colors.dart';

/// iOS-style squircle button — filled blue, white label.
/// Mimics CupertinoButton.filled with a slightly squircle radius.
class PrimaryButton extends StatelessWidget {
  const PrimaryButton({
    super.key,
    required this.label,
    this.onPressed,
    this.icon,
    this.loading = false,
    this.expanded = true,
    this.color,
  });

  final String label;
  final VoidCallback? onPressed;
  final IconData? icon;
  final bool loading;
  final bool expanded;
  final Color? color;

  @override
  Widget build(BuildContext context) {
    final disabled = onPressed == null || loading;
    final bg = color ?? AppColors.primary;

    final child = loading
        ? const CupertinoActivityIndicator(color: CupertinoColors.white)
        : Row(
            mainAxisSize: MainAxisSize.min,
            children: [
              if (icon != null) ...[
                Icon(icon, size: 18, color: CupertinoColors.white),
                const SizedBox(width: 8),
              ],
              Text(
                label,
                style: const TextStyle(
                  fontSize: 17,
                  fontWeight: FontWeight.w600,
                  color: CupertinoColors.white,
                ),
              ),
            ],
          );

    if (expanded) {
      return SizedBox(
        width: double.infinity,
        child: CupertinoButton(
          onPressed: disabled ? null : onPressed,
          color: disabled ? bg.withValues(alpha: 0.4) : bg,
          borderRadius: BorderRadius.circular(14),
          minimumSize: const Size(0, 50),
          padding: const EdgeInsets.symmetric(horizontal: 24),
          child: child,
        ),
      );
    }
    return CupertinoButton(
      onPressed: disabled ? null : onPressed,
      color: disabled ? bg.withValues(alpha: 0.4) : bg,
      borderRadius: BorderRadius.circular(14),
      minimumSize: const Size(0, 50),
      padding: const EdgeInsets.symmetric(horizontal: 24),
      child: child,
    );
  }
}

/// iOS-style secondary button — plain text, no fill.
class SecondaryButton extends StatelessWidget {
  const SecondaryButton({
    super.key,
    required this.label,
    this.onPressed,
    this.icon,
    this.expanded = true,
  });

  final String label;
  final VoidCallback? onPressed;
  final IconData? icon;
  final bool expanded;

  @override
  Widget build(BuildContext context) {
    return CupertinoButton(
      onPressed: onPressed,
      borderRadius: BorderRadius.circular(14),
      minimumSize: const Size(0, 50),
      padding: const EdgeInsets.symmetric(horizontal: 24),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          if (icon != null) ...[
            Icon(icon, size: 18),
            const SizedBox(width: 8),
          ],
          Text(label),
        ],
      ),
    );
  }
}
