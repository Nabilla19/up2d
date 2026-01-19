<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prognosa_model extends CI_Model
{
    // view yang kamu punya di DB
    private $view = 'vw_prognosa';

    private function _apply_filter($jenis, $rekap, $keyword)
    {
        if ($jenis !== '') {
            $this->db->where('jenis_anggaran', $jenis);
        }
        if ($rekap !== '') {
            $this->db->where('rekap', $rekap);
        }

        if ($keyword !== '') {
            $kw = trim($keyword);
            $this->db->group_start()
                ->like('jenis_anggaran', $kw)
                ->or_like('rekap', $kw)
                ->group_end();
        }
    }

    public function count_all($jenis = '', $rekap = '', $keyword = '')
    {
        $this->db->from($this->view);
        $this->_apply_filter((string)$jenis, (string)$rekap, (string)$keyword);
        return (int)$this->db->count_all_results();
    }

    public function get_paginated($limit, $offset, $jenis = '', $rekap = '', $keyword = '', $sort_by = '', $sort_dir = '')
    {
        $this->db->from($this->view);
        $this->_apply_filter((string)$jenis, (string)$rekap, (string)$keyword);

        // whitelist sort kolom agar aman + tidak error trim(null)
        $allowed = [
            'jenis_anggaran',
            'rekap',
            'jan_25',
            'feb_25',
            'mar_25',
            'apr_25',
            'mei_25',
            'jun_25',
            'jul_25',
            'aug_25',
            'sep_25',
            'okt_25',
            'nov_25',
            'des_25'
        ];

        $sort_by  = (string)($sort_by ?? '');
        $sort_dir = strtoupper((string)($sort_dir ?? ''));

        if (!in_array($sort_by, $allowed, true)) {
            $sort_by = 'jenis_anggaran';
        }
        if (!in_array($sort_dir, ['ASC', 'DESC'], true)) {
            $sort_dir = 'ASC';
        }

        $this->db->order_by($sort_by, $sort_dir);
        $this->db->order_by('rekap', 'ASC');

        $this->db->limit((int)$limit, (int)$offset);
        return $this->db->get()->result_array();
    }

    public function get_jenis_list()
    {
        return $this->db
            ->select('jenis_anggaran')
            ->from($this->view)
            ->group_by('jenis_anggaran')
            ->order_by('jenis_anggaran', 'ASC')
            ->get()
            ->result_array();
    }

    public function get_rekap_list($jenis = '')
    {
        $this->db->select('rekap')
            ->from($this->view);

        if ((string)$jenis !== '') {
            $this->db->where('jenis_anggaran', $jenis);
        }

        return $this->db
            ->group_by('rekap')
            ->order_by('rekap', 'ASC')
            ->get()
            ->result_array();
    }

    public function get_one($jenis, $rekap)
    {
        return $this->db
            ->from($this->view)
            ->where('jenis_anggaran', (string)$jenis)
            ->where('rekap', (string)$rekap)
            ->limit(1)
            ->get()
            ->row_array();
    }
}
