<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Monitoring_model', 'monitor');
        $this->load->helper(['form', 'url']);
        $this->load->library(['form_validation', 'session', 'pagination']);
    }

    // ============================================================
    // HALAMAN LIST
    // ============================================================
    public function index()
    {
        // Pagination
        $per_page = $this->input->get('per_page') ? (int)$this->input->get('per_page') : 5;
        $page     = $this->input->get('page') ? (int)$this->input->get('page') : 0;
        $keyword  = $this->input->get('keyword', true);

        // Hitung total data
        $total_rows = $this->monitor->count_monitoring($keyword);

        // Konfigurasi pagination
        $config['base_url'] = base_url('monitoring');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string'] = TRUE;

        $this->pagination->initialize($config);

        // Ambil data
        $monitoring = $this->monitor->get_monitoring_paginated($per_page, $page, $keyword);

        // KIRIM ke VIEW
        $data = [
            'monitoring' => $monitoring,
            'total_rows' => $total_rows,
            'pagination' => $this->pagination->create_links(),
            'keyword'    => $keyword,
            'per_page'   => $per_page,   // <= FIX ERROR
            'page_title' => 'Data Monitoring',
            'page_icon'  => 'fas fa-chart-line me-2"'
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('monitoring/vw_monitoring', $data);
        $this->load->view('layout/footer', $data);
    }


    // ============================================================
    // FORM TAMBAH
    // ============================================================
    public function tambah()
    {
        $this->_set_rules();

        if ($this->form_validation->run() === false) {
            $data['page_title'] = 'Tambah Monitoring';
            $this->load->view('layout/header', $data);
            $this->load->view('monitoring/vw_tambah_monitoring', $data);
            $this->load->view('layout/footer');
            return;
        }

        // Field utama
        $insert = [
            'NOMOR_PRK'        => $this->input->post('NOMOR_PRK', true),
            'NOMOR_SKK_IO'     => $this->input->post('NOMOR_SKK_IO', true),
            'NAMA_PEKERJAAN'   => $this->input->post('NAMA_PEKERJAAN', true),
            'NO_KONTRAK'       => $this->input->post('NO_KONTRAK', true),
            'VENDOR'           => $this->input->post('VENDOR', true),
            'TGL_KONTRAK'      => $this->input->post('TGL_KONTRAK', true),
            'NILAI_KONTRAK'    => $this->input->post('NILAI_KONTRAK', true),
            'KETERANGAN'       => $this->input->post('KETERANGAN', true),
        ];

        $this->monitor->insert_monitoring($insert);
        $this->session->set_flashdata('success', 'Data berhasil ditambahkan');
        redirect('monitoring');
    }

    // ============================================================
    // EDIT
    // ============================================================
    public function edit($id)
    {
        $monitor = $this->monitor->get_monitoring_by_id($id);
        if (!$monitor) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan');
            redirect('monitoring');
        }

        $this->_set_rules();

        if ($this->form_validation->run() === false) {
            $data['monitoring'] = $monitor;
            $data['page_title'] = 'Edit Monitoring';
            $this->load->view('layout/header', $data);
            $this->load->view('monitoring/vw_edit_monitoring', $data);
            $this->load->view('layout/footer');
            return;
        }

        $update = [
            'NOMOR_PRK'        => $this->input->post('NOMOR_PRK', true),
            'NOMOR_SKK_IO'     => $this->input->post('NOMOR_SKK_IO', true),
            'NAMA_PEKERJAAN'   => $this->input->post('NAMA_PEKERJAAN', true),
            'NO_KONTRAK'       => $this->input->post('NO_KONTRAK', true),
            'VENDOR'           => $this->input->post('VENDOR', true),
            'TGL_KONTRAK'      => $this->input->post('TGL_KONTRAK', true),
            'NILAI_KONTRAK'    => $this->input->post('NILAI_KONTRAK', true),
            'KETERANGAN'       => $this->input->post('KETERANGAN', true),
        ];

        $this->monitor->update_monitoring($id, $update);
        $this->session->set_flashdata('success', 'Data berhasil diupdate');
        redirect('monitoring');
    }

    // ============================================================
    // HAPUS
    // ============================================================
    public function hapus($id)
    {
        $monitor = $this->monitor->get_monitoring_by_id($id);
        if (!$monitor) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan');
            redirect('monitoring');
        }

        $this->monitor->delete_monitoring($id);
        $this->session->set_flashdata('success', 'Data berhasil dihapus');
        redirect('monitoring');
    }

    // ============================================================
    // RULES
    // ============================================================
    private function _set_rules()
    {
        $this->form_validation->set_rules('NOMOR_PRK', 'Nomor PRK', 'required');
        $this->form_validation->set_rules('NAMA_PEKERJAAN', 'Nama Pekerjaan', 'required');
        $this->form_validation->set_rules('VENDOR', 'Vendor', 'required');
    }

    // ============================================================
    // DETAIL
    // ============================================================
    public function detail($id)
    {
        $monitor = $this->monitor->get_monitoring_by_id($id);
        if (!$monitor) {
            $this->session->set_flashdata('error', 'Tidak ditemukan');
            redirect('monitoring');
        }

        $data['monitoring'] = $monitor;
        $data['page_title'] = 'Detail Monitoring';

        $this->load->view('layout/header', $data);
        $this->load->view('monitoring/vw_detail_monitoring', $data);
        $this->load->view('layout/footer');
    }
}
