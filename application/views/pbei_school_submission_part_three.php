<?php
$areas = isset($areas) && is_array($areas) ? $areas : array();
$esc = function($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$groupedAreas = array();
foreach($areas as $area){
    $name = trim((string) $area->evaluation_area);
    if($name === '') $name = 'Evaluation Area';
    if(!isset($groupedAreas[$name])) $groupedAreas[$name] = array();
    $groupedAreas[$name][] = $area;
}
$schoolName = trim((string) ($school->schoolName ?? $schoolId));
$summaryRemarks = array();
foreach((array) ($summaries ?? array()) as $summary){
    $summaryRemarks[(string) $summary->evaluation_area . '|' . (string) $summary->sub_area] = (string) $summary->remarks;
}
$summaryStats = function($rows) {
    $scores = array();
    foreach($rows as $row){ $rating = (int) ($row->rating ?? 0); if($rating >= 1 && $rating <= 4) $scores[] = $rating; }
    $total = array_sum($scores); $count = count($scores); $average = $count ? $total / $count : null;
    $point = $average === null ? 0 : (int) round($average);
    $descriptions = array(1 => 'Not Meeting the Standards', 2 => 'Partially Meeting the Standards', 3 => 'Nearly Meeting the Standards', 4 => 'Meeting the Standards');
    return array('total' => $total, 'count' => $count, 'average' => $average, 'description' => $point ? $descriptions[$point] : 'Not yet rated');
};
$overallStats = $summaryStats($areas);
$remainingCount = count($areas) - $overallStats['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include('includes/page-title.php'); ?>
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet">
    <style>
        body{margin:0!important;padding:0!important;background:#f4f7fb}#wrapper{height:auto!important;overflow:visible!important}.navbar-custom{top:0!important}.left-side-menu{top:70px!important}.content-page{margin-top:70px!important}
        .part-three-shell{padding:0 15px 38px}.part-three-hero{position:relative;margin:20px 0 24px;padding:30px;border-radius:18px;color:#fff;background:linear-gradient(135deg,#272b8c,#565de8)}.part-three-hero h2{margin:8px 0 0;color:#fff}.part-three-hero p{margin:8px 0 0;color:rgba(255,255,255,.84)}
        .helper-trigger{position:absolute;top:28px;right:30px;width:40px;height:40px;padding:0;border:1px solid rgba(255,255,255,.55);border-radius:50%;background:rgba(255,255,255,.14);color:#fff;font-size:1.35rem}.helper-trigger:hover,.helper-trigger:focus{background:#fff;color:#3c40c6}
        .evaluation-card{border:0;border-radius:0;box-shadow:none}.area-block{margin-bottom:28px;border:1px solid #e4e7ef;border-radius:0;overflow:hidden}.area-block:last-child{margin-bottom:0}.area-title{margin:0;padding:14px 18px;background:#f4f5fb;color:#272b8c;font-size:1.1rem;font-weight:800}
        .assessment-table{margin:0}.assessment-table th{border-color:#e4e7ef!important;background:#fff;color:#4d5873;font-size:.76rem;letter-spacing:.04em;text-transform:uppercase;vertical-align:middle}.assessment-table td{border-color:#e4e7ef!important;vertical-align:top}.assessment-table th,.assessment-table td{padding:14px}.subarea-row td{padding:10px 14px;background:#f8f9fc!important;color:#272b8c;font-weight:800}.item-copy{color:#4d5c6e;line-height:1.55;white-space:pre-line}.mov-copy{color:#637184;font-size:.9rem;line-height:1.55;white-space:pre-line}
        .rating-cell{width:68px;min-width:68px;padding:8px!important;text-align:center;vertical-align:middle!important}.rating-choice{display:flex;align-items:center;justify-content:center;min-height:42px;border:1px solid #dce1ee;border-radius:7px;cursor:pointer;font-weight:700;color:#4f5877}.rating-choice:hover{border-color:#565de8;background:#f3f4ff}.rating-choice input{margin-right:4px}.ratings-key{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:10px}.rating-key{padding:6px 10px;border-radius:20px;background:#f0f1ff;color:#42489d;font-size:.8rem}.autosave-note{color:#78819a;font-size:.8rem;margin-bottom:22px}.assessment-table thead th:last-child{display:none}.assessment-table form button[type=submit]{display:none}
        .summary-row th,.summary-row td{background:#fbfcff!important;vertical-align:middle!important}.summary-row th{color:#303754;text-align:right}.summary-row .summary-value{color:#272b8c;font-weight:800}.summary-row textarea{min-height:68px}.main-summary th,.main-summary td{background:#eef0ff!important}.helper-modal .modal-content{border:0;border-radius:15px}.helper-modal .modal-header{background:linear-gradient(135deg,#272b8c,#565de8);border-radius:15px 15px 0 0}.helper-modal .modal-title,.helper-modal .modal-title i,.helper-modal .close{color:#fff!important}.helper-copy{color:#4d5873;font-size:.96rem;line-height:1.7;text-align:justify}.helper-rating-table th{background:#f4f5fb;color:#303754}.helper-rating-table td,.helper-rating-table th{vertical-align:top}.empty-evaluations{padding:48px 18px;color:#78819a;text-align:center}
        @media(max-width:767.98px){.part-three-hero{padding:22px}.helper-trigger{top:20px;right:20px}.assessment-table{min-width:900px}.rating-cell{width:58px;min-width:58px}.evaluation-card .card-body{padding:16px!important}}

        .part-three-hero{background:linear-gradient(115deg,#253b77,#5056b8);padding:28px 30px;box-shadow:0 8px 28px #263e7614;margin-bottom:20px}.part-three-hero h1{color:#fff;font-size:28px;line-height:1.35;margin:12px 40px 10px 0}.part-three-hero p{font-size:13px}.part-three-hero .small{font-size:10px;letter-spacing:1.5px}.part-three-hero .btn{border-radius:8px;font-size:12px}.evaluation-navigation{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:22px}.evaluation-navigation>a,.evaluation-navigation>.is-current{display:flex;gap:10px;align-items:center;padding:10px 16px;border:1px solid #e1e7f1;border-radius:9px;background:#fff;color:#445b8a;font-size:12px;font-weight:700;text-decoration:none}.evaluation-navigation span span,.evaluation-navigation a span{font-weight:400;font-size:11px}.evaluation-navigation>.is-current{background:#4054a4;color:#fff;border-color:#4054a4}.evaluation-overview{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;margin-bottom:24px}.evaluation-metric{display:flex;gap:14px;align-items:center;padding:19px 22px;border:1px solid #e1e7f1;background:#fff;border-radius:14px}.evaluation-metric>i{display:grid;place-items:center;flex-shrink:0;width:44px;height:44px;border-radius:12px;background:#eef0ff;color:#535bb4;font-size:23px}.evaluation-metric:nth-child(3)>i{background:#e9f6f0;color:#288169}.evaluation-metric:nth-child(4)>i{background:#fff5df;color:#a77322}.evaluation-metric strong{display:block;font-size:27px;line-height:1.3;color:#263b60}.evaluation-metric div>span{font-size:11px;color:#728199}.evaluation-card{border:1px solid #e1e7f1;border-radius:16px;box-shadow:0 5px 22px #21375805}.evaluation-toolbar{display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap;margin-bottom:22px}.evaluation-kicker{font-size:10px;letter-spacing:1.3px;text-transform:uppercase;color:#647aab}.evaluation-toolbar h2{font-size:22px;color:#283e64;margin:8px 0}.evaluation-toolbar p{font-size:12px;color:#728199;margin:0}.area-jump{max-width:350px;min-width:240px}.area-jump label{font-size:11px;font-weight:600;color:#687c9c}.area-jump select{font-size:12px;border:1px solid #dce4f0;border-radius:8px;height:40px}.ratings-key{padding:14px;background:#f7f9fd;border:1px solid #e7edf5;border-radius:10px;gap:8px}.rating-key{font-size:11px;border-radius:6px;background:#fff;border:1px solid #e6ebf5;color:#647793}.rating-key strong{color:#4054a4}.autosave-note{font-size:12px;padding:10px 0;margin-bottom:16px}.area-block{border:1px solid #e1e7f1;border-radius:12px;scroll-margin-top:90px}.area-title{display:flex;justify-content:space-between;align-items:center;gap:14px;background:#edf2fc;color:#354c7c;padding:18px 20px;font-size:15px;font-weight:700}.area-title span{font-size:10px;font-weight:500;white-space:nowrap;color:#7b8fae}.assessment-table th{font-size:10px;letter-spacing:.6px;background:#fafbfe}.assessment-table td{font-size:12px;padding:16px}.item-copy{font-size:13px;color:#344967;line-height:1.7}.mov-copy{font-size:12px;color:#7a879c;line-height:1.7}.subarea-row td{font-size:12px;background:#f7f9fd!important;color:#52668c;padding:12px 16px}.rating-choice{margin:0;min-height:44px;border-radius:8px;background:#fff;font-size:12px;gap:4px}.rating-choice:has(input:checked){background:#4054a4;border-color:#4054a4;color:#fff;box-shadow:0 3px 10px #4054a422}.rating-choice input{accent-color:#4054a4}.rating-choice:has(input:focus-visible){outline:3px solid #a4b7ef;outline-offset:2px}.summary-row th{font-size:11px;letter-spacing:0;text-transform:none}.summary-row .summary-value{font-size:12px;color:#4054a4}.summary-row textarea{font-size:12px;border:1px solid #dce4f0;border-radius:8px;padding:12px;min-width:200px}.main-summary th,.main-summary td{background:#f0f4fc!important}.part-three-shell :focus-visible{outline:3px solid #a4b7ef;outline-offset:3px}
        @media(max-width:1100px){.evaluation-overview{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:767px){.part-three-hero{padding:24px}.part-three-hero h1{font-size:23px}.evaluation-overview{gap:10px}.evaluation-metric{padding:13px;gap:9px}.evaluation-metric>i{width:32px;height:32px;font-size:19px}.evaluation-metric strong{font-size:22px}.evaluation-metric div>span{font-size:10px}.area-jump{min-width:0;max-width:none;width:100%}.area-title{font-size:13px;align-items:flex-start}.evaluation-navigation{gap:7px}.evaluation-navigation>a,.evaluation-navigation>.is-current{padding:9px 12px}.evaluation-navigation a span,.evaluation-navigation span span{display:none}.ratings-key{gap:6px;padding:10px}.rating-key{font-size:10px}.assessment-table{min-width:760px}.rating-cell{min-width:52px;width:52px}}
    </style>
</head>
<body>
<div id="wrapper">
    <?php include('includes/top-bar.php'); ?>
    <?php include('includes/sidebar.php'); ?>
    <div class="content-page"><div class="content"><main class="container-fluid part-three-shell">
        <section class="part-three-hero">
            <a class="btn btn-sm btn-light" href="<?= base_url(); ?>Page/pbei_school_submissions"><i class="mdi mdi-arrow-left"></i> Back to Schools</a>
            <button class="helper-trigger" type="button" data-toggle="modal" data-target="#partThreeHelperModal" aria-label="Open Part III directions" title="Part III directions"><i class="mdi mdi-help-circle-outline"></i></button>
            <div class="text-uppercase font-weight-bold small mt-3">PBEI Recognition / Evaluation</div>
            <h1>Part III: Evaluation Areas and Indicators</h1>
            <p><?= $esc($schoolName); ?> &middot; School ID: <?= $esc($schoolId); ?></p>
        </section>
        <nav class="evaluation-navigation" aria-label="School evaluation parts">
            <a href="<?= site_url('Page/pbei_school_submission_part_one') . '?school_id=' . rawurlencode($schoolId); ?>">Part I <span>School profile</span></a>
            <a href="<?= site_url('Page/pbei_school_submission_details') . '?school_id=' . rawurlencode($schoolId); ?>">Part II <span>Documents</span></a>
            <span class="is-current" aria-current="page">Part III <span>Evaluation</span></span>
        </nav>
        <div class="evaluation-overview" aria-label="Evaluation progress">
            <div class="evaluation-metric"><i class="mdi mdi-view-grid-outline" aria-hidden="true"></i><div><strong><?= count($groupedAreas); ?></strong><span>Evaluation areas</span></div></div>
            <div class="evaluation-metric"><i class="mdi mdi-format-list-checks" aria-hidden="true"></i><div><strong><?= count($areas); ?></strong><span>Total indicators</span></div></div>
            <div class="evaluation-metric"><i class="mdi mdi-check-circle-outline" aria-hidden="true"></i><div><strong id="ratedIndicatorCount"><?= $overallStats['count']; ?></strong><span>Rated indicators</span></div></div>
            <div class="evaluation-metric"><i class="mdi mdi-clock-outline" aria-hidden="true"></i><div><strong id="remainingIndicatorCount"><?= $remainingCount; ?></strong><span>Not yet rated</span></div></div>
        </div>
        <section class="card evaluation-card">
            <div class="card-body p-3 p-md-4">
                <?php if($this->session->flashdata('success')): ?><div class="alert alert-success alert-dismissible fade show"><?= $esc($this->session->flashdata('success')); ?><button type="button" class="close" data-dismiss="alert">&times;</button></div><?php endif; ?>
                <?php if($this->session->flashdata('danger')): ?><div class="alert alert-danger alert-dismissible fade show"><?= $esc($this->session->flashdata('danger')); ?><button type="button" class="close" data-dismiss="alert">&times;</button></div><?php endif; ?>
                <div class="evaluation-toolbar"><div><span class="evaluation-kicker">Assessment workspace</span><h2>Rate the Indicators</h2><p>Choose a rating from 1 to 4 for each indicator. Add remarks below each area.</p></div><?php if ($groupedAreas): ?><div class="area-jump"><label for="evaluationAreaJump">Jump to evaluation area</label><select class="form-control" id="evaluationAreaJump"><option value="">Choose an area…</option><?php foreach ($groupedAreas as $jumpName=>$jumpRows): ?><option value="area-<?= md5($jumpName); ?>"><?= $esc($jumpName); ?></option><?php endforeach; ?></select></div><?php endif; ?></div>
                <div class="ratings-key"><span class="rating-key"><strong>1</strong>&nbsp; Not Meeting the Standards</span><span class="rating-key"><strong>2</strong>&nbsp; Partially Meeting the Standards</span><span class="rating-key"><strong>3</strong>&nbsp; Nearly Meeting the Standards</span><span class="rating-key"><strong>4</strong>&nbsp; Meeting the Standards</span></div><div class="autosave-note" id="autosaveStatus" role="status" aria-live="polite"><i class="mdi mdi-cloud-check-outline"></i> Changes save automatically.</div>
                <?php if(empty($groupedAreas)): ?>
                    <div class="empty-evaluations"><i class="mdi mdi-clipboard-text-outline font-24 d-block mb-2"></i>No evaluation areas have been configured yet. Add them from Evaluation Areas first.</div>
                <?php else: foreach($groupedAreas as $areaName => $rows): ?>
                    <?php $subgroups = array(); foreach($rows as $row){ $subName = trim((string) $row->sub_area); if($subName === '') $subName = 'General'; if(!isset($subgroups[$subName])) $subgroups[$subName] = array(); $subgroups[$subName][] = $row; } ?>
                    <section class="area-block" id="area-<?= md5($areaName); ?>" tabindex="-1"><h3 class="area-title"><?= $esc($areaName); ?><span><?= count($rows); ?> indicators</span></h3><div class="table-responsive"><table class="table assessment-table"><thead><tr><th>Indicators</th><th>Means of Verification<br><span class="font-weight-normal text-lowercase">(Minimum Requirements)</span></th><th colspan="4" class="text-center">Rating Scale</th><th>Save</th></tr><tr><th colspan="2"></th><th class="text-center">1</th><th class="text-center">2</th><th class="text-center">3</th><th class="text-center">4</th><th></th></tr></thead><tbody>
                    <?php foreach($subgroups as $subName => $subRows): $subStats = $summaryStats($subRows); $subKey = $areaName . '|' . ($subName === 'General' ? '' : $subName); $subFormId = 'summaryForm' . md5($subKey); ?>
                        <tr class="subarea-row"><td colspan="7"><?= $esc($subName); ?></td></tr>
                        <?php foreach($subRows as $row): $formId = 'evaluationForm' . (int) $row->id; ?><tr class="indicator-row" data-summary-area="<?= $esc($areaName); ?>" data-summary-sub="<?= $esc($subName === 'General' ? '' : $subName); ?>"><td><div class="item-copy"><?= nl2br($esc($row->indicators)); ?></div></td><td><div class="mov-copy"><?= nl2br($esc($row->movs)); ?></div></td><?php for($score=1; $score<=4; $score++): ?><td class="rating-cell"><label class="rating-choice"><input form="<?= $formId; ?>" type="radio" aria-label="<?= $esc('Rating ' . $score . ' for ' . $row->indicators); ?>" name="ratings[<?= (int) $row->id; ?>]" value="<?= $score; ?>"<?= (int) $row->rating === $score ? ' checked' : ''; ?>><span><?= $score; ?></span></label></td><?php endfor; ?><td class="text-center align-middle"><form id="<?= $formId; ?>" method="post" action="<?= base_url(); ?>Page/pbei_school_submission_part_three_save"><input type="hidden" name="school_id" value="<?= $esc($schoolId); ?>"><button type="submit" class="btn btn-sm btn-primary"><i class="mdi mdi-content-save-outline"></i> Save</button></form></td></tr><?php endforeach; ?>
                        <tr class="summary-row" data-summary-area="<?= $esc($areaName); ?>" data-summary-sub="<?= $esc($subName === 'General' ? '' : $subName); ?>"><th colspan="2">Total</th><td colspan="5"><span class="summary-value" data-summary-value="total"><?= $subStats['total']; ?></span> <span class="text-muted">from <span data-summary-value="count"><?= $subStats['count']; ?></span> rated indicator<?= $subStats['count'] === 1 ? '' : 's'; ?></span></td></tr><tr class="summary-row" data-summary-area="<?= $esc($areaName); ?>" data-summary-sub="<?= $esc($subName === 'General' ? '' : $subName); ?>"><th colspan="2">Average</th><td colspan="5"><span class="summary-value" data-summary-value="average"><?= $subStats['average'] === null ? '—' : number_format($subStats['average'], 2); ?></span></td></tr><tr class="summary-row" data-summary-area="<?= $esc($areaName); ?>" data-summary-sub="<?= $esc($subName === 'General' ? '' : $subName); ?>"><th colspan="2">Descriptive Equivalent</th><td colspan="5"><span class="summary-value" data-summary-value="description"><?= $esc($subStats['description']); ?></span></td></tr><tr class="summary-row" data-summary-area="<?= $esc($areaName); ?>" data-summary-sub="<?= $esc($subName === 'General' ? '' : $subName); ?>"><th colspan="2">Remarks</th><td colspan="4"><textarea form="<?= $subFormId; ?>" class="form-control form-control-sm" aria-label="Evaluation remarks" placeholder="Add observations or recommendations…" name="remarks" rows="2"><?= $esc($summaryRemarks[$subKey] ?? ''); ?></textarea></td><td class="text-center align-middle"><form id="<?= $subFormId; ?>" method="post" action="<?= base_url(); ?>Page/pbei_school_submission_part_three_summary_save"><input type="hidden" name="school_id" value="<?= $esc($schoolId); ?>"><input type="hidden" name="evaluation_area" value="<?= $esc($areaName); ?>"><input type="hidden" name="sub_area" value="<?= $esc($subName === 'General' ? '' : $subName); ?>"><button class="btn btn-sm btn-primary" type="submit"><i class="mdi mdi-content-save-outline"></i> Save</button></form></td></tr>
                    <?php endforeach; $areaStats = $summaryStats($rows); $areaKey = $areaName . '|'; $areaFormId = 'summaryForm' . md5($areaKey); ?>
                        <tr class="summary-row main-summary" data-summary-area="<?= $esc($areaName); ?>" data-summary-sub=""><th colspan="2"><?= $esc($areaName); ?> Total</th><td colspan="5"><span class="summary-value" data-summary-value="total"><?= $areaStats['total']; ?></span> <span class="text-muted">from <span data-summary-value="count"><?= $areaStats['count']; ?></span> rated indicator<?= $areaStats['count'] === 1 ? '' : 's'; ?></span></td></tr><tr class="summary-row main-summary" data-summary-area="<?= $esc($areaName); ?>" data-summary-sub=""><th colspan="2"><?= $esc($areaName); ?> Average</th><td colspan="5"><span class="summary-value" data-summary-value="average"><?= $areaStats['average'] === null ? '—' : number_format($areaStats['average'], 2); ?></span></td></tr><tr class="summary-row main-summary" data-summary-area="<?= $esc($areaName); ?>" data-summary-sub=""><th colspan="2"><?= $esc($areaName); ?> Descriptive Equivalent</th><td colspan="5"><span class="summary-value" data-summary-value="description"><?= $esc($areaStats['description']); ?></span></td></tr><tr class="summary-row main-summary" data-summary-area="<?= $esc($areaName); ?>" data-summary-sub=""><th colspan="2"><?= $esc($areaName); ?> Remarks</th><td colspan="4"><textarea form="<?= $areaFormId; ?>" class="form-control form-control-sm" aria-label="Evaluation remarks" placeholder="Add observations or recommendations…" name="remarks" rows="2"><?= $esc($summaryRemarks[$areaKey] ?? ''); ?></textarea></td><td class="text-center align-middle"><form id="<?= $areaFormId; ?>" method="post" action="<?= base_url(); ?>Page/pbei_school_submission_part_three_summary_save"><input type="hidden" name="school_id" value="<?= $esc($schoolId); ?>"><input type="hidden" name="evaluation_area" value="<?= $esc($areaName); ?>"><input type="hidden" name="sub_area" value=""><button class="btn btn-sm btn-primary" type="submit"><i class="mdi mdi-content-save-outline"></i> Save</button></form></td></tr>
                    </tbody></table></div></section>
                <?php endforeach; endif; ?>
            </div>
        </section>
    </main></div><?php include('includes/footer.php'); ?></div>
</div>
<div class="modal fade helper-modal" id="partThreeHelperModal" tabindex="-1" role="dialog" aria-labelledby="partThreeHelperTitle" aria-hidden="true"><div class="modal-dialog modal-lg modal-dialog-centered" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="partThreeHelperTitle"><i class="mdi mdi-information-outline"></i> Part III Directions</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body px-md-4 py-4"><p class="helper-copy">Please rate the compliance of the private Senior High School (SHS) with the regulatory indicators for each evaluation area using the four-point Likert scale, along with their corresponding descriptive equivalents and interpretations. The scale is designed to assess the level of conformance for each item, without a neutral option, to enable clear and decisive interpretation and support appropriate recommendations.</p><p class="font-weight-bold helper-copy">The completion of this portion shall be made collaboratively by the members of the MEIT, upon deliberation, during their Ocular Visit to the private SHS.</p><h6 class="font-weight-bold mt-4">Rating Scale for Each Indicator</h6><p class="helper-copy">The four-point Likert scale reflects the degree of attainment relative to compliance standards for the issuance of Government Recognition. Each scale point is accompanied by a qualitative descriptor, requiring evaluators to arrive at a definitive assessment of compliance.</p><div class="table-responsive"><table class="table table-bordered table-sm helper-rating-table mb-0"><thead><tr><th>Point</th><th>Descriptive Equivalent</th><th>Interpretation</th></tr></thead><tbody><tr><td class="text-center font-weight-bold">4</td><td><strong>Meeting the standards</strong></td><td>The prescribed criteria are fully met. There are sustainable efforts to level up conditions and practices. Documentary requirements are complete, current, and compliant with relevant laws, regulations, and DepEd policies.</td></tr><tr><td class="text-center font-weight-bold">3</td><td><strong>Nearly meeting the standards</strong></td><td>The prescribed criteria are nearly met. Documentary requirements have a few missing parts and/or errors.</td></tr><tr><td class="text-center font-weight-bold">2</td><td><strong>Partially meeting the standards</strong></td><td>The prescribed criteria are partially met. The documentary requirements have several missing parts and/or errors.</td></tr><tr><td class="text-center font-weight-bold">1</td><td><strong>Not meeting the standards</strong></td><td>No evidence of compliance with the criteria, laws, regulations, and DepEd policies has been noted.</td></tr></tbody></table></div></div><div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">Got it</button></div></div></div></div>
<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    var status = document.getElementById('autosaveStatus'), timers = {};
    var areaJump = document.getElementById('evaluationAreaJump');
    if (areaJump) areaJump.addEventListener('change', function(){
        var target = document.getElementById(this.value);
        if (target) { target.scrollIntoView({behavior:'auto', block:'start'}); target.focus({preventScroll:true}); }
    });
    function updateProgress(){
        var indicatorRows = document.querySelectorAll('.indicator-row');
        var rated = 0;
        indicatorRows.forEach(function(row){ if(row.querySelector('input[type="radio"]:checked')) rated++; });
        document.getElementById('ratedIndicatorCount').textContent = rated;
        document.getElementById('remainingIndicatorCount').textContent = indicatorRows.length - rated;
    }

    function setStatus(message, failed){ if(status){ status.innerHTML = '<i class="mdi ' + (failed ? 'mdi-alert-circle-outline' : 'mdi-cloud-check-outline') + '"></i> ' + message; status.className = 'autosave-note' + (failed ? ' text-danger' : ''); } }
    function save(form){
        if(!form || !form.action) return;
        setStatus('Saving changes…');
        fetch(form.action, {method:'POST', body:new FormData(form), headers:{'X-Requested-With':'XMLHttpRequest'}})
            .then(function(response){ if(!response.ok) throw new Error('Save failed'); return response.json(); })
            .then(function(){ setStatus('All changes saved.'); })
            .catch(function(){ setStatus('Could not save changes. Please try again.', true); });
    }
    function descriptiveEquivalent(average){ if(average >= 3.5) return 'Meeting the Standards'; if(average >= 2.5) return 'Nearly Meeting the Standards'; if(average >= 1.5) return 'Partially Meeting the Standards'; return 'Not Meeting the Standards'; }
    function recalculate(area, subArea){
        var rows = Array.prototype.filter.call(document.querySelectorAll('.indicator-row'), function(row){ return row.dataset.summaryArea === area && (subArea === '' || row.dataset.summarySub === subArea); });
        var scores = rows.map(function(row){ var selected = row.querySelector('input[type="radio"]:checked'); return selected ? parseInt(selected.value, 10) : 0; }).filter(function(score){ return score > 0; });
        var total = scores.reduce(function(sum, score){ return sum + score; }, 0), count = scores.length, average = count ? total / count : null;
        Array.prototype.filter.call(document.querySelectorAll('.summary-row'), function(row){ return row.dataset.summaryArea === area && row.dataset.summarySub === subArea; }).forEach(function(row){
            row.querySelectorAll('[data-summary-value]').forEach(function(value){
            if(value.dataset.summaryValue === 'total') value.textContent = total;
            if(value.dataset.summaryValue === 'count') value.textContent = count;
            if(value.dataset.summaryValue === 'average') value.textContent = average === null ? '—' : average.toFixed(2);
            if(value.dataset.summaryValue === 'description') value.textContent = average === null ? 'Not yet rated' : descriptiveEquivalent(average);
            });
        });
    }
    document.querySelectorAll('input[type="radio"][form]').forEach(function(input){ input.addEventListener('change', function(){ var row = input.closest('.indicator-row'); if(row){ recalculate(row.dataset.summaryArea, row.dataset.summarySub); recalculate(row.dataset.summaryArea, ''); } updateProgress(); save(document.getElementById(input.getAttribute('form'))); }); });
    document.querySelectorAll('textarea[form][name="remarks"]').forEach(function(textarea){ textarea.addEventListener('input', function(){ var id = textarea.getAttribute('form'); clearTimeout(timers[id]); timers[id] = setTimeout(function(){ save(document.getElementById(id)); }, 700); }); });
});
</script>
</body>
</html>
