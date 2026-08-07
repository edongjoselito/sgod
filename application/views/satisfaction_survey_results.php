<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Partner Satisfaction Survey Results | SDO Davao Oriental</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Responsive bootstrap 4 admin template" name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">

    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <style>
        .stat-card { padding:20px; border-radius:8px; background:#fff; box-shadow:0 2px 8px rgba(0,0,0,0.1); margin-bottom:20px; }
        .stat-label { font-size:12px; color:#6c757d; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; }
        .stat-value { font-size:32px; font-weight:700; color:#0864a6; }
        .stat-description { font-size:13px; color:#28a745; font-weight:600; margin-top:4px; }
        .stat-bar { height:8px; background:#e9ecef; border-radius:4px; margin-top:10px; }
        .stat-bar-fill { height:100%; background:linear-gradient(90deg, #0864a6, #f6bf26); border-radius:4px; }
        .rating-badge { display:inline-block; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600; }
        .rating-1 { background:#fee2e2; color:#991b1b; }
        .rating-2 { background:#fed7aa; color:#9a3412; }
        .rating-3 { background:#fef3c7; color:#92400e; }
        .rating-4 { background:#d1fae5; color:#065f46; }
        .rating-5 { background:#dbeafe; color:#1e40af; }
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
                            <div class="page-title-box">
                                <h4 class="page-title">Partner Satisfaction Survey Results</h4>
                                <ol class="breadcrumb p-0 m-0">
                                    <li class="breadcrumb-item"><a href="#">Brigada Eskwela</a></li>
                                    <li class="breadcrumb-item active">Survey Results</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <?php if($total_surveys === 0): ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center py-5">
                                    <i class="mdi mdi-clipboard-text-outline" style="font-size:64px; color:#dee2e6;"></i>
                                    <h3 class="mt-3">No surveys submitted yet</h3>
                                    <p class="text-muted">Partner satisfaction survey results will appear here once partners submit their feedback.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="stat-card">
                                <p class="stat-label">Responsiveness</p>
                                <div class="stat-value"><?= $averages['responsiveness']; ?></div>
                                <div class="stat-description"><?= isset($descriptions['responsiveness']) ? $descriptions['responsiveness'] : ''; ?></div>
                                <div class="stat-bar"><div class="stat-bar-fill" style="width:<?= ($averages['responsiveness'] / 5) * 100; ?>%"></div></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <p class="stat-label">Communication</p>
                                <div class="stat-value"><?= $averages['communication']; ?></div>
                                <div class="stat-description"><?= isset($descriptions['communication']) ? $descriptions['communication'] : ''; ?></div>
                                <div class="stat-bar"><div class="stat-bar-fill" style="width:<?= ($averages['communication'] / 5) * 100; ?>%"></div></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <p class="stat-label">Ease of Coordination</p>
                                <div class="stat-value"><?= $averages['ease_of_coordination']; ?></div>
                                <div class="stat-description"><?= isset($descriptions['ease_of_coordination']) ? $descriptions['ease_of_coordination'] : ''; ?></div>
                                <div class="stat-bar"><div class="stat-bar-fill" style="width:<?= ($averages['ease_of_coordination'] / 5) * 100; ?>%"></div></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <p class="stat-label">Transparency</p>
                                <div class="stat-value"><?= $averages['transparency']; ?></div>
                                <div class="stat-description"><?= isset($descriptions['transparency']) ? $descriptions['transparency'] : ''; ?></div>
                                <div class="stat-bar"><div class="stat-bar-fill" style="width:<?= ($averages['transparency'] / 5) * 100; ?>%"></div></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <p class="stat-label">Reporting Quality</p>
                                <div class="stat-value"><?= $averages['reporting_quality']; ?></div>
                                <div class="stat-description"><?= isset($descriptions['reporting_quality']) ? $descriptions['reporting_quality'] : ''; ?></div>
                                <div class="stat-bar"><div class="stat-bar-fill" style="width:<?= ($averages['reporting_quality'] / 5) * 100; ?>%"></div></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <p class="stat-label">Future Willingness</p>
                                <div class="stat-value"><?= $averages['future_willingness']; ?></div>
                                <div class="stat-description"><?= isset($descriptions['future_willingness']) ? $descriptions['future_willingness'] : ''; ?></div>
                                <div class="stat-bar"><div class="stat-bar-fill" style="width:<?= ($averages['future_willingness'] / 5) * 100; ?>%"></div></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Individual Survey Responses</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="surveysTable">
                                            <thead>
                                                <tr>
                                                    <th>Partner</th>
                                                    <th>Responsiveness</th>
                                                    <th>Communication</th>
                                                    <th>Coordination</th>
                                                    <th>Transparency</th>
                                                    <th>Reporting</th>
                                                    <th>Future</th>
                                                    <th>Submitted</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($surveys as $survey): ?>
                                                <tr>
                                                    <td>
                                                        <strong><?= htmlspecialchars($survey->partner_name ?: 'Unknown Partner'); ?></strong>
                                                        <?php if($survey->contact_person): ?>
                                                        <br><small class="text-muted"><?= htmlspecialchars($survey->contact_person); ?></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><span class="rating-badge rating-<?= $survey->responsiveness; ?>"><?= $survey->responsiveness; ?>/5</span></td>
                                                    <td><span class="rating-badge rating-<?= $survey->communication; ?>"><?= $survey->communication; ?>/5</span></td>
                                                    <td><span class="rating-badge rating-<?= $survey->ease_of_coordination; ?>"><?= $survey->ease_of_coordination; ?>/5</span></td>
                                                    <td><span class="rating-badge rating-<?= $survey->transparency; ?>"><?= $survey->transparency; ?>/5</span></td>
                                                    <td><span class="rating-badge rating-<?= $survey->reporting_quality; ?>"><?= $survey->reporting_quality; ?>/5</span></td>
                                                    <td><span class="rating-badge rating-<?= $survey->future_willingness; ?>"><?= $survey->future_willingness; ?>/5</span></td>
                                                    <td><?= date('M d, Y g:i A', strtotime($survey->submitted_at)); ?></td>
                                                </tr>
                                                <?php if($survey->comments): ?>
                                                <tr>
                                                    <td colspan="8" style="background:#f8f9fa;">
                                                        <small class="text-muted"><strong>Comments:</strong> <?= htmlspecialchars($survey->comments); ?></small>
                                                    </td>
                                                </tr>
                                                <?php endif; ?>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php include('includes/footer.php'); ?>
    </div>

    <script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#surveysTable').DataTable({
                pageLength: 10,
                order: [[7, 'desc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search surveys..."
                }
            });
        });
    </script>
</body>
</html>
