<?php
$schoolId = trim((string) $this->session->userdata('username'));
$schoolName = trim((string) ($school->schoolName ?? $this->session->userdata('fName') ?? 'School'));
$schoolType = trim((string) ($school->schoolType ?? ''));
$district = trim((string) ($school->district ?? ''));
$location = implode(', ', array_filter(array(trim((string) ($school->brgy ?? '')), trim((string) ($school->city ?? '')), trim((string) ($school->province ?? '')))));
$profileChecklist = array('schoolName', 'schoolType', 'schoolEmail', 'district', 'province', 'city', 'brgy', 'sitio', 'adminFName', 'adminLName', 'adminDesignation', 'adminMobile', 'adminEmail', 'ownership', 'ownerName', 'ownerEmail', 'ownerContactNo', 'presidentName', 'boardChairperson', 'corporateSecretary', 'schoolAdministrator', 'principalName', 'stationCode', 'yearEstab', 'recogNo', 'permitNo', 'permit_issuing_office', 'permit_status', 'shs_tracks_offered');
$profileCompleted = 0;
foreach ($profileChecklist as $profileField) {
    if (trim((string) ($school->{$profileField} ?? '')) !== '') {
        $profileCompleted++;
    }
}
$profileCompletion = count($profileChecklist) ? (int) round(($profileCompleted / count($profileChecklist)) * 100) : 0;
$dashboardConfig = array(
    'eyebrow' => 'School Dashboard',
    'eyebrow_icon' => 'mdi-school-outline',
    'title' => $schoolName,
    'subtitle' => 'View your school profile and keep your account information up to date.',
    'profile_name' => $schoolName,
    'profile_role' => $schoolId !== '' ? 'School ID ' . $schoolId : 'School Account',
    'hero_progress' => array(
        'value' => $profileCompletion,
        'label' => 'Profile Data Accomplishment',
        'context' => $profileCompleted . ' of ' . count($profileChecklist) . ' profile details completed'
    ),
    'metrics' => array(),
    'quick_links_title' => 'School Tools',
    'quick_links_caption' => 'Open the tools available to your School account',
    'quick_links_overlap' => TRUE,
    'quick_links' => array(
        array('label' => 'School Profile', 'context' => 'Review the school information on record.', 'href' => base_url() . 'Page/school_profile/' . rawurlencode($schoolId), 'icon' => 'mdi-card-account-details-outline'),
        array('label' => 'Edit School Profile', 'context' => 'Update your school contact and profile details.', 'href' => base_url() . 'Page/school_profile_edit', 'icon' => 'mdi-pencil-outline')
    ),
    'show_whereabouts' => FALSE
);
include(__DIR__ . '/includes/dashboard_standard.php');
