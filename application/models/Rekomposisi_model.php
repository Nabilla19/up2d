<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rekomposisi_model extends CI_Model
{
    private $table = 'rekomposisi';

    // 🔹 Ambil semua data rekomposisi, optional keyword untuk pencarian
    public function get_all_rekomposisi($keyword = null)
    {
        $this->db->order_by('ID_REKOMPOSISI', 'DESC');

        if ($keyword) {
            $this->db->group_start();
            $this->db->like('JENIS_ANGGARAN', $keyword);
            $this->db->or_like('NOMOR_PRK', $keyword);
            $this->db->or_like('NOMOR_SKK_IO', $keyword);
            $this->db->or_like('PRK', $keyword);
            $this->db->or_like('JUDUL_DRP', $keyword);
            $this->db->group_end();
        }

        return $this->db->get($this->table)->result_array();
    }

    // 🔹 Ambil data berdasarkan ID
    public function get_rekomposisi_by_id($id)
    {
        return $this->db->get_where($this->table, ['ID_REKOMPOSISI' => $id])->row_array();
    }

    // 🔹 Tambah data baru
    public function insert_rekomposisi($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // 🔹 Update data
    public function update_rekomposisi($id, $data)
    {
        $this->db->where('ID_REKOMPOSISI', $id);
        return $this->db->update($this->table, $data);
    }

    // 🔹 Hapus data
    public function delete_rekomposisi($id)
    {
        $this->db->where('ID_REKOMPOSISI', $id);
        return $this->db->delete($this->table);
    }

    // 🔹 Pagination
    public function get_rekomposisi_paginated($limit, $start, $keyword = null)
    {
        $this->db->order_by('ID_REKOMPOSISI', 'DESC');

        if ($keyword) {
            $this->db->group_start();
            $this->db->like('JENIS_ANGGARAN', $keyword);
            $this->db->or_like('NOMOR_PRK', $keyword);
            $this->db->or_like('NOMOR_SKK_IO', $keyword);
            $this->db->or_like('PRK', $keyword);
            $this->db->or_like('JUDUL_DRP', $keyword);
            $this->db->group_end();
        }

        return $this->db->get($this->table, $limit, $start)->result_array();
    }
}
