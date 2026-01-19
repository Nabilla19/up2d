<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller for Anggaran Operasi
 *
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Progress_kontrak_op_model $progress_kontrak_model
 * @property Rekomposisi_op_model $rekomposisi_model
 * @property Monitoring_op_model $monitoring_model
 * @property CI_URI $uri
 * @property CI_Config $config
 */
class Operasi extends CI_Controller {
    /** @var Progress_kontrak_op_model */
    public $progress_kontrak_model;
    /** @var Rekomposisi_op_model */
    public $rekomposisi_model;
    /** @var Monitoring_op_model */
    public $monitoring_model;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Progress_kontrak_op_model', 'progress_kontrak_model');
        $this->load->model('Rekomposisi_op_model', 'rekomposisi_model');
        $this->load->model('Monitoring_op_model', 'monitoring_model');
        $this->load->helper('url');
    }

    /**
     * Show Progress Kontrak table for Anggaran Operasi
     */
    public function progress_kontrak()
    {
        $data['title'] = 'Progress Kontrak';
        $data['icon'] = 'fa-cogs text-success';
        
        // Navbar data
        $data['page_title'] = 'Progress Kontrak (Operasi)';
        $data['page_icon'] = 'fas fa-chart-line';
        $data['parent_page_title'] = 'Operasi';
        $data['parent_page_url'] = '#';

        try {
            $result = $this->progress_kontrak_model->get_table_data(200);
            $data['fields'] = $result['fields'];
            $data['rows'] = $result['rows'];
        } catch (Exception $e) {
            $data['fields'] = [];
            $data['rows'] = [];
            $data['error'] = $e->getMessage();
        }

        $this->load->view('layout/header');
        $this->load->view('anggaran/operasi/progress_kontrak', $data);
        $this->load->view('layout/footer');
    }

    public function rekomposisi()
    {
        // For now reuse the same layout as progress_kontrak but with a different title
        $data['title'] = 'Rekomposisi';
        $data['icon'] = 'fa-cogs text-success';
        
        // Navbar data
        $data['page_title'] = 'Rekomposisi (Operasi)';
        $data['page_icon'] = 'fas fa-exchange-alt';
        $data['parent_page_title'] = 'Operasi';
        $data['parent_page_url'] = '#';
        
        try {
            $result = $this->rekomposisi_model->get_table_data(200);
            $data['fields'] = $result['fields'];
            $data['rows'] = $result['rows'];
        } catch (Exception $e) {
            $data['fields'] = [];
            $data['rows'] = [];
            $data['error'] = $e->getMessage();
        }
        $this->load->view('layout/header');
        $this->load->view('anggaran/operasi/rekomposisi', $data);
        $this->load->view('layout/footer');
    }

    public function monitoring()
    {
        $data['title'] = 'Monitoring';
        $data['icon'] = 'fa-cogs text-success';
        
        // Navbar data
        $data['page_title'] = 'Monitoring (Operasi)';
        $data['page_icon'] = 'fas fa-desktop';
        $data['parent_page_title'] = 'Operasi';
        $data['parent_page_url'] = '#';
        
        try {
            $result = $this->monitoring_model->get_table_data(500);
            $data['fields'] = $result['fields'];
            $data['rows'] = $result['rows'];
        } catch (Exception $e) {
            $data['fields'] = [];
            $data['rows'] = [];
            $data['error'] = $e->getMessage();
        }
        $this->load->view('layout/header');
        $this->load->view('anggaran/operasi/monitoring', $data);
        $this->load->view('layout/footer');
    }

    public function add_progress_kontrak()
    {
        $data['parent_page_title'] = 'Operasi';
        $data['parent_page_url'] = '#';
        $this->load->view('layout/header', $data);
        $this->load->view('anggaran/operasi/vw_add_progress_kontrak');
        $this->load->view('layout/footer');
    }

    public function store_progress_kontrak()
    {
        // Prepare data with spaces in field names (matching database schema)
        $data = array(
            'SUMBER DANA' => $this->input->post('SUMBER_DANA'),
            'SKKO' => $this->input->post('SKKO'),
            'SUB POS' => $this->input->post('SUB_POS'),
            'DRP' => $this->input->post('DRP'),
            'URAIAN KONTRAK / PEKERJAAN' => $this->input->post('URAIAN_KONTRAK_PEKERJAAN'),
            'USER' => $this->input->post('USER'),
            'PAGU ANG/RAB USER' => $this->input->post('PAGU_ANG_RAB_USER'),
            'KOMITMENT ND' => $this->input->post('KOMITMENT_ND'),
            'RENC AKHIR KONTRAK' => $this->input->post('RENC_AKHIR_KONTRAK'),
            'TGL ND/AMS' => $this->input->post('TGL_ND_AMS'),
            'NOMOR ND / AMS' => $this->input->post('NOMOR_ND_AMS'),
            'KETERANGAN' => $this->input->post('KETERANGAN'),
            'TAHAP KONTRAK' => $this->input->post('TAHAP_KONTRAK'),
            'PROGNOSA' => $this->input->post('PROGNOSA'),
            'NO SPK / SPB / KONTRAK' => $this->input->post('NO_SPK_SPB_KONTRAK'),
            'REKANAN' => $this->input->post('REKANAN'),
            'TGL KONTRAK' => $this->input->post('TGL_KONTRAK'),
            'TGL AKHIR KONTRAK' => $this->input->post('TGL_AKHIR_KONTRAK'),
            'NILAI KONTRAK TOTAL' => $this->input->post('NILAI_KONTRAK_TOTAL'),
            'NILAI KONTRAK TAHUN BERJALAN' => $this->input->post('NILAI_KONTRAK_TAHUN_BERJALAN'),
            'TGL BAYAR' => $this->input->post('TGL_BAYAR'),
            'ANGGARAN TERPAKAI' => $this->input->post('ANGGARAN_TERPAKAI'),
            'SISA ANGGARAN' => $this->input->post('SISA_ANGGARAN'),
            'STATUS KONTRAK' => $this->input->post('STATUS_KONTRAK'),
            'BLN KTRK1' => $this->input->post('BLN_KTRK1'),
            'BLN KTRK2' => $this->input->post('BLN_KTRK2'),
            'BLN KTRK3' => $this->input->post('BLN_KTRK3'),
            'BLN KTRK4' => $this->input->post('BLN_KTRK4'),
            'BLN KTRK5' => $this->input->post('BLN_KTRK5'),
            'BLN KTRK6' => $this->input->post('BLN_KTRK6'),
            'BLN KTRK7' => $this->input->post('BLN_KTRK7'),
            'BLN KTRK8' => $this->input->post('BLN_KTRK8'),
            'BLN KTRK9' => $this->input->post('BLN_KTRK9'),
            'BLN KTRK10' => $this->input->post('BLN_KTRK10'),
            'BLN KTRK11' => $this->input->post('BLN_KTRK11'),
            'BLN KTRK12' => $this->input->post('BLN_KTRK12'),
            'BULAN RENC BAYAR' => $this->input->post('BULAN_RENC_BAYAR'),
            'BULAN BAYAR' => $this->input->post('BULAN_BAYAR')
        );

        $insert = $this->db->insert('anggaran_op', $data);

        if ($insert) {
            $this->session->set_flashdata('success', 'Data progress kontrak operasi berhasil ditambahkan!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data progress kontrak operasi!');
        }

        redirect('anggaran/operasi/progress_kontrak');
    }

    public function add_rekomposisi()
    {
        $data['parent_page_title'] = 'Operasi';
        $data['parent_page_url'] = '#';
        $this->load->view('layout/header', $data);
        $this->load->view('anggaran/operasi/vw_add_rekomposisi');
        $this->load->view('layout/footer');
    }

    public function store_rekomposisi()
    {
        // Prepare data with spaces in field names (matching database schema)
        $data = array(
            'No' => $this->input->post('NO'),
            'Unit Detail' => $this->input->post('UNIT_DETAIL'),
            'Nomor SKKO' => $this->input->post('NOMOR_SKKO'),
            'Uraian Kegiatan' => $this->input->post('URAIAN_KEGIATAN'),
            'SKKO (Sebelum)' => $this->input->post('SKKO_SEBELUM'),
            'SKKO (Sesudah)' => $this->input->post('SKKO_SESUDAH'),
            'Ket' => $this->input->post('KET')
        );

        $insert = $this->db->insert('rekomposisi_op', $data);

        if ($insert) {
            $this->session->set_flashdata('success', 'Data rekomposisi operasi berhasil ditambahkan!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data rekomposisi operasi!');
        }

        redirect('anggaran/operasi/rekomposisi');
    }

    public function add_monitoring()
    {
        $data['parent_page_title'] = 'Operasi';
        $data['parent_page_url'] = '#';
        $this->load->view('layout/header', $data);
        $this->load->view('anggaran/operasi/vw_add_monitoring');
        $this->load->view('layout/footer');
    }

    public function store_monitoring()
    {
        // Prepare data with spaces in field names (matching database schema)
        $data = array(
            'SUMBER DANA' => $this->input->post('SUMBER_DANA'),
            'SKKO' => $this->input->post('SKKO'),
            'SUB POS' => $this->input->post('SUB_POS'),
            'DRP' => $this->input->post('DRP'),
            'URAIAN KONTRAK / PEKERJAAN' => $this->input->post('URAIAN_KONTRAK_PEKERJAAN'),
            'USER' => $this->input->post('USER'),
            'PAGU ANG/RAB USER' => $this->input->post('PAGU_ANG_RAB_USER'),
            'KOMITMENT ND' => $this->input->post('KOMITMENT_ND'),
            'RENC AKHIR KONTRAK' => $this->input->post('RENC_AKHIR_KONTRAK'),
            'TGL ND/AMS' => $this->input->post('TGL_ND_AMS'),
            'NOMOR ND / AMS' => $this->input->post('NOMOR_ND_AMS'),
            'KETERANGAN' => $this->input->post('KETERANGAN'),
            'TAHAP KONTRAK' => $this->input->post('TAHAP_KONTRAK'),
            'PROGNOSA' => $this->input->post('PROGNOSA'),
            'NO SPK / SPB / KONTRAK' => $this->input->post('NO_SPK_SPB_KONTRAK'),
            'REKANAN' => $this->input->post('REKANAN'),
            'TGL KONTRAK' => $this->input->post('TGL_KONTRAK'),
            'TGL AKHIR KONTRAK' => $this->input->post('TGL_AKHIR_KONTRAK'),
            'NILAI KONTRAK TOTAL' => $this->input->post('NILAI_KONTRAK_TOTAL'),
            'NILAI KONTRAK TAHUN BERJALAN' => $this->input->post('NILAI_KONTRAK_TAHUN_BERJALAN'),
            'TGL BAYAR' => $this->input->post('TGL_BAYAR'),
            'ANGGARAN TERPAKAI' => $this->input->post('ANGGARAN_TERPAKAI'),
            'SISA ANGGARAN' => $this->input->post('SISA_ANGGARAN'),
            'STATUS KONTRAK' => $this->input->post('STATUS_KONTRAK'),
            'BLN KTRK1' => $this->input->post('BLN_KTRK1'),
            'BLN KTRK2' => $this->input->post('BLN_KTRK2'),
            'BLN KTRK3' => $this->input->post('BLN_KTRK3'),
            'BLN KTRK4' => $this->input->post('BLN_KTRK4'),
            'BLN KTRK5' => $this->input->post('BLN_KTRK5'),
            'BLN KTRK6' => $this->input->post('BLN_KTRK6'),
            'BLN KTRK7' => $this->input->post('BLN_KTRK7'),
            'BLN KTRK8' => $this->input->post('BLN_KTRK8'),
            'BLN KTRK9' => $this->input->post('BLN_KTRK9'),
            'BLN KTRK10' => $this->input->post('BLN_KTRK10'),
            'BLN KTRK11' => $this->input->post('BLN_KTRK11'),
            'BLN KTRK12' => $this->input->post('BLN_KTRK12'),
            'BULAN RENC BAYAR' => $this->input->post('BULAN_RENC_BAYAR'),
            'BULAN BAYAR' => $this->input->post('BULAN_BAYAR')
        );

        $insert = $this->db->insert('monitoring_op', $data);

        if ($insert) {
            $this->session->set_flashdata('success', 'Data monitoring operasi berhasil ditambahkan!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data monitoring operasi!');
        }

        redirect('anggaran/operasi/monitoring');
    }

}
