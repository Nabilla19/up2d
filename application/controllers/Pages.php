<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pages extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url']);
        $this->load->library(['session']);
    }

    public function profile()
    {
        // require login
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Silakan masuk untuk melihat profil.');
            redirect('login');
        }

        $data['page_title'] = 'Profile';
        $data['page_icon'] = 'fas fa-user';
        $this->load->view('layout/header', $data);
        $this->load->view('pages/profile', $data);
        $this->load->view('layout/footer');
    }

    public function sign_up()
    {
        // minimal sign-up placeholder (can be extended)
        $data['page_title'] = 'Sign Up';
        $data['page_icon'] = 'fas fa-user-plus';
        $this->load->view('layout/header', $data);
        $this->load->view('pages/sign_up', $data);
        $this->load->view('layout/footer');
    }
}
