<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rekomposisi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Rekomposisi_model', 'rekom');

        // ✅ TAMBAHAN: Notifikasi model untuk log aktivitas
        $this->load->model('Notifikasi_model', 'notifModel');

        $this->load->library(['pagination', 'form_validation', 'session']);
        $this->load->helper(['url', 'form', 'authorization_helper']); // <-- TAMBAHAN: pastikan helper ini terload
    }

    // ✅ TAMBAHAN: LOG AKTIVITAS
    private function _log($jenis, $module = null, $record_id = null, $record_name = null)
    {
        $user_id = (int) $this->session->userdata('user_id');
        if (!$user_id) return;

        $email = (string) ($this->session->userdata('email') ?? '');
        $role  = (string) ($this->session->userdata('user_role') ?? $this->session->userdata('role') ?? '');

        $this->notifModel->log_aktivitas(
            $user_id,
            $email,
            $role,
            $jenis,
            $module,
            $record_id,
            $record_name,
            $this->input->ip_address(),
            (string) $this->input->user_agent()
        );
    }

    public function index()
    {
        // --- PERSISTENCE LOGIC ---
        
        // Keyword
        if ($this->input->get('keyword') !== null) {
            $keyword = trim($this->input->get('keyword', true));
            $this->session->set_userdata('rekom_keyword', $keyword);
        } else {
            $keyword = $this->session->userdata('rekom_keyword') ?? '';
        }

        // Per Page
        if ($this->input->get('per_page') !== null) {
            $per_page = (int)$this->input->get('per_page');
            $this->session->set_userdata('rekom_per_page', $per_page);
        } else {
            $per_page = (int)($this->session->userdata('rekom_per_page') ?? 5);
        }
        if ($per_page <= 0) $per_page = 5;
        // Keep session updated
        $this->session->set_userdata('rekom_per_page', $per_page);

        // offset (bukan nomor halaman, melainkan record index start)
        // Controller ini menggunakan logic ?page=OFFSET (CodeIgniter standard query string pagination)
        $page_offset = (int)($this->input->get('page') ?? 0);
        if ($page_offset < 0) $page_offset = 0;

        $total_rows = $this->rekom->count_all($keyword);

        $config['base_url'] = base_url('rekomposisi');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string'] = TRUE;

        // Bootstrap pagination
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
            'rekomposisi' => $this->rekom->get_paginated($per_page, $page_offset, $keyword),
            'pagination'  => $this->pagination->create_links(),
            'total_rows'  => $total_rows,
            'per_page'    => $per_page,
            'start_no'    => $page_offset + 1,
            'page_title' => 'Rekomposisi',
            'page_icon'  => 'fas fa-layer-group',
            'parent_page_title' => 'Anggaran',
            'parent_page_url' => '#',
            'keyword'     => $keyword // Pass to view
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('rekomposisi/vw_rekomposisi', $data);
        $this->load->view('layout/footer');
    }

    public function tambah()
    {
        require_rekomposisi_create(); // <-- TAMBAHAN: kunci role admin & perencanaan

        $this->_rules();

        if ($this->form_validation->run() === false) {
            $data['page_title'] = 'Tambah Rekomposisi';
            $data['page_icon'] = 'fas fa-plus-circle';
            $data['parent_page_title'] = 'Anggaran';
            $data['parent_page_url'] = '#';
            $this->load->view('layout/header', $data);
            $this->load->view('rekomposisi/vw_tambah_rekomposisi');
            $this->load->view('layout/footer');
            return;
        }

        $pagu = $this->_to_number($this->input->post('SKKI_O', true));

        $data = [
            'jenis_anggaran' => $this->input->post('JENIS_ANGGARAN', true),
            'nomor_prk'      => $this->input->post('NOMOR_PRK', true),
            'nomor_skk_io'   => $this->input->post('NOMOR_SKK_IO', true),
            'uraian_prk'     => $this->input->post('PRK', true),
            'pagu_skk_io'    => $pagu,
            'judul_drp'      => $this->input->post('JUDUL_DRP', true),
            'lkao_usulan'    => $this->input->post('LKAO_USULAN', true),
            'created_at'     => date('Y-m-d H:i:s')
        ];

        if ($this->rekom->exists_prk_drp($data['nomor_prk'], $data['judul_drp'])) {
            $this->session->set_flashdata('error', 'Judul DRP sudah ada untuk PRK ini.');
            redirect('rekomposisi/tambah');
        }

        $this->rekom->insert($data);

        // ✅ TAMBAHAN: log create
        $this->_log('create', 'rekomposisi', null, $data['judul_drp'] ?? null);

        $this->session->set_flashdata('success', 'Data rekomposisi berhasil ditambahkan.');
        redirect('rekomposisi');
    }

    public function edit($id)
    {
        require_rekomposisi_edit(); // <-- TAMBAHAN: kunci role admin & perencanaan

        $row = $this->rekom->get_by_id($id);
        if (!$row) show_404();

        $this->_rules();

        if ($this->form_validation->run() === false) {
            $data['rekomposisi'] = $row;
            $data['page_title'] = 'Edit Rekomposisi';
            $data['page_icon'] = 'fas fa-edit';
            $data['parent_page_title'] = 'Anggaran';
            $data['parent_page_url'] = '#';
            $this->load->view('layout/header', $data);
            $this->load->view('rekomposisi/vw_edit_rekomposisi', $data);
            $this->load->view('layout/footer');
            return;
        }

        $pagu = $this->_to_number($this->input->post('SKKI_O', true));

        $update = [
            'jenis_anggaran' => $this->input->post('JENIS_ANGGARAN', true),
            'nomor_prk'      => $this->input->post('NOMOR_PRK', true),
            'nomor_skk_io'   => $this->input->post('NOMOR_SKK_IO', true),
            'uraian_prk'     => $this->input->post('PRK', true),
            'pagu_skk_io'    => $pagu,
            'judul_drp'      => $this->input->post('JUDUL_DRP', true),
            'lkao_usulan'    => $this->input->post('LKAO_USULAN', true),
            'updated_at'     => date('Y-m-d H:i:s')
        ];

        // jika sudah dipakai entry_kontrak -> kunci nomor_prk & judul_drp
        if ($this->rekom->is_used_in_kontrak($row['nomor_prk'], $row['judul_drp'])) {
            unset($update['nomor_prk'], $update['judul_drp']);
        } else {
            // validasi unik saat boleh edit nomor_prk & judul_drp
            if ($this->rekom->exists_prk_drp($update['nomor_prk'], $update['judul_drp'], $id)) {
                $this->session->set_flashdata('error', 'Judul DRP sudah digunakan untuk PRK tersebut.');
                redirect('rekomposisi/edit/' . $id);
            }
        }

        $this->rekom->update($id, $update);

        // ✅ TAMBAHAN: log update
        $this->_log('update', 'rekomposisi', $id, $row['judul_drp'] ?? null);

        $this->session->set_flashdata('success', 'Data berhasil diperbarui.');
        redirect('rekomposisi');
    }

    public function hapus($id)
    {
        require_rekomposisi_delete(); // <-- TAMBAHAN: kunci role admin & perencanaan

        $row = $this->rekom->get_by_id($id);
        if (!$row) show_404();

        if ($this->rekom->is_used_in_kontrak($row['nomor_prk'], $row['judul_drp'])) {
            $this->session->set_flashdata('error', 'Data tidak bisa dihapus karena sudah dipakai kontrak.');
            redirect('rekomposisi');
        }

        $this->rekom->delete($id);

        // ✅ TAMBAHAN: log delete
        $this->_log('delete', 'rekomposisi', $id, $row['judul_drp'] ?? null);

        $this->session->set_flashdata('success', 'Data berhasil dihapus.');
        redirect('rekomposisi');
    }

    private function _rules()
    {
        $this->form_validation->set_rules('JENIS_ANGGARAN', 'Jenis Anggaran', 'required|trim');
        $this->form_validation->set_rules('NOMOR_PRK', 'Nomor PRK', 'required|trim');
        $this->form_validation->set_rules('NOMOR_SKK_IO', 'Nomor SKK IO', 'required|trim');
        // jangan numeric karena input bisa pakai titik
        $this->form_validation->set_rules('SKKI_O', 'Pagu SKK IO', 'required|trim');
        $this->form_validation->set_rules('JUDUL_DRP', 'Judul DRP', 'required|trim');
        $this->form_validation->set_rules('PRK', 'PRK', 'required|trim');
    }

    private function _to_number($str)
    {
        if ($str === null) return 0;
        $num = preg_replace('/[^\d]/', '', (string)$str);
        return $num === '' ? 0 : (float)$num;
    }
}
