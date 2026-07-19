<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Print Report Filter</title>
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
        <style>
            body {
                background: #f4f8fc;
                padding: 20px;
            }
            .filter-container {
                max-width: 600px;
                margin: 40px auto;
                background: #ffffff;
                padding: 30px;
                border-radius: 16px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            }
            .filter-header {
                text-align: center;
                margin-bottom: 25px;
            }
            .filter-header h2 {
                color: #1e3a8a;
                font-weight: 700;
                margin-bottom: 8px;
            }
            .filter-header p {
                color: #6b7280;
                margin: 0;
            }
            .form-group {
                margin-bottom: 20px;
            }
            .form-group label {
                font-weight: 600;
                color: #374151;
                margin-bottom: 8px;
                display: block;
            }
            .form-control {
                border-radius: 8px;
                border: 1px solid #d1d5db;
                padding: 12px;
                font-size: 14px;
            }
            .form-control:focus {
                border-color: #2563eb;
                box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            }
            .btn-submit {
                width: 100%;
                padding: 14px;
                background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
                color: #ffffff;
                border: none;
                border-radius: 8px;
                font-weight: 600;
                font-size: 16px;
                cursor: pointer;
                transition: all 0.2s ease;
            }
            .btn-submit:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
            }
            .btn-back {
                margin-top: 15px;
                text-align: center;
            }
            .btn-back a {
                color: #6b7280;
                text-decoration: none;
                font-weight: 500;
            }
            .btn-back a:hover {
                color: #2563eb;
            }
        </style>
    </head>

    <body>

        <div class="filter-container">
            <div class="filter-header">
                <h2><i class="mdi mdi-printer"></i> Print Report</h2>
                <p>Select your filter options to generate a printable report</p>
            </div>

            <?php
                echo form_open('Page/reportv2');
            ?>
            <input type="hidden" name="print" value="true">
            <input type="hidden" name="activityCategory" value="accomplishment">
            <input type="hidden" name="sec" value="<?= $this->uri->segment('3') ? urldecode($this->uri->segment('3')) : ''; ?>">

            <div class="form-group">
                <label>Filter By</label>
                <select class="form-control" name="filterType" id="filterType" onchange="toggleFilterFields()" required>
                    <option value="day">Per Day</option>
                    <option value="week">Per Week</option>
                    <option value="month">Per Month</option>
                </select>
            </div>

            <div class="form-group" id="dayField">
                <label>Date</label>
                <input type="date" name="date" id="dateInput" class="form-control" value="<?= date('Y-m-d'); ?>">
            </div>

            <div class="form-group" id="weekFields" style="display: none;">
                <label>Month</label>
                <select class="form-control" name="month" id="monthSelect">
                    <?php
                        $currentMonth = date('F');
                        for($m=1; $m<=12; ++$m){
                            $monthName = date('F', mktime(0, 0, 0, $m, 1));
                            $selected = ($monthName === $currentMonth) ? 'selected' : '';
                            echo '<option value="'.$monthName.'" '.$selected.'>'.$monthName.'</option>';
                        }
                    ?>
                </select>
            </div>

            <div class="form-group" id="weekField" style="display: none;">
                <label>Week</label>
                <select class="form-control" name="weekAcc" id="weekSelect">
                    <?php
                        $currentWeek = ceil(date('d') / 7);
                        for ($x = 1; $x <=5; $x++){
                            $selected = ($x === $currentWeek) ? 'selected' : '';
                            echo '<option value="'.$x.'" '.$selected.'> Week '.$x.'</option>';
                        }
                    ?>
                </select>
            </div>

            <div class="form-group" id="monthField" style="display: none;">
                <label>Month</label>
                <select class="form-control" name="month" id="monthSelectMonth">
                    <?php
                        $currentMonth = date('F');
                        for($m=1; $m<=12; ++$m){
                            $monthName = date('F', mktime(0, 0, 0, $m, 1));
                            $selected = ($monthName === $currentMonth) ? 'selected' : '';
                            echo '<option value="'.$monthName.'" '.$selected.'>'.$monthName.'</option>';
                        }
                    ?>
                </select>
            </div>

            <div class="form-group" id="yearField" style="display: none;">
                <label>Year</label>
                <input type="text" name="year" id="yearInput" class="form-control" value="<?= date('Y'); ?>">
            </div>

            <button type="submit" name="submit" class="btn-submit">
                <i class="mdi mdi-printer"></i> Generate Print Report
            </button>

            </form>

            <div class="btn-back">
                <a href="<?= base_url(); ?>Page/viewSecAccomplishments">
                    <i class="mdi mdi-arrow-left"></i> Back to Accomplishments
                </a>
            </div>
        </div>

        <script>
            function toggleFilterFields() {
                var filterType = document.getElementById('filterType').value;
                var dayField = document.getElementById('dayField');
                var weekFields = document.getElementById('weekFields');
                var weekField = document.getElementById('weekField');
                var monthField = document.getElementById('monthField');
                var yearField = document.getElementById('yearField');

                // Hide all fields first
                dayField.style.display = 'none';
                weekFields.style.display = 'none';
                weekField.style.display = 'none';
                monthField.style.display = 'none';
                yearField.style.display = 'none';

                // Show relevant fields based on selection
                if (filterType === 'day') {
                    dayField.style.display = 'block';
                } else if (filterType === 'week') {
                    weekFields.style.display = 'block';
                    weekField.style.display = 'block';
                } else if (filterType === 'month') {
                    monthField.style.display = 'block';
                    yearField.style.display = 'block';
                }
            }

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                toggleFilterFields();
            });
        </script>

    </body>
</html>
