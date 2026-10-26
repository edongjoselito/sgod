<?php defined('BASEPATH') OR exit('No direct script access allowed');
$esc = function($value){ return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$statusCounts = array_fill_keys(array('Pending', 'Endorsed', 'Approved', 'Returned', 'Rejected', 'Cancelled'), 0);
foreach ($requests as $request) {
    $status = trim((string) ($request->status ?? ''));
    if ($status === '') $status = 'Unspecified';
    if (!isset($statusCounts[$status])) $statusCounts[$status] = 0;
    $statusCounts[$status]++;
}
$statusStyles = array('Pending'=>'pending', 'Endorsed'=>'endorsed', 'Approved'=>'approved', 'Returned'=>'returned', 'Rejected'=>'rejected', 'Cancelled'=>'cancelled');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Authority to Travel</title>
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/dashboard-unified.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet">
    <style>
        .travel-hero{display:flex;justify-content:space-between;align-items:center;gap:24px;padding:30px;border-radius:20px;background:linear-gradient(120deg,#222968,#415bc0);color:#fff;margin-bottom:24px}
        .travel-hero h1{color:#fff;font-size:1.85rem;margin:8px 0 12px}.travel-hero p{color:#e0e6ff;margin:0}.travel-eyebrow{text-transform:uppercase;font-size:.72rem;letter-spacing:.14em;font-weight:700}.travel-total{min-width:110px;text-align:center;padding:18px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);border-radius:16px}.travel-total strong{display:block;font-size:2.1rem;line-height:1.2}.travel-total span{font-size:.8rem}
        .travel-filters{display:flex;flex-wrap:wrap;gap:10px;margin:12px 0 24px}.travel-filter{border:1px solid #dbe2ef;background:#fff;color:#42516c;border-radius:24px;padding:9px 15px;font-weight:600;cursor:pointer}.travel-filter span{display:inline-block;margin-left:8px;padding:1px 7px;border-radius:12px;background:#eef1f7;font-size:.78rem}.travel-filter.active{background:#303f92;border-color:#303f92;color:#fff}.travel-filter.active span{background:rgba(255,255,255,.18)}.travel-filter:focus-visible{outline:3px solid #829be5;outline-offset:3px}
        .travel-card{border:1px solid #e6eaf2;border-radius:18px;box-shadow:0 8px 28px rgba(30,45,80,.05);overflow:hidden}.travel-card .card-body{padding:26px}.travel-table thead th{background:#f5f7fb;color:#596780;font-size:.75rem;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;border-bottom:1px solid #e6eaf2}.travel-table td{vertical-align:middle;border-color:#edf0f5;padding-top:18px;padding-bottom:18px}.travel-purpose{max-width:380px;overflow-wrap:anywhere}.travel-status{display:inline-block;border-radius:20px;padding:6px 11px;font-size:.75rem;font-weight:700;background:#eef1f5;color:#526078;white-space:nowrap}.travel-status.pending{background:#fff3d6;color:#855700}.travel-status.endorsed{background:#e7efff;color:#2b55a0}.travel-status.approved{background:#e0f5e9;color:#236c42}.travel-status.returned{background:#fff0df;color:#955114}.travel-status.rejected{background:#fde7eb;color:#a42b45}.travel-status.cancelled{background:#eceef2;color:#586273}.travel-card .dataTables_filter input,.travel-card .dataTables_length select{border-radius:8px;border:1px solid #dbe2ef}.travel-card .dataTables_info{font-size:.85rem;color:#69768d}
        @media(max-width:575px){.travel-hero{padding:22px;align-items:flex-start;flex-direction:column}.travel-total{display:none}.travel-card .card-body{padding:16px}.travel-filter{padding:8px 11px}.travel-hero h1{font-size:1.5rem}}
        .travel-table-shell{width:100%;min-width:0}.travel-table{width:100%!important}.travel-table td{overflow-wrap:anywhere}.travel-table .btn{white-space:normal;min-height:44px;display:inline-flex;align-items:center;text-align:left}.travel-card .dataTables_wrapper>.row{align-items:center;row-gap:12px}.travel-card .pagination{flex-wrap:wrap;gap:3px}.travel-table tbody tr.child ul.dtr-details{display:block;width:100%}.travel-table tbody tr.child ul.dtr-details>li{display:flex;flex-wrap:wrap;gap:6px 16px;padding:12px 0}.travel-table tbody tr.child .dtr-title{min-width:110px;white-space:normal}.travel-table tbody tr.child .dtr-data{flex:1 1 180px;min-width:0;overflow-wrap:anywhere}.travel-table td.dtr-control:focus-visible,.travel-table td[tabindex]:focus-visible{outline:3px solid #829be5;outline-offset:-3px}
        @media(max-width:767px){.travel-card .dataTables_filter label{display:block;text-align:left}.travel-card .dataTables_filter input{display:block;width:100%;margin:6px 0 0;min-height:44px}.travel-card .dataTables_length{text-align:left}.travel-card .dataTables_length select{min-height:44px}.travel-card .dataTables_paginate .pagination{justify-content:center}.travel-card .page-link{min-height:44px;min-width:40px;display:flex;align-items:center;justify-content:center}.travel-table th,.travel-table td{padding:12px 8px}.travel-table thead th{white-space:normal}.travel-filters .travel-filter{min-height:44px}.travel-table tbody tr.child .dtr-data .btn{width:100%;justify-content:center}}
        .travel-card{overflow:visible}.travel-actions .dropdown-toggle{gap:8px}.travel-actions .dropdown-menu{max-width:calc(100vw - 32px);border:1px solid #e1e6ef;border-radius:10px;box-shadow:0 8px 24px rgba(30,45,80,.14);padding:6px}.travel-actions .dropdown-item{white-space:normal;min-height:44px;display:flex;align-items:center;padding:10px 12px;border-radius:6px}
        .travel-actions summary{list-style:none;cursor:pointer}.travel-actions summary::-webkit-details-marker{display:none}.travel-actions summary:focus-visible{outline:3px solid #829be5;outline-offset:3px}.travel-actions[open]>.dropdown-menu{display:block;position:static;float:none;transform:none;margin-top:6px;min-width:0;width:230px;max-width:100%;z-index:auto}.travel-actions[open]>summary{background:#303f92;color:#fff}.travel-table tbody tr.child .travel-actions{width:100%}
        .travel-table .travel-actions .travel-menu-toggle{width:44px;min-width:44px;height:44px;padding:0;justify-content:center;font-size:24px;line-height:1;text-align:center}
    </style>
</head>
<body class="dashboard-root-theme">
<div id="wrapper">
    <?php include(__DIR__ . '/includes/top-bar.php'); ?>
    <?php include(__DIR__ . '/includes/sidebar.php'); ?>
    <div class="content-page">
        <div class="content">
            <main class="container-fluid dashboard-shell py-4">
                <a href="<?= site_url('Page/section_head_dashboard'); ?>" class="btn btn-light mb-3">Back to Dashboard</a>
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success" role="status"><?= $esc($this->session->flashdata('success')); ?></div>
                <?php endif; ?>
                <header class="travel-hero">
                    <div><span class="travel-eyebrow"><i class="mdi mdi-airplane mr-1" aria-hidden="true"></i> Employee travel</span>
                    <h1>Authority to Travel</h1>
                    <p>Track your travel requests and their approval status.</p>
                    <p class="mt-2 small">Employee number: <?= $esc($employeeId); ?></p></div>
                    <div class="travel-total"><strong><?= count($requests); ?></strong><span>Total requests</span></div>
                </header>
                <?php if ($travelAvailable): ?>
                <h2 class="h5 mb-2">Filter by Status</h2>
                <div class="travel-filters" role="group" aria-label="Filter travel requests by status">
                    <button type="button" class="travel-filter active" data-status="" aria-pressed="true" aria-controls="travelTable">All requests <span><?= count($requests); ?></span></button>
                    <?php foreach ($statusCounts as $status => $count): ?>
                    <button type="button" class="travel-filter" data-status="<?= $esc($status); ?>" aria-pressed="false" aria-controls="travelTable"><?= $esc($status); ?> <span><?= $count; ?></span></button>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <section class="card travel-card"><div class="card-body">
                <h2 class="h5 mb-1">My Travel Requests</h2>
                <p class="text-muted mb-4">Search by purpose, destination, or request number. Expand a row using the + control to see more details on smaller screens.</p>
                <?php if (!$travelAvailable): ?>
                    <div class="alert alert-warning">Travel requests are currently unavailable.</div>
                <?php else: ?>
                    <div class="travel-table-shell"><table id="travelTable" class="table table-hover travel-table mb-0" style="width:100%">
                        <thead><tr><th scope="col">Request</th><th scope="col">Purpose</th><th scope="col">Destination</th><th scope="col">Inclusive Dates</th><th scope="col">Status</th><th scope="col">Travel Type</th><th scope="col">Date Encoded</th><th scope="col">Accomplishment</th><th scope="col">Actions</th></tr></thead>
                        <tbody><?php foreach ($requests as $request):
                            $status = trim((string) ($request->status ?? ''));
                            if ($status === '') $status = 'Unspecified';
                        ?>
                            <tr>
                                <td><?= $esc($request->id); ?></td>
                                <td class="travel-purpose"><?= nl2br($esc($request->purpose ?? '')); ?></td>
                                <td><?= $esc($request->destination ?? ''); ?></td>
                                <td><?= $esc($request->inclusive_date ?? ''); ?></td>
                                <td data-search="<?= $esc($status); ?>"><span class="travel-status <?= $esc($statusStyles[$status] ?? ''); ?>"><?= $esc($status); ?></span></td>
                                <td><?= isset($request->ttype) ? ((int) $request->ttype === 0 ? 'Within the Division' : 'Outside Division') : ''; ?></td>
                                <td><?= $esc($request->date_created ?? ''); ?></td>
                                <?php $hasAccomplishment = !empty($encodedTravelIds[(int) $request->id]); ?>
                                <td class="text-center" data-order="<?= $hasAccomplishment ? 1 : 0; ?>" data-search="<?= $hasAccomplishment ? 'Encoded' : 'Not encoded'; ?>">
                                    <span style="color:<?= $hasAccomplishment ? '#237743' : '#c62828'; ?>;font-size:1.5rem;font-weight:700" role="img" aria-label="<?= $hasAccomplishment ? 'Accomplishment encoded' : 'No accomplishment encoded'; ?>" title="<?= $hasAccomplishment ? 'Accomplishment encoded' : 'No accomplishment encoded'; ?>"><?= $hasAccomplishment ? '&#10003;' : '&#10007;'; ?></span>
                                </td>
                                <td>
                                    <details class="dropdown travel-actions">
                                        <summary class="btn btn-sm btn-outline-primary travel-menu-toggle" aria-label="Actions for travel request <?= (int) $request->id; ?>" title="Actions"><span aria-hidden="true">&hellip;</span></summary>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="<?= site_url('Reimbursement/travel/' . (int) $request->id); ?>"><i class="mdi mdi-cash-multiple mr-1" aria-hidden="true"></i>Process Reimbursement</a>
                                            <a class="dropdown-item" href="<?= site_url('Page/travel_details/' . (int) $request->id); ?>"><i class="mdi mdi-eye-outline mr-1" aria-hidden="true"></i>View Travel Details</a>
                                            <a class="dropdown-item" href="<?= site_url('Page/addAccomplishments') . '?travel_request_id=' . (int) $request->id; ?>"><i class="mdi mdi-file-document-edit-outline mr-1" aria-hidden="true"></i>Encode Accomplishments</a>
                                        </div>
                                    </details>
                                </td>
                            </tr>
                        <?php endforeach; ?></tbody>
                    </table></div>
                <?php endif; ?>
                </div></section>
            </main>
        </div>
        <?php include(__DIR__ . '/includes/footer.php'); ?>
    </div>
</div>
<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/dataTables.responsive.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.js"></script>
<script>
$(function () {
    document.addEventListener('click', function (event) {
        document.querySelectorAll('.travel-actions[open]').forEach(function (menu) {
            if (!menu.contains(event.target)) menu.removeAttribute('open');
        });
    });
    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') return;
        document.querySelectorAll('.travel-actions[open]').forEach(function (menu) {
            menu.removeAttribute('open');
            if (menu.contains(document.activeElement)) menu.querySelector('summary').focus();
        });
    });
    if (!$('#travelTable').length) return;
    var table = $('#travelTable').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        order: [[0, 'desc']],
        autoWidth: false,
        responsive: { details: { type: 'inline', target: 1 } },
        pagingType: 'simple_numbers',
        columnDefs: [
            { targets: [0, 5, 6], visible: false, className: 'never' },
            { targets: 4, responsivePriority: 2 },
            { targets: 1, className: 'all', responsivePriority: 1 },
            { targets: 7, responsivePriority: 4 },
            { targets: 8, orderable: false, searchable: false, responsivePriority: 5 },
            { targets: [2, 3], responsivePriority: 10 }
        ],
        language: {
            search: 'Search:',
            searchPlaceholder: 'Search travel requests',
            emptyTable: 'No travel requests found for your employee number.',
            zeroRecords: 'No requests match the selected status and search.',
            info: 'Showing _START_ to _END_ of _TOTAL_ requests',
            infoEmpty: 'Showing 0 requests',
            infoFiltered: '(filtered from _MAX_ total requests)',
            lengthMenu: 'Show _MENU_ requests'
        }
    });
    $('.travel-filter').on('click', function () {
        var status = $(this).attr('data-status');
        $('.travel-filter').removeClass('active').attr('aria-pressed', 'false');
        $(this).addClass('active').attr('aria-pressed', 'true');
        table.column(4).search(status ? '^' + $.fn.dataTable.util.escapeRegex(status) + '$' : '', true, false).draw();
    });
});
</script>
</body>
</html>
