<?php
$hasForm = !empty($bundle);
$form = $hasForm ? $bundle['form'] : array();
$status = $hasForm ? $form['status'] : 'No form selected';
$currentYear = (int) date('Y');
$jsonFlags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
$ipcrfCssVersion = @filemtime(FCPATH . 'assets/css/ipcrf.css') ?: time();
$ipcrfJsVersion = @filemtime(FCPATH . 'assets/js/ipcrf.js') ?: time();
$presetName = 'No preset loaded';
$latestReturn = NULL;
$isEmployeeOwner = $hasForm && (string) $form['employee_id'] === (string) $actor;
if ($hasForm && !empty($form['template_id'])) {
    foreach ($templates as $template) {
        if ((int) $template['id'] === (int) $form['template_id']) {
            $presetName = $template['name'];
            break;
        }
    }
}
if ($isEmployeeOwner && $status === Ipcrf_model::STATUS_RETURNED) {
    foreach ((array) $bundle['history'] as $historyEntry) {
        if ($historyEntry['to_status'] === Ipcrf_model::STATUS_RETURNED) {
            $latestReturn = $historyEntry;
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
<body class="ipcrf-page<?= $hasForm ? '' : ' ipcrf-landing-page'; ?>">
<div id="wrapper">
    <?php include(APPPATH . 'views/includes/top-bar.php'); ?>
    <?php include(APPPATH . 'views/includes/sidebar.php'); ?>

    <div class="content-page">
        <div class="content">
            <div class="container-fluid ipcrf-shell<?= $hasForm ? '' : ' ipcrf-landing-shell'; ?>">
                <div class="ipcrf-title-row<?= $hasForm ? '' : ' ipcrf-landing-hero'; ?>">
                    <div>
                        <span class="ipcrf-kicker">Performance Management</span>
                        <h1><?= $hasForm ? 'Individual Performance Commitment and Review' : 'My IPCRF Workspace'; ?></h1>
                        <p><?= $hasForm ? 'Build, rate, track and print the complete IPCRF from one screen.' : 'Create a performance record, assign its reviewers, and return to active IPCRFs from one organized workspace.'; ?></p>
                    </div>
                    <span class="ipcrf-status" id="formStatus"><?= htmlspecialchars($hasForm ? $status : 'Ready to Start', ENT_QUOTES, 'UTF-8'); ?></span>
                </div>

                <?php if ($hasForm): ?>
                <div class="ipcrf-toolbar">
                    <button type="button" class="btn btn-soft-primary" id="loadPresetBtn"<?= $edit_scope !== 'full' ? ' disabled' : ''; ?>><i class="mdi mdi-file-restore mr-1"></i>Load Preset</button>
                    <button type="button" class="btn btn-primary" id="saveDraftBtn"<?= $edit_scope === 'none' ? ' disabled' : ''; ?>><i class="mdi mdi-content-save-outline mr-1"></i><?= $edit_scope === 'rater' ? 'Save Review' : 'Save Draft'; ?></button>
                    <button type="button" class="btn btn-soft-secondary" data-toggle="modal" data-target="#editorGuideModal"><i class="mdi mdi-help-circle-outline mr-1"></i>Editing Guide</button>
                    <button type="button" class="btn btn-soft-secondary" id="openHistoryBtn" data-toggle="modal" data-target="#trackingHistoryModal"><i class="mdi mdi-history mr-1"></i>Tracking History</button>
                    <?php if (in_array($edit_scope, array('full', 'rater'), TRUE)): ?>
                    <button type="button" class="btn btn-soft-warning" id="missingItemsBtn" hidden><i class="mdi mdi-alert-outline mr-1"></i>Missing Items <span class="missing-items-count" id="missingItemsCount">0</span></button>
                    <?php endif; ?>
                    <a class="btn btn-soft-secondary" href="<?= site_url('Ipcrf/report/' . (int) $form['id']); ?>" target="_blank"><i class="mdi mdi-eye-outline mr-1"></i>Preview</a>
                    <span id="workflowButtons" class="d-inline-flex flex-wrap" style="gap:8px"></span>
                    <a class="btn btn-dark" href="<?= site_url('Ipcrf/report/' . (int) $form['id'] . '?autoprint=1'); ?>" target="_blank"><i class="mdi mdi-printer mr-1"></i>Print PDF</a>
                    <span class="toolbar-spacer"></span>
                    <span class="ipcrf-save-state is-saved" id="saveState" role="status" aria-live="polite"><i class="mdi mdi-check-circle-outline"></i><span>All changes saved</span></span>
                </div>

                <?php if ($isEmployeeOwner && $status === Ipcrf_model::STATUS_RETURNED): ?>
                <section class="return-remarks-notice" role="alert" aria-labelledby="returnRemarksTitle">
                    <span class="return-remarks-icon"><i class="mdi mdi-file-undo-outline"></i></span>
                    <div class="return-remarks-content">
                        <span class="return-remarks-kicker">Returned by the assigned rater</span>
                        <h2 id="returnRemarksTitle">Revision is required before resubmission</h2>
                        <blockquote><?= nl2br(htmlspecialchars($latestReturn && trim((string) $latestReturn['remarks']) !== '' ? $latestReturn['remarks'] : 'Please review the IPCRF and coordinate with the assigned rater for the required revisions.', ENT_QUOTES, 'UTF-8')); ?></blockquote>
                        <small><strong><?= htmlspecialchars($latestReturn ? ($latestReturn['acted_by_name'] ?: $latestReturn['acted_by']) : ($form['rater_name'] ?: 'Assigned rater'), ENT_QUOTES, 'UTF-8'); ?></strong><?= $latestReturn ? ' · Returned ' . htmlspecialchars(date('M d, Y g:i A', strtotime($latestReturn['acted_at'])), ENT_QUOTES, 'UTF-8') : ''; ?></small>
                        <p>Your IPCRF is unlocked. Edit the affected information below, save your changes, and submit it to the rater again when the remarks have been fulfilled.</p>
                    </div>
                    <a class="btn btn-danger return-remarks-action" href="#kraSection"><i class="mdi mdi-pencil-outline mr-1"></i>Review and Edit Form</a>
                </section>
                <?php endif; ?>

                <div class="ipcrf-workspace">
                    <main class="ipcrf-main">
                        <section class="ipcrf-panel ipcrf-section ipcrf-document" id="employeeSection">
                            <div class="ipcrf-section-head">
                                <div><h2>Employee Information</h2><p>Synced from the existing HRIS employee profile.</p></div>
                                <span class="badge badge-light"><?= htmlspecialchars($presetName, ENT_QUOTES, 'UTF-8'); ?> · Employee copy</span>
                            </div>
                            <div class="ipcrf-section-body">
                                <?php if ($edit_scope !== 'full'): ?>
                                    <div class="scope-notice"><i class="mdi mdi-lock-outline mr-1"></i><?= $edit_scope === 'rater' ? 'Rater review mode: review the ratings and Development Plan. Return with remarks when revision is needed, or approve with an incomplete-items warning.' : ($edit_scope === 'pmt' ? 'PMT validation mode: only competency ratings can be changed.' : 'This record is read-only at its current workflow stage.'); ?></div>
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
                                        <div class="employee-pdf-row employee-rater-row"><label>Approving Authority</label><select id="editorApprovingAuthority" class="form-control ipcrf-input"<?= $edit_scope !== 'full' ? ' disabled' : ''; ?>><option value="<?= htmlspecialchars($form['approving_authority_id'], ENT_QUOTES, 'UTF-8'); ?>" selected><?= htmlspecialchars($form['approving_authority_name'] ?: 'Select approving authority', ENT_QUOTES, 'UTF-8'); ?></option></select></div>
                                        <div class="employee-pdf-row"><label>Authority Position</label><strong id="approvingAuthorityPositionDisplay"><?= htmlspecialchars($form['approving_authority_position'], ENT_QUOTES, 'UTF-8'); ?></strong></div>
                                        <div class="employee-pdf-row period-row"><label>Review Period</label><span><input type="date" class="form-control ipcrf-input js-header-field" aria-label="Period start" data-field="period_start" value="<?= htmlspecialchars($form['period_start'], ENT_QUOTES, 'UTF-8'); ?>"<?= $edit_scope !== 'full' ? ' disabled' : ''; ?>><em>to</em><input type="date" class="form-control ipcrf-input js-header-field" aria-label="Period end" data-field="period_end" value="<?= htmlspecialchars($form['period_end'], ENT_QUOTES, 'UTF-8'); ?>"<?= $edit_scope !== 'full' ? ' disabled' : ''; ?>></span></div>
                                        <div class="employee-pdf-row"><label>Current Status</label><strong><?= htmlspecialchars($form['status'], ENT_QUOTES, 'UTF-8'); ?></strong></div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="ipcrf-panel ipcrf-section ipcrf-document" id="kraSection">
                            <div class="ipcrf-section-head">
                                <div><h2>KRAs, Objectives and Performance Standards</h2><p>Each objective is shown in one row with standards, results, Q–E–T ratings and score.</p></div>
                                <button type="button" class="btn btn-sm btn-soft-primary" id="addKraBtn"<?= $edit_scope !== 'full' ? ' disabled' : ''; ?>><i class="mdi mdi-plus mr-1"></i>Add New KRA</button>
                            </div>
                            <div class="ipcrf-section-body">
                                <div class="editor-quick-guide">
                                    <div class="guide-step"><span>1</span><div><strong>Create or edit a KRA</strong><small>Use Add New KRA, or click an existing KRA title to type.</small></div></div>
                                    <div class="guide-step"><span>2</span><div><strong>Add objectives</strong><small>Enter the objective, timeline and weight in a short dialog.</small></div></div>
                                    <div class="guide-step"><span>3</span><div><strong>Complete standards and results</strong><small>Click a Q, E or T standards cell; enter results directly in the row.</small></div></div>
                                    <div class="guide-step"><span>4</span><div><strong>Review and submit</strong><small>Incomplete items trigger a warning; return to edit or submit anyway.</small></div></div>
                                </div>
                                <div class="editing-legend">
                                    <span><b class="legend-edit">Click text</b> edits existing details like a document</span>
                                    <span><b class="legend-add">Add</b> creates a new item</span>
                                    <span><b class="legend-open">Q / E / T</b> opens all five rating levels for review</span>
                                    <span><b class="legend-auto">Autosave</b> saves changes after a short pause</span>
                                    <span><b class="legend-warning">Submit warning</b> lists incomplete items without blocking submission</span>
                                </div>
                                <div id="kraContainer"></div>
                            </div>
                        </section>

                        <section class="ipcrf-panel ipcrf-section ipcrf-document" id="competencySection">
                            <div class="ipcrf-section-head">
                                <div><h2>Competency Management</h2><p>Preset competencies appear in document-style groups with one shared Rating column.</p></div>
                                <button type="button" class="btn btn-sm btn-soft-primary" id="addCompetencyBtn"<?= $edit_scope !== 'full' ? ' disabled' : ''; ?>><i class="mdi mdi-plus mr-1"></i>Add Competency</button>
                            </div>
                            <div class="ipcrf-section-body">
                                <div class="competency-quick-guide"><span><strong>Click competency text or indicators to edit.</strong> Use Add Competency only when the preset needs another item.</span><span><b>One rating only</b> · no separate Employee, Rater or Final columns.</span></div>
                                <div class="rating-legend"><strong>Rating scale:</strong> 5 – Role Model &nbsp;·&nbsp; 4 – Consistently Demonstrates &nbsp;·&nbsp; 3 – Most of the Time Demonstrates &nbsp;·&nbsp; 2 – Sometimes Demonstrates &nbsp;·&nbsp; 1 – Rarely Demonstrates</div>
                                <div id="competencyContainer"></div>
                            </div>
                        </section>

                        <section class="ipcrf-panel ipcrf-section ipcrf-document" id="developmentSection">
                            <div class="ipcrf-section-head">
                                <div><h2>Development Plan</h2><p>Connect strengths and improvement needs with actionable learning interventions.</p></div>
                                <button type="button" class="btn btn-sm btn-soft-primary" id="addPlanBtn"<?= !in_array($edit_scope, array('full', 'rater'), TRUE) ? ' disabled' : ''; ?>><i class="mdi mdi-plus mr-1"></i>Add Development Entry</button>
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
                                    <div><label class="ipcrf-label" for="objectiveEditorWeight">Weight Percentage</label><div class="input-group"><input type="number" min="0" max="100" step="0.01" class="form-control ipcrf-input" id="objectiveEditorWeight" value="0" required><div class="input-group-append"><span class="input-group-text">%</span></div></div><small class="form-text text-muted">The recommended total is 100%. Any difference appears in the submission warning.</small></div>
                                </div>
                                <div class="dialog-next-step"><i class="mdi mdi-information-outline"></i><span>After saving, click directly into the row to revise its details and actual result. Select Quality, Efficiency or Timeliness to review its five rating levels.</span></div>
                            </div>
                            <div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary"><i class="mdi mdi-check mr-1"></i>Save Objective</button></div>
                        </form>
                    </div></div>
                </div>

                <div class="modal fade" id="standardsEditorModal" tabindex="-1" role="dialog" aria-labelledby="standardsEditorTitle" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document"><div class="modal-content ipcrf-editor-modal">
                        <form id="standardsEditorForm">
                            <div class="modal-header"><div><span class="modal-kicker" id="standardsObjectiveLabel">Performance Standards</span><h5 class="modal-title" id="standardsEditorTitle">Quality, Efficiency and Timeliness Standards</h5></div><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                            <div class="modal-body">
                                <input type="hidden" id="standardsEditorKra" value="-1"><input type="hidden" id="standardsEditorObjective" value="-1">
                                <div class="standards-modal-note"><i class="mdi mdi-information-outline"></i><span>Review or edit all five rating levels. The table column you clicked is highlighted.</span></div>
                                <div class="standards-modal-table">
                                    <div class="standards-modal-head">Rating</div><div class="standards-modal-head" data-standard-column="quality">Quality</div><div class="standards-modal-head" data-standard-column="efficiency">Efficiency</div><div class="standards-modal-head" data-standard-column="timeliness">Timeliness</div>
                                    <?php foreach (array(5, 4, 3, 2, 1) as $standardLevel): ?>
                                    <div class="standards-modal-level"><strong><?= $standardLevel; ?></strong><span><?= $standardLevel === 5 ? 'Outstanding' : ($standardLevel === 4 ? 'Very Satisfactory' : ($standardLevel === 3 ? 'Satisfactory' : ($standardLevel === 2 ? 'Unsatisfactory' : 'Poor'))); ?></span></div>
                                    <div data-standard-column="quality"><textarea class="js-standard-modal-input" data-dimension="quality" data-level="<?= $standardLevel; ?>" rows="3" aria-label="Quality rating <?= $standardLevel; ?>"></textarea></div>
                                    <div data-standard-column="efficiency"><textarea class="js-standard-modal-input" data-dimension="efficiency" data-level="<?= $standardLevel; ?>" rows="3" aria-label="Efficiency rating <?= $standardLevel; ?>"></textarea></div>
                                    <div data-standard-column="timeliness"><textarea class="js-standard-modal-input" data-dimension="timeliness" data-level="<?= $standardLevel; ?>" rows="3" aria-label="Timeliness rating <?= $standardLevel; ?>"></textarea></div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal" id="standardsCancelBtn">Cancel</button><button type="submit" class="btn btn-primary" id="standardsSaveBtn"><i class="mdi mdi-check mr-1"></i>Save Standards</button></div>
                        </form>
                    </div></div>
                </div>

                <div class="modal fade" id="competencyEditorModal" tabindex="-1" role="dialog" aria-labelledby="competencyEditorTitle" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document"><div class="modal-content ipcrf-editor-modal">
                        <form id="competencyEditorForm">
                            <div class="modal-header"><div><span class="modal-kicker">Competency Management</span><h5 class="modal-title" id="competencyEditorTitle">Add Competency</h5></div><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                            <div class="modal-body">
                                <div class="competency-dialog-grid">
                                    <div><label class="ipcrf-label" for="competencyEditorCategory">Competency Group</label><select class="form-control ipcrf-input" id="competencyEditorCategory"><option>Core Behavioral Competency</option><option>Core Skill</option><option>Leadership Competency</option></select></div>
                                    <div><label class="ipcrf-label" for="competencyEditorName">Competency Name</label><input type="text" class="form-control ipcrf-input" id="competencyEditorName" placeholder="Example: Communication" required></div>
                                    <div class="competency-dialog-indicators"><label class="ipcrf-label" for="competencyEditorIndicators">Behavioral Indicators</label><textarea class="form-control ipcrf-input" id="competencyEditorIndicators" rows="7" placeholder="Enter one indicator per line" required></textarea><small class="form-text text-muted">Use a separate line for every observable behavior or indicator.</small></div>
                                </div>
                            </div>
                            <div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary"><i class="mdi mdi-check mr-1"></i>Add Competency</button></div>
                        </form>
                    </div></div>
                </div>

                <div class="modal fade" id="developmentEditorModal" tabindex="-1" role="dialog" aria-labelledby="developmentEditorTitle" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document"><div class="modal-content ipcrf-editor-modal">
                        <form id="developmentEditorForm">
                            <div class="modal-header"><div><span class="modal-kicker">Development Plan</span><h5 class="modal-title" id="developmentEditorTitle">Add Development Entry</h5></div><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                            <div class="modal-body">
                                <div class="development-dialog-grid">
                                    <div><label class="ipcrf-label" for="developmentStrengths">Strengths</label><textarea class="form-control ipcrf-input" id="developmentStrengths" rows="3" placeholder="Strengths demonstrated by the employee"></textarea></div>
                                    <div><label class="ipcrf-label" for="developmentNeeds">Improvement Needs</label><textarea class="form-control ipcrf-input" id="developmentNeeds" rows="3" placeholder="Performance or competency areas to improve"></textarea></div>
                                    <div><label class="ipcrf-label" for="developmentObjectives">Learning Objectives</label><textarea class="form-control ipcrf-input" id="developmentObjectives" rows="3" placeholder="What the employee should learn or achieve"></textarea></div>
                                    <div><label class="ipcrf-label" for="developmentInterventions">Developmental Interventions</label><textarea class="form-control ipcrf-input" id="developmentInterventions" rows="3" placeholder="Training, coaching, mentoring or other action"></textarea></div>
                                    <div><label class="ipcrf-label" for="developmentTimeline">Target Timeline</label><input type="text" class="form-control ipcrf-input" id="developmentTimeline" placeholder="Example: August–October 2026"></div>
                                    <div><label class="ipcrf-label" for="developmentResponsible">Responsible Person</label><input type="text" class="form-control ipcrf-input" id="developmentResponsible" placeholder="Employee, rater, supervisor or office"></div>
                                    <div class="development-dialog-wide"><label class="ipcrf-label" for="developmentRemarks">Status / Remarks</label><textarea class="form-control ipcrf-input" id="developmentRemarks" rows="3" placeholder="Planned, ongoing, completed, or supporting remarks"></textarea></div>
                                </div>
                                <div class="dialog-next-step"><i class="mdi mdi-information-outline"></i><span>After adding the entry, the employee or assigned rater can continue editing it directly in the Development Plan table.</span></div>
                            </div>
                            <div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary"><i class="mdi mdi-check mr-1"></i>Add to Development Plan</button></div>
                        </form>
                    </div></div>
                </div>

                <div class="modal fade" id="editorGuideModal" tabindex="-1" role="dialog" aria-labelledby="editorGuideTitle" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document"><div class="modal-content ipcrf-editor-modal">
                        <div class="modal-header"><div><span class="modal-kicker">Help and Legend</span><h5 class="modal-title" id="editorGuideTitle">How to Complete the IPCRF</h5></div><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                        <div class="modal-body guide-modal-body">
                            <ol><li><strong>Employee Information:</strong> confirm the compact HRIS information, assigned rater and review period.</li><li><strong>KRAs:</strong> use Add New KRA when needed, then click an existing KRA title to revise it.</li><li><strong>Objectives and Results:</strong> add an objective inside the correct KRA. Existing code, objective, timeline, weight and actual result edit directly like document text.</li><li><strong>Standards:</strong> click Quality, Efficiency or Timeliness in an objective row to view or edit all five levels.</li><li><strong>Ratings:</strong> the owner proposes Q, E and T ratings in Draft. The assigned rater reviews them after submission.</li><li><strong>Competencies:</strong> click names or indicators to edit, choose the single Rating, and add another competency only when needed.</li><li><strong>Development Plan:</strong> Add Development Entry opens a guided dialog. Both the employee and assigned rater can add or edit plan entries.</li><li><strong>Missing Items:</strong> this button appears only while incomplete information exists and opens the current warning list.</li><li><strong>Return Paper:</strong> the assigned rater must explain the required corrections. A returned employee sees those remarks above the unlocked, editable form.</li><li><strong>Approval:</strong> incomplete items produce a warning but still permit Approve Anyway.</li></ol>
                            <div class="guide-note"><strong>Editing states:</strong> Draft and Returned records are editable by the employee. During rater review, ratings and the Development Plan are editable by the assigned rater. Validated and Locked records are read-only.</div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">Got it</button></div>
                    </div></div>
                </div>

                <div class="modal fade" id="trackingHistoryModal" tabindex="-1" role="dialog" aria-labelledby="trackingHistoryTitle" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document"><div class="modal-content ipcrf-editor-modal">
                        <div class="modal-header"><div><span class="modal-kicker">Workflow Tracking</span><h5 class="modal-title" id="trackingHistoryTitle">IPCRF Tracking History</h5></div><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                        <div class="modal-body tracking-modal-body">
                            <div class="tracking-current-state"><span><i class="mdi mdi-map-marker-path mr-1"></i>Current Status</span><strong id="historyCurrentStatus"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?></strong><small id="historyEntryCount">0 recorded transitions</small></div>
                            <div class="history-list tracking-history-list" id="historyList"></div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">Close History</button></div>
                    </div></div>
                </div>
                <?php else: ?>
                <section class="ipcrf-panel ipcrf-personal-home">
                    <div class="personal-home-head">
                        <span class="personal-home-icon"><i class="mdi mdi-account-card-details-outline"></i></span>
                        <div><span class="ipcrf-kicker">Personal Performance Workspace</span><h2>Set up your performance review</h2><p>Your HRIS profile is already linked. Assign the rater and approving authority, then select the performance period.</p></div>
                    </div>

                    <?php if (!empty($current_employee)): ?>
                    <!-- <div class="personal-employee-card">
                        <div class="personal-employee-avatar"><i class="mdi mdi-account-outline"></i></div>
                        <div class="personal-employee-primary"><span>Signed-in Employee</span><strong><?= htmlspecialchars($current_employee['name'], ENT_QUOTES, 'UTF-8'); ?></strong><small><?= htmlspecialchars($current_employee['id'], ENT_QUOTES, 'UTF-8'); ?></small></div>
                        <div class="personal-employee-detail"><span>Position</span><strong><?= htmlspecialchars($current_employee['position'] ?: 'Not recorded', ENT_QUOTES, 'UTF-8'); ?></strong></div>
                        <div class="personal-employee-detail"><span>Office / Section</span><strong><?= htmlspecialchars($current_employee['office'] ?: 'Not recorded', ENT_QUOTES, 'UTF-8'); ?></strong></div>
                    </div> -->

                    <div class="personal-start-panel">
                        <!-- <div class="personal-start-copy"><h3>Review assignments</h3><p>Choose both reviewing officials. An existing form for the same period opens automatically instead of creating a duplicate.</p></div> -->
                        <div class="personal-update-notice" id="personalUpdateNotice" hidden>
                            <div><i class="mdi mdi-pencil-outline"></i><span><strong>Updating reviewer assignments</strong><small id="personalUpdateRecordLabel"></small></span></div>
                            <button type="button" class="btn" id="cancelPersonalUpdate"><i class="mdi mdi-close mr-1"></i>Cancel update</button>
                        </div>
                        <form id="startIpcrfForm" class="ipcrf-start-form personal-start-form">
                            <input type="hidden" id="startFormId" name="form_id" value="">
                            <div class="assignment-field"><span class="assignment-field-icon"><i class="mdi mdi-account-check-outline"></i></span><label class="ipcrf-label">Assigned Rater</label><select id="startRater" name="rater_id" required></select><small>Search by name or employee ID.</small></div>
                            <div class="assignment-field"><span class="assignment-field-icon is-authority"><i class="mdi mdi-shield-account-outline"></i></span><label class="ipcrf-label">Approving Authority</label><select id="startApprovingAuthority" name="approving_authority_id" required></select><small>Select the official who will approve the IPCRF.</small></div>
                            <div class="period-field"><label class="ipcrf-label">Period Start</label><input type="date" class="form-control ipcrf-input" name="period_start" value="<?= $currentYear; ?>-01-01" data-default-value="<?= $currentYear; ?>-01-01" required></div>
                            <div class="period-field"><label class="ipcrf-label">Period End</label><input type="date" class="form-control ipcrf-input" name="period_end" value="<?= $currentYear; ?>-12-31" data-default-value="<?= $currentYear; ?>-12-31" required></div>
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-arrow-right mr-1"></i>Start / Open My IPCRF</button>
                        </form>
                    </div>
                    <?php else: ?>
                    <div class="personal-profile-warning"><i class="mdi mdi-account-alert-outline"></i><div><strong>HRIS employee profile not found</strong><span>Your signed-in username is not linked to an HRIS employee ID. Ask the administrator to update your account before creating an IPCRF.</span></div></div>
                    <?php endif; ?>

                    <div class="ipcrf-recent personal-form-list">
                        <div class="personal-list-head"><div><h3>My IPCRF Records</h3><p>Update or delete your records before they are approved or validated.</p></div><span><?= count((array) $recent_forms); ?> record<?= count((array) $recent_forms) === 1 ? '' : 's'; ?></span></div>
                        <?php if (!empty($recent_forms)): ?>
                        <div class="ipcrf-recent-grid">
                            <?php foreach ($recent_forms as $recent):
                                $canManageRecent = in_array($recent['status'], array(
                                    Ipcrf_model::STATUS_DRAFT,
                                    Ipcrf_model::STATUS_SUBMITTED_RATER,
                                    Ipcrf_model::STATUS_RETURNED
                                ), TRUE);
                                $recentUrl = site_url('Ipcrf/index/' . (int) $recent['id']);
                                $recentPeriod = date('M d, Y', strtotime($recent['period_start'])) . ' – ' . date('M d, Y', strtotime($recent['period_end']));
                            ?>
                            <article class="recent-card personal-form-card<?= $canManageRecent ? ' is-manageable' : ''; ?>" data-record-id="<?= (int) $recent['id']; ?>">
                                <a class="personal-form-card-main" href="<?= $recentUrl; ?>">
                                    <span class="personal-form-status"><?= htmlspecialchars($recent['status'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    <strong><?= htmlspecialchars($recentPeriod, ENT_QUOTES, 'UTF-8'); ?></strong>
                                    <span><i class="mdi mdi-account-check-outline mr-1"></i>Rater: <?= htmlspecialchars($recent['rater_name'] ?: 'Not assigned', ENT_QUOTES, 'UTF-8'); ?></span>
                                    <span><i class="mdi mdi-shield-account-outline mr-1"></i>Approving Authority: <?= htmlspecialchars($recent['approving_authority_name'] ?: 'Not assigned', ENT_QUOTES, 'UTF-8'); ?></span>
                                    <small>Last updated <?= htmlspecialchars(date('M d, Y g:i A', strtotime($recent['updated_at'])), ENT_QUOTES, 'UTF-8'); ?></small>
                                </a>
                                <div class="personal-form-actions">
                                    <?php if ($canManageRecent): ?>
                                    <button type="button" class="btn personal-form-update js-update-ipcrf" data-id="<?= (int) $recent['id']; ?>" data-period="<?= htmlspecialchars($recentPeriod, ENT_QUOTES, 'UTF-8'); ?>" data-period-start="<?= htmlspecialchars($recent['period_start'], ENT_QUOTES, 'UTF-8'); ?>" data-period-end="<?= htmlspecialchars($recent['period_end'], ENT_QUOTES, 'UTF-8'); ?>" data-rater-id="<?= htmlspecialchars($recent['rater_id'], ENT_QUOTES, 'UTF-8'); ?>" data-rater-name="<?= htmlspecialchars($recent['rater_name'], ENT_QUOTES, 'UTF-8'); ?>" data-authority-id="<?= htmlspecialchars($recent['approving_authority_id'], ENT_QUOTES, 'UTF-8'); ?>" data-authority-name="<?= htmlspecialchars($recent['approving_authority_name'], ENT_QUOTES, 'UTF-8'); ?>"><i class="mdi mdi-account-edit-outline mr-1"></i>Update</button>
                                    <button type="button" class="btn personal-form-delete js-delete-ipcrf" data-id="<?= (int) $recent['id']; ?>" data-period="<?= htmlspecialchars($recentPeriod, ENT_QUOTES, 'UTF-8'); ?>"><i class="mdi mdi-delete-outline mr-1"></i>Delete</button>
                                    <?php else: ?>
                                    <a class="personal-form-open" href="<?= $recentUrl; ?>">Open IPCRF <i class="mdi mdi-arrow-right"></i></a>
                                    <?php endif; ?>
                                </div>
                            </article>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <div class="personal-record-empty"><i class="mdi mdi-file-document-outline"></i><strong>No IPCRF records yet</strong><span>Assign the rater and approving authority, then choose the performance period to begin.</span></div>
                        <?php endif; ?>
                    </div>
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
    'reviewWarnings' => $review_warnings,
    'urls' => array(
        'employeeSearch' => site_url('Ipcrf/employee_search'),
        'create' => site_url('Ipcrf/create'),
        'deleteFormBase' => site_url('Ipcrf/delete_form'),
        'save' => $hasForm ? site_url('Ipcrf/save/' . (int) $form['id']) : '',
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
