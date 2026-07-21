<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Brigada Facilities Inspection</title>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
    <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />
    <style>
        th,
        td {
            vertical-align: middle !important;
        }

        input[type="text"],
        textarea {
            min-width: 200px;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="container-fluid mt-3 px-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0 text-white"><strong>PHYSICAL FACILITIES AND MAINTENANCE NEEDS ASSESSMENT FORM</strong></h4>
            </div>
            <div class="card-body">
                <?= $this->session->flashdata('msg'); ?>

                <div class="mb-4">
                    <p class="mb-0">
                        <strong>Instruction:</strong> Conduct an ocular inspection of the school physical facilities listed below.
                        Then provide the needed information to facilitate the improvement to be done on these identified facilities.
                    </p>
                </div>

                <form method="post" action="<?= base_url('Brigada/' . ($mode == 'update' ? 'update' : 'submit')) ?>">
                    <input type="hidden" name="inspector_name" value="<?= $this->session->userdata('username'); ?>">

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Facilities</th>
                                    <th class="text-center">Satisfactory</th>
                                    <th class="text-center">Unsatisfactory</th>
                                    <th>Remarks</th>
                                    <th>Nature of Improvement Needed</th>
                                    <th>Material Resources Needed</th>
                                    <th>Manpower Needed</th>
                                    <th>Manage</th>
                                </tr>
                            </thead>

                            <?php
                            $inspection_lookup = [];
                            foreach ($inspection_data ?? [] as $item) {
                                $inspection_lookup[$item->facility_id] = $item;
                            }
                            ?>

                            <tbody>
                                <?php foreach ($facilities as $index => $facility):
                                    $existing = $inspection_lookup[$facility->id] ?? null;
                                ?>
                                    <tr>
                                        <td>

                                            <?= $facility->name ?>
                                            <input type="hidden" name="facility_id[]" value="<?= $facility->id ?>">
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="condition_<?= $index ?>" value="satisfactory"
                                                <?= isset($existing) && $existing->is_satisfactory ? 'checked' : '' ?> required>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="condition_<?= $index ?>" value="unsatisfactory"
                                                <?= isset($existing) && !$existing->is_satisfactory ? 'checked' : '' ?>>
                                        </td>
                                        <td><textarea name="remarks_<?= $index ?>" class="form-control"><?= $existing->remarks ?? '' ?></textarea></td>
                                        <td><textarea name="improvement_<?= $index ?>" class="form-control"><?= $existing->improvement_needed ?? '' ?></textarea></td>
                                        <td><textarea name="materials_<?= $index ?>" class="form-control"><?= $existing->material_resources ?? '' ?></textarea></td>
                                        <td>
                                            <textarea name="manpower_<?= $index ?>" class="form-control"><?= $existing->manpower_needed ?? '' ?></textarea>
                                            <input type="hidden" name="quantity_<?= $index ?>" value="<?= $existing->quantity ?? '' ?>">
                                            <input type="hidden" name="key_persons_<?= $index ?>" value="<?= $existing->key_persons ?? '' ?>">
                                            <input type="hidden" name="strategy_<?= $index ?>" value="<?= $existing->strategy ?? '' ?>">
                                            <input type="hidden" name="person_responsible_<?= $index ?>" value="<?= $existing->person_responsible ?? '' ?>">
                                            <input type="hidden" name="status_<?= $index ?>" value="<?= $existing->status ?? '' ?>">
                                            <input type="hidden" name="remarks_form3_<?= $index ?>" value="<?= $existing->remarks_form3 ?? '' ?>">
                                        </td>
                                        
                                        <td class="text-center">
                                            <?php if (isset($existing) && !$existing->is_satisfactory): ?>
                                                <button type="button" class="btn btn-sm btn-danger open-form3-modal"
                                                    data-id="<?= $existing->id ?>">
                                                    Plan Action
                                                </button>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="<?= base_url('Brigada/unsatisfactory_report') ?>" class="btn btn-outline-danger mr-2 mb-2">
                                View Unsatisfactory Items Report
                            </a>
                            <a href="<?= base_url('Brigada/satisfactory_report') ?>" class="btn btn-outline-success mr-2 mb-2">
                                View Satisfactory Items Report
                            </a>
                            <!-- <a href="<?= base_url('Brigada/form3') ?>" class="btn btn-outline-primary mr-2 mb-2">
                                Open Brigada Eskwela Form 3
                            </a> -->
                            <a href="<?= base_url('Brigada/form1_report') ?>" class="btn btn-outline-success mr-2 mb-2">
                                BE Form 01 Report
                            </a>
                            <a href="<?= base_url('Brigada/form2_report') ?>" class="btn btn-outline-success mr-2 mb-2">
                                School Work Plan Report
                            </a>
                            <a href="<?= base_url('Brigada/form3_report') ?>" class="btn btn-outline-success mr-2 mb-2">
                                Resource Mobilization Report
                            </a>
                        </div>

                        <button type="submit" class="btn btn-<?= $mode == 'update' ? 'warning' : 'success' ?>">
                            <?= $mode == 'update' ? 'Update Inspection' : 'Submit Inspection' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- FORM 3 MODAL -->

    <div class="modal fade" id="form3Modal" tabindex="-1" role="dialog" aria-labelledby="form3ModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">

        data-bs-backdrop="static" data-bs-keyboard="false">

        <div class="modal-dialog" role="document">
            <form method="post" action="<?= base_url('Brigada/update_form3_fields') ?>">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="form3ModalLabel">Update Action Plan</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="inspection_id" id="modal_inspection_id">

                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="text" name="quantity" id="modal_quantity" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Key Persons</label>
                            <textarea name="key_persons" id="modal_key_persons" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Strategy</label>
                            <textarea name="strategy" id="modal_strategy" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Person Responsible</label>
                            <textarea name="person_responsible" id="modal_person_responsible" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <input type="text" name="status" id="modal_status" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea name="remarks_form3" id="modal_remarks" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Update</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script> -->
    <!-- Vendor js -->
    <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>

    <script src="<?= base_url(); ?>assets/libs/custombox/custombox.min.js"></script>

    <!-- App js -->
    <script src="<?= base_url(); ?>assets/js/app.min.js"></script>

    <script>
        $(document).on('click', '.open-form3-modal', function() {
            const id = $(this).data('id');
            $('#form3Modal input, #form3Modal textarea').val('');
            $('#modal_inspection_id').val(id);

            // Open the modal manually
            $('#form3Modal').modal('show');

            // Load data
            $.ajax({
                url: '<?= base_url('Brigada/get_form3_details') ?>',
                method: 'POST',
                data: {
                    inspection_id: id
                },
                dataType: 'json',
                success: function(response) {
                    $('#modal_quantity').val(response.quantity);
                    $('#modal_key_persons').val(response.key_persons);
                    $('#modal_strategy').val(response.strategy);
                    $('#modal_person_responsible').val(response.person_responsible);
                    $('#modal_status').val(response.status);
                    $('#modal_remarks').val(response.remarks_form3); // ✅ correct ID used here
                }
            });
        });
    </script>

</body>

</html>