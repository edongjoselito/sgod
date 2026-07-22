<?php
$esc = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <?php include('includes/page-title.php'); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />

        <style>
            :root {
                --memo-navy: #272b8c;
                --memo-blue: #3c40c6;
                --memo-ink: #23275d;
                --memo-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            }

            body {
                background:
                    radial-gradient(circle at top left, rgba(60, 64, 198, 0.10), transparent 24%),
                    linear-gradient(180deg, #f4f8fc 0%, #eef4fa 100%);
            }

            .content-page {
                background: transparent;
            }

            .memo-shell {
                position: relative;
                padding-bottom: 28px;
            }

            .memo-shell::before {
                content: "";
                position: absolute;
                inset: 24px 0 auto;
                height: 240px;
                border-radius: 30px;
                background: linear-gradient(135deg, rgba(60, 64, 198, 0.11), rgba(122, 128, 255, 0.10));
                z-index: 0;
            }

            .memo-shell > * {
                position: relative;
                z-index: 1;
            }

            .memo-hero {
                margin-top: 20px;
                border-radius: 28px;
                overflow: hidden;
                color: #ffffff;
                box-shadow: var(--memo-shadow);
                background:
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 32%),
                    linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
            }

            .memo-hero-body {
                padding: 32px;
            }

            .memo-eyebrow {
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

            .memo-title {
                margin: 18px 0 12px;
                color: #ffffff;
                font-size: clamp(2rem, 3vw, 2.7rem);
                line-height: 1.05;
                font-weight: 700;
                letter-spacing: -0.03em;
            }

            .hero-add-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                padding: 13px 20px;
                border: none;
                border-radius: 16px;
                color: var(--memo-navy);
                background: linear-gradient(135deg, #ffffff 0%, #eef7ff 100%);
                font-weight: 700;
                transition: transform 0.25s ease, box-shadow 0.25s ease;
            }

            .hero-add-btn:hover {
                color: var(--memo-navy);
                transform: translateY(-2px);
                box-shadow: 0 14px 32px rgba(17, 24, 39, 0.12);
                text-decoration: none;
            }
        </style>
    </head>

    <body>
        <div id="wrapper">
            <?php include('includes/top-bar.php'); ?>
            <?php include('includes/sidebar.php'); ?>

            <div class="content-page">
                <div class="content">
                    <div class="container-fluid memo-shell">
                        <div class="row">
                            <div class="col-12">
                                <div class="memo-hero">
                                    <div class="memo-hero-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <span class="memo-eyebrow">
                                                    <i class="mdi mdi-pencil-box-outline"></i>
                                                    Activity Design Registry
                                                </span>
                                                <h1 class="memo-title">Activity Designs</h1>
                                            </div>
                                            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                                <a href="<?= base_url(); ?>Page/activity_design_add" class="hero-add-btn">
                                                    <i class="mdi mdi-plus"></i>
                                                    New Activity Design
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= $this->session->flashdata('success'); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('danger')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= $this->session->flashdata('danger'); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        <?php endif; ?>

                        <div class="card">
                            <div class="card-body">
                                <?php if (empty($entries)): ?>
                                    <p class="text-muted mb-0">No activity designs yet. Click the button above to add one.</p>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Design No.</th>
                                                    <th>Activity Title</th>
                                                    <th>Date</th>
                                                    <th>Venue</th>
                                                    <th>Fund Source</th>
                                                    <th class="text-right">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($entries as $entry): ?>
                                                    <tr>
                                                        <td><?= $esc($entry->activity_design_no ?? ''); ?></td>
                                                        <td><?= $esc($entry->title); ?></td>
                                                        <td><?= $esc($entry->activity_date); ?></td>
                                                        <td><?= $esc($entry->venue); ?></td>
                                                        <td><?= $esc($entry->fund_source); ?></td>
                                                        <td class="text-right">
                                                            <div class="dropdown d-inline-block">
                                                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton<?= (int) $entry->id; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    Actions
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton<?= (int) $entry->id; ?>">
                                                                    <a class="dropdown-item" href="<?= base_url(); ?>Page/activity_design_edit/<?= (int) $entry->id; ?>">Edit</a>
                                                                    <a class="dropdown-item" href="<?= base_url(); ?>Page/activity_design_print/<?= (int) $entry->id; ?>" target="_blank">Print</a>
                                                                    <a class="dropdown-item text-danger" href="<?= base_url(); ?>Page/activity_design_delete/<?= (int) $entry->id; ?>" onclick="return confirm('Are you sure you want to delete this activity design?');">Delete</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include('includes/footer.php'); ?>
            </div>
        </div>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
    </body>
</html>
