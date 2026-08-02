<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Section extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index($id = 0)
    {
        $id = (int) $id;
        if ($id < 1) {
            show_404();
            return;
        }

        $section = $this->db->select('id, sectionName')
            ->where('id', $id)
            ->where('secGroup', 'SGOD')
            ->get('one_sgod_sections')
            ->row();
        if (!$section) {
            show_404();
            return;
        }

        $data['section'] = $section;
        $data['sgodSections'] = $this->db->select('id, sectionName')
            ->where('secGroup', 'SGOD')
            ->where('sectionName !=', 'Chief - SGOD')
            ->order_by('sectionName', 'ASC')
            ->get('one_sgod_sections')
            ->result();
        $this->load->view('public_section', $data);
    }
}
