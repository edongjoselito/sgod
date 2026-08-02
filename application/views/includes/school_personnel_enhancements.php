<?php
$personnelExpiryDates = array();
$personnelManageRows = array();
foreach ((array) ($personnel ?? array()) as $personnelRecord) {
    $personnelExpiryDates[] = (string) ($personnelRecord->prc_expiration ?? '');
    $personnelManageRows[] = (int) ($personnelRecord->id ?? 0);
}
$editPersonnelData = !empty($editPersonnel) ? get_object_vars($editPersonnel) : null;
?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('#personnelModal form');
    if (!form) return;
    var fullNameInput = form.querySelector('[name="full_name"]');
    var fullNameGroup = fullNameInput.closest('.form-group');
    fullNameGroup.innerHTML = '<div class="form-row"><div class="col-md-4"><label>First Name</label><input class="form-control" name="first_name" required></div><div class="col-md-4"><label>Middle Name</label><input class="form-control" name="middle_name"></div><div class="col-md-4"><label>Last Name</label><input class="form-control" name="last_name" required></div></div><input type="hidden" name="full_name">';
    form.addEventListener('submit', function () {
        var names = ['first_name', 'middle_name', 'last_name'].map(function (name) { return form.querySelector('[name="' + name + '"]').value.trim(); }).filter(Boolean);
        form.querySelector('[name="full_name"]').value = names.join(' ');
    });
    var type = form.querySelector('[name="personnel_type"]');
    var position = form.querySelector('[name="position_title"]');
    var contactRow = form.querySelector('[name="email"]').closest('.form-row');
    var educationRow = document.createElement('div');
    educationRow.className = 'form-row';
    educationRow.innerHTML = '<div class="form-group col-md-7"><label>Highest Educational Attainment</label><select class="form-control" name="highest_education"><option value="">Select Educational Attainment</option><option>High School Graduate</option><option>Vocational/Technical Certificate</option><option>Associate Degree</option><option>Bachelor\'s Degree</option><option>Bachelor\'s Degree with Professional Education Units</option><option>Bachelor\'s Degree with Master\'s Units</option><option>Master\'s Degree</option><option>Master\'s Degree with Doctoral Units</option><option>Doctorate Degree</option></select></div><div class="form-group col-md-5"><label>Course / Degree</label><input class="form-control" name="education_course"></div>';
    contactRow.parentNode.insertBefore(educationRow, contactRow);

    var teachingFields = document.createElement('div');
    teachingFields.className = 'form-row';
    teachingFields.innerHTML = '<div class="form-group col-md-4"><label>Major / Specialization</label><input class="form-control" name="major_specialization"></div><div class="form-group col-md-4"><label>PRC License Number</label><input class="form-control" name="prc_license_no"></div><div class="form-group col-md-4"><label>PRC Expiration</label><input class="form-control" type="date" name="prc_expiration"></div>';
    contactRow.parentNode.insertBefore(teachingFields, contactRow);

    var nonTeachingFields = document.createElement('div');
    nonTeachingFields.className = 'form-row';
    nonTeachingFields.innerHTML = '<div class="form-group col-md-6"><label>Non-Teaching Role</label><select class="form-control" name="non_teaching_role"><option value="">Select Role</option><option>Registrar</option><option>Accountant/Finance</option><option>Librarian</option><option>Guidance Counselor</option><option>Nurse</option><option>Cashier</option><option>IT Personnel</option><option>Administrative Staff</option><option>Utility Personnel</option><option>Others</option></select></div><div class="form-group col-md-6" id="nonTeachingOther"><label>Please Specify</label><input class="form-control" name="non_teaching_other"></div>';
    contactRow.parentNode.insertBefore(nonTeachingFields, contactRow);
    var role = nonTeachingFields.querySelector('[name="non_teaching_role"]');
    var other = document.getElementById('nonTeachingOther');
    function refreshFields() {
        var teaching = type.value === 'Teaching';
        teachingFields.style.display = '';
        nonTeachingFields.style.display = teaching ? 'none' : '';
        position.closest('.form-group').style.display = teaching ? '' : 'none';
        form.querySelector('#licensed').closest('.form-group').style.display = teaching ? '' : 'none';
        other.style.display = role.value === 'Others' ? '' : 'none';
    }
    type.addEventListener('change', refreshFields);
    role.addEventListener('change', refreshFields);
    refreshFields();

    var editPersonnel = <?= json_encode($editPersonnelData); ?>;
    if (editPersonnel) {
        var hiddenId = document.createElement('input'); hiddenId.type = 'hidden'; hiddenId.name = 'id'; hiddenId.value = editPersonnel.id; form.appendChild(hiddenId);
        ['employee_no', 'sex', 'position_title', 'personnel_type', 'employment_status', 'highest_education', 'education_course', 'major_specialization', 'prc_license_no', 'prc_expiration', 'non_teaching_role', 'non_teaching_other', 'email', 'mobile_no'].forEach(function (name) {
            var input = form.querySelector('[name="' + name + '"]'); if (input) input.value = editPersonnel[name] || '';
        });
        form.querySelector('[name="first_name"]').value = editPersonnel.first_name || editPersonnel.full_name || '';
        form.querySelector('[name="middle_name"]').value = editPersonnel.middle_name || '';
        form.querySelector('[name="last_name"]').value = editPersonnel.last_name || '';
        form.querySelector('[name="licensed"]').checked = Number(editPersonnel.licensed) === 1;
        form.querySelector('.modal-title').textContent = 'Edit Personnel';
        form.querySelector('[type="submit"]').textContent = 'Save Changes';
        refreshFields();
        $('#personnelModal').modal('show');
    }

    var table = document.querySelector('.personnel-card table');
    if (!table) return;
    var expiryDates = <?= json_encode($personnelExpiryDates); ?>;
    var headers = Array.prototype.slice.call(table.querySelectorAll('thead th'));
    var licenseIndex = headers.map(function (header) { return header.textContent.trim(); }).indexOf('License');
    if (licenseIndex === -1) return;
    var expirationHeader = document.createElement('th');
    expirationHeader.textContent = 'PRC Expiration';
    headers[licenseIndex].parentNode.insertBefore(expirationHeader, headers[licenseIndex].nextSibling);
    Array.prototype.slice.call(table.querySelectorAll('tbody tr')).forEach(function (row, index) {
        if (!row.children.length || row.children.length === 1) return;
        var cell = document.createElement('td');
        var expiry = expiryDates[index] || '';
        if (!expiry) {
            cell.textContent = 'Not specified';
        } else {
            var expiryDate = new Date(expiry + 'T00:00:00');
            var today = new Date(); today.setHours(0, 0, 0, 0);
            var days = Math.ceil((expiryDate - today) / 86400000);
            cell.appendChild(document.createTextNode(expiry + ' '));
            var badge = document.createElement('span'); badge.className = 'badge';
            if (days < 0) {
                badge.className += ' badge-danger'; badge.textContent = 'Expired'; cell.appendChild(badge);
            }
        }
        row.insertBefore(cell, row.children[licenseIndex + 1]);
    });
    var ids = <?= json_encode($personnelManageRows); ?>;
    var actionHeader = table.querySelector('thead th:last-child');
    if (actionHeader) actionHeader.textContent = 'Manage';
    Array.prototype.slice.call(table.querySelectorAll('tbody tr')).forEach(function (row, index) {
        if (!row.children.length || row.children.length === 1 || !ids[index]) return;
        var actionCell = row.lastElementChild;
        actionCell.innerHTML = '<div class="btn-group"><button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-toggle="dropdown">Manage</button><div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="<?= base_url(); ?>Page/school_personnel?edit=' + ids[index] + '"><i class="mdi mdi-pencil-outline mr-1"></i>Edit</a><a class="dropdown-item" href="<?= base_url(); ?>Page/school_personnel_details/' + ids[index] + '"><i class="mdi mdi-card-account-details-outline mr-1"></i>View More Details</a><div class="dropdown-divider"></div><a class="dropdown-item text-danger" href="<?= base_url(); ?>Page/school_personnel_delete/' + ids[index] + '" onclick="return confirm(\'Remove this personnel record?\');"><i class="mdi mdi-delete-outline mr-1"></i>Delete</a></div></div>';
    });
});
</script>
