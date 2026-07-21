<?php include('templates/head.php'); ?>
<?php include('templates/header.php'); ?>

<div class="content-page">
  <div class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-md-12">
          <div class="page-title-box">
            <h4 class="page-title">
              <a href="<?= base_url('Research'); ?>" class="btn btn-light">
                <i class="mdi mdi-arrow-left"></i> Back
              </a>
            </h4>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="card">

            <div class="card-header bg-info py-3 text-white">
              <div class="card-widgets">
                <a data-toggle="collapse" href="#cardForm" role="button"><i class="mdi mdi-minus"></i></a>
              </div>
              <h5 class="card-title mb-0 text-white">
                <?= ($mode === 'edit') ? 'Edit Research Request' : 'New Research Request'; ?>
              </h5>
            </div>

            <div id="cardForm" class="collapse show">
              <div class="card-body">

                <?php if ($this->session->flashdata('danger')): ?>
                  <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    <?= $this->session->flashdata('danger'); ?>
                  </div>
                <?php endif; ?>

                <?php if (validation_errors()): ?>
                  <div class="alert alert-danger"><?= validation_errors(); ?></div>
                <?php endif; ?>

                <?php
                  // passed from controller:
                  // $mainAuthorId, $mainAuthorText
                  $mainAuthorId   = isset($mainAuthorId)   ? (string)$mainAuthorId   : (string)$this->session->userdata('username');
                  $mainAuthorText = isset($mainAuthorText) ? (string)$mainAuthorText : $mainAuthorId;

                  // author_mode: only two values now (single | members)
                  // for backward compatibility: if old mode was main_and_members -> treat as members
                  $author_mode_val = set_value('author_mode',
                    ($mode === 'edit')
                      ? (($row->author_mode === 'main_and_members' || $row->author_mode === 'members_only') ? 'members' : 'single')
                      : 'single'
                  );
                ?>

                <div class="alert alert-info">
                  <b>Main Author:</b> Provide the main researcher. If you are not logged in, type the name/ID manually.<br>
                  <small>Single Researcher = main author only. Multiple Members = main author + selected members.</small>
                </div>

                <form method="post" enctype="multipart/form-data"
                      action="<?= ($mode==='edit') ? base_url('Research/update/'.$row->id) : base_url('Research/store'); ?>">

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Request Date</label>
                        <input type="date" name="request_date" class="form-control"
                               value="<?= set_value('request_date', ($mode==='edit') ? $row->request_date : date('Y-m-d')); ?>">
                      </div>
                    </div>

                    <?php if($mode==='edit'): ?>
                      <div class="col-md-8">
                        <div class="form-group">
                          <label>Control No.</label>
                          <input type="text" class="form-control" value="<?= htmlspecialchars($row->control_no); ?>" readonly>
                        </div>
                      </div>
                    <?php endif; ?>
                  </div>

                  <div class="form-group">
                    <label>Research Title</label>
                    <textarea name="research_title" class="form-control" rows="3"><?= set_value('research_title', ($mode==='edit') ? $row->research_title : ''); ?></textarea>
                  </div>

                 <div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label>Type of Researcher</label>
      <select name="researcher_type" class="form-control">
        <option value="">-- Select Type of Researcher --</option>

        <?php
          $types = [
            'Undergraduate Student Researcher',
            'Graduate School Researcher (Thesis)',
            'Graduate School Researcher (Dissertation)',
          ];

          $selectedType = set_value(
            'researcher_type',
            ($mode === 'edit') ? ($row->researcher_type ?? '') : ''
          );

          foreach ($types as $type):
        ?>
          <option value="<?= htmlspecialchars($type, ENT_QUOTES); ?>"
                  <?= ($selectedType === $type) ? 'selected' : ''; ?>>
            <?= htmlspecialchars($type, ENT_QUOTES); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label>School Name</label>
                        <input type="text" name="hei_name" class="form-control"
                               value="<?= set_value('hei_name', ($mode==='edit') ? $row->hei_name : ''); ?>">
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Campus Location</label>
                    <input type="text" name="hei_campus_location" class="form-control"
                           value="<?= set_value('hei_campus_location', ($mode==='edit') ? $row->hei_campus_location : ''); ?>">
                  </div>

                  <hr>

                  <!-- ONE ROW: Main Author + Members -->
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Main Author (ID or Name)</label>
                        <input type="text" name="main_author_id" class="form-control"
                               value="<?= set_value('main_author_id', $mainAuthorId); ?>"
                               placeholder="Enter main researcher ID or name">
                        <small class="text-muted">Who is leading this research.</small>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Researchers Mode</label>
                        <select name="author_mode" id="author_mode" class="form-control">
                          <option value="single"  <?= ($author_mode_val==='single')?'selected':''; ?>>Single Researcher</option>
                          <option value="members" <?= ($author_mode_val==='members')?'selected':''; ?>>Multiple Members</option>
                        </select>
                        <small class="text-muted">Add members only when needed.</small>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group" id="members_wrap">
                        <label>Members</label>
                        <select name="member_ids[]" id="member_ids" class="form-control" multiple></select>
                        <small class="text-muted">Select additional researchers.</small>
                      </div>
                    </div>
                  </div>

                  <hr>

                  <div class="form-group">
                    <label>Attachments (Images/PDFs)</label>
                    <input type="file" name="attachments[]" multiple class="form-control">
                    <small class="text-muted">Upload multiple files. Allowed: JPG, PNG, PDF.</small>
                  </div>

                  <?php if($mode==='edit'): ?>
                    <div class="mt-2">
                      <label>Existing Attachments</label>
                      <?php if(!empty($files)): ?>
                        <div class="row">
                          <?php foreach($files as $f): ?>
                            <?php $isImg = preg_match('/\.(jpg|jpeg|png)$/i', (string)$f->file_path); ?>
                            <div class="col-md-4 mb-3">
                              <div class="border rounded p-2">
                                <?php if($isImg): ?>
                                  <a href="<?= base_url($f->file_path); ?>" target="_blank">
                                    <img src="<?= base_url($f->file_path); ?>" style="width:100%;height:160px;object-fit:cover;">
                                  </a>
                                <?php else: ?>
                                  <div style="height:160px;display:flex;align-items:center;">
                                    <div>
                                      <i class="mdi mdi-file-pdf-box" style="font-size:44px;"></i><br>
                                      <a href="<?= base_url($f->file_path); ?>" target="_blank">Open PDF</a>
                                    </div>
                                  </div>
                                <?php endif; ?>

                                <small class="text-muted d-block mt-2">
                                  <?= htmlspecialchars($f->original_name ?: $f->file_path); ?>
                                </small>
                              </div>
                            </div>
                          <?php endforeach; ?>
                        </div>
                      <?php else: ?>
                        <div class="text-muted">No attachments yet.</div>
                      <?php endif; ?>
                    </div>
                  <?php endif; ?>

                  <?php if($mode==='edit'): ?>
                    <hr>
                    <div class="form-group">
                      <label>Status</label>
                      <input type="text" name="status" class="form-control"
                             value="<?= set_value('status', $row->status); ?>">
                    </div>
                  <?php endif; ?>

                  <button type="submit" class="btn btn-info">
                    <i class="mdi mdi-content-save"></i> <?= ($mode==='edit') ? 'Update' : 'Save'; ?>
                  </button>

                </form>

              </div>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>

<?php include('templates/footer.php'); ?>

<script>
$(function(){

  function staffSelect2($el){
    $el.select2({
      width: '100%',
      placeholder: 'Search staff by name or ID...',
      allowClear: true,
      ajax: {
        url: "<?= base_url('Research/ajax_staff'); ?>",
        dataType: 'json',
        delay: 250,
        data: function(params){ return { q: params.term || '' }; },
        processResults: function(data){ return data; }
      }
    });
  }

  staffSelect2($('#member_ids'));

  function syncMode(){
    var mode = $('#author_mode').val();

    if(mode === 'single'){
      // hide members, clear selection
      $('#members_wrap').hide();
      $('#member_ids').val(null).trigger('change');
    } else {
      // show members
      $('#members_wrap').show();
    }
  }

  $('#author_mode').on('change', syncMode);
  syncMode();

  // preload members on edit
  <?php if(!empty($members)): ?>
    <?php foreach($members as $m): ?>
      $('#member_ids').append(new Option("Selected (<?= htmlspecialchars($m->IDNumber, ENT_QUOTES); ?>)", "<?= htmlspecialchars($m->IDNumber, ENT_QUOTES); ?>", true, true));
    <?php endforeach; ?>
    $('#member_ids').trigger('change');
  <?php endif; ?>

});
</script>
