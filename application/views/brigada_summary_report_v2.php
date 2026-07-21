<?php include('templates/head.php'); ?>
<?php include('templates/header.php'); ?>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap');

  * { box-sizing: border-box; }
  :root {
    --c-ink: #0f172a;
    --c-ink-muted: #64748b;
    --c-surface: #ffffff;
    --c-surface-2: #f8fbff;
    --c-surface-3: #eef5ff;
    --c-surface-4: #e0ecff;
    --c-border: rgba(148, 163, 184, 0.22);
    --c-border-strong: rgba(59, 130, 246, 0.25);

    --c-primary: #2563eb;
    --c-primary-dark: #1d4ed8;
    --c-primary-light: #dbeafe;
    --c-primary-soft: #eff6ff;

    --c-success: #059669;
    --c-success-light: #d1fae5;

    --c-warning: #d97706;
    --c-warning-light: #fef3c7;

    --c-purple: #7c3aed;
    --c-purple-light: #ede9fe;

    --c-danger: #dc2626;
    --c-danger-light: #fee2e2;

    --shadow-sm: 0 4px 10px rgba(15, 23, 42, 0.04);
    --shadow-md: 0 10px 30px rgba(37, 99, 235, 0.10);
    --shadow-lg: 0 18px 40px rgba(30, 64, 175, 0.12);

    --radius-sm: 10px;
    --radius-md: 16px;
    --radius-lg: 22px;

    --font: 'Inter', sans-serif;
    --mono: 'JetBrains Mono', monospace;
  }

  body {
    background:
      radial-gradient(circle at top left, rgba(59,130,246,0.08), transparent 28%),
      radial-gradient(circle at top right, rgba(125,211,252,0.08), transparent 25%),
      #f6f9ff;
  }

  .brig-wrapper {
    font-family: var(--font);
    color: var(--c-ink);
    max-width: 100%;
  }

  /* ── PAGE HEADER ── */
  .brig-page-header {
    background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 45%, #0ea5e9 100%);
    padding: 30px 34px 26px;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    border-radius: 20px 20px 0 0;
    margin-bottom: 0;
    box-shadow: var(--shadow-lg);
    position: relative;
    overflow: hidden;
  }

  .brig-page-header::before {
    content: "";
    position: absolute;
    width: 260px;
    height: 260px;
    right: -70px;
    top: -120px;
    background: rgba(255,255,255,0.10);
    border-radius: 50%;
  }

  .brig-page-header::after {
    content: "";
    position: absolute;
    width: 180px;
    height: 180px;
    left: -40px;
    bottom: -110px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
  }

  .brig-page-header > * {
    position: relative;
    z-index: 1;
  }

  .brig-page-header h1 {
    font-size: 24px;
    font-weight: 800;
    color: #fff;
    letter-spacing: -0.03em;
    line-height: 1.15;
    margin: 0;
  }

  .brig-page-header p {
    font-size: 12px;
    color: rgba(255,255,255,0.82);
    margin: 7px 0 0;
    font-family: var(--mono);
    letter-spacing: 0.04em;
    text-transform: uppercase;
  }

  .brig-header-badge {
    background: rgba(255,255,255,0.14);
    border: 1px solid rgba(255,255,255,0.22);
    color: #fff;
    font-size: 11px;
    font-family: var(--mono);
    padding: 7px 14px;
    border-radius: 999px;
    letter-spacing: 0.05em;
    white-space: nowrap;
    backdrop-filter: blur(10px);
  }

  /* ── STAT CARDS ── */
  .brig-stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    padding: 18px;
    background: #fff;
    border-left: 1px solid var(--c-border);
    border-right: 1px solid var(--c-border);
    border-bottom: 1px solid var(--c-border);
    box-shadow: var(--shadow-md);
  }

  .brig-stat-card {
    background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    padding: 22px 22px;
    display: flex;
    flex-direction: column;
    gap: 7px;
    border: 1px solid var(--c-border);
    border-radius: 18px;
    box-shadow: var(--shadow-sm);
    transition: transform 0.18s ease, box-shadow 0.18s ease;
  }

  .brig-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 28px rgba(37, 99, 235, 0.10);
  }

  .brig-stat-icon {
    width: 42px;
    height: 42px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
    margin-bottom: 4px;
    font-weight: 700;
  }

  .brig-stat-icon.blue   { background: #dbeafe; color: #2563eb; }
  .brig-stat-icon.green  { background: #d1fae5; color: #059669; }
  .brig-stat-icon.amber  { background: #fef3c7; color: #d97706; }
  .brig-stat-icon.purple { background: #ede9fe; color: #7c3aed; }

  .brig-stat-value {
    font-size: 28px;
    font-weight: 800;
    letter-spacing: -0.04em;
    font-family: var(--mono);
    line-height: 1;
    color: var(--c-ink);
  }

  .brig-stat-label {
    font-size: 11px;
    color: var(--c-ink-muted);
    text-transform: uppercase;
    letter-spacing: 0.09em;
    font-weight: 700;
  }

  .brig-partner-summary-head {
    padding: 18px 24px 14px;
    background: #fff;
    border-left: 1px solid var(--c-border);
    border-right: 1px solid var(--c-border);
    border-bottom: 1px solid var(--c-border);
  }

  .brig-partner-summary-head .brig-section-sub {
    margin-top: 6px;
  }

  .brig-stats-row-3 {
    grid-template-columns: repeat(3, 1fr);
  }

  /* ── FILTER BAR ── */
  .brig-filter-bar {
    background: #fff;
    border: 1px solid var(--c-border);
    border-top: none;
    padding: 18px 24px;
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    box-shadow: var(--shadow-sm);
  }

  .brig-filter-label {
    font-size: 11px;
    font-weight: 700;
    color: var(--c-ink-muted);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    white-space: nowrap;
  }

  .brig-filter-select {
    font-family: var(--font);
    font-size: 13px;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    padding: 10px 36px 10px 13px;
    color: var(--c-ink);
    background:
      #f8fbff
      url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%233b82f6' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E")
      no-repeat right 12px center;
    -webkit-appearance: none;
    appearance: none;
    cursor: pointer;
    outline: none;
    transition: all 0.15s ease;
    min-width: 140px;
  }

  .brig-filter-select:focus {
    border-color: #60a5fa;
    box-shadow: 0 0 0 4px rgba(59,130,246,0.12);
    background-color: #fff;
  }

  .brig-filter-divider {
    width: 1px;
    height: 32px;
    background: #dbe4f0;
    flex-shrink: 0;
  }

  .brig-filter-spacer  { flex: 1; }

  .brig-btn {
    font-family: var(--font);
    font-size: 12px;
    font-weight: 700;
    border: 1px solid transparent;
    border-radius: 12px;
    padding: 10px 16px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    transition: all 0.18s ease;
    white-space: nowrap;
    letter-spacing: 0.01em;
    text-decoration: none;
    box-shadow: var(--shadow-sm);
  }

  .brig-btn-primary {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #fff;
    border-color: #2563eb;
  }

  .brig-btn-primary:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e40af);
    transform: translateY(-1px);
  }

  .brig-btn-ghost {
    background: #fff;
    color: var(--c-ink);
    border-color: #cbd5e1;
  }

  .brig-btn-ghost:hover {
    background: #eff6ff;
    border-color: #93c5fd;
    color: #1d4ed8;
  }

  .brig-btn-success {
    background: linear-gradient(135deg, #0ea5e9, #2563eb);
    color: #fff;
    border-color: #2563eb;
  }

  .brig-btn-success:hover {
    background: linear-gradient(135deg, #0284c7, #1d4ed8);
    transform: translateY(-1px);
  }

  /* ── SECTION HEAD ── */
  .brig-section-head {
    padding: 18px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    background: #fff;
    border-left: 1px solid var(--c-border);
    border-right: 1px solid var(--c-border);
    border-bottom: 1px solid var(--c-border);
  }

  .brig-section-title {
    font-size: 16px;
    font-weight: 800;
    color: var(--c-ink);
    letter-spacing: -0.02em;
  }

  .brig-section-sub {
    font-size: 12px;
    color: var(--c-ink-muted);
    margin-top: 4px;
  }

  .brig-search-box {
    font-family: var(--font);
    font-size: 13px;
    color: var(--c-ink);
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    padding: 10px 14px 10px 36px;
    outline: none;
    width: 240px;
    background:
      #f8fbff
      url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%233b82f6' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E")
      no-repeat 12px center;
    transition: all 0.15s ease;
  }

  .brig-search-box:focus {
    border-color: #60a5fa;
    background-color: #fff;
    box-shadow: 0 0 0 4px rgba(59,130,246,0.12);
  }

  .brig-search-box::placeholder {
    color: #94a3b8;
  }

  /* ── TABLE ── */
  .brig-table-wrap {
    overflow-x: auto;
    border-left: 1px solid var(--c-border);
    border-right: 1px solid var(--c-border);
    background: #fff;
  }

  .brig-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 13px;
    font-family: var(--font);
  }

  .brig-table thead th {
    background: linear-gradient(135deg, #1e3a8a, #2563eb);
    color: rgba(255,255,255,0.94);
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    padding: 14px 16px;
    white-space: nowrap;
    text-align: center;
    border-right: 1px solid rgba(255,255,255,0.10);
    border-bottom: none;
  }

  .brig-table thead th.th-school {
    text-align: left;
    min-width: 220px;
  }

  .brig-table thead th.th-idx {
    width: 52px;
  }

  .brig-table thead .brig-subhead th {
    background: #3b82f6;
    color: rgba(255,255,255,0.90);
    font-size: 10px;
    padding: 9px 16px;
    font-weight: 600;
  }

  .brig-table tbody tr {
    border-bottom: 1px solid #edf2f7;
    transition: background 0.15s ease;
  }

  .brig-table tbody tr:last-child {
    border-bottom: none;
  }

  .brig-table tbody tr:hover {
    background: #f8fbff;
  }

  .brig-table td {
    padding: 13px 16px;
    color: var(--c-ink);
    vertical-align: middle;
    border-bottom: 1px solid #edf2f7;
  }

  .brig-table td.td-idx {
    text-align: center;
    font-family: var(--mono);
    font-size: 11px;
    color: var(--c-ink-muted);
    width: 52px;
  }

  .brig-table td.td-school {
    font-weight: 700;
    font-size: 13px;
    white-space: nowrap;
    color: #0f172a;
  }

  .brig-table td.td-num {
    text-align: center;
    font-family: var(--mono);
    font-size: 12px;
  }

  .brig-table td.td-res {
    color: #1d4ed8;
    font-weight: 700;
  }

  .brig-table td.td-vol {
    color: #0f766e;
    font-weight: 700;
  }

  .brig-table td.td-total {
    text-align: center;
    font-family: var(--mono);
    font-weight: 800;
    font-size: 12px;
    color: #1e3a8a;
  }

  .brig-table tr.brig-subtotal {
    background: #eff6ff !important;
  }

  .brig-table tr.brig-subtotal td {
    font-weight: 800;
    font-family: var(--mono);
    font-size: 12px;
    color: #1e3a8a;
    border-top: 2px solid #93c5fd;
    border-bottom: 1px solid #bfdbfe;
  }

  .brig-table tr.brig-grand {
    background: linear-gradient(135deg, #1e40af, #2563eb) !important;
  }

  .brig-table tr.brig-grand td {
    color: #fff !important;
    font-weight: 800;
    font-family: var(--mono);
    font-size: 12px;
    border-bottom: none;
  }

  .brig-table tr.brig-grand td.td-res {
    color: #dbeafe !important;
  }

  .brig-table tr.brig-grand td.td-vol {
    color: #e0f2fe !important;
  }

  /* ── DATE CHIP ── */
  .brig-date-chip {
    display: inline-flex;
    align-items: center;
    background: rgba(255,255,255,0.18);
    border: 1px solid rgba(255,255,255,0.20);
    border-radius: 999px;
    padding: 5px 10px;
    font-family: var(--mono);
    font-size: 10px;
    color: rgba(255,255,255,0.96);
    white-space: nowrap;
    backdrop-filter: blur(8px);
  }

  /* ── TABLE FOOTER ── */
  .brig-table-footer {
    padding: 14px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 12px;
    color: var(--c-ink-muted);
    border: 1px solid var(--c-border);
    border-top: none;
    background: #fff;
    flex-wrap: wrap;
    gap: 8px;
    border-radius: 0 0 20px 20px;
    box-shadow: var(--shadow-sm);
  }

  .brig-rows-badge {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 999px;
    padding: 4px 10px;
    font-family: var(--mono);
    font-size: 11px;
    color: #1d4ed8;
    font-weight: 700;
  }

  /* ── FLASH MESSAGES ── */
  .brig-alert {
    padding: 14px 18px;
    border-radius: 14px;
    margin-bottom: 16px;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    box-shadow: var(--shadow-sm);
  }

  .brig-alert-success {
    background: var(--c-success-light);
    color: #065f46;
    border: 1px solid rgba(5,150,105,0.18);
  }

  .brig-alert-danger {
    background: var(--c-danger-light);
    color: #991b1b;
    border: 1px solid rgba(220,38,38,0.18);
  }

  /* ── EMPTY STATE ── */
  .brig-empty {
    padding: 70px 32px;
    text-align: center;
    color: var(--c-ink-muted);
    background: #fff;
  }

  .brig-empty-icon  {
    font-size: 36px;
    margin-bottom: 12px;
  }

  .brig-empty-title {
    font-size: 16px;
    font-weight: 800;
    color: var(--c-ink);
    margin-bottom: 6px;
  }

  .brig-empty-sub {
    font-size: 13px;
  }

  /* ── RESPONSIVE ── */
  @media (max-width: 992px) {
    .brig-stats-row {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 768px) {
    .brig-page-header,
    .brig-filter-bar,
    .brig-partner-summary-head,
    .brig-section-head,
    .brig-table-footer {
      padding-left: 16px;
      padding-right: 16px;
    }

    .brig-page-header {
      border-radius: 16px 16px 0 0;
    }

    .brig-stats-row {
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
      padding: 12px;
    }

    .brig-stat-card {
      padding: 16px;
      border-radius: 14px;
    }

    .brig-stat-value {
      font-size: 22px;
    }

    .brig-filter-spacer {
      display: none;
    }

    .brig-search-box {
      width: 100%;
    }

    .brig-table-footer {
      border-radius: 0 0 16px 16px;
    }
  }

  @media (max-width: 480px) {
    .brig-stats-row {
      grid-template-columns: 1fr;
    }

    .brig-page-header h1 {
      font-size: 20px;
    }
  }

  /* ── PRINT ── */
  @media print {
    body {
      background: #fff !important;
    }

    .brig-filter-bar,
    .brig-section-head .brig-search-box,
    .brig-btn {
      display: none !important;
    }

    .brig-page-header {
      border-radius: 0;
      box-shadow: none;
    }

    .brig-stats-row,
    .brig-table-footer {
      box-shadow: none;
    }

    .brig-table-wrap {
      overflow: visible;
    }
  }

  /* Restore vertical scrolling */
  html, body { overflow-y: auto !important; height: auto !important; }
  .content-page, .content { overflow: visible !important; height: auto !important; }
</style>

<!-- ============================================================== -->
<!-- Page Content -->
<!-- ============================================================== -->

<div class="content-page">
  <div class="content">
    <div class="container-fluid">

      <!-- Flash Messages -->
      <?php if ($this->session->flashdata('success')): ?>
        <div class="brig-alert brig-alert-success">
          &#10003; <?= $this->session->flashdata('success'); ?>
        </div>
      <?php endif; ?>

      <?php if ($this->session->flashdata('danger')): ?>
        <div class="brig-alert brig-alert-danger">
          &#9888; <?= $this->session->flashdata('danger'); ?>
        </div>
      <?php endif; ?>

      <div class="brig-wrapper">

        <!-- ── PAGE HEADER ── -->
        <div class="brig-page-header">
          <div>
            <h1>Brigada Eskwela Summary Report</h1>
            <p>Resources &amp; Volunteers &nbsp;·&nbsp; All Schools &nbsp;·&nbsp; FY <?= !empty($filter_year) ? $filter_year : date('Y'); ?></p>
          </div>
          <span class="brig-header-badge">V2 &nbsp;·&nbsp; DepEd Monitoring</span>
        </div>

        <!-- ── STAT CARDS ── -->
        <?php
          $stat_total_records    = 0;
          $stat_total_resources  = 0;
          $stat_total_volunteers = 0;
          $stat_total_days       = count($dates);

          if (!empty($data)) {
            foreach ($data as $school_id => $school_data) {
              foreach ($dates as $date) {
                $dk = $date->c_date;
                $d  = isset($school_data['data'][$dk]) ? $school_data['data'][$dk] : ['total_resources'=>0,'total_volunteers'=>0,'total_records'=>0];
                $stat_total_resources  += $d['total_resources'];
                $stat_total_volunteers += $d['total_volunteers'];
                $stat_total_records    += $d['total_records'];
              }
            }
          }
        ?>
        <div class="brig-stats-row">
          <div class="brig-stat-card">
            <div class="brig-stat-icon blue">&#9783;</div>
            <div class="brig-stat-value"><?= number_format($stat_total_records); ?></div>
            <div class="brig-stat-label">Total Records</div>
          </div>
          <div class="brig-stat-card">
            <div class="brig-stat-icon green">&#8369;</div>
            <div class="brig-stat-value">&#8369;<?= number_format($stat_total_resources, 0); ?></div>
            <div class="brig-stat-label">Total Resources</div>
          </div>
          <div class="brig-stat-card">
            <div class="brig-stat-icon amber">&#128100;</div>
            <div class="brig-stat-value"><?= number_format($stat_total_volunteers); ?></div>
            <div class="brig-stat-label">Total Volunteers</div>
          </div>
          <div class="brig-stat-card">
            <div class="brig-stat-icon purple">&#128197;</div>
            <div class="brig-stat-value"><?= $stat_total_days; ?></div>
            <div class="brig-stat-label">Total Days</div>
          </div>
        </div>

        <?php
          $partner_type_counts = isset($partner_type_counts) && is_array($partner_type_counts) ? $partner_type_counts : [];
          $partner_type_labels = [
            'Private_Sector' => 'Private Sector',
            'Public_Sector' => 'Public Sector',
            'International' => 'International',
            'Civil_Society_Organizations' => 'Civil Society Organizations'
          ];
          $partner_type_icons = [
            'Private_Sector' => 'blue',
            'Public_Sector' => 'green',
            'International' => 'amber',
            'Civil_Society_Organizations' => 'purple'
          ];
        ?>

        <div class="brig-partner-summary-head">
          <div class="brig-section-title">General Partner Type Summary</div>
          <div class="brig-section-sub">
            Distinct partners with contribution records for the selected period
          </div>
        </div>

        <div class="brig-stats-row">
          <?php foreach ($partner_type_labels as $type_key => $type_label): ?>
            <div class="brig-stat-card">
              <div class="brig-stat-icon <?= $partner_type_icons[$type_key]; ?>">&#128101;</div>
              <div class="brig-stat-value"><?= number_format($partner_type_counts[$type_key] ?? 0); ?></div>
              <div class="brig-stat-label"><?= htmlspecialchars($type_label); ?></div>
            </div>
          <?php endforeach; ?>
        </div>

        <?php
          $specific_partner_type_counts = isset($specific_partner_type_counts) && is_array($specific_partner_type_counts) ? $specific_partner_type_counts : [];
          $specific_partner_type_labels = [
            'Government' => 'Government',
            'INGO-International Non-Government Organizations' => 'International Non-Government Organization',
            'Others' => 'Others'
          ];
          $specific_partner_type_icons = [
            'Government' => 'blue',
            'INGO-International Non-Government Organizations' => 'amber',
            'Others' => 'purple'
          ];
        ?>

        <div class="brig-partner-summary-head">
          <div class="brig-section-title">Specific Partner Type Summary</div>
          <div class="brig-section-sub">
            Distinct partners with contribution records for the selected period
          </div>
        </div>

        <div class="brig-stats-row brig-stats-row-3">
          <?php foreach ($specific_partner_type_labels as $type_key => $type_label): ?>
            <div class="brig-stat-card">
              <div class="brig-stat-icon <?= $specific_partner_type_icons[$type_key]; ?>">&#128101;</div>
              <div class="brig-stat-value"><?= number_format($specific_partner_type_counts[$type_key] ?? 0); ?></div>
              <div class="brig-stat-label"><?= htmlspecialchars($type_label); ?></div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- ── FILTER BAR ── -->
        <form method="GET" action="<?= base_url(); ?>Brigada/brigada_summary_v2">
          <div class="brig-filter-bar">
            <span class="brig-filter-label">Month</span>
            <select name="month" class="brig-filter-select">
              <option value="">All Months</option>
              <?php
                $months = [1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',
                           7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December'];
                $cur_month = isset($filter_month) ? $filter_month : date('n');
                foreach ($months as $mn => $ml) {
                  $sel = ($mn == $cur_month) ? 'selected' : '';
                  echo "<option value=\"$mn\" $sel>$ml</option>";
                }
              ?>
            </select>

            <span class="brig-filter-label">Year</span>
            <select name="year" class="brig-filter-select">
              <?php
                $cur_year = isset($filter_year) ? $filter_year : date('Y');
                for ($y = date('Y'); $y >= (date('Y') - 5); $y--) {
                  $sel = ($y == $cur_year) ? 'selected' : '';
                  echo "<option value=\"$y\" $sel>$y</option>";
                }
              ?>
            </select>

            <div class="brig-filter-divider"></div>

            <button type="submit" class="brig-btn brig-btn-primary">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
              Apply Filter
            </button>

            <a href="<?= base_url(); ?>Brigada/brigada_summary_v2" class="brig-btn brig-btn-ghost">Reset</a>

            <div class="brig-filter-spacer"></div>

            <button type="button" class="brig-btn brig-btn-success" onclick="brigExportExcel('brig-main-table','brigada_summary_v2_<?= date('Y-m-d'); ?>')">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              Export Excel
            </button>

            <button type="button" class="brig-btn brig-btn-ghost" onclick="window.print()">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
              Print
            </button>
          </div>
        </form>

        <!-- ── SECTION HEADER ── -->
        <div class="brig-section-head">
          <div>
            <div class="brig-section-title"><?= !empty($cur_year) ? $cur_year : date('Y'); ?> School Records</div>
            <div class="brig-section-sub">
              Showing <?= !empty($data) ? count($data) : 0; ?> school(s)
              <?php if (!empty($cur_month)): ?>
                &nbsp;·&nbsp; <?= $months[(int)$cur_month] ?? ''; ?> <?= $cur_year; ?>
              <?php endif; ?>
            </div>
          </div>
          <input
            type="text"
            class="brig-search-box"
            id="brigSearch"
            placeholder="Search school..."
            oninput="brigSearchTable()"
          />
        </div>

        <!-- ── TABLE ── -->
        <div style="background: var(--c-surface);">
          <div class="brig-table-wrap">
            <table class="brig-table" id="brig-main-table">
              <thead>
                <tr>
                  <th class="th-idx">#</th>
                  <th class="th-school">School Name</th>
                  <?php foreach ($dates as $date): ?>
                    <th colspan="2">
                      <span class="brig-date-chip"><?= date('M d, Y', strtotime($date->c_date)); ?></span>
                    </th>
                  <?php endforeach; ?>
                  <th>Total Records</th>
                </tr>
                <tr class="brig-subhead">
                  <th></th>
                  <th class="th-school"></th>
                  <?php foreach ($dates as $date): ?>
                    <th>Resources</th>
                    <th>Volunteers</th>
                  <?php endforeach; ?>
                  <th>Records</th>
                </tr>
              </thead>

              <tbody id="brigTableBody">
                <?php if (!empty($data)): ?>
                  <?php
                    $c = 1;
                    $grand_total_resources  = 0;
                    $grand_total_volunteers = 0;
                    $date_totals = [];

                    foreach ($dates as $date) {
                      $date_totals[$date->c_date] = ['resources'=>0,'volunteers'=>0,'records'=>0];
                    }
                  ?>

                  <?php foreach ($data as $school_id => $school_data): ?>
                    <tr>
                      <td class="td-idx"><?= $c++; ?></td>
                      <td class="td-school"><?= htmlspecialchars($school_data['schoolName']); ?></td>

                      <?php
                        $school_total_records = 0;
                        foreach ($dates as $date):
                          $dk = $date->c_date;
                          $sd = isset($school_data['data'][$dk])
                                ? $school_data['data'][$dk]
                                : ['total_resources'=>0,'total_volunteers'=>0,'total_records'=>0];

                          $date_totals[$dk]['resources']  += $sd['total_resources'];
                          $date_totals[$dk]['volunteers'] += $sd['total_volunteers'];
                          $date_totals[$dk]['records']    += $sd['total_records'];

                          $grand_total_resources  += $sd['total_resources'];
                          $grand_total_volunteers += $sd['total_volunteers'];
                          $school_total_records   += $sd['total_records'];
                      ?>
                        <td class="td-num td-res">&#8369;<?= number_format($sd['total_resources'], 2); ?></td>
                        <td class="td-num td-vol"><?= number_format($sd['total_volunteers']); ?></td>
                      <?php endforeach; ?>

                      <td class="td-total"><?= number_format($school_total_records); ?></td>
                    </tr>
                  <?php endforeach; ?>

                  <tr class="brig-subtotal">
                    <td class="td-idx">—</td>
                    <td class="td-school" style="font-size:11px;letter-spacing:0.05em;">TOTAL PER DATE</td>
                    <?php foreach ($dates as $date): ?>
                      <td class="td-num td-res"><strong>&#8369;<?= number_format($date_totals[$date->c_date]['resources'], 2); ?></strong></td>
                      <td class="td-num td-vol"><strong><?= number_format($date_totals[$date->c_date]['volunteers']); ?></strong></td>
                    <?php endforeach; ?>
                    <td class="td-total"><?= number_format(array_sum(array_column($date_totals, 'records'))); ?></td>
                  </tr>

                  <tr class="brig-grand">
                    <td class="td-idx" style="color:rgba(255,255,255,0.45)">&#10022;</td>
                    <td class="td-school" style="font-size:11px;letter-spacing:0.07em;color:#fff">GRAND TOTALS</td>
                    <?php foreach ($dates as $date): ?>
                      <td class="td-num td-res"><strong>&#8369;<?= number_format($date_totals[$date->c_date]['resources'], 2); ?></strong></td>
                      <td class="td-num td-vol"><strong><?= number_format($date_totals[$date->c_date]['volunteers']); ?></strong></td>
                    <?php endforeach; ?>
                    <td class="td-total" style="color:#fff"><?= number_format(array_sum(array_column($date_totals, 'records'))); ?></td>
                  </tr>

                <?php else: ?>
                  <tr>
                    <td colspan="<?= 3 + (count($dates) * 2); ?>">
                      <div class="brig-empty">
                        <div class="brig-empty-icon">&#128269;</div>
                        <div class="brig-empty-title">No records found</div>
                        <div class="brig-empty-sub">Try adjusting the month or year filter.</div>
                      </div>
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <div class="brig-table-footer">
            <span>
              Showing <span class="brig-rows-badge" id="brigRowCount"><?= !empty($data) ? count($data) : 0; ?></span> schools
            </span>
            <span style="display:flex;gap:16px;flex-wrap:wrap;">
              <span>Generated: <strong><?= date('Y-m-d'); ?></strong></span>
              <span>System: <strong>DepEd Brigada MIS</strong></span>
            </span>
          </div>
        </div>

      </div>
    </div>
  </div>

  <?php include('templates/footer.php'); ?>
</div>

<script>
  function brigSearchTable() {
    var q    = document.getElementById('brigSearch').value.toLowerCase();
    var rows = document.querySelectorAll('#brigTableBody tr');
    var vis  = 0;

    rows.forEach(function(row) {
      if (row.classList.contains('brig-subtotal') || row.classList.contains('brig-grand')) {
        row.style.display = '';
        return;
      }

      var school = row.querySelector('.td-school');
      if (!school) {
        row.style.display = '';
        return;
      }

      if (school.textContent.toLowerCase().indexOf(q) > -1) {
        row.style.display = '';
        vis++;
      } else {
        row.style.display = 'none';
      }
    });

    var badge = document.getElementById('brigRowCount');
    if (badge) badge.textContent = vis;
  }

  function brigExportExcel(tableId, filename) {
    var table = document.getElementById(tableId);
    if (!table) return;

    var clone = table.cloneNode(true);
    clone.querySelectorAll('button, a').forEach(function(el) {
      el.parentNode.removeChild(el);
    });

    var html = clone.outerHTML;
    var dataType = 'application/vnd.ms-excel';
    filename = (filename || 'brigada_summary') + '.xls';

    var link = document.createElement('a');
    document.body.appendChild(link);

    if (navigator.msSaveOrOpenBlob) {
      var blob = new Blob(['\ufeff', html], { type: dataType });
      navigator.msSaveOrOpenBlob(blob, filename);
    } else {
      link.href = 'data:' + dataType + ', ' + html.replace(/ /g, '%20');
      link.download = filename;
      link.click();
    }

    document.body.removeChild(link);
  }
</script>
