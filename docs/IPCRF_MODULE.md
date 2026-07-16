# SGOD IPCRF Performance Management Module

The module is available to authenticated MIS users at `Ipcrf`. The page is a personal workspace: the signed-in account is resolved to its HRIS employee ID, and only that employee's IPCRFs appear. The employee selector has been removed; the employee only chooses an assigned rater and a performance period.

An IPCRF is visible to its employee owner and assigned rater. PMT visibility begins only after submission to PMT, while system administrators retain the existing administrative override. Users who have been assigned as a rater receive a conditional **IPCRF Rater Review** sidebar link. Its badge counts records currently **Submitted to Rater**, and the linked queue contains only those pending records assigned to the signed-in rater.

## Installation

1. Import `sql/create_ipcrf_module.sql` into the existing `depedmis_hris` database. The application also creates missing module tables on first authenticated use.
2. Ensure `upload/ipcrf_evidence` is writable by the Apache/PHP process. For the local XAMPP installation, the directory has already been created and made writable.
3. Open the module once while signed in. The first visit seeds the master **SGOD IPCRF 2025 Preset** transcribed from `ipcr/IPCRF_2025.pdf`.

The preset contains 5 KRAs, 15 objectives totaling exactly 100%, complete 1–5 Quality/Efficiency/Timeliness indicators, and 9 competencies. Loading it copies the data into the employee IPCRF; later edits never change the master.

New Draft records automatically receive an editable copy of the active preset. The timeline year is adjusted to the selected performance period. Existing empty Drafts with no preset are initialized the next time an authorized editor opens them.

The editor uses the full available content width. The former Section Navigator and Weight/Rating/Validation Summary have been removed. The toolbar now opens **Tracking History**, which shows the current status and recorded workflow transitions.

New KRAs and objectives use short, labeled dialogs so incomplete placeholder records are not created. Existing KRA titles and objective code, description, timeline, weight, actual result, and ratings edit directly in the document-style table. There is no Actions column; occasional copy, move, and delete commands live in an overflow menu inside the objective or KRA. Every KRA header has a right-aligned **Fold Table / Open Table** control. Clicking a Quality, Efficiency, or Timeliness cell opens a modal containing all five rating levels and all three dimensions.

Competency Management uses the same document-style pattern. The preset competencies are grouped by type, each group has the same explicit fold/open control, names and behavioral indicators edit directly, and a single **Rating** replaces the former Employee, Rater, and Final columns. New competencies are created through the labeled **Add Competency** dialog so blank placeholder rows are not saved.

The owner can enter proposed Q/E/T objective ratings in Draft or Returned status. The assigned rater reviews or revises those values after submission. Proposed averages are visible, but weighted scores, overall totals, adjectival ratings, and printed scores remain pending until the workflow reaches **Rater Approved**.

Submitting a Draft or Returned form to the rater is non-blocking. If required information, standards, accomplishments, competency ratings, or the 100% weight total are incomplete, the owner sees the current warning list and can return to editing or explicitly choose **Submit Anyway**. This acknowledgement is recorded in Tracking History. Later PMT transitions retain strict server-side validation.

Development Plan entries are created through a labeled modal and remain directly editable in the table. During **Submitted to Rater**, the assigned rater can add, edit, or remove Development Plan entries in addition to reviewing ratings. This does not grant the rater access to employee-owned KRA, objective, standard, accomplishment, evidence, period, or rater-assignment fields.

The standalone **Validate Review** control has been removed. A **Missing Items** button appears only when the current server-side review finds incomplete information and opens the warning list. Employee submission and rater approval are non-blocking: either action can proceed after explicit acknowledgement. The rater can instead choose **Return with Remarks**; remarks are required and appear in Tracking History so the employee knows what to revise.

## Workflow permissions

- **Draft / Returned for Revision:** the employee owner or system administrator can edit the complete form and propose objective Q/E/T ratings.
- **Submitted to Rater:** the assigned rater reviews or revises objective Q/E/T ratings, the competency Rating, and Development Plan entries, then approves or returns the form. Approval warnings can be acknowledged with **Approve Anyway**; returns require remarks.
- **Rater Approved:** the assigned rater submits the form to PMT.
- **Submitted to PMT:** PMT users can review or revise the competency Rating and validate the form.
- **PMT Validated:** PMT users can lock the record.
- **Locked:** the form is read-only. Only a system administrator can reopen it, and a reason is required.

`Chief - SGOD`, `System Administrator`, and `Super Admin` accounts are treated as PMT users. System and super administrators can reopen records.

## Report

Preview and Print PDF open a six-page, landscape, DepEd-style print view:

1. Pages 1–4: employee/rater details, KRAs, objectives, standards, accomplishments and Q/E/T ratings
2. Page 5: competencies and their single recorded ratings
3. Page 6: rating summary, development plan, workflow record and signature blocks

The Print PDF action opens the browser print dialog so the report can be printed or saved directly as PDF without a server-side PDF dependency.
