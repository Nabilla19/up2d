<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transport_request extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Transport_model', 'User_model']);
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form']);
        
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    public function index() {
        // Default to my requests
        $this->my_requests();
    }

    public function my_requests() {
        $user_id = $this->session->userdata('user_id');
        $data['page_title'] = 'Daftar Permohonan Saya';
        $data['requests'] = $this->Transport_model->get_my_requests($user_id);
        $this->load->view('layout/header', $data);
        $this->load->view('transport/my_requests', $data);
        $this->load->view('layout/footer');
    }

    public function all_requests() {
        $data['page_title'] = 'Daftar Permohonan Unit';
        $data['requests'] = $this->Transport_model->get_all_requests_detailed();
        $this->load->view('layout/header', $data);
        $this->load->view('transport/all_requests', $data);
        $this->load->view('layout/footer');
    }

    public function ajukan() {
        $data['page_title'] = 'Ajukan Peminjaman';
        $data['vehicle_types'] = $this->Transport_model->get_vehicle_types();
        $this->load->view('layout/header', $data);
        $this->load->view('transport/form_request', $data);
        $this->load->view('layout/footer');
    }

    public function create() {
        $data['page_title'] = 'Ajukan Permohonan Kendaraan';
        $data['vehicle_types'] = $this->Transport_model->get_vehicle_types();
        $this->load->view('layout/header', $data);
        $this->load->view('transport/form_request', $data);
        $this->load->view('layout/footer');
    }

    public function store() {
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('jabatan', 'Jabatan', 'required');
        $this->form_validation->set_rules('bagian', 'Bagian', 'required');
        $this->form_validation->set_rules('tujuan', 'Tujuan', 'required');
        $this->form_validation->set_rules('keperluan', 'Keperluan', 'required');
        $this->form_validation->set_rules('tanggal_jam_berangkat', 'Tanggal/Jam Berangkat', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->create();
        } else {
            $user_id = $this->session->userdata('user_id');
            $data = [
                'user_id' => $user_id,
                'nama' => $this->input->post('nama'),
                'jabatan' => $this->input->post('jabatan'),
                'bagian' => $this->input->post('bagian'),
                'macam_kendaraan' => $this->input->post('macam_kendaraan'),
                'jumlah_penumpang' => $this->input->post('jumlah_penumpang'),
                'tujuan' => $this->input->post('tujuan'),
                'keperluan' => $this->input->post('keperluan'),
                'tanggal_jam_berangkat' => $this->input->post('tanggal_jam_berangkat'),
                'lama_pakai' => $this->input->post('lama_pakai'),
                'status' => 'Pending Asmen',
                'barcode_pemohon' => md5('PEMOHON-'.$user_id.'-'.uniqid().'-'.time())
            ];

            $request_id = $this->Transport_model->create_request($data);

            if ($request_id) {
                $this->session->set_flashdata('success', 'Permohonan berhasil diajukan.');
                redirect('transport/detail/' . $request_id);
            } else {
                $this->session->set_flashdata('error', 'Gagal mengajukan permohonan.');
                redirect('transport/ajukan');
            }
        }
    }

    public function detail($id) {
        $request = $this->Transport_model->get_requests($id);
        if (!$request) {
            show_404();
        }

        $data['page_title'] = 'Detail Permohonan';
        $data['request'] = $request;
        $data['approval'] = $this->Transport_model->get_approval($id);
        $data['fleet'] = $this->Transport_model->get_fleet($id);
        $data['security'] = $this->Transport_model->get_security_log($id);

        $this->load->view('layout/header', $data);
        $this->load->view('transport/detail_request', $data);
        $this->load->view('layout/footer');
    }

    public function export_pdf($id = null) {
        $data['page_title'] = $id ? 'Laporan Perjalanan' : 'Laporan Riwayat Peminjaman Kendaraan';
        
        $all_requests = $this->Transport_model->get_all_requests_detailed();
        
        if ($id) {
            $data['requests'] = array_values(array_filter($all_requests, function($r) use ($id) {
                return $r['id'] == $id;
            }));
            
            // If ID specified but not found in detailed list, try a simpler fetch
            if (empty($data['requests'])) {
                $single = $this->Transport_model->get_requests($id);
                if ($single) {
                    $data['requests'] = [$single];
                }
            }
        } else {
            $data['requests'] = $all_requests;
        }
        
        $this->load->view('transport/export_view', $data);
    }
}
