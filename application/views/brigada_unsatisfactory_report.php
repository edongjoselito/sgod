<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Unsatisfactory Facilities Report</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>">
    <style>
        th,
        td {
            vertical-align: middle !important;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="card shadow">
        
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0 text-white"><strong>Unsatisfactory Facilities Report</strong></h4>
            </div>

            <div class="card-body">
                <?php if (empty($unsatisfactory)): ?>
                    <div class="alert alert-success">All facilities are marked satisfactory. No items to display.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Facility Name</th>
                                    <th>Remarks</th>
                                    <th>Nature of Improvement Needed</th>
                                    <th>Material Resources Needed</th>
                                    <th>Manpower Needed</th>
                                    <!-- <th>Inspection Date</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($unsatisfactory as $item): ?>
                                    <tr>
                                        <td><?= $item->facility_name ?></td>
                                        <td><?= $item->remarks ?></td>
                                        <td><?= $item->improvement_needed ?></td>
                                        <td><?= $item->material_resources ?></td>
                                        <td><?= $item->manpower_needed ?></td>
                                        <!-- <td><?= date('F j, Y', strtotime($item->inspection_date)) ?></td> -->
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <div class="text-right mt-3">
                    <a href="<?= base_url('Brigada') ?>" class="btn btn-secondary">← Back to Inspection Form</a>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>