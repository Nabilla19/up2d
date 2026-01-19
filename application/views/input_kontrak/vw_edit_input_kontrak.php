<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
        <div class="container-fluid py-1 px-3">
            <h6 class="font-weight-bolder text-white mb-0">
                <i class="fas fa-file-signature me-2 text-info"></i> Edit Input Kontrak
            </h6>
        </div>
    </nav>
    <div class="container-fluid py-4">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-gradient-primary text-white"><strong>Form Edit Input Kontrak</strong></div>
            <div class="card-body">
                <form action="<?= base_url('input_kontrak/update/' . urlencode($input_kontrak['ID'] ?? '')); ?>" method="post">
                    <input type="hidden" name="original_id" value="<?= htmlentities($input_kontrak['ID'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Sumber Dana</label>
                            <input type="text" class="form-control" name="SUMBER_DANA" value="<?= htmlentities($input_kontrak['SUMBER_DANA'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">SKKO</label>
                            <input type="text" class="form-control" name="SKKO" value="<?= htmlentities($input_kontrak['SKKO'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">SUB POS</label>
                            <input type="text" class="form-control" name="SUB_POS" value="<?= htmlentities($input_kontrak['SUB_POS'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">DRP</label>
                            <input type="text" class="form-control" name="DRP" value="<?= htmlentities($input_kontrak['DRP'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Uraian Kontrak / Pekerjaan</label>
                            <input type="text" class="form-control" name="URAIAN_KONTRAK_PEKERJAAN" value="<?= htmlentities($input_kontrak['URAIAN_KONTRAK_PEKERJAAN'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">User</label>
                            <input type="text" class="form-control" name="USER" value="<?= htmlentities($input_kontrak['USER'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pagu Ang/RAB User</label>
                            <input type="number" class="form-control" name="PAGU_ANG_RAB_USER" value="<?= htmlentities($input_kontrak['PAGU_ANG_RAB_USER'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Komitment ND</label>
                            <input type="text" class="form-control" name="KOMITMENT_ND" value="<?= htmlentities($input_kontrak['KOMITMENT_ND'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Renc Akhir Kontrak</label>
                            <input type="date" class="form-control" name="RENC_AKHIR_KONTRAK" value="<?= htmlentities($input_kontrak['RENC_AKHIR_KONTRAK'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tgl ND/AMS</label>
                            <input type="date" class="form-control" name="TGL_ND_AMS" value="<?= htmlentities($input_kontrak['TGL_ND_AMS'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nomor ND / AMS</label>
                            <input type="text" class="form-control" name="NOMOR_ND_AMS" value="<?= htmlentities($input_kontrak['NOMOR_ND_AMS'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Keterangan</label>
                            <input type="text" class="form-control" name="KETERANGAN" value="<?= htmlentities($input_kontrak['KETERANGAN'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tahap Kontrak</label>
                            <input type="text" class="form-control" name="TAHAP_KONTRAK" value="<?= htmlentities($input_kontrak['TAHAP_KONTRAK'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Prognosa</label>
                            <input type="text" class="form-control" name="PROGNOSA" value="<?= htmlentities($input_kontrak['PROGNOSA'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">No SPK / SPB / Kontrak</label>
                            <input type="text" class="form-control" name="NO_SPK_SPB_KONTRAK" value="<?= htmlentities($input_kontrak['NO_SPK_SPB_KONTRAK'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Rekanan</label>
                            <input type="text" class="form-control" name="REKANAN" value="<?= htmlentities($input_kontrak['REKANAN'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tgl Kontrak</label>
                            <input type="date" class="form-control" name="TGL_KONTRAK" value="<?= htmlentities($input_kontrak['TGL_KONTRAK'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tgl Akhir Kontrak</label>
                            <input type="date" class="form-control" name="TGL_AKHIR_KONTRAK" value="<?= htmlentities($input_kontrak['TGL_AKHIR_KONTRAK'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Nilai Kontrak Total</label>
                            <input type="number" class="form-control" name="NILAI_KONTRAK_TOTAL" value="<?= htmlentities($input_kontrak['NILAI_KONTRAK_TOTAL'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Nilai Kontrak Tahun Berjalan</label>
                            <input type="number" class="form-control" name="NILAI_KONTRAK_TAHUN_BERJALAN" value="<?= htmlentities($input_kontrak['NILAI_KONTRAK_TAHUN_BERJALAN'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tgl Bayar</label>
                            <input type="date" class="form-control" name="TGL_BAYAR" value="<?= htmlentities($input_kontrak['TGL_BAYAR'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Anggaran Terpakai</label>
                            <input type="number" class="form-control" name="ANGGARAN_TERPAKAI" value="<?= htmlentities($input_kontrak['ANGGARAN_TERPAKAI'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Sisa Anggaran</label>
                            <input type="number" class="form-control" name="SISA_ANGGARAN" value="<?= htmlentities($input_kontrak['SISA_ANGGARAN'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status Kontrak</label>
                            <input type="text" class="form-control" name="STATUS_KONTRAK" value="<?= htmlentities($input_kontrak['STATUS_KONTRAK'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <!-- Bulan Kontrak 1-12 -->
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <div class="col-md-2">
                                <label class="form-label">BLN KTRK<?= $i ?></label>
                                <input type="number" class="form-control" name="BLN_KTRK<?= $i ?>" value="<?= htmlentities($input_kontrak['BLN_KTRK' . $i] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        <?php endfor; ?>

                        <div class="col-md-3">
                            <label class="form-label">Bulan Renc Bayar</label>
                            <input type="text" class="form-control" name="BULAN_RENC_BAYAR" value="<?= htmlentities($input_kontrak['BULAN_RENC_BAYAR'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Bulan Bayar</label>
                            <input type="text" class="form-control" name="BULAN_BAYAR" value="<?= htmlentities($input_kontrak['BULAN_BAYAR'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                    </div>
                    <div class="mt-4">
                        <a href="<?= base_url('input_kontrak'); ?>" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>