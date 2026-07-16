<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipcrf extends CI_Controller
{
    private $employeeId = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'upload'));
        $this->load->model('SGODModel');
        $this->load->model('Ipcrf_model');
        if ($this->session->userdata('logged_in') !== TRUE) {
            redirect('login');
        }
        $this->Ipcrf_model->ensure_schema();
        $this->employeeId = $this->Ipcrf_model->resolve_employee_id($this->actor());
    }

    public function index($id = 0)
    {
        $id = $id ? (int) $id : (int) $this->input->get('id');
        $actor = $this->actor();
        $viewerId = $this->employeeId;
        $bundle = NULL;
        $scope = 'none';
        if ($id > 0) {
            $form = $this->Ipcrf_model->get_form($id);
            if (!$form || !$this->Ipcrf_model->can_view($form, $viewerId)) {
                show_error('You are not authorized to view this IPCRF.', 403);
                return;
            }
            $bundle = $this->Ipcrf_model->get_bundle($id);
            $scope = $this->Ipcrf_model->edit_scope($form, $viewerId);
            // Empty first-time records receive their own editable copy of the active preset.
            // Checking template_id prevents an intentionally cleared, previously initialized form
            // from being repopulated on every page load.
            if (
                $scope === 'full' &&
                empty($form['template_id']) &&
                empty($bundle['kras']) &&
                empty($bundle['competencies'])
            ) {
                $defaultTemplate = $this->Ipcrf_model->get_default_template();
                if ($defaultTemplate) {
                    $this->Ipcrf_model->load_template_into_form($id, $defaultTemplate['id'], $actor);
                    $bundle = $this->Ipcrf_model->get_bundle($id);
                    $form = $bundle['form'];
                }
            }
        }

        $data = array(
            'bundle' => $bundle,
            'edit_scope' => $scope,
            'review_warnings' => $bundle ? $this->review_warnings($bundle, $scope) : array(),
            'templates' => $this->Ipcrf_model->get_templates(),
            'recent_forms' => $this->Ipcrf_model->personal_forms($viewerId),
            'current_employee' => $this->Ipcrf_model->get_employee($viewerId),
            'actor' => $viewerId,
            'is_admin' => $this->Ipcrf_model->is_admin(),
            'is_pmt' => $this->Ipcrf_model->is_pmt()
        );
        $this->load->view('ipcrf/editor', $data);
    }

    public function rater_queue()
    {
        $employee = $this->Ipcrf_model->get_employee($this->employeeId);
        $forms = $employee ? $this->Ipcrf_model->submitted_rater_forms($this->employeeId) : array();
        $this->load->view('ipcrf/rater_queue', array(
            'current_employee' => $employee,
            'forms' => $forms
        ));
    }

    public function signature()
    {
        $employee = $this->Ipcrf_model->get_employee($this->employeeId);
        if (isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
            if (!$employee) {
                $this->session->set_flashdata('signature_error', 'Your account must be linked to an HRIS employee profile before you can upload a signature.');
                redirect('Ipcrf/signature');
                return;
            }

            $uploadPath = $this->signature_storage_path();
            if (!is_dir($uploadPath) && !@mkdir($uploadPath, 0775, TRUE)) {
                $this->session->set_flashdata('signature_error', 'Signature storage is not writable. Please contact the system administrator.');
                redirect('Ipcrf/signature');
                return;
            }

            $this->upload->initialize(array(
                'upload_path' => $uploadPath,
                'allowed_types' => 'png|jpg|jpeg',
                'max_size' => 2048,
                'max_width' => 3000,
                'max_height' => 2000,
                'encrypt_name' => TRUE,
                'remove_spaces' => TRUE,
                'detect_mime' => TRUE,
                'mod_mime_fix' => TRUE
            ));
            if (!$this->upload->do_upload('signature')) {
                $this->session->set_flashdata('signature_error', trim(strip_tags($this->upload->display_errors('', ''))));
                redirect('Ipcrf/signature');
                return;
            }

            $file = $this->upload->data();
            $imageType = strtolower((string) $file['image_type']);
            if (empty($file['is_image']) || !in_array($imageType, array('png', 'jpeg', 'jpg'), TRUE)) {
                @unlink($uploadPath . basename($file['file_name']));
                $this->session->set_flashdata('signature_error', 'The selected file is not a valid PNG or JPEG image.');
                redirect('Ipcrf/signature');
                return;
            }

            $existing = $this->Ipcrf_model->get_signature($this->employeeId);
            $now = date('Y-m-d H:i:s');
            $saved = $this->Ipcrf_model->save_signature($this->employeeId, array(
                'original_name' => $file['orig_name'],
                'stored_name' => $file['file_name'],
                'mime_type' => $imageType === 'png' ? 'image/png' : 'image/jpeg',
                'file_size' => (int) round($file['file_size'] * 1024),
                'uploaded_by' => $this->actor(),
                'uploaded_at' => $now,
                'updated_at' => $now
            ));
            if (!$saved) {
                @unlink($uploadPath . basename($file['file_name']));
                $this->session->set_flashdata('signature_error', 'The signature could not be saved. Please try again.');
                redirect('Ipcrf/signature');
                return;
            }

            if ($existing && $existing['stored_name'] !== $file['file_name']) {
                $oldPath = $uploadPath . basename($existing['stored_name']);
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $this->session->set_flashdata('signature_success', 'Your signature has been saved and will appear on your IPCRF reports.');
            redirect('Ipcrf/signature');
            return;
        }

        $this->load->view('ipcrf/signature', array(
            'current_employee' => $employee,
            'signature' => $employee ? $this->Ipcrf_model->get_signature($this->employeeId) : NULL,
            'success_message' => $this->session->flashdata('signature_success'),
            'error_message' => $this->session->flashdata('signature_error')
        ));
    }

    public function remove_signature()
    {
        if (!isset($_SERVER['REQUEST_METHOD']) || strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
            show_error('Method not allowed.', 405);
            return;
        }
        $signature = $this->Ipcrf_model->get_signature($this->employeeId);
        if (!$signature) {
            $this->session->set_flashdata('signature_error', 'No saved signature was found.');
            redirect('Ipcrf/signature');
            return;
        }
        if (!$this->Ipcrf_model->delete_signature($this->employeeId)) {
            $this->session->set_flashdata('signature_error', 'The signature could not be removed. Please try again.');
            redirect('Ipcrf/signature');
            return;
        }

        $path = $this->signature_storage_path() . basename($signature['stored_name']);
        if (is_file($path)) {
            @unlink($path);
        }
        $this->session->set_flashdata('signature_success', 'Your saved signature has been removed.');
        redirect('Ipcrf/signature');
    }

    public function signature_image($employeeId = '')
    {
        $employeeId = trim(rawurldecode((string) $employeeId));
        if (!$this->Ipcrf_model->can_view_signature($employeeId, $this->employeeId)) {
            show_error('You are not authorized to view this signature.', 403);
            return;
        }
        $signature = $this->Ipcrf_model->get_signature($employeeId);
        if (!$signature) {
            show_404();
            return;
        }
        $path = $this->signature_storage_path() . basename($signature['stored_name']);
        if (!is_file($path)) {
            show_404();
            return;
        }

        $mimeType = in_array($signature['mime_type'], array('image/png', 'image/jpeg'), TRUE)
            ? $signature['mime_type']
            : 'application/octet-stream';
        $contents = file_get_contents($path);
        $this->output
            ->set_content_type($mimeType)
            ->set_header('Content-Length: ' . strlen($contents))
            ->set_header('Cache-Control: private, max-age=300')
            ->set_output($contents);
    }

    public function employee_search()
    {
        $query = trim((string) $this->input->get('q', TRUE));
        $rows = $this->Ipcrf_model->search_employees($query, 30);
        $results = array();
        foreach ($rows as $row) {
            $employee = $this->Ipcrf_model->format_employee($row);
            $results[] = array(
                'id' => $employee['id'],
                'text' => $employee['name'] . ' · ' . $employee['id'],
                'employee' => $employee
            );
        }
        $this->json(array('results' => $results));
    }

    public function employee($id = '')
    {
        $employee = $this->Ipcrf_model->get_employee($id);
        if (!$employee) {
            $this->json(array('success' => FALSE, 'message' => 'Employee was not found in HRIS.'), 404);
            return;
        }
        $this->json(array('success' => TRUE, 'employee' => $employee));
    }

    public function create()
    {
        $employee = $this->Ipcrf_model->get_employee($this->employeeId);
        if (!$employee) {
            $this->json(array('success' => FALSE, 'message' => 'Your signed-in account is not linked to an active HRIS employee profile.'), 422);
            return;
        }
        $periodStart = $this->Ipcrf_model->valid_date($this->input->post('period_start', TRUE));
        $periodEnd = $this->Ipcrf_model->valid_date($this->input->post('period_end', TRUE));
        if (!$periodStart || !$periodEnd || $periodStart > $periodEnd) {
            $this->json(array('success' => FALSE, 'message' => 'Enter a valid performance period.'), 422);
            return;
        }

        $raterId = trim((string) $this->input->post('rater_id', TRUE));
        $rater = $raterId !== '' ? $this->Ipcrf_model->get_employee($raterId) : NULL;
        if (!$rater) {
            $this->json(array('success' => FALSE, 'message' => 'Select a valid assigned rater from HRIS.'), 422);
            return;
        }
        $data = array(
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'rater_id' => $rater['id'],
            'rater_name' => $rater['name'],
            'rater_position' => $rater['position']
        );
        $id = $this->Ipcrf_model->create_form($employee, $data, $this->actor());
        $openedForm = $this->Ipcrf_model->get_form($id);
        if (!$openedForm || !$this->Ipcrf_model->can_view($openedForm, $this->employeeId)) {
            $this->json(array('success' => FALSE, 'message' => 'Your IPCRF could not be opened.'), 403);
            return;
        }
        if (empty($openedForm['template_id']) && $this->Ipcrf_model->edit_scope($openedForm, $this->employeeId) === 'full') {
            $defaultTemplate = $this->Ipcrf_model->get_default_template();
            if ($defaultTemplate) {
                $this->Ipcrf_model->load_template_into_form($id, $defaultTemplate['id'], $this->actor());
            }
        }
        $this->json(array('success' => TRUE, 'id' => $id, 'url' => site_url('Ipcrf/index/' . $id)));
    }

    public function bundle($id)
    {
        $form = $this->authorized_form($id);
        if (!$form) {
            return;
        }
        $this->json(array(
            'success' => TRUE,
            'bundle' => $this->Ipcrf_model->get_bundle($id),
            'edit_scope' => $this->Ipcrf_model->edit_scope($form, $this->employeeId)
        ));
    }

    public function load_preset($id)
    {
        $form = $this->authorized_form($id);
        if (!$form) {
            return;
        }
        if ($this->Ipcrf_model->edit_scope($form, $this->employeeId) !== 'full') {
            $this->json(array('success' => FALSE, 'message' => 'This IPCRF is read-only at its current workflow stage.'), 403);
            return;
        }
        $templateId = (int) $this->input->post('template_id');
        if (!$templateId || !$this->Ipcrf_model->load_template_into_form($id, $templateId, $this->actor())) {
            $this->json(array('success' => FALSE, 'message' => 'The selected preset could not be loaded.'), 422);
            return;
        }
        $bundle = $this->Ipcrf_model->get_bundle($id);
        $this->json(array(
            'success' => TRUE,
            'message' => 'Preset copied into the employee IPCRF.',
            'bundle' => $bundle,
            'review_warnings' => $this->review_warnings($bundle, 'full')
        ));
    }

    public function save($id)
    {
        $form = $this->authorized_form($id);
        if (!$form) {
            return;
        }
        $scope = $this->Ipcrf_model->edit_scope($form, $this->employeeId);
        if ($scope === 'none') {
            $this->json(array('success' => FALSE, 'message' => 'This IPCRF is read-only at its current workflow stage.'), 403);
            return;
        }
        $payload = $this->json_input();
        if (!is_array($payload)) {
            $this->json(array('success' => FALSE, 'message' => 'Invalid form payload.'), 400);
            return;
        }
        if ($scope === 'full') {
            $header = isset($payload['form']) && is_array($payload['form']) ? $payload['form'] : array();
            $periodStart = $this->Ipcrf_model->valid_date(isset($header['period_start']) ? $header['period_start'] : '');
            $periodEnd = $this->Ipcrf_model->valid_date(isset($header['period_end']) ? $header['period_end'] : '');
            if (!$periodStart || !$periodEnd || $periodStart > $periodEnd) {
                $this->json(array('success' => FALSE, 'message' => 'Enter a valid performance period before saving.'), 422);
                return;
            }
            $duplicate = $this->Ipcrf_model->find_period_form($form['employee_id'], $periodStart, $periodEnd);
            if ($duplicate && (int) $duplicate['id'] !== (int) $id) {
                $this->json(array('success' => FALSE, 'message' => 'Another IPCRF already uses this employee and performance period.'), 422);
                return;
            }
        }
        if (!$this->Ipcrf_model->save_bundle($id, $payload, $this->actor(), $scope)) {
            $this->json(array('success' => FALSE, 'message' => 'The draft could not be saved.'), 500);
            return;
        }
        $savedBundle = $this->Ipcrf_model->get_bundle($id);
        $this->json(array(
            'success' => TRUE,
            'message' => 'Changes saved.',
            'bundle' => $savedBundle,
            'review_warnings' => $this->review_warnings($savedBundle, $scope),
            'saved_at' => date('g:i A')
        ));
    }

    public function validate_form($id)
    {
        $form = $this->authorized_form($id);
        if (!$form) {
            return;
        }
        if (
            $form['status'] !== Ipcrf_model::STATUS_SUBMITTED_RATER ||
            $form['rater_id'] !== $this->employeeId
        ) {
            $this->json(array('success' => FALSE, 'message' => 'Validation is available only to the assigned rater during rater review.'), 403);
            return;
        }
        $bundle = $this->Ipcrf_model->get_bundle($id);
        $errors = $this->Ipcrf_model->validation_errors($bundle, 'current');
        $this->json(array(
            'success' => empty($errors),
            'message' => empty($errors) ? 'The rater review is complete and ready for approval.' : 'Rater validation found items that need attention.',
            'errors' => $errors,
            'summary' => $this->summary($bundle)
        ), empty($errors) ? 200 : 422);
    }

    public function workflow($id)
    {
        $form = $this->authorized_form($id);
        if (!$form) {
            return;
        }
        $action = trim((string) $this->input->post('action', TRUE));
        $remarks = trim((string) $this->input->post('remarks', TRUE));
        $warningsConfirmed = (string) $this->input->post('confirm_warnings', TRUE) === '1';
        $actor = $this->actor();
        $actorId = $this->employeeId;
        $allowed = FALSE;
        $target = '';
        $validationStage = '';

        if ($action === 'submit_rater' && in_array($form['status'], array(Ipcrf_model::STATUS_DRAFT, Ipcrf_model::STATUS_RETURNED), TRUE) && $this->Ipcrf_model->edit_scope($form, $actorId) === 'full') {
            $allowed = TRUE; $target = Ipcrf_model::STATUS_SUBMITTED_RATER; $validationStage = 'submit_rater';
        } elseif ($action === 'return_revision' && $form['status'] === Ipcrf_model::STATUS_SUBMITTED_RATER && ($form['rater_id'] === $actorId || $this->Ipcrf_model->is_admin())) {
            if ($remarks === '') {
                $this->json(array('success' => FALSE, 'message' => 'Revision remarks are required.'), 422); return;
            }
            $allowed = TRUE; $target = Ipcrf_model::STATUS_RETURNED;
        } elseif ($action === 'rater_approve' && $form['status'] === Ipcrf_model::STATUS_SUBMITTED_RATER && ($form['rater_id'] === $actorId || $this->Ipcrf_model->is_admin())) {
            $allowed = TRUE; $target = Ipcrf_model::STATUS_RATER_APPROVED; $validationStage = 'rater_approve';
        } elseif ($action === 'submit_pmt' && $form['status'] === Ipcrf_model::STATUS_RATER_APPROVED && ($form['rater_id'] === $actorId || $this->Ipcrf_model->is_admin())) {
            $allowed = TRUE; $target = Ipcrf_model::STATUS_SUBMITTED_PMT; $validationStage = 'rater_approve';
        } elseif ($action === 'pmt_validate' && $form['status'] === Ipcrf_model::STATUS_SUBMITTED_PMT && $this->Ipcrf_model->is_pmt()) {
            $allowed = TRUE; $target = Ipcrf_model::STATUS_PMT_VALIDATED; $validationStage = 'pmt_validate';
        } elseif ($action === 'lock' && $form['status'] === Ipcrf_model::STATUS_PMT_VALIDATED && $this->Ipcrf_model->is_pmt()) {
            $allowed = TRUE; $target = Ipcrf_model::STATUS_LOCKED; $validationStage = 'lock';
        } elseif ($action === 'reopen' && $this->Ipcrf_model->is_admin() && !in_array($form['status'], array(Ipcrf_model::STATUS_DRAFT, Ipcrf_model::STATUS_RETURNED), TRUE)) {
            if ($remarks === '') {
                $this->json(array('success' => FALSE, 'message' => 'A reason is required when reopening an approved record.'), 422); return;
            }
            $allowed = TRUE; $target = Ipcrf_model::STATUS_RETURNED;
        }

        if (!$allowed) {
            $this->json(array('success' => FALSE, 'message' => 'That workflow action is not allowed for your role or the current status.'), 403);
            return;
        }
        if ($validationStage !== '') {
            $errors = $this->Ipcrf_model->validation_errors($this->Ipcrf_model->get_bundle($id), $validationStage);
            if ($errors) {
                if (in_array($action, array('submit_rater', 'rater_approve'), TRUE)) {
                    if (!$warningsConfirmed) {
                        $this->json(array(
                            'success' => FALSE,
                            'warning_only' => TRUE,
                            'message' => $action === 'rater_approve'
                                ? 'Some review information is incomplete. Review the warnings, return the form, or approve anyway.'
                                : 'Some IPCRF information is incomplete. Review the warnings or submit anyway.',
                            'errors' => $errors
                        ), 409);
                        return;
                    }
                    $remarks = ($action === 'rater_approve' ? 'Rater approved' : 'Submitted') . ' after acknowledging ' . count($errors) . ' incomplete item warning(s).';
                } else {
                    $this->json(array('success' => FALSE, 'message' => 'Complete the required fields before continuing.', 'errors' => $errors), 422);
                    return;
                }
            }
        }
        $this->Ipcrf_model->workflow_transition($id, $target, $remarks, $actor);
        $this->json(array('success' => TRUE, 'message' => 'Status changed to ' . $target . '.', 'status' => $target, 'reload' => site_url('Ipcrf/index/' . (int) $id)));
    }

    public function upload_evidence($id)
    {
        $form = $this->authorized_form($id);
        if (!$form) {
            return;
        }
        if ($this->Ipcrf_model->edit_scope($form, $this->employeeId) !== 'full') {
            $this->json(array('success' => FALSE, 'message' => 'Evidence can only be changed while the IPCRF is editable.'), 403);
            return;
        }
        $objectiveId = (int) $this->input->post('objective_id');
        if (!$this->Ipcrf_model->objective_belongs($objectiveId, $id)) {
            $this->json(array('success' => FALSE, 'message' => 'Save the objective before uploading evidence.'), 422);
            return;
        }
        $uploadPath = FCPATH . 'upload/ipcrf_evidence/';
        if (!is_dir($uploadPath) && !@mkdir($uploadPath, 0775, TRUE)) {
            $this->json(array('success' => FALSE, 'message' => 'Evidence storage is not writable.'), 500);
            return;
        }
        $this->upload->initialize(array(
            'upload_path' => $uploadPath,
            'allowed_types' => 'pdf|jpg|jpeg|png|doc|docx|xls|xlsx',
            'max_size' => 15360,
            'encrypt_name' => TRUE,
            'remove_spaces' => TRUE
        ));
        if (!$this->upload->do_upload('evidence')) {
            $this->json(array('success' => FALSE, 'message' => trim(strip_tags($this->upload->display_errors('', '')))), 422);
            return;
        }
        $file = $this->upload->data();
        $evidenceId = $this->Ipcrf_model->add_evidence(array(
            'form_id' => (int) $id, 'objective_id' => $objectiveId,
            'original_name' => $file['orig_name'], 'stored_name' => $file['file_name'],
            'mime_type' => $file['file_type'], 'file_size' => (int) round($file['file_size'] * 1024),
            'uploaded_by' => $this->actor(), 'uploaded_at' => date('Y-m-d H:i:s')
        ));
        $evidence = $this->Ipcrf_model->get_evidence($evidenceId);
        $evidence['url'] = site_url('Ipcrf/download_evidence/' . (int) $id . '/' . $evidenceId);
        $this->json(array('success' => TRUE, 'message' => 'Evidence uploaded.', 'evidence' => $evidence));
    }

    public function delete_evidence($id, $evidenceId)
    {
        $form = $this->authorized_form($id);
        if (!$form) {
            return;
        }
        if ($this->Ipcrf_model->edit_scope($form, $this->employeeId) !== 'full') {
            $this->json(array('success' => FALSE, 'message' => 'Evidence can only be changed while the IPCRF is editable.'), 403);
            return;
        }
        $evidence = $this->Ipcrf_model->get_evidence($evidenceId);
        if (!$evidence || (int) $evidence['form_id'] !== (int) $id) {
            $this->json(array('success' => FALSE, 'message' => 'Evidence was not found.'), 404);
            return;
        }
        $path = FCPATH . 'upload/ipcrf_evidence/' . $evidence['stored_name'];
        if (is_file($path)) {
            @unlink($path);
        }
        $this->Ipcrf_model->delete_evidence($evidenceId);
        $this->json(array('success' => TRUE, 'message' => 'Evidence removed.'));
    }

    public function download_evidence($id, $evidenceId)
    {
        if (!$this->authorized_form($id, FALSE)) {
            return;
        }
        $evidence = $this->Ipcrf_model->get_evidence($evidenceId);
        if (!$evidence || (int) $evidence['form_id'] !== (int) $id) {
            show_404();
            return;
        }
        $path = FCPATH . 'upload/ipcrf_evidence/' . $evidence['stored_name'];
        if (!is_file($path)) {
            show_404();
            return;
        }
        $this->load->helper('download');
        force_download($evidence['original_name'], file_get_contents($path), TRUE);
    }

    public function report($id)
    {
        $form = $this->authorized_form($id, FALSE);
        if (!$form) {
            return;
        }
        $bundle = $this->Ipcrf_model->get_bundle($id);
        $this->load->view('ipcrf/report', array(
            'bundle' => $bundle,
            'summary' => $this->summary($bundle),
            'autoprint' => $this->input->get('autoprint') === '1',
            'employee_signature' => $this->signature_data_uri($bundle['form']['employee_id']),
            'rater_signature' => $this->signature_data_uri($bundle['form']['rater_id'])
        ));
    }

    private function signature_storage_path()
    {
        return APPPATH . 'uploads/ipcrf_signatures/';
    }

    private function signature_data_uri($employeeId)
    {
        $signature = $this->Ipcrf_model->get_signature($employeeId);
        if (!$signature || !in_array($signature['mime_type'], array('image/png', 'image/jpeg'), TRUE)) {
            return '';
        }
        $path = $this->signature_storage_path() . basename($signature['stored_name']);
        if (!is_file($path)) {
            return '';
        }
        return 'data:' . $signature['mime_type'] . ';base64,' . base64_encode(file_get_contents($path));
    }

    private function summary($bundle)
    {
        $weight = 0;
        $score = 0;
        $ratedWeight = 0;
        $ratingComplete = 0;
        $approvedStatuses = array(
            Ipcrf_model::STATUS_RATER_APPROVED,
            Ipcrf_model::STATUS_SUBMITTED_PMT,
            Ipcrf_model::STATUS_PMT_VALIDATED,
            Ipcrf_model::STATUS_LOCKED
        );
        $ratingsApproved = in_array($bundle['form']['status'], $approvedStatuses, TRUE);
        foreach ((array) $bundle['kras'] as $kra) {
            foreach ((array) $kra['objectives'] as $objective) {
                $objectiveWeight = (float) $objective['weight'];
                $weight += $objectiveWeight;
                $q = (float) $objective['quality_rating'];
                $e = (float) $objective['efficiency_rating'];
                $t = (float) $objective['timeliness_rating'];
                if ($q > 0 && $e > 0 && $t > 0) {
                    $ratingComplete++;
                    $score += (($q + $e + $t) / 3) * ($objectiveWeight / 100);
                    $ratedWeight += $objectiveWeight;
                }
            }
        }
        $adjectival = 'Not yet rated';
        if ($score >= 4.5) $adjectival = 'Outstanding';
        elseif ($score >= 3.5) $adjectival = 'Very Satisfactory';
        elseif ($score >= 2.5) $adjectival = 'Satisfactory';
        elseif ($score >= 1.5) $adjectival = 'Unsatisfactory';
        elseif ($score >= 1) $adjectival = 'Poor';
        return array('weight' => round($weight, 2), 'weighted_score' => round($score, 3), 'rated_weight' => round($ratedWeight, 2), 'adjectival' => $adjectival, 'ratings_approved' => $ratingsApproved, 'rating_complete' => $ratingComplete);
    }

    private function authorized_form($id, $json = TRUE)
    {
        $form = $this->Ipcrf_model->get_form((int) $id);
        if (!$form) {
            if ($json) $this->json(array('success' => FALSE, 'message' => 'IPCRF was not found.'), 404);
            else show_404();
            return FALSE;
        }
        if (!$this->Ipcrf_model->can_view($form, $this->employeeId)) {
            if ($json) $this->json(array('success' => FALSE, 'message' => 'You are not authorized to access this IPCRF.'), 403);
            else show_error('You are not authorized to access this IPCRF.', 403);
            return FALSE;
        }
        return $form;
    }

    private function review_warnings($bundle, $scope)
    {
        if (!$bundle || empty($bundle['form']) || !in_array($scope, array('full', 'rater'), TRUE)) {
            return array();
        }
        return $this->Ipcrf_model->validation_errors($bundle, $scope === 'rater' ? 'rater_approve' : 'submit_rater');
    }

    private function actor()
    {
        return trim((string) $this->session->userdata('username'));
    }

    private function json_input()
    {
        $decoded = json_decode((string) $this->input->raw_input_stream, TRUE);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : NULL;
    }

    private function json($payload, $status = 200)
    {
        $this->output->set_status_header($status)->set_content_type('application/json', 'utf-8')->set_output(json_encode($payload));
    }
}
