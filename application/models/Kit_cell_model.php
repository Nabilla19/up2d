<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kit_cell_model extends CI_Model
{
    private $table = 'kit_cell'; // Nama tabel di database

    // 🔹 Mengambil semua data dari tabel kit_cell
    public function get_all_kit_cell()
    {
        return $this->db->get($this->table)->result_array();
    }

    // ✅ LIST + SEARCH (SERVER SIDE)
    public function get_kit_cell($limit, $offset, $search = '')
    {
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('SSOTNUMBER', $search);
            $this->db->or_like('UNITNAME', $search);
            $this->db->or_like('DESCRIPTION', $search);
            $this->db->or_like('NAMA_LOCATION', $search);
            $this->db->or_like('LOCATION', $search);
            $this->db->group_end();
        }

        $this->db->limit($limit, $offset);
        return $this->db->get($this->table)->result_array();
    }

    // ✅ COUNT + SEARCH (pagination ikut hasil search)
    public function count_all_kit_cell($search = '')
    {
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('SSOTNUMBER', $search);
            $this->db->or_like('UNITNAME', $search);
            $this->db->or_like('DESCRIPTION', $search);
            $this->db->or_like('NAMA_LOCATION', $search);
            $this->db->or_like('LOCATION', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results($this->table);
    }

    // 🔹 Mengambil data kit_cell berdasarkan SSOTNUMBER (Primary Key)
    public function get_kit_cell_by_id($ssotnumber)
    {
        return $this->db->get_where($this->table, ['SSOTNUMBER' => $ssotnumber])->row_array();
    }

    // 🔹 Menambahkan data baru ke tabel kit_cell
    public function insert_kit_cell($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // 🔹 Memperbarui data kit_cell berdasarkan SSOTNUMBER
    public function update_kit_cell($ssotnumber, $data)
    {
        $this->db->where('SSOTNUMBER', $ssotnumber);
        return $this->db->update($this->table, $data);
    }

    // 🔹 Menghapus data kit_cell berdasarkan SSOTNUMBER
    public function delete_kit_cell($ssotnumber)
    {
        $this->db->where('SSOTNUMBER', $ssotnumber);
        return $this->db->delete($this->table);
    }

    // 🔹 (Opsional) Mengambil data dengan join ke tabel pembangkit (Foreign Key)
    // Catatan: bagian ini hanya valid kalau memang kolomnya ada di DB kamu.
    public function get_kit_cell_with_pembangkit()
    {
        $this->db->select('kit_cell.*, pembangkit.NAMA_PEMBANGKIT');
        $this->db->from($this->table);
        $this->db->join('pembangkit', 'pembangkit.ID_PEMBANGKIT = kit_cell.ID_PEMBANGKIT', 'left');
        return $this->db->get()->result_array();
    }

    // 🔹 (Opsional) Pencarian (gunakan kolom yang benar-benar ada di tabel kit_cell)
    public function search_kit_cell($keyword)
    {
        $this->db->group_start();
        $this->db->like('SSOTNUMBER', $keyword);
        $this->db->or_like('UNITNAME', $keyword);
        $this->db->or_like('DESCRIPTION', $keyword);
        $this->db->or_like('NAMA_LOCATION', $keyword);
        $this->db->or_like('LOCATION', $keyword);
        $this->db->group_end();

        return $this->db->get($this->table)->result_array();
    }
}
