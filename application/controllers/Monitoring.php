<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        $this->load->model('Monitoring_model', 'monitor');
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'pagination']);
    }

    /* =========================
       ROLE HELPERS (SAMAKAN DENGAN Entry_kontrak)
       ========================= */

    private function _get_role_raw()
    {
        $r = $this->session->userdata('user_role');
        if (!$r) $r = $this->session->userdata('role');
        return strtolower(trim((string)$r));
    }

    // label yang tersimpan di kolom user_pengusul (HAR/FASOP/OPDIST/K3L&KAM/...)
    private function _role_label($role_raw)
    {
        $r = strtolower(trim((string)$role_raw));

        if ($r === 'pemeliharaan' || $r === 'har') return 'HAR';
        if ($r === 'fasilitas operasi' || $r === 'fasop') return 'FASOP';
        if ($r === 'operasi sistem distribusi' || $r === 'opdist') return 'OPDIST';
        if ($r === 'k3l & kam' || $r === 'k3l&kam') return 'K3L&KAM';

        // pengadaan & pengadaan keuangan = 1 role
        if ($r === 'pengadaan keuangan' || $r === 'pengadaan') return 'PENGADAAN KEUANGAN';

        if ($r === 'perencanaan') return 'PERENCANAAN';
        if ($r === 'kku') return 'KKU';
        if ($r === 'admin' || $r === 'administrator') return 'ADMIN';

        return strtoupper($role_raw);
    }

    private function _is_admin($role_raw)
    {
        $r = strtolower(trim((string)$role_raw));
        return ($r === 'admin' || $r === 'administrator');
    }

    // 4 role originator yang harus tampil hanya data miliknya sendiri
    private function _is_originator_filtered($role_raw)
    {
        $r = strtolower(trim((string)$role_raw));
        return in_array($r, [
            'pemeliharaan',
            'operasi sistem distribusi',
            'fasilitas operasi',
            'k3l & kam',
            'har',
            'opdist',
            'fasop',
            'k3l&kam',
        ], true);
    }

    /**
     * Role tertentu harus ditapis berdasarkan user_pengusul (label)
     * Role lain: full
     */
    private function _get_user_pengusul_filter()
    {
        $role_raw = $this->_get_role_raw();
        $role_label = $this->_role_label($role_raw);

        // Admin / Perencanaan / Pengadaan / KKU => FULL
        if ($this->_is_admin($role_raw)) return null;

        $r = strtolower(trim((string)$role_raw));
        if (in_array($r, ['perencanaan', 'pengadaan keuangan', 'pengadaan', 'kku'], true)) {
            return null;
        }

        // Originator (pemeliharaan/fasop/opdist/k3l&kam) => filter sesuai label yang disimpan
        if ($this->_is_originator_filtered($role_raw)) {
            return $role_label; // ini penting (HAR/FASOP/OPDIST/K3L&KAM)
        }

        // role lain => full (sesuai permintaan kamu)
        return null;
    }

    public function index()
    {
        $per_page = (int)($this->input->get('per_page') ?? 5);
        if ($per_page <= 0) $per_page = 5;

        $offset = (int)($this->input->get('page') ?? 0);
        if ($offset < 0) $offset = 0;

        $search = $this->input->get('search', true);

        // ✅ Ambil filter kolom: f[field]=value
        $column_filters = $this->input->get('f', true);
        if (!is_array($column_filters)) $column_filters = [];

        // ✅ filter sesuai role label
        $user_pengusul_filter = $this->_get_user_pengusul_filter();

        $total_rows = $this->monitor->count_all($search, $user_pengusul_filter, $column_filters);

        $config['base_url']             = base_url('monitoring');
        $config['total_rows']           = $total_rows;
        $config['per_page']             = $per_page;
        $config['page_query_string']    = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string']   = TRUE;

        // Bootstrap pagination
        $config['full_tag_open']  = '<nav><ul class="pagination pagination-sm mb-0">';
        $config['full_tag_close'] = '</ul></nav>';

        $config['first_link'] = '&laquo;';
        $config['last_link']  = '&raquo;';

        $config['first_tag_open']  = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open']   = '<li class="page-item">';
        $config['last_tag_close']  = '</li>';

        $config['cur_tag_open']  = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';

        $config['num_tag_open']  = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';

        $config['prev_link']      = '&lsaquo;';
        $config['prev_tag_open']  = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';

        $config['next_link']      = '&rsaquo;';
        $config['next_tag_open']  = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);

        $data = [
            'page_title'      => 'Monitoring',
            'parent_page_title' => 'Anggaran',
            'parent_page_url' => '#',
            'per_page'        => $per_page,
            'search'          => $search,
            'total_rows'      => $total_rows,
            'pagination'      => $this->pagination->create_links(),
            'start_no'        => $offset + 1,
            'column_filters'  => $column_filters, // ✅ agar view bisa isi ulang value filter
            'monitoring'      => $this->monitor->get_paginated($per_page, $offset, $search, $user_pengusul_filter, $column_filters),
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('monitoring/vw_monitoring', $data);
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

        $search = $this->input->get('search', true);

        // ✅ ambil filter kolom
        $column_filters = $this->input->get('f', true);
        if (!is_array($column_filters)) $column_filters = [];

        // ✅ filter juga agar export tidak bocor
        $user_pengusul_filter = $this->_get_user_pengusul_filter();

        $rows = $this->monitor->get_all($search, $user_pengusul_filter, $column_filters);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=monitoring.csv');

        $out = fopen('php://output', 'w');
        // BOM for Excel compatibility
        fwrite($out, "\xEF\xBB\xBF");

        if (empty($rows)) {
            fputcsv($out, ['NO DATA']);
            fclose($out);
            return;
        }

        fputcsv($out, array_keys($rows[0]));
        foreach ($rows as $r) {
            fputcsv($out, $r);
        }
        fclose($out);
    }

    public function detail($id)
    {
        // ✅ detail ikut filter juga
        $user_pengusul_filter = $this->_get_user_pengusul_filter();

        $row = $this->monitor->get_by_id($id, $user_pengusul_filter);
        if (!$row) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan / tidak punya akses');
            redirect('monitoring');
        }

        $data = [
            'page_title' => 'Detail Monitoring',
            'parent_page_title' => 'Anggaran',
            'parent_page_url' => '#',
            'row' => $row
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('monitoring/vw_detail_monitoring', $data);
        $this->load->view('layout/footer');
    }
}
