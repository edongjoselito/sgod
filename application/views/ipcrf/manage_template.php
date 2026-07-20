<?php
$template = isset($bundle['template']) ? $bundle['template'] : null;
$kras = isset($bundle['kras']) ? $bundle['kras'] : array();
$competencies = isset($bundle['competencies']) ? $bundle['competencies'] : array();
$totalWeight = isset($bundle['total_weight']) ? (float) $bundle['total_weight'] : 0;

$members = isset($members) ? $members : array();
$assignmentMap = isset($assignment_map) ? $assignment_map : array();
$memberNameMap = array();
foreach ($members as $member) {
    $fullName = trim(((string) $member['lName']) . ', ' . ((string) $member['fName']), ', ');
    $memberNameMap[$member['username']] = ($fullName !== '' ? $fullName : $member['username']);
}

$competencyGroups = array();
foreach ($competencies as $competency) {
    $competencyGroups[$competency['category']][] = $competency;
}

$levels = array(
    '5' => '5 — Outstanding',
    '4' => '4 — Very Satisfactory',
    '3' => '3 — Satisfactory',
    '2' => '2 — Unsatisfactory',
    '1' => '1 — Poor'
);

$weightOk = (abs($totalWeight - 100) < 0.01);

function ipcr_scale_value($scale, $level)
{
    if (is_array($scale) && isset($scale[$level])) {
        return (string) $scale[$level];
    }
    return '';
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
        <link href="<?= base_url(); ?>assets/libs/select2/select2.min.css" rel="stylesheet" type="text/css" />

        <style>
            :root {
                --ipcr-navy: #272b8c;
                --ipcr-blue: #3c40c6;
                --ipcr-ink: #23275d;
                --ipcr-muted: #7b7fa7;
                --ipcr-border: rgba(60, 64, 198, 0.14);
                --ipcr-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            }

            body {
                background:
                    radial-gradient(circle at top left, rgba(60, 64, 198, 0.10), transparent 24%),
                    linear-gradient(180deg, #f4f8fc 0%, #eef4fa 100%);
            }

            .content-page { background: transparent; }

            .ipcr-shell { padding-bottom: 28px; }

            .ipcr-hero {
                margin-top: 20px;
                border-radius: 26px;
                overflow: hidden;
                color: #fff;
                box-shadow: var(--ipcr-shadow);
                background:
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 32%),
                    linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
            }

            .ipcr-hero-body { padding: 30px; }

            .ipcr-eyebrow {
                display: inline-flex; align-items: center; gap: 8px;
                padding: 7px 14px; border-radius: 999px;
                background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.18);
                font-size: 0.78rem; letter-spacing: 0.08em; text-transform: uppercase;
            }

            .ipcr-title { margin: 16px 0 8px; font-size: clamp(1.7rem, 2.6vw, 2.3rem); font-weight: 700; letter-spacing: -0.03em; color: #fff; }
            .ipcr-copy { max-width: 640px; margin: 0; color: rgba(255, 255, 255, 0.82); line-height: 1.6; }

            .hero-meta { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 16px; }
            .hero-pill {
                display: inline-flex; align-items: center; gap: 8px;
                padding: 8px 14px; border-radius: 12px; font-weight: 700; font-size: 0.86rem;
                background: rgba(255, 255, 255, 0.14); border: 1px solid rgba(255, 255, 255, 0.2); color: #fff;
            }
            .hero-pill.warn { background: rgba(255, 205, 120, 0.22); border-color: rgba(255, 205, 120, 0.5); }

            .btn-hero {
                display: inline-flex; align-items: center; gap: 8px;
                padding: 11px 16px; border: none; border-radius: 13px; font-weight: 700;
                color: var(--ipcr-navy); background: linear-gradient(135deg, #ffffff 0%, #eef7ff 100%);
            }
            .btn-hero:hover { color: var(--ipcr-navy); }
            .btn-hero-outline {
                display: inline-flex; align-items: center; gap: 8px;
                padding: 11px 16px; border-radius: 13px; font-weight: 700;
                color: #fff; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.25);
            }
            .btn-hero-outline:hover { color: #fff; background: rgba(255,255,255,0.2); }

            .panel {
                border: 1px solid var(--ipcr-border); border-radius: 20px; background: #fff;
                box-shadow: var(--ipcr-shadow); overflow: hidden; margin-top: 22px;
            }
            .panel-head { display: flex; align-items: center; justify-content: space-between; gap: 14px; padding: 20px 24px; border-bottom: 1px solid var(--ipcr-border); flex-wrap: wrap; }
            .panel-head h4 { margin: 0; color: var(--ipcr-ink); font-weight: 700; letter-spacing: -0.02em; }
            .panel-head p { margin: 4px 0 0; color: var(--ipcr-muted); font-size: 0.88rem; }
            .panel-body { padding: 18px 24px 24px; }

            .kra-block { border: 1px solid var(--ipcr-border); border-radius: 16px; overflow: hidden; margin-bottom: 18px; }
            .kra-header {
                display: flex; align-items: center; justify-content: space-between; gap: 12px;
                padding: 14px 18px; background: linear-gradient(135deg, rgba(60,64,198,0.09), rgba(122,128,255,0.07));
            }
            .kra-header .kra-title { font-weight: 700; color: var(--ipcr-navy); font-size: 1.02rem; }

            .ipcr-table { width: 100%; border-collapse: collapse; }
            .ipcr-table th, .ipcr-table td { border: 1px solid var(--ipcr-border); padding: 10px 12px; vertical-align: top; font-size: 0.86rem; color: var(--ipcr-ink); }
            .ipcr-table thead th { background: #f4f8fc; color: var(--ipcr-muted); text-transform: uppercase; font-size: 0.72rem; letter-spacing: 0.06em; }
            .ipcr-table .col-code { width: 56px; white-space: nowrap; font-weight: 700; }
            .ipcr-table .col-weight { width: 70px; text-align: center; font-weight: 700; }
            .ipcr-table .col-actions { width: 96px; }
            .obj-desc { font-weight: 600; }
            .obj-timeline { display: block; margin-top: 6px; color: var(--ipcr-muted); font-size: 0.8rem; }

            .qet { margin-top: 10px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
            .qet-card { background: #f8fbff; border: 1px solid var(--ipcr-border); border-radius: 10px; padding: 8px 10px; }
            .qet-card h6 { margin: 0 0 6px; font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--ipcr-blue); font-weight: 700; }
            .qet-card ul { margin: 0; padding-left: 16px; }
            .qet-card li { font-size: 0.78rem; color: var(--ipcr-ink); margin-bottom: 2px; }
            .qet-card li b { color: var(--ipcr-navy); }

            .comp-group { margin-bottom: 18px; }
            .comp-group-title { font-weight: 700; color: var(--ipcr-navy); margin: 0 0 10px; }

            .icon-btn {
                display: inline-flex; align-items: center; gap: 5px; border: none; border-radius: 9px;
                padding: 6px 10px; font-size: 0.78rem; font-weight: 700; cursor: pointer;
            }
            .icon-btn.edit { background: rgba(60,64,198,0.1); color: var(--ipcr-blue); }
            .icon-btn.edit:hover { background: rgba(60,64,198,0.18); }
            .icon-btn.del { background: rgba(207,73,100,0.12); color: #cf4964; }
            .icon-btn.del:hover { background: rgba(207,73,100,0.2); color: #cf4964; }
            .icon-btn.add { background: rgba(60,64,198,0.1); color: var(--ipcr-blue); }

            .empty { text-align: center; padding: 30px; color: var(--ipcr-muted); }
            .empty i { font-size: 2rem; color: var(--ipcr-blue); display: block; margin-bottom: 8px; }

            /* Modals */
            .modal-content { border: none; border-radius: 20px; overflow: hidden; box-shadow: 0 28px 70px rgba(15,23,42,0.18); }
            .modal-header-ipcr { padding: 20px 24px; color: #fff; background: linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%); }
            .modal-header-ipcr h5 { margin: 0; color: #fff; font-weight: 700; }
            .modal-header-ipcr .close { color: #fff; opacity: 0.9; text-shadow: none; }
            .modal-body-ipcr { padding: 22px 24px; background: #f8fbff; }
            .lbl { font-weight: 700; color: var(--ipcr-ink); margin-bottom: 6px; }
            .form-control { border-radius: 11px; border: 1px solid var(--ipcr-border); }
            .qet-input-table { width: 100%; border-collapse: collapse; }
            .qet-input-table th, .qet-input-table td { border: 1px solid var(--ipcr-border); padding: 6px; }
            .qet-input-table thead th { background: #eef3fb; font-size: 0.74rem; text-transform: uppercase; color: var(--ipcr-muted); }
            .qet-input-table td textarea { width: 100%; border: none; resize: vertical; min-height: 40px; font-size: 0.82rem; background: transparent; }
            .qet-input-table .lvl { width: 56px; text-align: center; font-weight: 700; color: var(--ipcr-navy); background: #f8fbff; }
            .btn-primary-ipcr { border: none; border-radius: 12px; padding: 10px 18px; font-weight: 700; color: #fff; background: linear-gradient(135deg, #3c40c6 0%, #6f74ff 100%); }
            .btn-primary-ipcr:hover { color: #fff; }
            .btn-soft { border: none; border-radius: 12px; padding: 10px 16px; font-weight: 700; color: var(--ipcr-ink); background: #eef3f8; }

            .icon-btn.tag { background: rgba(19, 143, 108, 0.12); color: #0f8b6c; }
            .icon-btn.tag:hover { background: rgba(19, 143, 108, 0.2); color: #0f8b6c; }
            .kra-tags { display: flex; flex-wrap: wrap; gap: 6px; padding: 10px 18px 0; }
            .member-chip { display: inline-flex; align-items: center; gap: 6px; padding: 5px 11px; border-radius: 999px; background: rgba(60,64,198,0.08); color: var(--ipcr-navy); font-size: 0.78rem; font-weight: 700; }
            .member-chip i { font-size: 0.9rem; color: var(--ipcr-blue); }
            .kra-tags .none { color: var(--ipcr-muted); font-size: 0.8rem; font-style: italic; }
            .select2-container { width: 100% !important; }
            .select2-container--default .select2-selection--multiple { border-radius: 11px; border: 1px solid var(--ipcr-border); min-height: 46px; }
            .select2-container--default .select2-results__option--highlighted[aria-selected] { background: var(--ipcr-blue); }
            .select2-dropdown { z-index: 1060; }

            @media (max-width: 900px) { .qet { grid-template-columns: 1fr; } }

            @media print {
                #wrapper .left-side-menu, #wrapper .navbar-custom, .ipcr-hero .btn-hero, .ipcr-hero .btn-hero-outline,
                .panel-head .icon-btn, .col-actions, .kra-header .icon-btn, .comp-actions, .footer, .modal, .modal-backdrop { display: none !important; }
                body { background: #fff; }
                .content-page { margin-left: 0 !important; }
                .panel, .kra-block { box-shadow: none; }
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

                        <?php if ($this->session->flashdata('success')) : ?>
                            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <?= $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('danger')) : ?>
                            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <?= $this->session->flashdata('danger'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!$template) : ?>
                            <div class="panel"><div class="empty"><i class="mdi mdi-clipboard-alert-outline"></i>No active IPCR template found. Open the IPCRF module once to seed the default preset.</div></div>
                        <?php else : ?>

                        <div class="ipcr-hero">
                            <div class="ipcr-hero-body">
                                <div class="row align-items-center">
                                    <div class="col-lg-8">
                                        <span class="ipcr-eyebrow"><i class="mdi mdi-clipboard-edit-outline"></i> Manage IPCR — SGOD Chief</span>
                                        <h1 class="ipcr-title"><?= htmlspecialchars($template['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
                                        <p class="ipcr-copy">Set up the shared IPCR that every SGOD member's IPCRF is generated from. Changes here define the KRAs, objectives, performance standards and competencies for the whole section.</p>
                                        <div class="hero-meta">
                                            <span class="hero-pill"><i class="mdi mdi-calendar"></i> Year <?= htmlspecialchars((string) $template['year'], ENT_QUOTES, 'UTF-8'); ?></span>
                                            <span class="hero-pill <?= $weightOk ? '' : 'warn'; ?>">
                                                <i class="mdi mdi-scale-balance"></i> Total Weight <?= rtrim(rtrim(number_format($totalWeight, 2), '0'), '.'); ?>%<?= $weightOk ? '' : ' (should be 100%)'; ?>
                                            </span>
                                            <span class="hero-pill"><i class="mdi mdi-format-list-checks"></i> <?= count($kras); ?> KRAs</span>
                                            <span class="hero-pill"><i class="mdi mdi-star-outline"></i> <?= count($competencies); ?> Competencies</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                                        <button type="button" class="btn-hero mb-2" data-toggle="modal" data-target="#metaModal"><i class="mdi mdi-pencil"></i> Edit Details</button>
                                        <button type="button" class="btn-hero-outline mb-2" onclick="window.print()"><i class="mdi mdi-printer"></i> Preview / Print</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- KRAs & Objectives -->
                        <div class="panel">
                            <div class="panel-head">
                                <div>
                                    <h4>Key Result Areas &amp; Objectives</h4>
                                    <p>Grouped by KRA with weight and the 5-point Quality / Efficiency / Timeliness standards.</p>
                                </div>
                                <button type="button" class="icon-btn add" data-toggle="modal" data-target="#kraModal" onclick="prepKra()"><i class="mdi mdi-plus"></i> Add KRA</button>
                            </div>
                            <div class="panel-body">
                                <?php if (empty($kras)) : ?>
                                    <div class="empty"><i class="mdi mdi-format-list-bulleted"></i>No KRAs yet. Start by adding a Key Result Area.</div>
                                <?php else : ?>
                                    <?php foreach ($kras as $kra) : ?>
                                        <?php $kraMembers = isset($assignmentMap[(int) $kra['id']]) ? $assignmentMap[(int) $kra['id']] : array(); ?>
                                        <div class="kra-block">
                                            <div class="kra-header">
                                                <span class="kra-title"><?= htmlspecialchars($kra['title'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                <span>
                                                    <button type="button" class="icon-btn tag" data-toggle="modal" data-target="#tagModal"
                                                        onclick='prepTag(<?= (int) $kra['id']; ?>, <?= htmlspecialchars(json_encode($kra['title']), ENT_QUOTES, 'UTF-8'); ?>, <?= htmlspecialchars(json_encode(array_values($kraMembers)), ENT_QUOTES, 'UTF-8'); ?>)'><i class="mdi mdi-account-multiple-plus-outline"></i> Tag Members (<?= count($kraMembers); ?>)</button>
                                                    <button type="button" class="icon-btn add" data-toggle="modal" data-target="#objectiveModal"
                                                        onclick='prepObjective(<?= (int) $kra['id']; ?>, null)'><i class="mdi mdi-plus"></i> Objective</button>
                                                    <button type="button" class="icon-btn edit" data-toggle="modal" data-target="#kraModal"
                                                        onclick='prepKra(<?= json_encode(array("id" => (int) $kra['id'], "title" => $kra['title'])); ?>)'><i class="mdi mdi-pencil"></i></button>
                                                    <a class="icon-btn del js-del" href="<?= base_url(); ?>Ipcrf/kra_delete?id=<?= (int) $kra['id']; ?>"
                                                        data-confirm="Delete this KRA and ALL its objectives?"><i class="mdi mdi-trash-can-outline"></i></a>
                                                </span>
                                            </div>
                                            <div class="kra-tags">
                                                <?php if (empty($kraMembers)) : ?>
                                                    <span class="none">No members tagged yet — use “Tag Members” to assign this KRA.</span>
                                                <?php else : ?>
                                                    <?php foreach ($kraMembers as $memberUsername) : ?>
                                                        <span class="member-chip"><i class="mdi mdi-account"></i><?= htmlspecialchars(isset($memberNameMap[$memberUsername]) ? $memberNameMap[$memberUsername] : $memberUsername, ENT_QUOTES, 'UTF-8'); ?></span>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="ipcr-table">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-code">Code</th>
                                                            <th>Objective &amp; Performance Standards</th>
                                                            <th class="col-weight">Weight</th>
                                                            <th class="col-actions text-center">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (empty($kra['objectives'])) : ?>
                                                            <tr><td colspan="4" class="text-center text-muted">No objectives under this KRA yet.</td></tr>
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
                                                                                        <?php foreach (array('5','4','3','2','1') as $lvl) : $val = ipcr_scale_value($dimScale, $lvl); if ($val === '') continue; ?>
                                                                                            <li><b><?= $lvl; ?>:</b> <?= htmlspecialchars($val, ENT_QUOTES, 'UTF-8'); ?></li>
                                                                                        <?php endforeach; ?>
                                                                                    </ul>
                                                                                </div>
                                                                            <?php endforeach; ?>
                                                                        </div>
                                                                    </td>
                                                                    <td class="col-weight"><?= rtrim(rtrim(number_format((float) $objective['weight'], 2), '0'), '.'); ?>%</td>
                                                                    <td class="col-actions text-center">
                                                                        <button type="button" class="icon-btn edit mb-1" data-toggle="modal" data-target="#objectiveModal"
                                                                            onclick='prepObjective(<?= (int) $kra['id']; ?>, <?= htmlspecialchars(json_encode($objective), ENT_QUOTES, 'UTF-8'); ?>)'><i class="mdi mdi-pencil"></i></button>
                                                                        <a class="icon-btn del js-del" href="<?= base_url(); ?>Ipcrf/objective_delete?id=<?= (int) $objective['id']; ?>" data-confirm="Delete this objective?"><i class="mdi mdi-trash-can-outline"></i></a>
                                                                    </td>
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

                        <!-- Competencies -->
                        <div class="panel">
                            <div class="panel-head">
                                <div>
                                    <h4>Competencies</h4>
                                    <p>Behavioral competencies and skills evaluated for every member.</p>
                                </div>
                                <button type="button" class="icon-btn add" data-toggle="modal" data-target="#competencyModal" onclick="prepCompetency(null)"><i class="mdi mdi-plus"></i> Add Competency</button>
                            </div>
                            <div class="panel-body">
                                <?php if (empty($competencies)) : ?>
                                    <div class="empty"><i class="mdi mdi-star-outline"></i>No competencies yet.</div>
                                <?php else : ?>
                                    <?php foreach ($competencyGroups as $groupName => $groupItems) : ?>
                                        <div class="comp-group">
                                            <p class="comp-group-title"><?= htmlspecialchars($groupName, ENT_QUOTES, 'UTF-8'); ?></p>
                                            <div class="table-responsive">
                                                <table class="ipcr-table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:200px;">Competency</th>
                                                            <th>Behavioral Indicators</th>
                                                            <th class="col-actions text-center comp-actions">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($groupItems as $competency) : ?>
                                                            <tr>
                                                                <td class="obj-desc"><?= htmlspecialchars($competency['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                                <td>
                                                                    <ul style="margin:0; padding-left:18px;">
                                                                        <?php foreach ($competency['indicators'] as $indicator) : ?>
                                                                            <li style="font-size:0.82rem;"><?= htmlspecialchars($indicator, ENT_QUOTES, 'UTF-8'); ?></li>
                                                                        <?php endforeach; ?>
                                                                    </ul>
                                                                </td>
                                                                <td class="col-actions text-center comp-actions">
                                                                    <button type="button" class="icon-btn edit mb-1" data-toggle="modal" data-target="#competencyModal"
                                                                        onclick='prepCompetency(<?= htmlspecialchars(json_encode($competency), ENT_QUOTES, 'UTF-8'); ?>)'><i class="mdi mdi-pencil"></i></button>
                                                                    <a class="icon-btn del js-del" href="<?= base_url(); ?>Ipcrf/competency_delete?id=<?= (int) $competency['id']; ?>" data-confirm="Delete this competency?"><i class="mdi mdi-trash-can-outline"></i></a>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php endif; /* template */ ?>

                    </div>
                </div>
                <?php include(APPPATH . 'views/includes/footer.php'); ?>
            </div>
        </div>

        <?php if ($template) : ?>
        <!-- Meta modal -->
        <div id="metaModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header-ipcr"><button type="button" class="close" data-dismiss="modal">&times;</button><h5>Edit IPCR Template Details</h5></div>
                    <form method="post" action="<?= base_url(); ?>Ipcrf/template_meta_save">
                        <div class="modal-body-ipcr">
                            <div class="form-group"><label class="lbl">Template Name</label><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($template['name'], ENT_QUOTES, 'UTF-8'); ?>" required></div>
                            <div class="form-group"><label class="lbl">Year</label><input type="number" name="year" class="form-control" value="<?= htmlspecialchars((string) $template['year'], ENT_QUOTES, 'UTF-8'); ?>" min="2000" max="2100" required></div>
                            <div class="form-group mb-0"><label class="lbl">Description</label><textarea name="description" class="form-control" rows="3"><?= htmlspecialchars((string) $template['description'], ENT_QUOTES, 'UTF-8'); ?></textarea></div>
                        </div>
                        <div class="modal-body-ipcr d-flex justify-content-end" style="gap:10px; padding-top:0;">
                            <button type="button" class="btn-soft" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-primary-ipcr">Save Details</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- KRA modal -->
        <div id="kraModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header-ipcr"><button type="button" class="close" data-dismiss="modal">&times;</button><h5 id="kraModalTitle">Add KRA</h5></div>
                    <form method="post" action="<?= base_url(); ?>Ipcrf/kra_save">
                        <div class="modal-body-ipcr">
                            <input type="hidden" name="kra_id" id="kra_id" value="">
                            <div class="form-group mb-0"><label class="lbl">KRA Title</label><input type="text" name="title" id="kra_title" class="form-control" placeholder="e.g. I – RESOURCING" required></div>
                        </div>
                        <div class="modal-body-ipcr d-flex justify-content-end" style="gap:10px; padding-top:0;">
                            <button type="button" class="btn-soft" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-primary-ipcr">Save KRA</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Objective modal -->
        <div id="objectiveModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header-ipcr"><button type="button" class="close" data-dismiss="modal">&times;</button><h5 id="objectiveModalTitle">Add Objective</h5></div>
                    <form method="post" action="<?= base_url(); ?>Ipcrf/objective_save">
                        <div class="modal-body-ipcr">
                            <input type="hidden" name="kra_id" id="obj_kra_id" value="">
                            <input type="hidden" name="objective_id" id="obj_id" value="">
                            <div class="form-row">
                                <div class="form-group col-md-2"><label class="lbl">Code</label><input type="text" name="code" id="obj_code" class="form-control" placeholder="1.1"></div>
                                <div class="form-group col-md-8"><label class="lbl">Objective</label><input type="text" name="objective" id="obj_objective" class="form-control" required></div>
                                <div class="form-group col-md-2"><label class="lbl">Weight (%)</label><input type="number" step="0.01" name="weight" id="obj_weight" class="form-control" value="0"></div>
                            </div>
                            <div class="form-group"><label class="lbl">Timeline</label><input type="text" name="timeline" id="obj_timeline" class="form-control" placeholder="e.g. January to December 2025"></div>
                            <label class="lbl">Performance Standards (5 = Outstanding … 1 = Poor)</label>
                            <div class="table-responsive">
                                <table class="qet-input-table">
                                    <thead><tr><th class="lvl">Level</th><th>Quality</th><th>Efficiency</th><th>Timeliness</th></tr></thead>
                                    <tbody>
                                        <?php foreach (array('5','4','3','2','1') as $lvl) : ?>
                                            <tr>
                                                <td class="lvl"><?= $lvl; ?></td>
                                                <td><textarea name="quality[<?= $lvl; ?>]" id="obj_q_<?= $lvl; ?>"></textarea></td>
                                                <td><textarea name="efficiency[<?= $lvl; ?>]" id="obj_e_<?= $lvl; ?>"></textarea></td>
                                                <td><textarea name="timeliness[<?= $lvl; ?>]" id="obj_t_<?= $lvl; ?>"></textarea></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-body-ipcr d-flex justify-content-end" style="gap:10px; padding-top:0;">
                            <button type="button" class="btn-soft" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-primary-ipcr">Save Objective</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Competency modal -->
        <div id="competencyModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header-ipcr"><button type="button" class="close" data-dismiss="modal">&times;</button><h5 id="competencyModalTitle">Add Competency</h5></div>
                    <form method="post" action="<?= base_url(); ?>Ipcrf/competency_save">
                        <div class="modal-body-ipcr">
                            <input type="hidden" name="competency_id" id="comp_id" value="">
                            <div class="form-row">
                                <div class="form-group col-md-6"><label class="lbl">Category</label><input type="text" name="category" id="comp_category" class="form-control" placeholder="e.g. Core Behavioral Competency" required></div>
                                <div class="form-group col-md-6"><label class="lbl">Competency Name</label><input type="text" name="name" id="comp_name" class="form-control" required></div>
                            </div>
                            <label class="lbl">Behavioral Indicators (up to 5)</label>
                            <?php for ($i = 0; $i < 5; $i++) : ?>
                                <input type="text" name="indicators[]" id="comp_ind_<?= $i; ?>" class="form-control mb-2" placeholder="Indicator <?= $i + 1; ?>">
                            <?php endfor; ?>
                        </div>
                        <div class="modal-body-ipcr d-flex justify-content-end" style="gap:10px; padding-top:0;">
                            <button type="button" class="btn-soft" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-primary-ipcr">Save Competency</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tag Members modal -->
        <div id="tagModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header-ipcr"><button type="button" class="close" data-dismiss="modal">&times;</button><h5>Tag Members — <span id="tag_kra_name"></span></h5></div>
                    <form method="post" action="<?= base_url(); ?>Ipcrf/kra_assign">
                        <div class="modal-body-ipcr">
                            <input type="hidden" name="kra_id" id="tag_kra_id" value="">
                            <label class="lbl">Members assigned to this KRA</label>
                            <select name="members[]" id="tag_members" class="form-control" multiple data-placeholder="Select members to tag…">
                                <?php foreach ($members as $member) :
                                    $mName = trim(((string) $member['lName']) . ', ' . ((string) $member['fName']), ', ');
                                    $mName = $mName !== '' ? $mName : $member['username'];
                                    $mSection = trim((string) $member['section']);
                                    ?>
                                    <option value="<?= htmlspecialchars($member['username'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <?= htmlspecialchars($mName . ($mSection !== '' ? ' — ' . $mSection : ''), ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted d-block mt-2">Tagged members will see this KRA (with its objectives) on their “My IPCR” page. The order tagged becomes their KRA order.</small>
                        </div>
                        <div class="modal-body-ipcr d-flex justify-content-end" style="gap:10px; padding-top:0;">
                            <button type="button" class="btn-soft" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-primary-ipcr">Save Tags</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/select2/select2.min.js"></script>
        <script>
            function prepKra(kra) {
                document.getElementById('kra_id').value = kra ? kra.id : '';
                document.getElementById('kra_title').value = kra ? kra.title : '';
                document.getElementById('kraModalTitle').textContent = kra ? 'Edit KRA' : 'Add KRA';
            }

            function prepObjective(kraId, obj) {
                document.getElementById('obj_kra_id').value = kraId || '';
                document.getElementById('obj_id').value = obj ? obj.id : '';
                document.getElementById('obj_code').value = obj ? (obj.code || '') : '';
                document.getElementById('obj_objective').value = obj ? (obj.objective || '') : '';
                document.getElementById('obj_weight').value = obj ? (obj.weight || 0) : 0;
                document.getElementById('obj_timeline').value = obj ? (obj.timeline || '') : '';
                ['5','4','3','2','1'].forEach(function (lvl) {
                    document.getElementById('obj_q_' + lvl).value = obj && obj.quality ? (obj.quality[lvl] || '') : '';
                    document.getElementById('obj_e_' + lvl).value = obj && obj.efficiency ? (obj.efficiency[lvl] || '') : '';
                    document.getElementById('obj_t_' + lvl).value = obj && obj.timeliness ? (obj.timeliness[lvl] || '') : '';
                });
                document.getElementById('objectiveModalTitle').textContent = obj ? 'Edit Objective' : 'Add Objective';
            }

            function prepCompetency(comp) {
                document.getElementById('comp_id').value = comp ? comp.id : '';
                document.getElementById('comp_category').value = comp ? (comp.category || '') : '';
                document.getElementById('comp_name').value = comp ? (comp.name || '') : '';
                var indicators = (comp && comp.indicators) ? comp.indicators : [];
                for (var i = 0; i < 5; i++) {
                    document.getElementById('comp_ind_' + i).value = indicators[i] || '';
                }
                document.getElementById('competencyModalTitle').textContent = comp ? 'Edit Competency' : 'Add Competency';
            }

            function prepTag(kraId, kraName, assigned) {
                document.getElementById('tag_kra_id').value = kraId || '';
                document.getElementById('tag_kra_name').textContent = kraName || '';
                if (window.jQuery) {
                    jQuery('#tag_members').val(assigned || []).trigger('change');
                }
            }

            document.addEventListener('click', function (e) {
                var link = e.target.closest ? e.target.closest('.js-del') : null;
                if (link) {
                    if (!window.confirm(link.getAttribute('data-confirm') || 'Are you sure?')) {
                        e.preventDefault();
                    }
                }
            });

            if (window.jQuery) {
                jQuery(function ($) {
                    $('#tag_members').select2({
                        placeholder: 'Select members to tag…',
                        width: '100%',
                        dropdownParent: $('#tagModal')
                    });
                });
            }
        </script>
    </body>
</html>
