<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prognosa extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        $this->load->model('Prognosa_model', 'prognosa');
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'pagination']);
    }

    public function index()
    {
        $per_page = (int)($this->input->get('per_page') ?? 5);
        if ($per_page <= 0) $per_page = 5;

        $offset  = (int)($this->input->get('page') ?? 0);
        if ($offset < 0) $offset = 0;

        $jenis   = (string)($this->input->get('jenis', true) ?? '');
        $rekap   = (string)($this->input->get('rekap', true) ?? '');
        $search = (string)($this->input->get('search', true) ?? '');

        // optional sort (untuk hindari error trim(null))
        $sort_by  = (string)($this->input->get('sort_by', true) ?? '');
        $sort_dir = (string)($this->input->get('sort_dir', true) ?? '');

        $total_rows = $this->prognosa->count_all($jenis, $rekap, $search);

        $config['base_url'] = base_url('prognosa');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string'] = TRUE;

        // pagination bootstrap kecil
        $config['full_tag_open']  = '<nav><ul class="pagination pagination-sm mb-0">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['cur_tag_open']   = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']  = '</span></li>';
        $config['num_tag_open']   = '<li class="page-item">';
        $config['num_tag_close']  = '</li>';
        $config['attributes']     = ['class' => 'page-link'];

        $config['prev_link']      = '&laquo;';
        $config['next_link']      = '&raquo;';
        $config['prev_tag_open']  = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open']  = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $rows = $this->prognosa->get_paginated(
            $per_page,
            $offset,
            $jenis,
            $rekap,
            $search,
            $sort_by,
            $sort_dir
        );
        
        // DEBUG: Check what we got
        if (ENVIRONMENT === 'development') {
            error_log("Prognosa DEBUG - Rows count: " . count($rows));
            error_log("Prognosa DEBUG - First row: " . print_r($rows[0] ?? 'EMPTY', true));
        }

        $data = [
            'page_title'  => 'Prognosa',
            'parent_page_title' => 'Anggaran',
            'parent_page_url' => '#',
            'page_icon'   => 'fas fa-chart-pie me-2',

            'prognosa'    => $rows,  // Changed from 'rows' to 'prognosa'
            'total_rows'  => $total_rows,
            'pagination'  => $this->pagination->create_links(),
            'per_page'    => $per_page,
            'start_no'    => $offset + 1,

            'jenis_list'  => $this->prognosa->get_jenis_list(),
            'rekap_list'  => $this->prognosa->get_rekap_list($jenis),

            'filter_jenis'   => $jenis,
            'filter_rekap'   => $rekap,
            'search' => $search,
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('prognosa/vw_prognosa', $data);
        $this->load->view('layout/footer');
    }

    // =========================
    // DETAIL (berdasarkan jenis_anggaran + rekap)
    // URL: /prognosa/detail?jenis=...&rekap=...
    // =========================
    public function detail()
    {
        $jenis = (string)($this->input->get('jenis', true) ?? '');
        $rekap = (string)($this->input->get('rekap', true) ?? '');

        if ($jenis === '' || $rekap === '') {
            $this->session->set_flashdata('error', 'Parameter detail tidak lengkap.');
            redirect('prognosa');
        }

        $row = $this->prognosa->get_one($jenis, $rekap);
        if (!$row) {
            $this->session->set_flashdata('error', 'Data prognosa tidak ditemukan.');
            redirect('prognosa');
        }

        $data = [
            'page_title' => 'Detail Prognosa',
            'parent_page_title' => 'Anggaran',
            'parent_page_url' => '#',
            'page_icon'  => 'fas fa-chart-pie me-2',
            'row'        => $row
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('prognosa/vw_detail_prognosa', $data);
        $this->load->view('layout/footer');
    }
}
