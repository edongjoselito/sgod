<?php
$esc = function ($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$schoolAddress = function ($donation) {
    $parts = array_filter([trim((string) ($donation->sitio ?? '')), trim((string) ($donation->brgy ?? '')), trim((string) ($donation->city ?? '')), trim((string) ($donation->province ?? ''))], function ($part) { return $part !== ''; });
    return implode(', ', $parts);
};
$recordCount = count((array) $donations);
$typeCount = count((array) ($contributionSummary ?? []));
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
    <link href="<?= base_url(); ?>assets/css/brigada-pages.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body class="dashboard-root-theme">
<div id="wrapper">
    <?php include(__DIR__ . '/../includes/top-bar.php'); ?>
    <?php include(__DIR__ . '/../includes/sidebar.php'); ?>
    <div class="content-page"><div class="content">
        <main class="container-fluid br-shell">
            <section class="br-hero">
                <span class="br-hero-eyebrow"><i class="mdi mdi-hand-heart-outline"></i> Brigada Eskwela</span>
                <h1><?= $esc(str_replace('_', ' ', $partnerType)); ?> Donations</h1>
                <p>Donation records for this partner type.</p>
                <div class="br-hero-actions">
                    <a class="btn btn-light btn-sm" href="<?= base_url(); ?>Brigada/all_donation_details"><i class="mdi mdi-arrow-left"></i> Back to All Donations</a>
                </div>
            </section>

            <section class="br-kpi-grid">
                <div class="br-kpi accent-navy">
                    <div class="br-kpi-top">
                        <div class="br-kpi-icon"><i class="mdi mdi-cash-multiple"></i></div>
                        <span class="br-kpi-trend neutral"><?= $esc($selectedSy ?? 'all years'); ?></span>
                    </div>
                    <span class="br-kpi-value">₱<?= number_format((float) $totalAmount, 2); ?></span>
                    <span class="br-kpi-label">Total Amount</span>
                </div>
                <div class="br-kpi accent-teal">
                    <div class="br-kpi-top">
                        <div class="br-kpi-icon"><i class="mdi mdi-format-list-numbered"></i></div>
                    </div>
                    <span class="br-kpi-value"><?= $recordCount; ?></span>
                    <span class="br-kpi-label">Records</span>
                </div>
                <?php if (!empty($contributionSummary)): ?>
                <div class="br-kpi accent-amber">
                    <div class="br-kpi-top">
                        <div class="br-kpi-icon"><i class="mdi mdi-shape"></i></div>
                    </div>
                    <span class="br-kpi-value"><?= $typeCount; ?></span>
                    <span class="br-kpi-label">Contribution Types</span>
                </div>
                <?php endif; ?>
            </section>

            <section class="br-card">
                <form class="br-filter" method="get" action="<?= base_url(); ?>Brigada/donation_type_details">
                    <input type="hidden" name="partner_type" value="<?= $esc($partnerType); ?>">
                    <div>
                        <label for="sy-filter"><i class="mdi mdi-calendar"></i> School Year</label>
                        <select class="form-control" id="sy-filter" name="sy" onchange="this.form.submit()">
                            <option value="all">All Years</option>
                            <?php foreach((array) ($syValues ?? []) as $sy): ?>
                                <option value="<?= $esc($sy); ?>"<?= ($selectedSy ?? '') === $sy ? ' selected' : ''; ?>><?= $esc($sy); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="contribution-type-filter"><i class="mdi mdi-shape"></i> Contribution Type</label>
                        <select class="form-control" id="contribution-type-filter" name="contribution_type" onchange="this.form.submit()">
                            <option value="">All Types</option>
                            <?php foreach((array) $contributionSummary as $summary): $type = (string) ($summary->contribution_type ?? ''); ?>
                                <option value="<?= $esc($type); ?>"<?= $selectedContributionType === $type ? ' selected' : ''; ?>><?= $esc(str_replace('_', ' ', $type)); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="br-filter-actions">
                        <button class="btn btn-primary btn-sm" type="submit"><i class="mdi mdi-filter-variant"></i> View Details</button>
                        <?php if($selectedContributionType !== ''): ?><a class="btn btn-light btn-sm" href="<?= base_url(); ?>Brigada/donation_type_details?partner_type=<?= rawurlencode($partnerType); ?>"><i class="mdi mdi-close"></i> Clear</a><?php endif; ?>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table br-table" id="donationTable"><thead><tr><th>Date</th><th>Partner</th><th>Recipient School</th><th>Contribution</th><th>Type</th><th>Qty</th><th>Unit</th><th class="text-right">Amount</th><th>Status</th><th>Remarks</th></tr></thead><tbody>
                    <?php foreach((array) $donations as $donation): $address = $schoolAddress($donation); ?>
                        <tr><td style="font-weight:600;color:var(--br-ink);"><?= $esc($donation->c_date ?? ''); ?></td><td><?= $esc($donation->partner_name ?? '—'); ?></td><td><strong><?= $esc($donation->schoolName ?? '—'); ?></strong><?php if($address !== ''): ?><br><small style="color:var(--br-muted);"><i class="mdi mdi-map-marker" style="font-size:0.7rem;"></i> <?= $esc($address); ?></small><?php endif; ?></td><td><?= $esc($donation->project_name ?? $donation->spicific_contribution ?? '—'); ?></td><td><span class="br-badge br-badge-neutral"><?= $esc($donation->contribution_type ?? '—'); ?></span></td><td style="color:var(--br-muted);"><?= $esc($donation->quantity_of_conftribution ?? '—'); ?></td><td style="color:var(--br-muted);"><?= $esc($donation->unit_of_contribution ?? '—'); ?></td><td class="text-right amount"><?= !empty($donation->amount) ? '₱' . number_format((float) $donation->amount, 2) : '—'; ?></td><td><?= $esc($donation->status_agreement ?? '—'); ?></td><td style="color:var(--br-muted);"><?= $esc($donation->remarks ?? '—'); ?></td></tr>
                    <?php endforeach; ?>
                    <?php if(empty($donations)): ?><tr><td colspan="10" class="br-empty"><i class="mdi mdi-inbox-outline"></i><p>No donation records found.</p></td></tr><?php endif; ?>
                    </tbody></table>
                </div>
            </section>
        </main>
    </div><?php include(__DIR__ . '/../includes/footer.php'); ?></div>
</div>
<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
<script>$(function(){ $('#donationTable').DataTable({ pageLength: 10, order: [[0,'desc']], scrollX: true, autoWidth: false }); });</script>
</body>
</html>
