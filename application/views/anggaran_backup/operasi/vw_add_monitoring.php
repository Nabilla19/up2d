<?php // Form Add Monitoring Operasi ?>
<main class="main-content position-relative border-radius-lg">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm">
                        <a class="opacity-5 text-white" href="<?= base_url('dashboard'); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item text-sm">
                        <a class="opacity-5 text-white" href="<?= base_url('anggaran/operasi/monitoring'); ?>">Monitoring Operasi</a>
                    </li>
                    <li class="breadcrumb-item text-sm text-white active" aria-current="page">Tambah Data</li>
                </ol>
                <h6 class="font-weight-bolder text-white mb-0">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Monitoring Operasi
                </h6>
            </nav>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-gradient-primary text-white">
                <h6 class="mb-0 text-white"><i class="fas fa-tachometer-alt me-2"></i>Form Tambah Data Monitoring Operasi</h6>
            </div>

            <div class="card-body">
                <form id="addMonitoringOpForm" action="<?= base_url('anggaran/operasi/store_monitoring'); ?>" method="POST">
                    <div class="row g-3">

                        <!-- Sumber Dana -->
                        <div class="col-md-6">
                            <label class="form-label">Sumber Dana</label>
                            <textarea name="SUMBER_DANA" class="form-control" rows="2" required></textarea>
                        </div>

                        <!-- SKKO -->
                        <div class="col-md-6">
                            <label class="form-label">SKKO</label>
                            <input type="text" name="SKKO" class="form-control" required>
                        </div>

                        <!-- Sub Pos -->
                        <div class="col-md-6">
                            <label class="form-label">Sub Pos</label>
                            <input type="text" name="SUB_POS" class="form-control">
                        </div>

                        <!-- DRP -->
                        <div class="col-md-6">
                            <label class="form-label">DRP</label>
                            <input type="text" name="DRP" class="form-control">
                        </div>

                        <!-- Uraian Kontrak/Pekerjaan -->
                        <div class="col-md-12">
                            <label class="form-label">Uraian Kontrak / Pekerjaan</label>
                            <textarea name="URAIAN_KONTRAK_PEKERJAAN" class="form-control" rows="3" required></textarea>
                        </div>

                        <!-- User -->
                        <div class="col-md-4">
                            <label class="form-label">User</label>
                            <select name="USER" class="form-control" required>
                                <option value="">-- Pilih User --</option>
                                <option value="FASOP">FASOP</option>
                                <option value="HAR">HAR</option>
                                <option value="OPDIST">OPDIST</option>
                                <option value="K3">K3</option>
                            </select>
                        </div>

                        <!-- Pagu Ang/RAB User -->
                        <div class="col-md-4">
                            <label class="form-label">Pagu Ang/RAB User</label>
                            <input type="text" name="PAGU_ANG_RAB_USER" class="form-control">
                        </div>

                        <!-- Komitmen ND -->
                        <div class="col-md-4">
                            <label class="form-label">Komitmen ND</label>
                            <input type="text" name="KOMITMENT_ND" class="form-control">
                        </div>

                        <!-- Renc Akhir Kontrak -->
                        <div class="col-md-4">
                            <label class="form-label">Renc Akhir Kontrak</label>
                            <input type="text" name="RENC_AKHIR_KONTRAK" class="form-control" placeholder="dd/mm/yyyy">
                        </div>

                        <!-- Tgl ND/AMS -->
                        <div class="col-md-4">
                            <label class="form-label">Tgl ND/AMS</label>
                            <input type="text" name="TGL_ND_AMS" class="form-control" placeholder="dd/mm/yyyy">
                        </div>

                        <!-- Nomor ND/AMS -->
                        <div class="col-md-4">
                            <label class="form-label">Nomor ND / AMS</label>
                            <input type="text" name="NOMOR_ND_AMS" class="form-control">
                        </div>

                        <!-- Keterangan -->
                        <div class="col-md-6">
                            <label class="form-label">Keterangan</label>
                            <input type="text" name="KETERANGAN" class="form-control">
                        </div>

                        <!-- Tahap Kontrak -->
                        <div class="col-md-6">
                            <label class="form-label">Tahap Kontrak</label>
                            <input type="text" name="TAHAP_KONTRAK" class="form-control">
                        </div>

                        <!-- Prognosa -->
                        <div class="col-md-4">
                            <label class="form-label">Prognosa</label>
                            <input type="text" name="PROGNOSA" class="form-control" placeholder="dd/mm/yyyy">
                        </div>

                        <!-- No SPK/SPB/Kontrak -->
                        <div class="col-md-4">
                            <label class="form-label">No SPK / SPB / Kontrak</label>
                            <input type="text" name="NO_SPK_SPB_KONTRAK" class="form-control">
                        </div>

                        <!-- Rekanan -->
                        <div class="col-md-4">
                            <label class="form-label">Rekanan</label>
                            <input type="text" name="REKANAN" class="form-control">
                        </div>

                        <!-- Tgl Kontrak -->
                        <div class="col-md-4">
                            <label class="form-label">Tgl Kontrak</label>
                            <input type="text" name="TGL_KONTRAK" class="form-control" placeholder="dd/mm/yyyy">
                        </div>

                        <!-- Tgl Akhir Kontrak -->
                        <div class="col-md-4">
                            <label class="form-label">Tgl Akhir Kontrak</label>
                            <input type="text" name="TGL_AKHIR_KONTRAK" class="form-control" placeholder="dd/mm/yyyy">
                        </div>

                        <!-- Nilai Kontrak Total -->
                        <div class="col-md-4">
                            <label class="form-label">Nilai Kontrak Total</label>
                            <input type="text" name="NILAI_KONTRAK_TOTAL" class="form-control">
                        </div>

                        <!-- Nilai Kontrak Tahun Berjalan -->
                        <div class="col-md-4">
                            <label class="form-label">Nilai Kontrak Tahun Berjalan</label>
                            <input type="text" name="NILAI_KONTRAK_TAHUN_BERJALAN" class="form-control">
                        </div>

                        <!-- Tgl Bayar -->
                        <div class="col-md-4">
                            <label class="form-label">Tgl Bayar</label>
                            <input type="text" name="TGL_BAYAR" class="form-control" placeholder="dd/mm/yyyy">
                        </div>

                        <!-- Anggaran Terpakai -->
                        <div class="col-md-4">
                            <label class="form-label">Anggaran Terpakai</label>
                            <input type="text" name="ANGGARAN_TERPAKAI" class="form-control">
                        </div>

                        <!-- Sisa Anggaran -->
                        <div class="col-md-4">
                            <label class="form-label">Sisa Anggaran</label>
                            <input type="text" name="SISA_ANGGARAN" class="form-control">
                        </div>

                        <!-- Status Kontrak -->
                        <div class="col-md-4">
                            <label class="form-label">Status Kontrak</label>
                            <select name="STATUS_KONTRAK" class="form-control">
                                <option value="">-- Pilih Status --</option>
                                <option value="NODIN/SRT">NODIN/SRT</option>
                                <option value="TERKONTRAK">TERKONTRAK</option>
                                <option value="TERBAYAR">TERBAYAR</option>
                                <option value="SELESAI">SELESAI</option>
                                <option value="HUTANG USAHA 2024">HUTANG USAHA 2024</option>
                                <option value="MULTIYEARS">MULTIYEARS</option>
                                <option value="KONTRAK 2025">KONTRAK 2025</option>
                            </select>
                        </div>

                        <!-- Bulan Kontrak 1-12 -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary">Data Bulan Kontrak</h6>
                            <hr>
                        </div>

                        <?php for($i = 1; $i <= 12; $i++): ?>
                        <div class="col-md-3">
                            <label class="form-label">Bulan Kontrak <?= $i; ?></label>
                            <input type="text" name="BLN_KTRK<?= $i; ?>" class="form-control" placeholder="Nilai bulan <?= $i; ?>">
                        </div>
                        <?php endfor; ?>

                        <!-- Bulan Renc Bayar -->
                        <div class="col-md-6">
                            <label class="form-label">Bulan Renc Bayar</label>
                            <input type="text" name="BULAN_RENC_BAYAR" class="form-control" placeholder="mm/yy">
                        </div>

                        <!-- Bulan Bayar -->
                        <div class="col-md-6">
                            <label class="form-label">Bulan Bayar</label>
                            <input type="text" name="BULAN_BAYAR" class="form-control" placeholder="mm/yy">
                        </div>

                        <!-- Tombol Submit -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Data
                            </button>
                            <a href="<?= base_url('anggaran/operasi/monitoring'); ?>" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
