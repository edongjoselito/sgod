<?php $esc = function($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $esc($title); ?></title>
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/dashboard-unified.css" rel="stylesheet">
    <style>
        body { background:#f4f8fc; }
        .page-shell { padding:30px 0; }
        .page-hero { display:flex; align-items:center; justify-content:space-between; gap:24px; padding:28px; border-radius:20px; color:#fff; background:linear-gradient(135deg,#272b8c,#3c40c6); box-shadow:0 18px 40px rgba(39,43,140,.16); }
        .page-hero h1 { margin:8px 0; color:#fff; font-size:2rem; font-weight:700; }
        .page-hero p { margin:0; color:rgba(255,255,255,.82); }
        .card-panel { margin-top:24px; border:0; border-radius:18px; box-shadow:0 16px 38px rgba(15,23,42,.08); overflow:hidden; }
        .empty { padding:42px; text-align:center; color:#6c757d; }
    </style>
</head>
<body class="dashboard-root-theme">
<div id="wrapper">
    <?php include(__DIR__ . '/../includes/top-bar.php'); ?>
    <?php include(__DIR__ . '/../includes/sidebar.php'); ?>
    <div class="content-page"><div class="content">
        <main class="container-fluid dashboard-shell page-shell">
            <section class="page-hero">
                <div>
                    <h1>School Needs</h1>
                    <p>Select a school to view or add its needs.</p>
                </div>
            </section>

            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $esc($this->session->flashdata('success')); ?>
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('danger')): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <?= $esc($this->session->flashdata('danger')); ?>
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            <?php endif; ?>

            <section class="card card-panel p-4">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>School ID</th>
                                <th>School Name</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($schools)): ?>
                                <?php foreach ($schools as $index => $school): ?>
                                    <tr>
                                        <td><?= (int) $index + 1; ?></td>
                                        <td><?= $esc($school->schoolID); ?></td>
                                        <td><?= $esc($school->schoolName); ?></td>
                                        <td>
                                            <?= $esc($school->sitio ?? ''); ?> <?= $esc($school->brgy ?? ''); ?> <?= $esc($school->city ?? ''); ?> <?= $esc($school->province ?? ''); ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url(); ?>Page/school_needs/<?= (int) $school->schoolID; ?>" class="btn btn-sm btn-primary">Manage Needs</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="empty">No schools found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div><?php include(__DIR__ . '/../includes/footer.php'); ?></div>
</div>

<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
</body>
</html>
