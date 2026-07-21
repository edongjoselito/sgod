<?php
$esc = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Activity Design - <?= $esc($entry->title); ?></title>
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <style>
            @page { size: A4; margin: 1.5cm; }
            body {
                font-family: Arial, Helvetica, sans-serif;
                padding: 24px;
                color: #000;
                background: #fff;
                font-size: 11pt;
                line-height: 1.5;
            }
            @media print {
                body { padding: 0; margin: 0; }
                .no-print { display: none; }
                tr { page-break-inside: avoid; }
                .header, .signature, .print-footer { page-break-inside: avoid; }
            }
            .header {
                text-align: center;
                margin-bottom: 30px;
                padding-bottom: 12px;
                border-bottom: 2px solid #333;
            }
            .header h4 {
                margin: 0 0 6px;
                text-transform: uppercase;
                font-weight: 700;
                font-size: 16pt;
            }
            .header p {
                margin: 0;
                font-size: 11pt;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            td, th {
                border: 1px solid #333;
                padding: 8px 10px;
                vertical-align: top;
            }
            th {
                background: #eee;
                font-weight: 700;
            }
            .label-cell {
                font-weight: 700;
                width: 25%;
                background: #f8f9fa;
            }
            .signature {
                margin-top: 48px;
            }
            .signature p {
                margin: 0 0 4px;
            }
            .signature .overline {
                border-top: 1px solid #333;
                display: inline-block;
                width: 300px;
                text-align: center;
                padding-top: 4px;
                margin-top: 40px;
            }
            .print-footer {
                margin-top: 24px;
                font-size: 9pt;
                color: #555;
                text-align: right;
            }
        </style>
    </head>

    <body>
        <div class="no-print text-right mb-3">
            <button type="button" onclick="window.print()" class="btn btn-primary">Print Activity Design</button>
        </div>

        <div class="header">
            <h4>Department of Education</h4>
            <p class="mb-0">Activity Design</p>
        </div>

        <?php
        $budgetLines = (!empty($entry->budget_lines)) ? json_decode($entry->budget_lines, TRUE) : array();
        $grandTotal = 0;
        foreach ($budgetLines as $line) {
            $grandTotal += (float) ($line['total_amount'] ?? 0);
        }
        ?>

        <table>
            <tr>
                <td class="label-cell">Activity Design No.</td>
                <td colspan="3"><?= $esc($entry->activity_design_no ?? ''); ?></td>
            </tr>
            <tr>
                <td class="label-cell">Activity Title</td>
                <td colspan="3"><?= $esc($entry->title); ?></td>
            </tr>
            <tr>
                <td class="label-cell">Date</td>
                <td><?= $esc($entry->activity_date); ?></td>
                <td class="label-cell">Venue</td>
                <td><?= $esc($entry->venue); ?></td>
            </tr>
            <tr>
                <td class="label-cell">Rationale</td>
                <td colspan="3"><?= nl2br($esc($entry->rationale)); ?></td>
            </tr>
            <tr>
                <td class="label-cell">Objectives</td>
                <td colspan="3"><?= nl2br($esc($entry->objectives)); ?></td>
            </tr>
            <tr>
                <td class="label-cell">Fund Source</td>
                <td colspan="3"><?= $esc($entry->fund_source); ?></td>
            </tr>
        </table>

        <?php if (!empty($budgetLines)): ?>
            <h5 class="text-center" style="text-transform: uppercase; font-weight: 700; margin-bottom: 12px;">Budgetary Requirements</h5>
            <table>
                <thead>
                    <tr>
                        <th>Particulars</th>
                        <th>Amount</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($budgetLines as $line): ?>
                        <tr>
                            <td><?= $esc($line['particulars'] ?? ''); ?></td>
                            <td><?= number_format((float) ($line['amount'] ?? 0), 2); ?></td>
                            <td><?= $esc($line['unit'] ?? ''); ?></td>
                            <td><?= $esc($line['qty'] ?? ''); ?></td>
                            <td><?= number_format((float) ($line['total_amount'] ?? 0), 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Grand Total</th>
                        <th><?= number_format($grandTotal, 2); ?></th>
                    </tr>
                </tfoot>
            </table>
        <?php endif; ?>

        <div class="signature">
            <p>Prepared by:</p>
            <div class="overline">Signature over Printed Name</div>
        </div>

        <div class="print-footer">
            Printed on <?= date('F d, Y'); ?>
        </div>
    </body>
</html>
