<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gardu_hubung_model extends CI_Model
{
    private $table = 'gh'; // Nama tabel di database

    public function get_all_gardu_hubung()
    {
        return $this->db->get($this->table)->result_array();
    }

    // ✅ LIST + SEARCH
    public function get_gardu_hubung($limit, $offset, $search = '')
    {
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('UNITNAME_UP3', $search);
            $this->db->or_like('UNITNAME', $search);
            $this->db->or_like('SSOTNUMBER', $search);
            $this->db->or_like('DESCRIPTION', $search);
            $this->db->or_like('CITY', $search);
            $this->db->group_end();
        }

        $this->db->limit($limit, $offset);
        return $this->db->get($this->table)->result_array();
    }

    // ✅ COUNT + SEARCH (ini yang bikin pagination ikut hasil search)
    public function count_all_gardu_hubung($search = '')
    {
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('UNITNAME_UP3', $search);
            $this->db->or_like('UNITNAME', $search);
            $this->db->or_like('SSOTNUMBER', $search);
            $this->db->or_like('DESCRIPTION', $search);
            $this->db->or_like('CITY', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results($this->table);
    }

    public function get_gardu_hubung_by_id($ssotnumber)
    {
        return $this->db->get_where($this->table, ['SSOTNUMBER' => $ssotnumber])->row_array();
    }

    public function insert_gardu_hubung($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update_gardu_hubung($ssotnumber, $data)
    {
        $this->db->where('SSOTNUMBER', $ssotnumber);
        return $this->db->update($this->table, $data);
    }

    public function delete_gardu_hubung($ssotnumber)
    {
        $this->db->where('SSOTNUMBER', $ssotnumber);
        return $this->db->delete($this->table);
    }
}
