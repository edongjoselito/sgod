import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../data/models/user_profile.dart';
import '../../../core/di.dart';

/// Personnel screen for school-role users.
///
/// Fetches the personnel list for the user's school via
/// `api/school_personnel_index?school_id=<username>`. The backend queries
/// the `one_school_personnel` (or `school_personnel`) table ordered by
/// `full_name`. Since the exact columns may vary, we display the fields
/// that are present in each row.
class PersonnelView extends StatefulWidget {
  const PersonnelView({
    super.key,
    required this.profile,
    this.onMenuTap,
  });

  final UserProfile profile;
  final VoidCallback? onMenuTap;

  @override
  State<PersonnelView> createState() => _PersonnelViewState();
}

class _PersonnelViewState extends State<PersonnelView> {
  bool _loading = true;
  String? _error;
  List<Map<String, dynamic>> _personnel = [];
  String _search = '';

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
    final cacheKey = 'personnel_${widget.profile.username}';
    try {
      final data = await DI.api.get(
        'api/school_personnel_index',
        query: {'school_id': widget.profile.username},
      );
      final items = data == null
          ? <Map<String, dynamic>>[]
          : (data as List)
              .map((e) => e is Map<String, dynamic>
                  ? e
                  : Map<String, dynamic>.from(e as Map))
              .toList();
      // Cache in Drift for persistent offline access
      try {
        await DI.db.cacheList(cacheKey, items);
      } catch (_) {}
      if (mounted) {
        setState(() {
          _personnel = items;
          _loading = false;
        });
      }
    } catch (e) {
      // Try Drift persistent cache
      try {
        final cached = await DI.db.getCachedList(cacheKey);
        if (cached != null && cached.isNotEmpty) {
          if (mounted) {
            setState(() {
              _personnel = cached;
              _loading = false;
            });
          }
          return;
        }
      } catch (_) {}
      if (mounted) {
        setState(() {
          _error = '$e';
          _loading = false;
        });
      }
    }
  }

  List<Map<String, dynamic>> get _filtered {
    if (_search.isEmpty) return _personnel;
    final q = _search.toLowerCase();
    return _personnel.where((p) {
      final name = (p['full_name'] ?? p['name'] ?? '').toString().toLowerCase();
      final position = (p['position'] ?? '').toString().toLowerCase();
      return name.contains(q) || position.contains(q);
    }).toList();
  }

  @override
  Widget build(BuildContext context) {
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: CupertinoNavigationBar(
        middle: const Text('Personnel'),
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
        trailing: CupertinoButton(
          padding: EdgeInsets.zero,
          onPressed: _load,
          child: const Icon(CupertinoIcons.refresh, size: 22),
        ),
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
    if (_personnel.isEmpty) {
      return _CenteredMessage(
        icon: PhosphorIconsRegular.usersThree,
        title: 'No personnel found',
        message:
            'No personnel records are linked to your school '
            '(${widget.profile.username}).',
      );
    }
    return Column(
      children: [
        // ── Search bar ──────────────────────────────────────────────
        Padding(
          padding: const EdgeInsets.fromLTRB(16, 12, 16, 8),
          child: CupertinoSearchTextField(
            placeholder: 'Search personnel',
            onChanged: (v) => setState(() => _search = v),
            style: const TextStyle(color: AppColors.label),
          ),
        ),
        // ── Count ───────────────────────────────────────────────────
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 20),
          child: Align(
            alignment: Alignment.centerLeft,
            child: Text(
              '${_filtered.length} ${_filtered.length == 1 ? "person" : "people"}',
              style: const TextStyle(
                fontSize: 13,
                color: AppColors.secondaryLabel,
              ),
            ),
          ),
        ),
        const SizedBox(height: 4),
        // ── List ────────────────────────────────────────────────────
        Expanded(
          child: ListView.builder(
            padding: const EdgeInsets.fromLTRB(16, 4, 16, 20),
            itemCount: _filtered.length,
            itemBuilder: (context, i) {
              final p = _filtered[i];
              return _PersonnelTile(data: p);
            },
          ),
        ),
      ],
    );
  }
}

/// A single personnel row — displays name, position, and any other
/// fields present (email, contact, etc.) in a clean inset card.
class _PersonnelTile extends StatelessWidget {
  const _PersonnelTile({required this.data});
  final Map<String, dynamic> data;

  @override
  Widget build(BuildContext context) {
    final name = (data['full_name'] ?? data['name'] ?? 'Unknown').toString();
    final position = (data['position'] ?? '').toString();
    final email = (data['email'] ?? '').toString();
    final contact = (data['contact'] ?? data['phone'] ?? '').toString();

    return Container(
      margin: const EdgeInsets.only(bottom: 8),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: AppColors.surface,
        borderRadius: BorderRadius.circular(12),
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // ── Avatar circle with initials ───────────────────────────
          Container(
            width: 40,
            height: 40,
            decoration: BoxDecoration(
              color: AppColors.primary,
              borderRadius: BorderRadius.circular(10),
            ),
            child: Center(
              child: Text(
                _initials(name),
                style: const TextStyle(
                  color: CupertinoColors.white,
                  fontSize: 16,
                  fontWeight: FontWeight.w600,
                ),
              ),
            ),
          ),
          const SizedBox(width: 12),
          // ── Name + details ────────────────────────────────────────
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  name,
                  style: const TextStyle(
                    fontSize: 15,
                    fontWeight: FontWeight.w600,
                    color: AppColors.label,
                  ),
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                ),
                if (position.isNotEmpty) ...[
                  const SizedBox(height: 2),
                  Text(
                    position,
                    style: const TextStyle(
                      fontSize: 13,
                      color: AppColors.secondaryLabel,
                    ),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                ],
                if (email.isNotEmpty || contact.isNotEmpty) ...[
                  const SizedBox(height: 4),
                  if (email.isNotEmpty)
                    _contactRow(CupertinoIcons.envelope, email),
                  if (contact.isNotEmpty) ...[
                    if (email.isNotEmpty) const SizedBox(height: 2),
                    _contactRow(CupertinoIcons.phone, contact),
                  ],
                ],
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _contactRow(IconData icon, String text) {
    return Row(
      children: [
        Icon(icon, size: 12, color: AppColors.tertiaryLabel),
        const SizedBox(width: 4),
        Flexible(
          child: Text(
            text,
            style: const TextStyle(
              fontSize: 12,
              color: AppColors.tertiaryLabel,
            ),
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
          ),
        ),
      ],
    );
  }

  String _initials(String name) {
    final parts = name.trim().split(RegExp(r'\s+'));
    if (parts.isEmpty) return '?';
    if (parts.length == 1) return parts[0][0].toUpperCase();
    return (parts[0][0] + parts[1][0]).toUpperCase();
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
