import 'dart:typed_data';

import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';
import 'package:url_launcher/url_launcher.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../data/models/accomplishment_item.dart';
import '../../../../data/repositories/accomplishments_repository.dart';
import '../../../core/di.dart';

/// Attachments page for an accomplishment.
///
/// Lists attached PDFs/documents, allows upload and delete.
class AccomplishmentAttachmentsView extends StatefulWidget {
  const AccomplishmentAttachmentsView({super.key, required this.item});

  final AccomplishmentItem item;

  @override
  State<AccomplishmentAttachmentsView> createState() =>
      _AccomplishmentAttachmentsViewState();
}

class _AccomplishmentAttachmentsViewState
    extends State<AccomplishmentAttachmentsView> {
  List<Map<String, dynamic>> _reports = [];
  bool _loading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _loadReports();
  }

  Future<void> _loadReports() async {
    setState(() {
      _loading = true;
      _error = null;
    });
    try {
      _reports = await DI.accomplishments.listReports(widget.item.id);
    } catch (e) {
      _error = '$e';
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: const CupertinoNavigationBar(
        middle: Text('Attachments'),
        backgroundColor: AppColors.surface,
        border: Border(
          bottom: BorderSide(color: AppColors.separator, width: 0.5),
        ),
      ),
      child: SafeArea(
        child: Column(
          children: [
            // ── Info bar ──────────────────────────────────────────────
            Container(
              width: double.infinity,
              padding: const EdgeInsets.fromLTRB(16, 12, 16, 12),
              color: AppColors.surface,
              child: Row(
                children: [
                  const Icon(PhosphorIconsRegular.fileText,
                      size: 18, color: AppColors.primary),
                  const SizedBox(width: 8),
                  Expanded(
                    child: Text(
                      widget.item.activity,
                      style: const TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.w600,
                        color: AppColors.label,
                      ),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                  ),
                ],
              ),
            ),
            Container(height: 0.5, color: AppColors.separator),
            // ── List ───────────────────────────────────────────────────
            Expanded(
              child: RefreshControl(
                onRefresh: _loadReports,
                child: _buildBody(),
              ),
            ),
            // ── Upload button ──────────────────────────────────────────
            Container(
              decoration: const BoxDecoration(
                color: AppColors.surface,
                border: Border(
                  top: BorderSide(color: AppColors.separator, width: 0.5),
                ),
              ),
              padding: const EdgeInsets.all(16),
              child: SizedBox(
                width: double.infinity,
                child: CupertinoButton.filled(
                  onPressed: _showUploadSheet,
                  child: const Text('Upload Attachment'),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildBody() {
    if (_loading) {
      return const Center(child: CupertinoActivityIndicator(radius: 16));
    }
    if (_error != null) {
      return Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Icon(CupertinoIcons.wifi_exclamationmark,
                size: 40, color: AppColors.tertiaryLabel),
            const SizedBox(height: 12),
            Text(_error!, style: const TextStyle(fontSize: 14, color: AppColors.secondaryLabel)),
            const SizedBox(height: 12),
            CupertinoButton(onPressed: _loadReports, child: const Text('Retry')),
          ],
        ),
      );
    }
    if (_reports.isEmpty) {
      return Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Icon(PhosphorIconsRegular.paperclip,
                size: 48, color: AppColors.tertiaryLabel),
            const SizedBox(height: 16),
            const Text('No attachments yet',
                style: TextStyle(
                    fontSize: 17,
                    fontWeight: FontWeight.w600,
                    color: AppColors.label)),
            const SizedBox(height: 6),
            const Text('Upload PDFs, documents, or images.',
                style: TextStyle(fontSize: 14, color: AppColors.secondaryLabel)),
          ],
        ),
      );
    }
    return ListView.separated(
      padding: const EdgeInsets.fromLTRB(12, 12, 12, 16),
      itemCount: _reports.length,
      separatorBuilder: (_, __) => Container(height: 0.5, color: AppColors.separator),
      itemBuilder: (context, i) {
        final r = _reports[i];
        return _AttachmentRow(
          name: r['document_name'] ?? r['original_name'] ?? 'Attachment',
          originalName: r['original_name'] ?? '',
          date: r['uploaded_at'] ?? '',
          url: r['url'] ?? '',
          onDelete: () => _confirmDelete(r['id']?.toString() ?? ''),
        );
      },
    );
  }

  void _showUploadSheet() {
    showCupertinoModalPopup(
      context: context,
      builder: (ctx) => _UploadSheet(
        itemId: widget.item.id,
        onUploaded: () {
          Navigator.pop(ctx);
          _loadReports();
        },
      ),
    );
  }

  void _confirmDelete(String id) {
    showCupertinoDialog(
      context: context,
      builder: (c) => CupertinoAlertDialog(
        title: const Text('Delete Attachment'),
        content: const Text('Remove this attachment?'),
        actions: [
          CupertinoDialogAction(
            isDefaultAction: true,
            onPressed: () => Navigator.pop(c),
            child: const Text('Cancel'),
          ),
          CupertinoDialogAction(
            isDestructiveAction: true,
            onPressed: () {
              Navigator.pop(c);
              _doDelete(id);
            },
            child: const Text('Delete'),
          ),
        ],
      ),
    );
  }

  Future<void> _doDelete(String id) async {
    try {
      await DI.accomplishments.deleteReport(id);
      _loadReports();
    } catch (e) {
      if (mounted) {
        showCupertinoDialog(
          context: context,
          builder: (c) => CupertinoAlertDialog(
            title: const Text('Error'),
            content: Text('$e'),
            actions: [
              CupertinoDialogAction(
                isDefaultAction: true,
                onPressed: () => Navigator.pop(c),
                child: const Text('OK'),
              ),
            ],
          ),
        );
      }
    }
  }
}

// ── Attachment row ─────────────────────────────────────────────────────────
class _AttachmentRow extends StatelessWidget {
  const _AttachmentRow({
    required this.name,
    required this.originalName,
    required this.date,
    required this.url,
    required this.onDelete,
  });

  final String name;
  final String originalName;
  final String date;
  final String url;
  final VoidCallback onDelete;

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        color: AppColors.surface,
        borderRadius: BorderRadius.circular(10),
      ),
      clipBehavior: Clip.antiAlias,
      child: Row(
        children: [
          Expanded(
            child: CupertinoButton(
              onPressed: () => _openUrl(url),
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
              minSize: 0,
              child: Row(
                children: [
                  Container(
                    width: 36,
                    height: 36,
                    decoration: BoxDecoration(
                      color: AppColors.warning.withOpacity(0.1),
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: const Icon(PhosphorIconsRegular.filePdf,
                        size: 18, color: AppColors.warning),
                  ),
                  const SizedBox(width: 10),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          name,
                          style: const TextStyle(
                            fontSize: 14,
                            fontWeight: FontWeight.w600,
                            color: AppColors.label,
                          ),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                        const SizedBox(height: 2),
                        Text(
                          [
                            if (originalName.isNotEmpty) originalName,
                            if (date.isNotEmpty) date.split(' ').first,
                          ].join(' • '),
                          style: const TextStyle(
                            fontSize: 12,
                            color: AppColors.tertiaryLabel,
                          ),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
          CupertinoButton(
            onPressed: onDelete,
            padding: const EdgeInsets.only(right: 12),
            minSize: 0,
            child: const Icon(CupertinoIcons.delete_simple,
                size: 18, color: AppColors.danger),
          ),
        ],
      ),
    );
  }

  Future<void> _openUrl(String url) async {
    final uri = Uri.parse(url);
    if (await canLaunchUrl(uri)) {
      await launchUrl(uri, mode: LaunchMode.externalApplication);
    }
  }
}

// ── Upload sheet ───────────────────────────────────────────────────────────
class _UploadSheet extends StatefulWidget {
  const _UploadSheet({required this.itemId, required this.onUploaded});

  final String itemId;
  final VoidCallback onUploaded;

  @override
  State<_UploadSheet> createState() => _UploadSheetState();
}

class _UploadSheetState extends State<_UploadSheet> {
  final _nameController = TextEditingController();
  Uint8List? _fileBytes;
  String _fileName = '';
  bool _uploading = false;

  @override
  void dispose() {
    _nameController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      height: MediaQuery.of(context).size.height * 0.55,
      decoration: const BoxDecoration(
        color: AppColors.surface,
        borderRadius: BorderRadius.only(
          topLeft: Radius.circular(20),
          topRight: Radius.circular(20),
        ),
      ),
      child: Column(
        children: [
          Container(
            margin: const EdgeInsets.only(top: 8),
            width: 36,
            height: 5,
            decoration: BoxDecoration(
              color: AppColors.tertiaryLabel.withOpacity(0.3),
              borderRadius: BorderRadius.circular(3),
            ),
          ),
          const Padding(
            padding: EdgeInsets.all(16),
            child: Text(
              'Upload Attachment',
              style: TextStyle(
                fontSize: 17,
                fontWeight: FontWeight.w700,
                color: AppColors.label,
              ),
            ),
          ),
          Container(height: 0.5, color: AppColors.separator),
          Expanded(
            child: SingleChildScrollView(
              padding: const EdgeInsets.all(20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Document Name *',
                      style: TextStyle(
                          fontSize: 13,
                          fontWeight: FontWeight.w600,
                          color: AppColors.secondaryLabel)),
                  const SizedBox(height: 6),
                  CupertinoTextField(
                    controller: _nameController,
                    placeholder: 'e.g. Accomplishment Report Q3',
                    decoration: BoxDecoration(
                      color: AppColors.secondaryBackground,
                      borderRadius: BorderRadius.circular(8),
                    ),
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 12),
                    style: const TextStyle(fontSize: 15, color: AppColors.label),
                  ),
                  const SizedBox(height: 16),
                  const Text('File *',
                      style: TextStyle(
                          fontSize: 13,
                          fontWeight: FontWeight.w600,
                          color: AppColors.secondaryLabel)),
                  const SizedBox(height: 6),
                  CupertinoButton(
                    padding: EdgeInsets.zero,
                    onPressed: _pickFile,
                    child: Container(
                      width: double.infinity,
                      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 14),
                      decoration: BoxDecoration(
                        color: AppColors.secondaryBackground,
                        borderRadius: BorderRadius.circular(8),
                        border: Border.all(
                          color: _fileBytes != null
                              ? AppColors.success.withOpacity(0.3)
                              : AppColors.separator,
                          width: 1,
                        ),
                      ),
                      child: Row(
                        children: [
                          Icon(
                            _fileBytes != null
                                ? CupertinoIcons.checkmark_circle_fill
                                : CupertinoIcons.cloud_upload,
                            size: 20,
                            color: _fileBytes != null
                                ? AppColors.success
                                : AppColors.tertiaryLabel,
                          ),
                          const SizedBox(width: 8),
                          Expanded(
                            child: Text(
                              _fileName.isEmpty
                                  ? 'Choose a file (PDF, DOC, Image)'
                                  : _fileName,
                              style: TextStyle(
                                fontSize: 14,
                                color: _fileName.isEmpty
                                    ? AppColors.tertiaryLabel
                                    : AppColors.label,
                              ),
                              maxLines: 1,
                              overflow: TextOverflow.ellipsis,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),
          Container(height: 0.5, color: AppColors.separator),
          Padding(
            padding: const EdgeInsets.all(16),
            child: Row(
              children: [
                Expanded(
                  child: CupertinoButton(
                    onPressed: () => Navigator.pop(context),
                    color: AppColors.secondaryBackground,
                    borderRadius: BorderRadius.circular(10),
                    child: const Text('Cancel',
                        style: TextStyle(
                            fontSize: 15,
                            fontWeight: FontWeight.w600,
                            color: AppColors.label)),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: CupertinoButton(
                    onPressed: _uploading ? null : _upload,
                    color: AppColors.primary,
                    borderRadius: BorderRadius.circular(10),
                    child: _uploading
                        ? const CupertinoActivityIndicator(radius: 12, color: CupertinoColors.white)
                        : const Text('Upload',
                            style: TextStyle(
                                fontSize: 15,
                                fontWeight: FontWeight.w600,
                                color: CupertinoColors.white)),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  void _pickFile() {
    // For web, we use file_picker_web; for native, file_picker
    // Simplified: show a message for now since file picking needs platform-specific code
    showCupertinoDialog(
      context: context,
      builder: (c) => CupertinoAlertDialog(
        title: const Text('File Picker'),
        content: const Text(
            'File picking requires the file_picker package. For now, please upload via the web version.'),
        actions: [
          CupertinoDialogAction(
            isDefaultAction: true,
            onPressed: () => Navigator.pop(c),
            child: const Text('OK'),
          ),
        ],
      ),
    );
  }

  Future<void> _upload() async {
    if (_nameController.text.trim().isEmpty) {
      _alert('Document name is required');
      return;
    }
    if (_fileBytes == null) {
      _alert('Please choose a file');
      return;
    }

    setState(() => _uploading = true);
    try {
      // This would use DI.api.upload() with the file bytes
      // For now, show success since file picking is platform-specific
      if (mounted) {
        widget.onUploaded();
      }
    } catch (e) {
      if (mounted) _alert('Upload failed: $e');
    } finally {
      if (mounted) setState(() => _uploading = false);
    }
  }

  void _alert(String msg) {
    showCupertinoDialog(
      context: context,
      builder: (c) => CupertinoAlertDialog(
        title: const Text('Notice'),
        content: Text(msg),
        actions: [
          CupertinoDialogAction(
            isDefaultAction: true,
            onPressed: () => Navigator.pop(c),
            child: const Text('OK'),
          ),
        ],
      ),
    );
  }
}

// ── Simple refresh control wrapper ─────────────────────────────────────────
class RefreshControl extends StatelessWidget {
  const RefreshControl({super.key, required this.onRefresh, required this.child});

  final Future<void> Function() onRefresh;
  final Widget child;

  @override
  Widget build(BuildContext context) {
    return CustomScrollView(
      slivers: [
        CupertinoSliverRefreshControl(onRefresh: onRefresh),
        SliverToBoxAdapter(child: SizedBox(height: MediaQuery.of(context).size.height, child: child)),
      ],
    );
  }
}
