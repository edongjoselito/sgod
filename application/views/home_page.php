<?php
$partnerSignupValues = (array) $this->session->flashdata('partner_signup_values');
$partnerValue = function($key) use ($partnerSignupValues) {
    return htmlspecialchars((string) ($partnerSignupValues[$key] ?? ''), ENT_QUOTES, 'UTF-8');
};
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SDO Davao Oriental service portal">
    <title>SGOD ONE | SDO Davao Oriental</title>
    <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --navy:#062a4d; --blue:#0864a6; --gold:#f6bf26; --ink:#17324c; --muted:#6b7c8e; --line:#dbe7ef; --pale:#f4f9fc; }
        * { box-sizing:border-box; }
        html { scroll-behavior:smooth; }
        body { margin:0; color:var(--ink); background:var(--pale); font-family:"Segoe UI",Arial,sans-serif; }
        .gold-line { height:6px; background:var(--gold); }
        .site-header { background:#fff; }
        .portal-container { width:min(1140px, calc(100% - 42px)); margin:auto; }
        .brand-row { display:flex; align-items:center; justify-content:space-between; padding:17px 0; gap:22px; }
        .brand { display:flex; align-items:center; color:var(--navy); text-decoration:none; }
        .brand:hover { color:var(--navy); text-decoration:none; }
        .brand img { width:54px; max-height:56px; object-fit:contain; margin-right:13px; }
        .brand-kicker { display:block; color:var(--blue); font-size:10px; font-weight:800; letter-spacing:.13em; text-transform:uppercase; }
        .brand-name { display:block; font:700 22px/1.1 Georgia,serif; }
        .brand-note { color:var(--muted); font-size:13px; text-align:right; }
        .portal-nav { background:var(--navy); }
        .portal-nav ul { display:flex; flex-wrap:wrap; padding:0; margin:0; list-style:none; }
        .portal-nav a { display:block; padding:14px 20px; color:#e9f3f9; font-size:14px; font-weight:700; text-decoration:none; white-space:nowrap; }
        .portal-nav a:hover, .portal-nav a:focus { color:#fff; background:rgba(255,255,255,.12); }
        .portal-nav .dropdown-menu { z-index:1050; min-width:285px; padding:7px 0; border:0; border-radius:0 0 7px 7px; background:#fff; box-shadow:0 12px 25px rgba(3,29,54,.22); }
        .portal-nav .dropdown-menu a { padding:9px 18px; color:var(--ink); background:#fff; font-size:13px; white-space:normal; }
        .portal-nav .dropdown-menu a:hover, .portal-nav .dropdown-menu a:focus { color:var(--blue); background:#edf6fb; }
        .portal-nav .dropdown-toggle:after { margin-left:7px; vertical-align:2px; }
        .portal-nav .login-nav { margin-left:auto; color:#172f46; background:var(--gold); }
        .portal-nav .login-nav:hover { color:#172f46; background:#ffcf42; }
        .hero { color:#fff; background:linear-gradient(115deg,rgba(3,33,63,.97),rgba(8,100,166,.87)),url('<?= base_url(); ?>upload/environment.jpg') center/cover; }
        .hero-inner { display:grid; grid-template-columns:1fr 330px; align-items:center; gap:55px; min-height:365px; padding:63px 0; }
        .eyebrow { margin:0 0 13px; color:#ffdb69; font-size:12px; font-weight:800; letter-spacing:.15em; text-transform:uppercase; }
        h1 { max-width:700px; margin:0; color:#fff; font:700 clamp(38px,5vw,62px)/1.07 Georgia,serif; letter-spacing:-.035em; }
        .hero-copy { max-width:640px; margin:19px 0 0; color:#ddebf4; font-size:18px; line-height:1.55; }
        .hero-login { padding:28px; border:1px solid rgba(255,255,255,.27); border-radius:8px; background:rgba(2,26,48,.5); }
        .hero-login h2 { margin:0 0 8px; font:700 22px Georgia,serif; }
        .hero-login p { color:#d8e8f2; font-size:14px; }
        .btn-login { display:block; width:100%; padding:12px 16px; border:0; border-radius:4px; color:#17324c; background:var(--gold); font-size:14px; font-weight:800; text-align:center; cursor:pointer; }
        .btn-login:hover { background:#ffcf42; }
        main { padding:70px 0; }
        .section-label { margin:0 0 9px; color:var(--blue); font-size:12px; font-weight:800; letter-spacing:.14em; text-transform:uppercase; }
        .section-heading { display:flex; align-items:end; justify-content:space-between; gap:24px; margin-bottom:25px; }
        .section-heading h2 { margin:0; color:var(--navy); font:700 clamp(28px,3vw,37px)/1.12 Georgia,serif; }
        .section-heading p { max-width:490px; margin:0; color:var(--muted); }
        .section-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:18px; }
        .section-card { position:relative; display:flex; flex-direction:column; min-height:215px; padding:26px; overflow:hidden; border:1px solid var(--line); border-radius:8px; color:var(--ink); background:#fff; box-shadow:0 8px 24px rgba(15,58,90,.06); text-decoration:none; transition:.2s ease; }
        .section-card:before { position:absolute; top:0; right:0; left:0; height:5px; background:var(--blue); content:""; }
        .section-card:hover, .section-card:focus { color:var(--ink); box-shadow:0 14px 30px rgba(15,58,90,.14); transform:translateY(-4px); text-decoration:none; }
        .section-number { display:grid; place-items:center; width:43px; height:43px; margin-bottom:17px; border-radius:50%; color:var(--blue); background:#e9f5fb; font-size:14px; font-weight:800; }
        .section-card h3 { margin:0; color:var(--navy); font:700 20px/1.17 Georgia,serif; }
        .section-card p { margin:10px 0 18px; color:var(--muted); font-size:14px; line-height:1.5; }
        .open-link { margin-top:auto; color:var(--blue); font-size:13px; font-weight:800; }
        .open-link:after { margin-left:8px; content:"→"; font-size:17px; }
        .division-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:18px; }
        .division-card { padding:31px; border-radius:8px; background:var(--navy); color:#fff; }
        .division-card.cid { background:var(--blue); }
        .division-card .section-label { color:#ffdc67; }
        .division-card h2 { margin:0; color:#fff; font:700 28px/1.15 Georgia,serif; }
        .division-card p:not(.section-label) { max-width:450px; margin:10px 0 22px; color:#deedf5; }
        .division-card a { display:inline-block; padding:10px 16px; border-radius:4px; color:#17324c; background:var(--gold); font-size:13px; font-weight:800; text-decoration:none; }
        .division-card a:hover { background:#ffcf42; }
        .partner-callout { display:grid; grid-template-columns:1.25fr .75fr; gap:34px; align-items:center; margin:52px 0; padding:43px; border-radius:10px; color:#fff; background:linear-gradient(120deg,rgba(3,33,63,.98),rgba(8,100,166,.9)),url('<?= base_url(); ?>upload/environment1.jpg') center/cover; box-shadow:0 12px 30px rgba(15,58,90,.14); }
        .partner-callout .section-label { color:#ffdc67; }.partner-callout h2 { margin:0; color:#fff; font:700 clamp(30px,3.5vw,43px)/1.1 Georgia,serif; }.partner-callout p:not(.section-label) { max-width:620px; margin:15px 0 0; color:#dcebf5; font-size:16px; line-height:1.55; }.partner-points { display:grid; gap:12px; margin-top:22px; }.partner-point { display:flex; align-items:center; gap:10px; color:#edf7fc; font-size:14px; font-weight:700; }.partner-point span { display:grid; place-items:center; width:24px; height:24px; border-radius:50%; color:var(--navy); background:var(--gold); }.partner-cta { padding:30px; border:1px solid rgba(255,255,255,.27); border-radius:8px; background:rgba(2,26,48,.54); }.partner-cta h3 { margin:0; color:#fff; font:700 24px Georgia,serif; }.partner-cta p { margin:9px 0 20px; color:#dcebf5; font-size:14px; line-height:1.5; }.btn-partner { width:100%; padding:12px 16px; border:0; border-radius:4px; color:#17324c; background:var(--gold); font-size:14px; font-weight:800; cursor:pointer; }.btn-partner:hover { background:#ffcf42; }
        .partner-logo-showcase { grid-column:1/-1; margin-top:3px; padding-top:26px; border-top:1px solid rgba(255,255,255,.22); overflow:hidden; }.partner-logo-caption { margin:0 0 14px; color:#ffdc67; font-size:11px; font-weight:800; letter-spacing:.13em; text-transform:uppercase; }.partner-logo-window { overflow:hidden; mask-image:linear-gradient(90deg,transparent,#000 6%,#000 94%,transparent); }.partner-logo-track { display:flex; width:max-content; gap:16px; animation:partner-logo-scroll 28s linear infinite; }.partner-logo-window:hover .partner-logo-track { animation-play-state:paused; }.partner-logo { display:flex; align-items:center; justify-content:center; width:150px; height:82px; padding:10px; border:1px solid rgba(255,255,255,.23); border-radius:7px; background:#fff; box-shadow:0 8px 18px rgba(1,24,45,.2); }.partner-logo img { display:block; max-width:100%; max-height:58px; object-fit:contain; animation:partner-logo-glow 2.6s ease-in-out infinite; }.partner-logo:nth-child(2n) img { animation-delay:.7s; }.partner-logo:nth-child(3n) img { animation-delay:1.3s; }@keyframes partner-logo-scroll { to { transform:translateX(-50%); } }@keyframes partner-logo-glow { 0%,100% { opacity:.74; transform:scale(.96); } 50% { opacity:1; transform:scale(1); } }@media (prefers-reduced-motion:reduce) { .partner-logo-track,.partner-logo img { animation:none; } }
        footer { color:#b9cbd9; background:#031d36; }
        .footer-inner { display:flex; justify-content:space-between; gap:25px; padding:27px 0; font-size:13px; }
        .footer-inner p { margin:0; }
        .footer-inner strong { color:#fff; }
        .modal-content { overflow:hidden; border:0; border-radius:9px; box-shadow:0 22px 60px rgba(4,31,56,.28); }
        .login-modal-header { padding:25px 30px; border:0; color:#fff; background:linear-gradient(125deg,var(--navy),var(--blue)); }
        .login-modal-header .modal-title { color:#fff; font:700 24px Georgia,serif; }
        .login-modal-header p { margin:5px 0 0; color:#dcebf5; font-size:13px; }
        .login-modal-header .close { color:#fff; opacity:.9; text-shadow:none; }
        .login-modal-body { padding:28px 30px 30px; }
        .login-modal-body label { color:var(--ink); font-size:13px; font-weight:800; }
        .login-modal-body .form-control { height:44px; border-color:#cfdde7; }
        .login-modal-body .form-control:focus { border-color:var(--blue); box-shadow:0 0 0 .18rem rgba(8,100,166,.15); }
        .modal-signin { height:44px; border:0; color:#17324c; background:var(--gold); font-weight:800; }
        .modal-signin:hover { color:#17324c; background:#ffcf42; }
        /* Refined public portal layout */
        body { background:#f6f8fc; font-family:"DM Sans","Avenir Next",Arial,sans-serif; }
        .portal-container { width:min(1200px,calc(100% - 48px)); }
        .gold-line { height:4px; background:linear-gradient(90deg,#f6bf26,#ffdc67,#f6bf26); }
        .site-header { border-bottom:1px solid #e7edf4; }
        .brand-row { padding:20px 0; }
        .brand img { width:58px; max-height:60px; margin-right:15px; }
        .brand-kicker { color:#2876b7; font-size:10px; letter-spacing:.16em; }
        .brand-name,.hero h1,.hero-login h2,.section-heading h2,.division-card h2,.partner-callout h2,.partner-cta h3 { font-family:"Plus Jakarta Sans","DM Sans",Arial,sans-serif; }
        .brand-name { font-size:21px; letter-spacing:-.035em; }
        .brand-note { font-size:12px; line-height:1.65; }
        .portal-nav { background:#fff; border-top:1px solid #eef2f6; }
        .portal-nav a { padding:14px 18px; color:#29465f; font-size:13px; font-weight:700; }
        .portal-nav a:hover,.portal-nav a:focus { color:#0864a6; background:#edf6fc; }
        .portal-nav .login-nav { margin:8px 0 8px auto; padding:8px 16px; border-radius:999px; color:#17324c; background:#f6bf26; }
        .portal-nav .login-nav:hover { color:#17324c; background:#ffcf42; }
        .hero { width:min(1360px,calc(100% - 32px)); margin:24px auto 0; border-radius:24px; box-shadow:0 22px 50px rgba(6,42,77,.16); }
        .hero-inner { grid-template-columns:minmax(0,1fr) 365px; min-height:400px; gap:65px; padding:70px 58px; }
        .hero-deped-one-logo { display:grid; place-items:center; min-height:230px; padding:28px; border:1px solid rgba(255,255,255,.24); border-radius:18px; background:rgba(255,255,255,.96); box-shadow:0 16px 30px rgba(2,26,48,.2); }
        .hero-deped-one-logo img { display:block; width:100%; max-width:320px; height:auto; }
        .eyebrow,.section-label { font-size:11px; letter-spacing:.17em; }
        h1 { max-width:780px; font-size:clamp(40px,5vw,64px); letter-spacing:-.055em; }
        .hero-copy { max-width:620px; font-size:17px; }
        .hero-login { padding:30px; border-radius:16px; background:rgba(255,255,255,.13); backdrop-filter:blur(12px); box-shadow:inset 0 1px rgba(255,255,255,.18); }
        .hero-login h2 { font-size:21px; }
        .btn-login,.btn-partner,.modal-signin { border-radius:10px; transition:transform .2s ease,box-shadow .2s ease,background .2s ease; }
        .btn-login:hover,.btn-partner:hover,.modal-signin:hover { transform:translateY(-2px); box-shadow:0 10px 18px rgba(0,0,0,.16); }
        main { padding:60px 0 76px; }
        .division-grid { gap:22px; }
        .division-card { min-height:290px; padding:32px; border:1px solid rgba(255,255,255,.18); border-radius:18px; background:linear-gradient(145deg,#0a416f,#062a4d); box-shadow:0 14px 30px rgba(15,58,90,.12); transition:transform .2s ease,box-shadow .2s ease; }
        .division-card.cid { background:linear-gradient(145deg,#1681c7,#0864a6); }
        .division-card:hover { transform:translateY(-5px); box-shadow:0 22px 34px rgba(15,58,90,.18); }
        .division-card h2 { font-size:25px; letter-spacing:-.035em; }
        .division-card a { border-radius:9px; }
        .partner-callout { margin:58px 0 0; padding:52px; border-radius:22px; box-shadow:0 20px 42px rgba(15,58,90,.16); }
        .partner-callout h2 { letter-spacing:-.045em; }
        .partner-cta { border-radius:16px; background:rgba(2,26,48,.62); backdrop-filter:blur(8px); }
        .partner-logo { border-radius:12px; }
        footer { background:#041f39; }
        @media (max-width:820px) { .hero { width:calc(100% - 28px); }.hero-inner { grid-template-columns:1fr; gap:30px; padding:52px 36px; }.hero-login { max-width:none; }.division-grid { grid-template-columns:1fr; }.division-card { min-height:0; } }
        @media (max-width:560px) { .portal-container { width:calc(100% - 28px); }.brand-row { padding:14px 0; }.portal-nav ul { padding:5px 0; }.portal-nav a { padding:10px 12px; }.portal-nav .login-nav { margin:3px 0 3px auto; }.hero { margin-top:14px; border-radius:18px; }.hero-inner { padding:42px 24px; }.partner-callout { padding:30px 24px; border-radius:18px; } }
        @media (max-width:820px) { .section-grid { grid-template-columns:repeat(2,1fr); } }
        @media (max-width:560px) { .portal-container { width:min(100% - 28px,1140px); } .brand-note { display:none; } .brand-name { font-size:18px; } .brand img { width:47px; } .portal-nav a { padding:13px 15px; } .portal-nav .login-nav { margin-left:0; } .hero-inner { min-height:0; padding:48px 0; } .hero-copy { font-size:16px; } main { padding:48px 0; } .section-heading { display:block; } .section-heading p { margin-top:12px; } .section-grid,.division-grid { grid-template-columns:1fr; } .section-card { min-height:190px; } .partner-callout { margin:35px 0; padding:28px; } .footer-inner { display:block; } .footer-inner p + p { margin-top:8px; } .login-modal-header,.login-modal-body { padding-right:22px; padding-left:22px; } }
    </style>
</head>
<body>
    <div class="gold-line"></div>
    <header class="site-header">
        <div class="portal-container brand-row">
            <a class="brand" href="<?= base_url(); ?>" aria-label="SGOD ONE home">
                <img src="<?= base_url(); ?>assets/images/sgod.png" alt="DepEd SGOD ONE">
                <span><span class="brand-kicker">Department of Education</span><span class="brand-name">SDO Davao Oriental</span></span>
            </a>
            <div class="brand-note">One division. One purpose.<br>Better learning for every child.</div>
        </div>
        <nav class="portal-nav" aria-label="Primary navigation"><div class="portal-container"><ul>
            <li><a href="#home">Home</a></li>
            <li class="dropdown"><a class="dropdown-toggle" href="#" id="sgodMenu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">SGOD</a><div class="dropdown-menu" aria-labelledby="sgodMenu">
                <?php if (!empty($sgodSections)) : ?>
                    <?php foreach ($sgodSections as $sgodSection) : ?>
                        <?php
                            $externalSectionUrl = '';
                            if (stripos($sgodSection->sectionName, 'disaster risk reduction') !== false) {
                                $externalSectionUrl = 'https://drrm.depeddavor.com';
                            } elseif (stripos($sgodSection->sectionName, 'social mobilization') !== false) {
                                $externalSectionUrl = 'https://socmob.depeddavor.com';
                            } elseif (stripos($sgodSection->sectionName, 'school management') !== false) {
                                $externalSectionUrl = 'https://smme.depeddavor.com';
                            }
                        ?>
                        <a class="dropdown-item" href="<?= $externalSectionUrl !== '' ? $externalSectionUrl : site_url('section/' . (int) $sgodSection->id); ?>"<?= $externalSectionUrl !== '' ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?= htmlspecialchars($sgodSection->sectionName, ENT_QUOTES, 'UTF-8'); ?></a>
                    <?php endforeach; ?>
                <?php else : ?>
                    <span class="dropdown-item text-muted">No SGOD sections are available.</span>
                <?php endif; ?>
            </div></li>
            <li><a href="#cid">CID</a></li><li><a href="#osds">OSDS</a></li><li><a href="#partners">Partner with us</a></li>
            <li><a href="#loginModal" class="login-nav" data-toggle="modal" data-target="#loginModal">Login</a></li>
        </ul></div></nav>
    </header>

    <section class="hero" id="home"><div class="portal-container hero-inner">
        <div><p class="eyebrow">Digital service gateway</p><h1>Connected services for every school and learner.</h1><p class="hero-copy">Access the programs, resources, and support services of SDO Davao Oriental in one central portal.</p></div>
        <aside class="hero-deped-one-logo" aria-label="DepEd ONE"><img src="<?= base_url(); ?>assets/images/sgod.png" alt="DepEd ONE"></aside>
    </div></section>

    <main class="portal-container">
        <section class="division-grid" aria-label="Division offices">
            <article class="division-card" id="sgod"><p class="section-label">Division office</p><h2>School Governance &amp; Operations</h2><p>Programs and services that support school governance, operations, and learner welfare.</p><a href="<?= site_url('page/sgod'); ?>">Visit SGOD portal →</a></article>
            <article class="division-card cid" id="cid"><p class="section-label">Division office</p><h2>Curriculum and Implementation Division</h2><p>Resources and services that strengthen teaching, learning, and curriculum implementation.</p><a href="<?= site_url('page/cid_admin'); ?>">Visit CID portal →</a></article>
            <article class="division-card" id="osds"><p class="section-label">Division office</p><h2>Office of the Schools Division Superintendent</h2><p>Executive communications, directives, and division-wide administrative services.</p><a href="<?= site_url('page/osds_admin'); ?>">Visit OSDS portal →</a></article>
        </section>
        <section class="partner-callout" id="partners" aria-labelledby="partner-heading">
            <div><p class="section-label">Brigada Eskwela</p><h2 id="partner-heading">Help build better schools with DepEd.</h2><p>Your organization, business, civic group, or personal advocacy can make a meaningful difference for learners across Davao Oriental.</p><div class="partner-points"><div class="partner-point"><span>✓</span> Share resources, services, or expertise</div><div class="partner-point"><span>✓</span> Connect with Brigada Eskwela initiatives</div><div class="partner-point"><span>✓</span> Keep a single partner profile and support record</div></div></div>
            <aside class="partner-cta"><h3>Become a partner</h3><p>Create a partner account to register your organization and receive a dedicated portal for your Brigada Eskwela engagement.</p><button class="btn-partner" type="button" data-toggle="modal" data-target="#partnerSignupModal">Sign up as a partner</button></aside>
            <?php if (!empty($partnerLogos)) : ?>
                <div class="partner-logo-showcase" aria-label="Our Brigada Eskwela partners">
                    <p class="partner-logo-caption">Our Brigada Eskwela partners</p>
                    <div class="partner-logo-window"><div class="partner-logo-track">
                        <?php for ($copy = 0; $copy < 2; $copy++) : foreach ($partnerLogos as $partnerLogo) : ?>
                            <div class="partner-logo"<?= $copy === 1 ? ' aria-hidden="true"' : ''; ?>><img src="<?= base_url('uploads/brigada_partners_logo/' . rawurlencode($partnerLogo->file)); ?>" alt="<?= htmlspecialchars($partnerLogo->name, ENT_QUOTES, 'UTF-8'); ?> logo"></div>
                        <?php endforeach; endfor; ?>
                    </div></div>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer><div class="portal-container footer-inner"><p><strong>SDO Davao Oriental</strong> &nbsp;|&nbsp; Department of Education</p><p>Serving schools. Supporting learners. Strengthening communities.</p></div></footer>

    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content">
            <div class="modal-header login-modal-header"><div><h2 class="modal-title" id="loginModalTitle">Login</h2><p>Use your assigned account to continue.</p></div><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
            <div class="login-modal-body">
                <?php if($this->session->flashdata('msg')) : ?><div class="alert alert-danger alert-dismissible fade show" role="alert"><?= $this->session->flashdata('msg'); ?><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><?php endif; ?>
                <?php if($this->session->flashdata('partner_signup_success')) : ?><div class="alert alert-success" role="alert"><?= htmlspecialchars($this->session->flashdata('partner_signup_success'), ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
                <form action="<?= site_url('Login/auth'); ?>" method="post">
                    <div class="form-group"><label for="login-source">Sign In With</label><select class="form-control" name="login_source" id="login-source"><option value="sgod">One DepEd Account</option><option value="deped_mis">DepEd MIS Account</option></select><small class="form-text text-muted">Choose DepEd MIS to use your MIS account credentials.</small></div>
                    <div class="form-group"><label for="username">Username or email</label><input class="form-control" name="username" id="username" autocomplete="username" required></div>
                    <div class="form-group"><label for="password">Password</label><input class="form-control" type="password" name="password" id="password" autocomplete="current-password" required></div>
                    <div class="d-flex justify-content-between align-items-center mb-4"><div class="custom-control custom-checkbox"><input class="custom-control-input" id="remember" type="checkbox" checked><label class="custom-control-label font-weight-normal" for="remember">Remember me</label></div><a href="#" data-toggle="modal" data-target="#forgotModal" data-dismiss="modal" class="small">Forgot password?</a></div>
                    <button type="submit" class="btn modal-signin btn-block">Sign in</button>
                </form>
            </div>
        </div></div>
    </div>
    <div class="modal fade" id="forgotModal" tabindex="-1" role="dialog" aria-labelledby="forgotModalLabel" aria-hidden="true"><div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content"><div class="modal-header"><h4 class="modal-title" id="forgotModalLabel">Forgot password</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body"><form method="post" action="<?= site_url('login/forgot_pass'); ?>"><div class="form-group"><label for="reset-email">Email address</label><input type="email" name="email" id="reset-email" class="form-control" placeholder="Enter your email" required></div><button type="submit" class="btn btn-primary btn-block">Request a new password</button></form></div></div></div></div>
    <div class="modal fade" id="partnerSignupModal" tabindex="-1" role="dialog" aria-labelledby="partnerSignupModalTitle" aria-hidden="true"><div class="modal-dialog modal-lg modal-dialog-centered" role="document"><div class="modal-content"><div class="modal-header login-modal-header"><div><h2 class="modal-title" id="partnerSignupModalTitle">Partner with DepEd</h2><p>Register your organization for Brigada Eskwela collaboration.</p></div><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="login-modal-body">
        <?php if($this->session->flashdata('partner_signup_error')) : ?><div class="alert alert-danger"><?= htmlspecialchars($this->session->flashdata('partner_signup_error'), ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
        <form action="<?= site_url('Login/partner_signup'); ?>" method="post" id="partnerSignupForm">
            <div class="form-row"><div class="form-group col-md-7"><label for="partner-organization">Organization or Partner Name</label><input class="form-control" type="text" name="organization" id="partner-organization" value="<?= $partnerValue('organization'); ?>" required></div><div class="form-group col-md-5"><label for="partner-type">Organization Type</label><select class="form-control" name="general_type" id="partner-type"><option value="">Select organization type</option><option value="Private_Sector" <?= $partnerValue('general_type') === 'Private_Sector' ? 'selected' : ''; ?>>Private Sector</option><option value="Public_Sector" <?= $partnerValue('general_type') === 'Public_Sector' ? 'selected' : ''; ?>>Public Sector</option><option value="Civil_Society_Organizations" <?= $partnerValue('general_type') === 'Civil_Society_Organizations' ? 'selected' : ''; ?>>Civil Society Organizations</option><option value="International" <?= $partnerValue('general_type') === 'International' ? 'selected' : ''; ?>>International</option></select></div></div>
            <div class="form-row"><div class="form-group col-md-6"><label for="partner-first-name">Contact Person First Name</label><input class="form-control" type="text" name="first_name" id="partner-first-name" value="<?= $partnerValue('first_name'); ?>" required></div><div class="form-group col-md-6"><label for="partner-last-name">Last Name</label><input class="form-control" type="text" name="last_name" id="partner-last-name" value="<?= $partnerValue('last_name'); ?>" required></div></div>
            <div class="form-row"><div class="form-group col-md-6"><label for="partner-email">Email Address</label><input class="form-control" type="email" name="email" id="partner-email" value="<?= $partnerValue('email'); ?>" autocomplete="email" required><small id="partner-email-feedback" class="form-text"></small></div><div class="form-group col-md-6"><label for="partner-phone">Contact Number</label><input class="form-control" type="tel" name="phone" id="partner-phone" value="<?= $partnerValue('phone'); ?>"></div></div>
            <div class="form-row"><div class="form-group col-md-7"><label for="partner-address">Address</label><input class="form-control" type="text" name="address" id="partner-address" value="<?= $partnerValue('address'); ?>"></div><div class="form-group col-md-5"><label for="partner-specific-type">Specific Type</label><select class="form-control" name="specific_type" id="partner-specific-type"><option value="">Select specific type</option><option value="Government" <?= $partnerValue('specific_type') === 'Government' ? 'selected' : ''; ?>>Government</option><option value="INGO-International Non-Government Organization" <?= $partnerValue('specific_type') === 'INGO-International Non-Government Organization' ? 'selected' : ''; ?>>INGO-International Non-Government Organization</option><option value="Individual" <?= $partnerValue('specific_type') === 'Individual' ? 'selected' : ''; ?>>Individual</option><option value="Others" <?= $partnerValue('specific_type') === 'Others' ? 'selected' : ''; ?>>Others</option></select></div></div>
            <div class="form-row"><div class="form-group col-md-6"><label for="partner-password">Password</label><input class="form-control" type="password" name="password" id="partner-password" minlength="8" autocomplete="new-password" required><small id="partner-password-feedback" class="form-text"></small></div><div class="form-group col-md-6"><label for="partner-password-confirm">Confirm Password</label><input class="form-control" type="password" name="password_confirm" id="partner-password-confirm" minlength="8" autocomplete="new-password" required><small id="partner-password-match" class="form-text"></small></div></div>
            <div class="form-group"><label for="partner-captcha">Security Code</label><div class="d-flex align-items-center"><img id="partner-captcha-image" src="<?= site_url('Login/partner_captcha'); ?>" width="150" height="48" alt="Security code"><button class="btn btn-light ml-2" id="partner-captcha-refresh" type="button" aria-label="Get a new security code">↻</button></div><input class="form-control mt-2" type="text" name="captcha" id="partner-captcha" maxlength="5" autocomplete="off" placeholder="Enter the code shown" required></div>
            <button type="submit" class="btn modal-signin btn-block">Create partner account</button>
        </form>
    </div></div></div></div>
    <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
    <script>
        (function () {
            var email = document.getElementById('partner-email');
            var emailFeedback = document.getElementById('partner-email-feedback');
            var password = document.getElementById('partner-password');
            var passwordConfirm = document.getElementById('partner-password-confirm');
            var passwordFeedback = document.getElementById('partner-password-feedback');
            var passwordMatch = document.getElementById('partner-password-match');
            var captchaImage = document.getElementById('partner-captcha-image');
            var captchaRefresh = document.getElementById('partner-captcha-refresh');
            var checkTimer;

            function setFeedback(input, feedback, message, valid) {
                input.classList.remove('is-valid', 'is-invalid');
                feedback.classList.remove('text-success', 'text-danger');
                if (message) {
                    input.classList.add(valid ? 'is-valid' : 'is-invalid');
                    feedback.classList.add(valid ? 'text-success' : 'text-danger');
                }
                feedback.textContent = message;
            }
            function checkEmail() {
                var value = email.value.trim();
                if (!value) { setFeedback(email, emailFeedback, '', false); return; }
                if (!email.validity.valid) { setFeedback(email, emailFeedback, 'Enter a valid email address.', false); return; }
                fetch('<?= site_url('Login/partner_email_available'); ?>?email=' + encodeURIComponent(value), { credentials: 'same-origin' })
                    .then(function (response) { return response.json(); })
                    .then(function (data) { setFeedback(email, emailFeedback, data.available ? 'Email address is available.' : 'This email address is already registered.', data.available); })
                    .catch(function () { setFeedback(email, emailFeedback, 'We could not check this email right now.', false); });
            }
            function checkPassword() {
                var value = password.value;
                setFeedback(password, passwordFeedback, value ? (value.length >= 8 ? 'Password is acceptable.' : 'Use at least 8 characters.') : '', value.length >= 8);
                if (passwordConfirm.value) {
                    setFeedback(passwordConfirm, passwordMatch, passwordConfirm.value === value ? 'Passwords match.' : 'Passwords do not match.', passwordConfirm.value === value);
                } else { setFeedback(passwordConfirm, passwordMatch, '', false); }
            }
            function refreshCaptcha() {
                captchaImage.src = '<?= site_url('Login/partner_captcha'); ?>?refresh=' + Date.now();
                document.getElementById('partner-captcha').value = '';
            }
            if (email) {
                email.addEventListener('input', function () { clearTimeout(checkTimer); checkTimer = setTimeout(checkEmail, 350); });
                if (email.value) { checkEmail(); }
            }
            if (password && passwordConfirm) { password.addEventListener('input', checkPassword); passwordConfirm.addEventListener('input', checkPassword); }
            if (captchaRefresh) { captchaRefresh.addEventListener('click', refreshCaptcha); }
        }());
    </script>
    <?php if($this->session->flashdata('msg')) : ?><script>$(function(){ $('#loginModal').modal('show'); });</script><?php endif; ?>
    <?php if($this->session->flashdata('partner_signup_error')) : ?><script>$(function(){ $('#partnerSignupModal').modal('show'); });</script><?php endif; ?>
    <?php if($this->session->flashdata('partner_signup_success')) : ?><script>$(function(){ $('#loginModal').modal('show'); });</script><?php endif; ?>
</body>
</html>
