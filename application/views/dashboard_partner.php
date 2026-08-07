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
        body { min-height:100vh; color:var(--ink); background:#eef5fb; font-family:"Inter",system-ui,Arial,sans-serif; }
        .topline { height:6px; background:linear-gradient(90deg, #0f4dac 0%, #0c8cca 100%); } .nav { background:rgba(255,255,255,.98); backdrop-filter:blur(12px); box-shadow:0 1px 24px rgba(15,58,90,.08); }
        .shell { width:min(1140px, calc(100% - 36px)); margin:auto; } .nav-inner { display:flex; align-items:center; justify-content:space-between; gap:20px; padding:18px 0; }
        .brand { color:var(--navy); font:700 20px/1.1 "Georgia",serif; } .brand small { display:block; color:var(--blue); font:800 10px/1.3 "Inter",system-ui,Arial,sans-serif; letter-spacing:.18em; text-transform:uppercase; }
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

    <section class="hero"><p class="eyebrow">Brigada Eskwela partner portal</p><h1>Welcome, <?= $esc($name !== '' ? $name : 'Partner'); ?>.</h1><p>Thank you for helping strengthen schools and create better learning spaces for every child.</p></section>
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
