<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gi_cell_model extends CI_Model
{
    private $table = 'gi_cell'; // Nama tabel di database

    // Mengambil semua data dari tabel gi_cell
    public function get_all_gi_cell()
    {
        return $this->db->get($this->table)->result_array();
    }

    // Mengambil data gi_cell berdasarkan limit dan offset, serta pencarian
    public function get_gi_cell($limit, $offset, $search = '')
    {
        if ($search) {
            $this->db->group_start();
            $this->db->like('UNITNAME', $search); // Pencarian berdasarkan nama unit
            $this->db->or_like('SSOTNUMBER', $search); // Pencarian berdasarkan SSOTNUMBER
            $this->db->or_like('DESCRIPTION', $search); // Pencarian berdasarkan deskripsi
            $this->db->group_end();
        }
        $this->db->limit($limit, $offset); // Batasi jumlah data berdasarkan per_page dan offset
        $query = $this->db->get($this->table);
        return $query->result_array(); // Mengembalikan hasil data
    }

    // Menghitung jumlah data GI Cell dengan pencarian
    public function count_all_gi_cell($search = '')
    {
        if ($search) {
            $this->db->group_start();
            $this->db->like('UNITNAME', $search); // Pencarian berdasarkan nama unit
            $this->db->or_like('SSOTNUMBER', $search); // Pencarian berdasarkan SSOTNUMBER
            $this->db->or_like('DESCRIPTION', $search); // Pencarian berdasarkan deskripsi
            $this->db->group_end();
        }
        return $this->db->count_all_results($this->table); // Menghitung total data yang sesuai dengan pencarian
    }

    // Mengambil data gi_cell berdasarkan SSOTNUMBER (primary key business)
    public function get_gi_cell_by_id($ssotnumber)
    {
        return $this->db->get_where($this->table, ['SSOTNUMBER' => $ssotnumber])->row_array();
    }

    // Menambahkan data baru ke tabel gi_cell
    public function insert_gi_cell($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // Memperbarui data gi_cell berdasarkan SSOTNUMBER
    public function update_gi_cell($ssotnumber, $data)
    {
        $this->db->where('SSOTNUMBER', $ssotnumber);
        return $this->db->update($this->table, $data);
    }

    // Menghapus data gi_cell berdasarkan SSOTNUMBER
    public function delete_gi_cell($ssotnumber)
    {
        $this->db->where('SSOTNUMBER', $ssotnumber);
        return $this->db->delete($this->table);
    }
}
