<?php include('templates/head.php'); ?>
<link href="<?= base_url(); ?>assets/libs/custombox/custombox.min.css" rel="stylesheet" type="text/css">

<?php include('templates/header.php'); ?>

<style>
    .modal-header {
        background-color: rgb(112, 163, 239);
        color: #fff;
    }
    .money { text-align: right; white-space: nowrap; }
    .btn-min { min-width: 110px; }
    .note-badge {
        font-size: 11px;
        color: #fff;
        background: #dc3545;
        padding: 3px 6px;
        border-radius: 3px;
        display: inline-block;
        margin-left: 6px;
    }
</style>
<div class="content-page">
    <div class="content">

        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                        <div class="d-flex flex-column">
    <h4 class="page-title mb-0">Brigada Eskwela Daily Summary Report</h4>
    <small class="text-muted mt-1">
        Please ensure that daily reports are encoded on or before <strong>4:00 PM</strong>
        to allow time for consolidation and reporting to the regional office.
    </small>
    </div>


                        <div>
                            <a href="javascript:void(0)" class="btn btn-primary waves-effect waves-light"
                               data-toggle="modal" data-target="#addModal">
                                <i class="mdi mdi-plus"></i> Add Record
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <!-- Flash -->
            <?php if ($this->session->flashdata('success')) : ?>
                <?= '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>' . $this->session->flashdata('success') . '</div>'; ?>
            <?php endif; ?>

            <?php if ($this->session->flashdata('danger')) : ?>
                <?= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>' . $this->session->flashdata('danger') . '</div>'; ?>
            <?php endif; ?>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-responsive">

                            <h4 class="header-title mb-4">Daily Encoded Records</h4>

                            <table id="datatable" class="table table-bordered dt-responsive nowrap"
                                   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="width:120px;">Day</th>
                                        <th style="width:200px;">Resource Generated</th>
                                        <th style="width:160px;">No. of Volunteers</th>
                                        <th style="width:200px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($records)): ?>
                                        <?php foreach ($records as $row): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row->be_day); ?></td>
                                                <td class="money">₱<?= number_format((float)$row->resource_generated, 2); ?></td>
                                                <td><?= (int)$row->no_volunteers; ?></td>
                                                <td>
                                                    <button type="button"
                                                        class="btn btn-warning btn-sm waves-effect waves-light editBtn"
                                                        data-id="<?= $row->id ?>"
                                                        data-day="<?= htmlspecialchars($row->be_day, ENT_QUOTES, 'UTF-8'); ?>"
                                                        data-resource="<?= $row->resource_generated ?>"
                                                        data-volunteers="<?= $row->no_volunteers ?>"
                                                        data-toggle="modal"
                                                        data-target="#editModal">
                                                        <i class="mdi mdi-pencil"></i> Edit
                                                    </button>

                                                    <a href="<?= base_url('Brigada/DeleteDailyReport/' . $row->id) ?>"
                                                       class="btn btn-danger btn-sm waves-effect waves-light"
                                                       onclick="return confirm('Delete this entry?')">
                                                       <i class="mdi mdi-delete"></i> Delete
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

        </div><!-- container-fluid -->
    </div><!-- content -->


    <!-- =======================
         ADD MODAL (Bootstrap 4)
    ======================== -->
    <div id="addModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" action="<?= base_url('Brigada/SaveDailyReport') ?>" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Brigada Record</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Day</label>
                        <select class="form-control" name="be_day" required>
                            <option value=""></option>
                            <option value="Day 1">Day 1</option>
                            <option value="Day 2">Day 2</option>
                            <option value="Day 3">Day 3</option>
                            <option value="Day 4">Day 4</option>
                            <option value="Day 5">Day 5</option>
                            <option value="Day 6">Day 6</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>
                            Resource Generated
                            <span class="note-badge">Don’t use comma (e.g., 2000 not 2,000)</span>
                        </label>
                        <input type="number" step="0.01" class="form-control" name="resource_generated" required>
                    </div>

                    <div class="form-group">
                        <label>No. of Volunteers</label>
                        <input type="number" class="form-control" name="no_volunteers" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect waves-light btn-min">
                        <i class="mdi mdi-content-save"></i> Save
                    </button>
                    <button type="button" class="btn btn-secondary waves-effect waves-light btn-min" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- =======================
         EDIT MODAL (Bootstrap 4)
    ======================== -->
    <div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" id="editForm" class="modal-content">
                <div class="modal-header" style="background:#ffc107;color:#111;">
                    <h5 class="modal-title">Edit Brigada Eskwela Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">

                    <div class="form-group">
                        <label>Day</label>
                        <select class="form-control" name="be_day" id="edit_day" required>
                            <option value=""></option>
                            <option value="Day 1">Day 1</option>
                            <option value="Day 2">Day 2</option>
                            <option value="Day 3">Day 3</option>
                            <option value="Day 4">Day 4</option>
                            <option value="Day 5">Day 5</option>
                            <option value="Day 6">Day 6</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Resource Generated</label>
                        <input type="number" step="0.01" class="form-control" name="resource_generated" id="edit_resource" required>
                    </div>

                    <div class="form-group">
                        <label>No. of Volunteers</label>
                        <input type="number" class="form-control" name="no_volunteers" id="edit_volunteers" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect waves-light btn-min">
                        <i class="mdi mdi-content-save"></i> Update
                    </button>
                    <button type="button" class="btn btn-secondary waves-effect waves-light btn-min" data-dismiss="modal">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>


</div><!-- content-page -->

<?php include('templates/footer.php'); ?>

<script>
    // Uses jQuery (theme usually includes it already)
    $(document).on('click', '.editBtn', function () {
        var id = $(this).data('id');
        var day = $(this).data('day');
        var resource = $(this).data('resource');
        var volunteers = $(this).data('volunteers');

        $('#edit_id').val(id);
        $('#edit_day').val(day);
        $('#edit_resource').val(resource);
        $('#edit_volunteers').val(volunteers);

        $('#editForm').attr('action', "<?= base_url('Brigada/UpdateDailyReport/'); ?>" + id);
    });
</script>
