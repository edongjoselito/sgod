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
        <link href="<?= base_url(); ?>assets/libs/select2/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />

        <style>
            :root {
                --sections-navy: #16324f;
                --sections-blue: #2d7ff9;
                --sections-teal: #0f9f9a;
                --sections-gold: #efb54a;
                --sections-ink: #22384d;
                --sections-muted: #72859a;
                --sections-border: rgba(22, 50, 79, 0.12);
                --sections-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            }

            body {
                background:
                    radial-gradient(circle at top left, rgba(45, 127, 249, 0.10), transparent 24%),
                    linear-gradient(180deg, #f4f8fc 0%, #eef4fa 100%);
            }

            .content-page {
                background: transparent;
            }

            .sections-shell {
                position: relative;
                padding-bottom: 28px;
            }

            .sections-shell::before {
                content: "";
                position: absolute;
                inset: 24px 0 auto;
                height: 240px;
                border-radius: 30px;
                background: linear-gradient(135deg, rgba(45, 127, 249, 0.09), rgba(15, 159, 154, 0.08));
                z-index: 0;
            }

            .sections-shell > * {
                position: relative;
                z-index: 1;
            }

            .sections-hero {
                margin-top: 20px;
                border-radius: 28px;
                overflow: hidden;
                color: #ffffff;
                box-shadow: var(--sections-shadow);
                background:
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 32%),
                    linear-gradient(135deg, #16324f 0%, #1f5fa8 58%, #0f9f9a 100%);
            }

            .sections-hero-body {
                padding: 32px;
            }

            .sections-eyebrow {
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

            .sections-title {
                margin: 18px 0 12px;
                color: #ffffff;
                font-size: clamp(2rem, 3vw, 2.7rem);
                line-height: 1.05;
                font-weight: 700;
                letter-spacing: -0.03em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .sections-copy {
                max-width: 640px;
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
                border-radius: 22px;
                background: rgba(255, 255, 255, 0.12);
                border: 1px solid rgba(255, 255, 255, 0.18);
                backdrop-filter: blur(14px);
            }

            .hero-side-label {
                color: rgba(255, 255, 255, 0.72);
                font-size: 0.8rem;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .hero-side-value {
                margin: 12px 0 8px;
                color: #ffffff;
                font-size: 2.1rem;
                line-height: 1;
                font-weight: 700;
                letter-spacing: -0.04em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .hero-side-copy {
                color: rgba(255, 255, 255, 0.82);
                line-height: 1.65;
                margin-bottom: 22px;
            }

            .hero-identifier {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 10px 14px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.14);
                border: 1px solid rgba(255, 255, 255, 0.18);
                font-weight: 600;
            }

            .hero-add-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                width: 100%;
                margin-top: 22px;
                padding: 13px 18px;
                border: none;
                border-radius: 16px;
                color: #ffffff;
                background: linear-gradient(135deg, #16324f 0%, #1f5fa8 58%, #0f9f9a 100%);
                font-weight: 700;
                transition: transform 0.25s ease, box-shadow 0.25s ease;
            }

            .hero-add-btn:hover {
                color: #ffffff;
                transform: translateY(-2px);
                box-shadow: 0 14px 32px rgba(17, 24, 39, 0.12);
            }

            .metric-card {
                height: 100%;
                border: none;
                border-radius: 22px;
                background: rgba(255, 255, 255, 0.95);
                box-shadow: var(--sections-shadow);
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
                color: var(--sections-muted);
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.05em;
                text-transform: uppercase;
            }

            .metric-value {
                margin-bottom: 8px;
                color: var(--sections-ink);
                font-size: 2.2rem;
                line-height: 1;
                font-weight: 700;
                letter-spacing: -0.04em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .metric-label {
                color: var(--sections-ink);
                font-size: 1rem;
                font-weight: 700;
                margin-bottom: 8px;
            }

            .metric-note {
                color: var(--sections-muted);
                line-height: 1.6;
                margin-bottom: 0;
            }

            .sections-alert {
                border: none;
                border-radius: 18px;
                box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
            }

            .table-panel {
                border: 1px solid var(--sections-border);
                border-radius: 24px;
                background: rgba(255, 255, 255, 0.96);
                box-shadow: var(--sections-shadow);
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
                color: var(--sections-ink);
                font-size: 1.35rem;
                font-weight: 700;
                letter-spacing: -0.02em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .table-panel-copy {
                margin-bottom: 0;
                color: var(--sections-muted);
                line-height: 1.6;
            }

            .table-shell {
                padding: 20px 26px 26px;
            }

            .sections-table {
                margin-bottom: 0;
                border-collapse: separate;
                border-spacing: 0 12px;
            }

            .sections-table thead th {
                border: none;
                color: var(--sections-muted);
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                padding: 0 18px 8px;
            }

            .sections-table tbody tr {
                background: linear-gradient(180deg, #ffffff 0%, #f9fbfd 100%);
                box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
            }

            .sections-table tbody td {
                border: none;
                vertical-align: middle;
                padding: 18px;
                color: var(--sections-ink);
            }

            .sections-table tbody td:first-child {
                border-radius: 18px 0 0 18px;
            }

            .sections-table tbody td:last-child {
                border-radius: 0 18px 18px 0;
            }

            .section-name {
                font-weight: 700;
                letter-spacing: -0.01em;
            }

            .section-chip {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 7px 12px;
                border-radius: 999px;
                background: rgba(45, 127, 249, 0.10);
                color: var(--sections-blue);
                font-size: 0.82rem;
                font-weight: 700;
            }

            .section-subtext {
                display: block;
                margin-top: 6px;
                color: var(--sections-muted);
                font-size: 0.88rem;
            }

            .manage-actions {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                justify-content: center;
            }

            .action-btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 9px 14px;
                border-radius: 999px;
                font-weight: 700;
                font-size: 0.88rem;
                text-decoration: none;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .action-btn:hover {
                text-decoration: none;
                transform: translateY(-2px);
            }

            .action-btn-edit {
                color: #0d7a67;
                background: rgba(15, 159, 154, 0.12);
            }

            .action-btn-delete {
                color: #cc425d;
                background: rgba(204, 66, 93, 0.12);
            }

            .empty-state {
                text-align: center;
                padding: 38px 24px;
            }

            .empty-state i {
                font-size: 2.2rem;
                color: var(--sections-blue);
                margin-bottom: 12px;
            }

            .empty-state h5 {
                color: var(--sections-ink);
                font-weight: 700;
                margin-bottom: 8px;
            }

            .empty-state p {
                color: var(--sections-muted);
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
                background: linear-gradient(135deg, #16324f 0%, #1f5fa8 58%, #0f9f9a 100%);
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
                border: 1px solid rgba(22, 50, 79, 0.08);
                border-radius: 22px;
                background: #ffffff;
                padding: 24px;
            }

            .modern-label {
                color: var(--sections-ink);
                font-weight: 700;
                margin-bottom: 8px;
            }

            .modern-input {
                min-height: 48px;
                border-radius: 14px;
                border: 1px solid rgba(22, 50, 79, 0.14);
                padding: 12px 14px;
                box-shadow: none;
            }

            .modern-input:focus {
                border-color: var(--sections-blue);
                box-shadow: 0 0 0 0.18rem rgba(45, 127, 249, 0.14);
            }

            .select2-container {
                width: 100% !important;
            }

            .select2-container--default .select2-selection--single {
                min-height: 48px;
                border-radius: 14px;
                border: 1px solid rgba(22, 50, 79, 0.14);
                padding: 9px 14px;
            }

            .select2-container--default .select2-selection--multiple {
                min-height: 48px;
                border-radius: 14px;
                border: 1px solid rgba(22, 50, 79, 0.14);
                padding: 6px 10px;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 28px;
                padding-left: 0;
                color: var(--sections-ink);
            }

            .select2-container--default .select2-selection--multiple .select2-selection__rendered {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
                padding: 0;
            }

            .select2-container--default .select2-selection--multiple .select2-selection__choice {
                margin-top: 0;
                border: none;
                border-radius: 999px;
                padding: 5px 10px;
                background: rgba(45, 127, 249, 0.12);
                color: var(--sections-ink);
                font-weight: 600;
            }

            .select2-container--default .select2-selection--multiple .select2-search--inline .select2-search__field {
                margin-top: 0;
                min-height: 28px;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 46px;
                right: 10px;
            }

            .select2-container--default.select2-container--focus .select2-selection--single,
            .select2-container--default.select2-container--focus .select2-selection--multiple,
            .select2-container--default.select2-container--open .select2-selection--multiple,
            .select2-container--default.select2-container--open .select2-selection--single {
                border-color: var(--sections-blue);
                box-shadow: 0 0 0 0.18rem rgba(45, 127, 249, 0.14);
            }

            .member-list {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
            }

            .member-chip {
                display: inline-flex;
                align-items: center;
                border-radius: 999px;
                padding: 6px 10px;
                background: #edf5ff;
                color: var(--sections-ink);
                font-weight: 600;
            }

            .modal-footer-modern {
                display: flex;
                justify-content: flex-end;
                gap: 12px;
                padding-top: 16px;
            }

            .btn-soft-dark {
                color: var(--sections-ink);
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
                background: linear-gradient(135deg, #2d7ff9 0%, #0f9f9a 100%);
                box-shadow: 0 14px 30px rgba(45, 127, 249, 0.18);
            }

            .btn-gradient-primary:hover {
                color: #ffffff;
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
                .sections-hero-body,
                .table-shell,
                .modern-modal-body {
                    padding: 24px;
                }

                .table-panel-head {
                    flex-direction: column;
                    align-items: flex-start;
                    padding: 24px 24px 0;
                }
            }

            @media (max-width: 767.98px) {
                .sections-table thead {
                    display: none;
                }

                .sections-table,
                .sections-table tbody,
                .sections-table tr,
                .sections-table td {
                    display: block;
                    width: 100%;
                }

                .sections-table tbody tr {
                    margin-bottom: 14px;
                    border-radius: 18px;
                    overflow: hidden;
                }

                .sections-table tbody td {
                    padding: 14px 18px;
                    border-radius: 0 !important;
                }

                .sections-table tbody td::before {
                    content: attr(data-label);
                    display: block;
                    margin-bottom: 6px;
                    color: var(--sections-muted);
                    font-size: 0.76rem;
                    font-weight: 700;
                    letter-spacing: 0.08em;
                    text-transform: uppercase;
                }

                .manage-actions {
                    justify-content: flex-start;
                    flex-wrap: wrap;
                }

                .sections-hero-body {
                    padding: 22px;
                }

                .sections-title {
                    font-size: 1.9rem;
                }
            }
        </style>
    </head>

    <body>

        <?php
            $identifier = $this->session->userdata('secGroup');
            $totalSections = count($data);
            $sectionsWithHeads = 0;
            $withPosition = 0;
            $staffOptions = isset($staffOptions) && is_array($staffOptions) ? $staffOptions : [];
            $staffDirectory = [];
            $staffMemberLookup = [];
            $parseSectionMembers = static function ($memberValue) {
                $memberValue = trim((string) $memberValue);
                if ($memberValue === '') {
                    return [];
                }

                if (strpos($memberValue, ';') !== false) {
                    $rawMembers = explode(';', $memberValue);
                } elseif (preg_match('/\R/', $memberValue)) {
                    $rawMembers = preg_split('/\R+/', $memberValue);
                } else {
                    $rawMembers = [$memberValue];
                }

                $members = [];
                $memberIndex = [];

                foreach ($rawMembers as $rawMember) {
                    $normalizedMember = trim(preg_replace('/\s+/', ' ', (string) $rawMember));
                    if ($normalizedMember === '') {
                        continue;
                    }

                    $memberKey = strtolower($normalizedMember);
                    if (isset($memberIndex[$memberKey])) {
                        continue;
                    }

                    $memberIndex[$memberKey] = true;
                    $members[] = $normalizedMember;
                }

                return $members;
            };
            $selectedSectionHead = $this->input->post('sectionHead') ? trim((string) $this->input->post('sectionHead')) : '';
            $selectedSectionHeadPosition = $this->input->post('sectionHeadPosition') ? (string) $this->input->post('sectionHeadPosition') : '';
            $selectedMembers = $this->input->post('member');
            $selectedMembers = is_array($selectedMembers) ? $selectedMembers : $parseSectionMembers($selectedMembers);
            $selectedMembersLookup = [];

            foreach ($selectedMembers as $selectedMember) {
                $normalizedSelectedMember = trim(preg_replace('/\s+/', ' ', (string) $selectedMember));
                if ($normalizedSelectedMember === '') {
                    continue;
                }

                $selectedMembersLookup[$normalizedSelectedMember] = true;
            }

            foreach ($staffOptions as $staffRow) {
                $staffId = trim((string) $staffRow->IDNumber);
                if ($staffId === '') {
                    continue;
                }

                $displayName = trim(preg_replace('/\s+/', ' ', implode(' ', array_filter([
                    trim((string) $staffRow->LastName) !== '' ? trim((string) $staffRow->LastName) . ',' : '',
                    trim((string) $staffRow->FirstName),
                    trim((string) $staffRow->MiddleName),
                    trim((string) $staffRow->NameExtn),
                ]))));
                $memberLabel = trim(preg_replace('/\s+/', ' ', implode(' ', array_filter([
                    trim((string) $staffRow->FirstName),
                    trim((string) $staffRow->MiddleName),
                    trim((string) $staffRow->LastName),
                    trim((string) $staffRow->NameExtn),
                ]))));

                if ($memberLabel === '') {
                    $memberLabel = str_replace(',', '', $displayName);
                }

                if ($memberLabel === '') {
                    $memberLabel = $staffId;
                }

                $memberLabel .= ' (' . $staffId . ')';

                $staffDirectory[$staffId] = [
                    'name' => $displayName,
                    'position' => trim((string) ($staffRow->empPosition !== '' ? $staffRow->empPosition : $staffRow->jobTitle)),
                    'member_label' => $memberLabel,
                ];
                $staffMemberLookup[$memberLabel] = true;
            }

            foreach ($data as $row) {
                if (trim((string) $row->sectionHead) !== '') {
                    $sectionsWithHeads++;
                }

                if (trim((string) $row->sectionHeadPosition) !== '') {
                    $withPosition++;
                }
            }

            $metrics = [
                [
                    'label' => 'Total Sections',
                    'value' => $totalSections,
                    'badge' => 'Registry',
                    'note' => 'Current section entries managed under this department identifier.',
                    'icon' => 'mdi mdi-view-grid-plus-outline',
                    'accent' => '#2d7ff9',
                    'soft' => 'rgba(45, 127, 249, 0.12)',
                ],
                [
                    'label' => 'With Section Head',
                    'value' => $sectionsWithHeads,
                    'badge' => 'Leadership',
                    'note' => 'Sections that already have a named lead assigned.',
                    'icon' => 'mdi mdi-account-tie-outline',
                    'accent' => '#0f9f9a',
                    'soft' => 'rgba(15, 159, 154, 0.12)',
                ],
                [
                    'label' => 'With Position',
                    'value' => $withPosition,
                    'badge' => 'Profile',
                    'note' => 'Section records with position details completed.',
                    'icon' => 'mdi mdi-briefcase-outline',
                    'accent' => '#efb54a',
                    'soft' => 'rgba(239, 181, 74, 0.16)',
                ],
            ];
        ?>

        <div id="wrapper">
            <?php include('includes/top-bar.php'); ?>
            <?php include('includes/sidebar.php') ?>

            <div class="content-page">
                <div class="content">
                    <div class="container-fluid sections-shell">
                        <div class="row">
                            <div class="col-12">
                                <div class="sections-hero">
                                    <div class="sections-hero-body">
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <span class="sections-eyebrow">
                                                    <i class="mdi mdi-sitemap-outline"></i>
                                                    Department Section Registry
                                                </span>
                                                <h1 class="sections-title">Manage Sections</h1>
                                                <button type="button" class="hero-add-btn" data-toggle="modal" data-target=".bs-example-modal-lg">
                                                    <i class="mdi mdi-plus-circle-outline"></i>
                                                    Add New Section
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if($this->session->flashdata('success')) : ?>
                            <div class="alert alert-success alert-dismissible fade show sections-alert mt-4" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <?= $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if($this->session->flashdata('danger')) : ?>
                            <div class="alert alert-danger alert-dismissible fade show sections-alert mt-4" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <?= $this->session->flashdata('danger'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="row mt-4">
                            <?php foreach ($metrics as $index => $metric) { ?>
                                <div class="col-xl-4 col-md-6 mb-4">
                                    <div class="metric-card" style="--accent: <?= $metric['accent']; ?>; animation-delay: <?= number_format(($index + 1) * 0.08, 2, '.', ''); ?>s;">
                                        <div class="metric-card-body">
                                            <div class="metric-head">
                                                <span class="metric-icon" style="background: <?= $metric['soft']; ?>; color: <?= $metric['accent']; ?>;">
                                                    <i class="<?= $metric['icon']; ?>"></i>
                                                </span>
                                                <span class="metric-badge"><?= $metric['badge']; ?></span>
                                            </div>
                                            <h2 class="metric-value"><?= $metric['value']; ?></h2>
                                            <div class="metric-label"><?= $metric['label']; ?></div>
                                            <p class="metric-note"><?= $metric['note']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="table-panel">
                            <div class="table-panel-head">
                                <div>
                                    <h4 class="table-panel-title">Sections</h4>
                                    <p class="table-panel-copy">
                                        A modern view of the current section registry under the <?= htmlspecialchars($identifier, ENT_QUOTES, 'UTF-8'); ?> identifier.
                                    </p>
                                </div>
                            </div>

                            <div class="table-shell">
                                <div class="table-responsive">
                                    <table class="table sections-table">
                                        <thead>
                                            <tr>
                                                <th>Section</th>
                                                <th>Section Head</th>
                                                <th>Position</th>
                                                <th>Members</th>
                                                <th class="text-center">Manage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($data)) { ?>
                                                <?php foreach($data as $row) { ?>
                                                    <?php
                                                        $sectionHeadKey = trim((string) $row->sectionHead);
                                                        $sectionHeadProfile = isset($staffDirectory[$sectionHeadKey]) ? $staffDirectory[$sectionHeadKey] : null;
                                                        $sectionHeadLabel = $sectionHeadProfile ? $sectionHeadProfile['name'] : $sectionHeadKey;
                                                        $sectionHeadPositionLabel = trim((string) $row->sectionHeadPosition) !== ''
                                                            ? trim((string) $row->sectionHeadPosition)
                                                            : ($sectionHeadProfile ? $sectionHeadProfile['position'] : '');
                                                        $sectionMembers = $parseSectionMembers($row->member);
                                                    ?>
                                                    <tr>
                                                        <td data-label="Section">
                                                            <div class="section-name"><?= htmlspecialchars($row->sectionName, ENT_QUOTES, 'UTF-8'); ?></div>
                                                            <span class="section-subtext">Scoped to <?= htmlspecialchars($row->secGroup, ENT_QUOTES, 'UTF-8'); ?></span>
                                                        </td>
                                                        <td data-label="Section Head">
                                                            <?php if($sectionHeadKey !== '') { ?>
                                                                <span class="section-chip">
                                                                    <i class="mdi mdi-account-outline"></i>
                                                                    <?= htmlspecialchars($sectionHeadLabel, ENT_QUOTES, 'UTF-8'); ?>
                                                                </span>
                                                                <?php if($sectionHeadProfile) { ?>
                                                                    <span class="section-subtext mt-2">ID: <?= htmlspecialchars($sectionHeadKey, ENT_QUOTES, 'UTF-8'); ?></span>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <span class="section-subtext mt-0">No section head assigned yet.</span>
                                                            <?php } ?>
                                                        </td>
                                                        <td data-label="Position">
                                                            <?php if($sectionHeadPositionLabel !== '') { ?>
                                                                <?= htmlspecialchars($sectionHeadPositionLabel, ENT_QUOTES, 'UTF-8'); ?>
                                                            <?php } else { ?>
                                                                <span class="section-subtext mt-0">Position not set.</span>
                                                            <?php } ?>
                                                        </td>
                                                        <td data-label="Members">
                                                            <?php if (!empty($sectionMembers)) { ?>
                                                                <div class="member-list">
                                                                    <?php foreach ($sectionMembers as $sectionMember) { ?>
                                                                        <span class="member-chip"><?= htmlspecialchars($sectionMember, ENT_QUOTES, 'UTF-8'); ?></span>
                                                                    <?php } ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                <span class="section-subtext mt-0">No members added yet.</span>
                                                            <?php } ?>
                                                        </td>
                                                        <td data-label="Manage" class="text-center">
                                                            <div class="manage-actions">
                                                                <a href="<?= base_url(); ?>page/sections_edit/<?= $row->id; ?>" class="action-btn action-btn-edit">
                                                                    <i class="mdi mdi-pencil-outline"></i>
                                                                    Edit
                                                                </a>
                                                                <a href="delete_sec?id=<?= $row->id; ?>" class="action-btn action-btn-delete" onclick="return confirm('Are you sure you want to delete this section?')">
                                                                    <i class="mdi mdi-trash-can-outline"></i>
                                                                    Delete
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan="5">
                                                        <div class="empty-state">
                                                            <i class="mdi mdi-folder-open-outline d-block"></i>
                                                            <h5>No sections yet</h5>
                                                            <p>Create the first section for this department using the button above.</p>
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
                </div>

                <?php include('includes/footer.php'); ?>
            </div>
        </div>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/select2/select2.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
        <script>
            $(function () {
                const sectionHeadSelect = $('#sectionHeadSelect');
                const sectionHeadPositionInput = $('#sectionHeadPositionInput');
                const sectionMembersSelect = $('#sectionMembersSelect');

                sectionHeadSelect.select2({
                    placeholder: 'Select section head',
                    width: '100%',
                    dropdownParent: $('.bs-example-modal-lg')
                });

                sectionMembersSelect.select2({
                    placeholder: 'Select or add members',
                    width: '100%',
                    dropdownParent: $('.bs-example-modal-lg'),
                    closeOnSelect: false,
                    tags: true
                });

                function syncSectionHeadPosition() {
                    const selectedOption = sectionHeadSelect.find('option:selected');
                    const selectedPosition = selectedOption.data('position') || '';
                    if (selectedPosition !== '') {
                        sectionHeadPositionInput.val(selectedPosition);
                    }
                }

                sectionHeadSelect.on('change', syncSectionHeadPosition);

                if (sectionHeadSelect.val()) {
                    syncSectionHeadPosition();
                }
            });
        </script>

        <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="addSectionModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modern-modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h5 class="modern-modal-title" id="addSectionModalLabel">Add New Section</h5>
                        <p class="modern-modal-copy">Create a new section record for the <?= htmlspecialchars($identifier, ENT_QUOTES, 'UTF-8'); ?> identifier.</p>
                    </div>

                    <div class="modern-modal-body">
                        <div class="form-card">
                            <form method="post">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="modern-label">Section <span class="text-danger">*</span></label>
                                        <input type="text" name="sectionName" required class="form-control modern-input">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="modern-label">Section Head <span class="text-danger">*</span></label>
                                        <select name="sectionHead" id="sectionHeadSelect" required class="form-control modern-input">
                                            <option value="">Select section head</option>
                                            <?php foreach ($staffDirectory as $staffId => $staffProfile) { ?>
                                                <?php $staffIdValue = (string) $staffId; ?>
                                                <option value="<?= htmlspecialchars($staffIdValue, ENT_QUOTES, 'UTF-8'); ?>" data-position="<?= htmlspecialchars($staffProfile['position'], ENT_QUOTES, 'UTF-8'); ?>" <?= $selectedSectionHead === $staffIdValue ? 'selected' : ''; ?>>
                                                    <?= htmlspecialchars($staffProfile['name'] . ' (' . $staffIdValue . ')', ENT_QUOTES, 'UTF-8'); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="modern-label">Position <span class="text-danger">*</span></label>
                                        <input type="text" id="sectionHeadPositionInput" name="sectionHeadPosition" required class="form-control modern-input" value="<?= htmlspecialchars($selectedSectionHeadPosition, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="modern-label">Section Group <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control modern-input" value="<?= htmlspecialchars($identifier, ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                        <input type="hidden" name="secGroup" value="<?= htmlspecialchars($identifier, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="modern-label">Members</label>
                                    <select name="member[]" id="sectionMembersSelect" class="form-control modern-input" multiple>
                                        <?php foreach ($staffDirectory as $staffProfile) { ?>
                                            <option value="<?= htmlspecialchars($staffProfile['member_label'], ENT_QUOTES, 'UTF-8'); ?>" <?= isset($selectedMembersLookup[$staffProfile['member_label']]) ? 'selected' : ''; ?>>
                                                <?= htmlspecialchars($staffProfile['member_label'], ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                        <?php } ?>
                                        <?php foreach ($selectedMembers as $selectedMember) { ?>
                                            <?php if (!isset($staffMemberLookup[$selectedMember])) { ?>
                                                <option value="<?= htmlspecialchars($selectedMember, ENT_QUOTES, 'UTF-8'); ?>" selected>
                                                    <?= htmlspecialchars($selectedMember, ENT_QUOTES, 'UTF-8'); ?>
                                                </option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="modal-footer-modern">
                                    <button type="button" class="btn btn-soft-dark" data-dismiss="modal">Cancel</button>
                                    <input type="submit" name="submit" value="Save Section" class="btn btn-gradient-primary">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
