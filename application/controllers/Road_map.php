<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller for Road Map
 *
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Road_map_model $roadmapModel
 * @property CI_Pagination $pagination
 * @property CI_Upload $upload
 * @property CI_URI $uri
 * @property CI_Config $config
 */
class Road_map extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Road_map_model', 'roadmapModel');
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'pagination', 'upload']);
    }

    // ==========================
    // LIST DATA (READ)
    // ==========================
    public function index()
    {
        $data['judul'] = 'Data Road Map';
        
        // Navbar data
        $data['page_title'] = 'Data Road Map';
        $data['page_icon'] = 'fas fa-road';
        $data['parent_page_title'] = 'Pustaka';
        $data['parent_page_url'] = '#';

        // Konfigurasi pagination
        $config['base_url'] = site_url('road_map/index');
        
        // Ambil kata kunci pencarian dari query string
        $search = $this->input->get('search', TRUE);
        
        $config['total_rows'] = $this->roadmapModel->count_all_roadmap($search);
        
        // support per_page from query string (allowed values)
        $allowedPer = [5,10,25,50,100,500];
        $reqPer = (int) $this->input->get('per_page', TRUE);
        $perPage = in_array($reqPer, $allowedPer) ? $reqPer : 5; // default 5
        $config['per_page'] = $perPage;
        $config['reuse_query_string'] = TRUE;
        $config['uri_segment'] = 3;
        $config['use_page_numbers'] = TRUE;

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
        $config['attributes'] = ['class' => 'page-link'];

        $page_segment = $this->uri->segment(3);
        $page = (is_numeric($page_segment) && $page_segment > 0) ? (int)$page_segment : 1;
        $offset = ($page - 1) * $config['per_page'];

        $this->pagination->initialize($config);

        $data['road_map'] = $this->roadmapModel->get_roadmap($config['per_page'], $offset, $search);
        $data['pagination'] = $this->pagination->create_links();
        $data['start_no'] = $offset + 1;
        $data['per_page'] = $perPage;
        $data['total_rows'] = $config['total_rows'];
        $data['search'] = $search;

        $this->load->view('layout/header', $data);
        $this->load->view('road_map/vw_road_map', $data);
        $this->load->view('layout/footer');
    }

    // ==========================
    // TAMBAH DATA (CREATE)
    // ==========================
    public function tambah()
    {
        if (!can_create()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menambah data');
            redirect($this->router->fetch_class());
        }

        $data['judul'] = 'Tambah Road Map';
        $data['parent_page_title'] = 'Pustaka';
        $data['parent_page_url'] = '#';

        if (!$this->input->post()) {
            $this->load->view('layout/header', $data);
            $this->load->view('road_map/vw_tambah_road_map', $data);
            $this->load->view('layout/footer');
            return;
        }

        // Konfigurasi upload
        $config['upload_path']   = './uploads/roadmap/';
        $config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|ppt|pptx';
        $config['max_size']      = 0; // tidak dibatasi
        $config['encrypt_name']  = TRUE;

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $this->upload->initialize($config);
        $file_name = '';

        if (!empty($_FILES['FILE_ROADMAP']['name'])) {
            if ($this->upload->do_upload('FILE_ROADMAP')) {
                $file_name = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('road_map/tambah');
                return;
            }
        }

        $dataInput = [
            'NAMA_FILE'     => $this->input->post('NAMA_FILE', TRUE),
            'CREATED_BY'    => $this->input->post('CREATED_BY', TRUE),
            'FILE_ROADMAP'  => $file_name,
            'CREATED_AT'    => date('Y-m-d H:i:s')
        ];

        if ($this->roadmapModel->insert_roadmap($dataInput)) {
            $this->session->set_flashdata('success', 'Data Road Map berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data Road Map.');
        }

        redirect('road_map');
    }

    // ==========================
    // EDIT DATA (UPDATE)
    // ==========================
    public function edit($id)
    {
        if (!can_edit()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengubah data');
            redirect($this->router->fetch_class());
        }

        $data['judul'] = 'Edit Road Map';
        $data['parent_page_title'] = 'Pustaka';
        $data['parent_page_url'] = '#';
        $data['road_map'] = $this->roadmapModel->get_roadmap_by_id($id);

        if (!$data['road_map']) {
            show_404();
        }

        if (!$this->input->post()) {
            $this->load->view('layout/header', $data);
            $this->load->view('road_map/vw_edit_road_map', $data);
            $this->load->view('layout/footer');
            return;
        }

        $config['upload_path']   = './uploads/roadmap/';
        $config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|ppt|pptx';
        $config['max_size']      = 0;
        $config['encrypt_name']  = TRUE;

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $this->upload->initialize($config);
        $file_name = $data['road_map']['FILE_ROADMAP'];

        if (!empty($_FILES['FILE_ROADMAP']['name'])) {
            if ($this->upload->do_upload('FILE_ROADMAP')) {
                if ($file_name && file_exists('./uploads/roadmap/' . $file_name)) {
                    unlink('./uploads/roadmap/' . $file_name);
                }
                $file_name = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('road_map/edit/' . $id);
                return;
            }
        }

        $dataUpdate = [
            'NAMA_FILE'     => $this->input->post('NAMA_FILE', TRUE),
            'CREATED_BY'    => $this->input->post('CREATED_BY', TRUE),
            'FILE_ROADMAP'  => $file_name
        ];

        $this->roadmapModel->update_roadmap($id, $dataUpdate);
        $this->session->set_flashdata('success', 'Data Road Map berhasil diperbarui.');
        redirect('road_map');
    }

    // ==========================
    // HAPUS DATA (DELETE)
    // ==========================
    public function hapus($id)
    {
        if (!can_delete()) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menghapus data');
            redirect($this->router->fetch_class());
        }

        $roadmap = $this->roadmapModel->get_roadmap_by_id($id);
        if (!$roadmap) {
            show_404();
        }

        if (!empty($roadmap['FILE_ROADMAP']) && file_exists('./uploads/roadmap/' . $roadmap['FILE_ROADMAP'])) {
            unlink('./uploads/roadmap/' . $roadmap['FILE_ROADMAP']);
        }

        $this->roadmapModel->delete_roadmap($id);
        $this->session->set_flashdata('success', 'Data Road Map berhasil dihapus.');
        redirect('road_map');
    }
}
