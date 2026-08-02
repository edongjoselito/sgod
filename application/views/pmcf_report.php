<?php
if (!isset($title)) {
    $title = 'PMCF Report';
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?= $title; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <style>
            @page {
                size: A4 landscape;
                margin: 10mm;
            }
            body {
                background: #e9ecef;
                color: #000;
                font-size: 10pt;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .print-wrapper {
                max-width: 1300px;
                margin: 20px auto;
                background: #fff;
                padding: 20px;
                box-shadow: 0 0 12px rgba(0,0,0,.08);
            }
            .report-header {
                text-align: center;
                margin-bottom: 18px;
                padding-bottom: 12px;
                border-bottom: 2px solid #2c3e50;
            }
            .report-header h3 {
                margin: 0;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 1px;
                color: #2c3e50;
            }
            .report-header p {
                margin: 5px 0 0;
                color: #555;
                font-size: 10pt;
            }
            .report-meta {
                margin-bottom: 15px;
                font-size: 9.5pt;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }
            th, td {
                border: 1px solid #333;
                padding: 5px 6px;
                font-size: 8.5pt;
                vertical-align: top;
            }
            th {
                background-color: #f0f0f0;
                font-weight: 700;
                text-align: left;
            }
            .btn-print {
                margin-bottom: 15px;
            }
            @media print {
                body { background: #fff; }
                .print-wrapper { max-width: none; margin: 0; padding: 0; box-shadow: none; }
                .btn-print { display: none; }
                .no-break { page-break-inside: avoid; }
                thead { display: table-header-group; }
            }
            @media (max-width: 767.98px) {
                .btn-print .btn { width: 100%; margin-bottom: .5rem; }
            }
        </style>
    </head>
    <body>
        <div class="container-fluid print-wrapper">
            <div class="row">
                <div class="col-12">
                    <div class="btn-print d-print-none text-right">
                        <a href="<?= base_url(); ?>Page/pmcf" class="btn btn-secondary mr-2">Back to List</a>
                        <button type="button" class="btn btn-primary" onclick="window.print();">Print Report</button>
                    </div>

                    <div class="report-header">
                        <h3>PMCF Report</h3>
                    </div>

                    <div class="report-meta">
                        <strong>User:</strong> <?= htmlspecialchars((string) $this->session->userdata('username')); ?> &nbsp;|&nbsp;
                        <strong>Section:</strong> <?= htmlspecialchars((string) $this->session->userdata('section')); ?> &nbsp;|&nbsp;
                        <strong>Date Generated:</strong> <?= date('F d, Y'); ?>
                    </div>

                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Teacher Observed</th>
                                <th>Grade Level</th>
                                <th>Section</th>
                                <th>District</th>
                                <th>School</th>
                                <th>Term</th>
                                <th>Date Observed</th>
                                <th>Time</th>
                                <th>Subject Area</th>
                                <th>Supervisor</th>
                                <th>Designation</th>
                                <th>Coaching Mechanisms</th>
                                <th>Progress to Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php if (isset($records) && $records): ?>
                            <?php foreach ($records as $row): ?>
                            <tr class="no-break">
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
                            <?php else: ?>
                            <tr>
                                <td colspan="14" class="text-center">No records found.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
