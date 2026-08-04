<?php
$esc = function ($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$schoolAddress = function ($donation) {
    $parts = array_filter([
        trim((string) ($donation->sitio ?? '')),
        trim((string) ($donation->brgy ?? '')),
        trim((string) ($donation->city ?? '')),
        trim((string) ($donation->province ?? '')),
    ], function ($part) { return $part !== ''; });
    return implode(', ', $parts);
};
$selectedTypeLabel = trim((string) ($selectedPartnerType ?? ''));
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
        .donation-shell { padding:30px 0; }
        .donation-hero { display:flex; align-items:center; justify-content:space-between; gap:24px; padding:28px; border-radius:20px; color:#fff; background:linear-gradient(135deg,#272b8c,#3c40c6); box-shadow:0 18px 40px rgba(39,43,140,.16); }
        .donation-hero h1 { margin:8px 0; color:#fff; font-size:2rem; font-weight:700; }
        .donation-hero p { margin:0; color:rgba(255,255,255,.82); }
        .total-card { min-width:210px; padding:18px 22px; border:1px solid rgba(255,255,255,.28); border-radius:14px; background:rgba(255,255,255,.12); }
        .total-card span { display:block; color:rgba(255,255,255,.78); font-size:.74rem; font-weight:800; letter-spacing:.08em; text-transform:uppercase; }
        .total-card strong { display:block; margin-top:6px; color:#fff; font-size:1.8rem; }
        .summary-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(190px,1fr)); gap:16px; margin-top:24px; }
        .summary-card { display:block; padding:20px; border:1px solid #e8ecf5; border-radius:16px; background:#fff; box-shadow:0 10px 24px rgba(15,23,42,.05); color:inherit; transition:transform .2s ease,box-shadow .2s ease,border-color .2s ease; }
        .summary-card:hover, .summary-card:focus { border-color:#3c40c6; box-shadow:0 14px 28px rgba(60,64,198,.14); color:inherit; outline:0; text-decoration:none; transform:translateY(-2px); }
        .summary-card span { display:block; color:#68708a; font-size:.76rem; font-weight:800; letter-spacing:.05em; text-transform:uppercase; }
        .summary-card strong { display:block; margin-top:8px; color:#272b8c; font-size:1.45rem; }
        .summary-card small { display:block; margin-top:7px; color:#8a92aa; }
        .donation-card { margin-top:24px; border:0; border-radius:18px; box-shadow:0 16px 38px rgba(15,23,42,.08); overflow:hidden; }
        .donation-card .table th { border-top:0; color:#68708a; font-size:.72rem; letter-spacing:.06em; text-transform:uppercase; white-space:nowrap; }
        .donation-card .table td { vertical-align:middle; }
        .school-address { display:block; margin-top:4px; color:#6c757d; font-size:.8rem; }
        .empty { padding:42px; text-align:center; color:#6c757d; }
        @media (max-width:700px) { .donation-hero { display:block; } .total-card { margin-top:20px; } }
    </style>
</head>
<body class="dashboard-root-theme">
<div id="wrapper">
    <?php include(__DIR__ . '/../includes/top-bar.php'); ?>
    <?php include(__DIR__ . '/../includes/sidebar.php'); ?>
    <div class="content-page"><div class="content">
        <main class="container-fluid dashboard-shell donation-shell">
            <section class="donation-hero">
                <div>
                    <a class="btn btn-sm btn-light" href="<?= base_url(); ?>Brigada/list_of_partners">← Back to Partners</a>
                    <h1>All Donation Details</h1>
                    <p><?= $selectedTypeLabel !== '' ? 'Showing ' . $esc(str_replace('_', ' ', $selectedTypeLabel)) . ' partner donations.' : 'All recorded partner donations.'; ?></p>
                </div>
                <div class="total-card"><span>Overall Donation Amount</span><strong>₱<?= number_format((float) $totalAmount, 2); ?></strong></div>
            </section>

            <?php if (!empty($typeSummary)): ?>
                <section class="summary-grid" aria-label="Donation totals by partner type">
                    <?php foreach ($typeSummary as $summary): ?>
                        <?php $typeKey = trim((string) ($summary->partner_type_key ?? '')); ?>
                        <a class="summary-card" href="<?= base_url(); ?>Brigada/donation_type_details<?= $typeKey !== '' ? '?partner_type=' . rawurlencode($typeKey) : ''; ?>" title="View donation details for <?= $esc($summary->partner_type ?? 'Unspecified'); ?>">
                            <span><?= $esc($summary->partner_type ?? 'Unspecified'); ?></span>
                            <strong>₱<?= number_format((float) ($summary->total_amount ?? 0), 2); ?></strong>
                        </a>
                    <?php endforeach; ?>
                </section>
            <?php endif; ?>

            <section class="card donation-card"><div class="card-body p-0"><div class="table-responsive">
                <table class="table table-hover mb-0"><thead><tr><th>Date</th><th>Partner</th><th>Recipient School</th><th>Contribution</th><th>Type</th><th>Qty</th><th>Unit</th><th>Amount</th><th>Status</th><th>Remarks</th></tr></thead><tbody>
                <?php foreach ((array) $donations as $donation): ?>
                    <?php $address = $schoolAddress($donation); ?>
                    <tr><td><?= $esc($donation->c_date ?? ''); ?></td><td><?= $esc($donation->partner_name ?? '—'); ?></td><td><strong><?= $esc($donation->schoolName ?? '—'); ?></strong><?php if ($address !== ''): ?><span class="school-address"><?= $esc($address); ?></span><?php endif; ?></td><td><?= $esc($donation->project_name ?? $donation->spicific_contribution ?? '—'); ?></td><td><?= $esc($donation->contribution_type ?? '—'); ?></td><td><?= $esc($donation->quantity_of_conftribution ?? '—'); ?></td><td><?= $esc($donation->unit_of_contribution ?? '—'); ?></td><td><?= !empty($donation->amount) ? '₱' . number_format((float) $donation->amount, 2) : '—'; ?></td><td><?= $esc($donation->status_agreement ?? '—'); ?></td><td><?= $esc($donation->remarks ?? '—'); ?></td></tr>
                <?php endforeach; ?>
                <?php if (empty($donations)): ?><tr><td colspan="10" class="empty">No donation records found.</td></tr><?php endif; ?>
                </tbody></table>
            </div></div></section>
        </main>
    </div><?php include(__DIR__ . '/../includes/footer.php'); ?></div>
</div>
<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
</body>
</html>
