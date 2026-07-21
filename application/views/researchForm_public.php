<?php
/**
 * FILE: application/views/researchForm_public.php
 * Standalone Public Research Form + Success Summary (centered)
 * - No sidebar dependency, no status display
 * - After submit, shows only that record + Temp. Permit link (Research/report/{id})
 */
?>

<?php include('templates/head.php'); ?>

<style>
  :root{
    --card-radius: 16px;
    --soft-border: rgba(0,0,0,.08);
    --soft-shadow: 0 10px 30px rgba(0,0,0,.08);
  }

  /* Standalone page (avoid sidebar offsets) */
  .content-page.public-standalone{
    margin-left: 0 !important;
    padding-left: 0 !important;
  }

  /* Full-page centering */
  .public-center-wrap{
    min-height: 100vh;
    display: flex;
    align-items: center;     /* vertical center */
    justify-content: center; /* horizontal center */
    padding: 28px 12px;
  }

  .public-card{
    width: 100%;
    max-width: 900px;
  }

  .public-card .card{
    border-radius: var(--card-radius);
    overflow: hidden;
    border: 1px solid var(--soft-border);
    box-shadow: var(--soft-shadow);
  }

  /* Header */
  .public-hero{
    position: relative;
    padding: 18px 18px;
    background: linear-gradient(135deg, rgba(23,162,184,1), rgba(23,162,184,.75));
    color: #fff;
  }
  .public-hero h5{
    margin: 0;
    font-weight: 800;
    letter-spacing: .2px;
  }
  .public-hero .sub{
    margin-top: 6px;
    opacity: .95;
    font-size: .92rem;
  }

  /* Body */
  .public-body{
    padding: 18px;
    background: #fff;
  }

  /* Sections */
  .section-title{
    font-weight: 800;
    font-size: .95rem;
    margin: 0 0 10px 0;
    color: #2f3a40;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .section-title .dot{
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: #17a2b8;
    display: inline-block;
  }

  /* Inputs */
  .form-group label{
    font-weight: 700;
    font-size: .9rem;
  }
  .form-control{
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,.12);
  }
  .form-control:focus{
    box-shadow: 0 0 0 3px rgba(23,162,184,.18);
    border-color: rgba(23,162,184,.65);
  }

  /* Cards inside success view */
  .mini-kv{
    border: 1px dashed rgba(0,0,0,.18);
    border-radius: 14px;
    padding: 12px 14px;
    background: rgba(23,162,184,.04);
  }
  .mini-kv small{
    display:block;
    color:#6c757d;
    font-weight: 700;
    letter-spacing: .2px;
  }
  .mini-kv .val{
    margin-top: 2px;
    font-size: 1.15rem;
    font-weight: 900;
    color:#1f2a30;
    word-break: break-word;
  }

  .info-line{
    margin: 0 0 10px 0;
  }
  .info-line b{
    display:block;
    margin-bottom: 4px;
  }
  .info-box{
    border: 1px solid rgba(0,0,0,.08);
    border-radius: 14px;
    padding: 12px 14px;
    background: #fafbfc;
  }

  .btn{
    border-radius: 12px;
    font-weight: 800;
  }

  .btn-info{
    box-shadow: 0 8px 16px rgba(23,162,184,.22);
  }

  /* Attachments list */
  .attach-list{
    list-style: none;
    padding-left: 0;
    margin: 0;
  }
  .attach-list li{
    padding: 10px 12px;
    border: 1px solid rgba(0,0,0,.08);
    border-radius: 12px;
    margin-bottom: 10px;
    background: #fff;
    display:flex;
    align-items:center;
    justify-content: space-between;
    gap: 10px;
  }
  .attach-list .name{
    font-weight: 700;
    color:#2f3a40;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 560px;
  }
  .attach-list .open a{
    font-weight: 800;
    text-decoration: none;
  }

  /* Footer note */
  .foot-note{
    margin-top: 12px;
    color:#6c757d;
    font-size: .85rem;
  }

  @media (max-width: 576px){
    .attach-list .name{ max-width: 220px; }
  }
</style>

<!-- <div class="content-page public-standalone"> -->
  <div class="content">
    <div class="container-fluid public-center-wrap">

      <div class="public-card">
        <div class="card">

          <div class="public-hero text-center" style="background: linear-gradient(135deg, rgba(23,162,184,1), rgba(23,162,184,.75));"   >
            <h5 style="color:#fff; text-shadow: 0 2px 6px rgba(0,0,0,.12);">
              <?php if(($mode ?? '') === 'public_success'): ?>
                Research Request Submitted
              <?php else: ?>
                Request Form for Temporary Research Permit in DepEd Schools Division of Davao Oriental.
              <?php endif; ?>
            </h5>
            <div class="sub">
              <?php if(($mode ?? '') === 'public_success'): ?>
                Keep your Control No. for reference. You may open your Temp. Permit anytime.
              <?php else: ?>
                <p style="font-size: 13px;">Fill out the details below. After submitting, you will see only your own request and Temp. Permit.</p>
              <?php endif; ?>
            </div>
          </div>

          <div class="public-body">

            <?php if(!empty($danger)): ?>
              <div class="alert alert-danger">
                <?= htmlspecialchars($danger); ?>
              </div>
            <?php endif; ?>

            <?php if (validation_errors()): ?>
              <div class="alert alert-danger">
                <?= validation_errors(); ?>
              </div>
            <?php endif; ?>

            <?php if(($mode ?? '') === 'public_success'): ?>
              <!-- =========================
                   SUCCESS VIEW (only their data)
                   NO STATUS DISPLAY
                   ========================= -->

              <div class="alert alert-success">
                <b>Submitted successfully!</b> Please save your Control No. for reference.
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <div class="mini-kv">
                    <small>Control No.</small>
                    <div class="val"><?= htmlspecialchars($row->control_no); ?></div>
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <div class="mini-kv">
                    <small>Request Date</small>
                    <div class="val"><?= htmlspecialchars($row->request_date); ?></div>
                  </div>
                </div>
              </div>

              <div class="info-box mb-3">
                <p class="info-line mb-3">
                  <b>Researcher</b>
                  <?= htmlspecialchars((string)$row->main_author_id); ?>
                </p>

                <p class="info-line mb-3">
                  <b>Research Title</b>
                  <?= nl2br(htmlspecialchars((string)$row->research_title)); ?>
                </p>

                <div class="row">
                  <div class="col-md-6">
                    <p class="info-line mb-0">
                      <b>Researcher Type</b>
                      <?= htmlspecialchars((string)$row->researcher_type); ?>
                    </p>
                  </div>
                  <div class="col-md-6">
                    <p class="info-line mb-0">
                      <b>School</b>
                      <?= htmlspecialchars((string)$row->hei_name); ?>
                    </p>
                  </div>
                </div>

                <hr>

                <p class="info-line mb-0">
                  <b>Campus Location</b>
                  <?= htmlspecialchars((string)$row->hei_campus_location); ?>
                </p>
              </div>

              <?php if(!empty($files)): ?>
                <div class="mb-3">
                  <div class="section-title"><span class="dot"></span> Attachments</div>
                  <ul class="attach-list">
                    <?php foreach($files as $f): ?>
                      <?php
                        $label = !empty($f->original_name) ? $f->original_name : $f->file_path;
                      ?>
                      <li>
                        <div class="name" title="<?= htmlspecialchars($label); ?>">
                          <?= htmlspecialchars($label); ?>
                        </div>
                        <div class="open">
                          <a href="<?= base_url($f->file_path); ?>" target="_blank">Open</a>
                        </div>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              <?php endif; ?>

              <div class="d-flex flex-wrap gap-2">
                <!-- ✅ Requirement: Temp. Permit opens researchReport_letter.php via Research/report/{id} -->
                <a class="btn btn-info" target="_blank" href="<?= base_url('Research/report/'.(int)$row->id); ?>">
                  <i class="mdi mdi-file-document-box-check-outline"></i> Generate Temp. Permit
                </a>

                <a class="btn btn-light" href="<?= base_url('Research/public_form'); ?>">
                  Submit Another Request
                </a>
              </div>

              <div class="foot-note">
                Tip: Screenshot or copy your Control No. so you can reference your submission.
              </div>

            <?php else: ?>
              <!-- =========================
                   PUBLIC FORM
                   ========================= -->

              <div class="section-title"><span class="dot"></span> Request Details</div>

              <form method="post" enctype="multipart/form-data" action="<?= base_url('Research/public_store'); ?>">

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Request Date <span class="text-danger">*</span></label>
                      <input type="date" name="request_date" class="form-control"
                             value="<?= set_value('request_date', date('Y-m-d')); ?>">
                      <small class="text-muted">Date you are submitting this request.</small>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Researcher (Full Name) <span class="text-danger">*</span></label>
                      <input type="text" name="author_name" class="form-control"
                             value="<?= set_value('author_name', ''); ?>"
                             placeholder="e.g., Juan Dela Cruz">
                      <small class="text-muted">Name of the researcher.</small>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label>Research Title <span class="text-danger">*</span></label>
                  <textarea name="research_title" class="form-control" rows="3"
                            placeholder="Type the complete research title..."><?= set_value('research_title', ''); ?></textarea>
                  <small class="text-muted">Use the official title as it appears in your paper.</small>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Type of Researcher <span class="text-danger">*</span></label>
                      <select name="researcher_type" class="form-control">
                        <option value="">-- Select Type of Researcher --</option>
                        <?php
                          $types = [
                            'Undergraduate Student Researcher',
                            'Graduate School Researcher (Thesis)',
                            'Graduate School Researcher (Dissertation)',
                          ];
                          $selectedType = set_value('researcher_type', '');
                          foreach ($types as $type):
                        ?>
                          <option value="<?= htmlspecialchars($type, ENT_QUOTES); ?>"
                            <?= ($selectedType === $type) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($type, ENT_QUOTES); ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                      <small class="text-muted">Choose what applies to you.</small>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>School Name <span class="text-danger">*</span></label>
                      <input type="text" name="hei_name" class="form-control"
                             value="<?= set_value('hei_name', ''); ?>"
                             placeholder="e.g., Davao Oriental State University">
                      <small class="text-muted">Complete school name (HEI).</small>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label>Campus Location <span class="text-danger">*</span></label>
                  <input type="text" name="hei_campus_location" class="form-control"
                         value="<?= set_value('hei_campus_location', ''); ?>"
                         placeholder="e.g., City of Mati, Davao Oriental">
                  <small class="text-muted">City/municipality and province.</small>
                </div>

                <!-- <div class="section-title mt-4"><span class="dot"></span> Attachments</div>
                <div class="form-group">
                  <label>Attachments (Images/PDFs)</label>
                  <input type="file" name="attachments[]" multiple class="form-control" required>
                  <small class="text-muted">Allowed: JPG, PNG, PDF </small>
                </div> -->

                <button type="submit" class="btn btn-info btn-block">
                  <i class="mdi mdi-content-save"></i> Submit Request
                </button>

                <div class="foot-note text-center">
                  By submitting, you confirm the information provided is correct.
                </div>

              </form>

            <?php endif; ?>

          </div><!-- public-body -->
        </div><!-- card -->
      </div><!-- public-card -->

    </div><!-- public-center-wrap -->
  </div><!-- content -->
</div><!-- content-page -->

