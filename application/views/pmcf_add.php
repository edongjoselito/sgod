<?php
if (!isset($title)) {
    $title = 'Add PMCF';
}
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
        .pmcf-hero {
            background: linear-gradient(135deg, #2c3e50 0%, #4ca1af 100%);
            color: #fff;
            padding: 1.5rem;
            border-radius: .5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,.1);
        }
        .pmcf-hero h4 { color: #fff; margin: 0; font-weight: 600; }
        .pmcf-card {
            border: 0;
            border-radius: .5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,.06);
            transition: transform .2s ease, box-shadow .2s ease;
            margin-bottom: 1.5rem;
        }
        .pmcf-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0,0,0,.1);
        }
        .pmcf-label {
            font-weight: 600;
            font-size: .85rem;
            color: #495057;
            text-transform: uppercase;
            letter-spacing: .4px;
            margin-bottom: .25rem;
        }
        .form-control, .custom-select {
            border-radius: .375rem;
            border: 1px solid #ced4da;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-control:focus, .custom-select:focus {
            border-color: #4ca1af;
            box-shadow: 0 0 0 .2rem rgba(76,161,175,.25);
        }
        .btn-pmcf {
            background: linear-gradient(135deg, #4ca1af 0%, #2c3e50 100%);
            border: 0;
            color: #fff;
            padding: .5rem 1.5rem;
            border-radius: .375rem;
            font-weight: 600;
            transition: opacity .2s;
        }
        .btn-pmcf:hover { opacity: .9; color: #fff; }
        .btn-pmcf-outline {
            border: 1px solid #4ca1af;
            color: #4ca1af;
            background: #fff;
            padding: .5rem 1.5rem;
            border-radius: .375rem;
            font-weight: 600;
        }
        .btn-pmcf-outline:hover { background: #4ca1af; color: #fff; }
        @media (max-width: 767.98px) {
            .pmcf-hero { padding: 1rem; }
            .pmcf-hero h4 { font-size: 1.25rem; }
            .btn-pmcf, .btn-pmcf-outline { width: 100%; margin-bottom: .5rem; }
        }
    </style>
    </head>
    <body>
        <div id="wrapper">
            <?php include('includes/top-bar.php'); ?>
            <?php include('includes/sidebar.php'); ?>
            <div class="content-page">
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box pmcf-hero d-flex justify-content-between align-items-center">
                                    <h4 class="page-title">Add PMCF</h4>
                                    <a href="<?= base_url(); ?>Page/pmcf" class="btn btn-pmcf-outline">Back to List</a>
                                </div>
                            </div>
                        </div>

                        <?php if ($this->session->flashdata('success')): ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')): ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-12">
                                <div class="card pmcf-card">
                                    <div class="card-body">
                                        <h5 class="mb-3">PMCF Data Entry</h5>
                                        <form method="post" action="<?= base_url(); ?>Page/pmcf_add">
                                            <div class="row">
                                                <div class="col-md-4 mb-2">
                                                    <label class="pmcf-label">Name of Teacher Observed</label>
                                                    <input type="text" name="teacher_observed" class="form-control" required>
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                    <label class="pmcf-label">Grade Level</label>
                                                    <select name="grade_level" class="form-control custom-select">
                                                        <option value="">Select Grade Level</option>
                                                        <option value="Kindergarten">Kindergarten</option>
                                                        <option value="Grade 1">Grade 1</option>
                                                        <option value="Grade 2">Grade 2</option>
                                                        <option value="Grade 3">Grade 3</option>
                                                        <option value="Grade 4">Grade 4</option>
                                                        <option value="Grade 5">Grade 5</option>
                                                        <option value="Grade 6">Grade 6</option>
                                                        <option value="Grade 7">Grade 7</option>
                                                        <option value="Grade 8">Grade 8</option>
                                                        <option value="Grade 9">Grade 9</option>
                                                        <option value="Grade 10">Grade 10</option>
                                                        <option value="Grade 11">Grade 11</option>
                                                        <option value="Grade 12">Grade 12</option>
                                                        <option value="SPED">SPED</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                    <label class="pmcf-label">Section</label>
                                                    <input type="text" name="section" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 mb-2">
                                                    <label class="pmcf-label">District</label>
                                                    <select name="district" id="pmcf_district" class="form-control custom-select">
                                                        <option value="">Select District</option>
                                                        <?php if (isset($districts) && $districts): ?>
                                                        <?php foreach ($districts as $d): ?>
                                                        <option value="<?= htmlspecialchars((string) $d->district); ?>"><?= htmlspecialchars((string) $d->district); ?></option>
                                                        <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                    <label class="pmcf-label">School</label>
                                                    <select name="school" id="pmcf_school" class="form-control custom-select" disabled>
                                                        <option value="">Select School</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                    <label class="pmcf-label">Term</label>
                                                    <select name="quarter" class="form-control custom-select">
                                                        <option value="">Select Term</option>
                                                        <option value="Term 1">Term 1</option>
                                                        <option value="Term 2">Term 2</option>
                                                        <option value="Term 3">Term 3</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 mb-2">
                                                    <label class="pmcf-label">Date Observed</label>
                                                    <input type="date" name="date_observed" class="form-control" required>
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                    <label class="pmcf-label">Time Observed</label>
                                                    <input type="time" name="time_observed" class="form-control">
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                    <label class="pmcf-label">Subject Area</label>
                                                    <input type="text" name="subject_area" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <label class="pmcf-label">Instructional Supervisor</label>
                                                    <input type="text" name="instructional_supervisor" class="form-control">
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label class="pmcf-label">Designation</label>
                                                    <input type="text" name="designation" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 mb-2">
                                                    <label class="pmcf-label">Significant Incidents Description</label>
                                                    <small class="form-text text-muted mb-2"><i class="mdi mdi-information-outline"></i> <em>Example: Care Competency (PS) for Professionalism and Ethics (i.e., Mid-year supervisions to meet PEs competencies is needed) - He voluntarily gives up her weekend (Saturday) to tutor struggling students for free, despite having personal commitments.</em></small>
                                                    <textarea name="significant_incidents_description" class="form-control" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 mb-2">
                                                    <label class="pmcf-label">Impact on Job</label>
                                                    <textarea name="impact_on_job" class="form-control" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <label class="pmcf-label">Coaching Mechanisms</label>
                                                    <select name="coaching_mechanisms" class="form-control custom-select">
                                                        <option value="">Select</option>
                                                        <option value="Meeting (Group)">Meeting (Group)</option>
                                                        <option value="One on One">One on One</option>
                                                        <option value="Learning Action Cell Sessions">Learning Action Cell Sessions</option>
                                                        <option value="Walk-through Observations">Walk-through Observations</option>
                                                        <option value="Workshop">Workshop</option>
                                                        <option value="Others">Others</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label class="pmcf-label">If Others, specify</label>
                                                    <input type="text" name="coaching_mechanisms_others" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 mb-2">
                                                    <label class="pmcf-label">Feedback / Recommendation</label>
                                                    <textarea name="feedback_recommendation" class="form-control" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 mb-2">
                                                    <label class="pmcf-label">Progress to Date</label>
                                                    <textarea name="progress_to_date" class="form-control" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <button type="submit" name="submit" value="Save" class="btn btn-pmcf">Save</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <?php include('includes/footer.php'); ?>
            </div>
        </div>
        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
        <script>
            var pmcfSchools = <?= json_encode(isset($schools) ? $schools : []); ?>;
            function filterPmcfSchools() {
                var district = document.getElementById('pmcf_district').value;
                var schoolSelect = document.getElementById('pmcf_school');
                schoolSelect.innerHTML = '<option value="">Select School</option>';
                if (!district) {
                    schoolSelect.disabled = true;
                    return;
                }
                schoolSelect.disabled = false;
                pmcfSchools.forEach(function(s) {
                    if (s.district === district) {
                        var opt = document.createElement('option');
                        opt.value = s.schoolName;
                        opt.textContent = s.schoolName;
                        schoolSelect.appendChild(opt);
                    }
                });
            }
            document.getElementById('pmcf_district').addEventListener('change', filterPmcfSchools);
        </script>
    </body>
</html>
