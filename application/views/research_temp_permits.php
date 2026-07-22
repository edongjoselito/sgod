<!-- ============================================================== -->
<!-- Start Page Content here -->
<!-- ============================================================== -->
<?php
    $temp_permit_count = isset($temp_permit_count) ? (int)$temp_permit_count : 0;
    $research_requests = isset($research_requests) ? $research_requests : [];
    $h = function ($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); };

    // ---- Quick stats for the hero ----
    $thisMonth   = date('Y-m');
    $monthCount  = 0;
    $researchers = [];
    foreach ($research_requests as $r) {
        $rd = trim((string)($r->request_date ?? ''));
        if ($rd !== '' && date('Y-m', strtotime($rd)) === $thisMonth) $monthCount++;

        $name = trim((string)($r->main_author_name ?? ''));
        if ($name === '') $name = trim((string)($r->main_author_id ?? ''));
        if ($name !== '') $researchers[strtolower($name)] = true;
    }
    $researcherCount = count($researchers);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <?php include('includes/page-title.php'); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/dashboard-unified.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <style>
    /* ===== Temporary Permits – scoped styling ===== */
    .permits-hero{
        position:relative;
        border-radius:24px;
        padding:1.9rem 1.9rem;
        margin-bottom:1.5rem;
        background:radial-gradient(circle at 85% 15%, rgba(255,255,255,.23), transparent 26%),linear-gradient(135deg,#272b8c 0%,#3c40c6 62%,#6c70ef 100%);
        color:#fff;
        overflow:hidden;
        box-shadow:0 20px 45px rgba(39,43,140,.20);
    }
    .permits-hero::after{
        content:"";position:absolute;top:-70px;right:-40px;
        width:240px;height:240px;border-radius:50%;
        background:rgba(255,255,255,.12);
    }
    .permits-hero::before{
        content:"";position:absolute;bottom:-90px;right:150px;
        width:180px;height:180px;border-radius:50%;
        background:rgba(255,255,255,.08);
    }
    .permits-hero .hero-eyebrow{
        text-transform:uppercase;letter-spacing:1.5px;font-size:.72rem;
        font-weight:600;opacity:.85;margin-bottom:.35rem;
    }
    .permits-hero .hero-title{font-size:1.7rem;font-weight:700;margin-bottom:.3rem;color:#fff;}
    .permits-hero .hero-sub{opacity:.92;margin-bottom:0;max-width:520px;}
    .permits-hero .btn-hero{
        background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.4);
        color:#fff;font-weight:500;
    }
    .permits-hero .btn-hero:hover{background:rgba(255,255,255,.3);color:#fff;}

    .hero-stats{position:relative;z-index:2;}
    .stat-tile{
        background:rgba(255,255,255,.15);
        border:1px solid rgba(255,255,255,.22);
        border-radius:.6rem;
        padding:.7rem 1.1rem;
        text-align:center;
        backdrop-filter:blur(2px);
        min-width:104px;
    }
    .stat-tile .stat-num{font-size:1.55rem;font-weight:700;line-height:1.1;}
    .stat-tile .stat-label{
        font-size:.68rem;text-transform:uppercase;letter-spacing:.6px;opacity:.9;
    }

    /* ===== Card + table ===== */
    .permits-card{border:none;box-shadow:0 2px 14px rgba(0,0,0,.06);border-radius:.75rem;}
    .permits-card .card-header{border-bottom:1px solid #eef0f2;border-radius:.75rem .75rem 0 0;}

    #datatable-permits thead th{
        background:#f7f9fb;border-top:none;border-bottom:2px solid #e9edf1;
        text-transform:uppercase;font-size:.7rem;letter-spacing:.5px;
        color:#8a94a6;font-weight:600;white-space:nowrap;
    }
    #datatable-permits tbody td{vertical-align:middle;font-size:.85rem;padding:.65rem .75rem;}
    #datatable-permits tbody tr{transition:background .12s ease;}
    #datatable-permits tbody tr:hover{background:#f2fbfb;}

    .control-badge{
        display:inline-block;background:#e9fafa;color:#2a8f92;font-weight:700;
        padding:.22rem .55rem;border-radius:.35rem;font-size:.8rem;letter-spacing:.3px;
    }
    .type-badge{
        background:#eef0ff;color:#5b64d6;font-weight:600;
        padding:.3rem .6rem;border-radius:2rem;font-size:.72rem;
    }
    .researcher-name{font-weight:600;color:#343a40;}
    .researcher-hei{font-size:.75rem;color:#98a0ad;margin-top:2px;}

    .search-wrap .input-group-text{background:#fff;border-right:none;color:#8a94a6;}
    .search-wrap .form-control{border-left:none;}
    .search-wrap .form-control:focus{box-shadow:none;border-color:#ced4da;}
</style>
    </head>
    <body class="dashboard-root-theme">
        <div id="wrapper">
            <?php include('includes/top-bar.php'); ?>
            <?php include('includes/sidebar.php'); ?>

<div class="content-page">
    <div class="content">

        <main class="container-fluid dashboard-shell">

            <!-- ===================== HERO ===================== -->
            <div class="permits-hero">
                <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between">
                    <div class="mb-3 mb-lg-0 pr-lg-4">
                        <div class="hero-eyebrow"><i class="mdi mdi-shield-check-outline"></i> Research Office</div>
                        <h1 class="hero-title"><?= $h($title); ?></h1>
                        <p class="hero-sub">Track and review temporary research permit requests submitted through the public form.</p>
                        <a href="<?= base_url('Pages/view'); ?>" class="btn btn-hero btn-sm mt-3">
                            <i class="mdi mdi-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>

                    <div class="hero-stats d-flex flex-wrap" style="gap:.75rem;">
                        <div class="stat-tile">
                            <div class="stat-num"><?= $temp_permit_count; ?></div>
                            <div class="stat-label">Total</div>
                        </div>
                        <div class="stat-tile">
                            <div class="stat-num"><?= (int)$monthCount; ?></div>
                            <div class="stat-label">This Month</div>
                        </div>
                        <div class="stat-tile">
                            <div class="stat-num"><?= (int)$researcherCount; ?></div>
                            <div class="stat-label">Researchers</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== TABLE CARD ===================== -->
            <div class="row">
                <div class="col-12">
                    <div class="card permits-card">
                        <div class="card-header py-3 bg-white d-flex flex-wrap justify-content-between align-items-center">
                            <h5 class="header-title mb-0"><i class="mdi mdi-file-document-multiple-outline text-muted mr-1"></i> Permit Requests</h5>

                            <div class="d-flex align-items-center" style="gap:.75rem;">
                                <div class="search-wrap" style="min-width:240px;">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                                        </div>
                                        <input type="text" id="permitSearch" class="form-control" placeholder="Search permits...">
                                    </div>
                                </div>
                                <a href="javascript:;" class="text-muted" data-toggle="reload" title="Reload"><i class="mdi mdi-refresh"></i></a>
                            </div>
                        </div>

                        <div class="card-body pt-2">
                            <div class="table-responsive">
                                <table id="datatable-permits" class="table table-hover table-sm w-100 mb-0">
                                    <thead>
                                        <tr>
                                            <th>Control No.</th>
                                            <th>Research Title</th>
                                            <th>Name of Researcher</th>
                                            <th>Researcher Type</th>
                                            <th>Request Date</th>
                                            <!-- <th>Status</th> -->
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($research_requests)): ?>
                                            <?php foreach ($research_requests as $req): ?>
                                                <?php
                                                    $dateVal = isset($req->request_date) ? trim((string)$req->request_date) : '';
                                                    $dateFmt = $dateVal !== '' ? date('M d, Y', strtotime($dateVal)) : '';

                                                    $researcherName = trim((string)($req->main_author_name ?? ''));
                                                    if ($researcherName === '') $researcherName = trim((string)($req->main_author_id ?? ''));
                                                    $heiName = trim((string)($req->hei_name ?? ''));
                                                    $rType   = trim((string)($req->researcher_type ?? ''));
                                                ?>
                                                <tr>
                                                    <td class="text-nowrap"><span class="control-badge"><?= $h($req->control_no ?? ''); ?></span></td>
                                                    <td><?= $h($req->research_title ?? ''); ?></td>
                                                    <td>
                                                        <?php if ($researcherName !== ''): ?>
                                                            <div class="researcher-name"><?= $h($researcherName); ?></div>
                                                            <?php if ($heiName !== ''): ?>
                                                                <div class="researcher-hei"><i class="mdi mdi-school-outline"></i> <?= $h($heiName); ?></div>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <span class="text-muted">—</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($rType !== ''): ?>
                                                            <span class="type-badge"><?= $h($rType); ?></span>
                                                        <?php else: ?>
                                                            <span class="text-muted">—</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-nowrap" data-order="<?= $dateVal !== '' ? date('Y-m-d', strtotime($dateVal)) : ''; ?>">
                                                        <i class="mdi mdi-calendar-blank-outline text-muted mr-1"></i><?= $h($dateFmt); ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if (isset($req->id)): ?>
                                                            <a class="btn btn-sm btn-outline-primary" target="_blank" href="<?= base_url('Research/report/' . (int)$req->id); ?>">
                                                                <i class="mdi mdi-eye-outline"></i> View
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="text-muted">—</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    <i class="mdi mdi-inbox-outline d-block" style="font-size:2rem;opacity:.5;"></i>
                                                    No temporary permit requests found.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>

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
    // Wait for all global assets (vendor + DataTables) to finish loading
    window.addEventListener('load', function () {
        if (!window.jQuery || !jQuery.fn.DataTable) {
            console.error('DataTables assets not loaded.');
            return;
        }

        var $table = jQuery('#datatable-permits');

        if (jQuery.fn.DataTable.isDataTable($table)) {
            $table.DataTable().destroy();
        }

        var table = $table.DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 25,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            order: [[4, 'desc']],
            columnDefs: [
                { orderable: false, targets: 5 }
            ],
            dom: 'lrtip', // hides default DataTables search box
            language: {
                lengthMenu: "Show _MENU_ entries",
                zeroRecords: "No matching permits found",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                infoFiltered: "(filtered from _MAX_ total entries)"
            }
        });

        // Custom search input
        jQuery('#permitSearch').on('keyup change', function () {
            table.search(this.value).draw();
        });
    });
</script>
</body>
</html>
