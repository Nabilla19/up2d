<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transport_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // --- Requests ---
    public function get_requests($id = null) {
        if ($id) {
            return $this->db->get_where('transport_requests', ['id' => $id])->row_array();
        }
        return $this->db->get('transport_requests')->result_array();
    }

    public function get_my_requests($user_id) {
        return $this->db->get_where('transport_requests', ['user_id' => $user_id])->result_array();
    }

    public function create_request($data) {
        $this->db->insert('transport_requests', $data);
        return $this->db->insert_id();
    }

    public function update_request($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('transport_requests', $data);
    }

    // --- Approvals ---
    public function get_approval($request_id) {
        return $this->db->get_where('transport_approvals', ['request_id' => $request_id])->row_array();
    }

    public function add_approval($data) {
        return $this->db->insert('transport_approvals', $data);
    }

    // --- Fleet ---
    public function get_fleet($request_id) {
        return $this->db->get_where('transport_fleet', ['request_id' => $request_id])->row_array();
    }

    public function add_fleet($data) {
        return $this->db->insert('transport_fleet', $data);
    }

    // --- Security Logs ---
    public function get_security_log($request_id) {
        return $this->db->get_where('transport_security_logs', ['request_id' => $request_id])->row_array();
    }

    public function add_security_log($data) {
        return $this->db->insert('transport_security_logs', $data);
    }
    
    public function update_security_log($request_id, $data) {
        $this->db->where('request_id', $request_id);
        return $this->db->update('transport_security_logs', $data);
    }

    // --- Joined Data for Dashboard ---
    public function get_all_requests_detailed() {
        $this->db->select('tr.*, tf.mobil, tf.plat_nomor, tf.pengemudi, tf.created_at as fleet_assigned_at, ts.km_awal, ts.km_akhir, ts.jam_berangkat as log_jam_berangkat, ts.jam_kembali as log_jam_kembali, ts.lama_waktu, ts.jarak_tempuh, ts.foto_driver_berangkat, ts.foto_km_berangkat, ts.foto_driver_kembali, ts.foto_km_kembali, ta.is_approved, ta.catatan as catatan_asmen, ta.approved_at, tr.barcode_pemohon, ta.barcode_asmen, tf.barcode_fleet');
        $this->db->from('transport_requests tr');
        $this->db->join('transport_approvals ta', 'ta.request_id = tr.id', 'left');
        $this->db->join('transport_fleet tf', 'tf.request_id = tr.id', 'left');
        $this->db->join('transport_security_logs ts', 'ts.request_id = tr.id', 'left');
        $this->db->order_by('tr.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    // --- Vehicles ---
    public function get_vehicles() {
        return $this->db->get('transport_vehicles')->result_array();
    }

    public function get_available_vehicles($brand = null) {
        $where = ['status' => 'Available'];
        if ($brand) {
            $where['brand'] = $brand;
        }
        return $this->db->get_where('transport_vehicles', $where)->result_array();
    }

    public function update_vehicle_status($plat_nomor, $status, $request_id = null) {
        $data = ['status' => $status];
        if ($request_id !== null) {
            $data['last_request_id'] = $request_id;
        }
        $this->db->where('plat_nomor', $plat_nomor);
        return $this->db->update('transport_vehicles', $data);
    }

    public function get_vehicle_types() {
        return $this->db->get('transport_vehicle_types')->result_array();
    }
}
