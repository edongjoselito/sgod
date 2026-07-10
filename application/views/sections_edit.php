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

            .form-card {
                border: 1px solid rgba(22, 50, 79, 0.08);
                border-radius: 22px;
                background: #ffffff;
                padding: 28px;
                box-shadow: var(--sections-shadow);
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

            .modern-input:read-only {
                background: #f8fbff;
                color: var(--sections-muted);
            }

            .btn-gradient-primary {
                color: #ffffff;
                border: none;
                border-radius: 14px;
                padding: 11px 24px;
                font-weight: 700;
                background: linear-gradient(135deg, #2d7ff9 0%, #0f9f9a 100%);
                box-shadow: 0 14px 30px rgba(45, 127, 249, 0.18);
            }

            .btn-gradient-primary:hover {
                color: #ffffff;
            }

            .btn-soft-dark {
                color: var(--sections-ink);
                background: #eef3f8;
                border: none;
                border-radius: 14px;
                padding: 11px 20px;
                font-weight: 700;
            }

            @media (max-width: 767.98px) {
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
            $identifier = $data->secGroup ?? 'SGOD';
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
                                        <span class="sections-eyebrow">
                                            <i class="mdi mdi-pencil-outline"></i>
                                            Edit Section Record
                                        </span>
                                        <h1 class="sections-title">Update section details for <?= htmlspecialchars($identifier, ENT_QUOTES, 'UTF-8'); ?></h1>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="form-card">
                                    <form method="post">
                                        <div class="form-group">
                                            <label class="modern-label">Section <span class="text-danger">*</span></label>
                                            <input type="text" name="sectionName" required class="form-control modern-input" value="<?= htmlspecialchars($data->sectionName, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="modern-label">Section Head</label>
                                            <input type="text" class="form-control modern-input" name="sectionHead" value="<?= htmlspecialchars($data->sectionHead, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label class="modern-label">Position</label>
                                            <input type="text" class="form-control modern-input" name="sectionHeadPosition" value="<?= htmlspecialchars($data->sectionHeadPosition, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label class="modern-label">Section Group <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control modern-input" value="<?= htmlspecialchars($data->secGroup, ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                            <input type="hidden" name="secGroup" value="<?= htmlspecialchars($data->secGroup, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label class="modern-label">Members <span class="text-danger">*</span></label>
                                            <input type="text" required class="form-control modern-input" name="member" value="<?= htmlspecialchars($data->member, ENT_QUOTES, 'UTF-8'); ?>">
                                            <input type="hidden" required class="form-control" name="id" value="<?= $data->id; ?>">
                                        </div>

                                        <div class="form-group text-right mb-0">
                                            <a href="<?= base_url(); ?>Page/sections" class="btn btn-soft-dark mr-2">Cancel</a>
                                            <input type="submit" name="submit" value="Save Changes" class="btn btn-gradient-primary">
                                        </div>
                                    </form>
                                </div>
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
