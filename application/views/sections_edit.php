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
        <link href="<?= base_url(); ?>assets/libs/select2/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />

        <style>
            :root {
                --sections-navy: #16324f;
                --sections-blue: #2d7ff9;
                --sections-teal: #0f9f9a;
                --sections-gold: #efb54a;
                --sections-ink: #22384d;
                --sections-muted: #72859a;
                --sections-border: rgba(22, 50, 79, 0.12);
                --sections-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            }

            body {
                background:
                    radial-gradient(circle at top left, rgba(45, 127, 249, 0.10), transparent 24%),
                    linear-gradient(180deg, #f4f8fc 0%, #eef4fa 100%);
            }

            .content-page {
                background: transparent;
            }

            .sections-shell {
                position: relative;
                padding-bottom: 28px;
            }

            .sections-shell::before {
                content: "";
                position: absolute;
                inset: 24px 0 auto;
                height: 240px;
                border-radius: 30px;
                background: linear-gradient(135deg, rgba(45, 127, 249, 0.09), rgba(15, 159, 154, 0.08));
                z-index: 0;
            }

            .sections-shell > * {
                position: relative;
                z-index: 1;
            }

            .sections-hero {
                margin-top: 20px;
                border-radius: 28px;
                overflow: hidden;
                color: #ffffff;
                box-shadow: var(--sections-shadow);
                background:
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 32%),
                    linear-gradient(135deg, #16324f 0%, #1f5fa8 58%, #0f9f9a 100%);
            }

            .sections-hero-body {
                padding: 32px;
            }

            .sections-eyebrow {
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

            .sections-title {
                margin: 18px 0 12px;
                color: #ffffff;
                font-size: clamp(2rem, 3vw, 2.7rem);
                line-height: 1.05;
                font-weight: 700;
                letter-spacing: -0.03em;
                font-family: "Avenir Next", "Segoe UI", sans-serif;
            }

            .form-card {
                border: 1px solid rgba(22, 50, 79, 0.08);
                border-radius: 22px;
                background: #ffffff;
                padding: 28px;
                box-shadow: var(--sections-shadow);
            }

            .modern-label {
                color: var(--sections-ink);
                font-weight: 700;
                margin-bottom: 8px;
            }

            .modern-input {
                min-height: 48px;
                border-radius: 14px;
                border: 1px solid rgba(22, 50, 79, 0.14);
                padding: 12px 14px;
                box-shadow: none;
            }

            .modern-input:focus {
                border-color: var(--sections-blue);
                box-shadow: 0 0 0 0.18rem rgba(45, 127, 249, 0.14);
            }

            .modern-input:read-only {
                background: #f8fbff;
                color: var(--sections-muted);
            }

            .select2-container {
                width: 100% !important;
            }

            .select2-container--default .select2-selection--single {
                min-height: 48px;
                border-radius: 14px;
                border: 1px solid rgba(22, 50, 79, 0.14);
                padding: 9px 14px;
            }

            .select2-container--default .select2-selection--multiple {
                min-height: 48px;
                border-radius: 14px;
                border: 1px solid rgba(22, 50, 79, 0.14);
                padding: 6px 10px;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 28px;
                padding-left: 0;
                color: var(--sections-ink);
            }

            .select2-container--default .select2-selection--multiple .select2-selection__rendered {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
                padding: 0;
            }

            .select2-container--default .select2-selection--multiple .select2-selection__choice {
                margin-top: 0;
                border: none;
                border-radius: 999px;
                padding: 5px 10px;
                background: rgba(45, 127, 249, 0.12);
                color: var(--sections-ink);
                font-weight: 600;
            }

            .select2-container--default .select2-selection--multiple .select2-search--inline .select2-search__field {
                margin-top: 0;
                min-height: 28px;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 46px;
                right: 10px;
            }

            .select2-container--default.select2-container--focus .select2-selection--single,
            .select2-container--default.select2-container--focus .select2-selection--multiple,
            .select2-container--default.select2-container--open .select2-selection--multiple,
            .select2-container--default.select2-container--open .select2-selection--single {
                border-color: var(--sections-blue);
                box-shadow: 0 0 0 0.18rem rgba(45, 127, 249, 0.14);
            }

            .btn-gradient-primary {
                color: #ffffff;
                border: none;
                border-radius: 14px;
                padding: 11px 24px;
                font-weight: 700;
                background: linear-gradient(135deg, #2d7ff9 0%, #0f9f9a 100%);
                box-shadow: 0 14px 30px rgba(45, 127, 249, 0.18);
            }

            .btn-gradient-primary:hover {
                color: #ffffff;
            }

            .btn-soft-dark {
                color: var(--sections-ink);
                background: #eef3f8;
                border: none;
                border-radius: 14px;
                padding: 11px 20px;
                font-weight: 700;
            }

            @media (max-width: 767.98px) {
                .sections-hero-body {
                    padding: 22px;
                }

                .sections-title {
                    font-size: 1.9rem;
                }
            }
        </style>
    </head>

    <body>

        <?php
            $identifier = $data->secGroup ?? 'SGOD';
            $staffOptions = isset($staffOptions) && is_array($staffOptions) ? $staffOptions : [];
            $staffDirectory = [];
            $currentSectionHead = trim((string) $data->sectionHead);
            $staffMemberLookup = [];
            $parseSectionMembers = static function ($memberValue) {
                $memberValue = trim((string) $memberValue);
                if ($memberValue === '') {
                    return [];
                }

                if (strpos($memberValue, ';') !== false) {
                    $rawMembers = explode(';', $memberValue);
                } elseif (preg_match('/\R/', $memberValue)) {
                    $rawMembers = preg_split('/\R+/', $memberValue);
                } else {
                    $rawMembers = [$memberValue];
                }

                $members = [];
                $memberIndex = [];

                foreach ($rawMembers as $rawMember) {
                    $normalizedMember = trim(preg_replace('/\s+/', ' ', (string) $rawMember));
                    if ($normalizedMember === '') {
                        continue;
                    }

                    $memberKey = strtolower($normalizedMember);
                    if (isset($memberIndex[$memberKey])) {
                        continue;
                    }

                    $memberIndex[$memberKey] = true;
                    $members[] = $normalizedMember;
                }

                return $members;
            };
            $currentMembers = $parseSectionMembers($data->member);
            $currentMembersLookup = [];

            foreach ($currentMembers as $currentMember) {
                $currentMembersLookup[$currentMember] = true;
            }

            foreach ($staffOptions as $staffRow) {
                $staffId = trim((string) $staffRow->IDNumber);
                if ($staffId === '') {
                    continue;
                }

                $displayName = trim(preg_replace('/\s+/', ' ', implode(' ', array_filter([
                    trim((string) $staffRow->LastName) !== '' ? trim((string) $staffRow->LastName) . ',' : '',
                    trim((string) $staffRow->FirstName),
                    trim((string) $staffRow->MiddleName),
                    trim((string) $staffRow->NameExtn),
                ]))));
                $memberLabel = trim(preg_replace('/\s+/', ' ', implode(' ', array_filter([
                    trim((string) $staffRow->FirstName),
                    trim((string) $staffRow->MiddleName),
                    trim((string) $staffRow->LastName),
                    trim((string) $staffRow->NameExtn),
                ]))));

                if ($memberLabel === '') {
                    $memberLabel = str_replace(',', '', $displayName);
                }

                if ($memberLabel === '') {
                    $memberLabel = $staffId;
                }

                $memberLabel .= ' (' . $staffId . ')';

                $staffDirectory[$staffId] = [
                    'name' => $displayName,
                    'position' => trim((string) ($staffRow->empPosition !== '' ? $staffRow->empPosition : $staffRow->jobTitle)),
                    'member_label' => $memberLabel,
                ];
                $staffMemberLookup[$memberLabel] = true;
            }
        ?>

        <div id="wrapper">
            <?php include('includes/top-bar.php'); ?>
            <?php include('includes/sidebar.php') ?>

            <div class="content-page">
                <div class="content">
                    <div class="container-fluid sections-shell">
                        <div class="row">
                            <div class="col-12">
                                <div class="sections-hero">
                                    <div class="sections-hero-body">
                                        <span class="sections-eyebrow">
                                            <i class="mdi mdi-pencil-outline"></i>
                                            Edit Section Record
                                        </span>
                                        <h1 class="sections-title">Update section details for <?= htmlspecialchars($identifier, ENT_QUOTES, 'UTF-8'); ?></h1>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="form-card">
                                    <form method="post">
                                        <div class="form-group">
                                            <label class="modern-label">Section <span class="text-danger">*</span></label>
                                            <input type="text" name="sectionName" required class="form-control modern-input" value="<?= htmlspecialchars($data->sectionName, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="modern-label">Section Head</label>
                                            <select class="form-control modern-input" id="sectionHeadSelectEdit" name="sectionHead">
                                                <option value="">Select section head</option>
                                                <?php foreach ($staffDirectory as $staffId => $staffProfile) { ?>
                                                    <?php $staffIdValue = (string) $staffId; ?>
                                                    <option value="<?= htmlspecialchars($staffIdValue, ENT_QUOTES, 'UTF-8'); ?>" data-position="<?= htmlspecialchars($staffProfile['position'], ENT_QUOTES, 'UTF-8'); ?>" <?= $currentSectionHead === $staffIdValue ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars($staffProfile['name'] . ' (' . $staffIdValue . ')', ENT_QUOTES, 'UTF-8'); ?>
                                                    </option>
                                                <?php } ?>
                                                <?php if ($currentSectionHead !== '' && !isset($staffDirectory[$currentSectionHead])) { ?>
                                                    <option value="<?= htmlspecialchars($currentSectionHead, ENT_QUOTES, 'UTF-8'); ?>" selected>
                                                        <?= htmlspecialchars($currentSectionHead, ENT_QUOTES, 'UTF-8'); ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="modern-label">Position</label>
                                            <input type="text" class="form-control modern-input" id="sectionHeadPositionInputEdit" name="sectionHeadPosition" value="<?= htmlspecialchars($data->sectionHeadPosition, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label class="modern-label">Section Group <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control modern-input" value="<?= htmlspecialchars($data->secGroup, ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                            <input type="hidden" name="secGroup" value="<?= htmlspecialchars($data->secGroup, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label class="modern-label">Members</label>
                                            <select class="form-control modern-input" id="sectionMembersSelectEdit" name="member[]" multiple>
                                                <?php foreach ($staffDirectory as $staffProfile) { ?>
                                                    <option value="<?= htmlspecialchars($staffProfile['member_label'], ENT_QUOTES, 'UTF-8'); ?>" <?= isset($currentMembersLookup[$staffProfile['member_label']]) ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars($staffProfile['member_label'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </option>
                                                <?php } ?>
                                                <?php foreach ($currentMembers as $currentMember) { ?>
                                                    <?php if (!isset($staffMemberLookup[$currentMember])) { ?>
                                                        <option value="<?= htmlspecialchars($currentMember, ENT_QUOTES, 'UTF-8'); ?>" selected>
                                                            <?= htmlspecialchars($currentMember, ENT_QUOTES, 'UTF-8'); ?>
                                                        </option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <input type="hidden" required class="form-control" name="id" value="<?= $data->id; ?>">
                                        </div>

                                        <div class="form-group text-right mb-0">
                                            <a href="<?= base_url(); ?>Page/sections" class="btn btn-soft-dark mr-2">Cancel</a>
                                            <input type="submit" name="submit" value="Save Changes" class="btn btn-gradient-primary">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include('includes/footer.php'); ?>
            </div>
        </div>

        <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
        <script src="<?= base_url(); ?>assets/libs/select2/select2.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
        <script>
            $(function () {
                const sectionHeadSelect = $('#sectionHeadSelectEdit');
                const sectionHeadPositionInput = $('#sectionHeadPositionInputEdit');
                const sectionMembersSelect = $('#sectionMembersSelectEdit');

                sectionHeadSelect.select2({
                    placeholder: 'Select section head',
                    width: '100%'
                });

                sectionMembersSelect.select2({
                    placeholder: 'Select or add members',
                    width: '100%',
                    closeOnSelect: false,
                    tags: true
                });

                sectionHeadSelect.on('change', function () {
                    const selectedOption = sectionHeadSelect.find('option:selected');
                    const selectedPosition = selectedOption.data('position') || '';
                    if (selectedPosition !== '') {
                        sectionHeadPositionInput.val(selectedPosition);
                    }
                });

                sectionHeadSelect.trigger('change');
            });
        </script>

    </body>
</html>
