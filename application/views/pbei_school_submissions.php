<?php
$schools = isset($schools) && is_array($schools) ? $schools : array();
$esc = function($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$completionState = function($school) {
    if ((int) $school->applicable_requirement_count <= 0) return 'Not applicable';
    if ((int) $school->submitted_requirement_count >= (int) $school->applicable_requirement_count) return 'Complete';
    return (int) $school->submitted_requirement_count > 0 ? 'In progress' : 'Not started';
};
$stateCounts = array('Complete'=>0, 'In progress'=>0, 'Not started'=>0, 'Not applicable'=>0);
foreach ($schools as $school) $stateCounts[$completionState($school)]++;
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
    <link href="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet">
    <style>body{background:#f4f7fb}.submissions-hero{margin:20px 0 24px;padding:30px;border-radius:18px;color:#fff;background:linear-gradient(135deg,#272b8c,#565de8)}.submissions-hero h2{color:#fff;margin:8px 0 0}.submissions-hero p{margin:9px 0 0;color:rgba(255,255,255,.84)}.school-name{font-weight:700;color:#2d3651}.school-id{display:block;margin-top:3px;color:#77819a;font-size:.78rem}
.submissions-workspace{padding-bottom:30px}.submissions-hero{background:linear-gradient(115deg,#253b77,#5056b8);padding:28px 30px;box-shadow:0 8px 28px #263e7614;margin-bottom:20px}.submissions-hero h1{font-size:30px;color:white;margin:12px 0 10px}.submissions-hero p{font-size:13px}.submissions-hero .small{font-size:10px;letter-spacing:1.8px}.submissions-overview{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;margin-bottom:24px}.submission-metric{display:flex;gap:14px;align-items:center;padding:19px 22px;border:1px solid #e1e7f1;background:white;border-radius:14px}.metric-icon{width:44px;height:44px;display:grid;place-items:center;flex-shrink:0;font-size:23px;color:#535bb4;background:#eef0ff;border-radius:12px}.submission-metric:nth-child(2) .metric-icon{background:#e9f6f0;color:#288169}.submission-metric:nth-child(3) .metric-icon{background:#eaf3fc;color:#397eb7}.submission-metric:nth-child(4) .metric-icon{background:#fff5df;color:#a77322}.submission-metric strong{display:block;font-size:27px;line-height:1.3;color:#263b60}.submission-metric div>span{font-size:11px;color:#728199}.submissions-card{border:1px solid #e1e7f1;border-radius:16px;box-shadow:0 5px 22px #21375805}.submissions-card .card-body{padding:24px}.submission-list-heading{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap}.submission-kicker{font-size:10px;text-transform:uppercase;letter-spacing:1.3px;color:#647aab}.submission-list-heading h2{font-size:22px;color:#283e64;margin:8px 0}.submission-list-heading>span{font-size:12px;color:#536a9e;padding:9px 12px;border:1px solid #e1e7f1;background:#f0f4fc;border-radius:8px}.submission-note{font-size:12px;color:#728199;margin-bottom:20px}.submission-tools{display:flex;align-items:flex-end;gap:16px;padding:16px 18px;margin-bottom:22px;background:#f7f9fd;border:1px solid #e7edf5;border-radius:10px}.submission-tools>div{flex:0 1 280px}.submission-tools label{display:block;font-size:11px;font-weight:600;color:#687c9c;margin-bottom:6px}.submission-tools select{height:40px;font-size:12px;border:1px solid #dce4f0;border-radius:8px}.submission-tools button{margin-left:auto;background:white;color:#4a6098;border:1px solid #dce4f0;border-radius:8px;padding:10px 14px;font-size:12px;cursor:pointer}.submissions-card thead th{font-size:10px;text-transform:uppercase;letter-spacing:.7px;background:#f4f7fc;color:#647793;padding:14px 12px}.submissions-card tbody td{font-size:12px;padding:18px 12px;vertical-align:middle;border-bottom:1px solid #edf1f7}.school-name{font-size:13px;color:#2c4269}.school-id{font-size:10px;margin-top:6px}.completion-badge{display:inline-block;padding:4px 8px;border-radius:6px;background:#f0f3fa;color:#677b9c;font-size:10px;margin-bottom:8px}.completion-badge.is-complete{background:#e9f6ef;color:#287354}.evaluation-actions{display:flex;gap:6px;justify-content:flex-end;flex-wrap:wrap}.evaluation-actions .btn{font-size:11px;padding:8px 11px;border-radius:7px;margin:0!important;white-space:nowrap}.evaluation-actions .btn-primary{background:#4054a4;border-color:#4054a4}.submissions-card .dataTables_filter input,.submissions-card .dataTables_length select{border:1px solid #dce4f0;border-radius:8px;min-height:40px;font-size:12px}.submissions-card .dataTables_info,.submissions-card .dataTables_length label{font-size:12px;color:#70819c}.submissions-workspace :focus-visible{outline:3px solid #a4b7ef;outline-offset:3px}.submissions-card tbody tr.child .dtr-data{white-space:normal}.submissions-card tbody tr.child ul.dtr-details{width:100%}
@media(max-width:1100px){.submissions-overview{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:575px){.submissions-hero{padding:24px}.submissions-hero h1{font-size:26px}.submissions-overview{gap:10px}.submission-metric{padding:13px;gap:9px}.metric-icon{width:32px;height:32px;font-size:19px}.submission-metric strong{font-size:22px}.submission-metric div>span{font-size:10px}.submissions-card .card-body{padding:16px}.submission-tools{flex-wrap:wrap}.submission-tools>div{flex:1 1 100%}.submission-tools button{width:100%}.submissions-card .dataTables_filter label{display:block}.submissions-card .dataTables_filter input{width:100%;margin:6px 0}.evaluation-actions{justify-content:flex-start}}
</style>
</head>
<body>
<div id="wrapper">
    <?php include('includes/top-bar.php'); ?>
    <?php include('includes/sidebar.php'); ?>
    <div class="content-page"><div class="content"><main class="container-fluid submissions-workspace">
        <section class="submissions-hero"><div class="d-flex align-items-center"><i class="mdi mdi-trophy-outline font-24 mr-2"></i><span class="text-uppercase font-weight-bold small">PBEI Recognition</span></div><h1>School Submissions</h1><p>Select a school to review its documentary requirement submissions.</p></section>
        <div class="submissions-overview" aria-label="Submission summary">
            <?php foreach (array(array(count($schools),'Schools','mdi-school-outline'), array($stateCounts['Complete'],'Complete submissions','mdi-check-circle-outline'), array($stateCounts['In progress'],'In progress','mdi-progress-clock'), array($stateCounts['Not started'],'Not started','mdi-file-outline')) as $metric): ?>
            <div class="submission-metric"><span class="metric-icon"><i class="mdi <?= $esc($metric[2]); ?>" aria-hidden="true"></i></span><div><strong><?= number_format($metric[0]); ?></strong><span><?= $esc($metric[1]); ?></span></div></div>
            <?php endforeach; ?>
        </div>
        <section class="card submissions-card"><div class="card-body">
            <div class="submission-list-heading"><div><span class="submission-kicker">Documentary requirements</span><h2>School Submission Directory</h2></div><span id="submissionResults" role="status"><?= count($schools); ?> schools</span></div>
            <p class="submission-note">Review completion and open Parts I, II, or III to evaluate a school. Completion reflects submitted requirements, not evaluation approval.</p>
            <div class="submission-tools"><div><label for="submissionStatusFilter">Submission completion</label><select id="submissionStatusFilter" class="form-control"><option value="">All schools</option><?php foreach ($stateCounts as $state=>$count): ?><option value="<?= $esc($state); ?>"><?= $esc($state); ?> (<?= $count; ?>)</option><?php endforeach; ?></select></div><button type="button" id="resetSubmissionFilters"><i class="mdi mdi-refresh" aria-hidden="true"></i> Reset filters</button></div>
            <div class="submission-table-shell"><table id="schoolsSubmissionsTable" class="table table-hover w-100"><thead><tr><th>School</th><th class="text-right">Submission Completion</th><th class="text-right">Evaluation</th></tr></thead><tbody><?php foreach ($schools as $school): $state = $completionState($school); ?><tr data-completion-state="<?= $esc($state); ?>"><td><span class="school-name"><?= $esc(trim((string) $school->schoolName) !== '' ? $school->schoolName : $school->school_id); ?></span><span class="school-id"><?= $esc($school->school_id); ?></span></td><td class="text-right" data-order="<?= (int) $school->submission_completion_percentage; ?>">
            <span class="completion-badge <?= $state === 'Complete' ? 'is-complete' : ''; ?>"><?= $esc($state); ?></span><div class="d-flex align-items-center justify-content-end">
                <div class="progress flex-grow-1 mr-2" style="height:8px;min-width:90px;max-width:180px">
                    <div class="progress-bar bg-primary" role="progressbar" style="width:<?= (int) $school->submission_completion_percentage; ?>%" aria-valuenow="<?= (int) $school->submission_completion_percentage; ?>" aria-valuemin="0" aria-valuemax="100" aria-label="Submission completion"></div>
                </div>
                <span class="text-nowrap"><strong><?= (int) $school->submission_completion_percentage; ?>%</strong> <small class="text-muted">(<?= (int) $school->submitted_requirement_count; ?>/<?= (int) $school->applicable_requirement_count; ?> applicable)</small></span>
            </div>
        </td><td class="text-right"><div class="evaluation-actions"><a class="btn btn-sm btn-outline-primary mr-1" href="<?= base_url(); ?>Page/pbei_school_submission_part_one?school_id=<?= rawurlencode($school->school_id); ?>"><i class="mdi mdi-school-outline"></i> Part I</a><a class="btn btn-sm btn-primary mr-1" href="<?= base_url(); ?>Page/pbei_school_submission_details?school_id=<?= rawurlencode($school->school_id); ?>"><i class="mdi mdi-file-document-outline"></i> Part II</a><a class="btn btn-sm btn-outline-success" href="<?= base_url(); ?>Page/pbei_school_submission_part_three?school_id=<?= rawurlencode($school->school_id); ?>"><i class="mdi mdi-clipboard-check-outline"></i> Part III</a></div></td></tr><?php endforeach; ?></tbody></table></div></div></section>
    </main></div><?php include('includes/footer.php'); ?></div>
</div>
<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/dataTables.responsive.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.js"></script>
<script>
$(function() {
    var statusFilter = '';
    var table = $('#schoolsSubmissionsTable').DataTable({
        pageLength:25, lengthMenu:[10,25,50,100], order:[[0,'asc']], autoWidth:false, responsive:true,
        columnDefs:[{targets:0,responsivePriority:1},{targets:2,responsivePriority:2,orderable:false,searchable:false}],
        language:{search:'',searchPlaceholder:'Search school name or school ID…',lengthMenu:'Show _MENU_ schools',info:'Showing _START_ to _END_ of _TOTAL_ schools',infoEmpty:'No schools to display',emptyTable:'No school submissions available.',zeroRecords:'No schools match your filters. Try another search or reset filters.'}
    });
    $.fn.dataTable.ext.search.push(function(settings, values, index) {
        return settings.nTable.id !== 'schoolsSubmissionsTable' || !statusFilter || settings.aoData[index].nTr.getAttribute('data-completion-state') === statusFilter;
    });
    function updateSummary(){ $('#submissionResults').text(table.rows({search:'applied'}).count() + ' of ' + <?= count($schools); ?> + ' schools'); }
    table.on('draw',updateSummary); updateSummary();
    $('#submissionStatusFilter').on('change',function(){statusFilter=this.value;table.draw();});
    $('#resetSubmissionFilters').on('click',function(){statusFilter='';$('#submissionStatusFilter').val('');table.search('').columns().search('').draw();});
});
</script>
</body>
</html>
