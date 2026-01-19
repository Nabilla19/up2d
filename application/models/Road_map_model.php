<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Road_map_model extends CI_Model
{
    private $table = 'road_map'; // nama tabel di database

    /**
     * Ambil semua data Road Map (urutan terbaru di atas)
     */
    public function get_all_roadmap()
    {
        $this->db->order_by('CREATED_AT', 'DESC');
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Ambil data Road Map dengan limit dan offset (untuk pagination)
     */
    public function get_roadmap($limit, $offset)
    {
        $this->db->order_by('CREATED_AT', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Hitung total data Road Map
     */
    public function count_all_roadmap()
    {
        return $this->db->count_all($this->table);
    }

    /**
     * Ambil satu data Road Map berdasarkan ID
     */
    public function get_roadmap_by_id($id)
    {
        return $this->db->get_where($this->table, ['ID_ROADMAP' => $id])->row_array();
    }

    /**
     * Tambah data Road Map baru
     */
    public function insert_roadmap($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Update data Road Map berdasarkan ID
     */
    public function update_roadmap($id, $data)
    {
        $this->db->where('ID_ROADMAP', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Hapus data Road Map berdasarkan ID
     */
    public function delete_roadmap($id)
    {
        $this->db->where('ID_ROADMAP', $id);
        return $this->db->delete($this->table);
    }
}
