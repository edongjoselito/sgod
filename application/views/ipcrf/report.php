<?php
$form = $bundle['form'];
$ratingsApproved = !empty($summary['ratings_approved']);
$kraObjectiveGroups = array();
$kraSequence = 0;
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
    $kraSequence++;
    $groupObjectives = array();
    foreach ($kra['objectives'] as $objective) {
        if ($isEmptyObjective($objective)) continue;
        $objective['kra_title'] = $kraTitle;
        $objective['kra_group_key'] = !empty($kra['id']) ? 'kra-' . (int) $kra['id'] : 'kra-sequence-' . $kraSequence;
        $groupObjectives[] = $objective;
    }
    if ($groupObjectives) $kraObjectiveGroups[] = $groupObjectives;
}

// Keep every KRA intact while balancing the visible objectives across pages 1–4.
// This prevents a KRA title from being repeated merely because an equal-row page
// split landed in the middle of its objectives.
$objectivePages = array();
$groupCount = count($kraObjectiveGroups);
$reportPageCount = min(4, $groupCount);
$remainingObjectiveCount = 0;
foreach ($kraObjectiveGroups as $groupObjectives) $remainingObjectiveCount += count($groupObjectives);
$groupIndex = 0;
for ($reportPage = 0; $reportPage < $reportPageCount; $reportPage++) {
    $remainingPages = $reportPageCount - $reportPage;
    $pageObjectives = array();
    if ($remainingPages === 1) {
        while ($groupIndex < $groupCount) {
            $pageObjectives = array_merge($pageObjectives, $kraObjectiveGroups[$groupIndex]);
            $groupIndex++;
        }
    } else {
        $targetRows = (int) ceil($remainingObjectiveCount / $remainingPages);
        $pageRowCount = 0;
        while ($groupIndex < $groupCount) {
            $groupObjectives = $kraObjectiveGroups[$groupIndex];
            $groupRowCount = count($groupObjectives);
            $groupsAfter = $groupCount - ($groupIndex + 1);
            $pagesAfter = $remainingPages - 1;
            if (
                $pageRowCount > 0 &&
                $groupsAfter >= $pagesAfter &&
                abs($targetRows - ($pageRowCount + $groupRowCount)) > abs($targetRows - $pageRowCount)
            ) {
                break;
            }
            $pageObjectives = array_merge($pageObjectives, $groupObjectives);
            $pageRowCount += $groupRowCount;
            $remainingObjectiveCount -= $groupRowCount;
            $groupIndex++;
            if ($groupsAfter === $pagesAfter) break;
        }
    }
    $objectivePages[] = $pageObjectives;
}
while (count($objectivePages) < 4) $objectivePages[] = array();
foreach ($objectivePages as &$pageObjectives) {
    $pageObjectiveCount = count($pageObjectives);
    for ($objectiveIndex = 0; $objectiveIndex < $pageObjectiveCount; $objectiveIndex++) {
        $groupKey = $pageObjectives[$objectiveIndex]['kra_group_key'];
        $isGroupStart = $objectiveIndex === 0 || $pageObjectives[$objectiveIndex - 1]['kra_group_key'] !== $groupKey;
        $pageObjectives[$objectiveIndex]['kra_group_start'] = $isGroupStart;
        if (!$isGroupStart) continue;
        $rowspan = 1;
        while ($objectiveIndex + $rowspan < $pageObjectiveCount && $pageObjectives[$objectiveIndex + $rowspan]['kra_group_key'] === $groupKey) {
            $rowspan++;
        }
        $pageObjectives[$objectiveIndex]['kra_rowspan'] = $rowspan;
    }
}
unset($pageObjectives);
$e = function ($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$formatPeriod = function ($start, $end) {
    return date('F Y', strtotime($start)) . ' to ' . date('F Y', strtotime($end));
};
$standardLines = function ($values) use ($e) {
    $html = '';
    foreach (array('5', '4', '3', '2', '1') as $level) {
        $html .= '<div><b>' . $level . ' –</b> ' . $e(isset($values[$level]) ? $values[$level] : '') . '</div>';
    }
    return $html;
};
$competencyAverage = function () use ($bundle) {
    $values = array();
    foreach ($bundle['competencies'] as $competency) if ((int) $competency['rating'] > 0) $values[] = (int) $competency['rating'];
    return $values ? array_sum($values) / count($values) : 0;
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IPCRF – <?= $e($form['employee_name']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        @page { size: landscape; margin: 7mm; }
        * { box-sizing: border-box; }
        body { background: #e8ebef; color: #111; font-family: Arial, Helvetica, sans-serif; margin: 0; }
        .print-tools { align-items: center; background: #16264b; color: #fff; display: flex; gap: 10px; justify-content: center; padding: 10px; position: sticky; top: 0; z-index: 10; }
        .print-tools button { background: #fff; border: 0; border-radius: 5px; color: #16264b; cursor: pointer; font-weight: 700; padding: 8px 13px; }
        .print-book { margin: 14px auto; width: min(1400px, calc(100% - 24px)); }
        .print-page { background: #fff; box-shadow: 0 5px 24px rgba(0,0,0,.13); min-height: 190mm; margin-bottom: 14px; overflow: hidden; padding: 5mm; page-break-after: always; position: relative; }
        .print-page:last-child { page-break-after: auto; }
        .report-title { font-family: Georgia, 'Times New Roman', serif; font-size: 12pt; font-weight: 800; letter-spacing: .02em; margin: 0 0 2mm; text-align: center; }
        .report-header { border: 1px solid #111; display: grid; font-family: Georgia, 'Times New Roman', serif; font-size: 7.5pt; grid-template-columns: 1fr 1fr; margin-bottom: 2mm; }
        .report-header > div { min-height: 12mm; padding: 1.5mm 2mm; }
        .report-header > div + div { border-left: 1px solid #111; }
        .report-header p { margin: 0 0 .7mm; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: .7px solid #111; padding: 1mm; vertical-align: top; }
        th { background: #eef1f5; font-size: 6.4pt; text-align: center; text-transform: uppercase; vertical-align: middle; }
        .performance-table { font-family: Georgia, 'Times New Roman', serif; font-size: 6pt; table-layout: fixed; }
        .performance-table .kra { background: #e2e6ec; font-size: 6.6pt; font-weight: 800; letter-spacing: .025em; text-align: center; text-transform: uppercase; vertical-align: middle; }
        .performance-table .objective { font-size: 6.3pt; line-height: 1.17; }
        .performance-table .standards { font-size: 5.35pt; line-height: 1.14; padding: .7mm; }
        .performance-table .standards div { border-bottom: .4px solid #aaa; padding: .35mm 0; }
        .performance-table .standards div:last-child { border-bottom: 0; }
        .performance-table .center { text-align: center; vertical-align: middle; }
        .performance-table .actual { font-size: 5.8pt; line-height: 1.2; }
        .page-number { bottom: 2mm; color: #555; font-size: 6pt; position: absolute; right: 5mm; }
        .continued { color: #555; font-family: Georgia, 'Times New Roman', serif; font-size: 6pt; margin: 0 0 1mm; text-align: right; }
        .total-row { font-family: Georgia, 'Times New Roman', serif; font-size: 8pt; font-weight: 800; text-align: right; }
        .signature-row { display: grid; font-family: Georgia, 'Times New Roman', serif; font-size: 7pt; gap: 12mm; grid-template-columns: repeat(3,1fr); margin: 13mm 10mm 0; text-align: center; }
        .signature-line { border-top: 1px solid #111; padding-top: 1mm; }
        .competency-layout { display: grid; gap: 2mm; grid-template-columns: repeat(3,1fr); }
        .competency-group { break-inside: avoid; }
        .competency-group h3 { background: #dfe5ed; border: 1px solid #111; font-family: Georgia, 'Times New Roman', serif; font-size: 7.5pt; margin: 0; padding: 1.2mm; text-align: center; text-transform: uppercase; }
        .competency-table { font-family: Georgia, 'Times New Roman', serif; font-size: 5.8pt; table-layout: fixed; }
        .competency-table td { line-height: 1.2; padding: 1mm; }
        .competency-name { font-size: 6.7pt; font-weight: 800; }
        .competency-indicators { margin: .6mm 0 0 3mm; padding: 0; }
        .competency-indicators li { margin-bottom: .25mm; }
        .rating-key { border: 1px solid #111; font-family: Georgia, 'Times New Roman', serif; font-size: 6.2pt; margin-top: 2mm; padding: 1.2mm; text-align: center; }
        .page-six-grid { display: grid; gap: 4mm; grid-template-columns: 32% 68%; }
        .summary-card { border: 1px solid #111; margin-bottom: 3mm; }
        .summary-card h3 { background: #dfe5ed; border-bottom: 1px solid #111; font-family: Georgia, 'Times New Roman', serif; font-size: 8pt; margin: 0; padding: 1.5mm; text-align: center; text-transform: uppercase; }
        .summary-card table { font-family: Georgia, 'Times New Roman', serif; font-size: 7pt; }
        .summary-card td { padding: 1.4mm; }
        .summary-card td:last-child { font-weight: 800; text-align: center; }
        .development-table { font-family: Georgia, 'Times New Roman', serif; font-size: 5.9pt; table-layout: fixed; }
        .development-table td { height: 13mm; line-height: 1.2; }
        .workflow { font-family: Georgia, 'Times New Roman', serif; font-size: 6pt; }
        .workflow td { padding: 1mm; }
        .certification { border: 1px solid #111; font-family: Georgia, 'Times New Roman', serif; font-size: 7pt; line-height: 1.35; margin-top: 3mm; padding: 2mm; text-align: center; }
        .empty-row { color: #777; font-style: italic; padding: 8mm !important; text-align: center; }
        @media print {
            body { background: #fff; }
            .print-tools { display: none; }
            .print-book { margin: 0; width: 100%; }
            .print-page { box-shadow: none; height: 190mm; margin: 0; min-height: 0; }
        }
    </style>
</head>
<body>
<div class="print-tools"><span>DepEd-formatted six-page IPCRF preview</span><button type="button" onclick="window.print()">Print / Save as PDF</button><button type="button" onclick="window.close()">Close</button></div>
<div class="print-book">
    <?php foreach ($objectivePages as $pageIndex => $pageObjectives): ?>
    <section class="print-page">
        <?php if ($pageIndex === 0): ?>
            <h1 class="report-title">INDIVIDUAL PERFORMANCE COMMITMENT AND REVIEW FORM (IPCRF)</h1>
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
        <?php else: ?>
            <p class="continued">IPCRF Performance Standards – continued · <?= $e($form['employee_name']); ?></p>
        <?php endif; ?>
        <table class="performance-table">
            <colgroup>
                <col style="width:9%"><col style="width:12%"><col style="width:6%"><col style="width:4.5%">
                <col style="width:13%"><col style="width:13%"><col style="width:13%"><col style="width:12%">
                <col style="width:2.5%"><col style="width:2.5%"><col style="width:2.5%"><col style="width:4%"><col style="width:5.5%">
            </colgroup>
            <thead><tr><th>Key Result Area</th><th>Objectives</th><th>Timeline</th><th>Weight</th><th>Quality</th><th>Efficiency</th><th>Timeliness</th><th>Actual Results</th><th>Q</th><th>E</th><th>T</th><th>Rating</th><th>Score</th></tr></thead>
            <tbody>
            <?php if (!$pageObjectives): ?>
                <tr><td colspan="13" class="empty-row">No objective assigned to this report page.</td></tr>
            <?php endif; ?>
            <?php foreach ($pageObjectives as $objective):
                $q = (float) $objective['quality_rating']; $ef = (float) $objective['efficiency_rating']; $t = (float) $objective['timeliness_rating'];
                $rating = ($q > 0 && $ef > 0 && $t > 0) ? ($q + $ef + $t) / 3 : 0;
                $score = $ratingsApproved ? $rating * ((float) $objective['weight'] / 100) : 0;
            ?>
                <tr>
                    <?php if (!empty($objective['kra_group_start'])): ?><td class="kra center" rowspan="<?= (int) $objective['kra_rowspan']; ?>"><?= $e($objective['kra_title']); ?></td><?php endif; ?>
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
                    <td class="center"><?= $score > 0 ? number_format($score, 3) : ($rating > 0 && !$ratingsApproved ? 'Pending' : ''); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php if ($pageIndex === 3): ?>
            <table style="margin-top:2mm"><tr><td class="total-row">TOTAL WEIGHT: <?= number_format((float) $summary['weight'], 2); ?>%</td><td class="total-row" style="width:22%">WEIGHTED SCORE: <?= $ratingsApproved ? number_format((float) $summary['weighted_score'], 3) : 'PENDING RATER APPROVAL'; ?></td></tr></table>
            <div class="signature-row"><div class="signature-line"><b><?= $e($form['employee_name']); ?></b><br>RATEE</div><div class="signature-line"><b><?= $e($form['rater_name']); ?></b><br>RATER</div><div class="signature-line"><b>APPROVING AUTHORITY</b><br>NAME AND SIGNATURE</div></div>
        <?php endif; ?>
        <span class="page-number">Page <?= $pageIndex + 1; ?> of 6</span>
    </section>
    <?php endforeach; ?>

    <section class="print-page">
        <h1 class="report-title">COMPETENCIES</h1>
        <div class="competency-layout">
            <?php foreach (array('Core Behavioral Competency', 'Core Skill', 'Leadership Competency') as $category):
                $categoryRows = array_values(array_filter($bundle['competencies'], function ($competency) use ($category) { return $competency['category'] === $category; }));
                if (!$categoryRows && $category === 'Leadership Competency') continue;
            ?>
            <div class="competency-group">
                <h3><?= $e($category === 'Core Behavioral Competency' ? 'Core Behavioral Competencies' : ($category === 'Core Skill' ? 'Core Skills' : 'Leadership Competencies')); ?></h3>
                <table class="competency-table">
                    <colgroup><col style="width:88%"><col style="width:12%"></colgroup>
                    <thead><tr><th>Competency and Indicators</th><th>Rating</th></tr></thead>
                    <tbody>
                    <?php foreach ($categoryRows as $competency): ?>
                    <tr><td><span class="competency-name"><?= $e($competency['name']); ?></span><ol class="competency-indicators"><?php foreach ($competency['indicators'] as $indicator): ?><li><?= $e($indicator); ?></li><?php endforeach; ?></ol></td><td style="text-align:center;vertical-align:middle"><?= (int) $competency['rating'] ?: ''; ?></td></tr>
                    <?php endforeach; ?>
                    <?php if (!$categoryRows): ?><tr><td colspan="2" class="empty-row">No competencies in this category.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="rating-key"><b>COMPETENCY RATING SCALE:</b> 5 – Role Model &nbsp; | &nbsp; 4 – Consistently Demonstrates &nbsp; | &nbsp; 3 – Most of the Time Demonstrates &nbsp; | &nbsp; 2 – Sometimes Demonstrates &nbsp; | &nbsp; 1 – Rarely Demonstrates</div>
        <div class="signature-row" style="margin-top:8mm"><div class="signature-line"><b><?= $e($form['employee_name']); ?></b><br>RATEE</div><div class="signature-line"><b><?= $e($form['rater_name']); ?></b><br>RATER</div><div class="signature-line"><b>PMT VALIDATOR</b><br>NAME AND SIGNATURE</div></div>
        <span class="page-number">Page 5 of 6</span>
    </section>

    <section class="print-page">
        <h1 class="report-title">RATING SUMMARY AND DEVELOPMENT PLAN</h1>
        <div class="page-six-grid">
            <div>
                <div class="summary-card"><h3>Performance Rating Summary</h3><table>
                    <tr><td>Total Weight</td><td><?= number_format((float) $summary['weight'], 2); ?>%</td></tr>
                    <tr><td>Weighted Score</td><td><?= $ratingsApproved ? number_format((float) $summary['weighted_score'], 3) : 'Pending rater approval'; ?></td></tr>
                    <tr><td>Overall Rating</td><td><?= $ratingsApproved ? number_format((float) $summary['overall_rating'], 3) : 'Pending rater approval'; ?></td></tr>
                    <tr><td>Adjectival Rating</td><td><?= $e($summary['adjectival']); ?></td></tr>
                </table></div>
                <div class="summary-card"><h3>Competency Rating Summary</h3><table>
                    <tr><td>Average Rating</td><td><?= number_format($competencyAverage(), 2); ?></td></tr>
                </table></div>
                <div class="summary-card"><h3>Adjectival Scale</h3><table>
                    <tr><td>Outstanding</td><td>4.500–5.000</td></tr><tr><td>Very Satisfactory</td><td>3.500–4.499</td></tr><tr><td>Satisfactory</td><td>2.500–3.499</td></tr><tr><td>Unsatisfactory</td><td>1.500–2.499</td></tr><tr><td>Poor</td><td>1.000–1.499</td></tr>
                </table></div>
            </div>
            <div>
                <table class="development-table">
                    <thead><tr><th>Strengths</th><th>Improvement Needs</th><th>Learning Objectives</th><th>Developmental Interventions</th><th>Target Timeline</th><th>Responsible Person</th><th>Status / Remarks</th></tr></thead>
                    <tbody>
                    <?php foreach ($bundle['development'] as $plan): ?><tr><td><?= nl2br($e($plan['strengths'])); ?></td><td><?= nl2br($e($plan['improvement_needs'])); ?></td><td><?= nl2br($e($plan['learning_objectives'])); ?></td><td><?= nl2br($e($plan['interventions'])); ?></td><td><?= $e($plan['target_timeline']); ?></td><td><?= $e($plan['responsible_person']); ?></td><td><?= nl2br($e($plan['status_remarks'])); ?></td></tr><?php endforeach; ?>
                    <?php if (!$bundle['development']): ?><tr><td colspan="7" class="empty-row">No development plan has been entered.</td></tr><?php endif; ?>
                    </tbody>
                </table>
                <table class="workflow" style="margin-top:3mm"><thead><tr><th>Workflow Stage</th><th>Action By</th><th>Date</th><th>Remarks</th></tr></thead><tbody>
                    <?php foreach (array_reverse($bundle['history']) as $history): ?><tr><td><?= $e($history['to_status']); ?></td><td><?= $e($history['acted_by_name'] ?: $history['acted_by']); ?></td><td><?= $e($history['acted_at']); ?></td><td><?= $e($history['remarks']); ?></td></tr><?php endforeach; ?>
                </tbody></table>
                <div class="certification">I certify that the performance information and development plan shown in this Individual Performance Commitment and Review Form have been reviewed according to the approved performance management process.<div class="signature-row" style="margin-top:10mm"><div class="signature-line"><b><?= $e($form['employee_name']); ?></b><br>RATEE / DATE</div><div class="signature-line"><b><?= $e($form['rater_name']); ?></b><br>RATER / DATE</div><div class="signature-line"><b>APPROVING AUTHORITY / PMT</b><br>SIGNATURE / DATE</div></div></div>
            </div>
        </div>
        <span class="page-number">Page 6 of 6</span>
    </section>
</div>
<?php if ($autoprint): ?><script>window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 350); });</script><?php endif; ?>
</body>
</html>
