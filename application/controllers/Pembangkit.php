<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller for Pembangkit (Power Plant)
 *
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Pembangkit_model $pembangkit_model
 * @property CI_Pagination $pagination
 * @property CI_URI $uri
 * @property CI_Config $config
 */
class Pembangkit extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // ✅ Pakai alias biar konsisten & aman dari case-sensitive
        $this->load->model('Pembangkit_model', 'pembangkit_model');

        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'pagination']);
    }

    public function index()
    {
        $data['title'] = 'Data Pembangkit';

        $data['page_title'] = 'Data Pembangkit';
        $data['page_icon']  = 'fas fa-industry';
        $data['parent_page_title'] = 'Asset';
        $data['parent_page_url'] = '#';

        // ✅ ambil search dari query string
        // PERSISTENCE: Search
        if ($this->input->get('search') !== null) {
            $search = trim($this->input->get('search', TRUE));
            $this->session->set_userdata('pembangkit_search', $search);
        } else {
            $search = $this->session->userdata('pembangkit_search') ?? '';
        }
        $data['search'] = $search;

        // Konfigurasi paginasi
        $config['base_url'] = site_url('pembangkit/index');

        // ✅ total rows harus ikut search
        $config['total_rows'] = $this->pembangkit_model->count_all_pembangkit($search);

        // Per-page selector (from ?per_page)
        // PERSISTENCE: Per Page
        $allowedPerPage = [5, 10, 25, 50, 100, 500];
        if ($this->input->get('per_page') !== null) {
            $requestedPer = (int)$this->input->get('per_page');
            $this->session->set_userdata('pembangkit_per_page', $requestedPer);
        }

        $savedPer = (int)$this->session->userdata('pembangkit_per_page');
        $defaultPer = (int)$this->config->item('default_per_page');
        if($defaultPer <= 0) $defaultPer = 10;
        
        if ($savedPer > 0) {
            $perPage = in_array($savedPer, $allowedPerPage) ? $savedPer : $defaultPer;
        } else {
            $perPage = $defaultPer;
        }
        $this->session->set_userdata('pembangkit_per_page', $perPage);

        $config['per_page'] = $perPage;

        // page numbers via URI segment
        $config["uri_segment"] = 3;
        $config['use_page_numbers'] = TRUE;

        // ✅ pastikan query string (search/per_page) ikut kebawa ke link pagination
        $config['reuse_query_string'] = TRUE;

        // suffix + first_url (biar First/links juga bawa query string)
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

        // Ambil nomor halaman dari URI (segment 3)
        $page_segment = $this->uri->segment(3);
        $page = (is_numeric($page_segment) && (int)$page_segment > 0) ? (int)$page_segment : 1;
        if ($page <= 0) {
            $page = 1;
        }

        // Hitung offset
        $offset = ($page - 1) * $config['per_page'];

        // Inisialisasi paginasi
        $this->pagination->initialize($config);

        // ✅ get data ikut search
        $data['pembangkit'] = $this->pembangkit_model->get_pembangkit($config['per_page'], $offset, $search);

        $data['pagination'] = $this->pagination->create_links();
        $data['start_no']   = $offset + 1;
        $data['total_rows'] = $config['total_rows'];
        $data['per_page']   = $perPage;

        $this->load->view('layout/header');
        $this->load->view('pembangkit/vw_pembangkit', $data);
        $this->load->view('layout/footer');
    }

    public function tambah()
    {
        if (!can_create()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menambah data');
            redirect($this->router->fetch_class());
        }

        if ($this->input->post()) {
            $insertData = [
                'UNIT_LAYANAN'   => $this->input->post('UNIT_LAYANAN'),
                'PEMBANGKIT'     => $this->input->post('PEMBANGKIT'),
                'LONGITUDEX'     => $this->input->post('LONGITUDEX'),
                'LATITUDEY'      => $this->input->post('LATITUDEY'),
                'STATUS_OPERASI' => $this->input->post('STATUS_OPERASI'),
                'INC'            => $this->input->post('INC'),
                'OGF'            => $this->input->post('OGF'),
                'SPARE'          => $this->input->post('SPARE'),
                'COUPLE'         => $this->input->post('COUPLE'),
                'STATUS_SCADA'   => $this->input->post('STATUS_SCADA'),
                'IP_GATEWAY'     => $this->input->post('IP_GATEWAY'),
                'IP_RTU'         => $this->input->post('IP_RTU'),
                'MERK_RTU'       => $this->input->post('MERK_RTU'),
                'SN_RTU'         => $this->input->post('SN_RTU'),
                'THN_INTEGRASI'  => $this->input->post('THN_INTEGRASI'),
            ];

            $this->pembangkit_model->insert_pembangkit($insertData);
            $this->session->set_flashdata('success', 'Data pembangkit berhasil ditambahkan!');
            redirect('Pembangkit');
        } else {
            $data['title'] = 'Tambah Data Pembangkit';
            $data['parent_page_title'] = 'Asset';
            $data['parent_page_url'] = '#';
            $this->load->view('layout/header');
            $this->load->view('pembangkit/vw_tambah_pembangkit', $data);
            $this->load->view('layout/footer');
        }
    }

    public function edit($id)
    {
        if (!can_edit()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengubah data');
            redirect($this->router->fetch_class());
        }

        $data['pembangkit'] = $this->pembangkit_model->get_pembangkit_by_id($id);
        if (empty($data['pembangkit'])) {
            show_404();
        }

        if ($this->input->post()) {
            $updateData = [
                'UNIT_LAYANAN'   => $this->input->post('UNIT_LAYANAN'),
                'PEMBANGKIT'     => $this->input->post('PEMBANGKIT'),
                'LONGITUDEX'     => $this->input->post('LONGITUDEX'),
                'LATITUDEY'      => $this->input->post('LATITUDEY'),
                'STATUS_OPERASI' => $this->input->post('STATUS_OPERASI'),
                'INC'            => $this->input->post('INC'),
                'OGF'            => $this->input->post('OGF'),
                'SPARE'          => $this->input->post('SPARE'),
                'COUPLE'         => $this->input->post('COUPLE'),
                'STATUS_SCADA'   => $this->input->post('STATUS_SCADA'),
                'IP_GATEWAY'     => $this->input->post('IP_GATEWAY'),
                'IP_RTU'         => $this->input->post('IP_RTU'),
                'MERK_RTU'       => $this->input->post('MERK_RTU'),
                'SN_RTU'         => $this->input->post('SN_RTU'),
                'THN_INTEGRASI'  => $this->input->post('THN_INTEGRASI'),
            ];

            $update_success = $this->pembangkit_model->update_pembangkit($id, $updateData);

            if ($update_success) {
                log_update('pembangkit', $id, $updateData['PEMBANGKIT']);
            }

            $this->session->set_flashdata('success', 'Data pembangkit berhasil diperbarui!');
            redirect('Pembangkit');
        } else {
            $data['title'] = 'Edit Data Pembangkit';
            $data['parent_page_title'] = 'Asset';
            $data['parent_page_url'] = '#';
            $this->load->view('layout/header');
            $this->load->view('pembangkit/vw_edit_pembangkit', $data);
            $this->load->view('layout/footer');
        }
    }

    public function detail($id)
    {
        $data['pembangkit'] = $this->pembangkit_model->get_pembangkit_by_id($id);
        if (empty($data['pembangkit'])) {
            show_404();
        }

        $data['title'] = 'Detail Data Pembangkit';
        $data['parent_page_title'] = 'Asset';
        $data['parent_page_url'] = '#';
        $this->load->view('layout/header');
        $this->load->view('pembangkit/vw_detail_pembangkit', $data);
        $this->load->view('layout/footer');
    }

    public function hapus($id)
    {
        if (!can_delete()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menghapus data');
            redirect($this->router->fetch_class());
        }

        $pembangkit = $this->pembangkit_model->get_pembangkit_by_id($id);
        $pembangkit_name = $pembangkit ? ($pembangkit['PEMBANGKIT'] ?? 'ID-' . $id) : 'ID-' . $id;

        $delete_success = $this->pembangkit_model->delete_pembangkit($id);

        if ($delete_success) {
            log_delete('pembangkit', $id, $pembangkit_name);
        }

        $this->session->set_flashdata('success', 'Data pembangkit berhasil dihapus!');
        redirect('Pembangkit');
    }

    public function export_csv()
    {        // Block guest users from exporting
        if (function_exists('is_guest') && is_guest()) {
            $this->session->set_flashdata('error', 'Akses ditolak. Silakan login untuk mengunduh data.');
            redirect(strtolower($this->router->fetch_class()));
            return;
        }
        $all = $this->pembangkit_model->get_all_pembangkit();
        $label = 'Data Pembangkit';
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
