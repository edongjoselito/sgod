<?php
$esc = function($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$schoolAddress = function($donation) {
    $parts = array_filter(array(
        trim((string) ($donation->school_sitio ?? '')),
        trim((string) ($donation->school_brgy ?? '')),
        trim((string) ($donation->school_city ?? '')),
        trim((string) ($donation->school_province ?? ''))
    ), function($part) { return $part !== ''; });
    return implode(', ', $parts);
};
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Recorded Support | SDO Davao Oriental</title>
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root{--navy:#062a4d;--blue:#0864a6;--gold:#f6bf26;--ink:#17324c;--muted:#6b7c8e;--line:#dbe7ef}
        body{background:#f4f9fc;color:var(--ink);font-family:"Segoe UI",Arial,sans-serif}.top{height:6px;background:var(--gold)}.shell{width:min(1120px,calc(100% - 38px));margin:auto}.nav{padding:16px 0;background:#fff;box-shadow:0 2px 12px rgba(15,58,90,.06)}.brand{color:var(--navy);font:700 20px Georgia,serif;text-decoration:none}.brand small{display:block;color:var(--blue);font:800 10px Arial,sans-serif;letter-spacing:.1em;text-transform:uppercase}main{padding:42px 0}.hero{padding:31px;border-radius:10px;background:linear-gradient(125deg,#062a4d,#0864a6);color:#fff}.eyebrow{margin:0 0 10px;color:#ffdc67;font-size:11px;font-weight:800;letter-spacing:.13em;text-transform:uppercase}h1{margin:0;color:#fff;font:700 clamp(29px,4vw,42px)/1.1 Georgia,serif}.hero p:last-child{margin:11px 0 0;color:#dcebf5}.back{display:inline-block;margin-top:18px;color:var(--blue);font-weight:800;text-decoration:none}.card{margin-top:22px;border:1px solid var(--line);border-radius:9px;box-shadow:0 8px 24px rgba(15,58,90,.05)}.table th{border-top:0;color:#526a7c;font-size:11px;letter-spacing:.07em;text-transform:uppercase}.table td{vertical-align:middle}.amount{font-weight:800;color:var(--navy)}.empty{padding:38px;text-align:center;color:var(--muted)}@media(max-width:700px){.table{font-size:13px}}
    </style>
</head>
<body>
    <div class="top"></div>
    <nav class="nav"><div class="shell"><a class="brand" href="<?= site_url('Page/partner_dashboard'); ?>"><small>SDO Davao Oriental</small>Partner Portal</a></div></nav>
    <main class="shell">
        <section class="hero"><p class="eyebrow">Brigada Eskwela partner portal</p><h1>My Recorded Support</h1><p><?= $esc($partner ? $partner->name : 'Partner'); ?> — a complete view of donations and support recorded in the system.</p></section>
        <a class="back" href="<?= site_url('Page/partner_dashboard'); ?>">← Back to partner dashboard</a>
        <section class="card"><div class="table-responsive"><table class="table table-hover mb-0">
            <thead><tr><th>Date</th><th>Recipient school</th><th>Project / support</th><th>Contribution type</th><th>Quantity</th><th>Amount</th><th>Status</th></tr></thead>
            <tbody>
                <?php foreach($donations as $donation): ?>
                    <?php $address = $schoolAddress($donation); ?>
                    <tr>
                        <td><?= $esc($donation->c_date ?? ''); ?></td>
                        <td><strong><?= $esc($donation->school_name ?? '—'); ?></strong><?php if($address !== ''): ?><br><small class="text-muted"><?= $esc($address); ?></small><?php endif; ?></td>
                        <td><strong><?= $esc($donation->project_name ?? $donation->spicific_contribution ?? ''); ?></strong><?php if(!empty($donation->remarks)): ?><br><small class="text-muted"><?= $esc($donation->remarks); ?></small><?php endif; ?></td>
                        <td><?= $esc($donation->contribution_type ?? ''); ?></td>
                        <td><?= $esc(trim(($donation->quantity_of_conftribution ?? '') . ' ' . ($donation->unit_of_contribution ?? ''))); ?></td>
                        <td class="amount"><?= !empty($donation->amount) ? '₱' . number_format((float) $donation->amount, 2) : '—'; ?></td>
                        <td><?= $esc($donation->status_agreement ?? ''); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($donations)): ?><tr><td colspan="7" class="empty">No donations have been recorded for this partner yet.</td></tr><?php endif; ?>
            </tbody>
        </table></div></section>
    </main>
</body>
</html>
