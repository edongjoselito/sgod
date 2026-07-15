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
                --sgod-navy: #272b8c;
                --sgod-blue: #3c40c6;
                --sgod-teal: #565de8;
                --sgod-gold: #7a80ff;
                --sgod-ink: #23275d;
                --sgod-muted: #7b7fa7;
                --sgod-surface: #ffffff;
                --sgod-border: rgba(60, 64, 198, 0.12);
                --sgod-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            }

            body {
                background:
                    radial-gradient(circle at top right, rgba(60, 64, 198, 0.12), transparent 28%),
                    linear-gradient(180deg, #f5f9ff 0%, #edf4fb 100%);
            }

            .content-page {
                background: transparent;
            }

            .sgod-dashboard {
                position: relative;
                padding-bottom: 24px;
            }

            .sgod-dashboard::before {
                content: "";
                position: absolute;
                inset: 10px 0 auto;
                height: 260px;
                border-radius: 32px;
                background: linear-gradient(135deg, rgba(60, 64, 198, 0.11), rgba(122, 128, 255, 0.10));
                filter: blur(0);
                z-index: 0;
            }

            .sgod-dashboard > * {
                position: relative;
                z-index: 1;
            }

            .dashboard-hero {
                overflow: hidden;
                margin-top: 20px;
                margin-bottom: 14px;
                border-radius: 28px;
                padding: 32px;
                color: #ffffff;
                background:
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 30%),
                    linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
                box-shadow: var(--sgod-shadow);
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

            .stat-card {
                height: 100%;
                border: none;
                border-radius: 22px;
                background: rgba(255, 255, 255, 0.94);
                box-shadow: var(--sgod-shadow);
                overflow: hidden;
                position: relative;
                transform: translateY(0);
                transition: transform 0.25s ease, box-shadow 0.25s ease;
                animation: fade-up 0.65s ease both;
            }

            .stat-card::before {
                content: "";
                position: absolute;
                inset: 0 0 auto;
                height: 4px;
                background: var(--accent);
            }

            .stat-card:hover {
                transform: translateY(-6px);
                box-shadow: 0 28px 70px rgba(15, 23, 42, 0.12);
            }

            .stat-card-body {
                padding: 24px;
            }

            .stat-card-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 18px;
            }

            .stat-icon {
                width: 54px;
                height: 54px;
                border-radius: 16px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 1.45rem;
            }

            .stat-scope {
                display: inline-flex;
                align-items: center;
                padding: 7px 12px;
                border-radius: 999px;
                background: #f5f8fc;
                color: var(--sgod-muted);
                font-size: 0.78rem;
                font-weight: 600;
                letter-spacing: 0.05em;
                text-transform: uppercase;
            }

            .stat-value {
                margin-bottom: 8px;
                color: var(--sgod-ink);
                font-size: 2.2rem;
                line-height: 1;
                letter-spacing: -0.04em;
                font-weight: 700;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .stat-label {
                margin-bottom: 8px;
                color: var(--sgod-ink);
                font-size: 1rem;
                font-weight: 600;
            }

            .stat-note {
                color: var(--sgod-muted);
                line-height: 1.6;
            }

            .panel-card {
                height: 100%;
                padding: 28px;
                border-radius: 24px;
                border: 1px solid var(--sgod-border);
                background: rgba(255, 255, 255, 0.96);
                box-shadow: var(--sgod-shadow);
                animation: fade-up 0.72s ease both;
            }

            .panel-kicker {
                margin-bottom: 8px;
                color: var(--sgod-blue);
                font-size: 0.82rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .panel-title {
                margin-bottom: 8px;
                color: var(--sgod-ink);
                font-weight: 700;
                letter-spacing: -0.02em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .panel-copy {
                margin-bottom: 22px;
                color: var(--sgod-muted);
                line-height: 1.7;
            }

            .quick-action-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 16px;
            }

            .quick-action {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                padding: 18px 20px;
                border-radius: 20px;
                border: 1px solid rgba(60, 64, 198, 0.12);
                background: linear-gradient(180deg, #fbfbff 0%, #f1f3ff 100%);
                text-decoration: none;
                transition: transform 0.25s ease, box-shadow 0.25s ease;
            }

            .quick-action:hover {
                transform: translateY(-4px);
                box-shadow: 0 18px 44px rgba(60, 64, 198, 0.14);
                text-decoration: none;
            }

            .quick-action-copy {
                min-width: 0;
            }

            .quick-action-label {
                display: block;
                color: var(--sgod-ink);
                font-weight: 700;
                margin-bottom: 4px;
            }

            .quick-action-copy small {
                display: block;
                color: var(--sgod-muted);
                line-height: 1.5;
            }

            .quick-action-icon {
                flex-shrink: 0;
                width: 46px;
                height: 46px;
                border-radius: 16px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: #ffffff;
                color: var(--sgod-blue);
                font-size: 1.3rem;
                box-shadow: 0 12px 28px rgba(60, 64, 198, 0.14);
            }

            .scope-list {
                display: grid;
                gap: 16px;
                margin: 0;
                padding: 0;
                list-style: none;
            }

            .scope-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                padding-bottom: 14px;
                border-bottom: 1px dashed rgba(60, 64, 198, 0.12);
            }

            .scope-item:last-child {
                padding-bottom: 0;
                border-bottom: none;
            }

            .scope-item span {
                color: var(--sgod-muted);
            }

            .scope-item strong {
                color: var(--sgod-ink);
                font-weight: 700;
                text-align: right;
            }

            .insight-card {
                margin-top: 22px;
                padding: 18px 20px;
                border-radius: 18px;
                background: linear-gradient(135deg, rgba(39, 43, 140, 0.95), rgba(60, 64, 198, 0.92));
                color: #ffffff;
            }

            .insight-card span {
                display: inline-block;
                margin-bottom: 8px;
                font-size: 0.78rem;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                color: rgba(255, 255, 255, 0.72);
            }

            .insight-card p {
                margin-bottom: 0;
                color: rgba(255, 255, 255, 0.88);
                line-height: 1.7;
            }

            @keyframes fade-up {
                from {
                    opacity: 0;
                    transform: translateY(18px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @media (max-width: 991.98px) {
                .dashboard-hero,
                .panel-card {
                    padding: 24px;
                }

                .quick-action-grid {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 575.98px) {
                .dashboard-hero {
                    padding: 22px;
                }

                .hero-title {
                    font-size: 1.75rem;
                }

                .panel-card {
                    padding: 22px;
                }

                .scope-item {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .scope-item strong {
                    text-align: left;
                }
            }
        </style>
    </head>

    <body>

        <?php
            $identifier = strtoupper(trim((string) ($this->session->userdata('identifier') ?: $this->session->userdata('secGroup') ?: 'SGOD')));

            $quickLinks = [
                [
                    'href' => base_url() . 'Page/memo',
                    'label' => 'Open Memos',
                    'meta' => 'Post and track SGOD memoranda by group.',
                    'icon' => 'mdi mdi-file-document-edit-outline',
                ],
                [
                    'href' => base_url() . 'Page/sections',
                    'label' => 'Manage Sections',
                    'meta' => 'Create and maintain SGOD section records.',
                    'icon' => 'mdi mdi-sitemap-outline',
                ],
                [
                    'href' => base_url() . 'Page/usersList',
                    'label' => 'Manage Users',
                    'meta' => 'Create and maintain SGOD user accounts.',
                    'icon' => 'mdi mdi-account-cog-outline',
                ],
                [
                    'href' => base_url() . 'Page/public_whereabouts',
                    'label' => 'Public Whereabouts',
                    'meta' => 'View employee whereabouts on big touch screen display.',
                    'icon' => 'mdi mdi-monitor-dashboard',
                ],
            ];

            $snapshotItems = [
                ['label' => 'Department', 'value' => $identifier],
                ['label' => 'Date', 'value' => date('M d, Y')],
            ];
        ?>

        <div id="wrapper">
            <?php include('includes/top-bar.php'); ?>
            <?php include('includes/sidebar.php') ?>

            <div class="content-page">
                <div class="content">
                    <div class="container-fluid sgod-dashboard">
                        <div class="row">
                            <div class="col-12">
                                <div class="dashboard-hero">
                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <span class="hero-eyebrow">
                                                <i class="mdi mdi-shield-account-outline"></i>
                                                School Governance and Operations Division
                                            </span>
                                            <h1 class="hero-title">Welcome to <?= htmlspecialchars($identifier, ENT_QUOTES, 'UTF-8'); ?> Dashboard.</h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-7 mb-4">
                                <div class="panel-card">
                                    <div class="panel-kicker">Quick Actions</div>
                                    <h4 class="panel-title">Jump straight into daily SGOD work</h4>
                                    <p class="panel-copy">
                                        Open the most-used admin areas without digging through the menu. These shortcuts keep the
                                        page practical while the dashboard stays focused on SGOD-only data.
                                    </p>
                                    <div class="quick-action-grid" style="grid-template-columns: repeat(3, minmax(0, 1fr));">
                                        <?php foreach ($quickLinks as $link) { ?>
                                            <a href="<?= $link['href']; ?>" class="quick-action">
                                                <div class="quick-action-copy">
                                                    <span class="quick-action-label"><?= $link['label']; ?></span>
                                                    <small><?= $link['meta']; ?></small>
                                                </div>
                                                <span class="quick-action-icon">
                                                    <i class="<?= $link['icon']; ?>"></i>
                                                </span>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-5 mb-4">
                                <div class="panel-card">
                                    <div class="panel-kicker">SGOD Snapshot</div>
                                    <h4 class="panel-title">What this dashboard is showing</h4>
                                    <p class="panel-copy">
                                        The numbers below reflect the current department grouping so admins can spot scope issues
                                        early and keep SGOD records neatly separated from CID and OSDS.
                                    </p>
                                    <ul class="scope-list">
                                        <?php foreach ($snapshotItems as $item) { ?>
                                            <li class="scope-item">
                                                <span><?= $item['label']; ?></span>
                                                <strong><?= $item['value']; ?></strong>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <div class="insight-card">
                                        <span>Focus</span>
                                        <p>
                                            Memos, and user accounts managed from this workspace should stay aligned to the
                                            <strong><?= htmlspecialchars($identifier, ENT_QUOTES, 'UTF-8'); ?></strong> identifier for clean reporting.
                                        </p>
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
        <script src="<?= base_url(); ?>assets/libs/moment/moment.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/jquery-scrollto/jquery.scrollTo.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/pages/jquery.chat.js"></script>
        <script src="<?= base_url(); ?>assets/js/pages/jquery.todo.js"></script>
        <script src="<?= base_url(); ?>assets/libs/morris-js/morris.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/raphael/raphael.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/jquery-sparkline/jquery.sparkline.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/pages/dashboard.init.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
    </body>
</html>