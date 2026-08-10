import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../data/models/user_profile.dart';

/// iOS-style profile screen — grouped inset list sections.
class ProfileView extends StatelessWidget {
  const ProfileView({super.key, required this.profile});

  final UserProfile profile;

  @override
  Widget build(BuildContext context) {
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: const CupertinoNavigationBar(
        middle: Text('Profile'),
        backgroundColor: AppColors.surface,
        border: Border(bottom: BorderSide(color: AppColors.separator, width: 0.5)),
      ),
      child: SafeArea(
        child: ListView(
          children: [
            const SizedBox(height: 24),
            // ── Avatar + name ──────────────────────────────────────────
            Center(
              child: Container(
                width: 80,
                height: 80,
                decoration: BoxDecoration(
                  color: AppColors.primary,
                  borderRadius: BorderRadius.circular(20),
                ),
                child: Center(
                  child: Text(
                    _initials(),
                    style: const TextStyle(
                      color: CupertinoColors.white,
                      fontSize: 28,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                ),
              ),
            ),
            const SizedBox(height: 12),
            Center(
              child: Text(
                profile.fullName,
                style: const TextStyle(
                  fontSize: 22,
                  fontWeight: FontWeight.w700,
                  color: AppColors.label,
                ),
              ),
            ),
            const SizedBox(height: 2),
            Center(
              child: Text(
                '@${profile.username}',
                style: const TextStyle(
                  fontSize: 15,
                  color: AppColors.secondaryLabel,
                ),
              ),
            ),
            const SizedBox(height: 28),

            // ── Account section ────────────────────────────────────────
            CupertinoListSection.insetGrouped(
              header: const Text('Account'),
              children: [
                CupertinoListTile.notched(
                  leading: const Icon(PhosphorIconsRegular.identificationCard,
                      color: AppColors.primary, size: 22),
                  title: const Text('Role'),
                  trailing: Text(_roleLabel(profile.role),
                      style: const TextStyle(color: AppColors.secondaryLabel)),
                ),
                CupertinoListTile.notched(
                  leading: const Icon(PhosphorIconsRegular.folder,
                      color: AppColors.primary, size: 22),
                  title: const Text('Section'),
                  trailing: Text(
                    profile.section.isEmpty ? '—' : profile.section,
                    style: const TextStyle(color: AppColors.secondaryLabel),
                  ),
                ),
                CupertinoListTile.notched(
                  leading: const Icon(PhosphorIconsRegular.envelope,
                      color: AppColors.primary, size: 22),
                  title: const Text('Email'),
                  trailing: Text(
                    profile.email?.isNotEmpty == true ? profile.email! : '—',
                    style: const TextStyle(color: AppColors.secondaryLabel),
                  ),
                ),
                CupertinoListTile.notched(
                  leading: const Icon(PhosphorIconsRegular.database,
                      color: AppColors.primary, size: 22),
                  title: const Text('Source'),
                  trailing: Text(
                    profile.loginSource == 'deped_mis' ? 'DepEd MIS' : 'SGOD ONE',
                    style: const TextStyle(color: AppColors.secondaryLabel),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 20),
          ],
        ),
      ),
    );
  }

  String _initials() {
    final f = profile.fname.isNotEmpty ? profile.fname[0] : '';
    final l = profile.lname.isNotEmpty ? profile.lname[0] : '';
    return '$f$l'.toUpperCase();
  }

  String _roleLabel(Role role) {
    switch (role) {
      case Role.sgod: return 'SGOD';
      case Role.shns: return 'SHNS';
      case Role.school: return 'School';
      case Role.sned: return 'SNED';
      case Role.smme: return 'SMME';
      case Role.district: return 'District';
      case Role.unknown: return 'User';
    }
  }
}
