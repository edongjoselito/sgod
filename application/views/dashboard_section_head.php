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
        'title' => 'School directory',
        'copy' => 'Browse one school directory and filter public or private records inside the page.',
        'href' => base_url() . 'Page/schools',
        'icon' => 'mdi-office-building-outline'
    ),
    array(
        'title' => 'Section accomplishments',
        'copy' => 'Review accomplishment entries submitted by your section.',
        'href' => base_url() . 'Page/viewSecAccomplishments',
        'icon' => 'mdi-file-document-outline'
    ),
    array(
        'title' => 'Add accomplishment',
        'copy' => 'Open the streamlined accomplishment form and post a new record.',
        'href' => base_url() . 'Page/addAccomplishments',
        'icon' => 'mdi-plus-circle-outline'
    ),
    array(
        'title' => 'Manage users',
        'copy' => 'Check section user accounts and keep the team list up to date.',
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
                --head-navy: #272b8c;
                --head-blue: #3c40c6;
                --head-teal: #565de8;
                --head-ink: #23275d;
                --head-muted: #7b7fa7;
                --head-border: rgba(60, 64, 198, 0.12);
                --head-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            }

            body {
                background:
                    radial-gradient(circle at top left, rgba(60, 64, 198, 0.10), transparent 24%),
                    linear-gradient(180deg, #f4f8fc 0%, #eef4fa 100%);
            }

            .content-page {
                background: transparent;
            }

            .head-shell {
                position: relative;
                padding-bottom: 28px;
            }

            .head-shell::before {
                content: "";
                position: absolute;
                inset: 24px 0 auto;
                height: 260px;
                border-radius: 30px;
                background: linear-gradient(135deg, rgba(45, 127, 249, 0.09), rgba(15, 159, 154, 0.08));
                z-index: 0;
            }

            .head-shell > * {
                position: relative;
                z-index: 1;
            }

            .dashboard-hero {
                margin-top: 20px;
                margin-bottom: 24px;
                border-radius: 28px;
                overflow: hidden;
                color: #ffffff;
                box-shadow: var(--head-shadow);
                background:
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.18), transparent 32%),
                    linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
            }

            .dashboard-hero-body {
                padding: 34px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 24px;
                flex-wrap: wrap;
            }

            .hero-copy-wrap {
                flex: 1 1 420px;
                max-width: 680px;
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

            .hero-copy {
                color: rgba(255, 255, 255, 0.84);
                line-height: 1.7;
                margin-bottom: 0;
            }

            .hero-meta-card {
                min-width: 280px;
                max-width: 340px;
                border-radius: 22px;
                padding: 24px;
                background: rgba(255, 255, 255, 0.12);
                border: 1px solid rgba(255, 255, 255, 0.18);
                backdrop-filter: blur(14px);
            }

            .hero-meta-label {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                color: rgba(255, 255, 255, 0.76);
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                margin-bottom: 12px;
            }

            .hero-meta-name {
                color: #ffffff;
                font-size: 1.2rem;
                font-weight: 700;
                line-height: 1.3;
                margin-bottom: 8px;
            }

            .hero-meta-copy {
                color: rgba(255, 255, 255, 0.78);
                font-size: 0.92rem;
                line-height: 1.6;
                margin-bottom: 0;
            }

            .panel-card {
                height: 100%;
                border-radius: 24px;
                border: 1px solid rgba(60, 64, 198, 0.08);
                background: rgba(255, 255, 255, 0.95);
                box-shadow: var(--head-shadow);
                padding: 28px;
            }

            .panel-kicker {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 14px;
                border-radius: 999px;
                background: rgba(60, 64, 198, 0.08);
                color: var(--head-blue);
                font-size: 0.76rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .panel-title {
                margin: 18px 0 10px;
                color: var(--head-ink);
                font-size: 1.45rem;
                font-weight: 700;
                line-height: 1.2;
            }

            .panel-copy {
                color: var(--head-muted);
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
                color: var(--head-muted);
                font-size: 0.82rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.06em;
                margin-bottom: 10px;
            }

            .overview-stat-value {
                display: block;
                color: var(--head-ink);
                font-size: 2rem;
                line-height: 1;
                font-weight: 700;
                margin-bottom: 8px;
            }

            .overview-stat-note {
                display: block;
                color: var(--head-muted);
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
                color: var(--head-ink);
                font-size: 0.98rem;
                font-weight: 700;
                margin-bottom: 6px;
            }

            .action-tile-copy {
                color: var(--head-muted);
                font-size: 0.88rem;
                line-height: 1.55;
                margin: 0;
            }

            .action-tile-icon {
                color: var(--head-blue);
                font-size: 1.25rem;
                flex-shrink: 0;
            }

            .member-pill-list {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin-top: 22px;
            }

            .member-pill {
                display: inline-flex;
                align-items: center;
                padding: 8px 12px;
                border-radius: 999px;
                background: rgba(60, 64, 198, 0.08);
                color: var(--head-ink);
                font-size: 0.86rem;
                font-weight: 600;
            }

            @media (max-width: 991.98px) {
                .dashboard-hero-body {
                    padding: 28px;
                }

                .hero-meta-card {
                    max-width: none;
                    width: 100%;
                }
            }

            @media (max-width: 767.98px) {
                .head-shell::before {
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
                    <div class="container-fluid head-shell">
                        <div class="dashboard-hero">
                            <div class="dashboard-hero-body">
                                <div class="hero-copy-wrap">
                                    <span class="hero-eyebrow">
                                        <i class="mdi mdi-account-tie-outline"></i>
                                        Section Head Dashboard
                                    </span>
                                    <h1 class="hero-title"><?= htmlspecialchars($sectionName, ENT_QUOTES, 'UTF-8'); ?></h1>
                                    <p class="hero-copy">
                                        Use this cleaner dashboard to monitor your section's accomplishments, active team,
                                        and day-to-day access points without jumping through multiple pages first.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-7 mb-4">
                                <div class="panel-card">
                                    <div class="panel-kicker">Overview</div>
                                    <h4 class="panel-title">A cleaner section summary for <?= htmlspecialchars($sectionName, ENT_QUOTES, 'UTF-8'); ?></h4>
                                    <p class="panel-copy">Keep an eye on the shared counts that usually matter first when you open your section workspace.</p>
                                    <div class="overview-grid">
                                        <div class="overview-stat">
                                            <span class="overview-stat-label">Total schools</span>
                                            <strong class="overview-stat-value"><?= number_format($totalSchools); ?></strong>
                                            <span class="overview-stat-note">Combined public and private schools available in the shared directory.</span>
                                        </div>
                                        <div class="overview-stat">
                                            <span class="overview-stat-label">Accomplishments</span>
                                            <strong class="overview-stat-value"><?= number_format($accomplishmentCount); ?></strong>
                                            <span class="overview-stat-note">Reported accomplishment entries currently linked to your section.</span>
                                        </div>
                                        <div class="overview-stat">
                                            <span class="overview-stat-label">Active team</span>
                                            <strong class="overview-stat-value"><?= number_format($sectionUserCount); ?></strong>
                                            <span class="overview-stat-note">User accounts that can currently access this section area.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-5 mb-4">
                                <div class="panel-card">
                                    <div class="panel-kicker">Quick Access</div>
                                    <h4 class="panel-title">Open your most-used section pages</h4>
                                    <p class="panel-copy">Jump straight into the pages section heads usually need during routine monitoring and reporting.</p>
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

                        <?php if (!empty($listedMembers)) { ?>
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <div class="panel-card">
                                        <div class="panel-kicker">Listed Members</div>
                                        <h4 class="panel-title">People currently recorded under this section</h4>
                                        <p class="panel-copy">These are the member entries saved in the section record and shown across the section registry.</p>
                                        <div class="member-pill-list">
                                            <?php foreach ($listedMembers as $listedMember) { ?>
                                                <span class="member-pill"><?= htmlspecialchars($listedMember, ENT_QUOTES, 'UTF-8'); ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
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
            })();
        </script>

    </body>
</html>
