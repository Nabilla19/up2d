<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller for Import Data
 *
 * @property CI_Input $input
 * @property CI_Session $session
 * @property ImportJob_model $ImportJob_model
 * @property CI_Upload $upload
 * @property CI_DB_query_builder $db
 */
class Import extends CI_Controller
{
    private $entityMap = [
        'gi'           => 'gi',
        'gardu_induk'  => 'gi',
        'gardu_hubung' => 'gh',
        'gh_cell'      => 'gh_cell',
        'gi_cell'      => 'gi_cell',
        'kit_cell'     => 'kit_cell',
        'pembangkit'   => 'pembangkit',
        'pemutus'      => 'lbs_recloser',
        'unit'         => 'unit',
        'ulp'          => 'ulp',
        'data_kontrak' => 'data_kontrak',
        'rekomposisi'  => 'master_rekomposisi',
        'master_rekomposisi' => 'master_rekomposisi',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'form', 'file']);
        $this->load->library(['session', 'upload']);
        $this->load->database();
        $this->load->model(['ImportJob_model']);
    }

    // Validate and whitelist return_to parameter to avoid open redirects
    private function safe_return_to($r)
    {
        $r = trim((string)$r);
        if (empty($r)) return null;
        $base = rtrim(base_url(), '/');
        // allow absolute internal URLs starting with base_url
        if (strpos($r, $base) === 0) return $r;
        // allow relative paths starting with '/'
        if (strpos($r, '/') === 0) return $r;
        return null;
    }

    // =========================
    // Helpers (validasi CSV)
    // =========================
    private function normalize_header($s)
    {
        $s = strtolower(trim((string)$s));
        $s = preg_replace('/\s+/', ' ', $s);
        $s = preg_replace('/[^a-z0-9_ ]/', '', $s);
        $s = str_replace(' ', '_', $s);
        return $s;
    }

    private function get_expected_import_columns($target_table)
    {
        // kolom sistem yang tidak wajib ada di CSV
        $exclude = ['id', 'created_at', 'updated_at'];

        if ($target_table === 'gi') {
            $this->ImportJob_model->ensure_staging_for_gi();
            $fields = $this->db->list_fields('gi_import_raw');

            // staging GI punya kolom sistem lain
            $exclude = array_merge($exclude, ['import_id', 'row_no']);
            $excludeUpper = array_map('strtoupper', $exclude);

            $expected = [];
            foreach ($fields as $f) {
                if (!in_array(strtoupper($f), $excludeUpper, true)) {
                    $expected[] = strtoupper($f);
                }
            }
            return $expected;
        }

        if (!$this->db->table_exists($target_table)) {
            return null; // tabel target tidak ada
        }

        $fields = $this->db->list_fields($target_table);
        $isLower = in_array($target_table, ['master_rekomposisi', 'data_kontrak'], true);

        $expected = [];
        foreach ($fields as $f) {
            if (in_array(strtolower($f), $exclude, true)) continue;
            $expected[] = $isLower ? strtolower($f) : strtoupper($f);
        }
        return $expected;
    }

    private function map_csv_headers_to_columns(array $rawHeaders, $target_table)
    {
        $aliases = [
            'unit_layanan'   => 'UNIT_LAYANAN',
            'gardu_induk'    => 'GARDU_INDUK',
            'longitudex'     => 'LONGITUDEX',
            'latitudey'      => 'LATITUDEY',
            'status_operasi' => 'STATUS_OPERASI',
            'ip_rtu'         => 'IP_RTU',
            'ip_gateway'     => 'IP_GATEWAY',
        ];

        if ($target_table === 'data_kontrak') {
            $aliases = array_merge($aliases, [
                'jenis_anggaran'      => 'jenis_anggaran_text',
                'nomor_prk'           => 'nomor_prk_text',
                'uraian_prk'          => 'uraian_prk_text',
                'nomor_skkio'         => 'nomor_skk_io_text',
                'uraian_pekerjaan'    => 'uraian_pekerjaan',
                'user_pengusul'       => 'user_pengusul',
                'rab_user'            => 'rab_user',
                'rencana_hari_kerja'  => 'rencana_hari_kerja',
                'jenis_penagihan'     => 'jenis_penagihan',
                'tanggal_bastp'       => 'tanggal_bastp',
                'tgl_nd_ams'          => 'tgl_nd_ams',
                'nomor_nd_ams'        => 'nomor_nd_ams',
                'status_kontrak'      => 'status_kontrak',
                'keterangan'          => 'keterangan',
                'no_rks'              => 'no_rks',
                'metode_pengadaan'    => 'metode_pengadaan',
                'tahapan_pengadaan'   => 'tahapan_pengadaan',
                'prognosa_kontrak'    => 'prognosa_kontrak_tgl',
                'no_kontrak'          => 'no_kontrak',
                'vendor'              => 'pelaksana_vendor',
                'pelaksana_vendor'    => 'pelaksana_vendor',
                'tgl_kontrak'         => 'tgl_kontrak',
                'end_kontrak'         => 'end_kontrak',
                'nilai_kontrak'       => 'nilai_kontrak',
                'kendala_kontrak'     => 'kendala_kontrak',
                'tahapan_pembayaran'  => 'tahapan_pembayaran',
                'nilai_bayar'         => 'nilai_bayar',
                'tgl_tahapan'         => 'tgl_tahapan',
            ]);
        }

        $isLower = in_array($target_table, ['master_rekomposisi', 'data_kontrak'], true);

        $mappedByIndex = [];
        foreach ($rawHeaders as $i => $h) {
            $key = $this->normalize_header($h);
            $col = isset($aliases[$key]) ? $aliases[$key] : strtoupper($key);
            $col = $isLower ? strtolower($col) : strtoupper($col);
            $mappedByIndex[$i] = $col;
        }

        return $mappedByIndex; // index -> col
    }

    private function validate_csv_or_error(array $rawHeaders, $target_table)
    {
        $expected = $this->get_expected_import_columns($target_table);
        if ($expected === null) {
            return 'Tabel target tidak ditemukan: ' . $target_table;
        }

        $mappedByIndex = $this->map_csv_headers_to_columns($rawHeaders, $target_table);
        $mappedCols = array_values($mappedByIndex);

        // cek duplikat header setelah normalisasi/mapping
        if (count($mappedCols) !== count(array_unique($mappedCols))) {
            return 'Header CSV ada yang duplikat (nama kolom terdeteksi sama setelah normalisasi).';
        }

        // cek jumlah kolom
        if (count($mappedCols) !== count($expected)) {
            return 'Jumlah kolom CSV (' . count($mappedCols) . ') tidak sama dengan kolom tabel (' . count($expected) . ').';
        }

        // cek mismatch nama kolom (set compare)
        $missing = array_values(array_diff($expected, $mappedCols));
        $extra   = array_values(array_diff($mappedCols, $expected));

        if (!empty($missing)) {
            return 'Kolom CSV kurang: ' . implode(', ', $missing);
        }
        if (!empty($extra)) {
            return 'Kolom CSV tidak dikenali oleh tabel: ' . implode(', ', $extra);
        }

        return null;
    }

    // =========================
    // Pages
    // =========================

    // Upload form
    public function index($entity = 'gi')
    {
        $entity = strtolower($entity);
        if (!isset($this->entityMap[$entity])) $entity = 'gi';
        $targetTable = $this->entityMap[$entity];

        // kolom yang diharapkan (buat ditampilkan di view)
        $expected_columns = $this->get_expected_import_columns($targetTable);

        $data = [
            'title'            => 'Import ' . strtoupper($targetTable) . ' - Phase 1',
            'entity'           => $entity,
            'target_table'     => $targetTable,
            'expected_columns' => $expected_columns,
            'return_to'        => $this->safe_return_to($this->input->get('return_to', TRUE)),
        ];
        $this->load->view('import/form', $data);
    }

    // Preview first 100 rows (CSV only + strict column validation)
    public function preview($entity = 'gi')
    {
        $entity = strtolower($entity);
        if (!isset($this->entityMap[$entity])) $entity = 'gi';
        $targetTable = $this->entityMap[$entity];
        // Preserve validated return_to from form or query string
        $return_to = $this->safe_return_to($this->input->post('return_to', TRUE) ?? $this->input->get('return_to', TRUE) ?? '');

        $config = [
            'upload_path'   => FCPATH . 'uploads/imports/',
            'allowed_types' => 'csv',     // ✅ ONLY CSV
            'max_size'      => 10240,
            'encrypt_name'  => TRUE,
        ];
        if (!is_dir($config['upload_path'])) @mkdir($config['upload_path'], 0755, true);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file')) {
            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
            return redirect('import/' . $entity . ($return_to ? '?return_to=' . urlencode($return_to) : ''));
        }

        $fileData = $this->upload->data();
        $fullpath = $fileData['full_path'];

        // extra safety
        if (strtolower($fileData['file_ext']) !== '.csv') {
            @unlink($fullpath);
            $this->session->set_flashdata('error', 'File harus CSV (.csv).');
            return redirect('import/' . $entity . ($return_to ? '?return_to=' . urlencode($return_to) : ''));
        }

        // baca header dulu untuk validasi kolom
        $fp = fopen($fullpath, 'r');
        if ($fp === false) {
            @unlink($fullpath);
            $this->session->set_flashdata('error', 'Tidak bisa membuka file CSV.');
            return redirect('import/' . $entity . ($return_to ? '?return_to=' . urlencode($return_to) : ''));
        if ($firstLine === false) {
            fclose($fp);
            @unlink($fullpath);
            $this->session->set_flashdata('error', 'File CSV kosong / tidak valid.');
            return redirect('import/' . $entity . ($return_to ? '?return_to=' . urlencode($return_to) : ''));
        }

        $sep = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';
        $rawHeaders = str_getcsv(trim($firstLine), $sep);

        $err = $this->validate_csv_or_error($rawHeaders, $targetTable);
        if ($err !== null) {
            fclose($fp);
            @unlink($fullpath);
            $this->session->set_flashdata('error', 'Tidak bisa import: ' . $err);
            return redirect('import/' . $entity . ($return_to ? '?return_to=' . urlencode($return_to) : ''));
        }

        // kalau valid, baru create job
        $this->ImportJob_model->ensure_schema();
        $stagingTable = ($targetTable === 'gi') ? 'gi_import_raw' : null;

        $job_id = $this->ImportJob_model->create_job([
            'target_table'      => $targetTable,
            'staging_table'     => $stagingTable,
            'original_filename' => $fileData['client_name'],
            'stored_path'       => 'uploads/imports/' . $fileData['file_name'],
            'file_size'         => (int)$fileData['file_size'],
            'chunk_size'        => 500,
            'duplicate_policy'  => 'ask',
            'status'            => 'preview',
        ]);

        // preview 100 rows
        $rows = [];
        $lineNo = 0;
        rewind($fp);
        while (($data = fgetcsv($fp, 0, $sep)) !== false) {
            $lineNo++;
            if ($lineNo === 1) continue;
            $rows[] = $data;
            if (count($rows) >= 100) break;
        }
        fclose($fp);

        $data = [
            'title'            => 'Preview Import ' . strtoupper($targetTable),
            'job_id'           => $job_id,
            'entity'           => $entity,
            'target_table'     => $targetTable,
            'expected_columns' => $this->get_expected_import_columns($targetTable),
            'headers'          => $rawHeaders,
            'rows'             => $rows,
            'return_to'        => $return_to,
        ];
        $this->load->view('import/preview', $data);
    }

    // Process chunks into staging/target (CSV only + strict validation again)
    public function process($entity = 'gi')
    {
        $job_id = (int)$this->input->post('job_id');
        $return_to = $this->safe_return_to($this->input->post('return_to', TRUE) ?? '');
        $entity = strtolower($entity);

        $job = $this->ImportJob_model->get_job($job_id);
        if (!$job) return show_error('Job not found', 404);

        $stored_path = FCPATH . $job->stored_path;
        if (!is_file($stored_path)) return show_error('File not found', 404);

        // pastikan CSV
        if (strtolower(pathinfo($stored_path, PATHINFO_EXTENSION)) !== 'csv') {
            $this->ImportJob_model->finish_job($job_id, ['status' => 'failed']);
            $this->session->set_flashdata('error', 'File harus CSV (.csv).');
            return redirect('import/status/' . $job_id);
        }

        $fp = fopen($stored_path, 'r');
        if ($fp === false) return show_error('Cannot open file', 500);

        $firstLine = fgets($fp);
        if ($firstLine === false) {
            fclose($fp);
            $this->ImportJob_model->finish_job($job_id, ['status' => 'failed']);
            $this->session->set_flashdata('error', 'File CSV kosong / tidak valid.');
            return redirect('import/status/' . $job_id);
        }

        $sep = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';
        $rawHeaders = str_getcsv(trim($firstLine), $sep);

        // validasi ulang (anti bypass)
        $err = $this->validate_csv_or_error($rawHeaders, $job->target_table);
        if ($err !== null) {
            fclose($fp);
            $this->ImportJob_model->finish_job($job_id, ['status' => 'failed']);
            $this->session->set_flashdata('error', 'Tidak bisa import: ' . $err);
            return redirect('import/status/' . $job_id);
        }

        $mappedByIndex = $this->map_csv_headers_to_columns($rawHeaders, $job->target_table);

        $inserted = 0;
        $failed   = 0;
        $total    = 0;

        // ========= CASE 1: GI -> staging =========
        if ($job->target_table === 'gi') {
            $this->ImportJob_model->ensure_staging_for_gi();

            $lineNo = 1; // header sudah terbaca
            $batch = [];
            $chunkSize = 500;

            while (($row = fgetcsv($fp, 0, $sep)) !== false) {
                $lineNo++;
                $total++;

                // validasi struktur per baris (kolom harus konsisten)
                if (count($row) !== count($rawHeaders)) {
                    fclose($fp);
                    $this->ImportJob_model->finish_job($job_id, [
                        'total_rows' => $total,
                        'inserted'   => $inserted,
                        'failed'     => $failed + 1,
                        'status'     => 'failed',
                    ]);
                    $this->session->set_flashdata('error', 'Tidak bisa import: Struktur CSV tidak konsisten. Kolom pada baris ' . $lineNo . ' tidak sama dengan header.');
                    return redirect('import/status/' . $job_id . ($return_to ? '?return_to=' . urlencode($return_to) : ''));
                }

                $assoc = [];
                foreach ($row as $i => $val) {
                    $col = isset($mappedByIndex[$i]) ? $mappedByIndex[$i] : null;
                    if (!$col) continue;
                    $assoc[$col] = is_string($val) ? trim($val) : $val;
                }

                $assoc['import_id'] = $job_id;
                $assoc['row_no']    = $lineNo - 1;

                $batch[] = $assoc;

                if (count($batch) >= $chunkSize) {
                    $ok = $this->ImportJob_model->insert_staging_batch('gi', $batch);
                    if (!$ok) $failed += count($batch);
                    else $inserted += count($batch);
                    $batch = [];
                }
            }

            if (count($batch) > 0) {
                $ok = $this->ImportJob_model->insert_staging_batch('gi', $batch);
                if (!$ok) $failed += count($batch);
                else $inserted += count($batch);
            }

            fclose($fp);

            $this->ImportJob_model->finish_job($job_id, [
                'total_rows' => $total,
                'inserted'   => $inserted,
                'failed'     => $failed,
                'status'     => 'done'
            ]);

            $this->session->set_flashdata('success', 'Import selesai (staging): total ' . $total . ', masuk ' . $inserted . ', gagal ' . $failed);
            return redirect('import/status/' . $job_id . ($return_to ? '?return_to=' . urlencode($return_to) : ''));
        }

        // ========= CASE 2: entity lain -> direct append =========
        $target_table = $job->target_table;
        if (!$this->db->table_exists($target_table)) {
            fclose($fp);
            $this->ImportJob_model->finish_job($job_id, ['status' => 'failed']);
            $this->session->set_flashdata('error', 'Tabel target tidak ditemukan: ' . $target_table);
            return redirect('import/status/' . $job_id);
        }

        $target_fields = $this->db->list_fields($target_table);
        $isLower = in_array($target_table, ['master_rekomposisi', 'data_kontrak'], true);

        $lineNo = 1;
        $batch = [];
        $chunkSize = 500;

        while (($row = fgetcsv($fp, 0, $sep)) !== false) {
            $lineNo++;
            $total++;

            if (count($row) !== count($rawHeaders)) {
                fclose($fp);
                $this->ImportJob_model->finish_job($job_id, [
                    'total_rows' => $total,
                    'inserted'   => $inserted,
                    'failed'     => $failed + 1,
                    'status'     => 'failed',
                ]);
                $this->session->set_flashdata('error', 'Tidak bisa import: Struktur CSV tidak konsisten. Kolom pada baris ' . $lineNo . ' tidak sama dengan header.');
                return redirect('import/status/' . $job_id);
            }

            $assoc = [];
            foreach ($row as $i => $val) {
                $col = isset($mappedByIndex[$i]) ? $mappedByIndex[$i] : null;
                if (!$col) continue;

                $colFinal = $isLower ? strtolower($col) : strtoupper($col);

                if (in_array($colFinal, $target_fields, true)) {
                    $assoc[$colFinal] = is_string($val) ? trim($val) : $val;
                }
            }

            if (!empty($assoc)) $batch[] = $assoc;

            if (count($batch) >= $chunkSize) {
                $this->db->insert_batch($target_table, $batch);
                $aff = $this->db->affected_rows();
                if ($aff > 0) $inserted += $aff;
                else $failed += count($batch);
                $batch = [];
            }
        }

        if (count($batch) > 0) {
            $this->db->insert_batch($target_table, $batch);
            $aff = $this->db->affected_rows();
            if ($aff > 0) $inserted += $aff;
            else $failed += count($batch);
        }

        fclose($fp);

        $this->ImportJob_model->finish_job($job_id, [
            'total_rows' => $total,
            'inserted'   => $inserted,
            'failed'     => $failed,
            'status'     => 'committed'
        ]);

        if ($target_table === 'master_rekomposisi') {
            $this->session->set_flashdata('success', 'Import master rekomposisi selesai: total ' . $total . ', masuk ' . $inserted . ', gagal ' . $failed);
            return redirect('rekomposisi');
        }

        if ($target_table === 'data_kontrak') {
            $this->session->set_flashdata('success', 'Import data kontrak selesai: total ' . $total . ', masuk ' . $inserted . ', gagal ' . $failed);
            return redirect('data_kontrak');
        }

        $this->session->set_flashdata('success', 'Import selesai: total ' . $total . ', masuk ' . $inserted . ', gagal ' . $failed);
        return redirect('import/status/' . $job_id . ($return_to ? '?return_to=' . urlencode($return_to) : ''));
    }

    public function status($job_id)
    {
        $job = $this->ImportJob_model->get_job((int)$job_id);
        if (!$job) return show_404();
        $return_to = $this->safe_return_to($this->input->get('return_to', TRUE));
        $data = ['title' => 'Status Import', 'job' => $job, 'return_to' => $return_to];
        $this->load->view('import/status', $data);
    }

    public function download_error($job_id)
    {
        show_404();
    }

    public function commit($job_id)
    {
        $job = $this->ImportJob_model->get_job((int)$job_id);
        if (!$job) return show_404();

        if (empty($job->staging_table)) {
            $this->session->set_flashdata('error', 'Commit tidak diperlukan untuk import ini. Data sudah dimasukkan.');
            return redirect('import/status/' . $job->id);
        }

        $inserted = $this->ImportJob_model->commit_staging_to_target($job->id, $job->target_table);
        if ($inserted === -1) {
            $this->session->set_flashdata('error', 'Tabel target tidak ditemukan: ' . $job->target_table);
        } elseif ($inserted === -2) {
            $this->session->set_flashdata('error', 'Skema tabel target memiliki PRIMARY KEY yang bukan AUTO_INCREMENT. Ubah kolom PK menjadi AUTO_INCREMENT atau hapus PK sebelum commit (append-only).');
        } else {
            $this->ImportJob_model->finish_job($job->id, [
                'updated' => 0,
                'skipped' => 0,
                'status'  => 'committed'
            ]);
            $this->session->set_flashdata('success', 'Berhasil commit ke tabel ' . $job->target_table . ': ' . $inserted . ' baris ditambahkan.');
        }

        return redirect('import/status/' . $job->id);
    }
}
