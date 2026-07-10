<?php
$today = date('Y-m-d');
$currentMonth = date('m');
$currentYear = date('Y');
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
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />

        <style>
            :root {
                --where-navy: #272b8c;
                --where-blue: #3c40c6;
                --where-teal: #565de8;
                --where-ink: #23275d;
                --where-muted: #7b7fa7;
                --where-border: rgba(60, 64, 198, 0.12);
                --where-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            }

            body {
                background:
                    radial-gradient(circle at top left, rgba(60, 64, 198, 0.10), transparent 24%),
                    linear-gradient(180deg, #f4f8fc 0%, #eef4fa 100%);
            }

            .content-page {
                background: transparent;
            }

            .where-shell {
                position: relative;
                padding-bottom: 28px;
            }

            .form-card {
                border: 1px solid rgba(60, 64, 198, 0.08);
                border-radius: 22px;
                background: rgba(255, 255, 255, 0.95);
                padding: 28px;
                box-shadow: var(--where-shadow);
                max-width: 900px;
                margin: 0 auto;
            }

            .form-header {
                margin-bottom: 24px;
            }

            .form-title {
                color: var(--where-ink);
                font-size: 1.75rem;
                font-weight: 700;
                margin-bottom: 8px;
            }

            .form-subtitle {
                color: var(--where-muted);
                margin-bottom: 0;
            }

            .calendar-container {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 28px;
            }

            .calendar-wrapper {
                border: 1px solid var(--where-border);
                border-radius: 16px;
                padding: 20px;
                background: #ffffff;
            }

            .calendar-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
            }

            .calendar-header h4 {
                color: var(--where-ink);
                font-weight: 700;
                margin: 0;
            }

            .calendar-nav {
                background: none;
                border: none;
                font-size: 1.2rem;
                cursor: pointer;
                color: var(--where-blue);
                padding: 8px 12px;
                border-radius: 8px;
                transition: all 0.25s ease;
            }

            .calendar-nav:hover {
                background: rgba(60, 64, 198, 0.1);
            }

            .calendar-grid {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                gap: 8px;
            }

            .calendar-day-header {
                text-align: center;
                font-weight: 600;
                color: var(--where-muted);
                font-size: 0.85rem;
                padding: 8px;
            }

            .calendar-day {
                aspect-ratio: 1;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 12px;
                cursor: pointer;
                font-weight: 600;
                font-size: 0.95rem;
                transition: all 0.25s ease;
                background: #f8f9ff;
                color: var(--where-ink);
            }

            .calendar-day:hover {
                background: rgba(60, 64, 198, 0.1);
            }

            .calendar-day.selected {
                background: linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
                color: #ffffff;
            }

            .calendar-day.today {
                border: 2px solid var(--where-blue);
            }

            .calendar-day.empty {
                background: transparent;
                cursor: default;
            }

            .form-wrapper {
                border: 1px solid var(--where-border);
                border-radius: 16px;
                padding: 20px;
                background: #ffffff;
            }

            .form-wrapper.hidden {
                display: none;
            }

            .modern-label {
                color: var(--where-ink);
                font-weight: 600;
                font-size: 0.9rem;
                margin-bottom: 8px;
            }

            .modern-input {
                border: 1px solid var(--where-border);
                border-radius: 12px;
                padding: 12px 16px;
                font-size: 0.95rem;
                transition: all 0.25s ease;
                width: 100%;
            }

            .modern-input:focus {
                border-color: var(--where-blue);
                box-shadow: 0 0 0 3px rgba(60, 64, 198, 0.1);
                outline: none;
            }

            .btn-gradient-primary {
                background: linear-gradient(135deg, #272b8c 0%, #3c40c6 58%, #6f74ff 100%);
                border: none;
                color: #ffffff;
                padding: 12px 28px;
                border-radius: 12px;
                font-weight: 600;
                transition: all 0.25s ease;
            }

            .btn-gradient-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(60, 64, 198, 0.25);
            }

            .btn-soft-dark {
                color: var(--where-ink);
                background: #eef3f8;
                border: 1px solid rgba(60, 64, 198, 0.08);
                padding: 12px 28px;
                border-radius: 12px;
                font-weight: 600;
                transition: all 0.25s ease;
            }

            .btn-soft-dark:hover {
                background: #e8edf2;
            }

            .selected-date-display {
                background: linear-gradient(135deg, rgba(60, 64, 198, 0.1), rgba(86, 93, 232, 0.1));
                border-radius: 12px;
                padding: 16px;
                margin-bottom: 20px;
                text-align: center;
            }

            .selected-date-display h5 {
                color: var(--where-ink);
                font-weight: 700;
                margin: 0;
            }

            @media (max-width: 768px) {
                .calendar-container {
                    grid-template-columns: 1fr;
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
                    <div class="container-fluid where-shell">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-card">
                                    <div class="form-header">
                                        <h1 class="form-title">Post Your Whereabouts</h1>
                                        <p class="form-subtitle">Select a date from the calendar and enter your details.</p>
                                    </div>

                                    <div class="calendar-container">
                                        <div class="calendar-wrapper">
                                            <div class="calendar-header">
                                                <button class="calendar-nav" onclick="changeMonth(-1)">
                                                    <i class="mdi mdi-chevron-left"></i>
                                                </button>
                                                <h4 id="calendarMonthYear"></h4>
                                                <button class="calendar-nav" onclick="changeMonth(1)">
                                                    <i class="mdi mdi-chevron-right"></i>
                                                </button>
                                            </div>
                                            <div class="calendar-grid">
                                                <div class="calendar-day-header">Sun</div>
                                                <div class="calendar-day-header">Mon</div>
                                                <div class="calendar-day-header">Tue</div>
                                                <div class="calendar-day-header">Wed</div>
                                                <div class="calendar-day-header">Thu</div>
                                                <div class="calendar-day-header">Fri</div>
                                                <div class="calendar-day-header">Sat</div>
                                            </div>
                                            <div class="calendar-grid" id="calendarDays"></div>
                                        </div>

                                        <div class="form-wrapper" id="formWrapper">
                                            <div class="selected-date-display">
                                                <h5 id="selectedDateDisplay">Select a date to post whereabouts</h5>
                                            </div>

                                            <form method="post" id="whereaboutsForm">
                                                <input type="hidden" name="date" id="selectedDate" value="">

                                                <div class="form-group">
                                                    <label class="modern-label">Status <span class="text-danger">*</span></label>
                                                    <select name="status" class="form-control modern-input" required>
                                                        <option value="In Office">In Office</option>
                                                        <option value="Out of Office">Out of Office</option>
                                                        <option value="On Official Business">On Official Business</option>
                                                        <option value="On Leave">On Leave</option>
                                                        <option value="On Field Work">On Field Work</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label class="modern-label">Location <span class="text-danger">*</span></label>
                                                    <input type="text" name="location" class="form-control modern-input" placeholder="e.g., Main Office, School X, Conference Room" required>
                                                </div>

                                                <div class="form-group">
                                                    <label class="modern-label">Activity <span class="text-danger">*</span></label>
                                                    <textarea name="activity" class="form-control modern-input" rows="3" placeholder="What are you working on?" required></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label class="modern-label">Notes</label>
                                                    <textarea name="notes" class="form-control modern-input" rows="2" placeholder="Any additional details..."></textarea>
                                                </div>

                                                <div class="form-group text-right mb-0">
                                                    <a href="<?= base_url(); ?>Page/user_dashboard" class="btn btn-soft-dark mr-2">Cancel</a>
                                                    <input type="submit" name="submit" value="Post Whereabouts" class="btn btn-gradient-primary">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include('includes/footer.php'); ?>
            </div>
        </div>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>

        <script>
            let currentDate = new Date();
            let selectedDate = null;

            function renderCalendar() {
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth();
                const today = new Date();

                const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'];

                document.getElementById('calendarMonthYear').textContent = `${monthNames[month]} ${year}`;

                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();

                const calendarDays = document.getElementById('calendarDays');
                calendarDays.innerHTML = '';

                for (let i = 0; i < firstDay; i++) {
                    const emptyDay = document.createElement('div');
                    emptyDay.className = 'calendar-day empty';
                    calendarDays.appendChild(emptyDay);
                }

                for (let day = 1; day <= daysInMonth; day++) {
                    const dayElement = document.createElement('div');
                    dayElement.className = 'calendar-day';
                    dayElement.textContent = day;

                    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                    if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                        dayElement.classList.add('today');
                    }

                    if (selectedDate === dateStr) {
                        dayElement.classList.add('selected');
                    }

                    dayElement.addEventListener('click', () => selectDate(dateStr));
                    calendarDays.appendChild(dayElement);
                }
            }

            function selectDate(dateStr) {
                selectedDate = dateStr;
                document.getElementById('selectedDate').value = dateStr;

                const date = new Date(dateStr);
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                document.getElementById('selectedDateDisplay').textContent = date.toLocaleDateString('en-US', options);

                renderCalendar();
            }

            function changeMonth(delta) {
                currentDate.setMonth(currentDate.getMonth() + delta);
                renderCalendar();
            }

            document.addEventListener('DOMContentLoaded', function() {
                renderCalendar();
            });
        </script>

    </body>
</html>
