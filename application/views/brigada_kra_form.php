<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>School Work Plan</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>">
    <style>
        th,
        td {
            vertical-align: middle !important;
        }

        input[type="text"] {
            width: 100%;
            min-width: 200px;
        }
    </style>
</head>

<body>
    <div class="container-fluid mt-4 px-4">
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0 text-white"><strong>SCHOOL WORK PLAN</strong></h4>
            </div>
            <div class="card-body">
                <?= $this->session->flashdata('msg'); ?>

                <!-- Instructions -->
                <div class="mb-4">
                    <h5 class="text-primary font-weight-bold">WORK AND FINANCIAL PLAN TEMPLATE</h5>
                    <p class="mb-0">
                        <strong>Instruction:</strong> Instruction: List down all the specific activities that you will be undertaking in relation to the conduct of Brigada Eskwela in your school and fill in the required information.
                    </p>
                </div>

                <form method="post" action="<?= base_url('Brigada/save_kra_plan') ?>">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th>KRA</th>
                                    <th>Activities</th>
                                    <th>Timeline</th>
                                    <th>Responsible Person/s</th>
                                    <th>Materials Needed</th>
                                    <th>Budget</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($kras as $index => $kra):
                                    $data = $lookup[$kra->id] ?? null;
                                ?>
                                    <tr>
                                        <td>
                                            <?= $kra->name ?>
                                            <input type="hidden" name="kra_id[]" value="<?= $kra->id ?>">
                                        </td>
                                        <td><input type="text" name="activities[]" class="form-control" value="<?= $data->activities ?? '' ?>"></td>
                                        <td><input type="text" name="timeline[]" class="form-control" value="<?= $data->timeline ?? '' ?>"></td>
                                        <td><input type="text" name="person_responsible[]" class="form-control" value="<?= $data->person_responsible ?? '' ?>"></td>
                                        <td><input type="text" name="materials_needed[]" class="form-control" value="<?= $data->materials_needed ?? '' ?>"></td>
                                        <td><input type="text" name="budget[]" class="form-control" value="<?= $data->budget ?? '' ?>"></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="<?= base_url('Brigada/kra_report') ?>" class="btn btn-outline-secondary">
                            SCHOOL WORK PLAN REPORT
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Save KRA Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>