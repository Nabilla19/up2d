<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembangkit_model extends CI_Model
{
    private $table = 'pembangkit';

    public function get_all_pembangkit()
    {
        return $this->db->get($this->table)->result_array();
    }

    // ✅ get list + search (server-side)
    public function get_pembangkit($limit, $offset, $search = '')
    {
        if ($search) {
            $this->db->group_start();
            $this->db->like('UNIT_LAYANAN', $search);
            $this->db->or_like('PEMBANGKIT', $search);
            $this->db->or_like('STATUS_SCADA', $search);
            $this->db->or_like('MERK_RTU', $search);
            $this->db->or_like('IP_RTU', $search);
            $this->db->or_like('IP_GATEWAY', $search);
            $this->db->group_end();
        }

        $this->db->order_by('ID_PEMBANGKIT', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get($this->table)->result_array();
    }

    // ✅ count list + search (server-side)
    public function count_all_pembangkit($search = '')
    {
        if ($search) {
            $this->db->group_start();
            $this->db->like('UNIT_LAYANAN', $search);
            $this->db->or_like('PEMBANGKIT', $search);
            $this->db->or_like('STATUS_SCADA', $search);
            $this->db->or_like('MERK_RTU', $search);
            $this->db->or_like('IP_RTU', $search);
            $this->db->or_like('IP_GATEWAY', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results($this->table);
    }

    public function get_pembangkit_by_id($id)
    {
        return $this->db->get_where($this->table, ['ID_PEMBANGKIT' => $id])->row_array();
    }

    public function insert_pembangkit($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update_pembangkit($id, $data)
    {
        $this->db->where('ID_PEMBANGKIT', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete_pembangkit($id)
    {
        $this->db->where('ID_PEMBANGKIT', $id);
        return $this->db->delete($this->table);
    }
}
