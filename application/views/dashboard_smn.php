<?php
$sectionName = $this->session->userdata('section') ?: 'Social Mobilization and Networking';
$publicSchoolCount = !empty($data) && isset($data[0]->schoolCounts) ? (int) $data[0]->schoolCounts : 0;
$privateSchoolCount = !empty($data1) && isset($data1[0]->schoolCounts) ? (int) $data1[0]->schoolCounts : 0;
$accomplishmentCount = !empty($data2) && isset($data2[0]->Counts) ? (int) $data2[0]->Counts : 0;
$sectionUserCount = !empty($data3) && isset($data3[0]->Counts) ? (int) $data3[0]->Counts : 0;
$totalSchools = $publicSchoolCount + $privateSchoolCount;

$quickLinks = array(
    array(
        'title' => 'School directory',
        'copy' => 'Open one school directory and filter inside the page for public or private records.',
        'href' => base_url() . 'Page/schools',
        'icon' => 'mdi-arrow-top-right'
    ),
    array(
        'title' => 'Section accomplishments',
        'copy' => 'Check submitted accomplishment entries and updates.',
        'href' => base_url() . 'Page/viewSecAccomplishments',
        'icon' => 'mdi-arrow-top-right'
    )
);
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
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />

        <style>
            :root {
                --user-navy: #272b8c;
                --user-blue: #3c40c6;
                --user-teal: #565de8;
                --user-ink: #23275d;
                --user-muted: #7b7fa7;
                --user-border: rgba(60, 64, 198, 0.12);
                --user-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            }

            body {
                background:
                    radial-gradient(circle at top left, rgba(60, 64, 198, 0.10), transparent 24%),
                    linear-gradient(180deg, #f4f8fc 0%, #eef4fa 100%);
            }

            .content-page {
                background: transparent;
            }

            .user-shell {
                position: relative;
                padding-bottom: 28px;
            }

            .user-shell::before {
                content: "";
                position: absolute;
                inset: 24px 0 auto;
                height: 260px;
                border-radius: 30px;
                background: linear-gradient(135deg, rgba(45, 127, 249, 0.09), rgba(15, 159, 154, 0.08));
                z-index: 0;
            }

            .user-shell > * {
                position: relative;
                z-index: 1;
            }

            .dashboard-hero {
                margin-top: 20px;
                margin-bottom: 24px;
                border-radius: 28px;
                overflow: hidden;
                color: #ffffff;
                box-shadow: var(--user-shadow);
                background:
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.18), transparent 32%),
                    linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
            }

            .dashboard-hero-body {
                padding: 34px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 30px;
                flex-wrap: wrap;
            }

            .hero-copy-wrap {
                flex: 1 1 420px;
                max-width: 640px;
            }

            .hero-eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 14px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.12);
                border: 1px solid rgba(255, 255, 255, 0.18);
                font-size: 0.8rem;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .hero-title {
                margin: 18px 0 12px;
                color: #ffffff;
                font-size: clamp(2rem, 3vw, 2.8rem);
                line-height: 1.05;
                font-weight: 700;
                letter-spacing: -0.03em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .panel-card {
                height: 100%;
                border-radius: 24px;
                border: 1px solid rgba(60, 64, 198, 0.08);
                background: rgba(255, 255, 255, 0.95);
                box-shadow: var(--user-shadow);
                padding: 28px;
            }

            .panel-kicker {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 14px;
                border-radius: 999px;
                background: rgba(60, 64, 198, 0.08);
                color: var(--user-blue);
                font-size: 0.76rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .panel-title {
                margin: 18px 0 10px;
                color: var(--user-ink);
                font-size: 1.45rem;
                font-weight: 700;
                line-height: 1.2;
            }

            .panel-copy {
                color: var(--user-muted);
                font-size: 0.95rem;
                line-height: 1.7;
                margin-bottom: 0;
            }

            .overview-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 16px;
                margin-top: 24px;
            }

            .overview-stat {
                border-radius: 20px;
                background: linear-gradient(180deg, #f8faff 0%, #eef4ff 100%);
                border: 1px solid rgba(60, 64, 198, 0.08);
                padding: 20px;
            }

            .overview-stat-label {
                display: block;
                color: var(--user-muted);
                font-size: 0.82rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.06em;
                margin-bottom: 10px;
            }

            .overview-stat-value {
                display: block;
                color: var(--user-ink);
                font-size: 2rem;
                line-height: 1;
                font-weight: 700;
                margin-bottom: 8px;
            }

            .overview-stat-note {
                display: block;
                color: var(--user-muted);
                font-size: 0.88rem;
                line-height: 1.5;
            }

            .action-stack {
                display: grid;
                gap: 14px;
                margin-top: 24px;
            }

            .action-tile {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 14px;
                padding: 18px 20px;
                border-radius: 18px;
                border: 1px solid rgba(60, 64, 198, 0.08);
                background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
                text-decoration: none;
                transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            }

            .action-tile:hover,
            .action-tile:focus {
                text-decoration: none;
                transform: translateY(-1px);
                border-color: rgba(60, 64, 198, 0.16);
                box-shadow: 0 18px 30px rgba(15, 23, 42, 0.08);
            }

            .action-tile-title {
                color: var(--user-ink);
                font-size: 0.98rem;
                font-weight: 700;
                margin-bottom: 6px;
            }

            .action-tile-copy {
                color: var(--user-muted);
                font-size: 0.88rem;
                line-height: 1.55;
                margin: 0;
            }

            .action-tile-icon {
                color: var(--user-blue);
                font-size: 1.25rem;
                flex-shrink: 0;
            }

            @media (max-width: 991.98px) {
                .dashboard-hero-body {
                    padding: 28px;
                }
            }

            @media (max-width: 767.98px) {
                .user-shell::before {
                    left: 12px;
                    right: 12px;
                    inset-block-start: 18px;
                    height: 210px;
                }

                .dashboard-hero {
                    border-radius: 22px;
                }

                .dashboard-hero-body,
                .panel-card {
                    padding: 22px;
                }

                .hero-title {
                    font-size: 2rem;
                }
            }
        </style>
    </head>

    <body>

        <div id="wrapper">

            <?php include('includes/top-bar.php'); ?>

            <?php include('includes/sidebar.php') ?>

            <div class="content-page">
                <div class="content">
                    <div class="container-fluid user-shell">
                        <div class="dashboard-hero">
                            <div class="dashboard-hero-body">
                                <div class="hero-copy-wrap">
                                    <span class="hero-eyebrow">
                                        <i class="mdi mdi-account-group-outline"></i>
                                        Section Dashboard
                                    </span>
                                    <h1 class="hero-title"><?= htmlspecialchars($sectionName, ENT_QUOTES, 'UTF-8'); ?></h1>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-7 mb-4">
                                <div class="panel-card">
                                    <div class="panel-kicker">Overview</div>
                                    <h4 class="panel-title">A cleaner section summary for SMN</h4>
                                    <div class="overview-grid">
                                        <div class="overview-stat">
                                            <span class="overview-stat-label">Total schools</span>
                                            <strong class="overview-stat-value"><?= number_format($totalSchools); ?></strong>
                                            <span class="overview-stat-note">Combined public and private schools covered by the section.</span>
                                        </div>
                                        <div class="overview-stat">
                                            <span class="overview-stat-label">Accomplishments</span>
                                            <strong class="overview-stat-value"><?= number_format($accomplishmentCount); ?></strong>
                                            <span class="overview-stat-note">Reported accomplishment entries currently available for review.</span>
                                        </div>
                                        <div class="overview-stat">
                                            <span class="overview-stat-label">Active team</span>
                                            <strong class="overview-stat-value"><?= number_format($sectionUserCount); ?></strong>
                                            <span class="overview-stat-note">Section users who can access and work inside the SMN area.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-5 mb-4">
                                <div class="panel-card">
                                    <div class="panel-kicker">Quick Access</div>
                                    <h4 class="panel-title">Open your most-used SMN pages</h4>
                                    <div class="action-stack">
                                        <?php foreach ($quickLinks as $quickLink) { ?>
                                            <a href="<?= htmlspecialchars($quickLink['href'], ENT_QUOTES, 'UTF-8'); ?>" class="action-tile">
                                                <div>
                                                    <div class="action-tile-title"><?= htmlspecialchars($quickLink['title'], ENT_QUOTES, 'UTF-8'); ?></div>
                                                    <p class="action-tile-copy"><?= htmlspecialchars($quickLink['copy'], ENT_QUOTES, 'UTF-8'); ?></p>
                                                </div>
                                                <i class="mdi <?= htmlspecialchars($quickLink['icon'], ENT_QUOTES, 'UTF-8'); ?> action-tile-icon"></i>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include('includes/whereabouts_widget.php'); ?>
                <?php include('includes/footer.php'); ?>
            </div>
        </div>

        <?php include('includes/right-sidebar.php'); ?>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>

    </body>
</html>
