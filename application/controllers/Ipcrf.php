<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipcrf extends CI_Controller
{
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
    }

    public function index($id = 0)
    {
        $id = $id ? (int) $id : (int) $this->input->get('id');
        $actor = $this->actor();
        $bundle = NULL;
        $scope = 'none';
        if ($id > 0) {
            $form = $this->Ipcrf_model->get_form($id);
            if (!$form || !$this->Ipcrf_model->can_view($form, $actor)) {
                show_error('You are not authorized to view this IPCRF.', 403);
                return;
            }
            $bundle = $this->Ipcrf_model->get_bundle($id);
            $scope = $this->Ipcrf_model->edit_scope($form, $actor);
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
            'templates' => $this->Ipcrf_model->get_templates(),
            'recent_forms' => $this->Ipcrf_model->recent_forms($actor, $this->Ipcrf_model->is_pmt()),
            'actor' => $actor,
            'is_admin' => $this->Ipcrf_model->is_admin(),
            'is_pmt' => $this->Ipcrf_model->is_pmt()
        );
        $this->load->view('ipcrf/editor', $data);
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
        $employeeId = trim((string) $this->input->post('employee_id', TRUE));
        $employee = $this->Ipcrf_model->get_employee($employeeId);
        if (!$employee) {
            $this->json(array('success' => FALSE, 'message' => 'Select a valid employee from HRIS.'), 422);
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
        $data = array(
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'rater_id' => $rater ? $rater['id'] : '',
            'rater_name' => $rater ? $rater['name'] : $employee['direct_head'],
            'rater_position' => $rater ? $rater['position'] : $employee['direct_head_position']
        );
        $id = $this->Ipcrf_model->create_form($employee, $data, $this->actor());
        $openedForm = $this->Ipcrf_model->get_form($id);
        if (!$openedForm || !$this->Ipcrf_model->can_view($openedForm, $this->actor())) {
            $this->json(array('success' => FALSE, 'message' => 'An IPCRF already exists for that employee and period, but you are not authorized to open it.'), 403);
            return;
        }
        if (empty($openedForm['template_id']) && $this->Ipcrf_model->edit_scope($openedForm, $this->actor()) === 'full') {
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
            'edit_scope' => $this->Ipcrf_model->edit_scope($form, $this->actor())
        ));
    }

    public function load_preset($id)
    {
        $form = $this->authorized_form($id);
        if (!$form) {
            return;
        }
        if ($this->Ipcrf_model->edit_scope($form, $this->actor()) !== 'full') {
            $this->json(array('success' => FALSE, 'message' => 'This IPCRF is read-only at its current workflow stage.'), 403);
            return;
        }
        $templateId = (int) $this->input->post('template_id');
        if (!$templateId || !$this->Ipcrf_model->load_template_into_form($id, $templateId, $this->actor())) {
            $this->json(array('success' => FALSE, 'message' => 'The selected preset could not be loaded.'), 422);
            return;
        }
        $this->json(array('success' => TRUE, 'message' => 'Preset copied into the employee IPCRF.', 'bundle' => $this->Ipcrf_model->get_bundle($id)));
    }

    public function save($id)
    {
        $form = $this->authorized_form($id);
        if (!$form) {
            return;
        }
        $scope = $this->Ipcrf_model->edit_scope($form, $this->actor());
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
        $this->json(array('success' => TRUE, 'message' => 'Changes saved.', 'bundle' => $this->Ipcrf_model->get_bundle($id), 'saved_at' => date('g:i A')));
    }

    public function validate_form($id)
    {
        if (!$this->authorized_form($id)) {
            return;
        }
        $bundle = $this->Ipcrf_model->get_bundle($id);
        $errors = $this->Ipcrf_model->validation_errors($bundle, 'current');
        $this->json(array(
            'success' => empty($errors),
            'message' => empty($errors) ? 'All requirements for the current workflow stage are complete.' : 'Validation found items that need attention.',
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
        $actor = $this->actor();
        $allowed = FALSE;
        $target = '';
        $validationStage = '';

        if ($action === 'submit_rater' && in_array($form['status'], array(Ipcrf_model::STATUS_DRAFT, Ipcrf_model::STATUS_RETURNED), TRUE) && $this->Ipcrf_model->edit_scope($form, $actor) === 'full') {
            $allowed = TRUE; $target = Ipcrf_model::STATUS_SUBMITTED_RATER; $validationStage = 'submit_rater';
        } elseif ($action === 'return_revision' && $form['status'] === Ipcrf_model::STATUS_SUBMITTED_RATER && ($form['rater_id'] === $actor || $this->Ipcrf_model->is_admin())) {
            if ($remarks === '') {
                $this->json(array('success' => FALSE, 'message' => 'Revision remarks are required.'), 422); return;
            }
            $allowed = TRUE; $target = Ipcrf_model::STATUS_RETURNED;
        } elseif ($action === 'rater_approve' && $form['status'] === Ipcrf_model::STATUS_SUBMITTED_RATER && ($form['rater_id'] === $actor || $this->Ipcrf_model->is_admin())) {
            $allowed = TRUE; $target = Ipcrf_model::STATUS_RATER_APPROVED; $validationStage = 'rater_approve';
        } elseif ($action === 'submit_pmt' && $form['status'] === Ipcrf_model::STATUS_RATER_APPROVED && ($form['rater_id'] === $actor || $this->Ipcrf_model->is_admin())) {
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
                $this->json(array('success' => FALSE, 'message' => 'Complete the required fields before continuing.', 'errors' => $errors), 422);
                return;
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
        if ($this->Ipcrf_model->edit_scope($form, $this->actor()) !== 'full') {
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
        if ($this->Ipcrf_model->edit_scope($form, $this->actor()) !== 'full') {
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
            'autoprint' => $this->input->get('autoprint') === '1'
        ));
    }

    private function summary($bundle)
    {
        $weight = 0;
        $score = 0;
        $ratedWeight = 0;
        foreach ((array) $bundle['kras'] as $kra) {
            foreach ((array) $kra['objectives'] as $objective) {
                $objectiveWeight = (float) $objective['weight'];
                $weight += $objectiveWeight;
                $q = (float) $objective['quality_rating'];
                $e = (float) $objective['efficiency_rating'];
                $t = (float) $objective['timeliness_rating'];
                if ($q > 0 && $e > 0 && $t > 0) {
                    $score += (($q + $e + $t) / 3) * ($objectiveWeight / 100);
                    $ratedWeight += $objectiveWeight;
                }
            }
        }
        $rating = $ratedWeight > 0 ? $score / ($ratedWeight / 100) : 0;
        $adjectival = 'Not yet rated';
        if ($rating >= 4.5) $adjectival = 'Outstanding';
        elseif ($rating >= 3.5) $adjectival = 'Very Satisfactory';
        elseif ($rating >= 2.5) $adjectival = 'Satisfactory';
        elseif ($rating >= 1.5) $adjectival = 'Unsatisfactory';
        elseif ($rating >= 1) $adjectival = 'Poor';
        return array('weight' => round($weight, 2), 'weighted_score' => round($score, 3), 'overall_rating' => round($rating, 3), 'rated_weight' => round($ratedWeight, 2), 'adjectival' => $adjectival);
    }

    private function authorized_form($id, $json = TRUE)
    {
        $form = $this->Ipcrf_model->get_form((int) $id);
        if (!$form) {
            if ($json) $this->json(array('success' => FALSE, 'message' => 'IPCRF was not found.'), 404);
            else show_404();
            return FALSE;
        }
        if (!$this->Ipcrf_model->can_view($form, $this->actor())) {
            if ($json) $this->json(array('success' => FALSE, 'message' => 'You are not authorized to access this IPCRF.'), 403);
            else show_error('You are not authorized to access this IPCRF.', 403);
            return FALSE;
        }
        return $form;
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
