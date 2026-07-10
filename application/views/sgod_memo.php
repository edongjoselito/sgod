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
        <link href="<?= base_url(); ?>assets/libs/custombox/custombox.min.css" rel="stylesheet" type="text/css">
        <link href="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/libs/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/libs/datatables/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <style>
            :root {
                --memo-navy: #272b8c;
                --memo-blue: #3c40c6;
                --memo-teal: #565de8;
                --memo-gold: #7a80ff;
                --memo-rose: #cf4964;
                --memo-ink: #23275d;
                --memo-muted: #7b7fa7;
                --memo-border: rgba(60, 64, 198, 0.12);
                --memo-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            }

            body {
                background:
                    radial-gradient(circle at top left, rgba(60, 64, 198, 0.10), transparent 24%),
                    linear-gradient(180deg, #f4f8fc 0%, #eef4fa 100%);
            }

            .content-page {
                background: transparent;
            }

            .memo-shell {
                position: relative;
                padding-bottom: 28px;
            }

            .memo-shell::before {
                content: "";
                position: absolute;
                inset: 24px 0 auto;
                height: 240px;
                border-radius: 30px;
                background: linear-gradient(135deg, rgba(60, 64, 198, 0.11), rgba(122, 128, 255, 0.10));
                z-index: 0;
            }

            .memo-shell > * {
                position: relative;
                z-index: 1;
            }

            .memo-hero {
                margin-top: 20px;
                border-radius: 28px;
                overflow: hidden;
                color: #ffffff;
                box-shadow: var(--memo-shadow);
                background:
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 32%),
                    linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
            }

            .memo-hero-body {
                padding: 32px;
            }

            .memo-eyebrow {
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

            .memo-title {
                margin: 18px 0 12px;
                color: #ffffff;
                font-size: clamp(2rem, 3vw, 2.7rem);
                line-height: 1.05;
                font-weight: 700;
                letter-spacing: -0.03em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .hero-add-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                margin-top: 24px;
                padding: 13px 20px;
                border: none;
                border-radius: 16px;
                color: var(--memo-navy);
                background: linear-gradient(135deg, #ffffff 0%, #eef7ff 100%);
                font-weight: 700;
                transition: transform 0.25s ease, box-shadow 0.25s ease;
            }

            .hero-add-btn:hover {
                color: var(--memo-navy);
                transform: translateY(-2px);
                box-shadow: 0 14px 32px rgba(17, 24, 39, 0.12);
            }

            .metric-card {
                height: 100%;
                border: none;
                border-radius: 22px;
                background: rgba(255, 255, 255, 0.95);
                box-shadow: var(--memo-shadow);
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

            .metric-card-body {
                padding: 24px;
            }

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
                color: var(--memo-muted);
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.05em;
                text-transform: uppercase;
            }

            .metric-value {
                margin-bottom: 8px;
                color: var(--memo-ink);
                font-size: 2.2rem;
                line-height: 1;
                font-weight: 700;
                letter-spacing: -0.04em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .metric-label {
                color: var(--memo-ink);
                font-size: 1rem;
                font-weight: 700;
                margin-bottom: 8px;
            }

            .memo-alert {
                border: none;
                border-radius: 18px;
                box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
            }

            .table-panel {
                border: 1px solid var(--memo-border);
                border-radius: 24px;
                background: rgba(255, 255, 255, 0.96);
                box-shadow: var(--memo-shadow);
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
                color: var(--memo-ink);
                font-size: 1.35rem;
                font-weight: 700;
                letter-spacing: -0.02em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .table-panel-copy {
                margin-bottom: 0;
                color: var(--memo-muted);
                line-height: 1.6;
            }

            .table-shell {
                padding: 20px 26px 26px;
            }

            .dataTables_wrapper .dataTables_filter label,
            .dataTables_wrapper .dataTables_length label {
                color: var(--memo-muted);
                font-weight: 600;
            }

            .dataTables_wrapper .dataTables_filter input,
            .dataTables_wrapper .dataTables_length select {
                border-radius: 12px;
                border: 1px solid rgba(60, 64, 198, 0.14);
                min-height: 42px;
                padding: 8px 12px;
                box-shadow: none;
            }

            .memo-table {
                margin-bottom: 0 !important;
                border-collapse: separate !important;
                border-spacing: 0 12px !important;
            }

            .memo-table thead th {
                border: none !important;
                color: var(--memo-muted) !important;
                font-size: 0.78rem !important;
                font-weight: 700 !important;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                padding: 0 18px 8px !important;
                background: transparent !important;
            }

            .memo-table tbody tr {
                background: linear-gradient(180deg, #ffffff 0%, #f9fbfd 100%);
                box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
            }

            .memo-table tbody td {
                border: none !important;
                vertical-align: middle !important;
                padding: 18px !important;
                color: var(--memo-ink);
                background: transparent !important;
            }

            .memo-table tbody td:first-child {
                border-radius: 18px 0 0 18px;
            }

            .memo-table tbody td:last-child {
                border-radius: 0 18px 18px 0;
            }

            .memo-number-pill {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 14px;
                border-radius: 999px;
                background: rgba(60, 64, 198, 0.10);
                color: var(--memo-blue);
                font-size: 0.83rem;
                font-weight: 700;
            }

            .memo-title-cell {
                font-weight: 700;
                letter-spacing: -0.01em;
                color: var(--memo-ink);
            }

            .memo-title-cell small {
                display: block;
                margin-top: 6px;
                color: var(--memo-muted);
                font-size: 0.88rem;
                font-weight: 500;
            }

            .manage-actions {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                flex-wrap: wrap;
            }

            .action-btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 9px 14px;
                border-radius: 999px;
                font-weight: 700;
                font-size: 0.85rem;
                text-decoration: none;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .action-btn:hover {
                text-decoration: none;
                transform: translateY(-2px);
            }

            .action-view {
                color: #272b8c;
                background: rgba(60, 64, 198, 0.12);
            }

            .action-upload {
                color: #4e54d4;
                background: rgba(122, 128, 255, 0.18);
            }

            .action-edit {
                color: #565de8;
                background: rgba(86, 93, 232, 0.12);
            }

            .action-delete {
                color: var(--memo-rose);
                background: rgba(207, 73, 100, 0.12);
            }

            .action-disabled {
                color: var(--memo-muted);
                background: rgba(114, 133, 154, 0.12);
            }

            .empty-state {
                text-align: center;
                padding: 38px 24px;
            }

            .empty-state i {
                font-size: 2.2rem;
                color: var(--memo-blue);
                margin-bottom: 12px;
            }

            .empty-state h5 {
                color: var(--memo-ink);
                font-weight: 700;
                margin-bottom: 8px;
            }

            .empty-state p {
                color: var(--memo-muted);
                margin-bottom: 0;
            }

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

            .modern-modal-header .close {
                color: #ffffff;
                opacity: 0.9;
                text-shadow: none;
            }

            .modern-modal-title {
                margin-bottom: 6px;
                color: #ffffff;
                font-size: 1.35rem;
                font-weight: 700;
                letter-spacing: -0.02em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .modern-modal-copy {
                margin-bottom: 0;
                color: rgba(255, 255, 255, 0.80);
            }

            .modern-modal-body {
                padding: 28px;
                background: #f8fbff;
            }

            .form-card {
                border: 1px solid rgba(60, 64, 198, 0.08);
                border-radius: 22px;
                background: #ffffff;
                padding: 24px;
            }

            .modern-label {
                color: var(--memo-ink);
                font-weight: 700;
                margin-bottom: 8px;
            }

            .modern-input {
                min-height: 48px;
                border-radius: 14px;
                border: 1px solid rgba(60, 64, 198, 0.14);
                padding: 12px 14px;
                box-shadow: none;
            }

            .modern-input:focus {
                border-color: var(--memo-blue);
                box-shadow: 0 0 0 0.18rem rgba(60, 64, 198, 0.14);
            }

            .modern-textarea {
                min-height: 120px;
                resize: vertical;
            }

            .file-drop {
                padding: 18px;
                border: 1px dashed rgba(60, 64, 198, 0.18);
                border-radius: 16px;
                background: #f8fbff;
            }

            .modal-footer-modern {
                display: flex;
                justify-content: flex-end;
                gap: 12px;
                padding-top: 16px;
            }

            .btn-soft-dark {
                color: var(--memo-ink);
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

            .btn-gradient-primary:hover {
                color: #ffffff;
            }

            @keyframes fade-up {
                from {
                    opacity: 0;
                    transform: translateY(18px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @media (max-width: 991.98px) {
                .memo-hero-body,
                .table-shell,
                .modern-modal-body {
                    padding: 24px;
                }

                .table-panel-head {
                    flex-direction: column;
                    align-items: flex-start;
                    padding: 24px 24px 0;
                }

                .table-toolbar {
                    flex-direction: column;
                    align-items: flex-start;
                }
            }

            @media (max-width: 767.98px) {
                .memo-table thead {
                    display: none;
                }

                .memo-table,
                .memo-table tbody,
                .memo-table tr,
                .memo-table td {
                    display: block;
                    width: 100%;
                }

                .memo-table tbody tr {
                    margin-bottom: 14px;
                    border-radius: 18px;
                    overflow: hidden;
                }

                .memo-table tbody td {
                    padding: 14px 18px !important;
                    border-radius: 0 !important;
                }

                .memo-table tbody td::before {
                    content: attr(data-label);
                    display: block;
                    margin-bottom: 6px;
                    color: var(--memo-muted);
                    font-size: 0.76rem;
                    font-weight: 700;
                    letter-spacing: 0.08em;
                    text-transform: uppercase;
                }

                .manage-actions {
                    justify-content: flex-start;
                }

                .memo-hero-body {
                    padding: 22px;
                }

                .memo-title {
                    font-size: 1.9rem;
                }
            }
        </style>
    </head>

    <body>

        <?php
            $totalMemos = count($page);
            $memosWithFile = 0;
            $myMemos = 0;

            foreach ($page as $memo) {
                if (trim((string) $memo->fileName) !== '') {
                    $memosWithFile++;
                }

                if ($memo->added_by === $this->session->userdata('username')) {
                    $myMemos++;
                }
            }

            $memosWithoutFile = $totalMemos - $memosWithFile;

            $metrics = [
                [
                    'label' => 'Total Memos',
                    'value' => $totalMemos,
                    'badge' => 'Registry',
                    'icon' => 'mdi mdi-file-document-multiple-outline',
                    'accent' => '#3c40c6',
                    'soft' => 'rgba(60, 64, 198, 0.12)',
                ],
                [
                    'label' => 'With Attachment',
                    'value' => $memosWithFile,
                    'badge' => 'File Ready',
                    'icon' => 'mdi mdi-file-check-outline',
                    'accent' => '#565de8',
                    'soft' => 'rgba(86, 93, 232, 0.12)',
                ],
                [
                    'label' => 'Needs Upload',
                    'value' => $memosWithoutFile,
                    'badge' => 'Pending',
                    'icon' => 'mdi mdi-cloud-upload-outline',
                    'accent' => '#7a80ff',
                    'soft' => 'rgba(122, 128, 255, 0.16)',
                ],
                [
                    'label' => 'My Memos',
                    'value' => $myMemos,
                    'badge' => 'Ownership',
                    'icon' => 'mdi mdi-account-edit-outline',
                    'accent' => '#272b8c',
                    'soft' => 'rgba(39, 43, 140, 0.12)',
                ],
            ];
        ?>

        <div id="wrapper">
            <?php include('includes/top-bar.php'); ?>
            <?php include('includes/sidebar.php') ?>

            <div class="content-page">
                <div class="content">
                    <div class="container-fluid memo-shell">
                        <div class="row">
                            <div class="col-12">
                                <div class="memo-hero">
                                    <div class="memo-hero-body">
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <span class="memo-eyebrow">
                                                    <i class="mdi mdi-file-document-outline"></i>
                                                    Department Memorandum Registry
                                                </span>
                                                <h1 class="memo-title">Keep department memos organized in a cleaner workspace.</h1>
                                                <button type="button" class="hero-add-btn" data-toggle="modal" data-target="#addMemoDialog">
                                                    <i class="mdi mdi-plus-circle-outline"></i>
                                                    Add New Memo
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if($this->session->flashdata('success')) : ?>
                            <div class="alert alert-success alert-dismissible fade show memo-alert mt-4" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <?= $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if($this->session->flashdata('danger')) : ?>
                            <div class="alert alert-danger alert-dismissible fade show memo-alert mt-4" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <?= $this->session->flashdata('danger'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="row mt-4">
                            <?php foreach ($metrics as $index => $metric) { ?>
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="metric-card" style="--accent: <?= $metric['accent']; ?>; animation-delay: <?= number_format(($index + 1) * 0.08, 2, '.', ''); ?>s;">
                                        <div class="metric-card-body">
                                            <div class="metric-head">
                                                <span class="metric-icon" style="background: <?= $metric['soft']; ?>; color: <?= $metric['accent']; ?>;">
                                                    <i class="<?= $metric['icon']; ?>"></i>
                                                </span>
                                                <span class="metric-badge"><?= $metric['badge']; ?></span>
                                            </div>
                                            <h2 class="metric-value"><?= $metric['value']; ?></h2>
                                            <div class="metric-label"><?= $metric['label']; ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="table-panel">
                            <div class="table-panel-head">
                                <div>
                                    <h4 class="table-panel-title"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h4>
                                </div>
                            </div>

                            <div class="table-shell">
                                <div class="table-responsive">
                                    <table class="table memo-table" id="datatable">
                                        <thead>
                                            <tr>
                                                <th>Memo Number</th>
                                                <th>Memo Subject</th>
                                                <th class="text-center">Manage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($page)) { ?>
                                                <?php foreach($page as $row){ ?>
                                                    <tr>
                                                        <td data-label="Memo Number">
                                                            <span class="memo-number-pill">
                                                                <i class="mdi mdi-pound"></i>
                                                                <?= htmlspecialchars($row->memoNo, ENT_QUOTES, 'UTF-8'); ?>
                                                            </span>
                                                        </td>
                                                        <td data-label="Memo Subject">
                                                            <div class="memo-title-cell">
                                                                <?= nl2br(htmlspecialchars(wordwrap(strtoupper($row->title), 50, "\n", true), ENT_QUOTES, 'UTF-8')); ?>
                                                                <small>
                                                                    <?= !empty($row->fileName) ? 'Attachment available' : 'No attachment uploaded yet'; ?>
                                                                </small>
                                                            </div>
                                                        </td>
                                                        <td data-label="Manage" class="text-center">
                                                            <?php if($row->added_by == $this->session->userdata('username')){ ?>
                                                                <div class="manage-actions">
                                                                    <?php if(empty($row->fileName)) { ?>
                                                                        <a class="action-btn action-upload tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Upload File" href="<?= base_url(); ?>page/memo_file_update/<?= $row->id; ?>">
                                                                            <i class="mdi mdi-upload-outline"></i>
                                                                            Upload
                                                                        </a>
                                                                    <?php } else { ?>
                                                                        <a class="action-btn action-view tooltips" data-placement="top" data-toggle="tooltip" data-original-title="View File" href="<?= base_url(); ?>upload/memo/<?= $row->fileName; ?>" target="_blank">
                                                                            <i class="mdi mdi-eye-outline"></i>
                                                                            View
                                                                        </a>
                                                                        <a class="action-btn action-upload tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Update File" href="<?= base_url(); ?>page/memo_file_update/<?= $row->id; ?>">
                                                                            <i class="mdi mdi-sync"></i>
                                                                            Update File
                                                                        </a>
                                                                    <?php } ?>

                                                                    <a class="action-btn action-edit tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit" href="<?= base_url(); ?>page/memo_update/<?= $row->id; ?>">
                                                                        <i class="mdi mdi-pencil-outline"></i>
                                                                        Edit
                                                                    </a>
                                                                    <a onclick="return confirm('Are you sure you want to delete this memo?')" class="action-btn action-delete tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Delete" href="<?= base_url(); ?>page/memo_delete/<?= $row->id; ?>">
                                                                        <i class="mdi mdi-trash-can-outline"></i>
                                                                        Delete
                                                                    </a>
                                                                </div>
                                                            <?php } else { ?>
                                                                <div class="manage-actions">
                                                                    <?php if(!empty($row->fileName)) { ?>
                                                                        <a class="action-btn action-view tooltips" data-placement="top" data-toggle="tooltip" data-original-title="View File" href="<?= base_url(); ?>upload/memo/<?= $row->fileName; ?>" target="_blank">
                                                                            <i class="mdi mdi-eye-outline"></i>
                                                                            View
                                                                        </a>
                                                                    <?php } ?>
                                                                    <span class="action-btn action-disabled">
                                                                        <i class="mdi mdi-lock-outline"></i>
                                                                        Owner Only
                                                                    </span>
                                                                </div>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan="3">
                                                        <div class="empty-state">
                                                            <i class="mdi mdi-folder-open-outline d-block"></i>
                                                            <h5>No memos yet</h5>
                                                            <p>Create the first memo for this department using the button above.</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include('includes/footer.php'); ?>
            </div>
        </div>

        <div id="addMemoDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addMemoDialogLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modern-modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h5 class="modern-modal-title" id="addMemoDialogLabel"><?= htmlspecialchars($m_title, ENT_QUOTES, 'UTF-8'); ?></h5>
                        <p class="modern-modal-copy">Create a new memo entry for the <?= htmlspecialchars($identifier, ENT_QUOTES, 'UTF-8'); ?> identifier.</p>
                    </div>

                    <div class="modern-modal-body">
                        <div class="form-card">
                            <?= form_open_multipart('Page/'.$add_action); ?>
                                <div class="form-group">
                                    <label class="modern-label">Memo Number</label>
                                    <input type="text" required name="memoNo" class="form-control modern-input bg-light" value="<?= isset($next_memo_no) ? htmlspecialchars($next_memo_no, ENT_QUOTES, 'UTF-8') : ''; ?>" readonly />
                                </div>

                                <div class="form-group">
                                    <label class="modern-label">Subject</label>
                                    <textarea required name="title" class="form-control modern-input modern-textarea" placeholder="Enter memo subject..."></textarea>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="modern-label">File</label>
                                    <div class="file-drop">
                                        <input type="file" name="file" class="form-control-file" accept=".pdf" />
                                        <small class="form-text text-muted mt-2 mb-0">Only PDF files are allowed.</small>
                                    </div>
                                </div>

                                <input type="hidden" name="id" id="id" value="">
                                <input type="hidden" name="field" id="field" value="">

                                <div class="modal-footer-modern">
                                    <button type="button" class="btn btn-soft-dark" data-dismiss="modal">Cancel</button>
                                    <input type="submit" name="submit" class="btn btn-gradient-primary" value="Save Memo" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.buttons.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/buttons.bootstrap4.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/jszip/jszip.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/pdfmake/pdfmake.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/pdfmake/vfs_fonts.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/buttons.html5.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/buttons.print.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.responsive.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.keyTable.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.select.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/pages/datatables.init.js"></script>
        <script src="<?= base_url(); ?>assets/libs/custombox/custombox.min.js"></script>

        <script type="text/javascript">
            $('body').tooltip({selector: '[data-toggle="tooltip"]'});

            $('#datatable').DataTable({
                pageLength: 10,
                order: [],
                responsive: true,
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ memos",
                    paginate: {
                        previous: "<i class='fas fa-chevron-left'></i>",
                        next: "<i class='fas fa-chevron-right'></i>"
                    }
                }
            });
        </script>
    </body>
</html>
