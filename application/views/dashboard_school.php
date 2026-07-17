<?php
$schoolAccountName = trim(preg_replace('/\s+/', ' ', implode(' ', array_filter(array(
    $this->session->userdata('fName'),
    $this->session->userdata('mName'),
    $this->session->userdata('lName')
)))));
$schoolName = trim((string) ($this->session->userdata('schoolName') ?: $schoolAccountName ?: 'School'));
$schoolId = trim((string) $this->session->userdata('username'));
$dashboardConfig = array(
    'eyebrow' => 'School Dashboard',
    'eyebrow_icon' => 'mdi-school-outline',
    'title' => $schoolName,
    'subtitle' => 'Monitor implementation plans, planning references, and daily school activity from one workspace.',
    'profile_name' => $schoolName,
    'profile_role' => $schoolId !== '' ? 'School ID ' . $schoolId : 'School Account',
    'metrics' => array(
        array('value' => $data->num_rows(), 'label' => 'AIP', 'context' => 'Annual implementation plans', 'icon' => 'mdi-clipboard-text-outline', 'href' => base_url() . 'Page/aip'),
        array('value' => $pillar->num_rows(), 'label' => 'Pillars', 'context' => 'Planning pillars', 'icon' => 'mdi-view-column-outline'),
        array('value' => $pias->num_rows(), 'label' => 'PIAs', 'context' => 'Priority improvement areas', 'icon' => 'mdi-target-account'),
        array('value' => $domain->num_rows(), 'label' => 'Domains', 'context' => 'Planning domains', 'icon' => 'mdi-chart-donut')
    ),
    'quick_links_title' => 'School Tools',
    'quick_links_caption' => 'Open frequently used planning and profile pages',
    'quick_links' => array(
        array('label' => 'School Profile', 'context' => 'Review the school information on record.', 'href' => base_url() . 'Page/school_profile/' . rawurlencode($schoolId), 'icon' => 'mdi-card-account-details-outline'),
        array('label' => 'Annual Implementation Plan', 'context' => 'Open and manage the school AIP.', 'href' => base_url() . 'Page/aip', 'icon' => 'mdi-clipboard-text-outline'),
        array('label' => 'School Operating Plan', 'context' => 'Open the school operating plan.', 'href' => base_url() . 'Page/sop', 'icon' => 'mdi-file-chart-outline'),
        array('label' => 'Procurement Plan', 'context' => 'Open the annual procurement plan.', 'href' => base_url() . 'Page/view_app', 'icon' => 'mdi-cart-outline')
    )
);

include(__DIR__ . '/includes/dashboard_standard.php');
