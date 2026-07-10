<?php
$manilaTimezone = new DateTimeZone('Asia/Manila');
$manilaNow = new DateTime('now', $manilaTimezone);
$currentDate = $manilaNow->format('Y-m-d');
$currentDateLabel = $manilaNow->format('F d, Y');
$selectedDate = !empty($selectedDate) ? $selectedDate : $currentDate;
$selectedDateObject = DateTime::createFromFormat('Y-m-d', $selectedDate, $manilaTimezone);
if (!$selectedDateObject) {
    $selectedDateObject = clone $manilaNow;
}
$displayDate = $selectedDateObject->format('F d, Y');
$isCurrentDay = $selectedDate === $currentDate;
$employees = !empty($data) ? $data : [];
$defaultAvatarUrl = base_url() . 'upload/profile/avatar.png';
$buildAvatarUrl = static function ($avatarFile) use ($defaultAvatarUrl) {
    $avatarFile = trim((string) $avatarFile);
    if ($avatarFile === '') {
        return $defaultAvatarUrl;
    }

    return base_url() . 'upload/profile/' . rawurlencode(basename($avatarFile));
};
$buildInitials = static function ($fName, $lName) {
    return strtoupper(substr(trim((string) $fName), 0, 1) . substr(trim((string) $lName), 0, 1));
};
$sections = [];
$statusCounts = [
    'In Office' => 0,
    'Out of Office' => 0,
    'On Official Business' => 0,
    'On Leave' => 0,
    'On Field Work' => 0,
];
$statusClasses = [
    'In Office' => 'status-in-office',
    'Out of Office' => 'status-out-of-office',
    'On Official Business' => 'status-on-official-business',
    'On Leave' => 'status-on-leave',
    'On Field Work' => 'status-on-field-work',
];

foreach ($employees as $employee) {
    if (!in_array($employee->section, $sections, true)) {
        $sections[] = $employee->section;
    }

    if (isset($statusCounts[$employee->status])) {
        $statusCounts[$employee->status]++;
    }
}

sort($sections);

$totalEmployees = count($employees);
$standbyDefault = $isCurrentDay && $totalEmployees > 0;
$featuredEmployee = $totalEmployees > 0 ? $employees[0] : null;
$featuredStatusClass = ($featuredEmployee && isset($statusClasses[$featuredEmployee->status])) ? $statusClasses[$featuredEmployee->status] : '';
$featuredAvatarUrl = $featuredEmployee ? $buildAvatarUrl($featuredEmployee->userAvatar ?? '') : $defaultAvatarUrl;
$featuredInitials = $featuredEmployee ? $buildInitials($featuredEmployee->fName, $featuredEmployee->lName) : 'SG';
$tickerEmployees = $totalEmployees > 0 ? array_merge($employees, $employees) : [];
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Employee Whereabouts Display" name="description" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>Employee Whereabouts - <?= htmlspecialchars($displayDate, ENT_QUOTES, 'UTF-8'); ?></title>
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />

        <style>
            :root {
                --board-bg: #04111f;
                --board-bg-alt: #0c2435;
                --panel: rgba(7, 22, 38, 0.78);
                --panel-strong: rgba(8, 20, 36, 0.92);
                --panel-soft: rgba(255, 255, 255, 0.08);
                --line: rgba(159, 216, 255, 0.18);
                --text: #f6fbff;
                --muted: rgba(230, 242, 255, 0.7);
                --accent: #7af4ff;
                --accent-warm: #ffd166;
                --accent-alert: #ff7f6a;
                --shadow: 0 24px 70px rgba(0, 0, 0, 0.35);
                --flash: 0 0 0 rgba(122, 244, 255, 0);
                --headline-font: "Trebuchet MS", "Franklin Gothic Medium", "Arial Narrow", Arial, sans-serif;
                --body-font: "Segoe UI", "Gill Sans", sans-serif;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            html,
            body {
                min-height: 100%;
            }

            body {
                font-family: var(--body-font);
                color: var(--text);
                background:
                    radial-gradient(circle at top left, rgba(122, 244, 255, 0.18), transparent 32%),
                    radial-gradient(circle at 85% 12%, rgba(255, 209, 102, 0.22), transparent 24%),
                    radial-gradient(circle at 70% 78%, rgba(255, 127, 106, 0.18), transparent 26%),
                    linear-gradient(135deg, var(--board-bg) 0%, #082235 46%, var(--board-bg-alt) 100%);
                overflow-x: hidden;
                overflow-y: auto;
                position: relative;
            }

            body::before,
            body::after {
                content: "";
                position: fixed;
                inset: auto;
                border-radius: 999px;
                pointer-events: none;
                filter: blur(24px);
                opacity: 0.48;
                animation: drift 14s ease-in-out infinite;
            }

            body::before {
                width: 260px;
                height: 260px;
                top: 12%;
                right: -60px;
                background: rgba(122, 244, 255, 0.18);
            }

            body::after {
                width: 220px;
                height: 220px;
                bottom: 10%;
                left: -40px;
                background: rgba(255, 209, 102, 0.18);
                animation-delay: -6s;
            }

            @keyframes drift {
                0%,
                100% {
                    transform: translate3d(0, 0, 0) scale(1);
                }
                50% {
                    transform: translate3d(12px, -16px, 0) scale(1.06);
                }
            }

            .board-shell {
                position: relative;
                z-index: 1;
                min-height: 100vh;
                padding: 18px 22px 22px;
                display: grid;
                grid-template-rows: auto auto auto minmax(640px, 1fr);
                gap: 14px;
            }

            .toolbar,
            .summary-strip,
            .content-grid,
            .ticker-shell {
                min-width: 0;
            }

            .toolbar,
            .summary-card,
            .spotlight-panel,
            .board-panel,
            .ticker-shell {
                background: var(--panel);
                border: 1px solid var(--line);
                box-shadow: var(--shadow);
                backdrop-filter: blur(18px);
            }

            .eyebrow,
            .panel-kicker,
            .summary-label,
            .field-label,
            .chip-label {
                text-transform: uppercase;
                letter-spacing: 0.18em;
                font-size: 0.72rem;
                color: var(--muted);
            }

            .toolbar {
                border-radius: 26px;
                padding: 14px 18px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 12px;
                flex-wrap: wrap;
            }

            .toolbar-group {
                display: flex;
                align-items: center;
                gap: 12px;
                flex-wrap: wrap;
                min-width: 0;
            }

            .toolbar-group-wide {
                flex: 1;
            }

            .toolbar-control,
            .toolbar-button {
                height: 44px;
                border-radius: 18px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                background: rgba(255, 255, 255, 0.08);
                color: var(--text);
                padding: 0 16px;
                font-size: 0.92rem;
                transition: transform 0.25s ease, border-color 0.25s ease, background 0.25s ease;
            }

            .toolbar-control:focus,
            .toolbar-button:focus {
                outline: none;
                border-color: rgba(122, 244, 255, 0.6);
                background: rgba(255, 255, 255, 0.12);
            }

            .toolbar-control:hover,
            .toolbar-button:hover {
                transform: translateY(-1px);
            }

            .toolbar-control {
                min-width: 170px;
            }

            .toolbar-control::-webkit-calendar-picker-indicator {
                filter: invert(1);
                cursor: pointer;
            }

            .toolbar-control option {
                color: #06121f;
            }

            .search-shell {
                position: relative;
                flex: 1;
                min-width: 240px;
            }

            .search-shell .toolbar-control {
                width: 100%;
                padding-right: 44px;
            }

            .search-shell i {
                position: absolute;
                right: 16px;
                top: 50%;
                transform: translateY(-50%);
                color: var(--muted);
                font-size: 1.05rem;
                pointer-events: none;
            }

            .toolbar-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                min-width: 165px;
                cursor: pointer;
                font-weight: 600;
            }

            .toolbar-button-primary {
                background: linear-gradient(135deg, rgba(122, 244, 255, 0.16), rgba(122, 244, 255, 0.3));
                border-color: rgba(122, 244, 255, 0.32);
            }

            .toolbar-button-secondary {
                background: linear-gradient(135deg, rgba(255, 209, 102, 0.14), rgba(255, 209, 102, 0.26));
                border-color: rgba(255, 209, 102, 0.3);
            }

            .summary-strip {
                display: grid;
                grid-template-columns: repeat(5, minmax(0, 1fr));
                gap: 12px;
            }

            .summary-card {
                border-radius: 24px;
                padding: 14px 18px;
                position: relative;
                overflow: hidden;
            }

            .summary-card::after {
                content: "";
                position: absolute;
                inset: auto -40px -60px auto;
                width: 140px;
                height: 140px;
                border-radius: 50%;
                opacity: 0.3;
                background: radial-gradient(circle, rgba(255, 255, 255, 0.25), transparent 65%);
            }

            .summary-value {
                display: block;
                margin-top: 6px;
                font-family: var(--headline-font);
                font-size: clamp(1.65rem, 2vw, 2.25rem);
                line-height: 1;
            }

            .summary-card-total {
                background: linear-gradient(135deg, rgba(122, 244, 255, 0.18), rgba(122, 244, 255, 0.05));
            }

            .summary-card-office {
                background: linear-gradient(135deg, rgba(56, 239, 125, 0.18), rgba(17, 153, 142, 0.05));
            }

            .summary-card-official {
                background: linear-gradient(135deg, rgba(111, 116, 255, 0.16), rgba(111, 116, 255, 0.05));
            }

            .summary-card-field {
                background: linear-gradient(135deg, rgba(79, 172, 254, 0.18), rgba(0, 242, 254, 0.05));
            }

            .summary-card-leave {
                background: linear-gradient(135deg, rgba(245, 87, 108, 0.18), rgba(240, 147, 251, 0.05));
            }

            .content-grid {
                display: grid;
                grid-template-columns: minmax(0, 1.08fr) minmax(0, 1fr);
                gap: 16px;
                min-height: clamp(640px, 72vh, 980px);
                align-items: stretch;
            }

            .spotlight-panel,
            .board-panel {
                border-radius: 32px;
                padding: 22px;
                min-height: clamp(640px, 72vh, 980px);
                overflow: visible;
            }

            .spotlight-panel {
                display: grid;
                grid-template-rows: auto minmax(0, 1fr) auto;
                gap: 16px;
                position: relative;
            }

            .spotlight-top,
            .board-panel-top {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 14px;
            }

            .spotlight-top h2,
            .board-panel-top h2 {
                font-family: var(--headline-font);
                font-size: 1.55rem;
                margin-top: 8px;
            }

            .spotlight-mode-pill {
                padding: 10px 14px;
                border-radius: 999px;
                border: 1px solid rgba(122, 244, 255, 0.22);
                background: rgba(122, 244, 255, 0.08);
                color: var(--accent);
                font-weight: 700;
                white-space: nowrap;
            }

            .spotlight-card {
                border-radius: 30px;
                background: var(--panel-strong);
                border: 1px solid rgba(255, 255, 255, 0.1);
                padding: 24px;
                display: grid;
                grid-template-rows: auto auto auto auto;
                gap: 18px;
                min-height: 0;
                position: relative;
                overflow: hidden;
                transition: transform 0.35s ease, border-color 0.35s ease, box-shadow 0.35s ease;
            }

            .spotlight-card::before {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(140deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0));
                pointer-events: none;
            }

            .spotlight-card.is-empty {
                justify-content: center;
            }

            .spotlight-card.status-in-office {
                border-color: rgba(56, 239, 125, 0.4);
                box-shadow: 0 0 0 1px rgba(56, 239, 125, 0.15), 0 24px 60px rgba(17, 153, 142, 0.16);
            }

            .spotlight-card.status-out-of-office {
                border-color: rgba(244, 92, 67, 0.38);
                box-shadow: 0 0 0 1px rgba(244, 92, 67, 0.15), 0 24px 60px rgba(235, 51, 73, 0.16);
            }

            .spotlight-card.status-on-official-business {
                border-color: rgba(111, 116, 255, 0.42);
                box-shadow: 0 0 0 1px rgba(111, 116, 255, 0.18), 0 24px 60px rgba(60, 64, 198, 0.16);
            }

            .spotlight-card.status-on-leave {
                border-color: rgba(245, 87, 108, 0.42);
                box-shadow: 0 0 0 1px rgba(245, 87, 108, 0.18), 0 24px 60px rgba(240, 147, 251, 0.16);
            }

            .spotlight-card.status-on-field-work {
                border-color: rgba(0, 242, 254, 0.42);
                box-shadow: 0 0 0 1px rgba(0, 242, 254, 0.18), 0 24px 60px rgba(79, 172, 254, 0.16);
            }

            @keyframes standbyFlash {
                0%,
                100% {
                    transform: scale(1);
                    box-shadow: var(--flash);
                    filter: brightness(1);
                }
                50% {
                    transform: scale(1.014);
                    box-shadow: 0 0 0 10px rgba(122, 244, 255, 0.06), 0 0 46px rgba(122, 244, 255, 0.24);
                    filter: brightness(1.08);
                }
            }

            body.standby-mode .spotlight-card {
                animation: standbyFlash 1.5s ease-in-out infinite;
            }

            .spotlight-profile {
                display: grid;
                grid-template-columns: auto minmax(0, 1fr);
                gap: 20px;
                align-items: center;
            }

            .spotlight-avatar-shell {
                width: 140px;
                height: 140px;
                border-radius: 34px;
                position: relative;
                overflow: hidden;
                flex-shrink: 0;
                border: 1px solid rgba(255, 255, 255, 0.12);
                background: linear-gradient(135deg, rgba(122, 244, 255, 0.22), rgba(255, 209, 102, 0.24));
                box-shadow: 0 20px 44px rgba(0, 0, 0, 0.22);
            }

            .spotlight-avatar-image,
            .employee-avatar-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }

            .spotlight-avatar-fallback,
            .employee-avatar-fallback {
                position: absolute;
                inset: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: var(--headline-font);
                font-weight: 700;
                color: #ffffff;
                background: linear-gradient(135deg, rgba(60, 64, 198, 0.8), rgba(122, 244, 255, 0.45));
            }

            .spotlight-avatar-fallback {
                font-size: 3rem;
            }

            .spotlight-avatar-shell.has-image .spotlight-avatar-fallback,
            .employee-avatar.has-image .employee-avatar-fallback {
                opacity: 0;
                pointer-events: none;
            }

            .spotlight-identity {
                display: grid;
                gap: 6px;
            }

            .spotlight-identity h3 {
                font-family: var(--headline-font);
                font-size: clamp(2rem, 3vw, 3rem);
                line-height: 0.96;
            }

            .spotlight-identity p {
                color: var(--muted);
                font-size: 1rem;
            }

            .spotlight-badges {
                display: flex;
                align-items: center;
                gap: 10px;
                flex-wrap: wrap;
            }

            .status-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 10px 18px;
                border-radius: 999px;
                font-size: 0.86rem;
                font-weight: 700;
                letter-spacing: 0.03em;
                color: #ffffff;
            }

            .status-in-office {
                background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            }

            .status-out-of-office {
                background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            }

            .status-on-official-business {
                background: linear-gradient(135deg, #3c40c6 0%, #6f74ff 100%);
            }

            .status-on-leave {
                background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            }

            .status-on-field-work {
                background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            }

            .spotlight-section-pill {
                display: inline-flex;
                align-items: center;
                padding: 10px 14px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.08);
                border: 1px solid rgba(255, 255, 255, 0.08);
                font-size: 0.85rem;
                color: var(--muted);
            }

            .spotlight-fields {
                display: grid;
                gap: 14px;
                min-height: 0;
            }

            .spotlight-field {
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.07);
                border-radius: 20px;
                padding: 16px 18px;
            }

            .field-value {
                margin-top: 7px;
                font-size: 1.08rem;
                font-weight: 600;
                line-height: 1.45;
            }

            .field-value-muted {
                color: var(--muted);
            }

            .standby-note {
                border-radius: 18px;
                background: rgba(122, 244, 255, 0.08);
                border: 1px solid rgba(122, 244, 255, 0.14);
                padding: 14px 16px;
                color: var(--muted);
                font-size: 0.95rem;
            }

            .standby-note.is-hidden {
                display: none;
            }

            .board-panel {
                display: grid;
                grid-template-rows: auto auto auto;
                gap: 16px;
                align-content: start;
            }

            .board-count {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 105px;
                padding: 10px 14px;
                border-radius: 18px;
                background: rgba(255, 255, 255, 0.06);
                border: 1px solid rgba(255, 255, 255, 0.08);
                font-weight: 700;
                color: var(--muted);
            }

            .board-caption {
                color: var(--muted);
                font-size: 0.95rem;
            }

            .empty-filter-state {
                display: none;
                border-radius: 24px;
                border: 1px dashed rgba(255, 255, 255, 0.18);
                background: rgba(255, 255, 255, 0.04);
                padding: 18px;
                text-align: center;
                color: var(--muted);
            }

            .empty-filter-state.show {
                display: block;
            }

            .employee-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 14px;
                align-content: start;
                overflow: visible;
                padding-right: 0;
            }

            .employee-grid::-webkit-scrollbar {
                width: 8px;
            }

            .employee-grid::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.16);
                border-radius: 999px;
            }

            .employee-card {
                width: 100%;
                border: 1px solid rgba(255, 255, 255, 0.09);
                border-radius: 24px;
                background: rgba(255, 255, 255, 0.05);
                padding: 16px;
                color: var(--text);
                text-align: left;
                cursor: pointer;
                display: grid;
                gap: 12px;
                transition: transform 0.24s ease, border-color 0.24s ease, background 0.24s ease, box-shadow 0.24s ease;
            }

            .employee-card:hover,
            .employee-card:focus {
                transform: translateY(-3px);
                border-color: rgba(122, 244, 255, 0.28);
                background: rgba(255, 255, 255, 0.08);
                box-shadow: 0 16px 36px rgba(0, 0, 0, 0.18);
                outline: none;
            }

            .employee-card.active {
                border-color: rgba(122, 244, 255, 0.45);
                background: linear-gradient(135deg, rgba(122, 244, 255, 0.12), rgba(255, 255, 255, 0.06));
                box-shadow: 0 18px 40px rgba(122, 244, 255, 0.12);
            }

            body.standby-mode .employee-card.active {
                animation: activeCardPulse 1.5s ease-in-out infinite;
            }

            @keyframes activeCardPulse {
                0%,
                100% {
                    box-shadow: 0 18px 40px rgba(122, 244, 255, 0.12);
                }
                50% {
                    box-shadow: 0 22px 44px rgba(122, 244, 255, 0.22);
                }
            }

            .employee-card.is-hidden {
                display: none;
            }

            .employee-card-top {
                display: flex;
                align-items: center;
                gap: 12px;
                min-width: 0;
            }

            .employee-avatar {
                width: 52px;
                height: 52px;
                border-radius: 18px;
                background: linear-gradient(135deg, rgba(122, 244, 255, 0.22), rgba(255, 209, 102, 0.22));
                border: 1px solid rgba(255, 255, 255, 0.12);
                flex-shrink: 0;
                position: relative;
                overflow: hidden;
            }

            .employee-avatar-fallback {
                font-size: 1.1rem;
            }

            .employee-identity {
                min-width: 0;
            }

            .employee-identity strong,
            .employee-identity span,
            .employee-snippet strong,
            .employee-snippet span {
                display: block;
            }

            .employee-identity strong {
                font-size: 1rem;
                line-height: 1.25;
            }

            .employee-identity span {
                margin-top: 3px;
                color: var(--muted);
                font-size: 0.85rem;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .employee-snippet {
                display: grid;
                gap: 4px;
                min-width: 0;
            }

            .employee-snippet strong {
                color: var(--muted);
                text-transform: uppercase;
                letter-spacing: 0.12em;
                font-size: 0.68rem;
            }

            .employee-snippet span {
                font-size: 0.92rem;
                line-height: 1.4;
                overflow: hidden;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
            }

            .board-empty {
                height: 100%;
                display: grid;
                place-items: center;
                text-align: center;
                color: var(--muted);
                border-radius: 26px;
                border: 1px dashed rgba(255, 255, 255, 0.18);
                background: rgba(255, 255, 255, 0.04);
                padding: 28px;
            }

            .board-empty i {
                font-size: 3.8rem;
                margin-bottom: 14px;
                display: block;
                color: var(--accent-warm);
            }

            .ticker-shell {
                border-radius: 20px;
                padding: 8px 14px;
                display: grid;
                grid-template-columns: auto minmax(0, 1fr);
                gap: 12px;
                align-items: center;
                overflow: hidden;
            }

            .ticker-label {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                font-weight: 700;
                white-space: nowrap;
                font-size: 0.78rem;
                text-transform: uppercase;
                letter-spacing: 0.16em;
                color: var(--muted);
            }

            .ticker-label::before {
                content: "";
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background: var(--accent-alert);
                box-shadow: 0 0 0 6px rgba(255, 127, 106, 0.08);
            }

            .ticker-window {
                overflow: hidden;
                min-width: 0;
            }

            .ticker-track {
                display: inline-flex;
                gap: 10px;
                min-width: max-content;
                animation: tickerSlide 34s linear infinite;
                padding-right: 10px;
            }

            @keyframes tickerSlide {
                0% {
                    transform: translateX(0);
                }
                100% {
                    transform: translateX(-50%);
                }
            }

            .ticker-item {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 6px 10px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.06);
                border: 1px solid rgba(255, 255, 255, 0.08);
                color: var(--muted);
                white-space: nowrap;
                font-size: 0.78rem;
            }

            .ticker-item strong {
                color: var(--text);
            }

            body.standby-mode .toolbar {
                opacity: 0.42;
            }

            body.standby-mode .spotlight-mode-pill {
                color: var(--accent-warm);
            }

            body.standby-mode {
                overflow: hidden;
            }

            body.standby-mode .toolbar,
            body.standby-mode .summary-strip,
            body.standby-mode .board-panel,
            body.standby-mode .ticker-shell {
                display: none;
            }

            body.standby-mode .board-shell {
                min-height: 100vh;
                height: 100vh;
                padding: 14px;
                grid-template-rows: minmax(0, 1fr);
                gap: 0;
            }

            body.standby-mode .content-grid {
                grid-template-columns: minmax(0, 1fr);
                min-height: 100%;
                height: 100%;
                gap: 0;
            }

            body.standby-mode .spotlight-panel {
                min-height: 100%;
                height: 100%;
                padding: 24px;
                border-radius: 34px;
                grid-template-rows: auto minmax(0, 1fr) auto;
            }

            body.standby-mode .spotlight-top h2 {
                font-size: clamp(1.7rem, 2.2vw, 2.2rem);
            }

            body.standby-mode .spotlight-card {
                height: 100%;
                padding: 30px;
                gap: 22px;
                align-content: start;
            }

            body.standby-mode .spotlight-profile {
                gap: 26px;
            }

            body.standby-mode .spotlight-avatar-shell {
                width: clamp(180px, 17vw, 250px);
                height: clamp(180px, 17vw, 250px);
                border-radius: 38px;
            }

            body.standby-mode .spotlight-identity h3 {
                font-size: clamp(2.9rem, 5vw, 5rem);
            }

            body.standby-mode .spotlight-identity p {
                font-size: 1.2rem;
            }

            body.standby-mode .status-badge,
            body.standby-mode .spotlight-section-pill {
                font-size: 1rem;
                padding: 12px 20px;
            }

            body.standby-mode .field-label {
                font-size: 0.8rem;
            }

            body.standby-mode .field-value {
                font-size: clamp(1.18rem, 1.45vw, 1.55rem);
                line-height: 1.55;
            }

            body.standby-mode .standby-note {
                font-size: 1rem;
                padding: 16px 18px;
            }

            @media (max-width: 1280px) {
                body {
                    overflow: auto;
                }

                .board-shell {
                    min-height: 100vh;
                }

                .summary-strip,
                .content-grid {
                    grid-template-columns: 1fr;
                }

                .summary-strip {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }

            @media (max-width: 768px) {
                .board-shell {
                    padding: 14px;
                }

                .toolbar,
                .spotlight-panel,
                .board-panel,
                .ticker-shell {
                    border-radius: 24px;
                }

                .summary-strip {
                    grid-template-columns: 1fr;
                }

                .ticker-shell {
                    grid-template-columns: 1fr;
                }

                .toolbar-group,
                .toolbar-group-wide {
                    width: 100%;
                }

                .toolbar-control,
                .toolbar-button {
                    width: 100%;
                }
            }
        </style>
    </head>

    <body class="<?= $standbyDefault ? 'standby-mode' : ''; ?>">
        <main class="board-shell">
            <section class="ticker-shell">
                <div class="ticker-label">Live Feed</div>
                <div class="ticker-window">
                    <div class="ticker-track">
                        <?php if ($totalEmployees > 0): ?>
                            <?php foreach ($tickerEmployees as $employee): ?>
                                <div class="ticker-item">
                                    <strong><?= htmlspecialchars(trim($employee->fName . ' ' . $employee->lName), ENT_QUOTES, 'UTF-8'); ?></strong>
                                    <span><?= htmlspecialchars($employee->status, ENT_QUOTES, 'UTF-8'); ?></span>
                                    <span><?= htmlspecialchars($employee->location, ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="ticker-item">
                                <strong>Awaiting updates</strong>
                                <span>Post today’s whereabouts to start the standby feed.</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <section class="toolbar">
                <div class="toolbar-group toolbar-group-wide">
                    <input
                        type="date"
                        class="toolbar-control"
                        id="datePicker"
                        value="<?= htmlspecialchars($selectedDate, ENT_QUOTES, 'UTF-8'); ?>"
                        onchange="changeDate()"
                    >

                    <select class="toolbar-control" id="sectionFilter">
                        <option value="">All Sections</option>
                        <?php foreach ($sections as $section): ?>
                            <option value="<?= htmlspecialchars($section, ENT_QUOTES, 'UTF-8'); ?>">
                                <?= htmlspecialchars($section, ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div class="search-shell">
                        <input
                            type="text"
                            class="toolbar-control"
                            id="searchInput"
                            placeholder="Search personnel, section, location, or activity"
                        >
                        <i class="mdi mdi-magnify"></i>
                    </div>
                </div>

                <div class="toolbar-group">
                    <button type="button" class="toolbar-button toolbar-button-primary" id="standbyButton" onclick="toggleStandbyMode()">
                        <i class="mdi mdi-flash-outline"></i>
                        <span id="standbyButtonText"><?= $standbyDefault ? 'Exit Standby' : 'Start Standby'; ?></span>
                    </button>
                    <button type="button" class="toolbar-button toolbar-button-secondary" onclick="resetView()">
                        <i class="mdi mdi-refresh"></i>
                        Reset Board
                    </button>
                </div>
            </section>

            <section class="summary-strip">
                <article class="summary-card summary-card-total">
                    <span class="summary-label">Personnel on Board</span>
                    <strong class="summary-value" id="totalEmployees"><?= $totalEmployees; ?></strong>
                </article>
                <article class="summary-card summary-card-office">
                    <span class="summary-label">In Office</span>
                    <strong class="summary-value" id="inOfficeCount"><?= $statusCounts['In Office']; ?></strong>
                </article>
                <article class="summary-card summary-card-official">
                    <span class="summary-label">Official Business</span>
                    <strong class="summary-value" id="officialBusinessCount"><?= $statusCounts['On Official Business']; ?></strong>
                </article>
                <article class="summary-card summary-card-field">
                    <span class="summary-label">Field / Out</span>
                    <strong class="summary-value" id="fieldCount"><?= $statusCounts['On Field Work'] + $statusCounts['Out of Office']; ?></strong>
                </article>
                <article class="summary-card summary-card-leave">
                    <span class="summary-label">On Leave</span>
                    <strong class="summary-value" id="onLeaveCount"><?= $statusCounts['On Leave']; ?></strong>
                </article>
            </section>

            <section class="content-grid">
                <article class="spotlight-panel">
                    <div class="spotlight-top">
                        <div>
                            <div class="panel-kicker">Standby Spotlight</div>
                        </div>
                        <div class="spotlight-mode-pill" id="spotlightModePill">
                            <?= $standbyDefault ? htmlspecialchars($currentDateLabel, ENT_QUOTES, 'UTF-8') : ($isCurrentDay ? 'Ready for Standby' : 'Archive View'); ?>
                        </div>
                    </div>

                    <div class="spotlight-card <?= $featuredStatusClass ? $featuredStatusClass . ' ' : ''; ?><?= $featuredEmployee ? '' : 'is-empty'; ?>" id="spotlightCard">
                        <div class="spotlight-profile">
                            <div class="spotlight-avatar-shell has-image" id="spotlightAvatarShell">
                                <img
                                    src="<?= htmlspecialchars($featuredAvatarUrl, ENT_QUOTES, 'UTF-8'); ?>"
                                    alt="<?= $featuredEmployee ? htmlspecialchars($featuredEmployee->fName . ' ' . $featuredEmployee->lName, ENT_QUOTES, 'UTF-8') . ' avatar' : 'Personnel avatar'; ?>"
                                    class="spotlight-avatar-image"
                                    id="spotlightAvatarImage"
                                    onerror="this.style.display='none'; this.closest('.spotlight-avatar-shell').classList.remove('has-image');"
                                >
                                <div class="spotlight-avatar-fallback" id="spotlightAvatarFallback"><?= htmlspecialchars($featuredInitials, ENT_QUOTES, 'UTF-8'); ?></div>
                            </div>

                            <div class="spotlight-identity">
                                <div class="panel-kicker">Current Feature</div>
                                <h3 id="spotlightName">
                                    <?= $featuredEmployee ? htmlspecialchars($featuredEmployee->fName . ' ' . $featuredEmployee->lName, ENT_QUOTES, 'UTF-8') : 'No personnel posted yet'; ?>
                                </h3>
                                <p id="spotlightSubline">
                                    <?= $featuredEmployee ? htmlspecialchars($featuredEmployee->section, ENT_QUOTES, 'UTF-8') : 'Waiting for current day whereabouts entries.'; ?>
                                </p>
                            </div>
                        </div>

                        <div class="spotlight-badges" id="spotlightBadges"<?= $featuredEmployee ? '' : ' style="display:none;"'; ?>>
                            <span class="status-badge <?= $featuredStatusClass; ?>" id="spotlightStatus">
                                <?= $featuredEmployee ? htmlspecialchars($featuredEmployee->status, ENT_QUOTES, 'UTF-8') : ''; ?>
                            </span>
                            <span class="spotlight-section-pill" id="spotlightSection">
                                <?= $featuredEmployee ? htmlspecialchars($featuredEmployee->section, ENT_QUOTES, 'UTF-8') : ''; ?>
                            </span>
                        </div>

                        <div class="spotlight-fields">
                            <div class="spotlight-field">
                                <span class="field-label" id="spotlightPrimaryLabel"><?= $featuredEmployee ? 'Location' : 'Board Status'; ?></span>
                                <p class="field-value" id="spotlightLocation">
                                    <?= $featuredEmployee ? htmlspecialchars($featuredEmployee->location, ENT_QUOTES, 'UTF-8') : 'No whereabouts posted for this date.'; ?>
                                </p>
                            </div>

                            <div class="spotlight-field">
                                <span class="field-label"><?= $featuredEmployee ? 'Activity' : 'Next Step'; ?></span>
                                <p class="field-value <?= $featuredEmployee ? '' : 'field-value-muted'; ?>" id="spotlightActivity">
                                    <?= $featuredEmployee ? htmlspecialchars($featuredEmployee->activity, ENT_QUOTES, 'UTF-8') : 'As soon as personnel submit their whereabouts, they will rotate here automatically.'; ?>
                                </p>
                            </div>

                            <div class="spotlight-field" id="spotlightNotesWrap"<?= ($featuredEmployee && !empty($featuredEmployee->notes)) ? '' : ' style="display:none;"'; ?>>
                                <span class="field-label">Notes</span>
                                <p class="field-value field-value-muted" id="spotlightNotes">
                                    <?= $featuredEmployee && !empty($featuredEmployee->notes) ? htmlspecialchars($featuredEmployee->notes, ENT_QUOTES, 'UTF-8') : ''; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="standby-note<?= $isCurrentDay ? '' : ' is-hidden'; ?>" id="standbyHint">
                        <?= $isCurrentDay
                            ? 'Standby mode automatically cycles through today’s latest personnel whereabouts and refreshes the board to stay current.'
                            : ''; ?>
                    </div>
                </article>

                <article class="board-panel">
                    <div class="empty-filter-state" id="emptyFilterState">
                        No personnel matched the active search or section filter.
                    </div>

                    <?php if ($totalEmployees > 0): ?>
                        <div class="employee-grid" id="employeeGrid">
                            <?php foreach ($employees as $index => $employee): ?>
                                <?php
                                $statusClass = isset($statusClasses[$employee->status]) ? $statusClasses[$employee->status] : 'status-in-office';
                                $initials = $buildInitials($employee->fName, $employee->lName);
                                $avatarUrl = $buildAvatarUrl($employee->userAvatar ?? '');
                                $fullName = trim($employee->fName . ' ' . $employee->lName);
                                ?>
                                <button
                                    type="button"
                                    class="employee-card<?= $index === 0 ? ' active' : ''; ?>"
                                    data-card-index="<?= $index; ?>"
                                    data-employee-name="<?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-section-label="<?= htmlspecialchars($employee->section, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-status="<?= htmlspecialchars($employee->status, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-status-class="<?= htmlspecialchars($statusClass, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-location="<?= htmlspecialchars($employee->location, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-activity="<?= htmlspecialchars($employee->activity, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-notes="<?= htmlspecialchars((string) $employee->notes, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-avatar-url="<?= htmlspecialchars($avatarUrl, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-avatar-initials="<?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8'); ?>"
                                >
                                    <span class="employee-card-top">
                                        <span class="employee-avatar has-image">
                                            <img
                                                src="<?= htmlspecialchars($avatarUrl, ENT_QUOTES, 'UTF-8'); ?>"
                                                alt="<?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'); ?> avatar"
                                                class="employee-avatar-image"
                                                onerror="this.style.display='none'; this.closest('.employee-avatar').classList.remove('has-image');"
                                            >
                                            <span class="employee-avatar-fallback"><?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8'); ?></span>
                                        </span>
                                        <span class="employee-identity">
                                            <strong><?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'); ?></strong>
                                            <span><?= htmlspecialchars($employee->section, ENT_QUOTES, 'UTF-8'); ?></span>
                                        </span>
                                    </span>
                                    <span class="status-badge <?= $statusClass; ?>"><?= htmlspecialchars($employee->status, ENT_QUOTES, 'UTF-8'); ?></span>
                                    <span class="employee-snippet">
                                        <strong>Location</strong>
                                        <span><?= htmlspecialchars($employee->location, ENT_QUOTES, 'UTF-8'); ?></span>
                                    </span>
                                    <span class="employee-snippet">
                                        <strong>Activity</strong>
                                        <span><?= htmlspecialchars($employee->activity, ENT_QUOTES, 'UTF-8'); ?></span>
                                    </span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="board-empty">
                            <div>
                                <i class="mdi mdi-account-off-outline"></i>
                                <p>No whereabouts posted for <?= htmlspecialchars($displayDate, ENT_QUOTES, 'UTF-8'); ?>.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </article>
            </section>

        </main>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script>
            const TODAY_DATE = '<?= $currentDate; ?>';
            const TODAY_DATE_LABEL = '<?= htmlspecialchars($currentDateLabel, ENT_QUOTES, 'UTF-8'); ?>';
            const PAGE_URL = '<?= base_url(); ?>Page/public_whereabouts';
            const DEFAULT_AVATAR_URL = '<?= htmlspecialchars($defaultAvatarUrl, ENT_QUOTES, 'UTF-8'); ?>';
            const MANILA_TIMEZONE = 'Asia/Manila';
            const searchInput = document.getElementById('searchInput');
            const sectionFilter = document.getElementById('sectionFilter');
            const datePicker = document.getElementById('datePicker');
            const totalEmployeesEl = document.getElementById('totalEmployees');
            const inOfficeCountEl = document.getElementById('inOfficeCount');
            const officialBusinessCountEl = document.getElementById('officialBusinessCount');
            const fieldCountEl = document.getElementById('fieldCount');
            const onLeaveCountEl = document.getElementById('onLeaveCount');
            const emptyFilterState = document.getElementById('emptyFilterState');
            const standbyHint = document.getElementById('standbyHint');
            const standbyButtonText = document.getElementById('standbyButtonText');
            const spotlightModePill = document.getElementById('spotlightModePill');
            const spotlightCard = document.getElementById('spotlightCard');
            const spotlightName = document.getElementById('spotlightName');
            const spotlightSubline = document.getElementById('spotlightSubline');
            const spotlightStatus = document.getElementById('spotlightStatus');
            const spotlightSection = document.getElementById('spotlightSection');
            const spotlightBadges = document.getElementById('spotlightBadges');
            const spotlightPrimaryLabel = document.getElementById('spotlightPrimaryLabel');
            const spotlightLocation = document.getElementById('spotlightLocation');
            const spotlightActivity = document.getElementById('spotlightActivity');
            const spotlightNotes = document.getElementById('spotlightNotes');
            const spotlightNotesWrap = document.getElementById('spotlightNotesWrap');
            const spotlightAvatarShell = document.getElementById('spotlightAvatarShell');
            const spotlightAvatarImage = document.getElementById('spotlightAvatarImage');
            const spotlightAvatarFallback = document.getElementById('spotlightAvatarFallback');
            const employeeCards = Array.from(document.querySelectorAll('.employee-card'));

            let standbyMode = <?= $standbyDefault ? 'true' : 'false'; ?>;
            let spotlightIndex = 0;
            let standbyRotationInterval = null;
            let idleTimeout = null;
            let isBooting = true;

            function isBrowserFullscreenActive() {
                return Boolean(
                    document.fullscreenElement ||
                    document.webkitFullscreenElement ||
                    document.msFullscreenElement
                );
            }

            function enterBrowserFullscreen() {
                const target = document.documentElement;

                if (isBrowserFullscreenActive()) {
                    return Promise.resolve(true);
                }

                try {
                    if (target.requestFullscreen) {
                        const result = target.requestFullscreen({ navigationUI: 'hide' });
                        return result && typeof result.then === 'function' ? result : Promise.resolve(true);
                    }

                    if (target.webkitRequestFullscreen) {
                        target.webkitRequestFullscreen();
                        return Promise.resolve(true);
                    }

                    if (target.msRequestFullscreen) {
                        target.msRequestFullscreen();
                        return Promise.resolve(true);
                    }
                } catch (error) {
                    return Promise.reject(error);
                }

                return Promise.resolve(false);
            }

            function exitBrowserFullscreen() {
                try {
                    if (document.exitFullscreen && document.fullscreenElement) {
                        document.exitFullscreen();
                        return;
                    }

                    if (document.webkitExitFullscreen && document.webkitFullscreenElement) {
                        document.webkitExitFullscreen();
                        return;
                    }

                    if (document.msExitFullscreen && document.msFullscreenElement) {
                        document.msExitFullscreen();
                    }
                } catch (error) {
                    // Ignore fullscreen exit failures and keep the board responsive.
                }
            }

            function getVisibleCards() {
                return employeeCards.filter(card => !card.classList.contains('is-hidden'));
            }

            function decodeValue(value) {
                return value ? value.trim() : '';
            }

            function syncSpotlightAvatar(imageUrl, initials, name) {
                if (!spotlightAvatarShell || !spotlightAvatarImage || !spotlightAvatarFallback) {
                    return;
                }

                spotlightAvatarFallback.textContent = initials || 'SG';
                spotlightAvatarShell.classList.add('has-image');
                spotlightAvatarImage.style.display = 'block';
                spotlightAvatarImage.src = imageUrl || DEFAULT_AVATAR_URL;
                spotlightAvatarImage.alt = (name || 'Personnel') + ' avatar';
            }

            function setStandbyHint(message) {
                if (!standbyHint) {
                    return;
                }

                const text = (message || '').trim();
                standbyHint.textContent = text;
                standbyHint.classList.toggle('is-hidden', text === '');
            }

            function setActiveCard(card) {
                employeeCards.forEach(item => item.classList.remove('active'));

                if (card) {
                    card.classList.add('active');
                }
            }

            function setSpotlightFromCard(card) {
                if (!card || !spotlightCard) {
                    return;
                }

                const name = decodeValue(card.dataset.employeeName);
                const section = decodeValue(card.dataset.sectionLabel);
                const status = decodeValue(card.dataset.status);
                const statusClass = decodeValue(card.dataset.statusClass);
                const location = decodeValue(card.dataset.location);
                const activity = decodeValue(card.dataset.activity);
                const notes = decodeValue(card.dataset.notes);
                const avatarUrl = decodeValue(card.dataset.avatarUrl);
                const avatarInitials = decodeValue(card.dataset.avatarInitials);

                setActiveCard(card);
                syncSpotlightAvatar(avatarUrl, avatarInitials, name);
                spotlightName.textContent = name || 'Personnel record';
                spotlightSubline.textContent = section || 'Section not specified';
                spotlightPrimaryLabel.textContent = 'Location';
                spotlightLocation.textContent = location || 'No location provided.';
                spotlightActivity.textContent = activity || 'No activity provided.';
                spotlightActivity.classList.remove('field-value-muted');
                spotlightBadges.style.display = 'flex';
                spotlightSection.textContent = section || 'Section not specified';
                spotlightStatus.textContent = status || 'Status unavailable';
                spotlightStatus.className = 'status-badge ' + (statusClass || '');
                spotlightCard.className = 'spotlight-card ' + (statusClass || '');

                if (notes) {
                    spotlightNotesWrap.style.display = 'block';
                    spotlightNotes.textContent = notes;
                } else {
                    spotlightNotesWrap.style.display = 'none';
                    spotlightNotes.textContent = '';
                }
            }

            function setSpotlightEmpty(title, message) {
                if (!spotlightCard) {
                    return;
                }

                setActiveCard(null);
                syncSpotlightAvatar(DEFAULT_AVATAR_URL, 'SG', 'SGOD');
                spotlightCard.className = 'spotlight-card is-empty';
                spotlightName.textContent = title;
                spotlightSubline.textContent = 'Live board waiting for a visible personnel card.';
                spotlightPrimaryLabel.textContent = 'Board Status';
                spotlightLocation.textContent = message;
                spotlightActivity.textContent = 'Clear the filters or choose today’s date to return the standby feed.';
                spotlightActivity.classList.add('field-value-muted');
                spotlightBadges.style.display = 'none';
                spotlightNotesWrap.style.display = 'none';
                spotlightNotes.textContent = '';
            }

            function updateStats() {
                const visibleCards = getVisibleCards();
                let inOffice = 0;
                let officialBusiness = 0;
                let fieldOrOut = 0;
                let onLeave = 0;

                visibleCards.forEach(card => {
                    const status = card.dataset.status;

                    if (status === 'In Office') {
                        inOffice++;
                    } else if (status === 'On Official Business') {
                        officialBusiness++;
                    } else if (status === 'On Field Work' || status === 'Out of Office') {
                        fieldOrOut++;
                    } else if (status === 'On Leave') {
                        onLeave++;
                    }
                });

                totalEmployeesEl.textContent = visibleCards.length;
                inOfficeCountEl.textContent = inOffice;
                officialBusinessCountEl.textContent = officialBusiness;
                fieldCountEl.textContent = fieldOrOut;
                onLeaveCountEl.textContent = onLeave;
            }

            function stopStandbyRotation() {
                if (standbyRotationInterval) {
                    clearInterval(standbyRotationInterval);
                    standbyRotationInterval = null;
                }
            }

            function startStandbyRotation() {
                stopStandbyRotation();

                if (!standbyMode) {
                    return;
                }

                const visibleCards = getVisibleCards();
                if (!visibleCards.length) {
                    setSpotlightEmpty('No visible personnel', 'There are no visible personnel cards to cycle in standby mode.');
                    return;
                }

                if (spotlightIndex >= visibleCards.length) {
                    spotlightIndex = 0;
                }

                setSpotlightFromCard(visibleCards[spotlightIndex]);

                standbyRotationInterval = setInterval(() => {
                    const currentVisibleCards = getVisibleCards();
                    if (!standbyMode || !currentVisibleCards.length) {
                        return;
                    }

                    spotlightIndex = (spotlightIndex + 1) % currentVisibleCards.length;
                    setSpotlightFromCard(currentVisibleCards[spotlightIndex]);
                }, 3200);
            }

            function applyFilters(keepCurrentSpotlight = false) {
                const searchTerm = searchInput ? searchInput.value.trim().toLowerCase() : '';
                const sectionTerm = sectionFilter ? sectionFilter.value.trim().toLowerCase() : '';
                const activeCard = document.querySelector('.employee-card.active');

                employeeCards.forEach(card => {
                    const haystack = [
                        card.dataset.employeeName,
                        card.dataset.sectionLabel,
                        card.dataset.status,
                        card.dataset.location,
                        card.dataset.activity
                    ].join(' ').toLowerCase();
                    const section = card.dataset.sectionLabel.toLowerCase();
                    const matchesSearch = !searchTerm || haystack.includes(searchTerm);
                    const matchesSection = !sectionTerm || section === sectionTerm;

                    card.classList.toggle('is-hidden', !(matchesSearch && matchesSection));
                });

                const visibleCards = getVisibleCards();
                updateStats();

                if (emptyFilterState) {
                    emptyFilterState.classList.toggle('show', employeeCards.length > 0 && visibleCards.length === 0);
                }

                if (!visibleCards.length) {
                    stopStandbyRotation();
                    setSpotlightEmpty('No personnel matched', 'No personnel matched the current search or section filter.');
                    return;
                }

                if (keepCurrentSpotlight && activeCard && !activeCard.classList.contains('is-hidden')) {
                    spotlightIndex = visibleCards.indexOf(activeCard);
                    setSpotlightFromCard(activeCard);
                } else {
                    spotlightIndex = 0;
                    setSpotlightFromCard(visibleCards[0]);
                }

                if (standbyMode) {
                    startStandbyRotation();
                }
            }

            function syncModeLabels() {
                standbyButtonText.textContent = standbyMode ? 'Exit Standby' : 'Start Standby';

                if (datePicker.value === TODAY_DATE) {
                    spotlightModePill.textContent = standbyMode ? TODAY_DATE_LABEL : 'Ready for Standby';
                } else {
                    spotlightModePill.textContent = 'Archive View';
                }
            }

            function enterStandbyMode() {
                if (datePicker.value !== TODAY_DATE) {
                    window.location.href = PAGE_URL + '?date=' + TODAY_DATE;
                    return;
                }

                standbyMode = true;
                document.body.classList.add('standby-mode');
                syncModeLabels();
                setStandbyHint('Standby mode is flashing today’s latest personnel whereabouts and will keep rotating automatically.');

                const fullscreenRequest = enterBrowserFullscreen();
                if (fullscreenRequest && typeof fullscreenRequest.catch === 'function') {
                    fullscreenRequest.catch(() => {
                        if (standbyMode) {
                            setStandbyHint('Standby mode is active. If the browser blocks full screen, click Start Standby once or press F11 for a true kiosk display.');
                        }
                    });
                }

                if (searchInput.value || sectionFilter.value) {
                    searchInput.value = '';
                    sectionFilter.value = '';
                    applyFilters();
                } else {
                    startStandbyRotation();
                }
            }

            function exitStandbyMode() {
                standbyMode = false;
                document.body.classList.remove('standby-mode');
                stopStandbyRotation();
                exitBrowserFullscreen();
                syncModeLabels();

                if (datePicker.value === TODAY_DATE) {
                    setStandbyHint('');
                } else {
                    setStandbyHint('');
                }

                const visibleCards = getVisibleCards();
                if (visibleCards.length) {
                    const activeCard = document.querySelector('.employee-card.active:not(.is-hidden)');
                    setSpotlightFromCard(activeCard || visibleCards[0]);
                }
            }

            function toggleStandbyMode() {
                if (standbyMode) {
                    exitStandbyMode();
                } else {
                    enterStandbyMode();
                }

                scheduleStandbyReturn();
            }

            function changeDate() {
                const date = datePicker.value || TODAY_DATE;
                window.location.href = PAGE_URL + '?date=' + encodeURIComponent(date);
            }

            function resetView() {
                if (datePicker.value !== TODAY_DATE) {
                    window.location.href = PAGE_URL + '?date=' + TODAY_DATE;
                    return;
                }

                searchInput.value = '';
                sectionFilter.value = '';
                applyFilters();
                enterStandbyMode();
            }

            function scheduleStandbyReturn() {
                clearTimeout(idleTimeout);

                idleTimeout = setTimeout(() => {
                    if (!standbyMode) {
                        enterStandbyMode();
                    }
                }, 20000);
            }

            function handleWakeInteraction(event) {
                if (isBooting || employeeCards.length === 0) {
                    return;
                }

                const target = event && event.target ? event.target : null;
                if (standbyMode && target && typeof target.closest === 'function' && target.closest('#standbyButton')) {
                    return;
                }

                if (standbyMode) {
                    exitStandbyMode();
                }

                scheduleStandbyReturn();
            }

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    if (standbyMode) {
                        exitStandbyMode();
                    }

                    applyFilters(true);
                    scheduleStandbyReturn();
                });
            }

            if (sectionFilter) {
                sectionFilter.addEventListener('change', function() {
                    if (standbyMode) {
                        exitStandbyMode();
                    }

                    applyFilters();
                    scheduleStandbyReturn();
                });
            }

            employeeCards.forEach(card => {
                card.addEventListener('click', function() {
                    if (standbyMode) {
                        exitStandbyMode();
                    }

                    const visibleCards = getVisibleCards();
                    spotlightIndex = Math.max(visibleCards.indexOf(card), 0);
                    setSpotlightFromCard(card);
                    scheduleStandbyReturn();
                });
            });

            ['mousemove', 'mousedown', 'keydown', 'touchstart', 'wheel'].forEach(eventName => {
                document.addEventListener(eventName, handleWakeInteraction, { passive: true });
            });

            ['fullscreenchange', 'webkitfullscreenchange', 'msfullscreenchange'].forEach(eventName => {
                document.addEventListener(eventName, function() {
                    if (standbyMode && !isBrowserFullscreenActive()) {
                        setStandbyHint('Standby mode is active. If the browser shows its address bar again, click Start Standby once or press F11 to restore full screen.');
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                updateStats();

                if (employeeCards.length > 0) {
                    applyFilters(true);
                }

                syncModeLabels();

                if (standbyMode && datePicker.value === TODAY_DATE) {
                    startStandbyRotation();
                } else {
                    exitStandbyMode();
                }

                if (datePicker.value === TODAY_DATE) {
                    scheduleStandbyReturn();
                    setInterval(() => {
                        if (standbyMode) {
                            window.location.href = PAGE_URL + '?date=' + TODAY_DATE;
                        }
                    }, 60000);
                }

                window.setTimeout(() => {
                    isBooting = false;
                }, 1200);
            });
        </script>
    </body>
</html>
