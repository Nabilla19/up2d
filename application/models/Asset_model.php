<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asset_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get up to $limit rows from a table and the table fields.
     * Returns [fields => string[], rows => array[]]
     */
    public function get_table_data($table, $limit = 50)
    {
        // Get fields
        $fields = $this->db->list_fields($table);
        // Get rows
        $query = $this->db->limit($limit)->get($table);
        $rows = $query->result_array();
        return [ 'fields' => $fields, 'rows' => $rows ];
    }
}
