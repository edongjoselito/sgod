<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Research extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // NOTE: This controller itself does NOT require login.
        // If your system has a global login guard (hook/MY_Controller),
        // you must whitelist:
        // Research/public_form, Research/public_store, Research/public_success, Research/report

        $this->load->model('ResearchModel');
        $this->load->model('SGODModel');
        $this->load->helper(['url','form']);
        $this->load->library(['session','form_validation','upload']);
    }

    /* =========================
       PUBLIC FORM (standalone)
       URL: /Research/public_form
       ========================= */
    public function public_form()
    {
        $this->load->view('researchForm_public', [
            'mode' => 'public'
        ]);
    }

    /* =========================
       PUBLIC SAVE
       URL: /Research/public_store
       ========================= */
    public function public_store()
    {
        $this->_rules_public();

        if ($this->form_validation->run() === FALSE) {
            return $this->load->view('researchForm_public', [
                'mode' => 'public'
            ]);
        }

        $request_date = trim((string)$this->input->post('request_date', true));
        if ($request_date === '') $request_date = date('Y-m-d');

        $author_name = trim((string)$this->input->post('author_name', true));
        if ($author_name === '') $author_name = 'PUBLIC';

        $now = date('Y-m-d H:i:s');

        $this->db->trans_start();

        // ✅ STRICT MONTHLY SERIES: YYYY-MM-0001
        $control_no = $this->ResearchModel->generate_control_no_monthly($request_date);

        // PUBLIC FLOW: store author as FREE TEXT in main_author_id
        $payload = [
            'control_no'          => $control_no,
            'request_date'        => $request_date,
            'research_title'      => $this->input->post('research_title', true),

            'author_mode'         => 'single',
            'main_author_id'      => $author_name,
            'created_by'          => 'PUBLIC',

            'researcher_type'     => $this->input->post('researcher_type', true),
            'hei_name'            => $this->input->post('hei_name', true),
            'hei_campus_location' => $this->input->post('hei_campus_location', true),

            // stored in DB, but your public view will NOT display it
            'status'              => 'Submitted',

            'created_at'          => $now,
            'updated_at'          => $now
        ];

        $this->ResearchModel->insert($payload);
        $request_id = (int)$this->db->insert_id();

        // upload + save file rows
        $files = $this->_upload_many('attachments');
        $this->ResearchModel->add_files($request_id, $files);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return $this->load->view('researchForm_public', [
                'mode'   => 'public',
                'danger' => 'Failed to submit. Please try again.'
            ]);
        }

        redirect('Research/public_success/' . $request_id);
    }

    /* =========================
       PUBLIC SUCCESS
       URL: /Research/public_success/{id}
       ========================= */
    public function public_success($id)
    {
        $id  = (int)$id;
        $row = $this->ResearchModel->find($id);
        if (!$row) show_404();

        $files = $this->ResearchModel->get_files($id);

        $this->load->view('researchForm_public', [
            'mode'  => 'public_success',
            'row'   => $row,
            'files' => $files
        ]);
    }

    /* =========================
       TEMP. PERMIT REPORT
       URL: /Research/report/{id}
       ========================= */
    public function report($id)
    {
        $id  = (int)$id;
        $row = $this->ResearchModel->find_report_row($id);
        if (!$row) show_404();

        $members = $this->ResearchModel->get_members($id);
        $files   = $this->ResearchModel->get_files($id);

        // If author is staff IDNumber, use staff name; else use free-text author
        $researcherNameLetter = '';
        $maybeId = trim((string)($row->main_author_id ?? ''));

        $staffRow = $this->ResearchModel->staff_by_id($maybeId);
        if ($staffRow) {
            $mi = !empty($staffRow->MiddleName) ? (substr((string)$staffRow->MiddleName, 0, 1) . '.') : '';
            $researcherNameLetter = trim($staffRow->FirstName . ' ' . $mi . ' ' . $staffRow->LastName);
            $researcherNameLetter = trim(preg_replace('/\s+/', ' ', $researcherNameLetter));
        } else {
            $researcherNameLetter = $maybeId;
        }

        $this->load->view('researchReport_letter', [
            'row'                  => $row,
            'members'              => $members,
            'files'                => $files,
            'researcherNameLetter' => $researcherNameLetter
        ]);
    }

    /* =========================
       TEMP PERMITS LIST (internal)
       URL: /Research/temp_permits
       ========================= */
    public function temp_permits()
    {
        $data = [];
        $data['title'] = 'Temporary Permits';
        $data['pn'] = 'Company';
        $data['temp_permit_count'] = $this->ResearchModel->total_requests();
        $data['research_requests'] = $this->ResearchModel->all_requests();

        $this->load->view('templates/head');
        $this->load->view('templates/header');
        $this->load->view('research_temp_permits', $data);
        $this->load->view('templates/footer');
    }

    /* =========================
       VALIDATION RULES (PUBLIC)
       ========================= */
    private function _rules_public()
    {
        $this->form_validation->set_rules('request_date', 'Request Date', 'required');
        $this->form_validation->set_rules('author_name', 'Author', 'required');
        $this->form_validation->set_rules('research_title', 'Research Title', 'required');
        $this->form_validation->set_rules('researcher_type', 'Researcher Type', 'required');
        $this->form_validation->set_rules('hei_name', 'School Name', 'required');
        $this->form_validation->set_rules('hei_campus_location', 'Campus Location', 'required');
    }

    /* =========================
       MULTI UPLOAD (images/pdf)
       uploads/research/
       ========================= */
    private function _upload_many($field = 'attachments')
    {
        $saved = [];

        if (!isset($_FILES[$field]) || empty($_FILES[$field]['name'][0])) {
            return $saved;
        }

        $dir = rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'research' . DIRECTORY_SEPARATOR;

        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        // if not writable, just skip file saving (do not break public submission)
        if (!is_writable($dir)) {
            return $saved;
        }

        $count = count($_FILES[$field]['name']);

        for ($i = 0; $i < $count; $i++) {

            if (empty($_FILES[$field]['name'][$i])) continue;

            $_FILES['single']['name']     = $_FILES[$field]['name'][$i];
            $_FILES['single']['type']     = $_FILES[$field]['type'][$i];
            $_FILES['single']['tmp_name'] = $_FILES[$field]['tmp_name'][$i];
            $_FILES['single']['error']    = $_FILES[$field]['error'][$i];
            $_FILES['single']['size']     = $_FILES[$field]['size'][$i];

            $config = [];
            $config['upload_path']   = $dir;
            $config['allowed_types'] = 'jpg|jpeg|png|pdf';
            $config['max_size']      = 10240; // 10MB
            $config['encrypt_name']  = true;

            $this->upload->initialize($config);

            if ($this->upload->do_upload('single')) {
                $up = $this->upload->data();

                $saved[] = [
                    'file_path'     => 'uploads/research/' . $up['file_name'],
                    'original_name' => $up['orig_name'],
                    'file_ext'      => $up['file_ext'],
                ];
            }
        }

        return $saved;
    }
}
