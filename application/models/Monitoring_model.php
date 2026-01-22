<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring_model extends CI_Model
{
    private $view = 'vw_monitoring_final';

    // Function to apply filters to the query
    private function _apply_filters($search, $user_pengusul_filter = null, $column_filters = null)
    {
        // Filter by user_pengusul if available
        if ($user_pengusul_filter !== null && trim((string)$user_pengusul_filter) !== '') {
            $this->db->where('user_pengusul', $user_pengusul_filter);
        }

        // ✅ Column filters (AND) - whitelist untuk keamanan (mencegah injeksi kolom)
        $allowed_cols = [
            'id',
            'jenis_anggaran',
            'nomor_prk',
            'nomor_skk_io',
            'pagu_skk_io',
            'uraian_prk',
            'judul_drp',
            'drp',
            'uraian_pekerjaan',
            'user_pengusul',
            'rab_user',
            'renc_hari_kerja',
            'tgl_nd_ams',
            'nomor_nd_ams',
            'keterangan',
            'status_kontrak',
            'no_kontrak',
            'vendor',
            'tgl_kontrak',
            'end_kontrak',
            'nilai_kontrak',
            'kendala_kontrak',
            'no_rks',
            'kak',
            'metode_pengadaan',
            'harga_hpe',
            'harga_hps',
            'harga_nego',
            'tahapan_pengadaan',
            'prognosa_kontrak',
            'tahapan_pembayaran',
            'nilai_bayar',
            'tgl_tahapan',
            'jml_byr',
            'terbayar_thn_ini',
            'overpay_thn_ini',
            'ke_thn_2026',
            'terpakai_thn_ini',
            'sisa_anggaran',
            'total_bulan',
            'bulan_2025',
            'nilai_per_bulan',
            'alokasi_kontrak_thn_ini',
            'anggaran_terpakai',
            'jml_renc',
        ];

        // real_byr_bln1..12, ktrk_bln1..12, renc_byr_bln1..12
        for ($i = 1; $i <= 12; $i++) {
            $allowed_cols[] = "real_byr_bln{$i}";
            $allowed_cols[] = "ktrk_bln{$i}";
            $allowed_cols[] = "renc_byr_bln{$i}";
        }

        if (is_array($column_filters)) {
            foreach ($column_filters as $col => $val) {
                $col = trim((string)$col);
                $val = trim((string)$val);

                if ($col === '' || $val === '') continue;
                if (!in_array($col, $allowed_cols, true)) continue;

                // LIKE agar fleksibel
                $this->db->like($col, $val);
            }
        }

        // Filter by keyword across multiple columns
        if ($search && trim($search) !== '') {
            $kw = trim($search);

            $this->db->group_start()
                ->like('jenis_anggaran', $kw)
                ->or_like('nomor_prk', $kw)
                ->or_like('nomor_skk_io', $kw)
                ->or_like('uraian_prk', $kw)
                ->or_like('judul_drp', $kw)
                ->or_like('drp', $kw)
                ->or_like('uraian_pekerjaan', $kw)
                ->or_like('user_pengusul', $kw)
                ->or_like('status_kontrak', $kw)
                ->or_like('no_kontrak', $kw)
                ->or_like('vendor', $kw)
                ->or_like('no_rks', $kw)
                ->or_like('kak', $kw)
                ->or_like('metode_pengadaan', $kw)
                ->or_like('tahapan_pengadaan', $kw)
                ->or_like('nomor_nd_ams', $kw)
                ->group_end();
        }
    }

    // Count all records that match the filters
    public function count_all($search = null, $user_pengusul_filter = null, $column_filters = null)
    {
        $this->db->from($this->view);
        $this->_apply_filters($search, $user_pengusul_filter, $column_filters);
        return (int)$this->db->count_all_results();
    }

    // Get paginated data with filters applied
    public function get_paginated($limit, $offset, $search = null, $user_pengusul_filter = null, $column_filters = null)
    {
        $this->db->from($this->view);
        $this->_apply_filters($search, $user_pengusul_filter, $column_filters);

        // Sorting: Sort by 'id' descending for most recent data on top
        $this->db->order_by('id', 'DESC');

        // Limit the number of records for pagination
        $this->db->limit((int)$limit, (int)$offset);
        return $this->db->get()->result_array();
    }

    // Get all data that matches the filters
    public function get_all($search = null, $user_pengusul_filter = null, $column_filters = null)
    {
        $this->db->from($this->view);
        $this->_apply_filters($search, $user_pengusul_filter, $column_filters);

        // Sorting: Sort by 'id' descending for most recent data on top
        $this->db->order_by('id', 'DESC');

        return $this->db->get()->result_array();
    }

    // Get data by specific ID
    public function get_by_id($id, $user_pengusul_filter = null)
    {
        $this->db->from($this->view);
        $this->db->where('id', (int)$id);

        // Filter by user_pengusul if provided
        if ($user_pengusul_filter !== null && trim((string)$user_pengusul_filter) !== '') {
            $this->db->where('user_pengusul', $user_pengusul_filter);
        }

        return $this->db->get()->row_array();
    }
}
