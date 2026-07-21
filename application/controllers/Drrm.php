<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Drrm extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url','form','file']);
        $this->load->library(['session','upload']);
    }

    public function index()
    {
        $base = FCPATH.'uploads/DRRM/';
        $thumbDir = $base.'thumbs/';
        if (!is_dir($base)) { @mkdir($base, 0775, true); }
        if (!is_dir($thumbDir)) { @mkdir($thumbDir, 0775, true); }

        $list = [];
        $files = glob($base.'*.pdf');
        foreach ($files as $path) {
            $name = basename($path);
            $url  = base_url('uploads/DRRM/'.$name);
            $thumbPath = $thumbDir.pathinfo($name, PATHINFO_FILENAME).'.png';
            $thumbUrl  = file_exists($thumbPath)
                ? base_url('uploads/DRRM/thumbs/'.basename($thumbPath))
                : base_url('assets/img/pdf-placeholder.png');

            $list[] = [
                'name'       => $name,
                'url'        => $url,
                'thumb'      => $thumbUrl,
                'size_human' => $this->human_filesize(filesize($path)),
                'date_human' => date('M d, Y', filemtime($path)),
                'delete_url' => site_url('Drrm/delete?f='.rawurlencode($name)),
            ];
        }

        $data['files'] = $list;
        $this->load->view('drrm_uploads', $data);
    }

    public function upload()
    {
        $base = FCPATH.'uploads/DRRM/';
        $thumbDir = $base.'thumbs/';
        if (!is_dir($base)) { @mkdir($base, 0775, true); }
        if (!is_dir($thumbDir)) { @mkdir($thumbDir, 0775, true); }

        $config = [
            'upload_path'   => $base,
            'allowed_types' => 'pdf',
            'max_size'      => 20480,
            'file_ext_tolower' => TRUE,
            'remove_spaces' => TRUE,
            'encrypt_name'  => TRUE,
        ];
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('pdf')) {
            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
            redirect('Drrm');
            return;
        }

        $up = $this->upload->data();
        $pdfPath = $up['full_path'];
        $thumbPath = $thumbDir.pathinfo($up['file_name'], PATHINFO_FILENAME).'.png';

        if (extension_loaded('imagick')) {
            try {
                $im = new Imagick();
                $im->setResolution(150, 150);
                $im->readImage($pdfPath.'[0]');
                $im->setImageFormat('png');
                $im->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
                $im->writeImage($thumbPath);
                $im->clear();
                $im->destroy();
            } catch (Exception $e) {}
        }

        $this->session->set_flashdata('msg', 'Upload successful.');
        redirect('Drrm');
    }

    public function delete()
    {
        $name = $this->input->get('f', true);
        if (!$name) { redirect('Drrm'); return; }

        $base = FCPATH.'uploads/DRRM/';
        $thumbDir = $base.'thumbs/';
        $pdf = realpath($base.$name);
        $thumb = realpath($thumbDir.pathinfo($name, PATHINFO_FILENAME).'.png');

        if ($pdf && strpos($pdf, realpath($base)) === 0 && is_file($pdf)) { @unlink($pdf); }
        if ($thumb && strpos($thumb, realpath($thumbDir)) === 0 && is_file($thumb)) { @unlink($thumb); }

        $this->session->set_flashdata('msg', 'File deleted.');
        redirect('Drrm');
    }

    private function human_filesize($bytes, $decimals = 1)
    {
        $size = ['B','KB','MB','GB','TB'];
        $factor = floor((strlen((string)$bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)).$size[$factor];
    }
}
