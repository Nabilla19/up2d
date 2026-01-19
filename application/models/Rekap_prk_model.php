<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rekap_prk_model extends CI_Model
{
    private $view = 'vw_rkp_prk';

    private function apply_search($keyword)
    {
        if (!$keyword || trim($keyword) === '') return;
        $kw = trim($keyword);

        $this->db->group_start()
            ->like('jenis_anggaran', $kw)
            ->or_like('nomor_prk', $kw)
            ->or_like('uraian_prk', $kw)
            ->or_like('nomor_skk_io', $kw)
            ->group_end();
    }

    public function count_all($keyword = null, $jenis_anggaran = null)
    {
        $this->db->from($this->view);

        if ($jenis_anggaran && trim($jenis_anggaran) !== '') {
            $this->db->where('jenis_anggaran', $jenis_anggaran);
        }

        $this->apply_search($keyword);
        return (int)$this->db->count_all_results();
    }

    public function get_paginated($limit, $offset, $keyword = null, $jenis_anggaran = null)
    {
        $this->db->from($this->view);

        if ($jenis_anggaran && trim($jenis_anggaran) !== '') {
            $this->db->where('jenis_anggaran', $jenis_anggaran);
        }

        $this->apply_search($keyword);

        // urut: jenis -> nomor -> uraian (biar stabil)
        $this->db->order_by('jenis_anggaran', 'ASC');
        $this->db->order_by('nomor_prk', 'ASC');
        $this->db->order_by('uraian_prk', 'ASC');

        $this->db->limit((int)$limit, (int)$offset);
        return $this->db->get()->result_array();
    }

    /**
     * Karena view tidak punya primary key numeric,
     * detail pakai (jenis_anggaran, nomor_prk, uraian_prk)
     */
    public function get_detail($jenis_anggaran, $nomor_prk, $uraian_prk)
    {
        return $this->db->from($this->view)
            ->where('jenis_anggaran', $jenis_anggaran)
            ->where('nomor_prk', $nomor_prk)
            ->where('uraian_prk', $uraian_prk)
            ->limit(1)
            ->get()
            ->row_array();
    }

    public function get_jenis_anggaran_list()
    {
        return $this->db->select('jenis_anggaran')
            ->from($this->view)
            ->group_by('jenis_anggaran')
            ->order_by('jenis_anggaran', 'ASC')
            ->get()
            ->result_array();
    }

    public function export_all($keyword = null, $jenis_anggaran = null)
    {
        $this->db->from($this->view);

        if ($jenis_anggaran && trim($jenis_anggaran) !== '') {
            $this->db->where('jenis_anggaran', $jenis_anggaran);
        }

        $this->apply_search($keyword);

        $this->db->order_by('jenis_anggaran', 'ASC');
        $this->db->order_by('nomor_prk', 'ASC');
        $this->db->order_by('uraian_prk', 'ASC');

        return $this->db->get();
    }
}
