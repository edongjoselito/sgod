<?php
$esc = function ($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
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
    <style>
        body { background:#f4f8fc; }
        .edit-shell { padding:30px 0; }
        .edit-card { margin-top:24px; border:0; border-radius:18px; box-shadow:0 16px 38px rgba(15,23,42,.08); overflow:hidden; }
        .edit-card .form-label { font-weight:600; }
    </style>
</head>
<body class="dashboard-root-theme">
<div id="wrapper">
    <?php include(__DIR__ . '/../includes/top-bar.php'); ?>
    <?php include(__DIR__ . '/../includes/sidebar.php'); ?>
    <div class="content-page"><div class="content">
        <main class="container-fluid dashboard-shell edit-shell">
            <section class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <a class="btn btn-sm btn-light" href="<?= base_url(); ?>Brigada/partner_donation_details/<?= (int) $partner->id; ?>">← Back</a>
                    <h1 class="mt-2">Edit Donation</h1>
                    <p>Update details for <?= $esc($partner->name ?? 'Partner'); ?> donation.</p>
                </div>
            </section>
            <section class="card edit-card p-4">
                <form method="post" action="<?= base_url(); ?>Brigada/partner_donation_update/<?= (int) $donation->id; ?>">
                    <input type="hidden" name="id" value="<?= (int) $donation->id; ?>">
                    <input type="hidden" name="partners_id" value="<?= (int) $partner->id; ?>">
                    <input type="hidden" name="sy" value="<?= $esc($donation->sy ?? ''); ?>">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Donation Date</label>
                            <input type="date" class="form-control" name="c_date" value="<?= $esc($donation->c_date ?? ''); ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Recipient School</label>
                            <select class="form-control" name="school_id" required>
                                <option value="">Select a school</option>
                                <?php foreach ((array) ($schools ?? []) as $school): ?>
                                    <option value="<?= $esc($school->schoolID ?? ''); ?>" <?= ((string) ($school->schoolID ?? '') === (string) ($donation->school_id ?? '')) ? 'selected' : ''; ?>><?= $esc(trim(($school->schoolName ?? '') . ' (' . ($school->schoolID ?? '') . ')')); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Contribution Type</label>
                            <select class="form-control" name="contribution_id" required>
                                <option value="">Select a type</option>
                                <?php foreach ((array) ($contributionTypes ?? []) as $type): ?>
                                    <option value="<?= (int) ($type->id ?? 0); ?>" <?= ((int) ($type->id ?? 0) === (int) ($donation->contribution_id ?? 0)) ? 'selected' : ''; ?>><?= $esc(str_replace('_', ' ', $type->name ?? '')); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check mt-3 pt-1">
                                <input class="form-check-input" type="checkbox" id="taxIncentiveApplicable" name="tax_incentive_applicable" value="1" <?= !empty($donation->tax_incentive_applicable) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="taxIncentiveApplicable">To avail tax incentives</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Project / Description</label>
                            <input type="text" class="form-control" name="project_name" value="<?= $esc($donation->project_name ?? $donation->spicific_contribution ?? ''); ?>" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" step="1" min="0" class="form-control" name="quantity_of_conftribution" value="<?= $esc($donation->quantity_of_conftribution ?? ''); ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Unit</label>
                            <select class="form-control" name="unit_of_contribution">
                                <option value="">Select a unit</option>
                                <?php foreach (['pcs','set','bundle','box','pack','lot','kg','litre','piece','unit','others'] as $unit): ?>
                                    <option value="<?= $esc($unit); ?>" <?= ((string) ($donation->unit_of_contribution ?? '') === $unit) ? 'selected' : ''; ?>><?= $esc($unit); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Amount</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="amount" value="<?= $esc($donation->amount ?? ''); ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Agreement Status</label>
                            <input type="text" class="form-control" name="status_agreement" value="<?= $esc($donation->status_agreement ?? ''); ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Initiated By</label>
                            <input type="text" class="form-control" name="initiated_by" value="<?= $esc($donation->initiated_by ?? ''); ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Remarks</label>
                            <input type="text" class="form-control" name="remarks" value="<?= $esc($donation->remarks ?? ''); ?>">
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-secondary mr-2" onclick="window.history.back();">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </section>
        </main>
    </div><?php include(__DIR__ . '/../includes/footer.php'); ?></div>
</div>
<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
</body>
</html>
