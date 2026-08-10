<?php
$esc = function ($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$statusBadge = function ($status) use ($esc) {
    $status = strtolower(trim((string) ($status ?? '')));
    if ($status === '') $status = 'pending';
    $icons = ['pending'=>'mdi-clock-outline','validated'=>'mdi-check-circle','returned'=>'mdi-undo'];
    $i = $icons[$status] ?? 'mdi-circle-outline';
    return '<span class="br-badge br-badge-' . $esc($status) . '"><i class="mdi ' . $i . '"></i>' . ucfirst($esc($status)) . '</span>';
};
$f = $filters ?? array();
$pendingCount = 0; $validatedCount = 0; $returnedCount = 0;
foreach ($donations as $d) {
    $s = strtolower(trim((string) ($d->validation_status ?? '')));
    if ($s === '' || $s === 'pending') $pendingCount++;
    elseif ($s === 'validated') $validatedCount++;
    elseif ($s === 'returned') $returnedCount++;
}
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
    <link href="<?= base_url(); ?>assets/css/brigada-pages.css" rel="stylesheet">
</head>
<body class="dashboard-root-theme">
<div id="wrapper">
    <?php include(__DIR__ . '/../includes/top-bar.php'); ?>
    <?php include(__DIR__ . '/../includes/sidebar.php'); ?>
    <div class="content-page"><div class="content">
        <main class="container-fluid br-shell">
            <section class="br-hero">
                <span class="br-hero-eyebrow"><i class="mdi mdi-clipboard-check-outline"></i> Monitoring &amp; Validation</span>
                <h1>Validation Queue</h1>
                <p>Review, validate, or return school submissions. A school cannot validate its own submission — enforced server-side.</p>
                <div class="br-hero-actions">
                    <a class="btn btn-light btn-sm" href="<?= base_url(); ?>Brigada/all_donation_details"><i class="mdi mdi-format-list-bulleted"></i> All Donations</a>
                    <a class="btn btn-light btn-sm" href="<?= base_url(); ?>Brigada/yoy"><i class="mdi mdi-chart-line"></i> Year-on-Year</a>
                </div>
            </section>

            <?php if ($flash = $this->session->flashdata('success')): ?>
                <div class="alert alert-success"><i class="mdi mdi-check-circle"></i> <?= $esc($flash); ?></div>
            <?php endif; ?>
            <?php if ($flash = $this->session->flashdata('danger')): ?>
                <div class="alert alert-danger"><i class="mdi mdi-alert-circle"></i> <?= $esc($flash); ?></div>
            <?php endif; ?>

            <section class="br-kpi-grid">
                <div class="br-kpi accent-amber">
                    <div class="br-kpi-top">
                        <div class="br-kpi-icon"><i class="mdi mdi-clock-outline"></i></div>
                        <span class="br-kpi-trend neutral"><?= $pendingCount; ?> pending</span>
                    </div>
                    <span class="br-kpi-value"><?= $pendingCount; ?></span>
                    <span class="br-kpi-label">Awaiting Review</span>
                </div>
                <div class="br-kpi accent-green">
                    <div class="br-kpi-top">
                        <div class="br-kpi-icon"><i class="mdi mdi-check-circle"></i></div>
                        <span class="br-kpi-trend up">validated</span>
                    </div>
                    <span class="br-kpi-value"><?= $validatedCount; ?></span>
                    <span class="br-kpi-label">Validated</span>
                </div>
                <div class="br-kpi accent-coral">
                    <div class="br-kpi-top">
                        <div class="br-kpi-icon"><i class="mdi mdi-undo"></i></div>
                        <span class="br-kpi-trend down">returned</span>
                    </div>
                    <span class="br-kpi-value"><?= $returnedCount; ?></span>
                    <span class="br-kpi-label">Returned</span>
                </div>
                <div class="br-kpi accent-navy">
                    <div class="br-kpi-top">
                        <div class="br-kpi-icon"><i class="mdi mdi-file-multiple"></i></div>
                    </div>
                    <span class="br-kpi-value"><?= count($donations); ?></span>
                    <span class="br-kpi-label">Total Submissions</span>
                </div>
            </section>

            <section class="br-card">
                <form class="br-filter" method="get" action="<?= base_url(); ?>Brigada/validation_queue">
                    <div>
                        <label for="sy"><i class="mdi mdi-calendar"></i> School Year</label>
                        <select id="sy" name="sy" class="form-control">
                            <option value="">All years</option>
                            <?php foreach ($syValues as $sy): ?>
                                <option value="<?= $esc($sy); ?>" <?= ($f['sy'] ?? '') === $sy ? 'selected' : ''; ?>><?= $esc($sy); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="district"><i class="mdi mdi-map-marker"></i> District</label>
                        <select id="district" name="district" class="form-control">
                            <option value="">All districts</option>
                            <?php foreach ($districts as $d): ?>
                                <option value="<?= $esc($d->discription); ?>" <?= ($f['district'] ?? '') === $d->discription ? 'selected' : ''; ?>><?= $esc($d->discription); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="school_id"><i class="mdi mdi-school"></i> School ID</label>
                        <input id="school_id" name="school_id" class="form-control" value="<?= $esc($f['school_id'] ?? ''); ?>" placeholder="e.g. 1301260">
                    </div>
                    <div>
                        <label for="validation_status"><i class="mdi mdi-flag-checkered"></i> Status</label>
                        <select id="validation_status" name="validation_status" class="form-control">
                            <option value="">All</option>
                            <?php foreach (array('pending','validated','returned') as $s): ?>
                                <option value="<?= $esc($s); ?>" <?= ($f['validation_status'] ?? '') === $s ? 'selected' : ''; ?>><?= ucfirst($esc($s)); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="partner_type"><i class="mdi mdi-account-group"></i> Partner Type</label>
                        <select id="partner_type" name="partner_type" class="form-control">
                            <option value="">All</option>
                            <?php foreach ($partnerTypes as $p): ?>
                                <option value="<?= $esc($p->general_type); ?>" <?= ($f['partner_type'] ?? '') === $p->general_type ? 'selected' : ''; ?>><?= $esc(str_replace('_', ' ', $p->general_type)); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="br-filter-actions">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="mdi mdi-filter-variant"></i> Apply Filters</button>
                        <a class="btn btn-light btn-sm" href="<?= base_url(); ?>Brigada/validation_queue"><i class="mdi mdi-close"></i> Clear</a>
                    </div>
                </form>

                <form method="post" action="<?= base_url(); ?>Brigada/validation_bulk" id="bulkForm">
                    <?php foreach (array('sy','district','school_id','validation_status','partner_type') as $fk): ?>
                        <input type="hidden" name="<?= $fk; ?>" value="<?= $esc($f[$fk] ?? ''); ?>">
                    <?php endforeach; ?>
                    <div class="table-responsive">
                        <table class="table br-table">
                            <thead><tr>
                                <th style="width:40px"><input type="checkbox" id="selectAll"></th>
                                <th>Submitted</th><th>School</th><th>Partner</th><th>Contribution</th>
                                <th>SY</th><th class="text-right">Amount</th><th>Status</th><th>Flags</th><th>Action</th>
                            </tr></thead>
                            <tbody>
                            <?php if (empty($donations)): ?>
                                <tr><td colspan="10" class="br-empty"><i class="mdi mdi-inbox-outline"></i><p>No submissions match these filters.</p></td></tr>
                            <?php else: foreach ($donations as $d):
                                $flags = $this->BrigadaModel->get_flags($d->id);
                                $isPending = in_array(strtolower(trim((string) ($d->validation_status ?? ''))), array('', 'pending', 'returned'), TRUE);
                            ?>
                                <tr>
                                    <td><input type="checkbox" name="report_ids[]" value="<?= (int) $d->id; ?>" class="row-check" <?= $isPending ? '' : 'disabled'; ?>></td>
                                    <td>
                                        <div style="font-weight:600;color:var(--br-ink);"><?= $d->created_at ? $esc(date('M j, Y', strtotime($d->created_at))) : '—'; ?></div>
                                        <?php if (!empty($d->submitted_by)): ?><small style="color:var(--br-muted);">by <?= $esc($d->submitted_by); ?></small><?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?= $esc($d->schoolName ?? $d->school_id ?? '—'); ?></strong>
                                        <?php if (!empty($d->school_district)): ?><br><small style="color:var(--br-muted);"><i class="mdi mdi-map-marker" style="font-size:0.7rem;"></i> <?= $esc($d->school_district); ?></small><?php endif; ?>
                                    </td>
                                    <td><?= $esc($d->partner_name ?? '—'); ?></td>
                                    <td><?= $esc($d->contribution_type ?? $d->spicific_contribution ?? '—'); ?></td>
                                    <td><span class="br-badge br-badge-neutral"><?= $esc($d->sy ?? '—'); ?></span></td>
                                    <td class="text-right amount">₱<?= number_format((float) ($d->amount ?? 0), 2); ?></td>
                                    <td><?= $statusBadge($d->validation_status); ?></td>
                                    <td>
                                        <?php if (!empty($flags)): ?>
                                            <div class="br-flags">
                                            <?php foreach ($flags as $fl): ?>
                                                <span class="br-badge <?= $fl->severity === 'error' ? 'br-badge-error' : 'br-badge-warning'; ?>" title="<?= $esc($fl->detail); ?>"><?= $esc(str_replace('_', ' ', $fl->flag_code)); ?></span>
                                            <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <span style="color:var(--br-muted);">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($isPending): ?>
                                            <button type="button" class="btn btn-sm btn-success act-btn" data-id="<?= (int) $d->id; ?>" data-action="validate" title="Validate"><i class="mdi mdi-check"></i></button>
                                            <button type="button" class="btn btn-sm btn-outline-danger act-btn" data-id="<?= (int) $d->id; ?>" data-action="return" title="Return"><i class="mdi mdi-undo"></i></button>
                                        <?php else: ?>
                                            <span class="br-badge br-badge-neutral"><i class="mdi mdi-lock"></i> locked</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (!empty($donations)): ?>
                        <div class="br-card-header" style="border-top: 1px solid var(--br-border); border-bottom: 0;">
                            <span style="color:var(--br-muted);font-size:0.84rem;font-weight:600;"><?= count($donations); ?> submission<?= count($donations) === 1 ? '' : 's'; ?></span>
                            <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Validate all selected pending submissions?')"><i class="mdi mdi-check-all"></i> Bulk Validate Selected</button>
                        </div>
                    <?php endif; ?>
                </form>
            </section>
        </main>
    </div><?php include(__DIR__ . '/../includes/footer.php'); ?></div>
</div>

<div class="modal fade br-modal" id="decideModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form class="modal-content" method="post" action="<?= base_url(); ?>Brigada/validation_decide" id="decideForm">
        <input type="hidden" name="report_id" id="decideId">
        <input type="hidden" name="action" id="decideAction">
        <?php foreach (array('sy','district','school_id','validation_status','partner_type') as $fk): ?>
            <input type="hidden" name="<?= $fk; ?>" value="<?= $esc($f[$fk] ?? ''); ?>">
        <?php endforeach; ?>
        <div class="modal-header"><h5 id="decideTitle"><i class="mdi mdi-clipboard-check"></i> Validate submission</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
        <div class="modal-body">
            <div class="form-group">
                <label for="remarks"><i class="mdi mdi-comment-text-outline"></i> Remarks <small style="color:var(--br-muted);">(required when returning)</small></label>
                <textarea name="remarks" id="decideRemarks" class="form-control" placeholder="Reason for returning, or notes on the validation…"></textarea>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary" id="decideSubmit">Confirm</button></div>
    </form>
  </div>
</div>

<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
<script>
$('#selectAll').on('change', function () { $('.row-check').not(':disabled').prop('checked', this.checked); });
$('.act-btn').on('click', function () {
    var id = $(this).data('id'), action = $(this).data('action');
    $('#decideId').val(id); $('#decideAction').val(action);
    var isReturn = action === 'return';
    $('#decideTitle').html(isReturn ? '<i class="mdi mdi-undo"></i> Return submission' : '<i class="mdi mdi-check-circle"></i> Validate submission');
    $('#decideSubmit').removeClass('btn-success btn-danger').addClass(isReturn ? 'btn-danger' : 'btn-success').text(isReturn ? 'Return' : 'Validate');
    $('#decideModal').modal('show');
});
</script>
</body>
</html>
