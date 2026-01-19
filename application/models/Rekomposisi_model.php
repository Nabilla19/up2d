<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rekomposisi_model extends CI_Model
{
    protected $table = 'master_rekomposisi';

    /* =========================
       SANITIZE ANGKA (1.234.567 -> 1234567)
       ========================= */
    private function _to_number($str)
    {
        if ($str === null) return 0;
        // buang semua kecuali digit
        $num = preg_replace('/[^\d]/', '', (string)$str);
        return $num === '' ? 0 : (float)$num;
    }

    /* =========================
       FILTER SEARCH
       ========================= */
    private function _apply_search($keyword)
    {
        if ($keyword && trim($keyword) !== '') {
            $kw = trim($keyword);

            $this->db->group_start()
                ->like('jenis_anggaran', $kw)
                ->or_like('nomor_prk', $kw)
                ->or_like('nomor_skk_io', $kw)
                ->or_like('uraian_prk', $kw)
                ->or_like('judul_drp', $kw)
                ->group_end();
        }
    }

    /* =========================
       COUNT ALL (UNTUK PAGINATION)
       ========================= */
    public function count_all($keyword = null)
    {
        $this->db->from($this->table);
        $this->_apply_search($keyword);
        return (int)$this->db->count_all_results();
    }

    /* =========================
       GET PAGINATED
       ========================= */
    public function get_paginated($limit, $offset, $keyword = null)
    {
        $this->db->from($this->table);
        $this->_apply_search($keyword);
        $this->db->order_by('id', 'DESC');
        $this->db->limit((int)$limit, (int)$offset);
        return $this->db->get()->result_array();
    }

    /* =========================
       GET BY ID
       ========================= */
    public function get_by_id($id)
    {
        return $this->db
            ->where('id', (int)$id)
            ->get($this->table)
            ->row_array();
    }

    /* =========================
       INSERT
       ========================= */
    public function insert($data)
    {
        // normalize angka
        if (isset($data['pagu_skk_io'])) {
            $data['pagu_skk_io'] = $this->_to_number($data['pagu_skk_io']);
        }
        return $this->db->insert($this->table, $data);
    }

    /* =========================
       UPDATE
       ========================= */
    public function update($id, $data)
    {
        if (isset($data['pagu_skk_io'])) {
            $data['pagu_skk_io'] = $this->_to_number($data['pagu_skk_io']);
        }
        return $this->db
            ->where('id', (int)$id)
            ->update($this->table, $data);
    }

    /* =========================
       DELETE
       ========================= */
    public function delete($id)
    {
        return $this->db
            ->where('id', (int)$id)
            ->delete($this->table);
    }

    /* =========================
       VALIDASI UNIQUE (nomor_prk, judul_drp)
       ========================= */
    public function exists_prk_drp($nomor_prk, $judul_drp, $exclude_id = null)
    {
        $this->db->from($this->table)
            ->where('nomor_prk', $nomor_prk)
            ->where('judul_drp', $judul_drp);

        if ($exclude_id !== null) {
            $this->db->where('id !=', (int)$exclude_id);
        }

        return $this->db->count_all_results() > 0;
    }

    /* =========================
       CEK DIPAKAI DI ENTRY KONTRAK
       (mengunci edit/hapus PRK+DRP)
       ========================= */
    public function is_used_in_kontrak($nomor_prk, $judul_drp)
    {
        return $this->db
            ->from('entry_kontrak')
            ->where('nomor_prk', $nomor_prk)
            ->where('judul_drp', $judul_drp)
            ->limit(1)
            ->count_all_results() > 0;
    }

    /* =========================
       DROPDOWN ENTRY KONTRAK
       ========================= */

    // Jenis Anggaran
    public function get_jenis_anggaran()
    {
        return $this->db
            ->select('jenis_anggaran')
            ->from($this->table)
            ->group_by('jenis_anggaran')
            ->order_by('jenis_anggaran', 'ASC')
            ->get()
            ->result_array();
    }

    // PRK by Jenis (distinct nomor_prk + uraian_prk)
    public function get_prk_by_jenis($jenis)
    {
        return $this->db
            ->select('nomor_prk, uraian_prk')
            ->from($this->table)
            ->where('jenis_anggaran', $jenis)
            ->group_by(['nomor_prk', 'uraian_prk'])
            ->order_by('nomor_prk', 'ASC')
            ->get()
            ->result_array();
    }

    // Detail PRK (ambil data PRK utama; judul drp bisa beda-beda)
    public function get_prk_detail($nomor_prk)
    {
        return $this->db
            ->select('jenis_anggaran, nomor_prk, nomor_skk_io, uraian_prk, pagu_skk_io')
            ->from($this->table)
            ->where('nomor_prk', $nomor_prk)
            ->order_by('id', 'ASC')
            ->limit(1)
            ->get()
            ->row_array();
    }

    // DRP by PRK
    public function get_drp_by_prk($nomor_prk)
    {
        return $this->db
            ->select('judul_drp')
            ->from($this->table)
            ->where('nomor_prk', $nomor_prk)
            ->group_by('judul_drp')
            ->order_by('judul_drp', 'ASC')
            ->get()
            ->result_array();
    }

    /* =========================
       TAMBAHAN UNTUK ENTRY KONTRAK (AMAN FK PRK+DRP)
       ========================= */

    // Alias (opsional) kalau ada controller lama yang pakai nama ini
    public function get_prk_by_jenis_distinct($jenis)
    {
        return $this->get_prk_by_jenis($jenis);
    }

    // Detail by PRK + DRP (paling aman untuk dropdown & FK)
    public function get_prk_drp_detail($nomor_prk, $judul_drp)
    {
        return $this->db
            ->select('jenis_anggaran, nomor_prk, nomor_skk_io, uraian_prk, pagu_skk_io, judul_drp')
            ->from($this->table)
            ->where('nomor_prk', $nomor_prk)
            ->where('judul_drp', $judul_drp)
            ->limit(1)
            ->get()
            ->row_array();
    }
}
