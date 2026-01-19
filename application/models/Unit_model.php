<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Unit_model extends CI_Model
{
    private $table = 'unit';

    /**
     * Ambil data unit dengan pagination dan optional search query
     * (urut berdasarkan ID_UNIT ASC agar data baru tetap di paling akhir)
     */
    public function get_unit($limit, $offset = 0, $q = null)
    {
        $this->db->from($this->table);

        if (!empty($q)) {
            $this->db->group_start()
                     ->like('UNIT_PELAKSANA', $q)
                     ->or_like('UNIT_LAYANAN', $q)
                     ->or_like('ADDRESS', $q)
                     ->group_end();
        }

        // Urut berdasarkan ID_UNIT ASC => insertion order (lama ke baru)
        $this->db->order_by('ID_UNIT', 'ASC');

        $this->db->limit((int) $limit, (int) $offset);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Hitung semua unit, optional filter q
     */
    public function count_all_unit($q = null)
    {
        $this->db->from($this->table);

        if (!empty($q)) {
            $this->db->group_start()
                     ->like('UNIT_PELAKSANA', $q)
                     ->or_like('UNIT_LAYANAN', $q)
                     ->or_like('ADDRESS', $q)
                     ->group_end();
        }

        return (int) $this->db->count_all_results();
    }

    /**
     * Ambil semua ID terurut berdasarkan insertion order (dipakai untuk posisi global)
     */
    public function get_all_ids_ordered()
    {
        $this->db->select('ID_UNIT');
        $this->db->order_by('ID_UNIT', 'ASC'); // insertion order
        $rows = $this->db->get($this->table)->result_array();
        return array_column($rows, 'ID_UNIT');
    }

    /**
     * Kembalikan map ID => posisi (1-based) untuk daftar ID yang diberikan
     * @param array $ids
     * @return array
     */
    public function get_positions_for_ids(array $ids)
    {
        if (empty($ids)) return [];

        $allIds = $this->get_all_ids_ordered();
        $positions = [];
        $pos = 1;
        $idSet = array_flip($ids);
        foreach ($allIds as $id) {
            if (isset($idSet[$id])) {
                $positions[$id] = $pos;
                unset($idSet[$id]);
                if (empty($idSet)) break;
            }
            $pos++;
        }
        return $positions;
    }

    /**
     * Ambil data berdasarkan ID
     */
    public function get_unit_by_id($id)
    {
        return $this->db->get_where($this->table, ['ID_UNIT' => $id])->row_array();
    }

    /**
     * Tambah data baru
     */
    public function insert_unit($data)
    {
        if (isset($data['ID_UNIT'])) {
            unset($data['ID_UNIT']);
        }

        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Update data
     */
    public function update_unit($id, $data)
    {
        $this->db->where('ID_UNIT', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Hapus data
     */
    public function delete_unit($id)
    {
        $this->db->where('ID_UNIT', $id);
        return $this->db->delete($this->table);
    }

    /**
     * Ambil semua data tanpa paginasi (untuk export/download)
     * Default menggunakan ID_UNIT ASC agar urutan konsisten.
     */
    public function get_all_units()
    {
        $this->db->order_by('ID_UNIT', 'ASC');
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Return the underlying table name
     */
    public function get_table_name()
    {
        return $this->table;
    }
}
