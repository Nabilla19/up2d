<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Transport_fleet
 * Menangani penugasan kendaraan dan pengemudi (Fungsi Surat Jalan).
 * Dikelola oleh Bagian KKU.
 */
class Transport_fleet extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Transport_model', 'User_model']);
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form']);
        
        // Proteksi: Harus login
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        // Cek Hak Akses: Hanya Admin atau Bagian KKU
        $role_id = $this->session->userdata('role_id');
        $role = strtolower($this->session->userdata('user_role') ?: $this->session->userdata('role') ?: '');
        
        if (!in_array($role_id, [6]) && $role !== 'kku') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki hak akses ke menu Manajemen Fleet (Hanya Bagian KKU).');
            redirect('dashboard');
        }
    }

    /**
     * Halaman Daftar Tugas Fleet
     * Menampilkan daftar permohonan yang sudah disetujui Asmen, tapi belum punya kendaraan.
     */
    public function index() {
        $data['page_title'] = 'Manajemen Fleet';
        // Ambil hanya pengajuan dengan status Pending Fleet
        $all_requests = $this->Transport_model->get_all_requests_detailed();
        $data['requests'] = array_filter($all_requests, function($r) {
            return $r['status'] == 'Pending Fleet';
        });

        $this->load->view('layout/header', $data);
        $this->load->view('transport/fleet_list', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Halaman Input Kendaraan (Proses Surat Jalan)
     */
    public function process($id) {
        $data['page_title'] = 'Proses Fleet / Surat Jalan';
        $request = $this->Transport_model->get_requests($id);
        $data['request'] = $request;
        
        // FILTER: Mengambil kendaraan yang tersedia (Available) dan sesuai merk/jenis yang diminta pemohon
        $data['vehicles'] = $this->Transport_model->get_available_vehicles($request['macam_kendaraan']);
        
        // Aturan validasi
        $this->form_validation->set_rules('mobil', 'Mobil', 'required');
        $this->form_validation->set_rules('plat_nomor', 'Plat Nomor', 'required');
        $this->form_validation->set_rules('pengemudi', 'Pengemudi', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('transport/form_fleet', $data);
            $this->load->view('layout/footer');
        } else {
            $admin_id = $this->session->userdata('user_id');
            $fleet_data = [
                'request_id' => $id,
                'admin_id' => $admin_id,
                'mobil' => $this->input->post('mobil'),
                'plat_nomor' => $this->input->post('plat_nomor'),
                'pengemudi' => $this->input->post('pengemudi'),
                'barcode_fleet' => md5('KKU-'.$admin_id.'-'.uniqid().'-'.$id)
            ];

            // 1. Simpan data penugasan kendaraan
            $this->Transport_model->add_fleet($fleet_data);
            
            // 2. Ubah status kendaraan fisik menjadi 'In Use' (tidak bisa dipinjam orang lain)
            $this->Transport_model->update_vehicle_status($this->input->post('plat_nomor'), 'In Use', $id);
            
            // 3. Update status permohonan menjadi 'In Progress' (Siap digunakan/menunggu security)
            $this->Transport_model->update_request($id, ['status' => 'In Progress']);

            $this->session->set_flashdata('success', 'Data fleet berhasil disimpan. Status menjadi In Progress.');
            redirect('transport/fleet');
        }
    }
}
