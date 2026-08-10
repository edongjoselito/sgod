import 'package:flutter/cupertino.dart';
import 'package:intl/intl.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../core/theme/app_colors.dart';

/// Design tokens and shared widgets for the Brigada Eskwela module.
///
/// Built on the app's existing Cupertino system (AppColors + Instrument Sans)
/// so the module reads as part of the same app, with one module accent —
/// indigo — echoing the Brigada section's identity on the web.
class BrigadaTokens {
  BrigadaTokens._();

  static const Color accent = CupertinoColors.systemIndigo;

  /// Preparedness ratings, in scale order.
  static const Color fully = CupertinoColors.systemGreen;
  static const Color partially = CupertinoColors.systemOrange;
  static const Color notPrepared = CupertinoColors.systemRed;
  static const Color unanswered = CupertinoColors.systemGrey3;

  /// Accents for the summary stat tiles, in display order.
  static const Color records = CupertinoColors.systemBlue;
  static const Color resources = CupertinoColors.systemGreen;
  static const Color volunteers = CupertinoColors.systemOrange;
  static const Color days = CupertinoColors.systemPurple;

  static const double radius = 14;
  static const EdgeInsets pagePadding =
      EdgeInsets.symmetric(horizontal: 16, vertical: 8);

  /// Colour for a checklist rating (1/2/3), grey when unanswered.
  static Color forRating(int? value) {
    switch (value) {
      case 1:
        return fully;
      case 2:
        return partially;
      case 3:
        return notPrepared;
      default:
        return unanswered;
    }
  }

  static String labelForRating(int? value) {
    switch (value) {
      case 1:
        return 'Fully Prepared';
      case 2:
        return 'Partially Prepared';
      case 3:
        return 'Not Prepared';
      default:
        return 'No answer';
    }
  }
}

// ── Formatting ───────────────────────────────────────────────────────────────

final _plain = NumberFormat.decimalPattern();
final _peso = NumberFormat.currency(symbol: '₱', decimalDigits: 0);
final _pesoExact = NumberFormat.currency(symbol: '₱', decimalDigits: 2);

/// Thousands-separated integer.
String formatCount(num value) => _plain.format(value);

/// Peso amount, rounded to whole pesos.
String formatPeso(num value) => _peso.format(value);

/// Peso amount with centavos — used where an exact figure matters.
String formatPesoExact(num value) => _pesoExact.format(value);

/// Large numbers shortened for stat tiles (₱11.2M, 584.9K).
String formatCompact(num value, {bool peso = false}) {
  final prefix = peso ? '₱' : '';
  final abs = value.abs();
  if (abs >= 1000000000) {
    return '$prefix${(value / 1000000000).toStringAsFixed(1)}B';
  }
  if (abs >= 1000000) return '$prefix${(value / 1000000).toStringAsFixed(1)}M';
  if (abs >= 10000) return '$prefix${(value / 1000).toStringAsFixed(1)}K';
  return '$prefix${_plain.format(value)}';
}

/// `2026-06-22` → `Jun 22, 2026`; unparseable values pass through.
String formatDate(String raw) {
  final parsed = DateTime.tryParse(raw);
  if (parsed == null) return raw;
  return DateFormat('MMM d, y').format(parsed);
}

/// `2026-06-22` → `Jun 22`, for dense date rows.
String formatShortDate(String raw) {
  final parsed = DateTime.tryParse(raw);
  if (parsed == null) return raw;
  return DateFormat('MMM d').format(parsed);
}

/// Human "time ago" used by every freshness label.
String formatRelative(DateTime? time) {
  if (time == null) return 'never';
  final diff = DateTime.now().difference(time);
  if (diff.inSeconds < 45) return 'just now';
  if (diff.inMinutes < 60) {
    return '${diff.inMinutes} min ago';
  }
  if (diff.inHours < 24) {
    return '${diff.inHours} hour${diff.inHours == 1 ? '' : 's'} ago';
  }
  if (diff.inDays < 7) {
    return '${diff.inDays} day${diff.inDays == 1 ? '' : 's'} ago';
  }
  return DateFormat('MMM d, y').format(time);
}

/// Byte count for the offline-storage row.
String formatBytes(int bytes) {
  if (bytes < 1024) return '$bytes B';
  if (bytes < 1024 * 1024) return '${(bytes / 1024).toStringAsFixed(0)} KB';
  return '${(bytes / (1024 * 1024)).toStringAsFixed(1)} MB';
}

// ── Building blocks ──────────────────────────────────────────────────────────

/// Rounded surface container — the module's base card.
class BrigadaCard extends StatelessWidget {
  const BrigadaCard({
    super.key,
    required this.child,
    this.padding = const EdgeInsets.all(16),
    this.margin,
    this.onTap,
    this.color,
  });

  final Widget child;
  final EdgeInsetsGeometry padding;
  final EdgeInsetsGeometry? margin;
  final VoidCallback? onTap;
  final Color? color;

  @override
  Widget build(BuildContext context) {
    final content = Padding(padding: padding, child: child);
    return Container(
      margin: margin,
      decoration: BoxDecoration(
        color: color ?? AppColors.surface,
        borderRadius: BorderRadius.circular(BrigadaTokens.radius),
      ),
      clipBehavior: Clip.antiAlias,
      child: onTap == null
          ? content
          : CupertinoButton(
              padding: EdgeInsets.zero,
              onPressed: onTap,
              borderRadius: BorderRadius.circular(BrigadaTokens.radius),
              child: content,
            ),
    );
  }
}

/// Small uppercase heading above a group of cards.
class BrigadaSectionHeader extends StatelessWidget {
  const BrigadaSectionHeader(this.title, {super.key, this.trailing});

  final String title;
  final Widget? trailing;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(4, 20, 4, 8),
      child: Row(
        children: [
          Expanded(
            child: Text(
              title.toUpperCase(),
              style: const TextStyle(
                fontSize: 12,
                fontWeight: FontWeight.w700,
                letterSpacing: 0.6,
                color: AppColors.secondaryLabel,
              ),
            ),
          ),
          ?trailing,
        ],
      ),
    );
  }
}

/// Coloured status chip.
class BrigadaPill extends StatelessWidget {
  const BrigadaPill({
    super.key,
    required this.label,
    required this.color,
    this.icon,
    this.compact = false,
  });

  final String label;
  final Color color;
  final IconData? icon;
  final bool compact;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: compact ? 8 : 10,
        vertical: compact ? 3 : 5,
      ),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.13),
        borderRadius: BorderRadius.circular(999),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          if (icon != null) ...[
            Icon(icon, size: compact ? 11 : 13, color: color),
            const SizedBox(width: 4),
          ],
          Text(
            label,
            style: TextStyle(
              fontSize: compact ? 11 : 12.5,
              fontWeight: FontWeight.w600,
              color: color,
            ),
          ),
        ],
      ),
    );
  }
}

/// Headline number tile used across the summary and preparedness screens.
class BrigadaStatTile extends StatelessWidget {
  const BrigadaStatTile({
    super.key,
    required this.value,
    required this.label,
    required this.icon,
    required this.accent,
    this.onTap,
  });

  final String value;
  final String label;
  final IconData icon;
  final Color accent;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    return BrigadaCard(
      onTap: onTap,
      padding: const EdgeInsets.all(14),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        mainAxisSize: MainAxisSize.min,
        children: [
          Row(
            children: [
              Container(
                width: 30,
                height: 30,
                decoration: BoxDecoration(
                  color: accent.withValues(alpha: 0.13),
                  borderRadius: BorderRadius.circular(9),
                ),
                child: Icon(icon, size: 16, color: accent),
              ),
              const Spacer(),
              if (onTap != null)
                const Icon(CupertinoIcons.chevron_right,
                    size: 13, color: AppColors.tertiaryLabel),
            ],
          ),
          const SizedBox(height: 10),
          FittedBox(
            fit: BoxFit.scaleDown,
            alignment: Alignment.centerLeft,
            child: Text(
              value,
              maxLines: 1,
              style: const TextStyle(
                fontSize: 24,
                fontWeight: FontWeight.w700,
                letterSpacing: -0.5,
                color: AppColors.label,
              ),
            ),
          ),
          const SizedBox(height: 2),
          Text(
            label,
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
            style: const TextStyle(
              fontSize: 12.5,
              color: AppColors.secondaryLabel,
            ),
          ),
        ],
      ),
    );
  }
}

/// Two-column tile grid — the mobile answer to the web's stat-card row.
class BrigadaTileGrid extends StatelessWidget {
  const BrigadaTileGrid({super.key, required this.tiles, this.spacing = 10});

  final List<Widget> tiles;
  final double spacing;

  @override
  Widget build(BuildContext context) {
    final rows = <Widget>[];
    for (var i = 0; i < tiles.length; i += 2) {
      final left = tiles[i];
      final right = i + 1 < tiles.length ? tiles[i + 1] : null;
      rows.add(
        IntrinsicHeight(
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              Expanded(child: left),
              SizedBox(width: spacing),
              Expanded(child: right ?? const SizedBox.shrink()),
            ],
          ),
        ),
      );
      if (i + 2 < tiles.length) rows.add(SizedBox(height: spacing));
    }
    return Column(children: rows);
  }
}

/// One segment of a [BrigadaStackedBar].
class BrigadaSegment {
  const BrigadaSegment(this.value, this.color, this.label);
  final int value;
  final Color color;
  final String label;
}

/// Proportional bar showing how a total splits across categories —
/// the mobile stand-in for the web report's three count columns.
class BrigadaStackedBar extends StatelessWidget {
  const BrigadaStackedBar({
    super.key,
    required this.segments,
    this.height = 8,
    this.showLegend = false,
  });

  final List<BrigadaSegment> segments;
  final double height;
  final bool showLegend;

  @override
  Widget build(BuildContext context) {
    final total = segments.fold<int>(0, (sum, s) => sum + s.value);
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        ClipRRect(
          borderRadius: BorderRadius.circular(height / 2),
          child: SizedBox(
            height: height,
            child: total == 0
                ? Container(color: AppColors.separator.withValues(alpha: 0.4))
                : Row(
                    children: [
                      for (final segment in segments)
                        if (segment.value > 0)
                          Expanded(
                            flex: segment.value,
                            child: Container(color: segment.color),
                          ),
                    ],
                  ),
          ),
        ),
        if (showLegend) ...[
          const SizedBox(height: 10),
          Wrap(
            spacing: 14,
            runSpacing: 6,
            children: [
              for (final segment in segments)
                Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Container(
                      width: 8,
                      height: 8,
                      decoration: BoxDecoration(
                        color: segment.color,
                        shape: BoxShape.circle,
                      ),
                    ),
                    const SizedBox(width: 6),
                    Text(
                      '${segment.label} ${formatCount(segment.value)}',
                      style: const TextStyle(
                        fontSize: 12.5,
                        color: AppColors.secondaryLabel,
                      ),
                    ),
                  ],
                ),
            ],
          ),
        ],
      ],
    );
  }
}

/// Labelled 0..1 progress bar (survey averages, submission rates).
class BrigadaMeter extends StatelessWidget {
  const BrigadaMeter({
    super.key,
    required this.fraction,
    required this.color,
    this.height = 6,
  });

  final double fraction;
  final Color color;
  final double height;

  @override
  Widget build(BuildContext context) {
    return ClipRRect(
      borderRadius: BorderRadius.circular(height / 2),
      child: SizedBox(
        height: height,
        child: Stack(
          children: [
            Container(color: AppColors.separator.withValues(alpha: 0.35)),
            FractionallySizedBox(
              widthFactor: fraction.clamp(0.0, 1.0),
              child: Container(color: color),
            ),
          ],
        ),
      ),
    );
  }
}

/// Freshness / connectivity strip shown at the top of every Brigada screen.
class BrigadaFreshnessBar extends StatelessWidget {
  const BrigadaFreshnessBar({
    super.key,
    required this.isOnline,
    required this.fromCache,
    required this.fetchedAt,
    this.refreshError,
    this.onRetry,
  });

  final bool isOnline;
  final bool fromCache;
  final DateTime? fetchedAt;
  final Object? refreshError;
  final VoidCallback? onRetry;

  @override
  Widget build(BuildContext context) {
    // Live and healthy — no strip at all.
    if (isOnline && !fromCache && refreshError == null) {
      return const SizedBox.shrink();
    }

    final Color color;
    final IconData icon;
    final String message;

    if (!isOnline) {
      color = AppColors.warning;
      icon = PhosphorIconsRegular.wifiSlash;
      message = 'Offline — showing data saved ${formatRelative(fetchedAt)}';
    } else if (refreshError != null) {
      color = AppColors.danger;
      icon = PhosphorIconsRegular.warningCircle;
      message = "Couldn't refresh — showing data from ${formatRelative(fetchedAt)}";
    } else {
      color = AppColors.info;
      icon = PhosphorIconsRegular.clockCounterClockwise;
      message = 'Saved copy from ${formatRelative(fetchedAt)}';
    }

    return Container(
      width: double.infinity,
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 9),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.11),
        borderRadius: BorderRadius.circular(10),
      ),
      child: Row(
        children: [
          Icon(icon, size: 15, color: color),
          const SizedBox(width: 8),
          Expanded(
            child: Text(
              message,
              style: TextStyle(
                fontSize: 12.5,
                fontWeight: FontWeight.w500,
                color: color,
              ),
            ),
          ),
          if (onRetry != null && isOnline)
            GestureDetector(
              onTap: onRetry,
              child: Text(
                'Retry',
                style: TextStyle(
                  fontSize: 12.5,
                  fontWeight: FontWeight.w700,
                  color: color,
                ),
              ),
            ),
        ],
      ),
    );
  }
}

/// Centred empty state.
class BrigadaEmpty extends StatelessWidget {
  const BrigadaEmpty({
    super.key,
    required this.icon,
    required this.title,
    this.message,
    this.actionLabel,
    this.onAction,
  });

  final IconData icon;
  final String title;
  final String? message;
  final String? actionLabel;
  final VoidCallback? onAction;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 32, vertical: 56),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            width: 62,
            height: 62,
            decoration: BoxDecoration(
              color: BrigadaTokens.accent.withValues(alpha: 0.11),
              borderRadius: BorderRadius.circular(18),
            ),
            child: Icon(icon, size: 27, color: BrigadaTokens.accent),
          ),
          const SizedBox(height: 18),
          Text(
            title,
            textAlign: TextAlign.center,
            style: const TextStyle(
              fontSize: 17,
              fontWeight: FontWeight.w600,
              color: AppColors.label,
            ),
          ),
          if (message != null) ...[
            const SizedBox(height: 6),
            Text(
              message!,
              textAlign: TextAlign.center,
              style: const TextStyle(
                fontSize: 14.5,
                height: 1.35,
                color: AppColors.secondaryLabel,
              ),
            ),
          ],
          if (actionLabel != null && onAction != null) ...[
            const SizedBox(height: 18),
            CupertinoButton.filled(
              padding: const EdgeInsets.symmetric(horizontal: 22, vertical: 11),
              borderRadius: BorderRadius.circular(11),
              onPressed: onAction,
              child: Text(actionLabel!, style: const TextStyle(fontSize: 15)),
            ),
          ],
        ],
      ),
    );
  }
}

/// iOS-style search field with the module's spacing.
class BrigadaSearchField extends StatelessWidget {
  const BrigadaSearchField({
    super.key,
    required this.placeholder,
    required this.onChanged,
    this.controller,
  });

  final String placeholder;
  final ValueChanged<String> onChanged;
  final TextEditingController? controller;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: CupertinoSearchTextField(
        controller: controller,
        placeholder: placeholder,
        onChanged: onChanged,
        style: const TextStyle(fontSize: 16, color: AppColors.label),
      ),
    );
  }
}

/// Row with a label on the left and a value on the right.
class BrigadaDetailRow extends StatelessWidget {
  const BrigadaDetailRow({
    super.key,
    required this.label,
    required this.value,
    this.valueColor,
  });

  final String label;
  final String value;
  final Color? valueColor;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 5),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 116,
            child: Text(
              label,
              style: const TextStyle(
                fontSize: 13.5,
                color: AppColors.secondaryLabel,
              ),
            ),
          ),
          Expanded(
            child: Text(
              value.isEmpty ? '—' : value,
              style: TextStyle(
                fontSize: 14.5,
                fontWeight: FontWeight.w500,
                color: valueColor ?? AppColors.label,
              ),
            ),
          ),
        ],
      ),
    );
  }
}

// ── Sheets ───────────────────────────────────────────────────────────────────

/// Modal wheel picker. Returns the chosen index, or null if dismissed.
Future<int?> brigadaPickIndex(
  BuildContext context, {
  required String title,
  required List<String> options,
  required int initialIndex,
}) async {
  if (options.isEmpty) return null;
  var selected = initialIndex.clamp(0, options.length - 1);

  return showCupertinoModalPopup<int>(
    context: context,
    builder: (sheetContext) => Container(
      height: 300,
      decoration: const BoxDecoration(
        color: AppColors.secondaryBackground,
        borderRadius: BorderRadius.vertical(top: Radius.circular(14)),
      ),
      child: SafeArea(
        top: false,
        child: Column(
          children: [
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 6),
              decoration: const BoxDecoration(
                border: Border(
                  bottom:
                      BorderSide(color: AppColors.separator, width: 0.5),
                ),
              ),
              child: Row(
                children: [
                  CupertinoButton(
                    padding: const EdgeInsets.symmetric(horizontal: 12),
                    onPressed: () => Navigator.of(sheetContext).pop(),
                    child: const Text('Cancel'),
                  ),
                  Expanded(
                    child: Text(
                      title,
                      textAlign: TextAlign.center,
                      style: const TextStyle(
                        fontSize: 15,
                        fontWeight: FontWeight.w600,
                        color: AppColors.label,
                      ),
                    ),
                  ),
                  CupertinoButton(
                    padding: const EdgeInsets.symmetric(horizontal: 12),
                    onPressed: () => Navigator.of(sheetContext).pop(selected),
                    child: const Text('Done',
                        style: TextStyle(fontWeight: FontWeight.w600)),
                  ),
                ],
              ),
            ),
            Expanded(
              child: CupertinoPicker(
                magnification: 1.1,
                squeeze: 1.15,
                itemExtent: 38,
                scrollController:
                    FixedExtentScrollController(initialItem: selected),
                onSelectedItemChanged: (i) => selected = i,
                children: [
                  for (final option in options)
                    Center(
                      child: Padding(
                        padding: const EdgeInsets.symmetric(horizontal: 16),
                        child: Text(
                          option,
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: const TextStyle(fontSize: 18),
                        ),
                      ),
                    ),
                ],
              ),
            ),
          ],
        ),
      ),
    ),
  );
}

/// Compact nav-bar button that opens a picker — used for SY and period.
class BrigadaFilterButton extends StatelessWidget {
  const BrigadaFilterButton({
    super.key,
    required this.label,
    required this.onPressed,
  });

  final String label;
  final VoidCallback onPressed;

  @override
  Widget build(BuildContext context) {
    return CupertinoButton(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
      minimumSize: Size.zero,
      onPressed: onPressed,
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Text(
            label,
            style: const TextStyle(
              fontSize: 15,
              fontWeight: FontWeight.w600,
              color: BrigadaTokens.accent,
            ),
          ),
          const SizedBox(width: 3),
          const Icon(CupertinoIcons.chevron_down,
              size: 12, color: BrigadaTokens.accent),
        ],
      ),
    );
  }
}
