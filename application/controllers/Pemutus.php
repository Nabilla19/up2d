<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Pemutus_model $pemutus_model
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_Pagination $pagination
 * @property CI_URI $uri
 * @property CI_Config $config
 */
class Pemutus extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load model Pemutus_model
        $this->load->model('Pemutus_model', 'pemutus_model');
        // Load helper dan library
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'pagination']);
    }

    // 🔹 Halaman utama - tampilkan semua data Pemutus (LBS Recloser)
    public function index()
    {
        $data['title'] = 'Data Pemutus (LBS Recloser)';

        // Navbar data
        $data['page_title'] = 'Data Pemutus (LBS Recloser)';
        $data['page_icon']  = 'fas fa-toggle-on';
        $data['parent_page_title'] = 'Asset';
        $data['parent_page_url'] = '#';

        // ✅ ambil keyword search dari query string
        // PERSISTENCE: Search
        if ($this->input->get('search') !== null) {
            $search = trim($this->input->get('search', TRUE));
            $this->session->set_userdata('pemutus_search', $search);
        } else {
            $search = $this->session->userdata('pemutus_search') ?? '';
        }
        $data['search'] = $search;

        // Konfigurasi paginasi
        $config['base_url']   = site_url('pemutus/index');

        // ✅ total_rows harus mengikuti search (biar pagination benar)
        $config['total_rows'] = $this->pemutus_model->count_all_pemutus($search);

        // Per-page selector (from ?per_page), use config default_per_page
        // PERSISTENCE: Per Page
        $allowedPerPage = [5, 10, 25, 50, 100, 500];
        if ($this->input->get('per_page') !== null) {
            $requestedPer = (int)$this->input->get('per_page');
            $this->session->set_userdata('pemutus_per_page', $requestedPer);
        }

        $savedPer = (int)$this->session->userdata('pemutus_per_page');
        $defaultPer = (int) $this->config->item('default_per_page');
        if($defaultPer <= 0) $defaultPer = 10;
        
        $perPage = ($savedPer > 0 && in_array($savedPer, $allowedPerPage)) ? $savedPer : $defaultPer;
        $this->session->set_userdata('pemutus_per_page', $perPage);

        $config['per_page']         = $perPage;
        $config["uri_segment"]      = 3;
        $config['use_page_numbers'] = TRUE;

        // ✅ penting: supaya pagination bawa query string (per_page & search)
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
        $data['pemutus']    = $this->pemutus_model->get_pemutus($config['per_page'], $offset, $search);
        $data['pagination'] = $this->pagination->create_links();
        $data['start_no']   = $offset + 1;
        $data['total_rows'] = $config['total_rows'];
        $data['per_page']   = $perPage;

        $this->load->view('layout/header');
        $this->load->view('pemutus/vw_pemutus', $data);
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
            // Only use the 31 valid database columns from lbs_recloser table (correct structure)
            $insertData = [
                'CXUNIT' => $this->input->post('CXUNIT'),
                'UNITNAME' => $this->input->post('UNITNAME'),
                'UP3_2D' => $this->input->post('UP3_2D'),
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
                'NAMA_LOCATION' => $this->input->post('NAMA_LOCATION'),
                'LONGITUDEX' => $this->input->post('LONGITUDEX'),
                'LATITUDEY' => $this->input->post('LATITUDEY'),
                'ISASSET' => $this->input->post('ISASSET'),
                'PEREDAM' => $this->input->post('PEREDAM'),
                'STATUS_KEPEMILIKAN' => $this->input->post('STATUS_KEPEMILIKAN'),
                'CXPENYULANG' => $this->input->post('CXPENYULANG'),
                'TH_BUAT' => $this->input->post('TH_BUAT'),
                'TYPE_LBS' => $this->input->post('TYPE_LBS'),
                'MODE_OPERASI' => $this->input->post('MODE_OPERASI'),
                'TYPE_RECLOSER' => $this->input->post('TYPE_RECLOSER'),
                'MODE_OPR' => $this->input->post('MODE_OPR'),
                'TYPE_OPERASI' => $this->input->post('TYPE_OPERASI'),
                'TYPE_SECTIONALIZER' => $this->input->post('TYPE_SECTIONALIZER'),
                'PEMUTUS_TYPE' => $this->input->post('PEMUTUS_TYPE')
            ];

            $this->pemutus_model->insert_pemutus($insertData);
            $this->session->set_flashdata('success', 'Data Pemutus berhasil ditambahkan!');
            redirect('Pemutus');
        } else {
            $data['title'] = 'Tambah Data Pemutus';
            $data['parent_page_title'] = 'Asset';
            $data['parent_page_url'] = '#';
            $this->load->view('layout/header');
            $this->load->view('pemutus/vw_tambah_pemutus', $data);
            $this->load->view('layout/footer');
        }
    }

    // 🔹 Edit data
    public function edit($ssotnumber)
    {
        $data['pemutus'] = $this->pemutus_model->get_pemutus_by_id($ssotnumber);
        if (empty($data['pemutus'])) {
            show_404();
        }

        if ($this->input->post()) {
            $original = $this->input->post('original_SSOTNUMBER') ?: $ssotnumber;

            // Only use the 31 valid database columns from lbs_recloser table (correct structure)
            $updateData = [
                'CXUNIT' => $this->input->post('CXUNIT'),
                'UNITNAME' => $this->input->post('UNITNAME'),
                'UP3_2D' => $this->input->post('UP3_2D'),
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
                'NAMA_LOCATION' => $this->input->post('NAMA_LOCATION'),
                'LONGITUDEX' => $this->input->post('LONGITUDEX'),
                'LATITUDEY' => $this->input->post('LATITUDEY'),
                'ISASSET' => $this->input->post('ISASSET'),
                'PEREDAM' => $this->input->post('PEREDAM'),
                'STATUS_KEPEMILIKAN' => $this->input->post('STATUS_KEPEMILIKAN'),
                'CXPENYULANG' => $this->input->post('CXPENYULANG'),
                'TH_BUAT' => $this->input->post('TH_BUAT'),
                'TYPE_LBS' => $this->input->post('TYPE_LBS'),
                'MODE_OPERASI' => $this->input->post('MODE_OPERASI'),
                'TYPE_RECLOSER' => $this->input->post('TYPE_RECLOSER'),
                'MODE_OPR' => $this->input->post('MODE_OPR'),
                'TYPE_OPERASI' => $this->input->post('TYPE_OPERASI'),
                'TYPE_SECTIONALIZER' => $this->input->post('TYPE_SECTIONALIZER'),
                'PEMUTUS_TYPE' => $this->input->post('PEMUTUS_TYPE')
            ];

            $update_success = $this->pemutus_model->update_pemutus($original, $updateData);

            // Log aktivitas
            if ($update_success) {
                log_update('pemutus', $original, $updateData['KEYPOINT']);
            }

            $this->session->set_flashdata('success', 'Data Pemutus berhasil diperbarui!');
            redirect('Pemutus');
        } else {
            $data['title'] = 'Edit Data Pemutus';
            $data['parent_page_title'] = 'Asset';
            $data['parent_page_url'] = '#';
            $this->load->view('layout/header');
            $this->load->view('pemutus/vw_edit_pemutus', $data);
            $this->load->view('layout/footer');
        }
    }

    // 🔹 Detail data
    public function detail($ssotnumber)
    {
        $data['pemutus'] = $this->pemutus_model->get_pemutus_by_id($ssotnumber);
        if (empty($data['pemutus'])) {
            show_404();
        }

        // Ensure detail view has the same keys as the list view to avoid undefined index notices
        $expectedKeys = [
            'SSOTNUMBER','CXUNIT','UNITNAME','LOCATION','DESCRIPTION','VENDOR','MANUFACTURER',
            'INSTALLDATE','PRIORITY','STATUS','TUJDNUMBER','CHANGEBY','CHANGEDATE','CXCLASSIFICATIONDESC',
            'CXPENYULANG','NAMA_LOCATION','LONGITUDEX','LATITUDEY','ISASSET','STATUS_KEPEMILIKAN',
            'BURDEN','FAKTOR_KALI','JENIS_CT','KELAS_CT','KELAS_PROTEKSI','PRIMER_SEKUNDER',
            'TIPE_CT','OWNERSYSID','ISOLASI_KUBIKEL','JENIS_MVCELL','TH_BUAT','TYPE_MVCELL','CELL_TYPE'
        ];

        foreach ($expectedKeys as $k) {
            if (!array_key_exists($k, $data['pemutus'])) {
                $data['pemutus'][$k] = '';
            }
        }

        $data['title'] = 'Detail Data Pemutus';
        $data['parent_page_title'] = 'Asset';
        $data['parent_page_url'] = '#';
        $this->load->view('layout/header');
        $this->load->view('pemutus/vw_detail_pemutus', $data);
        $this->load->view('layout/footer');
    }

    // 🔹 Hapus data
    public function hapus($ssotnumber)
    {
        if (!can_delete()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menghapus data');
            redirect($this->router->fetch_class());
        }

        // Get data before delete for logging
        $pemutus_data = $this->pemutus_model->get_pemutus_by_id($ssotnumber);
        $name = $pemutus_data['KEYPOINT'] ?? $ssotnumber;

        $delete_success = $this->pemutus_model->delete_pemutus($ssotnumber);

        // Log aktivitas
        if ($delete_success) {
            log_delete('pemutus', $ssotnumber, $name);
        }

        $this->session->set_flashdata('success', 'Data Pemutus berhasil dihapus!');
        redirect('Pemutus');
    }

    // Export pemutus data to CSV
    public function export_csv()
    {
        // Block guest users from exporting
        if (function_exists('is_guest') && is_guest()) {
            $this->session->set_flashdata('error', 'Akses ditolak. Silakan login untuk mengunduh data.');
            redirect(strtolower($this->router->fetch_class()));
            return;
        }

        $all = $this->pemutus_model->get_all_pemutus();
        $label = 'Data Pemutus';
        $filename = $label . ' ' . date('d-m-Y') . '.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $out = fopen('php://output', 'w');
        // write UTF-8 BOM for Excel
        fwrite($out, "\xEF\xBB\xBF");

        if (empty($all)) {
            fputcsv($out, ['No data']);
            fclose($out);
            exit;
        }

        $headers = array_keys($all[0]);
        fputcsv($out, $headers);
        foreach ($all as $row) {
            $line = [];
            foreach ($headers as $h) {
                $line[] = isset($row[$h]) ? $row[$h] : '';
            }
            fputcsv($out, $line);
        }

        fclose($out);
        exit;
    }
}
