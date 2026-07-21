<!DOCTYPE html>
<html>

<head>
    <title>Brigada Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .modal-header {
            background-color: rgb(112, 163, 239);
            color: white;
        }

        .modal-footer .btn {
            min-width: 100px;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="mb-0">Brigada Eskwela Daily Summary Report</h3>
                <small class="text-muted">
                    Please ensure that daily reports are encoded on or before <strong>4:00 PM</strong> to allow time for consolidation and reporting to the regional office.
                </small>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">➕ Add Record</button>
        </div>


        <!-- Flash messages (if needed) -->
        <?php if ($this->session->flashdata('message')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('message') ?></div>
        <?php endif; ?>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Day</th>
                    <th>Resource Generated</th>
                    <th>No. of Volunteers</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $row): ?>
                    <tr>
                        <td><?= $row->be_day ?></td>
                        <td>₱<?= number_format($row->resource_generated, 2) ?></td>
                        <td><?= $row->no_volunteers ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm editBtn"
                                data-id="<?= $row->id ?>"
                                data-day="<?= $row->be_day ?>"
                                data-resource="<?= $row->resource_generated ?>"
                                data-volunteers="<?= $row->no_volunteers ?>"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal">✏️ Edit</button>

                            <a href="<?= base_url('Brigada/DeleteDailyReport/' . $row->id) ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Delete this entry?')">🗑️ Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="post" action="<?= base_url('Brigada/SaveDailyReport') ?>" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Brigada Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Day</label>

                        <select class="form-control" name="be_day" required>
                            <option></option>
                            <option value="Day 1">Day 1</option>
                            <option value="Day 2">Day 2</option>
                            <option value="Day 3">Day 3</option>
                            <option value="Day 4">Day 4</option>
                            <option value="Day 5">Day 5</option>
                            <option value="Day 6">Day 6</option>

                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Resource Generated <cite style="font-size:10px; color:#fff; background-color:red; padding:3px 5px; border-radius: 3px;">Don’t use a comma (e.g., 2000 instead of 2,000)</cite> </label>
                        <input type="number" step="0.01" class="form-control" name="resource_generated" required>
                    </div>
                    <div class="mb-3">
                        <label>No. of Volunteers</label>
                        <input type="number" class="form-control" name="no_volunteers" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">💾 Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="post" id="editForm" class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">Edit Brigada Eskwela Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label>Day</label>
                        <input type="text" class="form-control" name="be_day" id="edit_day" required maxlength="5">
                    </div>
                    <div class="mb-3">
                        <label>Resource Generated</label>
                        <input type="number" step="0.01" class="form-control" name="resource_generated" id="edit_resource" required>
                    </div>
                    <div class="mb-3">
                        <label>No. of Volunteers</label>
                        <input type="number" class="form-control" name="no_volunteers" id="edit_volunteers" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">💾 Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.editBtn').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('edit_id').value = this.dataset.id;
                document.getElementById('edit_day').value = this.dataset.day;
                document.getElementById('edit_resource').value = this.dataset.resource;
                document.getElementById('edit_volunteers').value = this.dataset.volunteers;

                // Change form action dynamically
                document.getElementById('editForm').action = "<?= base_url('Brigada/UpdateDailyReport/') ?>" + this.dataset.id;
            });
        });
    </script>

</body>

</html>