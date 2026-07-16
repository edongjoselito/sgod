<?php
$form = $bundle['form'];
$ratingsApproved = !empty($summary['ratings_approved']);
$employee_signature = isset($employee_signature) ? (string) $employee_signature : '';
$rater_signature = ($ratingsApproved && isset($rater_signature)) ? (string) $rater_signature : '';
$kraObjectiveGroups = array();

$isPlaceholderKra = function ($title) {
    $title = preg_replace('/\s+/u', ' ', trim((string) $title));
    if ($title === '') return TRUE;
    return (bool) preg_match('/^(?:new\s+key\s+result\s+area|new\s+kra|untitled\s+kra|key\s+result\s+area)(?:\s+\d+)?$/iu', $title);
};
$isEmptyObjective = function ($objective) {
    return trim((string) $objective['code']) === ''
        && trim((string) $objective['objective']) === ''
        && trim((string) $objective['timeline']) === ''
        && (float) $objective['weight'] <= 0
        && trim((string) $objective['accomplishment']) === '';
};
foreach ($bundle['kras'] as $kra) {
    $kraTitle = preg_replace('/\s+/u', ' ', trim((string) $kra['title']));
    if ($isPlaceholderKra($kraTitle)) continue;
    $objectives = array();
    foreach ($kra['objectives'] as $objective) {
        if ($isEmptyObjective($objective)) continue;
        $objective['kra_title'] = $kraTitle;
        $objectives[] = $objective;
    }
    if ($objectives) $kraObjectiveGroups[] = $objectives;
}

$competencyLabels = array(
    'Core Behavioral Competency' => 'Core Behavioral Competencies',
    'Core Skill' => 'Core Skills',
    'Leadership Competency' => 'Leadership Competencies'
);
$competencyGroups = array();
foreach ($competencyLabels as $category => $label) {
    $competencyGroups[$category] = array('title' => $label, 'rows' => array());
}
foreach ((array) $bundle['competencies'] as $competency) {
    $category = trim((string) $competency['category']);
    if (!isset($competencyGroups[$category])) {
        $competencyGroups[$category] = array('title' => $category !== '' ? $category : 'Other Competencies', 'rows' => array());
    }
    $competencyGroups[$category]['rows'][] = $competency;
}

$e = function ($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$formatPeriod = function ($start, $end) {
    return date('F Y', strtotime($start)) . ' to ' . date('F Y', strtotime($end));
};
$standardLines = function ($values) use ($e) {
    $html = '';
    foreach (array('5', '4', '3', '2', '1') as $level) {
        $html .= '<div><b>' . $level . '</b><span>' . $e(isset($values[$level]) ? $values[$level] : '') . '</span></div>';
    }
    return $html;
};
$averageRatings = function ($rows) {
    $ratings = array();
    foreach ((array) $rows as $row) {
        $rating = isset($row['rating']) ? (int) $row['rating'] : 0;
        if ($rating > 0) $ratings[] = $rating;
    }
    return $ratings ? array_sum($ratings) / count($ratings) : 0;
};
$overallCompetencyAverage = $averageRatings($bundle['competencies']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IPCRF – <?= $e($form['employee_name']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        @page { size: A4 landscape; margin: 7mm; }
        * { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; }
        body { background: #e6e9ee; color: #111; font-family: Arial, Helvetica, sans-serif; }
        .report-document { background: #fff; box-shadow: 0 5px 24px rgba(0,0,0,.14); margin: 14px auto; max-width: 1450px; padding: 6mm; }
        .document-heading { align-items: stretch; display: grid; gap: 4mm; grid-template-columns: 1fr 38mm; margin-bottom: 2mm; }
        .report-title { align-items: center; border: .8px solid #111; display: flex; font-family: Georgia, 'Times New Roman', serif; font-size: 12pt; font-weight: 800; justify-content: center; letter-spacing: .02em; margin: 0; min-height: 14mm; text-align: center; }
        .annex-box { align-items: center; border: .8px solid #111; display: flex; font-family: Georgia, 'Times New Roman', serif; font-size: 8pt; font-weight: 800; justify-content: center; }
        .report-header { background: #f1f1f1; border: .8px solid #111; display: grid; font-family: Georgia, 'Times New Roman', serif; font-size: 7.4pt; grid-template-columns: 1fr 1fr; margin-bottom: 2mm; }
        .report-header > div { min-height: 15mm; padding: 1.3mm 2mm; }
        .report-header > div + div { border-left: .8px solid #111; }
        .report-header p { margin: 0 0 .65mm; }
        .report-header p:last-child { margin-bottom: 0; }
        .report-section { margin-top: 4mm; }
        .section-title { background: #e2e4e7; border: .8px solid #111; break-after: avoid; font-family: Georgia, 'Times New Roman', serif; font-size: 9pt; font-weight: 800; margin: 0 0 1.5mm; padding: 1.3mm 2mm; text-align: center; text-transform: uppercase; }
        .section-note { font-family: Georgia, 'Times New Roman', serif; font-size: 7pt; line-height: 1.35; margin: 1.5mm 1mm 2mm; }
        table { border-collapse: collapse; width: 100%; }
        thead { display: table-header-group; }
        tfoot { display: table-row-group; }
        tr { break-inside: avoid; page-break-inside: avoid; }
        th, td { border: .65px solid #111; padding: .8mm; vertical-align: top; }
        th { background: #dedfe1; font-size: 6.1pt; text-align: center; text-transform: uppercase; vertical-align: middle; }
        .performance-table { font-family: Georgia, 'Times New Roman', serif; font-size: 5.8pt; table-layout: fixed; }
        .performance-table .kra { background: #eceef1; font-size: 6.4pt; font-weight: 800; letter-spacing: .02em; text-align: center; text-transform: uppercase; vertical-align: middle; }
        .performance-table .objective { font-size: 6.15pt; line-height: 1.2; }
        .performance-table .standards { font-size: 5.15pt; line-height: 1.12; padding: 0; }
        .performance-table .standards div { align-items: stretch; border-bottom: .45px solid #999; display: grid; grid-template-columns: 5mm 1fr; min-height: 5mm; }
        .performance-table .standards div:last-child { border-bottom: 0; }
        .performance-table .standards b { align-items: center; border-right: .45px solid #aaa; display: flex; justify-content: center; }
        .performance-table .standards span { align-items: center; display: flex; padding: .45mm .7mm; }
        .performance-table .center { text-align: center; vertical-align: middle; }
        .performance-table .actual { font-size: 5.7pt; line-height: 1.2; vertical-align: middle; }
        .performance-total-row td { background: #f1f2f4; font-weight: 800; padding: 1.2mm .8mm; text-align: center; vertical-align: middle; }
        .performance-total-row .total-label { font-size: 7pt; letter-spacing: .04em; text-align: right; }
        .performance-total-row .total-adjectival { font-size: 6pt; }
        .performance-total-row small { display: block; font-size: 5pt; letter-spacing: .03em; margin-bottom: .4mm; text-transform: uppercase; }
        .performance-total-row strong { display: block; font-size: 7.5pt; }
        .adjectival-scale-table { break-inside: avoid; font-family: Georgia, 'Times New Roman', serif; margin: 1.5mm auto 0; table-layout: fixed; width: 78%; }
        .adjectival-scale-table th { background: #dedfe1; font-size: 5.8pt; line-height: 1.15; width: 18%; }
        .adjectival-scale-table td { font-size: 5.7pt; font-weight: 700; padding: 1mm .6mm; text-align: center; vertical-align: middle; }
        .adjectival-scale-table .numerical-row td { font-size: 5.5pt; font-weight: 500; }
        .signature-row { break-inside: avoid; display: grid; font-family: Georgia, 'Times New Roman', serif; font-size: 7pt; gap: 12mm; grid-template-columns: repeat(3,1fr); margin: 14mm 10mm 2mm; text-align: center; }
        .signature-line { border-top: .8px solid #111; padding-top: 1mm; position: relative; }
        .signature-image { bottom: calc(100% + .2mm); display: block; height: 10mm; left: 50%; max-width: 50mm; object-fit: contain; position: absolute; transform: translateX(-50%); }
        .signature-line small { display: block; font-size: 5.5pt; margin-top: .4mm; }
        .competency-category { break-inside: auto; margin-bottom: 2mm; }
        .competency-table { font-family: Georgia, 'Times New Roman', serif; font-size: 5.8pt; table-layout: fixed; }
        .competency-table .category-title { background: #d9dde3; font-size: 7.2pt; letter-spacing: .02em; padding: 1mm; }
        .competency-table .column-title { background: #eceef1; font-size: 5.8pt; }
        .competency-entry { line-height: 1.22; padding: 1mm 1.2mm; }
        .competency-name { display: block; font-size: 6.5pt; font-weight: 800; margin-bottom: .5mm; }
        .competency-indicators { margin: 0 0 0 4mm; padding: 0; }
        .competency-indicators li { margin-bottom: .3mm; }
        .competency-rating { font-size: 10pt; font-weight: 800; text-align: center; vertical-align: middle; }
        .competency-footer { align-items: stretch; break-inside: avoid; display: grid; gap: 2mm; grid-template-columns: 1fr 66mm; margin-top: 2mm; }
        .competency-rating-key { border: .7px solid #111; display: grid; font-family: Georgia, 'Times New Roman', serif; font-size: 5.7pt; grid-template-columns: repeat(5,1fr); }
        .competency-rating-key > div { align-items: center; border-right: .5px solid #aaa; display: flex; gap: 1mm; justify-content: center; padding: 1.2mm; text-align: center; }
        .competency-rating-key > div:last-child { border-right: 0; }
        .competency-rating-key b { font-size: 8pt; }
        .competency-summary { font-family: Georgia, 'Times New Roman', serif; font-size: 6pt; }
        .competency-summary th { font-size: 6pt; }
        .competency-summary td:last-child { font-size: 7.5pt; font-weight: 800; text-align: center; vertical-align: middle; }
        .summary-layout { break-inside: avoid; display: grid; gap: 3mm; grid-template-columns: 1fr 1fr; }
        .summary-table { font-family: Georgia, 'Times New Roman', serif; font-size: 7pt; }
        .summary-table td { padding: 1.3mm 2mm; }
        .summary-table td:last-child { font-weight: 800; text-align: center; width: 30%; }
        .agreement-signatories { break-inside: avoid; display: grid; gap: 28mm; grid-template-columns: 1fr 1fr; margin: 12mm 12mm 3mm; }
        .agreement-signatory { font-family: Georgia, 'Times New Roman', serif; text-align: center; }
        .agreement-signature-line { border-bottom: .8px solid #111; height: 12mm; margin-bottom: 1mm; position: relative; }
        .agreement-signature-line img { bottom: 0; height: 11mm; left: 50%; max-width: 52mm; object-fit: contain; position: absolute; transform: translateX(-50%); }
        .agreement-signatory strong { display: block; font-size: 7pt; text-transform: uppercase; }
        .agreement-signatory span { display: block; font-size: 5.7pt; margin-top: .3mm; }
        .agreement-signatory small { display: block; font-size: 5.5pt; margin-top: 1mm; }
        .development-table { font-family: Georgia, 'Times New Roman', serif; font-size: 5.8pt; table-layout: fixed; }
        .development-table td { line-height: 1.25; min-height: 12mm; padding: 1.2mm; }
        .workflow { font-family: Georgia, 'Times New Roman', serif; font-size: 5.8pt; margin-top: 2mm; }
        .workflow-title { background: #eceef1; border: .7px solid #111; border-bottom: 0; break-after: avoid; font-family: Georgia, 'Times New Roman', serif; font-size: 6.5pt; font-weight: 800; margin-top: 2mm; padding: 1mm 1.5mm; text-transform: uppercase; }
        .certification { border: .7px solid #111; break-inside: avoid; font-family: Georgia, 'Times New Roman', serif; font-size: 6.5pt; line-height: 1.35; margin-top: 3mm; padding: 1.8mm 2mm; text-align: center; }
        .empty-row { color: #777; font-style: italic; padding: 5mm !important; text-align: center; }
        @media print {
            body { background: #fff; }
            .report-document { box-shadow: none; margin: 0; max-width: none; padding: 0; }
        }
        @media screen and (max-width: 900px) {
            .report-document { margin: 0; min-width: 1180px; }
        }
    </style>
</head>
<body>
<main class="report-document">
    <div class="document-heading">
        <h1 class="report-title">INDIVIDUAL PERFORMANCE COMMITMENT AND REVIEW FORM (IPCRF)</h1>
        <div class="annex-box">Annex F</div>
    </div>
    <div class="report-header">
        <div>
            <p><b>Name of Employee:</b> <?= $e($form['employee_name']); ?></p>
            <p><b>Employee ID:</b> <?= $e($form['employee_id']); ?></p>
            <p><b>Position:</b> <?= $e($form['position']); ?></p>
            <p><b>Office / Section:</b> <?= $e($form['office']); ?></p>
        </div>
        <div>
            <p><b>Name of Rater:</b> <?= $e($form['rater_name']); ?></p>
            <p><b>Position:</b> <?= $e($form['rater_position']); ?></p>
            <p><b>Review Period:</b> <?= $e($formatPeriod($form['period_start'], $form['period_end'])); ?></p>
            <p><b>Status:</b> <?= $e($form['status']); ?></p>
        </div>
    </div>

    <section class="report-section performance-section">
        <table class="performance-table">
            <colgroup>
                <col style="width:8.5%"><col style="width:12%"><col style="width:6%"><col style="width:4.5%">
                <col style="width:13.5%"><col style="width:13.5%"><col style="width:13.5%"><col style="width:11.5%">
                <col style="width:2.5%"><col style="width:2.5%"><col style="width:2.5%"><col style="width:4.5%"><col style="width:5%">
            </colgroup>
            <thead>
                <tr><th rowspan="2">Key Result Areas (KRA)</th><th rowspan="2">Objectives</th><th rowspan="2">Timeline</th><th rowspan="2">Weight per KRA</th><th colspan="3">Performance Indicators</th><th rowspan="2">Actual Results</th><th colspan="4">Rating</th><th rowspan="2">Score</th></tr>
                <tr><th>Quality</th><th>Efficiency</th><th>Timeliness</th><th>Q</th><th>E</th><th>T</th><th>Average</th></tr>
            </thead>
            <tbody>
            <?php if (!$kraObjectiveGroups): ?>
                <tr><td colspan="13" class="empty-row">No performance objectives have been entered.</td></tr>
            <?php else: ?>
            <?php foreach ($kraObjectiveGroups as $objectives): ?>
                <?php foreach ($objectives as $objective):
                    $q = (float) $objective['quality_rating'];
                    $ef = (float) $objective['efficiency_rating'];
                    $t = (float) $objective['timeliness_rating'];
                    $rating = ($q > 0 && $ef > 0 && $t > 0) ? ($q + $ef + $t) / 3 : 0;
                    $score = $rating > 0 ? $rating * ((float) $objective['weight'] / 100) : 0;
                ?>
                    <tr>
                        <td class="kra"><?= $e($objective['kra_title']); ?></td>
                        <td class="objective"><b><?= $e($objective['code']); ?></b> <?= nl2br($e($objective['objective'])); ?></td>
                        <td class="center"><?= $e($objective['timeline']); ?></td>
                        <td class="center"><b><?= number_format((float) $objective['weight'], 2); ?>%</b></td>
                        <td class="standards"><?= $standardLines($objective['quality']); ?></td>
                        <td class="standards"><?= $standardLines($objective['efficiency']); ?></td>
                        <td class="standards"><?= $standardLines($objective['timeliness']); ?></td>
                        <td class="actual"><?= nl2br($e($objective['accomplishment'])); ?><?php if (!empty($objective['evidence'])): ?><div style="margin-top:1mm"><b>Evidence:</b> <?= count($objective['evidence']); ?> file(s)</div><?php endif; ?></td>
                        <td class="center"><?= $q > 0 ? number_format($q, 0) : ''; ?></td>
                        <td class="center"><?= $ef > 0 ? number_format($ef, 0) : ''; ?></td>
                        <td class="center"><?= $t > 0 ? number_format($t, 0) : ''; ?></td>
                        <td class="center"><?= $rating > 0 ? number_format($rating, 2) : ''; ?></td>
                        <td class="center"><?= $rating > 0 ? number_format($score, 3) : ''; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            <tfoot>
                <tr class="performance-total-row">
                    <td colspan="3" class="total-label">TOTAL</td>
                    <td><small>Total Weight</small><strong><?= number_format((float) $summary['weight'], 2); ?>%</strong></td>
                    <td colspan="8" class="total-adjectival"><small>Adjectival Rating — Based on Total Score</small><strong><?= $e($summary['adjectival']); ?></strong></td>
                    <td><small>Total Score</small><strong><?= number_format((float) $summary['weighted_score'], 3); ?></strong></td>
                </tr>
            </tfoot>
        </table>

        <table class="adjectival-scale-table">
            <tbody>
                <tr><th>Adjectival Rating Scale</th><td>Outstanding</td><td>Very Satisfactory</td><td>Satisfactory</td><td>Unsatisfactory</td><td>Poor</td></tr>
                <tr class="numerical-row"><th>Numerical Rating Scale</th><td>4.500–5.000</td><td>3.500–4.499</td><td>2.500–3.499</td><td>1.500–2.499</td><td>1.000–1.499</td></tr>
            </tbody>
        </table>
        <div class="signature-row">
            <div class="signature-line"><?php if ($employee_signature !== ''): ?><img class="signature-image" src="<?= $e($employee_signature); ?>" alt="Ratee signature"><?php endif; ?><b><?= $e($form['employee_name']); ?></b><small>RATEE</small></div>
            <div class="signature-line"><?php if ($rater_signature !== ''): ?><img class="signature-image" src="<?= $e($rater_signature); ?>" alt="Rater signature"><?php endif; ?><b><?= $e($form['rater_name']); ?></b><small>RATER</small></div>
            <div class="signature-line"><b>APPROVING AUTHORITY</b><small>NAME AND SIGNATURE</small></div>
        </div>
    </section>

    <section class="report-section competencies-section">
        <h2 class="section-title">Part II: Competencies</h2>
        <?php $hasCompetencies = FALSE; ?>
        <?php foreach ($competencyGroups as $group): if (!$group['rows']) continue; $hasCompetencies = TRUE; ?>
            <div class="competency-category">
                <table class="competency-table">
                    <colgroup><col style="width:44%"><col style="width:6%"><col style="width:44%"><col style="width:6%"></colgroup>
                    <thead><tr><th class="category-title" colspan="4"><?= $e($group['title']); ?></th></tr><tr><th class="column-title">Competency and Behavioral Indicators</th><th class="column-title">Rating</th><th class="column-title">Competency and Behavioral Indicators</th><th class="column-title">Rating</th></tr></thead>
                    <tbody>
                    <?php foreach (array_chunk($group['rows'], 2) as $pair): ?>
                        <tr>
                            <?php foreach (array(0, 1) as $pairIndex): ?>
                                <?php if (isset($pair[$pairIndex])): $competency = $pair[$pairIndex]; ?>
                                    <td class="competency-entry"><span class="competency-name"><?= $e($competency['name']); ?></span><ol class="competency-indicators"><?php foreach ((array) $competency['indicators'] as $indicator): ?><li><?= $e($indicator); ?></li><?php endforeach; ?></ol></td>
                                    <td class="competency-rating"><?= (int) $competency['rating'] > 0 ? (int) $competency['rating'] : ''; ?></td>
                                <?php else: ?>
                                    <td class="competency-entry"></td><td class="competency-rating"></td>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
        <?php if (!$hasCompetencies): ?><table><tr><td class="empty-row">No competencies have been entered.</td></tr></table><?php endif; ?>

        <div class="competency-footer">
            <div class="competency-rating-key">
                <div><b>5</b><span>Role Model</span></div><div><b>4</b><span>Consistently Demonstrates</span></div><div><b>3</b><span>Most of the Time Demonstrates</span></div><div><b>2</b><span>Sometimes Demonstrates</span></div><div><b>1</b><span>Rarely Demonstrates</span></div>
            </div>
            <table class="competency-summary"><thead><tr><th colspan="2">Competency Rating Summary</th></tr></thead><tbody>
                <?php foreach ($competencyGroups as $group): if (!$group['rows']) continue; ?><tr><td><?= $e($group['title']); ?></td><td><?= number_format($averageRatings($group['rows']), 2); ?></td></tr><?php endforeach; ?>
                <tr><td><b>Overall Competency Rating</b></td><td><?= number_format($overallCompetencyAverage, 2); ?></td></tr>
            </tbody></table>
        </div>
    </section>

    <section class="report-section summary-section">
        <h2 class="section-title">Part III: Summary of Ratings for Discussion</h2>
        <div class="summary-layout">
            <table class="summary-table"><thead><tr><th colspan="2">Final Performance Results</th></tr></thead><tbody>
                <tr><td>Total Assigned Weight</td><td><?= number_format((float) $summary['weight'], 2); ?>%</td></tr>
                <tr><td>Total Score</td><td><?= number_format((float) $summary['weighted_score'], 3); ?></td></tr>
                <tr><td>Adjectival Rating</td><td><?= $e($summary['adjectival']); ?></td></tr>
            </tbody></table>
            <table class="summary-table"><thead><tr><th colspan="2">Competency Results</th></tr></thead><tbody>
                <?php foreach ($competencyGroups as $group): if (!$group['rows']) continue; ?><tr><td><?= $e($group['title']); ?></td><td><?= number_format($averageRatings($group['rows']), 2); ?></td></tr><?php endforeach; ?>
                <tr><td>Overall Competency Rating</td><td><?= number_format($overallCompetencyAverage, 2); ?></td></tr>
            </tbody></table>
        </div>
        <p class="section-note"><b>Employee–Supervisor Agreement:</b> The signatures below confirm that the employee and the assigned rater have reviewed the performance information captured in this form.</p>
        <div class="agreement-signatories">
            <div class="agreement-signatory">
                <div class="agreement-signature-line"><?php if ($employee_signature !== ''): ?><img src="<?= $e($employee_signature); ?>" alt="Ratee signature"><?php endif; ?></div>
                <strong><?= $e($form['employee_name']); ?></strong>
                <span>EMPLOYEE / RATEE</span>
                <small>Date: ____________________</small>
            </div>
            <div class="agreement-signatory">
                <div class="agreement-signature-line"><?php if ($rater_signature !== ''): ?><img src="<?= $e($rater_signature); ?>" alt="Rater signature"><?php endif; ?></div>
                <strong><?= $e($form['rater_name']); ?></strong>
                <span>RATER / SUPERVISOR</span>
                <small>Date: ____________________</small>
            </div>
        </div>
    </section>

    <section class="report-section development-section">
        <h2 class="section-title">Part IV: Development Plans</h2>
        <table class="development-table">
            <colgroup><col style="width:15%"><col style="width:15%"><col style="width:16%"><col style="width:18%"><col style="width:12%"><col style="width:12%"><col style="width:12%"></colgroup>
            <thead><tr><th rowspan="2">Strengths</th><th rowspan="2">Improvement Needs</th><th colspan="5">Action Plan</th></tr><tr><th>Learning Objective</th><th>Recommended Developmental Intervention</th><th>Timeline</th><th>Responsible Person</th><th>Status / Remarks</th></tr></thead>
            <tbody>
                <?php foreach ((array) $bundle['development'] as $plan): ?><tr><td><?= nl2br($e($plan['strengths'])); ?></td><td><?= nl2br($e($plan['improvement_needs'])); ?></td><td><?= nl2br($e($plan['learning_objectives'])); ?></td><td><?= nl2br($e($plan['interventions'])); ?></td><td><?= $e($plan['target_timeline']); ?></td><td><?= $e($plan['responsible_person']); ?></td><td><?= nl2br($e($plan['status_remarks'])); ?></td></tr><?php endforeach; ?>
                <?php if (!$bundle['development']): ?><tr><td colspan="7" class="empty-row">No development plan has been entered.</td></tr><?php endif; ?>
            </tbody>
        </table>

        <?php if (!empty($bundle['history'])): ?>
            <div class="workflow-title">Workflow History</div>
            <table class="workflow"><thead><tr><th>Workflow Stage</th><th>Action By</th><th>Date</th><th>Remarks</th></tr></thead><tbody>
                <?php foreach (array_reverse($bundle['history']) as $history): ?><tr><td><?= $e($history['to_status']); ?></td><td><?= $e($history['acted_by_name'] ?: $history['acted_by']); ?></td><td><?= $e($history['acted_at']); ?></td><td><?= $e($history['remarks']); ?></td></tr><?php endforeach; ?>
            </tbody></table>
        <?php endif; ?>

        <div class="certification">I certify that the performance information and development plan shown in this Individual Performance Commitment and Review Form have been reviewed according to the approved performance management process.
            <div class="signature-row">
                <div class="signature-line"><?php if ($employee_signature !== ''): ?><img class="signature-image" src="<?= $e($employee_signature); ?>" alt="Ratee signature"><?php endif; ?><b><?= $e($form['employee_name']); ?></b><small>RATEE / DATE</small></div>
                <div class="signature-line"><?php if ($rater_signature !== ''): ?><img class="signature-image" src="<?= $e($rater_signature); ?>" alt="Rater signature"><?php endif; ?><b><?= $e($form['rater_name']); ?></b><small>RATER / DATE</small></div>
                <div class="signature-line"><b>APPROVING AUTHORITY / PMT</b><small>SIGNATURE / DATE</small></div>
            </div>
        </div>
    </section>
</main>
<?php if ($autoprint): ?><script>window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 450); });</script><?php endif; ?>
</body>
</html>
