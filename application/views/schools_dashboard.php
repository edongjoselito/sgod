<?php
$school = (!empty($data) && is_array($data)) ? $data[0] : null;

if (!function_exists('school_dashboard_escape')) {
    function school_dashboard_escape($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('school_dashboard_join')) {
    function school_dashboard_join($parts, $separator = ', ')
    {
        $filteredParts = array_values(array_filter(array_map(static function ($part) {
            return trim((string) $part);
        }, $parts), static function ($part) {
            return $part !== '';
        }));

        return implode($separator, $filteredParts);
    }
}

if (!function_exists('school_dashboard_value_markup')) {
    function school_dashboard_value_markup($value, $fallback = 'Not yet listed')
    {
        $text = trim((string) $value);
        if ($text === '') {
            return '<span class="school-muted-value">' . school_dashboard_escape($fallback) . '</span>';
        }

        return school_dashboard_escape($text);
    }
}

$schoolName = $school ? trim((string) ($school->schoolName ?? '')) : '';
$schoolId = $school ? trim((string) ($school->schoolID ?? '')) : '';
$stationCode = $school ? trim((string) ($school->stationCode ?? '')) : '';
$district = $school ? trim((string) ($school->district ?? '')) : '';
$schoolType = $school ? trim((string) ($school->schoolType ?? '')) : '';
$yearEstablished = $school ? trim((string) ($school->yearEstab ?? '')) : '';
$schoolEmail = $school ? trim((string) ($school->schoolEmail ?? '')) : '';
$adminDesignation = $school ? trim((string) ($school->adminDesignation ?? '')) : '';
$adminMobile = $school ? trim((string) ($school->adminMobile ?? '')) : '';
$adminTel = $school ? trim((string) ($school->adminTel ?? '')) : '';
$permitNo = $school ? trim((string) ($school->permitNo ?? '')) : '';
$recognitionNo = $school ? trim((string) ($school->recogNo ?? '')) : '';
$sitio = $school ? trim((string) ($school->sitio ?? '')) : '';
$barangay = $school ? trim((string) ($school->brgy ?? '')) : '';
$city = $school ? trim((string) ($school->city ?? '')) : '';
$province = $school ? trim((string) ($school->province ?? '')) : '';
$schoolHead = $school ? school_dashboard_join(array(
    $school->adminFName ?? '',
    $school->adminMName ?? '',
    $school->adminLName ?? ''
), ' ') : '';
$fullAddress = school_dashboard_join(array($sitio, $barangay, $city, $province));
$locationLine = school_dashboard_join(array($barangay, $city, $province));
$contactNumbers = school_dashboard_join(array($adminMobile, $adminTel), ' / ');
$backLink = base_url() . 'Page/schools';

$completenessFields = array(
    $schoolName,
    $schoolId,
    $stationCode,
    $district,
    $schoolType,
    $yearEstablished,
    $schoolEmail,
    $schoolHead,
    $adminDesignation,
    $adminMobile,
    $adminTel,
    $permitNo,
    $recognitionNo,
    $fullAddress
);
$filledFields = 0;
foreach ($completenessFields as $fieldValue) {
    if (trim((string) $fieldValue) !== '') {
        $filledFields++;
    }
}
$profileCompleteness = count($completenessFields) > 0 ? (int) round(($filledFields / count($completenessFields)) * 100) : 0;

$missingItems = array();
if ($schoolEmail === '') {
    $missingItems[] = 'School email';
}
if ($adminMobile === '' && $adminTel === '') {
    $missingItems[] = 'Contact numbers';
}
if ($permitNo === '') {
    $missingItems[] = 'Permit number';
}
if ($recognitionNo === '') {
    $missingItems[] = 'Recognition number';
}
if ($yearEstablished === '') {
    $missingItems[] = 'Year established';
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

        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />

        <style>
            :root {
                --school-navy: #1f2a7c;
                --school-blue: #3555d3;
                --school-cyan: #00a9b8;
                --school-ink: #222a57;
                --school-muted: #7a829f;
                --school-border: rgba(53, 85, 211, 0.12);
                --school-surface: rgba(255, 255, 255, 0.96);
                --school-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            }

            body {
                background:
                    radial-gradient(circle at top left, rgba(53, 85, 211, 0.10), transparent 24%),
                    linear-gradient(180deg, #f4f7fb 0%, #edf3fa 100%);
            }

            .content-page {
                background: transparent;
            }

            .school-page-shell {
                position: relative;
                padding-bottom: 28px;
            }

            .school-page-shell::before {
                content: "";
                position: absolute;
                left: 0;
                right: 0;
                top: 20px;
                height: 250px;
                border-radius: 30px;
                background: linear-gradient(135deg, rgba(53, 85, 211, 0.10), rgba(0, 169, 184, 0.08));
                z-index: 0;
            }

            .school-page-shell > * {
                position: relative;
                z-index: 1;
            }

            .school-hero {
                margin-top: 20px;
                margin-bottom: 24px;
                border-radius: 28px;
                overflow: hidden;
                box-shadow: var(--school-shadow);
                background:
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.18), transparent 30%),
                    linear-gradient(135deg, #1f2a7c 0%, #3555d3 58%, #59a7ff 100%);
                color: #ffffff;
            }

            .school-hero-body {
                padding: 32px;
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                gap: 24px;
            }

            .school-kicker {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 14px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.12);
                border: 1px solid rgba(255, 255, 255, 0.18);
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .school-title {
                margin: 18px 0 12px;
                color: #ffffff;
                font-size: clamp(2rem, 3vw, 2.8rem);
                line-height: 1.05;
                font-weight: 700;
                letter-spacing: -0.03em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .school-copy {
                max-width: 760px;
                color: rgba(255, 255, 255, 0.85);
                line-height: 1.7;
                margin-bottom: 0;
            }

            .school-chip-row,
            .school-action-row {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }

            .school-chip-row {
                margin-top: 18px;
            }

            .school-chip {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 9px 13px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.12);
                color: rgba(255, 255, 255, 0.96);
                font-size: 0.84rem;
                font-weight: 600;
            }

            .school-action {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 12px 18px;
                border-radius: 999px;
                text-decoration: none;
                font-weight: 700;
                transition: transform 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
            }

            .school-action:hover,
            .school-action:focus {
                text-decoration: none;
                transform: translateY(-1px);
            }

            .school-action--primary {
                color: #ffffff;
                background: rgba(255, 255, 255, 0.12);
                border: 1px solid rgba(255, 255, 255, 0.24);
            }

            .school-action--primary:hover,
            .school-action--primary:focus {
                color: #ffffff;
                background: rgba(255, 255, 255, 0.18);
                box-shadow: 0 16px 30px rgba(15, 23, 42, 0.16);
            }

            .school-action--light {
                color: var(--school-ink);
                background: #ffffff;
                box-shadow: 0 14px 28px rgba(15, 23, 42, 0.12);
            }

            .school-action--light:hover,
            .school-action--light:focus {
                color: var(--school-blue);
            }

            .hero-side-card {
                min-width: 280px;
                max-width: 320px;
                border-radius: 24px;
                padding: 22px;
                background: rgba(10, 18, 63, 0.22);
                border: 1px solid rgba(255, 255, 255, 0.14);
                backdrop-filter: blur(12px);
            }

            .hero-side-label {
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                color: rgba(255, 255, 255, 0.70);
                margin-bottom: 12px;
            }

            .hero-side-value {
                font-size: 2.7rem;
                line-height: 1;
                font-weight: 700;
                color: #ffffff;
                margin-bottom: 10px;
            }

            .hero-side-copy {
                color: rgba(255, 255, 255, 0.78);
                line-height: 1.6;
                margin-bottom: 18px;
            }

            .hero-side-list {
                display: grid;
                gap: 10px;
            }

            .hero-side-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 14px;
                padding: 10px 0;
                border-top: 1px solid rgba(255, 255, 255, 0.12);
                font-size: 0.9rem;
            }

            .hero-side-item span:first-child {
                color: rgba(255, 255, 255, 0.72);
            }

            .hero-side-item span:last-child {
                color: #ffffff;
                font-weight: 700;
                text-align: right;
            }

            .metric-card {
                height: 100%;
                border-radius: 22px;
                border: 1px solid rgba(53, 85, 211, 0.08);
                background: var(--school-surface);
                box-shadow: var(--school-shadow);
                padding: 24px;
                position: relative;
                overflow: hidden;
            }

            .metric-card::before {
                content: "";
                position: absolute;
                inset: 0;
                opacity: 0.04;
                background: linear-gradient(135deg, var(--school-blue), var(--school-cyan));
            }

            .metric-card > * {
                position: relative;
                z-index: 1;
            }

            .metric-top {
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
                font-size: 24px;
            }

            .metric-badge {
                display: inline-flex;
                align-items: center;
                padding: 7px 12px;
                border-radius: 999px;
                background: rgba(53, 85, 211, 0.08);
                color: var(--school-blue);
                font-size: 0.73rem;
                font-weight: 700;
                letter-spacing: 0.04em;
                text-transform: uppercase;
            }

            .metric-value {
                color: var(--school-ink);
                font-size: 1.65rem;
                font-weight: 700;
                line-height: 1.1;
                margin-bottom: 6px;
            }

            .metric-label {
                color: var(--school-muted);
                font-size: 0.94rem;
                font-weight: 600;
                margin-bottom: 0;
            }

            .school-panel {
                border-radius: 24px;
                border: 1px solid rgba(53, 85, 211, 0.08);
                background: var(--school-surface);
                box-shadow: var(--school-shadow);
                padding: 28px;
                margin-bottom: 24px;
            }

            .school-panel-head {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 18px;
                flex-wrap: wrap;
                margin-bottom: 22px;
            }

            .school-panel-kicker {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 14px;
                border-radius: 999px;
                background: rgba(53, 85, 211, 0.08);
                color: var(--school-blue);
                font-size: 0.76rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .school-panel-title {
                margin: 14px 0 8px;
                color: var(--school-ink);
                font-size: 1.42rem;
                font-weight: 700;
                line-height: 1.2;
            }

            .school-panel-copy {
                color: var(--school-muted);
                font-size: 0.95rem;
                line-height: 1.65;
                margin-bottom: 0;
            }

            .school-pill {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 11px 14px;
                border-radius: 16px;
                border: 1px solid rgba(53, 85, 211, 0.08);
                background: linear-gradient(180deg, #f8faff 0%, #eff5ff 100%);
                color: var(--school-ink);
                font-weight: 700;
            }

            .detail-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 16px;
            }

            .detail-item {
                border-radius: 18px;
                border: 1px solid rgba(53, 85, 211, 0.08);
                background: #ffffff;
                padding: 18px;
            }

            .detail-label {
                display: block;
                color: var(--school-muted);
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.06em;
                text-transform: uppercase;
                margin-bottom: 8px;
            }

            .detail-value {
                color: var(--school-ink);
                font-size: 1rem;
                line-height: 1.6;
                font-weight: 600;
            }

            .school-muted-value {
                color: var(--school-muted);
                font-weight: 500;
            }

            .contact-stack,
            .insight-list {
                display: grid;
                gap: 14px;
            }

            .contact-card,
            .insight-card {
                border-radius: 18px;
                border: 1px solid rgba(53, 85, 211, 0.08);
                background: #ffffff;
                padding: 18px;
            }

            .contact-title,
            .insight-title {
                color: var(--school-ink);
                font-size: 1rem;
                font-weight: 700;
                margin-bottom: 6px;
            }

            .contact-copy,
            .insight-copy {
                color: var(--school-muted);
                line-height: 1.6;
                margin-bottom: 0;
            }

            .contact-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin-top: 14px;
            }

            .contact-link {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 10px 14px;
                border-radius: 12px;
                background: rgba(53, 85, 211, 0.08);
                color: var(--school-blue);
                text-decoration: none;
                font-size: 0.86rem;
                font-weight: 700;
            }

            .contact-link:hover,
            .contact-link:focus {
                text-decoration: none;
                color: var(--school-navy);
            }

            .completeness-ring {
                width: 118px;
                height: 118px;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background:
                    radial-gradient(circle at center, #ffffff 55%, transparent 56%),
                    conic-gradient(var(--school-blue) 0deg, var(--school-blue) calc(var(--ring-progress) * 1deg), rgba(53, 85, 211, 0.10) calc(var(--ring-progress) * 1deg), rgba(53, 85, 211, 0.10) 360deg);
                box-shadow: inset 0 0 0 1px rgba(53, 85, 211, 0.08);
            }

            .completeness-ring strong {
                color: var(--school-ink);
                font-size: 1.6rem;
                line-height: 1;
            }

            .missing-list {
                margin: 0;
                padding-left: 18px;
                color: var(--school-muted);
                line-height: 1.8;
            }

            .empty-shell {
                min-height: 60vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .empty-card {
                max-width: 620px;
                border-radius: 28px;
                border: 1px solid rgba(53, 85, 211, 0.10);
                background: rgba(255, 255, 255, 0.96);
                box-shadow: var(--school-shadow);
                padding: 36px;
                text-align: center;
            }

            .empty-card i {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 72px;
                height: 72px;
                border-radius: 20px;
                margin-bottom: 18px;
                background: rgba(53, 85, 211, 0.08);
                color: var(--school-blue);
                font-size: 34px;
            }

            .empty-card h2 {
                color: var(--school-ink);
                font-size: 2rem;
                font-weight: 700;
                margin-bottom: 12px;
            }

            .empty-card p {
                color: var(--school-muted);
                line-height: 1.7;
                margin-bottom: 24px;
            }

            @media (max-width: 991.98px) {
                .school-hero-body,
                .school-panel,
                .metric-card {
                    padding: 22px;
                }

                .detail-grid {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 767.98px) {
                .school-page-shell::before {
                    left: 12px;
                    right: 12px;
                    height: 220px;
                }

                .school-hero,
                .school-panel,
                .metric-card,
                .empty-card {
                    border-radius: 22px;
                }

                .school-title {
                    font-size: 2rem;
                }

                .hero-side-card {
                    max-width: 100%;
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
                    <div class="container-fluid school-page-shell">
                        <?php if ($school) { ?>
                            <div class="school-hero">
                                <div class="school-hero-body">
                                    <div>
                                        <span class="school-kicker">
                                            <i class="mdi mdi-school-outline"></i>
                                            School Dashboard
                                        </span>
                                        <h1 class="school-title"><?= school_dashboard_escape($schoolName !== '' ? $schoolName : 'Unnamed School'); ?></h1>
                                        <p class="school-copy">
                                            Review the school profile, leadership contacts, location, and reference details from one cleaner workspace built for quick scanning.
                                        </p>

                                        <div class="school-chip-row">
                                            <span class="school-chip"><i class="mdi mdi-pound"></i> <?= school_dashboard_escape($schoolId !== '' ? $schoolId : 'No school ID'); ?></span>
                                            <span class="school-chip"><i class="mdi mdi-map-marker-radius-outline"></i> <?= school_dashboard_escape($district !== '' ? $district : 'No district listed'); ?></span>
                                            <span class="school-chip"><i class="mdi mdi-domain"></i> <?= school_dashboard_escape($schoolType !== '' ? $schoolType : 'School type not listed'); ?></span>
                                        </div>
                                    </div>

                                    <div class="hero-side-card">
                                        <div class="hero-side-label">Profile Readiness</div>
                                        <div class="hero-side-value"><?= school_dashboard_escape($profileCompleteness); ?>%</div>
                                        <p class="hero-side-copy">
                                            Based on the available school, contact, and compliance fields currently saved in the directory.
                                        </p>

                                        <div class="hero-side-list">
                                            <div class="hero-side-item">
                                                <span>Address</span>
                                                <span><?= school_dashboard_escape($locationLine !== '' ? $locationLine : 'Pending'); ?></span>
                                            </div>
                                            <div class="hero-side-item">
                                                <span>School Head</span>
                                                <span><?= school_dashboard_escape($schoolHead !== '' ? $schoolHead : 'Pending'); ?></span>
                                            </div>
                                            <div class="hero-side-item">
                                                <span>Contactable</span>
                                                <span><?= school_dashboard_escape(($schoolEmail !== '' || $contactNumbers !== '') ? 'Yes' : 'No'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="school-action-row mb-4">
                                <a href="<?= $backLink; ?>" class="school-action school-action--light">
                                    <i class="mdi mdi-arrow-left"></i>
                                    Back to Schools Directory
                                </a>
                                <?php if ($schoolEmail !== '') { ?>
                                    <a href="mailto:<?= school_dashboard_escape($schoolEmail); ?>" class="school-action school-action--primary">
                                        <i class="mdi mdi-email-outline"></i>
                                        Email School
                                    </a>
                                <?php } ?>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-xl-3 mb-4">
                                    <div class="metric-card">
                                        <div class="metric-top">
                                            <span class="metric-icon" style="background: rgba(53, 85, 211, 0.12); color: #3555d3;">
                                                <i class="mdi mdi-pound-box-outline"></i>
                                            </span>
                                            <span class="metric-badge">Identity</span>
                                        </div>
                                        <div class="metric-value"><?= school_dashboard_escape($schoolId !== '' ? $schoolId : 'Pending'); ?></div>
                                        <div class="metric-label">School ID</div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3 mb-4">
                                    <div class="metric-card">
                                        <div class="metric-top">
                                            <span class="metric-icon" style="background: rgba(0, 169, 184, 0.14); color: #0f8f98;">
                                                <i class="mdi mdi-home-city-outline"></i>
                                            </span>
                                            <span class="metric-badge">Directory</span>
                                        </div>
                                        <div class="metric-value"><?= school_dashboard_escape($stationCode !== '' ? $stationCode : 'Pending'); ?></div>
                                        <div class="metric-label">Station Code</div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3 mb-4">
                                    <div class="metric-card">
                                        <div class="metric-top">
                                            <span class="metric-icon" style="background: rgba(124, 58, 237, 0.14); color: #7c3aed;">
                                                <i class="mdi mdi-shape-outline"></i>
                                            </span>
                                            <span class="metric-badge">Type</span>
                                        </div>
                                        <div class="metric-value"><?= school_dashboard_escape($schoolType !== '' ? $schoolType : 'Pending'); ?></div>
                                        <div class="metric-label">School Classification</div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3 mb-4">
                                    <div class="metric-card">
                                        <div class="metric-top">
                                            <span class="metric-icon" style="background: rgba(234, 88, 12, 0.14); color: #ea580c;">
                                                <i class="mdi mdi-timeline-clock-outline"></i>
                                            </span>
                                            <span class="metric-badge">History</span>
                                        </div>
                                        <div class="metric-value"><?= school_dashboard_escape($yearEstablished !== '' ? $yearEstablished : 'Pending'); ?></div>
                                        <div class="metric-label">Year Established</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-8">
                                    <div class="school-panel">
                                        <div class="school-panel-head">
                                            <div>
                                                <div class="school-panel-kicker">
                                                    <i class="mdi mdi-clipboard-text-outline"></i>
                                                    Overview
                                                </div>
                                                <h4 class="school-panel-title">School Details</h4>
                                                <p class="school-panel-copy">
                                                    Core directory information for the selected school, organized so the key facts are easy to review at a glance.
                                                </p>
                                            </div>
                                            <div class="school-pill">
                                                <i class="mdi mdi-map-marker-outline"></i>
                                                <?= school_dashboard_escape($district !== '' ? $district : 'District pending'); ?>
                                            </div>
                                        </div>

                                        <div class="detail-grid">
                                            <div class="detail-item">
                                                <span class="detail-label">School Name</span>
                                                <div class="detail-value"><?= school_dashboard_value_markup($schoolName, 'School name not listed'); ?></div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">School Type</span>
                                                <div class="detail-value"><?= school_dashboard_value_markup($schoolType, 'School type not listed'); ?></div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">School ID</span>
                                                <div class="detail-value"><?= school_dashboard_value_markup($schoolId, 'School ID not listed'); ?></div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Station Code</span>
                                                <div class="detail-value"><?= school_dashboard_value_markup($stationCode, 'Station code not listed'); ?></div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">District</span>
                                                <div class="detail-value"><?= school_dashboard_value_markup($district, 'District not listed'); ?></div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Year Established</span>
                                                <div class="detail-value"><?= school_dashboard_value_markup($yearEstablished, 'Year established not listed'); ?></div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Full Address</span>
                                                <div class="detail-value"><?= school_dashboard_value_markup($fullAddress, 'Address not listed'); ?></div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">School Email</span>
                                                <div class="detail-value"><?= school_dashboard_value_markup($schoolEmail, 'School email not listed'); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-4">
                                    <div class="school-panel">
                                        <div class="school-panel-head">
                                            <div>
                                                <div class="school-panel-kicker">
                                                    <i class="mdi mdi-account-tie-outline"></i>
                                                    Leadership
                                                </div>
                                                <h4 class="school-panel-title">Contact and Leadership</h4>
                                                <p class="school-panel-copy">
                                                    School head details and the fastest available contact channels for coordination.
                                                </p>
                                            </div>
                                        </div>

                                        <div class="contact-stack">
                                            <div class="contact-card">
                                                <div class="contact-title"><?= school_dashboard_escape($schoolHead !== '' ? $schoolHead : 'School head not listed'); ?></div>
                                                <p class="contact-copy"><?= school_dashboard_escape($adminDesignation !== '' ? $adminDesignation : 'Designation not listed'); ?></p>
                                            </div>

                                            <div class="contact-card">
                                                <div class="contact-title">Contact Numbers</div>
                                                <p class="contact-copy"><?= $contactNumbers !== '' ? school_dashboard_escape($contactNumbers) : 'No mobile or telephone number listed yet.'; ?></p>
                                                <?php if ($contactNumbers !== '' || $schoolEmail !== '') { ?>
                                                    <div class="contact-actions">
                                                        <?php if ($schoolEmail !== '') { ?>
                                                            <a href="mailto:<?= school_dashboard_escape($schoolEmail); ?>" class="contact-link">
                                                                <i class="mdi mdi-email-fast-outline"></i>
                                                                Send Email
                                                            </a>
                                                        <?php } ?>
                                                    </div>
                                                <?php } ?>
                                            </div>

                                            <div class="contact-card">
                                                <div class="contact-title">Location</div>
                                                <p class="contact-copy"><?= $locationLine !== '' ? school_dashboard_escape($locationLine) : 'Barangay, city, and province details are still incomplete.'; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-7">
                                    <div class="school-panel">
                                        <div class="school-panel-head">
                                            <div>
                                                <div class="school-panel-kicker">
                                                    <i class="mdi mdi-card-account-details-outline"></i>
                                                    Compliance
                                                </div>
                                                <h4 class="school-panel-title">Reference and Recognition</h4>
                                                <p class="school-panel-copy">
                                                    Permit and recognition details that are usually needed when checking a school record.
                                                </p>
                                            </div>
                                        </div>

                                        <div class="detail-grid">
                                            <div class="detail-item">
                                                <span class="detail-label">Permit Number</span>
                                                <div class="detail-value"><?= school_dashboard_value_markup($permitNo, 'Permit number not listed'); ?></div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Recognition Number</span>
                                                <div class="detail-value"><?= school_dashboard_value_markup($recognitionNo, 'Recognition number not listed'); ?></div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Barangay</span>
                                                <div class="detail-value"><?= school_dashboard_value_markup($barangay, 'Barangay not listed'); ?></div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">City / Municipality</span>
                                                <div class="detail-value"><?= school_dashboard_value_markup($city, 'City or municipality not listed'); ?></div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Province</span>
                                                <div class="detail-value"><?= school_dashboard_value_markup($province, 'Province not listed'); ?></div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Sitio / Street</span>
                                                <div class="detail-value"><?= school_dashboard_value_markup($sitio, 'Sitio or street not listed'); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-5">
                                    <div class="school-panel">
                                        <div class="school-panel-head">
                                            <div>
                                                <div class="school-panel-kicker">
                                                    <i class="mdi mdi-chart-donut"></i>
                                                    Readiness
                                                </div>
                                                <h4 class="school-panel-title">Profile Completeness</h4>
                                                <p class="school-panel-copy">
                                                    A quick signal of how complete this school profile is based on the available directory fields.
                                                </p>
                                            </div>
                                        </div>

                                        <div class="insight-list">
                                            <div class="insight-card text-center">
                                                <div class="completeness-ring" style="--ring-progress: <?= school_dashboard_escape((int) round($profileCompleteness * 3.6)); ?>;">
                                                    <strong><?= school_dashboard_escape($profileCompleteness); ?>%</strong>
                                                </div>
                                                <div class="insight-title mt-3">Directory coverage</div>
                                                <p class="insight-copy mb-0">
                                                    <?= school_dashboard_escape($filledFields); ?> of <?= school_dashboard_escape(count($completenessFields)); ?> key fields are already filled in.
                                                </p>
                                            </div>

                                            <div class="insight-card">
                                                <div class="insight-title">Still missing</div>
                                                <?php if (!empty($missingItems)) { ?>
                                                    <ul class="missing-list">
                                                        <?php foreach ($missingItems as $missingItem) { ?>
                                                            <li><?= school_dashboard_escape($missingItem); ?></li>
                                                        <?php } ?>
                                                    </ul>
                                                <?php } else { ?>
                                                    <p class="insight-copy mb-0">This record already includes the main school, contact, and compliance details shown on this page.</p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="empty-shell">
                                <div class="empty-card">
                                    <i class="mdi mdi-school-off-outline"></i>
                                    <h2>School Record Not Found</h2>
                                    <p>
                                        The selected school could not be loaded. The link may be outdated, or the school ID might no longer exist in the directory.
                                    </p>
                                    <a href="<?= base_url(); ?>Page/schools" class="school-action school-action--light">
                                        <i class="mdi mdi-arrow-left"></i>
                                        Return to Schools Directory
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <?php include('includes/footer.php'); ?>
            </div>
        </div>

        <?php include('includes/right-sidebar.php'); ?>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
    </body>
</html>
