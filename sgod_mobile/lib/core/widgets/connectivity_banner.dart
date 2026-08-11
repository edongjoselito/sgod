import 'package:flutter/cupertino.dart';

import '../theme/app_colors.dart';

/// iOS-style connectivity banner — shows when device is offline,
/// syncing, or has pending changes.
class ConnectivityBanner extends StatelessWidget {
  const ConnectivityBanner({
    super.key,
    required this.isOnline,
    this.isSyncing = false,
    this.pendingCount = 0,
    this.lastSync,
  });

  final bool isOnline;
  final bool isSyncing;
  final int pendingCount;
  final DateTime? lastSync;

  @override
  Widget build(BuildContext context) {
    if (isOnline && !isSyncing && pendingCount == 0) {
      return const SizedBox.shrink();
    }

    final message = !isOnline
        ? pendingCount > 0
            ? 'Offline — $pendingCount change${pendingCount == 1 ? '' : 's'} queued'
            : 'Offline — showing cached data'
        : isSyncing
            ? 'Syncing...'
            : '$pendingCount pending change${pendingCount == 1 ? '' : 's'}';

    final color = !isOnline ? AppColors.warning : AppColors.info;

    return Container(
      color: color.withValues(alpha: 0.12),
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      child: Row(
        children: [
          Icon(
            !isOnline
                ? CupertinoIcons.wifi_slash
                : isSyncing
                    ? CupertinoIcons.arrow_2_circlepath
                    : CupertinoIcons.clock,
            size: 14,
            color: color,
          ),
          const SizedBox(width: 8),
          Expanded(
            child: Text(
              message,
              style: TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w500,
                color: color,
              ),
            ),
          ),
        ],
      ),
    );
  }
}
