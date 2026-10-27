<?php
$escape = function($value){ return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$actionUrl = site_url('Page/accomplishmentAttachments/' . (int) $record->id);
$csrf = function() use ($escape) {
    $CI =& get_instance();
    if (!$CI->config->item('csrf_protection')) {
        return '';
    }
    return '<input type="hidden" name="' . $escape($CI->security->get_csrf_token_name()) . '" value="' . $escape($CI->security->get_csrf_hash()) . '">';
};
$accept = '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv,.jpg,.jpeg,.png,.gif';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<?php include('includes/page-title.php'); ?>
<link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet">
<style>
.attachments-shell{max-width:1240px;padding:30px 24px 60px;color:#25324b}.attachment-hero{background:linear-gradient(120deg,#243b76,#5054b8);border-radius:20px;padding:30px;color:#fff;margin-bottom:24px;box-shadow:0 12px 30px #263e761c}.attachment-hero h1{color:#fff;font-size:28px;font-weight:700;margin:10px 0}.attachment-hero p{color:#e0e6ff;max-width:800px}.attachment-eyebrow{text-transform:uppercase;letter-spacing:1.6px;font-size:11px;font-weight:700}.attachment-actions{display:flex;gap:10px;flex-wrap:wrap}.attachment-layout{display:grid;grid-template-columns:minmax(280px,360px) minmax(0,1fr);gap:24px;align-items:start}.attachment-card{padding:24px;margin-bottom:16px;border:1px solid #e4e9f2;border-radius:16px;box-shadow:0 5px 16px #23395b05}.attachment-name{overflow-wrap:anywhere}.attachments-shell .btn{border-radius:9px;padding:10px 15px;font-weight:600}.attachments-shell .btn-primary{background:#454cc0;border-color:#454cc0}.attachments-shell .form-control{border:1px solid #dce3ef;border-radius:9px;min-height:44px}.attachments-shell label{font-weight:600;font-size:13px}.attachment-intro{font-size:13px;color:#6a7790;line-height:1.7}.upload-area{border:2px dashed #cbd5ef;border-radius:12px;padding:22px 16px;background:#f6f8ff;text-align:center}.upload-area>i{display:block;font-size:32px;color:#555dca}.upload-area label{display:block;margin:8px 0 14px}.upload-area input{max-width:100%;font-size:12px}.file-feedback{display:block;overflow-wrap:anywhere;color:#576586;margin-top:10px;font-size:12px}.file-feedback.invalid{color:#b52b3c}.attachment-list-header{display:flex;justify-content:space-between;align-items:center;margin:4px 0 16px;gap:12px}.attachment-list-header h2{margin:0;font-size:20px}.attachment-count{background:#e9ecff;color:#454cc0;border-radius:20px;padding:4px 10px;font-size:13px}.file-heading{display:flex;gap:14px;align-items:flex-start}.file-type{background:#edf0ff;color:#4a53bb;min-width:52px;padding:13px 8px;text-align:center;border-radius:12px;font-size:11px;font-weight:800}.file-heading h3{margin:2px 0 6px;font-size:16px;font-weight:700}.file-meta{font-size:12px;color:#7b879b;overflow-wrap:anywhere}.attachment-editor{border-top:1px solid #edf0f6;margin-top:18px;padding-top:14px}.attachment-editor summary{cursor:pointer;color:#454cc0;font-weight:600;font-size:13px;padding:4px 0}.attachment-editor form{margin-top:18px}.attachment-empty{text-align:center;padding:48px 22px;border:1px dashed #ccd5e4;border-radius:16px;color:#6c7b93;background:#fafbff}.attachment-empty i{font-size:40px;color:#8b96c4;display:block;margin-bottom:12px}.attachment-empty h3{font-size:18px}.attachment-search{margin-bottom:16px}.attachment-delete{display:flex;justify-content:flex-end}.attachment-delete .btn{font-size:12px;padding:6px 10px}.attachment-hero .btn-light{background:#ffffff1c;color:#fff;border:1px solid #ffffff45}.attachments-shell :focus-visible{outline:3px solid #98afff;outline-offset:3px}@media(max-width:900px){.attachment-layout{grid-template-columns:1fr}.attachments-shell{padding:20px 14px}.attachment-hero{padding:24px}.attachment-hero h1{font-size:24px}}
</style>
</head>
<body><div id="wrapper">
<?php include('includes/top-bar.php'); include('includes/sidebar.php'); ?>
<div class="content-page"><div class="content"><main class="container-fluid attachments-shell">
<header class="attachment-hero"><div class="attachment-eyebrow">Accomplishment workspace</div><h1 class="h3">Documents &amp; Attachments</h1>
<p class="attachment-name"><?= $escape($record->activity); ?></p>
<div class="attachment-actions mb-3">
<a class="btn btn-light" href="<?= site_url('Page/updateAccomplishments?id=' . (int) $record->id); ?>">Edit Accomplishment</a>
<a class="btn btn-light" href="<?= site_url(!empty($record->travel_request_id) ? 'Page/authority_to_travel' : 'Page/viewSecAccomplishments'); ?>">Done <i class="mdi mdi-check"></i></a>
</div></header>
<?php if($error !== ''): ?><div class="alert alert-danger" role="alert"><?= $escape($error); ?></div><?php endif; ?>
<?php if($this->session->flashdata('success')): ?><div class="alert alert-success" role="status"><?= $escape($this->session->flashdata('success')); ?></div><?php endif; ?>
<div class="attachment-layout"><section class="card attachment-card">
<h2 class="h4"><i class="mdi mdi-paperclip"></i> Add Attachment</h2>
<p class="attachment-intro">Give your document a name, then choose a file to keep with this accomplishment.</p>
<form method="post" action="<?= $escape($actionUrl); ?>" enctype="multipart/form-data">
<?= $csrf(); ?><input type="hidden" name="action" value="create">
<div class="form-group"><label for="document_name">Document/Attachment Name</label><input class="form-control" id="document_name" name="document_name" maxlength="255" required value="<?= $escape($error !== '' && $this->input->post('action') === 'create' ? $this->input->post('document_name') : ''); ?>" placeholder="e.g. Attendance Sheet or Accomplishment Report"></div>
<div class="form-group upload-area"><i class="mdi mdi-cloud-upload-outline" aria-hidden="true"></i><label for="attachment_file">Choose an attachment</label><input class="form-control-file" type="file" id="attachment_file" name="attachment_file" accept="<?= $accept; ?>" required></div>
<p class="attachment-intro">PDF, Word, Excel, PowerPoint, TXT, CSV, JPG, PNG or GIF. Maximum 15 MB per file.</p><button class="btn btn-primary btn-block" type="submit"><i class="mdi mdi-plus"></i> Add Attachment</button>
</form>
</section>
<section aria-label="Saved attachments"><div class="attachment-list-header"><h2>Saved Attachments</h2><span class="attachment-count"><?= count($attachments); ?> files</span></div><div class="attachment-search"><label class="sr-only" for="attachment_search">Search attachments</label><input type="search" id="attachment_search" class="form-control" placeholder="Search by document or file name…"></div><p id="attachment_no_results" hidden role="status">No attachments match your search.</p>
<?php if(empty($attachments)): ?><div class="attachment-empty"><i class="mdi mdi-folder-open-outline" aria-hidden="true"></i><h3>Your documents belong here</h3><p>Add your first attachment using the form. Reports, certificates, and supporting documents can each have their own name.</p></div><?php endif; ?>
<?php foreach($attachments as $attachment): $attachmentId = (int) $attachment->id; ?>
<section class="card attachment-card saved-attachment">
<div class="file-heading"><span class="file-type"><?= $escape(strtoupper(pathinfo($attachment->original_name, PATHINFO_EXTENSION)) ?: 'FILE'); ?></span><div><h3 class="h5 attachment-name"><?= $escape($attachment->document_name ?: $attachment->original_name); ?></h3>
<p class="attachment-name"><a target="_blank" rel="noopener noreferrer" href="<?= base_url('upload/accomplishment_reports/' . rawurlencode($attachment->stored_name)); ?>">View / Download: <?= $escape($attachment->original_name); ?></a></p><div class="file-meta">Uploaded <?= $escape($attachment->uploaded_at); ?></div></div></div>
<details class="attachment-editor" <?= $error !== '' && (int) $this->input->post('attachment_id') === $attachmentId ? 'open' : ''; ?>><summary><i class="mdi mdi-pencil-outline"></i> Edit name or replace file</summary>
<form method="post" action="<?= $escape($actionUrl); ?>" enctype="multipart/form-data">
<?= $csrf(); ?><input type="hidden" name="action" value="update"><input type="hidden" name="attachment_id" value="<?= $attachmentId; ?>">
<div class="form-group"><label for="name_<?= $attachmentId; ?>">Document/Attachment Name</label><input class="form-control" id="name_<?= $attachmentId; ?>" name="document_name" maxlength="255" required value="<?= $escape($error !== '' && (int) $this->input->post('attachment_id') === $attachmentId ? $this->input->post('document_name') : ($attachment->document_name ?: $attachment->original_name)); ?>"></div>
<div class="form-group"><label for="file_<?= $attachmentId; ?>">Replace Attachment (optional)</label><input class="form-control-file" type="file" id="file_<?= $attachmentId; ?>" name="attachment_file" accept="<?= $accept; ?>"><small class="form-text text-muted">Leave empty to keep the current file.</small></div>
<button class="btn btn-primary" type="submit">Save Changes</button>
</form>
</details><form method="post" action="<?= $escape($actionUrl); ?>" class="mt-2 attachment-delete" onsubmit="return confirm('Delete this attachment?');">
<?= $csrf(); ?><input type="hidden" name="action" value="delete"><input type="hidden" name="attachment_id" value="<?= $attachmentId; ?>"><button class="btn btn-outline-danger" type="submit">Delete Attachment</button>
</form>
</section>
<?php endforeach; ?>
</section></div>
</main></div></div></div>
<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
<script>
(function () {
    var search = document.getElementById('attachment_search');
    var cards = Array.prototype.slice.call(document.querySelectorAll('.saved-attachment'));
    search.addEventListener('input', function () {
        var query = search.value.trim().toLowerCase();
        var visible = 0;
        cards.forEach(function (card) {
            var match = card.querySelector('.file-heading').textContent.toLowerCase().indexOf(query) !== -1;
            card.hidden = !match;
            if (match) visible++;
        });
        document.getElementById('attachment_no_results').hidden = !query || visible > 0;
    });
    document.querySelectorAll('.attachments-shell input[type="file"]').forEach(function (input) {
        var feedback = document.createElement('span');
        feedback.className = 'file-feedback';
        feedback.setAttribute('aria-live', 'polite');
        feedback.id = input.id + '_feedback';
        input.setAttribute('aria-describedby', feedback.id);
        input.parentNode.insertBefore(feedback, input.nextSibling);
        input.addEventListener('change', function () {
            var file = input.files[0];
            var message = '';
            if (file && file.size > 15 * 1024 * 1024) message = 'Choose a file smaller than 15 MB.';
            else if (file && !/\.(pdf|docx?|xlsx?|pptx?|txt|csv|jpe?g|png|gif)$/i.test(file.name)) message = 'Choose one of the supported file types.';
            input.setCustomValidity(message);
            feedback.classList.toggle('invalid', !!message);
            feedback.textContent = message || (file ? file.name + ' · ' + (file.size / 1024 / 1024).toFixed(2) + ' MB' : '');
        });
    });
})();
</script>
</body></html>
