<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Brigada Eskwela Form 3</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>">
    <style>
        th,
        td {
            vertical-align: middle !important;
        }
    </style>
</head>

<body>
    <div class="container-fluid mt-4 px-4">
        <div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0 text-white"><strong>RESOURCE MOBILIZATION FORM</strong></h4>
            </div>
            <div class="card-body">
                <?= $this->session->flashdata('msg'); ?>

                <!-- MATERIALS TABLE -->
                <h5 class="mt-3 mb-2"><strong>Materials</strong></h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Facility</th>
                                <th>Materials Needed</th>
                                <th>Quantity</th>
                                <th>Key Persons/Organization to be Tapped</th>
                                <th>Strategies/Plan of Action</th>
                                <th>Person Responsible</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($facilities as $item): ?>
                                <?php if (!empty($item->material_resources)): ?>
                                    <tr>
                                        <td><?= $item->name ?></td>
                                        <td><?= $item->material_resources ?></td>
                                        <td><?= $item->quantity ?></td>
                                        <td><?= $item->key_persons ?></td>
                                        <td><?= $item->strategy ?></td>
                                        <td><?= $item->person_responsible ?></td>
                                        <td><?= $item->status ?></td>
                                        <td><?= $item->remarks ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- MANPOWER TABLE -->
                <h5 class="mt-4 mb-2"><strong>Labor / Manpower</strong></h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Facility</th>
                                <th>Manpower Needed</th>
                                <th>Quantity</th>
                                <th>Key Persons/Organization to be Tapped</th>
                                <th>Strategies/Plan of Action</th>
                                <th>Person Responsible</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($facilities as $item): ?>
                                <?php if (!empty($item->manpower_needed)): ?>
                                    <tr>
                                        <td><?= $item->name ?></td>
                                        <td><?= $item->manpower_needed ?></td>
                                        <td><?= $item->quantity ?></td>
                                        <td><?= $item->key_persons ?></td>
                                        <td><?= $item->strategy ?></td>
                                        <td><?= $item->person_responsible ?></td>
                                        <td><?= $item->status ?></td>
                                        <td><?= $item->remarks ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</body>

</html>