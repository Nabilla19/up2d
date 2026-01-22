<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Entry_kontrak_model extends CI_Model
{
    protected $table = 'entry_kontrak';

    private function _to_number($val)
    {
        if ($val === null) return 0;
        $num = preg_replace('/[^\d]/', '', (string)$val);
        return $num === '' ? 0 : (float)$num;
    }

    private function _apply_search($search)
    {
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

    /**
     * stage:
     * - originator: hanya miliknya sendiri
     * - perencanaan: ND belum lengkap
     * - pengadaan: ND lengkap (tetap tampil meskipun kontrak sudah lengkap)
     * - kku: ND lengkap + (no_kontrak + vendor + tgl_kontrak) sudah terisi
     * - admin: semua
     */
    private function _apply_stage_filter($stage, $origin_label = null)
    {
        $stage = strtolower(trim((string)$stage));

        if ($stage === 'admin') {
            return;
        }

        if ($stage === 'originator') {
            $this->db->where('user_pengusul', (string)$origin_label);
            return;
        }

        if ($stage === 'perencanaan') {
            $this->db->group_start()
                ->where('(nomor_nd_ams IS NULL OR nomor_nd_ams = "")', null, false)
                ->or_where('(tgl_nd_ams IS NULL OR tgl_nd_ams = 0)', null, false)
                ->group_end();
            return;
        }

        if ($stage === 'pengadaan') {
            // ND harus lengkap
            $this->db->where('nomor_nd_ams IS NOT NULL', null, false);
            $this->db->where('nomor_nd_ams <>', '');
            $this->db->where('tgl_nd_ams IS NOT NULL', null, false);
            $this->db->where('tgl_nd_ams <> 0', null, false);

            // TIDAK ada filter "kontrak belum lengkap" agar data tetap berada di list pengadaan
            return;
        }

        if ($stage === 'kku') {
            // ND harus lengkap
            $this->db->where('nomor_nd_ams IS NOT NULL', null, false);
            $this->db->where('nomor_nd_ams <>', '');
            $this->db->where('tgl_nd_ams IS NOT NULL', null, false);
            $this->db->where('tgl_nd_ams <> 0', null, false);

            // Syarat masuk KKU:
            // no_kontrak + vendor + tgl_kontrak sudah terisi
            $this->db->where('no_kontrak IS NOT NULL', null, false);
            $this->db->where('no_kontrak <>', '');
            $this->db->where('vendor IS NOT NULL', null, false);
            $this->db->where('vendor <>', '');
            $this->db->where('tgl_kontrak IS NOT NULL', null, false);
            $this->db->where('tgl_kontrak <> 0', null, false);

            return;
        }
    }

    /**
     * ORDERING RULE (PERMINTAAN ANDA):
     * - Pengadaan: data "baru masuk / belum diisi pengadaan" muncul paling atas.
     *   Kriteria "belum diisi pengadaan" = salah satu dari (no_kontrak/vendor/tgl_kontrak) masih kosong.
     *
     * - KKU: data "baru masuk / belum diisi KKU" muncul paling atas.
     *   Kriteria "belum diisi KKU" = tahapan_pembayaran kosong ATAU tgl_tahapan kosong.
     *
     * - Lainnya: default id DESC
     */
    private function _apply_order_by($stage)
    {
        $stage = strtolower(trim((string)$stage));

        if ($stage === 'pengadaan') {
            // Incomplete procurement first (0), complete later (1)
            $this->db->order_by(
                "CASE 
                    WHEN (no_kontrak IS NULL OR no_kontrak = '' OR vendor IS NULL OR vendor = '' OR tgl_kontrak IS NULL OR tgl_kontrak = 0)
                    THEN 0 ELSE 1
                 END",
                "ASC",
                false
            );
            // data terbaru (yang baru berubah/masuk) di atas
            $this->db->order_by("COALESCE(updated_at, created_at)", "DESC", false);
            $this->db->order_by("id", "DESC");
            return;
        }

        if ($stage === 'kku') {
            // Incomplete KKU first (0), complete later (1)
            $this->db->order_by(
                "CASE
                    WHEN (tahapan_pembayaran IS NULL OR tahapan_pembayaran = '' OR tgl_tahapan IS NULL OR tgl_tahapan = 0)
                    THEN 0 ELSE 1
                 END",
                "ASC",
                false
            );
            $this->db->order_by("COALESCE(updated_at, created_at)", "DESC", false);
            $this->db->order_by("id", "DESC");
            return;
        }

        // default: terbaru by id
        $this->db->order_by('id', 'DESC');
    }

    public function count_for_role($role_raw, $role_label, $search = null)
    {
        $r = strtolower(trim((string)$role_raw));

        if ($r === 'admin' || $r === 'administrator') {
            return $this->count_all_filtered($search, 'admin', null);
        }
        if ($r === 'perencanaan') {
            return $this->count_all_filtered($search, 'perencanaan', null);
        }
        if ($r === 'pengadaan' || $r === 'pengadaan keuangan') {
            return $this->count_all_filtered($search, 'pengadaan', null);
        }
        if ($r === 'kku') {
            return $this->count_all_filtered($search, 'kku', null);
        }

        return $this->count_all_filtered($search, 'originator', $role_label);
    }

    public function get_for_role($role_raw, $role_label, $limit, $offset, $search = null)
    {
        $r = strtolower(trim((string)$role_raw));

        if ($r === 'admin' || $r === 'administrator') {
            return $this->get_paginated_filtered($limit, $offset, $search, 'admin', null);
        }
        if ($r === 'perencanaan') {
            return $this->get_paginated_filtered($limit, $offset, $search, 'perencanaan', null);
        }
        if ($r === 'pengadaan' || $r === 'pengadaan keuangan') {
            return $this->get_paginated_filtered($limit, $offset, $search, 'pengadaan', null);
        }
        if ($r === 'kku') {
            return $this->get_paginated_filtered($limit, $offset, $search, 'kku', null);
        }

        return $this->get_paginated_filtered($limit, $offset, $search, 'originator', $role_label);
    }

    public function count_all_filtered($search = null, $stage = 'admin', $origin_label = null)
    {
        $this->db->from($this->table);
        $this->_apply_stage_filter($stage, $origin_label);
        $this->_apply_search($search);
        return (int)$this->db->count_all_results();
    }

    public function get_paginated_filtered($limit, $offset, $search = null, $stage = 'admin', $origin_label = null)
    {
        $this->db->from($this->table);
        $this->_apply_stage_filter($stage, $origin_label);
        $this->_apply_search($search);
        $this->_apply_order_by($stage);
        $this->db->limit((int)$limit, (int)$offset);
        return $this->db->get()->result_array();
    }

    // ✅ Untuk EXPORT: ambil SEMUA data sesuai list (tanpa pagination)
    public function get_all_filtered($search = null, $stage = 'admin', $origin_label = null)
    {
        $this->db->from($this->table);
        $this->_apply_stage_filter($stage, $origin_label);
        $this->_apply_search($search);
        $this->_apply_order_by($stage);
        return $this->db->get()->result_array();
    }

    public function get_by_id($id)
    {
        return $this->db->where('id', (int)$id)->get($this->table)->row_array();
    }

    private function _normalize(&$data)
    {
        $numeric_fields = [
            'pagu_skk_io',
            'rab_user',
            'nilai_kontrak',
            'nilai_bayar',
            'anggaran_terpakai',
            'harga_hpe',
            'harga_hps',
            'harga_nego',
            'real_byr_bln1',
            'real_byr_bln2',
            'real_byr_bln3',
            'real_byr_bln4',
            'real_byr_bln5',
            'real_byr_bln6',
            'real_byr_bln7',
            'real_byr_bln8',
            'real_byr_bln9',
            'real_byr_bln10',
            'real_byr_bln11',
            'real_byr_bln12'
        ];

        foreach ($numeric_fields as $f) {
            if (!isset($data[$f]) || $data[$f] === null || $data[$f] === '') {
                $data[$f] = 0;
            } else {
                $data[$f] = $this->_to_number($data[$f]);
            }
        }

        $date_fields = ['tgl_kontrak', 'end_kontrak', 'tgl_tahapan', 'tgl_nd_ams', 'prognosa_kontrak'];
        foreach ($date_fields as $df) {
            if (!array_key_exists($df, $data)) continue;
            if ($data[$df] === '' || $data[$df] === '0000-00-00') {
                $data[$df] = null;
            }
        }
    }

    public function insert($data)
    {
        $this->_normalize($data);
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $this->_normalize($data);
        return $this->db->where('id', (int)$id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', (int)$id)->delete($this->table);
    }
}
