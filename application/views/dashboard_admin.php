<?php
$dashboardDivision = array(
    'code' => $this->session->userdata('secGroup') ?: 'SGOD',
    'name' => 'School Governance and Operations Division'
);
include(__DIR__ . '/includes/dashboard_admin_standard.php');
