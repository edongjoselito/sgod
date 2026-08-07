<?php
$esc = function($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$schoolAddress = function($donation) {
    $parts = array_filter(array(
        trim((string) ($donation->sitio ?? '')), trim((string) ($donation->brgy ?? '')),
        trim((string) ($donation->city ?? '')), trim((string) ($donation->province ?? ''))
    ), function($part) { return $part !== ''; });
    return implode(', ', $parts);
};
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title><?= $esc($title); ?></title><link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet"><link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet"><link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet"><link href="<?= base_url(); ?>assets/css/dashboard-unified.css" rel="stylesheet"><link href="<?= base_url(); ?>assets/libs/select2/select2.min.css" rel="stylesheet" type="text/css"><style>body{background:#f4f8fc}.donation-shell{padding:30px 0}.donation-hero{padding:28px;border-radius:20px;color:#fff;background:linear-gradient(135deg,#272b8c,#3c40c6);box-shadow:0 18px 40px rgba(39,43,140,.16)}.donation-hero h1{margin:8px 0;color:#fff;font-size:2rem;font-weight:700}.donation-hero p{margin:0;color:rgba(255,255,255,.82)}.donation-card{margin-top:24px;border:0;border-radius:18px;box-shadow:0 16px 38px rgba(15,23,42,.08);overflow:hidden}.donation-card .table th{border-top:0;color:#68708a;font-size:.72rem;letter-spacing:.06em;text-transform:uppercase;white-space:nowrap}.donation-card .table td{vertical-align:middle}.school-address{display:block;margin-top:4px;color:#6c757d;font-size:.8rem}.empty{padding:42px;text-align:center;color:#6c757d}</style></head><body class="dashboard-root-theme"><div id="wrapper"><?php include(__DIR__ . '/../includes/top-bar.php'); ?><?php include(__DIR__ . '/../includes/sidebar.php'); ?><div class="content-page"><div class="content"><main class="container-fluid dashboard-shell donation-shell"><section class="donation-hero d-flex align-items-center justify-content-between flex-wrap"><div>
                    <a class="btn btn-sm btn-light" href="<?= base_url(); ?>Brigada/list_of_partners">← Back to Partners</a>
                    <h1>Donation Details</h1>
                    <p><?= $esc($partner->name ?? 'Partner'); ?> — recorded Brigada Eskwela support.</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#partnerDonationModal">
                        + Add Donation
                    </button>
                </div>
            </section>

            <div class="modal fade" id="partnerDonationModal" tabindex="-1" role="dialog" aria-labelledby="partnerDonationModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="partnerDonationModalLabel">Add Donation for <?= $esc($partner->name ?? 'Partner'); ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="<?= base_url(); ?>Brigada/partner_donation_save">
                                <input type="hidden" name="partners_id" value="<?= (int) $partner->id; ?>">
                                <input type="hidden" name="sy" value="<?= $esc($currentSy ?? ''); ?>">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Donation Date</label>
                                        <input type="date" class="form-control" name="c_date" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Recipient School</label>
                                        <select id="partnerSchoolSelect" class="form-control select2-school" name="school_id" required>
                                            <option value="">Select a school</option>
                                            <?php foreach ((array) ($schools ?? []) as $school): ?>
                                                <option value="<?= $esc($school->schoolID ?? ''); ?>"><?= $esc(trim(($school->schoolName ?? '') . ' (' . ($school->schoolID ?? '') . ')')); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Contribution Type</label>
                                        <select class="form-control" name="contribution_id" required>
                                            <option value="">Select a type</option>
                                            <?php foreach ((array) ($contributionTypes ?? []) as $type): ?>
                                                <option value="<?= (int) ($type->id ?? 0); ?>"><?= $esc(str_replace('_', ' ', $type->name ?? '')); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check mt-3 pt-1">
                                            <input class="form-check-input" type="checkbox" id="taxIncentiveApplicable" name="tax_incentive_applicable" value="1">
                                            <label class="form-check-label" for="taxIncentiveApplicable">To avail tax incentives</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Project / Contribution Description</label>
                                        <input type="text" class="form-control" name="project_name" placeholder="e.g. school supplies donation" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Quantity</label>
                                        <input type="number" step="1" min="0" class="form-control" name="quantity_of_conftribution">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Unit</label>
                                        <select class="form-control" name="unit_of_contribution">
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
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Amount</label>
                                        <input type="number" step="0.01" min="0" class="form-control" name="amount" placeholder="0.00">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Agreement Status</label>
                                        <input type="text" class="form-control" name="status_agreement" placeholder="e.g. Completed">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Initiated By</label>
                                        <input type="text" class="form-control" name="initiated_by" placeholder="Organization or contact">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Remarks</label>
                                        <input type="text" class="form-control" name="remarks" placeholder="Optional notes">
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save Donation</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <section class="card donation-card"><div class="card-body p-0"><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Date</th><th>Recipient School</th><th>Contribution</th><th>Type</th><th>Qty</th><th>Unit</th><th>Amount</th><th>Status</th><th>Tax Incentive</th><th>Remarks</th><th>Action</th></tr></thead><tbody><?php foreach((array) $donations as $donation): ?><?php $address = $schoolAddress($donation); ?><tr><td><?= $esc($donation->c_date ?? ''); ?></td><td><strong><?= $esc($donation->schoolName ?? '—'); ?></strong><?php if($address !== ''): ?><span class="school-address"><?= $esc($address); ?></span><?php endif; ?></td><td><?= $esc($donation->project_name ?? $donation->spicific_contribution ?? '—'); ?></td><td><?= $esc($donation->contribution_type ?? '—'); ?></td><td><?= $esc($donation->quantity_of_conftribution ?? '—'); ?></td><td><?= $esc($donation->unit_of_contribution ?? '—'); ?></td><td><?= !empty($donation->amount) ? '₱' . number_format((float) $donation->amount, 2) : '—'; ?></td><td><?= $esc($donation->status_agreement ?? '—'); ?></td><td><?= !empty($donation->tax_incentive_applicable) ? 'Yes' : 'No'; ?></td><td><?= $esc($donation->remarks ?? '—'); ?></td><td><div class="btn-group" role="group" aria-label="Donation actions"><a href="<?= base_url(); ?>Brigada/partner_donation_view/<?= (int) $donation->id; ?>" class="btn btn-sm btn-outline-info">View</a><a href="<?= base_url(); ?>Brigada/partner_donation_update/<?= (int) $donation->id; ?>" class="btn btn-sm btn-outline-secondary">Edit</a><?php if(!empty($donation->tax_incentive_applicable)): ?><a href="<?= base_url(); ?>Brigada/tax_incentive_requirements/<?= (int) $donation->id; ?>" class="btn btn-sm btn-outline-primary">Requirements</a><?php endif; ?><a href="<?= base_url(); ?>Brigada/partner_donation_delete/<?= (int) $donation->id; ?>/<?= (int) $partner->id; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this donation?');">Delete</a></div></td></tr><?php endforeach; ?><?php if(empty($donations)): ?><tr><td colspan="11" class="empty">No donation records found for this partner.</td></tr><?php endif; ?></tbody></table></div></div></section></main></div><?php include(__DIR__ . '/../includes/footer.php'); ?></div></div><script src="<?= base_url(); ?>assets/js/vendor.min.js"></script><script src="<?= base_url(); ?>assets/libs/select2/select2.min.js"></script><script src="<?= base_url(); ?>assets/js/app.min.js"></script><script>$(function(){ $('#partnerSchoolSelect').select2({ width: '100%', dropdownParent: $('#partnerDonationModal') }); });</script></body></html>
