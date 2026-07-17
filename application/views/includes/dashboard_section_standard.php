<?php
$sectionName = trim((string) ($this->session->userdata('section') ?: 'Section'));
$firstName = trim((string) $this->session->userdata('fName'));
$middleName = trim((string) $this->session->userdata('mName'));
$lastName = trim((string) $this->session->userdata('lName'));
$profileName = trim(preg_replace('/\s+/', ' ', implode(' ', array_filter(array($firstName, $middleName, $lastName)))));
$publicSchoolCount = !empty($data) && isset($data[0]->schoolCounts) ? (int) $data[0]->schoolCounts : 0;
$privateSchoolCount = !empty($data1) && isset($data1[0]->schoolCounts) ? (int) $data1[0]->schoolCounts : 0;
$accomplishmentCount = !empty($data2) && isset($data2[0]->Counts) ? (int) $data2[0]->Counts : 0;
$sectionUserCount = !empty($data3) && isset($data3[0]->Counts) ? (int) $data3[0]->Counts : 0;
$totalSchools = $publicSchoolCount + $privateSchoolCount;

$dashboardConfig = array(
    'eyebrow' => 'Section Dashboard',
    'eyebrow_icon' => 'mdi-view-dashboard-outline',
    'title' => $sectionName,
    'subtitle' => 'Monitor schools, accomplishments, schedules, and section activity from one workspace.',
    'profile_name' => $profileName !== '' ? $profileName : 'Section User',
    'profile_role' => $sectionName,
    'metrics' => array(
        array(
            'value' => $totalSchools,
            'label' => 'Total Schools',
            'context' => number_format($publicSchoolCount) . ' public · ' . number_format($privateSchoolCount) . ' private',
            'icon' => 'mdi-school-outline',
            'href' => base_url() . 'Page/schools'
        ),
        array(
            'value' => $publicSchoolCount,
            'label' => 'Public Schools',
            'context' => 'Open public school records',
            'icon' => 'mdi-domain',
            'href' => base_url() . 'Page/schools?type=Public'
        ),
        array(
            'value' => $privateSchoolCount,
            'label' => 'Private Schools',
            'context' => 'Open private school records',
            'icon' => 'mdi-home-city-outline',
            'href' => base_url() . 'Page/schools?type=Private'
        ),
        array(
            'value' => $accomplishmentCount,
            'label' => 'Accomplishments',
            'context' => 'View submitted reports',
            'icon' => 'mdi-file-check-outline',
            'href' => base_url() . 'Page/viewSecAccomplishments'
        ),
        array(
            'value' => $sectionUserCount,
            'label' => 'Section Users',
            'context' => 'Active section accounts',
            'icon' => 'mdi-account-group-outline'
        )
    ),
    'distribution' => array(
        'public' => $publicSchoolCount,
        'private' => $privateSchoolCount
    ),
    'quick_links_title' => 'Section Tools',
    'quick_links_caption' => 'Open the pages used most often by your section',
    'quick_links' => array(
        array(
            'label' => 'School Directory',
            'context' => 'Browse public and private school records.',
            'href' => base_url() . 'Page/schools',
            'icon' => 'mdi-school-outline'
        ),
        array(
            'label' => 'Section Accomplishments',
            'context' => 'Review and manage submitted accomplishment records.',
            'href' => base_url() . 'Page/viewSecAccomplishments',
            'icon' => 'mdi-file-check-outline'
        )
    )
);

include(__DIR__ . '/dashboard_standard.php');
