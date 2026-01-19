<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_data_kontrak extends CI_Model
{
    private $table = 'kontrak';

    // ================================
    // GET ALL KONTRAK + JOIN MASTER
    // ================================
    public function get_all()
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
        $this->db->order_by('kontrak.id', 'DESC');

        return $this->db->get()->result();
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
