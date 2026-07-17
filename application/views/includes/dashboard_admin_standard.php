<?php
$dashboardDivision = isset($dashboardDivision) && is_array($dashboardDivision) ? $dashboardDivision : array();
$divisionCode = strtoupper(trim((string) ($dashboardDivision['code'] ?? $this->session->userdata('secGroup') ?: 'ADMIN')));
$divisionName = trim((string) ($dashboardDivision['name'] ?? $divisionCode));
$firstName = trim((string) $this->session->userdata('fName'));
$lastName = trim((string) $this->session->userdata('lName'));
$profileName = trim($firstName . ' ' . $lastName);
$usersCount = is_object($data ?? null) && method_exists($data, 'num_rows') ? (int) $data->num_rows() : 0;
$sectionsCount = is_object($data1 ?? null) && method_exists($data1, 'num_rows') ? (int) $data1->num_rows() : 0;
$accomplishmentsCount = is_object($data2 ?? null) && method_exists($data2, 'num_rows') ? (int) $data2->num_rows() : 0;
$schoolsCount = is_object($data3 ?? null) && method_exists($data3, 'num_rows') ? (int) $data3->num_rows() : 0;

$dashboardConfig = array(
    'eyebrow' => 'Administrative Dashboard',
    'eyebrow_icon' => 'mdi-shield-account-outline',
    'title' => $divisionCode . ' Dashboard',
    'subtitle' => 'Manage ' . $divisionName . ' records, accounts, reports, and daily operations from one workspace.',
    'profile_name' => $profileName !== '' ? $profileName : $divisionCode . ' Administrator',
    'profile_role' => $divisionName,
    'metrics' => array(
        array('value' => $usersCount, 'label' => 'Users', 'context' => 'Division user accounts', 'icon' => 'mdi-account-group-outline', 'href' => base_url() . 'Page/usersList'),
        array('value' => $sectionsCount, 'label' => 'Sections', 'context' => 'Division sections', 'icon' => 'mdi-sitemap-outline', 'href' => base_url() . 'Page/sections'),
        array('value' => $accomplishmentsCount, 'label' => 'Accomplishments', 'context' => 'Submitted division reports', 'icon' => 'mdi-file-check-outline'),
        array('value' => $schoolsCount, 'label' => 'Schools', 'context' => 'School directory records', 'icon' => 'mdi-school-outline', 'href' => base_url() . 'Page/schools')
    ),
    'quick_links_title' => 'Administrative Tools',
    'quick_links_caption' => 'Open frequently used management pages',
    'quick_links' => array(
        array('label' => 'Open Memos', 'context' => 'Post and track division memoranda.', 'href' => base_url() . 'Page/memo', 'icon' => 'mdi-file-document-edit-outline'),
        array('label' => 'Manage Sections', 'context' => 'Create and maintain section records.', 'href' => base_url() . 'Page/sections', 'icon' => 'mdi-sitemap-outline'),
        array('label' => 'Manage Users', 'context' => 'Create and maintain user accounts.', 'href' => base_url() . 'Page/usersList', 'icon' => 'mdi-account-cog-outline'),
        array('label' => 'Public Whereabouts', 'context' => 'Open the office whereabouts board.', 'href' => base_url() . 'Page/public_whereabouts', 'icon' => 'mdi-monitor-dashboard')
    )
);

include(__DIR__ . '/dashboard_standard.php');
