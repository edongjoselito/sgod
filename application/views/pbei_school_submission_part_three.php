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
    </style>
</head>
<body>
<div id="wrapper">
    <?php include('includes/top-bar.php'); ?>
    <?php include('includes/sidebar.php'); ?>
    <div class="content-page"><div class="content"><main class="container-fluid">
        <section class="part-three-hero">
            <a class="btn btn-sm btn-light" href="<?= base_url(); ?>Page/pbei_school_submissions"><i class="mdi mdi-arrow-left"></i> Back to Schools</a>
            <button class="helper-trigger" type="button" data-toggle="modal" data-target="#partThreeHelperModal" aria-label="Open Part III directions" title="Part III directions"><i class="mdi mdi-help-circle-outline"></i></button>
            <div class="text-uppercase font-weight-bold small mt-3">PBEI Recognition / Evaluation</div>
            <h2>Part III: Evaluation Areas and Indicators</h2>
            <p><?= $esc($schoolName); ?> &middot; School ID: <?= $esc($schoolId); ?></p>
        </section>
        <section class="card evaluation-card">
            <div class="card-body p-3 p-md-4">
                <?php if($this->session->flashdata('success')): ?><div class="alert alert-success alert-dismissible fade show"><?= $esc($this->session->flashdata('success')); ?><button type="button" class="close" data-dismiss="alert">&times;</button></div><?php endif; ?>
                <?php if($this->session->flashdata('danger')): ?><div class="alert alert-danger alert-dismissible fade show"><?= $esc($this->session->flashdata('danger')); ?><button type="button" class="close" data-dismiss="alert">&times;</button></div><?php endif; ?>
                <div class="ratings-key"><span class="rating-key"><strong>1</strong>&nbsp; Not Meeting the Standards</span><span class="rating-key"><strong>2</strong>&nbsp; Partially Meeting the Standards</span><span class="rating-key"><strong>3</strong>&nbsp; Nearly Meeting the Standards</span><span class="rating-key"><strong>4</strong>&nbsp; Meeting the Standards</span></div><div class="autosave-note" id="autosaveStatus"><i class="mdi mdi-cloud-check-outline"></i> Changes save automatically.</div>
                <?php if(empty($groupedAreas)): ?>
                    <div class="empty-evaluations"><i class="mdi mdi-clipboard-text-outline font-24 d-block mb-2"></i>No evaluation areas have been configured yet. Add them from Evaluation Areas first.</div>
                <?php else: foreach($groupedAreas as $areaName => $rows): ?>
                    <?php $subgroups = array(); foreach($rows as $row){ $subName = trim((string) $row->sub_area); if($subName === '') $subName = 'General'; if(!isset($subgroups[$subName])) $subgroups[$subName] = array(); $subgroups[$subName][] = $row; } ?>
                    <section class="area-block"><h3 class="area-title"><?= $esc($areaName); ?></h3><div class="table-responsive"><table class="table assessment-table"><thead><tr><th>Indicators</th><th>Means of Verification<br><span class="font-weight-normal text-lowercase">(Minimum Requirements)</span></th><th colspan="4" class="text-center">Rating Scale</th><th>Save</th></tr><tr><th colspan="2"></th><th class="text-center">1</th><th class="text-center">2</th><th class="text-center">3</th><th class="text-center">4</th><th></th></tr></thead><tbody>
                    <?php foreach($subgroups as $subName => $subRows): $subStats = $summaryStats($subRows); $subKey = $areaName . '|' . ($subName === 'General' ? '' : $subName); $subFormId = 'summaryForm' . md5($subKey); ?>
                        <tr class="subarea-row"><td colspan="7"><?= $esc($subName); ?></td></tr>
                        <?php foreach($subRows as $row): $formId = 'evaluationForm' . (int) $row->id; ?><tr class="indicator-row" data-summary-area="<?= $esc($areaName); ?>" data-summary-sub="<?= $esc($subName === 'General' ? '' : $subName); ?>"><td><div class="item-copy"><?= nl2br($esc($row->indicators)); ?></div></td><td><div class="mov-copy"><?= nl2br($esc($row->movs)); ?></div></td><?php for($score=1; $score<=4; $score++): ?><td class="rating-cell"><label class="rating-choice"><input form="<?= $formId; ?>" type="radio" name="ratings[<?= (int) $row->id; ?>]" value="<?= $score; ?>"<?= (int) $row->rating === $score ? ' checked' : ''; ?>><span><?= $score; ?></span></label></td><?php endfor; ?><td class="text-center align-middle"><form id="<?= $formId; ?>" method="post" action="<?= base_url(); ?>Page/pbei_school_submission_part_three_save"><input type="hidden" name="school_id" value="<?= $esc($schoolId); ?>"><button type="submit" class="btn btn-sm btn-primary"><i class="mdi mdi-content-save-outline"></i> Save</button></form></td></tr><?php endforeach; ?>
                        <tr class="summary-row" data-summary-area="<?= $esc($areaName); ?>" data-summary-sub="<?= $esc($subName === 'General' ? '' : $subName); ?>"><th colspan="2">Total</th><td colspan="5"><span class="summary-value" data-summary-value="total"><?= $subStats['total']; ?></span> <span class="text-muted">from <span data-summary-value="count"><?= $subStats['count']; ?></span> rated indicator<?= $subStats['count'] === 1 ? '' : 's'; ?></span></td></tr><tr class="summary-row" data-summary-area="<?= $esc($areaName); ?>" data-summary-sub="<?= $esc($subName === 'General' ? '' : $subName); ?>"><th colspan="2">Average</th><td colspan="5"><span class="summary-value" data-summary-value="average"><?= $subStats['average'] === null ? '—' : number_format($subStats['average'], 2); ?></span></td></tr><tr class="summary-row" data-summary-area="<?= $esc($areaName); ?>" data-summary-sub="<?= $esc($subName === 'General' ? '' : $subName); ?>"><th colspan="2">Descriptive Equivalent</th><td colspan="5"><span class="summary-value" data-summary-value="description"><?= $esc($subStats['description']); ?></span></td></tr><tr class="summary-row" data-summary-area="<?= $esc($areaName); ?>" data-summary-sub="<?= $esc($subName === 'General' ? '' : $subName); ?>"><th colspan="2">Remarks</th><td colspan="4"><textarea form="<?= $subFormId; ?>" class="form-control form-control-sm" name="remarks" rows="2"><?= $esc($summaryRemarks[$subKey] ?? ''); ?></textarea></td><td class="text-center align-middle"><form id="<?= $subFormId; ?>" method="post" action="<?= base_url(); ?>Page/pbei_school_submission_part_three_summary_save"><input type="hidden" name="school_id" value="<?= $esc($schoolId); ?>"><input type="hidden" name="evaluation_area" value="<?= $esc($areaName); ?>"><input type="hidden" name="sub_area" value="<?= $esc($subName === 'General' ? '' : $subName); ?>"><button class="btn btn-sm btn-primary" type="submit"><i class="mdi mdi-content-save-outline"></i> Save</button></form></td></tr>
                    <?php endforeach; $areaStats = $summaryStats($rows); $areaKey = $areaName . '|'; $areaFormId = 'summaryForm' . md5($areaKey); ?>
                        <tr class="summary-row main-summary" data-summary-area="<?= $esc($areaName); ?>" data-summary-sub=""><th colspan="2"><?= $esc($areaName); ?> Total</th><td colspan="5"><span class="summary-value" data-summary-value="total"><?= $areaStats['total']; ?></span> <span class="text-muted">from <span data-summary-value="count"><?= $areaStats['count']; ?></span> rated indicator<?= $areaStats['count'] === 1 ? '' : 's'; ?></span></td></tr><tr class="summary-row main-summary" data-summary-area="<?= $esc($areaName); ?>" data-summary-sub=""><th colspan="2"><?= $esc($areaName); ?> Average</th><td colspan="5"><span class="summary-value" data-summary-value="average"><?= $areaStats['average'] === null ? '—' : number_format($areaStats['average'], 2); ?></span></td></tr><tr class="summary-row main-summary" data-summary-area="<?= $esc($areaName); ?>" data-summary-sub=""><th colspan="2"><?= $esc($areaName); ?> Descriptive Equivalent</th><td colspan="5"><span class="summary-value" data-summary-value="description"><?= $esc($areaStats['description']); ?></span></td></tr><tr class="summary-row main-summary" data-summary-area="<?= $esc($areaName); ?>" data-summary-sub=""><th colspan="2"><?= $esc($areaName); ?> Remarks</th><td colspan="4"><textarea form="<?= $areaFormId; ?>" class="form-control form-control-sm" name="remarks" rows="2"><?= $esc($summaryRemarks[$areaKey] ?? ''); ?></textarea></td><td class="text-center align-middle"><form id="<?= $areaFormId; ?>" method="post" action="<?= base_url(); ?>Page/pbei_school_submission_part_three_summary_save"><input type="hidden" name="school_id" value="<?= $esc($schoolId); ?>"><input type="hidden" name="evaluation_area" value="<?= $esc($areaName); ?>"><input type="hidden" name="sub_area" value=""><button class="btn btn-sm btn-primary" type="submit"><i class="mdi mdi-content-save-outline"></i> Save</button></form></td></tr>
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
            var value = row.querySelector('[data-summary-value]'); if(!value) return;
            if(value.dataset.summaryValue === 'total') value.textContent = total;
            if(value.dataset.summaryValue === 'count') value.textContent = count;
            if(value.dataset.summaryValue === 'average') value.textContent = average === null ? '—' : average.toFixed(2);
            if(value.dataset.summaryValue === 'description') value.textContent = average === null ? 'Not yet rated' : descriptiveEquivalent(average);
        });
    }
    document.querySelectorAll('input[type="radio"][form]').forEach(function(input){ input.addEventListener('change', function(){ var row = input.closest('.indicator-row'); if(row){ recalculate(row.dataset.summaryArea, row.dataset.summarySub); recalculate(row.dataset.summaryArea, ''); } save(document.getElementById(input.getAttribute('form'))); }); });
    document.querySelectorAll('textarea[form][name="remarks"]').forEach(function(textarea){ textarea.addEventListener('input', function(){ var id = textarea.getAttribute('form'); clearTimeout(timers[id]); timers[id] = setTimeout(function(){ save(document.getElementById(id)); }, 700); }); });
});
</script>
</body>
</html>
