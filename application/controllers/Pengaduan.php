<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller for Pengaduan (Complaint Management)
 *
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Pengaduan_model $Pengaduan_model
 * @property CI_Pagination $pagination
 * @property CI_Upload $upload
 * @property CI_Form_validation $form_validation
 * @property CI_URI $uri
 * @property CI_Config $config
 */
class Pengaduan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pengaduan_model');

        // ✅ TAMBAHAN: Notifikasi model untuk log aktivitas
        $this->load->model('Notifikasi_model', 'notifModel');

        $this->load->helper(['form', 'url']);
        $this->load->library(['form_validation', 'upload', 'session', 'pagination']);
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

    // 🔹 Halaman utama
    public function index()
    {
        $data['judul'] = 'Data Pengaduan';

        // Navbar data
        $data['page_title'] = 'Data Pengaduan';
        $data['page_icon'] = 'fas fa-file-alt';

        // Pagination setup
        $per_page = (int) $this->input->get('per_page') ?: 5;
        $page = (int) $this->input->get('page') ?: 0;
        $q = trim($this->input->get('q', TRUE) ?? '');

        $config = [
            'base_url'            => base_url('pengaduan/index'),
            'total_rows'          => $this->Pengaduan_model->count_all_pengaduan($q),
            'per_page'            => $per_page,
            'page_query_string'   => true,
            'query_string_segment' => 'page',
            'reuse_query_string'  => true,

            // Bootstrap 5 style
            'full_tag_open'   => '<nav><ul class="pagination justify-content-center">',
            'full_tag_close'  => '</ul></nav>',
            'first_link'      => '« First',
            'first_tag_open'  => '<li class="page-item">',
            'first_tag_close' => '</li>',
            'last_link'       => 'Last »',
            'last_tag_open'   => '<li class="page-item">',
            'last_tag_close'  => '</li>',
            'next_link'       => '›',
            'next_tag_open'   => '<li class="page-item">',
            'next_tag_close'  => '</li>',
            'prev_link'       => '‹',
            'prev_tag_open'   => '<li class="page-item">',
            'prev_tag_close'  => '</li>',
            'cur_tag_open'    => '<li class="page-item active"><a class="page-link" href="#">',
            'cur_tag_close'   => '</a></li>',
            'num_tag_open'    => '<li class="page-item">',
            'num_tag_close'   => '</li>',
            'attributes'      => ['class' => 'page-link']
        ];

        $this->pagination->initialize($config);

        $data['pengaduan'] = $this->Pengaduan_model->get_pengaduan_paginated($per_page, $page, $q);
        $data['start_no'] = $page + 1;
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];
        $data['per_page'] = $per_page;
        $data['q'] = $q;

        $this->load->view('layout/header', $data);
        $this->load->view('pengaduan/vw_pengaduan', $data);
        $this->load->view('layout/footer');
    }

    // 🔹 Tambah Data Pengaduan
    public function tambah()
    {
        if (!can_create()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menambah data');
            redirect($this->router->fetch_class());
        }

        $data['judul'] = 'Tambah Pengaduan';
        $this->_set_rules();

        if ($this->form_validation->run() === false) {
            $this->load->view('layout/header', $data);
            $this->load->view('pengaduan/vw_tambah_pengaduan', $data);
            $this->load->view('layout/footer');
            return;
        }

        // Upload foto
        $foto_pengaduan = $this->_upload_file('FOTO_PENGADUAN', './uploads/pengaduan/');
        $foto_proses    = $this->_upload_file('FOTO_PROSES', './uploads/proses/');

        // Default status "Lapor" bila kosong
        $status = $this->input->post('STATUS', true);
        if (empty($status)) {
            $status = 'Lapor';
        }

        $insert_data = [
            'NAMA_UP3'          => $this->input->post('NAMA_UP3', true),
            'TANGGAL_PENGADUAN' => $this->input->post('TANGGAL_PENGADUAN', true),
            'JENIS_PENGADUAN'   => $this->input->post('JENIS_PENGADUAN', true),
            'ITEM_PENGADUAN'    => $this->input->post('ITEM_PENGADUAN', true),
            'LAPORAN'           => $this->input->post('LAPORAN', true),
            'TINDAK_LANJUT'     => $this->input->post('TINDAK_LANJUT', true),
            'FOTO_PENGADUAN'    => $foto_pengaduan,
            'TANGGAL_PROSES'    => $this->input->post('TANGGAL_PROSES', true),
            'TANGGAL_SELESAI'   => $this->input->post('TANGGAL_SELESAI', true),
            'FOTO_PROSES'       => $foto_proses,
            'STATUS'            => $status,
            'PIC'               => $this->input->post('PIC', true),
            'CATATAN'           => $this->input->post('CATATAN', true),

            // ✅ KOLOM BARU
            'TITIK_KOORDINAT'   => $this->input->post('TITIK_KOORDINAT', true),
        ];

        $this->Pengaduan_model->insert_pengaduan($insert_data);

        // ✅ TAMBAHAN: log create
        $this->_log('create', 'pengaduan', null, $insert_data['ITEM_PENGADUAN'] ?? null);

        $this->session->set_flashdata('success', 'Data pengaduan berhasil ditambahkan!');
        redirect('pengaduan');
    }

    // 🔹 Detail Pengaduan
    public function detail($id)
    {
        $data['judul'] = 'Detail Pengaduan';
        $data['pengaduan'] = $this->Pengaduan_model->get_pengaduan_by_id($id);

        if (!$data['pengaduan']) {
            show_404();
        }

        $this->load->view('layout/header', $data);
        $this->load->view('pengaduan/vw_detail_pengaduan', $data);
        $this->load->view('layout/footer');
    }

    // 🔹 Edit Pengaduan
    public function edit($id)
    {
        if (!can_edit()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengubah data');
            redirect($this->router->fetch_class());
        }

        $data['judul'] = 'Edit Pengaduan';
        $data['pengaduan'] = $this->Pengaduan_model->get_pengaduan_by_id($id);

        if (!$data['pengaduan']) {
            show_404();
        }

        $this->_set_rules();

        if ($this->form_validation->run() === false) {
            $this->load->view('layout/header', $data);
            $this->load->view('pengaduan/vw_edit_pengaduan', $data);
            $this->load->view('layout/footer');
            return;
        }

        // Upload ulang foto jika ada
        $foto_pengaduan = $this->_upload_file('FOTO_PENGADUAN', './uploads/pengaduan/', $data['pengaduan']['FOTO_PENGADUAN']);
        $foto_proses    = $this->_upload_file('FOTO_PROSES', './uploads/proses/', $data['pengaduan']['FOTO_PROSES']);

        // Jika status kosong, tetap pertahankan atau set default "Lapor"
        $status = $this->input->post('STATUS', true);
        if (empty($status)) {
            $status = !empty($data['pengaduan']['STATUS']) ? $data['pengaduan']['STATUS'] : 'Lapor';
        }

        $update_data = [
            'NAMA_UP3'          => $this->input->post('NAMA_UP3', true),
            'TANGGAL_PENGADUAN' => $this->input->post('TANGGAL_PENGADUAN', true),
            'JENIS_PENGADUAN'   => $this->input->post('JENIS_PENGADUAN', true),
            'ITEM_PENGADUAN'    => $this->input->post('ITEM_PENGADUAN', true),
            'LAPORAN'           => $this->input->post('LAPORAN', true),
            'TINDAK_LANJUT'     => $this->input->post('TINDAK_LANJUT', true),
            'FOTO_PENGADUAN'    => $foto_pengaduan,
            'TANGGAL_PROSES'    => $this->input->post('TANGGAL_PROSES', true),
            'TANGGAL_SELESAI'   => $this->input->post('TANGGAL_SELESAI', true),
            'FOTO_PROSES'       => $foto_proses,
            'STATUS'            => $status,
            'PIC'               => $this->input->post('PIC', true),
            'CATATAN'           => $this->input->post('CATATAN', true),

            // ✅ KOLOM BARU
            'TITIK_KOORDINAT'   => $this->input->post('TITIK_KOORDINAT', true),
        ];

        $this->Pengaduan_model->update_pengaduan($id, $update_data);

        // ✅ TAMBAHAN: log update
        $this->_log('update', 'pengaduan', $id, $update_data['ITEM_PENGADUAN'] ?? null);

        $this->session->set_flashdata('success', 'Data pengaduan berhasil diperbarui!');
        redirect('pengaduan');
    }

    // 🔹 Hapus Pengaduan
    public function hapus($id)
    {
        if (!can_delete()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menghapus data');
            redirect($this->router->fetch_class());
        }

        $pengaduan = $this->Pengaduan_model->get_pengaduan_by_id($id);
        if (!$pengaduan) {
            $this->session->set_flashdata('error', 'Data pengaduan tidak ditemukan!');
            redirect('pengaduan');
        }

        $this->_delete_file('./uploads/pengaduan/', $pengaduan['FOTO_PENGADUAN']);
        $this->_delete_file('./uploads/proses/', $pengaduan['FOTO_PROSES']);

        $this->Pengaduan_model->delete_pengaduan($id);

        // ✅ TAMBAHAN: log delete
        $this->_log('delete', 'pengaduan', $id, $pengaduan['ITEM_PENGADUAN'] ?? null);

        $this->session->set_flashdata('success', 'Data pengaduan berhasil dihapus!');
        redirect('pengaduan');
    }

    // =======================================================
    // 🔧 Fungsi Bantu
    // =======================================================

    private function _set_rules()
    {
        // Pesan error
        $this->form_validation->set_message('required', '{field} wajib diisi.');
        $this->form_validation->set_error_delimiters('<div class="text-danger small mt-1">', '</div>');

        // Ambil status (kalau UP3: select disabled -> ada hidden STATUS)
        $status = $this->input->post('STATUS', true);
        if (empty($status)) {
            $status = 'Lapor';
        }

        // ✅ WAJIB UNTUK SEMUA STATUS (Lapor/Diproses/Selesai)
        $this->form_validation->set_rules('NAMA_UP3', 'Nama UP3', 'required|trim');
        $this->form_validation->set_rules('TANGGAL_PENGADUAN', 'Tanggal Pengaduan', 'required');
        $this->form_validation->set_rules('JENIS_PENGADUAN', 'Jenis Pengaduan', 'required|trim');
        $this->form_validation->set_rules('ITEM_PENGADUAN', 'Item Pengaduan', 'required|trim');
        $this->form_validation->set_rules('LAPORAN', 'Laporan', 'required|trim');
        $this->form_validation->set_rules('STATUS', 'Status', 'required|trim');
        $this->form_validation->set_rules('PIC', 'PIC', 'required|trim');

        // ✅ KOLOM BARU: dibuat required agar data baru wajib isi
        $this->form_validation->set_rules('TITIK_KOORDINAT', 'Titik Koordinat', 'required|trim');

        // FOTO_PENGADUAN selalu wajib (tambah wajib upload, edit boleh pakai foto lama)
        $this->form_validation->set_rules('FOTO_PENGADUAN', 'Foto Pengaduan', 'callback_foto_pengaduan_required');

        // ✅ KONDISI: DIPROSES atau SELESAI -> wajib isi field proses
        if ($status === 'Diproses' || $status === 'Selesai') {
            $this->form_validation->set_rules('TINDAK_LANJUT', 'Tindak Lanjut', 'required|trim');
            $this->form_validation->set_rules('TANGGAL_PROSES', 'Tanggal Proses', 'required');
            // Foto proses wajib hanya saat diproses/selesai (edit boleh pakai foto lama)
            $this->form_validation->set_rules('FOTO_PROSES', 'Foto Proses', 'callback_foto_proses_required_if_needed');
        }

        // ✅ KONDISI: SELESAI -> wajib isi field selesai
        if ($status === 'Selesai') {
            $this->form_validation->set_rules('CATATAN', 'Catatan', 'required|trim');
            $this->form_validation->set_rules('TANGGAL_SELESAI', 'Tanggal Selesai', 'required');
        }
    }

    // FOTO_PENGADUAN: wajib upload saat tambah, edit boleh pakai foto lama
    public function foto_pengaduan_required()
    {
        $old = $this->input->post('OLD_FOTO_PENGADUAN', true);

        if (!empty($_FILES['FOTO_PENGADUAN']['name'])) {
            return true;
        }

        if (!empty($old)) {
            return true;
        }

        $this->form_validation->set_message('foto_pengaduan_required', '{field} wajib diupload.');
        return false;
    }

    // FOTO_PROSES: wajib jika STATUS Diproses/Selesai, edit boleh pakai foto lama
    public function foto_proses_required_if_needed()
    {
        $status = $this->input->post('STATUS', true);
        if (empty($status)) {
            $status = 'Lapor';
        }

        // Kalau masih Lapor, foto proses tidak wajib
        if ($status !== 'Diproses' && $status !== 'Selesai') {
            return true;
        }

        $old = $this->input->post('OLD_FOTO_PROSES', true);

        if (!empty($_FILES['FOTO_PROSES']['name'])) {
            return true;
        }

        if (!empty($old)) {
            return true;
        }

        $this->form_validation->set_message('foto_proses_required_if_needed', '{field} wajib diupload.');
        return false;
    }

    private function _upload_file($field_name, $path, $old_file = null)
    {
        if (empty($_FILES[$field_name]['name'])) {
            return $old_file;
        }

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $config = [
            'upload_path'   => $path,
            'allowed_types' => 'jpg|jpeg|png',
            'max_size'      => 2048,
            'encrypt_name'  => true,
        ];

        $this->upload->initialize($config);

        if ($this->upload->do_upload($field_name)) {
            if ($old_file && file_exists($path . $old_file)) {
                unlink($path . $old_file);
            }
            return $this->upload->data('file_name');
        }

        $this->session->set_flashdata('error', $this->upload->display_errors());
        redirect($this->uri->uri_string());
        exit;
    }

    private function _delete_file($path, $filename)
    {
        if (!empty($filename) && file_exists($path . $filename)) {
            unlink($path . $filename);
        }
    }
}
