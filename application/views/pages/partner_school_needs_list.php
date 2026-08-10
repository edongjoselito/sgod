<?php $esc = function($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $esc($title); ?> | SDO Davao Oriental</title>
    <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root { --navy:#102d4e; --blue:#1269aa; --aqua:#55c5c4; --gold:#f4bd4f; --ink:#1b2a41; --muted:#64748b; --border:#e3eaf2; }
        * { box-sizing:border-box; }
        body { margin:0; color:var(--ink); background:radial-gradient(circle at 8% 8%,rgba(85,197,196,.11),transparent 24rem),#f6f8fb; font-family:"DM Sans","Segoe UI",system-ui,sans-serif; }
        .topline { height:5px; background:linear-gradient(90deg,var(--aqua),#388ec5 48%,var(--gold)); }
        .shell { width:min(1180px,calc(100% - 32px)); margin:auto; }
        .nav { background:rgba(255,255,255,.86); border-bottom:1px solid var(--border); backdrop-filter:blur(18px); }
        .nav-inner { display:flex; align-items:center; justify-content:space-between; gap:20px; padding:17px 0; }
        .brand { color:var(--navy); font:700 21px/1.1 "Playfair Display",Georgia,serif; }
        .brand small { display:block; margin-bottom:3px; color:var(--blue); font:700 10px/1.3 "DM Sans",sans-serif; letter-spacing:.13em; text-transform:uppercase; }
        .back { padding:10px 17px; border:1px solid var(--border); border-radius:999px; color:var(--navy); background:#fff; font-size:14px; font-weight:700; text-decoration:none; }
        main { padding:38px 0 80px; }
        .hero { position:relative; overflow:hidden; padding:42px; border-radius:26px; color:#fff; background:linear-gradient(125deg,#102d4e,#155e96); box-shadow:0 24px 58px rgba(16,45,78,.18); }
        .hero::after { position:absolute; right:-55px; bottom:-110px; width:230px; height:230px; content:""; border:42px solid rgba(255,255,255,.10); border-radius:50%; }
        .eyebrow { margin:0 0 12px; color:#ffe09a; font-size:11px; font-weight:700; letter-spacing:.16em; text-transform:uppercase; }
        h1 { position:relative; margin:0; font:700 clamp(2rem,4vw,3rem)/1.12 "Playfair Display",Georgia,serif; }
        .hero p:last-child { position:relative; max-width:620px; margin:13px 0 0; color:rgba(255,255,255,.86); line-height:1.65; }
        .panel { margin-top:20px; padding:12px; border:1px solid var(--border); border-radius:20px; background:#fff; box-shadow:0 16px 40px rgba(18,45,78,.07); }
        table { width:100%; border-collapse:collapse; }
        th { padding:15px 14px; color:var(--blue); border-bottom:1px solid var(--border); font-size:11px; letter-spacing:.08em; text-align:left; text-transform:uppercase; }
        td { padding:17px 14px; border-bottom:1px solid #edf1f5; color:var(--muted); vertical-align:middle; }
        tr:last-child td { border-bottom:0; }
        td strong { color:var(--navy); }
        .action { display:inline-flex; padding:9px 13px; border-radius:9px; color:#fff; background:var(--blue); font-size:13px; font-weight:700; text-decoration:none; }
        .empty { padding:42px; color:var(--muted); text-align:center; }
        @media(max-width:700px) { .nav-inner { align-items:flex-start; flex-direction:column; } .hero { padding:30px 24px; } .panel { overflow-x:auto; } table { min-width:700px; } }
    </style>
</head>
<body>
<div class="topline"></div>
<nav class="nav"><div class="shell nav-inner"><div class="brand"><small>Department of Education</small>SDO Davao Oriental</div><a class="back" href="<?= site_url('Page/partner_dashboard'); ?>">← Partner dashboard</a></div></nav>
<main class="shell">
    <section class="hero"><p class="eyebrow">Partner resource center</p><h1>School needs</h1><p>Explore school needs and identify opportunities where your partnership can make a meaningful difference.</p></section>
    <section class="panel">
        <table>
            <thead><tr><th>#</th><th>School ID</th><th>School name</th><th>Address</th><th></th></tr></thead>
            <tbody>
                <?php if (!empty($schools)): ?>
                    <?php foreach ($schools as $index => $school): ?>
                        <tr><td><?= (int) $index + 1; ?></td><td><?= $esc($school->schoolID); ?></td><td><strong><?= $esc($school->schoolName); ?></strong></td><td><?= $esc(trim(($school->sitio ?? '') . ' ' . ($school->brgy ?? '') . ' ' . ($school->city ?? '') . ' ' . ($school->province ?? ''))); ?></td><td><a class="action" href="<?= site_url('Page/partner_school_needs/' . (int) $school->schoolID); ?>">View needs</a></td></tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td class="empty" colspan="5">No schools found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>
</body>
</html>
