<?php
$esc = function($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$formatAmount = function($value) { return !empty($value) ? '₱' . number_format((float) $value, 2) : '—'; };
?>
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
        .card-panel .table th { border-top:0; color:#68708a; font-size:.72rem; letter-spacing:.06em; text-transform:uppercase; white-space:nowrap; }
        .card-panel .table td { vertical-align:middle; }
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
                    <h1>Tax Incentive Documentary Requirements</h1>
                    <p>Manage documentary requirements for tax incentives.</p>
                </div>
                <button class="btn btn-light" type="button" data-toggle="modal" data-target="#requirementModal">
                    <i class="mdi mdi-plus"></i> Add New
                </button>
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
                            <tr><th>#</th><th>Requirement</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($requirements)): ?>
                                <?php foreach ($requirements as $index => $item): ?>
                                    <tr>
                                        <td><?= (int) $index + 1; ?></td>
                                        <td><?= $esc($item->requirement ?? '—'); ?></td>
                                        <td class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary js-edit-requirement" data-id="<?= (int) $item->id; ?>" data-requirement="<?= $esc($item->requirement); ?>">Edit</button>
                                            <a href="<?= base_url(); ?>Brigada/tax_incentive_requirements_delete/<?= (int) $item->id; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this requirement?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="empty">No documentary requirements recorded yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div><?php include(__DIR__ . '/../includes/footer.php'); ?></div>
</div>

<div class="modal fade" id="requirementModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= !empty($editingRequirement) ? 'Edit Requirement' : 'Add New Requirement'; ?></h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form method="post" action="<?= base_url(); ?>Brigada/tax_incentive_requirements_save">
                <div class="modal-body">
                    <input type="hidden" name="requirement_id" value="<?= (int) ($editingRequirement->id ?? 0); ?>">
                    <div class="form-group mb-3">
                        <label>Requirement</label>
                        <input type="text" class="form-control" name="requirement" required value="<?= $esc($editingRequirement->requirement ?? ''); ?>" placeholder="Enter requirement name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><?= !empty($editingRequirement) ? 'Update' : 'Save'; ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
<script>
$(document).ready(function() {
    $('.js-edit-requirement').on('click', function() {
        var id = $(this).data('id');
        var requirement = $(this).data('requirement');
        $('input[name="requirement_id"]').val(id);
        $('input[name="requirement"]').val(requirement);
        $('#requirementModal .modal-title').text('Edit Requirement');
        $('#requirementModal').modal('show');
    });

    $('#requirementModal').on('hidden.bs.modal', function() {
        $('input[name="requirement_id"]').val('0');
        $('input[name="requirement"]').val('');
        $('#requirementModal .modal-title').text('Add New Requirement');
    });
});
</script>
</body>
</html>
