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
$typeCount = count((array) ($typeSummary ?? []));
$recordCount = count((array) $donations);
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
</head>
<body class="dashboard-root-theme">
<div id="wrapper">
    <?php include(__DIR__ . '/../includes/top-bar.php'); ?>
    <?php include(__DIR__ . '/../includes/sidebar.php'); ?>
    <div class="content-page"><div class="content">
        <main class="container-fluid br-shell">
            <section class="br-hero">
                <span class="br-hero-eyebrow"><i class="mdi mdi-hand-heart-outline"></i> Brigada Eskwela</span>
                <h1>All Donation Details</h1>
                <p><?= $selectedTypeLabel !== '' ? 'Showing ' . $esc(str_replace('_', ' ', $selectedTypeLabel)) . ' partner donations.' : 'All recorded partner donations across the division.'; ?></p>
                <div class="br-hero-actions">
                    <a class="btn btn-light btn-sm" href="<?= base_url(); ?>Brigada/list_of_partners"><i class="mdi mdi-arrow-left"></i> Partners</a>
                    <a class="btn btn-light btn-sm" href="<?= base_url(); ?>Brigada/yoy"><i class="mdi mdi-chart-line"></i> Year-on-Year</a>
                </div>
            </section>

            <?php if (!empty($syValues)): ?>
                <form class="br-sy-select" method="get" action="<?= base_url(); ?>Brigada/all_donation_details">
                    <?php if (!empty($selectedPartnerType)): ?><input type="hidden" name="partner_type" value="<?= $esc($selectedPartnerType); ?>"><?php endif; ?>
                    <div><label for="sy">School Year</label><select id="sy" name="sy" class="form-control form-control-sm" onchange="this.form.submit()">
                        <?php foreach ($syValues as $sy): ?>
                            <option value="<?= $esc($sy); ?>" <?= ($selectedSy ?? '') === $sy ? 'selected' : ''; ?>><?= $esc($sy); ?></option>
                        <?php endforeach; ?>
                        <option value="all" <?= ($selectedSy ?? '') === '' ? 'selected' : ''; ?>>All years</option>
                    </select></div>
                    <noscript><button class="btn btn-primary btn-sm"><i class="mdi mdi-check"></i> Apply</button></noscript>
                </form>
            <?php endif; ?>

            <section class="br-kpi-grid">
                <div class="br-kpi accent-navy">
                    <div class="br-kpi-top">
                        <div class="br-kpi-icon"><i class="mdi mdi-cash-multiple"></i></div>
                        <span class="br-kpi-trend neutral"><?= $esc($selectedSy ?? 'all'); ?></span>
                    </div>
                    <span class="br-kpi-value">₱<?= number_format((float) $totalAmount, 2); ?></span>
                    <span class="br-kpi-label">Total Donations</span>
                </div>
                <div class="br-kpi accent-teal">
                    <div class="br-kpi-top">
                        <div class="br-kpi-icon"><i class="mdi mdi-format-list-numbered"></i></div>
                    </div>
                    <span class="br-kpi-value"><?= $recordCount; ?></span>
                    <span class="br-kpi-label">Records</span>
                </div>
                <div class="br-kpi accent-pink">
                    <div class="br-kpi-top">
                        <div class="br-kpi-icon"><i class="mdi mdi-account-group"></i></div>
                    </div>
                    <span class="br-kpi-value"><?= $typeCount; ?></span>
                    <span class="br-kpi-label">Partner Types</span>
                </div>
            </section>

            <?php if (!empty($typeSummary)): ?>
                <h3 class="br-section-title"><i class="mdi mdi-chart-donut" style="color:var(--br-violet);"></i> Donations by Partner Type</h3>
                <section class="br-card">
                    <div class="table-responsive"><table class="table br-table"><thead><tr><th>Partner Type</th><th class="text-right">Total Amount</th><th class="text-right">Records</th><th></th></tr></thead><tbody>
                    <?php foreach ($typeSummary as $summary): $typeKey = trim((string) ($summary->partner_type_key ?? '')); ?>
                        <tr><td>
                            <div style="display:flex;align-items:center;gap:12px;">
                                <span class="br-avatar" style="width:36px;height:36px;font-size:0.72rem;"><?= strtoupper(substr($esc($summary->partner_type ?? '?'), 0, 2)); ?></span>
                                <strong><?= $esc(str_replace('_', ' ', $summary->partner_type ?? 'Unspecified')); ?></strong>
                            </div>
                        </td><td class="text-right amount">₱<?= number_format((float) ($summary->total_amount ?? 0), 2); ?></td><td class="text-right" style="color:var(--br-muted);font-weight:600;"><?= (int) ($summary->record_count ?? 0); ?></td><td class="text-right"><a class="btn btn-sm btn-outline-primary" href="<?= base_url(); ?>Brigada/donation_type_details?partner_type=<?= rawurlencode($typeKey); ?>&amp;sy=<?= rawurlencode($selectedSy ?? ''); ?>">View <i class="mdi mdi-chevron-right"></i></a></td></tr>
                    <?php endforeach; ?>
                    </tbody></table></div>
                </section>
            <?php endif; ?>

            <h3 class="br-section-title"><i class="mdi mdi-format-list-bulleted" style="color:var(--br-navy-light);"></i> Donation Records</h3>
            <section class="br-card">
                <div class="br-card-header"><h5>All Records</h5><span class="br-badge br-badge-info"><?= $recordCount; ?> records</span></div>
                <div class="table-responsive">
                    <table class="table br-table"><thead><tr><th>Date</th><th>Partner</th><th>Recipient School</th><th>Contribution</th><th>Type</th><th>Qty</th><th>Unit</th><th class="text-right">Amount</th><th>Status</th></tr></thead><tbody>
                    <?php foreach ((array) $donations as $donation): $address = $schoolAddress($donation); ?>
                        <tr><td style="font-weight:600;color:var(--br-ink);"><?= $esc($donation->c_date ?? ''); ?></td><td><?= $esc($donation->partner_name ?? '—'); ?></td><td><strong><?= $esc($donation->schoolName ?? '—'); ?></strong><?php if ($address !== ''): ?><br><small style="color:var(--br-muted);"><i class="mdi mdi-map-marker" style="font-size:0.7rem;"></i> <?= $esc($address); ?></small><?php endif; ?></td><td><?= $esc($donation->project_name ?? $donation->spicific_contribution ?? '—'); ?></td><td><span class="br-badge br-badge-neutral"><?= $esc($donation->contribution_type ?? '—'); ?></span></td><td style="color:var(--br-muted);"><?= $esc($donation->quantity_of_conftribution ?? '—'); ?></td><td style="color:var(--br-muted);"><?= $esc($donation->unit_of_contribution ?? '—'); ?></td><td class="text-right amount"><?= !empty($donation->amount) ? '₱' . number_format((float) $donation->amount, 2) : '—'; ?></td><td><?= $esc($donation->status_agreement ?? '—'); ?></td></tr>
                    <?php endforeach; ?>
                    <?php if (empty($donations)): ?><tr><td colspan="9" class="br-empty"><i class="mdi mdi-inbox-outline"></i><p>No donation records found.</p></td></tr><?php endif; ?>
                    </tbody></table>
                </div>
            </section>
        </main>
    </div><?php include(__DIR__ . '/../includes/footer.php'); ?></div>
</div>
<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
</body>
</html>
