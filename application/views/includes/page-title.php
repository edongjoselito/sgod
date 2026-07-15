<?php
$section = $this->session->userdata('section');
if ($section && $section !== 'Super Admin' && $section !== 'System Administrator') {
    echo '<title>' . htmlspecialchars($section, ENT_QUOTES, 'UTF-8') . ' Dashboard</title>';
} else {
    echo '<title>School Governance Operations Division</title>';
}
?>