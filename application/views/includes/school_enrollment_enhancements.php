<?php
$enrollmentIds = array();
foreach ((array) ($enrollments ?? array()) as $enrollmentRecord) $enrollmentIds[] = (int) ($enrollmentRecord->id ?? 0);
$editEnrollmentData = !empty($editEnrollment) ? get_object_vars($editEnrollment) : null;
$filterSchoolYears = array('2026-2027', '2027-2028', '2028-2029');
foreach ((array) ($schoolYears ?? array()) as $schoolYearRecord) {
    $schoolYearValue = trim((string) ($schoolYearRecord->school_year ?? ''));
    if ($schoolYearValue !== '' && !in_array($schoolYearValue, $filterSchoolYears, TRUE)) $filterSchoolYears[] = $schoolYearValue;
}
$filterSelectedSchoolYear = (string) ($selectedSchoolYear ?? '');
$filterEnrollmentTotal = (int) (($totals['male'] ?? 0) + ($totals['female'] ?? 0));
?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('enrollmentModal');
    if (!modal) return;
    var form = modal.querySelector('form');
    var hero = document.querySelector('.enrollment-hero');
    var filter = document.createElement('form'); filter.method = 'get'; filter.action = '<?= base_url(); ?>Page/school_enrollment_details'; filter.className = 'mt-3';
    var filterLabel = document.createElement('label'); filterLabel.className = 'mr-2 mb-0'; filterLabel.textContent = 'Filter by School Year:';
    var filterSelect = document.createElement('select'); filterSelect.className = 'custom-select custom-select-sm d-inline-block'; filterSelect.name = 'school_year'; filterSelect.style.width = '180px';
    var all = document.createElement('option'); all.value = ''; all.textContent = 'All School Years'; filterSelect.appendChild(all);
    <?= json_encode($filterSchoolYears); ?>.forEach(function (year) { var option = document.createElement('option'); option.value = year; option.textContent = year; option.selected = year === <?= json_encode($filterSelectedSchoolYear); ?>; filterSelect.appendChild(option); });
    filterSelect.addEventListener('change', function () { filter.submit(); }); filter.appendChild(filterLabel); filter.appendChild(filterSelect); hero.appendChild(filter);
    var schoolYearSummary = document.createElement('div'); schoolYearSummary.className = 'mt-2 font-weight-bold';
    schoolYearSummary.textContent = 'School Year: ' + (<?= json_encode($filterSelectedSchoolYear); ?> || 'All School Years') + ' | Total Enrollment: ' + <?= json_encode($filterEnrollmentTotal); ?>;
    hero.appendChild(schoolYearSummary);
    var schoolYear = form.querySelector('[name="school_year"]');
    var semester = form.querySelector('[name="semester"]');
    if (semester) semester.closest('.form-group').remove();
    var wrapper = schoolYear.closest('.form-group');
    var select = document.createElement('select');
    select.className = 'form-control'; select.name = 'school_year'; select.required = true;
    ['2026-2027', '2027-2028', '2028-2029'].forEach(function (year) {
        var option = document.createElement('option'); option.value = year; option.textContent = year; if (year === '2026-2027') option.selected = true; select.appendChild(option);
    });
    schoolYear.parentNode.replaceChild(select, schoolYear);
    wrapper.className = 'form-group col-md-6';
    var editEnrollment = <?= json_encode($editEnrollmentData); ?>;
    if (editEnrollment) {
        var id = document.createElement('input'); id.type = 'hidden'; id.name = 'id'; id.value = editEnrollment.id; form.appendChild(id);
        select.value = editEnrollment.school_year || '2026-2027';
        form.querySelector('[name="grade_level"]').value = editEnrollment.grade_level || '';
        form.querySelector('[name="male_count"]').value = editEnrollment.male_count || 0;
        form.querySelector('[name="female_count"]').value = editEnrollment.female_count || 0;
        modal.querySelector('.modal-title').textContent = 'Edit Enrollment Details';
        form.querySelector('[type="submit"]').textContent = 'Save Changes';
        $('#enrollmentModal').modal('show');
    }
    var table = document.querySelector('.enrollment-card table');
    if (!table) return;
    var semesterHeader = Array.prototype.slice.call(table.querySelectorAll('thead th')).filter(function (header) { return header.textContent.trim() === 'Semester'; })[0];
    if (semesterHeader) {
        var semesterIndex = Array.prototype.indexOf.call(semesterHeader.parentNode.children, semesterHeader);
        semesterHeader.remove();
        Array.prototype.slice.call(table.querySelectorAll('tbody tr')).forEach(function (row) { if (row.children[semesterIndex]) row.children[semesterIndex].remove(); });
    }
    var schoolYearHeader = Array.prototype.slice.call(table.querySelectorAll('thead th')).filter(function (header) { return header.textContent.trim() === 'School Year'; })[0];
    if (schoolYearHeader) {
        var schoolYearIndex = Array.prototype.indexOf.call(schoolYearHeader.parentNode.children, schoolYearHeader);
        schoolYearHeader.remove();
        Array.prototype.slice.call(table.querySelectorAll('tbody tr')).forEach(function (row) { if (row.children[schoolYearIndex]) row.children[schoolYearIndex].remove(); });
    }
    var ids = <?= json_encode($enrollmentIds); ?>;
    var header = table.querySelector('thead th:last-child'); if (header) header.textContent = 'Manage';
    Array.prototype.slice.call(table.querySelectorAll('tbody tr')).forEach(function (row, index) {
        if (!row.children.length || row.children.length === 1 || !ids[index]) return;
        var cell = row.lastElementChild;
        cell.innerHTML = '<div class="btn-group"><button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-toggle="dropdown">Manage</button><div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="<?= base_url(); ?>Page/school_enrollment_details?edit=' + ids[index] + '"><i class="mdi mdi-pencil-outline mr-1"></i>Edit</a><div class="dropdown-divider"></div><a class="dropdown-item text-danger" href="<?= base_url(); ?>Page/school_enrollment_delete/' + ids[index] + '" onclick="return confirm(\'Remove this enrollment detail?\');"><i class="mdi mdi-delete-outline mr-1"></i>Delete</a></div></div>';
    });
});
</script>
