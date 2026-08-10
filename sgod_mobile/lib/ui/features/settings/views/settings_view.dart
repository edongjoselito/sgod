import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';
import 'package:provider/provider.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../core/widgets/app_dialogs.dart';
import '../../auth/view_models/auth_view_model.dart';

/// iOS-style settings screen — grouped inset list sections.
class SettingsView extends StatelessWidget {
  const SettingsView({super.key});

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
                  leading: const Icon(PhosphorIconsRegular.checkCircle,
                      color: AppColors.success, size: 22),
                  title: const Text('Sync Status'),
                  trailing: const Text('All synced',
                      style: TextStyle(color: AppColors.secondaryLabel)),
                ),
              ],
            ),
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
                  onTap: () {},
                ),
                CupertinoListTile.notched(
                  leading: const Icon(PhosphorIconsRegular.fingerprint,
                      color: AppColors.primary, size: 22),
                  title: const Text('Biometric Login'),
                  trailing: const Icon(CupertinoIcons.chevron_right,
                      size: 18, color: AppColors.tertiaryLabel),
                  onTap: () {},
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
                  color: AppColors.danger.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(12),
                  minSize: 50,
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
