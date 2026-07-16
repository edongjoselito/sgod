<?php
$ipcrfCssVersion = @filemtime(FCPATH . 'assets/css/ipcrf.css') ?: time();
$hasEmployee = !empty($current_employee);
$hasSignature = !empty($signature);
$signatureUrl = ($hasEmployee && $hasSignature)
    ? site_url('Ipcrf/signature_image/' . rawurlencode($current_employee['id'])) . '?v=' . rawurlencode($signature['updated_at'])
    : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>My Signature | SGOD MIS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/css/ipcrf.css?v=<?= $ipcrfCssVersion; ?>" rel="stylesheet" type="text/css" />
</head>
<body class="ipcrf-page">
<div id="wrapper">
    <?php include(APPPATH . 'views/includes/top-bar.php'); ?>
    <?php include(APPPATH . 'views/includes/sidebar.php'); ?>

    <div class="content-page">
        <div class="content">
            <div class="container-fluid ipcrf-shell signature-shell">
                <div class="ipcrf-title-row">
                    <div>
                        <span class="ipcrf-kicker">IPCR Identity</span>
                        <h1>My Signature</h1>
                        <p>Submit the signature image that will be placed on your IPCRF printouts.</p>
                    </div>
                    <span class="ipcrf-status <?= $hasSignature ? 'signature-status-ready' : ''; ?>">
                        <?= $hasSignature ? 'Signature on File' : 'Not Submitted'; ?>
                    </span>
                </div>

                <?php if ($success_message): ?>
                    <div class="alert alert-success signature-alert"><i class="mdi mdi-check-circle-outline"></i><span><?= htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8'); ?></span></div>
                <?php endif; ?>
                <?php if ($error_message): ?>
                    <div class="alert alert-danger signature-alert"><i class="mdi mdi-alert-circle-outline"></i><span><?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></span></div>
                <?php endif; ?>

                <?php if (!$hasEmployee): ?>
                    <div class="personal-profile-warning"><i class="mdi mdi-account-alert-outline"></i><div><strong>HRIS employee profile not found</strong><span>Your account must be linked to an HRIS employee ID before you can submit a signature.</span></div></div>
                <?php else: ?>
                    <div class="signature-layout">
                        <section class="ipcrf-panel signature-card">
                            <div class="signature-card-head">
                                <div><span>Signature Preview</span><strong><?= htmlspecialchars($current_employee['name'], ENT_QUOTES, 'UTF-8'); ?></strong><small><?= htmlspecialchars($current_employee['id'] . ' · ' . $current_employee['position'], ENT_QUOTES, 'UTF-8'); ?></small></div>
                                <i class="mdi mdi-draw"></i>
                            </div>
                            <div class="signature-preview" id="signaturePreview">
                                <?php if ($hasSignature): ?>
                                    <img src="<?= htmlspecialchars($signatureUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="Saved signature for <?= htmlspecialchars($current_employee['name'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php else: ?>
                                    <div class="signature-preview-empty"><i class="mdi mdi-fountain-pen-tip"></i><strong>No signature submitted</strong><span>Your selected image will be previewed here.</span></div>
                                <?php endif; ?>
                            </div>
                            <div class="signature-line-preview"><span><?= htmlspecialchars($current_employee['name'], ENT_QUOTES, 'UTF-8'); ?></span><small>Employee / Ratee</small></div>
                            <?php if ($hasSignature): ?>
                                <div class="signature-file-meta"><i class="mdi mdi-file-image-outline"></i><span><strong><?= htmlspecialchars($signature['original_name'], ENT_QUOTES, 'UTF-8'); ?></strong><small>Updated <?= htmlspecialchars(date('M d, Y g:i A', strtotime($signature['updated_at'])), ENT_QUOTES, 'UTF-8'); ?></small></span></div>
                            <?php endif; ?>
                        </section>

                        <section class="ipcrf-panel signature-upload-card">
                            <div class="signature-upload-head"><span class="signature-upload-icon"><i class="mdi mdi-cloud-upload-outline"></i></span><div><h2><?= $hasSignature ? 'Replace Signature' : 'Submit Signature'; ?></h2><p>Choose a clear image of your handwritten signature.</p></div></div>
                            <form method="post" action="<?= site_url('Ipcrf/signature'); ?>" enctype="multipart/form-data" id="signatureUploadForm">
                                <label class="signature-file-picker" for="signatureFile">
                                    <i class="mdi mdi-image-plus"></i>
                                    <span><strong>Select PNG or JPEG</strong><small id="signatureFileName">Transparent or white background · Maximum 2 MB</small></span>
                                    <span class="btn btn-soft-primary btn-sm">Browse</span>
                                </label>
                                <input class="signature-file-input" type="file" id="signatureFile" name="signature" accept="image/png,image/jpeg" required>
                                <div class="signature-guidance">
                                    <strong><i class="mdi mdi-lightbulb-on-outline"></i> For the best print result</strong>
                                    <ul><li>Use a tightly cropped image with only your signature.</li><li>Use a transparent PNG or a clean white background.</li><li>Make sure the writing is dark, centered and easy to read.</li></ul>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block"><i class="mdi mdi-content-save-outline mr-1"></i><?= $hasSignature ? 'Replace My Signature' : 'Save My Signature'; ?></button>
                            </form>

                            <?php if ($hasSignature): ?>
                                <form method="post" action="<?= site_url('Ipcrf/remove_signature'); ?>" class="signature-remove-form" onsubmit="return confirm('Remove your saved signature from IPCRF reports?');">
                                    <button type="submit" class="btn btn-link text-danger"><i class="mdi mdi-delete-outline mr-1"></i>Remove saved signature</button>
                                </form>
                            <?php endif; ?>
                        </section>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php include(APPPATH . 'views/includes/footer.php'); ?>
    </div>
</div>

<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
<script>
(function () {
    var input = document.getElementById('signatureFile');
    var preview = document.getElementById('signaturePreview');
    var fileName = document.getElementById('signatureFileName');
    if (!input || !preview || !fileName) return;
    input.addEventListener('change', function () {
        var file = input.files && input.files[0];
        if (!file) return;
        fileName.textContent = file.name + ' · ' + Math.max(1, Math.round(file.size / 1024)) + ' KB';
        if (file.type !== 'image/png' && file.type !== 'image/jpeg') return;
        var image = document.createElement('img');
        image.alt = 'Selected signature preview';
        image.src = URL.createObjectURL(file);
        image.onload = function () { URL.revokeObjectURL(image.src); };
        preview.innerHTML = '';
        preview.appendChild(image);
    });
})();
</script>
</body>
</html>
