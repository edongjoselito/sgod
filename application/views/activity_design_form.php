<?php
$esc = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
$entry = isset($entry) ? $entry : null;
$isEdit = !empty($entry);
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
            :root {
                --memo-navy: #272b8c;
                --memo-blue: #3c40c6;
                --memo-ink: #23275d;
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
            }

            .hero-add-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
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
                text-decoration: none;
            }
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
                                                <span class="memo-eyebrow">
                                                    <i class="mdi mdi-pencil-box-outline"></i>
                                                    Activity Design
                                                </span>
                                                <h1 class="memo-title"><?= $isEdit ? 'Edit' : 'New'; ?> Activity Design</h1>
                                            </div>
                                            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                                <a href="<?= base_url(); ?>Page/activity_design" class="hero-add-btn">
                                                    <i class="mdi mdi-arrow-left"></i>
                                                    Back to List
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form method="post" action="<?= base_url(); ?>Page/activity_design_save">
                            <?php if ($isEdit): ?>
                                <input type="hidden" name="id" value="<?= (int) $entry->id; ?>">
                            <?php endif; ?>

                            <?php
                            $budgetLines = ($isEdit && !empty($entry->budget_lines)) ? json_decode($entry->budget_lines, TRUE) : array();
                            if (empty($budgetLines)) {
                                $budgetLines = array(array('particulars' => '', 'amount' => '', 'unit' => '', 'qty' => '', 'total_amount' => ''));
                            }
                            ?>

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title text-muted">Activity Details</h5>

                                    <div class="form-group">
                                        <label>Activity Design No.</label>
                                        <input type="text" class="form-control" value="<?= $isEdit ? $esc($entry->activity_design_no ?? '') : (isset($next_no) ? $esc($next_no) : ''); ?>" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>Activity Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" value="<?= $isEdit ? $esc($entry->title) : ''; ?>" required>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label>Date</label>
                                            <input type="date" name="activity_date" class="form-control" value="<?= $isEdit ? $esc($entry->activity_date) : ''; ?>">
                                        </div>
                                        <div class="form-group col-md-8">
                                            <label>Venue</label>
                                            <input type="text" name="venue" class="form-control" value="<?= $isEdit ? $esc($entry->venue) : ''; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Fund Source</label>
                                        <input type="text" name="fund_source" class="form-control" value="<?= $isEdit ? $esc($entry->fund_source) : ''; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>Rationale</label>
                                        <textarea name="rationale" class="form-control" rows="3"><?= $isEdit ? $esc($entry->rationale) : ''; ?></textarea>
                                    </div>

                                    <div class="form-group mb-0">
                                        <label>Objectives</label>
                                        <textarea name="objectives" class="form-control" rows="4"><?= $isEdit ? $esc($entry->objectives) : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-body">
                                    <h5 class="card-title text-muted">Budgetary Requirements</h5>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover table-bordered mb-0" id="budgetTable">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Particulars</th>
                                                    <th style="min-width:110px;">Amount</th>
                                                    <th style="min-width:90px;">Unit</th>
                                                    <th style="min-width:70px;">Qty</th>
                                                    <th style="min-width:110px;">Total</th>
                                                    <th style="min-width:50px;"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($budgetLines as $line): ?>
                                                    <tr>
                                                        <td><input type="text" name="budget_particulars[]" class="form-control form-control-sm" value="<?= $esc($line['particulars'] ?? ''); ?>"></td>
                                                        <td><input type="number" step="0.01" name="budget_amount[]" class="form-control form-control-sm budget-amount" value="<?= isset($line['amount']) && $line['amount'] !== '' ? number_format((float) $line['amount'], 2, '.', '') : ''; ?>"></td>
                                                        <td><input type="text" name="budget_unit[]" class="form-control form-control-sm" value="<?= $esc($line['unit'] ?? ''); ?>"></td>
                                                        <td><input type="number" step="0.01" name="budget_qty[]" class="form-control form-control-sm budget-qty" value="<?= $esc($line['qty'] ?? ''); ?>"></td>
                                                        <td><input type="number" step="0.01" name="budget_total[]" class="form-control form-control-sm budget-total" value="<?= isset($line['total_amount']) && $line['total_amount'] !== '' ? number_format((float) $line['total_amount'], 2, '.', '') : ''; ?>"></td>
                                                        <td class="text-center align-middle"><button type="button" class="btn btn-sm btn-outline-danger remove-budget-row"><i class="mdi mdi-trash-can-outline"></i></button></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary mt-3" id="addBudgetRow">
                                        <i class="mdi mdi-plus mr-1"></i> Add Budget Line
                                    </button>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="mdi mdi-content-save-outline mr-1"></i> Save Activity Design
                                </button>
                                <a href="<?= base_url(); ?>Page/activity_design" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>

                <?php include('includes/footer.php'); ?>
            </div>
        </div>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
        <script>
            (function () {
                const table = document.getElementById('budgetTable');
                const addBtn = document.getElementById('addBudgetRow');
                if (!table || !addBtn) { return; }

                function recompute(row) {
                    const amount = parseFloat(row.querySelector('.budget-amount').value) || 0;
                    const qty = parseFloat(row.querySelector('.budget-qty').value) || 0;
                    const totalInput = row.querySelector('.budget-total');
                    totalInput.value = (amount * qty).toFixed(2);
                }

                table.addEventListener('input', function (e) {
                    if (e.target.classList.contains('budget-amount') || e.target.classList.contains('budget-qty')) {
                        recompute(e.target.closest('tr'));
                    }
                });

                table.addEventListener('click', function (e) {
                    if (e.target.classList.contains('remove-budget-row')) {
                        const rows = table.querySelectorAll('tbody tr');
                        if (rows.length > 1) {
                            e.target.closest('tr').remove();
                        }
                    }
                });

                addBtn.addEventListener('click', function () {
                    const template = table.querySelector('tbody tr');
                    if (!template) { return; }
                    const row = template.cloneNode(true);
                    row.querySelectorAll('input').forEach(function (input) { input.value = ''; });
                    table.querySelector('tbody').appendChild(row);
                });
            })();
        </script>
    </body>
</html>
