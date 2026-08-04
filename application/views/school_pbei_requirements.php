<?php
$requirements = isset($requirements) && is_array($requirements) ? $requirements : array();
$disclosure = isset($disclosure) ? $disclosure : null;
$esc = function($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <?php include('includes/page-title.php'); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css">
    <style>
        body { background: #f4f7fb; }
        .pbei-school-hero { margin: 20px 0 24px; padding: 30px; border-radius: 18px; color: #fff; background: linear-gradient(135deg, #272b8c, #565de8); }
        .pbei-school-hero h2 { color: #fff; margin: 8px 0 0; }
        .pbei-school-hero p { margin: 9px 0 0; color: rgba(255,255,255,.84); }
        .pbei-item { border: 0; border-radius: 16px; box-shadow: 0 8px 24px rgba(34, 52, 87, .08); overflow: hidden; }
        .pbei-item + .pbei-item { margin-top: 18px; }
        .pbei-order { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 50%; color: #363ba8; background: #e9ebff; font-weight: 700; }
        .pbei-title { color: #30365f; font-weight: 700; }
        .pbei-status { display: inline-flex; align-items: center; gap: 5px; padding: 6px 10px; border-radius: 999px; font-size: .78rem; font-weight: 700; white-space: nowrap; }
        .pbei-status--submitted { color: #1d7047; background: #e0f5e8; }
        .pbei-status--pending { color: #7b6570; background: #f1edf0; }
        .pbei-file { display: inline-flex; align-items: center; gap: 6px; margin-top: 10px; color: #287b4d; font-size: .87rem; font-weight: 600; }
        .pbei-upload-status { display: flex; align-items: center; gap: 12px; margin-top: 10px; }
        .pbei-file { margin-top: 0; }
        .pbei-remarks { color: #59657e; font-size: .87rem; }
        .pbei-remarks--validation { color: #dc3545; }
        .pbei-remarks--validated { color: #198754; }
        .pbei-validator-notes { margin-top: 10px; padding: 10px 12px; border-left: 3px solid #565de8; border-radius: 4px; color: #495570; background: #f3f4ff; font-size: .87rem; white-space: pre-line; }
        .disclosure-card { margin-top: 28px; border: 0; border-radius: 16px; box-shadow: 0 8px 24px rgba(34, 52, 87, .08); }
        .disclosure-title { color: #30365f; font-weight: 700; text-transform: uppercase; }
        .pbei-saved { color: #74809b; font-size: .8rem; }
        .custom-file-label::after { content: 'Browse'; }
    </style>
</head>
<body>
<div id="wrapper">
    <?php include('includes/top-bar.php'); ?>
    <?php include('includes/sidebar.php'); ?>
    <div class="content-page"><div class="content"><main class="container-fluid">
        <section class="pbei-school-hero">
            <div class="d-flex align-items-center"><i class="mdi mdi-trophy-outline font-24 mr-2"></i><span class="text-uppercase font-weight-bold small">PBEI</span></div>
            <h2>Mandatory Requirements</h2>
            <p>Attach the supporting PDF and add notes for each item.</p>
        </section>

        <?php if ($this->session->flashdata('success')): ?><div class="alert alert-success alert-dismissible fade show" role="alert"><?= $esc($this->session->flashdata('success')); ?><button type="button" class="close" data-dismiss="alert">&times;</button></div><?php endif; ?>
        <?php if ($this->session->flashdata('danger')): ?><div class="alert alert-danger alert-dismissible fade show" role="alert"><?= $esc($this->session->flashdata('danger')); ?><button type="button" class="close" data-dismiss="alert">&times;</button></div><?php endif; ?>

        <?php if (empty($requirements)): ?>
            <div class="alert alert-info">No PBEI requirements have been published yet.</div>
        <?php else: ?>
            <?php foreach ($requirements as $requirement): ?>
                <?php $isValidated = ($requirement->submission_status ?? '') === 'Validated'; ?>
                <section class="card pbei-item"><div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between mb-3"><div class="d-flex align-items-start"><span class="pbei-order mr-3"><?= (int) $requirement->sort_order; ?></span><div><h5 class="pbei-title mb-1"><?= $esc($requirement->requirement); ?></h5><?php if (trim((string) $requirement->description) !== ''): ?><p class="text-muted mb-0"><?= nl2br($esc($requirement->description)); ?></p><?php endif; ?></div></div><span class="pbei-status <?= !empty($requirement->submission_id) ? 'pbei-status--submitted' : 'pbei-status--pending'; ?>"><i class="mdi <?= !empty($requirement->submission_id) ? 'mdi-check-circle-outline' : 'mdi-clock-outline'; ?>"></i><?= !empty($requirement->submission_id) ? 'Submitted' : 'Not Submitted'; ?></span></div>
                    <form method="post" action="<?= base_url(); ?>Page/school_pbei_requirement_save" enctype="multipart/form-data">
                        <input type="hidden" name="requirement_id" value="<?= (int) $requirement->id; ?>">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <?php if (!$isValidated): ?><label>Upload PDF</label><div class="custom-file"><input type="file" class="custom-file-input" id="requirementPdf<?= (int) $requirement->id; ?>" name="requirement_pdf" accept="application/pdf,.pdf"><label class="custom-file-label" for="requirementPdf<?= (int) $requirement->id; ?>">Choose a PDF file</label></div><small class="form-text text-muted">PDF only, maximum 2 MB.</small><?php endif; ?>
                                <?php if (trim((string) $requirement->stored_name) !== ''): ?><?php $remarkText = $isValidated ? 'Validated' : 'For Validation'; ?><div class="pbei-upload-status"><a class="pbei-file" target="_blank" rel="noopener" title="View uploaded PDF" aria-label="View uploaded PDF" href="<?= base_url(); ?>upload/pbei_requirement_submissions/<?= rawurlencode(basename((string) $requirement->stored_name)); ?>"><i class="mdi mdi-eye-outline font-20"></i></a><span class="pbei-remarks <?= $isValidated ? 'pbei-remarks--validated' : 'pbei-remarks--validation'; ?>"><strong>Remarks:</strong> <?= $esc($remarkText); ?></span></div><?php endif; ?>
                                <?php if (trim((string) $requirement->division_remarks) !== ''): ?><div class="pbei-validator-notes"><strong>Validator's Notes:</strong> <?= $esc($requirement->division_remarks); ?></div><?php endif; ?>
                            </div>
                            <div class="form-group col-md-6"><label>Notes</label><textarea class="form-control" name="notes" rows="4"><?= $esc($requirement->notes); ?></textarea></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center"><span class="pbei-saved"><?php if (!empty($requirement->submitted_at)): ?>Last saved: <?= $esc(date('M j, Y g:i A', strtotime($requirement->submitted_at))); ?><?php endif; ?></span><button class="btn btn-primary" type="submit"><i class="mdi mdi-content-save-outline mr-1"></i>Save</button></div>
                    </form>
                </div></section>
            <?php endforeach; ?>
        <?php endif; ?>
        <section class="card disclosure-card"><div class="card-body p-4"><h5 class="disclosure-title mb-4">Disclosure on Pending Criminal Cases of Key School Officials</h5><form method="post" action="<?= base_url(); ?>Page/school_pbei_disclosure_save"><p>Does your president/member of the board/registrar/finance officer have a pending Criminal Case filed before any court in the Philippines by the Department of Education? <span class="text-danger">*</span></p><div class="form-group"><div class="custom-control custom-radio custom-control-inline"><input type="radio" id="pendingCaseYes" name="pending_case" value="Yes" class="custom-control-input" <?= ($disclosure->pending_case ?? '') === 'Yes' ? 'checked' : ''; ?> required><label class="custom-control-label" for="pendingCaseYes">Yes</label></div><div class="custom-control custom-radio custom-control-inline"><input type="radio" id="pendingCaseNo" name="pending_case" value="No" class="custom-control-input" <?= ($disclosure->pending_case ?? '') === 'No' ? 'checked' : ''; ?> required><label class="custom-control-label" for="pendingCaseNo">No</label></div></div><div class="form-group" id="criminalCaseDetails"><label>If yes, provide details and status of the Criminal Case/s. <span class="text-danger">*</span></label><textarea class="form-control" name="case_details" id="caseDetails" rows="4"><?= $esc($disclosure->case_details ?? ''); ?></textarea><small class="form-text text-muted">If yes, obtain clearance from the Legal Division under the Office of the Undersecretary for Legal and Legislative Affairs as an additional requirement for your application.</small></div><button class="btn btn-primary" type="submit"><i class="mdi mdi-content-save-outline mr-1"></i>Save Disclosure</button><a class="btn btn-outline-primary ml-2" target="_blank" href="<?= base_url(); ?>Page/pbei_sworn_statement"><i class="mdi mdi-printer-outline mr-1"></i>Print Sworn Statement</a></form></div></section>
    </main></div><?php include('includes/footer.php'); ?></div>
</div>
<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
<script>$('.custom-file-input').on('change', function () { var name = this.files.length ? this.files[0].name : 'Choose a PDF file'; $(this).next('.custom-file-label').text(name); }); function toggleCriminalCaseDetails(){var isYes=$('input[name="pending_case"]:checked').val()==='Yes';$('#criminalCaseDetails').toggle(isYes);$('#caseDetails').prop('required',isYes);} $('input[name="pending_case"]').on('change',toggleCriminalCaseDetails);toggleCriminalCaseDetails();</script>
</body>
</html>
