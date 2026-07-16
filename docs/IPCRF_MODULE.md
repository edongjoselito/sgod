# SGOD IPCRF Performance Management Module

The module is available to authenticated MIS users at `Ipcrf`. A navigation entry is included in every existing sidebar role.

## Installation

1. Import `sql/create_ipcrf_module.sql` into the existing `depedmis_hris` database. The application also creates missing module tables on first authenticated use.
2. Ensure `upload/ipcrf_evidence` is writable by the Apache/PHP process. For the local XAMPP installation, the directory has already been created and made writable.
3. Open the module once while signed in. The first visit seeds the master **SGOD IPCRF 2025 Preset** transcribed from `ipcr/IPCRF_2025.pdf`.

The preset contains 5 KRAs, 15 objectives totaling exactly 100%, complete 1–5 Quality/Efficiency/Timeliness indicators, and 9 competencies. Loading it copies the data into the employee IPCRF; later edits never change the master.

New Draft records automatically receive an editable copy of the active preset. The timeline year is adjusted to the selected performance period. Existing empty Drafts with no preset are initialized the next time an authorized editor opens them.

The editor uses the full available content width. The former Section Navigator and permanent Weight Summary columns have been removed; weight, validation checks, ratings, and workflow history are available from the **Weight & Validation** toolbar modal.

KRA and objective editing uses short, labeled dialogs instead of unlabeled inline controls. Creating a KRA immediately guides the user to its first objective. Each KRA and objective has explicit Edit, Add, Copy, Move, and Delete actions, long performance-standard panels stay collapsed until opened, and the page includes a four-step quick guide, editing legend, autosave cue, and detailed **Editing Guide** modal.

## Workflow permissions

- **Draft / Returned for Revision:** the employee, original encoder, or system administrator can edit the complete form.
- **Submitted to Rater:** the assigned rater can edit objective Q/E/T ratings and competency rater ratings, then approve or return the form.
- **Rater Approved:** the assigned rater submits the form to PMT.
- **Submitted to PMT:** PMT users can assign final competency ratings and validate the form.
- **PMT Validated:** PMT users can lock the record.
- **Locked:** the form is read-only. Only a system administrator can reopen it, and a reason is required.

`Chief - SGOD`, `System Administrator`, and `Super Admin` accounts are treated as PMT users. System and super administrators can reopen records.

## Report

Preview and Print PDF open a six-page, landscape, DepEd-style print view:

1. Pages 1–4: employee/rater details, KRAs, objectives, standards, accomplishments and Q/E/T ratings
2. Page 5: competencies and employee/rater/final ratings
3. Page 6: rating summary, development plan, workflow record and signature blocks

The Print PDF action opens the browser print dialog so the report can be printed or saved directly as PDF without a server-side PDF dependency.
