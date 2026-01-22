<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Kit_cell_model $kit_cell_model
 * @property Pembangkit_model $Pembangkit_model
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_Pagination $pagination
 * @property CI_URI $uri
 * @property CI_Config $config
 */
class Kit_cell extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load model
        $this->load->model('Kit_cell_model', 'kit_cell_model');
        $this->load->model('Pembangkit_model');

        // Load helper dan library
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'pagination']);
    }

    // 🔹 Halaman utama - tampilkan semua data KIT Cell
    public function index()
    {
        $data['title'] = 'Data KIT Cell';

        // Navbar data
        $data['page_title'] = 'Data KIT Cell';
        $data['page_icon']  = 'fas fa-microchip';
        $data['parent_page_title'] = 'Asset';
        $data['parent_page_url'] = '#';

        // ✅ ambil search dari query string
        // PERSISTENCE: Search
        // View uses name="q". Controller should check 'q' first.
        $q_param = $this->input->get('q', TRUE); 
        if ($q_param === null) $q_param = $this->input->get('search', TRUE);

        if ($q_param !== null) {
            $search = trim($q_param);
            $this->session->set_userdata('kc_search', $search);
        } else {
            $search = $this->session->userdata('kc_search') ?? '';
        }
        $data['search'] = $search;

        // Konfigurasi paginasi
        $config['base_url']   = site_url('kit_cell/index');

        // ✅ total rows harus mengikuti search
        $config['total_rows'] = $this->kit_cell_model->count_all_kit_cell($search);

        // Per-page selector (from ?per_page), use config default_per_page
        // PERSISTENCE: Per Page
        $allowedPerPage = [5, 10, 25, 50, 100, 500];
        if ($this->input->get('per_page') !== null) {
            $requestedPer = (int)$this->input->get('per_page');
            $this->session->set_userdata('kc_per_page', $requestedPer);
        }

        $savedPer = (int)$this->session->userdata('kc_per_page');
        $defaultPer = (int) $this->config->item('default_per_page');
        if($defaultPer <= 0) $defaultPer = 10;
        
        $perPage = ($savedPer > 0 && in_array($savedPer, $allowedPerPage)) ? $savedPer : $defaultPer;
        $this->session->set_userdata('kc_per_page', $perPage);

        $config['per_page']        = $perPage;
        $config["uri_segment"]     = 3;
        $config['use_page_numbers']= TRUE;

        // ✅ supaya pagination tetap bawa query string (search & per_page)
        $config['reuse_query_string'] = TRUE;

        // Customizing pagination links
        $config['full_tag_open']   = '<nav><ul class="pagination justify-content-end">';
        $config['full_tag_close']  = '</ul></nav>';
        $config['first_link']      = 'First';
        $config['first_tag_open']  = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_link']       = 'Last';
        $config['last_tag_open']   = '<li class="page-item">';
        $config['last_tag_close']  = '</li>';
        $config['next_link']       = '&raquo';
        $config['next_tag_open']   = '<li class="page-item">';
        $config['next_tag_close']  = '</li>';
        $config['prev_link']       = '&laquo';
        $config['prev_tag_open']   = '<li class="page-item">';
        $config['prev_tag_close']  = '</li>';
        $config['cur_tag_open']    = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close']   = '</a></li>';
        $config['num_tag_open']    = '<li class="page-item">';
        $config['num_tag_close']   = '</li>';
        $config['attributes']      = array('class' => 'page-link');

        // Ambil nomor halaman dari URI
        $page_segment = $this->uri->segment(3);
        $page = (is_numeric($page_segment) && $page_segment > 0) ? (int)$page_segment : 1;
        if ($page <= 0) {
            $page = 1;
        }

        // Hitung offset
        $offset = ($page - 1) * $config['per_page'];

        // Inisialisasi paginasi
        $this->pagination->initialize($config);

        // ✅ Ambil data untuk halaman saat ini (ikut search)
        $data['kit_cell']    = $this->kit_cell_model->get_kit_cell($config['per_page'], $offset, $search);
        $data['pagination']  = $this->pagination->create_links();
        $data['start_no']    = $offset + 1;
        $data['total_rows']  = $config['total_rows'];
        $data['per_page']    = $perPage;

        $this->load->view('layout/header');
        $this->load->view('kit_cell/vw_kit_cell', $data);
        $this->load->view('layout/footer');
    }

    // 🔹 Tambah data baru
    public function tambah()
    {
        if (!can_create()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menambah data');
            redirect($this->router->fetch_class());
        }

        if ($this->input->post()) {
            // Only use the valid database columns from kit_cell table
            $insertData = [
                'CXUNIT' => $this->input->post('CXUNIT'),
                'UNITNAME' => $this->input->post('UNITNAME'),
                'ASSETNUM' => $this->input->post('ASSETNUM'),
                'SSOTNUMBER' => $this->input->post('SSOTNUMBER'),
                'LOCATION' => $this->input->post('LOCATION'),
                'DESCRIPTION' => $this->input->post('DESCRIPTION'),
                'VENDOR' => $this->input->post('VENDOR'),
                'MANUFACTURER' => $this->input->post('MANUFACTURER'),
                'INSTALLDATE' => $this->input->post('INSTALLDATE'),
                'PRIORITY' => $this->input->post('PRIORITY'),
                'STATUS' => $this->input->post('STATUS'),
                'TUJDNUMBER' => $this->input->post('TUJDNUMBER'),
                'CHANGEBY' => $this->input->post('CHANGEBY'),
                'CHANGEDATE' => $this->input->post('CHANGEDATE'),
                'CXCLASSIFICATIONDESC' => $this->input->post('CXCLASSIFICATIONDESC'),
                'CXPENYULANG' => $this->input->post('CXPENYULANG'),
                'NAMA_LOCATION' => $this->input->post('NAMA_LOCATION'),
                'LONGITUDEX' => $this->input->post('LONGITUDEX'),
                'LATITUDEY' => $this->input->post('LATITUDEY'),
                'ISASSET' => $this->input->post('ISASSET'),
                'STATUS_KEPEMILIKAN' => $this->input->post('STATUS_KEPEMILIKAN'),
                'BURDEN' => $this->input->post('BURDEN'),
                'FAKTOR_KALI' => $this->input->post('FAKTOR_KALI'),
                'JENIS_CT' => $this->input->post('JENIS_CT'),
                'KELAS_CT' => $this->input->post('KELAS_CT'),
                'KELAS_PROTEKSI' => $this->input->post('KELAS_PROTEKSI'),
                'PRIMER_SEKUNDER' => $this->input->post('PRIMER_SEKUNDER'),
                'TIPE_CT' => $this->input->post('TIPE_CT'),
                'OWNERSYSID' => $this->input->post('OWNERSYSID'),
                'ISOLASI_KUBIKEL' => $this->input->post('ISOLASI_KUBIKEL'),
                'JENIS_MVCELL' => $this->input->post('JENIS_MVCELL'),
                'TH_BUAT' => $this->input->post('TH_BUAT'),
                'TYPE_MVCELL' => $this->input->post('TYPE_MVCELL'),
                'CELL_TYPE' => $this->input->post('CELL_TYPE')
            ];

            $this->kit_cell_model->insert_kit_cell($insertData);
            $this->session->set_flashdata('success', 'Data KIT Cell berhasil ditambahkan!');
            redirect('Kit_cell');
        } else {
            $data['title'] = 'Tambah Data KIT Cell';
            $data['parent_page_title'] = 'Asset';
            $data['parent_page_url'] = '#';
            $this->load->view('layout/header');
            $this->load->view('kit_cell/vw_tambah_kit_cell', $data);
            $this->load->view('layout/footer');
        }
    }

    // 🔹 Edit data
    public function edit($id)
    {
        $data['kit_cell'] = $this->kit_cell_model->get_kit_cell_by_id($id);
        if (empty($data['kit_cell'])) {
            show_404();
        }

        if ($this->input->post()) {
            $original = $this->input->post('original_SSOTNUMBER') ?: $id;

            // Only use the valid database columns from kit_cell table
            $updateData = [
                'CXUNIT' => $this->input->post('CXUNIT'),
                'UNITNAME' => $this->input->post('UNITNAME'),
                'ASSETNUM' => $this->input->post('ASSETNUM'),
                'SSOTNUMBER' => $this->input->post('SSOTNUMBER') ?: $this->input->post('original_SSOTNUMBER'),
                'LOCATION' => $this->input->post('LOCATION'),
                'DESCRIPTION' => $this->input->post('DESCRIPTION'),
                'VENDOR' => $this->input->post('VENDOR'),
                'MANUFACTURER' => $this->input->post('MANUFACTURER'),
                'INSTALLDATE' => $this->input->post('INSTALLDATE'),
                'PRIORITY' => $this->input->post('PRIORITY'),
                'STATUS' => $this->input->post('STATUS'),
                'TUJDNUMBER' => $this->input->post('TUJDNUMBER'),
                'CHANGEBY' => $this->input->post('CHANGEBY'),
                'CHANGEDATE' => $this->input->post('CHANGEDATE'),
                'CXCLASSIFICATIONDESC' => $this->input->post('CXCLASSIFICATIONDESC'),
                'CXPENYULANG' => $this->input->post('CXPENYULANG'),
                'NAMA_LOCATION' => $this->input->post('NAMA_LOCATION'),
                'LONGITUDEX' => $this->input->post('LONGITUDEX'),
                'LATITUDEY' => $this->input->post('LATITUDEY'),
                'ISASSET' => $this->input->post('ISASSET'),
                'STATUS_KEPEMILIKAN' => $this->input->post('STATUS_KEPEMILIKAN'),
                'BURDEN' => $this->input->post('BURDEN'),
                'FAKTOR_KALI' => $this->input->post('FAKTOR_KALI'),
                'JENIS_CT' => $this->input->post('JENIS_CT'),
                'KELAS_CT' => $this->input->post('KELAS_CT'),
                'KELAS_PROTEKSI' => $this->input->post('KELAS_PROTEKSI'),
                'PRIMER_SEKUNDER' => $this->input->post('PRIMER_SEKUNDER'),
                'TIPE_CT' => $this->input->post('TIPE_CT'),
                'OWNERSYSID' => $this->input->post('OWNERSYSID'),
                'ISOLASI_KUBIKEL' => $this->input->post('ISOLASI_KUBIKEL'),
                'JENIS_MVCELL' => $this->input->post('JENIS_MVCELL'),
                'TH_BUAT' => $this->input->post('TH_BUAT'),
                'TYPE_MVCELL' => $this->input->post('TYPE_MVCELL'),
                'CELL_TYPE' => $this->input->post('CELL_TYPE')
            ];

            $this->kit_cell_model->update_kit_cell($original, $updateData);
            $this->session->set_flashdata('success', 'Data KIT Cell berhasil diperbarui!');
            redirect('Kit_cell');
        } else {
            $data['title'] = 'Edit Data KIT Cell';
            $data['parent_page_title'] = 'Asset';
            $data['parent_page_url'] = '#';
            $this->load->view('layout/header');
            $this->load->view('kit_cell/vw_edit_kit_cell', $data);
            $this->load->view('layout/footer');
        }
    }

    // 🔹 Detail data
    public function detail($id)
    {
        $data['kit_cell'] = $this->kit_cell_model->get_kit_cell_by_id($id);
        if (empty($data['kit_cell'])) {
            show_404();
        }

        $data['title'] = 'Detail Data KIT Cell';
        $data['parent_page_title'] = 'Asset';
        $data['parent_page_url'] = '#';
        $this->load->view('layout/header');
        $this->load->view('kit_cell/vw_detail_kit_cell', $data);
        $this->load->view('layout/footer');
    }

    // 🔹 Hapus data
    public function hapus($id)
    {
        if (!can_delete()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menghapus data');
            redirect($this->router->fetch_class());
        }

        $this->kit_cell_model->delete_kit_cell($id);
        $this->session->set_flashdata('success', 'Data KIT Cell berhasil dihapus!');
        redirect('Kit_cell');
    }

    // Export semua data KIT Cell ke CSV
    public function export_csv()
    {
        // Block guest users from exporting
        if (function_exists('is_guest') && is_guest()) {
            $this->session->set_flashdata('error', 'Akses ditolak. Silakan login untuk mengunduh data.');
            redirect(strtolower($this->router->fetch_class()));
            return;
        }

        $all = $this->kit_cell_model->get_all_kit_cell();

        $label = 'Data KIT Cell';
        $filename = $label . ' ' . date('d-m-Y') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fwrite($output, "\xEF\xBB\xBF");

        if (empty($all)) {
            fputcsv($output, ['No data']);
            fclose($output);
            exit;
        }

        $first = $all[0];
        $headers = array_keys($first);
        fputcsv($output, $headers);

        foreach ($all as $row) {
            $line = [];
            foreach ($headers as $h) {
                $line[] = isset($row[$h]) ? $row[$h] : '';
            }
            fputcsv($output, $line);
        }

        fclose($output);
        exit;
    }
}
