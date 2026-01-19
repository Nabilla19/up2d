<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller for Notifikasi (Notification Management)
 *
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Notifikasi_model $notifModel
 * @property CI_URI $uri
 * @property CI_Config $config
 */
class Notifikasi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Notifikasi_model', 'notifModel');
        $this->load->helper(['url', 'form', 'authorization']);
        $this->load->library('session');

        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    // Tampilkan daftar notifikasi
    public function index()
    {
        $data['judul'] = 'Notifikasi Aktivitas';
        $data['notifikasi'] = $this->notifModel->get_all();
        $data['unread_count'] = $this->notifModel->count_unread();

        $this->load->view('layout/header', $data);
        $this->load->view('notifikasi/vw_notifikasi', $data);
        $this->load->view('layout/footer');
    }

    // Tandai satu notifikasi sebagai sudah dibaca
    public function mark_read($id = null)
    {
        if (!$id) {
            redirect('Notifikasi');
        }

        $this->notifModel->mark_read($id);
        $this->session->set_flashdata('success', 'Notifikasi ditandai sudah dibaca');
        redirect('Notifikasi');
    }

    // Tandai notifikasi sebagai sudah dibaca dan redirect ke URL target
    public function read($id = null)
    {
        if (!$id) {
            redirect('Notifikasi');
        }

        // Get notifikasi detail
        $notif = $this->notifModel->get_all();
        $target_notif = null;
        foreach ($notif as $n) {
            if ($n['id'] == $id) {
                $target_notif = $n;
                break;
            }
        }

        // Mark as read
        $this->notifModel->mark_read($id);

        // Redirect ke URL target jika ada module dan record_id
        if ($target_notif && !empty($target_notif['module']) && !empty($target_notif['record_id']) && $target_notif['jenis_aktivitas'] != 'delete') {
            redirect($target_notif['module'] . '/edit/' . $target_notif['record_id']);
        } else {
            // Jika tidak ada target, kembali ke notifikasi
            redirect('Notifikasi');
        }
    }

    // Tandai semua notifikasi sebagai sudah dibaca
    public function mark_all_read()
    {
        $this->notifModel->mark_all_read();
        $this->session->set_flashdata('success', 'Semua notifikasi ditandai sudah dibaca');
        redirect('Notifikasi');
    }

    // AJAX: Get latest notifications
    public function get_latest()
    {
        $latest = $this->notifModel->get_latest(10);
        $unread_count = $this->notifModel->count_unread();

        header('Content-Type: application/json');
        echo json_encode([
            'latest' => $latest,
            'unread_count' => (int)$unread_count
        ]);
    }

    // AJAX: Get unread count only
    public function ajax_unread_count()
    {
        $count = $this->notifModel->get_unread_count();
        header('Content-Type: application/json');
        echo json_encode(['unread' => (int)$count]);
    }

    // API: Get unread count for navbar
    public function get_unread_count()
    {
        $count = $this->notifModel->get_unread_count();
        header('Content-Type: application/json');
        echo json_encode(['count' => (int)$count]);
    }

    // Cleanup old notifications (bisa dipanggil via cron)
    public function cleanup($days = 30)
    {
        // Only admin can cleanup
        $role = $this->session->userdata('role');
        if (strtolower($role) !== 'admin') {
            show_error('Unauthorized', 403);
        }

        $this->notifModel->delete_old($days);
        $this->session->set_flashdata('success', "Notifikasi lebih dari {$days} hari berhasil dihapus");
        redirect('Notifikasi');
    }
}
