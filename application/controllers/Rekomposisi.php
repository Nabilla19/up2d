<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rekomposisi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Rekomposisi_model', 'rekom');
        $this->load->helper(['form', 'url']);
        $this->load->library(['form_validation', 'session', 'pagination']);
    }

    // Halaman list rekomposisi
    public function index()
    {
        // Ambil per_page dari query string, default 5
        $per_page = $this->input->get('per_page') ? (int)$this->input->get('per_page') : 5; // ✅ PERBAIKAN

        // Ambil offset dari query string 'page'. CI pagination mengirim offset langsung
        $offset = $this->input->get('page') ? (int)$this->input->get('page') : 0; // ✅ PERBAIKAN

        // Keyword pencarian
        $keyword = $this->input->get('keyword', true);

        // Hitung total rows
        $total_rows = $keyword ? count($this->rekom->get_all_rekomposisi($keyword))
            : $this->db->count_all('rekomposisi');

        // Konfigurasi pagination
        $config['base_url'] = base_url('rekomposisi');
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
        $rekomposisi = $this->rekom->get_rekomposisi_paginated($per_page, $offset, $keyword); // ✅ PERBAIKAN

        // Nomor urut tabel
        $start_no = $offset + 1; // ✅ PERBAIKAN

        $data = [
            'rekomposisi' => $rekomposisi,
            'total_rows'  => $total_rows,
            'per_page'    => $per_page,
            'start_no'    => $start_no,
            'pagination'  => $this->pagination->create_links(),
            'keyword'     => $keyword,
            'page_title'  => 'Data Rekomposisi',
            'page_icon'   => 'fas fa-random me-2'
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('rekomposisi/vw_rekomposisi', $data);
        $this->load->view('layout/footer', $data);
    }

    // Form tambah
    public function tambah()
    {
        if (!can_create()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menambah data');
            redirect('rekomposisi');
        }

        $data = [
            'page_title' => 'Tambah Rekomposisi',
            'page_icon'  => 'fas fa-random me-2'
        ];

        $this->_set_rules();

        if ($this->form_validation->run() === false) {
            $this->load->view('layout/header', $data);
            $this->load->view('rekomposisi/vw_tambah_rekomposisi', $data);
            $this->load->view('layout/footer', $data);
            return;
        }

        $insert_data = [
            'JENIS_ANGGARAN' => $this->input->post('JENIS_ANGGARAN', true),
            'NOMOR_PRK'      => $this->input->post('NOMOR_PRK', true),
            'NOMOR_SKK_IO'   => $this->input->post('NOMOR_SKK_IO', true),
            'PRK'            => $this->input->post('PRK', true),
            'SKKI_O'         => $this->input->post('SKKI_O', true),
            'REKOMPOSISI'    => $this->input->post('REKOMPOSISI', true),
            'JUDUL_DRP'      => $this->input->post('JUDUL_DRP', true)
        ];

        $this->rekom->insert_rekomposisi($insert_data);
        $this->session->set_flashdata('success', 'Data rekomposisi berhasil ditambahkan!');
        redirect('rekomposisi');
    }

    // Form edit
    public function edit($id)
    {
        if (!can_edit()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengubah data');
            redirect('rekomposisi');
        }

        $rekom = $this->rekom->get_rekomposisi_by_id($id);
        if (!$rekom) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan!');
            redirect('rekomposisi');
        }

        $data = [
            'rekomposisi' => $rekom,
            'page_title'  => 'Edit Rekomposisi',
            'page_icon'   => 'fas fa-random me-2'
        ];

        $this->_set_rules();

        if ($this->form_validation->run() === false) {
            $this->load->view('layout/header', $data);
            $this->load->view('rekomposisi/vw_edit_rekomposisi', $data);
            $this->load->view('layout/footer', $data);
            return;
        }

        $update_data = [
            'JENIS_ANGGARAN' => $this->input->post('JENIS_ANGGARAN', true),
            'NOMOR_PRK'      => $this->input->post('NOMOR_PRK', true),
            'NOMOR_SKK_IO'   => $this->input->post('NOMOR_SKK_IO', true),
            'PRK'            => $this->input->post('PRK', true),
            'SKKI_O'         => $this->input->post('SKKI_O', true),
            'REKOMPOSISI'    => $this->input->post('REKOMPOSISI', true),
            'JUDUL_DRP'      => $this->input->post('JUDUL_DRP', true)
        ];

        $this->rekom->update_rekomposisi($id, $update_data);
        $this->session->set_flashdata('success', 'Data rekomposisi berhasil diperbarui!');
        redirect('rekomposisi');
    }

    // Hapus
    public function hapus($id)
    {
        if (!can_delete()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menghapus data');
            redirect('rekomposisi');
        }

        $rekom = $this->rekom->get_rekomposisi_by_id($id);
        if (!$rekom) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan!');
            redirect('rekomposisi');
        }

        $this->rekom->delete_rekomposisi($id);
        $this->session->set_flashdata('success', 'Data rekomposisi berhasil dihapus!');
        redirect('rekomposisi');
    }

    // Rules form validation
    private function _set_rules()
    {
        $this->form_validation->set_rules('JENIS_ANGGARAN', 'Jenis Anggaran', 'required');
        $this->form_validation->set_rules('NOMOR_PRK', 'Nomor PRK', 'required');
        $this->form_validation->set_rules('NOMOR_SKK_IO', 'Nomor SKK IO', 'required');
    }

    // Export CSV
    public function export_csv()
    {
        $this->load->dbutil();
        $this->load->helper(['file', 'download']);

        $query = $this->db->query("SELECT * FROM rekomposisi");
        $csv = $this->dbutil->csv_from_result($query);

        force_download('rekomposisi.csv', $csv);
    }

    // Detail rekomposisi
    public function detail($id)
    {
        $rekom = $this->rekom->get_rekomposisi_by_id($id);

        if (!$rekom) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan!');
            redirect('rekomposisi');
        }

        $data = [
            'rekomposisi' => $rekom,
            'page_title'  => 'Detail Rekomposisi',
            'page_icon'   => 'fas fa-random me-2'
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('rekomposisi/vw_detail_rekomposisi', $data);
        $this->load->view('layout/footer', $data);
    }
}
