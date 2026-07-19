<?php
$sectionName = $this->session->userdata('section') ?: 'Section';
$secGroupName = $this->session->userdata('secGroup') ?: '';
$selectedYear = $this->input->post('year') ? (string) $this->input->post('year') : date('Y');
$selectedMonth = $this->input->post('month') ? (string) $this->input->post('month') : date('F');
$selectedScope = isset($selectedScope) && strtolower((string) $selectedScope) === 'personal' ? 'personal' : 'section';
$scopeLabel = $selectedScope === 'personal' ? 'Personal Accomplishments' : 'Section Accomplishments';
$isFiltered = (bool) $this->input->post('submit');
$records = is_array($data) ? $data : array();
$reportGroups = isset($reportGroups) && is_array($reportGroups) ? $reportGroups : array();
$attachmentModalAccId = (int) $this->session->flashdata('attachment_modal_acc_id');
$attachmentDocumentName = (string) $this->session->flashdata('attachment_document_name');
$recordCount = count($records);
$filterSummary = $isFiltered
    ? sprintf('Showing %s for %s / %s.', $scopeLabel, $selectedMonth, $selectedYear)
    : sprintf('Showing all %s.', strtolower($scopeLabel));

if (!function_exists('sect_accom_escape')) {
    function sect_accom_escape($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('sect_accom_resource_markup')) {
    function sect_accom_resource_markup($value)
    {
        $trimmed = trim((string) $value);

        if ($trimmed === '') {
            return '<span class="table-muted">None</span>';
        }

        $href = $trimmed;
        if (preg_match('/^www\./i', $trimmed)) {
            $href = 'https://' . $trimmed;
        }

        if (filter_var($href, FILTER_VALIDATE_URL)) {
            return '<a href="' . sect_accom_escape($href) . '" target="_blank" rel="noopener noreferrer" class="resource-button" title="' . sect_accom_escape($trimmed) . '"><i class="mdi mdi-open-in-new"></i><span>Open Link</span></a>';
        }

        return sect_accom_escape($trimmed);
    }
}
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
        <link href="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <style>
            :root {
                --user-navy: #272b8c;
                --user-blue: #3c40c6;
                --user-teal: #565de8;
                --user-ink: #23275d;
                --user-muted: #7b7fa7;
                --user-border: rgba(60, 64, 198, 0.12);
                --user-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
                --user-surface: rgba(255, 255, 255, 0.95);
            }

            body {
                background:
                    radial-gradient(circle at top left, rgba(60, 64, 198, 0.10), transparent 24%),
                    linear-gradient(180deg, #f4f8fc 0%, #eef4fa 100%);
            }

            .content-page {
                background: transparent;
            }

            .accom-shell {
                position: relative;
                padding-bottom: 28px;
            }

            .accom-shell::before {
                content: "";
                position: absolute;
                inset: 24px 0 auto;
                height: 240px;
                border-radius: 30px;
                background: linear-gradient(135deg, rgba(45, 127, 249, 0.09), rgba(15, 159, 154, 0.08));
                z-index: 0;
            }

            .accom-shell > * {
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
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 32%),
                    linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
            }

            .dashboard-hero-body {
                padding: 32px;
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 20px;
                flex-wrap: wrap;
            }

            .hero-copy-wrap {
                flex: 1 1 420px;
                max-width: 700px;
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
                color: rgba(255, 255, 255, 0.82);
                line-height: 1.7;
                margin-bottom: 0;
            }

            .hero-meta {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin-top: 18px;
            }

            .hero-chip {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 12px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.12);
                color: rgba(255, 255, 255, 0.95);
                font-size: 0.84rem;
                font-weight: 600;
            }

            .hero-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                justify-content: flex-end;
            }

            .hero-action {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 12px 18px;
                border-radius: 999px;
                border: 1px solid rgba(255, 255, 255, 0.24);
                color: #ffffff;
                background: rgba(255, 255, 255, 0.12);
                font-weight: 700;
                text-decoration: none;
                transition: transform 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
            }

            .hero-action:hover,
            .hero-action:focus {
                color: #ffffff;
                text-decoration: none;
                background: rgba(255, 255, 255, 0.18);
                transform: translateY(-1px);
                box-shadow: 0 16px 30px rgba(15, 23, 42, 0.16);
            }

            .hero-action--secondary {
                background: rgba(255, 255, 255, 0.08);
            }

            .flash-alert {
                border: none;
                border-radius: 18px;
                box-shadow: 0 12px 32px rgba(15, 23, 42, 0.08);
                padding: 16px 18px;
            }

            .panel-card {
                border-radius: 24px;
                border: 1px solid rgba(60, 64, 198, 0.08);
                background: var(--user-surface);
                box-shadow: var(--user-shadow);
                padding: 28px;
                margin-bottom: 24px;
            }

            .panel-head {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 16px;
                flex-wrap: wrap;
                margin-bottom: 22px;
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
                margin: 16px 0 8px;
                color: var(--user-ink);
                font-size: 1.45rem;
                font-weight: 700;
                line-height: 1.2;
            }

            .panel-copy {
                color: var(--user-muted);
                font-size: 0.95rem;
                line-height: 1.6;
                margin-bottom: 0;
            }

            .panel-summary {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                padding: 12px 16px;
                border-radius: 18px;
                background: linear-gradient(180deg, #f8faff 0%, #eef4ff 100%);
                border: 1px solid rgba(60, 64, 198, 0.08);
                color: var(--user-ink);
                font-weight: 600;
            }

            .filter-grid {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 18px;
            }

            .smart-field {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .smart-label {
                color: var(--user-ink);
                font-size: 0.9rem;
                font-weight: 700;
                margin: 0;
            }

            .smart-select {
                width: 100%;
                height: 48px;
                padding: 0 14px;
                border-radius: 14px;
                border: 1px solid rgba(60, 64, 198, 0.12);
                background: #ffffff;
                color: var(--user-ink);
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }

            .smart-select:focus {
                outline: none;
                border-color: rgba(60, 64, 198, 0.35);
                box-shadow: 0 0 0 3px rgba(60, 64, 198, 0.08);
            }

            .filter-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                margin-top: 22px;
            }

            .shell-button {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 12px 18px;
                border: none;
                border-radius: 14px;
                font-weight: 700;
                text-decoration: none;
                transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
            }

            .shell-button:hover,
            .shell-button:focus {
                text-decoration: none;
                transform: translateY(-1px);
                box-shadow: 0 14px 28px rgba(15, 23, 42, 0.12);
            }

            .shell-button--primary {
                background: linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
                color: #ffffff;
            }

            .shell-button--secondary {
                background: rgba(60, 64, 198, 0.08);
                color: var(--user-blue);
            }

            .table-shell .table-responsive {
                overflow-x: auto;
            }

            .datatable-modern {
                width: 100% !important;
                margin: 0 !important;
            }

            .datatable-modern thead th {
                border-top: none;
                border-bottom: 1px solid rgba(60, 64, 198, 0.10);
                color: var(--user-muted);
                font-size: 0.78rem;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                padding: 16px 14px;
                background: #f9fbff;
                vertical-align: middle;
            }

            .datatable-modern tbody td {
                border-top: 1px solid rgba(60, 64, 198, 0.08);
                padding: 16px 14px;
                vertical-align: top;
                color: var(--user-ink);
                font-size: 0.92rem;
                line-height: 1.55;
            }

            .datatable-modern tbody tr:hover {
                background: rgba(60, 64, 198, 0.02);
            }

            .activity-cell {
                min-width: 220px;
                font-weight: 700;
            }

            .date-cell,
            .numeric-cell,
            .percentage-cell {
                white-space: nowrap;
            }

            .wide-cell {
                min-width: 180px;
            }

            .notes-cell {
                min-width: 200px;
            }

            .category-pill {
                display: inline-flex;
                align-items: center;
                padding: 6px 10px;
                border-radius: 999px;
                background: rgba(37, 99, 235, 0.10);
                color: #2563eb;
                font-size: 0.76rem;
                font-weight: 700;
                letter-spacing: 0.03em;
            }

            .category-pill--updates {
                background: rgba(124, 58, 237, 0.12);
                color: #7c3aed;
            }

            .percentage-pill {
                display: inline-flex;
                align-items: center;
                padding: 6px 10px;
                border-radius: 999px;
                background: rgba(15, 118, 110, 0.12);
                color: #0f766e;
                font-size: 0.78rem;
                font-weight: 700;
            }

            .table-link {
                color: var(--user-blue);
                font-weight: 700;
                text-decoration: none;
                word-break: break-word;
            }

            .table-link:hover,
            .table-link:focus {
                color: var(--user-navy);
                text-decoration: none;
            }

            .resource-button {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 9px 12px;
                border-radius: 12px;
                background: rgba(37, 99, 235, 0.10);
                color: #2563eb;
                font-weight: 700;
                text-decoration: none;
                white-space: nowrap;
            }

            .resource-button:hover,
            .resource-button:focus {
                color: #1d4ed8;
                text-decoration: none;
                box-shadow: 0 10px 20px rgba(15, 23, 42, 0.08);
            }

            .table-muted {
                color: var(--user-muted);
            }

            .attachments-cell {
                min-width: 240px;
            }

            .attachment-stack {
                display: grid;
                gap: 8px;
            }

            .attachment-link {
                display: inline-flex;
                align-items: flex-start;
                gap: 10px;
                padding: 10px 12px;
                border-radius: 14px;
                background: rgba(234, 88, 12, 0.08);
                color: #c2410c;
                font-weight: 700;
                text-decoration: none;
            }

            .attachment-link:hover,
            .attachment-link:focus {
                color: #9a3412;
                text-decoration: none;
                box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
            }

            .attachment-meta {
                display: flex;
                flex-direction: column;
                gap: 2px;
                min-width: 0;
            }

            .attachment-name,
            .attachment-file {
                word-break: break-word;
            }

            .attachment-file {
                color: #9a3412;
                font-size: 0.74rem;
                font-weight: 600;
            }

            .manage-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                justify-content: center;
                min-width: 210px;
            }

            .manage-button {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 8px 12px;
                border-radius: 12px;
                font-size: 0.78rem;
                font-weight: 700;
                text-decoration: none;
                transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
            }

            button.manage-button {
                border: none;
                cursor: pointer;
            }

            .manage-button:hover,
            .manage-button:focus {
                text-decoration: none;
                transform: translateY(-1px);
                box-shadow: 0 10px 18px rgba(15, 23, 42, 0.10);
            }

            .manage-button--attach {
                background: rgba(124, 58, 237, 0.10);
                color: #7c3aed;
            }

            .manage-button--copy {
                background: rgba(15, 118, 110, 0.10);
                color: #0f766e;
            }

            .manage-button--edit {
                background: rgba(37, 99, 235, 0.10);
                color: #2563eb;
            }

            .manage-button--report {
                background: rgba(234, 88, 12, 0.10);
                color: #ea580c;
            }

            .manage-button--delete {
                background: rgba(220, 38, 38, 0.10);
                color: #dc2626;
            }

            .attachment-modal .modal-dialog {
                max-width: 780px;
            }

            .attachment-modal .modal-content {
                border: none;
                border-radius: 26px;
                overflow: hidden;
                box-shadow: 0 28px 70px rgba(15, 23, 42, 0.24);
            }

            .attachment-modal .modal-header {
                align-items: flex-start;
                padding: 24px 28px;
                border-bottom: none;
                background:
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 34%),
                    linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
            }

            .attachment-modal .modal-title {
                margin: 16px 0 0;
                color: #ffffff;
                font-size: 1.4rem;
                font-weight: 700;
            }

            .attachment-modal .close {
                margin: 0;
                padding: 0;
                color: #ffffff;
                opacity: 0.9;
                text-shadow: none;
            }

            .attachment-modal .close:hover,
            .attachment-modal .close:focus {
                color: #ffffff;
                opacity: 1;
            }

            .panel-kicker--light {
                background: rgba(255, 255, 255, 0.16);
                color: #ffffff;
                border: 1px solid rgba(255, 255, 255, 0.22);
            }

            .attachment-modal .modal-body {
                padding: 24px;
                background: #f6f9ff;
            }

            .attachment-target,
            .attachment-existing {
                padding: 18px 20px;
                border-radius: 20px;
                border: 1px solid rgba(60, 64, 198, 0.08);
                background: #ffffff;
            }

            .attachment-target {
                margin-bottom: 18px;
            }

            .attachment-target-label {
                color: var(--user-muted);
                font-size: 0.76rem;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                margin-bottom: 8px;
            }

            .attachment-target-title {
                color: var(--user-ink);
                font-size: 1.05rem;
                font-weight: 700;
                line-height: 1.5;
            }

            .attachment-existing {
                margin-bottom: 18px;
            }

            .attachment-existing-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                margin-bottom: 14px;
            }

            .attachment-existing-title {
                color: var(--user-ink);
                font-size: 1rem;
                font-weight: 700;
                margin: 0;
            }

            .attachment-count {
                display: inline-flex;
                align-items: center;
                padding: 6px 10px;
                border-radius: 999px;
                background: rgba(60, 64, 198, 0.08);
                color: var(--user-blue);
                font-size: 0.76rem;
                font-weight: 700;
            }

            .attachment-existing-list {
                display: grid;
                gap: 10px;
            }

            .modal-field {
                display: flex;
                flex-direction: column;
                gap: 10px;
                margin-bottom: 18px;
            }

            .modal-label {
                color: var(--user-ink);
                font-size: 0.92rem;
                font-weight: 700;
                margin: 0;
            }

            .modal-input {
                width: 100%;
                min-height: 48px;
                padding: 12px 14px;
                border-radius: 14px;
                border: 1px solid rgba(60, 64, 198, 0.14);
                background: #ffffff;
                color: var(--user-ink);
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }

            .modal-input:focus {
                outline: none;
                border-color: rgba(60, 64, 198, 0.35);
                box-shadow: 0 0 0 3px rgba(60, 64, 198, 0.08);
            }

            .modal-note {
                color: var(--user-muted);
                font-size: 0.84rem;
                line-height: 1.5;
            }

            .modal-actions {
                display: flex;
                flex-wrap: wrap;
                justify-content: flex-end;
                gap: 12px;
                margin-top: 24px;
                padding-top: 18px;
                border-top: 1px solid rgba(60, 64, 198, 0.08);
            }

            .dataTables_wrapper .row:first-child {
                margin-bottom: 16px;
            }

            .dataTables_wrapper .dataTables_length label,
            .dataTables_wrapper .dataTables_filter label,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                color: var(--user-muted);
                font-weight: 600;
            }

            .dataTables_wrapper .dataTables_filter input,
            .dataTables_wrapper .dataTables_length select {
                border: 1px solid rgba(60, 64, 198, 0.12);
                border-radius: 12px;
                background: #ffffff;
                color: var(--user-ink);
                min-height: 42px;
                padding: 0 14px;
            }

            .dataTables_wrapper .dataTables_filter input:focus,
            .dataTables_wrapper .dataTables_length select:focus {
                outline: none;
                border-color: rgba(60, 64, 198, 0.35);
                box-shadow: 0 0 0 3px rgba(60, 64, 198, 0.08);
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button {
                margin: 0 2px;
                border-radius: 10px !important;
                border: none !important;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.current,
            .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
                background: linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%) !important;
                color: #ffffff !important;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
                background: rgba(60, 64, 198, 0.08) !important;
                color: var(--user-blue) !important;
            }

            @media (max-width: 1199.98px) {
                .filter-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }

            @media (max-width: 767.98px) {
                .accom-shell::before {
                    left: 12px;
                    right: 12px;
                    inset-block-start: 18px;
                    height: 220px;
                }

                .dashboard-hero,
                .panel-card {
                    border-radius: 22px;
                }

                .dashboard-hero-body,
                .panel-card {
                    padding: 22px;
                }

                .filter-grid {
                    grid-template-columns: 1fr;
                }

                .hero-actions {
                    justify-content: flex-start;
                }

                .panel-head {
                    margin-bottom: 18px;
                }

                .attachment-existing-head,
                .modal-actions {
                    flex-direction: column;
                    align-items: stretch;
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
                    <div class="container-fluid accom-shell">
                        <div class="dashboard-hero">
                            <div class="dashboard-hero-body">
                                <div class="hero-copy-wrap">
                                    <span class="hero-eyebrow">
                                        <i class="mdi mdi-clipboard-text-outline"></i>
                                        Section Workspace
                                    </span>
                                    <h1 class="hero-title">Section Accomplishments</h1>
                                </div>
                            </div>
                        </div>

                        <div class="panel-card" style="margin-top: 24px;">
                            <div class="panel-head">
                                <div>
                                    <div class="panel-kicker">Quick Actions & Filter</div>
                                </div>
                                <div class="panel-summary">
                                    <i class="mdi mdi-filter-outline"></i>
                                    <span><?= sect_accom_escape($filterSummary); ?></span>
                                </div>
                            </div>

                            <div class="filter-actions" style="margin-bottom: 20px;">
                                <a href="<?= base_url(); ?>Page/addAccomplishments" class="shell-button shell-button--primary">
                                    <i class="mdi mdi-plus-circle-outline"></i>
                                    Add Accomplishment
                                </a>
                                <a target="_blank" rel="noopener noreferrer" href="<?= base_url(); ?>Page/print_report/<?= rawurlencode($sectionName); ?>" class="shell-button shell-button--secondary">
                                    <i class="mdi mdi-printer-outline"></i>
                                    Generate Report
                                </a>
                            </div>

                            <?php $att = array('class' => 'parsley-examples'); ?>
                            <?= form_open('Page/viewSecAccomplishments', $att); ?>
                                <div class="filter-grid">
                                    <div class="smart-field">
                                        <label class="smart-label" for="filterScope">Scope</label>
                                        <select class="smart-select" id="filterScope" name="scope" required>
                                            <option value="section" <?= $selectedScope === 'section' ? 'selected' : ''; ?>>Section Accomplishments</option>
                                            <option value="personal" <?= $selectedScope === 'personal' ? 'selected' : ''; ?>>Personal Accomplishments</option>
                                        </select>
                                    </div>

                                    <div class="smart-field">
                                        <label class="smart-label" for="filterYear">Year</label>
                                        <select class="smart-select" id="filterYear" name="year" required>
                                            <?php for ($i = 2022; $i < date('Y') + 8; $i++) { ?>
                                                <option value="<?= $i; ?>" <?= (string) $selectedYear === (string) $i ? 'selected' : ''; ?>><?= $i; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="smart-field">
                                        <label class="smart-label" for="filterMonth">Month</label>
                                        <select class="smart-select" id="filterMonth" name="month" required>
                                            <option value="" disabled <?= $selectedMonth === '' ? 'selected' : ''; ?>>Select month</option>
                                            <?php for ($m = 1; $m <= 12; ++$m) { ?>
                                                <?php $monthName = date('F', mktime(0, 0, 0, $m, 1)); ?>
                                                <option value="<?= $monthName; ?>" <?= $selectedMonth === $monthName ? 'selected' : ''; ?>><?= $monthName; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="filter-actions">
                                    <button type="submit" name="submit" value="1" class="shell-button shell-button--primary">
                                        <i class="mdi mdi-filter-check-outline"></i>
                                        Apply Filter
                                    </button>
                                    <a href="<?= base_url(); ?>Page/viewSecAccomplishments" class="shell-button shell-button--secondary">
                                        <i class="mdi mdi-refresh"></i>
                                        Reset Filters
                                    </a>
                                </div>
                            </form>
                        </div>

                        <?php if ($this->session->flashdata('success')) { ?>
                            <div class="alert alert-success flash-alert alert-dismissible fade show" role="alert">
                                <?= sect_accom_escape($this->session->flashdata('success')); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php } ?>

                        <?php if ($this->session->flashdata('danger')) { ?>
                            <div class="alert alert-warning flash-alert alert-dismissible fade show" role="alert">
                                <?= sect_accom_escape($this->session->flashdata('danger')); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php } ?>

                        <div class="panel-card table-shell">
                            <div class="panel-head">
                                <div>
                                    <div class="panel-kicker">Records</div>
                                    <h4 class="panel-title">Accomplishment list</h4>
                                    <p class="panel-copy">Review, copy, update, or remove accomplishment entries for your section.</p>
                                </div>
                                <div class="panel-summary">
                                    <i class="mdi mdi-format-list-checks"></i>
                                    <span><?= number_format($recordCount); ?> accomplishment<?= $recordCount === 1 ? '' : 's'; ?></span>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="datatable" class="table datatable-modern">
                                    <thead>
                                        <tr>
                                            <th>Activity</th>
                                            <th>Category</th>
                                            <th>Venue</th>
                                            <th>Date</th>
                                            <th>Resources</th>
                                            <th>Notes</th>
                                            <th>Remarks</th>
                                            <th>Attachments</th>
                                            <th style="text-align:center">Manage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($records as $row) { ?>
                                            <?php
                                                $category = trim((string) $row->activityCategory);
                                                $categoryClass = strtolower($category) === 'updates' ? 'category-pill category-pill--updates' : 'category-pill';
                                                $reportRecords = isset($reportGroups[(int) $row->id]) ? $reportGroups[(int) $row->id] : array();
                                                $attachmentPayload = array();

                                                foreach ($reportRecords as $attachmentRow) {
                                                    $attachmentPayload[] = array(
                                                        'document_name' => (string) $attachmentRow->document_name,
                                                        'original_name' => (string) $attachmentRow->original_name,
                                                        'url' => base_url() . 'upload/accomplishment_reports/' . rawurlencode($attachmentRow->stored_name)
                                                    );
                                                }

                                                $attachmentPayloadJson = htmlspecialchars(json_encode($attachmentPayload), ENT_QUOTES, 'UTF-8');
                                            ?>
                                            <tr>
                                                <td class="activity-cell"><?= sect_accom_escape($row->activity); ?></td>
                                                <td><span class="<?= $categoryClass; ?>"><?= sect_accom_escape($category ?: 'Uncategorized'); ?></span></td>
                                                <td class="wide-cell"><?= sect_accom_escape($row->venue); ?></td>
                                                <td class="date-cell"><?= sect_accom_escape($row->dateConducted); ?></td>
                                                <td class="wide-cell"><?= sect_accom_resource_markup($row->resources); ?></td>
                                                <td class="notes-cell"><?= nl2br(sect_accom_escape($row->notes)); ?></td>
                                                <td class="notes-cell"><?= nl2br(sect_accom_escape($row->remarks)); ?></td>
                                                <td class="attachments-cell">
                                                    <?php if (!empty($reportRecords)) { ?>
                                                        <div class="attachment-stack">
                                                            <?php foreach ($reportRecords as $attachmentRow) { ?>
                                                                <?php
                                                                    $documentLabel = trim((string) $attachmentRow->document_name) !== ''
                                                                        ? (string) $attachmentRow->document_name
                                                                        : (string) $attachmentRow->original_name;
                                                                ?>
                                                                <a href="<?= base_url(); ?>upload/accomplishment_reports/<?= rawurlencode($attachmentRow->stored_name); ?>" target="_blank" rel="noopener noreferrer" class="attachment-link" title="<?= sect_accom_escape($attachmentRow->original_name); ?>">
                                                                    <i class="mdi mdi-file-pdf-box"></i>
                                                                    <span class="attachment-meta">
                                                                        <span class="attachment-name"><?= sect_accom_escape($documentLabel); ?></span>
                                                                        <?php if (trim((string) $attachmentRow->original_name) !== '' && $attachmentRow->original_name !== $documentLabel) { ?>
                                                                            <span class="attachment-file"><?= sect_accom_escape($attachmentRow->original_name); ?></span>
                                                                        <?php } ?>
                                                                    </span>
                                                                </a>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class="table-muted">No PDF attached</span>
                                                    <?php } ?>
                                                </td>
                                                <td style="text-align:center">
                                                    <div class="manage-actions">
                                                        <button type="button" class="manage-button manage-button--attach js-open-attachment-modal" data-acc-id="<?= (int) $row->id; ?>" data-activity="<?= sect_accom_escape($row->activity); ?>" data-attachments="<?= $attachmentPayloadJson; ?>">
                                                            <i class="mdi mdi-paperclip"></i>
                                                            <?= empty($reportRecords) ? 'Add Attachment' : 'Add More' ?>
                                                        </button>
                                                        <a href="<?= base_url(); ?>Page/copy_acc/<?= (int) $row->id; ?>" class="manage-button manage-button--copy">
                                                            <i class="mdi mdi-content-copy"></i>
                                                            Copy
                                                        </a>
                                                        <a href="<?= base_url(); ?>Page/updateAccomplishments?id=<?= (int) $row->id; ?>" class="manage-button manage-button--edit">
                                                            <i class="mdi mdi-pencil-outline"></i>
                                                            Update
                                                        </a>
                                                        <a href="<?= base_url(); ?>Page/deleteAccomplishment?id=<?= (int) $row->id; ?>" class="manage-button manage-button--delete js-delete-accomplishment">
                                                            <i class="mdi mdi-delete-outline"></i>
                                                            Delete
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include('includes/footer.php'); ?>
            </div>
        </div>

        <div class="modal fade attachment-modal" id="attachmentModal" tabindex="-1" role="dialog" aria-labelledby="attachmentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <span class="panel-kicker panel-kicker--light">
                                <i class="mdi mdi-paperclip"></i>
                                PDF Attachment
                            </span>
                            <h5 class="modal-title" id="attachmentModalLabel">Attach PDF to Accomplishment</h5>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="attachment-target">
                            <div class="attachment-target-label">Selected accomplishment</div>
                            <div class="attachment-target-title" id="attachmentModalActivity">Select an accomplishment first.</div>
                        </div>

                        <div class="attachment-existing">
                            <div class="attachment-existing-head">
                                <h6 class="attachment-existing-title">Current PDF attachments</h6>
                                <span class="attachment-count" id="attachmentCountBadge">0 files</span>
                            </div>
                            <div class="attachment-existing-list" id="attachmentExistingList">
                                <span class="table-muted">No PDF attachments yet for this accomplishment.</span>
                            </div>
                        </div>

                        <form method="post" action="<?= base_url(); ?>Page/addAccomplishmentAttachment" enctype="multipart/form-data" id="attachmentForm">
                            <input type="hidden" name="acc_id" id="attachmentAccId" value="">

                            <div class="modal-field">
                                <label class="modal-label" for="attachmentDocumentName">Document Name</label>
                                <input type="text" class="modal-input" id="attachmentDocumentName" name="document_name" value="" required>
                            </div>

                            <div class="modal-field">
                                <label class="modal-label" for="attachmentFile">Attachment</label>
                                <input type="file" class="modal-input" id="attachmentFile" name="attachment_file" accept=".pdf,application/pdf" required>
                                <span class="modal-note">Upload one PDF file only for this document. You can reopen this modal anytime to add more attachments to the same accomplishment.</span>
                            </div>

                            <div class="modal-actions">
                                <button type="button" class="shell-button shell-button--secondary" data-dismiss="modal">
                                    <i class="mdi mdi-close"></i>
                                    Cancel
                                </button>
                                <button type="submit" class="shell-button shell-button--primary">
                                    <i class="mdi mdi-content-save-outline"></i>
                                    Save Attachment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.responsive.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#datatable').DataTable({
                    responsive: true,
                    autoWidth: false,
                    pageLength: 10,
                    order: [],
                    columnDefs: [
                        {
                            targets: -1,
                            orderable: false,
                            searchable: false
                        }
                    ],
                    language: {
                        search: '',
                        searchPlaceholder: 'Search activity, venue, remarks...',
                        lengthMenu: 'Show _MENU_ entries',
                        info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                        infoEmpty: 'No accomplishment entries available',
                        emptyTable: 'No accomplishments found for the selected filter.'
                    }
                });

                $(document).on('click', '.js-delete-accomplishment', function(event) {
                    event.preventDefault();

                    const deleteUrl = $(this).attr('href');
                    if (!deleteUrl) {
                        return;
                    }

                    Swal.fire({
                        title: 'Delete this accomplishment?',
                        text: 'This action cannot be undone.',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Delete',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#64748b'
                    }).then(function(result) {
                        if (result.value) {
                            window.location.href = deleteUrl;
                        }
                    });
                });

                const attachmentModal = $('#attachmentModal');
                const attachmentAccIdField = $('#attachmentAccId');
                const attachmentActivityLabel = $('#attachmentModalActivity');
                const attachmentDocumentNameField = $('#attachmentDocumentName');
                const attachmentFileField = $('#attachmentFile');
                const attachmentCountBadge = $('#attachmentCountBadge');
                const attachmentExistingList = $('#attachmentExistingList');
                const attachmentReopenState = {
                    accId: <?= (int) $attachmentModalAccId; ?>,
                    documentName: <?= json_encode($attachmentDocumentName); ?>
                };

                function renderAttachmentList(attachments) {
                    attachmentExistingList.empty();

                    if (!attachments.length) {
                        attachmentExistingList.html('<span class="table-muted">No PDF attachments yet for this accomplishment.</span>');
                        attachmentCountBadge.text('0 files');
                        return;
                    }

                    $.each(attachments, function(index, attachment) {
                        const documentLabel = attachment.document_name ? attachment.document_name : attachment.original_name;
                        const fileLabel = attachment.original_name ? attachment.original_name : 'PDF attachment';
                        const item = $('<a>', {
                            href: attachment.url,
                            target: '_blank',
                            rel: 'noopener noreferrer',
                            class: 'attachment-link'
                        });
                        const meta = $('<span>', { class: 'attachment-meta' });

                        item.append($('<i>', { class: 'mdi mdi-file-pdf-box' }));
                        meta.append($('<span>', { class: 'attachment-name', text: documentLabel }));

                        if (fileLabel && fileLabel !== documentLabel) {
                            meta.append($('<span>', { class: 'attachment-file', text: fileLabel }));
                        }

                        item.append(meta);
                        attachmentExistingList.append(item);
                    });

                    attachmentCountBadge.text(attachments.length + (attachments.length === 1 ? ' file' : ' files'));
                }

                function openAttachmentModal(trigger, preferredDocumentName) {
                    let attachments = [];
                    const rawAttachments = trigger.attr('data-attachments');

                    if (rawAttachments) {
                        try {
                            attachments = JSON.parse(rawAttachments);
                        } catch (error) {
                            attachments = [];
                        }
                    }

                    attachmentAccIdField.val(trigger.data('acc-id'));
                    attachmentActivityLabel.text(trigger.data('activity') || 'Selected accomplishment');
                    attachmentDocumentNameField.val(preferredDocumentName || '');
                    attachmentFileField.val('');
                    renderAttachmentList(attachments);
                    attachmentModal.modal('show');
                }

                $(document).on('click', '.js-open-attachment-modal', function() {
                    openAttachmentModal($(this), '');
                });

                if (attachmentReopenState.accId > 0) {
                    const reopenButton = $('.js-open-attachment-modal[data-acc-id="' + attachmentReopenState.accId + '"]').first();
                    if (reopenButton.length) {
                        openAttachmentModal(reopenButton, attachmentReopenState.documentName || '');
                    }
                }
            });
        </script>

    </body>
</html>
