<?php
defined('BASEPATH') or exit('No direct script access allowed');

function e($v)
{
    return htmlentities((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
}
function sel($a, $b)
{
    return (strtolower(trim((string)$a)) === strtolower(trim((string)$b))) ? 'selected' : '';
}

$role_raw   = $role_raw ?? '';
$is_admin   = !empty($is_admin);
$r = strtolower(trim($role_raw));

// role
$is_perencanaan = ($r === 'perencanaan');
$is_pengadaan   = ($r === 'pengadaan' || $r === 'pengadaan keuangan');
$is_kku         = ($r === 'kku');

// originator (tidak termasuk pengadaan)
$is_originator = in_array($r, [
    'pemeliharaan',
    'operasi sistem distribusi',
    'fasilitas operasi',
    'k3l & kam',
], true);

// stage completeness
$nd_complete = !empty($row['nomor_nd_ams']) && !empty($row['tgl_nd_ams']);
$kontrak_complete = !empty($row['no_kontrak'])
    && !empty($row['vendor'])
    && !empty($row['tgl_kontrak'])
    && !empty($row['end_kontrak']);

// tampil section
$show_master_user = ($is_admin || $is_originator || $is_perencanaan || $is_pengadaan || $is_kku);
$show_nd          = ($is_admin || $is_perencanaan || $is_pengadaan || $is_kku);
$show_pengadaan   = ($is_admin || (($is_pengadaan || $is_kku) && $nd_complete)); // KKU boleh lihat (read-only)
$show_kku         = ($is_admin || ($is_kku && $nd_complete && $kontrak_complete));

// editability
$can_edit_master_user = ($is_admin || $is_originator);
$can_edit_nd          = ($is_admin || $is_perencanaan);
$can_edit_pengadaan   = ($is_admin || $is_pengadaan) && $nd_complete;
// ✅ KKU hanya edit bagian KKU (ND+Kontrak lengkap)
$can_edit_kku         = ($is_admin || $is_kku) && $nd_complete && $kontrak_complete;
?>

<main class="main-content position-relative border-radius-lg">
    <?php $this->load->view('layout/navbar'); ?>

    <div class="container-fluid py-4">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-gradient-primary text-white">
                <h6 class="mb-0">ENTRY KONTRAK (Edit)</h6>
            </div>

            <div class="card-body">

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
                <?php endif; ?>

                <form method="post" action="<?= base_url('entry_kontrak/update/' . (int)($row['id'] ?? 0)); ?>">

                    <?php if ($show_master_user): ?>
                        <h6 class="mt-2">MASTER + USER</h6>
                        <div class="row g-3">

                            <div class="col-md-3">
                                <label>Jenis Anggaran</label>
                                <select name="jenis_anggaran" id="jenis_anggaran" class="form-select" <?= $can_edit_master_user ? '' : 'disabled'; ?> required>
                                    <option value="">-- Pilih --</option>
                                    <?php foreach ($jenis_anggaran as $j): ?>
                                        <option value="<?= e($j['jenis_anggaran']); ?>" <?= sel($row['jenis_anggaran'] ?? '', $j['jenis_anggaran']); ?>>
                                            <?= e($j['jenis_anggaran']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-5">
                                <label>Nomor PRK</label>
                                <select name="nomor_prk" id="nomor_prk" class="form-select" <?= $can_edit_master_user ? '' : 'disabled'; ?> required>
                                    <option value="<?= e($row['nomor_prk'] ?? ''); ?>">
                                        <?= e(($row['nomor_prk'] ?? '') . ' - ' . ($row['uraian_prk'] ?? '')); ?>
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Judul DRP</label>
                                <select name="judul_drp" id="judul_drp" class="form-select" <?= $can_edit_master_user ? '' : 'disabled'; ?> required>
                                    <option value="<?= e($row['judul_drp'] ?? ''); ?>"><?= e($row['judul_drp'] ?? ''); ?></option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Nomor SKK IO</label>
                                <input type="text" class="form-control" readonly value="<?= e($row['nomor_skk_io'] ?? ''); ?>">
                            </div>
                            <div class="col-md-4">
                                <label>Pagu SKK IO</label>
                                <input type="text" class="form-control" readonly value="<?= e($row['pagu_skk_io'] ?? ''); ?>">
                            </div>
                            <div class="col-md-4">
                                <label>Uraian PRK</label>
                                <input type="text" class="form-control" readonly value="<?= e($row['uraian_prk'] ?? ''); ?>">
                            </div>

                            <div class="col-md-6">
                                <label>Uraian Pekerjaan</label>
                                <textarea name="uraian_pekerjaan" class="form-control" <?= $can_edit_master_user ? '' : 'readonly'; ?>><?= e($row['uraian_pekerjaan'] ?? ''); ?></textarea>
                            </div>

                            <div class="col-md-3">
                                <label>User Pengusul</label>
                                <input type="text" class="form-control" readonly value="<?= e($row['user_pengusul'] ?? ''); ?>">
                            </div>

                            <div class="col-md-3">
                                <label>RAB User</label>
                                <input type="text" name="rab_user" class="form-control" value="<?= e($row['rab_user'] ?? ''); ?>" <?= $can_edit_master_user ? '' : 'readonly'; ?>>
                            </div>

                            <div class="col-md-3">
                                <label>Rencana Hari Kerja</label>
                                <input type="number" name="renc_hari_kerja" class="form-control" value="<?= e($row['renc_hari_kerja'] ?? ''); ?>" <?= $can_edit_master_user ? '' : 'readonly'; ?>>
                            </div>

                        </div>
                        <hr>
                    <?php endif; ?>

                    <?php if ($show_nd): ?>
                        <h6>ND / AMS</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label>Nomor ND/AMS</label>
                                <input type="text" name="nomor_nd_ams" class="form-control" value="<?= e($row['nomor_nd_ams'] ?? ''); ?>" <?= $can_edit_nd ? '' : 'readonly'; ?>>
                            </div>
                            <div class="col-md-4">
                                <label>Tanggal ND/AMS</label>
                                <input type="date" name="tgl_nd_ams" class="form-control" value="<?= e($row['tgl_nd_ams'] ?? ''); ?>" <?= $can_edit_nd ? '' : 'readonly'; ?>>
                            </div>
                            <div class="col-md-4">
                                <label>Keterangan</label>
                                <input type="text" name="keterangan" class="form-control" value="<?= e($row['keterangan'] ?? ''); ?>" <?= $can_edit_nd ? '' : 'readonly'; ?>>
                            </div>
                        </div>
                        <hr>
                    <?php endif; ?>

                    <?php if ($show_pengadaan): ?>
                        <h6>Pengadaan / Kontrak</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label>No RKS</label>
                                <input type="text" name="no_rks" class="form-control" value="<?= e($row['no_rks'] ?? ''); ?>" <?= $can_edit_pengadaan ? '' : 'readonly'; ?>>
                            </div>
                            <div class="col-md-4">
                                <label>KAK</label>
                                <input type="text" name="kak" class="form-control" value="<?= e($row['kak'] ?? ''); ?>" <?= $can_edit_pengadaan ? '' : 'readonly'; ?>>
                            </div>
                            <div class="col-md-4">
                                <label>Metode Pengadaan</label>
                                <select name="metode_pengadaan" class="form-select" <?= $can_edit_pengadaan ? '' : 'disabled'; ?>>
                                    <option value="">-- Pilih --</option>
                                    <?php foreach (['SPBJ UPL', 'Kontrak Rinci', 'SPK', 'Tender', 'Penunjukan langsung'] as $m): ?>
                                        <option value="<?= e($m); ?>" <?= sel($row['metode_pengadaan'] ?? '', $m); ?>><?= e($m); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- ✅ Harga HPE + Prognosa Kontrak (Prognosa tepat dibawah HPE) -->
                            <div class="col-md-4">
                                <label>Harga HPE</label>
                                <input type="text" name="harga_hpe" class="form-control" value="<?= e($row['harga_hpe'] ?? ''); ?>" <?= $can_edit_pengadaan ? '' : 'readonly'; ?>>

                                <div class="mt-3">
                                    <label>Prognosa Kontrak</label>
                                    <input type="date" name="prognosa_kontrak" class="form-control" value="<?= e($row['prognosa_kontrak'] ?? ''); ?>" <?= $can_edit_pengadaan ? '' : 'readonly'; ?>>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label>Harga HPS</label>
                                <input type="text" name="harga_hps" class="form-control" value="<?= e($row['harga_hps'] ?? ''); ?>" <?= $can_edit_pengadaan ? '' : 'readonly'; ?>>
                            </div>
                            <div class="col-md-4">
                                <label>Harga Nego</label>
                                <input type="text" name="harga_nego" class="form-control" value="<?= e($row['harga_nego'] ?? ''); ?>" <?= $can_edit_pengadaan ? '' : 'readonly'; ?>>
                            </div>

                            <!-- ✅ Tahapan Pengadaan diposisikan sebelum kolom kontrak -->
                            <div class="col-md-4">
                                <label>Tahapan Pengadaan</label>
                                <select name="tahapan_pengadaan" id="tahapan_pengadaan" class="form-select" <?= $can_edit_pengadaan ? '' : 'disabled'; ?>>
                                    <option value="">-- Pilih --</option>
                                    <?php foreach (
                                        [
                                            'Proses CDA',
                                            'Proses TTD Vendor',
                                            'Proses TTD Pengguna',
                                            'Pengadaan Selesai',
                                            'Pending & Menunggu Konfirmasi Anggaran',
                                            'Menunggu Nota Dinas',
                                            'Proses Kelengkapan Administrasi Calon Penyedia / Pendaftaran',
                                            'Proses Inisiasi Efroc',
                                            'Proses Penjilidan',
                                            'Pemberian Penjelasan',
                                            'Upload Dokumen',
                                            'Konfirmasi User',
                                            'Evaluasi Dokumen Penawaran',
                                            'Usulan Calon Pemenang',
                                            'Penetapan Calon Pemenang',
                                            'Masa Sanggah',
                                            'Gagal Lelang'
                                        ] as $o
                                    ): ?>
                                        <option value="<?= e($o); ?>" <?= sel($row['tahapan_pengadaan'] ?? '', $o); ?>><?= e($o); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted d-block mt-1">
                                    Jika memilih selain 4 tahapan (CDA/TTD Vendor/TTD Pengguna/Pengadaan Selesai), kolom kontrak akan otomatis nonaktif.
                                </small>
                            </div>

                            <div class="col-md-8"></div>

                            <!-- ✅ Kolom Kontrak (akan disabled/enable via JS bila boleh edit pengadaan) -->
                            <div class="col-md-4">
                                <label>No Kontrak</label>
                                <input type="text" name="no_kontrak" class="form-control kontrak-field" id="no_kontrak"
                                    value="<?= e($row['no_kontrak'] ?? ''); ?>"
                                    <?= $can_edit_pengadaan ? '' : 'readonly'; ?>>
                            </div>
                            <div class="col-md-4">
                                <label>Vendor</label>
                                <input type="text" name="vendor" class="form-control kontrak-field" id="vendor"
                                    value="<?= e($row['vendor'] ?? ''); ?>"
                                    <?= $can_edit_pengadaan ? '' : 'readonly'; ?>>
                            </div>

                            <div class="col-md-4">
                                <label>Tanggal Kontrak</label>
                                <input type="date" name="tgl_kontrak" class="form-control kontrak-field" id="tgl_kontrak"
                                    value="<?= e($row['tgl_kontrak'] ?? ''); ?>"
                                    <?= $can_edit_pengadaan ? '' : 'readonly'; ?>>
                            </div>
                            <div class="col-md-4">
                                <label>Akhir Kontrak</label>
                                <input type="date" name="end_kontrak" class="form-control kontrak-field" id="end_kontrak"
                                    value="<?= e($row['end_kontrak'] ?? ''); ?>"
                                    <?= $can_edit_pengadaan ? '' : 'readonly'; ?>>
                            </div>

                            <div class="col-md-4">
                                <label>Nilai Kontrak</label>
                                <input type="text" name="nilai_kontrak" class="form-control kontrak-field" id="nilai_kontrak"
                                    value="<?= e($row['nilai_kontrak'] ?? ''); ?>"
                                    <?= $can_edit_pengadaan ? '' : 'readonly'; ?>>
                            </div>

                            <div class="col-md-12">
                                <label>Kendala Kontrak</label>
                                <textarea name="kendala_kontrak" class="form-control kontrak-field" id="kendala_kontrak" <?= $can_edit_pengadaan ? '' : 'readonly'; ?>><?= e($row['kendala_kontrak'] ?? ''); ?></textarea>
                            </div>

                        </div>
                        <hr>
                    <?php endif; ?>

                    <?php if ($show_kku): ?>
                        <h6>KKU</h6>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label>Tahapan Pembayaran</label>
                                <select name="tahapan_pembayaran" class="form-select" <?= $can_edit_kku ? '' : 'disabled'; ?>>
                                    <option value="">-- Pilih Tahapan --</option>
                                    <?php foreach (['adm tagih & vendor', 'Submit VIP', 'Approval 1', 'Approval 2', 'Approval Pusat', 'Pembayaran'] as $o): ?>
                                        <option value="<?= e($o); ?>" <?= sel($row['tahapan_pembayaran'] ?? '', $o); ?>><?= e($o); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Nilai Bayar</label>
                                <input type="text" name="nilai_bayar" class="form-control" value="<?= e($row['nilai_bayar'] ?? ''); ?>" <?= $can_edit_kku ? '' : 'readonly'; ?>>
                            </div>

                            <div class="col-md-4">
                                <label>Tanggal Tahapan</label>
                                <input type="date" name="tgl_tahapan" class="form-control" value="<?= e($row['tgl_tahapan'] ?? ''); ?>" <?= $can_edit_kku ? '' : 'readonly'; ?>>
                            </div>
                        </div>

                        <hr>
                        <h6 class="mt-2">Realisasi Bayar per Bulan (Manual)</h6>
                        <div class="row g-3">
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <?php $field = 'real_byr_bln' . $i; ?>
                                <div class="col-md-3">
                                    <label>Realisasi Bulan <?= $i; ?></label>
                                    <input type="text" name="<?= e($field); ?>" class="form-control"
                                        value="<?= e($row[$field] ?? ''); ?>"
                                        <?= $can_edit_kku ? '' : 'readonly'; ?>
                                        placeholder="contoh: 10.000.000">
                                </div>
                            <?php endfor; ?>
                        </div>

                        <hr>
                    <?php endif; ?>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                        <a href="<?= base_url('entry_kontrak'); ?>" class="btn btn-secondary">Batal</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
<script>
    // ✅ rule tahapan pengadaan yang mengaktifkan kolom kontrak
    function isTahapanKontrak(val) {
        val = (val || '').toString().trim();
        return [
            'Proses CDA',
            'Proses TTD Vendor',
            'Proses TTD Pengguna',
            'Pengadaan Selesai'
        ].indexOf(val) !== -1;
    }

    // ✅ toggle enable/disable kolom kontrak (HANYA jika pengadaan boleh edit)
    function toggleKontrakFields(canEditPengadaan) {
        if (!canEditPengadaan) return;

        var tahapan = $('#tahapan_pengadaan').val();
        var allow = isTahapanKontrak(tahapan);

        $('.kontrak-field').each(function() {
            $(this).prop('disabled', !allow);
        });
    }

    $(document).ready(function() {
        var canEditPengadaan = <?= $can_edit_pengadaan ? 'true' : 'false'; ?>;

        // init kontrak fields state
        toggleKontrakFields(canEditPengadaan);

        // on change tahapan
        $('#tahapan_pengadaan').on('change', function() {
            toggleKontrakFields(canEditPengadaan);
        });
    });
</script>
