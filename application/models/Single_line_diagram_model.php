<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Single_line_diagram_model extends CI_Model
{
    private $table = 'single_line_diagram'; // huruf kecil sesuai database

    // ===============================
    // Ambil semua SLD + nama unit
    // ===============================
    public function get_all_sld()
    {
        $this->db->select('sld.*, u.UNIT_PELAKSANA AS NAMA_UNIT');
        $this->db->from($this->table . ' AS sld');
        $this->db->join('unit AS u', 'sld.ID_UNIT = u.ID_UNIT', 'left');
        $this->db->order_by('sld.CREATED_AT', 'DESC');
        return $this->db->get()->result_array();
    }

    // ===============================
    // Ambil SLD dengan paginasi + join unit
    // ===============================
    public function get_sld($limit, $offset, $search = '')
    {
        $this->db->select('sld.*, u.UNIT_PELAKSANA AS NAMA_UNIT');
        $this->db->from($this->table . ' AS sld');
        $this->db->join('unit AS u', 'sld.ID_UNIT = u.ID_UNIT', 'left');
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('sld.NAMA_GI', $search);
            $this->db->or_like('sld.NAMA_PENYULANG', $search);
            $this->db->or_like('u.UNIT_PELAKSANA', $search);
            $this->db->group_end();
        }
        $this->db->order_by('sld.CREATED_AT', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }

    // ===============================
    // Hitung semua data
    // ===============================
    public function count_all_sld($search = '')
    {
        $this->db->from($this->table . ' AS sld');
        $this->db->join('unit AS u', 'sld.ID_UNIT = u.ID_UNIT', 'left');
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('sld.NAMA_GI', $search);
            $this->db->or_like('sld.NAMA_PENYULANG', $search);
            $this->db->or_like('u.UNIT_PELAKSANA', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results();
    }

    // ===============================
    // Ambil berdasarkan ID
    // ===============================
    public function get_sld_by_id($id)
    {
        $this->db->select('sld.*, u.UNIT_PELAKSANA AS NAMA_UNIT');
        $this->db->from($this->table . ' AS sld');
        $this->db->join('unit AS u', 'sld.ID_UNIT = u.ID_UNIT', 'left');
        $this->db->where('sld.ID_SLD', $id);
        return $this->db->get()->row_array();
    }

    // ===============================
    // Insert data
    // ===============================
    public function insert_sld($data)
    {
        return $this->db->insert('single_line_diagram', $data);
    }


    // ===============================
    // Update data
    // ===============================
    public function update_sld($id, $data)
    {
        $this->db->where('ID_SLD', $id);
        return $this->db->update($this->table, $data);
    }

    // ===============================
    // Hapus data
    // ===============================
    public function delete_sld($id)
    {
        $this->db->where('ID_SLD', $id);
        return $this->db->delete($this->table);
    }
}
