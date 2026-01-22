<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_data_kontrak extends CI_Model
{
    private $table = 'kontrak';

    // ================================
    // GET ALL KONTRAK + JOIN MASTER
    // ================================
    public function get_all($search = null)
    {
        $this->db->select("
            kontrak.*,
            ja.nama AS jenis_anggaran_nama,
            prk.nomor_prk,
            prk.uraian_prk,
            drp.judul_drp,
            skk.nomor_skk_io,
            skk.pagu_skk_io
        ");
        $this->db->from($this->table);
        $this->db->join('jenis_anggaran ja', 'ja.id = kontrak.jenis_anggaran_id', 'left');
        $this->db->join('prk', 'prk.id = kontrak.prk_id', 'left');
        $this->db->join('drp', 'drp.id = kontrak.drp_id', 'left');
        $this->db->join('skk', 'skk.id = kontrak.skk_id', 'left');
        
        $this->_apply_search($search);
        
        $this->db->order_by('kontrak.id', 'DESC');

        return $this->db->get()->result();
    }

    public function count_all($search = null)
    {
        $this->db->from($this->table);
        $this->db->join('jenis_anggaran ja', 'ja.id = kontrak.jenis_anggaran_id', 'left');
        $this->db->join('prk', 'prk.id = kontrak.prk_id', 'left');
        $this->db->join('drp', 'drp.id = kontrak.drp_id', 'left');
        $this->db->join('skk', 'skk.id = kontrak.skk_id', 'left');
        
        $this->_apply_search($search);
        
        return $this->db->count_all_results();
    }

    public function get_limit($limit, $offset, $search = null)
    {
        $this->db->select("
            kontrak.*,
            ja.nama AS jenis_anggaran_nama,
            prk.nomor_prk,
            prk.uraian_prk,
            drp.judul_drp,
            skk.nomor_skk_io,
            skk.pagu_skk_io
        ");
        $this->db->from($this->table);
        $this->db->join('jenis_anggaran ja', 'ja.id = kontrak.jenis_anggaran_id', 'left');
        $this->db->join('prk', 'prk.id = kontrak.prk_id', 'left');
        $this->db->join('drp', 'drp.id = kontrak.drp_id', 'left');
        $this->db->join('skk', 'skk.id = kontrak.skk_id', 'left');
        
        $this->_apply_search($search);
        
        $this->db->order_by('kontrak.id', 'DESC');
        $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }

    private function _apply_search($search = null)
    {
        if ($search && trim($search) !== '') {
            $kw = trim($search);

            $this->db->group_start()
                ->like('kontrak.jenis_anggaran_text', $kw)
                ->or_like('kontrak.nomor_prk_text', $kw)
                ->or_like('kontrak.uraian_prk_text', $kw)
                ->or_like('kontrak.nomor_skk_io_text', $kw)
                ->or_like('kontrak.drp_text', $kw)
                ->or_like('kontrak.uraian_pekerjaan', $kw)
                ->or_like('kontrak.user_pengusul', $kw)
                ->or_like('kontrak.status_kontrak', $kw)
                ->or_like('kontrak.no_kontrak', $kw)
                ->or_like('kontrak.pelaksana_vendor', $kw)
                ->or_like('ja.nama', $kw)
                ->or_like('prk.nomor_prk', $kw)
                ->or_like('prk.uraian_prk', $kw)
                ->or_like('drp.judul_drp', $kw)
                ->or_like('skk.nomor_skk_io', $kw)
                ->group_end();
        }
    }

    // ================================
    // GET DETAIL KONTRAK BY ID
    // ================================
    public function get_by_id($id)
    {
        $this->db->select("
            kontrak.*,
            ja.nama AS jenis_anggaran_nama,
            prk.nomor_prk,
            prk.uraian_prk,
            drp.judul_drp,
            skk.nomor_skk_io,
            skk.pagu_skk_io
        ");
        $this->db->from('kontrak');
        $this->db->join('jenis_anggaran ja', 'ja.id = kontrak.jenis_anggaran_id', 'left');
        $this->db->join('prk', 'prk.id = kontrak.prk_id', 'left');
        $this->db->join('drp', 'drp.id = kontrak.drp_id', 'left');
        $this->db->join('skk', 'skk.id = kontrak.skk_id', 'left');
        $this->db->where('kontrak.id', (int)$id);

        return $this->db->get()->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id', (int)$id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', (int)$id)->delete($this->table);
    }

    // ================================
    // MASTER DROPDOWN
    // ================================
    public function get_jenis_anggaran()
    {
        return $this->db->order_by('id', 'ASC')->get('jenis_anggaran')->result();
    }

    // JSON format: [{id, label}]
    public function get_prk_by_jenis($jenis_id)
    {
        $this->db->select("id, CONCAT(nomor_prk,' - ',uraian_prk) AS label");
        $this->db->from('prk');
        $this->db->where('jenis_anggaran_id', (int)$jenis_id);
        $this->db->order_by('nomor_prk', 'ASC');
        return $this->db->get()->result();
    }

    public function get_skk_by_prk($prk_id)
    {
        $this->db->select("id, CONCAT(nomor_skk_io,' (',pagu_skk_io,')') AS label");
        $this->db->from('skk');
        $this->db->where('prk_id', (int)$prk_id);
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result();
    }

    public function get_drp_by_prk($prk_id)
    {
        $this->db->select("id, judul_drp AS label");
        $this->db->from('drp');
        $this->db->where('prk_id', (int)$prk_id);
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result();
    }

    // dipakai controller untuk ambil nomor_skk_io (infer tahun)
    public function get_skk_row($skk_id)
    {
        return $this->db->get_where('skk', ['id' => (int)$skk_id])->row();
    }
}
