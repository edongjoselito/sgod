<?php
$requestedType = $this->input->get('type') ? trim((string) $this->input->get('type')) : 'All';
$requestedTypeLower = strtolower($requestedType);
$activeDirectoryFilter = in_array($requestedTypeLower, array('public', 'private'), true) ? ucfirst($requestedTypeLower) : 'All';
$records = is_array($data) ? $data : array();

if (!function_exists('schools_escape')) {
    function schools_escape($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('schools_join_parts')) {
    function schools_join_parts($parts, $separator = ', ')
    {
        $filteredParts = array_values(array_filter(array_map(static function ($part) {
            return trim((string) $part);
        }, $parts), static function ($part) {
            return $part !== '';
        }));

        return implode($separator, $filteredParts);
    }
}

if (!function_exists('schools_normalize_type')) {
    function schools_normalize_type($value)
    {
        $type = trim((string) $value);
        if (strcasecmp($type, 'Public') === 0) {
            return 'Public';
        }
        if (strcasecmp($type, 'Private') === 0) {
            return 'Private';
        }

        return 'Other';
    }
}

$districtMap = array();
$totalSchools = count($records);
$publicSchoolCount = 0;
$privateSchoolCount = 0;

foreach ($records as $schoolRow) {
    $schoolType = schools_normalize_type($schoolRow->schoolType ?? '');
    if ($schoolType === 'Public') {
        $publicSchoolCount++;
    } elseif ($schoolType === 'Private') {
        $privateSchoolCount++;
    }

    $district = trim((string) ($schoolRow->district ?? ''));
    if ($district !== '') {
        $districtMap[$district] = true;
    }
}

$districtCount = count($districtMap);
$metricCards = array(
    array(
        'value' => number_format($totalSchools),
        'label' => 'All Schools',
        'icon' => 'mdi-school-outline',
        'icon_bg' => 'rgba(37, 99, 235, 0.12)',
        'icon_color' => '#2563eb',
        'badge' => 'Directory'
    ),
    array(
        'value' => number_format($publicSchoolCount),
        'label' => 'Public Schools',
        'icon' => 'mdi-domain',
        'icon_bg' => 'rgba(15, 118, 110, 0.14)',
        'icon_color' => '#0f766e',
        'badge' => 'Public'
    ),
    array(
        'value' => number_format($privateSchoolCount),
        'label' => 'Private Schools',
        'icon' => 'mdi-office-building',
        'icon_bg' => 'rgba(124, 58, 237, 0.14)',
        'icon_color' => '#7c3aed',
        'badge' => 'Private'
    ),
    array(
        'value' => number_format($districtCount),
        'label' => 'Districts Covered',
        'icon' => 'mdi-map-marker-radius-outline',
        'icon_bg' => 'rgba(234, 88, 12, 0.14)',
        'icon_color' => '#ea580c',
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

        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />
        <link href="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <style>
            :root {
                --schools-navy: #272b8c;
                --schools-blue: #3c40c6;
                --schools-teal: #565de8;
                --schools-ink: #23275d;
                --schools-muted: #7b7fa7;
                --schools-border: rgba(60, 64, 198, 0.12);
                --schools-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
                --schools-surface: rgba(255, 255, 255, 0.96);
            }

            body {
                background:
                    radial-gradient(circle at top left, rgba(60, 64, 198, 0.10), transparent 24%),
                    linear-gradient(180deg, #f4f8fc 0%, #eef4fa 100%);
            }

            .content-page {
                background: transparent;
            }

            .schools-shell {
                position: relative;
                padding-bottom: 28px;
            }

            .schools-shell::before {
                content: "";
                position: absolute;
                inset: 24px 0 auto;
                height: 240px;
                border-radius: 30px;
                background: linear-gradient(135deg, rgba(45, 127, 249, 0.09), rgba(15, 159, 154, 0.08));
                z-index: 0;
            }

            .schools-shell > * {
                position: relative;
                z-index: 1;
            }

            .schools-hero {
                margin-top: 20px;
                margin-bottom: 24px;
                border-radius: 28px;
                overflow: hidden;
                color: #ffffff;
                box-shadow: var(--schools-shadow);
                background:
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 32%),
                    linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
            }

            .schools-hero-body {
                padding: 32px;
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 20px;
                flex-wrap: wrap;
            }

            .schools-eyebrow {
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

            .schools-title {
                margin: 18px 0 12px;
                color: #ffffff;
                font-size: clamp(2rem, 3vw, 2.7rem);
                line-height: 1.05;
                font-weight: 700;
                letter-spacing: -0.03em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .hero-actions {
                min-width: 280px;
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .hero-actions-copy {
                color: rgba(255, 255, 255, 0.78);
                font-size: 0.9rem;
                line-height: 1.6;
                margin-bottom: 0;
            }

            .directory-filter-group {
                display: inline-flex;
                flex-wrap: wrap;
                gap: 10px;
            }

            .directory-filter-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                min-height: 46px;
                padding: 11px 16px;
                border-radius: 999px;
                border: 1px solid rgba(255, 255, 255, 0.24);
                background: rgba(255, 255, 255, 0.10);
                color: #ffffff;
                font-size: 0.9rem;
                font-weight: 700;
                transition: transform 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
            }

            .directory-filter-btn:hover,
            .directory-filter-btn:focus {
                outline: none;
                color: #ffffff;
                background: rgba(255, 255, 255, 0.18);
                transform: translateY(-1px);
            }

            .directory-filter-btn.is-active {
                background: #ffffff;
                color: var(--schools-blue);
                box-shadow: 0 16px 30px rgba(15, 23, 42, 0.16);
            }

            .metric-card {
                height: 100%;
                border: 1px solid rgba(60, 64, 198, 0.08);
                border-radius: 22px;
                background: rgba(255, 255, 255, 0.95);
                padding: 24px;
                box-shadow: var(--schools-shadow);
                overflow: hidden;
                position: relative;
            }

            .metric-card::before {
                content: "";
                position: absolute;
                inset: 0;
                opacity: 0.04;
                background: linear-gradient(135deg, var(--schools-blue), var(--schools-teal));
                z-index: 0;
            }

            .metric-body {
                position: relative;
                z-index: 1;
            }

            .metric-head {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 12px;
                margin-bottom: 16px;
            }

            .metric-icon {
                width: 52px;
                height: 52px;
                border-radius: 16px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 26px;
            }

            .metric-badge {
                display: inline-flex;
                align-items: center;
                padding: 7px 12px;
                border-radius: 999px;
                background: rgba(60, 64, 198, 0.08);
                color: var(--schools-blue);
                font-size: 0.73rem;
                font-weight: 700;
                letter-spacing: 0.04em;
                text-transform: uppercase;
            }

            .metric-value {
                font-size: 2.4rem;
                font-weight: 700;
                color: var(--schools-ink);
                line-height: 1;
                margin-bottom: 8px;
            }

            .metric-label {
                color: var(--schools-muted);
                font-size: 0.96rem;
                font-weight: 600;
                margin-bottom: 0;
            }

            .panel-card {
                border-radius: 24px;
                border: 1px solid rgba(60, 64, 198, 0.08);
                background: var(--schools-surface);
                box-shadow: var(--schools-shadow);
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
                color: var(--schools-blue);
                font-size: 0.76rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .panel-title {
                margin: 16px 0 8px;
                color: var(--schools-ink);
                font-size: 1.45rem;
                font-weight: 700;
                line-height: 1.2;
            }

            .panel-copy {
                color: var(--schools-muted);
                font-size: 0.95rem;
                line-height: 1.6;
                margin-bottom: 0;
            }

            .panel-controls {
                display: flex;
                flex-wrap: wrap;
                justify-content: flex-end;
                gap: 12px;
            }

            .panel-summary {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                padding: 12px 16px;
                border-radius: 18px;
                background: linear-gradient(180deg, #f8faff 0%, #eef4ff 100%);
                border: 1px solid rgba(60, 64, 198, 0.08);
                color: var(--schools-ink);
                font-weight: 600;
            }

            .directory-filter-group--panel .directory-filter-btn {
                min-height: 42px;
                border-color: rgba(60, 64, 198, 0.12);
                background: rgba(60, 64, 198, 0.05);
                color: var(--schools-blue);
            }

            .directory-filter-group--panel .directory-filter-btn:hover,
            .directory-filter-group--panel .directory-filter-btn:focus {
                background: rgba(60, 64, 198, 0.10);
                color: var(--schools-blue);
            }

            .directory-filter-group--panel .directory-filter-btn.is-active {
                background: linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
                color: #ffffff;
            }

            .table-responsive {
                overflow-x: auto;
            }

            .schools-table {
                width: 100% !important;
                margin: 0 !important;
            }

            .schools-table thead th {
                border-top: none;
                border-bottom: 1px solid rgba(60, 64, 198, 0.10);
                color: var(--schools-muted);
                font-size: 0.78rem;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                padding: 16px 14px;
                background: #f9fbff;
                vertical-align: middle;
            }

            .schools-table tbody td {
                border-top: 1px solid rgba(60, 64, 198, 0.08);
                padding: 16px 14px;
                vertical-align: top;
                color: var(--schools-ink);
                font-size: 0.92rem;
                line-height: 1.55;
            }

            .schools-table tbody tr:hover {
                background: rgba(60, 64, 198, 0.02);
            }

            .school-name {
                min-width: 240px;
            }

            .school-name strong {
                display: block;
                color: var(--schools-ink);
                font-size: 0.98rem;
                font-weight: 700;
                margin-bottom: 6px;
            }

            .school-id-chip,
            .district-chip,
            .reference-pill,
            .type-chip {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 12px;
                border-radius: 999px;
                font-size: 0.82rem;
                font-weight: 700;
            }

            .school-id-chip {
                background: rgba(60, 64, 198, 0.10);
                color: var(--schools-blue);
            }

            .district-chip {
                background: rgba(37, 99, 235, 0.10);
                color: #2563eb;
            }

            .reference-pill {
                background: rgba(15, 118, 110, 0.10);
                color: #0f766e;
            }

            .type-chip {
                justify-content: center;
                min-width: 86px;
            }

            .type-chip--public {
                background: rgba(15, 118, 110, 0.10);
                color: #0f766e;
            }

            .type-chip--private {
                background: rgba(124, 58, 237, 0.10);
                color: #7c3aed;
            }

            .type-chip--other {
                background: rgba(107, 114, 128, 0.12);
                color: #4b5563;
            }

            .table-muted {
                color: var(--schools-muted);
            }

            .action-link {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 10px 14px;
                border-radius: 12px;
                color: var(--schools-blue);
                background: rgba(60, 64, 198, 0.08);
                font-size: 0.84rem;
                font-weight: 700;
                text-decoration: none;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .action-link:hover,
            .action-link:focus {
                color: var(--schools-navy);
                text-decoration: none;
                transform: translateY(-1px);
                box-shadow: 0 10px 18px rgba(15, 23, 42, 0.10);
            }

            .empty-state {
                text-align: center;
                padding: 42px 24px;
            }

            .empty-state i {
                font-size: 2.4rem;
                color: var(--schools-blue);
                margin-bottom: 12px;
            }

            .empty-state h5 {
                color: var(--schools-ink);
                font-weight: 700;
                margin-bottom: 8px;
            }

            .empty-state p {
                color: var(--schools-muted);
                margin-bottom: 0;
            }

            .dataTables_wrapper .row:first-child {
                margin-bottom: 16px;
            }

            .dataTables_wrapper .dataTables_length label,
            .dataTables_wrapper .dataTables_filter label,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                color: var(--schools-muted);
                font-weight: 600;
            }

            .dataTables_wrapper .dataTables_filter input,
            .dataTables_wrapper .dataTables_length select {
                border: 1px solid rgba(60, 64, 198, 0.12);
                border-radius: 12px;
                background: #ffffff;
                color: var(--schools-ink);
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
                color: var(--schools-blue) !important;
            }

            @media (max-width: 991.98px) {
                .schools-hero-body,
                .panel-card,
                .metric-card {
                    padding: 22px;
                }
            }

            @media (max-width: 767.98px) {
                .schools-shell::before {
                    left: 12px;
                    right: 12px;
                    inset-block-start: 18px;
                    height: 220px;
                }

                .schools-hero,
                .panel-card,
                .metric-card {
                    border-radius: 22px;
                }

                .schools-title {
                    font-size: 2rem;
                }

                .hero-actions,
                .panel-controls {
                    width: 100%;
                }

                .directory-filter-group {
                    width: 100%;
                }

                .directory-filter-btn {
                    flex: 1 1 auto;
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
                    <div class="container-fluid schools-shell">
                        <div class="schools-hero">
                            <div class="schools-hero-body">
                                <div>
                                    <span class="schools-eyebrow">
                                        <i class="mdi mdi-school-outline"></i>
                                        Directory
                                    </span>
                                    <h1 class="schools-title">Schools Directory</h1>
                                </div>

                                <div class="hero-actions">
                                    <p class="hero-actions-copy">Choose a view without leaving the page.</p>
                                    <div class="directory-filter-group" id="heroDirectoryFilters">
                                        <button type="button" class="directory-filter-btn" data-filter="All">
                                            <i class="mdi mdi-view-grid-outline"></i>
                                            All
                                        </button>
                                        <button type="button" class="directory-filter-btn" data-filter="Public">
                                            <i class="mdi mdi-domain"></i>
                                            Public
                                        </button>
                                        <button type="button" class="directory-filter-btn" data-filter="Private">
                                            <i class="mdi mdi-office-building"></i>
                                            Private
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <?php foreach ($metricCards as $metric) { ?>
                                <div class="col-md-6 col-xl-3 mb-4">
                                    <div class="metric-card">
                                        <div class="metric-body">
                                            <div class="metric-head">
                                                <span class="metric-icon" style="background: <?= schools_escape($metric['icon_bg']); ?>; color: <?= schools_escape($metric['icon_color']); ?>;">
                                                    <i class="mdi <?= schools_escape($metric['icon']); ?>"></i>
                                                </span>
                                                <span class="metric-badge"><?= schools_escape($metric['badge']); ?></span>
                                            </div>
                                            <div class="metric-value"><?= schools_escape($metric['value']); ?></div>
                                            <div class="metric-label"><?= schools_escape($metric['label']); ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="panel-card">
                            <div class="panel-head">
                                <div>
                                    <div class="panel-kicker">School List</div>
                                    <h4 class="panel-title">All School Records</h4>
                                    <p class="panel-copy">Search by school name, type, district, address, or school head to quickly open a school dashboard.</p>
                                </div>
                                <div class="panel-controls">
                                    <div class="directory-filter-group directory-filter-group--panel" id="panelDirectoryFilters">
                                        <button type="button" class="directory-filter-btn" data-filter="All">All</button>
                                        <button type="button" class="directory-filter-btn" data-filter="Public">Public</button>
                                        <button type="button" class="directory-filter-btn" data-filter="Private">Private</button>
                                    </div>
                                    <div class="panel-summary">
                                        <i class="mdi mdi-filter-outline"></i>
                                        <span id="schoolsSummaryText"><?= number_format($totalSchools); ?> of <?= number_format($totalSchools); ?> schools</span>
                                        <span class="table-muted">|</span>
                                        <span id="schoolsFilterText">All schools</span>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="schoolsTable" class="table schools-table">
                                    <thead>
                                        <tr>
                                            <th>School</th>
                                            <th>Type</th>
                                            <th>District</th>
                                            <th>Address</th>
                                            <th>School Head</th>
                                            <th>Designation</th>
                                            <th>Permit No.</th>
                                            <th>Recognition No.</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($records)) { ?>
                                            <?php foreach ($records as $schoolRow) { ?>
                                                <?php
                                                $schoolName = trim((string) ($schoolRow->schoolName ?? ''));
                                                $schoolId = trim((string) ($schoolRow->schoolID ?? ''));
                                                $schoolType = schools_normalize_type($schoolRow->schoolType ?? '');
                                                $district = trim((string) ($schoolRow->district ?? ''));
                                                $address = schools_join_parts(array(
                                                    $schoolRow->sitio ?? '',
                                                    $schoolRow->brgy ?? '',
                                                    $schoolRow->city ?? '',
                                                    $schoolRow->province ?? ''
                                                ));
                                                $schoolHead = schools_join_parts(array(
                                                    $schoolRow->adminFName ?? '',
                                                    $schoolRow->adminMName ?? '',
                                                    $schoolRow->adminLName ?? ''
                                                ), ' ');
                                                $designation = trim((string) ($schoolRow->adminDesignation ?? ''));
                                                $permitNo = trim((string) ($schoolRow->permitNo ?? ''));
                                                $recognitionNo = trim((string) ($schoolRow->recogNo ?? ''));
                                                $typeClass = strtolower($schoolType);
                                                ?>
                                                <tr data-school-type="<?= schools_escape($schoolType); ?>">
                                                    <td class="school-name">
                                                        <strong><?= schools_escape($schoolName !== '' ? $schoolName : 'Unnamed School'); ?></strong>
                                                        <?php if ($schoolId !== '') { ?>
                                                            <span class="school-id-chip">
                                                                <i class="mdi mdi-pound"></i>
                                                                <?= schools_escape($schoolId); ?>
                                                            </span>
                                                        <?php } else { ?>
                                                            <span class="table-muted">No school ID listed</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <span class="type-chip type-chip--<?= schools_escape($typeClass); ?>">
                                                            <i class="mdi <?= $schoolType === 'Private' ? 'mdi-office-building' : ($schoolType === 'Public' ? 'mdi-domain' : 'mdi-help-circle-outline'); ?>"></i>
                                                            <?= schools_escape($schoolType); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if ($district !== '') { ?>
                                                            <span class="district-chip">
                                                                <i class="mdi mdi-map-marker"></i>
                                                                <?= schools_escape($district); ?>
                                                            </span>
                                                        <?php } else { ?>
                                                            <span class="table-muted">No district listed</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td><?= $address !== '' ? schools_escape($address) : '<span class="table-muted">No address listed</span>'; ?></td>
                                                    <td><?= $schoolHead !== '' ? schools_escape($schoolHead) : '<span class="table-muted">No school head listed</span>'; ?></td>
                                                    <td><?= $designation !== '' ? schools_escape($designation) : '<span class="table-muted">No designation listed</span>'; ?></td>
                                                    <td>
                                                        <?php if ($permitNo !== '') { ?>
                                                            <span class="reference-pill"><?= schools_escape($permitNo); ?></span>
                                                        <?php } else { ?>
                                                            <span class="table-muted">None</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($recognitionNo !== '') { ?>
                                                            <span class="reference-pill"><?= schools_escape($recognitionNo); ?></span>
                                                        <?php } else { ?>
                                                            <span class="table-muted">None</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?= base_url(); ?>Page/schoolDashoard?schoolid=<?= rawurlencode($schoolId); ?>" class="action-link">
                                                            <i class="mdi mdi-arrow-right-circle-outline"></i>
                                                            View Dashboard
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="9">
                                                    <div class="empty-state">
                                                        <i class="mdi mdi-school-outline"></i>
                                                        <h5>No schools found</h5>
                                                        <p>No school records are available in this directory view.</p>
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

        <?php include('includes/right-sidebar.php'); ?>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.responsive.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.js"></script>
        <script>
            $(function () {
                var totalSchools = <?= json_encode($totalSchools); ?>;
                var initialFilter = <?= json_encode($activeDirectoryFilter); ?>;
                var filterLabels = {
                    All: 'All schools',
                    Public: 'Public schools',
                    Private: 'Private schools'
                };

                var table = $('#schoolsTable').DataTable({
                    destroy: true,
                    autoWidth: false,
                    pageLength: 10,
                    responsive: true,
                    order: [[0, 'asc']],
                    columnDefs: [
                        {
                            targets: -1,
                            orderable: false,
                            searchable: false
                        }
                    ],
                    language: {
                        search: '',
                        searchPlaceholder: 'Search school, type, district, address, or head...',
                        lengthMenu: 'Show _MENU_ schools',
                        info: 'Showing _START_ to _END_ of _TOTAL_ schools',
                        infoEmpty: 'No school records available',
                        emptyTable: 'No school records found',
                        paginate: {
                            previous: '&lsaquo;',
                            next: '&rsaquo;'
                        }
                    }
                });

                function setActiveFilterButtons(filter) {
                    $('.directory-filter-btn').removeClass('is-active');
                    $('.directory-filter-btn[data-filter="' + filter + '"]').addClass('is-active');
                }

                function updateSummary(filter) {
                    var visibleCount = table.rows({search: 'applied'}).count();
                    $('#schoolsSummaryText').text(visibleCount + ' of ' + totalSchools + ' schools');
                    $('#schoolsFilterText').text(filterLabels[filter] || filterLabels.All);
                }

                function updateUrl(filter) {
                    if (!window.history || !window.history.replaceState || !window.URL) {
                        return;
                    }

                    var nextUrl = new URL(window.location.href);
                    if (filter === 'All') {
                        nextUrl.searchParams.delete('type');
                    } else {
                        nextUrl.searchParams.set('type', filter);
                    }

                    window.history.replaceState({}, '', nextUrl.toString());
                }

                function applyDirectoryFilter(filter) {
                    var safeFilter = filter === 'Public' || filter === 'Private' ? filter : 'All';
                    var regex = safeFilter === 'All' ? '' : '^' + safeFilter + '$';

                    table.column(1).search(regex, true, false).draw();
                    setActiveFilterButtons(safeFilter);
                    updateSummary(safeFilter);
                    updateUrl(safeFilter);
                }

                $('.directory-filter-btn').on('click', function () {
                    applyDirectoryFilter($(this).data('filter'));
                });

                table.on('draw', function () {
                    var activeButton = $('.directory-filter-btn.is-active').first().data('filter') || 'All';
                    updateSummary(activeButton);
                });

                applyDirectoryFilter(initialFilter);
            });
        </script>
    </body>
</html>
