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
        :root { --navy:#062a4d; --blue:#0864a6; --gold:#f6bf26; --ink:#17324c; --muted:#6b7c8e; --line:#dbe7ef; }
        body { min-height:100vh; color:var(--ink); background:#f4f9fc; font-family:"Segoe UI",Arial,sans-serif; }
        .topline { height:6px; background:var(--gold); } .nav { background:#fff; box-shadow:0 2px 16px rgba(15,58,90,.07); }
        .shell { width:min(1080px, calc(100% - 38px)); margin:auto; } .nav-inner { display:flex; align-items:center; justify-content:space-between; padding:15px 0; }
        .brand { color:var(--navy); font:700 20px Georgia,serif; } .brand small { display:block; color:var(--blue); font:800 10px/1.3 "Segoe UI",Arial,sans-serif; letter-spacing:.11em; text-transform:uppercase; }
        .logout { padding:9px 15px; border-radius:4px; color:var(--navy); background:#fff5d4; font-size:13px; font-weight:800; text-decoration:none; }
        main { padding:48px 0 64px; } .hero { padding:38px; border-radius:12px; color:#fff; background:linear-gradient(125deg,rgba(3,33,63,.98),rgba(8,100,166,.9)); box-shadow:0 14px 34px rgba(6,42,77,.16); }
        .eyebrow { margin:0 0 11px; color:#ffdc6b; font-size:12px; font-weight:800; letter-spacing:.14em; text-transform:uppercase; } h1 { margin:0; color:#fff; font:700 clamp(31px,4vw,46px)/1.1 Georgia,serif; } .hero p:last-child { max-width:640px; margin:14px 0 0; color:#deedf5; font-size:16px; }
        .grid { display:grid; grid-template-columns:repeat(3, 1fr); gap:18px; margin-top:23px; } .card { min-height:162px; padding:25px; border:1px solid var(--line); border-radius:9px; background:#fff; box-shadow:0 8px 24px rgba(15,58,90,.05); }
        .card-label { margin:0 0 9px; color:var(--blue); font-size:11px; font-weight:800; letter-spacing:.12em; text-transform:uppercase; } .card strong { display:block; color:var(--navy); font:700 25px/1.14 Georgia,serif; } .card p { margin:10px 0 0; color:var(--muted); font-size:14px; line-height:1.45; }
        .profile { grid-column:span 2; } .profile strong { font-size:22px; } .empty { margin-top:24px; padding:17px 20px; border-left:4px solid var(--gold); border-radius:4px; color:#66531a; background:#fff8e5; }
        .resource-section { margin-top:28px; } .resource-heading { display:flex; justify-content:space-between; align-items:end; gap:18px; margin-bottom:16px; }.resource-heading h2 { margin:0; color:var(--navy); font:700 28px/1.1 Georgia,serif; }.resource-heading p { max-width:520px; margin:0; color:var(--muted); font-size:14px; }.resource-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:18px; }.resource-card { display:flex; flex-direction:column; min-height:240px; padding:24px; border:1px solid var(--line); border-radius:9px; background:#fff; box-shadow:0 8px 24px rgba(15,58,90,.05); }.resource-icon { display:grid; place-items:center; width:42px; height:42px; margin-bottom:16px; border-radius:50%; color:var(--blue); background:#e8f4fa; font-size:20px; font-weight:800; }.resource-card h3 { margin:0; color:var(--navy); font:700 20px/1.18 Georgia,serif; }.resource-card p { margin:10px 0 17px; color:var(--muted); font-size:14px; line-height:1.5; }.resource-link { margin-top:auto; color:var(--blue); font-size:13px; font-weight:800; text-decoration:none; }.resource-link:hover { color:var(--navy); text-decoration:underline; }.steps { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; }.step { padding:18px; border-radius:8px; color:var(--ink); background:#fff; border:1px solid var(--line); }.step b { display:block; margin-bottom:7px; color:var(--blue); font-size:12px; letter-spacing:.1em; }.step span { font-size:14px; line-height:1.42; }.template-note { margin-top:17px; padding:13px 16px; border-left:4px solid var(--gold); color:#66531a; background:#fff8e5; font-size:13px; }
        @media(max-width:700px) { .grid,.resource-grid,.steps { grid-template-columns:1fr; } .profile { grid-column:auto; } .hero { padding:28px; } .resource-heading { display:block; }.resource-heading p { margin-top:9px; } }
    </style>
</head>
<body>
<div class="topline"></div><nav class="nav"><div class="shell nav-inner"><div class="brand"><small>Department of Education</small>SDO Davao Oriental</div><a class="logout" href="<?= site_url('Login/logout'); ?>">Sign out</a></div></nav>
<main class="shell">
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
            <article class="resource-card"><div class="resource-icon">DA</div><h3>Deed of Acceptance</h3><p>Record the school’s formal acceptance, the donated items, and its commitment to proper use and accountability.</p><a class="resource-link" href="<?= site_url('Page/partner_template/deed_of_acceptance'); ?>">Download Deed of Acceptance ↓</a></article>
            <article class="resource-card"><div class="resource-icon">✓</div><h3>Requirements checklist</h3><p>Use this checklist to prepare the standard coordination, documentation, donation, and acceptance requirements.</p><a class="resource-link" href="<?= site_url('Page/partner_template/requirements_checklist'); ?>">Download requirements checklist ↓</a></article>
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
