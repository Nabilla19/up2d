<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bpm_model extends CI_Model
{
    private $table = 'bpm'; // nama tabel di database

    /**
     * Ambil semua data BPM (urutan terbaru di atas)
     */
    public function get_all_bpm()
    {
        $this->db->order_by('CREATED_AT', 'DESC');
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Ambil data BPM dengan limit dan offset (untuk pagination)
     */
    public function get_bpm($limit, $offset, $search = null)
    {
        if (!empty($search)) {
            $this->db->like('NAMA_FILE', $search);
        }
        $this->db->order_by('CREATED_AT', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Hitung total data BPM
     */
    public function count_all_bpm($search = null)
    {
        if (!empty($search)) {
            $this->db->like('NAMA_FILE', $search);
            return $this->db->count_all_results($this->table);
        }
        return $this->db->count_all($this->table);
    }

    /**
     * Ambil satu data BPM berdasarkan ID
     */
    public function get_bpm_by_id($id)
    {
        return $this->db->get_where($this->table, ['ID_BPM' => $id])->row_array();
    }

    /**
     * Tambah data BPM baru
     * CREATED_AT otomatis diisi oleh MySQL (DEFAULT CURRENT_TIMESTAMP)
     */
    public function insert_bpm($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Update data BPM berdasarkan ID
     */
    public function update_bpm($id, $data)
    {
        $this->db->where('ID_BPM', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Hapus data BPM berdasarkan ID
     */
    public function delete_bpm($id)
    {
        $this->db->where('ID_BPM', $id);
        return $this->db->delete($this->table);
    }
}
