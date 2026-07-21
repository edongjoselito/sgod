<?php
if (!function_exists('brigada_escape')) {
    function brigada_escape($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('brigada_text_contains_keywords')) {
    function brigada_text_contains_keywords($value, array $keywords)
    {
        $value = strtolower(trim((string) $value));
        if ($value === '') {
            return false;
        }

        foreach ($keywords as $keyword) {
            $keyword = strtolower(trim((string) $keyword));
            if ($keyword !== '' && strpos($value, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('brigada_collect_entries')) {
    function brigada_collect_entries(array $accomplishmentRows, array $memoRows, array $keywords, array $options = array())
    {
        $entries = array();
        $includeAccomplishments = !isset($options['accomplishments']) || $options['accomplishments'];
        $includeMemos = !isset($options['memos']) || $options['memos'];

        if ($includeAccomplishments) {
            foreach ($accomplishmentRows as $row) {
                $haystacks = array(
                    isset($row->activity) ? $row->activity : '',
                    isset($row->particulars) ? $row->particulars : '',
                    isset($row->notes) ? $row->notes : '',
                    isset($row->remarks) ? $row->remarks : '',
                    isset($row->perIndicators) ? $row->perIndicators : '',
                );

                $matched = false;
                foreach ($haystacks as $haystack) {
                    if (brigada_text_contains_keywords($haystack, $keywords)) {
                        $matched = true;
                        break;
                    }
                }

                if (!$matched) {
                    continue;
                }

                $entries[] = array(
                    'reference' => trim((string) (!empty($row->dateConducted) ? $row->dateConducted : $row->targetDate)),
                    'source' => trim((string) $row->activityCategory),
                    'details' => trim((string) $row->activity),
                    'href' => ''
                );
            }
        }

        if ($includeMemos) {
            foreach ($memoRows as $row) {
                $haystacks = array(
                    isset($row->title) ? $row->title : '',
                    isset($row->memoNo) ? $row->memoNo : '',
                );

                $matched = false;
                foreach ($haystacks as $haystack) {
                    if (brigada_text_contains_keywords($haystack, $keywords)) {
                        $matched = true;
                        break;
                    }
                }

                if (!$matched) {
                    continue;
                }

                $href = '';
                if (!empty($row->fileName)) {
                    $href = base_url() . 'upload/memo/' . rawurlencode($row->fileName);
                }

                $entries[] = array(
                    'reference' => trim((string) $row->memoNo),
                    'source' => 'Memo',
                    'details' => trim((string) $row->title),
                    'href' => $href
                );
            }
        }

        return $entries;
    }
}

$accomplishmentRows = is_array($accomplishments) ? $accomplishments : array();
$memoRows = is_array($memos) ? $memos : array();
$brigadaAccomplishmentCount = isset($brigadaAccomplishmentCount) ? (int) $brigadaAccomplishmentCount : 0;
$brigadaUpdateCount = isset($brigadaUpdateCount) ? (int) $brigadaUpdateCount : 0;
$brigadaMemoCount = isset($brigadaMemoCount) ? (int) $brigadaMemoCount : 0;
$totalBrigadaItems = count($accomplishmentRows) + $brigadaMemoCount;
$latestActivityDate = '';

foreach ($accomplishmentRows as $row) {
    $dateLabel = trim((string) (isset($row->dateConducted) ? $row->dateConducted : ''));
    if ($dateLabel !== '') {
        $latestActivityDate = $dateLabel;
        break;
    }
}

$brigadaSections = array(
    array(
        'id' => 'school-preparedness',
        'title' => 'School Preparedness',
        'copy' => 'Preparation and readiness-related entries for Brigada Eskwela implementation.',
        'badge' => 'Preparedness',
        'entries' => brigada_collect_entries(
            $accomplishmentRows,
            $memoRows,
            array('school preparedness', 'readiness', 'school needs assessment', 'preparation', 'kick-off', 'implementation week'),
            array('memos' => false)
        ),
    ),
    array(
        'id' => 'spc-report',
        'title' => 'SPC Report',
        'copy' => 'Brigada records connected to school preparedness checklists, forms, and monitoring results.',
        'badge' => 'SPC',
        'entries' => brigada_collect_entries(
            $accomplishmentRows,
            $memoRows,
            array('spc', 'checklist', 'form 1', 'form 3', 'school needs assessment', 'monitoring results')
        ),
    ),
    array(
        'id' => 'summary-report',
        'title' => 'Summary Report',
        'copy' => 'Report-oriented Brigada Eskwela entries, including daily and summary reporting references.',
        'badge' => 'Reports',
        'entries' => brigada_collect_entries(
            $accomplishmentRows,
            $memoRows,
            array('summary report', 'daily summary report', 'report')
        ),
    ),
    array(
        'id' => 'partners',
        'title' => 'Partners',
        'copy' => 'Partner, donation, sponsorship, and assistance-related Brigada Eskwela records.',
        'badge' => 'Partners',
        'entries' => brigada_collect_entries(
            $accomplishmentRows,
            $memoRows,
            array('partner', 'partnership', 'donation', 'assistance', 'sponsor', 'adopt-a-school')
        ),
    ),
);
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <?php include('includes/page-title.php'); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Responsive bootstrap 4 admin template" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.ico">
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />

        <style>
            :root {
                --brigada-primary: #1f6f43;
                --brigada-primary-dark: #174f30;
                --brigada-highlight: #f0f7ec;
                --brigada-ink: #21312a;
                --brigada-muted: #61756a;
                --brigada-border: rgba(31, 111, 67, 0.12);
                --brigada-shadow: 0 20px 45px rgba(23, 79, 48, 0.12);
            }

            body {
                background:
                    radial-gradient(circle at top left, rgba(31, 111, 67, 0.10), transparent 24%),
                    linear-gradient(180deg, #f3f8f4 0%, #edf5ef 100%);
            }

            .content-page {
                background: transparent;
            }

            .brigada-shell {
                position: relative;
                padding-top: 22px;
                padding-bottom: 28px;
            }

            .brigada-hero {
                overflow: hidden;
                border-radius: 26px;
                color: #ffffff;
                box-shadow: var(--brigada-shadow);
                background:
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.18), transparent 28%),
                    linear-gradient(135deg, var(--brigada-primary-dark) 0%, var(--brigada-primary) 60%, #3a9f64 100%);
            }

            .brigada-hero-body {
                padding: 32px;
            }

            .brigada-eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 14px;
                border: 1px solid rgba(255, 255, 255, 0.18);
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.12);
                font-size: 0.79rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .brigada-title {
                margin: 18px 0 10px;
                color: #ffffff;
                font-size: clamp(2rem, 3vw, 2.8rem);
                font-weight: 700;
                letter-spacing: -0.03em;
            }

            .brigada-subtitle {
                max-width: 700px;
                margin: 0;
                color: rgba(255, 255, 255, 0.82);
                font-size: 0.97rem;
                line-height: 1.6;
            }

            .brigada-meta {
                margin-top: 18px;
                color: rgba(255, 255, 255, 0.82);
                font-size: 0.9rem;
            }

            .brigada-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                margin-top: 26px;
            }

            .brigada-action {
                display: inline-flex;
                align-items: center;
                gap: 9px;
                padding: 12px 18px;
                border-radius: 14px;
                font-weight: 700;
                text-decoration: none;
                transition: transform 0.22s ease, box-shadow 0.22s ease;
            }

            .brigada-action:hover {
                transform: translateY(-2px);
                text-decoration: none;
            }

            .brigada-action--light {
                color: var(--brigada-primary-dark);
                background: linear-gradient(135deg, #ffffff 0%, #eef8f1 100%);
                box-shadow: 0 12px 24px rgba(18, 44, 28, 0.12);
            }

            .brigada-action--ghost {
                color: #ffffff;
                border: 1px solid rgba(255, 255, 255, 0.22);
                background: rgba(255, 255, 255, 0.10);
            }

            .summary-card {
                height: 100%;
                margin-top: 24px;
                border: 1px solid var(--brigada-border);
                border-radius: 22px;
                background: #ffffff;
                box-shadow: 0 16px 30px rgba(33, 49, 42, 0.08);
            }

            .summary-card .card-body {
                padding: 24px;
            }

            .summary-label {
                color: var(--brigada-muted);
                font-size: 0.82rem;
                font-weight: 700;
                letter-spacing: 0.06em;
                text-transform: uppercase;
            }

            .summary-value {
                margin-top: 10px;
                color: var(--brigada-ink);
                font-size: 2rem;
                font-weight: 700;
                line-height: 1;
            }

            .summary-copy {
                margin-top: 12px;
                color: var(--brigada-muted);
                font-size: 0.92rem;
                line-height: 1.55;
            }

            .section-chip-row {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                margin-top: 26px;
            }

            .section-chip {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 10px 14px;
                border: 1px solid rgba(31, 111, 67, 0.12);
                border-radius: 999px;
                color: var(--brigada-primary-dark);
                background: #ffffff;
                box-shadow: 0 10px 22px rgba(33, 49, 42, 0.07);
                font-size: 0.88rem;
                font-weight: 700;
                text-decoration: none;
            }

            .section-chip:hover {
                color: var(--brigada-primary-dark);
                text-decoration: none;
                transform: translateY(-1px);
            }

            .panel-card {
                margin-top: 26px;
                border: 1px solid var(--brigada-border);
                border-radius: 24px;
                background: #ffffff;
                box-shadow: 0 16px 35px rgba(33, 49, 42, 0.08);
            }

            .panel-card .card-body {
                padding: 28px;
            }

            .panel-header {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: space-between;
                gap: 14px;
                margin-bottom: 18px;
            }

            .panel-title {
                margin: 0;
                color: var(--brigada-ink);
                font-size: 1.25rem;
                font-weight: 700;
            }

            .panel-copy {
                margin: 6px 0 0;
                color: var(--brigada-muted);
                font-size: 0.93rem;
            }

            .panel-badge {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 14px;
                border-radius: 999px;
                color: var(--brigada-primary-dark);
                background: var(--brigada-highlight);
                font-size: 0.84rem;
                font-weight: 700;
            }

            .table-shell {
                overflow-x: auto;
                border: 1px solid rgba(31, 111, 67, 0.08);
                border-radius: 18px;
            }

            .table thead th {
                border-bottom: none;
                color: var(--brigada-primary-dark);
                background: rgba(31, 111, 67, 0.06);
                font-size: 0.8rem;
                font-weight: 700;
                letter-spacing: 0.05em;
                text-transform: uppercase;
            }

            .table td,
            .table th {
                padding: 14px 16px;
                vertical-align: top;
            }

            .table tbody tr:last-child td {
                border-bottom: none;
            }

            .table-note {
                color: var(--brigada-muted);
                font-size: 0.88rem;
            }

            .empty-state {
                padding: 28px;
                border: 1px dashed rgba(31, 111, 67, 0.18);
                border-radius: 18px;
                color: var(--brigada-muted);
                background: rgba(31, 111, 67, 0.03);
                text-align: center;
            }

            .section-anchor {
                scroll-margin-top: 110px;
            }
        </style>
    </head>

    <body>

        <div id="wrapper">

            <?php include('includes/top-bar.php') ?>
            <?php include('includes/sidebar.php') ?>

            <div class="content-page">
                <div class="content">
                    <div class="container-fluid brigada-shell">

                        <div class="brigada-hero">
                            <div class="brigada-hero-body">
                                <h1 class="brigada-title">Brigada Eskwela</h1>
                                <p class="brigada-subtitle">
                                    Track Brigada Eskwela-related accomplishment entries and memos in one workspace.
                                </p>
                                <div class="brigada-meta">
                                    <?php if ($latestActivityDate !== ''): ?>
                                        Latest related activity: <?= brigada_escape($latestActivityDate); ?>
                                    <?php endif; ?>
                                </div>
                                <div class="brigada-actions">
                                    <a href="<?= base_url(); ?>Page/viewSecAccomplishments" class="brigada-action brigada-action--light">
                                        <i class="mdi mdi-file-check-outline"></i>
                                        <span>Open Accomplishments</span>
                                    </a>
                                    <a href="<?= base_url(); ?>Page/memo" class="brigada-action brigada-action--ghost">
                                        <i class="mdi mdi-file-document-outline"></i>
                                        <span>Open Memo Library</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-xl-3">
                                <div class="card summary-card">
                                    <div class="card-body">
                                        <div class="summary-label">Related Records</div>
                                        <div class="summary-value"><?= number_format($totalBrigadaItems); ?></div>
                                        <div class="summary-copy">Combined Brigada Eskwela accomplishments, updates, and memos currently visible to SMN.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="card summary-card">
                                    <div class="card-body">
                                        <div class="summary-label">Accomplishments</div>
                                        <div class="summary-value"><?= number_format($brigadaAccomplishmentCount); ?></div>
                                        <div class="summary-copy">Completed Brigada Eskwela work logged under SMN accomplishment records.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="card summary-card">
                                    <div class="card-body">
                                        <div class="summary-label">Updates</div>
                                        <div class="summary-value"><?= number_format($brigadaUpdateCount); ?></div>
                                        <div class="summary-copy">Brigada Eskwela update entries posted for monitoring and coordination.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="card summary-card">
                                    <div class="card-body">
                                        <div class="summary-label">Memos</div>
                                        <div class="summary-value"><?= number_format($brigadaMemoCount); ?></div>
                                        <div class="summary-copy">Division memos with Brigada Eskwela references available to your group.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="section-chip-row">
                            <?php foreach ($brigadaSections as $section): ?>
                                <a href="#<?= brigada_escape($section['id']); ?>" class="section-chip">
                                    <i class="mdi mdi-chevron-right"></i>
                                    <span><?= brigada_escape($section['title']); ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>

                        <?php foreach ($brigadaSections as $section): ?>
                            <div class="card panel-card section-anchor" id="<?= brigada_escape($section['id']); ?>">
                                <div class="card-body">
                                    <div class="panel-header">
                                        <div>
                                            <h2 class="panel-title"><?= brigada_escape($section['title']); ?></h2>
                                            <p class="panel-copy"><?= brigada_escape($section['copy']); ?></p>
                                        </div>
                                        <div class="panel-badge">
                                            <i class="mdi mdi-folder-outline"></i>
                                            <span><?= brigada_escape($section['badge']); ?></span>
                                        </div>
                                    </div>

                                    <?php if (!empty($section['entries'])): ?>
                                        <div class="table-shell">
                                            <table class="table table-striped mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Reference</th>
                                                        <th>Source</th>
                                                        <th>Details</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($section['entries'] as $entry): ?>
                                                        <tr>
                                                            <td><?= brigada_escape($entry['reference']); ?></td>
                                                            <td><?= brigada_escape($entry['source']); ?></td>
                                                            <td>
                                                                <?php if (!empty($entry['href'])): ?>
                                                                    <a href="<?= brigada_escape($entry['href']); ?>" target="_blank" rel="noopener noreferrer"><?= brigada_escape($entry['details']); ?></a>
                                                                <?php else: ?>
                                                                    <?= brigada_escape($entry['details']); ?>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <div class="empty-state">
                                            No records matched <?= brigada_escape($section['title']); ?> yet.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>

                <?php include('includes/footer.php'); ?>
            </div>
        </div>

        <?php include('includes/right-sidebar.php'); ?>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>

    </body>
</html>
