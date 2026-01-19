<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notifikasi_model extends CI_Model
{
    private $table = 'notifikasi_aktivitas';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Log aktivitas user (login, logout, CRUD operations)
     * @param int $user_id ID user dari tabel users
     * @param string $email Email user
     * @param string $role Role user (Admin, Pemeliharaan, dll)
     * @param string $jenis_aktivitas login|logout|create|update|delete|import
     * @param string $module Nama module/controller (gardu_induk, sop, dll) - NULL jika login/logout
     * @param int $record_id ID record yang di-CRUD - NULL jika login/logout
     * @param string $record_name Nama/label record untuk display - NULL jika login/logout
     * @param string $ip_address IP address user
     * @param string $user_agent Browser/device info
     */
    public function log_aktivitas($user_id, $email, $role, $jenis_aktivitas, $module = null, $record_id = null, $record_name = null, $ip_address = null, $user_agent = null)
    {
        // Format deskripsi sesuai jenis aktivitas
        if ($jenis_aktivitas === 'login') {
            $deskripsi = "Login berhasil";
            $menu_diakses = null;
        } elseif ($jenis_aktivitas === 'logout') {
            $deskripsi = "Logout";
            $menu_diakses = null;
        } elseif ($jenis_aktivitas === 'create') {
            $module_label = $this->get_module_label($module);
            $deskripsi = "Menambahkan data {$module_label}: {$record_name}";
            $menu_diakses = $module_label;
        } elseif ($jenis_aktivitas === 'update') {
            $module_label = $this->get_module_label($module);
            $deskripsi = "Mengedit data {$module_label}: {$record_name}";
            $menu_diakses = $module_label;
        } elseif ($jenis_aktivitas === 'delete') {
            $module_label = $this->get_module_label($module);
            $deskripsi = "Menghapus data {$module_label}: {$record_name}";
            $menu_diakses = $module_label;
        } elseif ($jenis_aktivitas === 'import') {
            $module_label = $this->get_module_label($module);
            $deskripsi = "Mengimport data {$module_label}: {$record_name}";
            $menu_diakses = $module_label;
        } else {
            $deskripsi = "Aktivitas pada {$module}";
            $menu_diakses = $module;
        }

        $data = [
            'user_id'         => $user_id,
            'email'           => $email,
            'role'            => $role,
            'jenis_aktivitas' => $jenis_aktivitas,
            'module'          => $module,
            'record_id'       => $record_id,
            'record_name'     => $record_name,
            'menu_diakses'    => $menu_diakses,
            'deskripsi'       => $deskripsi,
            'tanggal_waktu'   => date('Y-m-d H:i:s'),
            'status_baca'     => 0,
            'ip_address'      => $ip_address,
            'user_agent'      => $user_agent
        ];

        return $this->db->insert($this->table, $data);
    }

    /**
     * Get module label untuk display
     */
    private function get_module_label($module)
    {
        $labels = [
            'gardu_induk'         => 'Gardu Induk',
            'gardu_hubung'        => 'Gardu Hubung',
            'gi_cell'             => 'GI Cell',
            'gh_cell'             => 'GH Cell',
            'kit_cell'            => 'Kit Cell',
            'pembangkit'          => 'Pembangkit',
            'pemutus'             => 'Pemutus',
            'sop'                 => 'SOP',
            'bpm'                 => 'BPM',
            'ik'                  => 'IK',
            'road_map'            => 'Road Map',
            'spln'                => 'SPLN',
            'single_line_diagram' => 'SLD',
            'pengaduan'           => 'Pengaduan',
        ];

        return $labels[$module] ?? ucfirst(str_replace('_', ' ', $module));
    }

    /**
     * Ambil semua notifikasi
     */
    public function get_all()
    {
        $this->db->order_by('tanggal_waktu', 'DESC');
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Ambil semua notifikasi (alias untuk kompatibilitas)
     */
    public function get_all_notifications()
    {
        return $this->get_all();
    }

    /**
     * Ambil notifikasi terbaru
     * @param int $limit Jumlah data yang diambil
     */
    public function get_latest($limit = 10)
    {
        $this->db->order_by('tanggal_waktu', 'DESC');
        $this->db->limit($limit);
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Ambil notifikasi belum dibaca
     */
    public function get_unread()
    {
        $this->db->where('status_baca', 0);
        $this->db->order_by('tanggal_waktu', 'DESC');
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Hitung notifikasi belum dibaca
     */
    public function count_unread()
    {
        $this->db->where('status_baca', 0);
        return $this->db->count_all_results($this->table);
    }

    /**
     * Hitung notifikasi belum dibaca (alias untuk kompatibilitas)
     */
    public function get_unread_count()
    {
        return $this->count_unread();
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca
     * @param int $id ID notifikasi
     */
    public function mark_read($id)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, ['status_baca' => 1]);
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca
     */
    public function mark_all_read()
    {
        $this->db->set('status_baca', 1);
        return $this->db->update($this->table);
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca (alias)
     */
    public function mark_as_read()
    {
        return $this->mark_all_read();
    }

    /**
     * Hapus notifikasi lama (opsional, untuk cleanup)
     * @param int $days Hapus notifikasi lebih dari X hari
     */
    public function delete_old($days = 30)
    {
        $date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        $this->db->where('tanggal_waktu <', $date);
        return $this->db->delete($this->table);
    }
}
