import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../data/models/school_item.dart';
import '../../../../data/models/user_profile.dart';
import '../../../core/di.dart';
import '../../schools/views/school_detail_view.dart';

/// School Profile screen for school-role users.
///
/// The school user's username is their school ID (matching the web app's
/// convention of `$this->session->username` as `school_id`). This view
/// fetches the school list, finds the user's school, and shows its detail
/// with edit capability.
class SchoolProfileView extends StatefulWidget {
  const SchoolProfileView({
    super.key,
    required this.profile,
    this.onMenuTap,
  });

  final UserProfile profile;
  final VoidCallback? onMenuTap;

  @override
  State<SchoolProfileView> createState() => _SchoolProfileViewState();
}

class _SchoolProfileViewState extends State<SchoolProfileView> {
  bool _loading = true;
  String? _error;
  SchoolItem? _school;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() {
      _loading = true;
      _error = null;
    });
    try {
      final schools = await DI.schools.fetch(limit: 500);
      final mine = schools.where(
          (s) => s.schoolID == widget.profile.username).toList();
      if (mounted) {
        setState(() {
          _school = mine.isNotEmpty ? mine.first : null;
          _loading = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _error = '$e';
          _loading = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: CupertinoNavigationBar(
        middle: const Text('School Profile'),
        backgroundColor: AppColors.surface,
        border: const Border(
          bottom: BorderSide(color: AppColors.separator, width: 0.5),
        ),
        leading: widget.onMenuTap != null
            ? CupertinoButton(
                padding: EdgeInsets.zero,
                onPressed: widget.onMenuTap,
                child: const Icon(CupertinoIcons.line_horizontal_3, size: 22),
              )
            : null,
      ),
      child: SafeArea(
        top: false,
        child: _buildBody(),
      ),
    );
  }

  Widget _buildBody() {
    if (_loading) {
      return const Center(child: CupertinoActivityIndicator(radius: 16));
    }
    if (_error != null) {
      return _CenteredMessage(
        icon: CupertinoIcons.wifi_exclamationmark,
        title: 'Could not load',
        message: _error!,
        actionLabel: 'Retry',
        onAction: _load,
      );
    }
    if (_school == null) {
      return _CenteredMessage(
        icon: PhosphorIconsRegular.buildings,
        title: 'School not found',
        message:
            'No school record matches your account (${widget.profile.username}). '
            'Please contact your administrator.',
      );
    }
    return SchoolDetailView(item: _school!, onChanged: _load);
  }
}

/// Reusable centered empty/error state.
class _CenteredMessage extends StatelessWidget {
  const _CenteredMessage({
    required this.icon,
    required this.title,
    required this.message,
    this.actionLabel,
    this.onAction,
  });

  final IconData icon;
  final String title;
  final String message;
  final String? actionLabel;
  final VoidCallback? onAction;

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 32),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, size: 48, color: AppColors.tertiaryLabel),
            const SizedBox(height: 16),
            Text(
              title,
              style: const TextStyle(
                fontSize: 17,
                fontWeight: FontWeight.w600,
                color: AppColors.label,
              ),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 4),
            Text(
              message,
              style: const TextStyle(
                fontSize: 13,
                color: AppColors.secondaryLabel,
              ),
              textAlign: TextAlign.center,
            ),
            if (actionLabel != null && onAction != null) ...[
              const SizedBox(height: 16),
              CupertinoButton(onPressed: onAction, child: Text(actionLabel!)),
            ],
          ],
        ),
      ),
    );
  }
}
