<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($section->sectionName, ENT_QUOTES, 'UTF-8'); ?> | SDO Davao Oriental</title>
    <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
    <style>
        :root{--navy:#062a4d;--blue:#0864a6;--gold:#f6bf26;--ink:#17324c;--muted:#64778a;--pale:#f4f9fc}*{box-sizing:border-box}body{margin:0;color:var(--ink);background:var(--pale);font-family:"Segoe UI",Arial,sans-serif}.gold-line{height:6px;background:var(--gold)}.shell{width:min(1000px,calc(100% - 40px));margin:auto}.top{padding:20px 0;background:#fff;border-bottom:1px solid #dce7ee}.top a{color:var(--navy);font-weight:800;text-decoration:none}.crumb{padding:26px 0 15px;color:var(--muted);font-size:14px}.crumb a,.side-link{color:var(--blue);text-decoration:none}.hero{padding:42px 0 50px;color:#fff;background:linear-gradient(120deg,var(--navy),var(--blue))}.label{margin:0 0 12px;color:#ffdb69;font-size:12px;font-weight:800;letter-spacing:.14em;text-transform:uppercase}.hero h1{margin:0;font:700 clamp(34px,5vw,54px)/1.1 Georgia,serif;color:#fff}.content{display:grid;grid-template-columns:minmax(0,1fr) 270px;gap:24px;padding:42px 0 64px}.panel{padding:30px;border-radius:8px;background:#fff;box-shadow:0 8px 24px rgba(15,58,90,.07)}.panel h2{margin:0 0 13px;color:var(--navy);font:700 26px Georgia,serif}.panel p{margin:0;color:var(--muted);line-height:1.7}.side-title{margin:0 0 12px!important;font-size:18px!important}.side-link{display:block;padding:10px 0;border-top:1px solid #e2ebf1;font-size:14px}.side-link:first-of-type{border-top:0}.back{display:inline-block;margin-top:26px;padding:10px 15px;border-radius:4px;color:#17324c;background:var(--gold);font-size:13px;font-weight:800;text-decoration:none}.back:hover{background:#ffcf42}@media(max-width:700px){.content{grid-template-columns:1fr}.shell{width:min(100% - 28px,1000px)}}
    </style>
</head>
<body>
    <div class="gold-line"></div>
    <header class="top"><div class="shell"><a href="<?= base_url(); ?>">← SDO Davao Oriental</a></div></header>
    <div class="shell crumb"><a href="<?= base_url(); ?>">Home</a> / SGOD / <?= htmlspecialchars($section->sectionName, ENT_QUOTES, 'UTF-8'); ?></div>
    <section class="hero"><div class="shell"><p class="label">School Governance and Operations Division</p><h1><?= htmlspecialchars($section->sectionName, ENT_QUOTES, 'UTF-8'); ?></h1></div></section>
    <main class="shell content"><article class="panel"><h2>About this section</h2><p>This is the public information gateway for the <?= htmlspecialchars($section->sectionName, ENT_QUOTES, 'UTF-8'); ?> of SDO Davao Oriental. Use the staff sign-in portal to access internal tools, reports, and section workspaces.</p><a class="back" href="<?= base_url(); ?>">Return to portal</a></article><aside class="panel"><h2 class="side-title">SGOD Sections</h2><?php foreach ($sgodSections as $menuSection) : ?><a class="side-link" href="<?= site_url('section/' . (int) $menuSection->id); ?>"><?= htmlspecialchars($menuSection->sectionName, ENT_QUOTES, 'UTF-8'); ?></a><?php endforeach; ?></aside></main>
</body>
</html>
