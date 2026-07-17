<?php
$dashboardConfig = isset($dashboardConfig) && is_array($dashboardConfig) ? $dashboardConfig : array();
$dashboardEsc = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
$dashboardMetrics = isset($dashboardConfig['metrics']) && is_array($dashboardConfig['metrics']) ? $dashboardConfig['metrics'] : array();
$dashboardQuickLinks = isset($dashboardConfig['quick_links']) && is_array($dashboardConfig['quick_links']) ? $dashboardConfig['quick_links'] : array();
$dashboardProfileName = trim((string) ($dashboardConfig['profile_name'] ?? ''));
$dashboardProfileRole = trim((string) ($dashboardConfig['profile_role'] ?? ''));
$dashboardNameParts = $dashboardProfileName !== '' ? preg_split('/\s+/', $dashboardProfileName) : array();
$dashboardInitials = '';
if (!empty($dashboardNameParts)) {
    $dashboardInitials = strtoupper(substr($dashboardNameParts[0], 0, 1));
    if (count($dashboardNameParts) > 1) {
        $dashboardInitials .= strtoupper(substr($dashboardNameParts[count($dashboardNameParts) - 1], 0, 1));
    }
}
$dashboardInitials = $dashboardInitials !== '' ? $dashboardInitials : 'SG';
$dashboardDistribution = isset($dashboardConfig['distribution']) && is_array($dashboardConfig['distribution'])
    ? $dashboardConfig['distribution']
    : null;
$dashboardShowWhereabouts = !array_key_exists('show_whereabouts', $dashboardConfig) || !empty($dashboardConfig['show_whereabouts']);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <?php include(__DIR__ . '/page-title.php'); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
        <link href="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/dashboard-unified.css" rel="stylesheet" type="text/css" />
    </head>

    <body class="dashboard-root-theme">
        <div id="wrapper">
            <?php include(__DIR__ . '/top-bar.php'); ?>
            <?php include(__DIR__ . '/sidebar.php'); ?>

            <div class="content-page">
                <div class="content">
                    <main class="container-fluid dashboard-shell">
                        <section class="dashboard-hero">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <div class="hero-content">
                                        <span class="hero-eyebrow">
                                            <i class="mdi <?= $dashboardEsc($dashboardConfig['eyebrow_icon'] ?? 'mdi-view-dashboard-outline'); ?>"></i>
                                            <?= $dashboardEsc($dashboardConfig['eyebrow'] ?? 'Dashboard'); ?>
                                        </span>
                                        <h1 class="hero-title"><?= $dashboardEsc($dashboardConfig['title'] ?? 'Dashboard'); ?></h1>
                                        <p class="hero-subtitle"><?= $dashboardEsc($dashboardConfig['subtitle'] ?? 'Monitor your work and open frequently used tools from one workspace.'); ?></p>
                                    </div>
                                </div>

                                <?php if ($dashboardProfileName !== '' || $dashboardProfileRole !== '') { ?>
                                    <div class="col-lg-4">
                                        <div class="hero-profile">
                                            <span class="hero-avatar"><?= $dashboardEsc($dashboardInitials); ?></span>
                                            <div class="hero-profile-copy">
                                                <strong><?= $dashboardEsc($dashboardProfileName !== '' ? $dashboardProfileName : 'Dashboard User'); ?></strong>
                                                <span><?= $dashboardEsc($dashboardProfileRole !== '' ? $dashboardProfileRole : 'User'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </section>

                        <?php if (!empty($dashboardMetrics)) { ?>
                            <div class="row summary-row">
                                <?php foreach ($dashboardMetrics as $metric) {
                                    $metricHref = trim((string) ($metric['href'] ?? ''));
                                    $metricTag = $metricHref !== '' ? 'a' : 'div';
                                ?>
                                    <div class="col-xl col-md-4 col-sm-6 mb-2">
                                        <<?= $metricTag; ?><?= $metricHref !== '' ? ' href="' . $dashboardEsc($metricHref) . '"' : ''; ?> class="summary-card">
                                            <span class="summary-icon"><i class="mdi <?= $dashboardEsc($metric['icon'] ?? 'mdi-chart-box-outline'); ?>"></i></span>
                                            <span class="summary-copy">
                                                <strong class="summary-value" data-count="<?= (int) ($metric['value'] ?? 0); ?>"><?= number_format((int) ($metric['value'] ?? 0)); ?></strong>
                                                <span class="summary-label"><?= $dashboardEsc($metric['label'] ?? 'Total'); ?></span>
                                                <span class="summary-context"><?= $dashboardEsc($metric['context'] ?? 'Current records'); ?></span>
                                            </span>
                                            <?php if ($metricHref !== '') { ?><i class="mdi mdi-chevron-right summary-arrow"></i><?php } ?>
                                        </<?= $metricTag; ?>>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <?php if ($dashboardDistribution) {
                            $distributionPublic = (int) ($dashboardDistribution['public'] ?? 0);
                            $distributionPrivate = (int) ($dashboardDistribution['private'] ?? 0);
                            $distributionTotal = $distributionPublic + $distributionPrivate;
                            $distributionPublicShare = $distributionTotal > 0 ? (int) round(($distributionPublic / $distributionTotal) * 100) : 0;
                            $distributionPrivateShare = $distributionTotal > 0 ? 100 - $distributionPublicShare : 0;
                        ?>
                            <div class="row dashboard-insights-row">
                                <div class="col-12 mb-2">
                                    <div class="distribution-card">
                                        <div class="distribution-head">
                                            <span class="distribution-title"><i class="mdi mdi-chart-donut"></i> School Distribution</span>
                                            <span class="distribution-total"><?= number_format($distributionTotal); ?> schools</span>
                                        </div>
                                        <div class="distribution-bar" role="img" aria-label="Public schools <?= $distributionPublicShare; ?>%, private schools <?= $distributionPrivateShare; ?>%">
                                            <span class="distribution-segment is-public" style="width: <?= $distributionPublicShare; ?>%;"></span>
                                            <span class="distribution-segment is-private" style="width: <?= $distributionPrivateShare; ?>%;"></span>
                                        </div>
                                        <div class="distribution-legend">
                                            <span class="legend-item"><span class="legend-dot is-public"></span> Public <strong><?= number_format($distributionPublic); ?></strong> <span class="legend-pct"><?= $distributionPublicShare; ?>%</span></span>
                                            <span class="legend-item"><span class="legend-dot is-private"></span> Private <strong><?= number_format($distributionPrivate); ?></strong> <span class="legend-pct"><?= $distributionPrivateShare; ?>%</span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if (!empty($dashboardQuickLinks)) { ?>
                            <div class="row dashboard-main-row">
                                <div class="col-12">
                                    <section class="dashboard-card">
                                        <div class="card-heading">
                                            <div class="heading-group">
                                                <span class="heading-icon"><i class="mdi mdi-lightning-bolt-outline"></i></span>
                                                <div>
                                                    <h2 class="card-title"><?= $dashboardEsc($dashboardConfig['quick_links_title'] ?? 'Quick Actions'); ?></h2>
                                                    <p class="card-caption"><?= $dashboardEsc($dashboardConfig['quick_links_caption'] ?? 'Open frequently used pages'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dashboard-card-body">
                                            <div class="quick-action-grid">
                                                <?php foreach ($dashboardQuickLinks as $quickLink) { ?>
                                                    <a href="<?= $dashboardEsc($quickLink['href'] ?? '#'); ?>" class="quick-action">
                                                        <span>
                                                            <strong><?= $dashboardEsc($quickLink['label'] ?? 'Open'); ?></strong>
                                                            <small><?= $dashboardEsc($quickLink['context'] ?? 'Go to this page'); ?></small>
                                                        </span>
                                                        <span class="action-icon"><i class="mdi <?= $dashboardEsc($quickLink['icon'] ?? 'mdi-arrow-top-right'); ?>"></i></span>
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ($dashboardShowWhereabouts) { ?>
                            <div class="row dashboard-main-row">
                                <div class="col-12">
                                    <section class="dashboard-card">
                                        <div class="card-heading">
                                            <div class="heading-group">
                                                <span class="heading-icon"><i class="mdi mdi-calendar-month-outline"></i></span>
                                                <div>
                                                    <h2 class="card-title">My Calendar</h2>
                                                    <p class="card-caption">Schedules and whereabouts</p>
                                                </div>
                                            </div>
                                            <span class="today-badge"><i class="mdi mdi-calendar-today-outline"></i><?= $dashboardEsc(date('l, F j, Y')); ?></span>
                                        </div>
                                        <div class="calendar-zone">
                                            <?php include(__DIR__ . '/whereabouts_widget.php'); ?>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        <?php } ?>
                    </main>
                </div>

                <?php include(__DIR__ . '/footer.php'); ?>
            </div>
        </div>

        <?php include(__DIR__ . '/right-sidebar.php'); ?>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
    </body>
</html>
