<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Transport_approval
 * Menangani proses persetujuan oleh Asmen atau Bagian KKU.
 */
class Transport_approval extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Transport_model', 'User_model']);
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form']);
        
        // Proteksi login
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        // Cek Hak Akses: Asmen (Role 15-18), Admin (6), atau Bagian KKU
        $role_id = $this->session->userdata('role_id');
        $role_name = strtolower($this->session->userdata('user_role') ?: $this->session->userdata('role') ?: '');
        
        if (!in_array($role_id, [15, 16, 17, 18, 6]) && $role_name !== 'kku') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki hak akses ke menu Persetujuan.');
            redirect('dashboard');
        }
    }

    /**
     * Halaman Dashboard Persetujuan
     * Memfilter antrian berdasarkan Bidang/Role user
     */
    public function index() {
        $data['page_title'] = 'Persetujuan Asmen / KKU';
        $role_id = $this->session->userdata('role_id');
        $role_name = strtolower($this->session->userdata('user_role') ?: $this->session->userdata('role') ?: '');
        
        $user_id = $this->session->userdata('user_id');
        $all_requests = $this->Transport_model->get_all_requests_detailed();
        
        // 1. Antrian Masuk (Pending)
        $data['active_requests'] = array_filter($all_requests, function($r) use ($role_id, $role_name) {
            // Hanya yang statusnya Pending
            if ($r['status'] != 'Pending Asmen/KKU') return false;
            if ($role_id == 6) return true; // Admin bisa setujui semua

            $bagian = strtolower($r['bagian'] ?? '');
            $is_perencanaan = (strpos($bagian, 'perencanaan') !== false);
            $is_pemeliharaan = (strpos($bagian, 'pemeliharaan') !== false || strpos($bagian, 'har') !== false);
            $is_operasi = (strpos($bagian, 'operasi') !== false);
            $is_fasop = (strpos($bagian, 'fasop') !== false);

            // Mapping: Role Asmen vs Bidang yang bersangkutan
            if ($role_id == 15 && $is_perencanaan) return true;
            if ($role_id == 16 && $is_pemeliharaan) return true;
            if ($role_id == 17 && $is_operasi) return true;
            if ($role_id == 18 && $is_fasop) return true;
            
            // KKU menyetujui bidang di luar asmen operasional (misal: Umum, Keuangan, dll)
            if ($role_name === 'kku') return !($is_perencanaan || $is_pemeliharaan || $is_operasi || $is_fasop);
            return false;
        });

        // 2. Riwayat Saya (Yang sudah pernah diproses user ini)
        $data['history_requests'] = array_filter($all_requests, function($r) use ($user_id, $role_id) {
            if ($role_id == 6) {
                // Admin riwayatnya adalah semua yang sudah tidak pending
                return !in_array($r['status'], ['Pending Asmen', 'Pending Asmen/KKU']);
            }
            // User biasa hanya melihat yang asmen_id nya adalah dirinya sendiri
            return $r['asmen_id'] == $user_id;
        });

        $data['requests'] = $data['active_requests'];

        $this->load->view('layout/header', $data);
        $this->load->view('transport/approval_list', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Tombol Setuju
     */
    public function approve($id) {
        $this->process_approval($id, 'Disetujui', 'Pending Fleet');
    }

    /**
     * Tombol Tolak
     */
    public function reject($id) {
        $this->process_approval($id, 'Ditolak', 'Ditolak');
    }

    /**
     * Fungsi Internal untuk memproses status persetujuan
     */
    private function process_approval($id, $approval_status, $request_status) {
        $asmen_id = $this->session->userdata('user_id');
        
        $approval_data = [
            'request_id' => $id,
            'asmen_id' => $asmen_id,
            'catatan' => $this->input->post('catatan'),
            'is_approved' => ($approval_status == 'Disetujui' ? 1 : 0),
            'barcode_asmen' => md5('ASMEN-'.$asmen_id.'-'.uniqid().'-'.$id)
        ];

        // Simpan ke tabel transport_approvals
        $this->Transport_model->add_approval($approval_data);
        // Update status di tabel transport_requests
        $this->Transport_model->update_request($id, ['status' => $request_status]);

        $this->session->set_flashdata('success', 'Permohonan telah ' . strtolower($approval_status));
        redirect('transport/approval');
    }

    /**
     * Edit Permohonan (hanya bisa sebelum diproses lanjut)
     */
    public function edit($id) {
        $data['page_title'] = 'Edit Permohonan';
        $data['r'] = $this->Transport_model->get_requests($id);
        $data['vehicle_types'] = $this->Transport_model->get_vehicle_types();
        
        $this->load->view('layout/header', $data);
        $this->load->view('transport/form_edit_request', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Proses Update Perubahan
     */
    public function update($id) {
        $data = [
            'nama' => $this->input->post('nama'),
            'jabatan' => $this->input->post('jabatan'),
            'bagian' => $this->input->post('bagian'),
            'macam_kendaraan' => $this->input->post('macam_kendaraan'),
            'jumlah_penumpang' => $this->input->post('jumlah_penumpang'),
            'tujuan' => $this->input->post('tujuan'),
            'keperluan' => $this->input->post('keperluan'),
            'tanggal_jam_berangkat' => $this->input->post('tanggal_jam_berangkat'),
            'lama_pakai' => $this->input->post('lama_pakai')
        ];

        $this->Transport_model->update_request($id, $data);
        $this->session->set_flashdata('success', 'Perubahan permohonan berhasil disimpan.');
        redirect('transport/detail/' . $id);
    }
}
