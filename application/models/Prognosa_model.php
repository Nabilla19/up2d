<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prognosa_model extends CI_Model
{
    // view yang kamu punya di DB
    private $view = 'vw_prognosa';

    private function _apply_filter($jenis, $rekap, $search)
    {
        if ($jenis !== '') {
            // Use COLLATE to avoid collation mismatch
            $this->db->where("jenis_anggaran COLLATE utf8mb4_general_ci = ", $jenis);
        }
        if ($rekap !== '') {
            $this->db->where("rekap COLLATE utf8mb4_general_ci = ", $rekap);
        }

        if ($search !== '') {
            $kw = $this->db->escape_like_str(trim($search));
            $this->db->where("(jenis_anggaran COLLATE utf8mb4_general_ci LIKE '%{$kw}%' OR rekap COLLATE utf8mb4_general_ci LIKE '%{$kw}%')", NULL, FALSE);
        }
    }

    public function count_all($jenis = '', $rekap = '', $search = '')
    {
        // Use raw SQL to avoid collation issues
        $where_clauses = [];
        $params = [];
        
        if ((string)$jenis !== '') {
            $where_clauses[] = "jenis_anggaran COLLATE utf8mb4_general_ci = ?";
            $params[] = $jenis;
        }
        
        if ((string)$rekap !== '') {
            $where_clauses[] = "rekap COLLATE utf8mb4_general_ci = ?";
            $params[] = $rekap;
        }
        
        if ((string)$search !== '') {
            $kw = '%' . $search . '%';
            $where_clauses[] = "(jenis_anggaran COLLATE utf8mb4_general_ci LIKE ? OR rekap COLLATE utf8mb4_general_ci LIKE ?)";
            $params[] = $kw;
            $params[] = $kw;
        }
        
        $where_sql = empty($where_clauses) ? '' : 'WHERE ' . implode(' AND ', $where_clauses);
        $sql = "SELECT COUNT(*) as total FROM {$this->view} {$where_sql}";
        
        $query = $this->db->query($sql, $params);
        $result = $query->row_array();
        return (int)($result['total'] ?? 0);
    }

    public function get_paginated($limit, $offset, $jenis = '', $rekap = '', $search = '', $sort_by = '', $sort_dir = '')
    {
        // Use raw SQL to avoid collation issues with CodeIgniter query builder
        $where_clauses = [];
        $params = [];
        
        if ((string)$jenis !== '') {
            $where_clauses[] = "jenis_anggaran COLLATE utf8mb4_general_ci = ?";
            $params[] = $jenis;
        }
        
        if ((string)$rekap !== '') {
            $where_clauses[] = "rekap COLLATE utf8mb4_general_ci = ?";
            $params[] = $rekap;
        }
        
        if ((string)$search !== '') {
            $kw = '%' . $search . '%';
            $where_clauses[] = "(jenis_anggaran COLLATE utf8mb4_general_ci LIKE ? OR rekap COLLATE utf8mb4_general_ci LIKE ?)";
            $params[] = $kw;
            $params[] = $kw;
        }
        
        $where_sql = empty($where_clauses) ? '' : 'WHERE ' . implode(' AND ', $where_clauses);
        
        // Whitelist sort
        $allowed = ['jenis_anggaran', 'rekap', 'jan_25', 'feb_25', 'mar_25', 'apr_25', 'mei_25', 'jun_25', 'jul_25', 'aug_25', 'sep_25', 'okt_25', 'nov_25', 'des_25'];
        $sort_by = in_array($sort_by, $allowed) ? $sort_by : 'jenis_anggaran';
        $sort_dir = in_array(strtoupper($sort_dir), ['ASC', 'DESC']) ? strtoupper($sort_dir) : 'ASC';
        
        $sql = "SELECT * FROM {$this->view} {$where_sql} ORDER BY {$sort_by} {$sort_dir}, rekap ASC LIMIT ? OFFSET ?";
        $params[] = (int)$limit;
        $params[] = (int)$offset;
        
        $query = $this->db->query($sql, $params);
        return $query->result_array();
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
