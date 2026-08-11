import 'package:flutter/cupertino.dart';
import 'package:local_auth/local_auth.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';
import 'package:provider/provider.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../core/widgets/app_dialogs.dart';
import '../../../core/di.dart';
import '../../auth/view_models/auth_view_model.dart';

/// iOS-style settings screen — grouped inset list sections.
class SettingsView extends StatefulWidget {
  const SettingsView({super.key});

  @override
  State<SettingsView> createState() => _SettingsViewState();
}

class _SettingsViewState extends State<SettingsView> {
  final LocalAuthentication _localAuth = LocalAuthentication();
  bool _biometricEnabled = false;
  bool _biometricAvailable = false;
  bool _isSyncing = false;
  int _pendingCount = 0;
  DateTime? _lastSync;
  bool _isOnline = true;
  List<dynamic> _conflicted = [];
  List<dynamic> _failed = [];

  @override
  void initState() {
    super.initState();
    _loadBiometricState();
    _refreshSyncState();
    DI.sync.addListener(_onSyncChanged);
    _isOnline = DI.connectivity.isOnline;
    WidgetsBinding.instance.addPostFrameCallback((_) => DI.sync.loadIssues());
  }

  @override
  void dispose() {
    DI.sync.removeListener(_onSyncChanged);
    super.dispose();
  }

  void _onSyncChanged() {
    if (!mounted) return;
    _refreshSyncState();
  }

  void _refreshSyncState() {
    setState(() {
      _isSyncing = DI.sync.syncing;
      _pendingCount = DI.sync.pendingCount;
      _lastSync = DI.sync.lastSync;
      _isOnline = DI.connectivity.isOnline;
      _conflicted = DI.sync.conflicted;
      _failed = DI.sync.failed;
    });
  }

  Future<void> _loadBiometricState() async {
    final enabled = await DI.storage.isBiometricEnabled();
    bool available = false;
    try {
      available = await _localAuth.canCheckBiometrics &&
          await _localAuth.isDeviceSupported();
    } catch (e) {
      debugPrint('Biometric availability check failed: $e');
    }
    if (mounted) {
      setState(() {
        _biometricEnabled = enabled;
        _biometricAvailable = available;
      });
    }
  }

  Future<void> _toggleBiometric() async {
    if (!_biometricAvailable) {
      AppDialogs.alert(context, 'Biometric Login',
          'Biometric authentication is not available on this device.');
      return;
    }
    if (_biometricEnabled) {
      // Turning off — no auth prompt needed, just clear the flag.
      await DI.storage.setBiometricEnabled(false);
      setState(() => _biometricEnabled = false);
      return;
    }
    // Turning on — authenticate once to confirm the user's identity.
    try {
      final ok = await _localAuth.authenticate(
        localizedReason: 'Authenticate to enable biometric login',
        options: const AuthenticationOptions(
          stickyAuth: true,
          biometricOnly: true,
        ),
      );
      if (ok) {
        await DI.storage.setBiometricEnabled(true);
        if (mounted) setState(() => _biometricEnabled = true);
      }
    } catch (e) {
      if (mounted) {
        AppDialogs.alert(context, 'Biometric Login',
            'Authentication failed: $e');
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: const CupertinoNavigationBar(
        middle: Text('Settings'),
        backgroundColor: AppColors.surface,
        border: Border(bottom: BorderSide(color: AppColors.separator, width: 0.5)),
      ),
      child: SafeArea(
        child: ListView(
          children: [
            const SizedBox(height: 24),

            // ── Sync ───────────────────────────────────────────────────
            CupertinoListSection.insetGrouped(
              header: const Text('Offline Sync'),
              children: [
                CupertinoListTile.notched(
                  leading: Icon(
                    _isOnline
                        ? PhosphorIconsRegular.wifiHigh
                        : PhosphorIconsRegular.wifiSlash,
                    color: _isOnline ? AppColors.success : AppColors.warning,
                    size: 22,
                  ),
                  title: const Text('Connection'),
                  trailing: Text(
                    _isOnline ? 'Online' : 'Offline',
                    style: TextStyle(
                      color: _isOnline
                          ? AppColors.success
                          : AppColors.warning,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                ),
                CupertinoListTile.notched(
                  leading: Icon(
                    _pendingCount > 0
                        ? PhosphorIconsRegular.clockClockwise
                        : PhosphorIconsRegular.checkCircle,
                    color: _pendingCount > 0
                        ? AppColors.warning
                        : AppColors.success,
                    size: 22,
                  ),
                  title: const Text('Pending Changes'),
                  trailing: Text(
                    _pendingCount == 0
                        ? 'None'
                        : '$_pendingCount queued',
                    style: TextStyle(
                      color: _pendingCount > 0
                          ? AppColors.warning
                          : AppColors.secondaryLabel,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                ),
                CupertinoListTile.notched(
                  leading: Icon(
                    PhosphorIconsRegular.clock,
                    color: AppColors.tertiaryLabel,
                    size: 22,
                  ),
                  title: const Text('Last Synced'),
                  trailing: Text(
                    _lastSync == null
                        ? 'Never'
                        : _formatTime(_lastSync!),
                    style: const TextStyle(
                      color: AppColors.secondaryLabel,
                    ),
                  ),
                ),
                CupertinoListTile.notched(
                  leading: _isSyncing
                      ? const CupertinoActivityIndicator(radius: 10)
                      : Icon(
                          PhosphorIconsRegular.arrowsClockwise,
                          color: AppColors.primary,
                          size: 22,
                        ),
                  title: const Text('Sync Now'),
                  trailing: const Icon(CupertinoIcons.chevron_right,
                      size: 18, color: AppColors.tertiaryLabel),
                  onTap: _isSyncing
                      ? null
                      : () async {
                          await DI.sync.sync();
                          if (mounted) _refreshSyncState();
                        },
                ),
              ],
            ),

            // ── Sync Issues (conflicts + failures) ─────────────────────
            if (_conflicted.isNotEmpty || _failed.isNotEmpty) ...[
              const SizedBox(height: 24),
              CupertinoListSection.insetGrouped(
                header: const Text('Sync Issues'),
                children: [
                  for (final entry in _conflicted)
                    CupertinoListTile.notched(
                      leading: const Icon(CupertinoIcons.exclamationmark_triangle_fill,
                          color: AppColors.danger, size: 22),
                      title: Text('Conflict: ${_entityLabel(entry)}'),
                      subtitle: Text(
                        (entry as dynamic).errorMessage?.toString() ?? 'Server conflict',
                        maxLines: 2,
                        style: const TextStyle(fontSize: 12, color: AppColors.tertiaryLabel),
                      ),
                      trailing: Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          CupertinoButton(
                            padding: EdgeInsets.zero,
                            minimumSize: Size.zero,
                            onPressed: () => _discardIssue((entry as dynamic).id as int),
                            child: const Text('Discard',
                                style: TextStyle(fontSize: 13, color: AppColors.danger)),
                          ),
                        ],
                      ),
                    ),
                  for (final entry in _failed)
                    CupertinoListTile.notched(
                      leading: const Icon(CupertinoIcons.xmark_circle_fill,
                          color: AppColors.warning, size: 22),
                      title: Text('Failed: ${_entityLabel(entry)}'),
                      subtitle: Text(
                        (entry as dynamic).errorMessage?.toString() ?? 'Unknown error',
                        maxLines: 2,
                        style: const TextStyle(fontSize: 12, color: AppColors.tertiaryLabel),
                      ),
                      trailing: Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          CupertinoButton(
                            padding: EdgeInsets.zero,
                            minimumSize: Size.zero,
                            onPressed: () => _retryIssue((entry as dynamic).id as int),
                            child: const Text('Retry',
                                style: TextStyle(fontSize: 13, color: AppColors.primary)),
                          ),
                          const SizedBox(width: 8),
                          CupertinoButton(
                            padding: EdgeInsets.zero,
                            minimumSize: Size.zero,
                            onPressed: () => _discardIssue((entry as dynamic).id as int),
                            child: const Text('Discard',
                                style: TextStyle(fontSize: 13, color: AppColors.danger)),
                          ),
                        ],
                      ),
                    ),
                ],
              ),
            ],

            const SizedBox(height: 24),

            // ── Account ────────────────────────────────────────────────
            CupertinoListSection.insetGrouped(
              header: const Text('Account'),
              children: [
                CupertinoListTile.notched(
                  leading: const Icon(PhosphorIconsRegular.key,
                      color: AppColors.primary, size: 22),
                  title: const Text('Change Password'),
                  trailing: const Icon(CupertinoIcons.chevron_right,
                      size: 18, color: AppColors.tertiaryLabel),
                  onTap: () => AppDialogs.alert(context, 'Change Password',
                      'Password changes are not yet supported in the mobile app. Please change your password via the ONE DepED web portal.'),
                ),
                CupertinoListTile.notched(
                  leading: const Icon(PhosphorIconsRegular.fingerprint,
                      color: AppColors.primary, size: 22),
                  title: const Text('Biometric Login'),
                  trailing: CupertinoSwitch(
                    value: _biometricEnabled,
                    onChanged: (_) => _toggleBiometric(),
                    activeTrackColor: AppColors.primary,
                  ),
                  onTap: _biometricAvailable ? null : _toggleBiometric,
                ),
              ],
            ),
            const SizedBox(height: 24),

            // ── About ──────────────────────────────────────────────────
            CupertinoListSection.insetGrouped(
              header: const Text('About'),
              children: [
                CupertinoListTile.notched(
                  leading: const Icon(PhosphorIconsRegular.info,
                      color: AppColors.primary, size: 22),
                  title: const Text('Version'),
                  trailing: const Text('1.0.0 (1)',
                      style: TextStyle(color: AppColors.secondaryLabel)),
                ),
              ],
            ),
            const SizedBox(height: 32),

            // ── Logout ─────────────────────────────────────────────────
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 20),
              child: SizedBox(
                width: double.infinity,
                child: CupertinoButton(
                  onPressed: () => _confirmLogout(context),
                  color: AppColors.danger.withValues(alpha: 0.1),
                  borderRadius: BorderRadius.circular(12),
                  minimumSize: const Size(0, 50),
                  child: const Text(
                    'Log Out',
                    style: TextStyle(
                      color: AppColors.danger,
                      fontWeight: FontWeight.w600,
                      fontSize: 17,
                    ),
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  String _formatTime(DateTime time) {
    final now = DateTime.now();
    final diff = now.difference(time);
    if (diff.inMinutes < 1) return 'Just now';
    if (diff.inMinutes < 60) return '${diff.inMinutes}m ago';
    if (diff.inHours < 24) return '${diff.inHours}h ago';
    if (diff.inDays < 7) return '${diff.inDays}d ago';
    return '${time.month}/${time.day}/${time.year}';
  }

  String _entityLabel(dynamic entry) {
    final entity = entry.entity?.toString() ?? 'unknown';
    // The entity field stores the full API path (e.g. 'api/memos_save')
    // Strip the prefix for a cleaner label.
    final parts = entity.split('/');
    return parts.length > 1 ? parts.last : entity;
  }

  Future<void> _discardIssue(int id) async {
    await DI.sync.discardIssue(id);
    if (mounted) _refreshSyncState();
  }

  Future<void> _retryIssue(int id) async {
    await DI.sync.retryIssue(id);
    if (mounted) _refreshSyncState();
  }

  void _confirmLogout(BuildContext context) async {
    final confirmed = await AppDialogs.confirm(
      context,
      title: 'Log Out',
      message: 'Are you sure you want to log out? Cached data will be cleared.',
      confirmText: 'Log Out',
      destructive: true,
    );
    if (confirmed == true && context.mounted) {
      context.read<AuthViewModel>().logout();
    }
  }
}
