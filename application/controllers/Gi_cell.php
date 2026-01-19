<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller for GI Cell
 *
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Gi_cell_model $gi_cell_model
 * @property CI_Pagination $pagination
 * @property CI_URI $uri
 * @property CI_Config $config
 */
class Gi_cell extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load model
        $this->load->model('Gi_cell_model', 'gi_cell_model');
        // Load helper dan library
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'pagination']);
    }

    // Halaman utama - tampilkan semua data GI Cell
    public function index()
    {
        $data['title'] = 'Data GI Cell';

        // Navbar data
        $data['page_title'] = 'Data GI Cell';
        $data['page_icon'] = 'fas fa-wave-square';
        $data['parent_page_title'] = 'Asset';
        $data['parent_page_url'] = '#';

        // Ambil kata kunci pencarian dari query string
        $search = $this->input->get('search', TRUE);

        // Konfigurasi paginasi
        $config['base_url'] = site_url('gi_cell/index');
        $config['total_rows'] = $this->gi_cell_model->count_all_gi_cell($search); // Menambahkan parameter search untuk menghitung jumlah data
        $allowedPerPage = [5, 10, 25, 50, 100, 500];
        $requestedPer = (int) $this->input->get('per_page');
        $defaultPer = (int) $this->config->item('default_per_page');
        $perPage = in_array($requestedPer, $allowedPerPage) ? $requestedPer : $defaultPer;
        $config['per_page'] = $perPage;
        $config["uri_segment"] = 3;
        $config['use_page_numbers'] = TRUE;

        // ✅ PERBAIKAN: biar pagination tetap bawa query string (?search=...&per_page=...)
        $config['reuse_query_string'] = TRUE;

        // ✅ PERBAIKAN: pastikan first_url dan suffix ikut query GET (search, per_page, dll)
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

        // Ambil data untuk halaman saat ini
        $data['gi_cell'] = $this->gi_cell_model->get_gi_cell($config['per_page'], $offset, $search);
        $data['pagination'] = $this->pagination->create_links();
        $data['start_no'] = $offset + 1;
        $data['total_rows'] = $config['total_rows'];
        $data['per_page'] = $perPage;
        $data['search'] = $search; // Simpan kata kunci pencarian di view

        $this->load->view('layout/header');
        $this->load->view('gi_cell/vw_gi_cell', $data);
        $this->load->view('layout/footer');
    }

    // Tambah data baru
    public function tambah()
    {
        if (!can_create()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menambah data');
            redirect('Gi_cell');
        }
        if ($this->input->post()) {
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

            $this->gi_cell_model->insert_gi_cell($insertData);
            $this->session->set_flashdata('success', 'Data GI Cell berhasil ditambahkan!');
            redirect('Gi_cell');
        } else {
            $data['title'] = 'Tambah Data GI Cell';
            $data['parent_page_title'] = 'Asset';
            $data['parent_page_url'] = '#';
            $this->load->view('layout/header');
            $this->load->view('gi_cell/vw_tambah_gi_cell', $data);
            $this->load->view('layout/footer');
        }
    }

    // Edit data
    public function edit($id)
    {
        if (!can_edit()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengubah data');
            redirect('Gi_cell');
        }

        $data['gi_cell'] = $this->gi_cell_model->get_gi_cell_by_id($id);
        if (empty($data['gi_cell'])) {
            show_404();
        }

        $expected = [
            'SSOTNUMBER',
            'CXUNIT',
            'UNITNAME',
            'ASSETNUM',
            'LOCATION',
            'DESCRIPTION',
            'VENDOR',
            'MANUFACTURER',
            'INSTALLDATE',
            'PRIORITY',
            'STATUS',
            'TUJDNUMBER',
            'CHANGEBY',
            'CHANGEDATE',
            'CXCLASSIFICATIONDESC',
            'CXPENYULANG',
            'NAMA_LOCATION',
            'LONGITUDEX',
            'LATITUDEY',
            'ISASSET',
            'STATUS_KEPEMILIKAN',
            'BURDEN',
            'FAKTOR_KALI',
            'JENIS_CT',
            'KELAS_CT',
            'KELAS_PROTEKSI',
            'PRIMER_SEKUNDER',
            'TIPE_CT',
            'OWNERSYSID',
            'ISOLASI_KUBIKEL',
            'JENIS_MVCELL',
            'TH_BUAT',
            'TYPE_MVCELL',
            'CELL_TYPE'
        ];

        foreach ($expected as $k) {
            if (!array_key_exists($k, $data['gi_cell'])) {
                $data['gi_cell'][$k] = '';
            }
        }

        if ($this->input->post()) {
            $original = $this->input->post('original_SSOTNUMBER') ? $this->input->post('original_SSOTNUMBER') : $id;
            $updateData = [
                'SSOTNUMBER' => $this->input->post('SSOTNUMBER'),
                'CXUNIT' => $this->input->post('CXUNIT'),
                'UNITNAME' => $this->input->post('UNITNAME'),
                'ASSETNUM' => $this->input->post('ASSETNUM'),
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

            $this->gi_cell_model->update_gi_cell($original, $updateData);
            $this->session->set_flashdata('success', 'Data GI Cell berhasil diperbarui!');
            redirect('Gi_cell');
        } else {
            $data['title'] = 'Edit Data GI Cell';
            $data['parent_page_title'] = 'Asset';
            $data['parent_page_url'] = '#';
            $this->load->view('layout/header');
            $this->load->view('gi_cell/vw_edit_gi_cell', $data);
            $this->load->view('layout/footer');
        }
    }

    // Detail data
    public function detail($id)
    {
        $data['gi_cell'] = $this->gi_cell_model->get_gi_cell_by_id($id);
        if (empty($data['gi_cell'])) {
            show_404();
        }

        $data['title'] = 'Detail Data GI Cell';
        $data['parent_page_title'] = 'Asset';
        $data['parent_page_url'] = '#';
        $this->load->view('layout/header');
        $this->load->view('gi_cell/vw_detail_gi_cell', $data);
        $this->load->view('layout/footer');
    }

    // Hapus data
    public function hapus($id)
    {
        if (!can_delete()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menghapus data');
            redirect('Gi_cell');
        }
        $this->gi_cell_model->delete_gi_cell($id);
        $this->session->set_flashdata('success', 'Data GI Cell berhasil dihapus!');
        redirect('Gi_cell');
    }

    // Export GI Cell data to CSV
    public function export_csv()
    {
        // Block guest users from exporting
        if (function_exists('is_guest') && is_guest()) {
            $this->session->set_flashdata('error', 'Akses ditolak. Silakan login untuk mengunduh data.');
            redirect(strtolower($this->router->fetch_class()));
            return;
        }

        $all = $this->gi_cell_model->get_all_gi_cell();
        $label = 'Data GI Cell';
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
