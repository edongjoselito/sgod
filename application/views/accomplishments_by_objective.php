<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <?php include('includes/page-title.php'); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div id="wrapper">
        <?php include('includes/top-bar.php'); ?>
        <?php include('includes/sidebar.php'); ?>
        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Accomplishments for Objective</h4>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Activity</th>
                                                    <th>Date Conducted</th>
                                                    <th>Venue</th>
                                                    <th>Section</th>
                                                    <th>Encoder</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($data)) : ?>
                                                    <?php foreach ($data as $row) : ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($row->activity, ENT_QUOTES, 'UTF-8'); ?></td>
                                                            <td><?= htmlspecialchars($row->dateConducted, ENT_QUOTES, 'UTF-8'); ?></td>
                                                            <td><?= htmlspecialchars($row->venue, ENT_QUOTES, 'UTF-8'); ?></td>
                                                            <td><?= htmlspecialchars($row->section, ENT_QUOTES, 'UTF-8'); ?></td>
                                                            <td><?= htmlspecialchars($row->encoder, ENT_QUOTES, 'UTF-8'); ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center">No accomplishments encoded for this objective.</td>
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
