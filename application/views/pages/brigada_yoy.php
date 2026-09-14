<?php
$esc = function ($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); };
$totFor = function ($rows, $sy) {
    foreach ($rows as $r) if (($r->sy ?? '') === $sy) return $r;
    return null;
};
$ta = $totFor($totals, $syA); $tb = $totFor($totals, $syB);
$delta = (float) ($ta->total_amount ?? 0) - (float) ($tb->total_amount ?? 0);
$pct = (float) ($tb->total_amount ?? 0) > 0 ? ($delta / (float) ($tb->total_amount ?? 0)) * 100 : 0;
$isUp = $delta >= 0;
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
                <span class="br-hero-eyebrow"><i class="mdi mdi-chart-line"></i> Analytics</span>
                <h1>Year-on-Year</h1>
                <p>Compare division totals, district splits, contribution types and top contributors across two school years.</p>
                <div class="br-hero-actions">
                    <a class="btn btn-light btn-sm" href="<?= base_url(); ?>Brigada/all_donation_details"><i class="mdi mdi-format-list-bulleted"></i> All Donations</a>
                    <a class="btn btn-gold btn-sm" href="<?= base_url(); ?>Brigada/contribution_division_export?sy=<?= rawurlencode($syA); ?>"><i class="mdi mdi-download"></i> Division Export</a>
                </div>
            </section>

            <form class="br-sy-select" method="get" action="<?= base_url(); ?>Brigada/yoy">
                <div><label for="sy_a">Year A</label><select id="sy_a" name="sy_a" class="form-control form-control-sm">
                    <?php foreach ($syValues as $sy): ?><option value="<?= $esc($sy); ?>" <?= $syA === $sy ? 'selected' : ''; ?>><?= $esc($sy); ?></option><?php endforeach; ?>
                </select></div>
                <div><label for="sy_b">Year B</label><select id="sy_b" name="sy_b" class="form-control form-control-sm">
                    <?php foreach ($syValues as $sy): ?><option value="<?= $esc($sy); ?>" <?= $syB === $sy ? 'selected' : ''; ?>><?= $esc($sy); ?></option><?php endforeach; ?>
                </select></div>
                <button class="btn btn-primary btn-sm"><i class="mdi mdi-compare"></i> Compare</button>
            </form>

            <section class="br-kpi-grid">
                <div class="br-kpi accent-navy">
                    <div class="br-kpi-top">
                        <div class="br-kpi-icon"><i class="mdi mdi-cash-multiple"></i></div>
                        <span class="br-kpi-trend neutral"><?= $esc($syA); ?></span>
                    </div>
                    <span class="br-kpi-value">₱<?= number_format((float) ($ta->total_amount ?? 0), 2); ?></span>
                    <span class="br-kpi-label">Year A Total</span>
                    <span class="br-kpi-context"><?= (int) ($ta->record_count ?? 0); ?> records · <?= (int) ($ta->school_count ?? 0); ?> schools</span>
                </div>
                <div class="br-kpi accent-sky">
                    <div class="br-kpi-top">
                        <div class="br-kpi-icon"><i class="mdi mdi-cash-remove"></i></div>
                        <span class="br-kpi-trend neutral"><?= $esc($syB); ?></span>
                    </div>
                    <span class="br-kpi-value">₱<?= number_format((float) ($tb->total_amount ?? 0), 2); ?></span>
                    <span class="br-kpi-label">Year B Total</span>
                    <span class="br-kpi-context"><?= (int) ($tb->record_count ?? 0); ?> records · <?= (int) ($tb->school_count ?? 0); ?> schools</span>
                </div>
                <div class="br-kpi <?= $isUp ? 'accent-green' : 'accent-coral'; ?>">
                    <div class="br-kpi-top">
                        <div class="br-kpi-icon"><i class="mdi mdi-<?= $isUp ? 'trending-up' : 'trending-down'; ?>"></i></div>
                        <span class="br-kpi-trend <?= $isUp ? 'up' : 'down'; ?>"><i class="mdi mdi-<?= $isUp ? 'arrow-up' : 'arrow-down'; ?>"></i> <?= number_format(abs($pct), 1); ?>%</span>
                    </div>
                    <span class="br-kpi-value <?= $isUp ? 'up' : 'down'; ?>"><?= ($isUp ? '+' : '') . number_format($delta, 2); ?></span>
                    <span class="br-kpi-label">Change (B → A)</span>
                </div>
                <div class="br-kpi accent-pink">
                    <div class="br-kpi-top">
                        <div class="br-kpi-icon"><i class="mdi mdi-account-group"></i></div>
                    </div>
                    <span class="br-kpi-value"><?= (int) ($ta->partner_count ?? 0); ?></span>
                    <span class="br-kpi-label">Partners (<?= $esc($syA); ?>)</span>
                    <span class="br-kpi-context">distinct stakeholders</span>
                </div>
            </section>

            <h3 class="br-section-title"><i class="mdi mdi-chart-bar" style="color:var(--br-navy-light);"></i> Total Donations by School Year</h3>
            <section class="br-card"><div class="br-card-body">
                <div class="br-chart" style="height:280px;"><canvas id="yoyTotalsChart"></canvas></div>
            </div></section>

            <h3 class="br-section-title"><i class="mdi mdi-map-marker-multiple" style="color:var(--br-teal);"></i> By District</h3>
            <div class="row" style="margin:0 4px;">
                <div class="col-md-6"><section class="br-card"><div class="br-card-body">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
                        <span class="br-avatar" style="background:linear-gradient(135deg,var(--br-navy-light),var(--br-violet));width:32px;height:32px;font-size:0.72rem;">A</span>
                        <h5 style="margin:0;color:var(--br-ink);font-weight:800;"><?= $esc($syA); ?></h5>
                    </div>
                    <div class="br-chart" style="height:260px;"><canvas id="districtChartA"></canvas></div>
                </div></section></div>
                <div class="col-md-6"><section class="br-card"><div class="br-card-body">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
                        <span class="br-avatar" style="background:linear-gradient(135deg,var(--br-sky),#74c0fc);width:32px;height:32px;font-size:0.72rem;">B</span>
                        <h5 style="margin:0;color:var(--br-ink);font-weight:800;"><?= $esc($syB); ?></h5>
                    </div>
                    <div class="br-chart" style="height:260px;"><canvas id="districtChartB"></canvas></div>
                </div></section></div>
            </div>

            <h3 class="br-section-title"><i class="mdi mdi-shape" style="color:var(--br-amber);"></i> By Contribution Type</h3>
            <div class="row" style="margin:0 4px;">
                <div class="col-md-6"><section class="br-card"><div class="br-card-body">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
                        <span class="br-avatar" style="background:linear-gradient(135deg,var(--br-navy-light),var(--br-violet));width:32px;height:32px;font-size:0.72rem;">A</span>
                        <h5 style="margin:0;color:var(--br-ink);font-weight:800;"><?= $esc($syA); ?></h5>
                    </div>
                    <div class="br-chart" style="height:260px;"><canvas id="typeChartA"></canvas></div>
                </div></section></div>
                <div class="col-md-6"><section class="br-card"><div class="br-card-body">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
                        <span class="br-avatar" style="background:linear-gradient(135deg,var(--br-sky),#74c0fc);width:32px;height:32px;font-size:0.72rem;">B</span>
                        <h5 style="margin:0;color:var(--br-ink);font-weight:800;"><?= $esc($syB); ?></h5>
                    </div>
                    <div class="br-chart" style="height:260px;"><canvas id="typeChartB"></canvas></div>
                </div></section></div>
            </div>

            <h3 class="br-section-title"><i class="mdi mdi-trophy" style="color:var(--br-gold);"></i> Top 10 Rankings — <?= $esc($syA); ?></h3>
            <div class="row" style="margin:0 4px;">
                <div class="col-md-6"><section class="br-card">
                    <div class="br-card-header"><h5><i class="mdi mdi-school" style="color:var(--br-navy-light);"></i> Schools</h5><span class="br-badge br-badge-info">by amount</span></div>
                    <div class="table-responsive"><table class="table br-table"><thead><tr><th style="width:48px">#</th><th>School</th><th>District</th><th class="text-right">Amount</th></tr></thead><tbody>
                    <?php foreach ($topSchools as $i => $s): $rankClass = $i === 0 ? 'gold' : ($i === 1 ? 'silver' : ($i === 2 ? 'bronze' : '')); ?>
                        <tr><td><span class="br-rank <?= $rankClass; ?>"><?= $i + 1; ?></span></td><td><strong><?= $esc($s->schoolName ?? $s->school_id); ?></strong></td><td style="color:var(--br-muted);"><?= $esc($s->district ?? '—'); ?></td><td class="text-right amount">₱<?= number_format((float) ($s->total_amount ?? 0), 2); ?></td></tr>
                    <?php endforeach; ?>
                    <?php if (empty($topSchools)): ?><tr><td colspan="4" class="br-empty"><i class="mdi mdi-school-outline"></i><p>No data.</p></td></tr><?php endif; ?>
                    </tbody></table></div>
                </section></div>
                <div class="col-md-6"><section class="br-card">
                    <div class="br-card-header"><h5><i class="mdi mdi-account-star" style="color:var(--br-pink);"></i> Stakeholders</h5><span class="br-badge br-badge-info">by amount</span></div>
                    <div class="table-responsive"><table class="table br-table"><thead><tr><th style="width:48px">#</th><th>Partner</th><th>Sector</th><th class="text-right">Amount</th></tr></thead><tbody>
                    <?php foreach ($topStakeholders as $i => $s): $rankClass = $i === 0 ? 'gold' : ($i === 1 ? 'silver' : ($i === 2 ? 'bronze' : '')); ?>
                        <tr><td><span class="br-rank <?= $rankClass; ?>"><?= $i + 1; ?></span></td><td><strong><?= $esc($s->partner_name ?? '—'); ?></strong></td><td style="color:var(--br-muted);"><?= $esc(str_replace('_', ' ', (string) ($s->general_type ?? ''))); ?></td><td class="text-right amount">₱<?= number_format((float) ($s->total_amount ?? 0), 2); ?></td></tr>
                    <?php endforeach; ?>
                    <?php if (empty($topStakeholders)): ?><tr><td colspan="4" class="br-empty"><i class="mdi mdi-account-outline"></i><p>No data.</p></td></tr><?php endif; ?>
                    </tbody></table></div>
                </section></div>
            </div>
        </main>
    </div><?php include(__DIR__ . '/../includes/footer.php'); ?></div>
</div>
<script src="<?= base_url(); ?>assets/libs/chart-js/chart.umd.min.js"></script>
<script>
const P = ['#1a1f71','#3c40c6','#6c70ef','#00b8a9','#ff6b6b','#ffa94d','#2bb673','#e84a8d','#4dabf7','#4263eb'];
function bar(ctx, labels, values, title, colorIdx) {
    new Chart(ctx, { type:'bar', data:{ labels:labels, datasets:[{ label:title, data:values, backgroundColor:P, borderRadius:8, borderSkipped:false, maxBarThickness:50 }] },
        options:{ responsive:true, maintainAspectRatio:false,
            plugins:{ legend:{display:false},
                tooltip:{ backgroundColor:'#1a1d2e', titleFont:{weight:'700'}, bodyFont:{weight:'600'}, padding:12, cornerRadius:10, callbacks:{label:c=>'₱'+Number(c.raw).toLocaleString()} } },
            scales:{ y:{ ticks:{ callback:v=>'₱'+Number(v).toLocaleString(), font:{weight:'600'} }, grid:{color:'#eceef5'} },
                     x:{ grid:{display:false}, ticks:{font:{weight:'600'}} } } } });
}
const totals = <?= json_encode($totals); ?>;
bar(document.getElementById('yoyTotalsChart'), totals.map(r=>r.sy), totals.map(r=>Number(r.total_amount||0)), 'Total donations');
const dA = <?= json_encode($districtsA); ?>, dB = <?= json_encode($districtsB); ?>;
bar(document.getElementById('districtChartA'), dA.map(r=>r.district||'—'), dA.map(r=>Number(r.total_amount||0)), '<?= $esc($syA); ?>');
bar(document.getElementById('districtChartB'), dB.map(r=>r.district||'—'), dB.map(r=>Number(r.total_amount||0)), '<?= $esc($syB); ?>');
const tA = <?= json_encode($typesA); ?>, tB = <?= json_encode($typesB); ?>;
bar(document.getElementById('typeChartA'), tA.map(r=>String(r.contribution_type||'').replace(/_/g,' ')), tA.map(r=>Number(r.total_amount||0)), '<?= $esc($syA); ?>');
bar(document.getElementById('typeChartB'), tB.map(r=>String(r.contribution_type||'').replace(/_/g,' ')), tB.map(r=>Number(r.total_amount||0)), '<?= $esc($syB); ?>');
</script>
</body>
</html>
