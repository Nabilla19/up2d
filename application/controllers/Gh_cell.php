<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_Pagination $pagination
 * @property CI_URI $uri
 * @property Gh_cell_model $Gh_cell_model
 * @property CI_Config $config
 */
class Gh_cell extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load model
        $this->load->model('Gh_cell_model');
        // Load helper dan library
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'pagination']);
    }

    // Halaman utama - tampilkan semua data GH Cell
    public function index()
    {
        $data['title'] = 'Data GH Cell';

        // Navbar data
        $data['page_title'] = 'Data GH Cell';
        $data['page_icon'] = 'fas fa-square';
        $data['parent_page_title'] = 'Asset';
        $data['parent_page_url'] = '#';

        // ✅ PERBAIKAN: ambil kata kunci search dari query string
        $search = $this->input->get('search', TRUE);

        // Konfigurasi paginasi
        $config['base_url'] = site_url('gh_cell/index');

        // ✅ PERBAIKAN: total_rows dihitung sesuai search
        $config['total_rows'] = $this->Gh_cell_model->count_all_gh_cell($search);

        // per_page can be overridden via query string ?per_page=10
        $allowed = [5, 10, 25, 50, 100, 500];
        $requestedPer = (int) $this->input->get('per_page');

        // default per page: ambil dari config jika ada, fallback 10
        $defaultPer = (int) $this->config->item('default_per_page');
        if ($defaultPer <= 0) $defaultPer = 10;

        $per_page = in_array($requestedPer, $allowed) ? $requestedPer : $defaultPer;

        $config['per_page'] = $per_page;
        $config["uri_segment"] = 3;
        $config['use_page_numbers'] = TRUE;

        // ✅ PERBAIKAN: pagination harus ikut membawa query string (?search=...&per_page=...)
        $config['reuse_query_string'] = TRUE;

        // ✅ PERBAIKAN: suffix + first_url supaya link pagination tetap bawa GET param
        $query = $this->input->get(NULL, TRUE);
        if (!empty($query)) {
            $config['suffix'] = '?' . http_build_query($query, '', '&');
            $config['first_url'] = $config['base_url'] . $config['suffix'];
        }

        // Customizing pagination links
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

        // ✅ PERBAIKAN: ambil data sesuai search
        $data['gh_cell'] = $this->Gh_cell_model->get_gh_cell($config['per_page'], $offset, $search);

        $data['pagination'] = $this->pagination->create_links();
        $data['start_no'] = $offset + 1;
        $data['per_page'] = $config['per_page'];
        $data['total_rows'] = $config['total_rows'];

        // ✅ PERBAIKAN: kirim search ke view supaya input search tetap terisi
        $data['search'] = $search;

        $this->load->view('layout/header');
        $this->load->view('gh_cell/vw_gh_cell', $data);
        $this->load->view('layout/footer');
    }

    // Tambah data baru
    public function tambah()
    {
        if (!can_create()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menambah data');
            redirect($this->router->fetch_class());
        }

        if ($this->input->post()) {
            $insertData = [
                // Only fields that exist in database gh_cell (34 columns)
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

            $this->Gh_cell_model->insert_gh_cell($insertData);
            $this->session->set_flashdata('success', 'Data GH Cell berhasil ditambahkan!');
            redirect('Gh_cell');
        } else {
            $data['title'] = 'Tambah Data GH Cell';
            $data['parent_page_title'] = 'Asset';
            $data['parent_page_url'] = '#';
            $this->load->view('layout/header');
            $this->load->view('gh_cell/vw_tambah_gh_cell', $data);
            $this->load->view('layout/footer');
        }
    }

    // Edit data
    public function edit($id)
    {
        if (!can_edit()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengubah data');
            redirect($this->router->fetch_class());
        }

        $data['gh_cell'] = $this->Gh_cell_model->get_gh_cell_by_id($id);
        if (empty($data['gh_cell'])) {
            show_404();
        }

        if ($this->input->post()) {
            // Determine the original SSOTNUMBER (hidden field) to allow changing SSOTNUMBER safely
            $original = $this->input->post('original_SSOTNUMBER') ?: $id;

            // Build update data - only fields that exist in database gh_cell (34 columns)
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

            $this->Gh_cell_model->update_gh_cell($original, $updateData);
            $this->session->set_flashdata('success', 'Data GH Cell berhasil diperbarui!');
            redirect('Gh_cell');
        } else {
            $data['title'] = 'Edit Data GH Cell';
            $data['parent_page_title'] = 'Asset';
            $data['parent_page_url'] = '#';
            $this->load->view('layout/header');
            $this->load->view('gh_cell/vw_edit_gh_cell', $data);
            $this->load->view('layout/footer');
        }
    }

    // Detail data
    public function detail($id)
    {
        $data['gh_cell'] = $this->Gh_cell_model->get_gh_cell_by_id($id);
        if (empty($data['gh_cell'])) {
            show_404();
        }

        $data['title'] = 'Detail Data GH Cell';
        $data['parent_page_title'] = 'Asset';
        $data['parent_page_url'] = '#';
        $this->load->view('layout/header');
        $this->load->view('gh_cell/vw_detail_gh_cell', $data);
        $this->load->view('layout/footer');
    }

    // Hapus data
    public function hapus($id)
    {
        if (!can_delete()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menghapus data');
            redirect($this->router->fetch_class());
        }

        $this->Gh_cell_model->delete_gh_cell($id);
        $this->session->set_flashdata('success', 'Data GH Cell berhasil dihapus!');
        redirect('Gh_cell');
    }

    // Export GH Cell data to CSV
    public function export_csv()
    {
        // Block guest users from exporting
        if (function_exists('is_guest') && is_guest()) {
            $this->session->set_flashdata('error', 'Akses ditolak. Silakan login untuk mengunduh data.');
            redirect(strtolower($this->router->fetch_class()));
            return;
        }

        $all = $this->Gh_cell_model->get_all_gh_cell();
        $label = 'Data GH Cell';
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

        $headers = array_keys($all[0]);
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
