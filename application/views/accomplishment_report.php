<?php
$record = isset($record) ? $record : null;
$reportGroups = isset($reportGroups) && is_array($reportGroups) ? $reportGroups : array();
$attachments = $record && isset($reportGroups[$record->id]) ? $reportGroups[$record->id] : array();
$escape = function($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$value = function($field) use ($record) { return $record && isset($record->$field) && trim((string) $record->$field) !== '' ? (string) $record->$field : '—'; };
$generatedAt = new DateTime('now', new DateTimeZone('Asia/Manila'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?= $escape(isset($title) ? $title : 'Accomplishment Report'); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
<style>
*{box-sizing:border-box}body{margin:0;background:#eef2f8;color:#26344d;font:14px/1.65 -apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}.report-shell{max-width:960px;margin:30px auto 60px;padding:0 24px}.toolbar{display:flex;justify-content:space-between;gap:16px;align-items:center;margin-bottom:20px}.toolbar-label{font-size:12px;color:#77839a}.toolbar-actions{display:flex;gap:10px;flex-wrap:wrap}.button{display:inline-flex;align-items:center;justify-content:center;border:1px solid #d6deec;background:#fff;border-radius:9px;padding:10px 17px;font:600 13px/1.4 inherit;color:#344568;text-decoration:none;cursor:pointer}.button-primary{background:#354ca0;border-color:#354ca0;color:#fff}.report-paper{background:#fff;border:1px solid #e0e6ef;border-radius:16px;box-shadow:0 16px 48px #2034570c;overflow:hidden}.report-header{padding:38px 44px 30px;border-top:6px solid #354ca0;border-bottom:1px solid #e8edf5}.eyebrow{font-size:10px;letter-spacing:1.8px;text-transform:uppercase;font-weight:700;color:#536eae}.report-header h1{font-size:30px;line-height:1.25;letter-spacing:-.7px;margin:10px 0 16px;color:#203459}.header-meta{display:flex;justify-content:space-between;flex-wrap:wrap;gap:8px;font-size:12px;color:#718098}.section-badge{display:inline-block;background:#edf1fc;color:#3e568e;padding:5px 11px;border-radius:6px;font-size:12px;font-weight:600}.report-body{padding:32px 44px}.activity-title{font-size:22px;line-height:1.45;margin:8px 0 24px;color:#213758;overflow-wrap:anywhere}.summary-grid{display:grid;grid-template-columns:1fr 1fr;background:#f7f9fd;border:1px solid #e7ecf5;border-radius:10px;margin:0 0 30px}.summary-cell{padding:17px 20px}.summary-cell dt{font-size:10px;text-transform:uppercase;letter-spacing:1px;color:#73829c;font-weight:700;margin-bottom:5px}.summary-cell dd{margin:0;font-size:13px;font-weight:600;overflow-wrap:anywhere}.report-section{margin:28px 0}.section-heading{display:flex;align-items:center;gap:12px;margin:0 0 16px;font-size:15px;color:#283f65}.section-heading:after{content:"";height:1px;background:#e5eaf3;flex:1}.detail-list{margin:0}.detail-row{display:grid;grid-template-columns:130px minmax(0,1fr);gap:20px;padding:12px 0;border-bottom:1px solid #eff2f7}.detail-row:last-child{border:0}.detail-row dt{font-size:12px;font-weight:600;color:#75819a}.detail-row dd{margin:0;white-space:pre-wrap;overflow-wrap:anywhere}.narrative{white-space:pre-wrap;overflow-wrap:anywhere;color:#45546e}.attachments{list-style:none;padding:0;margin:0}.attachment{display:flex;gap:14px;align-items:center;border:1px solid #e4eaf3;border-radius:9px;padding:13px 16px;margin:10px 0;break-inside:avoid}.attachment-number{font-size:12px;color:#627ab4;background:#eef2fc;padding:5px 9px;border-radius:6px}.attachment-name{flex:1;font-weight:600;overflow-wrap:anywhere}.attachment a{color:#405baa;text-decoration:none;font-size:12px;white-space:nowrap}.empty-state{color:#8190a5;font-size:13px;background:#f8fafd;padding:18px;border-radius:8px}.report-footer{display:flex;justify-content:space-between;gap:12px;padding:18px 44px;border-top:1px solid #e8edf5;font-size:10px;color:#7b879d}.count{font-size:11px;color:#7083ab;font-weight:500}a:focus-visible,button:focus-visible{outline:3px solid #91a9ef;outline-offset:3px}
@media(max-width:600px){.report-shell{padding:0 12px;margin-top:16px}.toolbar{align-items:flex-start;flex-direction:column}.toolbar-label{display:none}.report-header,.report-body{padding:24px}.report-header h1{font-size:25px}.activity-title{font-size:19px}.summary-grid{grid-template-columns:1fr}.summary-cell{padding:12px 16px}.detail-row{grid-template-columns:1fr;gap:3px}.report-footer{padding:16px 24px;flex-direction:column}.attachment{flex-wrap:wrap}}
@page{size:A4 portrait;margin:16mm}
@media print{body{background:#fff;color:#111;font-size:10pt}.report-shell{max-width:none;margin:0;padding:0}.toolbar,.attachment a{display:none!important}.report-paper{border:0;border-radius:0;box-shadow:none;overflow:visible}.report-header{padding:0 0 20px;border-top:0;border-bottom:2px solid #354ca0}.report-header h1{font-size:23pt}.report-body{padding:22px 0}.report-footer{padding:14px 0}.activity-title{font-size:16pt}.summary-grid{grid-template-columns:1fr 1fr;break-inside:avoid}.detail-row{grid-template-columns:110px minmax(0,1fr);gap:16px;break-inside:avoid}.section-heading{break-after:avoid}.report-section{margin:20px 0}.attachment{break-inside:avoid}.header-meta{font-size:9pt}.summary-cell{padding:12px 16px}}
</style>
</head>
<body>
<main class="report-shell">
<nav class="toolbar" aria-label="Report actions"><a class="button" href="<?= site_url('Page/viewSecAccomplishments'); ?>">← Back to Accomplishments</a><div class="toolbar-actions"><span class="toolbar-label">A4 · Portrait</span><button type="button" class="button button-primary" onclick="window.print()">Print / Save as PDF</button></div></nav>
<article class="report-paper">
<header class="report-header"><div class="eyebrow">Accomplishment documentation</div><h1>Accomplishment Report</h1></header>
<div class="report-body">
<?php if ($record): ?>
<div class="eyebrow">Activity / Accomplishment</div><h2 class="activity-title"><?= $escape($value('activity')); ?></h2>
<dl class="summary-grid">
<?php foreach (array('activityCategory'=>'Category','dateConducted'=>'Date Conducted','venue'=>'Venue','accomplishmentScope'=>'Scope') as $field=>$label): ?>
<div class="summary-cell"><dt><?= $escape($label); ?></dt><dd><?= $escape($value($field)); ?></dd></div>
<?php endforeach; ?>
</dl>
<?php if (trim((string) ($record->particulars ?? '')) !== ''):
    $particulars = preg_replace('/<\s*br\s*\/?\s*>|<\/\s*(p|div|li|h[1-6])\s*>/i', "\n", (string) $record->particulars);
    $particulars = trim(html_entity_decode(strip_tags($particulars), ENT_QUOTES, 'UTF-8'));
?>
<section class="report-section"><h2 class="section-heading">Activity Details</h2><div class="narrative"><?= $escape($particulars); ?></div></section>
<?php endif; ?>
<section class="report-section"><h2 class="section-heading">Resources &amp; Observations</h2><dl class="detail-list">
<?php foreach (array('resources'=>'Resources','notes'=>'Notes','remarks'=>'Remarks') as $field=>$label): ?>
<div class="detail-row"><dt><?= $escape($label); ?></dt><dd><?= $escape($value($field)); ?></dd></div>
<?php endforeach; ?>
</dl></section>
<section class="report-section"><h2 class="section-heading">Supporting Documents <span class="count"><?= count($attachments); ?> attached</span></h2>
<?php if ($attachments): ?><ol class="attachments">
<?php foreach ($attachments as $index=>$attachment): ?>
<li class="attachment"><span class="attachment-number"><?= str_pad((string) ($index+1), 2, '0', STR_PAD_LEFT); ?></span><span class="attachment-name"><?= $escape(trim((string) $attachment->document_name) !== '' ? $attachment->document_name : 'Attachment'); ?></span><a href="<?= base_url('upload/accomplishment_reports/' . rawurlencode($attachment->stored_name)); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= $escape('Open ' . ($attachment->document_name ?: 'attachment')); ?>">Open document ↗</a></li>
<?php endforeach; ?></ol>
<?php else: ?><p class="empty-state">No supporting documents attached.</p><?php endif; ?>
</section>
<?php else: ?><p class="empty-state" role="alert">Accomplishment record not found.</p><?php endif; ?>
</div>
<footer class="report-footer"><span>Accomplishment Report<?= $record ? ' · #' . (int) $record->id : ''; ?></span><span>Generated <?= $escape($generatedAt->format('M d, Y · g:i A')); ?> (PHT)</span></footer>
</article>
</main>
</body>
</html>
