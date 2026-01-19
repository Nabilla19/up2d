<main class="main-content position-relative border-radius-lg">
    <?php $this->load->view('layout/navbar'); ?>

    <div class="container-fluid py-4">
        <div class="card shadow border-0 rounded-4">

            <div class="card-header bg-gradient-primary text-white rounded-top-4">
                <h6 class="mb-0">Form Tambah Data Rekomposisi</h6>
            </div>

            <div class="card-body">

                <?php if (validation_errors()): ?>
                    <div class="alert alert-danger text-white">
                        <?= validation_errors(); ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger text-white">
                        <?= $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('rekomposisi/tambah'); ?>" method="post" autocomplete="off">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Jenis Anggaran</label>
                            <select name="JENIS_ANGGARAN" class="form-control" required>
                                <option value="OPERASI" <?= set_value('JENIS_ANGGARAN') == 'OPERASI' ? 'selected' : ''; ?>>OPERASI</option>
                                <option value="INVESTASI" <?= set_value('JENIS_ANGGARAN') == 'INVESTASI' ? 'selected' : ''; ?>>INVESTASI</option>
                            </select>
                        </div>


                        <div class="col-md-6">
                            <label class="form-label">Nomor PRK</label>
                            <input type="text" name="NOMOR_PRK" class="form-control" required
                                value="<?= set_value('NOMOR_PRK'); ?>" placeholder="Contoh: 2025.DRKR.4.001">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nomor SKK IO</label>
                            <input type="text" name="NOMOR_SKK_IO" class="form-control" required
                                value="<?= set_value('NOMOR_SKK_IO'); ?>" placeholder="Contoh: 027/KEU...">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">PRK</label>
                            <input type="text" name="PRK" class="form-control" required
                                value="<?= set_value('PRK'); ?>" placeholder="Contoh: Har Dist - Command Center">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Pagu SKK IO</label>
                            <input type="text" name="SKKI_O" class="form-control" required
                                value="<?= set_value('SKKI_O'); ?>"
                                placeholder="Contoh: 2.721.473.184">
                            <small class="text-muted">Boleh pakai titik sebagai pemisah ribuan.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Judul DRP</label>
                            <input type="text" name="JUDUL_DRP" class="form-control" required
                                value="<?= set_value('JUDUL_DRP'); ?>" placeholder="Judul DRP">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">LKAO Usulan</label>
                            <input type="text" name="LKAO_USULAN" class="form-control"
                                value="<?= set_value('LKAO_USULAN'); ?>" placeholder="Opsional">
                        </div>

                        <div class="col-12 mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan
                            </button>
                            <a href="<?= base_url('rekomposisi'); ?>" class="btn btn-secondary">
                                Batal
                            </a>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
</main>