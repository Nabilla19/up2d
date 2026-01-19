<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ik_model extends CI_Model
{
    private $table = 'ik'; // nama tabel di database

    /**
     * Ambil semua data IK (urutan terbaru di atas)
     */
    public function get_all_ik()
    {
        $this->db->order_by('CREATED_AT', 'DESC');
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Ambil data IK dengan limit dan offset (untuk pagination)
     */
    public function get_ik($limit, $offset, $search = null)
    {
        if (!empty($search)) {
            $this->db->like('NAMA_FILE', $search);
        }
        $this->db->order_by('CREATED_AT', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Hitung total data IK
     */
    public function count_all_ik($search = null)
    {
        if (!empty($search)) {
            $this->db->like('NAMA_FILE', $search);
        }
        return $this->db->count_all_results($this->table);
    }

    /**
     * Ambil satu data IK berdasarkan ID
     */
    public function get_ik_by_id($id)
    {
        return $this->db->get_where($this->table, ['ID_IK' => $id])->row_array();
    }

    /**
     * Tambah data IK baru
     * CREATED_AT otomatis diisi oleh MySQL
     */
    public function insert_ik($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Update data IK berdasarkan ID
     */
    public function update_ik($id, $data)
    {
        $this->db->where('ID_IK', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Hapus data IK berdasarkan ID
     */
    public function delete_ik($id)
    {
        $this->db->where('ID_IK', $id);
        return $this->db->delete($this->table);
    }
}
