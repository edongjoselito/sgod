<?php
$hasForm = !empty($bundle);
$form = $hasForm ? $bundle['form'] : array();
$status = $hasForm ? $form['status'] : 'No form selected';
$currentYear = (int) date('Y');
$jsonFlags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
$ipcrfCssVersion = @filemtime(FCPATH . 'assets/css/ipcrf.css') ?: time();
$ipcrfJsVersion = @filemtime(FCPATH . 'assets/js/ipcrf.js') ?: time();
$presetName = 'No preset loaded';
if ($hasForm && !empty($form['template_id'])) {
    foreach ($templates as $template) {
        if ((int) $template['id'] === (int) $form['template_id']) {
            $presetName = $template['name'];
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>IPCRF Performance Management | SGOD MIS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/libs/select2/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/libs/toastr/toastr.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/css/ipcrf.css?v=<?= $ipcrfCssVersion; ?>" rel="stylesheet" type="text/css" />
</head>
<body class="ipcrf-page">
<div id="wrapper">
    <?php include(APPPATH . 'views/includes/top-bar.php'); ?>
    <?php include(APPPATH . 'views/includes/sidebar.php'); ?>

    <div class="content-page">
        <div class="content">
            <div class="container-fluid ipcrf-shell">
                <div class="ipcrf-title-row">
                    <div>
                        <span class="ipcrf-kicker">Performance Management</span>
                        <h1>Individual Performance Commitment and Review</h1>
                        <p>Build, rate, validate and print the complete IPCRF from one screen.</p>
                    </div>
                    <span class="ipcrf-status" id="formStatus"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?></span>
                </div>

                <?php if ($hasForm): ?>
                <div class="ipcrf-toolbar">
                    <button type="button" class="btn btn-soft-primary" id="loadPresetBtn"<?= $edit_scope !== 'full' ? ' disabled' : ''; ?>><i class="mdi mdi-file-restore mr-1"></i>Load Preset</button>
                    <button type="button" class="btn btn-primary" id="saveDraftBtn"<?= $edit_scope === 'none' ? ' disabled' : ''; ?>><i class="mdi mdi-content-save-outline mr-1"></i>Save Draft</button>
                    <button type="button" class="btn btn-soft-secondary" data-toggle="modal" data-target="#editorGuideModal"><i class="mdi mdi-help-circle-outline mr-1"></i>Editing Guide</button>
                    <button type="button" class="btn btn-soft-warning" id="openSummaryBtn" data-toggle="modal" data-target="#summaryModal"><i class="mdi mdi-chart-donut mr-1"></i><span id="toolbarWeight">Weight 0%</span></button>
                    <button type="button" class="btn btn-soft-info" id="validateBtn"><i class="mdi mdi-clipboard-check-outline mr-1"></i>Validate</button>
                    <a class="btn btn-soft-secondary" href="<?= site_url('Ipcrf/report/' . (int) $form['id']); ?>" target="_blank"><i class="mdi mdi-eye-outline mr-1"></i>Preview</a>
                    <span id="workflowButtons" class="d-inline-flex flex-wrap" style="gap:8px"></span>
                    <a class="btn btn-dark" href="<?= site_url('Ipcrf/report/' . (int) $form['id'] . '?autoprint=1'); ?>" target="_blank"><i class="mdi mdi-printer mr-1"></i>Print PDF</a>
                    <span class="toolbar-spacer"></span>
                    <span class="ipcrf-save-state" id="saveState">All changes saved</span>
                </div>

                <div class="ipcrf-workspace">
                    <main class="ipcrf-main">
                        <section class="ipcrf-panel ipcrf-section ipcrf-document" id="employeeSection">
                            <div class="ipcrf-section-head">
                                <div><h2>Employee Information</h2><p>Synced from the existing HRIS employee profile.</p></div>
                                <span class="badge badge-light"><?= htmlspecialchars($presetName, ENT_QUOTES, 'UTF-8'); ?> · Employee copy</span>
                            </div>
                            <div class="ipcrf-section-body">
                                <?php if ($edit_scope !== 'full'): ?>
                                    <div class="scope-notice"><i class="mdi mdi-lock-outline mr-1"></i><?= $edit_scope === 'rater' ? 'Rater review mode: only objective and competency rater ratings can be changed.' : ($edit_scope === 'pmt' ? 'PMT validation mode: only final competency ratings can be changed.' : 'This record is read-only at its current workflow stage.'); ?></div>
                                <?php endif; ?>
                                <div class="ipcrf-pdf-masthead">
                                    <span>Republic of the Philippines · Department of Education</span>
                                    <h2>INDIVIDUAL PERFORMANCE COMMITMENT AND REVIEW FORM (IPCRF)</h2>
                                    <small>Schools Division Office · Performance Management System</small>
                                </div>
                                <div class="employee-pdf-grid">
                                    <div class="employee-pdf-column">
                                        <div class="employee-pdf-row"><label>Name of Employee</label><strong><?= htmlspecialchars($form['employee_name'], ENT_QUOTES, 'UTF-8'); ?></strong></div>
                                        <div class="employee-pdf-row"><label>Employee ID Number</label><strong><?= htmlspecialchars($form['employee_id'], ENT_QUOTES, 'UTF-8'); ?></strong></div>
                                        <div class="employee-pdf-row"><label>Position</label><strong><?= htmlspecialchars($form['position'], ENT_QUOTES, 'UTF-8'); ?></strong></div>
                                        <div class="employee-pdf-row"><label>Office / Section</label><strong><?= htmlspecialchars($form['office'], ENT_QUOTES, 'UTF-8'); ?></strong></div>
                                    </div>
                                    <div class="employee-pdf-column">
                                        <div class="employee-pdf-row employee-rater-row"><label>Name of Rater</label><select id="editorRater" class="form-control ipcrf-input"<?= $edit_scope !== 'full' ? ' disabled' : ''; ?>><option value="<?= htmlspecialchars($form['rater_id'], ENT_QUOTES, 'UTF-8'); ?>" selected><?= htmlspecialchars($form['rater_name'] ?: 'Select rater', ENT_QUOTES, 'UTF-8'); ?></option></select></div>
                                        <div class="employee-pdf-row"><label>Position of Rater</label><strong id="raterPositionDisplay"><?= htmlspecialchars($form['rater_position'], ENT_QUOTES, 'UTF-8'); ?></strong></div>
                                        <div class="employee-pdf-row period-row"><label>Review Period</label><span><input type="date" class="form-control ipcrf-input js-header-field" aria-label="Period start" data-field="period_start" value="<?= htmlspecialchars($form['period_start'], ENT_QUOTES, 'UTF-8'); ?>"<?= $edit_scope !== 'full' ? ' disabled' : ''; ?>><em>to</em><input type="date" class="form-control ipcrf-input js-header-field" aria-label="Period end" data-field="period_end" value="<?= htmlspecialchars($form['period_end'], ENT_QUOTES, 'UTF-8'); ?>"<?= $edit_scope !== 'full' ? ' disabled' : ''; ?>></span></div>
                                        <div class="employee-pdf-row"><label>Current Status</label><strong><?= htmlspecialchars($form['status'], ENT_QUOTES, 'UTF-8'); ?></strong></div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="ipcrf-panel ipcrf-section ipcrf-document" id="kraSection">
                            <div class="ipcrf-section-head">
                                <div><h2>KRAs, Objectives and Performance Standards</h2><p>Expand a KRA or objective to edit all details and 1–5 indicators.</p></div>
                                <button type="button" class="btn btn-sm btn-soft-primary" id="addKraBtn"<?= $edit_scope !== 'full' ? ' disabled' : ''; ?>><i class="mdi mdi-plus mr-1"></i>Add New KRA</button>
                            </div>
                            <div class="ipcrf-section-body">
                                <div class="editor-quick-guide">
                                    <div class="guide-step"><span>1</span><div><strong>Create or edit a KRA</strong><small>Use the clearly labeled buttons in each KRA header.</small></div></div>
                                    <div class="guide-step"><span>2</span><div><strong>Add objectives</strong><small>Enter the objective, timeline and weight in a short dialog.</small></div></div>
                                    <div class="guide-step"><span>3</span><div><strong>Complete standards and results</strong><small>Open an objective row, then expand the 1–5 indicator table.</small></div></div>
                                    <div class="guide-step"><span>4</span><div><strong>Check the total</strong><small>Weight must equal exactly 100% before submission.</small></div></div>
                                </div>
                                <div class="editing-legend">
                                    <span><b class="legend-edit">Edit</b> changes existing details</span>
                                    <span><b class="legend-add">Add</b> creates a new item</span>
                                    <span><b class="legend-open">Open row</b> shows standards, accomplishments and evidence</span>
                                    <span><b class="legend-auto">Autosave</b> saves changes after a short pause</span>
                                </div>
                                <div id="kraContainer"></div>
                            </div>
                        </section>

                        <section class="ipcrf-panel ipcrf-section ipcrf-document" id="competencySection">
                            <div class="ipcrf-section-head">
                                <div><h2>Competency Management</h2><p>1 Rarely Demonstrates · 2 Sometimes · 3 Most of the Time · 4 Consistently · 5 Role Model</p></div>
                                <button type="button" class="btn btn-sm btn-soft-primary" id="addCompetencyBtn"<?= $edit_scope !== 'full' ? ' disabled' : ''; ?>><i class="mdi mdi-plus mr-1"></i>Add Competency</button>
                            </div>
                            <div class="ipcrf-section-body">
                                <div class="rating-legend"><strong>Rating scale:</strong> 5 – Role Model &nbsp;·&nbsp; 4 – Consistently Demonstrates &nbsp;·&nbsp; 3 – Most of the Time Demonstrates &nbsp;·&nbsp; 2 – Sometimes Demonstrates &nbsp;·&nbsp; 1 – Rarely Demonstrates</div>
                                <div id="competencyContainer"></div>
                            </div>
                        </section>

                        <section class="ipcrf-panel ipcrf-section ipcrf-document" id="developmentSection">
                            <div class="ipcrf-section-head">
                                <div><h2>Development Plan</h2><p>Connect strengths and improvement needs with actionable learning interventions.</p></div>
                                <button type="button" class="btn btn-sm btn-soft-primary" id="addPlanBtn"<?= $edit_scope !== 'full' ? ' disabled' : ''; ?>><i class="mdi mdi-plus mr-1"></i>Add Row</button>
                            </div>
                            <div class="ipcrf-section-body table-responsive">
                                <table class="table table-bordered development-table mb-0">
                                    <thead><tr><th>Strengths</th><th>Improvement Needs</th><th>Learning Objectives</th><th>Developmental Interventions</th><th>Target Timeline</th><th>Responsible Person</th><th>Status / Remarks</th><th></th></tr></thead>
                                    <tbody id="developmentContainer"></tbody>
                                </table>
                            </div>
                        </section>
                    </main>
                </div>

                <div class="modal fade" id="kraEditorModal" tabindex="-1" role="dialog" aria-labelledby="kraEditorTitle" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document"><div class="modal-content ipcrf-editor-modal">
                        <form id="kraEditorForm">
                            <div class="modal-header"><div><span class="modal-kicker">Key Result Area</span><h5 class="modal-title" id="kraEditorTitle">Add New KRA</h5></div><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                            <div class="modal-body"><input type="hidden" id="kraEditorIndex" value="-1"><label class="ipcrf-label" for="kraEditorName">KRA Title</label><input type="text" class="form-control ipcrf-input" id="kraEditorName" placeholder="Example: I – Resourcing" required><small class="form-text text-muted">Use a short, descriptive title. Objectives will be added inside this KRA after it is saved.</small></div>
                            <div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary"><i class="mdi mdi-check mr-1"></i>Save KRA</button></div>
                        </form>
                    </div></div>
                </div>

                <div class="modal fade" id="objectiveEditorModal" tabindex="-1" role="dialog" aria-labelledby="objectiveEditorTitle" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document"><div class="modal-content ipcrf-editor-modal">
                        <form id="objectiveEditorForm">
                            <div class="modal-header"><div><span class="modal-kicker" id="objectiveKraLabel">Objective</span><h5 class="modal-title" id="objectiveEditorTitle">Add New Objective</h5></div><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                            <div class="modal-body">
                                <input type="hidden" id="objectiveEditorKra" value="-1"><input type="hidden" id="objectiveEditorIndex" value="-1">
                                <div class="objective-dialog-grid">
                                    <div><label class="ipcrf-label" for="objectiveEditorCode">Objective Code</label><input type="text" class="form-control ipcrf-input" id="objectiveEditorCode" placeholder="Example: 1.1"></div>
                                    <div class="objective-dialog-main"><label class="ipcrf-label" for="objectiveEditorText">Objective</label><textarea class="form-control ipcrf-input" id="objectiveEditorText" rows="4" placeholder="Describe the expected output or result clearly." required></textarea></div>
                                    <div><label class="ipcrf-label" for="objectiveEditorTimeline">Timeline</label><input type="text" class="form-control ipcrf-input" id="objectiveEditorTimeline" placeholder="January to December 2026" required><small class="form-text text-muted">Enter the target period or deadline.</small></div>
                                    <div><label class="ipcrf-label" for="objectiveEditorWeight">Weight Percentage</label><div class="input-group"><input type="number" min="0" max="100" step="0.01" class="form-control ipcrf-input" id="objectiveEditorWeight" value="0" required><div class="input-group-append"><span class="input-group-text">%</span></div></div><small class="form-text text-muted">All objectives together must total 100%.</small></div>
                                </div>
                                <div class="dialog-next-step"><i class="mdi mdi-information-outline"></i><span>After saving, open the objective row to complete its Quality, Efficiency and Timeliness indicators, accomplishment and evidence.</span></div>
                            </div>
                            <div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary"><i class="mdi mdi-check mr-1"></i>Save Objective</button></div>
                        </form>
                    </div></div>
                </div>

                <div class="modal fade" id="editorGuideModal" tabindex="-1" role="dialog" aria-labelledby="editorGuideTitle" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document"><div class="modal-content ipcrf-editor-modal">
                        <div class="modal-header"><div><span class="modal-kicker">Help and Legend</span><h5 class="modal-title" id="editorGuideTitle">How to Complete the IPCRF</h5></div><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                        <div class="modal-body guide-modal-body">
                            <ol><li><strong>Employee Information:</strong> confirm the HRIS information, rater and review period.</li><li><strong>KRAs:</strong> use Add New KRA for a major result area. Use Edit KRA to rename it.</li><li><strong>Objectives:</strong> use Add Objective inside the correct KRA. Set its code, description, timeline and weight.</li><li><strong>Standards:</strong> open an objective row and expand the indicator table. Complete ratings 5 down to 1 for Quality, Efficiency and Timeliness.</li><li><strong>Results:</strong> enter accomplishments and upload supporting evidence in the same open objective row.</li><li><strong>Competencies and Development Plan:</strong> complete the sections below the KRAs.</li><li><strong>Before submission:</strong> open Weight & Validation and resolve every warning. The total weight must be exactly 100%.</li></ol>
                            <div class="guide-note"><strong>Editing states:</strong> Draft and Returned records are fully editable. Rater and PMT stages expose only their assigned rating fields. Validated and Locked records are read-only.</div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">Got it</button></div>
                    </div></div>
                </div>

                <div class="modal fade" id="summaryModal" tabindex="-1" role="dialog" aria-labelledby="summaryModalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document"><div class="modal-content ipcrf-editor-modal">
                        <div class="modal-header"><div><span class="modal-kicker">Form Readiness</span><h5 class="modal-title" id="summaryModalTitle">Weight, Rating and Validation Summary</h5></div><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                        <div class="modal-body summary-modal-grid">
                            <div class="summary-modal-card"><h6>Weight Summary</h6><div class="weight-ring" id="weightRing"><strong id="weightTotal">0%</strong></div><div class="summary-metric"><span>Required</span><strong>100.00%</strong></div><div class="summary-metric"><span>Variance</span><strong id="weightVariance">−100.00%</strong></div></div>
                            <div class="summary-modal-card"><h6>Rating Summary</h6><div class="summary-metric"><span>Weighted Score</span><strong id="weightedScore">0.000</strong></div><div class="summary-metric"><span>Overall Rating</span><strong id="overallRating">0.000</strong></div><div class="summary-metric"><span>Adjectival</span><strong id="adjectivalRating">Not yet rated</strong></div></div>
                            <div class="summary-modal-card"><h6>Validation Checklist</h6><ul class="validation-list" id="validationSummary"></ul><button type="button" class="btn btn-soft-info btn-block mt-3" id="modalValidateBtn"><i class="mdi mdi-clipboard-check-outline mr-1"></i>Run Full Validation</button></div>
                            <div class="summary-modal-card"><h6>Workflow History</h6><div class="history-list" id="historyList"></div></div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">Return to Form</button></div>
                    </div></div>
                </div>
                <?php else: ?>
                <section class="ipcrf-panel ipcrf-empty">
                    <div class="ipcrf-empty-icon"><i class="mdi mdi-clipboard-text-outline"></i></div>
                    <h2>Start or open an employee IPCRF</h2>
                    <p>Select an HRIS employee and rater. If the same employee and performance period already exists, the existing record opens instead of creating a duplicate.</p>
                    <form id="startIpcrfForm" class="ipcrf-start-form">
                        <div><label class="ipcrf-label">Employee</label><select id="startEmployee" name="employee_id" required></select></div>
                        <div><label class="ipcrf-label">Assigned Rater</label><select id="startRater" name="rater_id"></select></div>
                        <div><label class="ipcrf-label">Period Start</label><input type="date" class="form-control ipcrf-input" name="period_start" value="<?= $currentYear; ?>-01-01" required></div>
                        <div><label class="ipcrf-label">Period End</label><input type="date" class="form-control ipcrf-input" name="period_end" value="<?= $currentYear; ?>-12-31" required></div>
                        <button type="submit" class="btn btn-primary"><i class="mdi mdi-arrow-right mr-1"></i>Start / Open</button>
                    </form>

                    <?php if (!empty($recent_forms)): ?>
                    <div class="ipcrf-recent">
                        <h5 class="mb-3">Recent IPCRFs</h5>
                        <div class="ipcrf-recent-grid">
                            <?php foreach ($recent_forms as $recent): ?>
                            <a class="recent-card" href="<?= site_url('Ipcrf/index/' . (int) $recent['id']); ?>">
                                <strong><?= htmlspecialchars($recent['employee_name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                <span><?= htmlspecialchars($recent['position'] . ' · ' . $recent['office'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <span><?= htmlspecialchars(date('M Y', strtotime($recent['period_start'])) . ' – ' . date('M Y', strtotime($recent['period_end'])) . ' · ' . $recent['status'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </section>
                <?php endif; ?>
            </div>
        </div>
        <?php include(APPPATH . 'views/includes/footer.php'); ?>
    </div>
</div>

<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/select2/select2.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/toastr/toastr.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
<script>
window.IPCRF_CONFIG = <?= json_encode(array(
    'hasForm' => $hasForm,
    'bundle' => $bundle,
    'editScope' => $edit_scope,
    'templates' => $templates,
    'actor' => $actor,
    'isAdmin' => $is_admin,
    'isPmt' => $is_pmt,
    'urls' => array(
        'employeeSearch' => site_url('Ipcrf/employee_search'),
        'create' => site_url('Ipcrf/create'),
        'save' => $hasForm ? site_url('Ipcrf/save/' . (int) $form['id']) : '',
        'validate' => $hasForm ? site_url('Ipcrf/validate_form/' . (int) $form['id']) : '',
        'loadPreset' => $hasForm ? site_url('Ipcrf/load_preset/' . (int) $form['id']) : '',
        'workflow' => $hasForm ? site_url('Ipcrf/workflow/' . (int) $form['id']) : '',
        'uploadEvidence' => $hasForm ? site_url('Ipcrf/upload_evidence/' . (int) $form['id']) : '',
        'deleteEvidenceBase' => $hasForm ? site_url('Ipcrf/delete_evidence/' . (int) $form['id']) : ''
    )
), $jsonFlags); ?>;
</script>
<script src="<?= base_url(); ?>assets/js/ipcrf.js?v=<?= $ipcrfJsVersion; ?>"></script>
</body>
</html>
