<?php
$records = isset($records) && is_array($records) ? $records : array();
$years = isset($availableYears) && is_array($availableYears) ? $availableYears : array();
$esc = function($value){ return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$priorityBadge = function($p) {
    $p = strtolower(trim((string) $p));
    if ($p === 'high')   return '<span class="br-badge br-badge-error"><i class="mdi mdi-alert"></i> High</span>';
    if ($p === 'medium') return '<span class="br-badge br-badge-warning"><i class="mdi mdi-alert-circle-outline"></i> Medium</span>';
    if ($p === 'low')    return '<span class="br-badge br-badge-success"><i class="mdi mdi-check-circle-outline"></i> Low</span>';
    return '<span class="br-badge br-badge-neutral"><i class="mdi mdi-help-circle-outline"></i> Not set</span>';
};
$highCount = 0; $mediumCount = 0; $lowCount = 0;
foreach ($records as $r) {
    $p = strtolower(trim((string)($r->degree_of_priority ?? '')));
    if ($p === 'high') $highCount++; elseif ($p === 'medium') $mediumCount++; elseif ($p === 'low') $lowCount++;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <?php include('includes/page-title.php'); ?>
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/dashboard-unified.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/brigada-pages.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>.br-hero h2 { color: #fff !important; }</style>
</head>
<body class="dashboard-root-theme">
<div id="wrapper">
    <?php include('includes/top-bar.php'); ?>
    <?php include('includes/sidebar.php'); ?>
    <div class="content-page"><div class="content">
        <main class="container-fluid br-shell">
            <section class="br-hero">
                <span class="br-hero-eyebrow"><i class="mdi mdi-alert-circle-outline"></i> Section Head Workspace</span>
                <h2>Issues / Concerns</h2>
                <p><?= $esc($sectionName); ?> &mdash; record concerns and technical-assistance needs.</p>
                <div class="br-hero-actions">
                    <button class="btn btn-gold btn-sm" type="button" data-toggle="modal" data-target="#issueModal" onclick="openIssueForm()"><i class="mdi mdi-plus"></i> Add Issue / Concern</button>
                </div>
            </section>

            <?php if($this->session->flashdata('success')): ?><div class="alert alert-success"><i class="mdi mdi-check-circle"></i> <?= $esc($this->session->flashdata('success')); ?></div><?php endif; ?>
            <?php if($this->session->flashdata('danger')): ?><div class="alert alert-danger"><i class="mdi mdi-alert-circle"></i> <?= $esc($this->session->flashdata('danger')); ?></div><?php endif; ?>

            <section class="br-kpi-grid">
                <div class="br-kpi accent-navy">
                    <div class="br-kpi-top">
                        <div class="br-kpi-icon"><i class="mdi mdi-format-list-bulleted"></i></div>
                        <span class="br-kpi-trend neutral"><?= $esc($selectedYear); ?></span>
                    </div>
                    <span class="br-kpi-value"><?= count($records); ?></span>
                    <span class="br-kpi-label">Total Records</span>
                </div>
                <div class="br-kpi accent-coral">
                    <div class="br-kpi-top">
                        <div class="br-kpi-icon"><i class="mdi mdi-alert"></i></div>
                        <span class="br-kpi-trend <?= $highCount > 0 ? 'down' : 'neutral'; ?>"><?= $highCount > 0 ? 'urgent' : 'none'; ?></span>
                    </div>
                    <span class="br-kpi-value <?= $highCount > 0 ? 'down' : ''; ?>"><?= $highCount; ?></span>
                    <span class="br-kpi-label">High Priority</span>
                </div>
                <div class="br-kpi accent-amber">
                    <div class="br-kpi-top">
                        <div class="br-kpi-icon"><i class="mdi mdi-alert-circle-outline"></i></div>
                    </div>
                    <span class="br-kpi-value"><?= $mediumCount; ?></span>
                    <span class="br-kpi-label">Medium Priority</span>
                </div>
                <div class="br-kpi accent-green">
                    <div class="br-kpi-top">
                        <div class="br-kpi-icon"><i class="mdi mdi-check-circle-outline"></i></div>
                    </div>
                    <span class="br-kpi-value"><?= $lowCount; ?></span>
                    <span class="br-kpi-label">Low Priority</span>
                </div>
            </section>

            <section class="br-card">
                <form class="br-filter" method="get" action="<?= base_url(); ?>Page/section_head_issues_concerns">
                    <div>
                        <label for="issuesYear"><i class="mdi mdi-calendar"></i> Applicable Year</label>
                        <select class="form-control" id="issuesYear" name="year" onchange="this.form.submit()">
                            <option value="<?= date('Y'); ?>"<?= $selectedYear===date('Y')?' selected':''; ?>><?= date('Y'); ?></option>
                            <?php foreach($years as $year): $value=trim((string)$year->applicable_year); if($value!=='' && $value!==date('Y')): ?>
                                <option value="<?= $esc($value); ?>"<?= $selectedYear===$value?' selected':''; ?>><?= $esc($value); ?></option>
                            <?php endif; endforeach; ?>
                        </select>
                    </div>
                </form>
                <div class="table-responsive">
                    <table id="issuesTable" class="table br-table w-100">
                        <thead><tr><th>Issues / Concern</th><th>Priority</th><th>Probable Cause/s</th><th>Affected Performance</th><th>TA Need</th><th>TA Type</th><th>Actions</th></tr></thead>
                        <tbody>
                        <?php foreach($records as $record): ?>
                            <tr>
                                <td><a style="color:var(--br-navy-light);font-weight:700;text-decoration:none;" title="View full details and print" href="<?= base_url(); ?>Page/section_head_issues_concern_details/<?= (int)$record->id; ?>"><?= $esc($record->issue_concern); ?></a></td>
                                <td><?= $priorityBadge($record->degree_of_priority); ?></td>
                                <td style="color:var(--br-ink-2);"><?= $esc($record->probable_causes); ?></td>
                                <td style="color:var(--br-ink-2);"><?= $esc($record->affected_performance); ?></td>
                                <td><span class="br-badge br-badge-neutral"><?= $esc($record->ta_need); ?></span></td>
                                <td style="color:var(--br-muted);"><?= $esc($record->ta_type); ?></td>
                                <td>
                                    <div style="display:flex;gap:6px;">
                                        <a class="btn btn-sm btn-outline-primary" title="View and print details" href="<?= base_url(); ?>Page/section_head_issues_concern_details/<?= (int)$record->id; ?>"><i class="mdi mdi-printer"></i></a>
                                        <button class="btn btn-sm btn-outline-primary" type="button" title="Edit" data-toggle="modal" data-target="#issueModal" onclick='openIssueForm(<?= json_encode($record, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG); ?>)'><i class="mdi mdi-pencil"></i></button>
                                        <form method="post" action="<?= base_url(); ?>Page/section_head_issues_concerns_delete" onsubmit="return confirm('Remove this issue or concern?');"><input type="hidden" name="id" value="<?= (int)$record->id; ?>"><button class="btn btn-sm btn-outline-danger" title="Delete" type="submit"><i class="mdi mdi-delete"></i></button></form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if(empty($records)): ?><tr><td colspan="7" class="br-empty"><i class="mdi mdi-inbox-outline"></i><p>No issues or concerns recorded for <?= $esc($selectedYear); ?>.</p></td></tr><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div><?php include('includes/footer.php'); ?></div>
</div>

<div class="modal fade br-modal" id="issueModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <form class="modal-content" method="post" action="<?= base_url(); ?>Page/section_head_issues_concerns_save">
        <div class="modal-header"><h5 id="issueModalTitle"><i class="mdi mdi-plus-circle"></i> Add Issue / Concern</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
        <div class="modal-body">
            <input type="hidden" name="id" id="issueId">
            <div class="form-group"><label><i class="mdi mdi-calendar" style="color:var(--br-muted);"></i> Applicable Year</label><input class="form-control" type="number" min="2000" max="2100" name="applicable_year" id="applicableYear" value="<?= date('Y'); ?>"></div>
            <div class="form-group"><label><i class="mdi mdi-alert-circle" style="color:var(--br-muted);"></i> Issues / Concern <span class="text-danger">*</span></label><textarea class="form-control" name="issue_concern" id="issueConcern" rows="3" required></textarea></div>
            <div class="form-group"><label><i class="mdi mdi-flag" style="color:var(--br-muted);"></i> Degree of Priority</label><select class="form-control" name="degree_of_priority" id="degreeOfPriority"><option value="">Select priority</option><option>High</option><option>Medium</option><option>Low</option></select></div>
            <div class="form-group"><label><i class="mdi mdi-help-circle-outline" style="color:var(--br-muted);"></i> Probable Cause/s</label><textarea class="form-control" name="probable_causes" id="probableCauses" rows="3"></textarea></div>
            <div class="form-group"><label><i class="mdi mdi-chart-line" style="color:var(--br-muted);"></i> Affected Performance / Output Indicator/s</label><textarea class="form-control" name="affected_performance" id="affectedPerformance" rows="3"></textarea></div>
            <div class="form-group"><label><i class="mdi mdi-lightbulb" style="color:var(--br-muted);"></i> TA Need (Organization or Performance)</label><select class="form-control" name="ta_need" id="taNeed"><option value="">Select TA need</option><option>Organization</option><option>Performance</option><option>Organization and Performance</option></select></div>
            <div class="form-group mb-0"><label><i class="mdi mdi-tag" style="color:var(--br-muted);"></i> TA Type</label><input class="form-control" name="ta_type" id="taType"></div>
        </div>
        <div class="modal-footer"><button class="btn btn-light" type="button" data-dismiss="modal">Cancel</button><button class="btn btn-primary" type="submit"><i class="mdi mdi-content-save"></i> Save</button></div>
    </form>
  </div>
</div>

<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
<script>
function openIssueForm(item){
    item=item||{};
    document.getElementById('issueModalTitle').innerHTML = item.id ? '<i class="mdi mdi-pencil"></i> Edit Issue / Concern' : '<i class="mdi mdi-plus-circle"></i> Add Issue / Concern';
    document.getElementById('issueId').value=item.id||'';
    document.getElementById('applicableYear').value=item.applicable_year||new Date().getFullYear();
    document.getElementById('issueConcern').value=item.issue_concern||'';
    document.getElementById('degreeOfPriority').value=item.degree_of_priority||'';
    document.getElementById('probableCauses').value=item.probable_causes||'';
    document.getElementById('affectedPerformance').value=item.affected_performance||'';
    document.getElementById('taNeed').value=item.ta_need||'';
    document.getElementById('taType').value=item.ta_type||'';
}
$(function(){ $('#issuesTable').DataTable({ pageLength:25, order:[] }); });
</script>
</body>
</html>
