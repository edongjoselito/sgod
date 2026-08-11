import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../../core/theme/app_colors.dart';
import '../../../core/widgets/app_dialogs.dart';
import '../../../ui/core/di.dart';
import '../brigada_ui.dart';

/// Workstream B — Validation Queue.
///
/// SMN/SGOD validators view a filtered list of contribution submissions
/// requiring review, with automated quality flags. They can validate or
/// return submissions with remarks.
class ValidationQueueView extends StatefulWidget {
  const ValidationQueueView({super.key, this.sy});

  final String? sy;

  @override
  State<ValidationQueueView> createState() => _ValidationQueueViewState();
}

class _ValidationQueueViewState extends State<ValidationQueueView> {
  bool _loading = true;
  String? _error;
  Map<String, dynamic>? _data;
  String _statusFilter = '';
  String _syFilter = '';

  @override
  void initState() {
    super.initState();
    _syFilter = widget.sy ?? '';
    _load();
  }

  Future<void> _load() async {
    setState(() {
      _loading = true;
      _error = null;
    });
    try {
      final query = <String, dynamic>{};
      if (_syFilter.isNotEmpty) query['sy'] = _syFilter;
      if (_statusFilter.isNotEmpty) query['validation_status'] = _statusFilter;
      final data =
          await DI.api.get('api_brigada/validation_queue', query: query);
      if (mounted) {
        setState(() {
          _data = data as Map<String, dynamic>?;
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

  List<Map<String, dynamic>> get _queue {
    if (_data == null) return [];
    final list = _data!['queue'];
    if (list == null) return [];
    return (list as List).map((e) => e as Map<String, dynamic>).toList();
  }

  List<String> get _schoolYears {
    if (_data == null) return [];
    final list = _data!['school_years'];
    if (list == null) return [];
    return (list as List).map((e) => e.toString()).toList();
  }

  @override
  Widget build(BuildContext context) {
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: const CupertinoNavigationBar(
        middle: Text('Validation Queue'),
        backgroundColor: AppColors.surface,
        border: Border(
          bottom: BorderSide(color: AppColors.separator, width: 0.5),
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
    final items = _queue;
    return Column(
      children: [
        // ── Filters ─────────────────────────────────────────────────
        Padding(
          padding: const EdgeInsets.fromLTRB(16, 12, 16, 4),
          child: Row(
            children: [
              _filterChip('School Year', _syFilter, _schoolYears,
                  (v) => setState(() => _syFilter = v)),
              const SizedBox(width: 8),
              _filterChip(
                  'Status',
                  _statusFilter,
                  ['', 'pending', 'validated', 'returned'],
                  (v) => setState(() => _statusFilter = v)),
              const Spacer(),
              CupertinoButton(
                padding: EdgeInsets.zero,
                onPressed: _load,
                child: const Icon(CupertinoIcons.refresh, size: 20),
              ),
            ],
          ),
        ),
        if (items.isEmpty)
          Expanded(
            child: BrigadaEmpty(
              icon: PhosphorIconsRegular.checkSquare,
              title: 'Queue is empty',
              message: 'No submissions match the current filters.',
            ),
          )
        else
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.fromLTRB(16, 4, 16, 20),
              itemCount: items.length,
              itemBuilder: (context, i) {
                final q = items[i];
                return _QueueTile(
                  data: q,
                  onValidate: () => _showValidateDialog(q, 'validate'),
                  onReturn: () => _showValidateDialog(q, 'return'),
                );
              },
            ),
          ),
      ],
    );
  }

  Widget _filterChip(String label, String value, List<String> options,
      ValueChanged<String> onSelected) {
    return CupertinoButton(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
      color: AppColors.surface,
      borderRadius: BorderRadius.circular(8),
      minimumSize: const Size(0, 32),
      onPressed: () {
        showCupertinoModalPopup(
          context: context,
          builder: (ctx) => CupertinoActionSheet(
            title: Text(label),
            actions: [
              CupertinoActionSheetAction(
                onPressed: () {
                  onSelected('');
                  Navigator.pop(ctx);
                  _load();
                },
                child: const Text('All'),
              ),
              ...options.where((o) => o.isNotEmpty).map((opt) {
                return CupertinoActionSheetAction(
                  onPressed: () {
                    onSelected(opt);
                    Navigator.pop(ctx);
                    _load();
                  },
                  child: Text(opt),
                );
              }),
            ],
            cancelButton: CupertinoActionSheetAction(
              isDefaultAction: true,
              onPressed: () => Navigator.pop(ctx),
              child: const Text('Cancel'),
            ),
          ),
        );
      },
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Text(
            value.isEmpty ? label : value,
            style: const TextStyle(
              fontSize: 13,
              color: AppColors.label,
            ),
          ),
          const SizedBox(width: 4),
          const Icon(CupertinoIcons.chevron_down,
              size: 12, color: AppColors.tertiaryLabel),
        ],
      ),
    );
  }

  void _showValidateDialog(Map<String, dynamic> item, String action) {
    final reportId = int.tryParse('${item['id']}') ?? 0;
    final schoolName = (item['schoolName'] ?? 'Unknown').toString();
    final partnerName = (item['partner_name'] ?? '').toString();
    final remarksCtrl = TextEditingController();
    final isReturn = action == 'return';

    showCupertinoModalPopup(
      context: context,
      builder: (ctx) => CupertinoActionSheet(
        title: Text(isReturn ? 'Return Submission' : 'Validate Submission'),
        message: Text('$schoolName — $partnerName'),
        actions: [
          if (isReturn)
            CupertinoActionSheetAction(
              child: _RemarksInput(controller: remarksCtrl),
              onPressed: () {},
            ),
          CupertinoActionSheetAction(
            isDefaultAction: true,
            onPressed: () async {
              if (isReturn && remarksCtrl.text.trim().isEmpty) {
                Navigator.pop(ctx);
                AppDialogs.alert(context, 'Validation',
                    'Remarks are required when returning a submission.');
                return;
              }
              Navigator.pop(ctx);
              await _doValidate(reportId, action, remarksCtrl.text.trim());
            },
            child: Text(isReturn ? 'Return with Remarks' : 'Validate'),
          ),
        ],
        cancelButton: CupertinoActionSheetAction(
          onPressed: () => Navigator.pop(ctx),
          child: const Text('Cancel'),
        ),
      ),
    );
  }

  Future<void> _doValidate(int reportId, String action, String remarks) async {
    try {
      final result = await DI.write(
        endpoint: 'validate',
        entity: 'brigada_validation',
        operation: action == 'validate' ? 'update' : 'update',
        payload: {
          'report_id': reportId,
          'action': action,
          'remarks': remarks,
        },
        prefix: 'api_brigada',
      );
      if (result == null && mounted) {
        AppDialogs.alert(context, 'Queued',
            'You are offline. This validation action has been queued and will sync automatically when you reconnect.');
      }
      _load();
    } catch (e) {
      if (mounted) AppDialogs.alert(context, 'Action Failed', '$e');
    }
  }
}

class _RemarksInput extends StatelessWidget {
  const _RemarksInput({required this.controller});
  final TextEditingController controller;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      color: AppColors.background,
      child: CupertinoTextField(
        controller: controller,
        placeholder: 'Enter remarks for return...',
        maxLines: 4,
        padding: const EdgeInsets.all(12),
        style: const TextStyle(color: AppColors.label),
      ),
    );
  }
}

/// A single validation queue item.
class _QueueTile extends StatelessWidget {
  const _QueueTile({
    required this.data,
    required this.onValidate,
    required this.onReturn,
  });

  final Map<String, dynamic> data;
  final VoidCallback onValidate;
  final VoidCallback onReturn;

  @override
  Widget build(BuildContext context) {
    final schoolName = (data['schoolName'] ?? 'Unknown').toString();
    final district = (data['school_district'] ?? '').toString();
    final partnerName = (data['partner_name'] ?? '').toString();
    final contribType = (data['contribution_type'] ?? '').toString();
    final amount = (data['amount'] ?? '0').toString();
    final date = (data['c_date'] ?? '').toString();
    final status = (data['validation_status'] ?? 'pending').toString();
    final flags = data['flags'] as List?;

    return Container(
      margin: const EdgeInsets.only(bottom: 10),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: AppColors.surface,
        borderRadius: BorderRadius.circular(14),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Expanded(
                child: Text(
                  schoolName,
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
          if (district.isNotEmpty) ...[
            const SizedBox(height: 2),
            Text(district,
                style: const TextStyle(
                  fontSize: 13,
                  color: AppColors.tertiaryLabel,
                )),
          ],
          const SizedBox(height: 8),
          Text(partnerName,
              style: const TextStyle(
                fontSize: 14,
                color: AppColors.secondaryLabel,
              )),
          if (contribType.isNotEmpty)
            Text(contribType,
                style: const TextStyle(
                  fontSize: 13,
                  color: AppColors.tertiaryLabel,
                )),
          const SizedBox(height: 8),
          Row(
            children: [
              if (date.isNotEmpty)
                _metaTag(CupertinoIcons.calendar, date),
              if (amount != '0' && amount.isNotEmpty) ...[
                const SizedBox(width: 10),
                _metaTag(CupertinoIcons.money_dollar_circle, amount),
              ],
              if (flags != null && flags.isNotEmpty) ...[
                const Spacer(),
                _flagsBadge(flags),
              ],
            ],
          ),
          if (status == 'pending') ...[
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(
                  child: CupertinoButton(
                    onPressed: onValidate,
                    color: AppColors.success,
                    borderRadius: BorderRadius.circular(8),
                    minimumSize: const Size(0, 36),
                    child: const Text('Validate',
                        style: TextStyle(
                          fontSize: 14,
                          fontWeight: FontWeight.w600,
                          color: CupertinoColors.white,
                        )),
                  ),
                ),
                const SizedBox(width: 8),
                Expanded(
                  child: CupertinoButton(
                    onPressed: onReturn,
                    color: AppColors.danger,
                    borderRadius: BorderRadius.circular(8),
                    minimumSize: const Size(0, 36),
                    child: const Text('Return',
                        style: TextStyle(
                          fontSize: 14,
                          fontWeight: FontWeight.w600,
                          color: CupertinoColors.white,
                        )),
                  ),
                ),
              ],
            ),
          ],
        ],
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
    final hasError = flags.any(
        (f) => f is Map && (f['severity'] ?? '') == 'error');
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
          Text('${flags.length}',
              style: const TextStyle(
                fontSize: 11,
                fontWeight: FontWeight.w700,
                color: CupertinoColors.white,
              )),
        ],
      ),
    );
  }
}
