<?php
$sectionName = $this->session->userdata('section') ?: 'Section';
$sectionHeadName = trim(preg_replace('/\s+/', ' ', implode(' ', array_filter(array(
    $this->session->userdata('fName'),
    $this->session->userdata('mName'),
    $this->session->userdata('lName')
)))));
$sectionHeadPosition = !empty($sectionRecord->sectionHeadPosition) ? trim((string) $sectionRecord->sectionHeadPosition) : 'Section Head';
$publicSchoolCount = !empty($data) && isset($data[0]->schoolCounts) ? (int) $data[0]->schoolCounts : 0;
$privateSchoolCount = !empty($data1) && isset($data1[0]->schoolCounts) ? (int) $data1[0]->schoolCounts : 0;
$accomplishmentCount = !empty($data2) && isset($data2[0]->Counts) ? (int) $data2[0]->Counts : 0;
$sectionUserCount = !empty($data3) && isset($data3[0]->Counts) ? (int) $data3[0]->Counts : 0;
$totalSchools = $publicSchoolCount + $privateSchoolCount;
$publicShare = $totalSchools > 0 ? (int) round(($publicSchoolCount / $totalSchools) * 100) : 0;
$privateShare = $totalSchools > 0 ? 100 - $publicShare : 0;

$summaryCards = array(
    array(
        'value' => $totalSchools,
        'label' => 'Total Schools',
        'context' => number_format($publicSchoolCount) . ' public · ' . number_format($privateSchoolCount) . ' private',
        'icon' => 'mdi-school-outline',
        'href' => base_url() . 'Page/schools'
    ),
    array(
        'value' => $publicSchoolCount,
        'label' => 'Public Schools',
        'context' => $totalSchools > 0 ? $publicShare . '% of total' : 'No schools yet',
        'icon' => 'mdi-domain',
        'href' => base_url() . 'Page/schools'
    ),
    array(
        'value' => $privateSchoolCount,
        'label' => 'Private Schools',
        'context' => $totalSchools > 0 ? $privateShare . '% of total' : 'No schools yet',
        'icon' => 'mdi-home-city-outline',
        'href' => base_url() . 'Page/schools'
    ),
    array(
        'value' => $accomplishmentCount,
        'label' => 'Accomplishments',
        'context' => 'View submitted reports',
        'icon' => 'mdi-file-check-outline',
        'href' => base_url() . 'Page/viewSecAccomplishments'
    )
);

$nameParts = $sectionHeadName !== '' ? preg_split('/\s+/', $sectionHeadName) : array();
$sectionHeadInitials = '';
if (!empty($nameParts)) {
    $sectionHeadInitials .= strtoupper(substr($nameParts[0], 0, 1));
    if (count($nameParts) > 1) {
        $sectionHeadInitials .= strtoupper(substr($nameParts[count($nameParts) - 1], 0, 1));
    }
}
$sectionHeadInitials = $sectionHeadInitials !== '' ? $sectionHeadInitials : 'SH';
$dashboardDate = date('l, F j, Y');
$shouldPromptAvatarUpdate = !empty($shouldPromptAvatarUpdate);
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <?php include('includes/page-title.php'); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Responsive bootstrap 4 admin template" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
        <link href="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />

        <style>
            :root {
                --dashboard-primary: #3c40c6;
                --dashboard-primary-dark: #272b8c;
                --dashboard-primary-soft: #eef0ff;
                --dashboard-ink: #23264a;
                --dashboard-muted: #737894;
                --dashboard-border: #e7e9f3;
                --dashboard-surface: #ffffff;
                --dashboard-background: #f4f6fb;
                --dashboard-shadow: 0 12px 30px rgba(35, 38, 74, 0.08);
                --dashboard-shadow-hover: 0 18px 38px rgba(35, 38, 74, 0.13);
            }

            body {
                background:
                    radial-gradient(circle at 12% 0%, rgba(60, 64, 198, 0.08), transparent 26%),
                    var(--dashboard-background);
            }

            .content-page {
                background: transparent;
            }

            .dashboard-shell {
                padding-top: 20px;
                padding-bottom: 28px;
            }

            .dashboard-hero {
                position: relative;
                min-height: 154px;
                margin-bottom: 0;
                padding: 28px 30px 52px;
                overflow: hidden;
                border-radius: 24px;
                color: #ffffff;
                background:
                    radial-gradient(circle at 85% 15%, rgba(255, 255, 255, 0.23), transparent 26%),
                    linear-gradient(135deg, var(--dashboard-primary-dark) 0%, var(--dashboard-primary) 62%, #6c70ef 100%);
                box-shadow: 0 20px 45px rgba(39, 43, 140, 0.20);
            }

            .dashboard-hero::after {
                content: "";
                position: absolute;
                right: -70px;
                bottom: -110px;
                width: 260px;
                height: 260px;
                border: 45px solid rgba(255, 255, 255, 0.07);
                border-radius: 50%;
            }

            .hero-content,
            .hero-profile {
                position: relative;
                z-index: 1;
            }

            .hero-eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 7px;
                margin-bottom: 10px;
                color: rgba(255, 255, 255, 0.76);
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .hero-title {
                margin: 0;
                color: #ffffff;
                font-size: clamp(1.75rem, 3vw, 2.35rem);
                line-height: 1.15;
                font-weight: 700;
                letter-spacing: -0.025em;
            }

            .hero-subtitle {
                max-width: 600px;
                margin: 9px 0 0;
                color: rgba(255, 255, 255, 0.78);
                font-size: 0.94rem;
                line-height: 1.55;
            }

            .hero-profile {
                display: flex;
                align-items: center;
                justify-content: flex-end;
                gap: 13px;
                min-width: 250px;
                padding: 13px 15px;
                border: 1px solid rgba(255, 255, 255, 0.16);
                border-radius: 16px;
                background: rgba(255, 255, 255, 0.10);
                backdrop-filter: blur(12px);
            }

            .hero-avatar {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 46px;
                height: 46px;
                flex: 0 0 46px;
                border: 2px solid rgba(255, 255, 255, 0.22);
                border-radius: 15px;
                color: #ffffff;
                background: rgba(255, 255, 255, 0.16);
                font-size: 0.95rem;
                font-weight: 800;
                letter-spacing: 0.04em;
            }

            .hero-profile-copy {
                min-width: 0;
                text-align: left;
            }

            .hero-profile-copy strong,
            .hero-profile-copy span {
                display: block;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .hero-profile-copy strong {
                color: #ffffff;
                font-size: 0.92rem;
            }

            .hero-profile-copy span {
                margin-top: 3px;
                color: rgba(255, 255, 255, 0.69);
                font-size: 0.78rem;
            }

            .summary-row {
                position: relative;
                z-index: 2;
                margin-top: -33px;
                margin-right: 0;
                margin-left: 0;
                padding: 0 15px;
            }

            .summary-row > [class*="col-"] {
                padding-right: 7px;
                padding-left: 7px;
            }

            .summary-card {
                position: relative;
                display: flex;
                align-items: center;
                gap: 13px;
                min-height: 94px;
                height: 100%;
                padding: 17px 34px 17px 18px;
                border: 1px solid rgba(231, 233, 243, 0.95);
                border-radius: 17px;
                background: rgba(255, 255, 255, 0.98);
                box-shadow: var(--dashboard-shadow);
                text-decoration: none;
                transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            }

            .summary-card:hover,
            .summary-card:focus {
                text-decoration: none;
                transform: translateY(-3px);
                border-color: rgba(60, 64, 198, 0.24);
                box-shadow: var(--dashboard-shadow-hover);
            }

            .summary-icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 46px;
                height: 46px;
                flex: 0 0 46px;
                border-radius: 14px;
                color: var(--dashboard-primary);
                background: var(--dashboard-primary-soft);
                font-size: 1.35rem;
                transition: background 0.2s ease, color 0.2s ease;
            }

            .summary-card:hover .summary-icon,
            .summary-card:focus .summary-icon {
                color: #ffffff;
                background: var(--dashboard-primary);
            }

            .summary-copy {
                min-width: 0;
            }

            .summary-value {
                display: block;
                margin-bottom: 4px;
                color: var(--dashboard-ink);
                font-size: 1.55rem;
                line-height: 1;
                font-weight: 750;
            }

            .summary-label {
                display: block;
                color: var(--dashboard-ink);
                font-size: 0.82rem;
                font-weight: 700;
                white-space: nowrap;
            }

            .summary-context {
                display: block;
                margin-top: 3px;
                overflow: hidden;
                color: var(--dashboard-muted);
                font-size: 0.72rem;
                font-weight: 600;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .summary-arrow {
                position: absolute;
                top: 50%;
                right: 12px;
                color: #c2c5d8;
                font-size: 1.1rem;
                transform: translateY(-50%) translateX(-3px);
                opacity: 0;
                transition: opacity 0.2s ease, transform 0.2s ease, color 0.2s ease;
            }

            .summary-card:hover .summary-arrow,
            .summary-card:focus .summary-arrow {
                color: var(--dashboard-primary);
                opacity: 1;
                transform: translateY(-50%) translateX(0);
            }

            .distribution-card {
                display: flex;
                flex-wrap: nowrap;
                align-items: center;
                gap: 12px 22px;
                height: 100%;
                margin: 0;
                padding: 16px 20px;
                border: 1px solid var(--dashboard-border);
                border-radius: 17px;
                background: var(--dashboard-surface);
                box-shadow: var(--dashboard-shadow);
            }

            .dashboard-insights-row {
                margin: 14px 7px 0;
            }

            .dashboard-insights-row > [class*="col-"] {
                padding-right: 8px;
                padding-left: 8px;
            }

            .distribution-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                flex: 1 1 200px;
            }

            .distribution-title {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                color: var(--dashboard-ink);
                font-size: 0.9rem;
                font-weight: 750;
            }

            .distribution-title .mdi {
                color: var(--dashboard-primary);
                font-size: 1.1rem;
            }

            .distribution-total {
                color: var(--dashboard-muted);
                font-size: 0.78rem;
                font-weight: 650;
                white-space: nowrap;
            }

            .distribution-bar {
                display: flex;
                flex: 2 1 260px;
                height: 12px;
                overflow: hidden;
                border-radius: 999px;
                background: var(--dashboard-primary-soft);
            }

            .distribution-segment {
                height: 100%;
                transition: width 0.6s ease;
            }

            .distribution-segment.is-public {
                background: linear-gradient(135deg, var(--dashboard-primary), #6c70ef);
            }

            .distribution-segment.is-private {
                background: linear-gradient(135deg, #26c6a5, #34d3a8);
            }

            .distribution-legend {
                display: flex;
                gap: 18px;
                flex: 1 1 auto;
                flex-wrap: wrap;
            }

            .legend-item {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                color: var(--dashboard-muted);
                font-size: 0.78rem;
                font-weight: 600;
            }

            .legend-item strong {
                color: var(--dashboard-ink);
                font-weight: 750;
            }

            .legend-dot {
                width: 10px;
                height: 10px;
                border-radius: 50%;
            }

            .legend-dot.is-public {
                background: var(--dashboard-primary);
            }

            .legend-dot.is-private {
                background: #26c6a5;
            }

            .legend-pct {
                padding: 1px 7px;
                border-radius: 999px;
                background: var(--dashboard-primary-soft);
                color: var(--dashboard-primary);
                font-size: 0.7rem;
                font-weight: 750;
            }

            .dashboard-main-row {
                margin-top: 18px;
                margin-right: -8px;
                margin-left: -8px;
            }

            .dashboard-main-row > [class*="col-"] {
                padding-right: 8px;
                padding-left: 8px;
            }

            .dashboard-card {
                border: 1px solid var(--dashboard-border);
                border-radius: 20px;
                background: var(--dashboard-surface);
                box-shadow: var(--dashboard-shadow);
            }

            .calendar-card {
                min-height: 690px;
                overflow: hidden;
            }

            .card-heading {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                min-height: 68px;
                padding: 16px 20px;
                border-bottom: 1px solid var(--dashboard-border);
            }

            .heading-group {
                display: flex;
                align-items: center;
                gap: 12px;
                min-width: 0;
            }

            .heading-icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 38px;
                height: 38px;
                flex: 0 0 38px;
                border-radius: 12px;
                color: var(--dashboard-primary);
                background: var(--dashboard-primary-soft);
                font-size: 1.15rem;
            }

            .card-title {
                margin: 0;
                color: var(--dashboard-ink);
                font-size: 1rem;
                font-weight: 750;
            }

            .card-caption {
                margin: 3px 0 0;
                color: var(--dashboard-muted);
                font-size: 0.77rem;
            }

            .today-badge {
                display: inline-flex;
                align-items: center;
                gap: 7px;
                padding: 7px 11px;
                border-radius: 999px;
                color: var(--dashboard-primary);
                background: var(--dashboard-primary-soft);
                font-size: 0.75rem;
                font-weight: 700;
                white-space: nowrap;
            }

            .calendar-zone {
                width: 100%;
                min-height: 620px;
                padding: 16px 14px 20px;
            }

            .calendar-zone > .container,
            .calendar-zone > .container-fluid,
            .calendar-zone .container,
            .calendar-zone .container-fluid {
                width: 100%;
                max-width: none;
                padding-right: 0 !important;
                padding-left: 0 !important;
            }

            .calendar-zone .row {
                margin-right: -6px;
                margin-left: -6px;
            }

            .calendar-zone .row > [class*="col-"] {
                padding-right: 6px;
                padding-left: 6px;
            }

            .calendar-zone .card,
            .calendar-zone .panel,
            .calendar-zone .box {
                margin-bottom: 12px !important;
                border: 1px solid var(--dashboard-border) !important;
                border-radius: 15px !important;
                box-shadow: none !important;
            }

            .calendar-zone .card-body,
            .calendar-zone .panel-body,
            .calendar-zone .box-body {
                padding: 14px !important;
            }

            .calendar-zone .fc-toolbar,
            .calendar-zone .fc-header-toolbar {
                margin-bottom: 15px !important;
            }

            .calendar-zone .fc-toolbar h2,
            .calendar-zone .fc .fc-toolbar-title {
                color: var(--dashboard-ink);
                font-size: 1.15rem !important;
                font-weight: 750;
            }

            .calendar-zone .fc-button,
            .calendar-zone .fc .fc-button {
                padding: 0.42rem 0.72rem !important;
                border-radius: 8px !important;
                font-size: 0.78rem !important;
                line-height: 1.25 !important;
            }

            .calendar-zone .fc-day-header,
            .calendar-zone .fc-col-header-cell-cushion,
            .calendar-zone .fc-daygrid-day-number {
                font-size: 0.84rem !important;
            }

            .calendar-zone .fc-view,
            .calendar-zone .fc-view-container,
            .calendar-zone .fc-scrollgrid,
            .calendar-zone table {
                width: 100% !important;
            }

            .calendar-zone .fc-basic-view .fc-body .fc-row {
                min-height: 88px;
            }

            .calendar-zone .fc-daygrid-day-frame {
                min-height: 88px;
            }

            .calendar-zone .fc-event,
            .calendar-zone .fc-day-grid-event,
            .calendar-zone .fc-daygrid-event {
                border-radius: 6px !important;
                font-size: 0.78rem !important;
            }

            @media (max-width: 1199.98px) {
                .summary-row {
                    row-gap: 14px;
                }

                .calendar-card {
                    min-height: auto;
                }

                .calendar-zone {
                    min-height: 620px;
                }
            }

            @media (max-width: 991.98px) {
                .hero-profile {
                    justify-content: flex-start;
                    margin-top: 18px;
                }

            }

            @media (max-width: 767.98px) {
                .dashboard-shell {
                    padding-top: 12px;
                }

                .dashboard-hero {
                    min-height: auto;
                    padding: 22px 20px 48px;
                    border-radius: 20px;
                }

                .hero-title {
                    font-size: 1.55rem;
                }

                .hero-subtitle {
                    font-size: 0.85rem;
                }

                .hero-profile {
                    min-width: 0;
                    width: 100%;
                }

                .summary-row {
                    margin-top: -28px;
                    padding: 0 9px;
                }

                .summary-card {
                    min-height: 82px;
                    padding: 14px;
                }

                .summary-icon {
                    width: 40px;
                    height: 40px;
                    flex-basis: 40px;
                }

                .summary-value {
                    font-size: 1.32rem;
                }

                .dashboard-insights-row > [class*="col-"] + [class*="col-"] {
                    margin-top: 8px;
                }

                .distribution-card {
                    flex-wrap: wrap;
                }

                .card-heading {
                    align-items: flex-start;
                    min-height: 62px;
                    padding: 14px 15px;
                }

                .today-badge {
                    display: none;
                }

                .calendar-zone {
                    min-height: 560px;
                    padding: 10px;
                }

                .calendar-zone .fc-basic-view .fc-body .fc-row,
                .calendar-zone .fc-daygrid-day-frame {
                    min-height: 68px;
                }

                .calendar-zone .fc-toolbar,
                .calendar-zone .fc-header-toolbar {
                    gap: 7px;
                }

                .calendar-zone .fc-toolbar h2,
                .calendar-zone .fc .fc-toolbar-title {
                    font-size: 0.98rem !important;
                }

            }
        </style>
        <link href="<?= base_url(); ?>assets/css/dashboard-unified.css" rel="stylesheet" type="text/css" />
    </head>

    <body class="dashboard-root-theme">

        <div id="wrapper">

            <?php include('includes/top-bar.php'); ?>

            <?php include('includes/sidebar.php') ?>

            <div class="content-page">
                <div class="content">
                    <div class="container-fluid dashboard-shell">
                        <section class="dashboard-hero">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <div class="hero-content">
                                        <span class="hero-eyebrow">
                                            <i class="mdi mdi-view-dashboard-outline"></i>
                                            Section Head Dashboard
                                        </span>
                                        <h1 class="hero-title"><?= htmlspecialchars($sectionName, ENT_QUOTES, 'UTF-8'); ?></h1>
                                        <p class="hero-subtitle">Monitor schedules, schools, accomplishments, and section activity from one workspace.</p>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="hero-profile">
                                        <span class="hero-avatar"><?= htmlspecialchars($sectionHeadInitials, ENT_QUOTES, 'UTF-8'); ?></span>
                                        <div class="hero-profile-copy">
                                            <strong><?= htmlspecialchars($sectionHeadName !== '' ? $sectionHeadName : 'Section Head', ENT_QUOTES, 'UTF-8'); ?></strong>
                                            <span><?= htmlspecialchars($sectionHeadPosition, ENT_QUOTES, 'UTF-8'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <div class="row summary-row">
                            <?php foreach ($summaryCards as $summaryCard) { ?>
                                <div class="col-xl col-md-4 col-sm-6 mb-2">
                                    <a href="<?= htmlspecialchars($summaryCard['href'], ENT_QUOTES, 'UTF-8'); ?>" class="summary-card">
                                        <span class="summary-icon"><i class="mdi <?= htmlspecialchars($summaryCard['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i></span>
                                        <div class="summary-copy">
                                            <strong class="summary-value" data-count="<?= (int) $summaryCard['value']; ?>"><?= number_format((int) $summaryCard['value']); ?></strong>
                                            <span class="summary-label"><?= htmlspecialchars($summaryCard['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                                            <span class="summary-context"><?= htmlspecialchars($summaryCard['context'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        </div>
                                        <i class="mdi mdi-chevron-right summary-arrow"></i>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="row dashboard-insights-row">
                            <div class="col-lg-9 mb-2">
                                <div class="distribution-card">
                                    <div class="distribution-head">
                                        <span class="distribution-title">
                                            <i class="mdi mdi-chart-donut"></i>
                                            School Distribution
                                        </span>
                                        <span class="distribution-total"><?= number_format($totalSchools); ?> schools</span>
                                    </div>
                                    <div class="distribution-bar" role="img" aria-label="Public schools <?= $publicShare; ?>%, private schools <?= $privateShare; ?>%">
                                        <span class="distribution-segment is-public" style="width: <?= $publicShare; ?>%;"></span>
                                        <span class="distribution-segment is-private" style="width: <?= $privateShare; ?>%;"></span>
                                    </div>
                                    <div class="distribution-legend">
                                        <span class="legend-item">
                                            <span class="legend-dot is-public"></span>
                                            Public <strong><?= number_format($publicSchoolCount); ?></strong>
                                            <span class="legend-pct"><?= $publicShare; ?>%</span>
                                        </span>
                                        <span class="legend-item">
                                            <span class="legend-dot is-private"></span>
                                            Private <strong><?= number_format($privateSchoolCount); ?></strong>
                                            <span class="legend-pct"><?= $privateShare; ?>%</span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 mb-2">
                                <a href="<?= base_url(); ?>Page/usersListv2" class="summary-card">
                                    <span class="summary-icon"><i class="mdi mdi-account-group-outline"></i></span>
                                    <div class="summary-copy">
                                        <strong class="summary-value" data-count="<?= (int) $sectionUserCount; ?>"><?= number_format($sectionUserCount); ?></strong>
                                        <span class="summary-label">Active Users</span>
                                        <span class="summary-context">Manage section accounts</span>
                                    </div>
                                    <i class="mdi mdi-chevron-right summary-arrow"></i>
                                </a>
                            </div>
                        </div>

                        <div class="row dashboard-main-row">
                            <div class="col-12">
                                <section class="dashboard-card calendar-card">
                                    <div class="card-heading">
                                        <div class="heading-group">
                                            <span class="heading-icon"><i class="mdi mdi-calendar-month-outline"></i></span>
                                            <div>
                                                <h2 class="card-title">Section Calendar</h2>
                                                <p class="card-caption">Schedules and staff whereabouts</p>
                                            </div>
                                        </div>
                                        <span class="today-badge">
                                            <i class="mdi mdi-calendar-today-outline"></i>
                                            <?= htmlspecialchars($dashboardDate, ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </div>
                                    <div class="calendar-zone">
                                        <?php include('includes/whereabouts_widget.php'); ?>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include('includes/footer.php'); ?>
            </div>
        </div>

        <?php include('includes/right-sidebar.php'); ?>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
        <script>
            (function () {
                const shouldPromptAvatarUpdate = <?= json_encode($shouldPromptAvatarUpdate); ?>;
                const profilePictureUploadUrl = <?= json_encode(base_url() . 'Page/upload_user_profile_picture', JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;

                function showProfilePictureUploadPrompt() {
                    Swal.fire({
                        title: 'Upload a fresh profile picture',
                        html: `
                            <p style="color:#64748b; margin-bottom:14px;">
                                Pick a clear photo so everyone can recognize you more easily.
                            </p>
                            <input type="file" id="profilePictureInput" class="swal2-file" accept=".jpg,.jpeg,.png,.gif,image/*">
                            <p style="color:#94a3b8; font-size:0.85rem; margin:10px 0 0;">
                                Accepted formats: JPG, JPEG, PNG, GIF. Maximum file size: 2 MB.
                            </p>
                        `,
                        type: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Upload photo',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#3c40c6',
                        cancelButtonColor: '#6c757d',
                        showLoaderOnConfirm: true,
                        allowOutsideClick: function() {
                            return !Swal.isLoading();
                        },
                        preConfirm: function() {
                            const fileInput = document.getElementById('profilePictureInput');
                            const selectedFile = fileInput && fileInput.files ? fileInput.files[0] : null;

                            if (!selectedFile) {
                                Swal.showValidationMessage('Please choose an image to upload.');
                                return false;
                            }

                            if (!/\.(jpg|jpeg|png|gif)$/i.test(selectedFile.name || '')) {
                                Swal.showValidationMessage('Please choose a JPG, JPEG, PNG, or GIF image.');
                                return false;
                            }

                            if (selectedFile.size > (2 * 1024 * 1024)) {
                                Swal.showValidationMessage('The selected image is too large. Please use a file under 2 MB.');
                                return false;
                            }

                            const formData = new FormData();
                            formData.append('avatar', selectedFile);

                            return fetch(profilePictureUploadUrl, {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: formData
                            })
                            .then(function(response) {
                                return response.json().catch(function() {
                                    throw new Error('Upload failed. Please try again.');
                                });
                            })
                            .then(function(data) {
                                if (!data.success) {
                                    throw new Error(data.message || 'Unable to upload your profile picture.');
                                }

                                return data;
                            })
                            .catch(function(error) {
                                Swal.showValidationMessage(error.message || 'Unable to upload your profile picture.');
                            });
                        }
                    }).then(function(result) {
                        if (result.value && result.value.success) {
                            Swal.fire({
                                title: 'Profile picture updated',
                                text: 'Your new photo has been saved successfully.',
                                type: 'success',
                                confirmButtonColor: '#3c40c6'
                            }).then(function() {
                                window.location.reload();
                            });
                        }
                    });
                }

                function showDefaultAvatarReminder() {
                    Swal.fire({
                        title: 'Let your profile shine',
                        text: 'You are still using the default avatar. Add your photo to make your account feel more personal and easy to recognize.',
                        type: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Change profile picture',
                        cancelButtonText: 'Maybe later',
                        confirmButtonColor: '#3c40c6',
                        cancelButtonColor: '#6c757d'
                    }).then(function(result) {
                        if (result.value) {
                            showProfilePictureUploadPrompt();
                        }
                    });
                }

                document.querySelectorAll('.js-change-profile-picture').forEach(function(trigger) {
                    trigger.addEventListener('click', function(event) {
                        event.preventDefault();
                        showProfilePictureUploadPrompt();
                    });
                });

                animateCountUp();

                const url = new URL(window.location.href);
                if (url.searchParams.get('open_profile_picture') === '1') {
                    url.searchParams.delete('open_profile_picture');
                    window.history.replaceState({}, document.title, url.pathname + (url.search ? '?' + url.searchParams.toString() : '') + url.hash);
                    showProfilePictureUploadPrompt();
                    return;
                }

                if (shouldPromptAvatarUpdate) {
                    window.setTimeout(showDefaultAvatarReminder, 500);
                }

                function animateCountUp() {
                    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                        return;
                    }

                    document.querySelectorAll('.summary-value[data-count]').forEach(function(el) {
                        const target = parseInt(el.getAttribute('data-count'), 10) || 0;
                        if (target <= 0) {
                            return;
                        }

                        const duration = 900;
                        const start = performance.now();

                        function tick(now) {
                            const progress = Math.min((now - start) / duration, 1);
                            const eased = 1 - Math.pow(1 - progress, 3);
                            el.textContent = Math.round(target * eased).toLocaleString();
                            if (progress < 1) {
                                requestAnimationFrame(tick);
                            }
                        }

                        el.textContent = '0';
                        requestAnimationFrame(tick);
                    });
                }

                function resizeDashboardCalendar() {
                    window.dispatchEvent(new Event('resize'));

                    if (window.jQuery) {
                        const $calendar = window.jQuery('#calendar');
                        if ($calendar.length && typeof $calendar.fullCalendar === 'function') {
                            try {
                                $calendar.fullCalendar('option', 'height', 'parent');
                                $calendar.fullCalendar('updateSize');
                            } catch (error) {
                                // The calendar may use a different FullCalendar version.
                            }
                        }
                    }
                }

                window.setTimeout(resizeDashboardCalendar, 250);
                window.setTimeout(resizeDashboardCalendar, 900);
            })();
        </script>

    </body>
</html>
