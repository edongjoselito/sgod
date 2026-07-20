<?php
$positions = is_array($data) ? $data : array();
$totalPositions = count($positions);

$metrics = array(
    array(
        'value' => $totalPositions,
        'label' => 'Total Positions',
        'icon' => 'mdi-badge-account-horizontal-outline',
        'accent' => '#3c40c6',
        'icon_bg' => 'rgba(60, 64, 198, 0.12)',
        'icon_color' => '#3c40c6',
        'badge' => 'Catalog'
    )
);
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <?php include('includes/page-title.php'); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Responsive bootstrap 4 admin template" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
        <link href="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />
        <link href="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/libs/select2/select2.min.css" rel="stylesheet" type="text/css" />

        <style>
            :root {
                --users-navy: #272b8c;
                --users-blue: #3c40c6;
                --users-teal: #565de8;
                --users-gold: #7a80ff;
                --users-ink: #23275d;
                --users-muted: #7b7fa7;
                --users-border: rgba(60, 64, 198, 0.12);
                --users-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            }

            body {
                background:
                    radial-gradient(circle at top left, rgba(60, 64, 198, 0.10), transparent 24%),
                    linear-gradient(180deg, #f4f8fc 0%, #eef4fa 100%);
            }

            .content-page { background: transparent; }

            .users-shell { position: relative; padding-bottom: 28px; }

            .users-shell::before {
                content: "";
                position: absolute;
                inset: 24px 0 auto;
                height: 240px;
                border-radius: 30px;
                background: linear-gradient(135deg, rgba(60, 64, 198, 0.11), rgba(122, 128, 255, 0.10));
                z-index: 0;
            }

            .users-shell > * { position: relative; z-index: 1; }

            .users-hero {
                margin-top: 20px;
                border-radius: 28px;
                overflow: hidden;
                color: #ffffff;
                box-shadow: var(--users-shadow);
                background:
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 32%),
                    linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
            }

            .users-hero-body { padding: 32px; }

            .users-eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 14px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.12);
                border: 1px solid rgba(255, 255, 255, 0.18);
                font-size: 0.8rem;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .users-title {
                margin: 18px 0 12px;
                color: #ffffff;
                font-size: clamp(2rem, 3vw, 2.7rem);
                line-height: 1.05;
                font-weight: 700;
                letter-spacing: -0.03em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .users-copy {
                max-width: 620px;
                margin-bottom: 0;
                color: rgba(255, 255, 255, 0.82);
                line-height: 1.7;
            }

            .hero-side { height: 100%; display: flex; align-items: stretch; }

            .hero-side-card {
                width: 100%;
                padding: 24px;
                display: flex;
                align-items: center;
                border-radius: 22px;
                background: rgba(255, 255, 255, 0.12);
                border: 1px solid rgba(255, 255, 255, 0.18);
                backdrop-filter: blur(14px);
            }

            .hero-add-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                width: 100%;
                padding: 13px 18px;
                border: none;
                border-radius: 16px;
                color: var(--users-navy);
                background: linear-gradient(135deg, #ffffff 0%, #eef7ff 100%);
                font-weight: 700;
                transition: transform 0.25s ease, box-shadow 0.25s ease;
            }

            .hero-add-btn:hover {
                color: var(--users-navy);
                transform: translateY(-2px);
                box-shadow: 0 14px 32px rgba(17, 24, 39, 0.12);
            }

            .metric-card {
                height: 100%;
                border: none;
                border-radius: 22px;
                background: rgba(255, 255, 255, 0.95);
                box-shadow: var(--users-shadow);
                overflow: hidden;
                position: relative;
                animation: fade-up 0.65s ease both;
            }

            .metric-card::before {
                content: "";
                position: absolute;
                inset: 0 0 auto;
                height: 4px;
                background: var(--accent);
            }

            .metric-card-body { padding: 24px; }

            .metric-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 16px;
            }

            .metric-icon {
                width: 54px;
                height: 54px;
                border-radius: 16px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 1.4rem;
            }

            .metric-badge {
                display: inline-flex;
                align-items: center;
                padding: 7px 12px;
                border-radius: 999px;
                background: #f4f8fc;
                color: var(--users-muted);
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.05em;
                text-transform: uppercase;
            }

            .metric-value {
                margin-bottom: 8px;
                color: var(--users-ink);
                font-size: 2.2rem;
                line-height: 1;
                font-weight: 700;
                letter-spacing: -0.04em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .metric-label { color: var(--users-ink); font-size: 1rem; font-weight: 700; margin-bottom: 8px; }

            .users-shell .alert {
                border: none;
                border-radius: 18px;
                box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
            }

            .table-panel {
                border: 1px solid var(--users-border);
                border-radius: 24px;
                background: rgba(255, 255, 255, 0.96);
                box-shadow: var(--users-shadow);
                overflow: hidden;
            }

            .table-panel-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                padding: 24px 26px 0;
            }

            .table-panel-title {
                margin-bottom: 6px;
                color: var(--users-ink);
                font-size: 1.35rem;
                font-weight: 700;
                letter-spacing: -0.02em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .table-panel-copy { margin-bottom: 0; color: var(--users-muted); line-height: 1.6; }

            .table-shell { padding: 20px 26px 26px; }

            .dataTables_wrapper .dataTables_filter input,
            .dataTables_wrapper .dataTables_length select {
                border-radius: 12px;
                border: 1px solid rgba(60, 64, 198, 0.14);
                min-height: 42px;
                padding: 8px 12px;
                box-shadow: none;
            }

            .users-table {
                margin-bottom: 0 !important;
                border-collapse: separate !important;
                border-spacing: 0 12px !important;
            }

            .users-table thead th {
                border: none !important;
                color: var(--users-muted) !important;
                font-size: 0.78rem !important;
                font-weight: 700 !important;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                padding: 0 18px 8px !important;
                background: transparent !important;
            }

            .users-table tbody tr {
                background: linear-gradient(180deg, #ffffff 0%, #f9fbfd 100%);
                box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
            }

            .users-table tbody td {
                border: none !important;
                vertical-align: middle !important;
                padding: 18px !important;
                color: var(--users-ink);
                background: transparent !important;
            }

            .users-table tbody td:first-child { border-radius: 18px 0 0 18px; }
            .users-table tbody td:last-child { border-radius: 0 18px 18px 0; }

            .position-name { font-weight: 700; letter-spacing: -0.01em; color: var(--users-ink); }

            .status-pill {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 14px;
                border-radius: 999px;
                font-size: 0.83rem;
                font-weight: 700;
            }

            .status-active { background: rgba(60, 64, 198, 0.12); color: #3136a5; }
            .status-inactive { background: rgba(122, 128, 255, 0.18); color: #4e54d4; }

            .row-actions { display: inline-flex; gap: 10px; justify-content: center; flex-wrap: wrap; }

            .btn-action {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                border: none;
                border-radius: 12px;
                padding: 9px 14px;
                font-size: 0.82rem;
                font-weight: 700;
                cursor: pointer;
            }

            .btn-action-edit { background: rgba(60, 64, 198, 0.10); color: var(--users-blue); }
            .btn-action-edit:hover { background: rgba(60, 64, 198, 0.18); color: var(--users-blue); }
            .btn-action-delete { background: rgba(207, 73, 100, 0.12); color: #cf4964; }
            .btn-action-delete:hover { background: rgba(207, 73, 100, 0.20); color: #cf4964; }

            .empty-state { text-align: center; padding: 38px 24px; }
            .empty-state i { font-size: 2.2rem; color: var(--users-blue); margin-bottom: 12px; }
            .empty-state h5 { color: var(--users-ink); font-weight: 700; margin-bottom: 8px; }
            .empty-state p { color: var(--users-muted); margin-bottom: 0; }

            .modal-content {
                border: none;
                border-radius: 26px;
                overflow: hidden;
                box-shadow: 0 28px 70px rgba(15, 23, 42, 0.18);
            }

            .modern-modal-header {
                padding: 24px 28px;
                color: #ffffff;
                background: linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
            }

            .modern-modal-header .close { color: #ffffff; opacity: 0.9; text-shadow: none; }

            .modern-modal-title {
                margin-bottom: 6px;
                color: #ffffff;
                font-size: 1.35rem;
                font-weight: 700;
                letter-spacing: -0.02em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .modern-modal-copy { margin-bottom: 0; color: rgba(255, 255, 255, 0.80); }

            .modern-modal-body { padding: 28px; background: #f8fbff; }

            .form-card {
                border: 1px solid rgba(60, 64, 198, 0.08);
                border-radius: 22px;
                background: #ffffff;
                padding: 24px;
            }

            .modern-label { color: var(--users-ink); font-weight: 700; margin-bottom: 8px; }

            .modern-input {
                min-height: 48px;
                border-radius: 14px;
                border: 1px solid rgba(60, 64, 198, 0.14);
                padding: 12px 14px;
                box-shadow: none;
            }

            .modern-input:focus {
                border-color: var(--users-blue);
                box-shadow: 0 0 0 0.18rem rgba(60, 64, 198, 0.14);
            }

            .input-note { display: block; margin-top: 8px; color: var(--users-muted); font-size: 0.84rem; }

            .modal-footer-modern {
                display: flex;
                justify-content: flex-end;
                gap: 12px;
                padding-top: 16px;
                flex-wrap: wrap;
            }

            .btn-soft-dark {
                color: var(--users-ink);
                background: #eef3f8;
                border: none;
                border-radius: 14px;
                padding: 11px 16px;
                font-weight: 700;
            }

            .btn-gradient-primary {
                color: #ffffff;
                border: none;
                border-radius: 14px;
                padding: 11px 18px;
                font-weight: 700;
                background: linear-gradient(135deg, #3c40c6 0%, #6f74ff 100%);
                box-shadow: 0 14px 30px rgba(60, 64, 198, 0.18);
            }

            .btn-gradient-primary:hover { color: #ffffff; }

            /* Select2 look to match modern inputs */
            .select2-container .select2-selection--single {
                height: 48px;
                border-radius: 14px;
                border: 1px solid rgba(60, 64, 198, 0.14);
                display: flex;
                align-items: center;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 46px;
                padding-left: 14px;
                color: var(--users-ink);
                font-weight: 600;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow { height: 46px; }

            .select2-dropdown {
                border-radius: 14px;
                border: 1px solid rgba(60, 64, 198, 0.14);
                overflow: hidden;
                box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
            }

            .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background: var(--users-blue);
            }

            @keyframes fade-up {
                from { opacity: 0; transform: translateY(18px); }
                to { opacity: 1; transform: translateY(0); }
            }

            @media (max-width: 1199.98px) { .hero-side { margin-top: 24px; } }

            @media (max-width: 991.98px) {
                .users-hero-body, .table-shell, .modern-modal-body { padding: 22px; }
                .table-panel-head { padding: 22px 22px 0; }
            }

            @media (max-width: 767.98px) {
                .users-title { font-size: 2rem; }
                .table-panel-head { flex-direction: column; align-items: flex-start; }
                .modal-footer-modern { flex-direction: column-reverse; }
                .modal-footer-modern .btn,
                .modal-footer-modern input[type="submit"] { width: 100%; }
            }
        </style>
    </head>

    <body>
        <div id="wrapper">

            <?php include('includes/top-bar.php'); ?>
            <?php include('includes/sidebar.php'); ?>

            <div class="content-page">
                <div class="content">
                    <!-- <div class="container-fluid users-shell"> -->

                        <div class="users-hero">
                            <div class="users-hero-body">
                                <div class="row align-items-center">
                                    <div class="col-xl-8">
                                        <span class="users-eyebrow">
                                            <i class="mdi mdi-badge-account-horizontal-outline"></i>
                                            Position Management
                                        </span>
                                        <h1 class="users-title">Positions</h1>
                                        <p class="users-copy">
                                            Maintain the list of positions used when assigning users. Mark a position as
                                            <b>Admin</b> to grant those accounts administrative access.
                                        </p>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="hero-side">
                                            <div class="hero-side-card">
                                                <button type="button" class="hero-add-btn" data-toggle="modal" data-target="#addPositionModal">
                                                    <i class="mdi mdi-plus-circle-outline"></i>
                                                    Add New Position
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($this->session->flashdata('success')) : ?>
                            <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <?= $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($this->session->flashdata('danger')) : ?>
                            <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <?= $this->session->flashdata('danger'); ?>
                            </div>
                        <?php endif; ?>

                        <!-- <div class="row mt-4">
                            <?php foreach ($metrics as $index => $metric) : ?>
                                <div class="col-md-6 col-xl-4 mb-4">
                                    <div class="metric-card" style="--accent: <?= $metric['accent']; ?>; animation-delay: <?= number_format(($index + 1) * 0.08, 2, '.', ''); ?>s;">
                                        <div class="metric-card-body">
                                            <div class="metric-head">
                                                <span class="metric-icon" style="background: <?= $metric['icon_bg']; ?>; color: <?= $metric['icon_color']; ?>;">
                                                    <i class="mdi <?= $metric['icon']; ?>"></i>
                                                </span>
                                                <span class="metric-badge"><?= htmlspecialchars($metric['badge'], ENT_QUOTES, 'UTF-8'); ?></span>
                                            </div>
                                            <div class="metric-value"><?= htmlspecialchars((string) $metric['value'], ENT_QUOTES, 'UTF-8'); ?></div>
                                            <div class="metric-label"><?= htmlspecialchars($metric['label'], ENT_QUOTES, 'UTF-8'); ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div> -->
<br>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-panel">
                                    <div class="table-panel-head">
                                        <div>
                                            <h4 class="table-panel-title">Position Directory</h4>
                                            <p class="table-panel-copy">
                                                These positions appear in the Position dropdown when creating or editing a user.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="table-shell">
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-borderless dt-responsive nowrap users-table w-100">
                                                <thead>
                                                    <tr>
                                                        <th>Position Name</th>
                                                        <th class="text-center">Manage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($positions)) : ?>
                                                        <?php foreach ($positions as $index => $row) : ?>
                                                            <?php
                                                            $positionId = (string) $row->id;
                                                            $positionName = (string) $row->positionName;
                                                            $deleteUrl = base_url() . 'Page/delete_position?id=' . rawurlencode($positionId);
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <div class="position-name">
                                                                        <i class="mdi mdi-badge-account-horizontal-outline mr-1"></i>
                                                                        <?= htmlspecialchars($positionName, ENT_QUOTES, 'UTF-8'); ?>
                                                                    </div>
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="row-actions">
                                                                        <button type="button" class="btn-action btn-action-edit" data-toggle="modal" data-target="#editPositionModal<?= $index; ?>">
                                                                            <i class="mdi mdi-pencil-outline"></i> Edit
                                                                        </button>
                                                                        <a href="<?= htmlspecialchars($deleteUrl, ENT_QUOTES, 'UTF-8'); ?>"
                                                                           class="btn-action btn-action-delete js-delete-position"
                                                                           data-name="<?= htmlspecialchars($positionName, ENT_QUOTES, 'UTF-8'); ?>">
                                                                            <i class="mdi mdi-trash-can-outline"></i> Delete
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else : ?>
                                                        <tr>
                                                            <td colspan="2">
                                                                <div class="empty-state">
                                                                    <i class="mdi mdi-badge-account-horizontal-outline"></i>
                                                                    <h5>No positions yet</h5>
                                                                    <p>Add your first position to start assigning it to users.</p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Position Modal -->
                <div id="addPositionModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modern-modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h5 class="modern-modal-title">Add New Position</h5>
                                <p class="modern-modal-copy">Create a position and choose whether it grants admin access.</p>
                            </div>
                            <div class="modern-modal-body">
                                <form method="post" action="<?= base_url(); ?>Page/positions">
                                    <div class="form-card">
                                        <div class="form-group mb-0">
                                            <label class="modern-label">Position Name</label>
                                            <input type="text" name="positionName" class="form-control modern-input" placeholder="e.g. Section Head" required />
                                        </div>
                                    </div>

                                    <div class="modal-footer-modern">
                                        <button type="button" class="btn btn-soft-dark" data-dismiss="modal">Cancel</button>
                                        <input type="submit" name="submit" class="btn btn-gradient-primary" value="Save Position" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Position Modals -->
                <?php if (!empty($positions)) : ?>
                    <?php foreach ($positions as $index => $row) : ?>
                        <?php
                        $positionId = (string) $row->id;
                        $positionName = (string) $row->positionName;
                        $rowIsAdmin = (isset($row->isAdmin) && $row->isAdmin === 'Yes') ? 'Yes' : 'No';
                        ?>
                        <div id="editPositionModal<?= $index; ?>" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modern-modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h5 class="modern-modal-title">Edit Position</h5>
                                        <p class="modern-modal-copy">Update the position name or its admin access.</p>
                                    </div>
                                    <div class="modern-modal-body">
                                        <form method="post" action="<?= base_url(); ?>Page/update_position">
                                            <div class="form-card">
                                                <div class="form-row">
                                                    <div class="form-group col-md-8 mb-0">
                                                        <label class="modern-label">Position Name</label>
                                                        <input type="text" name="positionName" class="form-control modern-input" value="<?= htmlspecialchars($positionName, ENT_QUOTES, 'UTF-8'); ?>" required />
                                                    </div>
                                                    <div class="form-group col-md-4 mb-0">
                                                        <label class="modern-label">Admin Access</label>
                                                        <select class="form-control modern-input" name="isAdmin">
                                                            <option value="No" <?= $rowIsAdmin === 'No' ? 'selected' : ''; ?>>No</option>
                                                            <option value="Yes" <?= $rowIsAdmin === 'Yes' ? 'selected' : ''; ?>>Yes</option>
                                                        </select>
                                                        <small class="input-note">Choose <b>Yes</b> to mark accounts with this position as Admin.</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="hidden" name="id" value="<?= htmlspecialchars($positionId, ENT_QUOTES, 'UTF-8'); ?>" />

                                            <div class="modal-footer-modern">
                                                <button type="button" class="btn btn-soft-dark" data-dismiss="modal">Cancel</button>
                                                <input type="submit" name="submit" class="btn btn-gradient-primary" value="Update Position" />
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php include('includes/footer.php'); ?>
            </div>
        </div>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.responsive.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/select2/select2.min.js"></script>

        <script type="text/javascript">
            $(function () {
                $('#datatable').DataTable({
                    destroy: true,
                    autoWidth: false,
                    pageLength: 10,
                    order: [],
                    responsive: true,
                    columnDefs: [{ orderable: false, targets: 2 }],
                    language: {
                        search: "Search:",
                        lengthMenu: "Show _MENU_ positions",
                        info: "Showing _START_ to _END_ of _TOTAL_ positions",
                        paginate: { previous: "&lsaquo;", next: "&rsaquo;" }
                    }
                });

                $(document).on('click', '.js-delete-position', function (e) {
                    e.preventDefault();
                    var url = $(this).attr('href');
                    var name = $(this).data('name');
                    if (window.confirm('Are you sure you want to delete the position "' + name + '"?')) {
                        window.location.href = url;
                    }
                });
            });
        </script>
    </body>
</html>
