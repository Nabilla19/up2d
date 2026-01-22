<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Transport_security
 * Menangani pencatatan keluar-masuk kendaraan di pos security.
 * Bertanggung jawab atas pencatatan KM (Kilometer) awal dan akhir.
 */
class Transport_security extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Transport_model', 'User_model']);
        $this->load->library(['session', 'form_validation', 'upload']);
        $this->load->helper(['url', 'form']);
        
        // Proteksi login
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        // Hak Akses: Hanya Petugas Security (Role 19) atau Admin
        $role_id = $this->session->userdata('role_id');
        if (!in_array($role_id, [19, 6])) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki hak akses ke menu ini.');
            redirect('dashboard');
        }
    }

    /**
     * Halaman Utama Security
     * Menampilkan daftar kendaraan yang sedang beroperasi atau menunggu diproses.
     */
    public function index() {
        $data['page_title'] = 'Pos Security / Notifikasi';
        $data['requests'] = $this->Transport_model->get_all_requests_detailed();
        
        $this->load->view('layout/header', $data);
        $this->load->view('transport/security_list', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Check-In (Saat kendaraan keluar kantor)
     * Mencatat KM Awal dan Jam Berangkat
     */
    public function checkin($id) {
        $data['page_title'] = 'Security Check-In (Berangkat)';
        $data['request'] = $this->Transport_model->get_requests($id);
        
        // Validasi input
        $this->form_validation->set_rules('km_awal', 'KM Awal', 'required|numeric');
        $this->form_validation->set_rules('jam_berangkat', 'Jam Berangkat', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('transport/form_security_in', $data);
            $this->load->view('layout/footer');
        } else {
            $security_id = $this->session->userdata('user_id');
            
            $log_data = [
                'request_id' => $id,
                'security_id' => $security_id,
                'km_awal' => $this->input->post('km_awal'),
                'jam_berangkat' => $this->input->post('jam_berangkat'),
            ];

            // Proses Upload Foto (Driver & KM Awal)
            $config['upload_path'] = './uploads/transport/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 2048;
            $this->upload->initialize($config);

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }

            if ($this->upload->do_upload('foto_driver_berangkat')) {
                $log_data['foto_driver_berangkat'] = $this->upload->data('file_name');
            }
            if ($this->upload->do_upload('foto_km_berangkat')) {
                $log_data['foto_km_berangkat'] = $this->upload->data('file_name');
            }

            // Simpan log keberangkatan
            $this->Transport_model->add_security_log($log_data);
            
            $this->session->set_flashdata('success', 'Data check-in berhasil dicatat. Silakan cetak Surat Jalan.');
            redirect('transport/export_pdf/' . $id);
        }
    }

    /**
     * Check-Out (Saat kendaraan kembali ke kantor)
     * Mencatat KM Akhir dan Jam Kembali
     */
    public function checkout($id) {
        $data['page_title'] = 'Security Check-Out (Kembali)';
        $data['request'] = $this->Transport_model->get_requests($id);
        $data['log'] = $this->Transport_model->get_security_log($id);
        
        $this->form_validation->set_rules('km_akhir', 'KM Akhir', 'required|numeric');
        $this->form_validation->set_rules('jam_kembali', 'Jam Kembali', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('transport/form_security_out', $data);
            $this->load->view('layout/footer');
        } else {
            $log_data = [
                'km_akhir' => $this->input->post('km_akhir'),
                'jam_kembali' => $this->input->post('jam_kembali'),
                'lama_waktu' => $this->input->post('lama_waktu'),
                'jarak_tempuh' => $this->input->post('jarak_tempuh')
            ];

            // Proses Upload Foto (Driver & KM Akhir)
            $config['upload_path'] = './uploads/transport/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 2048;
            $this->upload->initialize($config);

            if ($this->upload->do_upload('foto_driver_kembali')) {
                $log_data['foto_driver_kembali'] = $this->upload->data('file_name');
            }
            if ($this->upload->do_upload('foto_km_kembali')) {
                $log_data['foto_km_kembali'] = $this->upload->data('file_name');
            }

            // Update log yang sudah ada dengan data kepulangan
            $this->Transport_model->update_security_log($id, $log_data);

            // 1. Kembalikan status kendaraan fisik menjadi 'Available'
            $fleet = $this->Transport_model->get_fleet($id);
            if ($fleet) {
                $this->Transport_model->update_vehicle_status($fleet['plat_nomor'], 'Available');
            }

            // 2. Ubah status permohonan menjadi 'Selesai'
            $this->Transport_model->update_request($id, ['status' => 'Selesai']);

            $this->session->set_flashdata('success', 'Data check-out berhasil dicatat. Status Permohonan: Selesai.');
            redirect('transport/export_pdf/' . $id);
        }
    }
}
