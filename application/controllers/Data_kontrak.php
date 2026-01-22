<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data_kontrak extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_data_kontrak');
        // load rekomposisi model supaya bisa dipakai langsung
        $this->load->model('Rekomposisi_model', 'rekom');
        $this->load->helper(['url', 'form', 'text']);
        $this->load->library('session');
    }

    // =========================
    // ROLE HELPERS
    // =========================
    private function _role()
    {
        $r = $this->session->userdata('user_role') ?: $this->session->userdata('role');
        return strtolower(trim((string)$r));
    }

    private function _is_admin($role)
    {
        return in_array($role, ['admin', 'administrator'], true);
    }

    private function _can_fill_master_user($role)
    {
        return in_array($role, [
            'admin',
            'administrator',
            'pemeliharaan',
            'fasilitas operasi',
            'har',
            'k3l & kam',
            'perencanaan',
            'kku',
        ], true);
    }

    private function _can_fill_perencanaan($role)
    {
        return in_array($role, [
            'admin',
            'administrator',
            'perencanaan',
            'k3l & kam',
        ], true);
    }

    private function _can_fill_pengadaan($role)
    {
        return in_array($role, [
            'admin',
            'administrator',
            'pengadaan keuangan',
        ], true);
    }

    private function _can_fill_kku($role)
    {
        return in_array($role, [
            'admin',
            'administrator',
            'kku',
            'k3l & kam',
        ], true);
    }

    private function _can_create_new($role)
    {
        return in_array($role, [
            'admin',
            'administrator',
            'pemeliharaan',
            'fasilitas operasi',
            'har',
            'k3l & kam',
            'perencanaan',
            'kku',
        ], true);
    }

    private function _forbidden()
    {
        show_error('Forbidden', 403);
        exit;
    }

    private function _pick_post(array $allowed)
    {
        $post = $this->input->post(NULL, TRUE);
        $out = [];
        foreach ($allowed as $k) {
            if (array_key_exists($k, $post)) {
                $out[$k] = $post[$k];
            }
        }
        return $out;
    }

    private function parse_year($s)
    {
        $s = (string)$s;
        if (preg_match('/\b(20\d{2})\b/', $s, $m)) return (int)$m[1];
        return null;
    }

    private function infer_tahun_target($skk_id, $nomor_skk_text, $tgl_tahapan)
    {
        if (!empty($skk_id)) {
            $skk = $this->M_data_kontrak->get_skk_row((int)$skk_id);
            if ($skk && !empty($skk->nomor_skk_io)) {
                $y = $this->parse_year($skk->nomor_skk_io);
                if ($y) return (int)$y;
            }
        }

        if (!empty($nomor_skk_text)) {
            $y = $this->parse_year($nomor_skk_text);
            if ($y) return (int)$y;
        }

        if (!empty($tgl_tahapan)) {
            return (int)date('Y', strtotime($tgl_tahapan));
        }

        return null;
    }

    private function build_realisasi_bulanan($tahapan_pembayaran, $tgl_tahapan, $nilai_bayar, $tahun_target = null)
    {
        $hasil = [];
        for ($m = 1; $m <= 12; $m++) $hasil["real_byr_bln{$m}"] = 0;

        $tahap = strtolower(trim((string)$tahapan_pembayaran));
        $tgl   = trim((string)$tgl_tahapan);
        $nilai = (float)$nilai_bayar;

        if ($tahap !== 'pembayaran' || $tgl === '') {
            return $hasil;
        }

        $ts = strtotime($tgl);
        if (!$ts) return $hasil;

        $bulan = (int)date('n', $ts);
        $tahun = (int)date('Y', $ts);

        if (!empty($tahun_target) && $tahun !== (int)$tahun_target) {
            return $hasil;
        }

        if ($bulan >= 1 && $bulan <= 12) {
            $hasil["real_byr_bln{$bulan}"] = $nilai;
        }

        return $hasil;
    }

    private function _fields_master()
    {
        return [
            'manual_master',
            'jenis_anggaran_id',
            'prk_id',
            'skk_id',
            'drp_id',
            'jenis_anggaran_text',
            'nomor_prk_text',
            'uraian_prk_text',
            'nomor_skk_io_text',
            'drp_text',
            'pagu_skk_io',
            'sisa_anggaran'
        ];
    }

    private function _fields_user()
    {
        return [
            'uraian_pekerjaan',
            'user_pengusul',
            'rab_user',
            'rencana_hari_kerja',
            'jenis_penagihan',
            'tanggal_bastp'
        ];
    }

    private function _fields_perencanaan()
    {
        return [
            'tgl_nd_ams',
            'nomor_nd_ams',
            'status_kontrak',
            'keterangan'
        ];
    }

    private function _fields_pengadaan()
    {
        return [
            'no_rks',
            'metode_pengadaan',
            'tahapan_pengadaan',
            'prognosa_kontrak_tgl',
            'harga_hpe',
            'harga_hps',
            'harga_nego',
            'kak',
            'no_kontrak',
            'pelaksana_vendor',
            'tgl_kontrak',
            'end_kontrak',
            'nilai_kontrak',
            'kendala_kontrak'
        ];
    }

    private function _fields_kku()
    {
        return [
            'tahapan_pembayaran',
            'nilai_bayar',
            'tgl_tahapan'
        ];
    }

    // =========================
    // LIST
    // =========================
    // =========================
    // LIST
    // =========================
    public function index($start = 0)
    {
        $role = $this->_role();

        $allowed_view_roles = [
            'admin', 'administrator', 'pemeliharaan', 'fasilitas operasi',
            'har', 'k3l & kam', 'perencanaan', 'kku', 'pengadaan keuangan',
        ];

        if (!in_array($role, $allowed_view_roles, true)) {
            return $this->_forbidden();
        }

        // --- 1. HANDLING FILTER & PAGINATION PERSISTENCE ---
        
        // Search Query (search)
        if ($this->input->get('search') !== null) {
            $search = $this->input->get('search');
            $this->session->set_userdata('kontrak_search', $search);
        } else {
            if ($this->session->userdata('kontrak_search')) {
                $search = $this->session->userdata('kontrak_search');
            } else {
                $search = '';
            }
        }

        // Per Page Limit
        if ($this->input->get('per_page') !== null) {
            $per_page = (int)$this->input->get('per_page');
            $this->session->set_userdata('kontrak_per_page', $per_page);
        } else {
            if ($this->session->userdata('kontrak_per_page')) {
                $per_page = (int)$this->session->userdata('kontrak_per_page');
            } else {
                $per_page = 10; // Default limit
            }
        }

        // --- 2. CONFIGURAION PAGINATION ---
        $this->load->library('pagination');
        
        $config['base_url'] = base_url('data_kontrak/index');
        $config['total_rows'] = $this->M_data_kontrak->count_all($search);
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 3; // segment 3 is $start (offset)
        $config['reuse_query_string'] = TRUE;

        // Styling Bootstrap 5 (Simple)
        $config['full_tag_open']    = '<nav><ul class="pagination pagination-sm justify-content-end">';
        $config['full_tag_close']   = '</ul></nav>';
        $config['first_link']       = 'First';
        $config['last_link']        = 'Last';
        $config['first_tag_open']   = '<li class="page-item">';
        $config['first_tag_close']  = '</li>';
        $config['prev_link']        = '&laquo';
        $config['prev_tag_open']    = '<li class="page-item">';
        $config['prev_tag_close']   = '</li>';
        $config['next_link']        = '&raquo';
        $config['next_tag_open']    = '<li class="page-item">';
        $config['next_tag_close']   = '</li>';
        $config['last_tag_open']    = '<li class="page-item">';
        $config['last_tag_close']   = '</li>';
        $config['cur_tag_open']     = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close']    = '</a></li>';
        $config['num_tag_open']     = '<li class="page-item">';
        $config['num_tag_close']    = '</li>';
        $config['attributes']       = array('class' => 'page-link');

        $this->pagination->initialize($config);

        // --- 3. FETCH DATA ---
        $data['kontrak'] = $this->M_data_kontrak->get_limit($per_page, $start, $search);
        
        // Metadata for View
        $data['search'] = $search;
        $data['per_page'] = $per_page;
        $data['start_no'] = $start + 1;
        $data['total_rows'] = $config['total_rows'];
        $data['pagination'] = $this->pagination->create_links();

        $data['page_title'] = 'Data Kontrak';
        $data['page_icon'] = 'fas fa-file-contract';
        $data['parent_page_title'] = 'Anggaran';
        $data['parent_page_url'] = '#';

        $this->load->view('layout/header');
        $this->load->view('Anggaran/input_kontrak/data_kontrak', $data);
        $this->load->view('layout/footer');
    }

    // =========================
    // TAMBAH (FORM)
    // =========================
    public function tambah()
    {
        $role = $this->_role();

        if (!$this->_can_create_new($role)) {
            return $this->_forbidden();
        }

        $data['jenis_anggaran'] = $this->M_data_kontrak->get_jenis_anggaran();
        $data['role_name'] = $this->session->userdata('role_name') ?? '';

        $data['page_title'] = 'Tambah Data Kontrak';
        $data['page_icon'] = 'fas fa-plus';
        $data['parent_page_title'] = 'Anggaran';
        $data['parent_page_url'] = '#';
        $this->load->view('layout/header', $data);
        $this->load->view('Anggaran/input_kontrak/tambah_kontrak', $data);
        $this->load->view('layout/footer');
    }

    // =========================
    // TAMBAH (AKSI)
    // =========================
    public function tambah_aksi()
    {
        $role = $this->_role();

        if (!$this->_can_create_new($role)) {
            return $this->_forbidden();
        }

        $is_admin           = $this->_is_admin($role);
        $can_perencanaan    = $this->_can_fill_perencanaan($role);
        $can_pengadaan      = $this->_can_fill_pengadaan($role);
        $can_kku            = $this->_can_fill_kku($role);

        $allowed = array_merge($this->_fields_master(), $this->_fields_user());
        if ($can_perencanaan) $allowed = array_merge($allowed, $this->_fields_perencanaan());
        if ($can_pengadaan)   $allowed = array_merge($allowed, $this->_fields_pengadaan());
        if ($can_kku)         $allowed = array_merge($allowed, $this->_fields_kku());

        $p = $this->_pick_post($allowed);
        $manual = ((int)($p['manual_master'] ?? 0) === 1);

        $jenis_anggaran_id = $manual ? null : (int)($p['jenis_anggaran_id'] ?? 0);
        $prk_id            = $manual ? null : (int)($p['prk_id'] ?? 0);
        $skk_id            = $manual ? null : (int)($p['skk_id'] ?? 0);
        $drp_id            = $manual ? null : (int)($p['drp_id'] ?? 0);

        $tahapan_pembayaran = $p['tahapan_pembayaran'] ?? null;
        $tgl_tahapan        = $p['tgl_tahapan'] ?? null;
        $nilai_bayar        = $p['nilai_bayar'] ?? 0;

        $tahun_target = $this->infer_tahun_target(
            $skk_id ?: null,
            $manual ? ($p['nomor_skk_io_text'] ?? null) : null,
            $tgl_tahapan
        );

        $data = [
            'jenis_anggaran_id' => $jenis_anggaran_id ?: null,
            'prk_id'            => $prk_id ?: null,
            'skk_id'            => $skk_id ?: null,
            'drp_id'            => $drp_id ?: null,

            'jenis_anggaran_text' => $manual ? ($p['jenis_anggaran_text'] ?? null) : null,
            'nomor_prk_text'      => $manual ? ($p['nomor_prk_text'] ?? null) : null,
            'uraian_prk_text'     => $manual ? ($p['uraian_prk_text'] ?? null) : null,
            'nomor_skk_io_text'   => $manual ? ($p['nomor_skk_io_text'] ?? null) : null,
            'drp_text'            => $manual ? ($p['drp_text'] ?? null) : null,

            'pagu_skk_io'   => (float)($p['pagu_skk_io'] ?? 0),
            'sisa_anggaran' => (float)($p['sisa_anggaran'] ?? 0),

            'uraian_pekerjaan'   => $p['uraian_pekerjaan'] ?? null,
            'user_pengusul'      => $p['user_pengusul'] ?? null,
            'rab_user'           => (float)($p['rab_user'] ?? 0),
            'rencana_hari_kerja' => (int)($p['rencana_hari_kerja'] ?? 0),
            'jenis_penagihan'    => $p['jenis_penagihan'] ?? null,
            'tanggal_bastp'      => $p['tanggal_bastp'] ?? null,

            'created_at' => date('Y-m-d H:i:s'),
        ];

        if ($can_perencanaan) {
            $data = array_merge($data, [
                'tgl_nd_ams'     => $p['tgl_nd_ams'] ?? null,
                'nomor_nd_ams'   => $p['nomor_nd_ams'] ?? null,
                'status_kontrak' => $p['status_kontrak'] ?? null,
                'keterangan'     => $p['keterangan'] ?? null,
            ]);
        }

        if ($can_pengadaan) {
            $data = array_merge($data, [
                'no_rks'               => $p['no_rks'] ?? null,
                'metode_pengadaan'     => $p['metode_pengadaan'] ?? null,
                'tahapan_pengadaan'    => $p['tahapan_pengadaan'] ?? null,
                'prognosa_kontrak_tgl' => $p['prognosa_kontrak_tgl'] ?? null,
                'harga_hpe'            => (float)($p['harga_hpe'] ?? 0),
                'harga_hps'            => (float)($p['harga_hps'] ?? 0),
                'harga_nego'           => (float)($p['harga_nego'] ?? 0),
                'kak'                  => $p['kak'] ?? null,
                'no_kontrak'           => $p['no_kontrak'] ?? null,
                'pelaksana_vendor'     => $p['pelaksana_vendor'] ?? null,
                'tgl_kontrak'          => $p['tgl_kontrak'] ?? null,
                'end_kontrak'          => $p['end_kontrak'] ?? null,
                'nilai_kontrak'        => (float)($p['nilai_kontrak'] ?? 0),
                'kendala_kontrak'      => $p['kendala_kontrak'] ?? null,
            ]);
        }

        if ($can_kku) {
            $data = array_merge($data, [
                'tahapan_pembayaran' => $tahapan_pembayaran,
                'nilai_bayar'        => (float)$nilai_bayar,
                'tgl_tahapan'        => $tgl_tahapan,
            ]);

            $data = array_merge($data, $this->build_realisasi_bulanan(
                $tahapan_pembayaran,
                $tgl_tahapan,
                $nilai_bayar,
                $tahun_target
            ));
        } else {
            $data = array_merge($data, $this->build_realisasi_bulanan('', '', 0, null));
        }

        // --- START TRANSACTION: insert kontrak + optional insert rekomposisi ---
        $this->db->trans_start();

        $this->M_data_kontrak->insert($data);
        $new_kontrak_id = $this->db->insert_id();

        // jika input manual, sinkron ke tabel rekomposisi
        if ($manual) {
            // siapkan data untuk rekomposisi (sesuaikan kolom tabel rekomposisi Anda)
            $rek_data = [
                'JENIS_ANGGARAN' => $p['jenis_anggaran_text'] ?? '',
                'NOMOR_PRK'      => $p['nomor_prk_text'] ?? '',
                'NOMOR_SKK_IO'   => $p['nomor_skk_io_text'] ?? '',
                'PRK'            => $p['uraian_prk_text'] ?? '',
                'SKKI_O'         => is_numeric($p['pagu_skk_io'] ?? null) ? (float)$p['pagu_skk_io'] : ($p['pagu_skk_io'] ?? ''),
                'REKOMPOSISI'    => $p['uraian_prk_text'] ?? '',
                'JUDUL_DRP'      => $p['drp_text'] ?? '',
                'created_at'     => date('Y-m-d H:i:s'),
                // optional FK kalau perlu:
                // 'jenis_anggaran_id' => $jenis_anggaran_id,
                // 'prk_id' => $prk_id,
                // 'skk_id' => $skk_id,
                // 'drp_id' => $drp_id
            ];

            // insert only if not exists
            if (method_exists($this->rekom, 'insert_if_not_exists')) {
                $this->rekom->insert_if_not_exists($rek_data);
            } else {
                $exists = $this->rekom->find_by_keys($rek_data['NOMOR_PRK'], $rek_data['NOMOR_SKK_IO'], $rek_data['JUDUL_DRP']);
                if (!$exists) {
                    $this->rekom->insert_rekomposisi($rek_data);
                }
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            // rollback otomatis dan beritahu user
            $this->session->set_flashdata('error', 'Gagal menyimpan data kontrak (DB error).');
            redirect('data_kontrak');
        }

        // sukses
        redirect('data_kontrak');
    }

    public function detail($id)
    {
        $data['kontrak'] = $this->M_data_kontrak->get_by_id((int)$id);
        $data['page_title'] = 'Detail Data Kontrak';
        $data['page_icon'] = 'fas fa-info-circle';
        $data['parent_page_title'] = 'Anggaran';
        $data['parent_page_url'] = '#';
        $this->load->view('layout/header');
        $this->load->view('Anggaran/input_kontrak/detail_kontrak', $data);
        $this->load->view('layout/footer');
    }

    public function edit($id)
    {
        $role = $this->_role();

        $is_admin        = $this->_is_admin($role);
        $can_master_user = $this->_can_fill_master_user($role);
        $can_perencanaan = $this->_can_fill_perencanaan($role);
        $can_pengadaan   = $this->_can_fill_pengadaan($role);
        $can_kku         = $this->_can_fill_kku($role);

        if (
            !$is_admin &&
            !$can_master_user &&
            !$can_perencanaan &&
            !$can_pengadaan &&
            !$can_kku
        ) {
            return $this->_forbidden();
        }

        $data['kontrak']        = $this->M_data_kontrak->get_by_id((int)$id);
        $data['jenis_anggaran'] = $this->M_data_kontrak->get_jenis_anggaran();

        $data['page_title'] = 'Edit Data Kontrak';
        $data['page_icon'] = 'fas fa-edit';
        $data['parent_page_title'] = 'Anggaran';
        $data['parent_page_url'] = '#';
        $this->load->view('layout/header', $data);
        $this->load->view('Anggaran/input_kontrak/edit_kontrak', $data);
        $this->load->view('layout/footer');
    }

    public function update($id)
    {
        $role = $this->_role();
        $id = (int)$id;

        $kontrak_lama = $this->M_data_kontrak->get_by_id($id);
        if (!$kontrak_lama) {
            show_404();
            return;
        }

        $is_admin        = $this->_is_admin($role);
        $can_master_user = $this->_can_fill_master_user($role);
        $can_perencanaan = $this->_can_fill_perencanaan($role);
        $can_pengadaan   = $this->_can_fill_pengadaan($role);
        $can_kku         = $this->_can_fill_kku($role);

        if ($is_admin) {
            $allowed = array_merge(
                $this->_fields_master(),
                $this->_fields_user(),
                $this->_fields_perencanaan(),
                $this->_fields_pengadaan(),
                $this->_fields_kku()
            );
        } elseif ($can_master_user) {
            $allowed = array_merge($this->_fields_master(), $this->_fields_user());
        } elseif ($can_perencanaan) {
            $allowed = $this->_fields_perencanaan();
        } elseif ($can_pengadaan) {
            $allowed = $this->_fields_pengadaan();
        } elseif ($can_kku) {
            $allowed = $this->_fields_kku();
        } else {
            return $this->_forbidden();
        }

        $p = $this->_pick_post($allowed);
        $data = [];

        if ($is_admin || $can_master_user) {
            $manual = ((int)($p['manual_master'] ?? 0) === 1);
            $jenis_anggaran_id = $manual ? null : (int)($p['jenis_anggaran_id'] ?? 0);
            $prk_id            = $manual ? null : (int)($p['prk_id'] ?? 0);
            $skk_id            = $manual ? null : (int)($p['skk_id'] ?? 0);
            $drp_id            = $manual ? null : (int)($p['drp_id'] ?? 0);

            $data = array_merge($data, [
                'jenis_anggaran_id' => $jenis_anggaran_id ?: null,
                'prk_id'            => $prk_id ?: null,
                'skk_id'            => $skk_id ?: null,
                'drp_id'            => $drp_id ?: null,
                'jenis_anggaran_text' => $manual ? ($p['jenis_anggaran_text'] ?? null) : null,
                'nomor_prk_text'      => $manual ? ($p['nomor_prk_text'] ?? null) : null,
                'uraian_prk_text'     => $manual ? ($p['uraian_prk_text'] ?? null) : null,
                'nomor_skk_io_text'   => $manual ? ($p['nomor_skk_io_text'] ?? null) : null,
                'drp_text'            => $manual ? ($p['drp_text'] ?? null) : null,
                'pagu_skk_io'   => (float)($p['pagu_skk_io'] ?? 0),
                'sisa_anggaran' => (float)($p['sisa_anggaran'] ?? 0),
                'uraian_pekerjaan'   => $p['uraian_pekerjaan'] ?? $kontrak_lama->uraian_pekerjaan,
                'user_pengusul'      => $p['user_pengusul'] ?? $kontrak_lama->user_pengusul,
                'rab_user'           => (float)($p['rab_user'] ?? $kontrak_lama->rab_user ?? 0),
                'rencana_hari_kerja' => (int)($p['rencana_hari_kerja'] ?? $kontrak_lama->rencana_hari_kerja ?? 0),
                'jenis_penagihan'    => $p['jenis_penagihan'] ?? $kontrak_lama->jenis_penagihan,
                'tanggal_bastp'      => $p['tanggal_bastp'] ?? $kontrak_lama->tanggal_bastp,
            ]);
        }

        if ($is_admin || $can_perencanaan) {
            $data = array_merge($data, [
                'tgl_nd_ams'     => $p['tgl_nd_ams'] ?? $kontrak_lama->tgl_nd_ams,
                'nomor_nd_ams'   => $p['nomor_nd_ams'] ?? $kontrak_lama->nomor_nd_ams,
                'status_kontrak' => $p['status_kontrak'] ?? $kontrak_lama->status_kontrak,
                'keterangan'     => $p['keterangan'] ?? $kontrak_lama->keterangan,
            ]);
        }

        if ($is_admin || $can_pengadaan) {
            $data = array_merge($data, [
                'no_rks'               => $p['no_rks'] ?? $kontrak_lama->no_rks,
                'metode_pengadaan'     => $p['metode_pengadaan'] ?? $kontrak_lama->metode_pengadaan,
                'tahapan_pengadaan'    => $p['tahapan_pengadaan'] ?? $kontrak_lama->tahapan_pengadaan,
                'prognosa_kontrak_tgl' => $p['prognosa_kontrak_tgl'] ?? $kontrak_lama->prognosa_kontrak_tgl,
                'harga_hpe'            => (float)($p['harga_hpe'] ?? $kontrak_lama->harga_hpe ?? 0),
                'harga_hps'            => (float)($p['harga_hps'] ?? $kontrak_lama->harga_hps ?? 0),
                'harga_nego'           => (float)($p['harga_nego'] ?? $kontrak_lama->harga_nego ?? 0),
                'kak'                  => $p['kak'] ?? $kontrak_lama->kak,
                'no_kontrak'           => $p['no_kontrak'] ?? $kontrak_lama->no_kontrak,
                'pelaksana_vendor'     => $p['pelaksana_vendor'] ?? $kontrak_lama->pelaksana_vendor,
                'tgl_kontrak'          => $p['tgl_kontrak'] ?? $kontrak_lama->tgl_kontrak,
                'end_kontrak'          => $p['end_kontrak'] ?? $kontrak_lama->end_kontrak,
                'nilai_kontrak'        => (float)($p['nilai_kontrak'] ?? $kontrak_lama->nilai_kontrak ?? 0),
                'kendala_kontrak'      => $p['kendala_kontrak'] ?? $kontrak_lama->kendala_kontrak,
            ]);
        }

        if ($is_admin || $can_kku) {
            $tahapan_pembayaran = $p['tahapan_pembayaran'] ?? $kontrak_lama->tahapan_pembayaran;
            $tgl_tahapan        = $p['tgl_tahapan'] ?? $kontrak_lama->tgl_tahapan;
            $nilai_bayar        = (float)($p['nilai_bayar'] ?? $kontrak_lama->nilai_bayar ?? 0);

            $data = array_merge($data, [
                'tahapan_pembayaran' => $tahapan_pembayaran,
                'tgl_tahapan'        => $tgl_tahapan,
                'nilai_bayar'        => $nilai_bayar,
            ]);

            $tahun_target = $this->infer_tahun_target(
                (int)($kontrak_lama->skk_id ?? 0),
                (string)($kontrak_lama->nomor_skk_io_text ?? ''),
                $tgl_tahapan
            );

            $data = array_merge($data, $this->build_realisasi_bulanan(
                $tahapan_pembayaran,
                $tgl_tahapan,
                $nilai_bayar,
                $tahun_target
            ));
        }

        $this->M_data_kontrak->update($id, $data);
        redirect('data_kontrak');
    }

    public function hapus($id)
    {
        $role = $this->_role();

        if (!$this->_is_admin($role)) {
            return $this->_forbidden();
        }

        $this->M_data_kontrak->delete((int)$id);
        redirect('data_kontrak');
    }

    public function export_csv()
    {
        // Block guest users from exporting
        if (function_exists('is_guest') && is_guest()) {
            $this->session->set_flashdata('error', 'Akses ditolak. Silakan login untuk mengunduh data.');
            redirect(strtolower($this->router->fetch_class()));
            return;
        }

        $search = $this->input->get('search') ?: $this->session->userdata('kontrak_search');
        $rows = $this->M_data_kontrak->get_all($search);

        $filename = 'data_kontrak_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF");

        if (empty($rows)) {
            fputcsv($out, ['No data']);
            fclose($out);
            exit;
        }

        $first = (array)$rows[0];
        $headers = array_keys($first);
        fputcsv($out, $headers);

        foreach ($rows as $r) {
            $line = [];
            $arr = (array)$r;
            foreach ($headers as $h) {
                $line[] = isset($arr[$h]) ? $arr[$h] : '';
            }
            fputcsv($out, $line);
        }

        fclose($out);
        exit;
    }

    public function prk_by_jenis($jenis_id)
    {
        $rows = $this->M_data_kontrak->get_prk_by_jenis((int)$jenis_id);
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($rows));
    }

    public function skk_by_prk($prk_id)
    {
        $rows = $this->M_data_kontrak->get_skk_by_prk((int)$prk_id);
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($rows));
    }

    public function drp_by_prk($prk_id)
    {
        $rows = $this->M_data_kontrak->get_drp_by_prk((int)$prk_id);
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($rows));
    }
}
