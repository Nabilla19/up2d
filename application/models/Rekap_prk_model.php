<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rekap_prk_model extends CI_Model
{
    private $table = 'prk_data'; // nama tabel di database

    // 🔹 Ambil semua data PRK, optional keyword untuk pencarian
    public function get_all_prk($keyword = null)
    {
        $this->db->order_by('ID_PRK', 'DESC');

        if ($keyword) {
            $this->db->group_start();
            $this->db->like('JENIS_ANGGARAN', $keyword);
            $this->db->or_like('NOMOR_PRK', $keyword);
            $this->db->or_like('URAIAN_PRK', $keyword);
            $this->db->or_like('NODIN_SRT', $keyword);
            $this->db->or_like('KE_TAHUN_2026', $keyword);
            $this->db->group_end();
        }

        return $this->db->get($this->table)->result_array();
    }

    // 🔹 Ambil data berdasarkan ID
    public function get_prk_by_id($id)
    {
        return $this->db->get_where($this->table, ['ID_PRK' => $id])->row_array();
    }

    // 🔹 Tambah data baru
    public function insert_prk($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // 🔹 Update data
    public function update_prk($id, $data)
    {
        $this->db->where('ID_PRK', $id);
        return $this->db->update($this->table, $data);
    }

    // 🔹 Hapus data
    public function delete_prk($id)
    {
        $this->db->where('ID_PRK', $id);
        return $this->db->delete($this->table);
    }

    // 🔹 Pagination
    public function get_prk_paginated($limit, $start, $keyword = null)
    {
        $this->db->order_by('ID_PRK', 'DESC');

        if ($keyword) {
            $this->db->group_start();
            $this->db->like('JENIS_ANGGARAN', $keyword);
            $this->db->or_like('NOMOR_PRK', $keyword);
            $this->db->or_like('URAIAN_PRK', $keyword);
            $this->db->or_like('NODIN_SRT', $keyword);
            $this->db->or_like('KE_TAHUN_2026', $keyword);
            $this->db->group_end();
        }

        return $this->db->get($this->table, $limit, $start)->result_array();
    }
}
