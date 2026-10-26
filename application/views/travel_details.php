<?php defined('BASEPATH') OR exit('No direct script access allowed');
$esc = function($value){ return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$fields = array(
    'IDNumber' => 'Employee Number', 'status' => 'Status',
    'purpose' => 'Purpose', 'destination' => 'Destination',
    'inclusive_date' => 'Inclusive Dates', 'date_start' => 'Start Date',
    'date_end' => 'End Date', 'permanent_station' => 'Permanent Station',
    'activity_host' => 'Activity Host', 'fund_source' => 'Fund Source',
    'date_created' => 'Date Encoded'
);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Travel Details</title>
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/dashboard-unified.css" rel="stylesheet">
    <style>
        .travel-detail-card{border:1px solid #e6eaf2;border-radius:18px;overflow:hidden;box-shadow:0 8px 28px rgba(30,45,80,.05)}
        .travel-detail-header{padding:28px;background:linear-gradient(120deg,#222968,#415bc0);color:#fff}.travel-detail-header h1{color:#fff;font-size:1.65rem}.travel-detail-header p{margin:0;color:#e0e6ff}
        .travel-detail-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:24px;margin:0}.travel-detail-grid dt{color:#69768d;font-size:.8rem;margin-bottom:6px}.travel-detail-grid dd{margin:0;overflow-wrap:anywhere;white-space:pre-line;color:#29364e}.travel-detail-wide{grid-column:1/-1}
        @media(max-width:575px){.travel-detail-grid{grid-template-columns:1fr}.travel-detail-header{padding:22px}}
    </style>
</head>
<body class="dashboard-root-theme">
<div id="wrapper">
    <?php include(__DIR__ . '/includes/top-bar.php'); ?>
    <?php include(__DIR__ . '/includes/sidebar.php'); ?>
    <div class="content-page"><div class="content">
        <main class="container-fluid dashboard-shell py-4">
            <a class="btn btn-light mb-3" href="<?= site_url('Page/authority_to_travel'); ?>">Back to Authority to Travel</a>
            <section class="card travel-detail-card">
                <header class="travel-detail-header"><h1>Travel Details</h1><p>Request #<?= (int) $travel->id; ?></p></header>
                <div class="card-body p-4">
                    <dl class="travel-detail-grid">
                    <?php foreach ($fields as $field => $label): ?>
                        <div class="<?= $field === 'purpose' ? 'travel-detail-wide' : ''; ?>"><dt><?= $esc($label); ?></dt><dd><?= isset($travel->$field) && trim((string) $travel->$field) !== '' ? $esc($travel->$field) : '—'; ?></dd></div>
                    <?php endforeach; ?>
                        <div><dt>Travel Type</dt><dd><?= isset($travel->ttype) ? ((int) $travel->ttype === 0 ? 'Within the Division' : 'Outside Division') : '—'; ?></dd></div>
                    </dl>
                    <a class="btn btn-outline-primary mt-4 mr-2" href="<?= site_url('Reimbursement/travel/' . (int) $travel->id); ?>">Process Reimbursement</a>
                    <a class="btn btn-primary mt-4" href="<?= site_url('Page/addAccomplishments') . '?travel_request_id=' . (int) $travel->id; ?>">Encode Accomplishments</a>
                </div>
            </section>
        </main>
    </div><?php include(__DIR__ . '/includes/footer.php'); ?></div>
</div>
<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
</body>
</html>
