<?php
$currentGroup = strtoupper((string) $this->session->userdata('secGroup'));
$totalUsers = is_array($data) ? count($data) : 0;
$activeUsers = 0;
$inactiveUsers = 0;
$coveredSectionsMap = array();

if (!empty($data)) {
    foreach ($data as $userRow) {
        if (strtolower((string) $userRow->acctStat) === 'active') {
            $activeUsers++;
        } else {
            $inactiveUsers++;
        }

        if (!empty($userRow->section)) {
            $coveredSectionsMap[$userRow->section] = true;
        }
    }
}

$managedSections = is_array($data1) ? count($data1) : 0;
$coveredSections = count($coveredSectionsMap);

$positionsList = (isset($positions) && is_array($positions)) ? $positions : array();
$positionNameSet = array();
foreach ($positionsList as $positionOption) {
    $positionNameSet[(string) $positionOption->positionName] = true;
}

$metrics = array(
    array(
        'value' => $totalUsers,
        'label' => 'User Accounts',
        'icon' => 'mdi-account-group-outline',
        'accent' => '#3c40c6',
        'icon_bg' => 'rgba(60, 64, 198, 0.12)',
        'icon_color' => '#3c40c6',
        'badge' => 'Directory'
    ),
    array(
        'value' => $activeUsers,
        'label' => 'Active Accounts',
        'icon' => 'mdi-account-check-outline',
        'accent' => '#565de8',
        'icon_bg' => 'rgba(86, 93, 232, 0.14)',
        'icon_color' => '#565de8',
        'badge' => 'Access'
    ),
    array(
        'value' => $inactiveUsers,
        'label' => 'Inactive Accounts',
        'icon' => 'mdi-account-off-outline',
        'accent' => '#7a80ff',
        'icon_bg' => 'rgba(122, 128, 255, 0.16)',
        'icon_color' => '#4e54d4',
        'badge' => 'Status'
    ),
    array(
        'value' => $managedSections,
        'label' => 'Managed Sections',
        'icon' => 'mdi-view-grid-outline',
        'accent' => '#272b8c',
        'icon_bg' => 'rgba(39, 43, 140, 0.12)',
        'icon_color' => '#272b8c',
        'badge' => 'Coverage'
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
        <link href="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />
        <link href="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/libs/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/libs/datatables/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/libs/select2/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/libs/select2/select2.min.css" rel="stylesheet" type="text/css" />

        <style>
            :root {
                --users-navy: #272b8c;
                --users-blue: #3c40c6;
                --users-teal: #565de8;
                --users-gold: #7a80ff;
                --users-rose: #cf4964;
                --users-ink: #23275d;
                --users-muted: #7b7fa7;
                --users-border: rgba(60, 64, 198, 0.12);
                --users-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            }

            body {
                background:
                    radial-gradient(circle at top left, rgba(60, 64, 198, 0.10), transparent 24%),
                    linear-gradient(180deg, #f4f8fc 0%, #eef4fa 100%);
            }

            .content-page {
                background: transparent;
            }

            .users-shell {
                position: relative;
                padding-bottom: 28px;
            }

            .users-shell::before {
                content: "";
                position: absolute;
                inset: 24px 0 auto;
                height: 240px;
                border-radius: 30px;
                background: linear-gradient(135deg, rgba(60, 64, 198, 0.11), rgba(122, 128, 255, 0.10));
                z-index: 0;
            }

            .users-shell > * {
                position: relative;
                z-index: 1;
            }

            .users-hero {
                margin-top: 20px;
                border-radius: 28px;
                overflow: hidden;
                color: #ffffff;
                box-shadow: var(--users-shadow);
                background:
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 32%),
                    linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
            }

            .users-hero-body {
                padding: 32px;
            }

            .users-eyebrow {
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

            .users-title {
                margin: 18px 0 12px;
                color: #ffffff;
                font-size: clamp(2rem, 3vw, 2.7rem);
                line-height: 1.05;
                font-weight: 700;
                letter-spacing: -0.03em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .users-copy {
                max-width: 620px;
                margin-bottom: 0;
                color: rgba(255, 255, 255, 0.82);
                line-height: 1.7;
            }

            .hero-side {
                height: 100%;
                display: flex;
                align-items: stretch;
            }

            .hero-side-card {
                width: 100%;
                padding: 24px;
                display: flex;
                align-items: center;
                border-radius: 22px;
                background: rgba(255, 255, 255, 0.12);
                border: 1px solid rgba(255, 255, 255, 0.18);
                backdrop-filter: blur(14px);
            }

            .hero-add-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                width: 100%;
                padding: 13px 18px;
                border: none;
                border-radius: 16px;
                color: var(--users-navy);
                background: linear-gradient(135deg, #ffffff 0%, #eef7ff 100%);
                font-weight: 700;
                transition: transform 0.25s ease, box-shadow 0.25s ease;
            }

            .hero-add-btn:hover {
                color: var(--users-navy);
                transform: translateY(-2px);
                box-shadow: 0 14px 32px rgba(17, 24, 39, 0.12);
            }

            .metric-card {
                height: 100%;
                border: none;
                border-radius: 22px;
                background: rgba(255, 255, 255, 0.95);
                box-shadow: var(--users-shadow);
                overflow: hidden;
                position: relative;
                animation: fade-up 0.65s ease both;
            }

            .metric-card::before {
                content: "";
                position: absolute;
                inset: 0 0 auto;
                height: 4px;
                background: var(--accent);
            }

            .metric-card-body {
                padding: 24px;
            }

            .metric-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 16px;
            }

            .metric-icon {
                width: 54px;
                height: 54px;
                border-radius: 16px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 1.4rem;
            }

            .metric-badge {
                display: inline-flex;
                align-items: center;
                padding: 7px 12px;
                border-radius: 999px;
                background: #f4f8fc;
                color: var(--users-muted);
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.05em;
                text-transform: uppercase;
            }

            .metric-value {
                margin-bottom: 8px;
                color: var(--users-ink);
                font-size: 2.2rem;
                line-height: 1;
                font-weight: 700;
                letter-spacing: -0.04em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .metric-label {
                color: var(--users-ink);
                font-size: 1rem;
                font-weight: 700;
                margin-bottom: 8px;
            }

            .users-shell .alert {
                border: none;
                border-radius: 18px;
                box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
            }

            .table-panel {
                border: 1px solid var(--users-border);
                border-radius: 24px;
                background: rgba(255, 255, 255, 0.96);
                box-shadow: var(--users-shadow);
                overflow: hidden;
            }

            .table-panel-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                padding: 24px 26px 0;
            }

            .table-panel-title {
                margin-bottom: 6px;
                color: var(--users-ink);
                font-size: 1.35rem;
                font-weight: 700;
                letter-spacing: -0.02em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .table-panel-copy {
                margin-bottom: 0;
                color: var(--users-muted);
                line-height: 1.6;
            }

            .table-shell {
                padding: 20px 26px 26px;
            }

            .dataTables_wrapper .dataTables_filter label,
            .dataTables_wrapper .dataTables_length label {
                color: var(--users-muted);
                font-weight: 600;
            }

            .dataTables_wrapper .dataTables_filter input,
            .dataTables_wrapper .dataTables_length select {
                border-radius: 12px;
                border: 1px solid rgba(60, 64, 198, 0.14);
                min-height: 42px;
                padding: 8px 12px;
                box-shadow: none;
            }

            .dataTables_wrapper .dataTables_info {
                color: var(--users-muted);
            }

            .users-table {
                margin-bottom: 0 !important;
                border-collapse: separate !important;
                border-spacing: 0 12px !important;
            }

            .users-table thead th {
                border: none !important;
                color: var(--users-muted) !important;
                font-size: 0.78rem !important;
                font-weight: 700 !important;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                padding: 0 18px 8px !important;
                background: transparent !important;
            }

            .users-table tbody tr {
                background: linear-gradient(180deg, #ffffff 0%, #f9fbfd 100%);
                box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
            }

            .users-table tbody td {
                border: none !important;
                vertical-align: middle !important;
                padding: 18px !important;
                color: var(--users-ink);
                background: transparent !important;
            }

            .users-table tbody td:first-child {
                border-radius: 18px 0 0 18px;
            }

            .users-table tbody td:last-child {
                border-radius: 0 18px 18px 0;
            }

            .user-name {
                font-weight: 700;
                letter-spacing: -0.01em;
                color: var(--users-ink);
            }

            .user-name small {
                display: block;
                margin-top: 6px;
                color: var(--users-muted);
                font-size: 0.88rem;
                font-weight: 500;
            }

            .user-chip,
            .section-chip,
            .status-pill {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 14px;
                border-radius: 999px;
                font-size: 0.83rem;
                font-weight: 700;
            }

            .user-chip {
                background: rgba(60, 64, 198, 0.10);
                color: var(--users-blue);
            }

            .section-chip {
                background: rgba(60, 64, 198, 0.10);
                color: var(--users-navy);
            }

            .status-active {
                background: rgba(60, 64, 198, 0.12);
                color: #3136a5;
            }

            .status-inactive {
                background: rgba(122, 128, 255, 0.18);
                color: #4e54d4;
            }

            .manage-combo {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .manage-select-wrap {
                position: relative;
                min-width: 220px;
            }

            .manage-select-icon {
                position: absolute;
                top: 50%;
                left: 14px;
                transform: translateY(-50%);
                color: var(--users-blue);
                font-size: 1rem;
                pointer-events: none;
            }

            .manage-select {
                width: 100%;
                min-height: 44px;
                padding: 10px 38px 10px 40px;
                border-radius: 14px;
                border: 1px solid rgba(60, 64, 198, 0.14);
                background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
                color: var(--users-ink);
                font-size: 0.85rem;
                font-weight: 700;
                box-shadow: none;
                cursor: pointer;
                appearance: none;
                -webkit-appearance: none;
                -moz-appearance: none;
                transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
            }

            .manage-select:hover,
            .manage-select:focus {
                outline: none;
                border-color: rgba(60, 64, 198, 0.3);
                box-shadow: 0 0 0 0.18rem rgba(60, 64, 198, 0.10);
                transform: translateY(-1px);
            }

            .manage-select-arrow {
                position: absolute;
                top: 50%;
                right: 14px;
                transform: translateY(-50%);
                color: var(--users-muted);
                font-size: 1rem;
                pointer-events: none;
            }

            .empty-state {
                text-align: center;
                padding: 38px 24px;
            }

            .empty-state i {
                font-size: 2.2rem;
                color: var(--users-blue);
                margin-bottom: 12px;
            }

            .empty-state h5 {
                color: var(--users-ink);
                font-weight: 700;
                margin-bottom: 8px;
            }

            .empty-state p {
                color: var(--users-muted);
                margin-bottom: 0;
            }

            .modal-content {
                border: none;
                border-radius: 26px;
                overflow: hidden;
                box-shadow: 0 28px 70px rgba(15, 23, 42, 0.18);
            }

            .modern-modal-header {
                padding: 24px 28px;
                color: #ffffff;
                background: linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
            }

            .modern-modal-header .close {
                color: #ffffff;
                opacity: 0.9;
                text-shadow: none;
            }

            .modern-modal-title {
                margin-bottom: 6px;
                color: #ffffff;
                font-size: 1.35rem;
                font-weight: 700;
                letter-spacing: -0.02em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .modern-modal-copy {
                margin-bottom: 0;
                color: rgba(255, 255, 255, 0.80);
            }

            .modern-modal-body {
                padding: 28px;
                background: #f8fbff;
            }

            .form-card {
                border: 1px solid rgba(60, 64, 198, 0.08);
                border-radius: 22px;
                background: #ffffff;
                padding: 24px;
            }

            .modern-label {
                color: var(--users-ink);
                font-weight: 700;
                margin-bottom: 8px;
            }

            .modern-input {
                min-height: 48px;
                border-radius: 14px;
                border: 1px solid rgba(60, 64, 198, 0.14);
                padding: 12px 14px;
                box-shadow: none;
            }

            .modern-input:focus {
                border-color: var(--users-blue);
                box-shadow: 0 0 0 0.18rem rgba(60, 64, 198, 0.14);
            }

            .input-note {
                display: block;
                margin-top: 8px;
                color: var(--users-muted);
                font-size: 0.84rem;
            }

            .modal-footer-modern {
                display: flex;
                justify-content: flex-end;
                gap: 12px;
                padding-top: 16px;
                flex-wrap: wrap;
            }

            .btn-soft-dark {
                color: var(--users-ink);
                background: #eef3f8;
                border: none;
                border-radius: 14px;
                padding: 11px 16px;
                font-weight: 700;
            }

            .btn-gradient-primary {
                color: #ffffff;
                border: none;
                border-radius: 14px;
                padding: 11px 18px;
                font-weight: 700;
                background: linear-gradient(135deg, #3c40c6 0%, #6f74ff 100%);
                box-shadow: 0 14px 30px rgba(60, 64, 198, 0.18);
            }

            .btn-gradient-primary:hover {
                color: #ffffff;
            }

            /* Select2 look to match modern inputs */
            .select2-container { width: 100% !important; }

            .select2-container .select2-selection--single {
                height: 48px;
                border-radius: 14px;
                border: 1px solid rgba(60, 64, 198, 0.14);
                display: flex;
                align-items: center;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 46px;
                padding-left: 14px;
                color: var(--users-ink);
                font-weight: 600;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow { height: 46px; }

            .select2-container--default.select2-container--focus .select2-selection--single,
            .select2-container--default.select2-container--open .select2-selection--single {
                border-color: var(--users-blue);
                box-shadow: 0 0 0 0.18rem rgba(60, 64, 198, 0.14);
            }

            .select2-dropdown {
                border-radius: 14px;
                border: 1px solid rgba(60, 64, 198, 0.14);
                overflow: hidden;
                box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
                z-index: 1060;
            }

            .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background: var(--users-blue);
            }

            .select2-container--default .select2-search--dropdown .select2-search__field {
                border-radius: 10px;
                border: 1px solid rgba(60, 64, 198, 0.18);
                padding: 8px 10px;
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

            @media (max-width: 1199.98px) {
                .hero-side {
                    margin-top: 24px;
                }
            }

            @media (max-width: 991.98px) {
                .users-hero-body,
                .table-shell,
                .modern-modal-body {
                    padding: 22px;
                }

                .table-panel-head {
                    padding: 22px 22px 0;
                }
            }

            @media (max-width: 767.98px) {
                .users-title {
                    font-size: 2rem;
                }

                .table-panel-head {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .dataTables_wrapper .dataTables_filter,
                .dataTables_wrapper .dataTables_length,
                .dataTables_wrapper .dataTables_info,
                .dataTables_wrapper .dataTables_paginate {
                    text-align: left !important;
                }

                .users-table tbody td {
                    padding: 16px !important;
                }

                .modal-footer-modern {
                    flex-direction: column-reverse;
                }

                .modal-footer-modern .btn,
                .modal-footer-modern input[type="submit"] {
                    width: 100%;
                }
            }
        </style>
    </head>

    <body>
        <div id="wrapper">

            <?php include('includes/top-bar.php'); ?>
            <?php include('includes/sidebar.php'); ?>

            <div class="content-page">
                <div class="content">
                    <div class="container-fluid users-shell">

                        <div class="users-hero">
                            <div class="users-hero-body">
                                <div class="row align-items-center">
                                    <div class="col-xl-8">
                                        <span class="users-eyebrow">
                                            <i class="mdi mdi-shield-account-outline"></i>
                                            Department User Management
                                        </span>
                                        <h1 class="users-title">Users &amp; Access</h1>
                                        <p class="users-copy">
                                            Create, update, and manage department user accounts in one clean workspace.
                                        </p>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="hero-side">
                                            <div class="hero-side-card">
                                                <button type="button" class="hero-add-btn" data-toggle="modal" data-target="#myModal">
                                                    <i class="mdi mdi-account-plus-outline"></i>
                                                    Add New User
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($this->session->flashdata('success')) : ?>
                            <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <?= $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($this->session->flashdata('danger')) : ?>
                            <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <?= $this->session->flashdata('danger'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($this->session->flashdata('msg')) : ?>
                            <div class="mt-4">
                                <?= $this->session->flashdata('msg'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="row mt-4">
                            <?php foreach ($metrics as $index => $metric) : ?>
                                <div class="col-md-6 col-xl-3 mb-4">
                                    <div class="metric-card" style="--accent: <?= $metric['accent']; ?>; animation-delay: <?= number_format(($index + 1) * 0.08, 2, '.', ''); ?>s;">
                                        <div class="metric-card-body">
                                            <div class="metric-head">
                                                <span class="metric-icon" style="background: <?= $metric['icon_bg']; ?>; color: <?= $metric['icon_color']; ?>;">
                                                    <i class="mdi <?= $metric['icon']; ?>"></i>
                                                </span>
                                                <span class="metric-badge"><?= htmlspecialchars($metric['badge'], ENT_QUOTES, 'UTF-8'); ?></span>
                                            </div>
                                            <div class="metric-value"><?= htmlspecialchars((string) $metric['value'], ENT_QUOTES, 'UTF-8'); ?></div>
                                            <div class="metric-label"><?= htmlspecialchars($metric['label'], ENT_QUOTES, 'UTF-8'); ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="table-panel">
                                    <div class="table-panel-head">
                                        <div>
                                            <h4 class="table-panel-title">User Directory</h4>
                                            <p class="table-panel-copy">
                                                Search by name, username, or section to update access quickly.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="table-shell">
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-borderless dt-responsive nowrap users-table w-100">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Username</th>
                                                        <th>Section</th>
                                                        <th>Position</th>
                                                        <th>Status</th>
                                                        <th class="text-center">Manage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($data)) : ?>
                                                        <?php foreach ($data as $index => $row) : ?>
                                                            <?php
                                                            $fullName = trim(((string) $row->lName) . ', ' . ((string) $row->fName), ', ');
                                                            if ($fullName === '') {
                                                                $fullName = (string) $row->username;
                                                            }
                                                            $username = (string) $row->username;
                                                            $email = !empty($row->email) ? (string) $row->email : $username;
                                                            $section = trim((string) $row->section) !== '' ? (string) $row->section : 'Unassigned';
                                                            $accountStatus = strtolower((string) $row->acctStat) === 'active' ? 'Active' : 'Inactive';
                                                            $positionName = isset($row->secPosition) ? trim((string) $row->secPosition) : '';
                                                            $positionDisplay = $positionName !== '' ? $positionName : 'Unassigned';
                                                            $statusClass = $accountStatus === 'Active' ? 'status-active' : 'status-inactive';
                                                            $toggleStatus = $accountStatus === 'Active' ? 'Inactive' : 'Active';
                                                            $toggleLabel = $accountStatus === 'Active' ? 'Deactivate user' : 'Activate user';
                                                            $toggleUrl = base_url() . 'Page/deactivate_user?username=' . rawurlencode($username) . '&status=' . rawurlencode($toggleStatus);
                                                            $resetUrl = base_url() . 'Page/reset_password?username=' . rawurlencode($username);
                                                            $deleteUrl = base_url() . 'Page/delete_account?id=' . rawurlencode($username);
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <div class="user-name">
                                                                        <?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'); ?>
                                                                        <small><?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></small>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span class="user-chip">
                                                                        <i class="mdi mdi-at"></i>
                                                                        <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span class="section-chip">
                                                                        <i class="mdi mdi-office-building-outline"></i>
                                                                        <?= htmlspecialchars($section, ENT_QUOTES, 'UTF-8'); ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span class="section-chip">
                                                                        <i class="mdi mdi-badge-account-horizontal-outline"></i>
                                                                        <?= htmlspecialchars($positionDisplay, ENT_QUOTES, 'UTF-8'); ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span class="status-pill <?= $statusClass; ?>">
                                                                        <i class="mdi mdi-circle-medium"></i>
                                                                        <?= htmlspecialchars($accountStatus, ENT_QUOTES, 'UTF-8'); ?>
                                                                    </span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="manage-combo">
                                                                        <div class="manage-select-wrap">
                                                                            <i class="mdi mdi-tune-variant manage-select-icon"></i>
                                                                            <select
                                                                                class="manage-select js-manage-select"
                                                                                data-username="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>"
                                                                                data-toggle-url="<?= htmlspecialchars($toggleUrl, ENT_QUOTES, 'UTF-8'); ?>"
                                                                                data-reset-url="<?= htmlspecialchars($resetUrl, ENT_QUOTES, 'UTF-8'); ?>"
                                                                                data-delete-url="<?= htmlspecialchars($deleteUrl, ENT_QUOTES, 'UTF-8'); ?>"
                                                                                data-edit-target="#editModal<?= $index; ?>"
                                                                                data-password-target="#changePasswordModal<?= $index; ?>"
                                                                            >
                                                                                <option value="">Manage user...</option>
                                                                                <option value="toggle"><?= htmlspecialchars($toggleLabel, ENT_QUOTES, 'UTF-8'); ?></option>
                                                                                <option value="reset">Reset password</option>
                                                                                <option value="password">Change password</option>
                                                                                <option value="edit">Edit profile</option>
                                                                                <option value="delete">Delete account</option>
                                                                            </select>
                                                                            <i class="mdi mdi-chevron-down manage-select-arrow"></i>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else : ?>
                                                        <tr>
                                                            <td colspan="6">
                                                                <div class="empty-state">
                                                                    <i class="mdi mdi-account-search-outline"></i>
                                                                    <h5>No user accounts found</h5>
                                                                    <p>Start by adding a user for one of the sections under this department.</p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modern-modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h5 class="modern-modal-title" id="myModalLabel">Add New User</h5>
                                <p class="modern-modal-copy">Create a department user account and assign it to a section.</p>
                            </div>
                            <div class="modern-modal-body">
                                <form method="post">
                                    <div class="form-card">
                                        <div class="form-group">
                                            <label class="modern-label">Select Staff from HRIS <span class="text-danger">*</span></label>
                                            <select class="form-control modern-input" name="IDNumber" id="staffSelect" required>
                                                <option value="">-- Select Staff --</option>
                                                <?php
                                                    $staffOptions = isset($staffOptions) && is_array($staffOptions) ? $staffOptions : [];
                                                    foreach($staffOptions as $staff){ ?>
                                                    <option value="<?= $staff->IDNumber; ?>"
                                                        data-fname="<?= htmlspecialchars($staff->FirstName, ENT_QUOTES, 'UTF-8'); ?>"
                                                        data-mname="<?= htmlspecialchars($staff->MiddleName, ENT_QUOTES, 'UTF-8'); ?>"
                                                        data-lname="<?= htmlspecialchars($staff->LastName, ENT_QUOTES, 'UTF-8'); ?>"
                                                        data-email="<?= htmlspecialchars($staff->IDNumber, ENT_QUOTES, 'UTF-8'); ?>">
                                                        <?= htmlspecialchars($staff->LastName . ', ' . $staff->FirstName . ' ' . $staff->MiddleName, ENT_QUOTES, 'UTF-8'); ?> (<?= $staff->IDNumber; ?>)
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label class="modern-label">First Name</label>
                                                <input type="text" name="fName" id="fName" class="form-control modern-input" required readonly />
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="modern-label">Middle Name</label>
                                                <input type="text" name="mName" id="mName" class="form-control modern-input" readonly />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="modern-label">E-mail / Username</label>
                                            <input type="text" name="email" class="form-control modern-input" />
                                            <small class="input-note">This value is also used as the username for sign in.</small>
                                        </div>

                                        <div class="form-group">
                                            <label class="modern-label">Password</label>
                                            <input type="password" name="password" class="form-control modern-input" />
                                        </div>

                                        <div class="form-group mb-0">
                                            <label class="modern-label">Section</label>
                                            <select class="form-control modern-input" name="section">
                                                <option value=""></option>
                                                <?php if (!empty($data1)) : ?>
                                                    <?php foreach ($data1 as $row) : ?>
                                                        <option value="<?= htmlspecialchars($row->sectionName, ENT_QUOTES, 'UTF-8'); ?>">
                                                            <?= htmlspecialchars($row->sectionName, ENT_QUOTES, 'UTF-8'); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="modal-footer-modern">
                                        <button type="button" class="btn btn-soft-dark" data-dismiss="modal">Cancel</button>
                                        <input type="submit" name="submit" class="btn btn-gradient-primary" value="Save User" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!empty($data)) : ?>
                    <?php foreach ($data as $index => $row) : ?>
                        <?php
                        $username = (string) $row->username;
                        $email = !empty($row->email) ? (string) $row->email : $username;
                        $fullName = trim(((string) $row->lName) . ', ' . ((string) $row->fName), ', ');
                        if ($fullName === '') {
                            $fullName = $username;
                        }
                        ?>
                        <div id="editModal<?= $index; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?= $index; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modern-modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h5 class="modern-modal-title" id="editModalLabel<?= $index; ?>">Edit User</h5>
                                        <p class="modern-modal-copy">Update the user's profile, section assignment, or password.</p>
                                    </div>
                                    <div class="modern-modal-body">
                                        <form method="post" action="<?= base_url(); ?>Page/update_user">
                                            <div class="form-card">
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label class="modern-label">First Name</label>
                                                        <input type="text" name="fName" class="form-control modern-input" value="<?= htmlspecialchars((string) $row->fName, ENT_QUOTES, 'UTF-8'); ?>" required />
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="modern-label">Last Name</label>
                                                        <input type="text" name="lName" class="form-control modern-input" value="<?= htmlspecialchars((string) $row->lName, ENT_QUOTES, 'UTF-8'); ?>" required />
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label class="modern-label">Username</label>
                                                        <input type="text" class="form-control modern-input" value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>" readonly />
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="modern-label">E-mail</label>
                                                        <input type="text" name="email" class="form-control modern-input" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" required />
                                                    </div>
                                                </div>

                                                <?php $rowSecPosition = isset($row->secPosition) ? (string) $row->secPosition : ''; ?>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label class="modern-label">Section</label>
                                                        <select class="form-control modern-input" name="section">
                                                            <?php if (!empty($data1)) : ?>
                                                                <?php foreach ($data1 as $sec) : ?>
                                                                    <option value="<?= htmlspecialchars($sec->sectionName, ENT_QUOTES, 'UTF-8'); ?>" <?= $sec->sectionName === $row->section ? 'selected' : ''; ?>>
                                                                        <?= htmlspecialchars($sec->sectionName, ENT_QUOTES, 'UTF-8'); ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="modern-label">Position</label>
                                                        <select class="form-control modern-input js-position-select" name="secPosition" data-placeholder="Select a position">
                                                            <option value=""></option>
                                                            <?php foreach ($positionsList as $positionOption) : ?>
                                                                <option value="<?= htmlspecialchars($positionOption->positionName, ENT_QUOTES, 'UTF-8'); ?>" <?= $positionOption->positionName === $rowSecPosition ? 'selected' : ''; ?>>
                                                                    <?= htmlspecialchars($positionOption->positionName, ENT_QUOTES, 'UTF-8'); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                            <?php if ($rowSecPosition !== '' && !array_key_exists($rowSecPosition, $positionNameSet)) : ?>
                                                                <option value="<?= htmlspecialchars($rowSecPosition, ENT_QUOTES, 'UTF-8'); ?>" selected>
                                                                    <?= htmlspecialchars($rowSecPosition, ENT_QUOTES, 'UTF-8'); ?>
                                                                </option>
                                                            <?php endif; ?>
                                                        </select>
                                                        <small class="input-note">
                                                            Positions are managed under
                                                            <a href="<?= base_url(); ?>Page/positions">Position</a>.
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="form-group mb-0">
                                                    <label class="modern-label">Password</label>
                                                    <input type="password" name="password" class="form-control modern-input" placeholder="Leave blank to keep the current password" />
                                                    <small class="input-note">Leave this blank if you do not want to change the password here.</small>
                                                </div>
                                            </div>

                                            <input type="hidden" name="username" value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>" />

                                            <div class="modal-footer-modern">
                                                <button type="button" class="btn btn-soft-dark" data-dismiss="modal">Cancel</button>
                                                <input type="submit" name="submit" class="btn btn-gradient-primary" value="Update User" />
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="changePasswordModal<?= $index; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel<?= $index; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modern-modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h5 class="modern-modal-title" id="changePasswordModalLabel<?= $index; ?>">Change Password</h5>
                                        <p class="modern-modal-copy">Set a new password for this user account.</p>
                                    </div>
                                    <div class="modern-modal-body">
                                        <form method="post" action="<?= base_url(); ?>Page/change_user_password">
                                            <div class="form-card">
                                                <div class="form-group">
                                                    <label class="modern-label">User</label>
                                                    <input type="text" class="form-control modern-input" value="<?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'); ?>" readonly />
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-6 mb-0">
                                                        <label class="modern-label">New Password</label>
                                                        <input type="password" name="new_password" class="form-control modern-input" minlength="8" required />
                                                    </div>
                                                    <div class="form-group col-md-6 mb-0">
                                                        <label class="modern-label">Confirm Password</label>
                                                        <input type="password" name="confirm_password" class="form-control modern-input" minlength="8" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="hidden" name="username" value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>" />

                                            <div class="modal-footer-modern">
                                                <button type="button" class="btn btn-soft-dark" data-dismiss="modal">Cancel</button>
                                                <input type="submit" class="btn btn-gradient-primary" value="Change Password" />
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php include('includes/footer.php'); ?>
            </div>
        </div>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.buttons.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/buttons.bootstrap4.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/jszip/jszip.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/pdfmake/pdfmake.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/pdfmake/vfs_fonts.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/buttons.html5.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/buttons.print.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.responsive.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.keyTable.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.select.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/select2/select2.min.js"></script>

        <script type="text/javascript">
            $(function () {
                $('#datatable').DataTable({
                    destroy: true,
                    autoWidth: false,
                    pageLength: 10,
                    order: [],
                    responsive: true,
                    language: {
                        search: "Search:",
                        lengthMenu: "Show _MENU_ users",
                        info: "Showing _START_ to _END_ of _TOTAL_ users",
                        paginate: {
                            previous: "&lsaquo;",
                            next: "&rsaquo;"
                        }
                    }
                });

                $('#datatable_filter input').attr('placeholder', 'Search users');

                $('#sectionSelect').select2({
                    placeholder: '-- Select Section --',
                    width: '100%',
                    dropdownParent: $('#myModal')
                });

                $('#staffSelect').select2({
                    placeholder: '-- Select Staff --',
                    width: '100%',
                    dropdownParent: $('#myModal')
                });

                $('#staffSelect').on('change', function() {
                    const selectedOption = $(this).find('option:selected');

                    if ($(this).val()) {
                        $('#fName').val(selectedOption.data('fname') || '');
                        $('#mName').val(selectedOption.data('mname') || '');
                        $('#lName').val(selectedOption.data('lname') || '');
                        $('#email').val(selectedOption.data('email') || '');
                    } else {
                        $('#fName').val('');
                        $('#mName').val('');
                        $('#lName').val('');
                        $('#email').val('');
                    }
                });

<<<<<<< HEAD
                $('.js-position-select').each(function () {
                    var $select = $(this);
                    $select.select2({
                        placeholder: $select.data('placeholder') || 'Select a position',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $select.closest('.modal')
                    });
=======
                $('#sectionSelect').select2({
                    placeholder: '-- Select Section --',
                    width: '100%',
                    dropdownParent: $('#myModal')
                });

                $('#staffSelect').select2({
                    placeholder: '-- Select Staff --',
                    width: '100%',
                    dropdownParent: $('#myModal')
                });

                $('#staffSelect').on('change', function() {
                    const selectedOption = $(this).find('option:selected');

                    if ($(this).val()) {
                        $('#fName').val(selectedOption.data('fname') || '');
                        $('#mName').val(selectedOption.data('mname') || '');
                        $('#lName').val(selectedOption.data('lname') || '');
                        $('#email').val(selectedOption.data('email') || '');
                    } else {
                        $('#fName').val('');
                        $('#mName').val('');
                        $('#lName').val('');
                        $('#email').val('');
                    }
>>>>>>> d90f53e4bc54e1a6cb3ae582a18d8d166823d3e4
                });

                $(document).on('change', '.js-manage-select', function () {
                    const $select = $(this);
                    const action = $select.val();
                    const username = $select.data('username');
                    const toggleUrl = $select.data('toggle-url');
                    const resetUrl = $select.data('reset-url');
                    const deleteUrl = $select.data('delete-url');
                    const editTarget = $select.data('edit-target');
                    const passwordTarget = $select.data('password-target');

                    const resetSelect = function () {
                        $select.prop('selectedIndex', 0);
                    };

                    if (!action) {
                        return;
                    }

                    if (action === 'toggle') {
                        const toggleLabel = $select.find('option:selected').text();
                        if (window.confirm('Are you sure you want to ' + toggleLabel.toLowerCase() + ' for ' + username + '?')) {
                            window.location.href = toggleUrl;
                            return;
                        }
                    }

                    if (action === 'reset') {
                        if (window.confirm('Are you sure you want to reset password to default (123456) for ' + username + '?')) {
                            window.location.href = resetUrl;
                            return;
                        }
                    }

                    if (action === 'password') {
                        $(passwordTarget).modal('show');
                    }

                    if (action === 'edit') {
                        $(editTarget).modal('show');
                    }

                    if (action === 'delete') {
                        if (window.confirm('Are you sure you want to delete the account for ' + username + '?')) {
                            window.location.href = deleteUrl;
                            return;
                        }
                    }

                    resetSelect();
                });
            });
        </script>
    </body>
</html>
