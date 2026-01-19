<?php
defined('BASEPATH') or exit('No direct script acces allowed');

/**
 * @property CI_Session $session
 * @property User_model $User_model
 * @property Dashboard_model $Dashboard_model
 * @property Notifikasi_model $Notifikasi_model
 * @property CI_Input $input
 */
class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Dashboard_model');
        $this->load->model('Notifikasi_model');
    }

    public function index()
    {
        $data['judul'] = "Halaman Dashboard";

        // Navbar data
        $data['page_title'] = "Dashboard";
        $data['page_icon'] = "ni ni-tv-2";

        // If user is logged in, fetch their login_count and last_login for display
        $data['login_count'] = null;
        $data['last_login'] = null;
        $data['user_role'] = null;

        // Try to get role from session first (faster)
        $session_role = $this->session->userdata('user_role');
        if ($session_role) {
            $data['user_role'] = $session_role; // Keep original case
        }

        $user_id = $this->session->userdata('user_id');
        if ($user_id) {
            $user = $this->User_model->find_by_id($user_id);
            if ($user) {
                $data['login_count'] = isset($user['login_count']) ? $user['login_count'] : null;
                $data['last_login'] = isset($user['last_login']) ? $user['last_login'] : null;
                // Override with DB role if available (keep original case)
                if (isset($user['role'])) {
                    $data['user_role'] = $user['role'];
                }
            }
        }

        // =========================
        // KPI DIPISAH AO (OPERASI) & AI (INVESTASI)
        // Sumber data: vw_rkp_prk
        // =========================
        $data['terkontrak_ao'] = $this->Dashboard_model->sum_vw_rkp_prk_by_jenis_anggaran('OPERASI', 'kontrak');
        $data['terkontrak_ai'] = $this->Dashboard_model->sum_vw_rkp_prk_by_jenis_anggaran('INVESTASI', 'kontrak');

        $data['real_bayar_ao'] = $this->Dashboard_model->sum_vw_rkp_prk_by_jenis_anggaran('OPERASI', 'terbayar');
        $data['real_bayar_ai'] = $this->Dashboard_model->sum_vw_rkp_prk_by_jenis_anggaran('INVESTASI', 'terbayar');

        $data['rencana_bayar_ao'] = $this->Dashboard_model->sum_vw_rkp_prk_by_jenis_anggaran('OPERASI', 'rencana_bayar');
        $data['rencana_bayar_ai'] = $this->Dashboard_model->sum_vw_rkp_prk_by_jenis_anggaran('INVESTASI', 'rencana_bayar');

        $data['sisa_anggaran_ao'] = $this->Dashboard_model->sum_vw_rkp_prk_by_jenis_anggaran('OPERASI', 'sisa');
        $data['sisa_anggaran_ai'] = $this->Dashboard_model->sum_vw_rkp_prk_by_jenis_anggaran('INVESTASI', 'sisa');

        // PRK aktif tetap gabungan (tidak dipisah)
        $data['prk_aktif'] = $this->Dashboard_model->get_prk_aktif();

        // =========================
        // CHART TERBAYAR DIPISAH AO & AI
        // Sumber data: vw_prognosa (rekap = 'TERBAYAR')
        // =========================
        $data['chart_labels'] = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $data['chart_values_ao'] = $this->Dashboard_model->get_terbayar_bulanan_2025_by_jenis_anggaran('OPERASI');
        $data['chart_values_ai'] = $this->Dashboard_model->get_terbayar_bulanan_2025_by_jenis_anggaran('INVESTASI');

        // =========================
        // TAMBAHAN: untuk ganti tabel "Sales by Country"
        // Rekap Anggaran per Jenis (INVESTASI, OPERASI, dll)
        // =========================
        $data['rekap_anggaran'] = $this->Dashboard_model->get_rekap_anggaran_per_jenis();

        // =========================
        // TAMBAHAN: untuk ganti "Categories"
        // Riwayat Aktivitas (seperti Notifikasi)
        // =========================
        $data['riwayat_aktivitas'] = $this->Notifikasi_model->get_latest(8);

        $this->load->view("layout/header");
        $this->load->view("dashboard/vw_dashboard.php", $data);
        $this->load->view("layout/footer");
    }

    /**
     * AJAX endpoint: Get login statistics for a specific role
     * Only accessible by admin users
     */
    public function get_role_login_stats()
    {
        // Check if user is admin
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $user = $this->User_model->find_by_id($user_id);
        if (!$user || strtolower($user['role']) !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Access denied. Admin only.']);
            return;
        }

        // Get role from query parameter
        $role = $this->input->get('role');

        if ($role) {
            // Get specific role stats
            $users = $this->User_model->get_users_login_stats($role);
            echo json_encode(['success' => true, 'role' => $role, 'users' => $users]);
        } else {
            // Get all roles summary
            $summary = $this->User_model->get_login_stats_by_role();
            echo json_encode(['success' => true, 'summary' => $summary]);
        }
    }
}
