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
    var reviewWarnings = config.reviewWarnings || [];
    var reviewWarningGroups = config.reviewWarningGroups || [];
    // Editing shows only started tables; a submit attempt reveals everything.
    var showAllWarnings = false;

    function setSaveState(status, message) {
        var icons = {
            pending: 'mdi-circle-edit-outline',
            saving: 'mdi-loading mdi-spin',
            saved: 'mdi-check-circle-outline',
            error: 'mdi-alert-circle-outline'
        };
        $('#saveState')
            .removeClass('is-pending is-saving is-saved is-error')
            .addClass('is-' + status)
            .html('<i class="mdi ' + icons[status] + '"></i><span>' + escapeHtml(message) + '</span>');
    }

    function showSavedNotice() {
        toastr.success('Your latest changes are safely stored.', 'Changes saved');
    }

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
        var $startForm = $('#startIpcrfForm');
        var $startRater = $('#startRater');
        var $startApprovingAuthority = $('#startApprovingAuthority');
        var $submitButton = $startForm.find('button[type="submit"]');

        $startRater.select2(employeeSelectOptions('Search assigned rater'));
        $startApprovingAuthority.select2(employeeSelectOptions('Search approving authority'));

        function setLandingEmployee($select, id, name) {
            $select.empty();
            if (id) {
                $select.append(new Option((name || id) + ' · ' + id, id, true, true));
            }
            $select.trigger('change');
        }

        function submitButtonHtml(loading) {
            if (loading) {
                return '<i class="mdi mdi-loading mdi-spin mr-1"></i>Saving Draft';
            }
            if (Number($('#startFormId').val()) > 0) {
                return '<i class="mdi mdi-content-save-edit-outline mr-1"></i>Save Draft & Open IPCRF';
            }
            return '<i class="mdi mdi-arrow-right mr-1"></i>Start / Open My IPCRF';
        }

        function resetLandingUpdate() {
            $('#startFormId').val('');
            setLandingEmployee($startRater, '', '');
            setLandingEmployee($startApprovingAuthority, '', '');
            $startForm.find('input[type="date"]').each(function () { $(this).val($(this).data('default-value')); });
            $('#personalUpdateNotice').prop('hidden', true);
            $startForm.removeClass('is-updating');
            $submitButton.html(submitButtonHtml(false));
        }

        $(document).on('click', '.js-update-ipcrf', function () {
            var $button = $(this);
            $('#startFormId').val(Number($button.data('id')));
            setLandingEmployee($startRater, String($button.data('rater-id') || ''), String($button.data('rater-name') || ''));
            setLandingEmployee($startApprovingAuthority, String($button.data('authority-id') || ''), String($button.data('authority-name') || ''));
            $startForm.find('[name="period_start"]').val(String($button.data('period-start') || ''));
            $startForm.find('[name="period_end"]').val(String($button.data('period-end') || ''));
            $('#personalUpdateRecordLabel').text(String($button.data('period') || 'Selected IPCRF') + ' · changes will be saved as Draft');
            $('#personalUpdateNotice').prop('hidden', false);
            $startForm.addClass('is-updating');
            $submitButton.html(submitButtonHtml(false));
            document.getElementById('personalUpdateNotice').scrollIntoView({ behavior: 'smooth', block: 'center' });
        });

        $('#cancelPersonalUpdate').on('click', resetLandingUpdate);

        $('#startIpcrfForm').on('submit', function (event) {
            event.preventDefault();
            var $button = $submitButton;
            $button.prop('disabled', true).html(submitButtonHtml(true));
            $.ajax({ url: config.urls.create, method: 'POST', data: $(this).serialize(), dataType: 'json' })
                .done(function (response) { window.location.href = response.url; })
                .fail(function (xhr) { showError(xhr, 'The IPCRF could not be opened.'); })
                .always(function () { $button.prop('disabled', false).html(submitButtonHtml(false)); });
        });

        $(document).on('click', '.js-delete-ipcrf', function () {
            var $button = $(this);
            var formId = Number($button.data('id'));
            var period = String($button.data('period') || 'this performance period');

            Swal.fire({
                icon: 'warning',
                title: 'Delete this IPCRF record?',
                text: period + ' and all of its saved information will be permanently deleted.',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete record',
                cancelButtonText: 'Keep record',
                confirmButtonColor: '#c44949'
            }).then(function (result) {
                if (!result.value) { return; }
                $button.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin mr-1"></i>Deleting');
                $.ajax({
                    url: config.urls.deleteFormBase + '/' + formId,
                    method: 'POST',
                    dataType: 'json'
                }).done(function (response) {
                    toastr.success(response.message);
                    window.location.href = response.reload || window.location.href;
                }).fail(function (xhr) {
                    showError(xhr, 'The IPCRF record could not be deleted.');
                    $button.prop('disabled', false).html('<i class="mdi mdi-delete-outline mr-1"></i>Delete');
                });
            });
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
        return { id: 0, category: 'Core Behavioral Competency', name: '', indicators: [], rating: 0 };
    }

    function newPlan() {
        return { id: 0, strengths: '', improvement_needs: '', learning_objectives: '', interventions: '', target_timeline: '', responsible_person: '', status_remarks: '' };
    }

    function isFull() { return scope === 'full'; }
    function canEditDevelopment() { return scope === 'full' || scope === 'rater'; }
    function canEnterObjectiveRatings() { return scope === 'full' || scope === 'rater'; }
    function ratingsAreApproved() {
        return ['Rater Approved', 'Submitted to PMT', 'PMT Validated', 'Locked'].indexOf(state.form.status) !== -1;
    }
    function disabledUnlessDevelopmentEditor() { return canEditDevelopment() ? '' : ' disabled'; }
    function editableAttribute(field, singleLine) {
        return isFull() ? ' contenteditable="true" spellcheck="true" class="objective-inline-field js-objective-inline' + (singleLine ? ' single-line' : '') + '" data-field="' + field + '"' : ' class="objective-inline-field"';
    }

    function ratingOptions(value, enabled) {
        var options = [
            { value: 0, label: '—' },
            { value: 5, label: '5' },
            { value: 4, label: '4' },
            { value: 3, label: '3' },
            { value: 2, label: '2' },
            { value: 1, label: '1' }
        ];
        var html = '<select class="form-control ipcrf-input"' + (enabled ? '' : ' disabled') + '>';
        options.forEach(function (option) {
            html += '<option value="' + option.value + '"' + (Number(value) === option.value ? ' selected' : '') + '>' + option.label + '</option>';
        });
        return html + '</select>';
    }

    function competencyRatingOptions(value, enabled) {
        var options = [
            { value: 0, label: '— Select rating —' },
            { value: 5, label: '5 — Role Model' },
            { value: 4, label: '4 — Consistently Demonstrates' },
            { value: 3, label: '3 — Most of the Time' },
            { value: 2, label: '2 — Sometimes Demonstrates' },
            { value: 1, label: '1 — Rarely Demonstrates' }
        ];
        var html = '<select class="form-control ipcrf-input"' + (enabled ? '' : ' disabled') + '>';
        options.forEach(function (option) {
            html += '<option value="' + option.value + '"' + (Number(value) === option.value ? ' selected' : '') + '>' + option.label + '</option>';
        });
        return html + '</select>';
    }

    function competencyEditableAttribute(field, singleLine) {
        return isFull() ? ' contenteditable="true" spellcheck="true" class="competency-inline-field js-competency-inline' + (singleLine ? ' single-line' : '') + '" data-field="' + field + '"' : ' class="competency-inline-field"';
    }

    function objectiveAverage(objective) {
        var q = Number(objective.quality_rating || 0);
        var e = Number(objective.efficiency_rating || 0);
        var t = Number(objective.timeliness_rating || 0);
        return q > 0 && e > 0 && t > 0 ? (q + e + t) / 3 : 0;
    }

    function standardCompletion(objective, dimension) {
        return ['5', '4', '3', '2', '1'].filter(function (level) {
            return objective[dimension] && String(objective[dimension][level] || '').trim();
        }).length;
    }

    function renderStandardCell(kraIndex, objectiveIndex, objective, dimension) {
        var completed = standardCompletion(objective, dimension);
        var statusClass = completed === 5 ? ' complete' : (completed > 0 ? ' partial' : ' empty');
        var label = dimension.charAt(0).toUpperCase() + dimension.slice(1);
        return '<div class="objective-standard-cell" data-label="' + label + '"><button type="button" class="standard-cell-button js-action' + statusClass + '" data-action="open-standards" data-kra="' + kraIndex + '" data-objective="' + objectiveIndex + '" data-dimension="' + dimension + '"><strong>Open 5 levels</strong><span class="standard-count">' + completed + ' / 5 complete</span><em>' + (isFull() ? 'View or edit details' : 'View details') + '</em></button></div>';
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
        var approved = ratingsAreApproved();
        var weighted = approved ? average * Number(objective.weight || 0) / 100 : 0;
        var html = '<div class="objective-row" data-kra="' + kraIndex + '" data-objective="' + objectiveIndex + '">';
        html += '<div class="objective-code" data-label="Code"><div' + editableAttribute('code', true) + ' data-kra="' + kraIndex + '" data-objective="' + objectiveIndex + '" data-placeholder="Code">' + escapeHtml(objective.code || (kraIndex + 1) + '.' + (objectiveIndex + 1)) + '</div></div>';
        html += '<div class="objective-summary-cell" data-label="Objective"><div' + editableAttribute('objective', false) + ' data-kra="' + kraIndex + '" data-objective="' + objectiveIndex + '" data-placeholder="Click to enter the objective">' + escapeHtml(objective.objective || '') + '</div>';
        var objectiveId = Number(objective.id || 0);
        var accCount = objectiveId > 0 && window.IPCRF_CONFIG && window.IPCRF_CONFIG.accCounts ? (window.IPCRF_CONFIG.accCounts[objectiveId] || 0) : 0;
        var accUrl = (window.IPCRF_CONFIG && window.IPCRF_CONFIG.urls && window.IPCRF_CONFIG.urls.accByObjectiveBase ? window.IPCRF_CONFIG.urls.accByObjectiveBase : '') + '/' + objectiveId;
        if (accCount > 0) {
            html += '<a class="objective-accomplishment-link" href="' + escapeHtml(accUrl) + '" target="_blank" title="View ' + accCount + ' encoded accomplishment(s)"><i class="mdi mdi-clipboard-list-outline"></i><span class="badge badge-soft-primary">' + accCount + '</span></a>';
        }
        if (isFull()) {
            html += '<div class="dropdown objective-more-menu"><button type="button" class="row-more-button js-row-menu-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="More objective options">•••</button><div class="dropdown-menu dropdown-menu-right"><button type="button" class="dropdown-item js-action" data-action="duplicate-objective" data-kra="' + kraIndex + '" data-objective="' + objectiveIndex + '">Copy objective</button><button type="button" class="dropdown-item js-action" data-action="objective-up" data-kra="' + kraIndex + '" data-objective="' + objectiveIndex + '">Move up</button><button type="button" class="dropdown-item js-action" data-action="objective-down" data-kra="' + kraIndex + '" data-objective="' + objectiveIndex + '">Move down</button><div class="dropdown-divider"></div><button type="button" class="dropdown-item text-danger js-action" data-action="delete-objective" data-kra="' + kraIndex + '" data-objective="' + objectiveIndex + '">Delete objective</button></div></div>';
        }
        html += '</div>';
        html += '<div class="objective-timeline" data-label="Timeline"><div' + editableAttribute('timeline', true) + ' data-kra="' + kraIndex + '" data-objective="' + objectiveIndex + '" data-placeholder="Timeline">' + escapeHtml(objective.timeline || '') + '</div></div>';
        html += '<div class="weight-chip" data-label="Weight"><div' + editableAttribute('weight', true) + ' data-kra="' + kraIndex + '" data-objective="' + objectiveIndex + '" data-placeholder="0.00">' + Number(objective.weight || 0).toFixed(2) + '</div><span>%</span></div>';
        ['quality', 'efficiency', 'timeliness'].forEach(function (dimension) {
            html += renderStandardCell(kraIndex, objectiveIndex, objective, dimension);
        });
        html += '<div class="objective-actual-cell" data-label="Actual Result / Evidence"><div' + editableAttribute('accomplishment', false) + ' data-kra="' + kraIndex + '" data-objective="' + objectiveIndex + '" data-placeholder="Click to enter the actual result / accomplishment">' + escapeHtml(objective.accomplishment || '') + '</div>' + renderEvidence(kraIndex, objectiveIndex, objective) + '</div>';
        ['quality_rating', 'efficiency_rating', 'timeliness_rating'].forEach(function (field, ratingIndex) {
            var ratingLabel = ['Q Rating', 'E Rating', 'T Rating'][ratingIndex];
            html += '<div class="objective-rating-cell" data-label="' + ratingLabel + '" title="' + (scope === 'full' ? 'Owner proposed rating; the rater reviews this after submission.' : '') + '"><span class="js-objective-rating-wrap" data-kra="' + kraIndex + '" data-objective="' + objectiveIndex + '" data-field="' + field + '">' + ratingOptions(objective[field], canEnterObjectiveRatings()) + '</span></div>';
        });
        html += '<div class="objective-score-cell ' + (approved ? 'approved' : 'pending') + '" data-label="Rating / Score">';
        if (approved) { html += '<span>Approved Average</span>'; }
        html += '<strong class="js-average-score">' + (average > 0 ? average.toFixed(2) : '—') + '</strong>';
        html += '<small class="js-score-caption">' + (approved ? 'Weighted ' : '') + '<b class="js-weighted-score">' + weighted.toFixed(3) + '</b></small></div>';
        return html + '</div>';
    }

    function renderKras() {
        var html = '';
        (state.kras || []).forEach(function (kra, kraIndex) {
            var weight = (kra.objectives || []).reduce(function (sum, objective) { return sum + Number(objective.weight || 0); }, 0);
            var isOpen = kraIndex === 0;
            html += '<details class="kra-card" id="kra-' + kraIndex + '"' + (isOpen ? ' open' : '') + '><summary>';
            html += '<span class="kra-pdf-label">Key Result Area</span>';
            html += '<span class="kra-heading">';
            html += '<strong class="kra-title-display js-kra-title" data-kra="' + kraIndex + '"' + (isFull() ? ' contenteditable="true" spellcheck="true" data-placeholder="KRA title"' : '') + '>' + escapeHtml(kra.title || '') + '</strong>';
            // Secondary line: the tag marker and counts sit under the title, out of the way.
            html += '<span class="kra-subline">';
            if (Number(kra.template_kra_id || 0) > 0) {
                html += '<span class="kra-tagged-badge" title="You were tagged into this KRA by the SGOD Chief."><i class="mdi mdi-tag-outline"></i>Tagged</span>';
            }
            html += '<span class="kra-meta">' + (kra.objectives || []).length + ' objective(s) · ' + weight.toFixed(2) + '%</span>';
            html += '<span class="kra-missing-slot"></span>';
            html += '</span></span>';
            html += '<span class="icon-actions action-buttons">';
            if (isFull()) {
                html += '<button type="button" class="action-text-btn add js-action" data-action="add-objective" data-kra="' + kraIndex + '">+ Add Objective</button><span class="dropdown"><button type="button" class="action-text-btn js-row-menu-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">More •••</button><span class="dropdown-menu dropdown-menu-right"><button type="button" class="dropdown-item js-action" data-action="duplicate-kra" data-kra="' + kraIndex + '">Copy KRA</button><button type="button" class="dropdown-item js-action" data-action="kra-up" data-kra="' + kraIndex + '">Move KRA up</button><button type="button" class="dropdown-item js-action" data-action="kra-down" data-kra="' + kraIndex + '">Move KRA down</button><span class="dropdown-divider"></span><button type="button" class="dropdown-item text-danger js-action" data-action="delete-kra" data-kra="' + kraIndex + '">Delete KRA</button></span></span>';
            }
            html += '<button type="button" class="action-text-btn details-toggle-button js-details-toggle" aria-expanded="' + (isOpen ? 'true' : 'false') + '" aria-controls="kra-content-' + kraIndex + '" title="' + (isOpen ? 'Fold this KRA table' : 'Open this KRA table') + '"><i class="mdi ' + (isOpen ? 'mdi-chevron-up' : 'mdi-chevron-down') + '"></i><span class="js-details-toggle-label">' + (isOpen ? 'Fold Table' : 'Open Table') + '</span></button></span>';
            html += '</summary><div class="kra-content" id="kra-content-' + kraIndex + '"><div class="kra-content-guide"><span><strong>One objective per row.</strong> Click a Quality, Efficiency or Timeliness cell to view all five standard levels.</span>' + (isFull() ? '<button type="button" class="action-text-btn add js-action" data-action="add-objective" data-kra="' + kraIndex + '">+ Add Objective to this KRA</button>' : '') + '</div>';
            if ((kra.objectives || []).length) {
                html += '<div class="objective-table-scroll"><div class="objective-table-grid"><div class="pdf-objective-header"><span>Code</span><span>Objectives</span><span>Timeline</span><span>Weight</span><span>Quality</span><span>Efficiency</span><span>Timeliness</span><span>Actual Result / Evidence</span><span title="Owner proposal / rater review">Q Rating</span><span title="Owner proposal / rater review">E Rating</span><span title="Owner proposal / rater review">T Rating</span><span>Rating / Score</span></div>';
                (kra.objectives || []).forEach(function (objective, objectiveIndex) { html += renderObjective(kraIndex, objectiveIndex, objective); });
                html += '</div></div>';
            }
            if (!(kra.objectives || []).length) html += '<div class="kra-empty-objectives">No objectives yet. Use <strong>+ Add Objective to this KRA</strong> above.</div>';
            if (isFull()) html += '<button class="add-dashed js-action" data-action="add-objective" data-kra="' + kraIndex + '" type="button"><i class="mdi mdi-plus mr-1"></i>Add Another Objective to ' + escapeHtml(kra.title || 'this KRA') + '</button>';
            html += '</div></details>';
        });
        if (!html) html = '<div class="kra-empty-objectives">No KRAs yet. Click <strong>Add New KRA</strong> above to create the first result area, or use <strong>Load Preset</strong>.</div>';
        $('#kraContainer').html(html);
    }

    function renderCompetencyRow(index, competency) {
        var categories = ['Core Behavioral Competency', 'Core Skill', 'Leadership Competency'];
        var html = '<div class="competency-table-row" data-competency="' + index + '">';
        html += '<div class="competency-name-cell"><div' + competencyEditableAttribute('name', true) + ' data-competency="' + index + '" data-placeholder="Click to enter the competency name">' + escapeHtml(competency.name || '') + '</div>';
        if (isFull()) {
            html += '<div class="dropdown competency-more-menu"><button type="button" class="row-more-button js-row-menu-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="More competency options">•••</button><div class="dropdown-menu dropdown-menu-right"><button type="button" class="dropdown-item js-action" data-action="duplicate-competency" data-competency="' + index + '">Copy competency</button><button type="button" class="dropdown-item js-action" data-action="competency-up" data-competency="' + index + '">Move up</button><button type="button" class="dropdown-item js-action" data-action="competency-down" data-competency="' + index + '">Move down</button><div class="dropdown-divider"></div>';
            categories.forEach(function (category) {
                if (category !== competency.category) html += '<button type="button" class="dropdown-item js-action" data-action="competency-category" data-category="' + escapeAttr(category) + '" data-competency="' + index + '">Move to ' + escapeHtml(category) + '</button>';
            });
            html += '<div class="dropdown-divider"></div><button type="button" class="dropdown-item text-danger js-action" data-action="delete-competency" data-competency="' + index + '">Delete competency</button></div></div>';
        }
        html += '</div>';
        html += '<div class="competency-indicators-cell"><div' + competencyEditableAttribute('indicators', false) + ' data-competency="' + index + '" data-placeholder="Click to enter behavioral indicators, one per line">' + escapeHtml((competency.indicators || []).join('\n')) + '</div></div>';
        html += '<div class="competency-rating-cell"><span class="js-competency-rating-wrap" data-competency="' + index + '" data-field="rating">' + competencyRatingOptions(competency.rating, scope !== 'none') + '</span></div>';
        return html + '</div>';
    }

    function renderCompetencies() {
        var html = '';
        var categories = ['Core Behavioral Competency', 'Core Skill', 'Leadership Competency'];
        (state.competencies || []).forEach(function (competency) {
            if (categories.indexOf(competency.category) === -1) categories.push(competency.category || 'Other Competency');
        });
        categories.forEach(function (category, categoryIndex) {
            var rows = [];
            (state.competencies || []).forEach(function (competency, index) {
                if ((competency.category || 'Other Competency') === category) rows.push({ competency: competency, index: index });
            });
            if (!rows.length) return;
            var groupTitle = category === 'Core Behavioral Competency' ? 'Core Behavioral Competencies' : (category === 'Core Skill' ? 'Core Skills' : (category === 'Leadership Competency' ? 'Leadership Competencies' : category));
            html += '<details class="competency-group-card" data-category="' + escapeAttr(category) + '"><summary><span class="competency-group-label">Competency Group</span><strong>' + escapeHtml(groupTitle) + '</strong><span class="competency-group-count">' + rows.length + ' competency item(s)</span><span class="competency-missing-slot"></span><span class="competency-group-actions">';
            if (isFull()) html += '<button type="button" class="action-text-btn add js-action" data-action="add-competency" data-category="' + escapeAttr(category) + '">+ Add Competency</button>';
            html += '<button type="button" class="action-text-btn details-toggle-button js-details-toggle" aria-expanded="false" aria-controls="competency-group-content-' + categoryIndex + '" title="Open this competency table"><i class="mdi mdi-chevron-down"></i><span class="js-details-toggle-label">Open Table</span></button></span>';
            html += '</summary><div class="competency-group-content" id="competency-group-content-' + categoryIndex + '"><div class="competency-content-guide"><span><strong>Click text to edit.</strong> Enter one behavioral indicator per line and choose one Rating.</span></div><div class="competency-table-scroll"><div class="competency-table-grid"><div class="competency-table-header"><span>Competency</span><span>Behavioral Indicators</span><span>Rating</span></div>';
            rows.forEach(function (row) { html += renderCompetencyRow(row.index, row.competency); });
            html += '</div></div>';
            if (isFull()) html += '<button type="button" class="add-dashed js-action" data-action="add-competency" data-category="' + escapeAttr(category) + '"><i class="mdi mdi-plus mr-1"></i>Add Another Competency to ' + escapeHtml(groupTitle) + '</button>';
            html += '</div></details>';
        });
        if (!html) html = '<div class="text-center py-4 ipcrf-muted">No competencies yet. Load the preset or add a competency.</div>';
        $('#competencyContainer').html(html);
    }

    function renderDevelopment() {
        var html = '';
        (state.development || []).forEach(function (plan, index) {
            html += '<tr>';
            ['strengths', 'improvement_needs', 'learning_objectives', 'interventions'].forEach(function (field) {
                html += '<td><textarea class="js-plan-field" data-plan="' + index + '" data-field="' + field + '"' + disabledUnlessDevelopmentEditor() + '>' + escapeHtml(plan[field]) + '</textarea></td>';
            });
            ['target_timeline', 'responsible_person'].forEach(function (field) {
                html += '<td><input class="js-plan-field" data-plan="' + index + '" data-field="' + field + '" value="' + escapeAttr(plan[field]) + '"' + disabledUnlessDevelopmentEditor() + '></td>';
            });
            html += '<td><textarea class="js-plan-field" data-plan="' + index + '" data-field="status_remarks"' + disabledUnlessDevelopmentEditor() + '>' + escapeHtml(plan.status_remarks) + '</textarea></td>';
            html += '<td>' + (canEditDevelopment() ? '<button type="button" class="btn-icon danger js-action" data-action="delete-plan" data-plan="' + index + '" title="Delete development entry"><i class="mdi mdi-delete-outline"></i></button>' : '') + '</td></tr>';
        });
        if (!html) html = '<tr><td colspan="8" class="text-center ipcrf-muted py-4">No development plan rows yet.</td></tr>';
        $('#developmentContainer').html(html);
    }

    function renderTrackingHistory() {
        var historyItems = state.history || [];
        $('#historyCurrentStatus').text(state.form.status);
        $('#historyEntryCount').text(historyItems.length + ' recorded transition' + (historyItems.length === 1 ? '' : 's'));
        $('#historyList').html(historyItems.map(function (history) {
            var fromStatus = history.from_status || 'Started';
            var toStatus = history.to_status || state.form.status;
            var actor = history.acted_by_name || history.acted_by || 'System';
            var html = '<div class="history-item"><strong>' + escapeHtml(toStatus) + '</strong>';
            html += '<span class="history-transition">' + escapeHtml(fromStatus) + ' <i class="mdi mdi-arrow-right"></i> ' + escapeHtml(toStatus) + '</span>';
            html += '<span>' + escapeHtml(actor) + ' · ' + escapeHtml(history.acted_at) + '</span>';
            if (history.remarks) html += '<span class="history-remarks">' + escapeHtml(history.remarks) + '</span>';
            return html + '</div>';
        }).join('') || '<div class="tracking-empty"><i class="mdi mdi-timeline-clock-outline"></i><strong>No transitions recorded yet</strong><span>This IPCRF is still in its initial ' + escapeHtml(state.form.status) + ' stage.</span></div>');
    }

    function hasValue(value) {
        return String(value === undefined || value === null ? '' : value).trim() !== '';
    }

    function validPerformancePeriod() {
        var start = String(state.form.period_start || '');
        var end = String(state.form.period_end || '');
        return /^\d{4}-\d{2}-\d{2}$/.test(start) && /^\d{4}-\d{2}-\d{2}$/.test(end) && start <= end;
    }

    // While editing, only tables the employee has actually started are flagged.
    // Attempting to submit flips this on so every outstanding item is shown.
    function visibleWarningGroups() {
        return (reviewWarningGroups || []).filter(function (group) {
            return showAllWarnings || group.started;
        });
    }

    function findWarningGroup(key) {
        var match = null;
        (reviewWarningGroups || []).forEach(function (group) {
            if (group.key === key) match = group;
        });
        return match;
    }

    function missingBadge(group) {
        var count = group.items.length;
        return '<button type="button" class="missing-badge js-missing-badge" data-key="' + escapeAttr(group.key) + '"'
            + ' title="See what is still missing here">'
            + '<i class="mdi mdi-alert-outline"></i>' + count + ' missing</button>';
    }

    // Each bucket is drawn on the table it belongs to: a KRA badge sits in that
    // KRA's own header, a competency badge in that group's header, and the
    // section-wide ones in the matching section head.
    function renderMissingBadges(groups) {
        $('.missing-badge').remove();
        $('.section-missing-slot').empty();

        groups.forEach(function (group) {
            var badge = missingBadge(group);
            if (group.scope === 'kra') {
                $('#kra-' + group.ref).find('.kra-missing-slot').first().html(badge);
            } else if (group.scope === 'competency') {
                $('.competency-group-card').filter(function () {
                    return String($(this).data('category')) === String(group.ref);
                }).find('.competency-missing-slot').first().html(badge);
            } else {
                $('#' + group.section).find('.section-missing-slot').first().html(badge);
            }
        });
    }

    function applyIncompleteHighlights() {
        $('.incomplete-item, .section-has-incomplete, .incomplete-focus-pulse')
            .removeClass('incomplete-item section-has-incomplete incomplete-focus-pulse');

        var groups = visibleWarningGroups();
        renderMissingBadges(groups);
        if (!groups.length) { return; }

        // Only outline fields belonging to a bucket that is currently shown, so an
        // untouched KRA keeps its blank cells clean.
        var showKra = {};
        var showCompetency = {};
        var showEmployee = false;
        var showDevelopment = false;
        groups.forEach(function (group) {
            if (group.scope === 'kra') { showKra[String(group.ref)] = true; }
            else if (group.scope === 'competency') { showCompetency[String(group.ref)] = true; }
            else if (group.scope === 'employee') { showEmployee = true; }
            else if (group.scope === 'development') { showDevelopment = true; }
        });

        function mark($element) {
            if ($element && $element.length) { $element.addClass('incomplete-item'); }
        }

        if (showEmployee) {
            if (!hasValue(state.form.employee_id) || !hasValue(state.form.employee_name)) {
                mark($('#employeeSection .employee-pdf-column').first().find('.employee-pdf-row').slice(0, 2));
            }
            if (!hasValue(state.form.rater_name)) { mark($('#raterAssignmentRow')); }
            if (!hasValue(state.form.approving_authority_name)) { mark($('#authorityAssignmentRow')); }
            if (!validPerformancePeriod()) { mark($('#periodAssignmentRow')); }
        }

        (state.kras || []).forEach(function (kra, kraIndex) {
            if (!showKra[String(kraIndex)]) { return; }
            var $kra = $('#kra-' + kraIndex);
            if (!hasValue(kra.title)) { mark($kra.children('summary')); }
            if (!(kra.objectives || []).length) { mark($kra); }

            (kra.objectives || []).forEach(function (objective, objectiveIndex) {
                var $cells = $kra.find('.objective-row[data-objective="' + objectiveIndex + '"]').children('div');
                if (!hasValue(objective.objective)) { mark($cells.eq(1)); }
                if (!hasValue(objective.timeline)) { mark($cells.eq(2)); }
                // Only a blank weight is outlined. An off-target total is reported
                // in the KRA section badge instead of lighting up every weight cell.
                if (Number(objective.weight || 0) <= 0) { mark($cells.eq(3)); }
                if (standardCompletion(objective, 'quality') < 5) { mark($cells.eq(4)); }
                if (standardCompletion(objective, 'efficiency') < 5) { mark($cells.eq(5)); }
                if (standardCompletion(objective, 'timeliness') < 5) { mark($cells.eq(6)); }
                if (!hasValue(objective.accomplishment)) { mark($cells.eq(7)); }
                if (scope === 'rater') {
                    if (Number(objective.quality_rating || 0) < 1) { mark($cells.eq(8)); }
                    if (Number(objective.efficiency_rating || 0) < 1) { mark($cells.eq(9)); }
                    if (Number(objective.timeliness_rating || 0) < 1) { mark($cells.eq(10)); }
                }
            });
        });

        $('.competency-group-card').each(function () {
            var $group = $(this);
            if (!showCompetency[String($group.data('category'))]) { return; }
            $group.find('.competency-table-row').each(function () {
                var $row = $(this);
                var competency = state.competencies[Number($row.data('competency'))] || {};
                var $cells = $row.children('div');
                if (!hasValue(competency.name)) { mark($cells.eq(0)); }
                if (!(competency.indicators || []).length) { mark($cells.eq(1)); }
                if (Number(competency.rating || 0) < 1) { mark($cells.eq(2)); }
            });
        });

        if (showDevelopment) {
            if (!(state.development || []).length) {
                mark($('#developmentContainer td').first());
            } else {
                (state.development || []).forEach(function (plan, planIndex) {
                    var $cells = $('#developmentContainer tr').eq(planIndex).children('td');
                    ['strengths', 'improvement_needs', 'learning_objectives', 'interventions', 'target_timeline', 'responsible_person'].forEach(function (field, fieldIndex) {
                        if (!hasValue(plan[field])) { mark($cells.eq(fieldIndex)); }
                    });
                });
            }
        }

        $('.ipcrf-section').each(function () {
            var $section = $(this);
            if ($section.find('.incomplete-item').length) { $section.addClass('section-has-incomplete'); }
        });
    }

    // Scoped to one bucket when a key is given, otherwise the first thing outstanding.
    function focusFirstIncomplete(key) {
        var group = key ? findWarningGroup(key) : null;
        var $scope = $(document);
        if (group && group.scope === 'kra') {
            $scope = $('#kra-' + group.ref);
        } else if (group && group.scope === 'competency') {
            $scope = $('.competency-group-card').filter(function () {
                return String($(this).data('category')) === String(group.ref);
            });
        } else if (group) {
            $scope = $('#' + group.section);
        }
        var $first = $scope.find('.incomplete-item').first();
        if (!$first.length && $scope.length) { $first = $scope.first(); }
        if (!$first.length) { $first = $('.incomplete-item').first(); }
        if (!$first.length) { return; }
        var $details = $first.closest('details');
        if ($details.length) {
            $details.prop('open', true);
            syncDetailsToggle($details.get(0));
        }
        $first.addClass('incomplete-focus-pulse');
        $first.get(0).scrollIntoView({ behavior: 'smooth', block: 'center' });
        setTimeout(function () { $first.removeClass('incomplete-focus-pulse'); }, 2400);
    }

    function renderMissingItems() {
        var groups = visibleWarningGroups();
        var count = 0;
        groups.forEach(function (group) { count += group.items.length; });
        $('#missingItemsCount').text(count);
        $('#missingItemsBtn').prop('hidden', count === 0).toggle(count > 0);
        applyIncompleteHighlights();
    }

    function showMissingItems(key) {
        var group = key ? findWarningGroup(key) : null;
        var groups = group ? [group] : visibleWarningGroups();
        if (!groups.length) { return; }

        var html;
        if (group) {
            html = '<ul class="submission-warning-list">'
                + group.items.map(function (item) { return '<li>' + escapeHtml(item) + '</li>'; }).join('')
                + '</ul>';
        } else {
            html = '<ul class="submission-warning-list">' + groups.map(function (entry) {
                return '<li><b>' + escapeHtml(entry.label) + '</b><ul>'
                    + entry.items.map(function (item) { return '<li>' + escapeHtml(item) + '</li>'; }).join('')
                    + '</ul></li>';
            }).join('') + '</ul>';
        }

        Swal.fire({
            icon: 'warning',
            title: group ? group.label : 'Still missing',
            html: '<p class="submission-warning-intro">Fill these in to finish '
                + (group ? 'this table' : 'the form') + '. Each one is outlined in orange.</p>' + html,
            confirmButtonText: 'Take me there',
            confirmButtonColor: '#b7791f'
        }).then(function (result) {
            if (result.value) { focusFirstIncomplete(key); }
        });
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
            html = actionButton('rater_approve', 'Complete Rater Review', 'mdi-clipboard-check-outline', 'btn-success') + actionButton('return_revision', 'Return Paper', 'mdi-file-undo-outline', 'btn-danger ml-2');
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
        renderTrackingHistory();
        renderMissingItems();
        renderWorkflow();
    }

    function markDirty() {
        if (scope === 'none') return;
        dirty = true;
        setSaveState('pending', 'Changes waiting to save');
        clearTimeout(saveTimer);
        saveTimer = setTimeout(function () { saveDraft(false); }, 1100);
    }

    function mergeServerIds(bundle) {
        if (!bundle) return;
        if (bundle.form) {
            state.form.rater_id = bundle.form.rater_id;
            state.form.rater_name = bundle.form.rater_name;
            state.form.rater_position = bundle.form.rater_position;
            state.form.approving_authority_id = bundle.form.approving_authority_id;
            state.form.approving_authority_name = bundle.form.approving_authority_name;
            state.form.approving_authority_position = bundle.form.approving_authority_position;
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
        var hadChanges = dirty;
        dirty = false;
        pendingSave = false;
        setSaveState('saving', 'Saving changes…');
        $('#saveDraftBtn').prop('disabled', true);
        activeSave = $.ajax({
            url: config.urls.save,
            method: 'POST',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            data: JSON.stringify(state)
        }).done(function (response) {
            mergeServerIds(response.bundle);
            reviewWarnings = response.review_warnings || [];
            reviewWarningGroups = response.review_warning_groups || [];
            renderMissingItems();
            if (pendingSave || dirty) {
                setSaveState('saving', 'Saving latest changes…');
            } else {
                setSaveState('saved', 'Saved ' + response.saved_at);
                if (hadChanges || showToast) showSavedNotice();
            }
        }).fail(function (xhr) {
            failed = true;
            dirty = true;
            setSaveState('error', 'Save failed — changes retained');
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

    function moveCompetency(index, direction) {
        var category = state.competencies[index].category;
        var indexes = [];
        state.competencies.forEach(function (competency, competencyIndex) {
            if (competency.category === category) indexes.push(competencyIndex);
        });
        var position = indexes.indexOf(index);
        var target = indexes[position + direction];
        if (typeof target === 'undefined') return;
        var current = state.competencies[index];
        state.competencies[index] = state.competencies[target];
        state.competencies[target] = current;
    }

    function defaultTimeline() {
        var year = state.form && state.form.period_start ? String(state.form.period_start).substring(0, 4) : String(new Date().getFullYear());
        return 'January to December ' + year;
    }

    function openKraEditor(index) {
        $('#kraEditorIndex').val(index);
        $('#kraEditorName').val('');
        $('#kraEditorTitle').text('Add New KRA');
        $('#kraEditorModal').modal('show');
        setTimeout(function () { $('#kraEditorName').trigger('focus'); }, 250);
    }

    function openObjectiveEditor(kraIndex, objectiveIndex) {
        var objective = newObjective();
        objective.code = (kraIndex + 1) + '.' + ((state.kras[kraIndex].objectives || []).length + 1);
        objective.timeline = defaultTimeline();
        $('#objectiveEditorKra').val(kraIndex);
        $('#objectiveEditorIndex').val(objectiveIndex);
        $('#objectiveEditorCode').val(objective.code || '');
        $('#objectiveEditorText').val(objective.objective || '');
        $('#objectiveEditorTimeline').val(objective.timeline || defaultTimeline());
        $('#objectiveEditorWeight').val(Number(objective.weight || 0));
        $('#objectiveKraLabel').text(state.kras[kraIndex].title || 'Objective');
        $('#objectiveEditorTitle').text('Add New Objective');
        $('#objectiveEditorModal').modal('show');
        setTimeout(function () { $('#objectiveEditorText').trigger('focus'); }, 250);
    }

    function openCompetencyEditor(category) {
        $('#competencyEditorCategory').val(category || 'Core Behavioral Competency');
        $('#competencyEditorName').val('');
        $('#competencyEditorIndicators').val('');
        $('#competencyEditorModal').modal('show');
        setTimeout(function () { $('#competencyEditorName').trigger('focus'); }, 250);
    }

    function openDevelopmentEditor() {
        $('#developmentEditorForm')[0].reset();
        $('#developmentEditorModal').modal('show');
        setTimeout(function () { $('#developmentStrengths').trigger('focus'); }, 250);
    }

    function openStandardsEditor(kraIndex, objectiveIndex, focusDimension) {
        var objective = state.kras[kraIndex].objectives[objectiveIndex];
        $('#standardsEditorKra').val(kraIndex);
        $('#standardsEditorObjective').val(objectiveIndex);
        $('#standardsObjectiveLabel').text((objective.code || (kraIndex + 1) + '.' + (objectiveIndex + 1)) + ' · ' + (objective.objective || 'Objective'));
        $('.js-standard-modal-input').each(function () {
            var dimension = String($(this).data('dimension'));
            var level = String($(this).data('level'));
            $(this).val(objective[dimension] && objective[dimension][level] ? objective[dimension][level] : '').prop('disabled', !isFull());
        });
        $('#standardsSaveBtn').toggle(isFull());
        $('#standardsCancelBtn').text(isFull() ? 'Cancel' : 'Close');
        $('#standardsEditorModal').removeClass('focus-quality focus-efficiency focus-timeliness').addClass('focus-' + focusDimension).modal('show');
        $('#standardsEditorModal').one('shown.bs.modal', function () {
            if (isFull()) $('.js-standard-modal-input[data-dimension="' + focusDimension + '"][data-level="5"]').trigger('focus');
        });
    }

    function updateStandardsCells(kraIndex, objectiveIndex) {
        var objective = state.kras[kraIndex].objectives[objectiveIndex];
        var $row = $('.objective-row[data-kra="' + kraIndex + '"][data-objective="' + objectiveIndex + '"]');
        ['quality', 'efficiency', 'timeliness'].forEach(function (dimension) {
            var completed = standardCompletion(objective, dimension);
            var $button = $row.find('[data-action="open-standards"][data-dimension="' + dimension + '"]');
            $button.removeClass('complete partial empty').addClass(completed === 5 ? 'complete' : (completed > 0 ? 'partial' : 'empty'));
            $button.find('.standard-count').text(completed + ' / 5 complete');
        });
    }

    function updateObjectiveScore(kraIndex, objectiveIndex) {
        var objective = state.kras[kraIndex].objectives[objectiveIndex];
        var average = objectiveAverage(objective);
        var approved = ratingsAreApproved();
        var weighted = approved ? average * Number(objective.weight || 0) / 100 : 0;
        var $row = $('.objective-row[data-kra="' + kraIndex + '"][data-objective="' + objectiveIndex + '"]');
        $row.find('.js-average-score').text(average > 0 ? average.toFixed(2) : '—');
        $row.find('.js-weighted-score').text(weighted.toFixed(3));
        $row.find('.js-score-caption').html((approved ? 'Weighted ' : '') + '<b class="js-weighted-score">' + weighted.toFixed(3) + '</b>');
    }

    function focusObjective(kraIndex, objectiveIndex) {
        setTimeout(function () {
            var kra = document.getElementById('kra-' + kraIndex);
            if (!kra) return;
            kra.open = true;
            var objectives = kra.querySelectorAll('.objective-row');
            if (objectives[objectiveIndex]) {
                objectives[objectiveIndex].classList.add('objective-row-highlight');
                objectives[objectiveIndex].scrollIntoView({ behavior: 'smooth', block: 'center' });
                setTimeout(function () { objectives[objectiveIndex].classList.remove('objective-row-highlight'); }, 1800);
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

    function syncDetailsToggle(details) {
        var isOpen = Boolean(details.open);
        var $button = $(details).children('summary').find('.js-details-toggle').first();
        $button.attr('aria-expanded', isOpen ? 'true' : 'false')
            .attr('title', isOpen ? 'Fold this table' : 'Open this table');
        $button.find('.js-details-toggle-label').text(isOpen ? 'Fold Table' : 'Open Table');
        $button.find('i').removeClass('mdi-chevron-up mdi-chevron-down').addClass(isOpen ? 'mdi-chevron-up' : 'mdi-chevron-down');
    }

    function handleAction(action, $button) {
        var k = Number($button.data('kra'));
        var o = Number($button.data('objective'));
        var c = Number($button.data('competency'));
        var p = Number($button.data('plan'));
        if (action === 'add-objective') {
            openObjectiveEditor(k, -1);
            return;
        } else if (action === 'add-competency') {
            openCompetencyEditor(String($button.data('category') || 'Core Behavioral Competency'));
            return;
        } else if (action === 'open-standards') {
            openStandardsEditor(k, o, String($button.data('dimension')));
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
            kraCopy.template_kra_id = 0; // A copy is the member's own KRA, not a tagged one.
            kraCopy.title += ' (Copy)';
            kraCopy.objectives.forEach(function (objective) { objective.id = 0; objective.evidence = []; });
            state.kras.splice(k + 1, 0, kraCopy);
        } else if (action === 'delete-kra') state.kras.splice(k, 1);
        else if (action === 'kra-up') moveItem(state.kras, k, k - 1);
        else if (action === 'kra-down') moveItem(state.kras, k, k + 1);
        else if (action === 'duplicate-competency') {
            var competencyCopy = JSON.parse(JSON.stringify(state.competencies[c]));
            competencyCopy.id = 0;
            state.competencies.splice(c + 1, 0, competencyCopy);
        }
        else if (action === 'competency-up') moveCompetency(c, -1);
        else if (action === 'competency-down') moveCompetency(c, 1);
        else if (action === 'competency-category') state.competencies[c].category = String($button.data('category'));
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

        $('#editorApprovingAuthority').select2(employeeSelectOptions('Search approving authority')).on('select2:select', function (event) {
            var employee = event.params.data.employee;
            state.form.approving_authority_id = employee.id;
            state.form.approving_authority_name = employee.name;
            state.form.approving_authority_position = employee.position;
            $('#approvingAuthorityPositionDisplay').text(employee.position || '—');
            markDirty();
        }).on('select2:clear', function () {
            state.form.approving_authority_id = '';
            state.form.approving_authority_name = '';
            state.form.approving_authority_position = '';
            $('#approvingAuthorityPositionDisplay').text('—');
            markDirty();
        });

        $(document).on('click', '.js-action', function (event) {
            event.preventDefault();
            event.stopPropagation();
            var $button = $(this);
            var action = $button.data('action');
            if (String(action).indexOf('delete-') === 0) {
                var itemLabel = action === 'delete-kra' ? 'this KRA and all of its objectives' : (action === 'delete-objective' ? 'this objective' : 'this item');
                Swal.fire({ icon: 'warning', title: 'Delete ' + itemLabel + '?', text: 'This change will be saved to the IPCRF.', showCancelButton: true, confirmButtonText: 'Yes, delete it', confirmButtonColor: '#c44949' }).then(function (result) {
                    if (result.value) handleAction(action, $button);
                });
                return;
            }
            handleAction(action, $button);
        });
        $(document).on('click', '.js-details-toggle', function (event) {
            event.preventDefault();
            event.stopPropagation();
            var details = $(this).closest('details')[0];
            if (details) details.open = !details.open;
        });
        document.addEventListener('toggle', function (event) {
            if ($(event.target).is('.kra-card, .competency-group-card')) syncDetailsToggle(event.target);
        }, true);
        $(document).on('click', '.js-kra-title, .js-row-menu-toggle', function (event) { event.stopPropagation(); });
        $(document).on('keydown', '.js-kra-title, .js-objective-inline.single-line, .js-competency-inline.single-line', function (event) {
            if (event.key === 'Enter') { event.preventDefault(); this.blur(); }
        });
        $(document).on('input', '.js-kra-title', function () {
            state.kras[Number($(this).data('kra'))].title = String(this.innerText || this.textContent || '').replace(/\u00a0/g, ' ');
            markDirty();
        });
        $(document).on('input', '.js-objective-inline', function () {
            var kraIndex = Number($(this).data('kra'));
            var objectiveIndex = Number($(this).data('objective'));
            var field = String($(this).data('field'));
            var value = String(this.innerText || this.textContent || '').replace(/\u00a0/g, ' ');
            state.kras[kraIndex].objectives[objectiveIndex][field] = field === 'weight' ? Math.max(0, Math.min(100, Number(value.replace(/[^0-9.]/g, '')) || 0)) : value;
            if (field === 'weight') {
                updateObjectiveScore(kraIndex, objectiveIndex);
                var kraWeight = (state.kras[kraIndex].objectives || []).reduce(function (sum, objective) { return sum + Number(objective.weight || 0); }, 0);
                $('#kra-' + kraIndex + ' .kra-meta').first().text((state.kras[kraIndex].objectives || []).length + ' objective(s) · ' + kraWeight.toFixed(2) + '%');
            }
            markDirty();
        });
        $(document).on('blur', '.js-objective-inline[data-field="weight"]', function () {
            var objective = state.kras[Number($(this).data('kra'))].objectives[Number($(this).data('objective'))];
            $(this).text(Number(objective.weight || 0).toFixed(2));
        });
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
            var kraIndex = Number($wrap.data('kra'));
            var objectiveIndex = Number($wrap.data('objective'));
            state.kras[kraIndex].objectives[objectiveIndex][$wrap.data('field')] = Number(this.value);
            updateObjectiveScore(kraIndex, objectiveIndex);
            markDirty();
        });
        $(document).on('input', '.js-competency-inline', function () {
            var competency = state.competencies[Number($(this).data('competency'))];
            var field = String($(this).data('field'));
            var value = String(this.innerText || this.textContent || '').replace(/\u00a0/g, ' ');
            competency[field] = field === 'indicators' ? value.split(/\r?\n/) : value;
            markDirty();
        });
        $(document).on('change', '.js-competency-rating-wrap select', function () {
            var $wrap = $(this).closest('.js-competency-rating-wrap');
            state.competencies[Number($wrap.data('competency'))][$wrap.data('field')] = Number(this.value);
            markDirty();
        });
        $(document).on('input', '.js-plan-field', function () { state.development[Number($(this).data('plan'))][$(this).data('field')] = this.value; markDirty(); });
        $(document).on('change', '.js-header-field', function () { state.form[$(this).data('field')] = this.value; markDirty(); });

        $('#addKraBtn').on('click', function () { openKraEditor(-1); });
        $('#addCompetencyBtn').on('click', function () { openCompetencyEditor('Core Behavioral Competency'); });
        $('#addPlanBtn').on('click', openDevelopmentEditor);
        $('#saveDraftBtn').on('click', function () { saveDraft(true); });
        $('#missingItemsBtn').on('click', function () {
            saveDraft(false).then(function () { showMissingItems(); });
        });
        // A badge is bound to one table, so it opens only that table's list.
        $(document).on('click', '.js-missing-badge', function (event) {
            event.preventDefault();
            event.stopPropagation();
            var key = $(this).data('key');
            saveDraft(false).then(function () { showMissingItems(key); });
        });

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
                toastr.success(isNew ? 'Objective added. Use the Q, E or T standards cells and enter the actual result in its row.' : 'Objective details updated.');
            });
        });

        $('#standardsEditorForm').on('submit', function (event) {
            event.preventDefault();
            if (!isFull()) {
                $('#standardsEditorModal').modal('hide');
                return;
            }
            var kraIndex = Number($('#standardsEditorKra').val());
            var objectiveIndex = Number($('#standardsEditorObjective').val());
            var objective = state.kras[kraIndex].objectives[objectiveIndex];
            $('.js-standard-modal-input').each(function () {
                var dimension = String($(this).data('dimension'));
                var level = String($(this).data('level'));
                objective[dimension] = objective[dimension] || emptyStandards();
                objective[dimension][level] = this.value;
            });
            hideModalThen($('#standardsEditorModal'), function () {
                updateStandardsCells(kraIndex, objectiveIndex);
                markDirty();
                toastr.success('Quality, Efficiency and Timeliness standards updated.');
            });
        });

        $('#competencyEditorForm').on('submit', function (event) {
            event.preventDefault();
            var name = String($('#competencyEditorName').val() || '').trim();
            var indicators = String($('#competencyEditorIndicators').val() || '').split(/\r?\n/).map(function (indicator) { return indicator.trim(); }).filter(Boolean);
            if (!name) { $('#competencyEditorName').trigger('focus'); return; }
            if (!indicators.length) { $('#competencyEditorIndicators').trigger('focus'); return; }
            var competency = newCompetency();
            competency.category = String($('#competencyEditorCategory').val() || 'Core Behavioral Competency');
            competency.name = name;
            competency.indicators = indicators;
            var competencyIndex = state.competencies.length;
            state.competencies.push(competency);
            hideModalThen($('#competencyEditorModal'), function () {
                structuralChange();
                setTimeout(function () {
                    var row = document.querySelector('.competency-table-row[data-competency="' + competencyIndex + '"]');
                    if (row) {
                        // Competency groups render folded, so open the one holding the new row.
                        var $group = $(row).closest('details');
                        if ($group.length) {
                            $group.prop('open', true);
                            syncDetailsToggle($group.get(0));
                        }
                        row.classList.add('competency-row-highlight');
                        row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        setTimeout(function () { row.classList.remove('competency-row-highlight'); }, 1800);
                    }
                }, 180);
                toastr.success('Competency added. Choose its Rating when ready.');
            });
        });

        $('#developmentEditorForm').on('submit', function (event) {
            event.preventDefault();
            var plan = newPlan();
            plan.strengths = String($('#developmentStrengths').val() || '').trim();
            plan.improvement_needs = String($('#developmentNeeds').val() || '').trim();
            plan.learning_objectives = String($('#developmentObjectives').val() || '').trim();
            plan.interventions = String($('#developmentInterventions').val() || '').trim();
            plan.target_timeline = String($('#developmentTimeline').val() || '').trim();
            plan.responsible_person = String($('#developmentResponsible').val() || '').trim();
            plan.status_remarks = String($('#developmentRemarks').val() || '').trim();
            var hasContent = Object.keys(plan).some(function (field) { return field !== 'id' && String(plan[field] || '').trim(); });
            if (!hasContent) {
                $('#developmentStrengths').trigger('focus');
                toastr.warning('Enter at least one Development Plan detail before adding the entry.');
                return;
            }
            state.development.push(plan);
            hideModalThen($('#developmentEditorModal'), function () {
                structuralChange();
                document.getElementById('developmentSection').scrollIntoView({ behavior: 'smooth', block: 'center' });
                toastr.success('Development Plan entry added. You can continue editing it in the table.');
            });
        });

        $('#loadPresetBtn').on('click', function () {
            var options = {};
            (config.templates || []).forEach(function (template) { options[template.id] = template.name + ' (' + template.year + ')'; });
            Swal.fire({ title: 'Load IPCRF preset', text: 'The preset is copied into this employee form and replaces its current KRAs and competencies.', input: 'select', inputOptions: options, showCancelButton: true, confirmButtonText: 'Load Preset', confirmButtonColor: '#3157c8' }).then(function (result) {
                if (!result.value) return;
                $.post(config.urls.loadPreset, { template_id: result.value }, null, 'json').done(function (response) {
                    state = response.bundle; reviewWarnings = response.review_warnings || []; reviewWarningGroups = response.review_warning_groups || []; renderAll(); toastr.success(response.message);
                }).fail(function (xhr) { showError(xhr, 'The preset could not be loaded.'); });
            });
        });

        $(document).on('click', '.js-workflow', function () {
            var action = $(this).data('action');
            var needsRemarks = action === 'return_revision' || action === 'reopen';
            var labels = { submit_rater: 'Submit this IPCRF to the assigned rater?', rater_approve: 'Complete the rater review for this IPCRF?', submit_pmt: 'Submit this reviewed IPCRF to the PMT?', pmt_validate: 'Validate this IPCRF as PMT?', lock: 'Lock this validated IPCRF?', return_revision: 'Return IPCRF Paper to Employee', reopen: 'Reopen this IPCRF for revision?' };
            function sendWorkflow(remarks, confirmWarnings) {
                $.post(config.urls.workflow, { action: action, remarks: remarks, confirm_warnings: confirmWarnings ? 1 : 0 }, null, 'json').done(function (response) {
                    toastr.success(response.message); window.location.href = response.reload;
                }).fail(function (xhr) {
                    var response = xhr && xhr.responseJSON ? xhr.responseJSON : {};
                    if ((action === 'submit_rater' || action === 'rater_approve') && response.warning_only) {
                        reviewWarnings = response.errors || [];
                        reviewWarningGroups = response.error_groups || [];
                        // Submitting reveals every outstanding table, including the
                        // ones the employee has not touched yet.
                        showAllWarnings = true;
                        renderMissingItems();
                        var warningItems = (response.error_groups || []).map(function (group) {
                            return '<li><b>' + escapeHtml(group.label) + '</b><ul>' + group.items.map(function (warning) {
                                return '<li>' + escapeHtml(warning) + '</li>';
                            }).join('') + '</ul></li>';
                        }).join('');
                        var completingReview = action === 'rater_approve';
                        Swal.fire({
                            icon: 'warning',
                            title: completingReview ? 'Complete review with blank fields?' : 'Some fields are still blank',
                            html: '<p class="submission-warning-intro">' + (completingReview ? 'You can fill in the fields outlined in orange, use Return Paper with clear remarks, or complete the rater review anyway.' : 'You can fill in the fields outlined in orange, or submit the IPCRF to the rater anyway.') + '</p><ul class="submission-warning-list">' + warningItems + '</ul>',
                            showCancelButton: true,
                            confirmButtonText: completingReview ? 'Complete Anyway' : 'Submit Anyway',
                            cancelButtonText: completingReview ? 'Review / Return' : 'Review Form',
                            confirmButtonColor: '#b7791f',
                            focusCancel: true
                        }).then(function (warningResult) {
                            if (warningResult.value) {
                                sendWorkflow(remarks, true);
                            } else {
                                focusFirstIncomplete();
                            }
                        });
                        return;
                    }
                    showError(xhr, 'The workflow status could not be changed.');
                });
            }
            var returningPaper = action === 'return_revision';
            var confirmText = action === 'submit_rater' ? 'Check and Submit' : (action === 'rater_approve' ? 'Complete Review' : (returningPaper ? 'Return Paper' : 'Continue'));
            Swal.fire({
                icon: returningPaper ? 'warning' : undefined,
                title: labels[action],
                text: returningPaper ? 'Explain what is missing or needs correction. The employee will see these remarks above the editable form.' : undefined,
                input: needsRemarks ? 'textarea' : undefined,
                inputPlaceholder: returningPaper ? 'Required: describe the scores, fields, evidence, or other information the employee must complete.' : (needsRemarks ? 'Required remarks / reason' : undefined),
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: returningPaper ? 'Keep Reviewing' : 'Cancel',
                confirmButtonColor: returningPaper ? '#b34b4b' : '#3157c8',
                inputValidator: needsRemarks ? function (value) { return value && value.trim() ? undefined : 'Remarks are required so the employee knows what to revise.'; } : undefined
            }).then(function (result) {
                if (!result.value && result.dismiss) return;
                var proceed = scope === 'none' ? $.Deferred().resolve().promise() : saveDraft(false);
                proceed.then(function () {
                    sendWorkflow(needsRemarks ? String(result.value || '').trim() : '', false);
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
        toastr.options = { closeButton: false, newestOnTop: true, preventDuplicates: false, progressBar: false, positionClass: 'toast-bottom-right', timeOut: 1900, extendedTimeOut: 500 };
        if (!config.hasForm) { initLanding(); return; }
        state.kras = state.kras || [];
        state.competencies = state.competencies || [];
        state.development = state.development || [];
        state.history = state.history || [];
        setSaveState('saved', 'All changes saved');
        renderAll();
        bindEditorEvents();
    });
})(jQuery);
