<?php
$kras = isset($kras) ? $kras : array();
$employee = isset($employee) ? $employee : null;
$employeeName = '';
if (is_array($employee)) {
    $employeeName = isset($employee['name']) ? (string) $employee['name'] : '';
}
if ($employeeName === '') {
    $employeeName = trim((string) $this->session->userdata('fName') . ' ' . (string) $this->session->userdata('lName'));
}

$objectiveCount = 0;
foreach ($kras as $kra) {
    $objectiveCount += isset($kra['objectives']) ? count($kra['objectives']) : 0;
}

function mykra_scale_value($scale, $level)
{
    return (is_array($scale) && isset($scale[$level])) ? (string) $scale[$level] : '';
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <?php include(APPPATH . 'views/includes/page-title.php'); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />

        <style>
            :root {
                --ipcr-navy: #272b8c; --ipcr-blue: #3c40c6; --ipcr-ink: #23275d; --ipcr-muted: #7b7fa7;
                --ipcr-border: rgba(60, 64, 198, 0.14); --ipcr-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            }
            body { background: radial-gradient(circle at top left, rgba(60,64,198,0.10), transparent 24%), linear-gradient(180deg, #f4f8fc 0%, #eef4fa 100%); }
            .content-page { background: transparent; }
            .ipcr-shell { padding-bottom: 28px; }

            .ipcr-hero {
                margin-top: 20px; border-radius: 26px; overflow: hidden; color: #fff; box-shadow: var(--ipcr-shadow);
                background: radial-gradient(circle at top right, rgba(255,255,255,0.16), transparent 32%), linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
            }
            .ipcr-hero-body { padding: 30px; }
            .ipcr-eyebrow { display: inline-flex; align-items: center; gap: 8px; padding: 7px 14px; border-radius: 999px; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.18); font-size: 0.78rem; letter-spacing: 0.08em; text-transform: uppercase; }
            .ipcr-title { margin: 16px 0 8px; font-size: clamp(1.7rem, 2.6vw, 2.2rem); font-weight: 700; letter-spacing: -0.03em; color: #fff; }
            .ipcr-copy { max-width: 640px; margin: 0; color: rgba(255,255,255,0.82); line-height: 1.6; }
            .hero-meta { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 16px; }
            .hero-pill { display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: 12px; font-weight: 700; font-size: 0.86rem; background: rgba(255,255,255,0.14); border: 1px solid rgba(255,255,255,0.2); color: #fff; }
            .btn-hero-outline { display: inline-flex; align-items: center; gap: 8px; padding: 11px 16px; border-radius: 13px; font-weight: 700; color: #fff; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.25); }
            .btn-hero-outline:hover { color: #fff; background: rgba(255,255,255,0.2); }

            .kra-block { border: 1px solid var(--ipcr-border); border-radius: 18px; overflow: hidden; margin-top: 20px; background: #fff; box-shadow: var(--ipcr-shadow); }
            .kra-header { display: flex; align-items: center; gap: 12px; padding: 16px 20px; background: linear-gradient(135deg, rgba(60,64,198,0.09), rgba(122,128,255,0.07)); }
            .kra-index { width: 40px; height: 40px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; color: #fff; background: linear-gradient(135deg, #3c40c6, #6f74ff); flex: none; }
            .kra-title { font-weight: 700; color: var(--ipcr-navy); font-size: 1.05rem; }
            .kra-title small { display: block; color: var(--ipcr-muted); font-weight: 600; font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.06em; }

            .ipcr-table { width: 100%; border-collapse: collapse; }
            .ipcr-table th, .ipcr-table td { border: 1px solid var(--ipcr-border); padding: 10px 12px; vertical-align: top; font-size: 0.86rem; color: var(--ipcr-ink); }
            .ipcr-table thead th { background: #f4f8fc; color: var(--ipcr-muted); text-transform: uppercase; font-size: 0.72rem; letter-spacing: 0.06em; }
            .ipcr-table .col-code { width: 56px; white-space: nowrap; font-weight: 700; }
            .ipcr-table .col-weight { width: 70px; text-align: center; font-weight: 700; }
            .obj-desc { font-weight: 600; }
            .obj-timeline { display: block; margin-top: 6px; color: var(--ipcr-muted); font-size: 0.8rem; }
            .qet { margin-top: 10px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
            .qet-card { background: #f8fbff; border: 1px solid var(--ipcr-border); border-radius: 10px; padding: 8px 10px; }
            .qet-card h6 { margin: 0 0 6px; font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--ipcr-blue); font-weight: 700; }
            .qet-card ul { margin: 0; padding-left: 16px; }
            .qet-card li { font-size: 0.78rem; margin-bottom: 2px; }
            .qet-card li b { color: var(--ipcr-navy); }

            .empty-panel { margin-top: 22px; border: 1px dashed var(--ipcr-border); border-radius: 18px; background: #fff; text-align: center; padding: 44px 24px; color: var(--ipcr-muted); }
            .empty-panel i { font-size: 2.4rem; color: var(--ipcr-blue); display: block; margin-bottom: 10px; }
            .empty-panel h5 { color: var(--ipcr-ink); font-weight: 700; }

            @media (max-width: 900px) { .qet { grid-template-columns: 1fr; } }
            @media print {
                #wrapper .left-side-menu, #wrapper .navbar-custom, .btn-hero-outline, .footer { display: none !important; }
                body { background: #fff; } .content-page { margin-left: 0 !important; } .kra-block { box-shadow: none; }
            }
        </style>
    </head>

    <body>
        <div id="wrapper">
            <?php include(APPPATH . 'views/includes/top-bar.php'); ?>
            <?php include(APPPATH . 'views/includes/sidebar.php'); ?>

            <div class="content-page">
                <div class="content">
                    <div class="container-fluid ipcr-shell">

                        <div class="ipcr-hero">
                            <div class="ipcr-hero-body">
                                <div class="row align-items-center">
                                    <div class="col-lg-8">
                                        <span class="ipcr-eyebrow"><i class="mdi mdi-clipboard-account-outline"></i> My IPCR</span>
                                        <h1 class="ipcr-title"><?= htmlspecialchars($employeeName !== '' ? $employeeName : 'My Assigned KRAs', ENT_QUOTES, 'UTF-8'); ?></h1>
                                        <p class="ipcr-copy">These are the Key Result Areas your SGOD Chief assigned to you, with their objectives and performance standards. They appear in the order they were assigned.</p>
                                        <div class="hero-meta">
                                            <span class="hero-pill"><i class="mdi mdi-format-list-checks"></i> <?= count($kras); ?> KRAs</span>
                                            <span class="hero-pill"><i class="mdi mdi-target"></i> <?= $objectiveCount; ?> Objectives</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                                        <?php if (!empty($kras)) : ?>
                                            <button type="button" class="btn-hero-outline" onclick="window.print()"><i class="mdi mdi-printer"></i> Print</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if (empty($kras)) : ?>
                            <div class="empty-panel">
                                <i class="mdi mdi-clipboard-text-off-outline"></i>
                                <h5>No KRAs assigned yet</h5>
                                <p>Your SGOD Chief has not tagged you into any KRA. Once you are tagged, the KRAs and their objectives will appear here.</p>
                            </div>
                        <?php else : ?>
                            <?php foreach ($kras as $index => $kra) : ?>
                                <div class="kra-block">
                                    <div class="kra-header">
                                        <span class="kra-index"><?= $index + 1; ?></span>
                                        <span class="kra-title">
                                            <small>KRA <?= $index + 1; ?></small>
                                            <?= htmlspecialchars($kra['title'], ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="ipcr-table">
                                            <thead>
                                                <tr>
                                                    <th class="col-code">Code</th>
                                                    <th>Objective &amp; Performance Standards</th>
                                                    <th class="col-weight">Weight</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($kra['objectives'])) : ?>
                                                    <tr><td colspan="3" class="text-center text-muted">No objectives defined for this KRA yet.</td></tr>
                                                <?php else : ?>
                                                    <?php foreach ($kra['objectives'] as $objective) : ?>
                                                        <tr>
                                                            <td class="col-code"><?= htmlspecialchars($objective['code'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                            <td>
                                                                <span class="obj-desc"><?= htmlspecialchars($objective['objective'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                                <?php if (trim((string) $objective['timeline']) !== '') : ?>
                                                                    <span class="obj-timeline"><i class="mdi mdi-clock-outline"></i> <?= htmlspecialchars($objective['timeline'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                                <?php endif; ?>
                                                                <div class="qet">
                                                                    <?php
                                                                    $dims = array('Quality' => $objective['quality'], 'Efficiency' => $objective['efficiency'], 'Timeliness' => $objective['timeliness']);
                                                                    foreach ($dims as $dimName => $dimScale) : ?>
                                                                        <div class="qet-card">
                                                                            <h6><?= $dimName; ?></h6>
                                                                            <ul>
                                                                                <?php foreach (array('5','4','3','2','1') as $lvl) : $val = mykra_scale_value($dimScale, $lvl); if ($val === '') continue; ?>
                                                                                    <li><b><?= $lvl; ?>:</b> <?= htmlspecialchars($val, ENT_QUOTES, 'UTF-8'); ?></li>
                                                                                <?php endforeach; ?>
                                                                            </ul>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            </td>
                                                            <td class="col-weight"><?= rtrim(rtrim(number_format((float) $objective['weight'], 2), '0'), '.'); ?>%</td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>
                </div>
                <?php include(APPPATH . 'views/includes/footer.php'); ?>
            </div>
        </div>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
    </body>
</html>
