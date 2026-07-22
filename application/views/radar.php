<?php
if (!isset($title)) {
    $title = 'RADAR';
}
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
    </head>
    <body>
        <div id="wrapper">
            <?php include('includes/top-bar.php'); ?>
            <?php include('includes/sidebar.php'); ?>
            <div class="content-page">
                <div class="content">
                    <div class="container-fluid">
                        <!-- RADAR placeholder page -->
                    </div>
                </div>
                <?php include('includes/footer.php'); ?>
            </div>
        </div>
        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
    </body>
</html>
