<?php
$esc = function($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$formatAmount = function($value) { return !empty($value) ? '₱' . number_format((float) $value, 2) : '—'; };
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
        .empty { padding:42px; text-align:center; color:#6c757d; }
        .detail-label { font-weight:600; color:#4b5663; }
        .detail-value { color:#121928; }
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
                    <a class="btn btn-sm btn-light" href="<?= base_url(); ?>Brigada/list_of_partners">← Back to Partners</a>
                    <h1>Tax Incentive Documentary Requirements</h1>
                    <p>Manage documentary requirements for donations marked eligible for tax incentives.</p>
                </div>
                <?php if (!empty($donation)): ?>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?= base_url(); ?>Brigada/partner_donation_view/<?= (int) $donation->id; ?>" class="btn btn-outline-light">Donation Details</a>
                        <a href="<?= base_url(); ?>Brigada/tax_incentive_requirements" class="btn btn-light">All Tax Incentive Donations</a>
                    </div>
                <?php endif; ?>
            </section>

            <?php if (!empty($donation)): ?>
                <section class="card card-panel p-4">
                    <h5 class="mb-4">Donation summary</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3"><div class="detail-label">Donation Date</div><div class="detail-value"><?= $esc($donation->c_date ?? '—'); ?></div></div>
                        <div class="col-md-4 mb-3"><div class="detail-label">Partner</div><div class="detail-value"><?= $esc($partner->name ?? '—'); ?></div></div>
                        <div class="col-md-4 mb-3"><div class="detail-label">Recipient School</div><div class="detail-value"><?= $esc($donation->schoolName ?? '—'); ?></div></div>
                        <div class="col-md-4 mb-3"><div class="detail-label">Contribution</div><div class="detail-value"><?= $esc($donation->project_name ?? $donation->spicific_contribution ?? '—'); ?></div></div>
                        <div class="col-md-4 mb-3"><div class="detail-label">Amount</div><div class="detail-value"><?= $formatAmount($donation->amount); ?></div></div>
                        <div class="col-md-4 mb-3"><div class="detail-label">Tax Incentive Eligible</div><div class="detail-value"><?= !empty($donation->tax_incentive_applicable) ? 'Yes' : 'No'; ?></div></div>
                    </div>
                </section>

                <section class="card card-panel p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="mb-0">Documentary requirements</h5>
                        <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#addRequirementPanel" aria-expanded="false" aria-controls="addRequirementPanel">Add Requirement</button>
                    </div>

                    <div class="collapse" id="addRequirementPanel">
                        <div class="card card-body mb-4">
                            <form method="post" action="<?= base_url(); ?>Brigada/tax_incentive_requirements_save">
                                <input type="hidden" name="donation_id" value="<?= (int) $donation->id; ?>">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Requirement</label>
                                        <input type="text" class="form-control" name="requirement" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-control" name="status">
                                            <option value="Pending">Pending</option>
                                            <option value="Submitted">Submitted</option>
                                            <option value="Completed">Completed</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Remarks</label>
                                        <input type="text" class="form-control" name="remarks" placeholder="Optional note">
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Save Requirement</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr><th>#</th><th>Requirement</th><th>Status</th><th>Remarks</th><th>Action</th></tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($requirements)): ?>
                                    <?php foreach ($requirements as $index => $item): ?>
                                        <tr>
                                            <td><?= (int) $index + 1; ?></td>
                                            <td><?= $esc($item->requirement ?? '—'); ?></td>
                                            <td><?= $esc($item->status ?? 'Pending'); ?></td>
                                            <td><?= $esc($item->remarks ?? '—'); ?></td>
                                            <td><a href="<?= base_url(); ?>Brigada/tax_incentive_requirements_delete/<?= (int) $item->id; ?>/<?= (int) $donation->id; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this requirement?');">Delete</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="empty">No documentary requirements recorded yet.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            <?php else: ?>
                <section class="card card-panel p-4">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr><th>Date</th><th>Partner</th><th>School</th><th>Contribution</th><th>Amount</th><th>Action</th></tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($donations)): ?>
                                    <?php foreach ($donations as $donationItem): ?>
                                        <?php $school = trim((string) ($donationItem->schoolName ?? '')); ?>
                                        <tr>
                                            <td><?= $esc($donationItem->c_date ?? '—'); ?></td>
                                            <td><?= $esc($donationItem->partner_name ?? '—'); ?></td>
                                            <td><?= $esc($school !== '' ? $school : '—'); ?></td>
                                            <td><?= $esc($donationItem->project_name ?? $donationItem->spicific_contribution ?? '—'); ?></td>
                                            <td><?= $formatAmount($donationItem->amount); ?></td>
                                            <td><a href="<?= base_url(); ?>Brigada/tax_incentive_requirements/<?= (int) $donationItem->id; ?>" class="btn btn-sm btn-outline-primary">View Requirements</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="empty">No tax incentive eligible donations found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            <?php endif; ?>
        </main>
    </div><?php include(__DIR__ . '/../includes/footer.php'); ?></div>
</div>
<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
</body>
</html>
