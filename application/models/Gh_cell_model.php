<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gh_cell_model extends CI_Model
{
    private $table = 'gh_cell';

    public function get_all_gh_cell()
    {
        return $this->db->get($this->table)->result_array();
    }

    // ✅ list + search server-side
    public function get_gh_cell($limit, $offset, $search = '')
    {
        if ($search) {
            $this->db->group_start();
            $this->db->like('UNITNAME', $search);
            $this->db->or_like('SSOTNUMBER', $search);
            $this->db->or_like('DESCRIPTION', $search);
            $this->db->or_like('NAMA_LOCATION', $search);
            $this->db->or_like('LOCATION', $search);
            $this->db->or_like('VENDOR', $search);
            $this->db->or_like('MANUFACTURER', $search);
            $this->db->group_end();
        }

        $this->db->order_by('SSOTNUMBER', 'ASC');
        $this->db->limit($limit, $offset);

        return $this->db->get($this->table)->result_array();
    }

    // ✅ count + search server-side
    public function count_all_gh_cell($search = '')
    {
        if ($search) {
            $this->db->group_start();
            $this->db->like('UNITNAME', $search);
            $this->db->or_like('SSOTNUMBER', $search);
            $this->db->or_like('DESCRIPTION', $search);
            $this->db->or_like('NAMA_LOCATION', $search);
            $this->db->or_like('LOCATION', $search);
            $this->db->or_like('VENDOR', $search);
            $this->db->or_like('MANUFACTURER', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results($this->table);
    }

    // ✅ by business PK: SSOTNUMBER
    public function get_gh_cell_by_id($ssotnumber)
    {
        return $this->db->get_where($this->table, ['SSOTNUMBER' => $ssotnumber])->row_array();
    }

    public function insert_gh_cell($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update_gh_cell($ssotnumber, $data)
    {
        $this->db->where('SSOTNUMBER', $ssotnumber);
        return $this->db->update($this->table, $data);
    }

    public function delete_gh_cell($ssotnumber)
    {
        $this->db->where('SSOTNUMBER', $ssotnumber);
        return $this->db->delete($this->table);
    }
}
