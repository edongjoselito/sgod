<?php
$ipcrfCssVersion = @filemtime(FCPATH . 'assets/css/ipcrf.css') ?: time();
$reviewCount = count((array) $forms);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Assigned IPCRF Reviews | SGOD MIS</title>
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
            <div class="container-fluid ipcrf-shell">
                <div class="ipcrf-title-row">
                    <div><span class="ipcrf-kicker">Assigned Rater Workspace</span><h1>IPCRFs Submitted for Review</h1><p>Review and validate only the employee IPCRFs assigned to you as rater.</p></div>
                    <span class="ipcrf-status"><?= $reviewCount; ?> Pending</span>
                </div>

                <div class="rater-queue-toolbar">
                    <a class="btn btn-soft-secondary" href="<?= site_url('Ipcrf'); ?>"><i class="mdi mdi-account-card-details-outline mr-1"></i>My IPCRF</a>
                    <?php if (!empty($current_employee)): ?>
                    <span><i class="mdi mdi-account-check-outline mr-1"></i>Signed in as <strong><?= htmlspecialchars($current_employee['name'], ENT_QUOTES, 'UTF-8'); ?></strong> · <?= htmlspecialchars($current_employee['id'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <?php endif; ?>
                </div>

                <section class="ipcrf-panel rater-queue-panel">
                    <div class="rater-queue-head"><div><h2>Pending Rater Validation</h2><p>The list is filtered by your HRIS employee ID and contains only records currently Submitted to Rater.</p></div><span><?= $reviewCount; ?> item<?= $reviewCount === 1 ? '' : 's'; ?></span></div>

                    <?php if (empty($current_employee)): ?>
                    <div class="personal-profile-warning m-3"><i class="mdi mdi-account-alert-outline"></i><div><strong>HRIS employee profile not found</strong><span>Your account cannot receive assigned IPCRF reviews until it is linked to an HRIS employee ID.</span></div></div>
                    <?php elseif ($reviewCount): ?>
                    <div class="rater-review-list">
                        <?php foreach ($forms as $review): ?>
                        <a class="rater-review-card" href="<?= site_url('Ipcrf/index/' . (int) $review['id']); ?>">
                            <span class="rater-review-icon"><i class="mdi mdi-file-document-edit-outline"></i></span>
                            <div class="rater-review-person"><span>Employee</span><strong><?= htmlspecialchars($review['employee_name'], ENT_QUOTES, 'UTF-8'); ?></strong><small><?= htmlspecialchars($review['employee_id'] . ' · ' . $review['position'], ENT_QUOTES, 'UTF-8'); ?></small><small><?= htmlspecialchars($review['office'], ENT_QUOTES, 'UTF-8'); ?></small></div>
                            <div class="rater-review-period"><span>Performance Period</span><strong><?= htmlspecialchars(date('M d, Y', strtotime($review['period_start'])) . ' – ' . date('M d, Y', strtotime($review['period_end'])), ENT_QUOTES, 'UTF-8'); ?></strong><small>Submitted <?= htmlspecialchars($review['submitted_at'] ? date('M d, Y g:i A', strtotime($review['submitted_at'])) : 'recently', ENT_QUOTES, 'UTF-8'); ?></small></div>
                            <div class="rater-review-action"><span>Submitted to Rater</span><strong>Review &amp; Validate <i class="mdi mdi-arrow-right"></i></strong></div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="rater-queue-empty"><i class="mdi mdi-clipboard-check-outline"></i><h3>No IPCRFs waiting for review</h3><p>New submissions assigned to you will automatically appear here.</p><a class="btn btn-primary" href="<?= site_url('Ipcrf'); ?>">Return to My IPCRF</a></div>
                    <?php endif; ?>
                </section>
            </div>
        </div>
        <?php include(APPPATH . 'views/includes/footer.php'); ?>
    </div>
</div>

<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
</body>
</html>
