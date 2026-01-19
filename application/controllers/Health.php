<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Health extends CI_Controller {

    public function db()
    {
        $this->output->set_content_type('application/json');

        try {
            $this->load->database();
            if ($this->db && $this->db->conn_id) {
                $q = $this->db->query('SELECT 1 AS ok');
                $row = $q->row();
                $this->output->set_status_header(200)
                    ->set_output(json_encode([
                        'status' => 'ok',
                        'driver' => $this->db->dbdriver,
                        'server_info' => function_exists('mysqli_get_server_info') && isset($this->db->conn_id) ? @mysqli_get_server_info($this->db->conn_id) : null,
                        'result' => $row ? (int)$row->ok : null,
                    ]));
                return;
            }

            $this->output->set_status_header(500)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'DB not connected',
                ]));
        } catch (Exception $e) {
            $this->output->set_status_header(500)
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ]));
        }
    }
}
