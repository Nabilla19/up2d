<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rekap_prk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        $this->load->model('Rekap_prk_model', 'prk');
        $this->load->helper(['url']);
        $this->load->library(['session', 'pagination']);
    }

    public function index()
    {
        $per_page = (int)($this->input->get('per_page') ?? 5);
        if ($per_page <= 0) $per_page = 5;

        $offset  = (int)($this->input->get('page') ?? 0);
        if ($offset < 0) $offset = 0;

        $keyword = $this->input->get('keyword', true);
        $jenis   = $this->input->get('jenis_anggaran', true); // filter optional

        $total_rows = $this->prk->count_all($keyword, $jenis);

        $config['base_url'] = base_url('rekap_prk');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string'] = TRUE;

        // pagination bootstrap
        $config['full_tag_open'] = '<nav><ul class="pagination pagination-sm mb-0">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close'] = '</span></li>';
        $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['prev_tag_close'] = '</span></li>';
        $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['next_tag_close'] = '</span></li>';

        $this->pagination->initialize($config);

        $data = [
            'page_title' => 'Rekap PRK',
            'parent_page_title' => 'Anggaran',
            'parent_page_url' => '#',
            'page_icon'  => 'fas fa-clipboard-list me-2',
            'per_page'   => $per_page,
            'total_rows' => $total_rows,
            'keyword'    => $keyword,
            'jenis_anggaran' => $jenis,
            'jenis_list' => $this->prk->get_jenis_anggaran_list(),
            'pagination' => $this->pagination->create_links(),
            'start_no'   => $offset + 1,
            'prk_data'   => $this->prk->get_paginated($per_page, $offset, $keyword, $jenis),
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('rekap_prk/vw_rekap_prk', $data);
        $this->load->view('layout/footer');
    }

    /**
     * detail pakai parameter URL:
     * /rekap_prk/detail?jenis=OPERASI&nomor_prk=...&uraian_prk=...
     */
    public function detail()
    {
        $jenis = $this->input->get('jenis', true);
        $nomor = $this->input->get('nomor_prk', true);
        $uraian = $this->input->get('uraian_prk', true);

        if (!$jenis || !$nomor || !$uraian) {
            $this->session->set_flashdata('error', 'Parameter detail tidak lengkap.');
            redirect('rekap_prk');
        }

        $row = $this->prk->get_detail($jenis, $nomor, $uraian);
        if (!$row) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('rekap_prk');
        }

        $data = [
            'page_title' => 'Detail Rekap PRK',
            'parent_page_title' => 'Anggaran',
            'parent_page_url' => '#',
            'page_icon'  => 'fas fa-info-circle me-2',
            'row' => $row
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('rekap_prk/vw_detail_rekap_prk', $data);
        $this->load->view('layout/footer');
    }

    public function export_csv()
    {
        // Block guest users from exporting
        if (function_exists('is_guest') && is_guest()) {
            $this->session->set_flashdata('error', 'Akses ditolak. Silakan login untuk mengunduh data.');
            redirect(strtolower($this->router->fetch_class()));
            return;
        }

        $keyword = $this->input->get('keyword', true);
        $jenis   = $this->input->get('jenis_anggaran', true);

        $this->load->dbutil();
        $this->load->helper(['download']);

        $query = $this->prk->export_all($keyword, $jenis);
        $csv   = $this->dbutil->csv_from_result($query);
        // prepend BOM so Excel recognizes UTF-8
        $csv = "\xEF\xBB\xBF" . $csv;

        force_download('rekap_prk.csv', $csv);
    }

    // ❌ Tambah/Edit/Hapus dimatikan karena sumber data adalah VIEW hasil rumus ERD
    public function tambah()
    {
        show_error('Rekap PRK berasal dari VIEW (otomatis). Tambah manual dinonaktifkan.', 403);
    }
    public function edit()
    {
        show_error('Rekap PRK berasal dari VIEW (otomatis). Edit manual dinonaktifkan.', 403);
    }
    public function hapus()
    {
        show_error('Rekap PRK berasal dari VIEW (otomatis). Hapus manual dinonaktifkan.', 403);
    }
}
