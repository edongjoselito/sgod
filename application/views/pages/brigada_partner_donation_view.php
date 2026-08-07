<?php
$esc = function ($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$schoolAddress = function ($donation) {
    $parts = array_filter([
        trim((string) ($donation->sitio ?? '')),
        trim((string) ($donation->brgy ?? '')),
        trim((string) ($donation->city ?? '')),
        trim((string) ($donation->province ?? '')),
    ], function ($part) { return $part !== ''; });
    return implode(', ', $parts);
};
$breakdownTotal = 0.0;
foreach ((array) ($breakdown ?? []) as $item) {
    $breakdownTotal += (float) ($item->amount ?? 0);
}
$donationAmount = (float) ($donation->amount ?? 0);
$breakdownMatches = $donationAmount > 0 && abs($breakdownTotal - $donationAmount) < 0.01;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $esc($title); ?></title>
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/dashboard-unified.css" rel="stylesheet">
    <style>
        body { background:#f4f8fc; }
        .donation-shell { padding:30px 0; }
        .donation-hero { display:flex; align-items:center; justify-content:space-between; gap:24px; padding:28px; border-radius:20px; color:#fff; background:linear-gradient(135deg,#272b8c,#3c40c6); box-shadow:0 18px 40px rgba(39,43,140,.16); }
        .donation-hero h1 { margin:8px 0; color:#fff; font-size:2rem; font-weight:700; }
        .donation-hero p { margin:0; color:rgba(255,255,255,.82); }
        .donation-card { margin-top:24px; border:0; border-radius:18px; box-shadow:0 16px 38px rgba(15,23,42,.08); overflow:hidden; }
        .donation-card .table th { border-top:0; color:#68708a; font-size:.72rem; letter-spacing:.06em; text-transform:uppercase; white-space:nowrap; }
        .donation-card .table td { vertical-align:middle; }
        .school-address { display:block; margin-top:4px; color:#6c757d; font-size:.8rem; }
        .empty { padding:42px; text-align:center; color:#6c757d; }
        .detail-label { font-weight:600; color:#4b5663; }
        .detail-value { color:#121928; }
        .print-only { display:none; }
        @media (max-width:700px) { .donation-hero { display:block; } }

        @media print {
            @page { size: A4 portrait; margin:18mm; }
            body, .dashboard-root-theme, #wrapper, .content-page, .content, .dashboard-shell, .donation-shell { background:#fff !important; color:#111 !important; }
            .donation-hero { background:#0f2040 !important; color:#fff !important; box-shadow:none !important; border-radius:0 !important; padding:20px 16px; }
            .donation-hero h1 { font-size:1.7rem; }
            .donation-hero p { color:rgba(255,255,255,.88) !important; }
            .donation-hero .btn, .donation-hero a { display:none !important; }
            .donation-card { box-shadow:none !important; border:1px solid #d8dde6 !important; border-radius:0 !important; margin-top:18px; }
            .donation-card .table { font-size:.92rem; }
            .donation-card .table th, .donation-card .table td { border-color:#e7edf4 !important; }
            .donation-card .table thead th { color:#2f3a59 !important; }
            .donation-card .table td, .donation-card .table th { padding:.75rem .85rem !important; }
            .donation-card form, .modal, .alert, .btn, .breadcrumb, .topbar, .sidebar, .left-side-menu, .page-title-box, .table-responsive, .dropdown-menu, .dropdown, input, select, textarea { display:none !important; }
            .print-only { display:block !important; }
            .page-break { display:block; page-break-before:always; }
        }
    </style>
</head>
<body class="dashboard-root-theme">
<div id="wrapper">
    <?php include(__DIR__ . '/../includes/top-bar.php'); ?>
    <?php include(__DIR__ . '/../includes/sidebar.php'); ?>
    <div class="content-page"><div class="content">
        <main class="container-fluid dashboard-shell donation-shell">
            <section class="donation-hero">
                <div>
                    <a class="btn btn-sm btn-light" href="<?= base_url(); ?>Brigada/partner_donation_details/<?= (int) $partner->id; ?>">← Back to Donations</a>
                    <h1>Donation Details</h1>
                    <p><?= $esc($partner->name ?? 'Partner'); ?> — detailed breakdown view.</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-light" onclick="window.print();">Print Details</button>
                    <a href="<?= base_url(); ?>Brigada/partner_donation_update/<?= (int) $donation->id; ?>" class="btn btn-outline-light">Edit Donation</a>
                    <a href="<?= base_url(); ?>Brigada/partner_donation_details/<?= (int) $partner->id; ?>" class="btn btn-light">Back to List</a>
                </div>
            </section>
            <section class="print-only">
                <div style="font-family:Arial,Helvetica,sans-serif; color:#24315f; margin-bottom:12px;">
                    <div style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:12px; font-size:0.95rem;">
                        <div>
                            <div style="font-size:1.1rem; font-weight:700;">School Donation Report</div>
                            <div style="color:#4d5d7a;">Partner: <?= $esc($partner->name ?? 'Partner'); ?></div>
                        </div>
                        <div style="text-align:right; color:#4d5d7a;">
                            <div>Print Date: <?= date('F j, Y'); ?></div>
                            <div>Donation ref #: <?= (int) $donation->id; ?></div>
                        </div>
                    </div>
                </div>
                <hr style="border:none; border-top:1px solid #d8dde6; margin:0 0 18px;" />
            </section>

            <section class="card donation-card p-4">
                <h5 class="mb-4">Donation summary</h5>
                <div class="row">
                    <div class="col-md-6 mb-3"><div class="detail-label">Donation Date</div><div class="detail-value"><?= $esc($donation->c_date ?? '—'); ?></div></div>
                    <div class="col-md-6 mb-3"><div class="detail-label">Recipient School</div><div class="detail-value"><?= $esc($donation->schoolName ?? '—'); ?><?php if (($addr = $schoolAddress($donation)) !== ''): ?><span class="school-address"><?= $esc($addr); ?></span><?php endif; ?></div></div>
                    <div class="col-md-6 mb-3"><div class="detail-label">Contribution</div><div class="detail-value"><?= $esc($donation->project_name ?? $donation->spicific_contribution ?? '—'); ?></div></div>
                    <div class="col-md-6 mb-3"><div class="detail-label">Contribution Type</div><div class="detail-value"><?= $esc($donation->contribution_type ?? '—'); ?></div></div>
                    <div class="col-md-3 mb-3"><div class="detail-label">Quantity</div><div class="detail-value"><?= $esc($donation->quantity_of_conftribution ?? '—'); ?></div></div>
                    <div class="col-md-3 mb-3"><div class="detail-label">Unit</div><div class="detail-value"><?= $esc($donation->unit_of_contribution ?? '—'); ?></div></div>
                    <div class="col-md-3 mb-3"><div class="detail-label">Amount</div><div class="detail-value"><?= !empty($donation->amount) ? '₱' . number_format((float) $donation->amount, 2) : '—'; ?></div></div>
                    <div class="col-md-3 mb-3"><div class="detail-label">Agreement Status</div><div class="detail-value"><?= $esc($donation->status_agreement ?? '—'); ?></div></div>
                    <div class="col-md-6 mb-3"><div class="detail-label">Initiated By</div><div class="detail-value"><?= $esc($donation->initiated_by ?? '—'); ?></div></div>
                    <div class="col-md-6 mb-3"><div class="detail-label">Remarks</div><div class="detail-value"><?= $esc($donation->remarks ?? '—'); ?></div></div>
                </div>
            </section>

            <section class="card donation-card p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="mb-0">Breakdown items</h5>
                </div>
                <div class="table-responsive mb-4">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr><th>#</th><th>Description</th><th>Qty</th><th>Unit</th><th>Unit Price</th><th>Total Price</th><th>Remarks</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($breakdown)): ?>
                                <?php foreach ($breakdown as $index => $item): ?>
                                    <?php
                                        $quantity = (float) ($item->quantity ?? 0);
                                        $unitPrice = !empty($item->unit_price) ? (float) $item->unit_price : ($quantity > 0 ? ((float) ($item->amount ?? 0) / $quantity) : 0);
                                        $totalPrice = !empty($item->amount) ? (float) $item->amount : ($quantity * $unitPrice);
                                    ?>
                                    <tr>
                                        <td><?= (int) $index + 1; ?></td>
                                        <td><?= $esc($item->item_description ?? '—'); ?></td>
                                        <td><?= $quantity > 0 ? $esc($item->quantity) : '—'; ?></td>
                                        <td><?= $esc($item->unit ?? '—'); ?></td>
                                        <td><?= $unitPrice > 0 ? '₱' . number_format($unitPrice, 2) : '—'; ?></td>
                                        <td><?= $totalPrice > 0 ? '₱' . number_format($totalPrice, 2) : '—'; ?></td>
                                        <td><?= $esc($item->remarks ?? '—'); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-secondary breakdown-edit-button" 
                                                data-id="<?= (int) $item->id; ?>" 
                                                data-report-id="<?= (int) $donation->id; ?>" 
                                                data-description="<?= $esc($item->item_description ?? ''); ?>" 
                                                data-quantity="<?= $esc($item->quantity ?? ''); ?>" 
                                                data-unit="<?= $esc($item->unit ?? ''); ?>" 
                                                data-unit-price="<?= $esc($item->unit_price ?? ''); ?>" 
                                                data-remarks="<?= $esc($item->remarks ?? ''); ?>">
                                                Edit
                                            </button>
                                            <a href="<?= base_url(); ?>Brigada/partner_donation_breakdown_delete/<?= (int) $item->id; ?>/<?= (int) $donation->id; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this item?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="5" class="text-right font-weight-bold">Total breakdown</td>
                                    <td class="font-weight-bold"><?= '₱' . number_format($breakdownTotal, 2); ?></td>
                                    <td colspan="2"></td>
                                </tr>
                            <?php else: ?>
                                <tr><td colspan="8" class="empty">No breakdown items recorded yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($donationAmount > 0): ?>
                    <div class="mb-3">
                        <?php if ($breakdownMatches): ?>
                            <div class="alert alert-success mb-0">The breakdown total matches the donated amount.</div>
                        <?php else: ?>
                            <div class="alert alert-warning mb-0">Warning: the breakdown total of <?= '₱' . number_format($breakdownTotal, 2); ?> does not match the donated amount of <?= '₱' . number_format($donationAmount, 2); ?>.</div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= base_url(); ?>Brigada/partner_donation_breakdown_save">
                    <input type="hidden" name="report_id" value="<?= (int) $donation->id; ?>">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Item Description</label>
                            <input type="text" class="form-control" name="item_description" placeholder="e.g. school bags" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" step="1" min="0" class="form-control" name="breakdown_quantity">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Unit</label>
                            <select class="form-control" name="breakdown_unit">
                                <option value="">Select a unit</option>
                                <option value="pcs">pcs</option>
                                <option value="set">set</option>
                                <option value="bundle">bundle</option>
                                <option value="box">box</option>
                                <option value="pack">pack</option>
                                <option value="lot">lot</option>
                                <option value="kg">kg</option>
                                <option value="litre">litre</option>
                                <option value="piece">piece</option>
                                <option value="unit">unit</option>
                                <option value="others">Others</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Unit Price</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="breakdown_unit_price" placeholder="0.00">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Remarks</label>
                            <input type="text" class="form-control" name="breakdown_remarks" placeholder="Optional note">
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Add Breakdown Item</button>
                    </div>
                </form>
            </section>

            <div class="modal fade" id="breakdownEditModal" tabindex="-1" role="dialog" aria-labelledby="breakdownEditModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="breakdownEditModalLabel">Edit Breakdown Item</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="<?= base_url(); ?>Brigada/partner_donation_breakdown_update">
                                <input type="hidden" name="breakdown_id" id="editBreakdownId" value="">
                                <input type="hidden" name="report_id" id="editReportId" value="">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Item Description</label>
                                        <input type="text" class="form-control" name="item_description" id="editItemDescription" required>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Quantity</label>
                                        <input type="number" step="1" min="0" class="form-control" name="breakdown_quantity" id="editBreakdownQuantity">
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Unit</label>
                                        <select class="form-control" name="breakdown_unit" id="editBreakdownUnit">
                                            <option value="">Select a unit</option>
                                            <option value="pcs">pcs</option>
                                            <option value="set">set</option>
                                            <option value="bundle">bundle</option>
                                            <option value="box">box</option>
                                            <option value="pack">pack</option>
                                            <option value="lot">lot</option>
                                            <option value="kg">kg</option>
                                            <option value="litre">litre</option>
                                            <option value="piece">piece</option>
                                            <option value="unit">unit</option>
                                            <option value="others">Others</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Unit Price</label>
                                        <input type="number" step="0.01" min="0" class="form-control" name="breakdown_unit_price" id="editBreakdownUnitPrice" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Remarks</label>
                                        <input type="text" class="form-control" name="breakdown_remarks" id="editBreakdownRemarks" placeholder="Optional note">
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div><?php include(__DIR__ . '/../includes/footer.php'); ?></div>
</div>
<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
<script>
    $(function() {
        $('.breakdown-edit-button').on('click', function() {
            var button = $(this);
            $('#editBreakdownId').val(button.data('id'));
            $('#editReportId').val(button.data('report-id'));
            $('#editItemDescription').val(button.data('description'));
            $('#editBreakdownQuantity').val(button.data('quantity'));
            $('#editBreakdownUnit').val(button.data('unit'));
            $('#editBreakdownUnitPrice').val(button.data('unit-price'));
            $('#editBreakdownRemarks').val(button.data('remarks'));
            $('#breakdownEditModal').modal('show');
        });
    });
</script>
</body>
</html>
