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

        <style>
            :root {
                --ac-navy: #272b8c;
                --ac-blue: #3c40c6;
                --ac-ink: #23275d;
                --ac-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            }

            body {
                background:
                    radial-gradient(circle at top left, rgba(60, 64, 198, 0.10), transparent 24%),
                    linear-gradient(180deg, #f4f8fc 0%, #eef4fa 100%);
            }

            .content-page {
                background: transparent;
            }

            .accomplishment-shell {
                position: relative;
                padding-bottom: 28px;
            }

            .accomplishment-shell::before {
                content: "";
                position: absolute;
                inset: 24px 0 auto;
                height: 240px;
                border-radius: 30px;
                background: linear-gradient(135deg, rgba(60, 64, 198, 0.11), rgba(122, 128, 255, 0.10));
                z-index: 0;
            }

            .accomplishment-shell > * {
                position: relative;
                z-index: 1;
            }

            .accomplishment-hero {
                margin-top: 20px;
                border-radius: 28px;
                overflow: hidden;
                color: #ffffff;
                box-shadow: var(--ac-shadow);
                background:
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 32%),
                    linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
            }

            .accomplishment-hero-body {
                padding: 32px;
            }

            .accomplishment-eyebrow {
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

            .accomplishment-title {
                margin: 16px 0 10px;
                color: #ffffff;
                font-size: clamp(1.8rem, 3vw, 2.4rem);
                line-height: 1.1;
                font-weight: 700;
                letter-spacing: -0.02em;
            }

            .accomplishment-hero-meta {
                color: rgba(255, 255, 255, 0.88);
                font-size: 0.95rem;
                margin-bottom: 6px;
            }

            .accomplishment-hero-meta strong {
                color: #ffffff;
                font-weight: 600;
            }

            .accomplishment-hero-actions {
                margin-top: 20px;
            }

            .accomplishment-hero .btn-light {
                color: var(--ac-navy);
                background: linear-gradient(135deg, #ffffff 0%, #eef7ff 100%);
                border: none;
                border-radius: 14px;
                padding: 10px 18px;
                font-weight: 700;
                transition: transform 0.25s ease, box-shadow 0.25s ease;
            }

            .accomplishment-hero .btn-light:hover {
                transform: translateY(-2px);
                box-shadow: 0 14px 32px rgba(17, 24, 39, 0.12);
            }

            .accomplishment-card {
                border: 1px solid rgba(60, 64, 198, 0.10);
                border-radius: 24px;
                box-shadow: var(--ac-shadow);
                background: #ffffff;
                overflow: hidden;
            }

            .accomplishment-table th {
                background: #f9fbff;
                color: var(--ac-ink);
                font-weight: 700;
                font-size: 0.82rem;
                text-transform: uppercase;
                letter-spacing: 0.06em;
                border-top: none;
                border-bottom: 1px solid rgba(60, 64, 198, 0.10);
                padding: 14px 18px;
            }

            .accomplishment-table td {
                padding: 14px 18px;
                vertical-align: middle;
                color: #334155;
                border-color: rgba(60, 64, 198, 0.06);
            }

            .accomplishment-table tbody tr:hover {
                background: rgba(60, 64, 198, 0.03);
            }

            @media print {
                .no-print { display: none !important; }
                .content-page { margin: 0; padding: 0; background: #ffffff; }
                .accomplishment-hero { background: #ffffff; color: #000; box-shadow: none; border-radius: 0; }
                .accomplishment-title { color: #000; }
                .accomplishment-hero-meta { color: #000; }
                .accomplishment-card { box-shadow: none; border: none; }
            }
        </style>
    </head>

    <body>
        <div id="wrapper">
            <?php include('includes/top-bar.php'); ?>
            <?php include('includes/sidebar.php'); ?>

            <div class="content-page">
                <div class="content">
                    <div class="container-fluid accomplishment-shell">
                        <div class="row no-print">
                            <div class="col-12">
                                <div class="accomplishment-hero">
                                    <div class="accomplishment-hero-body">
                                        <div class="accomplishment-eyebrow"><i class="mdi mdi-target"></i> Objective Accomplishments</div>
                                        <h1 class="accomplishment-title">Accomplishments by Objective</h1>
                                        <p class="accomplishment-hero-meta"><strong>KRA:</strong> <?= htmlspecialchars($kraTitle, ENT_QUOTES, 'UTF-8'); ?></p>
                                        <p class="accomplishment-hero-meta"><strong>Objective:</strong> <?= htmlspecialchars($objectiveText, ENT_QUOTES, 'UTF-8'); ?></p>
                                        <div class="accomplishment-hero-actions">
                                            <a href="javascript:window.print()" class="btn btn-light">
                                                <i class="mdi mdi-printer-outline"></i> Print
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="accomplishment-card">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped accomplishment-table mb-0" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Activity</th>
                                                        <th>Venue</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($data)) : ?>
                                                        <?php foreach ($data as $row) : ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($row->dateConducted, ENT_QUOTES, 'UTF-8'); ?></td>
                                                                <td><?= htmlspecialchars($row->activity, ENT_QUOTES, 'UTF-8'); ?></td>
                                                                <td><?= htmlspecialchars($row->venue, ENT_QUOTES, 'UTF-8'); ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else : ?>
                                                        <tr>
                                                            <td colspan="3" class="text-center">No accomplishments encoded for this objective.</td>
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
                <?php include('includes/footer.php'); ?>
            </div>
        </div>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
    </body>
</html>
