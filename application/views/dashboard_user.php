<?php
$section = $this->session->userdata('section');
$secGroup = $this->session->userdata('secGroup');
$username = $this->session->userdata('username');

$totalAccomplishments = is_array($data) ? count($data) : 0;
$myAccomplishments = 0;
$manilaNow = new DateTime('now', new DateTimeZone('Asia/Manila'));
$manilaToday = $manilaNow->format('Y-m-d');

if (!empty($data)) {
    foreach ($data as $acc) {
        if (isset($acc->added_by) && $acc->added_by === $username) {
            $myAccomplishments++;
        }
    }
}

$metrics = array(
    array(
        'value' => $totalAccomplishments,
        'label' => 'Total Accomplishments',
        'icon' => 'mdi-file-document-check-outline',
        'accent' => '#3c40c6',
        'icon_bg' => 'rgba(60, 64, 198, 0.12)',
        'icon_color' => '#3c40c6',
        'badge' => 'All'
    ),
    array(
        'value' => $myAccomplishments,
        'label' => 'My Accomplishments',
        'icon' => 'mdi-account-check-outline',
        'accent' => '#565de8',
        'icon_bg' => 'rgba(86, 93, 232, 0.14)',
        'icon_color' => '#565de8',
        'badge' => 'Personal'
    )
);

$statusCalendarStyles = array(
    'In Office' => array(
        'gradient' => 'linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%)',
        'accent' => '#2563eb',
        'tint' => 'rgba(37, 99, 235, 0.14)',
        'shadow' => 'rgba(37, 99, 235, 0.28)'
    ),
    'Out of Office' => array(
        'gradient' => 'linear-gradient(135deg, #0f9f9a 0%, #14b8a6 100%)',
        'accent' => '#0f766e',
        'tint' => 'rgba(15, 118, 110, 0.14)',
        'shadow' => 'rgba(15, 118, 110, 0.24)'
    ),
    'On Official Business' => array(
        'gradient' => 'linear-gradient(135deg, #6d28d9 0%, #8b5cf6 100%)',
        'accent' => '#7c3aed',
        'tint' => 'rgba(124, 58, 237, 0.14)',
        'shadow' => 'rgba(124, 58, 237, 0.24)'
    ),
    'On Leave' => array(
        'gradient' => 'linear-gradient(135deg, #f97316 0%, #fb7185 100%)',
        'accent' => '#ea580c',
        'tint' => 'rgba(234, 88, 12, 0.14)',
        'shadow' => 'rgba(234, 88, 12, 0.24)'
    ),
    'On Field Work' => array(
        'gradient' => 'linear-gradient(135deg, #059669 0%, #22c55e 100%)',
        'accent' => '#047857',
        'tint' => 'rgba(4, 120, 87, 0.14)',
        'shadow' => 'rgba(4, 120, 87, 0.24)'
    ),
    'default' => array(
        'gradient' => 'linear-gradient(135deg, #475569 0%, #64748b 100%)',
        'accent' => '#475569',
        'tint' => 'rgba(71, 85, 105, 0.14)',
        'shadow' => 'rgba(71, 85, 105, 0.22)'
    )
);

$calendarEntriesByDate = array();
if (!empty($whereabouts)) {
    foreach ($whereabouts as $where) {
        if (empty($where->date)) {
            continue;
        }

        $dateKey = (string) $where->date;
        if (!isset($calendarEntriesByDate[$dateKey])) {
            $calendarEntriesByDate[$dateKey] = array();
        }

        $calendarEntriesByDate[$dateKey][] = array(
            'status' => isset($where->status) ? (string) $where->status : '',
            'location' => isset($where->location) ? (string) $where->location : '',
            'activity' => isset($where->activity) ? (string) $where->activity : '',
            'notes' => isset($where->notes) ? (string) $where->notes : ''
        );
    }
}

$statusCalendarStylesJson = json_encode($statusCalendarStyles, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
$calendarEntriesJson = json_encode($calendarEntriesByDate, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
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
                --user-navy: #272b8c;
                --user-blue: #3c40c6;
                --user-teal: #565de8;
                --user-gold: #7a80ff;
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
                height: 240px;
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
                border-radius: 28px;
                overflow: hidden;
                color: #ffffff;
                box-shadow: var(--user-shadow);
                background:
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 32%),
                    linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
            }

            .dashboard-hero-body {
                padding: 32px;
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
                font-size: clamp(2rem, 3vw, 2.7rem);
                line-height: 1.05;
                font-weight: 700;
                letter-spacing: -0.03em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .hero-copy {
                color: rgba(255, 255, 255, 0.80);
                margin-bottom: 0;
            }

            .metric-card {
                height: 100%;
                border: 1px solid rgba(60, 64, 198, 0.08);
                border-radius: 22px;
                background: rgba(255, 255, 255, 0.95);
                padding: 24px;
                box-shadow: var(--user-shadow);
                overflow: hidden;
                position: relative;
            }

            .metric-card::before {
                content: "";
                position: absolute;
                inset: 0;
                opacity: 0.04;
                background: linear-gradient(135deg, var(--user-blue), var(--user-teal));
                z-index: 0;
            }

            .metric-body {
                position: relative;
                z-index: 1;
            }

            .metric-icon {
                width: 52px;
                height: 52px;
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 26px;
                margin-bottom: 16px;
            }

            .metric-value {
                font-size: 2.5rem;
                font-weight: 700;
                color: var(--user-ink);
                line-height: 1;
                margin-bottom: 8px;
            }

            .metric-label {
                color: var(--user-muted);
                font-weight: 600;
                font-size: 0.95rem;
                margin-bottom: 6px;
            }

            .metric-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 6px 12px;
                border-radius: 999px;
                background: rgba(60, 64, 198, 0.08);
                color: var(--user-blue);
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.06em;
            }

            .panel-card {
                border: 1px solid rgba(60, 64, 198, 0.08);
                border-radius: 22px;
                background: rgba(255, 255, 255, 0.95);
                padding: 28px;
                box-shadow: var(--user-shadow);
            }

            .panel-kicker {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 14px;
                border-radius: 999px;
                background: rgba(60, 64, 198, 0.08);
                color: var(--user-blue);
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.06em;
                margin-bottom: 12px;
            }

            .panel-title {
                color: var(--user-ink);
                font-size: 1.5rem;
                font-weight: 700;
                margin-bottom: 8px;
            }

            .panel-copy {
                color: var(--user-muted);
                margin-bottom: 24px;
            }

            .quick-action {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 18px 20px;
                border: 1px solid rgba(60, 64, 198, 0.08);
                border-radius: 16px;
                background: #ffffff;
                transition: all 0.25s ease;
                text-decoration: none;
                margin-bottom: 12px;
            }

            .quick-action:hover {
                border-color: var(--user-blue);
                box-shadow: 0 8px 24px rgba(60, 64, 198, 0.12);
                transform: translateY(-2px);
            }

            .quick-action-label {
                color: var(--user-ink);
                font-weight: 700;
                font-size: 1rem;
            }

            .quick-action small {
                color: var(--user-muted);
                font-size: 0.85rem;
            }

            .quick-action-icon {
                width: 44px;
                height: 44px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 22px;
                background: rgba(60, 64, 198, 0.08);
                color: var(--user-blue);
            }

            .mini-calendar-day {
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 40px;
                padding: 6px;
                border-radius: 10px;
                border: 1px solid transparent;
                background: #f8f9ff;
                color: var(--user-ink);
                cursor: pointer;
                font-size: 0.8rem;
                font-weight: 600;
                user-select: none;
                transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease, background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
            }

            .activity-preview {
                margin-top: 18px;
                padding: 18px;
                border-radius: 18px;
                border: 1px solid rgba(60, 64, 198, 0.08);
                background: linear-gradient(180deg, #fbfcff 0%, #f4f7ff 100%);
            }

            .activity-preview-label {
                font-size: 0.72rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                color: var(--user-blue);
                margin-bottom: 10px;
            }

            .activity-preview-empty {
                margin: 0;
                color: var(--user-muted);
                font-size: 0.9rem;
                line-height: 1.5;
            }

            .activity-preview-date {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                margin-bottom: 12px;
            }

            .activity-preview-date strong {
                color: var(--user-ink);
                font-size: 1rem;
            }

            .activity-preview-count {
                display: inline-flex;
                align-items: center;
                padding: 5px 10px;
                border-radius: 999px;
                background: rgba(60, 64, 198, 0.08);
                color: var(--user-blue);
                font-size: 0.75rem;
                font-weight: 700;
            }

            .activity-preview-entry {
                padding: 14px;
                border-radius: 14px;
                border: 1px solid rgba(60, 64, 198, 0.08);
                background: #ffffff;
            }

            .activity-preview-entry + .activity-preview-entry {
                margin-top: 10px;
            }

            .activity-preview-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
                margin-bottom: 10px;
            }

            .activity-preview-status {
                display: inline-flex;
                align-items: center;
                padding: 5px 10px;
                border-radius: 999px;
                font-size: 0.75rem;
                font-weight: 700;
                line-height: 1;
            }

            .activity-preview-location {
                color: var(--user-ink);
                font-size: 0.9rem;
                font-weight: 700;
                margin-bottom: 6px;
            }

            .activity-preview-activity {
                color: #42526b;
                font-size: 0.9rem;
                line-height: 1.5;
            }

            .activity-preview-notes {
                margin-top: 8px;
                color: var(--user-muted);
                font-size: 0.82rem;
                line-height: 1.45;
            }

            @media (max-width: 767.98px) {
                .dashboard-hero-body {
                    padding: 22px;
                }

                .hero-title {
                    font-size: 1.9rem;
                }

                .activity-preview-date {
                    align-items: flex-start;
                    flex-direction: column;
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
                        <div class="row">
                            <div class="col-12">
                                <div class="dashboard-hero">
                                    <div class="dashboard-hero-body">
                                        <span class="hero-eyebrow">
                                            <i class="mdi mdi-account-circle-outline"></i>
                                            Section User Dashboard
                                        </span>
                                        <h1 class="hero-title">Welcome, <?= htmlspecialchars($this->session->userdata('fName'), ENT_QUOTES, 'UTF-8'); ?>!</h1>
                                        <p class="hero-copy">
                                            Manage your accomplishments for <?= htmlspecialchars($section, ENT_QUOTES, 'UTF-8'); ?> under <?= htmlspecialchars($secGroup, ENT_QUOTES, 'UTF-8'); ?>.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <?php foreach ($metrics as $metric) { ?>
                                <div class="col-xl-6 col-md-6 mb-4">
                                    <div class="metric-card">
                                        <div class="metric-body">
                                            <div class="metric-icon" style="background: <?= $metric['icon_bg'] ?>; color: <?= $metric['icon_color'] ?>;">
                                                <i class="mdi <?= $metric['icon'] ?>"></i>
                                            </div>
                                            <div class="metric-value"><?= $metric['value'] ?></div>
                                            <div class="metric-label"><?= $metric['label'] ?></div>
                                            <span class="metric-badge"><?= $metric['badge'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="row mt-4">
                            <div class="col-xl-12 mb-4">
                                <div class="panel-card">
                                    <div class="panel-kicker">Whereabouts</div>
                                    <h4 class="panel-title">Update Your Whereabouts</h4>
                                    <p class="panel-copy">
                                        Let others know where you are and what you're working on today.
                                    </p>

                                    <div class="mini-calendar-wrapper" style="margin-bottom: 16px;">
                                        <div class="mini-calendar-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                            <button class="mini-calendar-nav" onclick="changeMiniMonth(-1)" style="background: none; border: none; cursor: pointer; color: var(--user-blue); padding: 4px 8px; border-radius: 6px;">
                                                <i class="mdi mdi-chevron-left"></i>
                                            </button>
                                            <h6 id="miniCalendarMonthYear" style="color: var(--user-ink); font-weight: 700; margin: 0;"></h6>
                                            <button class="mini-calendar-nav" onclick="changeMiniMonth(1)" style="background: none; border: none; cursor: pointer; color: var(--user-blue); padding: 4px 8px; border-radius: 6px;">
                                                <i class="mdi mdi-chevron-right"></i>
                                            </button>
                                        </div>
                                        <div class="mini-calendar-grid" style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; text-align: center;">
                                            <div class="mini-day-header" style="font-size: 0.7rem; color: var(--user-muted); font-weight: 600; padding: 4px;">S</div>
                                            <div class="mini-day-header" style="font-size: 0.7rem; color: var(--user-muted); font-weight: 600; padding: 4px;">M</div>
                                            <div class="mini-day-header" style="font-size: 0.7rem; color: var(--user-muted); font-weight: 600; padding: 4px;">T</div>
                                            <div class="mini-day-header" style="font-size: 0.7rem; color: var(--user-muted); font-weight: 600; padding: 4px;">W</div>
                                            <div class="mini-day-header" style="font-size: 0.7rem; color: var(--user-muted); font-weight: 600; padding: 4px;">T</div>
                                            <div class="mini-day-header" style="font-size: 0.7rem; color: var(--user-muted); font-weight: 600; padding: 4px;">F</div>
                                            <div class="mini-day-header" style="font-size: 0.7rem; color: var(--user-muted); font-weight: 600; padding: 4px;">S</div>
                                        </div>
                                        <div class="mini-calendar-days" id="miniCalendarDays" style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px;"></div>
                                    </div>

                                    <div class="activity-preview">
                                        <div class="activity-preview-label">Saved Whereabouts</div>
                                        <div id="calendarActivityPreviewBody">
                                            <p class="activity-preview-empty">Hover a highlighted date to preview your saved whereabouts details.</p>
                                        </div>
                                    </div>

                                    <a href="<?= base_url(); ?>Page/whereabouts" class="btn btn-gradient-primary" style="width: 100%; margin-top: 20px;">
                                        <i class="mdi mdi-map-marker-radius-outline"></i> Post Whereabouts
                                    </a>

                                    <?php if (!empty($whereabouts)): ?>
                                        <div style="margin-top: 24px;">
                                            <h6 style="color: var(--user-ink); font-weight: 600; margin-bottom: 12px;">Recent Posts</h6>
                                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 12px;">
                                                <?php foreach (array_slice($whereabouts, 0, 6) as $where): ?>
                                                    <div style="padding: 12px; border: 1px solid rgba(60, 64, 198, 0.08); border-radius: 12px; background: #ffffff;">
                                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                                            <span style="font-weight: 600; color: var(--user-ink); font-size: 0.9rem;"><?= htmlspecialchars($where->date, ENT_QUOTES, 'UTF-8'); ?></span>
                                                            <span style="font-size: 0.75rem; padding: 4px 8px; border-radius: 999px; background: rgba(60, 64, 198, 0.08); color: var(--user-blue);"><?= htmlspecialchars($where->status, ENT_QUOTES, 'UTF-8'); ?></span>
                                                        </div>
                                                        <div style="color: var(--user-muted); font-size: 0.85rem;"><?= htmlspecialchars($where->location, ENT_QUOTES, 'UTF-8'); ?></div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include('includes/footer.php'); ?>
            </div>
        </div>

        <!-- Whereabouts Modal -->
        <div id="whereaboutsModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 9999;">
            <div style="background: #ffffff; border-radius: 22px; padding: 28px; max-width: 500px; width: 90%; box-shadow: 0 24px 60px rgba(15, 23, 42, 0.2);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="color: var(--user-ink); font-weight: 700; margin: 0;">Post Whereabouts</h3>
                    <button onclick="closeWhereaboutsModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--user-muted);">&times;</button>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="color: var(--user-ink); font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; display: block;">Selected Dates</label>
                    <ul id="selectedDatesList" style="list-style: none; padding: 0; margin: 0; color: var(--user-muted); font-size: 0.9rem;"></ul>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="color: var(--user-ink); font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; display: block;">Status</label>
                    <select id="modalStatus" style="width: 100%; padding: 12px 16px; border: 1px solid rgba(60, 64, 198, 0.12); border-radius: 12px; font-size: 0.95rem;">
                        <option value="In Office">In Office</option>
                        <option value="Out of Office">Out of Office</option>
                        <option value="On Official Business">On Official Business</option>
                        <option value="On Leave">On Leave</option>
                        <option value="On Field Work">On Field Work</option>
                    </select>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="color: var(--user-ink); font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; display: block;">Location <span style="color: #ef4444;">*</span></label>
                    <input type="text" id="modalLocation" placeholder="e.g., Main Office, School X" style="width: 100%; padding: 12px 16px; border: 1px solid rgba(60, 64, 198, 0.12); border-radius: 12px; font-size: 0.95rem;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="color: var(--user-ink); font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; display: block;">Activity <span style="color: #ef4444;">*</span></label>
                    <textarea id="modalActivity" rows="3" placeholder="What are you working on?" style="width: 100%; padding: 12px 16px; border: 1px solid rgba(60, 64, 198, 0.12); border-radius: 12px; font-size: 0.95rem; resize: vertical;"></textarea>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="color: var(--user-ink); font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; display: block;">Notes</label>
                    <textarea id="modalNotes" rows="2" placeholder="Any additional details..." style="width: 100%; padding: 12px 16px; border: 1px solid rgba(60, 64, 198, 0.12); border-radius: 12px; font-size: 0.95rem; resize: vertical;"></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <button onclick="closeWhereaboutsModal()" style="padding: 12px 28px; border: 1px solid rgba(60, 64, 198, 0.08); border-radius: 12px; background: #eef3f8; color: var(--user-ink); font-weight: 600; cursor: pointer; transition: all 0.25s ease;">Cancel</button>
                    <button onclick="submitWhereabouts()" style="padding: 12px 28px; border: none; border-radius: 12px; background: linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%); color: #ffffff; font-weight: 600; cursor: pointer; transition: all 0.25s ease;">Post Whereabouts</button>
                </div>
            </div>
        </div>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>

        <script>
            const manilaToday = <?= $manilaToday ? json_encode($manilaToday, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) : "'0000-00-00'"; ?>;
            const statusStyles = <?= $statusCalendarStylesJson ?: '{}' ?>;
            const activityEntriesByDate = <?= $calendarEntriesJson ?: '{}' ?>;
            const todayGradient = 'linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%)';
            const selectedGradient = 'linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%)';
            const defaultStatusStyle = statusStyles.default || {
                gradient: 'linear-gradient(135deg, #475569 0%, #64748b 100%)',
                accent: '#475569',
                tint: 'rgba(71, 85, 105, 0.14)',
                shadow: 'rgba(71, 85, 105, 0.22)'
            };
            const previewFallbackMessage = 'Hover a highlighted date to preview your saved whereabouts details.';
            const datesWithActivities = new Set(Object.keys(activityEntriesByDate));

            let miniCurrentDate = parseDateString(manilaToday) || new Date();
            let selectedDates = new Set();
            let isDragging = false;
            let dragStartDay = null;

            function parseDateString(dateStr) {
                const parts = String(dateStr || '').split('-');
                if (parts.length !== 3) {
                    return null;
                }

                const year = parseInt(parts[0], 10);
                const month = parseInt(parts[1], 10);
                const day = parseInt(parts[2], 10);

                if (!year || !month || !day) {
                    return null;
                }

                return new Date(year, month - 1, day);
            }

            function escapeHtml(value) {
                return String(value || '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function getEntriesForDate(dateStr) {
                return Array.isArray(activityEntriesByDate[dateStr]) ? activityEntriesByDate[dateStr] : [];
            }

            function getPrimaryEntry(dateStr) {
                const entries = getEntriesForDate(dateStr);
                return entries.length ? entries[0] : null;
            }

            function getStatusStyle(status) {
                return statusStyles[status] || defaultStatusStyle;
            }

            function isTodayDate(dateStr) {
                return dateStr === manilaToday;
            }

            function formatDateLabel(dateStr, options) {
                const date = parseDateString(dateStr);
                return date ? date.toLocaleDateString('en-US', options) : dateStr;
            }

            function buildDayTitle(dateStr) {
                const entries = getEntriesForDate(dateStr);
                if (!entries.length) {
                    return '';
                }

                return entries.map(entry => {
                    const parts = [];

                    if (entry.status) {
                        parts.push(entry.status);
                    }
                    if (entry.location) {
                        parts.push(entry.location);
                    }
                    if (entry.activity) {
                        parts.push(entry.activity);
                    }
                    if (entry.notes) {
                        parts.push(entry.notes);
                    }

                    return parts.join(' | ');
                }).join('\n');
            }

            function resetActivityPreview() {
                const previewBody = document.getElementById('calendarActivityPreviewBody');
                if (!previewBody) {
                    return;
                }

                previewBody.innerHTML = `<p class="activity-preview-empty">${escapeHtml(previewFallbackMessage)}</p>`;
            }

            function renderActivityPreview(dateStr) {
                const previewBody = document.getElementById('calendarActivityPreviewBody');
                const entries = getEntriesForDate(dateStr);
                if (!previewBody || !entries.length) {
                    resetActivityPreview();
                    return;
                }

                const previewHeader = `
                    <div class="activity-preview-date">
                        <strong>${escapeHtml(formatDateLabel(dateStr, { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' }))}</strong>
                        <span class="activity-preview-count">${entries.length} ${entries.length === 1 ? 'entry' : 'entries'}</span>
                    </div>
                `;

                const previewCards = entries.map(entry => {
                    const statusStyle = getStatusStyle(entry.status);
                    const locationText = entry.location ? `Location: ${entry.location}` : 'Location not specified';
                    const activityText = entry.activity || 'No activity details provided.';
                    const notesText = entry.notes ? `<div class="activity-preview-notes">Notes: ${escapeHtml(entry.notes)}</div>` : '';

                    return `
                        <div class="activity-preview-entry">
                            <div class="activity-preview-head">
                                <span class="activity-preview-status" style="background: ${statusStyle.tint}; color: ${statusStyle.accent};">${escapeHtml(entry.status || 'Saved whereabouts')}</span>
                            </div>
                            <div class="activity-preview-location">${escapeHtml(locationText)}</div>
                            <div class="activity-preview-activity">${escapeHtml(activityText)}</div>
                            ${notesText}
                        </div>
                    `;
                }).join('');

                previewBody.innerHTML = previewHeader + previewCards;
            }

            function renderMiniCalendar() {
                const year = miniCurrentDate.getFullYear();
                const month = miniCurrentDate.getMonth();

                const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                document.getElementById('miniCalendarMonthYear').textContent = `${monthNames[month]} ${year}`;

                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();

                const miniCalendarDays = document.getElementById('miniCalendarDays');
                miniCalendarDays.innerHTML = '';

                for (let i = 0; i < firstDay; i++) {
                    const emptyDay = document.createElement('div');
                    emptyDay.style.padding = '6px';
                    miniCalendarDays.appendChild(emptyDay);
                }

                for (let day = 1; day <= daysInMonth; day++) {
                    const dayElement = document.createElement('div');
                    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    dayElement.textContent = day;
                    dayElement.className = 'mini-calendar-day';

                    dayElement.addEventListener('mousedown', (e) => {
                        e.preventDefault();
                        isDragging = true;
                        dragStartDay = day;
                        
                        if (selectedDates.has(dateStr)) {
                            selectedDates.delete(dateStr);
                        } else {
                            selectedDates.add(dateStr);
                        }
                        
                        updateDayStyle(dayElement, dateStr);
                    });

                    dayElement.addEventListener('mouseenter', () => {
                        if (isDragging && dragStartDay !== null) {
                            const start = Math.min(dragStartDay, day);
                            const end = Math.max(dragStartDay, day);
                            
                            for (let d = start; d <= end; d++) {
                                const ds = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                                selectedDates.add(ds);
                            }
                            renderMiniCalendar();
                        } else if (!isDragging) {
                            const hasActivity = datesWithActivities.has(dateStr);
                            const isToday = isTodayDate(dateStr);

                            updateDayStyle(dayElement, dateStr);
                            dayElement.style.transform = 'translateY(-1px)';

                            if (hasActivity) {
                                const statusStyle = getStatusStyle(getPrimaryEntry(dateStr).status);
                                dayElement.style.filter = 'brightness(1.08)';
                                dayElement.style.boxShadow = `0 16px 30px ${statusStyle.shadow}`;
                                dayElement.style.borderColor = 'rgba(255, 255, 255, 0.88)';
                                renderActivityPreview(dateStr);
                            } else {
                                dayElement.style.background = 'rgba(60, 64, 198, 0.1)';
                                dayElement.style.color = 'var(--user-blue)';
                                if (selectedDates.has(dateStr) || isToday) {
                                    updateDayStyle(dayElement, dateStr);
                                    dayElement.style.transform = 'translateY(-1px)';
                                }
                                resetActivityPreview();
                            }
                        }
                    });

                    dayElement.addEventListener('mouseleave', () => {
                        if (!isDragging) {
                            updateDayStyle(dayElement, dateStr);
                            resetActivityPreview();
                        }
                    });

                    dayElement.addEventListener('click', () => {
                        if (selectedDates.size >= 1) {
                            showWhereaboutsModal();
                        }
                    });

                    updateDayStyle(dayElement, dateStr);
                    miniCalendarDays.appendChild(dayElement);
                }
            }

            function updateDayStyle(dayElement, dateStr) {
                const primaryEntry = getPrimaryEntry(dateStr);
                const hasActivity = datesWithActivities.has(dateStr);
                const isToday = isTodayDate(dateStr);
                const statusStyle = primaryEntry ? getStatusStyle(primaryEntry.status) : defaultStatusStyle;

                dayElement.style.transform = 'translateY(0)';
                dayElement.style.filter = 'none';
                dayElement.style.borderColor = 'transparent';
                dayElement.style.boxShadow = 'none';
                dayElement.title = hasActivity ? buildDayTitle(dateStr) : '';

                if (selectedDates.has(dateStr)) {
                    dayElement.style.background = selectedGradient;
                    dayElement.style.color = '#ffffff';
                    dayElement.style.boxShadow = '0 14px 28px rgba(238, 90, 36, 0.24)';
                } else if (hasActivity) {
                    dayElement.style.background = statusStyle.gradient;
                    dayElement.style.color = '#ffffff';
                    dayElement.style.boxShadow = `0 12px 22px ${statusStyle.shadow}`;
                } else if (isToday) {
                    dayElement.style.background = todayGradient;
                    dayElement.style.color = '#ffffff';
                } else {
                    dayElement.style.background = '#f8f9ff';
                    dayElement.style.color = 'var(--user-ink)';
                }

                if (isToday && hasActivity && !selectedDates.has(dateStr)) {
                    dayElement.style.borderColor = 'rgba(255, 255, 255, 0.88)';
                } else if (isToday) {
                    dayElement.style.borderColor = 'rgba(39, 43, 140, 0.24)';
                }
            }

            function changeMiniMonth(delta) {
                miniCurrentDate.setMonth(miniCurrentDate.getMonth() + delta);
                selectedDates.clear();
                resetActivityPreview();
                renderMiniCalendar();
            }

            function showWhereaboutsModal() {
                const modal = document.getElementById('whereaboutsModal');
                const datesList = document.getElementById('selectedDatesList');
                datesList.innerHTML = '';
                
                const sortedDates = Array.from(selectedDates).sort();
                sortedDates.forEach(date => {
                    const li = document.createElement('li');
                    li.textContent = formatDateLabel(date, { weekday: 'short', month: 'short', day: 'numeric' });
                    li.style.padding = '4px 0';
                    li.style.borderBottom = '1px solid rgba(60, 64, 198, 0.08)';
                    datesList.appendChild(li);
                });
                
                modal.style.display = 'flex';
            }

            function closeWhereaboutsModal() {
                document.getElementById('whereaboutsModal').style.display = 'none';
            }

            function submitWhereabouts() {
                const status = document.getElementById('modalStatus').value;
                const locationValue = document.getElementById('modalLocation').value;
                const activity = document.getElementById('modalActivity').value;
                const notes = document.getElementById('modalNotes').value;
                
                if (!locationValue || !activity) {
                    alert('Please fill in location and activity');
                    return;
                }
                
                const dates = Array.from(selectedDates);
                let successCount = 0;
                
                dates.forEach(date => {
                    const formData = new FormData();
                    formData.append('date', date);
                    formData.append('status', status);
                    formData.append('location', locationValue);
                    formData.append('activity', activity);
                    formData.append('notes', notes);
                    
                    fetch('<?= base_url(); ?>Page/whereabouts_ajax', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            successCount++;
                            if (successCount === dates.length) {
                                alert('Whereabouts posted successfully for ' + dates.length + ' date(s)');
                                closeWhereaboutsModal();
                                selectedDates.clear();
                                renderMiniCalendar();
                                location.reload();
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            }

            document.addEventListener('mouseup', () => {
                isDragging = false;
                dragStartDay = null;
            });

            document.addEventListener('DOMContentLoaded', function() {
                resetActivityPreview();
                renderMiniCalendar();
            });
        </script>

    </body>
</html>
