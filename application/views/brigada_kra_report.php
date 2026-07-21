<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>SCHOOL WORK PLAN REPORT</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>">
    <style>
        th,
        td {
            vertical-align: middle !important;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0 text-white"><strong>SCHOOL WORK PLAN REPORT</strong></h4>
                <button onclick="window.print()" class="btn btn-light btn-sm no-print">🖨 Print</button>
            </div>
            <div class="card-body">
                <?php if (empty($kra_plans)): ?>
                    <div class="alert alert-info">No KRA planning data submitted yet.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th>KRA</th>
                                    <th>Activities</th>
                                    <th>Timeline</th>
                                    <th>Person Responsible</th>
                                    <th>Materials Needed</th>
                                    <th>Budget</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($kra_plans as $row): ?>
                                    <tr>
                                        <td><?= $row->kra_name ?></td>
                                        <td><?= $row->activities ?></td>
                                        <td><?= $row->timeline ?></td>
                                        <td><?= $row->person_responsible ?></td>
                                        <td><?= $row->materials_needed ?></td>
                                        <td><?= $row->budget ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <div class="text-right mt-3 no-print">
                    <a href="<?= base_url('Brigada/kra_planning_form') ?>" class="btn btn-outline-primary">← Back to Planning Form</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>