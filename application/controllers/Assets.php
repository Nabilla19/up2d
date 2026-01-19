<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assets extends CI_Controller
{
    private $tables = [
        // table => [title, icon]
        'unit' => ['Unit', 'fa-building text-success'],
        'gi' => ['Gardu Induk (GI)', 'fa-bolt text-warning'],
        'gi_cell' => ['GI Cell', 'fa-wave-square text-info'],
        'gh' => ['Gardu Hubung (GH)', 'fa-network-wired text-primary'],
        'gh_cell' => ['GH Cell', 'fa-square text-secondary'],
        'pembangkit' => ['Pembangkit', 'fa-industry text-danger'],
        'kit_cell' => ['KIT Cell', 'fa-microchip text-primary'],
        'lbs_recloser' => ['LBS / Recloser', 'fa-toggle-on text-warning'],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Asset_model');
        $this->load->helper('url');
    }

    public function index()
    {
        // Redirect to first table for convenience
        redirect('assets/table/unit');
    }

    public function table($name)
    {
        if (!isset($this->tables[$name])) show_404();

        $meta = $this->tables[$name];
        $data['title'] = $meta[0];
        $data['icon'] = $meta[1];
        $data['table'] = $name;
        $data['tables'] = $this->tables; // for sidebar highlighting if needed
        $data['parent_page_title'] = 'Asset';
        $data['parent_page_url'] = '#';

        try {
            $result = $this->Asset_model->get_table_data($name, 50);
            $data['fields'] = $result['fields'];
            $data['rows'] = $result['rows'];
        } catch (Exception $e) {
            $data['fields'] = [];
            $data['rows'] = [];
            $data['error'] = $e->getMessage();
        }

        $this->load->view('layout/header');
        $this->load->view('assets/vw_list', $data);
        $this->load->view('layout/footer');
    }
}
