<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller for Gardu Induk (Main Substation)
 *
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Gardu_induk_model $garduModel
 * @property CI_Pagination $pagination
 * @property CI_URI $uri
 * @property CI_Config $config
 */
class Gardu_induk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Gardu_induk_model', 'garduModel');
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'pagination']);
    }

    // =======================
    // LIST DATA (READ)
    // =======================
    public function index()
    {
        $data['judul'] = 'Data Gardu Induk';

        // Navbar data
        $data['page_title'] = 'Data Gardu Induk';
        $data['page_icon'] = 'fas fa-bolt';
        $data['parent_page_title'] = 'Asset';
        $data['parent_page_url'] = '#';

        // === Search query (sama seperti Unit) ===
        // === Handle Persistence ===
        // Search
        if ($this->input->get('q') !== null) {
            $q = trim($this->input->get('q', TRUE));
            $this->session->set_userdata('gardu_q', $q);
        } elseif ($this->input->get('per_page') !== null && $this->input->get('q') === null) {
            // If per_page is set but q is not, clear the session search (this is a reset)
            $this->session->unset_userdata('gardu_q');
            $q = '';
        } else {
            $q = $this->session->userdata('gardu_q') ?? '';
        }
        $data['q'] = $q;

        // Per Page
        if ($this->input->get('per_page') !== null) {
            $per_page = (int)$this->input->get('per_page');
            $this->session->set_userdata('gardu_per_page', $per_page);
        } else {
            $per_page = (int)($this->session->userdata('gardu_per_page') ?? 10);
        }
        $this->session->set_userdata('gardu_per_page', $per_page);

        // Konfigurasi paginasi
        $config['base_url'] = site_url('gardu_induk/index');
        $config['total_rows'] = $this->garduModel->count_all_gardu_induk($q); // <-- pakai q
        $config['per_page'] = $per_page;
        $config["uri_segment"] = 3;
        $config['use_page_numbers'] = TRUE;
        $config['reuse_query_string'] = TRUE; // pertahankan q & per_page

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
        $page = (is_numeric($page_segment) && (int)$page_segment > 0) ? (int)$page_segment : 1;
        if ($page <= 0) $page = 1;

        // Hitung offset
        $offset = ($page - 1) * $config['per_page'];

        // Inisialisasi paginasi
        $this->pagination->initialize($config);

        // Ambil data untuk halaman saat ini (pakai q)
        $data['gardu_induk'] = $this->garduModel->get_gardu_induk($config['per_page'], $offset, $q);
        $data['pagination'] = $this->pagination->create_links();
        $data['start_no'] = $offset + 1;
        $data['per_page'] = $per_page;
        $data['total_rows'] = $config['total_rows'];

        // Posisi global saat search (mirip Unit)
        if (!empty($q) && !empty($data['gardu_induk'])) {
            $ids = array_column($data['gardu_induk'], 'SSOTNUMBER');
            $data['positions'] = $this->garduModel->get_positions_for_ids($ids, $q);
        } else {
            $data['positions'] = [];
        }

        $this->load->view('layout/header', $data);
        $this->load->view('gardu_induk/vw_gardu_induk', $data);
        $this->load->view('layout/footer');
    }

    // =======================
    // TAMBAH DATA (CREATE)
    // =======================
    public function tambah()
    {
        // Check permission
        if (!can_create()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menambah data');
            redirect('Gardu_induk');
        }

        $data['judul'] = 'Tambah Data Gardu Induk';
        $data['parent_page_title'] = 'Asset';
        $data['parent_page_url'] = '#';

        if ($this->input->post()) {
            $dataInput = [
                'UNIT_LAYANAN'   => $this->input->post('UNIT_LAYANAN'),
                'GARDU_INDUK'    => $this->input->post('GARDU_INDUK'),
                'LONGITUDEX'     => $this->input->post('LONGITUDEX'),
                'LATITUDEY'      => $this->input->post('LATITUDEY'),
                'STATUS_OPERASI' => $this->input->post('STATUS_OPERASI'),
                'JML_TD'         => $this->input->post('JML_TD'),
                'INC'            => $this->input->post('INC'),
                'OGF'            => $this->input->post('OGF'),
                'SPARE'          => $this->input->post('SPARE'),
                'COUPLE'         => $this->input->post('COUPLE'),
                'BUS_RISER'      => $this->input->post('BUS_RISER'),
                'BBVT'           => $this->input->post('BBVT'),
                'PS'             => $this->input->post('PS'),
                'STATUS_SCADA'   => $this->input->post('STATUS_SCADA'),
                'IP_GATEWAY'     => $this->input->post('IP_GATEWAY'),
                'IP_RTU'         => $this->input->post('IP_RTU'),
                'MERK_RTU'       => $this->input->post('MERK_RTU'),
                'SN_RTU'         => $this->input->post('SN_RTU'),
                'THN_INTEGRASI'  => $this->input->post('THN_INTEGRASI'),
            ];

            $insert_id = $this->garduModel->insert_gardu_induk($dataInput);

            // Log aktivitas
            if ($insert_id) {
                log_create('gardu_induk', $insert_id, $dataInput['GARDU_INDUK']);
            }

            $this->session->set_flashdata('success', 'Data berhasil ditambahkan');
            redirect('gardu_induk');
        }

        $this->load->view('layout/header', $data);
        $this->load->view('gardu_induk/vw_tambah_gardu_induk', $data);
        $this->load->view('layout/footer');
    }

    // =======================
    // DETAIL DATA
    // =======================
    public function detail($id)
    {
        $data['judul'] = 'Detail Gardu Induk';
        $data['parent_page_title'] = 'Asset';
        $data['parent_page_url'] = '#';
        $data['gardu_induk'] = $this->garduModel->get_gardu_induk_by_id($id);

        if (!$data['gardu_induk']) {
            show_404();
        }

        // Pastikan semua key yang dipakai di view detail ada (default ke empty string)
        $expectedKeys = [
            'UP3_2D',
            'UNITNAME_UP3',
            'CXUNIT',
            'UNITNAME',
            'LOCATION',
            'SSOTNUMBER',
            'DESCRIPTION',
            'STATUS',
            'TUJDNUMBER',
            'ASSETCLASSHI',
            'SADDRESSCODE',
            'CXCLASSIFICATIONDESC',
            'PENYULANG',
            'PARENT',
            'PARENT_DESCRIPTION',
            'INSTALLDATE',
            'ACTUALOPRDATE',
            'CHANGEDATE',
            'CHANGEBY',
            'LATITUDEY',
            'LONGITUDEX',
            'FORMATTEDADDRESS',
            'STREETADDRESS',
            'CITY',
            'ISASSET',
            'STATUS_KEPEMILIKAN',
            // also include fields used in other forms/views to be safe
            'UNIT_LAYANAN',
            'GARDU_INDUK',
            'LONGITUDEX',
            'LATITUDEY',
            'STATUS_OPERASI',
            'JML_TD',
            'INC',
            'OGF',
            'SPARE',
            'COUPLE',
            'BUS_RISER',
            'BBVT',
            'PS',
            'STATUS_SCADA',
            'IP_GATEWAY',
            'IP_RTU',
            'MERK_RTU',
            'SN_RTU',
            'THN_INTEGRASI'
        ];

        foreach ($expectedKeys as $k) {
            if (!array_key_exists($k, $data['gardu_induk'])) {
                $data['gardu_induk'][$k] = '';
            }
        }

        // Backward-compatible alias: some views expect ID_GI; map it to SSOTNUMBER if available
        if (empty($data['gardu_induk']['ID_GI']) && !empty($data['gardu_induk']['SSOTNUMBER'])) {
            $data['gardu_induk']['ID_GI'] = $data['gardu_induk']['SSOTNUMBER'];
        }

        // Provide backward-compatible alias ID_GI for views that expect it
        if (empty($data['gardu_induk']['ID_GI']) && !empty($data['gardu_induk']['SSOTNUMBER'])) {
            $data['gardu_induk']['ID_GI'] = $data['gardu_induk']['SSOTNUMBER'];
        }

        $this->load->view('layout/header', $data);
        $this->load->view('gardu_induk/vw_detail_gardu_induk', $data);
        $this->load->view('layout/footer');
    }

    // =======================
    // HAPUS DATA (DELETE)
    // =======================
    public function hapus($id)
    {
        // Check permission
        if (!can_delete()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menghapus data');
            redirect('Gardu_induk');
        }

        $gardu = $this->garduModel->get_gardu_induk_by_id($id);
        if (!$gardu) {
            show_404();
        }

        $gardu_name = $gardu['GARDU_INDUK'] ?? 'ID-' . $id;
        $delete_success = $this->garduModel->delete_gardu_induk($id);

        // Log aktivitas
        if ($delete_success) {
            log_delete('gardu_induk', $id, $gardu_name);
        }

        $this->session->set_flashdata('success', 'Data berhasil dihapus');
        redirect('gardu_induk');
    }

    // Export semua data gardu induk ke CSV (human-friendly filename)
    public function export_csv()
    {
        // Block guest users from exporting
        if (function_exists('is_guest') && is_guest()) {
            $this->session->set_flashdata('error', 'Akses ditolak. Silakan login untuk mengunduh data.');
            redirect(strtolower($this->router->fetch_class()));
            return;
        }

        $all = $this->garduModel->get_all_gardu_induk();

        $label = 'Data Gardu Induk';
        $filename = $label . ' ' . date('d-m-Y') . '.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        // BOM
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

    // =======================
    // EDIT DATA (UPDATE)
    // =======================
    public function edit($id)
    {
        // Check permission
        if (!can_edit()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengubah data');
            redirect('Gardu_induk');
        }

        $data['judul'] = 'Edit Data Gardu Induk';
        $data['parent_page_title'] = 'Asset';
        $data['parent_page_url'] = '#';
        $data['gardu_induk'] = $this->garduModel->get_gardu_induk_by_id($id);

        if (!$data['gardu_induk']) {
            show_404();
        }

        // Pastikan key tersedia supaya view edit tidak memicu undefined index
        $expectedKeys = [
            'UNIT_LAYANAN',
            'GARDU_INDUK',
            'LONGITUDEX',
            'LATITUDEY',
            'STATUS_OPERASI',
            'JML_TD',
            'INC',
            'OGF',
            'SPARE',
            'COUPLE',
            'BUS_RISER',
            'BBVT',
            'PS',
            'STATUS_SCADA',
            'IP_GATEWAY',
            'IP_RTU',
            'MERK_RTU',
            'SN_RTU',
            'THN_INTEGRASI',
            'SSOTNUMBER',
            'UP3_2D',
            'UNITNAME',
            'LOCATION'
        ];
        foreach ($expectedKeys as $k) {
            if (!array_key_exists($k, $data['gardu_induk'])) {
                $data['gardu_induk'][$k] = '';
            }
        }

        if ($this->input->post()) {
            $dataUpdate = [
                'UNIT_LAYANAN'   => $this->input->post('UNIT_LAYANAN'),
                'GARDU_INDUK'    => $this->input->post('GARDU_INDUK'),
                'LONGITUDEX'     => $this->input->post('LONGITUDEX'),
                'LATITUDEY'      => $this->input->post('LATITUDEY'),
                'STATUS_OPERASI' => $this->input->post('STATUS_OPERASI'),
                'JML_TD'         => $this->input->post('JML_TD'),
                'INC'            => $this->input->post('INC'),
                'OGF'            => $this->input->post('OGF'),
                'SPARE'          => $this->input->post('SPARE'),
                'COUPLE'         => $this->input->post('COUPLE'),
                'BUS_RISER'      => $this->input->post('BUS_RISER'),
                'BBVT'           => $this->input->post('BBVT'),
                'PS'             => $this->input->post('PS'),
                'STATUS_SCADA'   => $this->input->post('STATUS_SCADA'),
                'IP_GATEWAY'     => $this->input->post('IP_GATEWAY'),
                'IP_RTU'         => $this->input->post('IP_RTU'),
                'MERK_RTU'       => $this->input->post('MERK_RTU'),
                'SN_RTU'         => $this->input->post('SN_RTU'),
                'THN_INTEGRASI'  => $this->input->post('THN_INTEGRASI'),
            ];

            $update_success = $this->garduModel->update_gardu_induk($id, $dataUpdate);

            // Log aktivitas
            if ($update_success) {
                log_update('gardu_induk', $id, $dataUpdate['GARDU_INDUK']);
            }

            $this->session->set_flashdata('success', 'Data berhasil diperbarui');
            redirect('gardu_induk');
        }

        $this->load->view('layout/header', $data);
        $this->load->view('gardu_induk/vw_edit_gardu_induk', $data);
        $this->load->view('layout/footer');
    }

    public function update()
    {
        // Check permission
        if (!can_edit()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengubah data');
            redirect('Gardu_induk');
        }

        // Determine submitted identifier(s)
        $submittedId = $this->input->post('SSOTNUMBER') ? $this->input->post('SSOTNUMBER') : $this->input->post('ID_GI');
        // Use the original SSOTNUMBER (hidden field) for WHERE so changing SSOTNUMBER is supported
        $original = $this->input->post('original_SSOTNUMBER') ? $this->input->post('original_SSOTNUMBER') : null;
        $dataUpdate = [
            'UP3_2D' => $this->input->post('UP3_2D'),
            'UNITNAME_UP3' => $this->input->post('UNITNAME_UP3'),
            'CXUNIT' => $this->input->post('CXUNIT'),
            'UNITNAME' => $this->input->post('UNITNAME'),
            'LOCATION' => $this->input->post('LOCATION'),
            'DESCRIPTION' => $this->input->post('DESCRIPTION'),
            'STATUS' => $this->input->post('STATUS'),
            'TUJDNUMBER' => $this->input->post('TUJDNUMBER'),
            'ASSETCLASSHI' => $this->input->post('ASSETCLASSHI'),
            'SADDRESSCODE' => $this->input->post('SADDRESSCODE'),
            'CXCLASSIFICATIONDESC' => $this->input->post('CXCLASSIFICATIONDESC'),
            'PENYULANG' => $this->input->post('PENYULANG'),
            'PARENT' => $this->input->post('PARENT'),
            'PARENT_DESCRIPTION' => $this->input->post('PARENT_DESCRIPTION'),
            'INSTALLDATE' => $this->input->post('INSTALLDATE'),
            'ACTUALOPRDATE' => $this->input->post('ACTUALOPRDATE'),
            'CHANGEDATE' => $this->input->post('CHANGEDATE'),
            'CHANGEBY' => $this->input->post('CHANGEBY'),
            'LONGITUDEX' => $this->input->post('LONGITUDEX'),
            'LATITUDEY' => $this->input->post('LATITUDEY'),
            // allow updating SSOTNUMBER (primary key) if needed
            'SSOTNUMBER' => $this->input->post('SSOTNUMBER'),
            'FORMATTEDADDRESS' => $this->input->post('FORMATTEDADDRESS'),
            'STREETADDRESS' => $this->input->post('STREETADDRESS'),
            'CITY' => $this->input->post('CITY'),
            'ISASSET' => $this->input->post('ISASSET'),
            'STATUS_KEPEMILIKAN' => $this->input->post('STATUS_KEPEMILIKAN')
        ];

        // Save using original identifier if present; otherwise use submittedId
        $whereId = $original ? $original : $submittedId;
        $update_success = $this->garduModel->update_gardu_induk($whereId, $dataUpdate);

        // Log aktivitas
        if ($update_success) {
            log_update('gardu_induk', $whereId, $dataUpdate['UNITNAME']);
        }

        $this->session->set_flashdata('success', 'Data berhasil diperbarui');
        redirect('gardu_induk');
    }
}
