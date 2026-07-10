<?php
$section = $this->session->userdata('section');
$secGroup = $this->session->userdata('secGroup');
$username = $this->session->userdata('username');

$totalAccomplishments = is_array($data) ? count($data) : 0;
$myAccomplishments = 0;

if (!empty($data)) {
    foreach ($data as $acc) {
        if (isset($acc->added_by) && $acc->added_by === $username) {
            $myAccomplishments++;
        }
    }
}

$metrics = array(
    array(
        'value' => $totalAccomplishments,
        'label' => 'Total Accomplishments',
        'icon' => 'mdi-file-document-check-outline',
        'accent' => '#3c40c6',
        'icon_bg' => 'rgba(60, 64, 198, 0.12)',
        'icon_color' => '#3c40c6',
        'badge' => 'All'
    ),
    array(
        'value' => $myAccomplishments,
        'label' => 'My Accomplishments',
        'icon' => 'mdi-account-check-outline',
        'accent' => '#565de8',
        'icon_bg' => 'rgba(86, 93, 232, 0.14)',
        'icon_color' => '#565de8',
        'badge' => 'Personal'
    )
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
        <link href="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />

        <style>
            :root {
                --user-navy: #272b8c;
                --user-blue: #3c40c6;
                --user-teal: #565de8;
                --user-gold: #7a80ff;
                --user-ink: #23275d;
                --user-muted: #7b7fa7;
                --user-border: rgba(60, 64, 198, 0.12);
                --user-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            }

            body {
                background:
                    radial-gradient(circle at top left, rgba(60, 64, 198, 0.10), transparent 24%),
                    linear-gradient(180deg, #f4f8fc 0%, #eef4fa 100%);
            }

            .content-page {
                background: transparent;
            }

            .user-shell {
                position: relative;
                padding-bottom: 28px;
            }

            .user-shell::before {
                content: "";
                position: absolute;
                inset: 24px 0 auto;
                height: 240px;
                border-radius: 30px;
                background: linear-gradient(135deg, rgba(45, 127, 249, 0.09), rgba(15, 159, 154, 0.08));
                z-index: 0;
            }

            .user-shell > * {
                position: relative;
                z-index: 1;
            }

            .dashboard-hero {
                margin-top: 20px;
                border-radius: 28px;
                overflow: hidden;
                color: #ffffff;
                box-shadow: var(--user-shadow);
                background:
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 32%),
                    linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
            }

            .dashboard-hero-body {
                padding: 32px;
            }

            .hero-eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 14px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.12);
                border: 1px solid rgba(255, 255, 255, 0.18);
                font-size: 0.8rem;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .hero-title {
                margin: 18px 0 12px;
                color: #ffffff;
                font-size: clamp(2rem, 3vw, 2.7rem);
                line-height: 1.05;
                font-weight: 700;
                letter-spacing: -0.03em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .hero-copy {
                color: rgba(255, 255, 255, 0.80);
                margin-bottom: 0;
            }

            .metric-card {
                height: 100%;
                border: 1px solid rgba(60, 64, 198, 0.08);
                border-radius: 22px;
                background: rgba(255, 255, 255, 0.95);
                padding: 24px;
                box-shadow: var(--user-shadow);
                overflow: hidden;
                position: relative;
            }

            .metric-card::before {
                content: "";
                position: absolute;
                inset: 0;
                opacity: 0.04;
                background: linear-gradient(135deg, var(--user-blue), var(--user-teal));
                z-index: 0;
            }

            .metric-body {
                position: relative;
                z-index: 1;
            }

            .metric-icon {
                width: 52px;
                height: 52px;
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 26px;
                margin-bottom: 16px;
            }

            .metric-value {
                font-size: 2.5rem;
                font-weight: 700;
                color: var(--user-ink);
                line-height: 1;
                margin-bottom: 8px;
            }

            .metric-label {
                color: var(--user-muted);
                font-weight: 600;
                font-size: 0.95rem;
                margin-bottom: 6px;
            }

            .metric-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 6px 12px;
                border-radius: 999px;
                background: rgba(60, 64, 198, 0.08);
                color: var(--user-blue);
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.06em;
            }

            .panel-card {
                border: 1px solid rgba(60, 64, 198, 0.08);
                border-radius: 22px;
                background: rgba(255, 255, 255, 0.95);
                padding: 28px;
                box-shadow: var(--user-shadow);
            }

            .panel-kicker {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 14px;
                border-radius: 999px;
                background: rgba(60, 64, 198, 0.08);
                color: var(--user-blue);
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.06em;
                margin-bottom: 12px;
            }

            .panel-title {
                color: var(--user-ink);
                font-size: 1.5rem;
                font-weight: 700;
                margin-bottom: 8px;
            }

            .panel-copy {
                color: var(--user-muted);
                margin-bottom: 24px;
            }

            .quick-action {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 18px 20px;
                border: 1px solid rgba(60, 64, 198, 0.08);
                border-radius: 16px;
                background: #ffffff;
                transition: all 0.25s ease;
                text-decoration: none;
                margin-bottom: 12px;
            }

            .quick-action:hover {
                border-color: var(--user-blue);
                box-shadow: 0 8px 24px rgba(60, 64, 198, 0.12);
                transform: translateY(-2px);
            }

            .quick-action-label {
                color: var(--user-ink);
                font-weight: 700;
                font-size: 1rem;
            }

            .quick-action small {
                color: var(--user-muted);
                font-size: 0.85rem;
            }

            .quick-action-icon {
                width: 44px;
                height: 44px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 22px;
                background: rgba(60, 64, 198, 0.08);
                color: var(--user-blue);
            }

            @media (max-width: 767.98px) {
                .dashboard-hero-body {
                    padding: 22px;
                }

                .hero-title {
                    font-size: 1.9rem;
                }
            }
        </style>
    </head>

    <body>
        <div id="wrapper">
            <?php include('includes/top-bar.php'); ?>
            <?php include('includes/sidebar.php') ?>

            <div class="content-page">
                <div class="content">
                    <div class="container-fluid user-shell">
                        <div class="row">
                            <div class="col-12">
                                <div class="dashboard-hero">
                                    <div class="dashboard-hero-body">
                                        <span class="hero-eyebrow">
                                            <i class="mdi mdi-account-circle-outline"></i>
                                            Section User Dashboard
                                        </span>
                                        <h1 class="hero-title">Welcome, <?= htmlspecialchars($this->session->userdata('fName'), ENT_QUOTES, 'UTF-8'); ?>!</h1>
                                        <p class="hero-copy">
                                            Manage your accomplishments for <?= htmlspecialchars($section, ENT_QUOTES, 'UTF-8'); ?> under <?= htmlspecialchars($secGroup, ENT_QUOTES, 'UTF-8'); ?>.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <?php foreach ($metrics as $metric) { ?>
                                <div class="col-xl-6 col-md-6 mb-4">
                                    <div class="metric-card">
                                        <div class="metric-body">
                                            <div class="metric-icon" style="background: <?= $metric['icon_bg'] ?>; color: <?= $metric['icon_color'] ?>;">
                                                <i class="mdi <?= $metric['icon'] ?>"></i>
                                            </div>
                                            <div class="metric-value"><?= $metric['value'] ?></div>
                                            <div class="metric-label"><?= $metric['label'] ?></div>
                                            <span class="metric-badge"><?= $metric['badge'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="row mt-4">
                            <div class="col-xl-12 mb-4">
                                <div class="panel-card">
                                    <div class="panel-kicker">Whereabouts</div>
                                    <h4 class="panel-title">Post Your Location</h4>
                                    <p class="panel-copy">
                                        Let others know where you are and what you're working on today.
                                    </p>

                                    <div class="mini-calendar-wrapper" style="margin-bottom: 16px;">
                                        <div class="mini-calendar-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                            <button class="mini-calendar-nav" onclick="changeMiniMonth(-1)" style="background: none; border: none; cursor: pointer; color: var(--user-blue); padding: 4px 8px; border-radius: 6px;">
                                                <i class="mdi mdi-chevron-left"></i>
                                            </button>
                                            <h6 id="miniCalendarMonthYear" style="color: var(--user-ink); font-weight: 700; margin: 0;"></h6>
                                            <button class="mini-calendar-nav" onclick="changeMiniMonth(1)" style="background: none; border: none; cursor: pointer; color: var(--user-blue); padding: 4px 8px; border-radius: 6px;">
                                                <i class="mdi mdi-chevron-right"></i>
                                            </button>
                                        </div>
                                        <div class="mini-calendar-grid" style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; text-align: center;">
                                            <div class="mini-day-header" style="font-size: 0.7rem; color: var(--user-muted); font-weight: 600; padding: 4px;">S</div>
                                            <div class="mini-day-header" style="font-size: 0.7rem; color: var(--user-muted); font-weight: 600; padding: 4px;">M</div>
                                            <div class="mini-day-header" style="font-size: 0.7rem; color: var(--user-muted); font-weight: 600; padding: 4px;">T</div>
                                            <div class="mini-day-header" style="font-size: 0.7rem; color: var(--user-muted); font-weight: 600; padding: 4px;">W</div>
                                            <div class="mini-day-header" style="font-size: 0.7rem; color: var(--user-muted); font-weight: 600; padding: 4px;">T</div>
                                            <div class="mini-day-header" style="font-size: 0.7rem; color: var(--user-muted); font-weight: 600; padding: 4px;">F</div>
                                            <div class="mini-day-header" style="font-size: 0.7rem; color: var(--user-muted); font-weight: 600; padding: 4px;">S</div>
                                        </div>
                                        <div class="mini-calendar-days" id="miniCalendarDays" style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px;"></div>
                                    </div>

                                    <a href="<?= base_url(); ?>Page/whereabouts" class="btn btn-gradient-primary" style="width: 100%; margin-top: 20px;">
                                        <i class="mdi mdi-map-marker-radius-outline"></i> Post Whereabouts
                                    </a>

                                    <?php if (!empty($whereabouts)): ?>
                                        <div style="margin-top: 24px;">
                                            <h6 style="color: var(--user-ink); font-weight: 600; margin-bottom: 12px;">Recent Posts</h6>
                                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 12px;">
                                                <?php foreach (array_slice($whereabouts, 0, 6) as $where): ?>
                                                    <div style="padding: 12px; border: 1px solid rgba(60, 64, 198, 0.08); border-radius: 12px; background: #ffffff;">
                                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                                            <span style="font-weight: 600; color: var(--user-ink); font-size: 0.9rem;"><?= htmlspecialchars($where->date, ENT_QUOTES, 'UTF-8'); ?></span>
                                                            <span style="font-size: 0.75rem; padding: 4px 8px; border-radius: 999px; background: rgba(60, 64, 198, 0.08); color: var(--user-blue);"><?= htmlspecialchars($where->status, ENT_QUOTES, 'UTF-8'); ?></span>
                                                        </div>
                                                        <div style="color: var(--user-muted); font-size: 0.85rem;"><?= htmlspecialchars($where->location, ENT_QUOTES, 'UTF-8'); ?></div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include('includes/footer.php'); ?>
            </div>
        </div>

        <!-- Whereabouts Modal -->
        <div id="whereaboutsModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 9999;">
            <div style="background: #ffffff; border-radius: 22px; padding: 28px; max-width: 500px; width: 90%; box-shadow: 0 24px 60px rgba(15, 23, 42, 0.2);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="color: var(--user-ink); font-weight: 700; margin: 0;">Post Whereabouts</h3>
                    <button onclick="closeWhereaboutsModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--user-muted);">&times;</button>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="color: var(--user-ink); font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; display: block;">Selected Dates</label>
                    <ul id="selectedDatesList" style="list-style: none; padding: 0; margin: 0; color: var(--user-muted); font-size: 0.9rem;"></ul>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="color: var(--user-ink); font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; display: block;">Status</label>
                    <select id="modalStatus" style="width: 100%; padding: 12px 16px; border: 1px solid rgba(60, 64, 198, 0.12); border-radius: 12px; font-size: 0.95rem;">
                        <option value="In Office">In Office</option>
                        <option value="Out of Office">Out of Office</option>
                        <option value="On Official Business">On Official Business</option>
                        <option value="On Leave">On Leave</option>
                        <option value="On Field Work">On Field Work</option>
                    </select>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="color: var(--user-ink); font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; display: block;">Location <span style="color: #ef4444;">*</span></label>
                    <input type="text" id="modalLocation" placeholder="e.g., Main Office, School X" style="width: 100%; padding: 12px 16px; border: 1px solid rgba(60, 64, 198, 0.12); border-radius: 12px; font-size: 0.95rem;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="color: var(--user-ink); font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; display: block;">Activity <span style="color: #ef4444;">*</span></label>
                    <textarea id="modalActivity" rows="3" placeholder="What are you working on?" style="width: 100%; padding: 12px 16px; border: 1px solid rgba(60, 64, 198, 0.12); border-radius: 12px; font-size: 0.95rem; resize: vertical;"></textarea>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="color: var(--user-ink); font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; display: block;">Notes</label>
                    <textarea id="modalNotes" rows="2" placeholder="Any additional details..." style="width: 100%; padding: 12px 16px; border: 1px solid rgba(60, 64, 198, 0.12); border-radius: 12px; font-size: 0.95rem; resize: vertical;"></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <button onclick="closeWhereaboutsModal()" style="padding: 12px 28px; border: 1px solid rgba(60, 64, 198, 0.08); border-radius: 12px; background: #eef3f8; color: var(--user-ink); font-weight: 600; cursor: pointer; transition: all 0.25s ease;">Cancel</button>
                    <button onclick="submitWhereabouts()" style="padding: 12px 28px; border: none; border-radius: 12px; background: linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%); color: #ffffff; font-weight: 600; cursor: pointer; transition: all 0.25s ease;">Post Whereabouts</button>
                </div>
            </div>
        </div>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>

        <script>
            let miniCurrentDate = new Date();
            let selectedDates = new Set();
            let isDragging = false;
            let dragStartDay = null;
            let datesWithActivities = new Set();

            <?php if (!empty($whereabouts)): ?>
                <?php foreach ($whereabouts as $where): ?>
                    datesWithActivities.add('<?= $where->date; ?>');
                <?php endforeach; ?>
            <?php endif; ?>

            function renderMiniCalendar() {
                const year = miniCurrentDate.getFullYear();
                const month = miniCurrentDate.getMonth();
                const today = new Date();

                const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                document.getElementById('miniCalendarMonthYear').textContent = `${monthNames[month]} ${year}`;

                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();

                const miniCalendarDays = document.getElementById('miniCalendarDays');
                miniCalendarDays.innerHTML = '';

                for (let i = 0; i < firstDay; i++) {
                    const emptyDay = document.createElement('div');
                    emptyDay.style.padding = '6px';
                    miniCalendarDays.appendChild(emptyDay);
                }

                for (let day = 1; day <= daysInMonth; day++) {
                    const dayElement = document.createElement('div');
                    dayElement.textContent = day;
                    dayElement.style.padding = '6px';
                    dayElement.style.borderRadius = '6px';
                    dayElement.style.cursor = 'pointer';
                    dayElement.style.fontSize = '0.8rem';
                    dayElement.style.fontWeight = '600';
                    dayElement.style.color = 'var(--user-ink)';
                    dayElement.style.background = '#f8f9ff';
                    dayElement.style.transition = 'all 0.25s ease';
                    dayElement.style.userSelect = 'none';

                    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                    if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                        dayElement.style.background = 'linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%)';
                        dayElement.style.color = '#ffffff';
                    } else if (datesWithActivities.has(dateStr)) {
                        dayElement.style.background = 'linear-gradient(135deg, #0f9f9a 0%, #2d7ff9 100%)';
                        dayElement.style.color = '#ffffff';
                    }

                    if (selectedDates.has(dateStr)) {
                        dayElement.style.background = 'linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%)';
                        dayElement.style.color = '#ffffff';
                    }

                    dayElement.addEventListener('mousedown', (e) => {
                        e.preventDefault();
                        isDragging = true;
                        dragStartDay = day;
                        
                        if (selectedDates.has(dateStr)) {
                            selectedDates.delete(dateStr);
                        } else {
                            selectedDates.add(dateStr);
                        }
                        
                        updateDayStyle(dayElement, dateStr, today, month, year);
                    });

                    dayElement.addEventListener('mouseenter', () => {
                        if (isDragging && dragStartDay !== null) {
                            const start = Math.min(dragStartDay, day);
                            const end = Math.max(dragStartDay, day);
                            
                            for (let d = start; d <= end; d++) {
                                const ds = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                                selectedDates.add(ds);
                            }
                            renderMiniCalendar();
                        } else if (!isDragging) {
                            if (!(day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) && !selectedDates.has(dateStr)) {
                                dayElement.style.background = 'rgba(60, 64, 198, 0.1)';
                            }
                        }
                    });

                    dayElement.addEventListener('mouseleave', () => {
                        if (!isDragging) {
                            updateDayStyle(dayElement, dateStr, today, month, year);
                        }
                    });

                    dayElement.addEventListener('click', () => {
                        if (selectedDates.size >= 1) {
                            showWhereaboutsModal();
                        }
                    });

                    miniCalendarDays.appendChild(dayElement);
                }
            }

            function updateDayStyle(dayElement, dateStr, today, month, year) {
                const day = parseInt(dayElement.textContent);
                
                if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                    dayElement.style.background = 'linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%)';
                    dayElement.style.color = '#ffffff';
                } else if (selectedDates.has(dateStr)) {
                    dayElement.style.background = 'linear-gradient(135deg, #0f9f9a 0%, #2d7ff9 100%)';
                    dayElement.style.color = '#ffffff';
                } else {
                    dayElement.style.background = '#f8f9ff';
                    dayElement.style.color = 'var(--user-ink)';
                }
            }

            function changeMiniMonth(delta) {
                miniCurrentDate.setMonth(miniCurrentDate.getMonth() + delta);
                selectedDates.clear();
                renderMiniCalendar();
            }

            function showWhereaboutsModal() {
                const modal = document.getElementById('whereaboutsModal');
                const datesList = document.getElementById('selectedDatesList');
                datesList.innerHTML = '';
                
                const sortedDates = Array.from(selectedDates).sort();
                sortedDates.forEach(date => {
                    const d = new Date(date);
                    const options = { weekday: 'short', month: 'short', day: 'numeric' };
                    const li = document.createElement('li');
                    li.textContent = d.toLocaleDateString('en-US', options);
                    li.style.padding = '4px 0';
                    li.style.borderBottom = '1px solid rgba(60, 64, 198, 0.08)';
                    datesList.appendChild(li);
                });
                
                modal.style.display = 'flex';
            }

            function closeWhereaboutsModal() {
                document.getElementById('whereaboutsModal').style.display = 'none';
            }

            function submitWhereabouts() {
                const status = document.getElementById('modalStatus').value;
                const location = document.getElementById('modalLocation').value;
                const activity = document.getElementById('modalActivity').value;
                const notes = document.getElementById('modalNotes').value;
                
                if (!location || !activity) {
                    alert('Please fill in location and activity');
                    return;
                }
                
                const dates = Array.from(selectedDates);
                let successCount = 0;
                
                dates.forEach(date => {
                    const formData = new FormData();
                    formData.append('date', date);
                    formData.append('status', status);
                    formData.append('location', location);
                    formData.append('activity', activity);
                    formData.append('notes', notes);
                    
                    fetch('<?= base_url(); ?>Page/whereabouts_ajax', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            successCount++;
                            if (successCount === dates.length) {
                                alert('Whereabouts posted successfully for ' + dates.length + ' date(s)');
                                closeWhereaboutsModal();
                                selectedDates.clear();
                                renderMiniCalendar();
                                location.reload();
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            }

            document.addEventListener('mouseup', () => {
                isDragging = false;
                dragStartDay = null;
            });

            document.addEventListener('DOMContentLoaded', function() {
                renderMiniCalendar();
            });
        </script>

    </body>
</html>
