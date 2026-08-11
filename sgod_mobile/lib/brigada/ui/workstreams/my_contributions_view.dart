import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../core/theme/app_colors.dart';
import '../../../core/widgets/app_dialogs.dart';
import '../../../ui/core/di.dart';
import '../brigada_ui.dart';
import 'contribution_form_view.dart';
import 'attachments_view.dart';

/// Workstream F — My Contributions.
///
/// School users view their own school's contribution records for a school
/// year, with validation status, quality flags, and attached documents.
/// They can add new contributions and edit pending ones.
class MyContributionsView extends StatefulWidget {
  const MyContributionsView({
    super.key,
    this.username,
    this.sy,
  });

  final String? username;
  final String? sy;

  @override
  State<MyContributionsView> createState() => _MyContributionsViewState();
}

class _MyContributionsViewState extends State<MyContributionsView> {
  bool _loading = true;
  String? _error;
  Map<String, dynamic>? _data;
  late String _sy;

  @override
  void initState() {
    super.initState();
    _sy = widget.sy ?? '';
    _load();
  }

  Future<void> _load() async {
    setState(() {
      _loading = true;
      _error = null;
    });
    try {
      final query = <String, dynamic>{};
      if (_sy.isNotEmpty) query['sy'] = _sy;
      final data = await DI.api.get('api_brigada/my_contributions', query: query);
      if (mounted) {
        setState(() {
          _data = data as Map<String, dynamic>?;
          if (_data != null && _sy.isEmpty) {
            _sy = (_data!['sy'] ?? '').toString();
          }
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

  List<Map<String, dynamic>> get _contributions {
    if (_data == null) return [];
    final list = _data!['contributions'];
    if (list == null) return [];
    return (list as List).map((e) => e as Map<String, dynamic>).toList();
  }

  @override
  Widget build(BuildContext context) {
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: CupertinoNavigationBar(
        middle: const Text('My Contributions'),
        backgroundColor: AppColors.surface,
        border: const Border(
          bottom: BorderSide(color: AppColors.separator, width: 0.5),
        ),
        trailing: CupertinoButton(
          padding: EdgeInsets.zero,
          onPressed: () {
            Navigator.push(
              context,
              CupertinoPageRoute(
                builder: (_) => ContributionFormView(
                  username: widget.username,
                  sy: _sy,
                ),
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
      return BrigadaEmpty(
        icon: PhosphorIconsRegular.warningCircle,
        title: 'Could not load',
        message: _error!,
      );
    }
    final items = _contributions;
    if (items.isEmpty) {
      return BrigadaEmpty(
        icon: PhosphorIconsRegular.handshake,
        title: 'No contributions yet',
        message: 'Tap + to record your first contribution for $_sy.',
      );
    }
    return CustomScrollView(
      slivers: [
        CupertinoSliverRefreshControl(onRefresh: _load),
        SliverToBoxAdapter(
          child: ListView.builder(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            padding: const EdgeInsets.fromLTRB(16, 12, 16, 24),
            itemCount: items.length,
            itemBuilder: (context, i) {
              final c = items[i];
              return _ContributionTile(
                data: c,
                onEdit: () {
                  final status = (c['validation_status'] ?? 'pending').toString();
                  if (status == 'validated') {
                    AppDialogs.alert(context, 'Locked',
                        'This contribution has been validated and can no longer be edited.');
                    return;
                  }
                  Navigator.push(
                    context,
                    CupertinoPageRoute(
                      builder: (_) => ContributionFormView(
                        username: widget.username,
                        sy: _sy,
                        existing: c,
                      ),
                    ),
                  ).then((_) => _load());
                },
                onAttachments: () {
                  final id = int.tryParse('${c['id']}') ?? 0;
                  Navigator.push(
                    context,
                    CupertinoPageRoute(
                      builder: (_) => AttachmentsView(
                        entityType: 'contribution',
                        entityId: id,
                        sy: _sy,
                        schoolId: widget.username,
                      ),
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

/// A single contribution record tile.
class _ContributionTile extends StatelessWidget {
  const _ContributionTile({
    required this.data,
    required this.onEdit,
    required this.onAttachments,
  });

  final Map<String, dynamic> data;
  final VoidCallback onEdit;
  final VoidCallback onAttachments;

  @override
  Widget build(BuildContext context) {
    final partnerName = (data['partner_name'] ?? 'Unknown partner').toString();
    final contribType = (data['contribution_type'] ?? '').toString();
    final specific = (data['spicific_contribution'] ?? '').toString();
    final amount = (data['amount'] ?? '0').toString();
    final date = (data['c_date'] ?? '').toString();
    final status = (data['validation_status'] ?? 'pending').toString();
    final flags = data['flags'] as List?;
    final attachments = data['attachments'] as List?;

    return Container(
      margin: const EdgeInsets.only(bottom: 10),
      decoration: BoxDecoration(
        color: AppColors.surface,
        borderRadius: BorderRadius.circular(14),
      ),
      child: CupertinoButton(
        onPressed: onEdit,
        padding: EdgeInsets.zero,
        child: Padding(
          padding: const EdgeInsets.all(14),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  Expanded(
                    child: Text(
                      partnerName,
                      style: const TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.w600,
                        color: AppColors.label,
                      ),
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                  ),
                  _statusBadge(status),
                ],
              ),
              if (contribType.isNotEmpty || specific.isNotEmpty) ...[
                const SizedBox(height: 4),
                Text(
                  [contribType, specific]
                      .where((s) => s.isNotEmpty)
                      .join(' — '),
                  style: const TextStyle(
                    fontSize: 14,
                    color: AppColors.secondaryLabel,
                  ),
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                ),
              ],
              const SizedBox(height: 8),
              Row(
                children: [
                  if (date.isNotEmpty)
                    _metaTag(CupertinoIcons.calendar, date),
                  if (amount != '0' && amount.isNotEmpty) ...[
                    const SizedBox(width: 10),
                    _metaTag(CupertinoIcons.money_dollar_circle, amount),
                  ],
                  if (attachments != null && attachments.isNotEmpty) ...[
                    const SizedBox(width: 10),
                    _metaTag(CupertinoIcons.paperclip,
                        '${attachments.length}'),
                  ],
                  const Spacer(),
                  // ── Flags ──────────────────────────────────────────
                  if (flags != null && flags.isNotEmpty)
                    _flagsBadge(flags),
                ],
              ),
              const SizedBox(height: 8),
              // ── Action row ──────────────────────────────────────────
              Row(
                children: [
                  CupertinoButton(
                    padding: EdgeInsets.zero,
                    minimumSize: const Size(0, 32),
                    onPressed: onAttachments,
                    child: const Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Icon(CupertinoIcons.paperclip,
                            size: 16, color: AppColors.primary),
                        SizedBox(width: 4),
                        Text('Attachments',
                            style: TextStyle(
                              fontSize: 13,
                              color: AppColors.primary,
                              fontWeight: FontWeight.w500,
                            )),
                      ],
                    ),
                  ),
                  const Spacer(),
                  if (status != 'validated')
                    const Text('Edit',
                        style: TextStyle(
                          fontSize: 13,
                          color: AppColors.tertiaryLabel,
                        )),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _statusBadge(String status) {
    final color = switch (status) {
      'validated' => AppColors.success,
      'returned' => AppColors.danger,
      _ => AppColors.warning,
    };
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
      decoration: BoxDecoration(
        color: color,
        borderRadius: BorderRadius.circular(100),
      ),
      child: Text(
        status.toUpperCase(),
        style: const TextStyle(
          fontSize: 10,
          fontWeight: FontWeight.w700,
          color: CupertinoColors.white,
        ),
      ),
    );
  }

  Widget _metaTag(IconData icon, String text) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Icon(icon, size: 13, color: AppColors.tertiaryLabel),
        const SizedBox(width: 3),
        Text(text,
            style: const TextStyle(
              fontSize: 12,
              color: AppColors.tertiaryLabel,
            )),
      ],
    );
  }

  Widget _flagsBadge(List flags) {
    final hasError = flags.any((f) =>
        (f is Map && (f['severity'] ?? '') == 'error'));
    final color = hasError ? AppColors.danger : AppColors.warning;
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
      decoration: BoxDecoration(
        color: color,
        borderRadius: BorderRadius.circular(6),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          const Icon(CupertinoIcons.flag_fill,
              size: 12, color: CupertinoColors.white),
          const SizedBox(width: 3),
          Text(
            '${flags.length}',
            style: const TextStyle(
              fontSize: 11,
              fontWeight: FontWeight.w700,
              color: CupertinoColors.white,
            ),
          ),
        ],
      ),
    );
  }
}
