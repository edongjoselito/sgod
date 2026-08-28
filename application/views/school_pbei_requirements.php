<?php
$requirements = isset($requirements) && is_array($requirements) ? $requirements : array();
$disclosure = isset($disclosure) ? $disclosure : null;
$esc = function($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$submittedCount = 0;
foreach ($requirements as $requirement) { if (trim((string) ($requirement->stored_name ?? '')) !== '') $submittedCount++; }
$requirementCount = count($requirements);
$pendingCount = max(0, $requirementCount - $submittedCount);
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
        :root { --pbei-navy:#142b52; --pbei-blue:#2064a1; --pbei-aqua:#4bc1be; --pbei-gold:#f5c35b; --pbei-ink:#20314a; --pbei-muted:#64748b; --pbei-border:#e4eaf1; }
        body { background:radial-gradient(circle at 6% 4%,rgba(75,193,190,.12),transparent 22rem),radial-gradient(circle at 98% 25%,rgba(245,195,91,.11),transparent 20rem),#f6f8fb; color:var(--pbei-ink); }
        .pbei-shell { max-width:1240px; margin:0 auto; padding-bottom:50px; }
        .pbei-school-hero { position:relative; overflow:hidden; margin:24px 0; padding:clamp(28px,4vw,46px); border-radius:25px; color:#fff; background:linear-gradient(125deg,#142b52,#1b659d 62%,#2383aa); box-shadow:0 24px 56px rgba(20,43,82,.19); }
        .pbei-school-hero::after { position:absolute; right:-55px; bottom:-115px; width:245px; height:245px; content:""; border:43px solid rgba(255,255,255,.10); border-radius:50%; }
        .pbei-school-hero > * { position:relative; z-index:1; }
        .pbei-kicker { display:inline-flex; align-items:center; gap:8px; color:#ffe4a4; font-size:.72rem; font-weight:800; letter-spacing:.13em; text-transform:uppercase; }
        .pbei-school-hero h2 { margin:11px 0 0; color:#fff; font-size:clamp(1.9rem,3vw,2.6rem); font-weight:800; letter-spacing:-.035em; }
        .pbei-school-hero p { max-width:590px; margin:10px 0 0; color:rgba(255,255,255,.85); line-height:1.65; }
        .pbei-hero-footer { display:flex; flex-wrap:wrap; align-items:center; gap:12px; margin-top:24px; }
        .pbei-order-link { display:inline-flex; align-items:center; gap:7px; padding:10px 14px; border:1px solid rgba(255,255,255,.48); border-radius:11px; color:#fff; background:rgba(255,255,255,.12); font-size:.84rem; font-weight:700; text-decoration:none; transition:background .2s ease,color .2s ease,transform .2s ease; }
        .pbei-order-link:hover { color:#174b7b; background:#fff; text-decoration:none; transform:translateY(-1px); }
        .pbei-progress { display:inline-flex; align-items:center; gap:9px; padding:10px 14px; border-radius:11px; color:#fff; background:rgba(9,28,57,.25); font-size:.84rem; }
        .pbei-progress strong { color:#fff0ba; font-size:1rem; }
		.requirements-toolbar { display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:16px; margin:0 0 20px; padding:17px 20px; border:1px solid var(--pbei-border); border-radius:16px; background:#fff; box-shadow:0 10px 26px rgba(20,43,82,.05); }
		.requirements-summary { display:flex; flex-wrap:wrap; gap:18px; }
		.requirements-summary-item { display:flex; align-items:center; gap:8px; color:var(--pbei-muted); font-size:.86rem; }
		.requirements-summary-item strong { color:var(--pbei-navy); font-size:1rem; }
		.requirement-filter { display:flex; flex-wrap:wrap; gap:7px; }
		.requirement-filter button { padding:7px 11px; border:1px solid #dce5ef; border-radius:999px; color:#526276; background:#fff; font-size:.78rem; font-weight:800; }
		.requirement-filter button.active,.requirement-filter button:hover { border-color:var(--pbei-blue); color:#fff; background:var(--pbei-blue); }
		.pbei-item.is-filtered { display:none; }
        .pbei-item { border:1px solid var(--pbei-border); border-radius:19px; box-shadow:0 12px 34px rgba(20,43,82,.065); overflow:hidden; transition:transform .2s ease,box-shadow .2s ease; }
        .pbei-item + .pbei-item { margin-top:16px; }
        .pbei-item:hover { transform:translateY(-2px); box-shadow:0 18px 40px rgba(20,43,82,.10); }
        .pbei-item .card-body { padding:26px !important; }
        .pbei-order { flex:0 0 auto; display:inline-flex; align-items:center; justify-content:center; width:38px; height:38px; border-radius:12px; color:var(--pbei-blue); background:#e9f3f7; font-size:.9rem; font-weight:800; }
        .pbei-title { color:var(--pbei-navy); font-size:1.12rem; font-weight:800; }
        .pbei-title + p { color:var(--pbei-muted)!important; font-size:.92rem; line-height:1.55; }
        .pbei-status { display:inline-flex; align-items:center; gap:5px; padding:7px 11px; border-radius:999px; font-size:.75rem; font-weight:800; white-space:nowrap; }
        .pbei-status--submitted { color:#147049; background:#e1f7ed; }
        .pbei-status--pending { color:#8a5d10; background:#fff4d8; }
        .pbei-item label { color:var(--pbei-ink); font-size:.83rem; font-weight:800; }
        .pbei-item .form-control,.pbei-item .custom-file-label { border-color:#dce5ef; border-radius:9px; box-shadow:none; }
        .pbei-item textarea.form-control { min-height:106px; resize:vertical; }
        .pbei-file { display:inline-flex; align-items:center; gap:6px; margin-top:0; color:#187c56; font-size:.87rem; font-weight:700; }
        .pbei-upload-status { display:flex; align-items:center; gap:12px; margin-top:12px; }
        .pbei-remarks { color:var(--pbei-muted); font-size:.87rem; }
        .pbei-remarks--validation { color:#b45309; }.pbei-remarks--validated { color:#198754; }
        .pbei-validator-notes { margin-top:12px; padding:11px 13px; border-left:3px solid var(--pbei-blue); border-radius:8px; color:#44536b; background:#eff6fb; font-size:.87rem; line-height:1.55; white-space:pre-line; }
        .pbei-saved { color:#7b8799; font-size:.8rem; }.pbei-item .btn-primary,.disclosure-card .btn-primary { border-color:var(--pbei-blue); background:var(--pbei-blue); }
        .disclosure-card { margin-top:28px; border:1px solid var(--pbei-border); border-radius:19px; box-shadow:0 12px 34px rgba(20,43,82,.065); }
        .disclosure-card .card-body { padding:28px !important; }.disclosure-title { color:var(--pbei-navy); font-size:.92rem; font-weight:800; letter-spacing:.07em; text-transform:uppercase; }
        .custom-file-label::after { content:'Browse'; border-radius:0 8px 8px 0; color:var(--pbei-navy); background:#edf3f8; font-weight:700; }
        @media(max-width:575px){ .pbei-shell{padding-right:4px;padding-left:4px}.pbei-school-hero{margin-top:14px;border-radius:20px}.pbei-item .card-body,.disclosure-card .card-body{padding:20px!important}.pbei-item .d-flex.justify-content-between{gap:14px}.pbei-status{font-size:.68rem}.pbei-hero-footer{align-items:stretch}.pbei-order-link,.pbei-progress{justify-content:center;width:100%;}.pbei-item form > .d-flex{align-items:flex-start!important;flex-direction:column;}.pbei-item form > .d-flex .btn{width:100%;} }
    </style>
</head>
<body>
<div id="wrapper">
    <?php include('includes/top-bar.php'); ?>
    <?php include('includes/sidebar.php'); ?>
    <div class="content-page"><div class="content"><main class="container-fluid pbei-shell">
        <section class="pbei-school-hero">
            <div class="pbei-kicker"><i class="mdi mdi-trophy-outline font-20"></i> PBEI Recognition</div>
            <h2>Mandatory Requirements</h2>
            <div class="pbei-hero-footer"><a class="pbei-order-link" href="https://www.deped.gov.ph/wp-content/uploads/DO_s2026_012r.pdf" target="_blank" rel="noopener noreferrer"><i class="mdi mdi-file-document-outline"></i> Read DepEd Order No. 12, s. 2026</a><span class="pbei-progress"><i class="mdi mdi-check-circle-outline"></i><strong><?= $submittedCount; ?>/<?= $requirementCount; ?></strong> requirements submitted</span></div>
        </section>

        <?php if ($this->session->flashdata('success')): ?><div class="alert alert-success alert-dismissible fade show" role="alert"><?= $esc($this->session->flashdata('success')); ?><button type="button" class="close" data-dismiss="alert">&times;</button></div><?php endif; ?>
        <?php if ($this->session->flashdata('danger')): ?><div class="alert alert-danger alert-dismissible fade show" role="alert"><?= $esc($this->session->flashdata('danger')); ?><button type="button" class="close" data-dismiss="alert">&times;</button></div><?php endif; ?>

		<?php if (!empty($requirements)): ?>
		<section class="requirements-toolbar" aria-label="Requirements overview">
			<div class="requirements-summary">
				<div class="requirements-summary-item"><i class="mdi mdi-format-list-checks text-primary font-20"></i><span><strong><?= $requirementCount; ?></strong> total requirements</span></div>
				<div class="requirements-summary-item"><i class="mdi mdi-check-circle-outline text-success font-20"></i><span><strong><?= $submittedCount; ?></strong> submitted</span></div>
				<div class="requirements-summary-item"><i class="mdi mdi-clock-outline text-warning font-20"></i><span><strong><?= $pendingCount; ?></strong> remaining</span></div>
			</div>
			<div class="requirement-filter" role="group" aria-label="Filter requirements">
				<button type="button" class="active" data-requirement-filter="all">All</button>
				<button type="button" data-requirement-filter="pending">To submit</button>
				<button type="button" data-requirement-filter="submitted">Submitted</button>
			</div>
		</section>
		<?php endif; ?>

        <?php if (empty($requirements)): ?>
            <div class="alert alert-info">No PBEI requirements have been published yet.</div>
        <?php else: ?>
            <?php foreach ($requirements as $requirement): ?>
				<?php $hasAttachment = trim((string) ($requirement->stored_name ?? '')) !== ''; $isValidated = $hasAttachment && strtoupper(trim((string) ($requirement->submission_status ?? ''))) === 'VALIDATED'; ?>
				<section class="card pbei-item" data-requirement-status="<?= $hasAttachment ? 'submitted' : 'pending'; ?>"><div class="card-body p-4">
	                    <div class="d-flex align-items-start justify-content-between mb-3"><div class="d-flex align-items-start"><span class="pbei-order mr-3"><?= (int) $requirement->sort_order; ?></span><div><h5 class="pbei-title mb-1"><?= $esc($requirement->requirement); ?></h5><?php if (trim((string) $requirement->description) !== ''): ?><p class="text-muted mb-0"><?= nl2br($esc($requirement->description)); ?></p><?php endif; ?></div></div><span class="pbei-status <?= $hasAttachment ? 'pbei-status--submitted' : 'pbei-status--pending'; ?>"><i class="mdi <?= $hasAttachment ? 'mdi-check-circle-outline' : 'mdi-clock-outline'; ?>"></i><?= $hasAttachment ? ($isValidated ? 'Validated' : 'For Validation') : 'Not Submitted'; ?></span></div>
                    <form method="post" action="<?= base_url(); ?>Page/school_pbei_requirement_save" enctype="multipart/form-data">
                        <input type="hidden" name="requirement_id" value="<?= (int) $requirement->id; ?>">
                        <div class="form-row">
                            <div class="form-group col-md-6">
	                                <?php if (!$isValidated): ?><label><?= trim((string) $requirement->stored_name) !== '' ? 'Replace PDF' : 'Upload PDF'; ?></label><div class="custom-file"><input type="file" class="custom-file-input" id="requirementPdf<?= (int) $requirement->id; ?>" name="requirement_pdf" accept="application/pdf,.pdf"><label class="custom-file-label" for="requirementPdf<?= (int) $requirement->id; ?>">Choose a PDF file</label></div><small class="form-text text-muted">PDF only, maximum 2 MB. <?= trim((string) $requirement->stored_name) !== '' ? 'Select a new file to replace the current upload.' : ''; ?></small><?php endif; ?>
								<?php if (trim((string) $requirement->stored_name) !== ''): ?><?php $remarkText = $isValidated ? 'Validated' : 'For Validation'; ?><div class="pbei-upload-status"><a class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener" title="Preview uploaded PDF" aria-label="Preview uploaded PDF" href="<?= base_url(); ?>upload/pbei_requirement_submissions/<?= rawurlencode(basename((string) $requirement->stored_name)); ?>"><i class="mdi mdi-eye-outline mr-1"></i>Preview PDF</a><?php if (!$isValidated): ?><button class="btn btn-sm btn-outline-danger" type="submit" formaction="<?= base_url(); ?>Page/school_pbei_requirement_remove_attachment" formmethod="post" onclick="return confirm('Remove this attachment?');"><i class="mdi mdi-delete-outline mr-1"></i>Remove attachment</button><?php endif; ?><span class="pbei-remarks <?= $isValidated ? 'pbei-remarks--validated' : 'pbei-remarks--validation'; ?>"><strong>Remarks:</strong> <?= $esc($remarkText); ?></span></div><?php endif; ?>
                                <?php if (trim((string) $requirement->division_remarks) !== ''): ?><div class="pbei-validator-notes"><strong>Validator's Notes:</strong> <?= $esc($requirement->division_remarks); ?></div><?php endif; ?>
                            </div>
	                            <div class="form-group col-md-6"><label>Notes</label><textarea class="form-control" name="notes" rows="4" <?= $isValidated ? 'disabled' : ''; ?>><?= $esc($requirement->notes); ?></textarea></div>
                        </div>
	                        <div class="d-flex justify-content-between align-items-center"><span class="pbei-saved"><?php if (!empty($requirement->submitted_at)): ?>Last saved: <?= $esc(date('M j, Y g:i A', strtotime($requirement->submitted_at))); ?><?php endif; ?></span><?php if ($isValidated): ?><span class="text-success font-weight-bold"><i class="mdi mdi-lock-outline mr-1"></i>Validated — editing is locked</span><?php else: ?><button class="btn btn-primary" type="submit"><i class="mdi mdi-content-save-outline mr-1"></i>Save</button><?php endif; ?></div>
                    </form>
                </div></section>
            <?php endforeach; ?>
        <?php endif; ?>
        <section class="card disclosure-card"><div class="card-body p-4"><h5 class="disclosure-title mb-4">Disclosure on Pending Criminal Cases of Key School Officials</h5><form method="post" action="<?= base_url(); ?>Page/school_pbei_disclosure_save"><p>Does your president/member of the board/registrar/finance officer have a pending Criminal Case filed before any court in the Philippines by the Department of Education? <span class="text-danger">*</span></p><div class="form-group"><div class="custom-control custom-radio custom-control-inline"><input type="radio" id="pendingCaseYes" name="pending_case" value="Yes" class="custom-control-input" <?= ($disclosure->pending_case ?? '') === 'Yes' ? 'checked' : ''; ?> required><label class="custom-control-label" for="pendingCaseYes">Yes</label></div><div class="custom-control custom-radio custom-control-inline"><input type="radio" id="pendingCaseNo" name="pending_case" value="No" class="custom-control-input" <?= ($disclosure->pending_case ?? '') === 'No' ? 'checked' : ''; ?> required><label class="custom-control-label" for="pendingCaseNo">No</label></div></div><div class="form-group" id="criminalCaseDetails"><label>If yes, provide details and status of the Criminal Case/s. <span class="text-danger">*</span></label><textarea class="form-control" name="case_details" id="caseDetails" rows="4"><?= $esc($disclosure->case_details ?? ''); ?></textarea><small class="form-text text-muted">If yes, obtain clearance from the Legal Division under the Office of the Undersecretary for Legal and Legislative Affairs as an additional requirement for your application.</small></div><button class="btn btn-primary" type="submit"><i class="mdi mdi-content-save-outline mr-1"></i>Save Disclosure</button><a class="btn btn-outline-primary ml-2" target="_blank" href="<?= base_url(); ?>Page/pbei_sworn_statement"><i class="mdi mdi-printer-outline mr-1"></i>Print Sworn Statement</a></form></div></section>
    </main></div><?php include('includes/footer.php'); ?></div>
</div>
<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
<script>$('.custom-file-input').on('change', function () { var name = this.files.length ? this.files[0].name : 'Choose a PDF file'; $(this).next('.custom-file-label').text(name); }); function toggleCriminalCaseDetails(){var isYes=$('input[name="pending_case"]:checked').val()==='Yes';$('#criminalCaseDetails').toggle(isYes);$('#caseDetails').prop('required',isYes);} $('input[name="pending_case"]').on('change',toggleCriminalCaseDetails);toggleCriminalCaseDetails(); $('.requirement-filter button').on('click',function(){var filter=$(this).data('requirement-filter');$('.requirement-filter button').removeClass('active');$(this).addClass('active');$('.pbei-item').each(function(){var matches=filter==='all'||$(this).data('requirement-status')===filter;$(this).toggleClass('is-filtered',!matches);});});</script>
</body>
</html>
