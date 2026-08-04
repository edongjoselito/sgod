<?php
$esc = function ($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); };
$school = !empty($data) ? $data[0] : null;
$value = function ($field, $fallback = 'Not specified') use ($school, $esc) {
    $item = $school ? trim((string) ($school->{$field} ?? '')) : '';
    return $esc($item !== '' ? $item : $fallback);
};
$schoolId = $school ? trim((string) ($school->schoolID ?? '')) : '';
$canEdit = $this->session->userdata('section') === 'School' && $schoolId !== '' && $schoolId === (string) $this->session->userdata('username');
$schoolHead = $school ? trim(implode(' ', array_filter(array($school->adminFName ?? '', $school->adminMName ?? '', $school->adminLName ?? '')))) : '';
$educationalLevels = $school ? array_filter(array_map('trim', explode(',', (string) ($school->educational_levels_offered ?? '')))) : array();
$schoolField = function (array $fields, $fallback = 'Not specified') use ($school) {
    foreach ($fields as $field) {
        if ($school && isset($school->{$field}) && trim((string) $school->{$field}) !== '') {
            return trim((string) $school->{$field});
        }
    }
    return $fallback;
};
$ownershipContacts = array(
    array('role' => 'Owner Name', 'name' => $schoolField(array('ownerName', 'schoolOwner', 'owner')), 'email' => $schoolField(array('ownerEmail', 'schoolOwnerEmail')), 'phone' => $schoolField(array('ownerContactNo', 'ownerContactNumber', 'ownerMobile', 'ownerPhone'))),
    array('role' => 'President', 'name' => $schoolField(array('presidentName', 'president')), 'email' => $schoolField(array('presidentEmail')), 'phone' => $schoolField(array('presidentContactNo', 'presidentContactNumber', 'presidentMobile', 'presidentPhone'))),
    array('role' => 'Board Chairperson', 'name' => $schoolField(array('boardChairperson', 'boardChairman', 'boardChairName')), 'email' => $schoolField(array('boardChairpersonEmail', 'boardChairEmail')), 'phone' => $schoolField(array('boardChairpersonContactNo', 'boardChairContactNo', 'boardChairMobile', 'boardChairPhone'))),
    array('role' => 'Corporate Secretary', 'name' => $schoolField(array('corporateSecretary', 'corporateSecretaryName')), 'email' => $schoolField(array('corporateSecretaryEmail')), 'phone' => $schoolField(array('corporateSecretaryContactNo', 'corporateSecretaryContactNumber', 'corporateSecretaryMobile', 'corporateSecretaryPhone'))),
    array('role' => 'School Head', 'name' => $schoolHead !== '' ? $schoolHead : 'Not specified', 'email' => $schoolField(array('adminEmail', 'schoolHeadEmail', 'schoolEmail')), 'phone' => $schoolField(array('adminMobile', 'adminTel', 'schoolHeadContactNo', 'schoolHeadMobile'))),
    array('role' => 'School Administrator', 'name' => $schoolField(array('schoolAdministrator', 'schoolAdministratorName', 'administratorName', 'administrator')), 'email' => $schoolField(array('schoolAdministratorEmail', 'administratorEmail')), 'phone' => $schoolField(array('schoolAdministratorContactNo', 'schoolAdministratorContactNumber', 'administratorContactNo', 'administratorMobile'))),
    array('role' => 'Principal', 'name' => $schoolField(array('principalName', 'principal')), 'email' => $schoolField(array('principalEmail')), 'phone' => $schoolField(array('principalContactNo', 'principalContactNumber', 'principalMobile', 'principalPhone')))
);
$recognitionDetails = array(
    array('label' => 'Permit Number', 'value' => $schoolField(array('permitNo'))),
    array('label' => 'Permit Issued Date', 'value' => $schoolField(array('permit_issued_date'))),
    array('label' => 'Permit Expiry Date', 'value' => $schoolField(array('permit_expiry_date'))),
    array('label' => 'Permit Issuing Office', 'value' => $schoolField(array('permit_issuing_office'))),
    array('label' => 'Permit Status', 'value' => $schoolField(array('permit_status'))),
    array('label' => 'PEAC Member', 'value' => !empty($school->peac_member) ? 'Yes' : 'No'),
    array('label' => 'ESC Recipient', 'value' => !empty($school->esc_recipient) ? 'Yes' : 'No'),
    array('label' => 'Voucher Program', 'value' => !empty($school->voucher_program) ? 'Yes' : 'No'),
    array('label' => 'SHS Tracks Offered', 'value' => $schoolField(array('shs_tracks_offered'))),
    array('label' => 'Recognition Details', 'value' => $schoolField(array('government_recognition_details')))
);
?>
<style>
html, body { height:auto !important; min-height:100% !important; overflow-y:auto !important; }
/* app.min.css fixes #wrapper to the viewport height and hides its overflow.
   This page has tab content below the viewport, so let the document grow normally. */
#wrapper { height:auto !important; min-height:100vh !important; overflow:hidden !important; }
.content-page, .content { min-height:100vh !important; height:auto !important; overflow:visible !important; }
.content-page { padding-bottom:24px !important; }
.content-page > .footer { position:static !important; left:auto !important; right:auto !important; width:auto !important; margin-top:0 !important; }
/* Keep the shared sidebar/header logo vertically centred with breathing room. */
.navbar-custom .logo-box { height:70px !important; }
.navbar-custom .logo-box .logo-light { height:70px !important; line-height:normal !important; display:flex !important; align-items:center !important; justify-content:center !important; }
.navbar-custom .logo-box .logo-light img { display:block; max-height:46px; }
.ownership-contact small { display:block; margin-top:9px; color:#66708a; line-height:1.55; word-break:break-word; }
.school-profile-shell.container { width:100% !important; max-width:none !important; padding-left:15px !important; padding-right:15px !important; }
.profile-panel, .profile-panel .tab-content, .profile-panel .tab-pane { overflow:visible !important; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var grid = document.querySelector('#school-profile .details-grid');
    if (!grid) return;
    var item = document.createElement('div');
    item.className = 'detail-item';
    item.innerHTML = '<span>Educational Levels Offered</span><strong><?= $esc(!empty($educationalLevels) ? implode(', ', $educationalLevels) : 'Not specified'); ?></strong>';
    grid.appendChild(item);
    var ownershipGrid = document.querySelector('#school-ownership .details-grid');
    if (!ownershipGrid) return;
    var contacts = <?= json_encode($ownershipContacts); ?>;
    contacts.forEach(function (contact) {
        var card = document.createElement('div');
        card.className = 'detail-item ownership-contact';
        var role = document.createElement('span');
        var name = document.createElement('strong');
        var details = document.createElement('small');
        role.textContent = contact.role;
        name.textContent = contact.name;
        details.textContent = 'Email: ' + contact.email + ' | Contact No.: ' + contact.phone;
        card.appendChild(role);
        card.appendChild(name);
        card.appendChild(details);
        ownershipGrid.appendChild(card);
    });
    var recognitionGrid = document.querySelector('#government-recognition .details-grid');
    if (!recognitionGrid) return;
    var recognitionDetails = <?= json_encode($recognitionDetails); ?>;
    recognitionDetails.forEach(function (detail) {
        var card = document.createElement('div');
        var label = document.createElement('span');
        var value = document.createElement('strong');
        card.className = 'detail-item';
        label.textContent = detail.label;
        value.textContent = detail.value;
        card.appendChild(label);
        card.appendChild(value);
        recognitionGrid.appendChild(card);
    });
});
</script>
<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>School Profile</title><link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet"><link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet"><link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet"><link href="<?= base_url(); ?>assets/css/dashboard-unified.css" rel="stylesheet"><style>body{background:#f4f8fc}.school-profile-shell{width:100%;max-width:none;padding:20px 15px 28px}.profile-hero{padding:30px;border-radius:22px 22px 0 0;color:#fff;background:linear-gradient(135deg,#272b8c,#3c40c6)}.profile-hero h1{margin:10px 0 0;color:#fff;font-size:2rem}.profile-hero p{margin:7px 0 0;color:rgba(255,255,255,.8)}.profile-panel{border:0;border-radius:0 0 22px 22px;box-shadow:0 16px 38px rgba(15,23,42,.08);overflow:hidden}.profile-tabs{padding:15px 20px 0;border-bottom:1px solid #e8ecf5;background:#fff}.profile-tabs .nav-link{border:0;border-bottom:3px solid transparent;color:#66708a;font-weight:700}.profile-tabs .nav-link.active{border-bottom-color:#3c40c6;color:#272b8c;background:transparent}.tab-pane{padding:28px}.details-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.detail-item{padding:16px;border:1px solid #e8ecf5;border-radius:12px;background:#fbfcff}.detail-item span{display:block;margin-bottom:6px;color:#7b849a;font-size:.73rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase}.detail-item strong{color:#2d3651;font-size:.95rem;word-break:break-word}@media(max-width:640px){.details-grid{grid-template-columns:1fr}.profile-tabs{overflow-x:auto;white-space:nowrap}.profile-hero{padding:24px}}</style></head><body class="dashboard-root-theme"><div id="wrapper"><?php include(__DIR__ . '/includes/top-bar.php'); ?><?php include(__DIR__ . '/includes/sidebar.php'); ?><div class="content-page"><div class="content"><main class="container-fluid dashboard-shell school-profile-shell"><section class="profile-hero"><a class="btn btn-sm btn-light" href="<?= base_url(); ?><?= $this->session->userdata('section') === 'School' ? 'Page/School' : 'Page/schools'; ?>">← Back</a><?php if($canEdit): ?><a class="btn btn-sm btn-outline-light ml-2" href="<?= base_url(); ?>Page/school_profile_edit"><i class="mdi mdi-pencil-outline"></i> Edit Profile</a><?php endif; ?><h1><?= $value('schoolName', 'School Profile'); ?></h1><p>School ID: <?= $esc($schoolId !== '' ? $schoolId : 'Not specified'); ?></p></section><section class="card profile-panel"><nav class="profile-tabs"><div class="nav nav-tabs" role="tablist"><a class="nav-link active" data-toggle="tab" href="#school-profile" role="tab">School Profile</a><a class="nav-link" data-toggle="tab" href="#school-location" role="tab">School Location</a><a class="nav-link" data-toggle="tab" href="#school-ownership" role="tab">School Ownership</a><a class="nav-link" data-toggle="tab" href="#government-recognition" role="tab">Government Recognition</a></div></nav><div class="tab-content"><section class="tab-pane fade show active" id="school-profile" role="tabpanel"><div class="details-grid"><div class="detail-item"><span>School Name</span><strong><?= $value('schoolName'); ?></strong></div><div class="detail-item"><span>School ID</span><strong><?= $esc($schoolId !== '' ? $schoolId : 'Not specified'); ?></strong></div><div class="detail-item"><span>School Type</span><strong><?= $value('schoolType'); ?></strong></div><div class="detail-item"><span>School Email</span><strong><?= $value('schoolEmail'); ?></strong></div><div class="detail-item"><span>School Head</span><strong><?= $esc($schoolHead !== '' ? $schoolHead : 'Not specified'); ?></strong></div><div class="detail-item"><span>Designation</span><strong><?= $value('adminDesignation'); ?></strong></div><div class="detail-item"><span>Mobile Number</span><strong><?= $value('adminMobile'); ?></strong></div><div class="detail-item"><span>Telephone Number</span><strong><?= $value('adminTel'); ?></strong></div></div></section><section class="tab-pane fade" id="school-location" role="tabpanel"><div class="details-grid"><div class="detail-item"><span>District</span><strong><?= $value('district'); ?></strong></div><div class="detail-item"><span>Province</span><strong><?= $value('province'); ?></strong></div><div class="detail-item"><span>City / Municipality</span><strong><?= $value('city'); ?></strong></div><div class="detail-item"><span>Barangay</span><strong><?= $value('brgy'); ?></strong></div><div class="detail-item"><span>Sitio</span><strong><?= $value('sitio'); ?></strong></div></div></section><section class="tab-pane fade" id="school-ownership" role="tabpanel"><div class="details-grid"><div class="detail-item"><span>School Type</span><strong><?= $value('schoolType'); ?></strong></div><div class="detail-item"><span>Ownership</span><strong><?= $value('ownership', $school ? ($school->schoolOwnership ?? 'Not specified') : 'Not specified'); ?></strong></div><div class="detail-item"><span>Land Ownership</span><strong><?= $value('landOwnership'); ?></strong></div><div class="detail-item"><span>School Category</span><strong><?= $value('schoolCategory'); ?></strong></div></div></section><section class="tab-pane fade" id="government-recognition" role="tabpanel"><div class="details-grid"><div class="detail-item"><span>Station Code</span><strong><?= $value('stationCode'); ?></strong></div><div class="detail-item"><span>Year Established</span><strong><?= $value('yearEstab'); ?></strong></div><div class="detail-item"><span>Permit Number</span><strong><?= $value('permitNo'); ?></strong></div><div class="detail-item"><span>Recognition Number</span><strong><?= $value('recogNo'); ?></strong></div></div></section></div></section></main></div></div></div><script src="<?= base_url(); ?>assets/js/vendor.min.js"></script><script src="<?= base_url(); ?>assets/js/app.min.js"></script></body></html>
