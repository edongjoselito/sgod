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
                    <a href="<?= base_url(); ?>Page/school_needs_list" class="btn btn-sm btn-light">← Back to School List</a>
                    <h1><?= $esc($title); ?></h1>
                    <p>View and add needs for this school.</p>
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

            <section class="card card-panel p-4 mb-4">
                <h5 class="mb-3"><?= !empty($editingNeed) ? 'Edit Need' : 'Add Need'; ?></h5>
                <form method="post" action="<?= base_url(); ?>Page/school_needs/<?= (int) $school->schoolID; ?>">
                    <input type="hidden" name="save_need" value="1">
                    <input type="hidden" name="need_id" value="<?= (int) ($editingNeed->id ?? 0); ?>">
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label>Need / Item</label>
                            <input type="text" class="form-control" name="need" required value="<?= $esc($editingNeed->need ?? ''); ?>" placeholder="Enter need or item">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Quantity</label>
                            <input type="number" class="form-control" name="quantity" min="1" value="<?= (int) ($editingNeed->quantity ?? 1); ?>">
                        </div>
                        <div class="col-md-5 mb-3">
                            <label>Remarks</label>
                            <input type="text" class="form-control" name="remarks" value="<?= $esc($editingNeed->remarks ?? ''); ?>" placeholder="Optional remarks">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><?= !empty($editingNeed) ? 'Update Need' : 'Add Need'; ?></button>
                    <?php if (!empty($editingNeed)): ?>
                        <a href="<?= base_url(); ?>Page/school_needs/<?= (int) $school->schoolID; ?>" class="btn btn-secondary">Cancel</a>
                    <?php endif; ?>
                </form>
            </section>

            <section class="card card-panel p-4">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Need / Item</th>
                                <th>Quantity</th>
                                <th>Remarks</th>
                                <th>Date Added</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($needs)): ?>
                                <?php foreach ($needs as $index => $need): ?>
                                    <tr>
                                        <td><?= (int) $index + 1; ?></td>
                                        <td><?= $esc($need->need); ?></td>
                                        <td><?= (int) $need->quantity; ?></td>
                                        <td><?= $esc($need->remarks ?? '—'); ?></td>
                                        <td><?= $esc($need->created_at); ?></td>
                                        <td class="d-flex gap-2">
                                            <a href="<?= base_url(); ?>Page/school_needs/<?= (int) $school->schoolID; ?>?edit=<?= (int) $need->id; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <a href="<?= base_url(); ?>Page/school_needs/<?= (int) $school->schoolID; ?>?delete=<?= (int) $need->id; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this need?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="empty">No needs recorded yet for this school.</td></tr>
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
