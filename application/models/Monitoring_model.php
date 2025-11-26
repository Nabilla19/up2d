<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring_model extends CI_Model
{
    private $table = 'monitoring';

    // 🔹 Ambil semua data (opsional pakai keyword)
    public function get_all_monitoring($keyword = null)
    {
        $this->db->order_by('ID_MONITORING', 'DESC');

        if ($keyword) {
            $this->db->group_start();
            $this->db->like('NOMOR_PRK', $keyword);
            $this->db->or_like('NOMOR_SKK_IO', $keyword);
            $this->db->or_like('JENIS_ANGGARAN', $keyword);
            $this->db->or_like('URAIAN_PRK', $keyword);
            $this->db->or_like('DRP', $keyword);
            $this->db->or_like('URAIAN_PEKERJAAN', $keyword);
            $this->db->or_like('USER', $keyword);
            $this->db->or_like('NO_KONTRAK', $keyword);
            $this->db->or_like('PELAKSANA_VENDOR', $keyword);
            $this->db->group_end();
        }

        return $this->db->get($this->table)->result_array();
    }

    // 🔹 Ambil berdasarkan ID
    public function get_monitoring_by_id($id)
    {
        return $this->db->get_where($this->table, ['ID_MONITORING' => $id])->row_array();
    }

    // 🔹 Insert data
    public function insert_monitoring($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // 🔹 Update data
    public function update_monitoring($id, $data)
    {
        $this->db->where('ID_MONITORING', $id);
        return $this->db->update($this->table, $data);
    }

    // 🔹 Delete data
    public function delete_monitoring($id)
    {
        $this->db->where('ID_MONITORING', $id);
        return $this->db->delete($this->table);
    }

    // 🔹 Pagination
    public function get_monitoring_paginated($limit, $start, $keyword = null)
    {
        $this->db->order_by('ID_MONITORING', 'DESC');

        if ($keyword) {
            $this->db->group_start();
            $this->db->like('NOMOR_PRK', $keyword);
            $this->db->or_like('NOMOR_SKK_IO', $keyword);
            $this->db->or_like('JENIS_ANGGARAN', $keyword);
            $this->db->or_like('URAIAN_PRK', $keyword);
            $this->db->or_like('DRP', $keyword);
            $this->db->or_like('USER', $keyword);
            $this->db->or_like('NO_KONTRAK', $keyword);
            $this->db->or_like('PELAKSANA_VENDOR', $keyword);
            $this->db->group_end();
        }

        return $this->db->get($this->table, $limit, $start)->result_array();
    }

    // 🔹 Hitung total baris untuk pagination
    public function count_monitoring($keyword = null)
    {
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('NOMOR_PRK', $keyword);
            $this->db->or_like('NOMOR_SKK_IO', $keyword);
            $this->db->or_like('JENIS_ANGGARAN', $keyword);
            $this->db->or_like('URAIAN_PRK', $keyword);
            $this->db->or_like('DRP', $keyword);
            $this->db->group_end();
        }

        return $this->db->count_all_results('monitoring');
    }
}
