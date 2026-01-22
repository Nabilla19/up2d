<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sop_model extends CI_Model
{
    private $table = 'sop'; // nama tabel di database

    /**
     * Ambil semua data SOP (urutan terbaru di atas)
     */
    public function get_all_sop()
    {
        $this->db->order_by('CREATED_AT', 'DESC');
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Ambil data SOP dengan limit dan offset (untuk pagination)
     */
    public function get_sop($limit, $offset, $search = '')
    {
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('NAMA_FILE', $search);
            $this->db->or_like('CREATED_BY', $search);
            $this->db->group_end();
        }
        $this->db->order_by('CREATED_AT', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Hitung total data SOP
     */
    public function count_all_sop($search = '')
    {
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('NAMA_FILE', $search);
            $this->db->or_like('CREATED_BY', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results($this->table);
    }

    /**
     * Ambil satu data SOP berdasarkan ID
     */
    public function get_sop_by_id($id)
    {
        return $this->db->get_where($this->table, ['ID_SOP' => $id])->row_array();
    }

    /**
     * Tambah data SOP baru
     * CREATED_AT otomatis diisi oleh MySQL (DEFAULT CURRENT_TIMESTAMP)
     */
    public function insert_sop($data)
    {
        // Tidak perlu menambahkan CREATED_AT karena sudah otomatis di DB
        return $this->db->insert($this->table, $data);
    }

    /**
     * Update data SOP berdasarkan ID
     */
    public function update_sop($id, $data)
    {
        $this->db->where('ID_SOP', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Hapus data SOP berdasarkan ID
     */
    public function delete_sop($id)
    {
        $this->db->where('ID_SOP', $id);
        return $this->db->delete($this->table);
    }
}
