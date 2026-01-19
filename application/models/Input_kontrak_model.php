<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Input_kontrak_model extends CI_Model
{
    private $table = 'input_kontrak'; // nama tabel sesuai database

    public function count_all($search = null)
    {
        if ($search === null || $search === '') {
            return $this->db->count_all($this->table);
        }

        // safe like using escape
        $s = '%' . $this->db->escape_like_str($search) . '%';
        $sql = "SELECT COUNT(*) as cnt FROM `" . $this->db->escape_str($this->table) . "` WHERE `USER` LIKE ? OR `REKANAN` LIKE ? OR `SKKO` LIKE ? OR `URAIAN KONTRAK / PEKERJAAN` LIKE ?";
        $q = $this->db->query($sql, [$s, $s, $s, $s]);
        $row = $q->row_array();
        return (int)($row['cnt'] ?? 0);
    }

    public function get_limit($limit, $offset, $search = null)
    {
        if ($search === null || $search === '') {
            return $this->db
                ->limit($limit, $offset)
                ->get($this->table)
                ->result_array();
        }

        $s = '%' . $this->db->escape_like_str($search) . '%';
        $sql = "SELECT * FROM `" . $this->db->escape_str($this->table) . "` WHERE `USER` LIKE ? OR `REKANAN` LIKE ? OR `SKKO` LIKE ? OR `URAIAN KONTRAK / PEKERJAAN` LIKE ? LIMIT ? OFFSET ?";
        $q = $this->db->query($sql, [$s, $s, $s, $s, (int)$limit, (int)$offset]);
        return $q->result_array();
    }

    public function get_all()
    {
        return $this->db->get($this->table)->result_array();
    }

    // Ambil data berdasarkan ID
    public function get_by_id($id)
    {
        $this->db->select("
            `ID`,
            `SUMBER DANA` AS SUMBER_DANA,
            `SKKO`,
            `SUB POS` AS SUB_POS,
            `DRP`,
            `URAIAN KONTRAK / PEKERJAAN` AS URAIAN_KONTRAK_PEKERJAAN,
            `USER`,
            `PAGU ANG/RAB USER` AS PAGU_ANG_RAB_USER,
            `KOMITMENT ND` AS KOMITMENT_ND,
            `RENC AKHIR KONTRAK` AS RENC_AKHIR_KONTRAK,
            `TGL ND/AMS` AS TGL_ND_AMS,
            `NOMOR ND / AMS` AS NOMOR_ND_AMS,
            `KETERANGAN`,
            `TAHAP KONTRAK` AS TAHAP_KONTRAK,
            `PROGNOSA`,
            `NO SPK / SPB / KONTRAK` AS NO_SPK_SPB_KONTRAK,
            `REKANAN`,
            `TGL KONTRAK` AS TGL_KONTRAK,
            `TGL AKHIR KONTRAK` AS TGL_AKHIR_KONTRAK,
            `NILAI KONTRAK TOTAL` AS NILAI_KONTRAK_TOTAL,
            `NILAI KONTRAK TAHUN BERJALAN` AS NILAI_KONTRAK_TAHUN_BERJALAN,
            `TGL BAYAR` AS TGL_BAYAR,
            `ANGGARAN TERPAKAI` AS ANGGARAN_TERPAKAI,
            `SISA ANGGARAN` AS SISA_ANGGARAN,
            `STATUS KONTRAK` AS STATUS_KONTRAK,
            `BLN KTRK1` AS BLN_KTRK1,
            `BLN KTRK2` AS BLN_KTRK2,
            `BLN KTRK3` AS BLN_KTRK3,
            `BLN KTRK4` AS BLN_KTRK4,
            `BLN KTRK5` AS BLN_KTRK5,
            `BLN KTRK6` AS BLN_KTRK6,
            `BLN KTRK7` AS BLN_KTRK7,
            `BLN KTRK8` AS BLN_KTRK8,
            `BLN KTRK9` AS BLN_KTRK9,
            `BLN KTRK10` AS BLN_KTRK10,
            `BLN KTRK11` AS BLN_KTRK11,
            `BLN KTRK12` AS BLN_KTRK12,
            `BULAN RENC BAYAR` AS BULAN_RENC_BAYAR,
            `BULAN BAYAR` AS BULAN_BAYAR
        ");
        $this->db->from($this->table);
        $this->db->where('ID', $id);
        return $this->db->get()->row_array();
    }

    public function update($id, $data)
    {
        return $this->db->where('ID', $id)->update($this->table, $data);
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // Insert to specific table (anggaran_op or anggaran_inv)
    public function insert_to_table($table_name, $data)
    {
        return $this->db->insert($table_name, $data);
    }

    // Get data from specific table
    public function get_from_table($table_name, $limit = null, $offset = null)
    {
        if ($limit && $offset !== null) {
            return $this->db->limit($limit, $offset)->get($table_name)->result_array();
        }
        return $this->db->get($table_name)->result_array();
    }

    // Count from specific table
    public function count_from_table($table_name)
    {
        return $this->db->count_all($table_name);
    }
}
