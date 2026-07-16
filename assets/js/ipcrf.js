(function ($) {
    'use strict';

    var config = window.IPCRF_CONFIG || {};
    var state = config.bundle || null;
    var scope = config.editScope || 'none';
    var saveTimer = null;
    var saving = false;
    var pendingSave = false;
    var dirty = false;
    var activeSave = null;

    function escapeHtml(value) {
        return $('<div>').text(value == null ? '' : String(value)).html();
    }

    function escapeAttr(value) {
        return escapeHtml(value).replace(/`/g, '&#96;');
    }

    function showError(xhr, fallback) {
        var response = xhr && xhr.responseJSON ? xhr.responseJSON : {};
        var message = response.message || fallback || 'The request could not be completed.';
        if (response.errors && response.errors.length) {
            message += '<br><small>' + response.errors.map(escapeHtml).join('<br>') + '</small>';
        }
        Swal.fire({ icon: 'error', title: 'Action needed', html: message, confirmButtonColor: '#3157c8' });
    }

    function employeeSelectOptions(placeholder) {
        return {
            width: '100%',
            placeholder: placeholder,
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: config.urls.employeeSearch,
                dataType: 'json',
                delay: 250,
                data: function (params) { return { q: params.term || '' }; },
                processResults: function (data) { return data; },
                cache: true
            }
        };
    }

    function initLanding() {
        $('#startEmployee').select2(employeeSelectOptions('Search employee name or ID'));
        $('#startRater').select2(employeeSelectOptions('Search assigned rater (optional)'));
        $('#startIpcrfForm').on('submit', function (event) {
            event.preventDefault();
            var $button = $(this).find('button[type="submit"]');
            $button.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin mr-1"></i>Opening');
            $.ajax({ url: config.urls.create, method: 'POST', data: $(this).serialize(), dataType: 'json' })
                .done(function (response) { window.location.href = response.url; })
                .fail(function (xhr) { showError(xhr, 'The IPCRF could not be opened.'); })
                .always(function () { $button.prop('disabled', false).html('<i class="mdi mdi-arrow-right mr-1"></i>Start / Open'); });
        });
    }

    function emptyStandards() {
        return { '5': '', '4': '', '3': '', '2': '', '1': '' };
    }

    function newObjective() {
        return {
            id: 0, code: '', objective: '', timeline: '', weight: 0,
            quality: emptyStandards(), efficiency: emptyStandards(), timeliness: emptyStandards(),
            accomplishment: '', quality_rating: 0, efficiency_rating: 0, timeliness_rating: 0, evidence: []
        };
    }

    function newCompetency() {
        return { id: 0, category: 'Core Behavioral Competency', name: '', indicators: [''], employee_rating: 0, rater_rating: 0, final_rating: 0 };
    }

    function newPlan() {
        return { id: 0, strengths: '', improvement_needs: '', learning_objectives: '', interventions: '', target_timeline: '', responsible_person: '', status_remarks: '' };
    }

    function isFull() { return scope === 'full'; }
    function disabledUnlessFull() { return isFull() ? '' : ' disabled'; }

    function ratingOptions(value, enabled) {
        var labels = { 0: '—', 1: '1', 2: '2', 3: '3', 4: '4', 5: '5' };
        var html = '<select class="form-control ipcrf-input"' + (enabled ? '' : ' disabled') + '>';
        Object.keys(labels).forEach(function (number) {
            html += '<option value="' + number + '"' + (Number(value) === Number(number) ? ' selected' : '') + '>' + labels[number] + '</option>';
        });
        return html + '</select>';
    }

    function objectiveAverage(objective) {
        var q = Number(objective.quality_rating || 0);
        var e = Number(objective.efficiency_rating || 0);
        var t = Number(objective.timeliness_rating || 0);
        return q > 0 && e > 0 && t > 0 ? (q + e + t) / 3 : 0;
    }

    function renderStandards(kraIndex, objectiveIndex, objective) {
        var html = '<details class="standards-box"><summary>Quality, Efficiency and Timeliness Standards</summary><div class="standards-grid">';
        html += '<div class="standard-head">Rate</div><div class="standard-head">Quality</div><div class="standard-head">Efficiency</div><div class="standard-head">Timeliness</div>';
        ['5', '4', '3', '2', '1'].forEach(function (level) {
            html += '<div class="scale-level">' + level + '</div>';
            ['quality', 'efficiency', 'timeliness'].forEach(function (dimension) {
                var value = objective[dimension] && objective[dimension][level] ? objective[dimension][level] : '';
                html += '<div><textarea class="js-standard" data-kra="' + kraIndex + '" data-objective="' + objectiveIndex + '" data-dimension="' + dimension + '" data-level="' + level + '"' + disabledUnlessFull() + '>' + escapeHtml(value) + '</textarea></div>';
            });
        });
        return html + '</div></details>';
    }

    function renderEvidence(kraIndex, objectiveIndex, objective) {
        var html = '<div class="evidence-row"><span class="ipcrf-label mb-0 mr-1">Evidence</span>';
        (objective.evidence || []).forEach(function (evidence) {
            html += '<span class="evidence-pill"><i class="mdi mdi-paperclip"></i><a href="' + escapeAttr(evidence.url) + '" target="_blank" title="' + escapeAttr(evidence.original_name) + '">' + escapeHtml(evidence.original_name) + '</a>';
            if (isFull()) {
                html += '<button class="btn-icon danger js-delete-evidence" type="button" data-evidence="' + evidence.id + '" data-kra="' + kraIndex + '" data-objective="' + objectiveIndex + '" title="Remove"><i class="mdi mdi-close"></i></button>';
            }
            html += '</span>';
        });
        if (isFull()) {
            html += '<label class="btn btn-xs btn-soft-primary mb-0"><i class="mdi mdi-upload mr-1"></i>Upload<input type="file" class="d-none js-evidence-input" data-kra="' + kraIndex + '" data-objective="' + objectiveIndex + '" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx"></label>';
        }
        return html + '</div>';
    }

    function renderObjective(kraIndex, objectiveIndex, objective) {
        var average = objectiveAverage(objective);
        var weighted = average * Number(objective.weight || 0) / 100;
        var html = '<details class="objective-card">';
        html += '<summary><span class="objective-code">' + escapeHtml(objective.code || (kraIndex + 1) + '.' + (objectiveIndex + 1)) + '</span><span class="objective-summary-text">' + escapeHtml(objective.objective || 'New objective') + '</span><span class="objective-timeline">' + escapeHtml(objective.timeline || 'No timeline') + '</span><span class="weight-chip">' + Number(objective.weight || 0).toFixed(2) + '%</span>';
        if (isFull()) {
            html += '<span class="icon-actions action-buttons"><button type="button" class="action-text-btn js-action" data-action="edit-objective" data-kra="' + kraIndex + '" data-objective="' + objectiveIndex + '">Edit Details</button><button type="button" class="action-text-btn js-action" data-action="duplicate-objective" data-kra="' + kraIndex + '" data-objective="' + objectiveIndex + '">Copy</button><button type="button" class="action-text-btn move js-action" data-action="objective-up" data-kra="' + kraIndex + '" data-objective="' + objectiveIndex + '" title="Move up">↑</button><button type="button" class="action-text-btn move js-action" data-action="objective-down" data-kra="' + kraIndex + '" data-objective="' + objectiveIndex + '" title="Move down">↓</button><button type="button" class="action-text-btn danger js-action" data-action="delete-objective" data-kra="' + kraIndex + '" data-objective="' + objectiveIndex + '">Delete</button></span>';
        } else {
            html += '<span class="icon-actions action-buttons"><span class="ipcrf-muted">Open row for details</span></span>';
        }
        html += '</summary><div class="objective-content"><div class="objective-detail-heading"><div><strong>Performance Standards and Results</strong><span>Complete the indicators, actual results, evidence and ratings below.</span></div>' + (isFull() ? '<button type="button" class="action-text-btn js-action" data-action="edit-objective" data-kra="' + kraIndex + '" data-objective="' + objectiveIndex + '">Edit Objective Details</button>' : '') + '</div>' + renderStandards(kraIndex, objectiveIndex, objective);
        html += '<div class="result-grid"><div><label class="ipcrf-label">Actual Results / Accomplishments</label><textarea rows="3" class="form-control ipcrf-input js-objective-field" data-kra="' + kraIndex + '" data-objective="' + objectiveIndex + '" data-field="accomplishment"' + disabledUnlessFull() + '>' + escapeHtml(objective.accomplishment) + '</textarea>' + renderEvidence(kraIndex, objectiveIndex, objective) + '</div>';
        html += '<div><label class="ipcrf-label">Rater Q–E–T Rating</label><div class="rating-row">';
        ['quality_rating', 'efficiency_rating', 'timeliness_rating'].forEach(function (field, index) {
            html += '<div class="rating-cell"><span>' + ['QUALITY', 'EFFICIENCY', 'TIMELINESS'][index] + '</span><span class="js-objective-rating-wrap" data-kra="' + kraIndex + '" data-objective="' + objectiveIndex + '" data-field="' + field + '">' + ratingOptions(objective[field], scope === 'rater') + '</span></div>';
        });
        html += '</div><div class="objective-score">Average ' + average.toFixed(2) + ' · Weighted ' + weighted.toFixed(3) + '</div></div></div></div></details>';
        return html;
    }

    function renderKras() {
        var html = '';
        (state.kras || []).forEach(function (kra, kraIndex) {
            var weight = (kra.objectives || []).reduce(function (sum, objective) { return sum + Number(objective.weight || 0); }, 0);
            html += '<details class="kra-card" id="kra-' + kraIndex + '"' + (kraIndex === 0 ? ' open' : '') + '><summary>';
            html += '<span class="kra-pdf-label">Key Result Area</span><strong class="kra-title-display">' + escapeHtml(kra.title || 'Untitled KRA') + '</strong><span class="kra-meta">' + (kra.objectives || []).length + ' objective(s) · ' + weight.toFixed(2) + '%</span>';
            if (isFull()) {
                html += '<span class="icon-actions action-buttons"><button type="button" class="action-text-btn js-action" data-action="edit-kra" data-kra="' + kraIndex + '">Edit KRA</button><button type="button" class="action-text-btn add js-action" data-action="add-objective" data-kra="' + kraIndex + '">+ Add Objective</button><button type="button" class="action-text-btn js-action" data-action="duplicate-kra" data-kra="' + kraIndex + '">Copy KRA</button><button type="button" class="action-text-btn move js-action" data-action="kra-up" data-kra="' + kraIndex + '" title="Move up">↑</button><button type="button" class="action-text-btn move js-action" data-action="kra-down" data-kra="' + kraIndex + '" title="Move down">↓</button><button type="button" class="action-text-btn danger js-action" data-action="delete-kra" data-kra="' + kraIndex + '">Delete</button></span>';
            }
            html += '</summary><div class="kra-content"><div class="kra-content-guide"><span><strong>Objectives in this KRA.</strong> Click a row to open its performance standards and results.</span>' + (isFull() ? '<button type="button" class="action-text-btn add js-action" data-action="add-objective" data-kra="' + kraIndex + '">+ Add Objective to this KRA</button>' : '') + '</div><div class="pdf-objective-header"><span>Code</span><span>Objectives</span><span>Timeline</span><span>Weight</span><span>Actions</span></div>';
            (kra.objectives || []).forEach(function (objective, objectiveIndex) { html += renderObjective(kraIndex, objectiveIndex, objective); });
            if (!(kra.objectives || []).length) html += '<div class="kra-empty-objectives">No objectives yet. Use <strong>+ Add Objective to this KRA</strong> above.</div>';
            if (isFull()) html += '<button class="add-dashed js-action" data-action="add-objective" data-kra="' + kraIndex + '" type="button"><i class="mdi mdi-plus mr-1"></i>Add Another Objective to ' + escapeHtml(kra.title || 'this KRA') + '</button>';
            html += '</div></details>';
        });
        if (!html) html = '<div class="kra-empty-objectives">No KRAs yet. Click <strong>Add New KRA</strong> above to create the first result area, or use <strong>Load Preset</strong>.</div>';
        $('#kraContainer').html(html);
    }

    function renderCompetencies() {
        var html = '';
        (state.competencies || []).forEach(function (competency, index) {
            html += '<div class="competency-card"><div class="competency-head">';
            html += '<select class="form-control ipcrf-input js-competency-field" data-competency="' + index + '" data-field="category"' + disabledUnlessFull() + '><option' + (competency.category === 'Core Behavioral Competency' ? ' selected' : '') + '>Core Behavioral Competency</option><option' + (competency.category === 'Core Skill' ? ' selected' : '') + '>Core Skill</option><option' + (competency.category === 'Leadership Competency' ? ' selected' : '') + '>Leadership Competency</option></select>';
            html += '<input class="form-control ipcrf-input js-competency-field" data-competency="' + index + '" data-field="name" value="' + escapeAttr(competency.name) + '" placeholder="Competency name"' + disabledUnlessFull() + '>';
            if (isFull()) html += '<button class="btn-icon danger js-action" data-action="delete-competency" data-competency="' + index + '" title="Delete"><i class="mdi mdi-delete-outline"></i></button>';
            html += '</div><div class="competency-body"><div><label class="ipcrf-label">Indicators (one per line)</label><textarea rows="5" class="form-control ipcrf-input js-competency-indicators" data-competency="' + index + '"' + disabledUnlessFull() + '>' + escapeHtml((competency.indicators || []).join('\n')) + '</textarea></div>';
            html += '<div><label class="ipcrf-label">Ratings</label><div class="competency-ratings">';
            [['employee_rating', 'Employee', scope === 'full'], ['rater_rating', 'Rater', scope === 'rater'], ['final_rating', 'Final', scope === 'pmt']].forEach(function (rating) {
                html += '<div class="rating-cell"><span>' + rating[1].toUpperCase() + '</span><span class="js-competency-rating-wrap" data-competency="' + index + '" data-field="' + rating[0] + '">' + ratingOptions(competency[rating[0]], rating[2]) + '</span></div>';
            });
            html += '</div></div></div></div>';
        });
        if (!html) html = '<div class="text-center py-4 ipcrf-muted">No competencies yet. Load the preset or add a competency.</div>';
        $('#competencyContainer').html(html);
    }

    function renderDevelopment() {
        var html = '';
        (state.development || []).forEach(function (plan, index) {
            html += '<tr>';
            ['strengths', 'improvement_needs', 'learning_objectives', 'interventions'].forEach(function (field) {
                html += '<td><textarea class="js-plan-field" data-plan="' + index + '" data-field="' + field + '"' + disabledUnlessFull() + '>' + escapeHtml(plan[field]) + '</textarea></td>';
            });
            ['target_timeline', 'responsible_person'].forEach(function (field) {
                html += '<td><input class="js-plan-field" data-plan="' + index + '" data-field="' + field + '" value="' + escapeAttr(plan[field]) + '"' + disabledUnlessFull() + '></td>';
            });
            html += '<td><textarea class="js-plan-field" data-plan="' + index + '" data-field="status_remarks"' + disabledUnlessFull() + '>' + escapeHtml(plan.status_remarks) + '</textarea></td>';
            html += '<td>' + (isFull() ? '<button type="button" class="btn-icon danger js-action" data-action="delete-plan" data-plan="' + index + '"><i class="mdi mdi-delete-outline"></i></button>' : '') + '</td></tr>';
        });
        if (!html) html = '<tr><td colspan="8" class="text-center ipcrf-muted py-4">No development plan rows yet.</td></tr>';
        $('#developmentContainer').html(html);
    }

    function calculateSummary() {
        var weight = 0, score = 0, ratedWeight = 0, objectiveCount = 0, accomplishments = 0, standardGaps = 0;
        (state.kras || []).forEach(function (kra) {
            (kra.objectives || []).forEach(function (objective) {
                objectiveCount++;
                var objectiveWeight = Number(objective.weight || 0);
                var average = objectiveAverage(objective);
                weight += objectiveWeight;
                if (String(objective.accomplishment || '').trim()) accomplishments++;
                ['quality', 'efficiency', 'timeliness'].forEach(function (dimension) {
                    ['5', '4', '3', '2', '1'].forEach(function (level) {
                        if (!objective[dimension] || !String(objective[dimension][level] || '').trim()) standardGaps++;
                    });
                });
                if (average > 0) { score += average * objectiveWeight / 100; ratedWeight += objectiveWeight; }
            });
        });
        var overall = ratedWeight > 0 ? score / (ratedWeight / 100) : 0;
        var adjectival = 'Not yet rated';
        if (overall >= 4.5) adjectival = 'Outstanding';
        else if (overall >= 3.5) adjectival = 'Very Satisfactory';
        else if (overall >= 2.5) adjectival = 'Satisfactory';
        else if (overall >= 1.5) adjectival = 'Unsatisfactory';
        else if (overall >= 1) adjectival = 'Poor';
        return { weight: weight, score: score, ratedWeight: ratedWeight, overall: overall, adjectival: adjectival, objectiveCount: objectiveCount, accomplishments: accomplishments, standardGaps: standardGaps };
    }

    function renderSummary() {
        var summary = calculateSummary();
        var clamped = Math.max(0, Math.min(100, summary.weight));
        var ringColor = Math.abs(summary.weight - 100) < 0.01 ? '#0f9f8f' : (summary.weight > 100 ? '#d84a4a' : '#f5b942');
        $('#weightRing').css('background', 'conic-gradient(' + ringColor + ' ' + (clamped * 3.6) + 'deg, #e8edf5 0deg)');
        $('#weightTotal').text(summary.weight.toFixed(2) + '%');
        $('#toolbarWeight').text('Weight ' + summary.weight.toFixed(2) + '%');
        $('#openSummaryBtn').toggleClass('weight-ready', Math.abs(summary.weight - 100) < .01).toggleClass('weight-warning', Math.abs(summary.weight - 100) >= .01);
        var variance = summary.weight - 100;
        $('#weightVariance').text((variance > 0 ? '+' : variance < 0 ? '−' : '') + Math.abs(variance).toFixed(2) + '%').css('color', Math.abs(variance) < .01 ? '#0f9f8f' : '#d84a4a');
        $('#weightedScore').text(summary.score.toFixed(3));
        $('#overallRating').text(summary.overall.toFixed(3));
        $('#adjectivalRating').text(summary.adjectival);
        var employeeRatings = (state.competencies || []).filter(function (c) { return Number(c.employee_rating) > 0; }).length;
        var checks = [
            { valid: Math.abs(summary.weight - 100) < .01, label: 'Total weight is exactly 100%' },
            { valid: summary.objectiveCount > 0 && summary.standardGaps === 0, label: summary.standardGaps ? summary.standardGaps + ' performance indicator field(s) incomplete' : 'All performance indicators are complete' },
            { valid: summary.objectiveCount > 0 && summary.accomplishments === summary.objectiveCount, label: summary.accomplishments + ' of ' + summary.objectiveCount + ' accomplishments entered' },
            { valid: state.competencies && state.competencies.length > 0 && employeeRatings === state.competencies.length, label: employeeRatings + ' of ' + (state.competencies || []).length + ' employee competency ratings entered' }
        ];
        $('#validationSummary').html(checks.map(function (check) { return '<li class="' + (check.valid ? 'valid' : '') + '"><i class="mdi ' + (check.valid ? 'mdi-check-circle' : 'mdi-alert-circle-outline') + '"></i><span>' + escapeHtml(check.label) + '</span></li>'; }).join(''));
        $('#historyList').html((state.history || []).map(function (history) {
            return '<div class="history-item"><strong>' + escapeHtml(history.to_status) + '</strong><span>' + escapeHtml(history.acted_by_name || history.acted_by) + ' · ' + escapeHtml(history.acted_at) + '</span>' + (history.remarks ? '<span>' + escapeHtml(history.remarks) + '</span>' : '') + '</div>';
        }).join('') || '<span class="ipcrf-muted">No workflow activity yet.</span>');
    }

    function actionButton(action, label, icon, style) {
        return '<button type="button" class="btn ' + style + ' js-workflow" data-action="' + action + '"><i class="mdi ' + icon + ' mr-1"></i>' + label + '</button>';
    }

    function renderWorkflow() {
        var form = state.form;
        var status = form.status;
        var html = '';
        if ((status === 'Draft' || status === 'Returned for Revision') && scope === 'full') {
            html = actionButton('submit_rater', 'Submit to Rater', 'mdi-send-outline', 'btn-success');
        } else if (status === 'Submitted to Rater' && (form.rater_id === config.actor || config.isAdmin)) {
            html = actionButton('rater_approve', 'Rater Approve', 'mdi-check-decagram', 'btn-success') + actionButton('return_revision', 'Return', 'mdi-undo-variant', 'btn-soft-danger ml-2');
        } else if (status === 'Rater Approved' && (form.rater_id === config.actor || config.isAdmin)) {
            html = actionButton('submit_pmt', 'Submit to PMT', 'mdi-send-check-outline', 'btn-success');
        } else if (status === 'Submitted to PMT' && config.isPmt) {
            html = actionButton('pmt_validate', 'PMT Validate', 'mdi-shield-check-outline', 'btn-success');
        } else if (status === 'PMT Validated' && config.isPmt) {
            html = actionButton('lock', 'Lock Record', 'mdi-lock-outline', 'btn-success');
        } else if (config.isAdmin && status !== 'Draft' && status !== 'Returned for Revision') {
            html = actionButton('reopen', 'Reopen', 'mdi-lock-open-variant-outline', 'btn-warning');
        }
        $('#workflowButtons').html(html);
        $('#formStatus').text(status);
    }

    function renderAll() {
        renderKras();
        renderCompetencies();
        renderDevelopment();
        renderSummary();
        renderWorkflow();
    }

    function markDirty() {
        if (scope === 'none') return;
        dirty = true;
        $('#saveState').text('Unsaved changes').css('color', '#b77b08');
        clearTimeout(saveTimer);
        saveTimer = setTimeout(function () { saveDraft(false); }, 1100);
        renderSummary();
    }

    function mergeServerIds(bundle) {
        if (!bundle) return;
        if (bundle.form) {
            state.form.rater_id = bundle.form.rater_id;
            state.form.rater_name = bundle.form.rater_name;
            state.form.rater_position = bundle.form.rater_position;
            state.form.period_start = bundle.form.period_start;
            state.form.period_end = bundle.form.period_end;
        }
        (state.kras || []).forEach(function (kra, k) {
            if (!bundle.kras[k]) return;
            kra.id = bundle.kras[k].id;
            (kra.objectives || []).forEach(function (objective, o) {
                if (bundle.kras[k].objectives[o]) {
                    objective.id = bundle.kras[k].objectives[o].id;
                    objective.evidence = bundle.kras[k].objectives[o].evidence || objective.evidence || [];
                }
            });
        });
        (state.competencies || []).forEach(function (competency, c) { if (bundle.competencies[c]) competency.id = bundle.competencies[c].id; });
        (state.development || []).forEach(function (plan, p) { if (bundle.development[p]) plan.id = bundle.development[p].id; });
    }

    function saveDraft(showToast) {
        clearTimeout(saveTimer);
        if (scope === 'none') return $.Deferred().resolve().promise();
        if (saving) {
            pendingSave = true;
            return activeSave.then(function () { return saveDraft(showToast); });
        }
        saving = true;
        var failed = false;
        dirty = false;
        pendingSave = false;
        $('#saveState').text('Saving…').css('color', '#3157c8');
        $('#saveDraftBtn').prop('disabled', true);
        activeSave = $.ajax({
            url: config.urls.save,
            method: 'POST',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            data: JSON.stringify(state)
        }).done(function (response) {
            mergeServerIds(response.bundle);
            $('#saveState').text('Saved ' + response.saved_at).css('color', '#0f9f8f');
            if (showToast) toastr.success(response.message || 'Changes saved.');
        }).fail(function (xhr) {
            failed = true;
            dirty = true;
            $('#saveState').text('Save failed').css('color', '#d84a4a');
            showError(xhr, 'The draft could not be saved.');
        }).always(function () {
            saving = false;
            $('#saveDraftBtn').prop('disabled', scope === 'none');
            if (!failed && (pendingSave || dirty)) {
                saveTimer = setTimeout(function () { saveDraft(false); }, 350);
            }
        });
        return activeSave;
    }

    function structuralChange() {
        renderAll();
        markDirty();
    }

    function moveItem(items, from, to) {
        if (to < 0 || to >= items.length) return;
        items.splice(to, 0, items.splice(from, 1)[0]);
    }

    function defaultTimeline() {
        var year = state.form && state.form.period_start ? String(state.form.period_start).substring(0, 4) : String(new Date().getFullYear());
        return 'January to December ' + year;
    }

    function openKraEditor(index) {
        var isNew = index < 0;
        $('#kraEditorIndex').val(index);
        $('#kraEditorName').val(isNew ? '' : state.kras[index].title);
        $('#kraEditorTitle').text(isNew ? 'Add New KRA' : 'Edit KRA');
        $('#kraEditorModal').modal('show');
        setTimeout(function () { $('#kraEditorName').trigger('focus'); }, 250);
    }

    function openObjectiveEditor(kraIndex, objectiveIndex) {
        var isNew = objectiveIndex < 0;
        var objective = isNew ? newObjective() : state.kras[kraIndex].objectives[objectiveIndex];
        if (isNew) {
            objective.code = (kraIndex + 1) + '.' + ((state.kras[kraIndex].objectives || []).length + 1);
            objective.timeline = defaultTimeline();
        }
        $('#objectiveEditorKra').val(kraIndex);
        $('#objectiveEditorIndex').val(objectiveIndex);
        $('#objectiveEditorCode').val(objective.code || '');
        $('#objectiveEditorText').val(objective.objective || '');
        $('#objectiveEditorTimeline').val(objective.timeline || defaultTimeline());
        $('#objectiveEditorWeight').val(Number(objective.weight || 0));
        $('#objectiveKraLabel').text(state.kras[kraIndex].title || 'Objective');
        $('#objectiveEditorTitle').text(isNew ? 'Add New Objective' : 'Edit Objective Details');
        $('#objectiveEditorModal').modal('show');
        setTimeout(function () { $('#objectiveEditorText').trigger('focus'); }, 250);
    }

    function focusObjective(kraIndex, objectiveIndex) {
        setTimeout(function () {
            var kra = document.getElementById('kra-' + kraIndex);
            if (!kra) return;
            kra.open = true;
            var objectives = kra.querySelectorAll('.objective-card');
            if (objectives[objectiveIndex]) {
                objectives[objectiveIndex].open = true;
                objectives[objectiveIndex].scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, 180);
    }

    function hideModalThen($modal, callback) {
        $modal.one('hidden.bs.modal', callback);
        var modalInstance = $modal.data('bs.modal');
        if (modalInstance && modalInstance._isTransitioning) {
            $modal.one('shown.bs.modal', function () { $modal.modal('hide'); });
            return;
        }
        $modal.modal('hide');
    }

    function handleAction(action, $button) {
        var k = Number($button.data('kra'));
        var o = Number($button.data('objective'));
        var c = Number($button.data('competency'));
        var p = Number($button.data('plan'));
        if (action === 'add-objective') {
            openObjectiveEditor(k, -1);
            return;
        } else if (action === 'edit-objective') {
            openObjectiveEditor(k, o);
            return;
        } else if (action === 'edit-kra') {
            openKraEditor(k);
            return;
        } else if (action === 'duplicate-objective') {
            var objectiveCopy = JSON.parse(JSON.stringify(state.kras[k].objectives[o]));
            objectiveCopy.id = 0; objectiveCopy.evidence = [];
            state.kras[k].objectives.splice(o + 1, 0, objectiveCopy);
        } else if (action === 'delete-objective') state.kras[k].objectives.splice(o, 1);
        else if (action === 'objective-up') moveItem(state.kras[k].objectives, o, o - 1);
        else if (action === 'objective-down') moveItem(state.kras[k].objectives, o, o + 1);
        else if (action === 'duplicate-kra') {
            var kraCopy = JSON.parse(JSON.stringify(state.kras[k]));
            kraCopy.id = 0;
            kraCopy.title += ' (Copy)';
            kraCopy.objectives.forEach(function (objective) { objective.id = 0; objective.evidence = []; });
            state.kras.splice(k + 1, 0, kraCopy);
        } else if (action === 'delete-kra') state.kras.splice(k, 1);
        else if (action === 'kra-up') moveItem(state.kras, k, k - 1);
        else if (action === 'kra-down') moveItem(state.kras, k, k + 1);
        else if (action === 'delete-competency') state.competencies.splice(c, 1);
        else if (action === 'delete-plan') state.development.splice(p, 1);
        structuralChange();
        if (action === 'duplicate-objective') focusObjective(k, o + 1);
    }

    function bindEditorEvents() {
        $('#editorRater').select2(employeeSelectOptions('Search assigned rater')).on('select2:select', function (event) {
            var employee = event.params.data.employee;
            state.form.rater_id = employee.id;
            state.form.rater_name = employee.name;
            state.form.rater_position = employee.position;
            $('#raterPositionDisplay').text(employee.position || '—');
            markDirty();
        }).on('select2:clear', function () {
            state.form.rater_id = ''; state.form.rater_name = ''; state.form.rater_position = ''; $('#raterPositionDisplay').text('—'); markDirty();
        });

        $(document).on('click', '.js-action', function (event) {
            event.preventDefault();
            event.stopPropagation();
            var $button = $(this);
            var action = $button.data('action');
            if (String(action).indexOf('delete-') === 0) {
                var itemLabel = action === 'delete-kra' ? 'this KRA and all of its objectives' : (action === 'delete-objective' ? 'this objective' : 'this item');
                Swal.fire({ icon: 'warning', title: 'Delete ' + itemLabel + '?', text: 'This change will be saved to the Draft.', showCancelButton: true, confirmButtonText: 'Yes, delete it', confirmButtonColor: '#c44949' }).then(function (result) {
                    if (result.value) handleAction(action, $button);
                });
                return;
            }
            handleAction(action, $button);
        });
        $(document).on('input', '.js-kra-title', function () { state.kras[Number($(this).data('kra'))].title = this.value; markDirty(); });
        $(document).on('input change', '.js-objective-field', function () {
            var objective = state.kras[Number($(this).data('kra'))].objectives[Number($(this).data('objective'))];
            objective[$(this).data('field')] = $(this).data('field') === 'weight' ? Number(this.value || 0) : this.value;
            markDirty();
        });
        $(document).on('input', '.js-standard', function () {
            var objective = state.kras[Number($(this).data('kra'))].objectives[Number($(this).data('objective'))];
            objective[$(this).data('dimension')][String($(this).data('level'))] = this.value;
            markDirty();
        });
        $(document).on('change', '.js-objective-rating-wrap select', function () {
            var $wrap = $(this).closest('.js-objective-rating-wrap');
            state.kras[Number($wrap.data('kra'))].objectives[Number($wrap.data('objective'))][$wrap.data('field')] = Number(this.value);
            markDirty();
        });
        $(document).on('input change', '.js-competency-field', function () { state.competencies[Number($(this).data('competency'))][$(this).data('field')] = this.value; markDirty(); });
        $(document).on('input', '.js-competency-indicators', function () { state.competencies[Number($(this).data('competency'))].indicators = this.value.split(/\r?\n/); markDirty(); });
        $(document).on('change', '.js-competency-rating-wrap select', function () {
            var $wrap = $(this).closest('.js-competency-rating-wrap');
            state.competencies[Number($wrap.data('competency'))][$wrap.data('field')] = Number(this.value);
            markDirty();
        });
        $(document).on('input', '.js-plan-field', function () { state.development[Number($(this).data('plan'))][$(this).data('field')] = this.value; markDirty(); });
        $(document).on('change', '.js-header-field', function () { state.form[$(this).data('field')] = this.value; markDirty(); });

        $('#addKraBtn').on('click', function () { openKraEditor(-1); });
        $('#addCompetencyBtn').on('click', function () { state.competencies.push(newCompetency()); structuralChange(); });
        $('#addPlanBtn').on('click', function () { state.development.push(newPlan()); structuralChange(); });
        $('#saveDraftBtn').on('click', function () { saveDraft(true); });

        $('#kraEditorForm').on('submit', function (event) {
            event.preventDefault();
            var index = Number($('#kraEditorIndex').val());
            var title = String($('#kraEditorName').val() || '').trim();
            if (!title) { $('#kraEditorName').trigger('focus'); return; }
            var isNew = index < 0;
            if (isNew) {
                index = state.kras.length;
                state.kras.push({ id: 0, title: title, objectives: [] });
            } else {
                state.kras[index].title = title;
            }
            hideModalThen($('#kraEditorModal'), function () {
                structuralChange();
                var kra = document.getElementById('kra-' + index);
                if (kra) kra.open = true;
                if (isNew) {
                    toastr.success('KRA created. Now add its first objective.');
                    openObjectiveEditor(index, -1);
                }
            });
        });

        $('#objectiveEditorForm').on('submit', function (event) {
            event.preventDefault();
            var kraIndex = Number($('#objectiveEditorKra').val());
            var objectiveIndex = Number($('#objectiveEditorIndex').val());
            var textValue = String($('#objectiveEditorText').val() || '').trim();
            var timelineValue = String($('#objectiveEditorTimeline').val() || '').trim();
            if (!textValue) { $('#objectiveEditorText').trigger('focus'); return; }
            if (!timelineValue) { $('#objectiveEditorTimeline').trigger('focus'); return; }
            var isNew = objectiveIndex < 0;
            var objective = isNew ? newObjective() : state.kras[kraIndex].objectives[objectiveIndex];
            objective.code = String($('#objectiveEditorCode').val() || '').trim();
            objective.objective = textValue;
            objective.timeline = timelineValue;
            objective.weight = Math.max(0, Math.min(100, Number($('#objectiveEditorWeight').val() || 0)));
            if (isNew) {
                objectiveIndex = state.kras[kraIndex].objectives.length;
                state.kras[kraIndex].objectives.push(objective);
            }
            hideModalThen($('#objectiveEditorModal'), function () {
                structuralChange();
                focusObjective(kraIndex, objectiveIndex);
                toastr.success(isNew ? 'Objective added. Complete its indicators and results below.' : 'Objective details updated.');
            });
        });

        $('#loadPresetBtn').on('click', function () {
            var options = {};
            (config.templates || []).forEach(function (template) { options[template.id] = template.name + ' (' + template.year + ')'; });
            Swal.fire({ title: 'Load IPCRF preset', text: 'The preset is copied into this employee form and replaces its current KRAs and competencies.', input: 'select', inputOptions: options, showCancelButton: true, confirmButtonText: 'Load Preset', confirmButtonColor: '#3157c8' }).then(function (result) {
                if (!result.value) return;
                $.post(config.urls.loadPreset, { template_id: result.value }, null, 'json').done(function (response) {
                    state = response.bundle; renderAll(); toastr.success(response.message);
                }).fail(function (xhr) { showError(xhr, 'The preset could not be loaded.'); });
            });
        });

        $('#validateBtn, #modalValidateBtn').on('click', function () {
            $('#summaryModal').modal('hide');
            var proceed = scope === 'none' ? $.Deferred().resolve().promise() : saveDraft(false);
            proceed.then(function () {
                $.getJSON(config.urls.validate).done(function (response) {
                    Swal.fire({ icon: 'success', title: 'Validation passed', text: response.message, confirmButtonColor: '#3157c8' });
                }).fail(function (xhr) { showError(xhr, 'Validation found incomplete requirements.'); });
            });
        });

        $(document).on('click', '.js-workflow', function () {
            var action = $(this).data('action');
            var needsRemarks = action === 'return_revision' || action === 'reopen';
            var labels = { submit_rater: 'Submit this IPCRF to the assigned rater?', rater_approve: 'Approve this IPCRF as rater?', submit_pmt: 'Submit this approved IPCRF to the PMT?', pmt_validate: 'Validate this IPCRF as PMT?', lock: 'Lock this validated IPCRF?', return_revision: 'Return this IPCRF for revision?', reopen: 'Reopen this IPCRF for revision?' };
            Swal.fire({ title: labels[action], input: needsRemarks ? 'textarea' : undefined, inputPlaceholder: needsRemarks ? 'Required remarks / reason' : undefined, showCancelButton: true, confirmButtonText: 'Continue', confirmButtonColor: '#3157c8', inputValidator: needsRemarks ? function (value) { return value && value.trim() ? undefined : 'Remarks are required.'; } : undefined }).then(function (result) {
                if (!result.value && result.dismiss) return;
                var proceed = scope === 'none' ? $.Deferred().resolve().promise() : saveDraft(false);
                proceed.then(function () {
                    $.post(config.urls.workflow, { action: action, remarks: needsRemarks ? result.value : '' }, null, 'json').done(function (response) {
                        toastr.success(response.message); window.location.href = response.reload;
                    }).fail(function (xhr) { showError(xhr, 'The workflow status could not be changed.'); });
                });
            });
        });

        $(document).on('change', '.js-evidence-input', function () {
            var input = this;
            if (!input.files || !input.files[0]) return;
            var k = Number($(input).data('kra')), o = Number($(input).data('objective'));
            saveDraft(false).then(function () {
                var objective = state.kras[k].objectives[o];
                if (!objective.id) { toastr.error('Save the objective before uploading evidence.'); return; }
                var data = new FormData(); data.append('objective_id', objective.id); data.append('evidence', input.files[0]);
                $.ajax({ url: config.urls.uploadEvidence, method: 'POST', data: data, processData: false, contentType: false, dataType: 'json' }).done(function (response) {
                    objective.evidence = objective.evidence || []; objective.evidence.unshift(response.evidence); renderKras(); toastr.success(response.message);
                }).fail(function (xhr) { showError(xhr, 'Evidence could not be uploaded.'); });
            });
        });

        $(document).on('click', '.js-delete-evidence', function (event) {
            event.preventDefault(); event.stopPropagation();
            var $button = $(this), k = Number($button.data('kra')), o = Number($button.data('objective')), evidenceId = Number($button.data('evidence'));
            $.ajax({ url: config.urls.deleteEvidenceBase + '/' + evidenceId, method: 'POST', dataType: 'json' }).done(function (response) {
                state.kras[k].objectives[o].evidence = (state.kras[k].objectives[o].evidence || []).filter(function (item) { return Number(item.id) !== evidenceId; });
                renderKras(); toastr.success(response.message);
            }).fail(function (xhr) { showError(xhr, 'Evidence could not be removed.'); });
        });
    }

    $(function () {
        toastr.options = { closeButton: true, progressBar: true, positionClass: 'toast-bottom-right', timeOut: 2800 };
        if (!config.hasForm) { initLanding(); return; }
        state.kras = state.kras || [];
        state.competencies = state.competencies || [];
        state.development = state.development || [];
        state.history = state.history || [];
        renderAll();
        bindEditorEvents();
    });
})(jQuery);
