<?php
$esc = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
$entries = isset($entries) && is_array($entries) ? $entries : array();
$fundSources = array(); $venues = array();
foreach ($entries as $entry) {
    $fund = trim((string) $entry->fund_source); if ($fund !== '') $fundSources[$fund] = true;
    $venue = trim((string) $entry->venue); if ($venue !== '') $venues[$venue] = true;
}
uksort($fundSources, 'strnatcasecmp');
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

        <link href="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
        <link href="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet">
        <style>
            :root {
                --memo-navy: #272b8c;
                --memo-blue: #3c40c6;
                --memo-ink: #23275d;
                --memo-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            }

            body {
                background:
                    radial-gradient(circle at top left, rgba(60, 64, 198, 0.10), transparent 24%),
                    linear-gradient(180deg, #f4f8fc 0%, #eef4fa 100%);
            }

            .content-page {
                background: transparent;
            }

            .memo-shell {
                position: relative;
                padding-bottom: 28px;
            }

            .memo-shell::before {
                content: "";
                position: absolute;
                inset: 24px 0 auto;
                height: 240px;
                border-radius: 30px;
                background: linear-gradient(135deg, rgba(60, 64, 198, 0.11), rgba(122, 128, 255, 0.10));
                z-index: 0;
            }

            .memo-shell > * {
                position: relative;
                z-index: 1;
            }

            .memo-hero {
                margin-top: 20px;
                border-radius: 28px;
                overflow: hidden;
                color: #ffffff;
                box-shadow: var(--memo-shadow);
                background:
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 32%),
                    linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
            }

            .memo-hero-body {
                padding: 32px;
            }

            .memo-eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 14px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.12);
                border: 1px solid rgba(255, 255, 255, 0.18);
                font-size: 0.8rem;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .memo-title {
                margin: 18px 0 12px;
                color: #ffffff;
                font-size: clamp(2rem, 3vw, 2.7rem);
                line-height: 1.05;
                font-weight: 700;
                letter-spacing: -0.03em;
            }

            .hero-add-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                padding: 13px 20px;
                border: none;
                border-radius: 16px;
                color: var(--memo-navy);
                background: linear-gradient(135deg, #ffffff 0%, #eef7ff 100%);
                font-weight: 700;
                transition: transform 0.25s ease, box-shadow 0.25s ease;
            }

            .hero-add-btn:hover {
                color: var(--memo-navy);
                transform: translateY(-2px);
                box-shadow: 0 14px 32px rgba(17, 24, 39, 0.12);
                text-decoration: none;
            }

.memo-shell::before{display:none}.memo-hero{background:linear-gradient(115deg,#253b77,#5056b8);border-radius:18px;margin-bottom:22px;box-shadow:0 8px 28px #263e7614}.memo-hero-body{padding:28px 30px}.memo-eyebrow{border:0;background:none;padding:0;font-size:10px;letter-spacing:1.8px}.memo-title{font-size:30px;margin:12px 0 10px}.design-intro{font-size:13px;color:#dfe6ff;margin:0}.hero-add-btn{border-radius:9px;font-size:12px;padding:11px 18px}.design-overview{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px}.design-metric{padding:19px 22px;background:#fff;border:1px solid #e1e7f1;border-radius:14px}.design-metric strong{display:block;font-size:27px;color:#263b60}.design-metric span{font-size:12px;color:#728199}.design-card{border:1px solid #e1e7f1;border-radius:16px;box-shadow:0 5px 22px #21375805}.design-card .card-body{padding:24px}.design-heading{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:20px}.design-kicker{font-size:10px;letter-spacing:1.3px;text-transform:uppercase;color:#647aab}.design-heading h2{font-size:22px;color:#283e64;margin:8px 0}.design-heading>span{font-size:12px;color:#70819c}.design-tools{display:flex;align-items:flex-end;gap:16px;padding:16px 18px;background:#f7f9fd;border:1px solid #e7edf5;border-radius:10px;margin-bottom:22px}.design-tools>div{flex:0 1 280px}.design-tools label{font-size:11px;color:#687c9c;font-weight:600}.design-tools select{height:40px;font-size:12px;border:1px solid #dce4f0;border-radius:8px}.design-tools button{margin-left:auto;font-size:12px;background:white;border:1px solid #dce4f0;border-radius:8px;padding:10px 14px}.design-card thead th{background:#f4f7fc;font-size:10px;text-transform:uppercase;letter-spacing:.7px;color:#647793}.design-card td{font-size:12px;padding:17px 12px;vertical-align:top;overflow-wrap:anywhere}.design-card td:nth-child(2){font-weight:600;color:#2c4269}.design-actions{display:inline-block;text-align:left}.design-actions summary{list-style:none;cursor:pointer;font-size:12px;border-radius:8px;padding:8px 12px}.design-actions summary::-webkit-details-marker{display:none}.design-actions-menu{margin-top:6px;background:#fff;border:1px solid #e1e7f1;border-radius:8px;padding:6px;min-width:150px}.design-actions-menu a{font-size:12px;padding:9px 12px;border-radius:6px}.design-card .dataTables_filter input,.design-card .dataTables_length select{border-radius:8px;border:1px solid #dce4f0;min-height:40px;font-size:12px}.memo-shell :focus-visible{outline:3px solid #a4b7ef;outline-offset:3px}@media(max-width:575px){.memo-hero-body{padding:24px}.memo-title{font-size:26px}.design-overview{gap:9px}.design-metric{padding:14px}.design-metric strong{font-size:23px}.design-metric span{font-size:10px}.design-card .card-body{padding:16px}.design-tools{flex-wrap:wrap}.design-tools>div{flex:1 1 100%}.design-tools button{width:100%}.design-heading{flex-wrap:wrap}.design-card .dataTables_filter label{display:block}.design-card .dataTables_filter input{width:100%;margin:6px 0}}
        </style>
    </head>

    <body>
        <div id="wrapper">
            <?php include('includes/top-bar.php'); ?>
            <?php include('includes/sidebar.php'); ?>

            <div class="content-page">
                <div class="content">
                    <div class="container-fluid memo-shell">
                        <div class="row">
                            <div class="col-12">
                                <div class="memo-hero">
                                    <div class="memo-hero-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <span class="memo-eyebrow">
                                                    <i class="mdi mdi-pencil-box-outline"></i>
                                                    Activity Design Registry
                                                </span>
                                                <h1 class="memo-title">Activity Designs</h1><p class="design-intro">Prepare, organize, and print your activity proposals.</p>
                                            </div>
                                            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                                <a href="<?= base_url(); ?>Page/activity_design_add" class="hero-add-btn">
                                                    <i class="mdi mdi-plus"></i>
                                                    New Activity Design
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= $this->session->flashdata('success'); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('danger')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= $this->session->flashdata('danger'); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        <?php endif; ?>

                        <div class="design-overview">
                            <?php foreach (array(array(count($entries),'Activity designs'),array(count($fundSources),'Fund sources'),array(count($venues),'Venues')) as $metric): ?><div class="design-metric"><strong><?= number_format($metric[0]); ?></strong><span><?= $esc($metric[1]); ?></span></div><?php endforeach; ?>
                        </div>
                        <div class="card design-card">
                            <div class="card-body">
                                <div class="design-heading"><div><span class="design-kicker">Personal registry</span><h2>My Activity Designs</h2></div><span id="designResults" role="status"><?= count($entries); ?> designs</span></div>
                                <div class="design-tools"><div><label for="designFundFilter">Fund source</label><select class="form-control" id="designFundFilter"><option value="">All fund sources</option><?php foreach (array_keys($fundSources) as $fund): ?><option value="<?= $esc($fund); ?>"><?= $esc($fund); ?></option><?php endforeach; ?></select></div><button type="button" id="resetDesignFilters" class="btn btn-light"><i class="mdi mdi-refresh" aria-hidden="true"></i> Reset filters</button></div>
                                <?php if (empty($entries)): ?>
                                    <p class="text-muted mb-0">No activity designs yet. Click the button above to add one.</p>
                                <?php else: ?>
                                    <div class="design-table-shell">
                                        <table id="designTable" class="table table-hover mb-0" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Design No.</th>
                                                    <th>Activity Title</th>
                                                    <th>Date</th>
                                                    <th>Venue</th>
                                                    <th>Fund Source</th>
                                                    <th class="text-right">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($entries as $entry): ?>
                                                    <tr>
                                                        <td><?= $esc($entry->activity_design_no ?? ''); ?></td>
                                                        <td><?= $esc($entry->title); ?></td>
                                                        <td><?= $esc($entry->activity_date); ?></td>
                                                        <td><?= $esc($entry->venue); ?></td>
                                                        <td data-search="<?= $esc(trim((string) $entry->fund_source)); ?>"><?= $esc($entry->fund_source); ?></td>
                                                        <td class="text-right">
                                                            <details class="design-actions">
                                                                <summary class="btn btn-sm btn-outline-primary" aria-label="<?= $esc('Actions for ' . $entry->title); ?>">Actions ▾</summary>
                                                                <div class="design-actions-menu">
                                                                    <a class="dropdown-item" href="<?= base_url(); ?>Page/activity_design_edit/<?= (int) $entry->id; ?>">Edit</a>
                                                                    <a class="dropdown-item" href="<?= base_url(); ?>Page/activity_design_print/<?= (int) $entry->id; ?>" target="_blank" rel="noopener noreferrer">Print</a>
                                                                    <a class="dropdown-item text-danger" href="<?= base_url(); ?>Page/activity_design_delete/<?= (int) $entry->id; ?>" onclick="return confirm('Are you sure you want to delete this activity design?');">Delete</a>
                                                                </div>
                                                            </details>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include('includes/footer.php'); ?>
            </div>
        </div>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.responsive.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.js"></script>
        <script>
        $(function(){
            if (!$('#designTable').length) return;
            var table = $('#designTable').DataTable({pageLength:10,autoWidth:false,responsive:true,order:[],columnDefs:[{targets:1,responsivePriority:1},{targets:5,responsivePriority:2,orderable:false,searchable:false}],language:{search:'',searchPlaceholder:'Search title, design number, venue…',lengthMenu:'Show _MENU_ designs',info:'Showing _START_ to _END_ of _TOTAL_ designs',infoEmpty:'No designs to display',zeroRecords:'No designs match your filters. Try another search or reset filters.'}});
            function summary(){ $('#designResults').text(table.rows({search:'applied'}).count() + ' of ' + <?= count($entries); ?> + ' designs'); }
            table.on('draw',summary);summary();
            $('#designFundFilter').on('change',function(){table.column(4).search(this.value ? '^'+$.fn.dataTable.util.escapeRegex(this.value)+'$' : '',true,false).draw();});
            $('#resetDesignFilters').on('click',function(){$('#designFundFilter').val('');table.search('').columns().search('').draw();});
        });
        </script>
    </body>
</html>
