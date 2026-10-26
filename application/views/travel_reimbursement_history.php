<?php defined('BASEPATH') OR exit('No direct script access allowed');
$esc=function($value){return htmlspecialchars((string)$value,ENT_QUOTES,'UTF-8');};
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Record History — Travel #<?= (int)$travel->id; ?></title>
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/dashboard-unified.css" rel="stylesheet">
    <style>
        .history-card{border:1px solid #e5eaf2;border-radius:16px;padding:24px;background:white;overflow-wrap:anywhere}
        .history-card h1{font-size:1.7rem}
        .claim-history{border-left:3px solid #dce4f4;padding-left:20px;margin-top:24px}
        .claim-history li{margin:0 0 16px;list-style:none}
        .claim-history p{white-space:pre-wrap}
        @media(max-width:575px){.history-card{padding:16px}}
    </style>
</head>
<body class="dashboard-root-theme">
<div id="wrapper">
    <?php include(__DIR__.'/includes/top-bar.php'); ?>
    <?php include(__DIR__.'/includes/sidebar.php'); ?>
    <div class="content-page"><div class="content">
        <main class="container-fluid dashboard-shell py-4">
            <a class="btn btn-light mb-3" href="<?= site_url('Reimbursement/travel/'.(int)$travel->id); ?>">Back to Reimbursement</a>
            <section class="history-card">
                <h1>Record History</h1>
                <p>Travel #<?= (int)$travel->id; ?> · <?= $esc($travel->purpose); ?></p>
                <p class="text-muted">Your saved changes are kept here for reference.</p>
                <ul class="claim-history">
                    <?php foreach($events as $event): ?>
                    <li><strong><?= $esc($event['action']); ?></strong><br><small><?= $esc($event['created_at'].' · '.$event['actor']); ?></small><p><?= $esc($event['remarks']); ?></p></li>
                    <?php endforeach; ?>
                    <?php if(!$events): ?><li>No saved activity yet.</li><?php endif; ?>
                </ul>
            </section>
        </main>
    </div><?php include(__DIR__.'/includes/footer.php'); ?></div>
</div>
<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
</body>
</html>
