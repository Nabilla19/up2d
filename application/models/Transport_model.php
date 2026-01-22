/**
 * Model Transport_model
 * Menangani seluruh interaksi database untuk modul E-Transport.
 * Terdiri dari tabel: transport_requests, transport_approvals, transport_fleet, transport_security_logs, dan transport_vehicles.
 */
class Transport_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // --- Permohonan (Requests) ---
    
    /**
     * Mengambil data permohonan
     * @param int|null $id ID spesifik permohonan
     */
    public function get_requests($id = null) {
        if ($id) {
            return $this->db->get_where('transport_requests', ['id' => $id])->row_array();
        }
        return $this->db->get('transport_requests')->result_array();
    }

    /**
     * Mengambil daftar permohonan milik user tertentu
     */
    public function get_my_requests($user_id) {
        return $this->db->get_where('transport_requests', ['user_id' => $user_id])->result_array();
    }

    /**
     * Menyimpan permohonan baru
     */
    public function create_request($data) {
        $this->db->insert('transport_requests', $data);
        return $this->db->insert_id();
    }

    /**
     * Update data/status permohonan
     */
    public function update_request($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('transport_requests', $data);
    }

    // --- Persetujuan (Approvals) ---
    
    /**
     * Mengambil data persetujuan (Asmen/KKU) untuk suatu request
     */
    public function get_approval($request_id) {
        return $this->db->get_where('transport_approvals', ['request_id' => $request_id])->row_array();
    }

    /**
     * Menambahkan record persetujuan
     */
    public function add_approval($data) {
        return $this->db->insert('transport_approvals', $data);
    }

    // --- Manajemen Armada (Fleet/Surat Jalan) ---
    
    /**
     * Mengambil data armada (Mobil/Driver) yang ditugaskan
     */
    public function get_fleet($request_id) {
        return $this->db->get_where('transport_fleet', ['request_id' => $request_id])->row_array();
    }

    /**
     * Menugaskan kendaraan dan pengemudi (KKU)
     */
    public function add_fleet($data) {
        return $this->db->insert('transport_fleet', $data);
    }

    // --- Log Keamanan (Security Logs) ---
    
    /**
     * Mengambil log check-in/out security
     */
    public function get_security_log($request_id) {
        return $this->db->get_where('transport_security_logs', ['request_id' => $request_id])->row_array();
    }

    /**
     * Pencatatan Jam Berangkat & KM Awal
     */
    public function add_security_log($data) {
        return $this->db->insert('transport_security_logs', $data);
    }
    
    /**
     * Pencatatan Jam Kembali & KM Akhir
     */
    public function update_security_log($request_id, $data) {
        $this->db->where('request_id', $request_id);
        return $this->db->update('transport_security_logs', $data);
    }

    // --- Query Terintegrasi (Joined Data) ---
    
    /**
     * Mengambil semua detail permohonan gabungan dari semua fase (Approval, Fleet, Security)
     * Digunakan untuk dashboard detail dan export
     */
    public function get_all_requests_detailed() {
        $this->db->select('tr.*, tf.mobil, tf.plat_nomor, tf.pengemudi, tf.created_at as fleet_assigned_at, ts.km_awal, ts.km_akhir, ts.jam_berangkat as log_jam_berangkat, ts.jam_kembali as log_jam_kembali, ts.lama_waktu, ts.jarak_tempuh, ts.foto_driver_berangkat, ts.foto_km_berangkat, ts.foto_driver_kembali, ts.foto_km_kembali, ta.asmen_id, ta.is_approved, ta.catatan as catatan_asmen, ta.approved_at, tr.barcode_pemohon, ta.barcode_asmen, tf.barcode_fleet');
        $this->db->from('transport_requests tr');
        $this->db->join('transport_approvals ta', 'ta.request_id = tr.id', 'left');
        $this->db->join('transport_fleet tf', 'tf.request_id = tr.id', 'left');
        $this->db->join('transport_security_logs ts', 'ts.request_id = tr.id', 'left');
        $this->db->order_by('tr.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    // --- Master Data Kendaraan (Vehicles) ---
    
    /**
     * Daftar semua unit kendaraan
     */
    public function get_vehicles() {
        return $this->db->get('transport_vehicles')->result_array();
    }

    /**
     * Mencari kendaraan yang berstatus 'Available'
     * @param string|null $brand Filter berdasarkan merk/tipe
     */
    public function get_available_vehicles($brand = null) {
        $where = ['status' => 'Available'];
        if ($brand) {
            $where['brand'] = $brand;
        }
        return $this->db->get_where('transport_vehicles', $where)->result_array();
    }

    /**
     * Sinkronisasi status kendaraan (Available / In Use)
     */
    public function update_vehicle_status($plat_nomor, $status, $request_id = null) {
        $data = ['status' => $status];
        if ($request_id !== null) {
            $data['last_request_id'] = $request_id;
        }
        $this->db->where('plat_nomor', $plat_nomor);
        return $this->db->update('transport_vehicles', $data);
    }

    /**
     * Mengambil referensi Jenis Kendaraan (MPV, Pick Up, dll)
     */
    public function get_vehicle_types() {
        return $this->db->get('transport_vehicle_types')->result_array();
    }

    /**
     * Menghitung total antrian untuk Badge Notifikasi Sidebar
     * Mempertimbangkan Filter Bidang untuk Asmen
     * @param int|null $role_id
     * @param string|null $role_name
     * @return array [pending_asmen, pending_fleet, in_progress]
     */
    public function get_pending_counts($role_id = null, $role_name = null) {
        $counts = [
            'pending_asmen' => 0,
            'pending_fleet' => 0,
            'in_progress'   => 0
        ];

        $role_name = $role_name ? strtolower($role_name) : '';

        // Ambil kandidat request yang aktif
        $this->db->select('id, status, bagian');
        $this->db->where_in('status', ['Pending Asmen', 'Pending Asmen/KKU', 'Pending Fleet', 'In Progress']);
        $requests = $this->db->get('transport_requests')->result_array();

        if (empty($requests)) return $counts;

        foreach ($requests as $r) {
            $status = $r['status'];
            $bagian = strtolower($r['bagian'] ?? '');

            if ($status == 'Pending Asmen' || $status == 'Pending Asmen/KKU') {
                // Filter Notifikasi untuk Asmen (Hanya yang di bidangnya)
                $can_approve = false;
                if ($role_id == 6) {
                    $can_approve = true;
                } else {
                    $is_perencanaan = (strpos($bagian, 'perencanaan') !== false);
                    $is_pemeliharaan = (strpos($bagian, 'pemeliharaan') !== false || strpos($bagian, 'har') !== false);
                    $is_operasi = (strpos($bagian, 'operasi') !== false);
                    $is_fasop = (strpos($bagian, 'fasop') !== false);

                    if ($role_id == 15 && $is_perencanaan) $can_approve = true;
                    elseif ($role_id == 16 && $is_pemeliharaan) $can_approve = true;
                    elseif ($role_id == 17 && $is_operasi) $can_approve = true;
                    elseif ($role_id == 18 && $is_fasop) $can_approve = true;
                    elseif ($role_name === 'kku' && !($is_perencanaan || $is_pemeliharaan || $is_operasi || $is_fasop)) $can_approve = true;
                }
                if ($can_approve) $counts['pending_asmen']++;
            } 
            elseif ($status == 'Pending Fleet') {
                // Notifikasi untuk KKU (Penugasan Armada)
                if ($role_id == 6 || $role_name === 'kku') {
                    $counts['pending_fleet']++;
                }
            } 
            elseif ($status == 'In Progress') {
                // Notifikasi untuk Security (Monitoring Kendaraan Luar)
                if ($role_id == 6 || $role_id == 19) {
                    $counts['in_progress']++;
                }
            }
        }

        return $counts;
    }
}
