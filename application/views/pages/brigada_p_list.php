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
    .hero-actions { display:flex; flex-wrap:wrap; justify-content:flex-end; gap:10px; }
    .hero-add-btn { display:inline-flex; align-items:center; justify-content:center; gap:10px; padding:13px 20px; margin:0!important; border:0; border-radius:16px; color:var(--memo-navy); background:linear-gradient(135deg,#fff 0%,#eef7ff 100%); font-weight:700; cursor:pointer; transition:transform .25s ease,box-shadow .25s ease; }
    .hero-add-btn:hover { color:var(--memo-navy); transform:translateY(-2px); box-shadow:0 14px 32px rgba(17,24,39,.12); text-decoration:none; }
    .partners-card { margin-top:24px; border:0; border-radius:22px; box-shadow:var(--memo-shadow); overflow:hidden; }
    .partners-card .card-body { padding:26px; }
    .partner-summary-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(190px,1fr)); gap:16px; margin-top:24px; }.partner-summary-card { padding:20px; border:1px solid #e8ecf5; border-radius:16px; background:#fff; box-shadow:0 10px 24px rgba(15,23,42,.05); cursor:pointer; transition:transform .2s ease,box-shadow .2s ease,border-color .2s ease; }.partner-summary-card:hover,.partner-summary-card:focus,.partner-summary-card.is-active { border-color:var(--memo-blue); box-shadow:0 14px 28px rgba(60,64,198,.14); outline:0; transform:translateY(-2px); }.partner-summary-label { display:block; color:#68708a; font-size:.76rem; font-weight:800; letter-spacing:.05em; text-transform:uppercase; }.partner-summary-count { display:block; margin-top:8px; color:var(--memo-navy); font-size:2rem; font-weight:800; line-height:1; }.partner-summary-caption { display:block; margin-top:7px; color:#8a92aa; font-size:.82rem; }
    .partners-card .table thead th { border-top:0; border-bottom:1px solid #e8ecf5; color:#68708a; font-size:.72rem; font-weight:800; letter-spacing:.05em; text-transform:uppercase; white-space:nowrap; }
    .partners-card .table td { border-color:#eef1f7; color:#343958; vertical-align:middle; }
    .partner-number { color:#8a92aa; font-weight:700; width:54px; }
    .partner-type { display:inline-flex; padding:5px 10px; border-radius:999px; background:rgba(60,64,198,.09); color:var(--memo-blue); font-size:.76rem; font-weight:700; }
    .action-btn { border-radius:10px; font-weight:700; }.partners-card .dropdown,.partners-card .dropup { position:relative; z-index:2; }.partners-card .dropdown.show,.partners-card .dropup.show { z-index:1055; }.partner-actions-menu { z-index:1060; min-width:180px; border:0; border-radius:12px; box-shadow:0 12px 28px rgba(15,23,42,.16); }.partner-actions-menu .dropdown-item { padding:9px 15px; font-size:.84rem; cursor:pointer; }
    .partner-add-toggle { position:fixed; width:1px; height:1px; opacity:0; pointer-events:none; }
    .partner-add-modal { display:none; }
    .partner-add-toggle:checked + .partner-add-modal { position:fixed; inset:0; z-index:1060; display:flex; align-items:center; justify-content:center; padding:24px; overflow-y:auto; background:rgba(15,23,42,.62); }
    .partner-add-dialog { width:min(760px,100%); max-height:calc(100vh - 48px); margin:auto; }
    .partner-add-form { display:flex; flex-direction:column; max-height:calc(100vh - 48px); overflow:hidden; border-radius:18px; background:#fff; box-shadow:0 28px 70px rgba(15,23,42,.3); }
    .partner-add-header,.partner-add-footer { display:flex; align-items:center; justify-content:space-between; gap:16px; padding:18px 24px; border:0; }
    .partner-add-header { border-bottom:1px solid #e8ecf5; }.partner-add-footer { justify-content:flex-end; border-top:1px solid #e8ecf5; }
    .partner-add-title { margin:0; color:var(--memo-ink); font-size:1.15rem; font-weight:700; }.partner-add-close { margin:0; color:#68708a; font-size:1.7rem; font-weight:400; line-height:1; cursor:pointer; }
    .partner-add-body { padding:24px; overflow-y:auto; }
    @media (max-width:767px) { .memo-hero-body,.partners-card .card-body { padding:22px; } .hero-actions { justify-content:flex-start; } .hero-add-btn { flex:1 1 190px; } }
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
                    <div class="col-md-4 mt-3 mt-md-0">
                        <div class="hero-actions">
                            <a href="<?= base_url(); ?>Brigada/all_donation_details" class="hero-add-btn"><i class="mdi mdi-format-list-bulleted"></i> View All Donations</a>
                            <label for="partnerAddToggle" class="hero-add-btn"><i class="mdi mdi-plus"></i> Add Partner</label>
                        </div>
                    </div>
                <?php endif; ?>
            </div></div></div></div></div>

            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show mt-4" role="alert"><?= $esc($this->session->flashdata('success')); ?><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('danger')): ?>
                <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert"><?= $esc($this->session->flashdata('danger')); ?><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>
            <?php endif; ?>

            <?php if (!empty($typeSummary)): ?><section class="partner-summary-grid" aria-label="Partners by organization type"><?php foreach ($typeSummary as $summary): ?><?php $rawType = trim((string) ($summary->general_type ?? '')); $typeName = $rawType !== '' ? $rawType : 'Unspecified'; ?><article class="partner-summary-card js-type-filter" data-partner-type="<?= $esc($rawType); ?>" role="button" tabindex="0"><span class="partner-summary-label"><?= $esc(str_replace('_', ' ', $typeName)); ?></span><strong class="partner-summary-count"><?= (int) ($summary->partner_count ?? 0); ?></strong><span class="partner-summary-caption">Partner<?= (int) ($summary->partner_count ?? 0) === 1 ? '' : 's'; ?></span></article><?php endforeach; ?></section><?php endif; ?>

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
                                        <td class="text-right"><div class="dropup"><button class="btn btn-sm btn-light action-btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button><div class="dropdown-menu dropdown-menu-right partner-actions-menu"><a href="<?= base_url(); ?>Brigada/partner_donation_details/<?= (int) $row->id; ?>" class="dropdown-item">View Donation Details</a><button type="button" class="dropdown-item js-edit-partner" data-toggle="modal" data-target="#partnerEditModal" data-id="<?= (int) $row->id; ?>" data-name="<?= $esc($row->name ?? ''); ?>" data-address="<?= $esc($row->address ?? ''); ?>" data-contact-person="<?= $esc($row->contact_person ?? ''); ?>" data-contact="<?= $esc($row->contact ?? ''); ?>" data-general-type="<?= $esc($row->general_type ?? ''); ?>" data-specific-type="<?= $esc($row->specific_type ?? ''); ?>">Edit Partner</button><a href="<?= base_url(); ?>Brigada/partners_delete/<?= (int) $row->id; ?>" class="dropdown-item text-danger" onclick="return confirm('Delete this partner?');">Delete Partner</a></div></div></td>
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
                <input class="partner-add-toggle" type="checkbox" id="partnerAddToggle" aria-hidden="true">
                <section class="partner-add-modal" id="partnerAddModal" role="dialog" aria-labelledby="partnerAddModalLabel">
                    <div class="partner-add-dialog">
                        <form class="partner-add-form" method="post" action="<?= base_url(); ?>Brigada/settings_partners" enctype="multipart/form-data">
                            <div class="partner-add-header">
                                <h5 class="partner-add-title" id="partnerAddModalLabel">Add Partner &amp; Portal Account</h5>
                                <label for="partnerAddToggle" class="partner-add-close" aria-label="Close"><span>&times;</span></label>
                            </div>
                            <div class="partner-add-body">
                                <div class="form-row"><div class="form-group col-md-7"><label for="partner-organization">Organization or Partner Name</label><input id="partner-organization" class="form-control" type="text" name="organization" required></div><div class="form-group col-md-5"><label for="partner-general-type">Organization Type</label><select id="partner-general-type" class="form-control" name="general_type"><option value="">Select Organization Type</option><option value="Private_Sector">Private Sector</option><option value="Public_Sector">Public Sector</option><option value="Civil_Society_Organizations">Civil Society Organizations</option><option value="International">International</option></select></div></div>
                                <div class="form-row"><div class="form-group col-md-6"><label for="partner-first-name">Contact Person First Name</label><input id="partner-first-name" class="form-control" type="text" name="first_name" required></div><div class="form-group col-md-6"><label for="partner-last-name">Last Name</label><input id="partner-last-name" class="form-control" type="text" name="last_name" required></div></div>
                                <div class="form-row"><div class="form-group col-md-6"><label for="partner-email">Email Address</label><input id="partner-email" class="form-control" type="email" name="email" autocomplete="email" required><small id="partner-email-feedback" class="form-text"></small></div><div class="form-group col-md-6"><label for="partner-phone">Contact Number</label><input id="partner-phone" class="form-control" type="tel" name="phone"></div></div>
                                <div class="form-row"><div class="form-group col-md-7"><label for="partner-address">Address</label><input id="partner-address" class="form-control" type="text" name="address"></div><div class="form-group col-md-5"><label for="partner-specific-type">Specific Type</label><select id="partner-specific-type" class="form-control" name="specific_type"><option value="">Select Specific Type</option><option value="Government">Government</option><option value="INGO-International Non-Government Organization">INGO-International Non-Government Organization</option><option value="Individual">Individual</option><option value="Others">Others</option></select></div></div>
                                <div class="form-row"><div class="form-group col-md-6"><label for="partner-password">Password</label><div class="input-group"><input id="partner-password" class="form-control" type="password" name="password" minlength="8" required><div class="input-group-append"><button class="btn btn-outline-secondary" type="button" id="generatePartnerPassword">Generate</button><button class="btn btn-outline-secondary" type="button" id="togglePartnerPassword" aria-label="Show password" aria-pressed="false"><i class="mdi mdi-eye-outline"></i></button></div></div><small class="form-text text-muted">Generate a strong temporary password.</small></div><div class="form-group col-md-6"><label for="partner-password-confirm">Confirm Password</label><input id="partner-password-confirm" class="form-control" type="password" name="password_confirm" minlength="8" required></div></div>
                                <div class="form-group mb-0">
                                    <label for="partner-file">Logo <span class="text-muted">(optional)</span></label>
                                    <input id="partner-file" type="file" class="form-control-file" name="file" accept=".jpg,.jpeg,.png">
                                </div>
                            </div>
                            <div class="partner-add-footer">
                                <label for="partnerAddToggle" class="btn btn-light mb-0">Cancel</label>
                                <button type="submit" class="btn btn-primary">Save Partner</button>
                            </div>
                        </form>
                    </div>
                </section>

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
        var partnersDataTable;
        var partnerEmail = document.getElementById('partner-email');
        var partnerEmailFeedback = document.getElementById('partner-email-feedback');
        var emailCheckTimer;
        function setEmailFeedback(message, available) {
            if (!partnerEmail || !partnerEmailFeedback) { return; }
            partnerEmail.classList.remove('is-valid', 'is-invalid');
            partnerEmailFeedback.classList.remove('text-success', 'text-danger');
            if (message) {
                partnerEmail.classList.add(available ? 'is-valid' : 'is-invalid');
                partnerEmailFeedback.classList.add(available ? 'text-success' : 'text-danger');
            }
            partnerEmailFeedback.textContent = message;
        }
        function checkPartnerEmail() {
            var email = partnerEmail.value.trim();
            if (!email) { setEmailFeedback('', false); return; }
            if (!partnerEmail.validity.valid) { setEmailFeedback('Enter a valid email address.', false); return; }
            fetch('<?= site_url('Brigada/partner_email_available'); ?>?email=' + encodeURIComponent(email), { credentials: 'same-origin' })
                .then(function (response) { return response.json(); })
                .then(function (data) { setEmailFeedback(data.available ? 'Email address is available.' : 'This email address already has an account.', data.available); })
                .catch(function () { setEmailFeedback('Unable to check this email right now.', false); });
        }
        if (partnerEmail) {
            partnerEmail.addEventListener('input', function () {
                clearTimeout(emailCheckTimer);
                emailCheckTimer = setTimeout(checkPartnerEmail, 350);
            });
        }

        var generatePasswordButton = document.getElementById('generatePartnerPassword');
        var togglePasswordButton = document.getElementById('togglePartnerPassword');
        var partnerPassword = document.getElementById('partner-password');
        var partnerPasswordConfirm = document.getElementById('partner-password-confirm');
        if (togglePasswordButton && partnerPassword) {
            togglePasswordButton.addEventListener('click', function () {
                var showPassword = partnerPassword.type === 'password';
                partnerPassword.type = showPassword ? 'text' : 'password';
                if (partnerPasswordConfirm) { partnerPasswordConfirm.type = showPassword ? 'text' : 'password'; }
                togglePasswordButton.setAttribute('aria-label', showPassword ? 'Hide password' : 'Show password');
                togglePasswordButton.setAttribute('aria-pressed', showPassword ? 'true' : 'false');
                togglePasswordButton.querySelector('i').className = showPassword ? 'mdi mdi-eye-off-outline' : 'mdi mdi-eye-outline';
            });
        }
        if (generatePasswordButton && partnerPassword && partnerPasswordConfirm) {
            generatePasswordButton.addEventListener('click', function () {
                var characters = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%*_-';
                var values = new Uint32Array(14);
                var password = '';
                if (window.crypto && window.crypto.getRandomValues) {
                    window.crypto.getRandomValues(values);
                    values.forEach(function (value) { password += characters.charAt(value % characters.length); });
                } else {
                    for (var index = 0; index < 14; index++) { password += characters.charAt(Math.floor(Math.random() * characters.length)); }
                }
                partnerPassword.value = password;
                partnerPasswordConfirm.value = password;
                partnerPassword.focus();
            });
        }

        if (window.jQuery && jQuery.fn.DataTable) {
            partnersDataTable = jQuery('#partnersTable').DataTable({
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

        document.querySelectorAll('.js-type-filter').forEach(function (card) {
            function filterByType() {
                if (!partnersDataTable) { return; }
                var type = card.dataset.partnerType || '';
                var isActive = card.classList.contains('is-active');
                document.querySelectorAll('.js-type-filter').forEach(function (item) { item.classList.remove('is-active'); });
                if (isActive) {
                    partnersDataTable.column(5).search('').draw();
                    return;
                }
                card.classList.add('is-active');
                partnersDataTable.column(5).search('^' + jQuery.fn.dataTable.util.escapeRegex(type) + '$', true, false).draw();
            }
            card.addEventListener('click', filterByType);
            card.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); filterByType(); }
            });
        });

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
