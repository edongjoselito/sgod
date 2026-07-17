<?php
$section = $this->session->userdata('section');
$secGroup = $this->session->userdata('secGroup');
$username = $this->session->userdata('username');

$manilaNow = new DateTime('now', new DateTimeZone('Asia/Manila'));
$manilaToday = $manilaNow->format('Y-m-d');

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
$shouldPromptAvatarUpdate = !empty($shouldPromptAvatarUpdate);
$dashboardUserName = trim(preg_replace('/\s+/', ' ', implode(' ', array_filter(array(
    $this->session->userdata('fName'),
    $this->session->userdata('mName'),
    $this->session->userdata('lName')
)))));
$dashboardUserNameParts = $dashboardUserName !== '' ? preg_split('/\s+/', $dashboardUserName) : array();
$dashboardUserInitials = '';
if (!empty($dashboardUserNameParts)) {
    $dashboardUserInitials = strtoupper(substr($dashboardUserNameParts[0], 0, 1));
    if (count($dashboardUserNameParts) > 1) {
        $dashboardUserInitials .= strtoupper(substr($dashboardUserNameParts[count($dashboardUserNameParts) - 1], 0, 1));
    }
}
$dashboardUserInitials = $dashboardUserInitials !== '' ? $dashboardUserInitials : 'SU';
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
        <link href="<?= base_url(); ?>assets/libs/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
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

            .calendar-event-hint {
                margin: 6px 0 0;
                color: var(--user-muted);
                font-size: 0.88rem;
                line-height: 1.5;
            }

            .calendar-event-stack {
                display: grid;
                gap: 14px;
            }

            .calendar-event-card {
                padding: 18px;
                border-radius: 18px;
                border: 1px solid rgba(60, 64, 198, 0.1);
                background: linear-gradient(180deg, #fbfcff 0%, #f5f8ff 100%);
            }

            .calendar-event-card-head {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 14px;
                margin-bottom: 16px;
            }

            .calendar-event-card-title {
                margin: 0;
                color: var(--user-ink);
                font-size: 0.98rem;
                font-weight: 700;
            }

            .calendar-event-card-copy {
                margin: 4px 0 0;
                color: var(--user-muted);
                font-size: 0.82rem;
                line-height: 1.45;
            }

            .calendar-event-remove,
            .calendar-event-add {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                border-radius: 12px;
                font-weight: 700;
                font-size: 0.88rem;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .calendar-event-remove {
                padding: 9px 12px;
                border: 1px solid rgba(239, 68, 68, 0.18);
                background: rgba(239, 68, 68, 0.08);
                color: #dc2626;
            }

            .calendar-event-remove:hover:not(:disabled),
            .calendar-event-add:hover {
                transform: translateY(-1px);
            }

            .calendar-event-remove:disabled {
                opacity: 0.45;
                cursor: not-allowed;
                transform: none;
            }

            .calendar-event-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 14px;
            }

            .calendar-event-field--full {
                grid-column: 1 / -1;
            }

            .calendar-event-label {
                display: block;
                margin-bottom: 8px;
                color: var(--user-ink);
                font-size: 0.88rem;
                font-weight: 700;
            }

            .calendar-event-input {
                width: 100%;
                padding: 12px 16px;
                border: 1px solid rgba(60, 64, 198, 0.12);
                border-radius: 12px;
                font-size: 0.95rem;
                background: #ffffff;
                color: var(--user-ink);
            }

            .calendar-event-input:focus {
                outline: none;
                border-color: rgba(60, 64, 198, 0.35);
                box-shadow: 0 0 0 3px rgba(60, 64, 198, 0.08);
            }

            .calendar-event-textarea {
                min-height: 92px;
                resize: vertical;
            }

            .calendar-event-toolbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                flex-wrap: wrap;
                margin-top: 16px;
            }

            .calendar-event-add {
                padding: 11px 16px;
                border: 1px solid rgba(60, 64, 198, 0.14);
                background: rgba(60, 64, 198, 0.08);
                color: var(--user-blue);
            }

            .calendar-event-summary {
                color: var(--user-muted);
                font-size: 0.84rem;
                font-weight: 600;
            }

            .calendar-app-shell {
                display: grid;
                grid-template-columns: minmax(0, 1.7fr) minmax(300px, 0.95fr);
                gap: 24px;
                align-items: start;
            }

            .calendar-main {
                border: 1px solid rgba(60, 64, 198, 0.08);
                border-radius: 22px;
                background: linear-gradient(180deg, #ffffff 0%, #fbfcff 100%);
                padding: 20px;
            }

            .calendar-main-toolbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 14px;
                flex-wrap: wrap;
                margin-bottom: 18px;
            }

            .calendar-main-copy {
                color: var(--user-muted);
                font-size: 0.88rem;
                line-height: 1.55;
                margin: 0;
            }

            .calendar-legend {
                display: flex;
                align-items: center;
                gap: 10px;
                flex-wrap: wrap;
            }

            .calendar-legend-chip {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 12px;
                border-radius: 999px;
                background: rgba(60, 64, 198, 0.06);
                color: var(--user-ink);
                font-size: 0.76rem;
                font-weight: 700;
                letter-spacing: 0.02em;
            }

            .calendar-legend-dot {
                width: 10px;
                height: 10px;
                border-radius: 999px;
                flex-shrink: 0;
            }

            .whereabouts-calendar {
                min-height: 0;
            }

            .whereabouts-calendar .fc-toolbar {
                margin-bottom: 18px;
            }

            .whereabouts-calendar .fc-toolbar h2 {
                color: var(--user-ink);
                font-size: 1.35rem;
                font-weight: 800;
                letter-spacing: -0.02em;
            }

            .whereabouts-calendar .fc-button {
                border: none;
                border-radius: 12px;
                background: rgba(60, 64, 198, 0.08);
                color: var(--user-blue);
                box-shadow: none;
                text-transform: none;
                font-weight: 700;
                padding: 0.5rem 0.9rem;
                height: auto;
            }

            .whereabouts-calendar .fc-button:hover,
            .whereabouts-calendar .fc-button:focus,
            .whereabouts-calendar .fc-button.fc-state-hover {
                background: rgba(60, 64, 198, 0.14);
                color: var(--user-blue);
                box-shadow: none;
            }

            .whereabouts-calendar .fc-button.fc-state-active,
            .whereabouts-calendar .fc-button.fc-state-down {
                background: linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
                color: #ffffff;
                box-shadow: 0 14px 28px rgba(60, 64, 198, 0.16);
            }

            .whereabouts-calendar .fc-button .fc-icon {
                color: inherit;
            }

            .whereabouts-calendar .fc-head-container,
            .whereabouts-calendar .fc-row,
            .whereabouts-calendar td,
            .whereabouts-calendar th {
                border-color: rgba(60, 64, 198, 0.08);
            }

            .whereabouts-calendar .fc-head th {
                padding: 14px 6px;
                color: var(--user-muted);
                font-size: 0.78rem;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                background: #f9fbff;
            }

            .whereabouts-calendar .fc-day-top {
                padding: 8px 10px 0;
            }

            .whereabouts-calendar .fc-day-number {
                color: var(--user-ink);
                font-weight: 700;
                font-size: 0.9rem;
            }

            .whereabouts-calendar .fc-day.fc-day-today {
                background: rgba(60, 64, 198, 0.06);
            }

            .whereabouts-calendar .fc-day.fc-day-selected {
                background: rgba(39, 43, 140, 0.08);
                box-shadow: inset 0 0 0 2px rgba(39, 43, 140, 0.16);
            }

            .whereabouts-calendar .fc-day-grid-event {
                margin: 2px 6px 0;
                border: none;
                border-radius: 10px;
                padding: 3px 8px;
                box-shadow: 0 10px 18px rgba(15, 23, 42, 0.08);
            }

            .whereabouts-calendar .fc-event,
            .whereabouts-calendar .fc-event:hover {
                color: #ffffff;
                text-decoration: none;
            }

            .whereabouts-calendar .fc-title {
                font-size: 0.78rem;
                font-weight: 700;
                line-height: 1.35;
                white-space: normal;
            }

            .whereabouts-calendar .fc-more {
                color: var(--user-blue);
                font-size: 0.76rem;
                font-weight: 700;
                margin-left: 7px;
            }

            .whereabouts-calendar .fc-list-table td,
            .whereabouts-calendar .fc-list-heading td {
                border-color: rgba(60, 64, 198, 0.08);
            }

            .whereabouts-calendar .fc-list-heading-main,
            .whereabouts-calendar .fc-list-heading-alt {
                color: var(--user-ink);
                font-weight: 700;
            }

            .calendar-agenda-card {
                border: 1px solid rgba(60, 64, 198, 0.08);
                border-radius: 22px;
                background: linear-gradient(180deg, #fbfcff 0%, #f4f7ff 100%);
                padding: 22px;
                position: sticky;
                top: 24px;
            }

            .calendar-agenda-head {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 14px;
                margin-bottom: 16px;
            }

            .calendar-agenda-title {
                color: var(--user-ink);
                font-size: 1.1rem;
                font-weight: 800;
                line-height: 1.3;
                margin: 6px 0 4px;
            }

            .calendar-agenda-copy {
                color: var(--user-muted);
                font-size: 0.88rem;
                line-height: 1.5;
                margin: 0;
            }

            .calendar-agenda-add {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 10px 14px;
                border: 1px solid rgba(60, 64, 198, 0.12);
                border-radius: 14px;
                background: rgba(60, 64, 198, 0.08);
                color: var(--user-blue);
                font-size: 0.84rem;
                font-weight: 800;
                white-space: nowrap;
                cursor: pointer;
                transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            }

            .calendar-agenda-add:hover,
            .calendar-agenda-add:focus {
                transform: translateY(-1px);
                background: rgba(60, 64, 198, 0.14);
                box-shadow: 0 10px 20px rgba(60, 64, 198, 0.12);
                outline: none;
            }

            .calendar-agenda-empty {
                margin: 0;
                color: var(--user-muted);
                font-size: 0.9rem;
                line-height: 1.6;
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

                .calendar-event-card-head,
                .calendar-event-toolbar {
                    align-items: stretch;
                    flex-direction: column;
                }

                .calendar-event-grid {
                    grid-template-columns: minmax(0, 1fr);
                }

                .calendar-app-shell {
                    grid-template-columns: minmax(0, 1fr);
                }

                .calendar-agenda-card {
                    position: static;
                }

                .calendar-main-toolbar,
                .calendar-agenda-head {
                    align-items: stretch;
                    flex-direction: column;
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
                    <div class="container-fluid user-shell dashboard-shell">
                        <section class="dashboard-hero">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <div class="hero-content">
                                        <span class="hero-eyebrow">
                                            <i class="mdi mdi-account-circle-outline"></i>
                                            Section User Dashboard
                                        </span>
                                        <h1 class="hero-title">Welcome, <?= htmlspecialchars($this->session->userdata('fName'), ENT_QUOTES, 'UTF-8'); ?>!</h1>
                                        <p class="hero-subtitle">
                                            Manage your accomplishments for <?= htmlspecialchars($section, ENT_QUOTES, 'UTF-8'); ?> under <?= htmlspecialchars($secGroup, ENT_QUOTES, 'UTF-8'); ?>.
                                        </p>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="hero-profile">
                                        <span class="hero-avatar"><?= htmlspecialchars($dashboardUserInitials, ENT_QUOTES, 'UTF-8'); ?></span>
                                        <div class="hero-profile-copy">
                                            <strong><?= htmlspecialchars($dashboardUserName !== '' ? $dashboardUserName : 'Section User', ENT_QUOTES, 'UTF-8'); ?></strong>
                                            <span><?= htmlspecialchars($section, ENT_QUOTES, 'UTF-8'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <div class="row dashboard-main-row">
                            <div class="col-12">
                                <section class="dashboard-card">
                                    <div class="card-heading">
                                        <div class="heading-group">
                                            <span class="heading-icon"><i class="mdi mdi-calendar-month-outline"></i></span>
                                            <div>
                                                <h2 class="card-title">My Calendar</h2>
                                                <p class="card-caption">Update your schedule and whereabouts</p>
                                            </div>
                                        </div>
                                        <span class="today-badge">
                                            <i class="mdi mdi-calendar-today-outline"></i>
                                            <?= htmlspecialchars($manilaNow->format('l, F j, Y'), ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </div>

                                    <div class="dashboard-card-body">
                                    <div class="calendar-app-shell">
                                        <div class="calendar-main">
                                            <div class="calendar-main-toolbar">
                                                <div class="calendar-legend">
                                                    <span class="calendar-legend-chip"><span class="calendar-legend-dot" style="background:#2563eb;"></span>In Office</span>
                                                    <span class="calendar-legend-chip"><span class="calendar-legend-dot" style="background:#0f766e;"></span>Out of Office</span>
                                                    <span class="calendar-legend-chip"><span class="calendar-legend-dot" style="background:#7c3aed;"></span>Official Business</span>
                                                    <span class="calendar-legend-chip"><span class="calendar-legend-dot" style="background:#ea580c;"></span>On Leave</span>
                                                    <span class="calendar-legend-chip"><span class="calendar-legend-dot" style="background:#047857;"></span>Field Work</span>
                                                </div>
                                            </div>
                                            <div id="whereaboutsCalendar" class="whereabouts-calendar"></div>
                                        </div>

                                        <div class="calendar-agenda-card">
                                            <div class="calendar-agenda-head">
                                                <div>
                                                    <div class="activity-preview-label">Selected Day</div>
                                                    <h5 class="calendar-agenda-title" id="selectedDateTitle"></h5>
                                                    <p class="calendar-agenda-copy" id="selectedDateMeta"></p>
                                                </div>
                                                <button type="button" class="calendar-agenda-add" id="openSelectedDateComposer">
                                                    <i class="mdi mdi-plus-circle-outline"></i>
                                                    Add Activity
                                                </button>
                                            </div>
                                            <div id="calendarActivityPreviewBody">
                                                <p class="calendar-agenda-empty">Choose a date from the calendar to view its schedule.</p>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include('includes/footer.php'); ?>
            </div>
        </div>

        <!-- Whereabouts Modal -->
        <div id="whereaboutsModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 9999;">
            <div style="background: #ffffff; border-radius: 22px; padding: 28px; max-width: 760px; width: 92%; max-height: 88vh; overflow-y: auto; box-shadow: 0 24px 60px rgba(15, 23, 42, 0.2);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="color: var(--user-ink); font-weight: 700; margin: 0;">Plan Activities / Events</h3>
                    <button onclick="closeWhereaboutsModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--user-muted);">&times;</button>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="color: var(--user-ink); font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; display: block;">Selected Dates</label>
                    <ul id="selectedDatesList" style="list-style: none; padding: 0; margin: 0; color: var(--user-muted); font-size: 0.9rem;"></ul>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="color: var(--user-ink); font-weight: 600; font-size: 0.9rem; margin-bottom: 6px; display: block;">Activities / Events</label>
                    <p class="calendar-event-hint">Add one or more activity cards below. Every card will be saved to each selected date.</p>
                    <div id="calendarEventsContainer" class="calendar-event-stack" style="margin-top: 16px;"></div>
                    <div class="calendar-event-toolbar">
                        <button type="button" id="addCalendarEventButton" class="calendar-event-add">
                            <i class="mdi mdi-plus-circle-outline"></i>
                            Add Another Activity
                        </button>
                        <span id="calendarEventSummary" class="calendar-event-summary">1 activity ready to save.</span>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <button onclick="closeWhereaboutsModal()" style="padding: 12px 28px; border: 1px solid rgba(60, 64, 198, 0.08); border-radius: 12px; background: #eef3f8; color: var(--user-ink); font-weight: 600; cursor: pointer; transition: all 0.25s ease;">Cancel</button>
                    <button onclick="submitWhereabouts()" style="padding: 12px 28px; border: none; border-radius: 12px; background: linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%); color: #ffffff; font-weight: 600; cursor: pointer; transition: all 0.25s ease;">Save Activities</button>
                </div>
            </div>
        </div>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/moment/moment.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/fullcalendar/fullcalendar.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.js"></script>

        <script>
            const manilaToday = <?= $manilaToday ? json_encode($manilaToday, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) : "'0000-00-00'"; ?>;
            const statusStyles = <?= $statusCalendarStylesJson ?: '{}' ?>;
            const activityEntriesByDate = <?= $calendarEntriesJson ?: '{}' ?>;
            const shouldPromptAvatarUpdate = <?= json_encode($shouldPromptAvatarUpdate); ?>;
            const profilePictureUploadUrl = <?= json_encode(base_url() . 'Page/upload_user_profile_picture', JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
            const defaultStatusStyle = statusStyles.default || {
                gradient: 'linear-gradient(135deg, #475569 0%, #64748b 100%)',
                accent: '#475569',
                tint: 'rgba(71, 85, 105, 0.14)',
                shadow: 'rgba(71, 85, 105, 0.22)'
            };
            const whereaboutsStatusOptions = [
                'In Office',
                'Out of Office',
                'On Official Business',
                'On Leave',
                'On Field Work'
            ];
            const previewFallbackMessage = 'No activities scheduled yet. Use the Add Activity button to plan this day.';

            let selectedDates = new Set();
            let selectedCalendarDate = manilaToday;
            let whereaboutsCalendar = null;

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

            function getStatusStyle(status) {
                return statusStyles[status] || defaultStatusStyle;
            }

            function formatDateLabel(dateStr, options) {
                const date = parseDateString(dateStr);
                return date ? date.toLocaleDateString('en-US', options) : dateStr;
            }

            function updateSelectedDateHeading(dateStr, entryCount) {
                const titleElement = document.getElementById('selectedDateTitle');
                const metaElement = document.getElementById('selectedDateMeta');
                if (!titleElement || !metaElement) {
                    return;
                }

                titleElement.textContent = formatDateLabel(dateStr, {
                    weekday: 'long',
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                });

                if (entryCount > 0) {
                    metaElement.textContent = `${entryCount} scheduled ${entryCount === 1 ? 'activity' : 'activities'} for this date.`;
                    return;
                }

                metaElement.textContent = dateStr === manilaToday
                    ? 'Nothing scheduled yet for today.'
                    : 'No activities scheduled yet for this date.';
            }

            function resetActivityPreview(dateStr) {
                const targetDate = dateStr || selectedCalendarDate || manilaToday;
                const previewBody = document.getElementById('calendarActivityPreviewBody');
                if (!previewBody) {
                    return;
                }

                updateSelectedDateHeading(targetDate, 0);
                previewBody.innerHTML = `<p class="calendar-agenda-empty">${escapeHtml(previewFallbackMessage)}</p>`;
            }

            function renderActivityPreview(dateStr) {
                const previewBody = document.getElementById('calendarActivityPreviewBody');
                const entries = getEntriesForDate(dateStr);
                if (!previewBody || !entries.length) {
                    resetActivityPreview(dateStr);
                    return;
                }

                updateSelectedDateHeading(dateStr, entries.length);
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

            function getCalendarEventsContainer() {
                return document.getElementById('calendarEventsContainer');
            }

            function buildStatusOptions(selectedStatus) {
                return whereaboutsStatusOptions.map(function(status) {
                    const selected = status === selectedStatus ? ' selected' : '';
                    return `<option value="${escapeHtml(status)}"${selected}>${escapeHtml(status)}</option>`;
                }).join('');
            }

            function createCalendarEventCard(entry) {
                const values = entry || {};
                const selectedStatus = values.status || 'In Office';
                const location = values.location || '';
                const activity = values.activity || '';
                const notes = values.notes || '';
                const card = document.createElement('div');

                card.className = 'calendar-event-card';
                card.innerHTML = `
                    <div class="calendar-event-card-head">
                        <div>
                            <h4 class="calendar-event-card-title">Activity / Event</h4>
                            <p class="calendar-event-card-copy">Set the status, location, activity details, and optional notes for this schedule item.</p>
                        </div>
                        <button type="button" class="calendar-event-remove">
                            <i class="mdi mdi-delete-outline"></i>
                            Remove
                        </button>
                    </div>
                    <div class="calendar-event-grid">
                        <div class="calendar-event-field">
                            <label class="calendar-event-label">Status</label>
                            <select class="calendar-event-input" data-field="status">
                                ${buildStatusOptions(selectedStatus)}
                            </select>
                        </div>
                        <div class="calendar-event-field">
                            <label class="calendar-event-label">Location <span style="color: #ef4444;">*</span></label>
                            <input type="text" class="calendar-event-input" data-field="location" placeholder="e.g., Main Office, School X" value="${escapeHtml(location)}">
                        </div>
                        <div class="calendar-event-field calendar-event-field--full">
                            <label class="calendar-event-label">Activity / Event <span style="color: #ef4444;">*</span></label>
                            <textarea class="calendar-event-input calendar-event-textarea" data-field="activity" placeholder="What are you working on or attending?">${escapeHtml(activity)}</textarea>
                        </div>
                        <div class="calendar-event-field calendar-event-field--full">
                            <label class="calendar-event-label">Notes</label>
                            <textarea class="calendar-event-input calendar-event-textarea" data-field="notes" placeholder="Any additional details...">${escapeHtml(notes)}</textarea>
                        </div>
                    </div>
                `;

                const removeButton = card.querySelector('.calendar-event-remove');
                removeButton.addEventListener('click', function() {
                    if (getCalendarEventsContainer().querySelectorAll('.calendar-event-card').length <= 1) {
                        return;
                    }

                    card.remove();
                    updateCalendarEventCardsState();
                });

                return card;
            }

            function updateCalendarEventSummary() {
                const summary = document.getElementById('calendarEventSummary');
                const container = getCalendarEventsContainer();
                if (!summary || !container) {
                    return;
                }

                const entryCount = container.querySelectorAll('.calendar-event-card').length;
                const dateCount = selectedDates.size;
                const eventLabel = entryCount === 1 ? 'activity' : 'activities';
                const dateLabel = dateCount === 1 ? 'date' : 'dates';

                if (dateCount > 0) {
                    summary.textContent = `${entryCount} ${eventLabel} will be saved across ${dateCount} selected ${dateLabel}.`;
                    return;
                }

                summary.textContent = `${entryCount} ${eventLabel} ready to save.`;
            }

            function updateCalendarEventCardsState() {
                const container = getCalendarEventsContainer();
                if (!container) {
                    return;
                }

                const cards = Array.from(container.querySelectorAll('.calendar-event-card'));
                const disableRemove = cards.length <= 1;

                cards.forEach(function(card, index) {
                    const title = card.querySelector('.calendar-event-card-title');
                    const removeButton = card.querySelector('.calendar-event-remove');

                    if (title) {
                        title.textContent = `Activity / Event #${index + 1}`;
                    }

                    if (removeButton) {
                        removeButton.disabled = disableRemove;
                    }
                });

                updateCalendarEventSummary();
            }

            function addCalendarEventCard(entry) {
                const container = getCalendarEventsContainer();
                if (!container) {
                    return;
                }

                container.appendChild(createCalendarEventCard(entry));
                updateCalendarEventCardsState();
            }

            function resetCalendarEventCards(entries) {
                const container = getCalendarEventsContainer();
                if (!container) {
                    return;
                }

                const initialEntries = Array.isArray(entries) && entries.length ? entries : [{}];
                container.innerHTML = '';

                initialEntries.forEach(function(entry) {
                    container.appendChild(createCalendarEventCard(entry));
                });

                updateCalendarEventCardsState();
            }

            function ensureCalendarEventCards() {
                const container = getCalendarEventsContainer();
                if (!container) {
                    return;
                }

                if (!container.querySelector('.calendar-event-card')) {
                    resetCalendarEventCards();
                    return;
                }

                updateCalendarEventCardsState();
            }

            function collectCalendarEventEntries() {
                const container = getCalendarEventsContainer();
                if (!container) {
                    return [];
                }

                return Array.from(container.querySelectorAll('.calendar-event-card')).map(function(card) {
                    const getFieldValue = function(fieldName) {
                        const field = card.querySelector(`[data-field="${fieldName}"]`);
                        return field ? String(field.value || '').trim() : '';
                    };

                    return {
                        status: getFieldValue('status') || 'In Office',
                        location: getFieldValue('location'),
                        activity: getFieldValue('activity'),
                        notes: getFieldValue('notes')
                    };
                });
            }

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

            function slugifyStatus(status) {
                return String(status || 'default')
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/(^-|-$)/g, '') || 'default';
            }

            function normalizeCalendarEntry(entry) {
                const values = entry || {};

                return {
                    status: String(values.status || 'In Office').trim() || 'In Office',
                    location: String(values.location || '').trim(),
                    activity: String(values.activity || '').trim(),
                    notes: String(values.notes || '').trim()
                };
            }

            function buildCalendarEntrySignature(entry) {
                const normalizedEntry = normalizeCalendarEntry(entry);

                return [
                    normalizedEntry.status,
                    normalizedEntry.location,
                    normalizedEntry.activity,
                    normalizedEntry.notes
                ].join('||');
            }

            function buildConsecutiveDateRanges(dates) {
                const uniqueDates = Array.from(new Set((dates || []).filter(Boolean))).sort();
                const ranges = [];
                let rangeStart = null;
                let previousDate = null;

                uniqueDates.forEach(function(dateStr) {
                    if (!rangeStart) {
                        rangeStart = dateStr;
                        previousDate = dateStr;
                        return;
                    }

                    const expectedNextDate = moment(previousDate, 'YYYY-MM-DD').add(1, 'day').format('YYYY-MM-DD');
                    if (dateStr === expectedNextDate) {
                        previousDate = dateStr;
                        return;
                    }

                    ranges.push({
                        start: rangeStart,
                        end: previousDate
                    });

                    rangeStart = dateStr;
                    previousDate = dateStr;
                });

                if (rangeStart) {
                    ranges.push({
                        start: rangeStart,
                        end: previousDate
                    });
                }

                return ranges;
            }

            function buildCalendarEvent(dateRange, entry, index) {
                const normalizedEntry = normalizeCalendarEntry(entry);
                const statusStyle = getStatusStyle(normalizedEntry.status);
                const rangeStart = dateRange.start;
                const rangeEnd = dateRange.end;
                const endDateExclusive = moment(rangeEnd, 'YYYY-MM-DD').add(1, 'day').format('YYYY-MM-DD');
                const isSingleDate = rangeStart === rangeEnd;
                const dateSummary = isSingleDate
                    ? formatDateLabel(rangeStart, { month: 'short', day: 'numeric', year: 'numeric' })
                    : `${formatDateLabel(rangeStart, { month: 'short', day: 'numeric' })} to ${formatDateLabel(rangeEnd, { month: 'short', day: 'numeric', year: 'numeric' })}`;

                return {
                    id: `whereabouts-${rangeStart}-${rangeEnd}-${index}-${slugifyStatus(normalizedEntry.status)}-${slugifyStatus(normalizedEntry.activity || normalizedEntry.location || 'activity')}`,
                    title: normalizedEntry.activity || normalizedEntry.location || normalizedEntry.status || 'Activity',
                    start: rangeStart,
                    end: endDateExclusive,
                    allDay: true,
                    backgroundColor: statusStyle.accent,
                    borderColor: statusStyle.accent,
                    textColor: '#ffffff',
                    className: ['calendar-range-event'],
                    whereaboutsDate: rangeStart,
                    whereaboutsRangeStart: rangeStart,
                    whereaboutsRangeEnd: rangeEnd,
                    whereaboutsDateSummary: dateSummary,
                    whereaboutsStatus: normalizedEntry.status || 'Saved whereabouts',
                    whereaboutsLocation: normalizedEntry.location,
                    whereaboutsActivity: normalizedEntry.activity,
                    whereaboutsNotes: normalizedEntry.notes
                };
            }

            function buildCalendarEvents() {
                const groupedEntries = {};
                const events = [];

                Object.keys(activityEntriesByDate).sort().forEach(function(dateStr) {
                    getEntriesForDate(dateStr).forEach(function(entry) {
                        const normalizedEntry = normalizeCalendarEntry(entry);
                        const signature = buildCalendarEntrySignature(normalizedEntry);

                        if (!groupedEntries[signature]) {
                            groupedEntries[signature] = {
                                entry: normalizedEntry,
                                dates: []
                            };
                        }

                        groupedEntries[signature].dates.push(dateStr);
                    });
                });

                Object.keys(groupedEntries).forEach(function(signature, groupIndex) {
                    const groupedEntry = groupedEntries[signature];

                    buildConsecutiveDateRanges(groupedEntry.dates).forEach(function(dateRange, rangeIndex) {
                        events.push(buildCalendarEvent(dateRange, groupedEntry.entry, `${groupIndex}-${rangeIndex}`));
                    });
                });

                return events.sort(function(left, right) {
                    if (left.start !== right.start) {
                        return left.start < right.start ? -1 : 1;
                    }

                    return String(left.title || '').localeCompare(String(right.title || ''));
                });
            }

            function syncCalendarDaySelection() {
                if (!whereaboutsCalendar) {
                    return;
                }

                const calendarElement = $('#whereaboutsCalendar');
                calendarElement.find('.fc-day').removeClass('fc-day-selected');

                if (!selectedCalendarDate) {
                    return;
                }

                calendarElement.find(`.fc-day[data-date="${selectedCalendarDate}"]`).addClass('fc-day-selected');
            }

            function setSelectedCalendarDate(dateStr) {
                selectedCalendarDate = dateStr || manilaToday;
                renderActivityPreview(selectedCalendarDate);
                syncCalendarDaySelection();
            }

            function buildSelectedRangeDates(start, end) {
                const dates = [];
                const cursor = start.clone().startOf('day');
                const endDate = end.clone().startOf('day');

                while (cursor.isBefore(endDate)) {
                    dates.push(cursor.format('YYYY-MM-DD'));
                    cursor.add(1, 'day');
                }

                if (!dates.length) {
                    dates.push(start.format('YYYY-MM-DD'));
                }

                return dates;
            }

            function showWhereaboutsModal(dates) {
                const normalizedDates = Array.isArray(dates) && dates.length
                    ? dates.slice().sort()
                    : [selectedCalendarDate || manilaToday];
                const modal = document.getElementById('whereaboutsModal');
                const datesList = document.getElementById('selectedDatesList');

                selectedDates = new Set(normalizedDates);
                datesList.innerHTML = '';

                normalizedDates.forEach(function(date) {
                    const li = document.createElement('li');
                    li.textContent = formatDateLabel(date, { weekday: 'short', month: 'short', day: 'numeric' });
                    li.style.padding = '4px 0';
                    li.style.borderBottom = '1px solid rgba(60, 64, 198, 0.08)';
                    datesList.appendChild(li);
                });

                resetCalendarEventCards();
                updateCalendarEventSummary();
                modal.style.display = 'flex';

                if (whereaboutsCalendar) {
                    whereaboutsCalendar.fullCalendar('unselect');
                }
            }

            function closeWhereaboutsModal() {
                document.getElementById('whereaboutsModal').style.display = 'none';
                selectedDates.clear();
                resetCalendarEventCards();
                updateCalendarEventSummary();

                if (whereaboutsCalendar) {
                    whereaboutsCalendar.fullCalendar('unselect');
                }
            }

            function appendEntriesToCalendarData(dates, entries) {
                dates.forEach(function(dateStr) {
                    if (!Array.isArray(activityEntriesByDate[dateStr])) {
                        activityEntriesByDate[dateStr] = [];
                    }

                    entries.forEach(function(entry) {
                        activityEntriesByDate[dateStr].push({
                            status: entry.status,
                            location: entry.location,
                            activity: entry.activity,
                            notes: entry.notes
                        });
                    });
                });
            }

            function initializeWhereaboutsCalendar() {
                const calendarElement = $('#whereaboutsCalendar');
                if (!calendarElement.length || typeof calendarElement.fullCalendar !== 'function') {
                    return;
                }

                whereaboutsCalendar = calendarElement;
                calendarElement.fullCalendar({
                    themeSystem: 'bootstrap4',
                    defaultView: 'month',
                    defaultDate: manilaToday,
                    height: 'auto',
                    fixedWeekCount: false,
                    editable: false,
                    selectable: true,
                    selectHelper: true,
                    unselectAuto: false,
                    displayEventTime: false,
                    eventLimit: 3,
                    navLinks: false,
                    header: {
                        left: 'prev,next today addActivityButton',
                        center: 'title',
                        right: 'month,listWeek'
                    },
                    buttonText: {
                        month: 'Month',
                        listWeek: 'Agenda'
                    },
                    customButtons: {
                        addActivityButton: {
                            text: 'Add Activity',
                            click: function() {
                                showWhereaboutsModal([selectedCalendarDate || manilaToday]);
                            }
                        }
                    },
                    events: function(start, end, timezone, callback) {
                        callback(buildCalendarEvents());
                    },
                    dayClick: function(date) {
                        setSelectedCalendarDate(date.format('YYYY-MM-DD'));
                    },
                    select: function(start, end) {
                        const rangeDates = buildSelectedRangeDates(start, end);
                        setSelectedCalendarDate(rangeDates[0]);
                        showWhereaboutsModal(rangeDates);
                    },
                    eventClick: function(event) {
                        const eventDate = event.whereaboutsDate || event.start.format('YYYY-MM-DD');
                        setSelectedCalendarDate(eventDate);
                        return false;
                    },
                    eventRender: function(event, element) {
                        const tooltipLines = [
                            event.whereaboutsDateSummary,
                            event.whereaboutsStatus,
                            event.whereaboutsLocation,
                            event.whereaboutsActivity,
                            event.whereaboutsNotes
                        ].filter(Boolean);

                        if (tooltipLines.length) {
                            element.attr('title', tooltipLines.join('\n'));
                        }
                    },
                    viewRender: function() {
                        window.requestAnimationFrame(syncCalendarDaySelection);
                    },
                    eventAfterAllRender: function() {
                        syncCalendarDaySelection();
                    }
                });

                setSelectedCalendarDate(selectedCalendarDate || manilaToday);
            }

            function submitWhereabouts() {
                const dates = Array.from(selectedDates).sort();
                const entries = collectCalendarEventEntries();

                if (!dates.length) {
                    Swal.fire({
                        title: 'No date selected',
                        text: 'Please choose at least one date from the calendar.',
                        type: 'warning',
                        confirmButtonColor: '#3c40c6'
                    });
                    return;
                }

                if (!entries.length) {
                    Swal.fire({
                        title: 'Add an activity',
                        text: 'Please add at least one activity or event before saving.',
                        type: 'warning',
                        confirmButtonColor: '#3c40c6'
                    });
                    return;
                }

                const invalidEntryIndex = entries.findIndex(function(entry) {
                    return !entry.location || !entry.activity;
                });

                if (invalidEntryIndex !== -1) {
                    Swal.fire({
                        title: 'Complete the required fields',
                        text: `Please complete the location and activity for Activity / Event #${invalidEntryIndex + 1}.`,
                        type: 'warning',
                        confirmButtonColor: '#3c40c6'
                    });
                    return;
                }

                const formData = new FormData();
                formData.append('dates', JSON.stringify(dates));
                formData.append('entries', JSON.stringify(entries));

                fetch('<?= base_url(); ?>Page/whereabouts_ajax', {
                    method: 'POST',
                    body: formData
                })
                .then(function(response) {
                    return response.json().catch(function() {
                        throw new Error('Unable to save your activities right now.');
                    }).then(function(data) {
                        if (!response.ok || !data.success) {
                            throw new Error(data.message || 'Unable to save your activities right now.');
                        }

                        return data;
                    });
                })
                .then(function(data) {
                    const entryLabel = data.entries_saved === 1 ? 'activity' : 'activities';
                    const dateLabel = data.dates_saved === 1 ? 'date' : 'dates';
                    appendEntriesToCalendarData(dates, entries);

                    if (whereaboutsCalendar) {
                        whereaboutsCalendar.fullCalendar('refetchEvents');
                    }

                    setSelectedCalendarDate(dates[0] || selectedCalendarDate);

                    Swal.fire({
                        title: 'Activities saved',
                        text: `${data.entries_saved} ${entryLabel} saved across ${data.dates_saved} ${dateLabel}.`,
                        type: 'success',
                        confirmButtonColor: '#3c40c6'
                    }).then(function() {
                        closeWhereaboutsModal();
                    });
                })
                .catch(function(error) {
                    Swal.fire({
                        title: 'Unable to save activities',
                        text: error.message || 'Please try again.',
                        type: 'error',
                        confirmButtonColor: '#3c40c6'
                    });
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                const addCalendarEventButton = document.getElementById('addCalendarEventButton');
                const openSelectedDateComposer = document.getElementById('openSelectedDateComposer');
                const profilePictureTriggers = document.querySelectorAll('.js-change-profile-picture');
                const urlParams = new URLSearchParams(window.location.search);

                profilePictureTriggers.forEach(function(trigger) {
                    trigger.addEventListener('click', function(event) {
                        event.preventDefault();
                        showProfilePictureUploadPrompt();
                    });
                });

                if (addCalendarEventButton) {
                    addCalendarEventButton.addEventListener('click', function() {
                        addCalendarEventCard();
                    });
                }

                resetCalendarEventCards();
                resetActivityPreview(selectedCalendarDate);
                initializeWhereaboutsCalendar();

                if (openSelectedDateComposer) {
                    openSelectedDateComposer.addEventListener('click', function() {
                        showWhereaboutsModal([selectedCalendarDate || manilaToday]);
                    });
                }

                if (urlParams.get('open_profile_picture') === '1') {
                    if (window.history && typeof window.history.replaceState === 'function') {
                        urlParams.delete('open_profile_picture');
                        const nextQuery = urlParams.toString();
                        const nextUrl = `${window.location.pathname}${nextQuery ? `?${nextQuery}` : ''}${window.location.hash || ''}`;
                        window.history.replaceState({}, document.title, nextUrl);
                    }

                    showProfilePictureUploadPrompt();
                    return;
                }

                if (shouldPromptAvatarUpdate) {
                    showDefaultAvatarReminder();
                }
            });
        </script>

    </body>
</html>
