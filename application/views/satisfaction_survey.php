<?php
$esc = function($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$name = trim(implode(' ', array_filter(array($this->session->userdata('fName'), $this->session->userdata('lName')))));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Partner Satisfaction Survey | SDO Davao Oriental</title>
    <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet">
    <style>
        :root { --navy:#062a4d; --blue:#0864a6; --gold:#f6bf26; --ink:#17324c; --muted:#6b7c8e; --line:#dbe7ef; }
        body { min-height:100vh; color:var(--ink); background:#f4f9fc; font-family:"Segoe UI",Arial,sans-serif; }
        .topline { height:6px; background:var(--gold); } .nav { background:#fff; box-shadow:0 2px 16px rgba(15,58,90,.07); }
        .shell { width:min(800px, calc(100% - 38px)); margin:auto; } .nav-inner { display:flex; align-items:center; justify-content:space-between; padding:15px 0; }
        .brand { color:var(--navy); font:700 20px Georgia,serif; } .brand small { display:block; color:var(--blue); font:800 10px/1.3 "Segoe UI",Arial,sans-serif; letter-spacing:.11em; text-transform:uppercase; }
        .back-link { padding:9px 15px; border-radius:4px; color:var(--navy); background:#fff5d4; font-size:13px; font-weight:800; text-decoration:none; }
        main { padding:48px 0 64px; } .hero { padding:38px; border-radius:12px; color:#fff; background:linear-gradient(125deg,rgba(3,33,63,.98),rgba(8,100,166,.9)); box-shadow:0 14px 34px rgba(6,42,77,.16); }
        .eyebrow { margin:0 0 11px; color:#ffdc6b; font-size:12px; font-weight:800; letter-spacing:.14em; text-transform:uppercase; } h1 { margin:0; color:#fff; font:700 clamp(31px,4vw,46px)/1.1 Georgia,serif; } .hero p:last-child { max-width:640px; margin:14px 0 0; color:#deedf5; font-size:16px; }
        .survey-form { padding:32px; border:1px solid var(--line); border-radius:12px; background:#fff; box-shadow:0 8px 24px rgba(15,58,90,.05); margin-top:28px; }
        .form-group { margin-bottom:24px; } .form-label { display:block; margin-bottom:8px; color:var(--navy); font:700 14px "Segoe UI",Arial,sans-serif; }
        .form-label small { font-weight:400; color:var(--muted); font-size:12px; margin-left:8px; }
        .rating-group { display:flex; gap:12px; margin-top:10px; } .rating-option { flex:1; }
        .rating-option input { display:none; } .rating-option label { display:block; padding:12px 8px; border:2px solid var(--line); border-radius:8px; text-align:center; cursor:pointer; transition:all .2s; font-weight:600; color:var(--muted); }
        .rating-option input:checked + label { border-color:var(--blue); background:#e8f4fa; color:var(--blue); }
        .rating-option:hover label { border-color:var(--blue); }
        .rating-labels { display:flex; justify-content:space-between; margin-top:6px; font-size:11px; color:var(--muted); }
        textarea.form-control { min-height:120px; border:1px solid var(--line); border-radius:8px; padding:12px; font-size:14px; }
        .btn-submit { width:100%; padding:16px; border:none; border-radius:8px; color:#fff; background:linear-gradient(135deg, #22c55e 0%, #16a34a 100%); font:700 16px "Segoe UI",Arial,sans-serif; cursor:pointer; transition:all .3s; }
        .btn-submit:hover { transform:scale(1.02); box-shadow:0 8px 24px rgba(34,197,94,.4); }
        .alert { padding:16px 20px; border-radius:8px; margin-bottom:24px; font-size:14px; }
        .alert-success { background:#d1fae5; color:#065f46; border-left:4px solid #22c55e; }
        .alert-danger { background:#fee2e2; color:#991b1b; border-left:4px solid #dc2626; }
        @media(max-width:600px) { .rating-group { flex-wrap:wrap; } .rating-option { min-width:calc(33.33% - 8px); } }
    </style>
</head>
<body>
<div class="topline"></div><nav class="nav"><div class="shell nav-inner"><div class="brand"><small>Department of Education</small>SDO Davao Oriental</div><a class="back-link" href="<?= site_url('Page/partner_dashboard'); ?>">← Back to Dashboard</a></div></nav>
<main class="shell">
    <section class="hero"><p class="eyebrow">Partner feedback</p><h1>Satisfaction Survey</h1><p>Please rate your experience partnering with SDO Davao Oriental. Your feedback helps us improve our partnership programs.</p></section>
    
    <?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $esc($this->session->flashdata('success')); ?></div>
    <?php endif; ?>
    
    <?php if($this->session->flashdata('danger')): ?>
    <div class="alert alert-danger"><?= $esc($this->session->flashdata('danger')); ?></div>
    <?php endif; ?>
    
    <?php echo form_open('Page/satisfaction_survey', array('class' => 'survey-form')); ?>
        <?php if($partner): ?>
        <div class="form-group">
            <label class="form-label">Partner Organization</label>
            <p style="color:var(--ink); font-size:15px;"><?= $esc($partner->name); ?></p>
        </div>
        <?php endif; ?>
        
        <div class="form-group">
            <label class="form-label">Responsiveness <small>How responsive was SDO Davao Oriental to your inquiries and requests?</small></label>
            <div class="rating-group">
                <?php for($i=1; $i<=5; $i++): ?>
                <div class="rating-option">
                    <input type="radio" name="responsiveness" id="responsiveness_<?= $i; ?>" value="<?= $i; ?>" <?= set_radio('responsiveness', $i); ?>>
                    <label for="responsiveness_<?= $i; ?>"><?= $i; ?></label>
                </div>
                <?php endfor; ?>
            </div>
            <div class="rating-labels"><span>Poor</span><span>Excellent</span></div>
            <?php echo form_error('responsiveness', '<div class="text-danger" style="font-size:12px; margin-top:6px;">', '</div>'); ?>
        </div>
        
        <div class="form-group">
            <label class="form-label">Communication <small>How clear and effective was the communication throughout the partnership?</small></label>
            <div class="rating-group">
                <?php for($i=1; $i<=5; $i++): ?>
                <div class="rating-option">
                    <input type="radio" name="communication" id="communication_<?= $i; ?>" value="<?= $i; ?>" <?= set_radio('communication', $i); ?>>
                    <label for="communication_<?= $i; ?>"><?= $i; ?></label>
                </div>
                <?php endfor; ?>
            </div>
            <div class="rating-labels"><span>Poor</span><span>Excellent</span></div>
            <?php echo form_error('communication', '<div class="text-danger" style="font-size:12px; margin-top:6px;">', '</div>'); ?>
        </div>
        
        <div class="form-group">
            <label class="form-label">Ease of coordination <small>How easy was it to coordinate activities and logistics with the SDO?</small></label>
            <div class="rating-group">
                <?php for($i=1; $i<=5; $i++): ?>
                <div class="rating-option">
                    <input type="radio" name="ease_of_coordination" id="ease_of_coordination_<?= $i; ?>" value="<?= $i; ?>" <?= set_radio('ease_of_coordination', $i); ?>>
                    <label for="ease_of_coordination_<?= $i; ?>"><?= $i; ?></label>
                </div>
                <?php endfor; ?>
            </div>
            <div class="rating-labels"><span>Difficult</span><span>Easy</span></div>
            <?php echo form_error('ease_of_coordination', '<div class="text-danger" style="font-size:12px; margin-top:6px;">', '</div>'); ?>
        </div>
        
        <div class="form-group">
            <label class="form-label">Transparency <small>How transparent was the SDO in processes, decisions, and reporting?</small></label>
            <div class="rating-group">
                <?php for($i=1; $i<=5; $i++): ?>
                <div class="rating-option">
                    <input type="radio" name="transparency" id="transparency_<?= $i; ?>" value="<?= $i; ?>" <?= set_radio('transparency', $i); ?>>
                    <label for="transparency_<?= $i; ?>"><?= $i; ?></label>
                </div>
                <?php endfor; ?>
            </div>
            <div class="rating-labels"><span>Low</span><span>High</span></div>
            <?php echo form_error('transparency', '<div class="text-danger" style="font-size:12px; margin-top:6px;">', '</div>'); ?>
        </div>
        
        <div class="form-group">
            <label class="form-label">Reporting quality <small>How would you rate the quality and timeliness of reports and documentation?</small></label>
            <div class="rating-group">
                <?php for($i=1; $i<=5; $i++): ?>
                <div class="rating-option">
                    <input type="radio" name="reporting_quality" id="reporting_quality_<?= $i; ?>" value="<?= $i; ?>" <?= set_radio('reporting_quality', $i); ?>>
                    <label for="reporting_quality_<?= $i; ?>"><?= $i; ?></label>
                </div>
                <?php endfor; ?>
            </div>
            <div class="rating-labels"><span>Poor</span><span>Excellent</span></div>
            <?php echo form_error('reporting_quality', '<div class="text-danger" style="font-size:12px; margin-top:6px;">', '</div>'); ?>
        </div>
        
        <div class="form-group">
            <label class="form-label">Future willingness to partner <small>How likely are you to partner with SDO Davao Oriental again?</small></label>
            <div class="rating-group">
                <?php for($i=1; $i<=5; $i++): ?>
                <div class="rating-option">
                    <input type="radio" name="future_willingness" id="future_willingness_<?= $i; ?>" value="<?= $i; ?>" <?= set_radio('future_willingness', $i); ?>>
                    <label for="future_willingness_<?= $i; ?>"><?= $i; ?></label>
                </div>
                <?php endfor; ?>
            </div>
            <div class="rating-labels"><span>Unlikely</span><span>Very likely</span></div>
            <?php echo form_error('future_willingness', '<div class="text-danger" style="font-size:12px; margin-top:6px;">', '</div>'); ?>
        </div>
        
        <div class="form-group">
            <label class="form-label">Additional comments <small>Optional - Share any other feedback or suggestions (optional)</small></label>
            <textarea name="comments" class="form-control" placeholder="Please share any additional feedback, suggestions, or areas for improvement..."><?= set_value('comments'); ?></textarea>
        </div>
        
        <button type="submit" class="btn-submit">Submit Survey</button>
    <?php echo form_close(); ?>
</main>
</body>
</html>
