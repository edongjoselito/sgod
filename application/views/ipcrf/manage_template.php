<?php
$template = isset($bundle['template']) ? $bundle['template'] : null;
$kras = isset($bundle['kras']) ? $bundle['kras'] : array();

$members = isset($members) ? $members : array();
$assignmentMap = isset($assignment_map) ? $assignment_map : array();
$memberNameMap = array();
foreach ($members as $member) {
    $fullName = trim(((string) $member['lName']) . ', ' . ((string) $member['fName']), ', ');
    $memberNameMap[$member['username']] = ($fullName !== '' ? $fullName : $member['username']);
}

$objectiveCount = 0;
foreach ($kras as $kra) {
    $objectiveCount += count(isset($kra['objectives']) ? $kra['objectives'] : array());
}

$taggedMembers = array();
foreach ($assignmentMap as $assignedUsernames) {
    foreach ($assignedUsernames as $assignedUsername) {
        $taggedMembers[$assignedUsername] = TRUE;
    }
}
$taggedCount = count($taggedMembers);
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
                margin-top: 20px; border-radius: 26px; overflow: hidden; color: #fff; box-shadow: var(--ipcr-shadow);
                background: radial-gradient(circle at top right, rgba(255,255,255,0.16), transparent 32%), linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
            }
            .ipcr-hero-body { padding: 30px; }
            .ipcr-eyebrow { display: inline-flex; align-items: center; gap: 8px; padding: 7px 14px; border-radius: 999px; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.18); font-size: 0.78rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; }
            .ipcr-title { margin: 16px 0 8px; font-size: clamp(1.7rem, 2.6vw, 2.2rem); font-weight: 700; letter-spacing: -0.03em; color: #fff; }
            .ipcr-copy { max-width: 640px; margin: 0; color: rgba(255,255,255,0.82); line-height: 1.6; }
            .hero-meta { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 18px; }
            .hero-pill { display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: 12px; font-weight: 700; font-size: 0.86rem; background: rgba(255,255,255,0.14); border: 1px solid rgba(255,255,255,0.2); color: #fff; }
            .btn-hero {
                display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; border-radius: 13px;
                font-weight: 700; color: var(--ipcr-navy); background: #fff; border: none; cursor: pointer;
                box-shadow: 0 12px 26px rgba(15, 23, 42, 0.16);
            }
            .btn-hero:hover { color: var(--ipcr-blue); }
            .hero-template { margin-top: 14px; font-size: 0.82rem; color: rgba(255,255,255,0.7); }
            .hero-template b { color: rgba(255,255,255,0.95); font-weight: 700; }

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
            .kra-header .kra-head-left { display: flex; align-items: center; gap: 12px; min-width: 0; }
            .kra-header .kra-index { width: 40px; height: 40px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; color: #fff; background: linear-gradient(135deg, #3c40c6, #6f74ff); flex: none; }
            .kra-header .kra-title { font-weight: 700; color: var(--ipcr-navy); font-size: 1.02rem; }
            .kra-header .kra-title small { display: block; color: var(--ipcr-muted); font-weight: 600; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.06em; }
            .kra-actions { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }

            .ipcr-table { width: 100%; border-collapse: collapse; }
            .ipcr-table th, .ipcr-table td { border: 1px solid var(--ipcr-border); padding: 10px 12px; vertical-align: top; font-size: 0.86rem; color: var(--ipcr-ink); }
            .ipcr-table thead th { background: #f4f8fc; color: var(--ipcr-muted); text-transform: uppercase; font-size: 0.72rem; letter-spacing: 0.06em; }
            .ipcr-table .col-code { width: 56px; white-space: nowrap; font-weight: 700; }
            .ipcr-table .col-actions { width: 96px; }
            .obj-desc { font-weight: 600; }
            .obj-timeline { display: block; margin-top: 6px; color: var(--ipcr-muted); font-size: 0.8rem; }

            .icon-btn {
                display: inline-flex; align-items: center; gap: 5px; border: none; border-radius: 9px;
                padding: 6px 10px; font-size: 0.78rem; font-weight: 700; cursor: pointer;
            }
            .icon-btn.edit { background: rgba(60,64,198,0.1); color: var(--ipcr-blue); }
            .icon-btn.edit:hover { background: rgba(60,64,198,0.18); }
            .icon-btn.del { background: rgba(207,73,100,0.12); color: #cf4964; }
            .icon-btn.del:hover { background: rgba(207,73,100,0.2); color: #cf4964; }
            .icon-btn.add { background: rgba(60,64,198,0.1); color: var(--ipcr-blue); }
            .icon-btn.tag { background: rgba(19, 143, 108, 0.12); color: #0f8b6c; }
            .icon-btn.tag:hover { background: rgba(19, 143, 108, 0.2); color: #0f8b6c; }

            .empty { text-align: center; padding: 30px; color: var(--ipcr-muted); }
            .empty i { font-size: 2rem; color: var(--ipcr-blue); display: block; margin-bottom: 8px; }

            /* Modals — anchored near the top of the viewport, not vertically centered. */
            .modal-dialog { margin-top: 30px; }
            @media (min-width: 768px) { .modal-dialog.modal-md { max-width: 600px; } }
            .modal-content { border: none; border-radius: 20px; overflow: hidden; box-shadow: 0 28px 70px rgba(15,23,42,0.18); }
            .modal-header-ipcr { padding: 20px 24px; color: #fff; background: linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%); }
            .modal-header-ipcr h5 { margin: 0; color: #fff; font-weight: 700; }
            .modal-header-ipcr .close { color: #fff; opacity: 0.9; text-shadow: none; }
            .modal-body-ipcr { padding: 22px 24px; background: #f8fbff; }
            .lbl { font-weight: 700; color: var(--ipcr-ink); margin-bottom: 6px; }
            .form-control { border-radius: 11px; border: 1px solid var(--ipcr-border); }
            .btn-primary-ipcr { border: none; border-radius: 12px; padding: 10px 18px; font-weight: 700; color: #fff; background: linear-gradient(135deg, #3c40c6 0%, #6f74ff 100%); }
            .btn-primary-ipcr:hover { color: #fff; }
            .btn-soft { border: none; border-radius: 12px; padding: 10px 16px; font-weight: 700; color: var(--ipcr-ink); background: #eef3f8; }

            .kra-tags { display: flex; flex-wrap: wrap; gap: 6px; padding: 10px 18px 0; }
            .member-chip { display: inline-flex; align-items: center; gap: 6px; padding: 5px 11px; border-radius: 999px; background: rgba(60,64,198,0.08); color: var(--ipcr-navy); font-size: 0.78rem; font-weight: 700; }
            .member-chip i { font-size: 0.9rem; color: var(--ipcr-blue); }
            .kra-tags .none { color: var(--ipcr-muted); font-size: 0.8rem; font-style: italic; }
            .select2-container { width: 100% !important; }
            .select2-container--default .select2-selection--multiple { border-radius: 11px; border: 1px solid var(--ipcr-border); min-height: 46px; }
            .select2-container--default .select2-results__option--highlighted[aria-selected] { background: var(--ipcr-blue); }
            .select2-dropdown { z-index: 1060; }
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
                                        <h1 class="ipcr-title">KRAs, Objectives &amp; Member Tags</h1>
                                        <p class="ipcr-copy">Add a Key Result Area, set the objectives under it, then tag the members who are accountable for it. Tagged members see the KRA on their “My IPCR” page.</p>
                                        <div class="hero-meta">
                                            <span class="hero-pill"><i class="mdi mdi-format-list-checks"></i> <?= count($kras); ?> KRA<?= count($kras) === 1 ? '' : 's'; ?></span>
                                            <span class="hero-pill"><i class="mdi mdi-target"></i> <?= $objectiveCount; ?> Objective<?= $objectiveCount === 1 ? '' : 's'; ?></span>
                                            <span class="hero-pill"><i class="mdi mdi-account-multiple-outline"></i> <?= $taggedCount; ?> Member<?= $taggedCount === 1 ? '' : 's'; ?> tagged</span>
                                        </div>
                                        <?php if (trim((string) $template['name']) !== '') : ?>
                                            <div class="hero-template">
                                                <i class="mdi mdi-file-document-outline"></i> Active template:
                                                <b><?= htmlspecialchars($template['name'], ENT_QUOTES, 'UTF-8'); ?></b><?php if (!empty($template['year'])) : ?> · <b><?= htmlspecialchars($template['year'], ENT_QUOTES, 'UTF-8'); ?></b><?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-lg-4 text-lg-right mt-4 mt-lg-0">
                                        <button type="button" class="btn-hero" data-toggle="modal" data-target="#kraModal" onclick="prepKra()"><i class="mdi mdi-plus"></i> Add KRA</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel">
                            <div class="panel-head">
                                <div>
                                    <h4>Key Result Areas</h4>
                                    <p><?= count($kras); ?> KRA<?= count($kras) === 1 ? '' : 's'; ?> defined.</p>
                                </div>
                            </div>
                            <div class="panel-body">
                                <?php if (empty($kras)) : ?>
                                    <div class="empty">
                                        <i class="mdi mdi-format-list-bulleted"></i>
                                        No KRAs yet. Start by adding a Key Result Area.
                                        <div class="mt-3">
                                            <button type="button" class="btn-primary-ipcr" data-toggle="modal" data-target="#kraModal" onclick="prepKra()"><i class="mdi mdi-plus"></i> Add KRA</button>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <?php $kraIndex = 0; ?>
                                    <?php foreach ($kras as $kra) : ?>
                                        <?php
                                        $kraMembers = isset($assignmentMap[(int) $kra['id']]) ? $assignmentMap[(int) $kra['id']] : array();
                                        $kraIndex++;
                                        $kraObjectives = isset($kra['objectives']) ? $kra['objectives'] : array();
                                        ?>
                                        <div class="kra-block">
                                            <div class="kra-header">
                                                <span class="kra-head-left">
                                                    <span class="kra-index"><?= $kraIndex; ?></span>
                                                    <span class="kra-title">
                                                        <?= htmlspecialchars($kra['title'], ENT_QUOTES, 'UTF-8'); ?>
                                                        <small><?= count($kraObjectives); ?> objective<?= count($kraObjectives) === 1 ? '' : 's'; ?> · <?= count($kraMembers); ?> tagged</small>
                                                    </span>
                                                </span>
                                                <span class="kra-actions">
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
                                                            <th>Objective</th>
                                                            <th class="col-actions text-center">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (empty($kra['objectives'])) : ?>
                                                            <tr><td colspan="3" class="text-center text-muted">No objectives under this KRA yet.</td></tr>
                                                        <?php else : ?>
                                                            <?php foreach ($kra['objectives'] as $objective) : ?>
                                                                <tr>
                                                                    <td class="col-code"><?= htmlspecialchars($objective['code'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                                    <td>
                                                                        <span class="obj-desc"><?= htmlspecialchars($objective['objective'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                                        <?php if (trim((string) $objective['timeline']) !== '') : ?>
                                                                            <span class="obj-timeline"><i class="mdi mdi-clock-outline"></i> <?= htmlspecialchars($objective['timeline'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                                        <?php endif; ?>
                                                                    </td>
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

                        <?php endif; /* template */ ?>

                    </div>
                </div>
                <?php include(APPPATH . 'views/includes/footer.php'); ?>
            </div>
        </div>

        <?php if ($template) : ?>
        <!-- KRA modal -->
        <div id="kraModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md">
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
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header-ipcr"><button type="button" class="close" data-dismiss="modal">&times;</button><h5 id="objectiveModalTitle">Add Objective</h5></div>
                    <form method="post" action="<?= base_url(); ?>Ipcrf/objective_save">
                        <div class="modal-body-ipcr">
                            <input type="hidden" name="kra_id" id="obj_kra_id" value="">
                            <input type="hidden" name="objective_id" id="obj_id" value="">
                            <div class="form-row">
                                <div class="form-group col-md-3"><label class="lbl">Code</label><input type="text" name="code" id="obj_code" class="form-control" required></div>
                                <div class="form-group col-md-9"><label class="lbl">Timeline</label><input type="text" name="timeline" id="obj_timeline" class="form-control" placeholder="e.g. January to December 2025"></div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12 mb-0"><label class="lbl">Objective</label><input type="text" name="objective" id="obj_objective" class="form-control" required></div>
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

        <!-- Tag Members modal -->
        <div id="tagModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
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
                                        <?= htmlspecialchars($mName . ' (' . $member['username'] . ')' . ($mSection !== '' ? ' — ' . $mSection : ''), ENT_QUOTES, 'UTF-8'); ?>
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
                document.getElementById('obj_timeline').value = obj ? (obj.timeline || '') : '';
                document.getElementById('objectiveModalTitle').textContent = obj ? 'Edit Objective' : 'Add Objective';
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
