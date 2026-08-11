import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../data/models/user_profile.dart';
import '../../../core/di.dart';
import 'pmcf_add_view.dart';
import 'pmcf_detail_view.dart';

/// PMCF (Program Management Consolidated Form) list view.
///
/// Shows the authenticated user's PMCF records (teacher observation/coaching
/// forms). CID-only feature. Users can add new records and view details.
class PmcfListView extends StatefulWidget {
  const PmcfListView({
    super.key,
    required this.profile,
    this.onMenuTap,
  });

  final UserProfile profile;
  final VoidCallback? onMenuTap;

  @override
  State<PmcfListView> createState() => _PmcfListViewState();
}

class _PmcfListViewState extends State<PmcfListView> {
  bool _loading = true;
  String? _error;
  List<Map<String, dynamic>> _records = [];
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
    try {
      final data = await DI.api.get('api/pmcf_index');
      if (mounted) {
        setState(() {
          _records = data == null
              ? <Map<String, dynamic>>[]
              : (data as List)
                  .map((e) => e as Map<String, dynamic>)
                  .toList();
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

  List<Map<String, dynamic>> get _filtered {
    if (_search.isEmpty) return _records;
    final q = _search.toLowerCase();
    return _records.where((r) {
      final teacher = (r['teacher_observed'] ?? '').toString().toLowerCase();
      final school = (r['school'] ?? '').toString().toLowerCase();
      final district = (r['district'] ?? '').toString().toLowerCase();
      return teacher.contains(q) || school.contains(q) || district.contains(q);
    }).toList();
  }

  @override
  Widget build(BuildContext context) {
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: CupertinoNavigationBar(
        middle: const Text('PMCF'),
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
          onPressed: () {
            Navigator.push(
              context,
              CupertinoPageRoute(
                builder: (_) => PmcfAddView(profile: widget.profile),
              ),
            ).then((_) => _load());
          },
          child: const Icon(CupertinoIcons.add, size: 26),
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
    if (_records.isEmpty) {
      return _CenteredMessage(
        icon: PhosphorIconsRegular.fileText,
        title: 'No PMCF records yet',
        message: 'Tap + to create your first observation record.',
      );
    }
    return Column(
      children: [
        Padding(
          padding: const EdgeInsets.fromLTRB(16, 12, 16, 8),
          child: CupertinoSearchTextField(
            placeholder: 'Search by teacher, school, or district',
            onChanged: (v) => setState(() => _search = v),
            style: const TextStyle(color: AppColors.label),
          ),
        ),
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 20),
          child: Align(
            alignment: Alignment.centerLeft,
            child: Text(
              '${_filtered.length} ${_filtered.length == 1 ? "record" : "records"}',
              style: const TextStyle(
                fontSize: 13,
                color: AppColors.secondaryLabel,
              ),
            ),
          ),
        ),
        const SizedBox(height: 4),
        Expanded(
          child: ListView.builder(
            padding: const EdgeInsets.fromLTRB(16, 4, 16, 20),
            itemCount: _filtered.length,
            itemBuilder: (context, i) {
              final r = _filtered[i];
              return _PmcfTile(
                data: r,
                onTap: () {
                  Navigator.push(
                    context,
                    CupertinoPageRoute(
                      builder: (_) => PmcfDetailView(record: r),
                    ),
                  ).then((_) => _load());
                },
              );
            },
          ),
        ),
      ],
    );
  }
}

/// A single PMCF record row.
class _PmcfTile extends StatelessWidget {
  const _PmcfTile({required this.data, required this.onTap});
  final Map<String, dynamic> data;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    final teacher = (data['teacher_observed'] ?? 'Unknown').toString();
    final school = (data['school'] ?? '').toString();
    final district = (data['district'] ?? '').toString();
    final dateObserved = (data['date_observed'] ?? '').toString();
    final gradeLevel = (data['grade_level'] ?? '').toString();
    final coaching = (data['coaching_mechanisms'] ?? '').toString();

    return CupertinoButton(
      onPressed: onTap,
      padding: EdgeInsets.zero,
      child: Container(
        margin: const EdgeInsets.only(bottom: 8),
        padding: const EdgeInsets.all(14),
        decoration: BoxDecoration(
          color: AppColors.surface,
          borderRadius: BorderRadius.circular(12),
        ),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              width: 40,
              height: 40,
              decoration: BoxDecoration(
                color: AppColors.info,
                borderRadius: BorderRadius.circular(10),
              ),
              child: const Icon(
                CupertinoIcons.person_2,
                size: 20,
                color: CupertinoColors.white,
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    teacher,
                    style: const TextStyle(
                      fontSize: 15,
                      fontWeight: FontWeight.w600,
                      color: AppColors.label,
                    ),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                  if (school.isNotEmpty || district.isNotEmpty) ...[
                    const SizedBox(height: 2),
                    Text(
                      [school, district].where((s) => s.isNotEmpty).join(' • '),
                      style: const TextStyle(
                        fontSize: 13,
                        color: AppColors.secondaryLabel,
                      ),
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                  ],
                  const SizedBox(height: 4),
                  Row(
                    children: [
                      if (dateObserved.isNotEmpty)
                        _tag(CupertinoIcons.calendar, dateObserved),
                      if (gradeLevel.isNotEmpty) ...[
                        const SizedBox(width: 8),
                        _tag(CupertinoIcons.book, gradeLevel),
                      ],
                      if (coaching.isNotEmpty) ...[
                        const SizedBox(width: 8),
                        _tag(CupertinoIcons.chat_bubble, coaching),
                      ],
                    ],
                  ),
                ],
              ),
            ),
            const Icon(
              CupertinoIcons.chevron_right,
              size: 16,
              color: AppColors.tertiaryLabel,
            ),
          ],
        ),
      ),
    );
  }

  Widget _tag(IconData icon, String text) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Icon(icon, size: 11, color: AppColors.tertiaryLabel),
        const SizedBox(width: 3),
        Text(
          text,
          style: const TextStyle(
            fontSize: 11,
            color: AppColors.tertiaryLabel,
          ),
          maxLines: 1,
          overflow: TextOverflow.ellipsis,
        ),
      ],
    );
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
