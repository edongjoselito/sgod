import 'package:file_picker/file_picker.dart';
import 'package:flutter/cupertino.dart';
import 'package:http/http.dart' as http;
import 'package:phosphor_flutter/phosphor_flutter.dart';
import 'package:url_launcher/url_launcher.dart';

import '../../../core/theme/app_colors.dart';
import '../../../core/widgets/app_dialogs.dart';
import '../../../ui/core/di.dart';
import '../brigada_ui.dart';

/// Workstream C — Attachments list + upload.
///
/// Shows supporting documents attached to a contribution, tax requirement,
/// or partner record. Supports upload (whitelisted file types) and delete
/// (with ownership checks on the server side).
class AttachmentsView extends StatefulWidget {
  const AttachmentsView({
    super.key,
    required this.entityType,
    required this.entityId,
    this.sy,
    this.schoolId,
  });

  final String entityType;
  final int entityId;
  final String? sy;
  final String? schoolId;

  @override
  State<AttachmentsView> createState() => _AttachmentsViewState();
}

class _AttachmentsViewState extends State<AttachmentsView> {
  bool _loading = true;
  String? _error;
  List<Map<String, dynamic>> _attachments = [];
  bool _uploading = false;

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
      final data = await DI.api.get('api_brigada/attachments', query: {
        'entity_type': widget.entityType,
        'entity_id': widget.entityId,
      });
      if (mounted) {
        final map = data as Map<String, dynamic>?;
        final list = map?['attachments'];
        setState(() {
          _attachments = list == null
              ? []
              : (list as List)
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

  Future<void> _pickAndUpload() async {
    final result = await FilePicker.platform.pickFiles(
      type: FileType.custom,
      allowedExtensions: ['pdf', 'jpg', 'jpeg', 'png', 'docx', 'xlsx'],
      withData: true,
    );
    if (result == null || result.files.isEmpty) return;
    final file = result.files.first;
    if (file.bytes == null) return;

    setState(() => _uploading = true);
    try {
      final fields = <String, String>{
        'entity_type': widget.entityType,
        'entity_id': widget.entityId.toString(),
      };
      if (widget.sy != null && widget.sy!.isNotEmpty) {
        fields['sy'] = widget.sy!;
      }
      if (widget.schoolId != null && widget.schoolId!.isNotEmpty) {
        fields['school_id'] = widget.schoolId!;
      }

      final fileMultipart = http.MultipartFile.fromBytes(
        'file',
        file.bytes!,
        filename: file.name,
      );
      await DI.api.upload(
        'api_brigada/attachment_upload',
        fields: fields,
        fileField: 'file',
        file: fileMultipart,
      );
      _load();
    } catch (e) {
      if (mounted) AppDialogs.alert(context, 'Upload Failed', '$e');
    } finally {
      if (mounted) setState(() => _uploading = false);
    }
  }

  Future<void> _delete(Map<String, dynamic> attachment) async {
    final id = int.tryParse('${attachment['id']}') ?? 0;
    final ok = await AppDialogs.confirm(
      context,
      title: 'Delete Attachment',
      message: 'Delete "${attachment['file_name']}"?',
      destructive: true,
    );
    if (ok != true) return;
    try {
      final result = await DI.write(
        endpoint: 'attachment_delete',
        entity: 'brigada_attachments',
        operation: 'delete',
        payload: {'attachment_id': id},
        prefix: 'api_brigada',
      );
      if (result == null && mounted) {
        AppDialogs.alert(context, 'Queued',
            'You are offline. This deletion has been queued and will sync automatically when you reconnect.');
      }
      _load();
    } catch (e) {
      if (mounted) AppDialogs.alert(context, 'Delete Failed', '$e');
    }
  }

  Future<void> _openAttachment(Map<String, dynamic> attachment) async {
    final path = (attachment['file_path'] ?? '').toString();
    if (path.isEmpty) return;
    final url = DI.api.resolveAssetUrl(path);
    final uri = Uri.tryParse(url);
    if (uri != null) {
      await launchUrl(uri);
    }
  }

  @override
  Widget build(BuildContext context) {
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      navigationBar: CupertinoNavigationBar(
        middle: const Text('Attachments'),
        backgroundColor: AppColors.surface,
        border: const Border(
          bottom: BorderSide(color: AppColors.separator, width: 0.5),
        ),
        trailing: _uploading
            ? const CupertinoActivityIndicator(radius: 12)
            : CupertinoButton(
                padding: EdgeInsets.zero,
                onPressed: _pickAndUpload,
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
    if (_attachments.isEmpty) {
      return BrigadaEmpty(
        icon: PhosphorIconsRegular.paperclip,
        title: 'No attachments',
        message: 'Tap + to upload a supporting document.',
      );
    }
    return ListView.builder(
      padding: const EdgeInsets.fromLTRB(16, 12, 16, 20),
      itemCount: _attachments.length,
      itemBuilder: (context, i) {
        final a = _attachments[i];
        return _AttachmentTile(
          data: a,
          onOpen: () => _openAttachment(a),
          onDelete: () => _delete(a),
        );
      },
    );
  }
}

/// A single attachment row.
class _AttachmentTile extends StatelessWidget {
  const _AttachmentTile({
    required this.data,
    required this.onOpen,
    required this.onDelete,
  });

  final Map<String, dynamic> data;
  final VoidCallback onOpen;
  final VoidCallback onDelete;

  @override
  Widget build(BuildContext context) {
    final fileName = (data['file_name'] ?? 'Unknown').toString();
    final mimeType = (data['mime_type'] ?? '').toString();
    final sizeBytes = int.tryParse('${data['size_bytes']}') ?? 0;
    final uploadedBy = (data['uploaded_by'] ?? '').toString();
    final uploadedAt = (data['uploaded_at'] ?? '').toString();

    return Container(
      margin: const EdgeInsets.only(bottom: 8),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: AppColors.surface,
        borderRadius: BorderRadius.circular(12),
      ),
      child: Row(
        children: [
          _fileIcon(mimeType),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  fileName,
                  style: const TextStyle(
                    fontSize: 15,
                    fontWeight: FontWeight.w600,
                    color: AppColors.label,
                  ),
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                ),
                const SizedBox(height: 2),
                Text(
                  [_formatSize(sizeBytes), uploadedBy, uploadedAt]
                      .where((s) => s.isNotEmpty)
                      .join(' • '),
                  style: const TextStyle(
                    fontSize: 12,
                    color: AppColors.tertiaryLabel,
                  ),
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                ),
              ],
            ),
          ),
          CupertinoButton(
            padding: EdgeInsets.zero,
            onPressed: onOpen,
            child: const Icon(CupertinoIcons.eye,
                size: 20, color: AppColors.primary),
          ),
          CupertinoButton(
            padding: EdgeInsets.zero,
            onPressed: onDelete,
            child: const Icon(CupertinoIcons.trash,
                size: 18, color: AppColors.danger),
          ),
        ],
      ),
    );
  }

  Widget _fileIcon(String mime) {
    IconData icon;
    Color color;
    if (mime.contains('pdf')) {
      icon = CupertinoIcons.doc_richtext;
      color = AppColors.danger;
    } else if (mime.contains('image')) {
      icon = CupertinoIcons.photo;
      color = AppColors.info;
    } else if (mime.contains('sheet') || mime.contains('excel')) {
      icon = CupertinoIcons.chart_bar_square;
      color = AppColors.success;
    } else if (mime.contains('word') || mime.contains('document')) {
      icon = CupertinoIcons.doc_text;
      color = AppColors.primary;
    } else {
      icon = CupertinoIcons.doc;
      color = AppColors.tertiaryLabel;
    }
    return Container(
      width: 40,
      height: 40,
      decoration: BoxDecoration(
        color: color,
        borderRadius: BorderRadius.circular(10),
      ),
      child: Icon(icon, size: 20, color: CupertinoColors.white),
    );
  }

  String _formatSize(int bytes) {
    if (bytes == 0) return '';
    if (bytes < 1024) return '${bytes}B';
    if (bytes < 1024 * 1024) return '${(bytes / 1024).toStringAsFixed(1)}KB';
    return '${(bytes / (1024 * 1024)).toStringAsFixed(1)}MB';
  }
}
