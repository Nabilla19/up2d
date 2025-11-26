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

    // ====================================================
    // LIST DATA + PAGINATION
    // ====================================================
    public function index()
    {
        $per_page = (int) ($this->input->get('per_page') ?? 5);
        $offset   = (int) ($this->input->get('page') ?? 0);
        $keyword  = $this->input->get('keyword', true);

        if ($keyword) {
            $total_rows = count($this->prk->get_all_prk($keyword));
        } else {
            $total_rows = $this->db->count_all('prk_data');
        }

        $config = [
            'base_url'             => base_url('rekap_prk'),
            'total_rows'           => $total_rows,
            'per_page'             => $per_page,
            'page_query_string'    => true,
            'query_string_segment' => 'page',
            'reuse_query_string'   => true,
            'full_tag_open'        => '<nav><ul class="pagination pagination-sm">',
            'full_tag_close'       => '</ul></nav>',
            'cur_tag_open'         => '<li class="page-item active"><span class="page-link">',
            'cur_tag_close'        => '</span></li>',
            'num_tag_open'         => '<li class="page-item"><span class="page-link">',
            'num_tag_close'        => '</span></li>',
        ];
        $this->pagination->initialize($config);

        $prk_data = $this->prk->get_prk_paginated($per_page, $offset, $keyword);

        $data = [
            'prk_data'  => $prk_data,
            'per_page'  => $per_page,
            'total_rows' => $total_rows,
            'pagination' => $this->pagination->create_links(),
            'keyword'   => $keyword,
            'start_no'  => $offset + 1,
            'page_title' => 'Data PRK',
            'page_icon' => 'fas fa-clipboard-list me-2'
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('rekap_prk/vw_rekap_prk', $data);
        $this->load->view('layout/footer');
    }

    // ====================================================
    // FORM TAMBAH DATA
    // ====================================================
    public function tambah()
    {
        if (!can_create()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses.');
            redirect('rekap_prk');
        }

        $this->_set_rules();

        $data = [
            'page_title' => 'Tambah PRK',
            'page_icon'  => 'fas fa-file-invoice-dollar',
            'prk_list'   => $this->prk->get_all_rekomposisi_prk() // ⬅️ PERBAIKAN UTAMA
        ];

        if ($this->form_validation->run() === false) {
            $this->load->view('layout/header', $data);
            $this->load->view('rekap_prk/vw_tambah_rekap_prk', $data);
            $this->load->view('layout/footer');
            return;
        }

        $insert_data = $this->_collect_prk_post();
        $this->prk->insert_prk($insert_data);

        $this->session->set_flashdata('success', 'Data PRK berhasil ditambahkan!');
        redirect('rekap_prk');
    }

    // ====================================================
    // FORM EDIT DATA
    // ====================================================
    public function edit($id)
    {
        if (!can_edit()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses.');
            redirect('rekap_prk');
        }

        $rekap = $this->prk->get_prk_by_id($id);

        if (!$rekap) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan!');
            redirect('rekap_prk');
        }

        $this->_set_rules();

        $data = [
            'rekap'     => $rekap,
            'page_title' => 'Edit PRK',
            'page_icon' => 'fas fa-file-invoice-dollar',
            'prk_list'  => $this->prk->get_all_rekomposisi_prk() // ⬅️ PERBAIKAN UNTUK EDIT
        ];

        if ($this->form_validation->run() === false) {
            $this->load->view('layout/header', $data);
            $this->load->view('rekap_prk/vw_edit_rekap_prk', $data);
            $this->load->view('layout/footer');
            return;
        }

        $update_data = $this->_collect_prk_post();
        $this->prk->update_prk($id, $update_data);

        $this->session->set_flashdata('success', 'Data PRK berhasil diperbarui!');
        redirect('rekap_prk');
    }

    // ====================================================
    // HAPUS DATA
    // ====================================================
    public function hapus($id)
    {
        if (!can_delete()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses.');
            redirect('rekap_prk');
        }

        $rekap = $this->prk->get_prk_by_id($id);

        if (!$rekap) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan!');
            redirect('rekap_prk');
        }

        $this->prk->delete_prk($id);
        $this->session->set_flashdata('success', 'Data berhasil dihapus!');
        redirect('rekap_prk');
    }

    // ====================================================
    // DETAIL DATA
    // ====================================================
    public function detail($id)
    {
        $rekap = $this->prk->get_prk_by_id($id);

        if (!$rekap) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan!');
            redirect('rekap_prk');
        }

        $data = [
            'rekap'     => $rekap,
            'page_title' => 'Detail PRK',
            'page_icon' => 'fas fa-file-invoice-dollar'
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('rekap_prk/vw_detail_rekap_prk', $data);
        $this->load->view('layout/footer');
    }

    // ====================================================
    // EXPORT CSV
    // ====================================================
    public function export_csv()
    {
        $this->load->dbutil();
        $this->load->helper(['file', 'download']);

        $query = $this->db->query("SELECT * FROM prk_data");
        $csv   = $this->dbutil->csv_from_result($query);

        force_download('rekap_prk.csv', $csv);
    }

    // ====================================================
    // RULES FORM
    // ====================================================
    private function _set_rules()
    {
        $this->form_validation->set_rules('JENIS_ANGGARAN', 'Jenis Anggaran', 'required');
        $this->form_validation->set_rules('NOMOR_PRK', 'Nomor PRK', 'required');
        $this->form_validation->set_rules('URAIAN_PRK', 'Uraian PRK', 'required');
    }

    // ====================================================
    // AMBIL POST DATA
    // ====================================================
    private function _collect_prk_post()
    {
        return [
            'JENIS_ANGGARAN' => $this->input->post('JENIS_ANGGARAN'),
            'NOMOR_PRK'      => $this->input->post('NOMOR_PRK'),
            'URAIAN_PRK'     => $this->input->post('URAIAN_PRK'),
            'PAGU_SKK_IO'    => $this->input->post('PAGU_SKK_IO'),
            'RENC_KONTRAK'   => $this->input->post('RENC_KONTRAK'),
            'NODIN_SRT'      => $this->input->post('NODIN_SRT'),
            'KONTRAK'        => $this->input->post('KONTRAK'),
            'SISA'           => $this->input->post('SISA'),
            'RENCANA_BAYAR'  => $this->input->post('RENCANA_BAYAR'),
            'TERBAYAR'       => $this->input->post('TERBAYAR'),
            'KE_TAHUN_2026'  => $this->input->post('KE_TAHUN_2026'),
        ];
    }


    public function get_prk()
    {
        $jenis = $this->input->post('jenis');

        $data = $this->prk->get_prk_by_jenis($jenis);

        echo json_encode($data);
    }

    public function get_uraian()
    {
        $nomor = $this->input->post('nomor_prk');
        $data = $this->prk->get_uraian_prk($nomor);

        echo json_encode($data);
    }
}
