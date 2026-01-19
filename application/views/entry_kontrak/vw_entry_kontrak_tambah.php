<?php
defined('BASEPATH') or exit('No direct script access allowed');

function e($v)
{
    return htmlentities((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
}

$role_raw     = $role_raw ?? '';
$role_label   = $role_label ?? '';
$is_admin     = !empty($is_admin);

$r = strtolower(trim((string)$role_raw));
$is_pengadaan = ($r === 'pengadaan' || $r === 'pengadaan keuangan');
$is_kku       = ($r === 'kku');

// ✅ originator (yang boleh tambah data awal)
$is_originator = in_array($r, [
    'pemeliharaan',
    'operasi sistem distribusi',
    'fasilitas operasi',
    'k3l & kam',
], true);

// tambah boleh: admin + kku + originator (pengadaan tidak boleh tambah)
$show_nd_section        = $is_admin; // ND di tambah admin saja
$show_pengadaan_section = $is_admin; // pengadaan section di tambah admin saja

// ✅ PERBAIKAN: KKU saat tambah sama seperti originator -> hanya master+user
// Jadi section KKU saat tambah hanya admin saja
$show_kku_section       = $is_admin;
?>

<main class="main-content position-relative border-radius-lg">
    <?php $this->load->view('layout/navbar'); ?>

    <div class="container-fluid py-4">
        <div class="card shadow border-0 rounded-4">

            <div class="card-header bg-gradient-primary text-white">
                <h6 class="mb-0">ENTRY KONTRAK (Tambah)</h6>
                <small>Login sebagai: <b><?= e(strtoupper($role_label)); ?></b></small>
            </div>

            <div class="card-body">

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
                <?php endif; ?>

                <form method="post" action="<?= base_url('entry_kontrak/store'); ?>">

                    <h6 class="mt-2">AUTO (MASTER)</h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label>Jenis Anggaran</label>
                            <select name="jenis_anggaran" id="jenis_anggaran" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <?php foreach ($jenis_anggaran as $j): ?>
                                    <option value="<?= e($j['jenis_anggaran']); ?>"><?= e($j['jenis_anggaran']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-5">
                            <label>Nomor PRK</label>
                            <select name="nomor_prk" id="nomor_prk" class="form-select" required disabled>
                                <option value="">-- Pilih PRK --</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Judul DRP</label>
                            <select name="judul_drp" id="judul_drp" class="form-select" required disabled>
                                <option value="">-- Pilih DRP --</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Nomor SKK IO</label>
                            <input type="text" name="nomor_skk_io" id="nomor_skk_io" class="form-control" readonly>
                        </div>

                        <div class="col-md-4">
                            <label>Pagu SKK IO</label>
                            <input type="text" name="pagu_skk_io" id="pagu_skk_io" class="form-control" readonly>
                        </div>

                        <div class="col-md-4">
                            <label>Uraian PRK</label>
                            <input type="text" name="uraian_prk" id="uraian_prk" class="form-control" readonly>
                        </div>
                    </div>

                    <hr>

                    <h6>USER (Diisi oleh <?= e($role_label); ?>)</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label>Uraian Pekerjaan</label>
                            <textarea name="uraian_pekerjaan" class="form-control"></textarea>
                        </div>

                        <div class="col-md-3">
                            <label>User Pengusul (AUTO)</label>
                            <input type="text" class="form-control" value="<?= e($role_label); ?>" readonly>
                            <small class="text-muted">Terisi otomatis sesuai login.</small>
                        </div>

                        <div class="col-md-3">
                            <label>RAB User</label>
                            <input type="text" name="rab_user" class="form-control" placeholder="contoh: 150.000.000">
                        </div>

                        <div class="col-md-3">
                            <label>Rencana Hari Kerja</label>
                            <input type="number" name="renc_hari_kerja" class="form-control">
                        </div>
                    </div>

                    <?php if ($show_nd_section): ?>
                        <hr>
                        <h6>ND / AMS (Admin saja)</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label>Nomor ND/AMS</label>
                                <input type="text" name="nomor_nd_ams" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Tanggal ND/AMS</label>
                                <input type="date" name="tgl_nd_ams" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Keterangan</label>
                                <input type="text" name="keterangan" class="form-control">
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($show_pengadaan_section): ?>
                        <hr>
                        <h6>Bagian Pengadaan / Kontrak (Admin saja saat tambah)</h6>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label>No RKS</label>
                                <input type="text" name="no_rks" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>KAK</label>
                                <input type="text" name="kak" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Metode Pengadaan</label>
                                <select name="metode_pengadaan" class="form-select">
                                    <option value="">-- Pilih --</option>
                                    <option value="SPBJ UPL">SPBJ UPL</option>
                                    <option value="Kontrak Rinci">Kontrak Rinci</option>
                                    <option value="SPK">SPK</option>
                                    <option value="Tender">Tender</option>
                                    <option value="Penunjukan langsung">Penunjukan langsung</option>
                                </select>
                            </div>

                            <!-- ✅ Harga HPE + Prognosa Kontrak (Prognosa tepat dibawah HPE) -->
                            <div class="col-md-4">
                                <label>Harga HPE</label>
                                <input type="text" name="harga_hpe" class="form-control" placeholder="contoh: 12.000.000">

                                <div class="mt-3">
                                    <label>Prognosa Kontrak</label>
                                    <input type="date" name="prognosa_kontrak" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label>Harga HPS</label>
                                <input type="text" name="harga_hps" class="form-control" placeholder="contoh: 125.000.000">
                            </div>

                            <div class="col-md-4">
                                <label>Harga Nego</label>
                                <input type="text" name="harga_nego" class="form-control" placeholder="contoh: 500.000">
                            </div>

                            <!-- ✅ Tahapan Pengadaan diposisikan sebelum kolom kontrak -->
                            <div class="col-md-4">
                                <label>Tahapan Pengadaan</label>
                                <select name="tahapan_pengadaan" id="tahapan_pengadaan" class="form-select">
                                    <option value="">-- Pilih --</option>
                                    <option>Proses CDA</option>
                                    <option>Proses TTD Vendor</option>
                                    <option>Proses TTD Pengguna</option>
                                    <option>Pengadaan Selesai</option>
                                    <option>Pending & Menunggu Konfirmasi Anggaran</option>
                                    <option>Menunggu Nota Dinas</option>
                                    <option>Proses Kelengkapan Administrasi Calon Penyedia / Pendaftaran</option>
                                    <option>Proses Inisiasi Efroc</option>
                                    <option>Proses Penjilidan</option>
                                    <option>Pemberian Penjelasan</option>
                                    <option>Upload Dokumen</option>
                                    <option>Konfirmasi User</option>
                                    <option>Evaluasi Dokumen Penawaran</option>
                                    <option>Usulan Calon Pemenang</option>
                                    <option>Penetapan Calon Pemenang</option>
                                    <option>Masa Sanggah</option>
                                    <option>Gagal Lelang</option>
                                </select>
                                <small class="text-muted d-block mt-1">
                                    Jika memilih selain 4 tahapan (CDA/TTD Vendor/TTD Pengguna/Pengadaan Selesai), kolom kontrak akan otomatis nonaktif.
                                </small>
                            </div>

                            <div class="col-md-8"></div>

                            <!-- ✅ Kolom Kontrak (akan disabled/enable via JS) -->
                            <div class="col-md-4">
                                <label>No Kontrak</label>
                                <input type="text" name="no_kontrak" class="form-control kontrak-field" id="no_kontrak">
                            </div>
                            <div class="col-md-4">
                                <label>Vendor</label>
                                <input type="text" name="vendor" class="form-control kontrak-field" id="vendor">
                            </div>
                            <div class="col-md-4">
                                <label>Tanggal Kontrak</label>
                                <input type="date" name="tgl_kontrak" class="form-control kontrak-field" id="tgl_kontrak">
                            </div>
                            <div class="col-md-4">
                                <label>Akhir Kontrak</label>
                                <input type="date" name="end_kontrak" class="form-control kontrak-field" id="end_kontrak">
                            </div>
                            <div class="col-md-4">
                                <label>Nilai Kontrak</label>
                                <input type="text" name="nilai_kontrak" class="form-control kontrak-field" id="nilai_kontrak" placeholder="contoh: 240.000.000">
                            </div>

                            <div class="col-md-12">
                                <label>Kendala Kontrak</label>
                                <textarea name="kendala_kontrak" class="form-control kontrak-field" id="kendala_kontrak"></textarea>
                            </div>

                        </div>
                    <?php endif; ?>

                    <?php if ($show_kku_section): ?>
                        <hr>
                        <h6>KKU (Tahapan Pembayaran + Realisasi Bayar)</h6>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label>Tahapan Pembayaran</label>
                                <select name="tahapan_pembayaran" class="form-select">
                                    <option value="">-- Pilih Tahapan --</option>
                                    <option value="adm tagih & vendor">adm tagih & vendor</option>
                                    <option value="Submit VIP">Submit VIP</option>
                                    <option value="Approval 1">Approval 1</option>
                                    <option value="Approval 2">Approval 2</option>
                                    <option value="Approval Pusat">Approval Pusat</option>
                                    <option value="Pembayaran">Pembayaran</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Nilai Bayar</label>
                                <input type="text" name="nilai_bayar" class="form-control" placeholder="contoh: 120.000.000">
                            </div>
                            <div class="col-md-4">
                                <label>Tanggal Tahapan</label>
                                <input type="date" name="tgl_tahapan" class="form-control">
                            </div>
                        </div>

                        <hr>
                        <h6 class="mt-2">Realisasi Bayar per Bulan (Manual)</h6>
                        <div class="row g-3">
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <div class="col-md-3">
                                    <label>Realisasi Bulan <?= $i; ?></label>
                                    <input type="text" name="real_byr_bln<?= $i; ?>" class="form-control" placeholder="contoh: 10.000.000">
                                </div>
                            <?php endfor; ?>
                        </div>
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
    function resetMasterFields() {
        $('#nomor_skk_io').val('');
        $('#pagu_skk_io').val('');
        $('#uraian_prk').val('');
    }

    function postJSON(url, data, onSuccess) {
        $.ajax({
            url: url,
            method: "POST",
            data: data,
            dataType: "json",
            success: onSuccess,
            error: function(xhr) {
                console.error("AJAX Error:", url, xhr.status, xhr.responseText);
                alert("Gagal load dropdown. Cek console (F12).");
            }
        });
    }

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

    // ✅ toggle enable/disable kolom kontrak
    function toggleKontrakFields() {
        var tahapan = $('#tahapan_pengadaan').val();
        var allow = isTahapanKontrak(tahapan);

        // jika tidak allow -> disable semua field kontrak
        $('.kontrak-field').each(function() {
            $(this).prop('disabled', !allow);
        });
    }

    $(document).ready(function() {
        // init kontrak fields state
        toggleKontrakFields();

        // on change tahapan
        $('#tahapan_pengadaan').on('change', function() {
            toggleKontrakFields();
        });

        $('#jenis_anggaran').change(function() {
            const jenis = $(this).val();
            resetMasterFields();

            $('#nomor_prk').prop('disabled', true).html('<option value="">-- Pilih PRK --</option>');
            $('#judul_drp').prop('disabled', true).html('<option value="">-- Pilih DRP --</option>');

            if (!jenis) return;

            postJSON("<?= base_url('entry_kontrak/prk_by_jenis'); ?>", {
                jenis_anggaran: jenis
            }, function(data) {
                let opt = '<option value="">-- Pilih PRK --</option>';
                if (Array.isArray(data)) {
                    data.forEach(function(v) {
                        opt += '<option value="' + (v.nomor_prk || '') + '">' + (v.nomor_prk || '') + ' - ' + (v.uraian_prk || '') + '</option>';
                    });
                }
                $('#nomor_prk').html(opt).prop('disabled', false);
            });
        });

        $('#nomor_prk').change(function() {
            const prk = $(this).val();
            resetMasterFields();
            $('#judul_drp').prop('disabled', true).html('<option value="">-- Pilih DRP --</option>');
            if (!prk) return;

            postJSON("<?= base_url('entry_kontrak/drp_by_prk'); ?>", {
                nomor_prk: prk
            }, function(drp) {
                let opt = '<option value="">-- Pilih DRP --</option>';
                if (Array.isArray(drp)) {
                    drp.forEach(function(v) {
                        opt += '<option value="' + (v.judul_drp || '') + '">' + (v.judul_drp || '') + '</option>';
                    });
                }
                $('#judul_drp').html(opt).prop('disabled', false);
            });
        });

        $('#judul_drp').change(function() {
            const prk = $('#nomor_prk').val();
            const drp = $(this).val();
            resetMasterFields();
            if (!prk || !drp) return;

            postJSON("<?= base_url('entry_kontrak/detail_prk_drp'); ?>", {
                nomor_prk: prk,
                judul_drp: drp
            }, function(d) {
                if (!d || !d.nomor_skk_io) {
                    alert("Detail master tidak ditemukan. Cek master_rekomposisi.");
                    return;
                }
                $('#nomor_skk_io').val(d.nomor_skk_io || '');
                $('#pagu_skk_io').val(d.pagu_skk_io || '');
                $('#uraian_prk').val(d.uraian_prk || '');
            });
        });
    });
</script>
