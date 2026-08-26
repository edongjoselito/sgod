<?php
$record = isset($record) ? $record : null;
if (!$record) { show_404(); return; }
$input = function($name, $fallback = '') {
    $posted = $this->input->post($name);
    return $posted !== NULL ? (string) $posted : (string) $fallback;
};
$isIsoDate = function($value) { return preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $value); };
$fromDate = $input('activityDateFrom', $isIsoDate($record->activityDateFrom) ? $record->activityDateFrom : ($isIsoDate($record->targetDate) ? $record->targetDate : ''));
$toDate = $input('activityDateTo', $isIsoDate($record->activityDateTo) ? $record->activityDateTo : ($isIsoDate($record->targetDate) ? $record->targetDate : ''));
$postedScope = $this->input->post('accomplishmentScope');
$scopeValues = $postedScope === NULL ? array($record->accomplishmentScope ?: 'section') : (is_array($postedScope) ? $postedScope : array($postedScope));
$scopeValues = array_map(function($scope) { return strtolower(trim((string) $scope)); }, $scopeValues);
$sectionScopeSelected = in_array('section', $scopeValues, TRUE) || in_array('both', $scopeValues, TRUE);
$personalScopeSelected = in_array('personal', $scopeValues, TRUE) || in_array('both', $scopeValues, TRUE);
$kraId = (int) $input('kra_id', $record->kra_id);
$objectiveId = (int) $input('objective_id', $record->objective_id);
$uploadError = isset($uploadError) ? trim((string) $uploadError) : '';
$additionalPhotos = isset($additionalPhotos) && is_array($additionalPhotos) ? $additionalPhotos : array();
$reports = isset($reports) && is_array($reports) ? $reports : array();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include('includes/page-title.php'); ?>
    <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/libs/summernote/summernote-bs4.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(180deg,#f4f7fc,#eef3fb); }
        .edit-shell { max-width: 1180px; padding: 25px 15px 45px; }
        .edit-hero { padding: 30px; border-radius: 24px 24px 0 0; color: #fff; background: linear-gradient(135deg,#272b8c,#3c40c6 58%,#6f74ff); }
        .edit-hero h1 { margin: 8px 0 0; color: #fff; font-size: 1.75rem; }
        .edit-card { border: 0; border-radius: 0 0 24px 24px; box-shadow: 0 18px 42px rgba(39,43,140,.12); }
        .section-block { padding: 25px 30px; border-bottom: 1px solid #edf0f7; }
        .section-title { margin-bottom: 18px; color: #272b8c; font-size: .9rem; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; }
        .field-grid { display: grid; grid-template-columns: repeat(12,minmax(0,1fr)); gap: 17px; }
        .span-12 { grid-column: span 12; } .span-6 { grid-column: span 6; } .span-4 { grid-column: span 4; }
        .field-label { display: block; margin-bottom: 7px; color: #343957; font-size: .84rem; font-weight: 700; }
        .field-input,.field-textarea { width: 100%; padding: 10px 12px; border: 1px solid #dce1ed; border-radius: 8px; color: #343957; background: #fff; }
        .field-textarea { min-height: 95px; resize: vertical; } .required { color: #d64545; }
        .field-help { display: block; margin-top: 6px; color: #77809b; font-size: .78rem; }
        .photo-preview { display: block; max-width: 270px; max-height: 160px; margin-top: 10px; border-radius: 9px; object-fit: cover; }
        .remove-photo-option { display: inline-flex; align-items: center; gap: 6px; margin-top: 10px; color: #a12b2b; font-size: .82rem; font-weight: 700; }
        .photo-drop-zone { padding: 22px; border: 2px dashed #dce1ed; border-radius: 12px; color: #55607b; text-align: center; background: #f8f9ff; transition: .2s ease; }
        .photo-drop-zone.is-dragging { border-color: #3c40c6; background: #eef0ff; }
        .photo-drop-zone input { margin-top: 10px; }
        .photo-selection { margin-top: 9px; color: #77809b; font-size: .82rem; }
        .additional-photo-list { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 12px; }
        .additional-photo-item { position: relative; width: 92px; }
        .additional-photo-list a { display: block; width: 92px; height: 72px; overflow: hidden; border: 1px solid #dce1ed; border-radius: 8px; background: #f4f6fb; }
        .additional-photo-list img { display: block; width: 100%; height: 100%; object-fit: cover; }
        .additional-photo-item label { display: block; margin-top: 4px; color: #a12b2b; font-size: .68rem; font-weight: 700; text-align: center; }
        .form-actions { display: flex; justify-content: space-between; gap: 14px; padding: 23px 30px; }
        .btn-form { border: 0; border-radius: 9px; padding: 11px 18px; font-weight: 700; text-decoration: none; }
        .btn-cancel { color: #343957; background: #eef1f8; } .btn-save { color: #fff; background: #3c40c6; }
        .note-editor.note-frame { border-color: #dce1ed; border-radius: 8px; }
        @media(max-width:700px){.section-block{padding:21px}.span-6,.span-4{grid-column:span 12}.form-actions{padding:21px;flex-direction:column-reverse}.btn-form{text-align:center}}
    </style>
</head>
<body>
<div id="wrapper">
    <?php include('includes/top-bar.php'); include('includes/sidebar.php'); ?>
    <div class="content-page"><div class="content"><main class="container-fluid edit-shell">
        <section class="edit-hero"><div>Update entry</div><h1>Edit Accomplishment</h1></section>
        <section class="card edit-card">
            <?php if ($uploadError !== '') : ?><div class="alert alert-warning m-3 mb-0"><?= htmlspecialchars($uploadError,ENT_QUOTES,'UTF-8'); ?></div><?php endif; ?>
            <form method="post" action="<?= base_url(); ?>Page/updateAccomplishments?id=<?= (int) $record->id; ?>" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= (int) $record->id; ?>">
                <input type="hidden" name="activityCategory" value="<?= htmlspecialchars($input('activityCategory',$record->activityCategory ?: 'Accomplishment'),ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="perIndicators" value="<?= htmlspecialchars($input('perIndicators',$record->perIndicators),ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="target" value="<?= htmlspecialchars($input('target',$record->target),ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="achieved" value="<?= htmlspecialchars($input('achieved',$record->achieved),ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="percentageAccom" value="<?= htmlspecialchars($input('percentageAccom',$record->percentageAccom),ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="remarks" value="<?= htmlspecialchars($input('remarks',$record->remarks),ENT_QUOTES,'UTF-8'); ?>">
                <div class="section-block">
                    <div class="section-title"><i class="mdi mdi-calendar-range"></i> Activity Date</div>
                    <div class="field-grid">
                        <div class="span-4"><span class="field-label">Scope <span class="required">*</span></span><div class="d-flex flex-wrap" style="gap:16px;padding:10px 0;"><label class="mb-0"><input type="checkbox" name="accomplishmentScope[]" value="section" <?= $sectionScopeSelected?'checked':''; ?>> Section Accomplishments</label><label class="mb-0"><input type="checkbox" name="accomplishmentScope[]" value="personal" <?= $personalScopeSelected?'checked':''; ?>> Personal Accomplishments</label></div><span class="field-help">Select one or both scopes.</span></div>
                        <div class="span-4"><label class="field-label" for="activityDateFrom">From</label><input class="field-input" type="date" id="activityDateFrom" name="activityDateFrom" value="<?= htmlspecialchars($fromDate,ENT_QUOTES,'UTF-8'); ?>"></div>
                        <div class="span-4"><label class="field-label" for="activityDateTo">To</label><input class="field-input" type="date" id="activityDateTo" name="activityDateTo" value="<?= htmlspecialchars($toDate,ENT_QUOTES,'UTF-8'); ?>"></div>
                    </div>
                </div>
                <div class="section-block">
                    <div class="section-title"><i class="mdi mdi-clipboard-text-outline"></i> Activity Details / Accomplishment Details</div>
                    <div class="field-grid">
                        <div class="span-12"><label class="field-label" for="activity">Activity/Accomplishment Title <span class="required">*</span></label><input class="field-input" id="activity" name="activity" value="<?= htmlspecialchars($input('activity',$record->activity),ENT_QUOTES,'UTF-8'); ?>" required></div>
                        <div class="span-12"><label class="field-label" for="particulars">Activity/Accomplishment Details</label><textarea class="field-textarea" id="particulars" name="particulars"><?= htmlspecialchars($input('particulars',$record->particulars),ENT_QUOTES,'UTF-8'); ?></textarea></div>
                        <div class="span-12"><label class="field-label" for="venue">Venue</label><input class="field-input" id="venue" name="venue" value="<?= htmlspecialchars($input('venue',$record->venue),ENT_QUOTES,'UTF-8'); ?>"></div>
                        <div class="span-6"><label class="field-label" for="kra_id">KRA</label><select class="field-input" id="kra_id" name="kra_id"><option value="">-- Select KRA --</option><option value="0" <?= $kraId===0?'selected':''; ?>>Not Related</option><?php foreach($kraOptions as $kra) : ?><option value="<?= (int)$kra->id; ?>" <?= $kraId===(int)$kra->id?'selected':''; ?>><?= htmlspecialchars($kra->title,ENT_QUOTES,'UTF-8'); ?></option><?php endforeach; ?></select></div>
                        <div class="span-6"><label class="field-label" for="objective_id">Objective</label><select class="field-input" id="objective_id" name="objective_id"><option value="">-- Select Objective --</option><option value="0" <?= $objectiveId===0?'selected':''; ?>>Not Related</option><?php foreach($objectiveOptions as $objective) : ?><option value="<?= (int)$objective->id; ?>" data-kra="<?= (int)$objective->template_kra_id; ?>" <?= $objectiveId===(int)$objective->id?'selected':''; ?>><?= htmlspecialchars($objective->code.' - '.$objective->objective,ENT_QUOTES,'UTF-8'); ?></option><?php endforeach; ?></select></div>
                    </div>
                </div>
                <div class="section-block">
                    <div class="section-title"><i class="mdi mdi-link-variant"></i> Supporting Notes</div>
                    <div class="field-grid">
                        <div class="span-12"><label class="field-label" for="resources">Resources Link</label><textarea class="field-textarea" id="resources" name="resources"><?= htmlspecialchars($input('resources',$record->resources),ENT_QUOTES,'UTF-8'); ?></textarea></div>
                        <div class="span-12"><label class="field-label" for="notes">Additional Notes</label><textarea class="field-textarea" id="notes" name="notes"><?= htmlspecialchars($input('notes',$record->notes),ENT_QUOTES,'UTF-8'); ?></textarea></div>
                    </div>
                </div>
                <div class="section-block">
                    <div class="section-title"><i class="mdi mdi-image-outline"></i> Featured Photo</div>
                    <div class="field-grid">
                        <div class="span-12"><label class="field-label" for="featured_photo">Upload Photo</label><input class="field-input" type="file" id="featured_photo" name="featured_photo" accept="image/jpeg,image/png,image/gif"><span class="field-help">Optional. JPG, PNG, or GIF image up to 5 MB. Uploading a new image replaces the current featured photo.</span><?php if (!empty($record->featured_photo)) : ?><img class="photo-preview" src="<?= base_url(); ?>upload/accomplishment_featured_photos/<?= rawurlencode($record->featured_photo); ?>" alt="Current featured photo"><label class="remove-photo-option"><input type="checkbox" name="remove_featured_photo" value="1"> Remove current featured photo</label><?php endif; ?></div>
                        <div class="span-12"><label class="field-label" for="additional_photos">Additional Photos</label><div class="photo-drop-zone" id="additionalPhotoDropZone"><i class="mdi mdi-image-multiple-outline" style="font-size:2rem;color:#3c40c6;"></i><div>Drag multiple photos here, or choose files.</div><input class="field-input" type="file" id="additional_photos" name="additional_photos[]" accept="image/jpeg,image/png,image/gif" multiple><div class="photo-selection" id="additionalPhotoSelection">No additional photos selected.</div></div><span class="field-help">Optional. Add multiple JPG, PNG, or GIF photos (up to 5 MB each). Existing photos are retained unless marked for removal below.</span><?php if (!empty($additionalPhotos)) : ?><div class="additional-photo-list"><?php foreach ($additionalPhotos as $photo) : ?><div class="additional-photo-item"><a href="<?= base_url(); ?>upload/accomplishment_additional_photos/<?= rawurlencode($photo->file_name); ?>" target="_blank" rel="noopener"><img src="<?= base_url(); ?>upload/accomplishment_additional_photos/<?= rawurlencode($photo->file_name); ?>" alt="Existing additional photo"></a><label><input type="checkbox" name="removeAdditionalPhotos[]" value="<?= (int) $photo->id; ?>"> Remove</label></div><?php endforeach; ?></div><?php endif; ?></div>
                    </div>
                </div>
                <div class="section-block">
                    <div class="section-title"><i class="mdi mdi-file-pdf-outline"></i> Signed Accomplishment Report</div>
                    <div class="field-grid">
                        <div class="span-6"><label class="field-label" for="signed_report_name">Document Name</label><input class="field-input" type="text" id="signed_report_name" name="signed_report_name" value="<?= htmlspecialchars($input('signed_report_name',''),ENT_QUOTES,'UTF-8'); ?>" placeholder="Signed Accomplishment Report"></div>
                        <div class="span-6"><label class="field-label" for="signed_report">Attach Signed Report (PDF)</label><input class="field-input" type="file" id="signed_report" name="signed_report" accept="application/pdf,.pdf"><span class="field-help">Optional. PDF only, up to 15 MB. New uploads are added while existing reports are retained.</span></div>
                        <?php if (!empty($reports)) : ?><div class="span-12"><span class="field-label">Attached Reports</span><?php foreach ($reports as $report) : ?><a class="record-link-button" target="_blank" rel="noopener" href="<?= base_url(); ?>upload/accomplishment_reports/<?= rawurlencode($report->stored_name); ?>"><i class="mdi mdi-file-pdf-outline"></i><?= htmlspecialchars($report->document_name ?: $report->original_name, ENT_QUOTES, 'UTF-8'); ?></a><?php endforeach; ?></div><?php endif; ?>
                    </div>
                </div>
                <div class="form-actions"><a class="btn-form btn-cancel" href="<?= base_url(); ?>Page/viewSecAccomplishments">Cancel</a><button class="btn-form btn-save" type="submit" name="update" value="1"><i class="mdi mdi-content-save-outline"></i> Update Accomplishment</button></div>
            </form>
        </section>
    </main></div><?php include('includes/footer.php'); ?></div>
</div>
<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/summernote/summernote-bs4.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
<script>
(function($){
    $('#particulars').summernote({height:220,toolbar:[['style',['style']],['font',['bold','italic','underline','clear']],['para',['ul','ol','paragraph']],['insert',['link']],['view',['codeview']]]});
    var kra=$('#kra_id'), objective=$('#objective_id'), options=objective.find('option[data-kra]').clone();
    function filterObjectives(kraId){var selected=objective.val();objective.empty().append('<option value="">-- Select Objective --</option>').append('<option value="0">Not Related</option>');options.each(function(){var option=$(this);if(String(kraId)!=='0'&&(!kraId||String(option.data('kra'))===String(kraId)))objective.append(option.clone());});objective.val(objective.find('option[value="'+selected+'"]').length?selected:(String(kraId)==='0'?'0':''));}
    kra.on('change',function(){filterObjectives($(this).val());}); filterObjectives(kra.val());
    var additionalInput=document.getElementById('additional_photos'), dropZone=document.getElementById('additionalPhotoDropZone'), selection=document.getElementById('additionalPhotoSelection');
    function showAdditionalPhotoCount(files){if(selection)selection.textContent=files&&files.length?files.length+' additional photo'+(files.length===1?'':'s')+' selected.':'No additional photos selected.';}
    if(additionalInput&&dropZone){additionalInput.addEventListener('change',function(){showAdditionalPhotoCount(this.files);});['dragenter','dragover'].forEach(function(eventName){dropZone.addEventListener(eventName,function(event){event.preventDefault();dropZone.classList.add('is-dragging');});});['dragleave','drop'].forEach(function(eventName){dropZone.addEventListener(eventName,function(event){event.preventDefault();dropZone.classList.remove('is-dragging');});});dropZone.addEventListener('drop',function(event){if(event.dataTransfer.files.length){additionalInput.files=event.dataTransfer.files;showAdditionalPhotoCount(additionalInput.files);}});}
})(jQuery);
</script>
</body></html>
