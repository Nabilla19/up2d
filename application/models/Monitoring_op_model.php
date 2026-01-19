<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_op_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_table_data($limit = 500)
    {
        $table = 'monitoring_op';
        if (!$this->db->table_exists($table)) {
            throw new Exception('Table ' . $table . ' does not exist in database');
        }
        $fields = $this->db->list_fields($table);
        $query = $this->db->limit((int)$limit)->get($table);
        $rows = $query->result_array();
        return ['fields' => $fields, 'rows' => $rows];
    }

}
