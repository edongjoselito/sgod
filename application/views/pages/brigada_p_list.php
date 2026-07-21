<?php
$esc = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <?php include(__DIR__ . '/../includes/page-title.php'); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/dashboard-unified.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <style>
    :root { --memo-navy: #272b8c; --memo-blue: #3c40c6; --memo-ink: #23275d; --memo-shadow: 0 24px 60px rgba(15,23,42,.08); }
    body { background: radial-gradient(circle at top left, rgba(60,64,198,.10), transparent 24%), linear-gradient(180deg,#f4f8fc 0%,#eef4fa 100%); }
    .content-page { background: transparent; }
    .memo-shell { position:relative; padding-bottom:28px; }
    .memo-shell::before { content:""; position:absolute; inset:24px 0 auto; height:240px; border-radius:30px; background:linear-gradient(135deg,rgba(60,64,198,.11),rgba(122,128,255,.10)); z-index:0; }
    .memo-shell > * { position:relative; z-index:1; }
    .memo-hero { margin-top:0; border-radius:28px; overflow:hidden; color:#fff; box-shadow:var(--memo-shadow); background:radial-gradient(circle at top right,rgba(255,255,255,.16),transparent 32%),linear-gradient(135deg,#272b8c 0%,#3c40c6 58%,#6f74ff 100%); }
    .memo-hero-body { padding:32px; }
    .memo-eyebrow { display:inline-flex; align-items:center; gap:8px; padding:8px 14px; border-radius:999px; background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.18); font-size:.8rem; letter-spacing:.08em; text-transform:uppercase; }
    .memo-title { margin:18px 0 12px; color:#fff; font-size:clamp(2rem,3vw,2.7rem); line-height:1.05; font-weight:700; letter-spacing:-.03em; }
    .memo-subtitle { margin:0; color:rgba(255,255,255,.82); font-size:1rem; }
    .hero-add-btn { display:inline-flex; align-items:center; justify-content:center; gap:10px; padding:13px 20px; border:0; border-radius:16px; color:var(--memo-navy); background:linear-gradient(135deg,#fff 0%,#eef7ff 100%); font-weight:700; transition:transform .25s ease,box-shadow .25s ease; }
    .hero-add-btn:hover { color:var(--memo-navy); transform:translateY(-2px); box-shadow:0 14px 32px rgba(17,24,39,.12); text-decoration:none; }
    .partners-card { margin-top:24px; border:0; border-radius:22px; box-shadow:var(--memo-shadow); overflow:hidden; }
    .partners-card .card-body { padding:26px; }
    .partners-card .table thead th { border-top:0; border-bottom:1px solid #e8ecf5; color:#68708a; font-size:.72rem; font-weight:800; letter-spacing:.05em; text-transform:uppercase; white-space:nowrap; }
    .partners-card .table td { border-color:#eef1f7; color:#343958; vertical-align:middle; }
    .partner-number { color:#8a92aa; font-weight:700; width:54px; }
    .partner-type { display:inline-flex; padding:5px 10px; border-radius:999px; background:rgba(60,64,198,.09); color:var(--memo-blue); font-size:.76rem; font-weight:700; }
    .action-btn { border-radius:10px; font-weight:700; }
    @media (max-width:767px) { .memo-hero-body,.partners-card .card-body { padding:22px; } }
</style>
    </head>
    <body class="dashboard-root-theme">
        <div id="wrapper">
            <?php include(__DIR__ . '/../includes/top-bar.php'); ?>
            <?php include(__DIR__ . '/../includes/sidebar.php'); ?>

<div class="content-page">
    <div class="content">
        <main class="container-fluid dashboard-shell memo-shell">
            <div class="row"><div class="col-12"><div class="memo-hero"><div class="memo-hero-body"><div class="row align-items-center">
                <div class="col-md-8">
                    <span class="memo-eyebrow"><i class="mdi mdi-handshake-outline"></i> Brigada Eskwela</span>
                    <h1 class="memo-title"><?= $esc($title ?? 'Partners'); ?></h1>
                    <p class="memo-subtitle">Manage the organizations and individuals supporting school initiatives.</p>
                </div>
                <?php if (!empty($can_manage_partners)): ?>
                    <div class="col-md-4 text-md-right mt-3 mt-md-0">
                        <button type="button" class="hero-add-btn" data-toggle="modal" data-target="#partnerAddModal"><i class="mdi mdi-plus"></i> Add Partner</button>
                    </div>
                <?php endif; ?>
            </div></div></div></div></div>

            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show mt-4" role="alert"><?= $esc($this->session->flashdata('success')); ?><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('danger')): ?>
                <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert"><?= $esc($this->session->flashdata('danger')); ?><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>
            <?php endif; ?>

            <div class="card partners-card"><div class="card-body">
                <div class="table-responsive">
                    <table id="partnersTable" class="table table-hover mb-0">
                        <thead><tr>
                            <th>#</th><th>Name</th><th>Address</th><th>Contact Person</th><th>Contact</th><th>Type</th><th>Specific Type</th>
                            <?php if (!empty($can_manage_partners)): ?><th class="text-right">Actions</th><?php endif; ?>
                        </tr></thead>
                        <tbody>
                            <?php $index = 1; ?>
                            <?php foreach ((array) $data as $row): ?>
                                <tr>
                                    <td class="partner-number"><?= $index++; ?></td>
                                    <td><?= $esc($row->name ?? ''); ?></td>
                                    <td><?= $esc($row->address ?? ''); ?></td>
                                    <td><?= $esc($row->contact_person ?? ''); ?></td>
                                    <td><?= $esc($row->contact ?? ''); ?></td>
                                    <td><span class="partner-type"><?= $esc($row->general_type ?? ''); ?></span></td>
                                    <td><?= $esc($row->specific_type ?? ''); ?></td>
                                    <?php if (!empty($can_manage_partners)): ?>
                                        <td class="text-right">
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-light action-btn js-edit-partner"
                                                data-toggle="modal"
                                                data-target="#partnerEditModal"
                                                data-id="<?= (int) $row->id; ?>"
                                                data-name="<?= $esc($row->name ?? ''); ?>"
                                                data-address="<?= $esc($row->address ?? ''); ?>"
                                                data-contact-person="<?= $esc($row->contact_person ?? ''); ?>"
                                                data-contact="<?= $esc($row->contact ?? ''); ?>"
                                                data-general-type="<?= $esc($row->general_type ?? ''); ?>"
                                                data-specific-type="<?= $esc($row->specific_type ?? ''); ?>"
                                            >Edit</button>
                                            <a href="<?= base_url(); ?>Brigada/partners_delete/<?= (int) $row->id; ?>" class="btn btn-sm btn-danger action-btn" onclick="return confirm('Delete this partner?');">Delete</a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($data)): ?>
                                <tr><td colspan="<?= !empty($can_manage_partners) ? 8 : 7; ?>" class="text-center text-muted py-4">No partners found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div></div>

            <?php if (!empty($can_manage_partners)): ?>
                <div class="modal fade" id="partnerAddModal" tabindex="-1" role="dialog" aria-labelledby="partnerAddModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <form class="modal-content" method="post" action="<?= base_url(); ?>Brigada/settings_partners" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h5 class="modal-title" id="partnerAddModalLabel">Add Partner</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <?php include(__DIR__ . '/partials/brigada_partner_fields.php'); ?>
                                <div class="form-group mb-0">
                                    <label for="partner-file">Logo <span class="text-muted">(optional)</span></label>
                                    <input id="partner-file" type="file" class="form-control-file" name="file" accept=".jpg,.jpeg,.png">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Partner</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="modal fade" id="partnerEditModal" tabindex="-1" role="dialog" aria-labelledby="partnerEditModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <form class="modal-content" method="post" action="<?= base_url(); ?>Brigada/partners_update">
                            <div class="modal-header">
                                <h5 class="modal-title" id="partnerEditModalLabel">Edit Partner</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" id="edit-partner-id">
                                <?php include(__DIR__ . '/partials/brigada_partner_fields.php'); ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
    <?php include(__DIR__ . '/../includes/footer.php'); ?>
</div>
</div>

<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.jQuery && jQuery.fn.DataTable) {
            jQuery('#partnersTable').DataTable({
                order: [[1, 'asc']],
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
                scrollX: true,
                language: {
                    search: 'Search partners:',
                    lengthMenu: 'Show _MENU_ partners',
                    emptyTable: 'No partners found.'
                },
                columnDefs: [
                    { orderable: false, targets: <?= !empty($can_manage_partners) ? '[7]' : '[]'; ?> }
                ]
            });
        }

        document.querySelectorAll('.js-edit-partner').forEach(function (button) {
            button.addEventListener('click', function () {
                var modal = document.getElementById('partnerEditModal');
                if (!modal) return;

                modal.querySelector('#edit-partner-id').value = button.dataset.id || '';
                modal.querySelector('[name="name"]').value = button.dataset.name || '';
                modal.querySelector('[name="address"]').value = button.dataset.address || '';
                modal.querySelector('[name="contact_person"]').value = button.dataset.contactPerson || '';
                modal.querySelector('[name="contact"]').value = button.dataset.contact || '';
                modal.querySelector('[name="general_type"]').value = button.dataset.generalType || '';
                modal.querySelector('[name="specific_type"]').value = button.dataset.specificType || '';
            });
        });
    });
</script>
</body>
</html>
