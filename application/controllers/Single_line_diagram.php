<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller for Single Line Diagram
 *
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Single_line_diagram_model $sldModel
 * @property CI_Pagination $pagination
 * @property CI_Upload $upload
 * @property CI_URI $uri
 * @property CI_Config $config
 */
class Single_Line_Diagram extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Single_line_diagram_model', 'sldModel');

        // ✅ TAMBAHAN: Notifikasi model untuk log aktivitas
        $this->load->model('Notifikasi_model', 'notifModel');

        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'pagination', 'upload']);
    }

    // ✅ TAMBAHAN: LOG AKTIVITAS
    private function _log($jenis, $module = null, $record_id = null, $record_name = null)
    {
        $user_id = (int) $this->session->userdata('user_id');
        if (!$user_id) return;

        $email = (string) ($this->session->userdata('email') ?? '');
        $role  = (string) ($this->session->userdata('user_role') ?? $this->session->userdata('role') ?? '');

        $this->notifModel->log_aktivitas(
            $user_id,
            $email,
            $role,
            $jenis,
            $module,
            $record_id,
            $record_name,
            $this->input->ip_address(),
            (string) $this->input->user_agent()
        );
    }

    // =======================
    // LIST DATA (READ)
    // =======================
    public function index()
    {
        $data['judul'] = 'Data Single Line Diagram';
        
        // Navbar data
        $data['page_title'] = 'Data Single Line Diagram';
        $data['page_icon'] = 'fas fa-project-diagram';
        $data['parent_page_title'] = 'Operasi';
        $data['parent_page_url'] = '#';

        // Per-page dari query string atau default (allowed values)
        $allowedPer = [5,10,25,50,100,500];
        $reqPer = (int) $this->input->get('per_page', TRUE);
        $per_page = in_array($reqPer, $allowedPer) ? $reqPer : 5; // default 5

        // Konfigurasi pagination (menggunakan query string)
        $config['base_url'] = site_url('Single_Line_Diagram/index') . '?';
        $config['total_rows'] = $this->sldModel->count_all_sld();
        $config['per_page'] = $per_page;
        $config['reuse_query_string'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';

        // Tampilan pagination
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-end">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['next_link'] = '&raquo;';
        $config['prev_link'] = '&laquo;';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');

        // Hitung offset berdasarkan page query string
        // Note: when page_query_string = TRUE and use_page_numbers is FALSE (default),
        // the pagination links will include the offset (0, per_page, 2*per_page, ...)
        $pageParam = $this->input->get($config['query_string_segment']);
        if (!empty($config['use_page_numbers'])) {
            // pageParam is a page number (1,2,3...)
            $pageNum = (is_numeric($pageParam) && $pageParam > 0) ? (int)$pageParam : 1;
            $offset = ($pageNum - 1) * $config['per_page'];
        } else {
            // pageParam is the offset (0, per_page, ...)
            $offset = (is_numeric($pageParam) && $pageParam >= 0) ? (int)$pageParam : 0;
        }

        $this->pagination->initialize($config);

        $data['sld'] = $this->sldModel->get_sld($config['per_page'], $offset);
        $data['pagination'] = $this->pagination->create_links();
        $data['start_no'] = $offset + 1;
        $data['total_rows'] = $config['total_rows'];
        $data['per_page'] = $per_page;

        $this->load->view('layout/header', $data);
        $this->load->view('single_line_diagram/vw_single_line_diagram', $data);
        $this->load->view('layout/footer');
    }

    // =======================
    // TAMBAH DATA (CREATE)
    // =======================
    public function tambah()
    {
        if (!can_create()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menambah data');
            redirect($this->router->fetch_class());
        }

        $data['judul'] = 'Tambah Single Line Diagram';
        $data['parent_page_title'] = 'Operasi';
        $data['parent_page_url'] = '#';
        $this->load->model('Unit_model', 'unitModel');
        $data['unit'] = $this->unitModel->get_all_units(); // use existing model method

        if (!$this->input->post()) {
            $this->load->view('layout/header', $data);
            $this->load->view('single_line_diagram/vw_tambah_single_line_diagram', $data);
            $this->load->view('layout/footer');
            return;
        }

        // Konfigurasi upload file
        $config['upload_path']   = './uploads/sld/';
        $config['allowed_types'] = 'pdf';
        $config['max_size']      = 0;
        $config['encrypt_name']  = TRUE;

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $this->upload->initialize($config);
        $file_name = '';

        if (!empty($_FILES['FILE_PDF']['name'])) {
            if ($this->upload->do_upload('FILE_PDF')) {
                $file_name = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('Single_Line_Diagram/tambah');
                return;
            }
        }

        $dataInput = [
            'ID_UNIT'        => $this->input->post('ID_UNIT', TRUE), // ✅ tambahkan field unit
            'NAMA_GI'        => $this->input->post('NAMA_GI', TRUE),
            'NAMA_PENYULANG' => $this->input->post('NAMA_PENYULANG', TRUE),
            'FILE_PDF'       => $file_name,
            'CREATED_BY'     => $this->input->post('CREATED_BY', TRUE),
            'CREATED_AT'     => date('Y-m-d H:i:s')
        ];

        if ($this->sldModel->insert_sld($dataInput)) {

            // ✅ TAMBAHAN: log create
            $this->_log('create', 'Single_Line_Diagram', null, $dataInput['NAMA_GI'] ?? null);

            $this->session->set_flashdata('success', 'Data Single Line Diagram berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data Single Line Diagram.');
        }

        redirect('Single_Line_Diagram');
    }

    // =======================
    // EDIT DATA (UPDATE)
    // =======================
    public function edit($id)
    {
        if (!can_edit()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengubah data');
            redirect($this->router->fetch_class());
        }

        $data['judul'] = 'Edit Single Line Diagram';
        $data['parent_page_title'] = 'Operasi';
        $data['parent_page_url'] = '#';
        $data['sld'] = $this->sldModel->get_sld_by_id($id);

        if (!$data['sld']) {
            show_404();
        }

        // load unit list for dropdown in edit form
        $this->load->model('Unit_model', 'unitModel');
        $data['unit'] = $this->unitModel->get_all_units();

        if (!$this->input->post()) {
            $this->load->view('layout/header', $data);
            $this->load->view('single_line_diagram/vw_edit_single_line_diagram', $data);
            $this->load->view('layout/footer');
            return;
        }

        $config['upload_path']   = './uploads/sld/';
        $config['allowed_types'] = 'pdf';
        $config['max_size']      = 0;
        $config['encrypt_name']  = TRUE;

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $this->upload->initialize($config);
        $file_name = $data['sld']['FILE_PDF'];

        if (!empty($_FILES['FILE_PDF']['name'])) {
            if ($this->upload->do_upload('FILE_PDF')) {
                if ($file_name && file_exists('./uploads/sld/' . $file_name)) {
                    unlink('./uploads/sld/' . $file_name);
                }
                $file_name = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('Single_Line_Diagram/edit/' . $id);
                return;
            }
        }

        $dataUpdate = [
            'ID_UNIT'        => $this->input->post('ID_UNIT', TRUE),
            'NAMA_GI'        => $this->input->post('NAMA_GI', TRUE),
            'NAMA_PENYULANG' => $this->input->post('NAMA_PENYULANG', TRUE),
            'FILE_PDF'       => $file_name,
            'CREATED_BY'     => $this->input->post('CREATED_BY', TRUE)
        ];

        $this->sldModel->update_sld($id, $dataUpdate);

        // ✅ TAMBAHAN: log update
        $this->_log('update', 'Single_Line_Diagram', $id, $dataUpdate['NAMA_GI'] ?? null);

        $this->session->set_flashdata('success', 'Data Single Line Diagram berhasil diperbarui.');
        redirect('Single_Line_Diagram');
    }

    // =======================
    // HAPUS DATA (DELETE)
    // =======================
    public function hapus($id)
    {
        if (!can_delete()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menghapus data');
            redirect($this->router->fetch_class());
        }

        $sld = $this->sldModel->get_sld_by_id($id);
        if (!$sld) {
            show_404();
        }

        if (!empty($sld['FILE_PDF']) && file_exists('./uploads/sld/' . $sld['FILE_PDF'])) {
            unlink('./uploads/sld/' . $sld['FILE_PDF']);
        }

        $this->sldModel->delete_sld($id);

        // ✅ TAMBAHAN: log delete
        $this->_log('delete', 'Single_Line_Diagram', $id, $sld['NAMA_GI'] ?? null);

        $this->session->set_flashdata('success', 'Data Single Line Diagram berhasil dihapus.');
        redirect('Single_Line_Diagram');
    }
}
