<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gardu_induk_model extends CI_Model
{
    private $table = 'gi'; // sesuaikan dengan nama tabel di database kamu

    /**
     * Ambil semua data tanpa paginasi (untuk export/download)
     * Urutkan agar konsisten
     */
    public function get_all_gardu_induk()
    {
        $this->db->order_by('SSOTNUMBER', 'ASC');
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Ambil data gardu induk dengan pagination dan optional search query
     */
    public function get_gardu_induk($limit, $offset = 0, $q = null)
    {
        $this->db->from($this->table);

        if (!empty($q)) {
            $this->db->group_start()
                ->like('UNITNAME_UP3', $q)
                ->or_like('UNITNAME', $q)
                ->or_like('SSOTNUMBER', $q)
                ->or_like('DESCRIPTION', $q)
                ->or_like('CITY', $q)
            ->group_end();
        }

        $this->db->order_by('SSOTNUMBER', 'ASC');
        $this->db->limit((int) $limit, (int) $offset);

        return $this->db->get()->result_array();
    }

    /**
     * Hitung semua gardu induk, optional filter q
     */
    public function count_all_gardu_induk($q = null)
    {
        $this->db->from($this->table);

        if (!empty($q)) {
            $this->db->group_start()
                ->like('UNITNAME_UP3', $q)
                ->or_like('UNITNAME', $q)
                ->or_like('SSOTNUMBER', $q)
                ->or_like('DESCRIPTION', $q)
                ->or_like('CITY', $q)
            ->group_end();
        }

        return (int) $this->db->count_all_results();
    }

    /**
     * Ambil semua SSOTNUMBER terurut (optional filter q)
     * Dipakai untuk posisi global saat search
     */
    private function get_all_ids_ordered($q = null)
    {
        $this->db->select('SSOTNUMBER');
        $this->db->from($this->table);

        if (!empty($q)) {
            $this->db->group_start()
                ->like('UNITNAME_UP3', $q)
                ->or_like('UNITNAME', $q)
                ->or_like('SSOTNUMBER', $q)
                ->or_like('DESCRIPTION', $q)
                ->or_like('CITY', $q)
            ->group_end();
        }

        $this->db->order_by('SSOTNUMBER', 'ASC');
        $rows = $this->db->get()->result_array();

        return array_column($rows, 'SSOTNUMBER');
    }

    /**
     * Kembalikan map SSOTNUMBER => posisi (1-based) untuk daftar id yang diberikan,
     * berdasarkan urutan hasil pencarian (kalau q ada) atau urutan tabel (kalau q kosong).
     */
    public function get_positions_for_ids(array $ids, $q = null)
    {
        if (empty($ids)) return [];

        $allIds = $this->get_all_ids_ordered($q);
        $positions = [];
        $pos = 1;

        // quick lookup
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
     * Ambil data berdasarkan SSOTNUMBER (primary key)
     */
    public function get_gardu_induk_by_id($ssotnumber)
    {
        return $this->db->get_where($this->table, ['SSOTNUMBER' => $ssotnumber])->row_array();
    }

    /**
     * Tambah data baru
     */
    public function insert_gardu_induk($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Update data
     */
    public function update_gardu_induk($ssotnumber, $data)
    {
        $this->db->where('SSOTNUMBER', $ssotnumber);
        return $this->db->update($this->table, $data);
    }

    /**
     * Hapus data
     */
    public function delete_gardu_induk($ssotnumber)
    {
        $this->db->where('SSOTNUMBER', $ssotnumber);
        return $this->db->delete($this->table);
    }

    /**
     * Return the underlying table name
     */
    public function get_table_name()
    {
        return $this->table;
    }
}
