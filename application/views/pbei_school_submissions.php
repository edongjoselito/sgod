<?php
$schools = isset($schools) && is_array($schools) ? $schools : array();
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
    <link href="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">
    <style>body{background:#f4f7fb}.submissions-hero{margin:20px 0 24px;padding:30px;border-radius:18px;color:#fff;background:linear-gradient(135deg,#272b8c,#565de8)}.submissions-hero h2{color:#fff;margin:8px 0 0}.submissions-hero p{margin:9px 0 0;color:rgba(255,255,255,.84)}.school-name{font-weight:700;color:#2d3651}.school-id{display:block;margin-top:3px;color:#77819a;font-size:.78rem}</style>
</head>
<body>
<div id="wrapper">
    <?php include('includes/top-bar.php'); ?>
    <?php include('includes/sidebar.php'); ?>
    <div class="content-page"><div class="content"><main class="container-fluid">
        <section class="submissions-hero"><div class="d-flex align-items-center"><i class="mdi mdi-trophy-outline font-24 mr-2"></i><span class="text-uppercase font-weight-bold small">PBEI Recognition</span></div><h2>Schools Submissions</h2><p>Select a school to review its documentary requirement submissions.</p></section>
        <section class="card"><div class="card-body"><div class="table-responsive"><table id="schoolsSubmissionsTable" class="table table-hover w-100"><thead><tr><th>School</th><th class="text-right">Evaluation</th></tr></thead><tbody><?php foreach ($schools as $school): ?><tr><td><span class="school-name"><?= $esc(trim((string) $school->schoolName) !== '' ? $school->schoolName : $school->school_id); ?></span><span class="school-id"><?= $esc($school->school_id); ?></span></td><td class="text-right"><a class="btn btn-sm btn-outline-primary mr-1" href="<?= base_url(); ?>Page/pbei_school_submission_part_one?school_id=<?= rawurlencode($school->school_id); ?>"><i class="mdi mdi-school-outline"></i> Part I</a><a class="btn btn-sm btn-primary mr-1" href="<?= base_url(); ?>Page/pbei_school_submission_details?school_id=<?= rawurlencode($school->school_id); ?>"><i class="mdi mdi-file-document-outline"></i> Part II</a><a class="btn btn-sm btn-outline-success" href="<?= base_url(); ?>Page/pbei_school_submission_part_three?school_id=<?= rawurlencode($school->school_id); ?>"><i class="mdi mdi-clipboard-check-outline"></i> Part III</a></td></tr><?php endforeach; ?></tbody></table></div></div></section>
    </main></div><?php include('includes/footer.php'); ?></div>
</div>
<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
<script>$(function(){$('#schoolsSubmissionsTable').DataTable({pageLength:25,order:[[0,'asc']]});});</script>
</body>
</html>
