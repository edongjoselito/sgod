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
$listedMembers = array();

if (!empty($sectionRecord->member)) {
    foreach (preg_split('/\s*;\s*/', (string) $sectionRecord->member) as $memberEntry) {
        $memberEntry = trim(preg_replace('/\s+/', ' ', (string) $memberEntry));
        if ($memberEntry !== '') {
            $listedMembers[] = $memberEntry;
        }
    }
}

$quickLinks = array(
    array(
        'title' => 'Schools',
        'href' => base_url() . 'Page/schools',
        'icon' => 'mdi-office-building-outline'
    ),
    array(
        'title' => 'Reports',
        'href' => base_url() . 'Page/viewSecAccomplishments',
        'icon' => 'mdi-file-document-outline'
    ),
    array(
        'title' => 'Add Report',
        'href' => base_url() . 'Page/addAccomplishments',
        'icon' => 'mdi-plus-circle-outline'
    ),
    array(
        'title' => 'Users',
        'href' => base_url() . 'Page/usersListv2',
        'icon' => 'mdi-account-multiple-outline'
    )
);

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
                --dash-primary: #3c40c6;
                --dash-dark: #25296f;
                --dash-text: #24294d;
                --dash-muted: #7a809f;
                --dash-border: #e5e8f5;
                --dash-surface: #ffffff;
                --dash-bg: #f4f6fb;
                --dash-shadow: 0 8px 24px rgba(35, 39, 93, 0.08);
            }

            body {
                background: var(--dash-bg);
            }

            .content-page {
                background: transparent;
            }

            .dashboard-shell {
                padding-top: 14px;
                padding-bottom: 18px;
            }

            .dashboard-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                min-height: 72px;
                margin-bottom: 14px;
                padding: 14px 18px;
                border-radius: 16px;
                color: #ffffff;
                background: linear-gradient(135deg, var(--dash-dark), var(--dash-primary));
                box-shadow: var(--dash-shadow);
            }

            .dashboard-heading {
                display: flex;
                align-items: center;
                min-width: 0;
                gap: 12px;
            }

            .dashboard-heading-icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 42px;
                height: 42px;
                flex: 0 0 42px;
                border-radius: 12px;
                background: rgba(255, 255, 255, 0.14);
                font-size: 1.35rem;
            }

            .dashboard-title {
                margin: 0;
                color: #ffffff;
                font-size: 1.3rem;
                line-height: 1.2;
                font-weight: 700;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .dashboard-subtitle {
                margin: 3px 0 0;
                color: rgba(255, 255, 255, 0.72);
                font-size: 0.82rem;
            }

            .head-user {
                min-width: 0;
                text-align: right;
            }

            .head-user strong,
            .head-user span {
                display: block;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .head-user strong {
                font-size: 0.9rem;
                color: #ffffff;
            }

            .head-user span {
                margin-top: 2px;
                color: rgba(255, 255, 255, 0.68);
                font-size: 0.76rem;
            }

            .dashboard-main-row {
                margin-right: -7px;
                margin-left: -7px;
            }

            .dashboard-main-row > [class*="col-"] {
                padding-right: 7px;
                padding-left: 7px;
            }

            .compact-card,
            .calendar-panel {
                border: 1px solid var(--dash-border);
                border-radius: 16px;
                background: var(--dash-surface);
                box-shadow: var(--dash-shadow);
            }

            .compact-card {
                margin-bottom: 14px;
                padding: 14px;
            }

            .compact-card-title,
            .calendar-panel-title {
                margin: 0;
                color: var(--dash-text);
                font-size: 0.9rem;
                font-weight: 700;
            }

            .stat-list {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 8px;
                margin-top: 12px;
            }

            .stat-item {
                min-width: 0;
                padding: 12px 8px;
                border: 1px solid #eceef8;
                border-radius: 12px;
                text-align: center;
                background: #f8f9fe;
            }

            .stat-item strong {
                display: block;
                color: var(--dash-primary);
                font-size: 1.45rem;
                line-height: 1;
                font-weight: 700;
            }

            .stat-item span {
                display: block;
                margin-top: 6px;
                color: var(--dash-muted);
                font-size: 0.7rem;
                line-height: 1.2;
                font-weight: 600;
            }

            .quick-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 8px;
                margin-top: 12px;
            }

            .quick-link {
                display: flex;
                align-items: center;
                min-width: 0;
                gap: 9px;
                min-height: 52px;
                padding: 10px;
                border: 1px solid #e8eaf6;
                border-radius: 12px;
                color: var(--dash-text);
                background: #ffffff;
                transition: border-color 0.18s ease, transform 0.18s ease, background 0.18s ease;
            }

            .quick-link:hover,
            .quick-link:focus {
                color: var(--dash-primary);
                text-decoration: none;
                border-color: rgba(60, 64, 198, 0.28);
                background: #f7f7ff;
                transform: translateY(-1px);
            }

            .quick-link-icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 30px;
                height: 30px;
                flex: 0 0 30px;
                border-radius: 9px;
                color: var(--dash-primary);
                background: rgba(60, 64, 198, 0.09);
                font-size: 1rem;
            }

            .quick-link span {
                min-width: 0;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                font-size: 0.78rem;
                font-weight: 700;
            }

            .member-summary {
                margin: 0;
            }

            .member-summary summary {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
                color: var(--dash-text);
                cursor: pointer;
                list-style: none;
                font-size: 0.84rem;
                font-weight: 700;
            }

            .member-summary summary::-webkit-details-marker {
                display: none;
            }

            .member-count {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 25px;
                height: 25px;
                padding: 0 7px;
                border-radius: 999px;
                color: var(--dash-primary);
                background: rgba(60, 64, 198, 0.09);
                font-size: 0.72rem;
            }

            .member-pill-list {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
                max-height: 116px;
                margin-top: 12px;
                overflow-y: auto;
            }

            .member-pill {
                display: inline-flex;
                align-items: center;
                padding: 5px 8px;
                border-radius: 999px;
                color: #4f557b;
                background: #f0f2fa;
                font-size: 0.72rem;
                font-weight: 600;
            }

            .calendar-panel {
                min-height: calc(100vh - 145px);
                overflow: hidden;
            }

            .calendar-panel-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                min-height: 48px;
                padding: 10px 14px;
                border-bottom: 1px solid var(--dash-border);
            }

            .calendar-panel-caption {
                color: var(--dash-muted);
                font-size: 0.72rem;
                white-space: nowrap;
            }

            .calendar-zone {
                min-height: calc(100vh - 194px);
                padding: 10px;
                overflow: visible;
            }

            /* Neutralize excess spacing from the included whereabouts/calendar view. */
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
                margin-right: -5px;
                margin-left: -5px;
            }

            .calendar-zone .row > [class*="col-"] {
                padding-right: 5px;
                padding-left: 5px;
            }

            .calendar-zone .card,
            .calendar-zone .panel,
            .calendar-zone .box {
                margin-bottom: 10px !important;
                border-radius: 12px !important;
                box-shadow: none !important;
            }

            .calendar-zone .card-body,
            .calendar-zone .panel-body,
            .calendar-zone .box-body {
                padding: 10px !important;
            }

            .calendar-zone .fc-toolbar,
            .calendar-zone .fc-header-toolbar {
                margin-bottom: 10px !important;
            }

            .calendar-zone .fc-toolbar h2,
            .calendar-zone .fc .fc-toolbar-title {
                color: var(--dash-text);
                font-size: 1.05rem !important;
                font-weight: 700;
            }

            .calendar-zone .fc-button,
            .calendar-zone .fc .fc-button {
                padding: 0.3rem 0.55rem !important;
                font-size: 0.74rem !important;
                line-height: 1.25 !important;
            }

            .calendar-zone .fc-day-header,
            .calendar-zone .fc-col-header-cell-cushion,
            .calendar-zone .fc-daygrid-day-number {
                font-size: 0.74rem !important;
            }

            .calendar-zone .fc-event,
            .calendar-zone .fc-day-grid-event,
            .calendar-zone .fc-daygrid-event {
                font-size: 0.7rem !important;
            }

            @media (max-width: 1199.98px) {
                .calendar-panel {
                    min-height: auto;
                }

                .calendar-zone {
                    min-height: 620px;
                }
            }

            @media (max-width: 767.98px) {
                .dashboard-shell {
                    padding-top: 10px;
                }

                .dashboard-header {
                    align-items: flex-start;
                    min-height: auto;
                    padding: 12px 14px;
                }

                .dashboard-title {
                    font-size: 1.05rem;
                    white-space: normal;
                }

                .dashboard-subtitle,
                .head-user {
                    display: none;
                }

                .stat-list {
                    grid-template-columns: repeat(3, minmax(82px, 1fr));
                    overflow-x: auto;
                }

                .calendar-panel-header {
                    align-items: flex-start;
                }

                .calendar-panel-caption {
                    display: none;
                }

                .calendar-zone {
                    min-height: 560px;
                    padding: 6px;
                }

                .calendar-zone .fc-toolbar,
                .calendar-zone .fc-header-toolbar {
                    gap: 6px;
                }

                .calendar-zone .fc-toolbar h2,
                .calendar-zone .fc .fc-toolbar-title {
                    font-size: 0.92rem !important;
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
                    <div class="container-fluid dashboard-shell">
                        <header class="dashboard-header">
                            <div class="dashboard-heading">
                                <span class="dashboard-heading-icon">
                                    <i class="mdi mdi-view-dashboard-outline"></i>
                                </span>
                                <div>
                                    <h1 class="dashboard-title"><?= htmlspecialchars($sectionName, ENT_QUOTES, 'UTF-8'); ?></h1>
                                    <p class="dashboard-subtitle">Section Head Dashboard</p>
                                </div>
                            </div>

                            <?php if ($sectionHeadName !== '') { ?>
                                <div class="head-user">
                                    <strong><?= htmlspecialchars($sectionHeadName, ENT_QUOTES, 'UTF-8'); ?></strong>
                                    <span><?= htmlspecialchars($sectionHeadPosition, ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                            <?php } ?>
                        </header>

                        <div class="row dashboard-main-row">
                            <div class="col-xl-3 col-lg-4">
                                <section class="compact-card">
                                    <h2 class="compact-card-title">Overview</h2>
                                    <div class="stat-list">
                                        <div class="stat-item" title="Total schools">
                                            <strong><?= number_format($totalSchools); ?></strong>
                                            <span>Schools</span>
                                        </div>
                                        <div class="stat-item" title="Section accomplishments">
                                            <strong><?= number_format($accomplishmentCount); ?></strong>
                                            <span>Reports</span>
                                        </div>
                                        <div class="stat-item" title="Active section users">
                                            <strong><?= number_format($sectionUserCount); ?></strong>
                                            <span>Users</span>
                                        </div>
                                    </div>
                                </section>

                                <section class="compact-card">
                                    <h2 class="compact-card-title">Quick Access</h2>
                                    <div class="quick-grid">
                                        <?php foreach ($quickLinks as $quickLink) { ?>
                                            <a href="<?= htmlspecialchars($quickLink['href'], ENT_QUOTES, 'UTF-8'); ?>" class="quick-link">
                                                <i class="mdi <?= htmlspecialchars($quickLink['icon'], ENT_QUOTES, 'UTF-8'); ?> quick-link-icon"></i>
                                                <span><?= htmlspecialchars($quickLink['title'], ENT_QUOTES, 'UTF-8'); ?></span>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </section>

                                <?php if (!empty($listedMembers)) { ?>
                                    <section class="compact-card">
                                        <details class="member-summary">
                                            <summary>
                                                <span>Section Members</span>
                                                <span class="member-count"><?= number_format(count($listedMembers)); ?></span>
                                            </summary>
                                            <div class="member-pill-list">
                                                <?php foreach ($listedMembers as $listedMember) { ?>
                                                    <span class="member-pill"><?= htmlspecialchars($listedMember, ENT_QUOTES, 'UTF-8'); ?></span>
                                                <?php } ?>
                                            </div>
                                        </details>
                                    </section>
                                <?php } ?>
                            </div>

                            <div class="col-xl-9 col-lg-8">
                                <section class="calendar-panel">
                                    <div class="calendar-panel-header">
                                        <h2 class="calendar-panel-title">
                                            <i class="mdi mdi-calendar-month-outline mr-1"></i>
                                            Calendar
                                        </h2>
                                        <span class="calendar-panel-caption">Schedule and whereabouts</span>
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