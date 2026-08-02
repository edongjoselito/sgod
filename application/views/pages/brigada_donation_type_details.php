<?php
$esc = function ($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$schoolAddress = function ($donation) {
    $parts = array_filter([trim((string) ($donation->sitio ?? '')), trim((string) ($donation->brgy ?? '')), trim((string) ($donation->city ?? '')), trim((string) ($donation->province ?? ''))], function ($part) { return $part !== ''; });
    return implode(', ', $parts);
};
?>
<script>
window.addEventListener('load', function () {
    var css = document.createElement('link');
    css.rel = 'stylesheet';
    css.href = '<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.css';
    document.head.appendChild(css);
    var enhancement = document.createElement('style');
    enhancement.textContent = '\
        .donation-card .dataTables_wrapper{padding:20px 22px 22px;background:#fff} \
        .donation-card .dataTables_length,.donation-card .dataTables_filter{margin-bottom:18px;color:#68708a;font-size:.85rem} \
        .donation-card .dataTables_filter input,.donation-card .dataTables_length select{height:36px;margin-left:7px;border:1px solid #dce3f0;border-radius:8px;background:#fff;color:#293047;box-shadow:none} \
        .donation-card .dataTables_filter input{padding:6px 10px} \
        .donation-card .dataTables_filter input:focus,.donation-card .dataTables_length select:focus{border-color:#3c40c6;outline:0;box-shadow:0 0 0 3px rgba(60,64,198,.12)} \
        .donation-card table.dataTable{margin-top:0!important;border:1px solid #e8ecf5;border-radius:10px;overflow:hidden} \
        .donation-card table.dataTable thead th{padding:14px 12px!important;border-bottom:1px solid #dce3f0!important;background:#f8faff;color:#52607a!important} \
        .donation-card table.dataTable tbody td{padding:13px 12px!important;border-top:1px solid #edf0f6} \
        .donation-card table.dataTable tbody tr:hover{background:#f7f8ff} \
        .donation-card .dataTables_info{padding-top:17px;color:#77819a;font-size:.83rem} \
        .donation-card .dataTables_paginate{padding-top:12px} \
        .donation-card .paginate_button{margin-left:4px!important;border:1px solid #dce3f0!important;border-radius:7px!important;background:#fff!important;color:#525d77!important} \
        .donation-card .paginate_button.current,.donation-card .paginate_button.current:hover{border-color:#3c40c6!important;background:#3c40c6!important;color:#fff!important} \
        .donation-card .paginate_button:hover{border-color:#3c40c6!important;background:#eef0ff!important;color:#272b8c!important}';
    document.head.appendChild(enhancement);

    var datatables = document.createElement('script');
    datatables.src = '<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js';
    datatables.onload = function () {
        var bootstrap = document.createElement('script');
        bootstrap.src = '<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js';
        bootstrap.onload = function () {
            var $table = window.jQuery('.donation-card table');
            if ($table.length && !window.jQuery.fn.DataTable.isDataTable($table)) {
                $table.DataTable({ pageLength: 10, order: [[0, 'desc']], scrollX: true, autoWidth: false });
            }
        };
        document.body.appendChild(bootstrap);
    };
    document.body.appendChild(datatables);
});
</script>
<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title><?= $esc($title); ?></title><link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet"><link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet"><link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet"><link href="<?= base_url(); ?>assets/css/dashboard-unified.css" rel="stylesheet"><style>body{background:#f4f8fc}.donation-shell{padding:30px 0}.donation-hero{display:flex;align-items:center;justify-content:space-between;gap:24px;padding:28px;border-radius:20px;color:#fff;background:linear-gradient(135deg,#272b8c,#3c40c6);box-shadow:0 18px 40px rgba(39,43,140,.16)}.donation-hero h1{margin:8px 0;color:#fff;font-size:2rem;font-weight:700}.donation-hero p{margin:0;color:rgba(255,255,255,.82)}.total-card{min-width:210px;padding:18px 22px;border:1px solid rgba(255,255,255,.28);border-radius:14px;background:rgba(255,255,255,.12)}.total-card span{display:block;color:rgba(255,255,255,.78);font-size:.74rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.total-card strong{display:block;margin-top:6px;color:#fff;font-size:1.8rem}.donation-card{width:100%;min-width:0;margin-top:24px;border:0;border-radius:18px;box-shadow:0 16px 38px rgba(15,23,42,.08);overflow:hidden}.filter-bar{display:flex;align-items:end;gap:14px;padding:20px 22px;border-bottom:1px solid #e8ecf5;background:#fff}.filter-bar label{display:block;margin:0 0 6px;color:#68708a;font-size:.75rem;font-weight:800;letter-spacing:.05em;text-transform:uppercase}.filter-bar .form-control{min-width:260px}.donation-card .table-responsive{width:100%;max-width:100%;overflow-x:auto}.donation-card .table{min-width:1050px}.donation-card .table th{border-top:0;color:#68708a;font-size:.72rem;letter-spacing:.06em;text-transform:uppercase;white-space:nowrap}.donation-card .table td{max-width:260px;vertical-align:middle;overflow-wrap:anywhere;word-break:break-word}.school-address{display:block;margin-top:4px;color:#6c757d;font-size:.8rem}.empty{padding:42px;text-align:center;color:#6c757d}@media(max-width:700px){.donation-hero{display:block}.total-card{margin-top:20px}.filter-bar{display:block}.filter-bar .form-control{min-width:0}.filter-bar .btn{margin-top:10px}}</style></head><body class="dashboard-root-theme"><div id="wrapper"><?php include(__DIR__ . '/../includes/top-bar.php'); ?><?php include(__DIR__ . '/../includes/sidebar.php'); ?><div class="content-page"><div class="content"><main class="container-fluid dashboard-shell donation-shell"><section class="donation-hero"><div><a class="btn btn-sm btn-light" href="<?= base_url(); ?>Brigada/all_donation_details">← Back to All Donations</a><h1><?= $esc(str_replace('_', ' ', $partnerType)); ?> Donation Details</h1><p>Donation records for this partner type.</p></div><div class="total-card"><span>Total Donation Amount</span><strong>₱<?= number_format((float) $totalAmount, 2); ?></strong></div></section><section class="card donation-card"><form class="filter-bar" method="get" action="<?= base_url(); ?>Brigada/donation_type_details"><input type="hidden" name="partner_type" value="<?= $esc($partnerType); ?>"><div><label for="contribution-type-filter">Contribution Type</label><select class="form-control" id="contribution-type-filter" name="contribution_type" onchange="this.form.submit()"><option value="">All Types</option><?php foreach((array) $contributionSummary as $summary): ?><?php $type = (string) ($summary->contribution_type ?? ''); ?><option value="<?= $esc($type); ?>"<?= $selectedContributionType === $type ? ' selected' : ''; ?>><?= $esc(str_replace('_', ' ', $type)); ?></option><?php endforeach; ?></select></div><button class="btn btn-primary" type="submit">View Details</button><?php if($selectedContributionType !== ''): ?><a class="btn btn-light" href="<?= base_url(); ?>Brigada/donation_type_details?partner_type=<?= rawurlencode($partnerType); ?>">Clear</a><?php endif; ?></form><div class="card-body p-0"><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Date</th><th>Partner</th><th>Recipient School</th><th>Contribution</th><th>Type</th><th>Qty</th><th>Unit</th><th>Amount</th><th>Status</th><th>Remarks</th></tr></thead><tbody><?php foreach((array) $donations as $donation): ?><?php $address = $schoolAddress($donation); ?><tr><td><?= $esc($donation->c_date ?? ''); ?></td><td><?= $esc($donation->partner_name ?? '—'); ?></td><td><strong><?= $esc($donation->schoolName ?? '—'); ?></strong><?php if($address !== ''): ?><span class="school-address"><?= $esc($address); ?></span><?php endif; ?></td><td><?= $esc($donation->project_name ?? $donation->spicific_contribution ?? '—'); ?></td><td><?= $esc($donation->contribution_type ?? '—'); ?></td><td><?= $esc($donation->quantity_of_conftribution ?? '—'); ?></td><td><?= $esc($donation->unit_of_contribution ?? '—'); ?></td><td><?= !empty($donation->amount) ? '₱' . number_format((float) $donation->amount, 2) : '—'; ?></td><td><?= $esc($donation->status_agreement ?? '—'); ?></td><td><?= $esc($donation->remarks ?? '—'); ?></td></tr><?php endforeach; ?><?php if(empty($donations)): ?><tr><td colspan="10" class="empty">No donation records found.</td></tr><?php endif; ?></tbody></table></div></div></section></main></div><?php include(__DIR__ . '/../includes/footer.php'); ?></div></div><script src="<?= base_url(); ?>assets/js/vendor.min.js"></script><script src="<?= base_url(); ?>assets/js/app.min.js"></script></body></html>
