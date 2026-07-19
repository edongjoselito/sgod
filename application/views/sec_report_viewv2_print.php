<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Section Accomplishments Report</title>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap');

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                font-size: 11pt;
                line-height: 1.5;
                color: #1a1a1a;
                background: #ffffff;
            }

            @media print {
                body {
                    font-size: 10pt;
                }

                .no-print {
                    display: none !important;
                }

                @page {
                    margin: 0.5in;
                    size: letter;
                }
            }

            .container {
                max-width: 8.5in;
                margin: 0 auto;
                padding: 20px;
            }

            .header {
                text-align: center;
                margin-bottom: 30px;
                padding-bottom: 20px;
                border-bottom: 2px solid #2563eb;
            }

            .logos {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 20px;
                margin-bottom: 20px;
            }

            .logos img {
                height: 60px;
                width: auto;
            }

            .header h1 {
                font-family: 'Playfair Display', Georgia, serif;
                font-size: 18pt;
                font-weight: 700;
                color: #1e3a8a;
                margin-bottom: 8px;
                letter-spacing: 0.5px;
            }

            .header h2 {
                font-family: 'Inter', sans-serif;
                font-size: 14pt;
                font-weight: 600;
                color: #374151;
                margin-bottom: 4px;
            }

            .header h3 {
                font-family: 'Inter', sans-serif;
                font-size: 12pt;
                font-weight: 500;
                color: #6b7280;
            }

            .section-header {
                margin: 30px 0 20px;
                padding: 15px;
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                border-left: 4px solid #2563eb;
                border-radius: 4px;
            }

            .section-header h1 {
                font-family: 'Playfair Display', Georgia, serif;
                font-size: 16pt;
                font-weight: 700;
                color: #1e3a8a;
                margin-bottom: 4px;
            }

            .section-header h2 {
                font-family: 'Inter', sans-serif;
                font-size: 12pt;
                font-weight: 600;
                color: #4b5563;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
                font-size: 10pt;
            }

            thead {
                background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
                color: #ffffff;
            }

            th {
                font-family: 'Inter', sans-serif;
                font-weight: 600;
                font-size: 9pt;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                padding: 12px 10px;
                text-align: left;
                border: 1px solid #1e40af;
            }

            td {
                padding: 10px;
                border: 1px solid #e5e7eb;
                vertical-align: top;
            }

            tbody tr:nth-child(even) {
                background: #f9fafb;
            }

            tbody tr:hover {
                background: #f3f4f6;
            }

            .no-records {
                text-align: center;
                padding: 40px;
                font-size: 12pt;
                color: #6b7280;
                font-style: italic;
            }

            .print-controls {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1000;
                display: flex;
                gap: 10px;
            }

            .print-btn {
                padding: 12px 24px;
                background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
                color: #ffffff;
                border: none;
                border-radius: 8px;
                font-family: 'Inter', sans-serif;
                font-size: 11pt;
                font-weight: 600;
                cursor: pointer;
                box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
                transition: all 0.2s ease;
            }

            .print-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
            }

            .print-btn:active {
                transform: translateY(0);
            }

            .page-break {
                page-break-before: always;
                margin-top: 40px;
            }

            .footer {
                margin-top: 40px;
                padding-top: 20px;
                border-top: 1px solid #e5e7eb;
                text-align: center;
                font-size: 9pt;
                color: #6b7280;
            }

            @media print {
                .print-controls {
                    display: none;
                }

                .page-break {
                    page-break-before: always;
                }
            }
        </style>
    </head>

    <body>
        <div class="print-controls no-print">
            <button onclick="window.print()" class="print-btn">
                <i class="mdi mdi-printer"></i> Print Report
            </button>
        </div>

        <div class="container">
            <!-- Cover Page -->
            <div class="header">
                <div class="logos">
                    <img src="<?= base_url(); ?>assets/images/report/matatag.png" alt="Matatag" />
                    <img src="<?= base_url(); ?>assets/images/report/deped.png" alt="DepEd" />
                    <img src="<?= base_url(); ?>assets/images/report/davor.png" alt="DAVOR" />
                </div>
                <h1>SCHOOL GOVERNANCE AND OPERATIONS DIVISION</h1>
                <?php if(isset($acc)){?>
                <?php $sectionName = is_array($acc) ? (isset($acc['section']) ? $acc['section'] : '') : (isset($acc->section) ? $acc->section : ''); ?>
                <?php } ?>
            </div>

            <?php if((isset($acc) || !empty($acc)) && (!empty($accomplish) || !empty($update))){ ?>

                <?php if($cat == "updates"){?>
                    <div class="section-header">
                        <h1><?= $sectionName; ?></h1>
                        <h2>Updates</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Activity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($update as $row){?>
                                <tr>
                                    <td><?= $row['targetDate']; ?></td>
                                    <td><?= $row['activity']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                <?php }elseif($cat == "accomplishment"){?>

                    <div class="section-header">
                        <h1><?= $sectionName; ?></h1>
                        <h2>Accomplishments</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Activity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($accomplish as $row){?>
                                <tr>
                                    <td><?= $row['dateConducted']; ?></td>
                                    <td><?= $row['activity']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                <?php }else{ ?>
                    <?php if(!empty($accomplish)){ ?>
                        <div class="section-header">
                            <h1><?= $sectionName; ?></h1>
                            <h2>Accomplishments</h2>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Activity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($accomplish as $row){?>
                                    <tr>
                                        <td><?= $row['dateConducted']; ?></td>
                                        <td><?= $row['activity']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>

                    <?php if(!empty($update)){ ?>
                        <div class="page-break"></div>
                        <div class="section-header">
                            <h1><?= $sectionName; ?></h1>
                            <h2>Updates</h2>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Activity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($update as $row){?>
                                    <tr>
                                        <td><?= $row['targetDate']; ?></td>
                                        <td><?= $row['activity']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                <?php } ?>

            <?php }else{ ?>
                <div class="no-records">
                    <h1>NO RECORDS FOUND</h1>
                </div>
            <?php } ?>
        </div>
    </body>
</html>
