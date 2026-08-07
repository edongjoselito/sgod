<?php
if (!isset($title)) {
    $title = 'Accomplishment Report';
}
$record = isset($record) ? $record : null;
$reportGroups = isset($reportGroups) && is_array($reportGroups) ? $reportGroups : array();
$attachments = isset($reportGroups[$record->id]) ? $reportGroups[$record->id] : array();
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
                size: A4 portrait;
                margin: 15mm;
            }
            body {
                background: #e9ecef;
                color: #000;
                font-size: 11pt;
                line-height: 1.5;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .print-wrapper {
                max-width: 800px;
                margin: 20px auto;
                background: #fff;
                padding: 30px;
                box-shadow: 0 0 12px rgba(0,0,0,.08);
            }
            .report-header {
                text-align: center;
                margin-bottom: 25px;
                padding-bottom: 15px;
                border-bottom: 2px solid #2c3e50;
            }
            .report-header h3 {
                margin: 0;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 1px;
                color: #2c3e50;
                font-size: 16pt;
            }
            .report-header p {
                margin: 8px 0 0;
                color: #555;
                font-size: 10pt;
            }
            .report-meta {
                margin-bottom: 20px;
                font-size: 9.5pt;
                color: #666;
            }
            .section-title {
                font-weight: 700;
                font-size: 12pt;
                color: #2c3e50;
                margin: 20px 0 10px;
                padding-bottom: 5px;
                border-bottom: 1px solid #ddd;
            }
            .detail-row {
                display: flex;
                margin-bottom: 8px;
            }
            .detail-label {
                width: 140px;
                font-weight: 700;
                color: #555;
                flex-shrink: 0;
            }
            .detail-value {
                color: #333;
                word-break: break-word;
            }
            .detail-value pre {
                white-space: pre-wrap;
                word-wrap: break-word;
                margin: 0;
                font-family: inherit;
            }
            .attachments-list {
                margin-top: 10px;
            }
            .attachment-item {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 8px 12px;
                border: 1px solid #ddd;
                border-radius: 6px;
                margin-bottom: 8px;
                background: #f9f9f9;
            }
            .attachment-item i {
                color: #ea580c;
                font-size: 18pt;
            }
            .attachment-info {
                flex: 1;
            }
            .attachment-name {
                font-weight: 700;
                color: #333;
            }
            .attachment-file {
                font-size: 9pt;
                color: #666;
            }
            .btn-print {
                margin-bottom: 15px;
            }
            @media print {
                body { background: #fff; }
                .print-wrapper { max-width: none; margin: 0; padding: 0; box-shadow: none; }
                .btn-print { display: none; }
            }
            @media (max-width: 767.98px) {
                .detail-row { flex-direction: column; }
                .detail-label { width: 100%; margin-bottom: 4px; }
                .btn-print .btn { width: 100%; margin-bottom: .5rem; }
            }
        </style>
    </head>
    <body>
        <div class="container-fluid print-wrapper">
            <div class="row">
                <div class="col-12">
                    <div class="btn-print d-print-none text-right">
                        <a href="<?= base_url(); ?>Page/viewSecAccomplishments" class="btn btn-secondary mr-2">Back to List</a>
                        <button type="button" class="btn btn-primary" onclick="window.print();">Print Report</button>
                    </div>

                    <div class="report-header">
                        <h3>Accomplishment Report</h3>
                        <p>Individual Accomplishment Details</p>
                    </div>

                    <?php if ($record): ?>
                    <div class="report-meta">
                        <strong>Date Generated:</strong> <?= date('F d, Y g:i A'); ?>
                    </div>

                    <div class="section-title">Basic Information</div>
                    <div class="detail-row">
                        <span class="detail-label">Activity:</span>
                        <span class="detail-value"><?= htmlspecialchars((string) $record->activity); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Category:</span>
                        <span class="detail-value"><?= htmlspecialchars((string) $record->activityCategory); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Venue:</span>
                        <span class="detail-value"><?= htmlspecialchars((string) $record->venue); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date Conducted:</span>
                        <span class="detail-value"><?= htmlspecialchars((string) $record->dateConducted); ?></span>
                    </div>

                    <div class="section-title">Details</div>
                    <div class="detail-row">
                        <span class="detail-label">Resources:</span>
                        <span class="detail-value"><?= htmlspecialchars((string) $record->resources); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Notes:</span>
                        <span class="detail-value"><pre><?= htmlspecialchars((string) $record->notes); ?></pre></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Remarks:</span>
                        <span class="detail-value"><pre><?= htmlspecialchars((string) $record->remarks); ?></pre></span>
                    </div>

                    <?php if (!empty($attachments)): ?>
                    <div class="section-title">Attachments</div>
                    <div class="attachments-list">
                        <?php foreach ($attachments as $attachment): ?>
                        <div class="attachment-item">
                            <i class="mdi mdi-file-pdf-box"></i>
                            <div class="attachment-info">
                                <div class="attachment-name"><?= htmlspecialchars((string) $attachment->document_name); ?></div>
                                <?php if ($attachment->original_name && $attachment->original_name !== $attachment->document_name): ?>
                                <div class="attachment-file"><?= htmlspecialchars((string) $attachment->original_name); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="section-title">Attachments</div>
                    <p style="color: #666; font-style: italic;">No PDF attachments for this accomplishment.</p>
                    <?php endif; ?>

                    <?php else: ?>
                    <div class="alert alert-warning">Accomplishment record not found.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </body>
</html>
