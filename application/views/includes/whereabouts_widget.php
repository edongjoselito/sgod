<?php
/**
 * Shared Whereabouts widget.
 *
 * Drop-in include that lets ANY logged-in user view their own whereabouts
 * calendar and add activities straight from their dashboard. It is fully
 * self-contained: it fetches its own data, ships its own scoped styles, and
 * loads FullCalendar on demand so it works on dashboards that don't already
 * load it. Saving goes through the existing Page/whereabouts_ajax endpoint,
 * which stores against the current session user.
 */

if (!defined('WB_WIDGET_RENDERED')) {
    define('WB_WIDGET_RENDERED', TRUE);

    // Ensure the storage table exists before we query it.
    if (method_exists($this, 'auto_migrate_whereabouts_table')) {
        $this->auto_migrate_whereabouts_table();
    }

    $wbUsername = $this->session->userdata('username');
    $wbWhereabouts = $wbUsername ? $this->SGODModel->get_user_whereabouts($wbUsername) : array();

    $wbManilaNow = new DateTime('now', new DateTimeZone('Asia/Manila'));
    $wbManilaToday = $wbManilaNow->format('Y-m-d');

    $wbStatusStyles = array(
        'In Office' => array('accent' => '#2563eb', 'tint' => 'rgba(37, 99, 235, 0.14)'),
        'Out of Office' => array('accent' => '#0f766e', 'tint' => 'rgba(15, 118, 110, 0.14)'),
        'On Official Business' => array('accent' => '#7c3aed', 'tint' => 'rgba(124, 58, 237, 0.14)'),
        'On Leave' => array('accent' => '#ea580c', 'tint' => 'rgba(234, 88, 12, 0.14)'),
        'On Field Work' => array('accent' => '#047857', 'tint' => 'rgba(4, 120, 87, 0.14)'),
        'default' => array('accent' => '#475569', 'tint' => 'rgba(71, 85, 105, 0.14)')
    );

    $wbEntriesByDate = array();
    if (!empty($wbWhereabouts)) {
        foreach ($wbWhereabouts as $where) {
            if (empty($where->date)) {
                continue;
            }
            $dateKey = (string) $where->date;
            if (!isset($wbEntriesByDate[$dateKey])) {
                $wbEntriesByDate[$dateKey] = array();
            }
            $wbEntriesByDate[$dateKey][] = array(
                'status' => isset($where->status) ? (string) $where->status : '',
                'location' => isset($where->location) ? (string) $where->location : '',
                'activity' => isset($where->activity) ? (string) $where->activity : '',
                'notes' => isset($where->notes) ? (string) $where->notes : ''
            );
        }
    }

    $wbJsonFlags = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP;
    $wbStatusStylesJson = json_encode($wbStatusStyles, $wbJsonFlags);
    $wbEntriesJson = json_encode($wbEntriesByDate, $wbJsonFlags);
?>
<style>
    .wb-widget {
        --user-navy: #272b8c;
        --user-blue: #3c40c6;
        --user-teal: #565de8;
        --user-ink: #23275d;
        --user-muted: #7b7fa7;
        margin-top: 24px;
        margin-bottom: 24px;
    }
    .wb-widget .panel-card {
        border: 1px solid rgba(60, 64, 198, 0.08);
        border-radius: 22px;
        background: #ffffff;
        padding: 28px;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
    }
    .wb-widget .panel-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(60, 64, 198, 0.08);
        color: var(--user-blue);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 12px;
    }
    .wb-widget .panel-title {
        color: var(--user-ink);
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .wb-widget .panel-copy {
        color: var(--user-muted);
        margin-bottom: 24px;
    }
    .wb-widget .calendar-app-shell {
        display: grid;
        grid-template-columns: minmax(0, 1.7fr) minmax(300px, 0.95fr);
        gap: 24px;
        align-items: start;
    }
    .wb-widget .calendar-main {
        border: 1px solid rgba(60, 64, 198, 0.08);
        border-radius: 22px;
        background: linear-gradient(180deg, #ffffff 0%, #fbfcff 100%);
        padding: 20px;
    }
    .wb-widget .calendar-main-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }
    .wb-widget .calendar-legend {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .wb-widget .calendar-legend-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(60, 64, 198, 0.06);
        color: var(--user-ink);
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: 0.02em;
    }
    .wb-widget .calendar-legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 999px;
        flex-shrink: 0;
    }
    .wb-widget .whereabouts-calendar .fc-toolbar {
        margin-bottom: 18px;
    }
    .wb-widget .whereabouts-calendar .fc-toolbar h2 {
        color: var(--user-ink);
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: -0.02em;
    }
    .wb-widget .whereabouts-calendar .fc-button {
        border: none;
        border-radius: 12px;
        background: rgba(60, 64, 198, 0.08);
        color: var(--user-blue);
        box-shadow: none;
        text-transform: none;
        font-weight: 700;
        padding: 0.5rem 0.9rem;
        height: auto;
    }
    .wb-widget .whereabouts-calendar .fc-button:hover,
    .wb-widget .whereabouts-calendar .fc-button:focus,
    .wb-widget .whereabouts-calendar .fc-button.fc-state-hover {
        background: rgba(60, 64, 198, 0.14);
        color: var(--user-blue);
        box-shadow: none;
    }
    .wb-widget .whereabouts-calendar .fc-button.fc-state-active,
    .wb-widget .whereabouts-calendar .fc-button.fc-state-down {
        background: linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
        color: #ffffff;
        box-shadow: 0 14px 28px rgba(60, 64, 198, 0.16);
    }
    .wb-widget .whereabouts-calendar .fc-button .fc-icon {
        color: inherit;
    }
    .wb-widget .whereabouts-calendar .fc-head-container,
    .wb-widget .whereabouts-calendar .fc-row,
    .wb-widget .whereabouts-calendar td,
    .wb-widget .whereabouts-calendar th {
        border-color: rgba(60, 64, 198, 0.08);
    }
    .wb-widget .whereabouts-calendar .fc-head th {
        padding: 14px 6px;
        color: var(--user-muted);
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        background: #f9fbff;
    }
    .wb-widget .whereabouts-calendar .fc-day-top {
        padding: 8px 10px 0;
    }
    .wb-widget .whereabouts-calendar .fc-day-number {
        color: var(--user-ink);
        font-weight: 700;
        font-size: 0.9rem;
    }
    .wb-widget .whereabouts-calendar .fc-day.fc-day-today {
        background: rgba(60, 64, 198, 0.06);
    }
    .wb-widget .whereabouts-calendar .fc-day.fc-day-selected {
        background: rgba(39, 43, 140, 0.08);
        box-shadow: inset 0 0 0 2px rgba(39, 43, 140, 0.16);
    }
    .wb-widget .whereabouts-calendar .fc-day-grid-event {
        margin: 2px 6px 0;
        border: none;
        border-radius: 10px;
        padding: 3px 8px;
        box-shadow: 0 10px 18px rgba(15, 23, 42, 0.08);
    }
    .wb-widget .whereabouts-calendar .fc-event,
    .wb-widget .whereabouts-calendar .fc-event:hover {
        color: #ffffff;
        text-decoration: none;
    }
    .wb-widget .whereabouts-calendar .fc-title {
        font-size: 0.78rem;
        font-weight: 700;
        line-height: 1.35;
        white-space: normal;
    }
    .wb-widget .whereabouts-calendar .fc-more {
        color: var(--user-blue);
        font-size: 0.76rem;
        font-weight: 700;
        margin-left: 7px;
    }
    .wb-widget .calendar-agenda-card {
        border: 1px solid rgba(60, 64, 198, 0.08);
        border-radius: 22px;
        background: linear-gradient(180deg, #fbfcff 0%, #f4f7ff 100%);
        padding: 22px;
        position: sticky;
        top: 24px;
    }
    .wb-widget .calendar-agenda-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 16px;
    }
    .wb-widget .calendar-agenda-title {
        color: var(--user-ink);
        font-size: 1.1rem;
        font-weight: 800;
        line-height: 1.3;
        margin: 6px 0 4px;
    }
    .wb-widget .calendar-agenda-copy {
        color: var(--user-muted);
        font-size: 0.88rem;
        line-height: 1.5;
        margin: 0;
    }
    .wb-widget .calendar-agenda-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border: 1px solid rgba(60, 64, 198, 0.12);
        border-radius: 14px;
        background: rgba(60, 64, 198, 0.08);
        color: var(--user-blue);
        font-size: 0.84rem;
        font-weight: 800;
        white-space: nowrap;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }
    .wb-widget .calendar-agenda-add:hover,
    .wb-widget .calendar-agenda-add:focus {
        transform: translateY(-1px);
        background: rgba(60, 64, 198, 0.14);
        box-shadow: 0 10px 20px rgba(60, 64, 198, 0.12);
        outline: none;
    }
    .wb-widget .calendar-agenda-empty {
        margin: 0;
        color: var(--user-muted);
        font-size: 0.9rem;
        line-height: 1.6;
    }
    .wb-widget .activity-preview-label {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--user-blue);
        margin-bottom: 10px;
    }
    .wb-widget .activity-preview-date {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }
    .wb-widget .activity-preview-date strong {
        color: var(--user-ink);
        font-size: 1rem;
    }
    .wb-widget .activity-preview-count {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 999px;
        background: rgba(60, 64, 198, 0.08);
        color: var(--user-blue);
        font-size: 0.75rem;
        font-weight: 700;
    }
    .wb-widget .activity-preview-entry {
        padding: 14px;
        border-radius: 14px;
        border: 1px solid rgba(60, 64, 198, 0.08);
        background: #ffffff;
    }
    .wb-widget .activity-preview-entry + .activity-preview-entry {
        margin-top: 10px;
    }
    .wb-widget .activity-preview-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 10px;
    }
    .wb-widget .activity-preview-status {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        line-height: 1;
    }
    .wb-widget .activity-preview-location {
        color: var(--user-ink);
        font-size: 0.9rem;
        font-weight: 700;
        margin-bottom: 6px;
    }
    .wb-widget .activity-preview-activity {
        color: #42526b;
        font-size: 0.9rem;
        line-height: 1.5;
    }
    .wb-widget .activity-preview-notes {
        margin-top: 8px;
        color: var(--user-muted);
        font-size: 0.82rem;
        line-height: 1.45;
    }
    .wb-widget .calendar-event-hint {
        margin: 6px 0 0;
        color: var(--user-muted);
        font-size: 0.88rem;
        line-height: 1.5;
    }
    .wb-widget .calendar-event-stack {
        display: grid;
        gap: 14px;
    }
    .wb-widget .calendar-event-card {
        padding: 18px;
        border-radius: 18px;
        border: 1px solid rgba(60, 64, 198, 0.1);
        background: linear-gradient(180deg, #fbfcff 0%, #f5f8ff 100%);
    }
    .wb-widget .calendar-event-card-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 16px;
    }
    .wb-widget .calendar-event-card-title {
        margin: 0;
        color: var(--user-ink);
        font-size: 0.98rem;
        font-weight: 700;
    }
    .wb-widget .calendar-event-card-copy {
        margin: 4px 0 0;
        color: var(--user-muted);
        font-size: 0.82rem;
        line-height: 1.45;
    }
    .wb-widget .calendar-event-remove,
    .wb-widget .calendar-event-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.88rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .wb-widget .calendar-event-remove {
        padding: 9px 12px;
        border: 1px solid rgba(239, 68, 68, 0.18);
        background: rgba(239, 68, 68, 0.08);
        color: #dc2626;
    }
    .wb-widget .calendar-event-remove:hover:not(:disabled),
    .wb-widget .calendar-event-add:hover {
        transform: translateY(-1px);
    }
    .wb-widget .calendar-event-remove:disabled {
        opacity: 0.45;
        cursor: not-allowed;
        transform: none;
    }
    .wb-widget .calendar-event-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }
    .wb-widget .calendar-event-field--full {
        grid-column: 1 / -1;
    }
    .wb-widget .calendar-event-label {
        display: block;
        margin-bottom: 8px;
        color: var(--user-ink);
        font-size: 0.88rem;
        font-weight: 700;
    }
    .wb-widget .calendar-event-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid rgba(60, 64, 198, 0.12);
        border-radius: 12px;
        font-size: 0.95rem;
        background: #ffffff;
        color: var(--user-ink);
    }
    .wb-widget .calendar-event-input:focus {
        outline: none;
        border-color: rgba(60, 64, 198, 0.35);
        box-shadow: 0 0 0 3px rgba(60, 64, 198, 0.08);
    }
    .wb-widget .calendar-event-textarea {
        min-height: 92px;
        resize: vertical;
    }
    .wb-widget .calendar-event-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 16px;
    }
    .wb-widget .calendar-event-add {
        padding: 11px 16px;
        border: 1px solid rgba(60, 64, 198, 0.14);
        background: rgba(60, 64, 198, 0.08);
        color: var(--user-blue);
    }
    .wb-widget .calendar-event-summary {
        color: var(--user-muted);
        font-size: 0.84rem;
        font-weight: 600;
    }
    @media (max-width: 991.98px) {
        .wb-widget .calendar-app-shell {
            grid-template-columns: minmax(0, 1fr);
        }
        .wb-widget .calendar-agenda-card {
            position: static;
        }
    }
    @media (max-width: 767.98px) {
        .wb-widget .calendar-event-grid {
            grid-template-columns: minmax(0, 1fr);
        }
        .wb-widget .calendar-event-card-head,
        .wb-widget .calendar-event-toolbar {
            align-items: stretch;
            flex-direction: column;
        }
    }
</style>

<div class="container-fluid wb-widget">
    <div class="row">
        <div class="col-12">
            <div class="panel-card">
                <div class="panel-kicker">Whereabouts</div>
                <h4 class="panel-title">Update Your Whereabouts</h4>
                <p class="panel-copy">Let others know where you are and what you're working on. Your activities appear on your dashboard and on the office whereabouts board.</p>

                <div class="calendar-app-shell">
                    <div class="calendar-main">
                        <div class="calendar-main-toolbar">
                            <div class="calendar-legend">
                                <span class="calendar-legend-chip"><span class="calendar-legend-dot" style="background:#2563eb;"></span>In Office</span>
                                <span class="calendar-legend-chip"><span class="calendar-legend-dot" style="background:#0f766e;"></span>Out of Office</span>
                                <span class="calendar-legend-chip"><span class="calendar-legend-dot" style="background:#7c3aed;"></span>Official Business</span>
                                <span class="calendar-legend-chip"><span class="calendar-legend-dot" style="background:#ea580c;"></span>On Leave</span>
                                <span class="calendar-legend-chip"><span class="calendar-legend-dot" style="background:#047857;"></span>Field Work</span>
                            </div>
                        </div>
                        <div id="whereaboutsCalendar" class="whereabouts-calendar"></div>
                    </div>

                    <div class="calendar-agenda-card">
                        <div class="calendar-agenda-head">
                            <div>
                                <div class="activity-preview-label">Selected Day</div>
                                <h5 class="calendar-agenda-title" id="selectedDateTitle"></h5>
                                <p class="calendar-agenda-copy" id="selectedDateMeta"></p>
                            </div>
                            <button type="button" class="calendar-agenda-add" id="openSelectedDateComposer">
                                <i class="mdi mdi-plus-circle-outline"></i>
                                Add Activity
                            </button>
                        </div>
                        <div id="calendarActivityPreviewBody">
                            <p class="calendar-agenda-empty">Choose a date from the calendar to view its schedule.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Whereabouts Modal -->
    <div id="whereaboutsModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 9999;">
        <div style="background: #ffffff; border-radius: 22px; padding: 28px; max-width: 760px; width: 92%; max-height: 88vh; overflow-y: auto; box-shadow: 0 24px 60px rgba(15, 23, 42, 0.2);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="color: var(--user-ink); font-weight: 700; margin: 0;">Plan Activities / Events</h3>
                <button onclick="wbCloseWhereaboutsModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--user-muted);">&times;</button>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="color: var(--user-ink); font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; display: block;">Selected Dates</label>
                <ul id="selectedDatesList" style="list-style: none; padding: 0; margin: 0; color: var(--user-muted); font-size: 0.9rem;"></ul>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="color: var(--user-ink); font-weight: 600; font-size: 0.9rem; margin-bottom: 6px; display: block;">Activities / Events</label>
                <p class="calendar-event-hint">Add one or more activity cards below. Every card will be saved to each selected date.</p>
                <div id="calendarEventsContainer" class="calendar-event-stack" style="margin-top: 16px;"></div>
                <div class="calendar-event-toolbar">
                    <button type="button" id="addCalendarEventButton" class="calendar-event-add">
                        <i class="mdi mdi-plus-circle-outline"></i>
                        Add Another Activity
                    </button>
                    <span id="calendarEventSummary" class="calendar-event-summary">1 activity ready to save.</span>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <button onclick="wbCloseWhereaboutsModal()" style="padding: 12px 28px; border: 1px solid rgba(60, 64, 198, 0.08); border-radius: 12px; background: #eef3f8; color: var(--user-ink); font-weight: 600; cursor: pointer;">Cancel</button>
                <button onclick="wbSubmitWhereabouts()" style="padding: 12px 28px; border: none; border-radius: 12px; background: linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%); color: #ffffff; font-weight: 600; cursor: pointer;">Save Activities</button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var WB_BASE = <?= json_encode(base_url(), $wbJsonFlags); ?>;
    var manilaToday = <?= json_encode($wbManilaToday, $wbJsonFlags); ?>;
    var statusStyles = <?= $wbStatusStylesJson ?: '{}' ?>;
    var activityEntriesByDate = <?= $wbEntriesJson ?: '{}' ?>;
    var defaultStatusStyle = statusStyles.default || { accent: '#475569', tint: 'rgba(71, 85, 105, 0.14)' };
    var whereaboutsStatusOptions = ['In Office', 'Out of Office', 'On Official Business', 'On Leave', 'On Field Work'];
    var previewFallbackMessage = 'No activities scheduled yet. Use the Add Activity button to plan this day.';

    var selectedDates = new Set();
    var selectedCalendarDate = manilaToday;
    var whereaboutsCalendar = null;

    function notify(opts) {
        if (window.Swal && typeof Swal.fire === 'function') {
            Swal.fire(opts);
            return Promise.resolve({});
        }
        window.alert((opts.title || '') + (opts.text ? '\n\n' + opts.text : ''));
        return Promise.resolve({});
    }

    function parseDateString(dateStr) {
        var parts = String(dateStr || '').split('-');
        if (parts.length !== 3) { return null; }
        var year = parseInt(parts[0], 10);
        var month = parseInt(parts[1], 10);
        var day = parseInt(parts[2], 10);
        if (!year || !month || !day) { return null; }
        return new Date(year, month - 1, day);
    }

    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    function getEntriesForDate(dateStr) {
        return Array.isArray(activityEntriesByDate[dateStr]) ? activityEntriesByDate[dateStr] : [];
    }

    function getStatusStyle(status) {
        return statusStyles[status] || defaultStatusStyle;
    }

    function formatDateLabel(dateStr, options) {
        var date = parseDateString(dateStr);
        return date ? date.toLocaleDateString('en-US', options) : dateStr;
    }

    function updateSelectedDateHeading(dateStr, entryCount) {
        var titleElement = document.getElementById('selectedDateTitle');
        var metaElement = document.getElementById('selectedDateMeta');
        if (!titleElement || !metaElement) { return; }
        titleElement.textContent = formatDateLabel(dateStr, { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
        if (entryCount > 0) {
            metaElement.textContent = entryCount + ' scheduled ' + (entryCount === 1 ? 'activity' : 'activities') + ' for this date.';
            return;
        }
        metaElement.textContent = dateStr === manilaToday
            ? 'Nothing scheduled yet for today.'
            : 'No activities scheduled yet for this date.';
    }

    function resetActivityPreview(dateStr) {
        var targetDate = dateStr || selectedCalendarDate || manilaToday;
        var previewBody = document.getElementById('calendarActivityPreviewBody');
        if (!previewBody) { return; }
        updateSelectedDateHeading(targetDate, 0);
        previewBody.innerHTML = '<p class="calendar-agenda-empty">' + escapeHtml(previewFallbackMessage) + '</p>';
    }

    function renderActivityPreview(dateStr) {
        var previewBody = document.getElementById('calendarActivityPreviewBody');
        var entries = getEntriesForDate(dateStr);
        if (!previewBody || !entries.length) {
            resetActivityPreview(dateStr);
            return;
        }
        updateSelectedDateHeading(dateStr, entries.length);
        var previewHeader = '<div class="activity-preview-date"><strong>'
            + escapeHtml(formatDateLabel(dateStr, { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' }))
            + '</strong><span class="activity-preview-count">' + entries.length + ' ' + (entries.length === 1 ? 'entry' : 'entries') + '</span></div>';
        var previewCards = entries.map(function(entry) {
            var statusStyle = getStatusStyle(entry.status);
            var locationText = entry.location ? 'Location: ' + entry.location : 'Location not specified';
            var activityText = entry.activity || 'No activity details provided.';
            var notesText = entry.notes ? '<div class="activity-preview-notes">Notes: ' + escapeHtml(entry.notes) + '</div>' : '';
            return '<div class="activity-preview-entry"><div class="activity-preview-head">'
                + '<span class="activity-preview-status" style="background: ' + statusStyle.tint + '; color: ' + statusStyle.accent + ';">'
                + escapeHtml(entry.status || 'Saved whereabouts') + '</span></div>'
                + '<div class="activity-preview-location">' + escapeHtml(locationText) + '</div>'
                + '<div class="activity-preview-activity">' + escapeHtml(activityText) + '</div>'
                + notesText + '</div>';
        }).join('');
        previewBody.innerHTML = previewHeader + previewCards;
    }

    function getCalendarEventsContainer() {
        return document.getElementById('calendarEventsContainer');
    }

    function buildStatusOptions(selectedStatus) {
        return whereaboutsStatusOptions.map(function(status) {
            var selected = status === selectedStatus ? ' selected' : '';
            return '<option value="' + escapeHtml(status) + '"' + selected + '>' + escapeHtml(status) + '</option>';
        }).join('');
    }

    function createCalendarEventCard(entry) {
        var values = entry || {};
        var selectedStatus = values.status || 'In Office';
        var location = values.location || '';
        var activity = values.activity || '';
        var notes = values.notes || '';
        var card = document.createElement('div');
        card.className = 'calendar-event-card';
        card.innerHTML = '<div class="calendar-event-card-head"><div>'
            + '<h4 class="calendar-event-card-title">Activity / Event</h4>'
            + '<p class="calendar-event-card-copy">Set the status, location, activity details, and optional notes for this schedule item.</p></div>'
            + '<button type="button" class="calendar-event-remove"><i class="mdi mdi-delete-outline"></i> Remove</button></div>'
            + '<div class="calendar-event-grid">'
            + '<div class="calendar-event-field"><label class="calendar-event-label">Status</label>'
            + '<select class="calendar-event-input" data-field="status">' + buildStatusOptions(selectedStatus) + '</select></div>'
            + '<div class="calendar-event-field"><label class="calendar-event-label">Location <span style="color: #ef4444;">*</span></label>'
            + '<input type="text" class="calendar-event-input" data-field="location" placeholder="e.g., Main Office, School X" value="' + escapeHtml(location) + '"></div>'
            + '<div class="calendar-event-field calendar-event-field--full"><label class="calendar-event-label">Activity / Event <span style="color: #ef4444;">*</span></label>'
            + '<textarea class="calendar-event-input calendar-event-textarea" data-field="activity" placeholder="What are you working on or attending?">' + escapeHtml(activity) + '</textarea></div>'
            + '<div class="calendar-event-field calendar-event-field--full"><label class="calendar-event-label">Notes</label>'
            + '<textarea class="calendar-event-input calendar-event-textarea" data-field="notes" placeholder="Any additional details...">' + escapeHtml(notes) + '</textarea></div>'
            + '</div>';
        var removeButton = card.querySelector('.calendar-event-remove');
        removeButton.addEventListener('click', function() {
            if (getCalendarEventsContainer().querySelectorAll('.calendar-event-card').length <= 1) { return; }
            card.remove();
            updateCalendarEventCardsState();
        });
        return card;
    }

    function updateCalendarEventSummary() {
        var summary = document.getElementById('calendarEventSummary');
        var container = getCalendarEventsContainer();
        if (!summary || !container) { return; }
        var entryCount = container.querySelectorAll('.calendar-event-card').length;
        var dateCount = selectedDates.size;
        var eventLabel = entryCount === 1 ? 'activity' : 'activities';
        var dateLabel = dateCount === 1 ? 'date' : 'dates';
        if (dateCount > 0) {
            summary.textContent = entryCount + ' ' + eventLabel + ' will be saved across ' + dateCount + ' selected ' + dateLabel + '.';
            return;
        }
        summary.textContent = entryCount + ' ' + eventLabel + ' ready to save.';
    }

    function updateCalendarEventCardsState() {
        var container = getCalendarEventsContainer();
        if (!container) { return; }
        var cards = Array.prototype.slice.call(container.querySelectorAll('.calendar-event-card'));
        var disableRemove = cards.length <= 1;
        cards.forEach(function(card, index) {
            var title = card.querySelector('.calendar-event-card-title');
            var removeButton = card.querySelector('.calendar-event-remove');
            if (title) { title.textContent = 'Activity / Event #' + (index + 1); }
            if (removeButton) { removeButton.disabled = disableRemove; }
        });
        updateCalendarEventSummary();
    }

    function addCalendarEventCard(entry) {
        var container = getCalendarEventsContainer();
        if (!container) { return; }
        container.appendChild(createCalendarEventCard(entry));
        updateCalendarEventCardsState();
    }

    function resetCalendarEventCards(entries) {
        var container = getCalendarEventsContainer();
        if (!container) { return; }
        var initialEntries = Array.isArray(entries) && entries.length ? entries : [{}];
        container.innerHTML = '';
        initialEntries.forEach(function(entry) {
            container.appendChild(createCalendarEventCard(entry));
        });
        updateCalendarEventCardsState();
    }

    function collectCalendarEventEntries() {
        var container = getCalendarEventsContainer();
        if (!container) { return []; }
        return Array.prototype.slice.call(container.querySelectorAll('.calendar-event-card')).map(function(card) {
            var getFieldValue = function(fieldName) {
                var field = card.querySelector('[data-field="' + fieldName + '"]');
                return field ? String(field.value || '').trim() : '';
            };
            return {
                status: getFieldValue('status') || 'In Office',
                location: getFieldValue('location'),
                activity: getFieldValue('activity'),
                notes: getFieldValue('notes')
            };
        });
    }

    function slugifyStatus(status) {
        return String(status || 'default').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '') || 'default';
    }

    function normalizeCalendarEntry(entry) {
        var values = entry || {};
        return {
            status: String(values.status || 'In Office').trim() || 'In Office',
            location: String(values.location || '').trim(),
            activity: String(values.activity || '').trim(),
            notes: String(values.notes || '').trim()
        };
    }

    function buildCalendarEntrySignature(entry) {
        var normalizedEntry = normalizeCalendarEntry(entry);
        return [normalizedEntry.status, normalizedEntry.location, normalizedEntry.activity, normalizedEntry.notes].join('||');
    }

    function buildConsecutiveDateRanges(dates) {
        var uniqueDates = Array.from(new Set((dates || []).filter(Boolean))).sort();
        var ranges = [];
        var rangeStart = null;
        var previousDate = null;
        uniqueDates.forEach(function(dateStr) {
            if (!rangeStart) { rangeStart = dateStr; previousDate = dateStr; return; }
            var expectedNextDate = moment(previousDate, 'YYYY-MM-DD').add(1, 'day').format('YYYY-MM-DD');
            if (dateStr === expectedNextDate) { previousDate = dateStr; return; }
            ranges.push({ start: rangeStart, end: previousDate });
            rangeStart = dateStr;
            previousDate = dateStr;
        });
        if (rangeStart) { ranges.push({ start: rangeStart, end: previousDate }); }
        return ranges;
    }

    function buildCalendarEvent(dateRange, entry, index) {
        var normalizedEntry = normalizeCalendarEntry(entry);
        var statusStyle = getStatusStyle(normalizedEntry.status);
        var rangeStart = dateRange.start;
        var rangeEnd = dateRange.end;
        var endDateExclusive = moment(rangeEnd, 'YYYY-MM-DD').add(1, 'day').format('YYYY-MM-DD');
        var isSingleDate = rangeStart === rangeEnd;
        var dateSummary = isSingleDate
            ? formatDateLabel(rangeStart, { month: 'short', day: 'numeric', year: 'numeric' })
            : formatDateLabel(rangeStart, { month: 'short', day: 'numeric' }) + ' to ' + formatDateLabel(rangeEnd, { month: 'short', day: 'numeric', year: 'numeric' });
        return {
            id: 'whereabouts-' + rangeStart + '-' + rangeEnd + '-' + index + '-' + slugifyStatus(normalizedEntry.status) + '-' + slugifyStatus(normalizedEntry.activity || normalizedEntry.location || 'activity'),
            title: normalizedEntry.activity || normalizedEntry.location || normalizedEntry.status || 'Activity',
            start: rangeStart,
            end: endDateExclusive,
            allDay: true,
            backgroundColor: statusStyle.accent,
            borderColor: statusStyle.accent,
            textColor: '#ffffff',
            className: ['calendar-range-event'],
            whereaboutsDate: rangeStart,
            whereaboutsRangeStart: rangeStart,
            whereaboutsRangeEnd: rangeEnd,
            whereaboutsDateSummary: dateSummary,
            whereaboutsStatus: normalizedEntry.status || 'Saved whereabouts',
            whereaboutsLocation: normalizedEntry.location,
            whereaboutsActivity: normalizedEntry.activity,
            whereaboutsNotes: normalizedEntry.notes
        };
    }

    function buildCalendarEvents() {
        var groupedEntries = {};
        var events = [];
        Object.keys(activityEntriesByDate).sort().forEach(function(dateStr) {
            getEntriesForDate(dateStr).forEach(function(entry) {
                var normalizedEntry = normalizeCalendarEntry(entry);
                var signature = buildCalendarEntrySignature(normalizedEntry);
                if (!groupedEntries[signature]) {
                    groupedEntries[signature] = { entry: normalizedEntry, dates: [] };
                }
                groupedEntries[signature].dates.push(dateStr);
            });
        });
        Object.keys(groupedEntries).forEach(function(signature, groupIndex) {
            var groupedEntry = groupedEntries[signature];
            buildConsecutiveDateRanges(groupedEntry.dates).forEach(function(dateRange, rangeIndex) {
                events.push(buildCalendarEvent(dateRange, groupedEntry.entry, groupIndex + '-' + rangeIndex));
            });
        });
        return events.sort(function(left, right) {
            if (left.start !== right.start) { return left.start < right.start ? -1 : 1; }
            return String(left.title || '').localeCompare(String(right.title || ''));
        });
    }

    function syncCalendarDaySelection() {
        if (!whereaboutsCalendar) { return; }
        var calendarElement = jQuery('#whereaboutsCalendar');
        calendarElement.find('.fc-day').removeClass('fc-day-selected');
        if (!selectedCalendarDate) { return; }
        calendarElement.find('.fc-day[data-date="' + selectedCalendarDate + '"]').addClass('fc-day-selected');
    }

    function setSelectedCalendarDate(dateStr) {
        selectedCalendarDate = dateStr || manilaToday;
        renderActivityPreview(selectedCalendarDate);
        syncCalendarDaySelection();
    }

    function buildSelectedRangeDates(start, end) {
        var dates = [];
        var cursor = start.clone().startOf('day');
        var endDate = end.clone().startOf('day');
        while (cursor.isBefore(endDate)) {
            dates.push(cursor.format('YYYY-MM-DD'));
            cursor.add(1, 'day');
        }
        if (!dates.length) { dates.push(start.format('YYYY-MM-DD')); }
        return dates;
    }

    function showWhereaboutsModal(dates) {
        var normalizedDates = Array.isArray(dates) && dates.length ? dates.slice().sort() : [selectedCalendarDate || manilaToday];
        var modal = document.getElementById('whereaboutsModal');
        var datesList = document.getElementById('selectedDatesList');
        selectedDates = new Set(normalizedDates);
        datesList.innerHTML = '';
        normalizedDates.forEach(function(date) {
            var li = document.createElement('li');
            li.textContent = formatDateLabel(date, { weekday: 'short', month: 'short', day: 'numeric' });
            li.style.padding = '4px 0';
            li.style.borderBottom = '1px solid rgba(60, 64, 198, 0.08)';
            datesList.appendChild(li);
        });
        resetCalendarEventCards();
        updateCalendarEventSummary();
        modal.style.display = 'flex';
        if (whereaboutsCalendar) { whereaboutsCalendar.fullCalendar('unselect'); }
    }

    function closeWhereaboutsModal() {
        document.getElementById('whereaboutsModal').style.display = 'none';
        selectedDates.clear();
        resetCalendarEventCards();
        updateCalendarEventSummary();
        if (whereaboutsCalendar) { whereaboutsCalendar.fullCalendar('unselect'); }
    }

    function appendEntriesToCalendarData(dates, entries) {
        dates.forEach(function(dateStr) {
            if (!Array.isArray(activityEntriesByDate[dateStr])) { activityEntriesByDate[dateStr] = []; }
            entries.forEach(function(entry) {
                activityEntriesByDate[dateStr].push({
                    status: entry.status, location: entry.location, activity: entry.activity, notes: entry.notes
                });
            });
        });
    }

    function initializeWhereaboutsCalendar() {
        var calendarElement = jQuery('#whereaboutsCalendar');
        if (!calendarElement.length || typeof calendarElement.fullCalendar !== 'function') { return; }
        whereaboutsCalendar = calendarElement;
        calendarElement.fullCalendar({
            themeSystem: 'bootstrap4',
            defaultView: 'month',
            defaultDate: manilaToday,
            height: 'auto',
            fixedWeekCount: false,
            editable: false,
            selectable: true,
            selectHelper: true,
            unselectAuto: false,
            displayEventTime: false,
            eventLimit: 3,
            navLinks: false,
            header: { left: 'prev,next today addActivityButton', center: 'title', right: 'month,listWeek' },
            buttonText: { month: 'Month', listWeek: 'Agenda' },
            customButtons: {
                addActivityButton: {
                    text: 'Add Activity',
                    click: function() { showWhereaboutsModal([selectedCalendarDate || manilaToday]); }
                }
            },
            events: function(start, end, timezone, callback) { callback(buildCalendarEvents()); },
            dayClick: function(date) { setSelectedCalendarDate(date.format('YYYY-MM-DD')); },
            select: function(start, end) {
                var rangeDates = buildSelectedRangeDates(start, end);
                setSelectedCalendarDate(rangeDates[0]);
                showWhereaboutsModal(rangeDates);
            },
            eventClick: function(event) {
                var eventDate = event.whereaboutsDate || event.start.format('YYYY-MM-DD');
                setSelectedCalendarDate(eventDate);
                return false;
            },
            eventRender: function(event, element) {
                var tooltipLines = [event.whereaboutsDateSummary, event.whereaboutsStatus, event.whereaboutsLocation, event.whereaboutsActivity, event.whereaboutsNotes].filter(Boolean);
                if (tooltipLines.length) { element.attr('title', tooltipLines.join('\n')); }
            },
            viewRender: function() { window.requestAnimationFrame(syncCalendarDaySelection); },
            eventAfterAllRender: function() { syncCalendarDaySelection(); }
        });
        setSelectedCalendarDate(selectedCalendarDate || manilaToday);
    }

    function submitWhereabouts() {
        var dates = Array.from(selectedDates).sort();
        var entries = collectCalendarEventEntries();
        if (!dates.length) {
            notify({ title: 'No date selected', text: 'Please choose at least one date from the calendar.', type: 'warning', confirmButtonColor: '#3c40c6' });
            return;
        }
        if (!entries.length) {
            notify({ title: 'Add an activity', text: 'Please add at least one activity or event before saving.', type: 'warning', confirmButtonColor: '#3c40c6' });
            return;
        }
        var invalidEntryIndex = entries.findIndex(function(entry) { return !entry.location || !entry.activity; });
        if (invalidEntryIndex !== -1) {
            notify({ title: 'Complete the required fields', text: 'Please complete the location and activity for Activity / Event #' + (invalidEntryIndex + 1) + '.', type: 'warning', confirmButtonColor: '#3c40c6' });
            return;
        }
        var formData = new FormData();
        formData.append('dates', JSON.stringify(dates));
        formData.append('entries', JSON.stringify(entries));
        fetch(WB_BASE + 'Page/whereabouts_ajax', { method: 'POST', body: formData })
            .then(function(response) {
                return response.json().catch(function() {
                    throw new Error('Unable to save your activities right now.');
                }).then(function(data) {
                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'Unable to save your activities right now.');
                    }
                    return data;
                });
            })
            .then(function(data) {
                var entryLabel = data.entries_saved === 1 ? 'activity' : 'activities';
                var dateLabel = data.dates_saved === 1 ? 'date' : 'dates';
                appendEntriesToCalendarData(dates, entries);
                if (whereaboutsCalendar) { whereaboutsCalendar.fullCalendar('refetchEvents'); }
                setSelectedCalendarDate(dates[0] || selectedCalendarDate);
                notify({ title: 'Activities saved', text: data.entries_saved + ' ' + entryLabel + ' saved across ' + data.dates_saved + ' ' + dateLabel + '.', type: 'success', confirmButtonColor: '#3c40c6' });
                closeWhereaboutsModal();
            })
            .catch(function(error) {
                notify({ title: 'Unable to save activities', text: error.message || 'Please try again.', type: 'error', confirmButtonColor: '#3c40c6' });
            });
    }

    // Expose the handlers referenced by inline onclick in the modal.
    window.wbCloseWhereaboutsModal = closeWhereaboutsModal;
    window.wbSubmitWhereabouts = submitWhereabouts;

    // Lazily load a dependency script only when it isn't already present.
    function ensureScript(test, src, cb) {
        if (test()) { cb(); return; }
        var existing = document.querySelector('script[data-wb-src="' + src + '"]');
        if (existing) {
            existing.addEventListener('load', cb);
            return;
        }
        var script = document.createElement('script');
        script.src = src;
        script.setAttribute('data-wb-src', src);
        script.onload = cb;
        document.body.appendChild(script);
    }

    function ensureFullCalendarCss() {
        if (document.getElementById('wb-fullcalendar-css')) { return; }
        var link = document.createElement('link');
        link.id = 'wb-fullcalendar-css';
        link.rel = 'stylesheet';
        link.type = 'text/css';
        link.href = WB_BASE + 'assets/libs/fullcalendar/fullcalendar.min.css';
        document.head.appendChild(link);
    }

    function boot() {
        var addCalendarEventButton = document.getElementById('addCalendarEventButton');
        var openSelectedDateComposer = document.getElementById('openSelectedDateComposer');

        if (addCalendarEventButton) {
            addCalendarEventButton.addEventListener('click', function() { addCalendarEventCard(); });
        }
        if (openSelectedDateComposer) {
            openSelectedDateComposer.addEventListener('click', function() { showWhereaboutsModal([selectedCalendarDate || manilaToday]); });
        }

        resetCalendarEventCards();
        resetActivityPreview(selectedCalendarDate);

        // jQuery ships in vendor.min.js which is loaded ahead of DOMContentLoaded.
        if (!window.jQuery) { return; }

        ensureFullCalendarCss();
        ensureScript(
            function() { return typeof moment !== 'undefined'; },
            WB_BASE + 'assets/libs/moment/moment.min.js',
            function() {
                ensureScript(
                    function() { return window.jQuery && jQuery.fn && typeof jQuery.fn.fullCalendar === 'function'; },
                    WB_BASE + 'assets/libs/fullcalendar/fullcalendar.min.js',
                    function() { initializeWhereaboutsCalendar(); }
                );
            }
        );
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();
</script>
<?php
}
