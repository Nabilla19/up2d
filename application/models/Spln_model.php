<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Spln_model extends CI_Model
{
    private $table = 'spln'; // nama tabel di database

    /**
     * Ambil semua data SPLN (urutan terbaru di atas)
     */
    public function get_all_spln()
    {
        $this->db->order_by('CREATED_AT', 'DESC');
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Ambil data SPLN dengan limit dan offset (untuk pagination)
     */
    public function get_spln($limit, $offset)
    {
        $this->db->order_by('CREATED_AT', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Hitung total data SPLN
     */
    public function count_all_spln()
    {
        return $this->db->count_all($this->table);
    }

    /**
     * Ambil satu data SPLN berdasarkan ID
     */
    public function get_spln_by_id($id)
    {
        return $this->db->get_where($this->table, ['ID_SPLN' => $id])->row_array();
    }

    /**
     * Tambah data SPLN baru
     */
    public function insert_spln($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Update data SPLN berdasarkan ID
     */
    public function update_spln($id, $data)
    {
        $this->db->where('ID_SPLN', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Hapus data SPLN berdasarkan ID
     */
    public function delete_spln($id)
    {
        $this->db->where('ID_SPLN', $id);
        return $this->db->delete($this->table);
    }
}
