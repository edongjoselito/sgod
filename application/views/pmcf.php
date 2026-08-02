<?php
if (!isset($title)) {
    $title = 'PMCF';
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <?php include('includes/page-title.php'); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />
        <link href="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <style>
        .pmcf-hero {
            background: linear-gradient(135deg, #2c3e50 0%, #4ca1af 100%);
            color: #fff;
            padding: 1.5rem;
            border-radius: .5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,.1);
        }
        .pmcf-hero h4 { color: #fff; margin: 0; font-weight: 600; }
        .pmcf-card {
            border: 0;
            border-radius: .5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,.06);
            margin-bottom: 1.5rem;
        }
        .btn-pmcf {
            background: linear-gradient(135deg, #4ca1af 0%, #2c3e50 100%);
            border: 0;
            color: #fff;
            padding: .5rem 1.5rem;
            border-radius: .375rem;
            font-weight: 600;
            transition: opacity .2s;
        }
        .btn-pmcf:hover { opacity: .9; color: #fff; }
        .table th { background: #f8f9fa; font-weight: 600; white-space: nowrap; }
        @media (max-width: 767.98px) {
            .pmcf-hero { padding: 1rem; }
            .pmcf-hero h4 { font-size: 1.25rem; }
            .pmcf-hero .btn-pmcf { width: 100%; margin-top: .5rem; }
            .pmcf-hero .d-flex { flex-direction: column; align-items: flex-start !important; }
        }
    </style>
    </head>
    <body>
        <div id="wrapper">
            <?php include('includes/top-bar.php'); ?>
            <?php include('includes/sidebar.php'); ?>
            <div class="content-page">
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box pmcf-hero d-flex justify-content-between align-items-center">
                                    <h4 class="page-title">PMCF Records</h4>
                                    <div>
                                        <a href="<?= base_url(); ?>Page/pmcf_report" target="_blank" class="btn btn-light mr-2">Print Report</a>
                                        <a href="<?= base_url(); ?>Page/pmcf_add" class="btn btn-pmcf">Add New</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($this->session->flashdata('success')): ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')): ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-12">
                                <div class="card pmcf-card">
                                    <div class="card-body table-responsive">
                                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Teacher Observed</th>
                                                    <th>Grade Level</th>
                                                    <th>Section</th>
                                                    <th>District</th>
                                                    <th>School</th>
                                                    <th>Term</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Subject Area</th>
                                                    <th>Supervisor</th>
                                                    <th>Designation</th>
                                                    <th>Coaching</th>
                                                    <th>Progress</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (isset($records) && $records): ?>
                                                <?php $i = 1; foreach ($records as $row): ?>
                                                <tr>
                                                    <td><?= $i++; ?></td>
                                                    <td><?= htmlspecialchars((string) $row->teacher_observed); ?></td>
                                                    <td><?= htmlspecialchars((string) $row->grade_level); ?></td>
                                                    <td><?= htmlspecialchars((string) $row->section); ?></td>
                                                    <td><?= htmlspecialchars((string) $row->district); ?></td>
                                                    <td><?= htmlspecialchars((string) $row->school); ?></td>
                                                    <td><?= htmlspecialchars((string) $row->quarter); ?></td>
                                                    <td><?= htmlspecialchars((string) $row->date_observed); ?></td>
                                                    <td><?= htmlspecialchars((string) $row->time_observed); ?></td>
                                                    <td><?= htmlspecialchars((string) $row->subject_area); ?></td>
                                                    <td><?= htmlspecialchars((string) $row->instructional_supervisor); ?></td>
                                                    <td><?= htmlspecialchars((string) $row->designation); ?></td>
                                                    <td><?= htmlspecialchars((string) $row->coaching_mechanisms); ?><?= ($row->coaching_mechanisms_others ? ' - ' . htmlspecialchars((string) $row->coaching_mechanisms_others) : ''); ?></td>
                                                    <td><?= htmlspecialchars((string) $row->progress_to_date); ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <?php include('includes/footer.php'); ?>
            </div>
        </div>
        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.responsive.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#datatable').DataTable({
                    responsive: true,
                    pageLength: 25,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']]
                });
            });
        </script>
    </body>
</html>
