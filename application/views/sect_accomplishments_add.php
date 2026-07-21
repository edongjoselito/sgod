<?php
$sectionName = $this->session->userdata('section') ?: 'Section';
$secGroupName = $this->session->userdata('secGroup') ?: '';
$selectedAccomplishmentScope = $this->input->post('accomplishmentScope') && strtolower((string) $this->input->post('accomplishmentScope')) === 'personal'
    ? 'personal'
    : 'section';
$activityDateFromValue = $this->input->post('activityDateFrom') ? (string) $this->input->post('activityDateFrom') : date('Y-m-d');
$activityDateToValue = $this->input->post('activityDateTo') ? (string) $this->input->post('activityDateTo') : $activityDateFromValue;
$activityValue = $this->input->post('activity') ? (string) $this->input->post('activity') : '';
$categoryValue = $this->input->post('activityCategory') ? (string) $this->input->post('activityCategory') : 'Accomplishment';
$venueValue = $this->input->post('venue') ? (string) $this->input->post('venue') : '';
$kraIdValue = $this->input->post('kra_id') ? (int) $this->input->post('kra_id') : 0;
$objectiveIdValue = $this->input->post('objective_id') ? (int) $this->input->post('objective_id') : 0;
$perIndicatorsValue = $this->input->post('perIndicators') ? (string) $this->input->post('perIndicators') : '';
$targetValue = $this->input->post('target') ? (string) $this->input->post('target') : '';
$achievedValue = $this->input->post('achieved') ? (string) $this->input->post('achieved') : '';
$percentageValue = $this->input->post('percentageAccom') ? (string) $this->input->post('percentageAccom') : '';
$resourcesValue = $this->input->post('resources') ? (string) $this->input->post('resources') : '';
$notesValue = $this->input->post('notes') ? (string) $this->input->post('notes') : '';
$remarksValue = $this->input->post('remarks') ? (string) $this->input->post('remarks') : '';
$uploadError = isset($uploadError) ? trim((string) $uploadError) : '';
$weeklyReportUrl = base_url() . 'Page/sec_filterv2/2/' . rawurlencode($sectionName);
$activityRangeSummary = $activityDateFromValue;
if ($activityDateToValue !== '' && $activityDateToValue !== $activityDateFromValue) {
    $activityRangeSummary .= ' to ' . $activityDateToValue;
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <?php include('includes/page-title.php'); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Responsive bootstrap 4 admin template" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />

        <style>
            :root {
                --accom-navy: #272b8c;
                --accom-blue: #3c40c6;
                --accom-teal: #565de8;
                --accom-ink: #23275d;
                --accom-muted: #7b7fa7;
                --accom-border: rgba(60, 64, 198, 0.12);
                --accom-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
                --accom-surface: rgba(255, 255, 255, 0.96);
            }

            body {
                background:
                    radial-gradient(circle at top left, rgba(60, 64, 198, 0.10), transparent 24%),
                    linear-gradient(180deg, #f4f8fc 0%, #eef4fa 100%);
            }

            .content-page {
                background: transparent;
            }

            .accom-add-shell {
                position: relative;
                padding-bottom: 28px;
            }

            .accom-add-shell::before {
                content: "";
                position: absolute;
                inset: 24px 0 auto;
                height: 240px;
                border-radius: 30px;
                background: linear-gradient(135deg, rgba(45, 127, 249, 0.09), rgba(15, 159, 154, 0.08));
                z-index: 0;
            }

            .accom-add-shell > * {
                position: relative;
                z-index: 1;
            }

            .accom-hero {
                margin-top: 20px;
                margin-bottom: 24px;
                border-radius: 28px;
                overflow: hidden;
                color: #ffffff;
                box-shadow: var(--accom-shadow);
                background:
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 32%),
                    linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
            }

            .accom-hero-body {
                padding: 32px;
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 18px;
                flex-wrap: wrap;
            }

            .accom-eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 14px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.12);
                border: 1px solid rgba(255, 255, 255, 0.18);
                font-size: 0.8rem;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .accom-title {
                margin: 18px 0 12px;
                color: #ffffff;
                font-size: clamp(2rem, 3vw, 2.7rem);
                line-height: 1.05;
                font-weight: 700;
                letter-spacing: -0.03em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .accom-copy {
                max-width: 720px;
                color: rgba(255, 255, 255, 0.82);
                line-height: 1.7;
                margin-bottom: 0;
            }

            .hero-meta {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin-top: 18px;
            }

            .hero-chip {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 12px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.12);
                color: rgba(255, 255, 255, 0.95);
                font-size: 0.84rem;
                font-weight: 600;
            }

            .hero-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
            }

            .hero-action {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 12px 18px;
                border-radius: 999px;
                border: 1px solid rgba(255, 255, 255, 0.24);
                color: #ffffff;
                background: rgba(255, 255, 255, 0.12);
                font-weight: 700;
                text-decoration: none;
                transition: transform 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
            }

            .hero-action:hover,
            .hero-action:focus {
                color: #ffffff;
                text-decoration: none;
                background: rgba(255, 255, 255, 0.18);
                transform: translateY(-1px);
                box-shadow: 0 16px 30px rgba(15, 23, 42, 0.16);
            }

            button.hero-action {
                border: 1px solid rgba(255, 255, 255, 0.24);
                cursor: pointer;
            }

            button.hero-action:focus {
                outline: none;
            }

            .hero-action--secondary {
                background: rgba(255, 255, 255, 0.08);
            }

            .panel-card {
                border-radius: 24px;
                border: 1px solid var(--accom-border);
                background: var(--accom-surface);
                box-shadow: var(--accom-shadow);
                padding: 28px;
                margin-bottom: 24px;
            }

            .inline-alert {
                border: none;
                border-radius: 18px;
                box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
                margin-bottom: 22px;
            }

            .panel-kicker {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 14px;
                border-radius: 999px;
                background: rgba(60, 64, 198, 0.08);
                color: var(--accom-blue);
                font-size: 0.76rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .panel-title {
                margin: 16px 0 8px;
                color: var(--accom-ink);
                font-size: 1.45rem;
                font-weight: 700;
                line-height: 1.2;
            }

            .panel-copy {
                color: var(--accom-muted);
                font-size: 0.95rem;
                line-height: 1.6;
                margin-bottom: 0;
            }

            .section-block + .section-block {
                margin-top: 26px;
                padding-top: 24px;
                border-top: 1px solid rgba(60, 64, 198, 0.08);
            }

            .section-title {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-bottom: 18px;
                color: var(--accom-ink);
                font-size: 1.02rem;
                font-weight: 700;
            }

            .section-title i {
                color: var(--accom-blue);
                font-size: 1.1rem;
            }

            .field-grid {
                display: grid;
                grid-template-columns: repeat(12, minmax(0, 1fr));
                gap: 18px;
            }

            .field-span-3,
            .field-span-4,
            .field-span-6,
            .field-span-12 {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .field-span-3 {
                grid-column: span 3;
            }

            .field-span-4 {
                grid-column: span 4;
            }

            .field-span-6 {
                grid-column: span 6;
            }

            .field-span-12 {
                grid-column: span 12;
            }

            .field-label {
                color: var(--accom-ink);
                font-size: 0.92rem;
                font-weight: 700;
                margin: 0;
            }

            .field-label .required {
                color: #dc2626;
            }

            .field-input,
            .field-textarea,
            .field-select {
                width: 100%;
                min-height: 48px;
                padding: 12px 14px;
                border-radius: 14px;
                border: 1px solid rgba(60, 64, 198, 0.14);
                background: #ffffff;
                color: var(--accom-ink);
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }

            .field-textarea {
                min-height: 120px;
                resize: vertical;
            }

            .field-input:focus,
            .field-textarea:focus,
            .field-select:focus {
                outline: none;
                border-color: rgba(60, 64, 198, 0.35);
                box-shadow: 0 0 0 3px rgba(60, 64, 198, 0.08);
            }

            .field-note {
                color: var(--accom-muted);
                font-size: 0.84rem;
                line-height: 1.5;
            }

            .aside-card {
                position: sticky;
                top: 24px;
            }

            .aside-list {
                display: grid;
                gap: 14px;
                margin-top: 22px;
            }

            .aside-item {
                padding: 16px 18px;
                border-radius: 18px;
                background: linear-gradient(180deg, #f8faff 0%, #eef4ff 100%);
                border: 1px solid rgba(60, 64, 198, 0.08);
            }

            .aside-item-label {
                display: block;
                color: var(--accom-muted);
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.06em;
                text-transform: uppercase;
                margin-bottom: 8px;
            }

            .aside-item-value {
                display: block;
                color: var(--accom-ink);
                font-size: 1rem;
                font-weight: 700;
                line-height: 1.4;
            }

            .tips-stack {
                display: grid;
                gap: 12px;
                margin-top: 22px;
            }

            .tip-card {
                padding: 16px 18px;
                border-radius: 18px;
                border: 1px solid rgba(60, 64, 198, 0.08);
                background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            }

            .tip-title {
                color: var(--accom-ink);
                font-size: 0.95rem;
                font-weight: 700;
                margin-bottom: 6px;
            }

            .tip-copy {
                color: var(--accom-muted);
                font-size: 0.88rem;
                line-height: 1.55;
                margin-bottom: 0;
            }

            .form-actions {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                gap: 12px;
                margin-top: 28px;
                padding-top: 24px;
                border-top: 1px solid rgba(60, 64, 198, 0.08);
            }

            .action-group {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
            }

            .shell-button {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 12px 18px;
                border: none;
                border-radius: 14px;
                font-weight: 700;
                text-decoration: none;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
                cursor: pointer;
            }

            .shell-button:hover,
            .shell-button:focus {
                text-decoration: none;
                transform: translateY(-1px);
                box-shadow: 0 14px 28px rgba(15, 23, 42, 0.12);
            }

            .shell-button--primary {
                color: #ffffff;
                background: linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
            }

            .shell-button--secondary {
                color: var(--accom-blue);
                background: rgba(60, 64, 198, 0.08);
            }

            .panel-kicker--light {
                background: rgba(255, 255, 255, 0.16);
                color: #ffffff;
                border: 1px solid rgba(255, 255, 255, 0.22);
            }

            .weekly-report-modal .modal-dialog {
                max-width: 1180px;
            }

            .weekly-report-modal .modal-content {
                border: none;
                border-radius: 28px;
                overflow: hidden;
                box-shadow: 0 28px 70px rgba(15, 23, 42, 0.24);
            }

            .weekly-report-modal .modal-header {
                align-items: flex-start;
                padding: 24px 28px;
                border-bottom: none;
                background:
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 34%),
                    linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
            }

            .weekly-report-modal .modal-title {
                margin: 14px 0 0;
                color: #ffffff;
                font-size: 1.35rem;
                font-weight: 700;
            }

            .weekly-report-modal .close {
                margin: 0;
                padding: 0;
                color: #ffffff;
                opacity: 0.9;
                text-shadow: none;
            }

            .weekly-report-modal .close:hover,
            .weekly-report-modal .close:focus {
                color: #ffffff;
                opacity: 1;
            }

            .weekly-report-toolbar {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                padding: 16px 24px;
                border-bottom: 1px solid rgba(60, 64, 198, 0.08);
                background: #ffffff;
            }

            .weekly-report-toolbar p {
                margin: 0;
                color: var(--accom-muted);
                line-height: 1.6;
            }

            .weekly-report-modal .modal-body {
                padding: 0;
                background: #edf3fb;
            }

            .weekly-report-frame {
                display: block;
                width: 100%;
                min-height: 72vh;
                border: 0;
                background: #ffffff;
            }

            @media (max-width: 1199.98px) {
                .aside-card {
                    position: static;
                }
            }

            @media (max-width: 991.98px) {
                .field-span-3,
                .field-span-4,
                .field-span-6 {
                    grid-column: span 6;
                }
            }

            @media (max-width: 767.98px) {
                .accom-add-shell::before {
                    left: 12px;
                    right: 12px;
                    inset-block-start: 18px;
                    height: 220px;
                }

                .accom-hero,
                .panel-card {
                    border-radius: 22px;
                }

                .accom-hero-body,
                .panel-card {
                    padding: 22px;
                }

                .field-span-3,
                .field-span-4,
                .field-span-6,
                .field-span-12 {
                    grid-column: span 12;
                }

                .hero-actions,
                .action-group,
                .form-actions {
                    width: 100%;
                }

                .weekly-report-toolbar {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .form-actions {
                    flex-direction: column;
                    align-items: stretch;
                }

                .weekly-report-frame {
                    min-height: 60vh;
                }
            }
        </style>
    </head>

    <body>

        <div id="wrapper">

            <?php include('includes/top-bar.php'); ?>
            <?php include('includes/sidebar.php') ?>

            <div class="content-page">
                <div class="content">
                    <div class="container-fluid accom-add-shell">
                        <div class="accom-hero">
                            <div class="accom-hero-body">
                                <div>
                                    <span class="accom-eyebrow">
                                        <i class="mdi mdi-clipboard-plus-outline"></i>
                                        New Entry
                                    </span>
                                    <h1 class="accom-title">Add Accomplishment</h1>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="panel-card">
                                    <?php if ($uploadError !== '') { ?>
                                        <div class="alert alert-warning inline-alert" role="alert">
                                            <?= htmlspecialchars($uploadError, ENT_QUOTES, 'UTF-8'); ?>
                                        </div>
                                    <?php } ?>

                                    <form method="post" action="<?= base_url(); ?>Page/addAccomplishments">
                                        <div class="section-block">
                                            <div class="section-title">
                                                <i class="mdi mdi-calendar-range"></i>
                                                Activity Date
                                            </div>
                                            <div class="field-grid">
                                                <div class="field-span-4">
                                                    <label class="field-label" for="accomplishmentScope">Scope <span class="required">*</span></label>
                                                    <select class="field-select" id="accomplishmentScope" name="accomplishmentScope" required>
                                                        <option value="section" <?= $selectedAccomplishmentScope === 'section' ? 'selected' : ''; ?>>Section Accomplishments</option>
                                                        <option value="personal" <?= $selectedAccomplishmentScope === 'personal' ? 'selected' : ''; ?>>Personal Accomplishments</option>
                                                    </select>
                                                </div>

                                                <div class="field-span-4">
                                                    <label class="field-label" for="activityDateFrom">From <span class="required">*</span></label>
                                                    <input type="date" class="field-input" id="activityDateFrom" name="activityDateFrom" value="<?= htmlspecialchars($activityDateFromValue, ENT_QUOTES, 'UTF-8'); ?>" required>
                                                </div>

                                                <div class="field-span-4">
                                                    <label class="field-label" for="activityDateTo">To <span class="required">*</span></label>
                                                    <input type="date" class="field-input" id="activityDateTo" name="activityDateTo" value="<?= htmlspecialchars($activityDateToValue, ENT_QUOTES, 'UTF-8'); ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="section-block">
                                            <div class="section-title">
                                                <i class="mdi mdi-clipboard-text-outline"></i>
                                                Activity Details
                                            </div>
                                            <div class="field-grid">
                                                <div class="field-span-12">
                                                    <label class="field-label" for="activity">Activity <span class="required">*</span></label>
                                                    <textarea class="field-textarea" id="activity" name="activity" required><?= htmlspecialchars($activityValue, ENT_QUOTES, 'UTF-8'); ?></textarea>
                                                </div>

                                                <input type="hidden" id="activityCategory" name="activityCategory" value="<?= htmlspecialchars($categoryValue, ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" id="perIndicators" name="perIndicators" value="<?= htmlspecialchars($perIndicatorsValue, ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" id="target" name="target" value="<?= htmlspecialchars($targetValue, ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" id="achieved" name="achieved" value="<?= htmlspecialchars($achievedValue, ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" id="percentageAccom" name="percentageAccom" value="<?= htmlspecialchars($percentageValue, ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" id="remarks" name="remarks" value="<?= htmlspecialchars($remarksValue, ENT_QUOTES, 'UTF-8'); ?>">

                                                <div class="field-span-12">
                                                    <label class="field-label" for="venue">Venue</label>
                                                    <input type="text" class="field-input" id="venue" name="venue" value="<?= htmlspecialchars($venueValue, ENT_QUOTES, 'UTF-8'); ?>">
                                                </div>

                                                <div class="field-span-6">
                                                    <label class="field-label" for="kra_id">KRA</label>
                                                    <select class="field-input" id="kra_id" name="kra_id">
                                                        <option value="">-- Select KRA --</option>
                                                        <?php if (!empty($kraOptions)) : ?>
                                                            <?php foreach ($kraOptions as $kra) : ?>
                                                                <option value="<?= (int) $kra->id; ?>" <?= (int) ($kraIdValue ?? 0) === (int) $kra->id ? 'selected' : ''; ?>>
                                                                    <?= htmlspecialchars($kra->title, ENT_QUOTES, 'UTF-8'); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>

                                                <div class="field-span-6">
                                                    <label class="field-label" for="objective_id">Objective</label>
                                                    <select class="field-input" id="objective_id" name="objective_id">
                                                        <option value="">-- Select Objective --</option>
                                                        <?php if (!empty($objectiveOptions)) : ?>
                                                            <?php foreach ($objectiveOptions as $objective) : ?>
                                                                <option value="<?= (int) $objective->id; ?>" data-kra="<?= (int) $objective->template_kra_id; ?>" <?= (int) ($objectiveIdValue ?? 0) === (int) $objective->id ? 'selected' : ''; ?>>
                                                                    <?= htmlspecialchars($objective->code . ' - ' . $objective->objective, ENT_QUOTES, 'UTF-8'); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="section-block">
                                            <div class="section-title">
                                                <i class="mdi mdi-link-variant"></i>
                                                Supporting Notes
                                            </div>
                                            <div class="field-grid">
                                                <div class="field-span-12">
                                                    <label class="field-label" for="resources">Resources Link</label>
                                                    <textarea class="field-textarea" id="resources" name="resources"><?= htmlspecialchars($resourcesValue, ENT_QUOTES, 'UTF-8'); ?></textarea>
                                                </div>

                                                <div class="field-span-12">
                                                    <label class="field-label" for="notes">Additional Notes</label>
                                                    <textarea class="field-textarea" id="notes" name="notes"><?= htmlspecialchars($notesValue, ENT_QUOTES, 'UTF-8'); ?></textarea>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-actions">
                                            <div class="action-group">
                                                <a href="<?= base_url(); ?>Page/viewSecAccomplishments" class="shell-button shell-button--secondary">
                                                    <i class="mdi mdi-arrow-left"></i>
                                                    Cancel
                                                </a>
                                            </div>

                                            <div class="action-group">
                                                <button type="submit" name="submit" value="1" class="shell-button shell-button--primary">
                                                    <i class="mdi mdi-content-save-outline"></i>
                                                    Save Accomplishment
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <?php include('includes/footer.php'); ?>
            </div>
        </div>

        <div class="modal fade weekly-report-modal" id="weeklyReportModal" tabindex="-1" role="dialog" aria-labelledby="weeklyReportModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <span class="panel-kicker panel-kicker--light">
                                <i class="mdi mdi-calendar-week-outline"></i>
                                Report Preview
                            </span>
                            <h5 class="modal-title" id="weeklyReportModalLabel">Weekly Report</h5>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="weekly-report-toolbar">
                        <p>Preview the weekly report here without leaving the accomplishment form.</p>
                        <a href="<?= htmlspecialchars($weeklyReportUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" class="shell-button shell-button--secondary weekly-report-open">
                            <i class="mdi mdi-open-in-new"></i>
                            Open in New Tab
                        </a>
                    </div>
                    <div class="modal-body">
                        <iframe
                            class="weekly-report-frame"
                            src="about:blank"
                            title="Weekly Report Preview"
                            loading="lazy"
                        ></iframe>
                    </div>
                </div>
            </div>
        </div>

        <?php include('includes/right-sidebar.php'); ?>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
        <script>
            (function ($) {
                var weeklyReportModal = $('#weeklyReportModal');

                weeklyReportModal.on('show.bs.modal', function (event) {
                    var trigger = $(event.relatedTarget);
                    var reportUrl = trigger.data('report-url') || '<?= htmlspecialchars($weeklyReportUrl, ENT_QUOTES, 'UTF-8'); ?>';

                    weeklyReportModal.find('.weekly-report-frame').attr('src', reportUrl);
                    weeklyReportModal.find('.weekly-report-open').attr('href', reportUrl);
                });

                weeklyReportModal.on('hidden.bs.modal', function () {
                    weeklyReportModal.find('.weekly-report-frame').attr('src', 'about:blank');
                });

                var kraSelect = $('#kra_id');
                var objectiveSelect = $('#objective_id');
                var allObjectives = objectiveSelect.find('option[data-kra]').clone();

                function filterObjectives(kraId) {
                    var selectedObjective = objectiveSelect.val();
                    objectiveSelect.empty().append('<option value="">-- Select Objective --</option>');
                    allObjectives.each(function () {
                        var option = $(this);
                        if (!kraId || String(option.data('kra')) === String(kraId)) {
                            objectiveSelect.append(option.clone());
                        }
                    });
                    if (selectedObjective && objectiveSelect.find('option[value="' + selectedObjective + '"]').length) {
                        objectiveSelect.val(selectedObjective);
                    } else {
                        objectiveSelect.val('');
                    }
                }

                kraSelect.on('change', function () {
                    filterObjectives($(this).val());
                });

                filterObjectives(kraSelect.val());
            })(jQuery);
        </script>

    </body>
</html>
