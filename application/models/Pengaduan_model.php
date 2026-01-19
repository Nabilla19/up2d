<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengaduan_model extends CI_Model
{
    private $table = 'pengaduan'; // Nama tabel di database

    // 🔹 Mengambil semua data pengaduan

    public function get_all_pengaduan()
    {
        // Urutkan berdasarkan ID_PENGADUAN terbaru
        $this->db->order_by('ID_PENGADUAN', 'DESC');
        return $this->db->get($this->table)->result_array();
    }


    // 🔹 Mengambil data pengaduan berdasarkan ID_PENGADUAN (Primary Key)
    public function get_pengaduan_by_id($id_pengaduan)
    {
        return $this->db->get_where($this->table, ['ID_PENGADUAN' => $id_pengaduan])->row_array();
    }

    // 🔹 Menambahkan data pengaduan baru
    public function insert_pengaduan($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // 🔹 Memperbarui data pengaduan berdasarkan ID_PENGADUAN
    public function update_pengaduan($id_pengaduan, $data)
    {
        $this->db->where('ID_PENGADUAN', $id_pengaduan);
        return $this->db->update($this->table, $data);
    }

    // 🔹 Menghapus data pengaduan berdasarkan ID_PENGADUAN
    public function delete_pengaduan($id_pengaduan)
    {
        $this->db->where('ID_PENGADUAN', $id_pengaduan);
        return $this->db->delete($this->table);
    }

    // 🔹 (Opsional) Mengambil data pengaduan dengan join ke tabel UP3 (jika nanti ada tabel up3)
    // public function get_pengaduan_with_up3()
    // {
    //     $this->db->select('pengaduan.*, up3.NAMA_UP3');
    //     $this->db->from($this->table);
    //     $this->db->join('up3', 'up3.NAMA_UP3 = pengaduan.NAMA_UP3', 'left');
    //     return $this->db->get()->result_array();
    // }

    // 🔹 (Opsional) Pencarian berdasarkan nama UP3, jenis pengaduan, status, atau PIC
    public function search_pengaduan($keyword)
    {
        $this->db->like('NAMA_UP3', $keyword);
        $this->db->or_like('JENIS_PENGADUAN', $keyword);
        $this->db->or_like('STATUS', $keyword);
        $this->db->or_like('PIC', $keyword);
        return $this->db->get($this->table)->result_array();
    }

    public function count_all_pengaduan($search = null)
    {
        if (!empty($search)) {
            $this->db->like('NAMA_UP3', $search);
            $this->db->or_like('JENIS_PENGADUAN', $search);
            $this->db->or_like('STATUS', $search);
            $this->db->or_like('PIC', $search);
            return $this->db->count_all_results($this->table);
        }
        return $this->db->count_all($this->table);
    }

    public function get_pengaduan_paginated($limit, $start, $search = null)
    {
        if (!empty($search)) {
            $this->db->like('NAMA_UP3', $search);
            $this->db->or_like('JENIS_PENGADUAN', $search);
            $this->db->or_like('STATUS', $search);
            $this->db->or_like('PIC', $search);
        }

        $this->db->order_by('ID_PENGADUAN', 'DESC'); // urutkan dari terbaru ke lama
        return $this->db->get('pengaduan', $limit, $start)->result_array();
    }
}
