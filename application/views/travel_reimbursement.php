<?php defined('BASEPATH') OR exit('No direct script access allowed');
$esc=function($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');};
$money=function($v){return 'PHP '.number_format((int)$v/100,2);};
$hidden=function()use($token,$esc){echo '<input type="hidden" name="claim_token" value="'.$esc($token).'">';};
if ($mode === 'queue') {
    $totalExpenses = 0; $totalNet = 0; $documentCount = 0;
    foreach ($claims as $queueClaim) {
        $totalExpenses += (int) $queueClaim['total_cents'];
        $totalNet += max(0, (int) $queueClaim['total_cents'] - (int) $queueClaim['advance_cents']);
        if (!empty($queueClaim['document_path'])) $documentCount++;
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Travel Reimbursement</title>
<link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet"><link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet"><link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet"><link href="<?= base_url(); ?>assets/css/dashboard-unified.css" rel="stylesheet">
<?php if ($mode === 'queue'): ?>
<link href="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet">
<?php endif; ?>
<style>
.claim-hero{padding:28px;border-radius:18px;background:linear-gradient(120deg,#222968,#415bc0);color:white;margin-bottom:24px}
.claim-hero h1{color:white;font-size:1.7rem}
.claim-card{border:1px solid #e5eaf2;border-radius:16px;padding:24px;background:white;margin-bottom:24px}
.claim-card h2{font-size:1.2rem;margin-bottom:18px}
.claim-summary{display:flex;gap:24px;flex-wrap:wrap}
.claim-summary strong{display:block;font-size:1.2rem}
.claim-summary span{color:#64748b}
.claim-form label{font-weight:600}
.claim-form input,.claim-form select,.claim-form .btn{min-height:44px}
.claim-form input[type=checkbox]{min-height:0}
.claim-check{display:flex;gap:10px;align-items:baseline}
.claim-check input{flex-shrink:0}
.claim-table td{vertical-align:middle;min-width:110px}
.claim-table .description{min-width:210px}
.claim-links{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:18px}
.claim-note{color:#65728a;font-size:.9rem}
.itinerary-details{padding:20px;border:1px solid #e5eaf2;border-radius:14px;background:#f8faff}
.itinerary-subheading{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:18px}
.itinerary-subheading h3{margin:0;color:#26356f;font-size:1rem;font-weight:700}
.itinerary-subheading p{margin:4px 0 0;color:#65728a;font-size:.9rem}
.itinerary-entry{margin-bottom:0}
.itinerary-entry thead th{background:#f1f4fa;color:#344267;text-align:center;vertical-align:middle;border-color:#e1e7f0;font-size:.82rem}
.itinerary-entry tbody td{vertical-align:top;padding:10px;border-color:#e7ebf2;background:#fff}
.itinerary-entry tbody tr:hover td{background:#fbfcff}
.itinerary-entry .form-control{border-color:#dce3ed;border-radius:8px}
.itinerary-entry .form-control:focus{border-color:#667eea;box-shadow:0 0 0 .18rem rgba(102,126,234,.15)}
.itinerary-entry .row-total{display:inline-block;white-space:nowrap;font-weight:700;color:#26356f;padding-top:9px}
.itinerary-entry tfoot th,.itinerary-entry tfoot td{background:#f1f4fa;color:#26356f;font-weight:700}
.itinerary-entry .remove-expense{min-width:40px;min-height:40px;padding:0;font-size:1.25rem;line-height:1}
.itinerary-entry-wrap{border:1px solid #e1e7f0;border-radius:12px;overflow-x:auto}
.expense-actions{display:flex;justify-content:flex-end;margin-top:14px}
/* Entry cards adapt to the space left by the sidebar at every viewport size. */
    .itinerary-entry-wrap{overflow:visible;border:0}
    .itinerary-entry{display:block!important;width:100%!important;min-width:0!important;table-layout:fixed}
    .itinerary-entry thead{display:none!important}
    .itinerary-entry tbody{display:grid!important;width:100%;gap:14px}
    .itinerary-entry tbody tr{display:grid!important;width:100%;min-width:0;grid-template-columns:repeat(auto-fit,minmax(min(100%,220px),1fr));gap:16px;padding:14px;border:1px solid #e1e7f0;border-radius:14px;background:#fff;box-shadow:0 5px 16px rgba(29,45,92,.06)}
    .itinerary-entry tbody td{display:block!important;width:auto!important;min-width:0!important;max-width:100%;padding:0;border:0;background:transparent!important}
    .itinerary-entry tbody td .form-control{width:100%;min-width:0}
    .itinerary-entry tbody td label{display:block;width:100%;min-width:0}
    .itinerary-entry tbody td:before{content:attr(data-label);display:block;margin:0 0 5px;color:#64748b;font-size:.72rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase}
    .itinerary-entry tbody td[data-label="Total Amount"]{align-self:end;padding:9px 0}
    .itinerary-entry tbody td[data-label="Actions"]{grid-column:1/-1}
    .itinerary-row-actions{display:flex;flex-wrap:wrap;gap:8px}
    .itinerary-row-actions .btn{flex:1 1 auto;margin:0}
    .itinerary-entry tfoot{display:block!important;width:100%;margin-top:14px}
    .itinerary-entry tfoot tr{display:grid!important;width:100%;grid-template-columns:1fr auto;padding:12px 14px;border-radius:10px;background:#f1f4fa}
    .itinerary-entry tfoot th,.itinerary-entry tfoot td{display:block!important;padding:0;border:0;background:transparent}
    .itinerary-entry tfoot th{grid-column:1;min-width:0}
    .itinerary-entry tfoot #itinerary-total{grid-column:2;grid-row:1}
    .itinerary-entry tfoot td{display:none!important}
@media(max-width:767px){
    .itinerary-details{padding:16px}
    .expense-actions{justify-content:stretch}
    .expense-actions .btn{width:100%}
}
@media(max-width:480px){
    .itinerary-entry tbody tr{grid-template-columns:1fr}
    .itinerary-entry tbody td[data-label="Date"],.itinerary-entry tbody td[data-label="Places to Be Visited"]{grid-column:auto}
    .itinerary-entry tbody td[data-label="Actions"]{grid-column:1}
}
@media(max-width:575px){.claim-card{padding:16px}.claim-hero{padding:20px}.claim-form .btn{margin-bottom:8px}.claim-summary{gap:16px}.claim-summary>div{flex-basis:100%}}

.reimbursement-queue .claim-hero{background:linear-gradient(115deg,#253b77,#5056b8);padding:28px 30px;border-radius:18px;box-shadow:0 8px 28px #263e7614;margin-bottom:20px}.reimbursement-queue .claim-hero h1{font-size:30px;margin-bottom:10px}.reimbursement-queue .claim-hero p{font-size:13px;color:#dfe6ff}.claim-overview{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;margin-bottom:24px}.claim-metric{display:flex;align-items:center;gap:12px;padding:19px;border:1px solid #e1e7f1;border-radius:14px;background:#fff}.claim-metric-icon{display:grid;place-items:center;width:40px;height:40px;flex-shrink:0;border-radius:12px;background:#eef0ff;color:#535bb4;font-size:22px}.claim-metric:nth-child(2) .claim-metric-icon{background:#eaf3fc;color:#397eb7}.claim-metric:nth-child(3) .claim-metric-icon{background:#e9f6f0;color:#288169}.claim-metric strong{display:block;font-size:21px;color:#263b60;line-height:1.4;overflow-wrap:anywhere}.claim-metric div{min-width:0}.claim-metric div>span{font-size:11px;color:#728199}.reimbursement-queue .claim-card{box-shadow:0 5px 22px #21375805}.claim-list-heading{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap}.claim-list-heading h2{font-size:22px;color:#283e64;margin:8px 0}.claim-kicker{font-size:10px;text-transform:uppercase;letter-spacing:1.3px;color:#647aab}.reimbursement-queue .btn{font-size:12px;border-radius:8px;padding:10px 14px}.reimbursement-queue .btn-primary{background:#4054a4;border-color:#4054a4}.reimbursement-queue .claim-note{font-size:12px;margin-bottom:20px}.claim-directory-tools{display:flex;align-items:flex-end;gap:16px;flex-wrap:wrap;padding:16px 18px;border:1px solid #e7edf5;background:#f7f9fd;border-radius:10px;margin-bottom:22px}.claim-directory-tools>div{flex:0 1 260px}.claim-directory-tools label{display:block;font-size:11px;font-weight:600;color:#687c9c;margin-bottom:6px}.claim-directory-tools select{height:40px;font-size:12px;border:1px solid #dce4f0;border-radius:8px}.claim-directory-tools>span{margin:auto 0 10px;font-size:12px;color:#70819c}.claim-directory-tools>button{margin-left:auto;border:1px solid #dce4f0;background:#fff;color:#4a6098}.claim-queue-table thead th{background:#f4f7fc;font-size:10px;text-transform:uppercase;letter-spacing:.7px;white-space:nowrap;color:#647793}.claim-queue-table td{padding:17px 12px;font-size:12px;vertical-align:top;border-bottom:1px solid #edf1f7;overflow-wrap:anywhere}.claim-queue-table tbody tr:hover{background:#f8faff}.claim-purpose{min-width:180px;color:#2c4269;line-height:1.65}.claim-reference{display:block;font-size:10px;color:#8997ac;margin-top:5px}.claim-amount{white-space:nowrap;color:#304777;font-weight:600;font-variant-numeric:tabular-nums}.claim-document-badge{display:inline-block;padding:5px 8px;font-size:10px;border-radius:6px;background:#f0f2f7;color:#77869d;white-space:nowrap}.has-documents{background:#e9f6ef;color:#287354}.claim-empty-note{padding:18px;background:#f7f9fd;border-radius:10px;font-size:13px;color:#70819c}.reimbursement-queue .dataTables_filter input,.reimbursement-queue .dataTables_length select{border:1px solid #dce4f0;border-radius:8px;min-height:40px;font-size:12px}.reimbursement-queue .dataTables_info,.reimbursement-queue .dataTables_length label{font-size:12px;color:#70819c}.reimbursement-queue :focus-visible{outline:3px solid #a4b7ef;outline-offset:3px}
@media(max-width:1200px){.claim-overview{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:575px){.claim-overview{grid-template-columns:1fr;gap:10px}.claim-metric{padding:14px 18px}.claim-metric strong{font-size:22px}.claim-directory-tools>div{flex:1 1 100%}.claim-directory-tools>button{width:100%}.reimbursement-queue .claim-hero{padding:24px}.reimbursement-queue .claim-hero h1{font-size:26px}.reimbursement-queue .dataTables_filter label{display:block}.reimbursement-queue .dataTables_filter input{width:100%;margin:6px 0}.claim-queue-table .pagination{flex-wrap:wrap}.claim-list-heading>.btn{width:100%}}
</style></head>
<body class="dashboard-root-theme"><div id="wrapper"><?php include(__DIR__.'/includes/top-bar.php'); ?><?php include(__DIR__.'/includes/sidebar.php'); ?><div class="content-page"><div class="content"><main class="container-fluid dashboard-shell py-4 <?= $mode === 'queue' ? 'reimbursement-queue' : ''; ?>">
<div class="claim-links"><a class="btn btn-light" href="<?= site_url('Page/authority_to_travel'); ?>">Authority to Travel</a><a class="btn btn-light" href="<?= site_url('Reimbursement'); ?>">Reimbursement Claims</a></div>
<header class="claim-hero"><h1>Travel Reimbursement</h1><p class="mb-0">Prepare your itinerary, keep personal reimbursement records, and print documents for submission.</p></header>
<?php foreach(array('success','error') as $messageType): if($message=$this->session->flashdata($messageType)): ?><div role="alert" class="alert alert-<?= $messageType==='error'?'danger':'success'; ?>"><?= $esc($message); ?></div><?php endif; endforeach; ?>
<?php if($mode==='queue'): ?>
<div class="claim-overview" aria-label="Reimbursement summary">
<?php foreach (array(array(count($claims), 'Reimbursement records', 'mdi-folder-outline'), array($money($totalExpenses), 'Total recorded expenses', 'mdi-receipt'), array($money($totalNet), 'Total net claimed', 'mdi-cash-multiple'), array($documentCount, 'Records with documents', 'mdi-paperclip')) as $metric): ?>
<div class="claim-metric"><span class="claim-metric-icon"><i class="mdi <?= $esc($metric[2]); ?>" aria-hidden="true"></i></span><div><strong><?= $esc($metric[0]); ?></strong><span><?= $esc($metric[1]); ?></span></div></div>
<?php endforeach; ?>
</div>
<section class="claim-card"><div class="claim-list-heading"><div><span class="claim-kicker">Personal travel records</span><h2>My Reimbursement Records</h2></div><a class="btn btn-primary" href="<?= site_url('Page/authority_to_travel'); ?>"><i class="mdi mdi-plus" aria-hidden="true"></i> Start from a Travel Request</a></div>
<p class="claim-note">Open a record to manage your itinerary, expenses, and supporting documents.</p>
<div class="claim-directory-tools"><div><label for="claimDocumentFilter">Supporting documents</label><select id="claimDocumentFilter" class="form-control"><option value="">All records</option><option value="Attached">With documents</option><option value="Not attached">Without documents</option></select></div><span id="claimResults" role="status"><?= count($claims); ?> records</span><button type="button" id="resetClaimFilters" class="btn btn-light"><i class="mdi mdi-refresh" aria-hidden="true"></i> Reset filters</button></div>
<div class="claim-queue-table"><table id="claimQueueTable" class="table" style="width:100%"><thead><tr><th>Employee</th><th>Travel / Purpose</th><th>Destination</th><th>Net Claimed</th><th>Updated</th><th>Documents</th><th>Action</th></tr></thead><tbody>
<?php foreach($claims as $row): $hasDocuments = !empty($row['document_path']); ?><tr>
<td><?= $esc($row['employee_id']); ?></td>
<td class="claim-purpose"><strong><?= $esc($row['purpose']); ?></strong><span class="claim-reference">Travel #<?= (int) $row['travel_id']; ?></span></td>
<td><?= $esc($row['destination'] ?? ''); ?></td>
<td class="claim-amount" data-order="<?= max(0,(int)$row['total_cents']-(int)$row['advance_cents']); ?>"><?= $money(max(0,$row['total_cents']-$row['advance_cents'])); ?></td>
<td><?= $esc($row['updated_at']); ?></td>
<td data-search="<?= $hasDocuments ? 'Attached' : 'Not attached'; ?>"><span class="claim-document-badge <?= $hasDocuments ? 'has-documents' : ''; ?>"><?= $hasDocuments ? 'Attached' : 'Not attached'; ?></span></td>
<td><a class="btn btn-outline-primary btn-sm" href="<?= site_url('Reimbursement/travel/'.$row['travel_id']); ?>">Open Claim <i class="mdi mdi-arrow-right ml-1" aria-hidden="true"></i></a></td></tr><?php endforeach; ?>
</tbody></table></div>
<?php if (!$claims): ?><p class="claim-empty-note">To create your first record, open a travel request and choose <strong>Process Reimbursement</strong> from its Actions menu.</p><?php endif; ?>
</section>
<?php else:
$items=$rules->itinerary_display_rows($claim ? json_decode($claim['items_json'],TRUE) : array()); $checks=$claim ? json_decode($claim['checklist_json'],TRUE) : array();
$advance=number_format(($claim['advance_cents'] ?? 0)/100,2,'.',''); $notes=$claim['notes'] ?? '';
$retry=$this->session->flashdata('reimbursement_input');
if($editable && is_array($retry) && (int)$retry['travel_id']===(int)$travel->id){$items=is_array($retry['items'])?$retry['items']:array();$meta=is_array($retry['meta'] ?? NULL)?$retry['meta']:$meta;$advance=$retry['advance'];$notes=$retry['notes'];$checks=array_fill_keys(is_array($retry['checks'])?$retry['checks']:array(),TRUE);}
if(!$items) $items=array(array('date'=>'','date_to'=>'','places'=>'','departure'=>'','arrival'=>'','means'=>'','transportation'=>'','incidental'=>'','per_diem'=>'','reference'=>''));
?>
<section class="claim-card"><div class="d-flex flex-wrap justify-content-between"><h2>Travel #<?= (int)$travel->id; ?></h2><span class="badge badge-primary p-2 align-self-start"><?= $claim ? 'Saved Record' : 'New Record'; ?></span></div><p><strong><?= $esc($travel->purpose); ?></strong><br><?= $esc($travel->destination); ?> · <?= $esc($travel->inclusive_date); ?></p><p class="claim-note">Employee <?= $esc($travel->IDNumber); ?></p>
<div class="claim-summary"><div><span>Expenses Claimed</span><strong><?= $money($claim['total_cents'] ?? 0); ?></strong></div><div><span>Cash Advance</span><strong><?= $money($claim['advance_cents'] ?? 0); ?></strong></div><div><span>Net Reimbursement Claimed</span><strong><?= $money(max(0,($claim['total_cents'] ?? 0)-($claim['advance_cents'] ?? 0))); ?></strong></div></div></section>
<section class="claim-card"><h2>Itinerary and Supporting Documents</h2>
<?php if($claim): ?><a class="btn btn-outline-primary mb-3" href="<?= site_url('Reimbursement/itinerary/'.(int)$travel->id); ?>" target="_blank" rel="noopener">Print Itinerary / Save as PDF</a><?php endif; ?>
<form class="claim-form" method="post" enctype="multipart/form-data" action="<?= site_url('Reimbursement/save/'.(int)$travel->id); ?>"><?php $hidden(); ?><input type="hidden" name="revision" value="<?= (int)($claim['revision'] ?? 0); ?>">
<fieldset <?= !$editable?'disabled':''; ?>><legend class="h6">Itinerary of Travel</legend>
<div class="itinerary-details"><div class="itinerary-subheading"><div><h3>Travel details</h3><p>Enter your trip information as it should appear on the printed itinerary.</p></div></div><div class="form-row">
<?php foreach(array('agency'=>'Agency','number'=>'Itinerary No.','employee_name'=>'Name','purpose'=>'Purpose of Travel') as $key=>$label): ?>
<div class="form-group col-md-<?= $key==='purpose'||$key==='agency'?'8':'4'; ?>"><label for="meta-<?= $key; ?>"><?= $esc($label); ?></label><input id="meta-<?= $key; ?>" class="form-control" name="meta[<?= $key; ?>]" maxlength="<?= $key==='purpose'?2000:($key==='number'?100:255); ?>" value="<?= $esc($meta[$key] ?? ''); ?>" <?= $key!=='number'?'required':''; ?>></div>
<?php endforeach; ?></div></div>
<div class="itinerary-subheading mt-4"><div><h3>Travel legs and expenses</h3><p>Add one row for each travel leg or stay at the venue.</p></div></div>
<p class="claim-note">Use From / To for a date range. Per diem defaults to ₱360 per day, including both dates in a range. You can override it, including entering 0 when another row already covers the same day.</p>
<div class="itinerary-entry-wrap"><table class="table table-bordered claim-table itinerary-entry" id="expense-table"><thead><tr><th rowspan="2">Date</th><th rowspan="2">Places to Be Visited</th><th colspan="2">Time</th><th colspan="4">Expenses</th><th rowspan="2">Total Amount</th><th rowspan="2">Action</th></tr><tr><th>Depart.</th><th>Arrival</th><th>Means of Trans.</th><th>Transportation</th><th>Incidental Expenses</th><th>Per Diem</th></tr></thead><tbody>
<?php foreach(array_values($items) as $i=>$item): ?><tr>
<td data-label="Date"><label class="small mb-0">From<input aria-label="Travel date from" class="form-control" type="date" name="items[<?= $i; ?>][date]" value="<?= $esc($item['date'] ?? ''); ?>"></label><label class="small mb-0 mt-1">To (optional)<input aria-label="Travel date to" class="form-control" type="date" name="items[<?= $i; ?>][date_to]" value="<?= $esc($item['date_to'] ?? ''); ?>"></label></td>
<td class="description" data-label="Places to Be Visited"><label class="small mb-0">Places to be visited<input aria-label="Places to be visited" class="form-control" maxlength="500" name="items[<?= $i; ?>][places]" value="<?= $esc($item['places'] ?? ''); ?>"></label><label class="small mt-2 mb-0">Receipt / Evidence Ref.<input aria-label="Receipt or evidence reference" class="form-control" maxlength="200" name="items[<?= $i; ?>][reference]" value="<?= $esc($item['reference'] ?? ''); ?>"></label></td>
<?php foreach(array('departure'=>'Departure','arrival'=>'Arrival') as $key=>$label): ?><td data-label="<?= $label; ?>"><input aria-label="<?= $label; ?> time" class="form-control" type="time" name="items[<?= $i; ?>][<?= $key; ?>]" value="<?= $esc($item[$key] ?? ''); ?>"></td><?php endforeach; ?>
<td data-label="Means of Transportation"><input aria-label="Means of transportation" class="form-control" name="items[<?= $i; ?>][means]" maxlength="100" value="<?= $esc($item['means'] ?? ''); ?>" placeholder="e.g. Bus"></td>
<?php foreach(array('transportation'=>'Transportation','incidental'=>'Incidental Expenses','per_diem'=>'Per Diem') as $key=>$label): ?><td data-label="<?= $label; ?>"><input aria-label="<?= $esc(str_replace('_',' ',$key)); ?> in pesos" class="form-control expense-amount" type="number" min="0" max="99999999.99" step="0.01" name="items[<?= $i; ?>][<?= $key; ?>]" value="<?= $esc($key === 'per_diem' && ($item[$key] ?? '') === '' ? '360.00' : ($item[$key] ?? '')); ?>" <?= $key === 'per_diem' ? 'data-auto-per-diem="' . (($item[$key] ?? '') === '' ? '1' : '0') . '"' : ''; ?>></td><?php endforeach; ?>
<td data-label="Total Amount"><output class="row-total">0.00</output></td><td data-label="Actions"><div class="itinerary-row-actions"><?php if($editable): ?><button type="submit" class="btn btn-sm btn-outline-primary update-expense" name="action" value="update_entry:<?= $i; ?>" aria-label="Update itinerary entry <?= $i+1; ?>">Update</button><button type="submit" class="btn btn-sm btn-outline-danger delete-expense" name="action" value="delete_entry:<?= $i; ?>" aria-label="Delete itinerary entry <?= $i+1; ?>">Delete</button><?php else: ?><span class="claim-note">Read only</span><?php endif; ?></div></td></tr><?php endforeach; ?>
</tbody><tfoot><tr><th colspan="8" class="text-right">TOTAL</th><th id="itinerary-total">0.00</th><td></td></tr></tfoot></table></div>
<?php if($editable): ?><div class="expense-actions"><button type="button" id="add-expense" class="btn btn-outline-primary">+ Add Itinerary Row</button></div><?php endif; ?>
<p class="mt-3"><a class="btn btn-outline-secondary" href="<?= site_url('Reimbursement/settings/'.(int)$travel->id); ?>">Report Settings</a></p>
<div class="form-row"><div class="form-group col-md-4"><label for="cash-advance">Cash Advance Received (PHP)</label><input id="cash-advance" class="form-control" type="number" min="0" max="99999999.99" step="0.01" name="advance" value="<?= $esc($advance); ?>"><small>Include any advance for this travel. Do not claim costs already paid by the office or host.</small></div><div class="form-group col-md-8"><label for="claim-notes">Explanations / Provided Meals and Lodging</label><textarea id="claim-notes" class="form-control" name="notes" maxlength="5000" rows="3"><?= $esc($notes); ?></textarea></div></div>
<p id="claim-preview" class="alert alert-light" aria-live="polite"></p>
<?php if($editable): ?><div class="form-group mt-3"><label for="documents">Supporting Documents (PDF, up to 15 MB)</label><input id="documents" class="form-control-file" type="file" name="documents" accept="application/pdf,.pdf"><small>Optional: keep a PDF copy for your personal records. Uploading a new PDF replaces the current copy.</small></div><?php endif; ?></fieldset>
<?php if(!empty($claim['document_path'])): ?><p><a href="<?= site_url('Reimbursement/document/'.(int)$travel->id); ?>">Download Current Supporting Documents</a></p><?php endif; ?>
<button class="btn btn-primary" name="action" value="save">Save Record</button> <button class="btn btn-outline-primary" name="action" value="save_print">Save and Print Itinerary</button>
</form></section>
<p><a class="btn btn-outline-secondary" href="<?= site_url('Reimbursement/history/'.(int)$travel->id); ?>">View Record History</a></p>
<?php endif; ?>
</main></div><?php include(__DIR__.'/includes/footer.php'); ?></div></div><script src="<?= base_url(); ?>assets/js/vendor.min.js"></script><script src="<?= base_url(); ?>assets/js/app.min.js"></script>
<?php if ($mode === 'queue'): ?>
<script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/dataTables.responsive.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.js"></script>
<script>
$(function () {
    var table = $('#claimQueueTable').DataTable({
        pageLength: 10, lengthMenu: [10,25,50,100], autoWidth: false, responsive: true,
        order: [[4, 'desc']],
        columnDefs: [{targets:1,responsivePriority:1},{targets:6,responsivePriority:2,orderable:false,searchable:false},{targets:3,responsivePriority:3}],
        language: {search:'',searchPlaceholder:'Search purpose, destination, travel or employee number…',lengthMenu:'Show _MENU_ records',info:'Showing _START_ to _END_ of _TOTAL_ records',infoEmpty:'No reimbursement records',emptyTable:'No reimbursement records yet.',zeroRecords:'No records match your filters. Try another search or reset filters.'}
    });
    function updateSummary() { $('#claimResults').text(table.rows({search:'applied'}).count() + ' of ' + <?= count($claims); ?> + ' records'); }
    table.on('draw', updateSummary); updateSummary();
    $('#claimDocumentFilter').on('change', function () {
        table.column(5).search(this.value ? '^' + $.fn.dataTable.util.escapeRegex(this.value) + '$' : '', true, false).draw();
    });
    $('#resetClaimFilters').on('click', function () { $('#claimDocumentFilter').val(''); table.search('').columns().search('').draw(); });
});
</script>
<?php endif; ?>
<script>
(function(){
var table=document.getElementById('expense-table'); if(!table)return;
var body=table.querySelector('tbody'),template=body.rows[0].cloneNode(true),counter=body.rows.length;
function defaultPerDiem(row){
    var input=row.querySelector('[name$="[per_diem]"]');
    if(input.dataset.autoPerDiem!=='1')return;
    var from=row.querySelector('[name$="[date]"]').value;
    var to=row.querySelector('[name$="[date_to]"]').value || from;
    var start=Date.parse(from+'T00:00:00Z'),end=Date.parse(to+'T00:00:00Z');
    var days=Number.isFinite(start)&&Number.isFinite(end)&&end>=start ? Math.floor((end-start)/86400000)+1 : 1;
    input.value=(360*days).toFixed(2);
}
function resetRow(row){
    row.querySelectorAll('input').forEach(function(el){el.value='';});
    var perDiem=row.querySelector('[name$="[per_diem]"]');
    perDiem.dataset.autoPerDiem='1';defaultPerDiem(row);
}
body.addEventListener('input',function(e){
    if(e.target.matches('[name$="[per_diem]"]'))e.target.dataset.autoPerDiem='0';
    if(e.target.matches('[name$="[date]"], [name$="[date_to]"]'))defaultPerDiem(e.target.closest('tr'));
});
body.addEventListener('change',function(e){
    if(e.target.matches('[name$="[date]"], [name$="[date_to]"]')){defaultPerDiem(e.target.closest('tr'));totals();}
});
function totals(){var total=0;Array.from(body.rows).forEach(function(row){var amount=0;row.querySelectorAll('.expense-amount').forEach(function(el){amount+=Math.round((Number(el.value)||0)*100);});row.querySelector('.row-total').textContent=(amount/100).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2});total+=amount;});document.getElementById('itinerary-total').textContent=(total/100).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2});var advance=Math.round((Number(document.getElementById('cash-advance').value)||0)*100),net=total-advance;document.getElementById('claim-preview').textContent='Expenses: PHP '+(total/100).toFixed(2)+' · Net claimed: PHP '+(Math.max(0,net)/100).toFixed(2)+(net<0?' · Advance exceeds expenses; settle the balance with Accounting.':'');}
var add=document.getElementById('add-expense');if(add)add.addEventListener('click',function(){if(body.rows.length>=80)return;var row=template.cloneNode(true);row.querySelectorAll('input,select').forEach(function(el){el.name=el.name.replace(/items\[\d+\]/,'items['+counter+']');if(el.tagName==='INPUT')el.value='';});row.querySelectorAll('.update-expense').forEach(function(button){button.value='update_entry:'+counter;button.setAttribute('aria-label','Update itinerary entry '+(counter+1));});row.querySelectorAll('.delete-expense').forEach(function(button){button.value='delete_entry:'+counter;button.setAttribute('aria-label','Delete itinerary entry '+(counter+1));});resetRow(row);counter++;body.appendChild(row);totals();});
body.addEventListener('click',function(e){var button=e.target.closest('.delete-expense');if(button&&!window.confirm('Delete this itinerary entry? This will save the removal immediately.'))e.preventDefault();});document.addEventListener('input',totals);Array.from(body.rows).forEach(defaultPerDiem);totals();
})();
</script></body></html>
