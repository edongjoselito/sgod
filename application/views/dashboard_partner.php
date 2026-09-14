<?php
$esc = function($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$name = trim(implode(' ', array_filter(array($this->session->userdata('fName'), $this->session->userdata('lName')))));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Partner Dashboard | SDO Davao Oriental</title>
    <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet">
    <style>
        :root { --navy:#092b4c; --blue:#0875c3; --gold:#f5b336; --ink:#172c43; --muted:#5f6f87; --line:#dbe5ef; --surface:#ffffff; --surface-soft:#f8fbff; --border:#d6e2ee; }
        body { min-height:100vh; color:var(--ink); background:#eef5fb; font-family:system-ui,-apple-system,"Segoe UI",Arial,sans-serif; }
        .topline { height:6px; background:linear-gradient(90deg, #0f4dac 0%, #0c8cca 100%); } .nav { background:rgba(255,255,255,.98); backdrop-filter:blur(12px); box-shadow:0 1px 24px rgba(15,58,90,.08); }
        .shell { width:min(1140px, calc(100% - 36px)); margin:auto; } .nav-inner { display:flex; align-items:center; justify-content:space-between; gap:20px; padding:18px 0; }
        .brand { color:var(--navy); font:700 20px/1.1 "Georgia",serif; } .brand small { display:block; color:var(--blue); font:800 10px/1.3 system-ui,-apple-system,"Segoe UI",Arial,sans-serif; letter-spacing:.18em; text-transform:uppercase; }
        .logout { padding:11px 18px; border-radius:999px; color:#0d2c52; background:#fff; border:1px solid #d7e3f1; font-size:14px; font-weight:700; text-decoration:none; }
        main { padding:50px 0 72px; }
        .hero { display:grid; grid-template-columns:1.5fr 1fr; gap:26px; padding:36px; border-radius:24px; color:#fff; background:linear-gradient(150deg, #0a2750 0%, #1070bf 100%); box-shadow:0 26px 60px rgba(8,28,63,.16); }
        .hero-copy { max-width:520px; }
        .eyebrow { margin:0 0 14px; color:#ffd36a; font-size:12px; font-weight:800; letter-spacing:.18em; text-transform:uppercase; }
        .hero h1 { margin:0 0 18px; color:#fff; font:700 clamp(32px,4vw,48px)/1.05 "Georgia",serif; }
        .hero p { margin:0; color:rgba(255,255,255,.88); font-size:16px; line-height:1.8; }
        .hero-cta { display:flex; flex-wrap:wrap; gap:12px; margin-top:24px; }
        .hero-cta a { display:inline-flex; align-items:center; justify-content:center; min-width:170px; padding:13px 18px; border-radius:999px; background:#fff; color:#0f3e76; font-size:14px; font-weight:700; text-decoration:none; transition:transform .2s ease,box-shadow .2s ease; box-shadow:0 10px 30px rgba(6,24,60,.08); }
        .hero-cta a:hover { transform:translateY(-1px); box-shadow:0 14px 34px rgba(6,24,60,.14); }
        .hero-stats { display:grid; gap:18px; }
        .stat-card { padding:24px; border-radius:18px; background:rgba(255,255,255,.14); border:1px solid rgba(255,255,255,.2); backdrop-filter:blur(12px); }
        .stat-card strong { display:block; font-size:2.2rem; line-height:1; color:#fff; }
        .stat-card span { display:block; margin-top:12px; color:rgba(255,255,255,.85); font-size:0.95rem; }
        .overview-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; margin-top:32px; }
        .overview-card { padding:24px; border-radius:18px; background:#fff; border:1px solid #d9e4ef; box-shadow:0 18px 32px rgba(15,58,90,.04); }
        .overview-card span { display:block; margin-bottom:14px; color:var(--blue); font-size:11px; font-weight:800; letter-spacing:.14em; text-transform:uppercase; }
        .overview-card h2 { margin:0; color:var(--navy); font-size:2.4rem; line-height:1; }
        .overview-card p { margin:14px 0 0; color:var(--muted); font-size:14px; line-height:1.8; }
        .overview-secondary { display:grid; grid-template-columns:2fr 1fr; gap:20px; margin-top:22px; }
        .profile-card, .quick-links { padding:24px; border-radius:18px; background:#fff; border:1px solid #d9e4ef; box-shadow:0 18px 32px rgba(15,58,90,.04); }
        .profile-card h3, .quick-links h3 { margin:0 0 12px; color:var(--navy); font-size:1.35rem; }
        .profile-list { list-style:none; margin:0; padding:0; display:grid; gap:12px; }
        .profile-list li { display:flex; justify-content:space-between; gap:14px; color:var(--muted); font-size:0.95rem; }
        .profile-list li strong { color:var(--ink); font-weight:700; }
        .quick-links p { color:var(--muted); margin:0 0 18px; }
        .quick-actions { display:grid; gap:12px; }
        .quick-action { display:flex; align-items:center; gap:14px; padding:16px 18px; border-radius:14px; background:#eef6ff; color:var(--navy); text-decoration:none; font-weight:700; border:1px solid #d7e8fb; }
        .quick-action span { display:inline-flex; width:38px; height:38px; align-items:center; justify-content:center; border-radius:12px; background:#d9e9ff; font-size:1rem; }
        .note-card { margin-top:26px; padding:18px 22px; border-radius:14px; background:#fff8e5; color:#5a431c; border-left:4px solid #f5b336; font-size:0.95rem; line-height:1.6; }
        .resource-section { margin-top:34px; }
        .resource-heading { display:flex; justify-content:space-between; align-items:flex-end; gap:18px; margin-bottom:18px; }
        .resource-heading h2 { margin:0; color:var(--navy); font-size:1.8rem; }
        .resource-heading p { max-width:620px; margin:0; color:var(--muted); font-size:14px; }
        .resource-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:18px; }
        .resource-card { display:flex; flex-direction:column; min-height:240px; padding:24px; border:1px solid #d9e4ef; border-radius:18px; background:#fff; box-shadow:0 18px 32px rgba(15,58,90,.04); }
        .resource-icon { display:grid; place-items:center; width:46px; height:46px; margin-bottom:18px; border-radius:14px; color:#0f4dac; background:#e8f2ff; font-size:20px; font-weight:800; }
        .resource-card h3 { margin:0; color:var(--navy); font-size:1.15rem; line-height:1.3; }
        .resource-card p { margin:12px 0 18px; color:var(--muted); font-size:0.96rem; line-height:1.6; }
        .resource-link { margin-top:auto; color:var(--blue); font-size:0.95rem; font-weight:700; text-decoration:none; }
        .resource-link:hover { color:#0a3f7a; text-decoration:underline; }
        @media(max-width:980px){ .hero, .overview-grid, .overview-secondary, .resource-grid { grid-template-columns:1fr; } .hero { padding:28px; } }
        @media(max-width:700px){ .nav-inner { flex-direction:column; align-items:flex-start; } .hero-cta { flex-direction:column; } main { padding:36px 0 52px; } }

        /* Modern partner portal theme */
        :root { --navy:#102d4e; --blue:#1269aa; --blue-deep:#0b4d82; --aqua:#55c5c4; --gold:#f4bd4f; --ink:#1b2a41; --muted:#64748b; --border:#e3eaf2; --shadow:0 16px 40px rgba(18,45,78,.07); }
        * { box-sizing:border-box; }
        body { background:radial-gradient(circle at 8% 8%,rgba(85,197,196,.11),transparent 24rem),radial-gradient(circle at 96% 34%,rgba(244,189,79,.10),transparent 22rem),#f6f8fb; font-family:"DM Sans","Segoe UI",system-ui,sans-serif; letter-spacing:-.01em; }
        .topline { height:5px; background:linear-gradient(90deg,var(--aqua),#388ec5 48%,var(--gold)); }
        .nav { background:rgba(255,255,255,.82); backdrop-filter:blur(18px); border-bottom:1px solid rgba(227,234,242,.9); box-shadow:none; }
        .shell { width:min(1180px,calc(100% - 32px)); }
        .nav-inner { gap:24px; padding:17px 0; }
        .brand { color:var(--navy); font:700 21px/1.1 "Playfair Display",Georgia,serif; }
        .brand small { color:var(--blue); font:700 10px/1.3 "DM Sans",sans-serif; letter-spacing:.13em; }
        .logout { padding:10px 17px; color:var(--navy); background:#f7fafc; border-color:var(--border); transition:color .2s ease,background .2s ease,transform .2s ease; }
        .logout:hover { color:#fff; background:var(--navy); transform:translateY(-1px); }
        main { padding:38px 0 80px; }
        .hero { position:relative; display:block; overflow:hidden; isolation:isolate; padding:clamp(32px,5vw,56px); border-radius:28px; background:linear-gradient(125deg,#102d4e 0%,#155e96 58%,#1a7cac 100%); box-shadow:0 28px 65px rgba(16,45,78,.20); }
        .hero::before,.hero::after { position:absolute; z-index:-1; content:""; border-radius:50%; pointer-events:none; }
        .hero::before { width:340px; height:340px; top:-195px; right:-82px; border:52px solid rgba(255,255,255,.10); }
        .hero::after { width:190px; height:190px; right:20%; bottom:-138px; background:rgba(244,189,79,.24); }
        .eyebrow { margin:0 0 14px; color:#ffe09a; font-size:11px; font-weight:700; letter-spacing:.16em; }
        .hero h1 { max-width:700px; margin:0 0 14px; font:700 clamp(32px,4vw,52px)/1.08 "Playfair Display",Georgia,serif; }
        .hero p { max-width:650px; font-size:16px; line-height:1.7; }
        .hero-cta { gap:10px; margin-top:24px; }
        .hero-cta a { min-width:154px; padding:13px 20px; border-radius:12px; color:var(--blue-deep); }
        .hero-cta a:hover { transform:translateY(-2px); }
        .hero-cta a.secondary { color:#fff; background:rgba(255,255,255,.13); border:1px solid rgba(255,255,255,.3); box-shadow:none; }
        .grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px; margin-top:20px; }
        .card { position:relative; display:flex; flex-direction:column; min-height:185px; padding:26px; overflow:hidden; border:1px solid var(--border); border-radius:20px; background:#fff; box-shadow:var(--shadow); transition:transform .2s ease,box-shadow .2s ease; }
        .card::before { position:absolute; top:0; left:26px; width:42px; height:4px; content:""; border-radius:0 0 4px 4px; background:var(--aqua); }
        .card:nth-child(2)::before { background:var(--gold); }
        .card:hover,.resource-card:hover,.step:hover { transform:translateY(-3px); box-shadow:0 22px 42px rgba(16,45,78,.11); }
        .card-label { margin:0 0 11px; color:var(--blue-deep); font-size:11px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; }
        .card strong { color:var(--navy); font-size:clamp(1.55rem,2.4vw,2rem); line-height:1.15; }
        .card p:not(.card-label) { margin:10px 0 0; color:var(--muted); line-height:1.6; }
        .resource-section { margin-top:56px; }
        .resource-heading { align-items:flex-end; gap:32px; margin-bottom:22px; }
        .resource-heading h2 { font:700 clamp(1.65rem,3vw,2.15rem)/1.2 "Playfair Display",Georgia,serif; }
        .resource-heading p { line-height:1.65; }
        .steps { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:16px; }
        .step { display:flex; flex-direction:column; gap:9px; min-height:158px; padding:22px; border:1px solid var(--border); border-radius:18px; background:#fff; box-shadow:var(--shadow); transition:transform .2s ease,box-shadow .2s ease; }
        .step b { color:var(--blue-deep); font-size:.79rem; letter-spacing:.09em; }
        .step span { color:var(--muted); font-size:.95rem; line-height:1.55; }
        .resource-grid { gap:16px; }
        .resource-card { min-height:255px; padding:25px; border-color:var(--border); border-radius:20px; box-shadow:var(--shadow); transition:transform .2s ease,box-shadow .2s ease; }
        .resource-icon { color:var(--blue-deep); background:#e9f4f5; font-family:"DM Sans",sans-serif; font-size:13px; font-weight:700; letter-spacing:.02em; }
        .resource-card h3 { font-size:1.18rem; }
        .resource-card p { line-height:1.65; }
        .resource-link { color:var(--blue-deep); }
        .empty,.template-note { margin-top:20px; padding:17px 20px; border:1px solid #f5df9d; border-left:4px solid var(--gold); border-radius:16px; background:#fff9e9; color:#684c13; font-size:.95rem; line-height:1.6; }
        @media(max-width:980px){ .steps { grid-template-columns:repeat(2,minmax(0,1fr)); } .resource-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } }
        @media(max-width:700px){ .grid,.steps,.resource-grid { grid-template-columns:1fr; } .resource-heading { display:block; } .resource-heading > p { margin-top:10px; } }
        @media(max-width:480px){ .shell { width:calc(100% - 24px); } .hero { padding:30px 24px; border-radius:22px; } .nav-inner { gap:12px; } }

    </style>
</head>
<body>
<div class="topline"></div><nav class="nav"><div class="shell nav-inner"><div class="brand"><small>Department of Education</small>SDO Davao Oriental</div><a class="logout" href="<?= site_url('Login/logout'); ?>">Sign out</a></div></nav>
<main class="shell">
    <?php if($this->session->flashdata('success')): ?>
    <div style="padding:16px 20px; border-radius:8px; margin-bottom:24px; font-size:14px; background:#d1fae5; color:#065f46; border-left:4px solid #22c55e;"><?= $esc($this->session->flashdata('success')); ?></div>
    <?php endif; ?>

    <?php if($this->session->flashdata('danger')): ?>
    <div style="padding:16px 20px; border-radius:8px; margin-bottom:24px; font-size:14px; background:#fee2e2; color:#991b1b; border-left:4px solid #dc2626;"><?= $esc($this->session->flashdata('danger')); ?></div>
    <?php endif; ?>

    <section class="hero">
        <p class="eyebrow">Brigada Eskwela partner portal</p>
        <h1>Welcome, <?= $esc($name !== '' ? $name : 'Partner'); ?>.</h1>
        <p>Thank you for helping strengthen schools and create better learning spaces for every child.</p>
        <div class="hero-cta">
            <a href="<?= site_url('Page/partner_school_needs_list'); ?>">View school needs</a>
            <a class="secondary" href="<?= site_url('Page/satisfaction_survey'); ?>">Take survey</a>
        </div>
    </section>
    <section class="grid" aria-label="Partner account summary">
        <article class="card profile"><p class="card-label">Partner profile</p><strong><?= $esc($partner ? $partner->name : 'Partner profile is being prepared'); ?></strong><p><?= $esc($partner ? trim(($partner->general_type ?? '') . ($partner->specific_type ? ' · ' . $partner->specific_type : '')) : 'Your registration was received successfully.'); ?></p></article>
        <article class="card"><p class="card-label">Recorded support</p><strong><?= (int) $contributionCount; ?></strong><p>Brigada Eskwela contribution record<?= (int) $contributionCount === 1 ? '' : 's'; ?>.</p><a class="resource-link" href="<?= site_url('Page/partner_donations'); ?>">View all donations →</a></article>
    </section>
    <?php if(!$partner): ?><div class="empty">Your account is active, but its Brigada partner profile could not yet be found. Please contact the Social Mobilization and Networking team for assistance.</div><?php endif; ?>
    <section class="resource-section" aria-labelledby="adopt-a-school-heading">
        <div class="resource-heading"><div><p class="card-label">Partner resource center</p><h2 id="adopt-a-school-heading">Adopt-a-School Program guide</h2></div><p>Use these materials to plan, document, and coordinate meaningful school support with SDO Davao Oriental.</p></div>
        <div class="steps" aria-label="Adopt-a-School process">
            <div class="step"><b>01 · CONNECT</b><span>Discuss the school need and the type of support you wish to provide.</span></div><div class="step"><b>02 · AGREE</b><span>Define the project, responsibilities, and timeline with the school and SDO.</span></div><div class="step"><b>03 · DOCUMENT</b><span>Prepare the appropriate MOA and donation or acceptance documents.</span></div><div class="step"><b>04 · IMPLEMENT</b><span>Coordinate turnover, recording, acknowledgment, and reporting.</span></div>
        </div>
    </section>
    <section class="resource-section" aria-labelledby="template-heading">
        <div class="resource-heading"><div><p class="card-label">Editable downloads</p><h2 id="template-heading">Partnership document templates</h2></div><p>Download a starting template, complete the required details, then have it reviewed by the appropriate SDO offices before signing.</p></div>
        <div class="resource-grid">
            <article class="resource-card"><div class="resource-icon">MOA</div><h3>Memorandum of Agreement</h3><p>Set out the purpose, expected support, responsibilities, implementation period, and signatures for a formal partnership.</p><a class="resource-link" href="<?= site_url('Page/partner_template/moa'); ?>">Download MOA template ↓</a></article>
            <article class="resource-card"><div class="resource-icon">DD</div><h3>Deed of Donation</h3><p>Document the donated items or support, complete specifications, estimated value, and the donor and donee details.</p><a class="resource-link" href="<?= site_url('Page/partner_template/deed_of_donation'); ?>">Download Deed of Donation ↓</a></article>
            <article class="resource-card"><div class="resource-icon">DA</div><h3>Deed of Acceptance</h3><p>Record the school's formal acceptance, the donated items, and its commitment to proper use and accountability.</p><a class="resource-link" href="<?= site_url('Page/partner_template/deed_of_acceptance'); ?>">Download Deed of Acceptance ↓</a></article>
            <article class="resource-card"><div class="resource-icon">✓</div><h3>Requirements checklist</h3><p>Use this checklist to prepare the standard coordination, documentation, donation, and acceptance requirements.</p><a class="resource-link" href="<?= site_url('Page/partner_template/requirements_checklist'); ?>">Download requirements checklist ↓</a></article>
            <article class="resource-card"><div class="resource-icon">★</div><h3>Satisfaction Survey</h3><p>Share your feedback on your partnership experience with SDO Davao Oriental to help us improve.</p><a class="resource-link" href="<?= site_url('Page/satisfaction_survey'); ?>">Take survey →</a></article>
        </div>
        <div class="template-note">Templates are working drafts, not final legal instruments. Coordinate with the school and the SDO Davao Oriental Social Mobilization and Networking team before execution.</div>
    </section>
    <section class="resource-section" aria-labelledby="policy-heading">
        <div class="resource-heading"><div><p class="card-label">Official references</p><h2 id="policy-heading">Know the program requirements</h2></div><p>These official DepEd references explain the Adopt-a-School framework and the documentation expected for private-sector support.</p></div>
        <div class="resource-grid">
            <article class="resource-card"><div class="resource-icon">RA</div><h3>Adopt-a-School overview</h3><p>Read the program overview, benefits, and frequently asked questions for prospective partners.</p><a class="resource-link" href="https://www.deped.gov.ph/about-adopt-a-school/" target="_blank" rel="noopener noreferrer">Open official overview ↗</a></article>
            <article class="resource-card"><div class="resource-icon">IRR</div><h3>Revised program rules</h3><p>Review DepEd Order No. 2, s. 2013, the revised implementing rules for RA 8525.</p><a class="resource-link" href="https://www.deped.gov.ph/2013/01/18/do-2-s-2013-revised-implementing-rules-and-regulations-of-republic-act-ra-no-8525-otherwise-known-as-the-adopt-a-school-program-act/" target="_blank" rel="noopener noreferrer">Read DO 2, s. 2013 ↗</a></article>
            <article class="resource-card"><div class="resource-icon">DOC</div><h3>Donation documentation</h3><p>Review the documentation procedures for private-sector donations to public schools.</p><a class="resource-link" href="https://www.deped.gov.ph/2009/07/20/do-82-s-2009-documentation-procedures-on-private-sector-donations-to-public-schools/" target="_blank" rel="noopener noreferrer">Read DO 82, s. 2009 ↗</a></article>
        </div>
    </section>
</main>
</body>
</html>
