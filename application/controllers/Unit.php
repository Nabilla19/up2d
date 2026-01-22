<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller for Unit Pelaksana
 *
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Unit_model $Unit_model
 * @property CI_Pagination $pagination
 * @property CI_URI $uri
 * @property CI_Config $config
 */
class Unit extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load model
        $this->load->model('Unit_model');
        // Load helper untuk URL
        $this->load->helper(['url', 'form']);
        // Load library session (untuk flashdata) dan pagination
        $this->load->library(['session', 'pagination']);
    }

    // Export semua data unit ke CSV yang kompatibel dengan Excel
    public function export_csv()
    {
        // Block guest users from exporting
        if (function_exists('is_guest') && is_guest()) {
            $this->session->set_flashdata('error', 'Akses ditolak. Silakan login untuk mengunduh data.');
            redirect(strtolower($this->router->fetch_class()));
            return;
        }

        // Ambil semua data
        $all = $this->Unit_model->get_all_units();

        // Set headers untuk download CSV (Excel-friendly)
    // Human-friendly label for this export
    $label = 'Data Unit';
    // Filename format requested: "Data Unit dd-mm-yyyy.csv"
    $filename = $label . ' ' . date('d-m-Y') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        // tulis BOM UTF-8 supaya Excel mengenali encoding
        fwrite($output, "\xEF\xBB\xBF");

        // Jika tidak ada data, kirim header saja
        if (empty($all)) {
            fputcsv($output, ['No data']);
            fclose($output);
            exit;
        }

        // header CSV: ambil keys dari baris pertama
        $first = $all[0];
        $headers = array_keys($first);
        // optionally rename ID_UNIT to ID
        fputcsv($output, $headers);

        // tulis setiap baris
        $no = 1;
        foreach ($all as $row) {
            // convert all values to string, preserve order
            $line = [];
            foreach ($headers as $h) {
                $line[] = isset($row[$h]) ? $row[$h] : '';
            }
            fputcsv($output, $line);
        }

        fclose($output);
        exit;
    }

    // Halaman utama - tampilkan semua data dengan paginasi
    public function index()
    {
        $data['title'] = 'Data Unit';
        $data['page_title'] = 'Data Unit';
        $data['page_icon'] = 'fas fa-building';
        
        // Breadcrumb: Asset / Data Unit
        $data['parent_page_title'] = 'Asset';
        $data['parent_page_url'] = '#'; 

        // --- HANDLING PAGINATION PERSISTENCE ---
        
        // Search
        if ($this->input->get('q') !== null) {
            $q = trim($this->input->get('q', TRUE));
            $this->session->set_userdata('unit_q', $q);
        } elseif ($this->input->get('per_page') !== null && $this->input->get('q') === null) {
            // If per_page is set but q is not, clear the session search (this is a reset)
            $this->session->unset_userdata('unit_q');
            $q = '';
        } else {
            $q = $this->session->userdata('unit_q') ?? '';
        }
        $data['q'] = $q;

        // Per Page
        if ($this->input->get('per_page') !== null) {
            $per_page = (int)$this->input->get('per_page');
            $this->session->set_userdata('unit_per_page', $per_page);
        } else {
            $per_page = (int)($this->session->userdata('unit_per_page') ?? 10);
        }
        $this->session->set_userdata('unit_per_page', $per_page);

        // Pagination config
        $config['base_url'] = site_url('Unit/index');
        $config['total_rows'] = $this->Unit_model->count_all_unit($q);
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 3;
        $config['reuse_query_string'] = TRUE; // important
        $allowedPerPage = [5, 10, 25, 50, 100, 500];
        $config['per_page'] = in_array($per_page, $allowedPerPage) ? $per_page : 10;
        $config["uri_segment"] = 3;
        $config['use_page_numbers'] = TRUE;

        // Pagination styling
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-end">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');

        // Ambil page number
        $page = ($this->uri->segment(3)) ? (int)$this->uri->segment(3) : 1;
        if ($page <= 0) $page = 1;
        $offset = ($page - 1) * $config['per_page'];

        $this->pagination->initialize($config);

        // Ambil data
        $data['unit'] = $this->Unit_model->get_unit($config['per_page'], $offset, $q);
        $data['pagination'] = $this->pagination->create_links();
        $data['start_no'] = $offset + 1;
        $data['per_page'] = $config['per_page'];
        $data['total_rows'] = $config['total_rows'];

        // Map posisi global jika ada search
        if (!empty($q) && !empty($data['unit'])) {
            $ids = array_column($data['unit'], 'ID_UNIT');
            $data['positions'] = $this->Unit_model->get_positions_for_ids($ids);
        } else {
            $data['positions'] = [];
        }

        $this->load->view('layout/header');
        $this->load->view('unit/vw_unit', $data);
        $this->load->view('layout/footer');
    }

    // Tambah data baru
    public function tambah()
    {
        // Check permission
        if (!can_create()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menambah data');
            redirect('Unit');
        }

        if ($this->input->post()) {
            $insertData = [
                'UNIT_PELAKSANA' => $this->input->post('UNIT_PELAKSANA'),
                'UNIT_LAYANAN' => $this->input->post('UNIT_LAYANAN'),
                'LONGITUDEX' => $this->input->post('LONGITUDEX'),
                'LATITUDEY' => $this->input->post('LATITUDEY'),
                'ADDRESS' => $this->input->post('ADDRESS')
            ];

            $insert_id = $this->Unit_model->insert_unit($insertData);
            
            // Log aktivitas create
            if ($insert_id) {
                log_create('unit', $insert_id, $insertData['UNIT_PELAKSANA']);
            }
            
            $this->session->set_flashdata('success', 'Data Unit berhasil ditambahkan!');
            redirect('Unit');
        } else {
            $data['title'] = 'Tambah Data Unit';
            $data['parent_page_title'] = 'Asset';
            $data['parent_page_url'] = '#';
            $this->load->view('layout/header');
            $this->load->view('unit/vw_tambah_unit', $data);
            $this->load->view('layout/footer');
        }
    }

    // Edit data
    public function edit($id)
    {
        // Check permission
        if (!can_edit()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengubah data');
            redirect('Unit');
        }

        $data['unit'] = $this->Unit_model->get_unit_by_id($id);
        if (empty($data['unit'])) {
            show_404();
        }

        if ($this->input->post()) {
            $updateData = [
                'UNIT_PELAKSANA' => $this->input->post('UNIT_PELAKSANA'),
                'UNIT_LAYANAN' => $this->input->post('UNIT_LAYANAN'),
                'LONGITUDEX' => $this->input->post('LONGITUDEX'),
                'LATITUDEY' => $this->input->post('LATITUDEY'),
                'ADDRESS' => $this->input->post('ADDRESS')
            ];

            $update_success = $this->Unit_model->update_unit($id, $updateData);
            
            // Log aktivitas update
            if ($update_success) {
                log_update('unit', $id, $updateData['UNIT_PELAKSANA']);
            }
            
            $this->session->set_flashdata('success', 'Data Unit berhasil diperbarui!');
            redirect('Unit');
        } else {
            $data['title'] = 'Edit Data Unit';
            $data['parent_page_title'] = 'Asset';
            $data['parent_page_url'] = '#';
            $this->load->view('layout/header');
            $this->load->view('unit/vw_edit_unit', $data);
            $this->load->view('layout/footer');
        }
    }

    // Detail data
    public function detail($id)
    {
        $data['unit'] = $this->Unit_model->get_unit_by_id($id);
        if (empty($data['unit'])) {
            show_404();
        }

        $data['title'] = 'Detail Data Unit';
        $data['parent_page_title'] = 'Asset';
        $data['parent_page_url'] = '#';
        $this->load->view('layout/header');
        $this->load->view('unit/vw_detail_unit', $data);
        $this->load->view('layout/footer');
    }

    // Hapus data
    public function hapus($id)
    {
        // Check permission
        if (!can_delete()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menghapus data');
            redirect('Unit');
        }

        // Get data before delete for logging
        $unit = $this->Unit_model->get_unit_by_id($id);
        $unit_name = $unit ? ($unit['UNIT_PELAKSANA'] ?? 'ID-' . $id) : 'ID-' . $id;
        
        $delete_success = $this->Unit_model->delete_unit($id);
        
        // Log aktivitas delete
        if ($delete_success) {
            log_delete('unit', $id, $unit_name);
        }
        
        $this->session->set_flashdata('success', 'Data Unit berhasil dihapus!');
        redirect('Unit');
    }
}