<?php
$esc = function($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$formatAmount = function($value) { return !empty($value) ? '₱' . number_format((float) $value, 2) : '—'; };

$tracked = array();
foreach ($tracking as $t) {
    $tracked[$t->donation_id . '_' . $t->requirement_id] = $t;
}

$grouped = array();
foreach ($partners as $p) {
    $grouped[$p->donation_id] = $p;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $esc($title); ?></title>
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/dashboard-unified.css" rel="stylesheet">
    <style>
        body { background:#f4f8fc; }
        .page-shell { padding:30px 0; }
        .page-hero { display:flex; align-items:center; justify-content:space-between; gap:24px; padding:28px; border-radius:20px; color:#fff; background:linear-gradient(135deg,#272b8c,#3c40c6); box-shadow:0 18px 40px rgba(39,43,140,.16); }
        .page-hero h1 { margin:8px 0; color:#fff; font-size:2rem; font-weight:700; }
        .page-hero p { margin:0; color:rgba(255,255,255,.82); }
        .card-panel { margin-top:24px; border:0; border-radius:18px; box-shadow:0 16px 38px rgba(15,23,42,.08); overflow:hidden; }
        .card-panel .table th { border-top:0; color:#68708a; font-size:.72rem; letter-spacing:.06em; text-transform:uppercase; white-space:nowrap; }
        .card-panel .table td { vertical-align:middle; }
        .requirement-row:nth-child(odd) { background:#f9f9f9; }
        .partner-card { border-left:4px solid #272b8c; }
    </style>
</head>
<body class="dashboard-root-theme">
<div id="wrapper">
    <?php include(__DIR__ . '/../includes/top-bar.php'); ?>
    <?php include(__DIR__ . '/../includes/sidebar.php'); ?>
    <div class="content-page"><div class="content">
        <main class="container-fluid dashboard-shell page-shell">
            <section class="page-hero">
                <div>
                    <h1>ASP Tracking</h1>
                    <p>Track documentary requirements for partners with tax incentives.</p>
                </div>
            </section>

            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $esc($this->session->flashdata('success')); ?>
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('danger')): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <?= $esc($this->session->flashdata('danger')); ?>
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= base_url(); ?>Brigada/asp_tracking">
                <input type="hidden" name="save_tracking" value="1">

                <?php if (!empty($grouped)): ?>
                    <?php foreach ($grouped as $donationId => $partner): ?>
                        <section class="card card-panel p-4 mb-4 partner-card">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <h5 class="mb-1"><?= $esc($partner->partner_name); ?></h5>
                                    <small class="text-muted">
                                        <?= $esc($partner->c_date ?? '—'); ?> · <?= $esc($partner->schoolName ?? '—'); ?> · <?= $esc($partner->project_name ?? $partner->spicific_contribution ?? '—'); ?> · <?= $formatAmount($partner->amount); ?>
                                    </small>
                                </div>
                                <input type="hidden" name="donation_id" value="<?= (int) $donationId; ?>">
                                <button type="submit" class="btn btn-primary btn-sm">Save Tracking</button>
                            </div>

                            <?php if (!empty($requirements)): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th style="width:35%;">Requirement</th>
                                                <th style="width:15%;">Completed</th>
                                                <th style="width:50%;">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($requirements as $req): ?>
                                                <?php
                                                    $key = $donationId . '_' . $req->id;
                                                    $current = isset($tracked[$key]) ? $tracked[$key] : null;
                                                ?>
                                                <tr class="requirement-row">
                                                    <td>
                                                        <input type="hidden" name="requirements[]" value="<?= (int) $req->id; ?>">
                                                        <?= $esc($req->requirement); ?>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="completed_<?= (int) $donationId; ?>_<?= (int) $req->id; ?>" name="completed[<?= (int) $req->id; ?>]" value="1"<?= !empty($current->is_completed) ? ' checked' : ''; ?>>
                                                            <label class="custom-control-label" for="completed_<?= (int) $donationId; ?>_<?= (int) $req->id; ?>"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm" name="remarks[<?= (int) $req->id; ?>]" value="<?= $esc($current->remarks ?? ''); ?>" placeholder="Type remarks here">
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted mb-0">No requirements configured yet.</p>
                            <?php endif; ?>
                        </section>
                    <?php endforeach; ?>
                <?php else: ?>
                    <section class="card card-panel p-4">
                        <p class="text-center text-muted mb-0">No tax incentive eligible donations found.</p>
                    </section>
                <?php endif; ?>
            </form>
        </main>
    </div><?php include(__DIR__ . '/../includes/footer.php'); ?></div>
</div>

<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
</body>
</html>
