<?php
$esc = function ($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$school = isset($school) ? $school : null;
$accountEmail = trim((string) ($accountEmail ?? ''));
$districts = isset($districts) ? (array) $districts : array();
$addressRows = isset($addressRows) ? (array) $addressRows : array();
$selectedEducationLevels = $school ? array_filter(array_map('trim', explode(',', (string) ($school->educational_levels_offered ?? '')))) : array();
$recognitionForm = array(
    'permitNo' => $school->permitNo ?? '', 'permit_issued_date' => $school->permit_issued_date ?? '', 'permit_expiry_date' => $school->permit_expiry_date ?? '',
    'permit_issuing_office' => $school->permit_issuing_office ?? '', 'permit_status' => $school->permit_status ?? '',
    'peac_member' => !empty($school->peac_member), 'esc_recipient' => !empty($school->esc_recipient), 'voucher_program' => !empty($school->voucher_program),
    'shs_tracks_offered' => $school ? array_filter(array_map('trim', explode(',', (string) ($school->shs_tracks_offered ?? '')))) : array(),
    'government_recognition_details' => $school->government_recognition_details ?? ''
);
?>
<style>
html, body { height:auto !important; min-height:100% !important; overflow-y:auto !important; }
#wrapper { height:auto !important; min-height:100vh !important; overflow:hidden !important; }
.content-page, .content { min-height:100vh !important; height:auto !important; overflow:visible !important; }
.content-page { padding-bottom:24px !important; }
.content-page > .footer { position:static !important; left:auto !important; right:auto !important; width:auto !important; }
.profile-shell.container-fluid { width:100% !important; max-width:none !important; padding:20px 15px 28px !important; }
.profile-card { border-radius:0 0 22px 22px !important; box-shadow:0 16px 38px rgba(15,23,42,.08) !important; }
.profile-head { border-radius:22px 22px 0 0 !important; padding:30px !important; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('form[action$="school_profile_update"]');
    var actions = form && form.querySelector('.text-right');
    if (!actions) return;
    var selected = <?= json_encode(array_values($selectedEducationLevels)); ?>;
    var levels = ['Preschool', 'Kindergarten', 'Elementary', 'Junior High School', 'Senior High School', 'College', 'Graduate School', 'TVET'];
    var fieldset = document.createElement('fieldset');
    fieldset.className = 'border rounded p-3 mb-4';
    fieldset.innerHTML = '<legend class="w-auto px-2 mb-0" style="font-size:.85rem;font-weight:800;color:#272b8c">Educational Levels Offered</legend><small class="d-block mb-2 text-muted">Select all levels offered by the school.</small>';
    levels.forEach(function (level) {
        var id = 'education-level-' + level.toLowerCase().replace(/[^a-z0-9]+/g, '-');
        var item = document.createElement('div'); item.className = 'custom-control custom-checkbox custom-control-inline mb-2';
        item.innerHTML = '<input class="custom-control-input" type="checkbox" id="' + id + '" name="educational_levels_offered[]" value="' + level + '"><label class="custom-control-label" for="' + id + '">' + level + '</label>';
        item.querySelector('input').checked = selected.indexOf(level) !== -1;
        fieldset.appendChild(item);
    });
    actions.parentNode.insertBefore(fieldset, actions);
    var recognition = <?= json_encode($recognitionForm); ?>;
    var recognitionFieldset = document.createElement('fieldset');
    recognitionFieldset.className = 'border rounded p-3 mb-4';
    recognitionFieldset.innerHTML = '<legend class="w-auto px-2 mb-0" style="font-size:.85rem;font-weight:800;color:#272b8c">Government Recognition and Programs</legend>';
    function inputField(labelText, name, type) {
        var group = document.createElement('div'); group.className = 'form-group col-md-6';
        var label = document.createElement('label'); label.textContent = labelText;
        var input = document.createElement('input'); input.className = 'form-control'; input.name = name; input.type = type || 'text'; input.value = recognition[name] || '';
        group.appendChild(label); group.appendChild(input); return group;
    }
    var permitRow = document.createElement('div'); permitRow.className = 'form-row';
    permitRow.appendChild(inputField('Permit Number', 'permitNo'));
    permitRow.appendChild(inputField('Permit Issuing Office', 'permit_issuing_office'));
    recognitionFieldset.appendChild(permitRow);
    var datesRow = document.createElement('div'); datesRow.className = 'form-row';
    datesRow.appendChild(inputField('Permit Issued Date', 'permit_issued_date', 'date'));
    datesRow.appendChild(inputField('Permit Expiry Date', 'permit_expiry_date', 'date'));
    recognitionFieldset.appendChild(datesRow);
    var statusRow = document.createElement('div'); statusRow.className = 'form-row';
    statusRow.appendChild(inputField('Permit Status', 'permit_status'));
    recognitionFieldset.appendChild(statusRow);
    [['peac_member', 'PEAC Member'], ['esc_recipient', 'ESC Recipient'], ['voucher_program', 'Voucher Program Recipient']].forEach(function (item) {
        var wrapper = document.createElement('div'); wrapper.className = 'custom-control custom-checkbox custom-control-inline mb-3';
        var checkbox = document.createElement('input'); checkbox.className = 'custom-control-input'; checkbox.type = 'checkbox'; checkbox.id = item[0]; checkbox.name = item[0]; checkbox.value = '1'; checkbox.checked = !!recognition[item[0]];
        var label = document.createElement('label'); label.className = 'custom-control-label'; label.htmlFor = item[0]; label.textContent = item[1];
        wrapper.appendChild(checkbox); wrapper.appendChild(label); recognitionFieldset.appendChild(wrapper);
    });
    var tracksLabel = document.createElement('label'); tracksLabel.className = 'd-block font-weight-bold'; tracksLabel.textContent = 'SHS Tracks Offered'; recognitionFieldset.appendChild(tracksLabel);
    ['Academic Track', 'Technical-Professional (Tech-Pro) Track'].forEach(function (track) {
        var wrapper = document.createElement('div'); wrapper.className = 'custom-control custom-checkbox custom-control-inline mb-2';
        var checkbox = document.createElement('input'); checkbox.className = 'custom-control-input'; checkbox.type = 'checkbox'; checkbox.id = 'track-' + track.replace(/[^a-z]/gi, '').toLowerCase(); checkbox.name = 'shs_tracks_offered[]'; checkbox.value = track; checkbox.checked = (recognition.shs_tracks_offered || []).indexOf(track) !== -1;
        var label = document.createElement('label'); label.className = 'custom-control-label'; label.htmlFor = checkbox.id; label.textContent = track;
        wrapper.appendChild(checkbox); wrapper.appendChild(label); recognitionFieldset.appendChild(wrapper);
    });
    var detailsGroup = document.createElement('div'); detailsGroup.className = 'form-group mt-2 mb-0';
    var detailsLabel = document.createElement('label'); detailsLabel.textContent = 'Other Government Recognition Details';
    var details = document.createElement('textarea'); details.className = 'form-control'; details.name = 'government_recognition_details'; details.rows = 3; details.value = recognition.government_recognition_details || '';
    detailsGroup.appendChild(detailsLabel); detailsGroup.appendChild(details); recognitionFieldset.appendChild(detailsGroup);
    actions.parentNode.insertBefore(recognitionFieldset, actions);
    var profileValues = <?= json_encode($school ? get_object_vars($school) : array()); ?>;
    function profileInput(labelText, name, type, fallback) {
        var group = document.createElement('div'); group.className = 'form-group col-md-6';
        var label = document.createElement('label'); label.textContent = labelText;
        var input = document.createElement('input'); input.className = 'form-control'; input.name = name; input.type = type || 'text'; input.value = profileValues[name] || fallback || '';
        group.appendChild(label); group.appendChild(input); return group;
    }
    var schoolHeadContactRow = form.querySelector('[name="adminMobile"]').closest('.form-row');
    if (schoolHeadContactRow) {
        var mobileField = form.querySelector('[name="adminMobile"]').closest('.form-group');
        var telephoneField = form.querySelector('[name="adminTel"]').closest('.form-group');
        var schoolHeadEmail = profileInput('School Head Email', 'adminEmail', 'email');
        mobileField.className = telephoneField.className = schoolHeadEmail.className = 'form-group col-md-4';
        schoolHeadContactRow.appendChild(schoolHeadEmail);
    }

    var ownership = document.createElement('fieldset'); ownership.className = 'border rounded p-3 mb-4';
    ownership.innerHTML = '<legend class="w-auto px-2 mb-0" style="font-size:.85rem;font-weight:800;color:#272b8c">School Ownership and Leadership Contacts</legend>';
    [
        ['Owner', 'ownerName', 'ownerEmail', 'ownerContactNo'], ['President', 'presidentName', 'presidentEmail', 'presidentContactNo'],
        ['Board Chairperson', 'boardChairperson', 'boardChairpersonEmail', 'boardChairpersonContactNo'], ['Corporate Secretary', 'corporateSecretary', 'corporateSecretaryEmail', 'corporateSecretaryContactNo'],
        ['School Administrator', 'schoolAdministrator', 'schoolAdministratorEmail', 'schoolAdministratorContactNo'], ['Principal', 'principalName', 'principalEmail', 'principalContactNo']
    ].forEach(function (person) {
        var title = document.createElement('div'); title.className = 'w-100 mt-2 mb-1 font-weight-bold text-primary'; title.textContent = person[0]; ownership.appendChild(title);
        var row = document.createElement('div'); row.className = 'form-row';
        var nameField = profileInput(person[0] + ' Name', person[1]);
        var emailField = profileInput('Email', person[2], 'email');
        var contactField = profileInput('Contact Number', person[3]);
        nameField.className = emailField.className = contactField.className = 'form-group col-md-4';
        row.appendChild(nameField); row.appendChild(emailField); row.appendChild(contactField); ownership.appendChild(row);
    });
    actions.parentNode.insertBefore(ownership, actions);

});
</script>
<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Edit School Profile</title><link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet"><link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet"><link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet"><link href="<?= base_url(); ?>assets/css/dashboard-unified.css" rel="stylesheet"><style>body{background:#f4f8fc}.profile-shell{max-width:960px;padding:30px 15px}.profile-card{border:0;border-radius:20px;box-shadow:0 16px 38px rgba(15,23,42,.08)}.profile-head{padding:26px 28px;color:#fff;background:linear-gradient(135deg,#272b8c,#3c40c6);border-radius:20px 20px 0 0}.profile-head h1{margin:0;color:#fff;font-size:1.7rem}.profile-head p{margin:7px 0 0;color:rgba(255,255,255,.8)}.profile-card .card-body{padding:28px}.form-section{margin:24px 0 12px;color:#272b8c;font-size:.85rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase}.form-group label{font-weight:700;color:#4b5572}.form-control{border-radius:9px}.read-only{background:#f4f6fb}</style></head><body class="dashboard-root-theme"><div id="wrapper"><?php include(__DIR__ . '/includes/top-bar.php'); ?><?php include(__DIR__ . '/includes/sidebar.php'); ?><div class="content-page"><div class="content"><main class="container-fluid dashboard-shell profile-shell"><div class="card profile-card"><div class="profile-head"><a class="btn btn-sm btn-light mb-3" href="<?= base_url(); ?>Page/School">← Back to Dashboard</a><h1>Edit School Profile</h1><p>Keep your school contact and profile details current.</p></div><div class="card-body"><?php if(!$school): ?><div class="alert alert-warning mb-0">Your school profile could not be found.</div><?php else: ?><form method="post" action="<?= base_url(); ?>Page/school_profile_update"><div class="form-section">School Information</div><div class="form-row"><div class="form-group col-md-4"><label>School ID</label><input class="form-control read-only" value="<?= $esc($school->schoolID ?? ''); ?>" readonly></div><div class="form-group col-md-8"><label>School Name</label><input class="form-control" name="schoolName" value="<?= $esc($school->schoolName ?? ''); ?>" required></div></div><div class="form-row"><div class="form-group col-md-6"><label>School Type</label><input class="form-control" name="schoolType" value="<?= $esc($school->schoolType ?? '') ?>"></div></div><div class="form-row"><div class="form-group col-md-6"><label>School Email</label><input class="form-control" type="email" name="schoolEmail" value="<?= $esc($accountEmail !== '' ? $accountEmail : ($school->schoolEmail ?? '')); ?>"></div><div class="form-group col-md-6"><label>School Head Designation</label><input class="form-control" name="adminDesignation" value="<?= $esc($school->adminDesignation ?? ''); ?>"></div></div><div class="form-section">Address</div><div class="form-row"><div class="form-group col-md-6"><label>District</label><select class="form-control" name="district"><option value="">Select District</option><?php foreach($districts as $districtRow): ?><?php $districtName = trim((string) ($districtRow->district ?? '')); ?><option value="<?= $esc($districtName); ?>"<?= $districtName === trim((string) ($school->district ?? '')) ? ' selected' : ''; ?>><?= $esc($districtName); ?></option><?php endforeach; ?></select></div><div class="form-group col-md-6"><label>Province</label><select class="form-control" name="province" id="provinceSelect" data-current="<?= $esc($school->province ?? ''); ?>"><option value="">Select Province</option></select></div></div><div class="form-row"><div class="form-group col-md-6"><label>City / Municipality</label><select class="form-control" name="city" id="municipalitySelect" data-current="<?= $esc($school->city ?? ''); ?>" disabled><option value="">Select City / Municipality</option></select></div><div class="form-group col-md-6"><label>Barangay</label><select class="form-control" name="brgy" id="barangaySelect" data-current="<?= $esc($school->brgy ?? ''); ?>" disabled><option value="">Select Barangay</option></select></div></div><div class="form-row"><div class="form-group col-md-6"><label>Sitio</label><input class="form-control" name="sitio" value="<?= $esc($school->sitio ?? ''); ?>"></div></div><div class="form-section">School Head Contact</div><div class="form-row"><div class="form-group col-md-4"><label>First Name</label><input class="form-control" name="adminFName" value="<?= $esc($school->adminFName ?? ''); ?>"></div><div class="form-group col-md-4"><label>Middle Name</label><input class="form-control" name="adminMName" value="<?= $esc($school->adminMName ?? ''); ?>"></div><div class="form-group col-md-4"><label>Last Name</label><input class="form-control" name="adminLName" value="<?= $esc($school->adminLName ?? ''); ?>"></div></div><div class="form-row"><div class="form-group col-md-6"><label>Mobile Number</label><input class="form-control" name="adminMobile" value="<?= $esc($school->adminMobile ?? ''); ?>"></div><div class="form-group col-md-6"><label>Telephone Number</label><input class="form-control" name="adminTel" value="<?= $esc($school->adminTel ?? ''); ?>"></div></div><div class="text-right"><a class="btn btn-light" href="<?= base_url(); ?>Page/School">Cancel</a><button class="btn btn-primary" type="submit">Save Profile</button></div></form><?php endif; ?></div></div></main></div><?php include(__DIR__ . '/includes/footer.php'); ?></div></div><script src="<?= base_url(); ?>assets/js/vendor.min.js"></script><script src="<?= base_url(); ?>assets/js/app.min.js"></script><script>document.addEventListener('DOMContentLoaded',function(){var rows=<?= json_encode($addressRows); ?>,province=document.getElementById('provinceSelect'),municipality=document.getElementById('municipalitySelect'),barangay=document.getElementById('barangaySelect');if(!province)return;function value(row,names){var keys=Object.keys(row);for(var i=0;i<keys.length;i++){var key=keys[i].toLowerCase().replace(/[^a-z]/g,'');if(names.indexOf(key)!==-1&&row[keys[i]]!==null)return String(row[keys[i]]).trim()}return ''}function unique(values){return values.filter(function(item,index){return item!==''&&values.indexOf(item)===index}).sort()}function options(select,values,placeholder,current){select.innerHTML='<option value="">'+placeholder+'</option>';values.forEach(function(item){var option=document.createElement('option');option.value=item;option.textContent=item;if(item===current)option.selected=true;select.appendChild(option)});select.disabled=values.length===0}var provinceNames=['province','provincename','provincecode','prov'],municipalityNames=['municipality','municipalityname','city','citymunicipality','citymunicipalityname'],barangayNames=['barangay','barangayname','brgy'];var currentProvince=province.dataset.current||'',currentMunicipality=municipality.dataset.current||'',currentBarangay=barangay.dataset.current||'';var provinces=unique(rows.map(function(row){return value(row,provinceNames)}));if(currentProvince&&provinces.indexOf(currentProvince)===-1)provinces.push(currentProvince);options(province,provinces,'Select Province',currentProvince);function loadMunicipalities(selected){var list=unique(rows.filter(function(row){return value(row,provinceNames)===province.value}).map(function(row){return value(row,municipalityNames)}));if(selected&&list.indexOf(selected)===-1)list.push(selected);options(municipality,list,'Select City / Municipality',selected||'');loadBarangays(currentBarangay)}function loadBarangays(selected){var list=unique(rows.filter(function(row){return value(row,provinceNames)===province.value&&value(row,municipalityNames)===municipality.value}).map(function(row){return value(row,barangayNames)}));if(selected&&list.indexOf(selected)===-1)list.push(selected);options(barangay,list,'Select Barangay',selected||'')}province.addEventListener('change',function(){currentBarangay='';loadMunicipalities('')});municipality.addEventListener('change',function(){loadBarangays('')});loadMunicipalities(currentMunicipality)});</script></body></html>
