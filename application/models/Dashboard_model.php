<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        // database sudah autoload
    }

    /**
     * Hitung jumlah PRK aktif (gabungan)
     * Diambil dari vw_rkp_prk dengan kondisi:
     * - renc_kontrak > 0 OR kontrak > 0 OR rencana_bayar > 0 OR terbayar > 0
     */
    public function get_prk_aktif()
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM vw_rkp_prk
            WHERE
                COALESCE(renc_kontrak,0) > 0
                OR COALESCE(kontrak,0) > 0
                OR COALESCE(rencana_bayar,0) > 0
                OR COALESCE(terbayar,0) > 0
        ";
        $row = $this->db->query($sql)->row_array();
        return isset($row['total']) ? (int)$row['total'] : 0;
    }

    /**
     * SUM kolom tertentu dari vw_rkp_prk berdasarkan jenis_anggaran
     * jenis_anggaran: 'OPERASI' atau 'INVESTASI'
     * field yang diizinkan: kontrak, terbayar, rencana_bayar, sisa
     */
    public function sum_vw_rkp_prk_by_jenis_anggaran($jenis_anggaran, $field)
    {
        $allowed_fields = array('kontrak', 'terbayar', 'rencana_bayar', 'sisa');
        if (!in_array($field, $allowed_fields, true)) {
            return 0.0;
        }

        $sql = "
            SELECT COALESCE(SUM(COALESCE($field,0)),0) AS total
            FROM vw_rkp_prk
            WHERE UPPER(jenis_anggaran) = ?
        ";
        $row = $this->db->query($sql, array(strtoupper($jenis_anggaran)))->row_array();
        return isset($row['total']) ? (float)$row['total'] : 0.0;
    }

    /**
     * Ambil TERBAYAR bulanan (Jan-Dec 2025) per jenis_anggaran
     * Sumber data: vw_prognosa
     * Filter: rekap = 'TERBAYAR' dan jenis_anggaran = 'OPERASI' / 'INVESTASI'
     *
     * return array 12 angka sesuai urutan bulan
     */
    public function get_terbayar_bulanan_2025_by_jenis_anggaran($jenis_anggaran)
    {
        $sql = "
            SELECT
                COALESCE(SUM(COALESCE(jan_25,0)),0) AS jan,
                COALESCE(SUM(COALESCE(feb_25,0)),0) AS feb,
                COALESCE(SUM(COALESCE(mar_25,0)),0) AS mar,
                COALESCE(SUM(COALESCE(apr_25,0)),0) AS apr,
                COALESCE(SUM(COALESCE(mei_25,0)),0) AS mei,
                COALESCE(SUM(COALESCE(jun_25,0)),0) AS jun,
                COALESCE(SUM(COALESCE(jul_25,0)),0) AS jul,
                COALESCE(SUM(COALESCE(aug_25,0)),0) AS aug,
                COALESCE(SUM(COALESCE(sep_25,0)),0) AS sep,
                COALESCE(SUM(COALESCE(okt_25,0)),0) AS okt,
                COALESCE(SUM(COALESCE(nov_25,0)),0) AS nov,
                COALESCE(SUM(COALESCE(des_25,0)),0) AS des
            FROM vw_prognosa
            WHERE UPPER(rekap) = 'TERBAYAR'
              AND UPPER(jenis_anggaran) = ?
        ";
        $row = $this->db->query($sql, array(strtoupper($jenis_anggaran)))->row_array();

        return array(
            isset($row['jan']) ? (float)$row['jan'] : 0.0,
            isset($row['feb']) ? (float)$row['feb'] : 0.0,
            isset($row['mar']) ? (float)$row['mar'] : 0.0,
            isset($row['apr']) ? (float)$row['apr'] : 0.0,
            isset($row['mei']) ? (float)$row['mei'] : 0.0,
            isset($row['jun']) ? (float)$row['jun'] : 0.0,
            isset($row['jul']) ? (float)$row['jul'] : 0.0,
            isset($row['aug']) ? (float)$row['aug'] : 0.0,
            isset($row['sep']) ? (float)$row['sep'] : 0.0,
            isset($row['okt']) ? (float)$row['okt'] : 0.0,
            isset($row['nov']) ? (float)$row['nov'] : 0.0,
            isset($row['des']) ? (float)$row['des'] : 0.0
        );
    }

    /**
     * Rekap Anggaran per Jenis (untuk tabel)
     * Sumber data: vw_rkp_prk
     *
     * Kolom:
     * - jenis_anggaran
     * - pagu_skk_io
     * - kontrak
     * - terbayar
     * - sisa
     */
    public function get_rekap_anggaran_per_jenis()
    {
        $sql = "
            SELECT
                jenis_anggaran,
                COALESCE(SUM(COALESCE(pagu_skk_io,0)),0) AS pagu,
                COALESCE(SUM(COALESCE(kontrak,0)),0)     AS terkontrak,
                COALESCE(SUM(COALESCE(terbayar,0)),0)    AS terbayar,
                COALESCE(SUM(COALESCE(sisa,0)),0)        AS sisa
            FROM vw_rkp_prk
            GROUP BY jenis_anggaran
            ORDER BY jenis_anggaran ASC
        ";
        return $this->db->query($sql)->result_array();
    }
}
