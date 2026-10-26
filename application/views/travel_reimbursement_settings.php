<?php defined('BASEPATH') OR exit('No direct script access allowed');
$esc=function($value){return htmlspecialchars((string)$value,ENT_QUOTES,'UTF-8');};
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Report Settings — Travel #<?= (int)$travel->id; ?></title>
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/dashboard-unified.css" rel="stylesheet">
    <style>
        .history-card{border:1px solid #e5eaf2;border-radius:16px;padding:24px;background:white;overflow-wrap:anywhere}
        .history-card h1{font-size:1.7rem}
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
                <h1>Report Settings</h1>
                <p>Travel #<?= (int)$travel->id; ?> · <?= $esc($travel->purpose); ?></p>
                <h2 class="h5">Printed Signatory Details</h2>
                <p class="text-muted">These settings apply to this travel report. Leave names blank for handwritten signatures.</p>
                <?php if($message=$this->session->flashdata('success')): ?><div class="alert alert-success" role="alert"><?= $esc($message); ?></div><?php endif; ?>
                <?php if($error): ?><div class="alert alert-danger" role="alert"><?= $esc($error); ?></div><?php endif; ?>
                <form method="post" action="<?= site_url('Reimbursement/settings/'.(int)$travel->id); ?>">
                    <input type="hidden" name="claim_token" value="<?= $esc($token); ?>">
                    <div class="form-row">
                        <?php foreach($fields as $key=>$label): ?>
                        <div class="form-group col-md-<?= $key==='prepared_by'?'12':'6'; ?>">
                            <label for="setting-<?= $key; ?>"><?= $esc($label); ?></label>
                            <input id="setting-<?= $key; ?>" class="form-control" name="settings[<?= $key; ?>]" maxlength="255" value="<?= $esc($meta[$key] ?? ''); ?>">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </section>
        </main>
    </div><?php include(__DIR__.'/includes/footer.php'); ?></div>
</div>
<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
</body>
</html>
