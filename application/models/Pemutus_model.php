<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pemutus_model extends CI_Model
{
    private $table = 'lbs_recloser'; // Nama tabel di database

    // 🔹 Mengambil semua data dari tabel lbs_recloser
    public function get_all_pemutus()
    {
        return $this->db->get($this->table)->result_array();
    }

    // ✅ LIST + SEARCH (SERVER SIDE)
    public function get_pemutus($limit, $offset, $search = '')
    {
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('SSOTNUMBER', $search);
            $this->db->or_like('CXUNIT', $search);
            $this->db->or_like('UNITNAME', $search);
            $this->db->or_like('LOCATION', $search);
            $this->db->or_like('DESCRIPTION', $search);
            $this->db->group_end();
        }

        $this->db->limit($limit, $offset);
        return $this->db->get($this->table)->result_array();
    }

    // ✅ COUNT + SEARCH (pagination ikut hasil search)
    public function count_all_pemutus($search = '')
    {
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('SSOTNUMBER', $search);
            $this->db->or_like('CXUNIT', $search);
            $this->db->or_like('UNITNAME', $search);
            $this->db->or_like('LOCATION', $search);
            $this->db->or_like('DESCRIPTION', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results($this->table);
    }

    // 🔹 Mengambil data pemutus berdasarkan SSOTNUMBER (Primary Key)
    public function get_pemutus_by_id($ssotnumber)
    {
        return $this->db->get_where($this->table, ['SSOTNUMBER' => $ssotnumber])->row_array();
    }

    // 🔹 Menambahkan data baru ke tabel lbs_recloser
    public function insert_pemutus($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // 🔹 Memperbarui data pemutus berdasarkan SSOTNUMBER
    public function update_pemutus($ssotnumber, $data)
    {
        $this->db->where('SSOTNUMBER', $ssotnumber);
        return $this->db->update($this->table, $data);
    }

    // 🔹 Menghapus data pemutus berdasarkan SSOTNUMBER
    public function delete_pemutus($ssotnumber)
    {
        $this->db->where('SSOTNUMBER', $ssotnumber);
        return $this->db->delete($this->table);
    }

    // 🔹 (Opsional) Mengambil data dengan join ke tabel unit_layanan (jika ada relasi)
    public function get_pemutus_with_unit()
    {
        $this->db->select('lbs_recloser.*, ulp.NAMA_ULP');
        $this->db->from($this->table);
        $this->db->join('ulp', 'ulp.CXUNIT = lbs_recloser.UNIT_LAYANAN', 'left');
        return $this->db->get()->result_array();
    }

    // 🔹 (Opsional) Untuk pencarian berdasarkan nama keypoint atau penyulang
    public function search_pemutus($keyword)
    {
        $this->db->like('KEYPOINT', $keyword);
        $this->db->or_like('PENYULANG', $keyword);
        $this->db->or_like('SSOTNUMBER', $keyword);
        return $this->db->get($this->table)->result_array();
    }
}
