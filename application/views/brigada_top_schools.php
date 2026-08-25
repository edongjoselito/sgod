<?php include('templates/head.php'); ?>
<?php include('templates/header.php'); ?>
<?php
$esc = function ($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$rangeLabel = !empty($isCurrentYearDefault)
    ? ($defaultPeriodLabel ?? ('Current year (' . date('Y') . ')'))
    : (($dateFrom || $dateTo)
    ? (($dateFrom ?: 'Beginning') . ' to ' . ($dateTo ?: 'Present'))
    : 'All reporting dates');
$groups = array(
    'elementary' => array('title' => 'Elementary Schools', 'icon' => 'mdi-school', 'class' => 'elementary'),
    'junior_high' => array('title' => 'Junior High Schools', 'icon' => 'mdi-school-outline', 'class' => 'junior-high'),
    'junior_high_with_senior_high' => array('title' => 'Junior High with Senior High', 'icon' => 'mdi-school-outline', 'class' => 'integrated-high')
);
?>
<style>
    /* The legacy shell fixes #wrapper to the viewport and hides overflow.
       A 20-row leaderboard needs the document itself to be scrollable. */
    html { height: auto; min-height: 100%; }
    body { min-height: 100vh; overflow-x: hidden; overflow-y: auto !important; }
    #wrapper { display: flow-root; height: auto !important; min-height: 100vh; overflow: visible !important; }
    .content-page { min-height: calc(100vh - 70px); overflow: visible !important; margin-top: 70px !important; padding: 0 15px 105px !important; }
    .content-page > .content { margin-top: 0 !important; padding-top: 0 !important; }
    .top-schools-page { max-width: 1540px; margin: 0 auto; padding: 0 4px 42px; }
    .top-schools-hero { position: relative; overflow: hidden; padding: 27px 30px; border-radius: 18px; color: #fff; background: linear-gradient(135deg, #172554, #1d4ed8); box-shadow: 0 14px 32px rgba(30, 64, 175, .21); }
    .top-schools-hero:after { content: ''; position: absolute; width: 220px; height: 220px; right: -55px; top: -115px; border: 36px solid rgba(255,255,255,.08); border-radius: 50%; }
    .top-schools-hero h4 { color: #fff; margin: 0 0 6px; font-weight: 700; }
    .top-schools-hero p { margin: 0; opacity: .88; }
    .date-range-pill { position: relative; z-index: 1; display: inline-flex; align-items: center; margin-top: 15px; padding: 6px 11px; border-radius: 30px; background: rgba(255,255,255,.13); font-size: .79rem; }
    .top-schools-filter { margin: 20px 0; padding: 19px 21px; border: 0; border-radius: 16px; box-shadow: 0 5px 20px rgba(31, 41, 55, .08); }
    .top-schools-filter label { font-size: .78rem; color: #59657a; font-weight: 700; text-transform: uppercase; letter-spacing: .03em; }
    .ranking-card { height: calc(100% - 18px); margin-bottom: 18px; border: 0; border-radius: 16px; overflow: hidden; box-shadow: 0 5px 20px rgba(31, 41, 55, .08); }
    .ranking-card .card-header { border: 0; padding: 17px 20px; color: #fff; }
    .ranking-card.elementary .card-header { background: #087f5b; }
    .ranking-card.junior-high .card-header { background: #5c35a7; }
    .ranking-card.integrated-high .card-header { background: #b45309; }
    .ranking-card h5 { color: inherit; margin: 0; font-weight: 700; }
    .ranking-card .table { margin-bottom: 0; }
    .ranking-card td { vertical-align: middle; }
    .ranking-card th { color: #657187; border-top: 0; font-size: .72rem; letter-spacing: .04em; text-transform: uppercase; }
    .rank-number { width: 38px; height: 38px; display: inline-flex; justify-content: center; align-items: center; border-radius: 50%; font-weight: 800; background: #eef3fb; color: #2d4f85; }
    .rank-number.top-three { background: #fff3cd; color: #a46d00; }
    .school-name { color: #26344c; font-weight: 700; }
    .school-meta { color: #7c8799; font-size: .81rem; }
    .resource-total { color: #087f5b; font-weight: 800; white-space: nowrap; font-variant-numeric: tabular-nums; }
    .empty-ranking { padding: 36px 20px; color: #7c8799; text-align: center; }
    @media (max-width: 767.98px) { .content-page { padding: 0 10px 95px !important; } .top-schools-hero { padding: 22px; } .top-schools-filter { padding: 18px; } .top-schools-filter .btn { margin-top: 12px; } .ranking-card { height: auto; } }
</style>
<div class="content-page"><div class="content"><div class="container-fluid top-schools-page">
    <section class="top-schools-hero">
        <h4><i class="mdi mdi-trophy-variant-outline mr-1"></i> Top Schools</h4>
        <p>Top 20 schools ranked by total Brigada Eskwela resources generated.</p>
        <span class="date-range-pill"><i class="mdi mdi-calendar-range mr-1"></i><?= $esc($rangeLabel); ?></span>
    </section>

    <form class="card top-schools-filter" method="get" action="<?= base_url('Brigada/top_schools'); ?>">
        <div class="form-row align-items-end">
            <div class="form-group col-md-4 mb-md-0"><label for="dateFrom">Date from</label><input type="date" class="form-control" id="dateFrom" name="date_from" value="<?= $esc($dateFrom); ?>"></div>
            <div class="form-group col-md-4 mb-md-0"><label for="dateTo">Date to</label><input type="date" class="form-control" id="dateTo" name="date_to" value="<?= $esc($dateTo); ?>"></div>
            <div class="col-md-4"><button type="submit" class="btn btn-primary mr-2"><i class="mdi mdi-filter-outline"></i> Apply filter</button><a href="<?= base_url('Brigada/top_schools'); ?>" class="btn btn-light">Clear</a></div>
        </div>
        <small class="text-muted d-block mt-3">The current school year is shown by default. Change either date and the rankings update to match the selected range.</small>
    </form>

    <?php if ($filterError): ?><div class="alert alert-warning"><?= $esc($filterError); ?> Showing all reporting dates.</div><?php endif; ?>
    <div class="row">
        <?php foreach ($groups as $key => $group): $rows = $rankings[$key]; ?>
        <div class="col-xl-4 col-lg-6">
            <section class="card ranking-card <?= $group['class']; ?>">
                <header class="card-header d-flex justify-content-between align-items-center"><h5><i class="mdi <?= $group['icon']; ?> mr-1"></i><?= $group['title']; ?></h5><span class="badge badge-light"><?= count($rows); ?> of 20</span></header>
                <?php if (empty($rows)): ?>
                    <div class="empty-ranking"><i class="mdi mdi-chart-bar-stacked" style="font-size:30px"></i><p class="mb-0 mt-2">No resource reports found for this group.</p></div>
                <?php else: ?>
                    <div class="table-responsive"><table class="table table-hover"><thead><tr><th class="text-center">Rank</th><th>School</th><th class="text-right">Resources Generated</th></tr></thead><tbody>
                        <?php foreach ($rows as $index => $row): $rank = $index + 1; ?>
                        <tr><td class="text-center"><span class="rank-number<?= $rank <= 3 ? ' top-three' : ''; ?>"><?= $rank; ?></span></td><td><div class="school-name"><?= $esc($row->schoolName); ?></div><div class="school-meta"><?= $esc($row->district ?: 'District not specified'); ?> &middot; <?= (int) $row->report_count; ?> contribution<?= (int) $row->report_count === 1 ? '' : 's'; ?></div></td><td class="text-right resource-total">&#8369;<?= number_format((float) $row->total_resources, 2); ?></td></tr>
                        <?php endforeach; ?>
                    </tbody></table></div>
                <?php endif; ?>
            </section>
        </div>
        <?php endforeach; ?>
    </div>
</div></div></div>
<?php include('templates/footer.php'); ?>
