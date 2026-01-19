<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transport_fleet extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Transport_model', 'User_model']);
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form']);
        
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        // Role KKU or 6 (Admin)
        $role_id = $this->session->userdata('role_id');
        $role = strtolower($this->session->userdata('user_role') ?: $this->session->userdata('role') ?: '');
        
        if (!in_array($role_id, [6]) && $role !== 'kku') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki hak akses ke menu Manajemen Fleet (Hanya Bagian KKU).');
            redirect('dashboard');
        }
    }

    public function index() {
        $data['page_title'] = 'Manajemen Fleet';
        // Only show Pending Fleet requests
        $all_requests = $this->Transport_model->get_all_requests_detailed();
        $data['requests'] = array_filter($all_requests, function($r) {
            return $r['status'] == 'Pending Fleet';
        });

        $this->load->view('layout/header', $data);
        $this->load->view('transport/fleet_list', $data);
        $this->load->view('layout/footer');
    }

    public function process($id) {
        $data['page_title'] = 'Proses Fleet / Surat Jalan';
        $request = $this->Transport_model->get_requests($id);
        $data['request'] = $request;
        
        // Filter available vehicles by the BRAND requested by the user
        $data['vehicles'] = $this->Transport_model->get_available_vehicles($request['macam_kendaraan']);
        
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

            $this->Transport_model->add_fleet($fleet_data);
            
            // Update Vehicle Status to 'In Use'
            $this->Transport_model->update_vehicle_status($this->input->post('plat_nomor'), 'In Use', $id);
            
            $this->Transport_model->update_request($id, ['status' => 'In Progress']);

            $this->session->set_flashdata('success', 'Data fleet berhasil disimpan. Status menjadi In Progress.');
            redirect('transport/fleet');
        }
    }
}
