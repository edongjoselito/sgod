<?php
$esc = function ($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$attCount = count((array) ($attachments ?? []));
$flagCount = count((array) ($flags ?? []));
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
                <span class="br-hero-eyebrow"><i class="mdi mdi-pencil-outline"></i> Edit Donation</span>
                <h1>Edit Donation</h1>
                <p>Update details for <?= $esc($partner->name ?? 'Partner'); ?> donation.</p>
                <div class="br-hero-actions">
                    <a class="btn btn-light btn-sm" href="<?= base_url(); ?>Brigada/partner_donation_details/<?= (int) $partner->id; ?>"><i class="mdi mdi-arrow-left"></i> Back</a>
                </div>
            </section>

            <?php if ($flash = $this->session->flashdata('success')): ?>
                <div class="alert alert-success"><i class="mdi mdi-check-circle"></i> <?= $esc($flash); ?></div>
            <?php endif; ?>
            <?php if ($flash = $this->session->flashdata('danger')): ?>
                <div class="alert alert-danger"><i class="mdi mdi-alert-circle"></i> <?= $esc($flash); ?></div>
            <?php endif; ?>

            <section class="br-card">
                <div class="br-card-header"><h5><i class="mdi mdi-file-document-edit" style="color:var(--br-navy-light);"></i> Donation Details</h5></div>
                <div class="br-card-body">
                    <form method="post" action="<?= base_url(); ?>Brigada/partner_donation_update/<?= (int) $donation->id; ?>">
                        <input type="hidden" name="id" value="<?= (int) $donation->id; ?>">
                        <input type="hidden" name="partners_id" value="<?= (int) $partner->id; ?>">
                        <input type="hidden" name="sy" value="<?= $esc($donation->sy ?? ''); ?>">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold"><i class="mdi mdi-calendar" style="color:var(--br-muted);"></i> Donation Date</label>
                                <input type="date" class="form-control" name="c_date" value="<?= $esc($donation->c_date ?? ''); ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold"><i class="mdi mdi-school" style="color:var(--br-muted);"></i> Recipient School</label>
                                <select class="form-control" name="school_id" required>
                                    <option value="">Select a school</option>
                                    <?php foreach ((array) ($schools ?? []) as $school): ?>
                                        <option value="<?= $esc($school->schoolID ?? ''); ?>" <?= ((string) ($school->schoolID ?? '') === (string) ($donation->school_id ?? '')) ? 'selected' : ''; ?>><?= $esc(trim(($school->schoolName ?? '') . ' (' . ($school->schoolID ?? '') . ')')); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold"><i class="mdi mdi-shape" style="color:var(--br-muted);"></i> Contribution Type</label>
                                <select class="form-control" name="contribution_id" required>
                                    <option value="">Select a type</option>
                                    <?php foreach ((array) ($contributionTypes ?? []) as $type): ?>
                                        <option value="<?= (int) ($type->id ?? 0); ?>" <?= ((int) ($type->id ?? 0) === (int) ($donation->contribution_id ?? 0)) ? 'selected' : ''; ?>><?= $esc(str_replace('_', ' ', $type->name ?? '')); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold"><i class="mdi mdi-text" style="color:var(--br-muted);"></i> Project / Description</label>
                                <input type="text" class="form-control" name="project_name" value="<?= $esc($donation->project_name ?? $donation->spicific_contribution ?? ''); ?>" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold"><i class="mdi mdi-numeric" style="color:var(--br-muted);"></i> Quantity</label>
                                <input type="number" step="1" min="0" class="form-control" name="quantity_of_conftribution" value="<?= $esc($donation->quantity_of_conftribution ?? ''); ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold"><i class="mdi mdi-ruler" style="color:var(--br-muted);"></i> Unit</label>
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
                                <label class="form-label font-weight-bold"><i class="mdi mdi-cash" style="color:var(--br-muted);"></i> Amount</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="amount" value="<?= $esc($donation->amount ?? ''); ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold"><i class="mdi mdi-handshake" style="color:var(--br-muted);"></i> Agreement Status</label>
                                <input type="text" class="form-control" name="status_agreement" value="<?= $esc($donation->status_agreement ?? ''); ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold"><i class="mdi mdi-account" style="color:var(--br-muted);"></i> Initiated By</label>
                                <input type="text" class="form-control" name="initiated_by" value="<?= $esc($donation->initiated_by ?? ''); ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold"><i class="mdi mdi-comment" style="color:var(--br-muted);"></i> Remarks</label>
                                <input type="text" class="form-control" name="remarks" value="<?= $esc($donation->remarks ?? ''); ?>">
                            </div>
                        </div>
                        <div class="form-check mb-3" style="padding-left:2.25rem;">
                            <input class="form-check-input" type="checkbox" id="taxIncentiveApplicable" name="tax_incentive_applicable" value="1" <?= !empty($donation->tax_incentive_applicable) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="taxIncentiveApplicable" style="font-weight:600;color:var(--br-ink);"><i class="mdi mdi-percent" style="color:var(--br-green);"></i> To avail tax incentives</label>
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-light mr-2" onclick="window.history.back();">Cancel</button>
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Save Changes</button>
                        </div>
                    </form>
                </div>
            </section>

            <section class="br-card">
                <div class="br-card-header">
                    <h5><i class="mdi mdi-paperclip" style="color:var(--br-teal);"></i> Supporting Documents</h5>
                    <div style="display:flex;gap:8px;align-items:center;">
                        <?php if ($flagCount > 0): ?><span class="br-badge br-badge-warning"><i class="mdi mdi-flag"></i> <?= $flagCount; ?> flag<?= $flagCount === 1 ? '' : 's'; ?></span><?php endif; ?>
                        <span class="br-badge br-badge-info"><?= $attCount; ?> file<?= $attCount === 1 ? '' : 's'; ?></span>
                    </div>
                </div>
                <div class="br-card-body">
                    <?php if (!empty($flags)): ?>
                        <div class="alert alert-warning" style="margin-top:0;">
                            <div>
                                <strong><i class="mdi mdi-flag"></i> Flagged issues:</strong>
                                <div class="br-flags mt-2">
                                    <?php foreach ($flags as $fl): ?>
                                        <span class="br-badge <?= $fl->severity === 'error' ? 'br-badge-error' : 'br-badge-warning'; ?>" title="<?= $esc($fl->detail); ?>"><?= $esc(str_replace('_', ' ', $fl->flag_code)); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form method="post" enctype="multipart/form-data" action="<?= base_url(); ?>Brigada/attachment_upload" style="background:var(--br-surface-2);padding:20px;border-radius:var(--br-radius);border:2px dashed var(--br-border-2);">
                        <input type="hidden" name="entity_type" value="contribution">
                        <input type="hidden" name="entity_id" value="<?= (int) ($donation->id ?? 0); ?>">
                        <input type="hidden" name="sy" value="<?= $esc($donation->sy ?? ''); ?>">
                        <input type="hidden" name="school_id" value="<?= $esc($donation->school_id ?? ''); ?>">
                        <input type="hidden" name="redirect" value="<?= current_url(); ?>">
                        <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                            <div style="flex:1;min-width:200px;">
                                <label class="form-label font-weight-bold" style="margin-bottom:8px;"><i class="mdi mdi-cloud-upload" style="color:var(--br-navy-light);"></i> Attach a document</label>
                                <input type="file" name="file" class="form-control-file" required style="font-size:0.88rem;">
                                <small style="color:var(--br-muted);display:block;margin-top:6px;">PDF, JPG, PNG, DOCX, XLSX — max 10 MB</small>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-upload"></i> Upload</button>
                        </div>
                    </form>

                    <?php if (!empty($attachments)): ?>
                        <ul class="br-attach-list">
                            <?php foreach ($attachments as $att): $ext = strtolower(pathinfo($att->file_name, PATHINFO_EXTENSION)); $icon = 'mdi-file-outline';
                                if (in_array($ext, ['jpg','jpeg','png','gif'])) $icon = 'mdi-file-image';
                                elseif ($ext === 'pdf') $icon = 'mdi-file-pdf';
                                elseif (in_array($ext, ['doc','docx'])) $icon = 'mdi-file-word';
                                elseif (in_array($ext, ['xls','xlsx'])) $icon = 'mdi-file-excel';
                            ?>
                                <li>
                                    <div>
                                        <a href="<?= base_url(); ?><?= $esc($att->file_path); ?>" target="_blank"><i class="mdi <?= $icon; ?>"></i> <?= $esc($att->file_name); ?></a>
                                        <span class="br-attach-meta"><?= strtoupper($esc($att->mime_type ?? '')); ?> · <?= number_format((int) $att->size_bytes); ?> bytes · <?= $esc($att->uploaded_by ?? ''); ?> · <?= $esc($att->uploaded_at ?? ''); ?></span>
                                    </div>
                                    <form method="post" action="<?= base_url(); ?>Brigada/attachment_delete" onsubmit="return confirm('Delete this attachment?')">
                                        <input type="hidden" name="attachment_id" value="<?= (int) $att->id; ?>">
                                        <input type="hidden" name="redirect" value="<?= current_url(); ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="mdi mdi-delete"></i></button>
                                    </form>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="br-empty"><i class="mdi mdi-paperclip"></i><p>No documents attached yet.</p></div>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div><?php include(__DIR__ . '/../includes/footer.php'); ?></div>
</div>
<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
</body>
</html>
