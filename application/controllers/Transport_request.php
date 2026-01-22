<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Transport_request
 * Menangani pembuatan pengajuan (request) peminjaman kendaraan oleh user.
 */
class Transport_request extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load model dan library yang dibutuhkan
        $this->load->model(['Transport_model', 'User_model']);
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form']);
        
        // Proteksi: Harus login untuk mengakses
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    /**
     * Halaman Dashboard Permohonan
     * Mengarahkan ke daftar permohonan milik user saat ini
     */
    public function index() {
        $this->my_requests();
    }

    /**
     * Menampilkan daftar permohonan milik user yang sedang login
     */
    public function my_requests() {
        $user_id = $this->session->userdata('user_id');
        $data['page_title'] = 'Daftar Permohonan Saya';
        $data['requests'] = $this->Transport_model->get_my_requests($user_id);

        $this->load->view('layout/header', $data);
        $this->load->view('transport/my_requests', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Menampilkan seluruh permohonan unit (Bisa dilihat oleh semua user yang sudah login)
     */
    public function all_requests() {
        $data['page_title'] = 'Daftar Permohonan Unit';
        $data['requests'] = $this->Transport_model->get_all_requests_detailed();

        $this->load->view('layout/header', $data);
        $this->load->view('transport/all_requests', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Halaman Form Pengajuan Baru
     */
    public function ajukan() {
        $data['page_title'] = 'Ajukan Peminjaman';
        $data['vehicle_types'] = $this->Transport_model->get_vehicle_types(); // Mengambil jenis kendaraan (Avanza, Innova, dll)

        $this->load->view('layout/header', $data);
        $this->load->view('transport/form_request', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Alias dari ajukan()
     */
    public function create() {
        $this->ajukan();
    }

    /**
     * Proses penyimpanan data pengajuan ke database
     */
    public function store() {
        // Validasi input wajib
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('jabatan', 'Jabatan', 'required');
        $this->form_validation->set_rules('bagian', 'Bagian', 'required');
        $this->form_validation->set_rules('tujuan', 'Tujuan', 'required');
        $this->form_validation->set_rules('keperluan', 'Keperluan', 'required');
        $this->form_validation->set_rules('tanggal_jam_berangkat', 'Tanggal/Jam Berangkat', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->create(); // Kembali ke form jika gagal validasi
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
                'status' => 'Pending Asmen/KKU', // Status awal pengajuan
                'barcode_pemohon' => md5('PEMOHON-'.$user_id.'-'.uniqid().'-'.time()) // Generate kode unik untuk barcode
            ];

            $request_id = $this->Transport_model->create_request($data);

            if ($request_id) {
                // Notifikasi sukses
                $this->session->set_flashdata('success', 'Permohonan berhasil diajukan. Menunggu persetujuan Asmen / KKU.');
                redirect('transport/detail/' . $request_id);
            } else {
                $this->session->set_flashdata('error', 'Gagal mengajukan permohonan.');
                redirect('transport/ajukan');
            }
        }
    }

    /**
     * Halaman Detail Permohonan
     * Menampilkan seluruh informasi dari pengajuan sampai log security
     */
    public function detail($id) {
        $request = $this->Transport_model->get_requests($id);
        if (!$request) {
            show_404();
        }

        $data['page_title'] = 'Detail Permohonan';
        $data['request'] = $request;
        $data['approval'] = $this->Transport_model->get_approval($id); // Data persetujuan Asmen
        $data['fleet'] = $this->Transport_model->get_fleet($id); // Data driver & kendaraan
        $data['security'] = $this->Transport_model->get_security_log($id); // Data log KM

        $this->load->view('layout/header', $data);
        $this->load->view('transport/detail_request', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Cetak Laporan / PDF Detail Perjalanan
     */
    public function export_pdf($id = null) {
        $data['page_title'] = $id ? 'Laporan Perjalanan' : 'Laporan Riwayat Peminjaman Kendaraan';
        
        $all_requests = $this->Transport_model->get_all_requests_detailed();
        
        if ($id) {
            // Filter hanya 1 ID jika parameter ada
            $data['requests'] = array_values(array_filter($all_requests, function($r) use ($id) {
                return $r['id'] == $id;
            }));
            
            if (empty($data['requests'])) {
                $single = $this->Transport_model->get_requests($id);
                if ($single) { $data['requests'] = [$single]; }
            }
        } else {
            $data['requests'] = $all_requests;
        }
        
        $this->load->view('transport/export_view', $data);
    }
}
