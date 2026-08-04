<?php
$requirements = isset($requirements) && is_array($requirements) ? $requirements : array();
$nextOrder = isset($nextOrder) ? max(1, (int) $nextOrder) : 1;
$escape = function($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
?>
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
    <link href="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <style>
        .pbei-hero { margin: 20px 0 24px; padding: 30px; border-radius: 18px; color: #fff; background: linear-gradient(135deg, #3c40c6, #6f74ff); }
        .pbei-hero h2 { color: #fff; margin: 10px 0; }
        .pbei-hero p { margin: 0; opacity: .9; max-width: 760px; }
        .pbei-eyebrow { font-size: .78rem; text-transform: uppercase; letter-spacing: .08em; font-weight: 700; opacity: .85; }
        .table td { vertical-align: middle; }
        .requirement-name { font-weight: 600; color: #343a40; }
    </style>
</head>
<body>
<div id="wrapper">
    <?php include('includes/top-bar.php'); ?>
    <?php include('includes/sidebar.php'); ?>
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="pbei-hero">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="pbei-eyebrow"><i class="mdi mdi-trophy-outline"></i> PBEI Recognition</div>
                            <h2>Requirements Register</h2>
                        </div>
                        <div class="col-md-4 text-md-right mt-3 mt-md-0">
                            <button type="button" class="btn btn-light" data-toggle="modal" data-target="#requirementModal" onclick="openRequirementForm()"><i class="mdi mdi-plus"></i> Add Requirement</button>
                        </div>
                    </div>
                </div>

                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert"><?= $escape($this->session->flashdata('success')); ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('danger')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert"><?= $escape($this->session->flashdata('danger')); ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div><h4 class="header-title mb-1">Recognition Requirements</h4></div>
                            <span class="badge badge-primary badge-pill px-3 py-2"><?= count($requirements); ?> item<?= count($requirements) === 1 ? '' : 's'; ?></span>
                        </div>
                        <div class="table-responsive">
                            <table id="requirementsTable" class="table table-hover w-100">
                                <thead><tr><th>Order</th><th>Requirement</th><th class="text-right">Actions</th></tr></thead>
                                <tbody>
                                <?php foreach ($requirements as $row): ?>
                                    <tr>
                                        <td><?= (int) $row->sort_order; ?></td>
                                        <td><div class="requirement-name"><?= $escape($row->requirement); ?></div><?php if (trim((string) $row->description) !== ''): ?><small class="text-muted"><?= $escape($row->description); ?></small><?php endif; ?></td>
                                        <td class="text-right">
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#requirementModal" onclick='openRequirementForm(<?= json_encode(array("id" => (int) $row->id, "requirement" => $row->requirement, "description" => $row->description, "sort_order" => (int) $row->sort_order), JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG); ?>)'><i class="mdi mdi-pencil"></i> Edit</button>
                                            <form method="post" action="<?= base_url(); ?>Page/pbei_requirement_delete" class="d-inline" onsubmit="return confirm('Delete this requirement?');"><input type="hidden" name="id" value="<?= (int) $row->id; ?>"><button type="submit" class="btn btn-sm btn-outline-danger"><i class="mdi mdi-delete"></i></button></form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('includes/footer.php'); ?>
    </div>
</div>

<div id="requirementModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document"><div class="modal-content">
        <form method="post" action="<?= base_url(); ?>Page/pbei_requirement_save">
            <div class="modal-header"><h5 class="modal-title" id="requirementModalTitle">Add PBEI Requirement</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
                <input type="hidden" name="id" id="requirementId">
                <div class="form-group"><label>Order</label><input type="number" class="form-control" name="sort_order" id="requirementOrder" min="1" value="<?= $nextOrder; ?>"></div>
                <div class="form-group"><label>Requirement <span class="text-danger">*</span></label><textarea class="form-control" name="requirement" id="requirementName" rows="3" maxlength="255" required></textarea></div>
                <div class="form-group"><label>Description (optional)</label><textarea class="form-control" name="description" id="requirementDescription" rows="3"></textarea></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save Requirement</button></div>
        </form>
    </div></div>
</div>

<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
<script>
function openRequirementForm(item) {
    item = item || { id: '', requirement: '', description: '', sort_order: <?= $nextOrder; ?> };
    document.getElementById('requirementModalTitle').textContent = item.id ? 'Edit PBEI Requirement' : 'Add PBEI Requirement';
    document.getElementById('requirementId').value = item.id;
    document.getElementById('requirementName').value = item.requirement;
    document.getElementById('requirementDescription').value = item.description;
    document.getElementById('requirementOrder').value = item.sort_order;
}
$(function() { $('#requirementsTable').DataTable({ pageLength: 25, order: [[0, 'asc']] }); });
</script>
</body>
</html>
