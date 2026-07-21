<?php
$esc = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
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
            :root { --memo-navy: #272b8c; --memo-blue: #3c40c6; --memo-ink: #23275d; --memo-shadow: 0 24px 60px rgba(15,23,42,.08); }
            body { background: radial-gradient(circle at top left, rgba(60,64,198,.10), transparent 24%), linear-gradient(180deg,#f4f8fc 0%,#eef4fa 100%); }
            .content-page { background: transparent; }
            .memo-shell { position: relative; padding-bottom: 28px; }
            .memo-shell::before { content:""; position:absolute; inset:24px 0 auto; height:240px; border-radius:30px; background:linear-gradient(135deg,rgba(60,64,198,.11),rgba(122,128,255,.10)); z-index:0; }
            .memo-shell > * { position: relative; z-index: 1; }
            .memo-hero { margin-top:20px; border-radius:28px; overflow:hidden; color:#fff; box-shadow:var(--memo-shadow); background:radial-gradient(circle at top right,rgba(255,255,255,.16),transparent 32%),linear-gradient(135deg,#272b8c 0%,#3c40c6 58%,#6f74ff 100%); }
            .memo-hero-body { padding:32px; }
            .memo-eyebrow { display:inline-flex; align-items:center; gap:8px; padding:8px 14px; border-radius:999px; background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.18); font-size:.8rem; letter-spacing:.08em; text-transform:uppercase; }
            .memo-title { margin:18px 0 12px; color:#fff; font-size:clamp(2rem,3vw,2.7rem); line-height:1.05; font-weight:700; letter-spacing:-.03em; }
            .memo-subtitle { margin:0; color:rgba(255,255,255,.82); font-size:1rem; }
            .report-card { margin-top:24px; border:0; border-radius:22px; box-shadow:var(--memo-shadow); overflow:hidden; }
            .report-card .card-body { padding:26px; }
            .report-accordion .card { margin-bottom:14px; border:1px solid #e8ecf5; border-radius:16px !important; overflow:hidden; box-shadow:none; }
            .report-accordion .card-header { padding:0; border:0; background:#f8f9ff; }
            .category-toggle { display:flex; align-items:center; justify-content:space-between; width:100%; padding:18px 20px; color:var(--memo-ink); font-weight:700; text-decoration:none; }
            .category-toggle:hover { color:var(--memo-blue); text-decoration:none; }
            .category-number { display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; margin-right:10px; border-radius:50%; background:rgba(60,64,198,.1); color:var(--memo-blue); font-size:.8rem; }
            .report-table { margin-bottom:0; }
            .report-table thead th { border-top:0; border-bottom:1px solid #e8ecf5; color:#68708a; background:#fff; font-size:.72rem; font-weight:800; letter-spacing:.04em; text-align:center; text-transform:uppercase; vertical-align:middle; }
            .report-table td { border-color:#eef1f7; color:#343958; vertical-align:middle; }
            .report-table td:first-child { min-width:280px; }
            .count-link { display:inline-flex; align-items:center; justify-content:center; min-width:38px; padding:6px 10px; border-radius:999px; text-decoration:none; font-weight:800; }
            .count-link.full { color:#047857; background:#ecfdf5; }
            .count-link.partial { color:#b45309; background:#fff7e6; }
            .count-link.none { color:#b91c1c; background:#fef2f2; }
            .count-link:hover { text-decoration:none; filter:brightness(.96); }
            @media (max-width:767px) { .memo-hero-body,.report-card .card-body { padding:22px; } .report-table td:first-child { min-width:200px; } }
        </style>
    </head>
    <body>
        <div id="wrapper">
            <?php include('includes/top-bar.php'); ?>
            <?php include('includes/sidebar.php'); ?>
            <div class="content-page">
                <div class="content">
                    <div class="container-fluid memo-shell">
                        <div class="row"><div class="col-12"><div class="memo-hero"><div class="memo-hero-body"><div class="row align-items-center">
                            <div class="col-md-8">
                                <span class="memo-eyebrow"><i class="mdi mdi-chart-box-outline"></i> School Preparedness Checklist</span>
                                <h1 class="memo-title">Preparedness Summary</h1>
                                <p class="memo-subtitle">Review school compliance totals by checklist category and preparedness status.</p>
                            </div>
                            <div class="col-md-4 text-md-right mt-3 mt-md-0"><span class="memo-eyebrow">School Year <?= $esc($this->session->userdata('cur_sy')); ?></span></div>
                        </div></div></div></div></div>

                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show mt-4" role="alert"><?= $esc($this->session->flashdata('success')); ?><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('danger')): ?>
                            <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert"><?= $esc($this->session->flashdata('danger')); ?><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>
                        <?php endif; ?>

                        <div class="card report-card"><div class="card-body">
                            <div id="preparednessAccordion" class="report-accordion">
                                <?php $categoryNumber = 1; ?>
                                <?php foreach ($data as $category): ?>
                                    <?php $items = $this->Common->one_cond('brigada_spc_items', 'spc_cat_id', $category->id); $collapseId = 'summaryCategory' . (int) $category->id; ?>
                                    <div class="card">
                                        <div class="card-header" id="heading<?= (int) $category->id; ?>">
                                            <a class="category-toggle" href="#<?= $collapseId; ?>" data-toggle="collapse" aria-expanded="<?= $categoryNumber === 1 ? 'true' : 'false'; ?>" aria-controls="<?= $collapseId; ?>">
                                                <span><span class="category-number"><?= $categoryNumber; ?></span><?= $esc($category->name); ?></span><i class="mdi mdi-chevron-down"></i>
                                            </a>
                                        </div>
                                        <div id="<?= $collapseId; ?>" class="collapse <?= $categoryNumber === 1 ? 'show' : ''; ?>" data-parent="#preparednessAccordion">
                                            <div class="card-body p-0"><div class="table-responsive">
                                                <table class="table report-table">
                                                    <thead><tr><th>Category</th><th>Fully Prepared</th><th>Partially Prepared</th><th>Not Prepared</th></tr></thead>
                                                    <tbody>
                                                        <?php $itemNumber = 1; ?>
                                                        <?php foreach ($items as $item): ?>
                                                            <?php
                                                            $field = 'q' . $category->id . $itemNumber++;
                                                            $fullyPrepared = $this->Common->two_cond_count_row('brigada_spc_feedback', $field, 1, 'sy', $this->session->userdata('cur_sy'))->num_rows();
                                                            $partiallyPrepared = $this->Common->two_cond_count_row('brigada_spc_feedback', $field, 2, 'sy', $this->session->userdata('cur_sy'))->num_rows();
                                                            $notPrepared = $this->Common->two_cond_count_row('brigada_spc_feedback', $field, 3, 'sy', $this->session->userdata('cur_sy'))->num_rows();
                                                            ?>
                                                            <tr>
                                                                <td><?= $esc($item->description); ?></td>
                                                                <td class="text-center"><a class="count-link full" href="<?= base_url(); ?>Brigada/spc_feedback/1/<?= (int) $item->id; ?>"><?= $fullyPrepared; ?></a></td>
                                                                <td class="text-center"><a class="count-link partial" href="<?= base_url(); ?>Brigada/spc_feedback/2/<?= (int) $item->id; ?>"><?= $partiallyPrepared; ?></a></td>
                                                                <td class="text-center"><a class="count-link none" href="<?= base_url(); ?>Brigada/spc_feedback/3/<?= (int) $item->id; ?>"><?= $notPrepared; ?></a></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div></div>
                                        </div>
                                    </div>
                                    <?php $categoryNumber++; ?>
                                <?php endforeach; ?>
                            </div>
                        </div></div>
                    </div>
                </div>
                <?php include('includes/footer.php'); ?>
            </div>
        </div>
        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
    </body>
</html>
