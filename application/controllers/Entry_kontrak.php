<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Entry_kontrak extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        $this->load->model('Entry_kontrak_model', 'kontrak');
        $this->load->model('Rekomposisi_model', 'rekom');
        $this->load->model('Notifikasi_model', 'notifModel');

        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'form_validation', 'pagination']);
    }

    private function _get_role_raw()
    {
        $r = $this->session->userdata('user_role');
        if (!$r) $r = $this->session->userdata('role');
        return strtolower(trim((string)$r));
    }

    private function _role_label($role_raw)
    {
        $r = strtolower(trim((string)$role_raw));

        if ($r === 'pemeliharaan' || $r === 'har') return 'HAR';
        if ($r === 'fasilitas operasi' || $r === 'fasop') return 'FASOP';
        if ($r === 'operasi sistem distribusi' || $r === 'opdist') return 'OPDIST';
        if ($r === 'k3l & kam' || $r === 'k3l&kam') return 'K3L&KAM';

        if ($r === 'pengadaan keuangan' || $r === 'pengadaan') return 'PENGADAAN';

        if ($r === 'perencanaan') return 'PERENCANAAN';
        if ($r === 'kku') return 'KKU';
        if ($r === 'admin' || $r === 'administrator') return 'ADMIN';

        return strtoupper($role_raw);
    }

    private function _is_admin($role_raw)
    {
        $r = strtolower(trim((string)$role_raw));
        return ($r === 'admin' || $r === 'administrator');
    }

    private function _is_perencanaan($role_raw)
    {
        return (strtolower(trim((string)$role_raw)) === 'perencanaan');
    }

    private function _is_pengadaan($role_raw)
    {
        $r = strtolower(trim((string)$role_raw));
        return ($r === 'pengadaan' || $r === 'pengadaan keuangan');
    }

    private function _is_kku($role_raw)
    {
        return (strtolower(trim((string)$role_raw)) === 'kku');
    }

    private function _is_originator($role_raw)
    {
        $r = strtolower(trim((string)$role_raw));
        return in_array($r, [
            'pemeliharaan',
            'operasi sistem distribusi',
            'fasilitas operasi',
            'k3l & kam',
        ], true);
    }

    /**
     * ✅ RULE BARU:
     * Field kontrak (no_kontrak, vendor, tgl_kontrak, end_kontrak, nilai_kontrak, kendala_kontrak)
     * hanya boleh diisi jika tahapan_pengadaan termasuk 4 pilihan ini.
     */
    private function _kontrak_enabled_by_tahapan($tahapan)
    {
        $t = trim((string)$tahapan);
        return in_array($t, [
            'Proses CDA',
            'Proses TTD Vendor',
            'Proses TTD Pengguna',
            'Pengadaan Selesai',
        ], true);
    }

    private function _auto_status($tgl_nd_ams, $nomor_nd_ams, $tgl_kontrak, $end_kontrak)
    {
        $today = date('Y-m-d');

        if (empty($tgl_nd_ams) && empty($nomor_nd_ams)) {
            return 'RENCANA';
        }

        if (!empty($end_kontrak) && $end_kontrak <= $today) {
            return 'SELESAI';
        }

        if (!empty($tgl_kontrak) && !empty($end_kontrak) && $end_kontrak > $today) {
            return 'TERKONTRAK';
        }

        return 'PROSES';
    }

    private function _kontrak_complete($row)
    {
        return !empty($row['no_kontrak']) && !empty($row['vendor']) && !empty($row['tgl_kontrak']);
    }

    private function _nd_complete($row)
    {
        return !empty($row['nomor_nd_ams']) && !empty($row['tgl_nd_ams']);
    }

    private function _log($jenis, $module = null, $record_id = null, $record_name = null)
    {
        $user_id = (int)$this->session->userdata('user_id');
        if (!$user_id) return;

        $email = (string)($this->session->userdata('email') ?? '');
        $role  = (string)($this->session->userdata('user_role') ?? $this->session->userdata('role') ?? '');

        $this->notifModel->log_aktivitas(
            $user_id,
            $email,
            $role,
            $jenis,
            $module,
            $record_id,
            $record_name,
            $this->input->ip_address(),
            (string)$this->input->user_agent()
        );
    }

    public function index()
    {
        $role_raw   = $this->_get_role_raw();
        $role_label = $this->_role_label($role_raw);
        $is_admin   = $this->_is_admin($role_raw);

        $per_page = (int)($this->input->get('per_page') ?? 10);
        if ($per_page <= 0) $per_page = 10;

        $offset = (int)($this->input->get('page') ?? 0);
        if ($offset < 0) $offset = 0;

        $search = $this->input->get('search', true);

        $total_rows = $this->kontrak->count_for_role($role_raw, $role_label, $search);

        $config['base_url']             = base_url('entry_kontrak');
        $config['total_rows']           = $total_rows;
        $config['per_page']             = $per_page;
        $config['page_query_string']    = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string']   = TRUE;

        $config['full_tag_open']  = '<nav><ul class="pagination pagination-sm mb-0">';
        $config['full_tag_close'] = '</ul></nav>';

        $config['first_link'] = '&laquo;';
        $config['last_link']  = '&raquo;';

        $config['first_tag_open']  = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open']   = '<li class="page-item">';
        $config['last_tag_close']  = '</li>';

        $config['cur_tag_open']  = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';

        $config['num_tag_open']  = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';

        $config['prev_link']      = '&lsaquo;';
        $config['prev_tag_open']  = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';

        $config['next_link']      = '&rsaquo;';
        $config['next_tag_open']  = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);

        $rows = $this->kontrak->get_for_role($role_raw, $role_label, $per_page, $offset, $search);

        $can_edit_row = [];
        foreach ($rows as $rw) {
            $id = (int)($rw['id'] ?? 0);
            if ($id <= 0) continue;

            if ($is_admin) {
                $can_edit_row[$id] = true;
                continue;
            }

            if ($this->_is_originator($role_raw)) {
                $own = (($rw['user_pengusul'] ?? '') === $role_label);
                $can_edit_row[$id] = ($own && !$this->_nd_complete($rw));
                continue;
            }

            if ($this->_is_perencanaan($role_raw)) {
                $can_edit_row[$id] = !$this->_nd_complete($rw);
                continue;
            }

            if ($this->_is_pengadaan($role_raw)) {
                // Pengadaan boleh edit semua yang ND lengkap (meskipun kontrak sudah lengkap)
                $can_edit_row[$id] = $this->_nd_complete($rw);
                continue;
            }

            if ($this->_is_kku($role_raw)) {
                $can_edit_row[$id] = ($this->_nd_complete($rw) && $this->_kontrak_complete($rw));
                continue;
            }

            $can_edit_row[$id] = false;
        }

        $data = [
            'page_title'   => 'Entry Kontrak',
            'parent_page_title' => 'Anggaran',
            'parent_page_url' => '#',
            'rows'         => $rows,
            'pagination'   => $this->pagination->create_links(),
            'total_rows'   => $total_rows,
            'per_page'     => $per_page,
            'start_no'     => $offset + 1,
            'search'      => $search,
            'role_raw'     => $role_raw,
            'role_label'   => $role_label,
            'is_admin'     => $is_admin,
            'can_create'   => ($is_admin || $this->_is_kku($role_raw) || $this->_is_originator($role_raw)),
            'can_edit_row' => $can_edit_row,
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('entry_kontrak/vw_entry_kontrak_list', $data);
        $this->load->view('layout/footer');
    }

    public function tambah()
    {
        $role_raw = $this->_get_role_raw();

        if (
            !$this->_is_admin($role_raw)
            && !$this->_is_kku($role_raw)
            && !$this->_is_originator($role_raw)
        ) {
            show_error('Anda tidak punya akses tambah Entry Kontrak.', 403);
        }

        $data = [
            'page_title'     => 'Tambah Entry Kontrak',
            'parent_page_title' => 'Anggaran',
            'parent_page_url' => '#',
            'jenis_anggaran' => $this->rekom->get_jenis_anggaran(),
            'role_raw'       => $role_raw,
            'role_label'     => $this->_role_label($role_raw),
            'is_admin'       => $this->_is_admin($role_raw),
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('entry_kontrak/vw_entry_kontrak_tambah', $data);
        $this->load->view('layout/footer');
    }

    public function store()
    {
        $role_raw   = $this->_get_role_raw();
        $role_label = $this->_role_label($role_raw);

        if (
            !$this->_is_admin($role_raw)
            && !$this->_is_kku($role_raw)
            && !$this->_is_originator($role_raw)
        ) {
            show_error('Anda tidak punya akses simpan Entry Kontrak.', 403);
        }

        $this->form_validation->set_rules('jenis_anggaran', 'Jenis Anggaran', 'required');
        $this->form_validation->set_rules('nomor_prk', 'Nomor PRK', 'required');
        $this->form_validation->set_rules('judul_drp', 'Judul DRP', 'required');

        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('entry_kontrak/tambah');
        }

        $nomor_prk = $this->input->post('nomor_prk', true);
        $judul_drp = $this->input->post('judul_drp', true);

        $master = $this->rekom->get_prk_drp_detail($nomor_prk, $judul_drp);
        if (!$master) {
            $this->session->set_flashdata('error', 'Master PRK + DRP tidak ditemukan.');
            redirect('entry_kontrak/tambah');
        }

        $tgl_nd_ams   = null;
        $nomor_nd_ams = null;
        $tgl_kontrak  = null;
        $end_kontrak  = null;

        // ✅ tambahan: baca tahapan pengadaan (admin saja akan punya nilai)
        $tahapan_pengadaan_post = null;

        if ($this->_is_admin($role_raw)) {
            $tgl_nd_ams   = $this->input->post('tgl_nd_ams', true);
            $nomor_nd_ams = $this->input->post('nomor_nd_ams', true);

            $tahapan_pengadaan_post = $this->input->post('tahapan_pengadaan', true);

            // tgl kontrak hanya dihitung jika tahapan termasuk 4
            if ($this->_kontrak_enabled_by_tahapan($tahapan_pengadaan_post)) {
                $tgl_kontrak = $this->input->post('tgl_kontrak', true);
                $end_kontrak = $this->input->post('end_kontrak', true);
            } else {
                $tgl_kontrak = null;
                $end_kontrak = null;
            }
        }

        $status_auto = $this->_auto_status($tgl_nd_ams, $nomor_nd_ams, $tgl_kontrak, $end_kontrak);

        $data = [
            'jenis_anggaran' => $master['jenis_anggaran'],
            'nomor_prk'      => $master['nomor_prk'],
            'nomor_skk_io'   => $master['nomor_skk_io'],
            'pagu_skk_io'    => $master['pagu_skk_io'],
            'uraian_prk'     => $master['uraian_prk'],
            'judul_drp'      => $master['judul_drp'],
            'drp'            => $this->input->post('drp', true),

            'uraian_pekerjaan' => $this->input->post('uraian_pekerjaan', true),
            'user_pengusul'    => $role_label,
            'rab_user'         => $this->input->post('rab_user', true),
            'renc_hari_kerja'  => $this->input->post('renc_hari_kerja', true),

            'tgl_nd_ams'   => $tgl_nd_ams,
            'nomor_nd_ams' => $nomor_nd_ams,
            'keterangan'   => $this->input->post('keterangan', true),

            'no_rks'            => $this->_is_admin($role_raw) ? $this->input->post('no_rks', true) : null,
            'kak'               => $this->_is_admin($role_raw) ? $this->input->post('kak', true) : null,
            'metode_pengadaan'  => $this->_is_admin($role_raw) ? $this->input->post('metode_pengadaan', true) : null,
            'harga_hpe'         => $this->_is_admin($role_raw) ? $this->input->post('harga_hpe', true) : null,
            'harga_hps'         => $this->_is_admin($role_raw) ? $this->input->post('harga_hps', true) : null,
            'harga_nego'        => $this->_is_admin($role_raw) ? $this->input->post('harga_nego', true) : null,
            'tahapan_pengadaan' => $this->_is_admin($role_raw) ? $this->input->post('tahapan_pengadaan', true) : null,
            'prognosa_kontrak'  => $this->_is_admin($role_raw) ? $this->input->post('prognosa_kontrak', true) : null,

            'status_kontrak'  => $status_auto,

            // default sesuai code lama:
            'no_kontrak'      => $this->_is_admin($role_raw) ? $this->input->post('no_kontrak', true) : null,
            'vendor'          => $this->_is_admin($role_raw) ? $this->input->post('vendor', true) : null,
            'tgl_kontrak'     => $tgl_kontrak,
            'end_kontrak'     => $end_kontrak,
            'nilai_kontrak'   => $this->_is_admin($role_raw) ? $this->input->post('nilai_kontrak', true) : null,
            'kendala_kontrak' => $this->_is_admin($role_raw) ? $this->input->post('kendala_kontrak', true) : null,
            'anggaran_terpakai' => $this->_is_admin($role_raw) ? $this->input->post('anggaran_terpakai', true) : null,

            'tahapan_pembayaran' => $this->_is_admin($role_raw) ? $this->input->post('tahapan_pembayaran', true) : null,
            'nilai_bayar'        => $this->_is_admin($role_raw) ? $this->input->post('nilai_bayar', true) : null,
            'tgl_tahapan'        => $this->_is_admin($role_raw) ? $this->input->post('tgl_tahapan', true) : null,

            'real_byr_bln1'  => $this->_is_admin($role_raw) ? $this->input->post('real_byr_bln1', true) : null,
            'real_byr_bln2'  => $this->_is_admin($role_raw) ? $this->input->post('real_byr_bln2', true) : null,
            'real_byr_bln3'  => $this->_is_admin($role_raw) ? $this->input->post('real_byr_bln3', true) : null,
            'real_byr_bln4'  => $this->_is_admin($role_raw) ? $this->input->post('real_byr_bln4', true) : null,
            'real_byr_bln5'  => $this->_is_admin($role_raw) ? $this->input->post('real_byr_bln5', true) : null,
            'real_byr_bln6'  => $this->_is_admin($role_raw) ? $this->input->post('real_byr_bln6', true) : null,
            'real_byr_bln7'  => $this->_is_admin($role_raw) ? $this->input->post('real_byr_bln7', true) : null,
            'real_byr_bln8'  => $this->_is_admin($role_raw) ? $this->input->post('real_byr_bln8', true) : null,
            'real_byr_bln9'  => $this->_is_admin($role_raw) ? $this->input->post('real_byr_bln9', true) : null,
            'real_byr_bln10' => $this->_is_admin($role_raw) ? $this->input->post('real_byr_bln10', true) : null,
            'real_byr_bln11' => $this->_is_admin($role_raw) ? $this->input->post('real_byr_bln11', true) : null,
            'real_byr_bln12' => $this->_is_admin($role_raw) ? $this->input->post('real_byr_bln12', true) : null,

            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // ✅ enforce backend: jika tahapan bukan 4 pilihan, kontrak dipaksa NULL (agar tidak masuk list KKU)
        if ($this->_is_admin($role_raw)) {
            $tah = $data['tahapan_pengadaan'] ?? null;
            if (!$this->_kontrak_enabled_by_tahapan($tah)) {
                $data['no_kontrak'] = null;
                $data['vendor'] = null;
                $data['tgl_kontrak'] = null;
                $data['end_kontrak'] = null;
                $data['nilai_kontrak'] = null;
                $data['kendala_kontrak'] = null;
                $data['anggaran_terpakai'] = null;
            }
        }

        $this->kontrak->insert($data);

        $this->_log('create', 'entry_kontrak', null, $data['judul_drp'] ?? null);

        $this->session->set_flashdata('success', 'Data kontrak berhasil disimpan.');
        redirect('entry_kontrak');
    }

    public function detail($id)
    {
        $row = $this->kontrak->get_by_id($id);
        if (!$row) show_404();

        $data = [
            'page_title' => 'Detail Entry Kontrak',
            'parent_page_title' => 'Anggaran',
            'parent_page_url' => '#',
            'row'        => $row,
            'role_raw'   => $this->_get_role_raw(),
            'role_label' => $this->_role_label($this->_get_role_raw()),
            'is_admin'   => $this->_is_admin($this->_get_role_raw()),
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('entry_kontrak/vw_entry_kontrak_detail', $data);
        $this->load->view('layout/footer');
    }

    public function edit($id)
    {
        $row = $this->kontrak->get_by_id($id);
        if (!$row) show_404();

        $role_raw   = $this->_get_role_raw();
        $role_label = $this->_role_label($role_raw);
        $is_admin   = $this->_is_admin($role_raw);

        if (!$is_admin) {
            if ($this->_is_originator($role_raw)) {
                if (($row['user_pengusul'] ?? '') !== $role_label) show_error('Tidak boleh edit data user lain.', 403);
                if ($this->_nd_complete($row)) show_error('Data sudah masuk tahap Perencanaan. Tidak bisa edit originator.', 403);
            }

            if ($this->_is_perencanaan($role_raw)) {
                if ($this->_nd_complete($row)) show_error('ND/AMS sudah lengkap. Tahap perencanaan selesai.', 403);
            }

            if ($this->_is_pengadaan($role_raw)) {
                if (!$this->_nd_complete($row)) show_error('ND/AMS belum lengkap. Belum masuk tahap Pengadaan.', 403);
            }

            if ($this->_is_kku($role_raw)) {
                if (!$this->_nd_complete($row) || !$this->_kontrak_complete($row)) {
                    show_error('Belum masuk tahap KKU (ND/AMS atau Kontrak belum lengkap).', 403);
                }
            }
        }

        $data = [
            'page_title'     => 'Edit Entry Kontrak',
            'parent_page_title' => 'Anggaran',
            'parent_page_url' => '#',
            'row'            => $row,
            'jenis_anggaran' => $this->rekom->get_jenis_anggaran(),
            'role_raw'       => $role_raw,
            'role_label'     => $role_label,
            'is_admin'       => $is_admin,
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('entry_kontrak/vw_entry_kontrak_edit', $data);
        $this->load->view('layout/footer');
    }

    public function update($id)
    {
        $row = $this->kontrak->get_by_id($id);
        if (!$row) show_404();

        $role_raw   = $this->_get_role_raw();
        $role_label = $this->_role_label($role_raw);
        $is_admin   = $this->_is_admin($role_raw);

        if (!$is_admin) {
            if ($this->_is_originator($role_raw)) {
                if (($row['user_pengusul'] ?? '') !== $role_label) show_error('Tidak boleh edit data user lain.', 403);
                if ($this->_nd_complete($row)) show_error('Data sudah masuk tahap Perencanaan. Tidak bisa edit originator.', 403);
            }

            if ($this->_is_perencanaan($role_raw)) {
                if ($this->_nd_complete($row)) show_error('ND/AMS sudah lengkap. Tahap perencanaan selesai.', 403);
            }

            if ($this->_is_pengadaan($role_raw)) {
                if (!$this->_nd_complete($row)) show_error('ND/AMS belum lengkap. Belum masuk tahap Pengadaan.', 403);
            }

            if ($this->_is_kku($role_raw)) {
                if (!$this->_nd_complete($row) || !$this->_kontrak_complete($row)) {
                    show_error('Belum masuk tahap KKU (ND/AMS atau Kontrak belum lengkap).', 403);
                }
            }
        }

        $data = [];

        if ($is_admin) {
            $this->form_validation->set_rules('jenis_anggaran', 'Jenis Anggaran', 'required');
            $this->form_validation->set_rules('nomor_prk', 'Nomor PRK', 'required');
            $this->form_validation->set_rules('judul_drp', 'Judul DRP', 'required');

            if ($this->form_validation->run() === false) {
                $this->session->set_flashdata('error', validation_errors());
                redirect('entry_kontrak/edit/' . $id);
            }

            $nomor_prk = $this->input->post('nomor_prk', true);
            $judul_drp = $this->input->post('judul_drp', true);

            $master = $this->rekom->get_prk_drp_detail($nomor_prk, $judul_drp);
            if (!$master) {
                $this->session->set_flashdata('error', 'Master PRK + DRP tidak ditemukan.');
                redirect('entry_kontrak/edit/' . $id);
            }

            $data = [
                'jenis_anggaran' => $master['jenis_anggaran'],
                'nomor_prk'      => $master['nomor_prk'],
                'nomor_skk_io'   => $master['nomor_skk_io'],
                'pagu_skk_io'    => $master['pagu_skk_io'],
                'uraian_prk'     => $master['uraian_prk'],
                'judul_drp'      => $master['judul_drp'],
                'drp'            => $this->input->post('drp', true),

                'uraian_pekerjaan' => $this->input->post('uraian_pekerjaan', true),
                'rab_user'         => $this->input->post('rab_user', true),
                'renc_hari_kerja'  => $this->input->post('renc_hari_kerja', true),

                'nomor_nd_ams' => $this->input->post('nomor_nd_ams', true),
                'tgl_nd_ams'   => $this->input->post('tgl_nd_ams', true),
                'keterangan'   => $this->input->post('keterangan', true),

                'no_rks'            => $this->input->post('no_rks', true),
                'kak'               => $this->input->post('kak', true),
                'metode_pengadaan'  => $this->input->post('metode_pengadaan', true),
                'harga_hpe'         => $this->input->post('harga_hpe', true),
                'harga_hps'         => $this->input->post('harga_hps', true),
                'harga_nego'        => $this->input->post('harga_nego', true),
                'tahapan_pengadaan' => $this->input->post('tahapan_pengadaan', true),
                'prognosa_kontrak'  => $this->input->post('prognosa_kontrak', true),

                'no_kontrak'      => $this->input->post('no_kontrak', true),
                'vendor'          => $this->input->post('vendor', true),
                'tgl_kontrak'     => $this->input->post('tgl_kontrak', true),
                'end_kontrak'     => $this->input->post('end_kontrak', true),
                'nilai_kontrak'   => $this->input->post('nilai_kontrak', true),
                'kendala_kontrak' => $this->input->post('kendala_kontrak', true),
                'anggaran_terpakai' => $this->input->post('anggaran_terpakai', true),

                'tahapan_pembayaran' => $this->input->post('tahapan_pembayaran', true),
                'nilai_bayar'        => $this->input->post('nilai_bayar', true),
                'tgl_tahapan'        => $this->input->post('tgl_tahapan', true),

                'real_byr_bln1'  => $this->input->post('real_byr_bln1', true),
                'real_byr_bln2'  => $this->input->post('real_byr_bln2', true),
                'real_byr_bln3'  => $this->input->post('real_byr_bln3', true),
                'real_byr_bln4'  => $this->input->post('real_byr_bln4', true),
                'real_byr_bln5'  => $this->input->post('real_byr_bln5', true),
                'real_byr_bln6'  => $this->input->post('real_byr_bln6', true),
                'real_byr_bln7'  => $this->input->post('real_byr_bln7', true),
                'real_byr_bln8'  => $this->input->post('real_byr_bln8', true),
                'real_byr_bln9'  => $this->input->post('real_byr_bln9', true),
                'real_byr_bln10' => $this->input->post('real_byr_bln10', true),
                'real_byr_bln11' => $this->input->post('real_byr_bln11', true),
                'real_byr_bln12' => $this->input->post('real_byr_bln12', true),

                'updated_at' => date('Y-m-d H:i:s'),
            ];

            // ✅ enforce backend (admin juga) sesuai rule tahapan_pengadaan
            $tah = $data['tahapan_pengadaan'] ?? null;
            if (!$this->_kontrak_enabled_by_tahapan($tah)) {
                $data['no_kontrak'] = null;
                $data['vendor'] = null;
                $data['tgl_kontrak'] = null;
                $data['end_kontrak'] = null;
                $data['nilai_kontrak'] = null;
                $data['kendala_kontrak'] = null;
                $data['anggaran_terpakai'] = null;
            }

        } elseif ($this->_is_originator($role_raw)) {

            $this->form_validation->set_rules('jenis_anggaran', 'Jenis Anggaran', 'required');
            $this->form_validation->set_rules('nomor_prk', 'Nomor PRK', 'required');
            $this->form_validation->set_rules('judul_drp', 'Judul DRP', 'required');

            if ($this->form_validation->run() === false) {
                $this->session->set_flashdata('error', validation_errors());
                redirect('entry_kontrak/edit/' . $id);
            }

            $nomor_prk = $this->input->post('nomor_prk', true);
            $judul_drp = $this->input->post('judul_drp', true);

            $master = $this->rekom->get_prk_drp_detail($nomor_prk, $judul_drp);
            if (!$master) {
                $this->session->set_flashdata('error', 'Master PRK + DRP tidak ditemukan.');
                redirect('entry_kontrak/edit/' . $id);
            }

            $data = [
                'jenis_anggaran' => $master['jenis_anggaran'],
                'nomor_prk'      => $master['nomor_prk'],
                'nomor_skk_io'   => $master['nomor_skk_io'],
                'pagu_skk_io'    => $master['pagu_skk_io'],
                'uraian_prk'     => $master['uraian_prk'],
                'judul_drp'      => $master['judul_drp'],
                'drp'            => $this->input->post('drp', true),

                'uraian_pekerjaan' => $this->input->post('uraian_pekerjaan', true),
                'rab_user'         => $this->input->post('rab_user', true),
                'renc_hari_kerja'  => $this->input->post('renc_hari_kerja', true),

                'updated_at' => date('Y-m-d H:i:s'),
            ];

        } elseif ($this->_is_perencanaan($role_raw)) {

            $data = [
                'nomor_nd_ams' => $this->input->post('nomor_nd_ams', true),
                'tgl_nd_ams'   => $this->input->post('tgl_nd_ams', true),
                'keterangan'   => $this->input->post('keterangan', true),
                'updated_at'   => date('Y-m-d H:i:s'),
            ];

        } elseif ($this->_is_pengadaan($role_raw)) {

            $data = [
                'no_rks'            => $this->input->post('no_rks', true),
                'kak'               => $this->input->post('kak', true),
                'metode_pengadaan'  => $this->input->post('metode_pengadaan', true),
                'harga_hpe'         => $this->input->post('harga_hpe', true),
                'harga_hps'         => $this->input->post('harga_hps', true),
                'harga_nego'        => $this->input->post('harga_nego', true),
                'tahapan_pengadaan' => $this->input->post('tahapan_pengadaan', true),
                'prognosa_kontrak'  => $this->input->post('prognosa_kontrak', true),

                'no_kontrak'      => $this->input->post('no_kontrak', true),
                'vendor'          => $this->input->post('vendor', true),
                'tgl_kontrak'     => $this->input->post('tgl_kontrak', true),
                'end_kontrak'     => $this->input->post('end_kontrak', true),
                'nilai_kontrak'   => $this->input->post('nilai_kontrak', true),
                'kendala_kontrak' => $this->input->post('kendala_kontrak', true),
                'anggaran_terpakai' => $this->input->post('anggaran_terpakai', true),

                'updated_at' => date('Y-m-d H:i:s'),
            ];

            // ✅ enforce backend: selain 4 tahapan => kontrak dipaksa NULL (agar tidak masuk list KKU)
            $tah = $data['tahapan_pengadaan'] ?? null;
            if (!$this->_kontrak_enabled_by_tahapan($tah)) {
                $data['no_kontrak'] = null;
                $data['vendor'] = null;
                $data['tgl_kontrak'] = null;
                $data['end_kontrak'] = null;
                $data['nilai_kontrak'] = null;
                $data['kendala_kontrak'] = null;
                $data['anggaran_terpakai'] = null;
            }

        } elseif ($this->_is_kku($role_raw)) {

            $data = [
                'tahapan_pembayaran' => $this->input->post('tahapan_pembayaran', true),
                'nilai_bayar'        => $this->input->post('nilai_bayar', true),
                'tgl_tahapan'        => $this->input->post('tgl_tahapan', true),

                'real_byr_bln1'  => $this->input->post('real_byr_bln1', true),
                'real_byr_bln2'  => $this->input->post('real_byr_bln2', true),
                'real_byr_bln3'  => $this->input->post('real_byr_bln3', true),
                'real_byr_bln4'  => $this->input->post('real_byr_bln4', true),
                'real_byr_bln5'  => $this->input->post('real_byr_bln5', true),
                'real_byr_bln6'  => $this->input->post('real_byr_bln6', true),
                'real_byr_bln7'  => $this->input->post('real_byr_bln7', true),
                'real_byr_bln8'  => $this->input->post('real_byr_bln8', true),
                'real_byr_bln9'  => $this->input->post('real_byr_bln9', true),
                'real_byr_bln10' => $this->input->post('real_byr_bln10', true),
                'real_byr_bln11' => $this->input->post('real_byr_bln11', true),
                'real_byr_bln12' => $this->input->post('real_byr_bln12', true),

                'updated_at' => date('Y-m-d H:i:s'),
            ];

        } else {
            show_error('Role tidak diizinkan update.', 403);
        }

        $merged = array_merge($row, $data);
        $data['status_kontrak'] = $this->_auto_status(
            $merged['tgl_nd_ams'] ?? null,
            $merged['nomor_nd_ams'] ?? null,
            $merged['tgl_kontrak'] ?? null,
            $merged['end_kontrak'] ?? null
        );

        $this->kontrak->update($id, $data);

        $this->_log('update', 'entry_kontrak', $id, $row['judul_drp'] ?? null);

        $this->session->set_flashdata('success', 'Data kontrak berhasil diperbarui.');
        redirect('entry_kontrak');
    }

    public function hapus($id)
    {
        $role_raw = $this->_get_role_raw();
        if (!$this->_is_admin($role_raw)) {
            show_error('Hanya admin yang boleh menghapus.', 403);
        }

        $row = $this->kontrak->get_by_id($id);
        if (!$row) show_404();

        $this->kontrak->delete($id);

        $this->_log('delete', 'entry_kontrak', $id, $row['judul_drp'] ?? null);

        $this->session->set_flashdata('success', 'Data kontrak berhasil dihapus.');
        redirect('entry_kontrak');
    }

    public function prk_by_jenis()
    {
        echo json_encode(
            $this->rekom->get_prk_by_jenis($this->input->post('jenis_anggaran', true))
        );
    }

    public function drp_by_prk()
    {
        echo json_encode(
            $this->rekom->get_drp_by_prk($this->input->post('nomor_prk', true))
        );
    }

    public function detail_prk_drp()
    {
        echo json_encode(
            $this->rekom->get_prk_drp_detail(
                $this->input->post('nomor_prk', true),
                $this->input->post('judul_drp', true)
            )
        );
    }

    /* =========================
       EXPORT: ambil semua data sesuai LIST (bukan per halaman)
       ========================= */
    private function _get_stage_for_current_role()
    {
        $role_raw = $this->_get_role_raw();
        if ($this->_is_admin($role_raw)) return ['stage' => 'admin', 'origin_label' => null];

        if ($this->_is_perencanaan($role_raw)) return ['stage' => 'perencanaan', 'origin_label' => null];
        if ($this->_is_pengadaan($role_raw))   return ['stage' => 'pengadaan', 'origin_label' => null];
        if ($this->_is_kku($role_raw))         return ['stage' => 'kku', 'origin_label' => null];

        // originator
        return ['stage' => 'originator', 'origin_label' => $this->_role_label($role_raw)];
    }

    private function _get_all_rows_for_current_list_export()
    {
        $search = $this->input->get('search', true);

        $ctx = $this->_get_stage_for_current_role();
        $stage = $ctx['stage'];
        $origin_label = $ctx['origin_label'];

        // ✅ Ambil semua data filtered sesuai list role (tanpa pagination)
        $rows = $this->kontrak->get_all_filtered($search, $stage, $origin_label);

        return [
            'search' => $search,
            'stage'   => $stage,
            'rows'    => $rows
        ];
    }

    public function export_csv()
    {
        // Block guest users from exporting
        if (function_exists('is_guest') && is_guest()) {
            $this->session->set_flashdata('error', 'Akses ditolak. Silakan login untuk mengunduh data.');
            redirect(strtolower($this->router->fetch_class()));
            return;
        }

        $ctx  = $this->_get_all_rows_for_current_list_export();
        $rows = $ctx['rows'];

        $role_label = $this->_role_label($this->_get_role_raw());
        $filename = 'entry_kontrak_' . strtolower((string)$role_label) . '_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($output, [
            'No',
            'Pengusul',
            'Jenis',
            'Nomor PRK',
            'PRK',
            'Judul DRP',
            'Metode',
            'Tahapan Pengadaan',
            'Prognosa',
            'Status',
            'RAB User',
            'Nilai Kontrak',
            'Vendor',
            'No Kontrak'
        ]);

        $no = 1;
        foreach ($rows as $r) {
            fputcsv($output, [
                $no++,
                (string)($r['user_pengusul'] ?? ''),
                (string)($r['jenis_anggaran'] ?? ''),
                (string)($r['nomor_prk'] ?? ''),
                (string)($r['uraian_prk'] ?? ''),
                (string)($r['judul_drp'] ?? ''),
                (string)($r['metode_pengadaan'] ?? ''),
                (string)($r['tahapan_pengadaan'] ?? ''),
                (string)($r['prognosa_kontrak'] ?? ''),
                (string)($r['status_kontrak'] ?? ''),
                (string)($r['rab_user'] ?? ''),
                (string)($r['nilai_kontrak'] ?? ''),
                (string)($r['vendor'] ?? ''),
                (string)($r['no_kontrak'] ?? ''),
            ]);
        }

        fclose($output);
        exit;
    }

    public function export_gsheet()
    {
        // Block guest users from exporting
        if (function_exists('is_guest') && is_guest()) {
            $this->session->set_flashdata('error', 'Akses ditolak. Silakan login untuk mengekspor data ke Google Sheets.');
            redirect(strtolower($this->router->fetch_class()));
            return;
        }
        if (!class_exists(\Google\Client::class)) {
            $autoload = FCPATH . 'vendor/autoload.php';
            if (file_exists($autoload)) {
                require_once $autoload;
            }
        }

        if (!class_exists(\Google\Client::class)) {
            $this->session->set_flashdata('error', 'Google API Client belum terbaca. Pastikan composer_autoload aktif dan vendor/autoload.php ada.');
            redirect('entry_kontrak');
            return;
        }

        // ✅ ambil SEMUA data sesuai list (tanpa pagination)
        $ctx  = $this->_get_all_rows_for_current_list_export();
        $rows = $ctx['rows'];

        $values = [];
        $values[] = [
            'No',
            'Pengusul',
            'Jenis',
            'Nomor PRK',
            'PRK',
            'Judul DRP',
            'Metode',
            'Tahapan Pengadaan',
            'Prognosa',
            'Status',
            'RAB User',
            'Nilai Kontrak',
            'Vendor',
            'No Kontrak'
        ];

        $no = 1;
        foreach ($rows as $r) {
            $values[] = [
                $no++,
                (string)($r['user_pengusul'] ?? ''),
                (string)($r['jenis_anggaran'] ?? ''),
                (string)($r['nomor_prk'] ?? ''),
                (string)($r['uraian_prk'] ?? ''),
                (string)($r['judul_drp'] ?? ''),
                (string)($r['metode_pengadaan'] ?? ''),
                (string)($r['tahapan_pengadaan'] ?? ''),
                (string)($r['prognosa_kontrak'] ?? ''),
                (string)($r['status_kontrak'] ?? ''),
                (string)($r['rab_user'] ?? ''),
                (string)($r['nilai_kontrak'] ?? ''),
                (string)($r['vendor'] ?? ''),
                (string)($r['no_kontrak'] ?? ''),
            ];
        }

        $saPath = APPPATH . 'config/google_service_account.json';
        if (!file_exists($saPath)) {
            $this->session->set_flashdata('error', 'File service account Google tidak ditemukan: ' . $saPath);
            redirect('entry_kontrak');
            return;
        }

        // Spreadsheet MASTER tetap 1 file ini
        $SPREADSHEET_ID = '1C3hqcfukTwIhM6l85ArtW-W9_ezwbDntff6jHzE_WpE';

        try {
            $client = new \Google\Client();
            $client->setApplicationName('Entry Kontrak Export');
            $client->setAuthConfig($saPath);

            $client->setScopes([
                \Google\Service\Sheets::SPREADSHEETS,
                \Google\Service\Drive::DRIVE_FILE,
            ]);

            $sheets = new \Google\Service\Sheets($client);
            $drive  = new \Google\Service\Drive($client);

            $fixedTitle = 'EXPORT';

            $spread = $sheets->spreadsheets->get($SPREADSHEET_ID, [
                'fields' => 'sheets(properties(sheetId,title))'
            ]);

            $exportSheetId = null;
            $sheetsArr = $spread->getSheets();
            $requests = [];

            if (is_array($sheetsArr)) {
                foreach ($sheetsArr as $sh) {
                    $prop = $sh->getProperties();
                    if (!$prop) continue;

                    $t = (string)$prop->getTitle();
                    $sid = $prop->getSheetId();

                    if ($t === $fixedTitle) {
                        $exportSheetId = $sid;
                        continue;
                    }

                    if (strpos($t, 'EXPORT_') === 0) {
                        $requests[] = new \Google\Service\Sheets\Request([
                            'deleteSheet' => ['sheetId' => $sid]
                        ]);
                    }
                }
            }

            if ($exportSheetId === null) {
                $requests[] = new \Google\Service\Sheets\Request([
                    'addSheet' => [
                        'properties' => [
                            'title' => $fixedTitle
                        ]
                    ]
                ]);
            }

            if (!empty($requests)) {
                $batchUpdateReq = new \Google\Service\Sheets\BatchUpdateSpreadsheetRequest([
                    'requests' => $requests
                ]);
                $sheets->spreadsheets->batchUpdate($SPREADSHEET_ID, $batchUpdateReq);

                $spread = $sheets->spreadsheets->get($SPREADSHEET_ID, [
                    'fields' => 'sheets(properties(sheetId,title))'
                ]);

                foreach ($spread->getSheets() as $sh) {
                    $prop = $sh->getProperties();
                    if ($prop && $prop->getTitle() === $fixedTitle) {
                        $exportSheetId = $prop->getSheetId();
                        break;
                    }
                }
            }

            if ($exportSheetId === null) {
                throw new \Exception('Gagal memastikan sheet EXPORT tersedia.');
            }

            $clearReq = new \Google\Service\Sheets\ClearValuesRequest();
            $sheets->spreadsheets_values->clear(
                $SPREADSHEET_ID,
                $fixedTitle . '!A:Z',
                $clearReq
            );

            $body = new \Google\Service\Sheets\ValueRange([
                'values' => $values
            ]);

            $params = ['valueInputOption' => 'RAW'];
            $sheets->spreadsheets_values->update(
                $SPREADSHEET_ID,
                $fixedTitle . '!A1',
                $body,
                $params
            );

            $permission = new \Google\Service\Drive\Permission([
                'type' => 'anyone',
                'role' => 'reader',
            ]);

            try {
                $drive->permissions->create($SPREADSHEET_ID, $permission, ['sendNotificationEmail' => false]);
            } catch (\Throwable $ePerm) {
                // jika permission sudah ada, abaikan
            }

            redirect('https://docs.google.com/spreadsheets/d/' . $SPREADSHEET_ID . '/edit#gid=' . $exportSheetId);
            return;

        } catch (\Throwable $e) {
            $this->session->set_flashdata('error', 'Gagal export ke Google Spreadsheet: ' . $e->getMessage());
            redirect('entry_kontrak');
            return;
        }
    }
}
