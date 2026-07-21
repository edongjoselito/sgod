<?php include('templates/head.php'); ?>
<?php include('templates/header.php'); ?>

<!-- Small custom styles just for this page -->
<style>
    .brigada-card-title {
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .05rem;
    }

    .brigada-subtitle {
        font-size: 0.9rem;
        font-weight: 500;
    }

    .brigada-table-wrapper {
        max-height: 70vh;
        overflow-y: auto;
        border: 1px solid #dee2e6;
        border-radius: .35rem;
    }

    .brigada-table {
        font-size: 0.8rem;
        margin-bottom: 0;
        background-color: #fff;
    }

    .brigada-table thead th {
        position: sticky;
        top: 0;
        z-index: 5;
        background: #f1f3f5;
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
    }

    .brigada-table th,
    .brigada-table td {
        padding: .4rem .45rem;
        vertical-align: middle;
    }

    .brigada-table td {
        text-align: center;
    }

    .brigada-table td.school-col {
        text-align: left;
        font-weight: 500;
        white-space: nowrap;
    }

    .brigada-table td.idx-col {
        width: 40px;
        text-align: center;
    }

    .brigada-total-row {
        background-color: #e9ecef;
        font-weight: 600;
    }

    .brigada-total-row td {
        text-align: center;
    }

    .brigada-toolbar {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: .5rem;
        margin-bottom: .75rem;
    }

    @media (max-width: 576px) {
        .brigada-toolbar {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<!-- ============================================================== -->
<!-- Start Page Content here -->
<!-- ============================================================== -->

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <?php if ($this->session->flashdata('success')) : ?>
                <?= '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>'
                    . $this->session->flashdata('success') .
                    '</div>';
                ?>
            <?php endif; ?>

            <?php if ($this->session->flashdata('danger')) : ?>
                <?= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>'
                    . $this->session->flashdata('danger') .
                    '</div>';
                ?>
            <?php endif; ?>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="brigada-toolbar">
                                <div>
                                    <h4 class="header-title brigada-card-title mb-1">
                                        <?= date('Y'); ?> BRIGADA ESKWELA SUMMARY REPORT
                                    </h4>
                                    <span class="badge badge-success brigada-subtitle">
                                        RESOURCES GENERATED AND NUMBER OF VOLUNTEERS
                                    </span>
                                </div>

                                <!-- Export to Excel button -->
                                <button type="button"
                                    class="btn btn-sm btn-success"
                                    onclick="exportTableToExcel('brigada-table', 'brigada_summary_<?= date('Y'); ?>')">
                                    <i class="mdi mdi-file-excel-box"></i> Export to Excel
                                </button>
                            </div>

                            <div class="brigada-table-wrapper table-responsive">
                                <table class="table table-bordered table-hover brigada-table" id="brigada-table">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" colspan="2" style="text-align: center; vertical-align: middle;">School</th>
                                            <th colspan="2" class="text-center">DAY 1</th>
                                            <th colspan="2" class="text-center">DAY 2</th>
                                            <th colspan="2" class="text-center">DAY 3</th>
                                            <th colspan="2" class="text-center">DAY 4</th>
                                            <th colspan="2" class="text-center">DAY 5</th>
                                            <th colspan="2" class="text-center">DAY 6 <br />(If Necessary)</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">RESOURCES <br />GENERATED</th>
                                            <th class="text-center">NO. OF <br />VOLUNTEERS</th>
                                            <th class="text-center">RESOURCES <br />GENERATED</th>
                                            <th class="text-center">NO. OF <br />VOLUNTEERS</th>
                                            <th class="text-center">RESOURCES <br />GENERATED</th>
                                            <th class="text-center">NO. OF <br />VOLUNTEERS</th>
                                            <th class="text-center">RESOURCES <br />GENERATED</th>
                                            <th class="text-center">NO. OF <br />VOLUNTEERS</th>
                                            <th class="text-center">RESOURCES <br />GENERATED</th>
                                            <th class="text-center">NO. OF <br />VOLUNTEERS</th>
                                            <th class="text-center">RESOURCES <br />GENERATED</th>
                                            <th class="text-center">NO. OF <br />VOLUNTEERS</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $c = 1;
                                        $day_totals = [];
                                        $volunteer_totals = [];

                                        for ($i = 1; $i <= 6; $i++) {
                                            $day_totals[$i] = 0;
                                            $volunteer_totals[$i] = 0;
                                        }

                                        foreach ($school as $row) {
                                            $days = [];
                                            for ($i = 1; $i <= 6; $i++) {
                                                $days[$i] = $this->Common->two_cond_row(
                                                    'brigada_daily_report',
                                                    'schoolID',
                                                    $row->schoolID,
                                                    'be_day',
                                                    'Day ' . $i
                                                );
                                            }
                                        ?>
                                            <tr>
                                                <td class="idx-col"><?= $c++; ?></td>
                                                <td class="school-col"><?= strtoupper($row->schoolName); ?></td>

                                                <?php foreach ($days as $i => $day) : ?>
                                                    <td>
                                                        <?= !empty($day) ? number_format($day->resource_generated, 2) : '' ?>
                                                    </td>
                                                    <td>
                                                        <?= !empty($day) ? number_format($day->no_volunteers) : '' ?>
                                                    </td>
                                                    <?php
                                                    if (!empty($day)) {
                                                        $day_totals[$i] += $day->resource_generated;
                                                        $volunteer_totals[$i] += $day->no_volunteers;
                                                    }
                                                    ?>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php } ?>

                                        <!-- Totals Row -->
                                        <tr class="brigada-total-row">
                                            <td colspan="2">TOTAL</td>
                                            <?php for ($i = 1; $i <= 6; $i++) : ?>
                                                <td><?= number_format($day_totals[$i], 2); ?></td>
                                                <td><?= number_format($volunteer_totals[$i]); ?></td>
                                            <?php endfor; ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> <!-- end table wrapper -->

                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->

        </div>
        <!-- end container-fluid -->
    </div>
    <!-- end content -->

    <?php include('templates/footer.php'); ?>

    <!-- ================== Export to Excel Script ================== -->
    <script>
        function exportTableToExcel(tableID, filename = '') {
            var dataType = 'application/vnd.ms-excel';
            var tableSelect = document.getElementById(tableID);

            if (!tableSelect) return;

            // clone table to avoid messing with the DOM
            var tableClone = tableSelect.cloneNode(true);

            // Optional: remove buttons/icons inside table before export (if any)
            var buttons = tableClone.querySelectorAll('button, a');
            buttons.forEach(function(btn) {
                btn.parentNode.removeChild(btn);
            });

            var tableHTML = tableClone.outerHTML.replace(/ /g, '%20');

            // Specify file name
            filename = filename ? filename + '.xls' : 'brigada_summary.xls';

            // Create download link element
            var downloadLink = document.createElement("a");
            document.body.appendChild(downloadLink);

            if (navigator.msSaveOrOpenBlob) {
                // For IE
                var blob = new Blob(['\ufeff', tableHTML], {
                    type: dataType
                });
                navigator.msSaveOrOpenBlob(blob, filename);
            } else {
                // For other browsers
                downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
                downloadLink.download = filename;
                downloadLink.click();
            }

            // Clean up
            document.body.removeChild(downloadLink);
        }
    </script>