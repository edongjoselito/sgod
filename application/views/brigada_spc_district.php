<?php
$esc = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
$districtName = isset($district->discription) ? $district->discription : 'District';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <?php include('includes/page-title.php'); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />

        <style>
            :root { --memo-navy: #272b8c; --memo-blue: #3c40c6; --memo-ink: #23275d; --memo-shadow: 0 24px 60px rgba(15, 23, 42, 0.08); }
            body { background: radial-gradient(circle at top left, rgba(60, 64, 198, 0.10), transparent 24%), linear-gradient(180deg, #f4f8fc 0%, #eef4fa 100%); }
            .content-page { background: transparent; }
            .memo-shell { position: relative; padding-bottom: 28px; }
            .memo-shell::before { content: ""; position: absolute; inset: 24px 0 auto; height: 240px; border-radius: 30px; background: linear-gradient(135deg, rgba(60, 64, 198, 0.11), rgba(122, 128, 255, 0.10)); z-index: 0; }
            .memo-shell > * { position: relative; z-index: 1; }
            .memo-hero { margin-top: 20px; border-radius: 28px; overflow: hidden; color: #fff; box-shadow: var(--memo-shadow); background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 32%), linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%); }
            .memo-hero-body { padding: 32px; }
            .memo-eyebrow { display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: 999px; background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.18); font-size: .8rem; letter-spacing: .08em; text-transform: uppercase; }
            .memo-title { margin: 18px 0 12px; color: #fff; font-size: clamp(2rem, 3vw, 2.7rem); line-height: 1.05; font-weight: 700; letter-spacing: -.03em; }
            .memo-subtitle { margin: 0; color: rgba(255, 255, 255, .82); font-size: 1rem; }
            .district-card { margin-top: 24px; border: 0; border-radius: 22px; box-shadow: var(--memo-shadow); overflow: hidden; }
            .district-card .card-body { padding: 26px; }
            .district-card .table thead th { border-top: 0; border-bottom: 1px solid #e8ecf5; color: #68708a; font-size: .75rem; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; }
            .district-card .table td { border-color: #eef1f7; color: #343958; vertical-align: middle; }
            .school-number { color: #8a92aa; font-weight: 700; width: 64px; }
            .status-chip { display: inline-flex; align-items: center; gap: 7px; padding: 6px 11px; border-radius: 999px; font-size: .78rem; font-weight: 700; }
            .status-chip.pending { color: #b45309; background: #fff7e6; }
            .status-chip.complete { color: #047857; background: #ecfdf5; }
            .hero-add-btn { display: inline-flex; align-items: center; justify-content: center; gap: 9px; padding: 10px 16px; border: none; border-radius: 14px; color: var(--memo-navy); background: linear-gradient(135deg, #fff 0%, #eef7ff 100%); font-weight: 700; transition: transform .25s ease, box-shadow .25s ease; }
            .hero-add-btn:hover { color: var(--memo-navy); transform: translateY(-2px); box-shadow: 0 14px 32px rgba(17, 24, 39, .12); text-decoration: none; }
        </style>
    </head>
    <body>
        <div id="wrapper">
            <?php include('includes/top-bar.php'); ?>
            <?php include('includes/sidebar.php'); ?>
            <div class="content-page">
                <div class="content">
                    <div class="container-fluid memo-shell">
                        <div class="row">
                            <div class="col-12">
                                <div class="memo-hero">
                                    <div class="memo-hero-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <span class="memo-eyebrow"><i class="mdi mdi-school-outline"></i> School Preparedness Checklist</span>
                                                <h1 class="memo-title"><?= $esc($districtName); ?> District</h1>
                                                <p class="memo-subtitle">Review submitted school preparedness checklists for the selected district.</p>
                                            </div>
                                            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                                <span class="memo-eyebrow">School Year <?= $esc($this->session->userdata('cur_sy')); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                                <?= $esc($this->session->flashdata('success')); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('danger')): ?>
                            <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                                <?= $esc($this->session->flashdata('danger')); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        <?php endif; ?>

                        <div class="card district-card">
                            <div class="card-body">
                                <?php if (empty($school)): ?>
                                    <p class="text-muted mb-0">No public schools are available for this district.</p>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead>
                                                <tr><th>No.</th><th>School Name</th><th>Status</th><th class="text-right">Action</th></tr>
                                            </thead>
                                            <tbody>
                                                <?php $counter = 1; ?>
                                                <?php foreach ($school as $row): ?>
                                                    <?php $submission = $this->Common->two_cond_row('brigada_spc_feedback', 'school_id', $row->schoolID, 'sy', $this->session->userdata('cur_sy')); ?>
                                                    <tr>
                                                        <td class="school-number"><?= $counter++; ?></td>
                                                        <td><?= $esc($row->schoolName); ?></td>
                                                        <td>
                                                            <?php if (empty($submission)): ?>
                                                                <span class="status-chip pending"><i class="mdi mdi-clock-outline"></i> Not submitted</span>
                                                            <?php else: ?>
                                                                <span class="status-chip complete"><i class="mdi mdi-check-circle-outline"></i> Submitted</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-right">
                                                            <?php if (!empty($submission)): ?>
                                                                <a href="<?= base_url(); ?>Brigada/brigada_spc_district/<?= $esc($row->schoolID); ?>" target="_blank" class="hero-add-btn"><i class="mdi mdi-eye-outline"></i> View Checklist</a>
                                                            <?php else: ?>
                                                                <span class="text-muted small">Awaiting submission</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include('includes/footer.php'); ?>
            </div>
        </div>
        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
    </body>
</html>
