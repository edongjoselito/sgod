<?php
$esc = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
$districtName = isset($district->discription) ? $district->discription : 'School';
$isSchoolUser = $this->session->userdata('position') === 'School';
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
            :root { --memo-navy: #272b8c; --memo-blue: #3c40c6; --memo-ink: #23275d; --memo-shadow: 0 24px 60px rgba(15, 23, 42, .08); }
            body { background: radial-gradient(circle at top left, rgba(60, 64, 198, .10), transparent 24%), linear-gradient(180deg, #f4f8fc 0%, #eef4fa 100%); }
            .content-page { background: transparent; }
            .memo-shell { position: relative; padding-bottom: 28px; }
            .memo-shell::before { content: ""; position: absolute; inset: 24px 0 auto; height: 240px; border-radius: 30px; background: linear-gradient(135deg, rgba(60, 64, 198, .11), rgba(122, 128, 255, .10)); z-index: 0; }
            .memo-shell > * { position: relative; z-index: 1; }
            .memo-hero { margin-top: 20px; border-radius: 28px; overflow: hidden; color: #fff; box-shadow: var(--memo-shadow); background: radial-gradient(circle at top right, rgba(255, 255, 255, .16), transparent 32%), linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%); }
            .memo-hero-body { padding: 32px; }
            .memo-eyebrow { display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: 999px; background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.18); font-size: .8rem; letter-spacing: .08em; text-transform: uppercase; }
            .memo-title { margin: 18px 0 12px; color: #fff; font-size: clamp(2rem, 3vw, 2.7rem); line-height: 1.05; font-weight: 700; letter-spacing: -.03em; }
            .memo-subtitle { margin: 0; color: rgba(255,255,255,.82); font-size: 1rem; }
            .checklist-card { margin-top: 24px; border: 0; border-radius: 22px; box-shadow: var(--memo-shadow); overflow: hidden; }
            .checklist-card .card-body { padding: 26px; }
            .checklist-accordion .card { border: 1px solid #e8ecf5; border-radius: 16px !important; overflow: hidden; margin-bottom: 14px; box-shadow: none; }
            .checklist-accordion .card-header { border: 0; background: #f8f9ff; padding: 0; }
            .checklist-accordion .category-toggle { display: flex; align-items: center; justify-content: space-between; width: 100%; padding: 18px 20px; color: var(--memo-ink); font-weight: 700; text-decoration: none; }
            .checklist-accordion .category-toggle:hover { color: var(--memo-blue); text-decoration: none; }
            .checklist-accordion .category-number { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; margin-right: 10px; border-radius: 50%; background: rgba(60,64,198,.1); color: var(--memo-blue); font-size: .8rem; }
            .checklist-table { margin-bottom: 0; }
            .checklist-table thead th { border-top: 0; border-bottom: 1px solid #e8ecf5; background: #fff; color: #68708a; font-size: .72rem; font-weight: 800; letter-spacing: .04em; text-align: center; text-transform: uppercase; vertical-align: middle; }
            .checklist-table td { border-color: #eef1f7; color: #343958; vertical-align: middle; }
            .checklist-table td:first-child { min-width: 280px; }
            .checklist-table textarea { min-width: 180px; border-color: #dfe4f0; border-radius: 10px; font-size: .9rem; }
            .status-radio { width: 18px; height: 18px; accent-color: var(--memo-blue); cursor: pointer; }
            .save-checklist { display: inline-flex; align-items: center; gap: 9px; padding: 12px 20px; border: 0; border-radius: 14px; color: #fff; background: linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%); font-weight: 700; box-shadow: 0 12px 26px rgba(60,64,198,.2); }
            @media (max-width: 767px) { .memo-hero-body, .checklist-card .card-body { padding: 22px; } .checklist-table td:first-child { min-width: 200px; } }
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
                                <span class="memo-eyebrow"><i class="mdi mdi-clipboard-check-outline"></i> School Preparedness Checklist</span>
                                <h1 class="memo-title"><?= $esc($districtName); ?></h1>
                                <p class="memo-subtitle"><?= $isSchoolUser ? 'Complete and submit your school preparedness assessment.' : 'Review the submitted school preparedness assessment.'; ?></p>
                            </div>
                            <div class="col-md-4 text-md-right mt-3 mt-md-0"><span class="memo-eyebrow">School Year <?= $esc($this->session->userdata('cur_sy')); ?></span></div>
                        </div></div></div></div></div>

                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show mt-4" role="alert"><?= $esc($this->session->flashdata('success')); ?><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('danger')): ?>
                            <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert"><?= $esc($this->session->flashdata('danger')); ?><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>
                        <?php endif; ?>

                        <?php $attributes = array('class' => 'parsley-examples'); ?>
                        <?= form_open('Brigada/brigada_spc', $attributes); ?>
                        <input type="hidden" value="<?= $esc($this->session->userdata('username')); ?>" name="school_id">
                        <input type="hidden" value="<?= $esc($this->session->userdata('cur_fy')); ?>" name="fy">
                        <input type="hidden" value="<?= date('Y-m-d'); ?>" name="cdate">
                        <input type="hidden" value="<?= isset($district->id) ? (int) $district->id : ''; ?>" name="district">
                        <input type="hidden" value="<?= $esc($this->session->userdata('cur_sy')); ?>" name="sy">
                        <?php if (!empty($existing)): ?><input type="hidden" name="feedback_id" value="<?= (int) $existing[0]->id; ?>"><?php endif; ?>

                        <div class="card checklist-card"><div class="card-body">
                            <div id="checklistAccordion" class="checklist-accordion">
                                <?php $categoryNumber = 1; ?>
                                <?php foreach ($data as $category): ?>
                                    <?php
                                    $items = $this->Common->one_cond('brigada_spc_items', 'spc_cat_id', $category->id);
                                    $feedback = !empty($existing) ? (array) $existing[0] : array();
                                    $collapseId = 'checklistCategory' . (int) $category->id;
                                    ?>
                                    <div class="card">
                                        <div class="card-header" id="heading<?= (int) $category->id; ?>">
                                            <a class="category-toggle" href="#<?= $collapseId; ?>" data-toggle="collapse" aria-expanded="<?= $categoryNumber === 1 ? 'true' : 'false'; ?>" aria-controls="<?= $collapseId; ?>">
                                                <span><span class="category-number"><?= $categoryNumber; ?></span><?= $esc($category->name); ?></span><i class="mdi mdi-chevron-down"></i>
                                            </a>
                                        </div>
                                        <div id="<?= $collapseId; ?>" class="collapse <?= $categoryNumber === 1 ? 'show' : ''; ?>" data-parent="#checklistAccordion">
                                            <div class="card-body p-0">
                                                <div class="table-responsive">
                                                    <table class="table checklist-table">
                                                        <thead>
                                                            <tr><th>Category</th><th>Fully Prepared</th><th>Partially Prepared</th><th>Not Prepared</th><th>Remarks</th></tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $itemNumber = 1; ?>
                                                            <?php foreach ($items as $item): ?>
                                                                <?php
                                                                $field = $category->id . $itemNumber++;
                                                                $value = isset($feedback['q' . $field]) ? (string) $feedback['q' . $field] : '';
                                                                $remarks = isset($feedback['r' . $field]) ? $feedback['r' . $field] : '';
                                                                ?>
                                                                <tr>
                                                                    <td><?= $esc($item->description); ?></td>
                                                                    <td class="text-center"><input type="hidden" name="q<?= $field; ?>" value="0"><input class="status-radio" type="radio" name="q<?= $field; ?>" value="1" <?= $value === '1' ? 'checked' : ''; ?>></td>
                                                                    <td class="text-center"><input class="status-radio" type="radio" name="q<?= $field; ?>" value="2" <?= $value === '2' ? 'checked' : ''; ?>></td>
                                                                    <td class="text-center"><input class="status-radio" type="radio" name="q<?= $field; ?>" value="3" <?= $value === '3' ? 'checked' : ''; ?>></td>
                                                                    <td><textarea class="form-control" name="r<?= $field; ?>" rows="2"><?= $esc($remarks); ?></textarea></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $categoryNumber++; ?>
                                <?php endforeach; ?>
                            </div>
                            <?php if ($isSchoolUser): ?>
                                <div class="mt-4 text-right"><button type="submit" name="submit" value="Submit" class="save-checklist"><i class="mdi mdi-content-save-outline"></i> Save Checklist</button></div>
                            <?php endif; ?>
                        </div></div>
                        <?= form_close(); ?>
                    </div>
                </div>
                <?php include('includes/footer.php'); ?>
            </div>
        </div>
        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
    </body>
</html>
