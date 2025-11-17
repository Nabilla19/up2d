<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rekap_prk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Rekap_prk_model', 'prk');
        $this->load->helper(['form', 'url']);
        $this->load->library(['form_validation', 'session', 'pagination']);
    }

    // Halaman list PRK
    public function index()
    {
        // Ambil per_page dari query string, default 5
        $per_page = $this->input->get('per_page') ? (int)$this->input->get('per_page') : 5;

        // Ambil offset dari query string 'page'
        $offset = $this->input->get('page') ? (int)$this->input->get('page') : 0;

        // Keyword pencarian
        $keyword = $this->input->get('keyword', true);

        // Hitung total rows
        $total_rows = $keyword ? count($this->prk->get_all_prk($keyword))
            : $this->db->count_all('prk_data');

        // Konfigurasi pagination
        $config['base_url'] = base_url('rekap_prk');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string'] = TRUE;

        // Tag HTML pagination
        $config['full_tag_open'] = '<nav><ul class="pagination pagination-sm">';
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

        // Ambil data paginated
        $prk_data = $this->prk->get_prk_paginated($per_page, $offset, $keyword);

        // Nomor urut tabel
        $start_no = $offset + 1;

        $data = [
            'prk_data'   => $prk_data,
            'total_rows' => $total_rows,
            'per_page'   => $per_page,
            'start_no'   => $start_no,
            'pagination' => $this->pagination->create_links(),
            'keyword'    => $keyword,
            'page_title' => 'Data PRK',
            'page_icon'  => 'fas fa-file-invoice-dollar'
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('rekap_prk/vw_rekap_prk', $data);
        $this->load->view('layout/footer', $data);
    }

    // Form tambah
    public function tambah()
    {
        if (!can_create()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menambah data');
            redirect('rekap_prk');
        }

        $data = [
            'page_title' => 'Tambah PRK',
            'page_icon'  => 'fas fa-file-invoice-dollar'
        ];

        $this->_set_rules();

        if ($this->form_validation->run() === false) {
            $this->load->view('layout/header', $data);
            $this->load->view('rekap_prk/vw_tambah_prk', $data);
            $this->load->view('layout/footer', $data);
            return;
        }

        $insert_data = [
            'JENIS_ANGGARAN' => $this->input->post('JENIS_ANGGARAN', true),
            'NOMOR_PRK'      => $this->input->post('NOMOR_PRK', true),
            'URAIAN_PRK'     => $this->input->post('URAIAN_PRK', true),
            'PAGU_SKK_IO'    => $this->input->post('PAGU_SKK_IO', true),
            'RENC_KONTRAK'   => $this->input->post('RENC_KONTRAK', true),
            'NODIN_SRT'      => $this->input->post('NODIN_SRT', true),
            'KONTRAK'        => $this->input->post('KONTRAK', true),
            'SISA'           => $this->input->post('SISA', true),
            'RENCANA_BAYAR'  => $this->input->post('RENCANA_BAYAR', true),
            'TERBAYAR'       => $this->input->post('TERBAYAR', true),
            'KE_TAHUN_2026'  => $this->input->post('KE_TAHUN_2026', true)
        ];

        $this->prk->insert_prk($insert_data);
        $this->session->set_flashdata('success', 'Data PRK berhasil ditambahkan!');
        redirect('rekap_prk');
    }

    // Form edit
    public function edit($id)
    {
        if (!can_edit()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengubah data');
            redirect('rekap_prk');
        }

        $prk = $this->prk->get_prk_by_id($id);
        if (!$prk) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan!');
            redirect('rekap_prk');
        }

        $data = [
            'prk_data'   => $prk,
            'page_title' => 'Edit PRK',
            'page_icon'  => 'fas fa-file-invoice-dollar'
        ];

        $this->_set_rules();

        if ($this->form_validation->run() === false) {
            $this->load->view('layout/header', $data);
            $this->load->view('rekap_prk/vw_edit_prk', $data);
            $this->load->view('layout/footer', $data);
            return;
        }

        $update_data = [
            'JENIS_ANGGARAN' => $this->input->post('JENIS_ANGGARAN', true),
            'NOMOR_PRK'      => $this->input->post('NOMOR_PRK', true),
            'URAIAN_PRK'     => $this->input->post('URAIAN_PRK', true),
            'PAGU_SKK_IO'    => $this->input->post('PAGU_SKK_IO', true),
            'RENC_KONTRAK'   => $this->input->post('RENC_KONTRAK', true),
            'NODIN_SRT'      => $this->input->post('NODIN_SRT', true),
            'KONTRAK'        => $this->input->post('KONTRAK', true),
            'SISA'           => $this->input->post('SISA', true),
            'RENCANA_BAYAR'  => $this->input->post('RENCANA_BAYAR', true),
            'TERBAYAR'       => $this->input->post('TERBAYAR', true),
            'KE_TAHUN_2026'  => $this->input->post('KE_TAHUN_2026', true)
        ];

        $this->prk->update_prk($id, $update_data);
        $this->session->set_flashdata('success', 'Data PRK berhasil diperbarui!');
        redirect('rekap_prk');
    }

    // Hapus
    public function hapus($id)
    {
        if (!can_delete()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menghapus data');
            redirect('rekap_prk');
        }

        $prk = $this->prk->get_prk_by_id($id);
        if (!$prk) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan!');
            redirect('rekap_prk');
        }

        $this->prk->delete_prk($id);
        $this->session->set_flashdata('success', 'Data PRK berhasil dihapus!');
        redirect('rekap_prk');
    }

    // Rules form validation
    private function _set_rules()
    {
        $this->form_validation->set_rules('JENIS_ANGGARAN', 'Jenis Anggaran', 'required');
        $this->form_validation->set_rules('NOMOR_PRK', 'Nomor PRK', 'required');
        $this->form_validation->set_rules('URAIAN_PRK', 'Uraian PRK', 'required');
    }

    // Export CSV
    public function export_csv()
    {
        $this->load->dbutil();
        $this->load->helper(['file', 'download']);

        $query = $this->db->query("SELECT * FROM prk_data"); // ✅ GANTI TABEL
        $csv = $this->dbutil->csv_from_result($query);

        force_download('rekap_prk.csv', $csv);
    }

    // Detail PRK
    public function detail($id)
    {
        $prk = $this->prk->get_prk_by_id($id);

        if (!$prk) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan!');
            redirect('rekap_prk');
        }

        $data = [
            'prk_data'   => $prk,
            'page_title' => 'Detail PRK',
            'page_icon'  => 'fas fa-file-invoice-dollar'
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('rekap_prk/vw_detail_prk', $data);
        $this->load->view('layout/footer', $data);
    }
}
