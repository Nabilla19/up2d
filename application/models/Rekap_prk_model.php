<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rekap_prk_model extends CI_Model
{
    private $table = 'prk_data'; // Nama tabel

    // 🔹 Ambil semua PRK
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

    // 🔹 Ambil data berdasar ID (fungsi utama)
    public function get_prk_by_id($id)
    {
        return $this->db->get_where($this->table, ['ID_PRK' => $id])->row_array();
    }

    // 🔹 Alias: agar controller yang memanggil get_by_id tetap aman
    public function get_by_id($id)
    {
        return $this->get_prk_by_id($id);
    }

    // 🔹 Tambah data
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

    public function get_all_rekomposisi_prk()
    {
        return $this->db->select('NOMOR_PRK')
            ->from('rekomposisi')
            ->group_by('NOMOR_PRK')
            ->order_by('NOMOR_PRK', 'ASC')
            ->get()
            ->result_array();
    }
    public function get_prk_by_jenis($jenis)
    {
        return $this->db->select('NOMOR_PRK')
            ->from('rekomposisi')
            ->where('JENIS_ANGGARAN', $jenis)
            ->group_by('NOMOR_PRK')
            ->order_by('NOMOR_PRK', 'ASC')
            ->get()
            ->result_array();
    }

    public function get_uraian_prk($nomor_prk)
    {
        return $this->db->select('PRK')
            ->from('rekomposisi')
            ->where('NOMOR_PRK', $nomor_prk)
            ->get()
            ->row_array();
    }
}
