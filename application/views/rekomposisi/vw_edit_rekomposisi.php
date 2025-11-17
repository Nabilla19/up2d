<main class="main-content position-relative border-radius-lg">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
        <div class="container-fluid py-1 px-3">
            <h6 class="font-weight-bolder text-white mb-0">
                <i class="fas fa-random me-2 text-info"></i> Edit Data Rekomposisi
            </h6>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-gradient-primary text-white">
                <strong>Form Edit Data Rekomposisi</strong>
            </div>
            <div class="card-body">
                <form action="<?= base_url('rekomposisi/edit/' . urlencode($rekomposisi['ID_REKOMPOSISI'] ?? '')); ?>" method="post">
                    <input type="hidden" name="original_id" value="<?= htmlentities($rekomposisi['ID_REKOMPOSISI'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                    <div class="row g-3">

                        <!-- Jenis Anggaran -->
                        <div class="col-md-6">
                            <label class="form-label">Jenis Anggaran</label>
                            <input type="text" class="form-control" name="JENIS_ANGGARAN" value="<?= htmlentities($rekomposisi['JENIS_ANGGARAN'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>

                        <!-- Nomor PRK -->
                        <div class="col-md-6">
                            <label class="form-label">Nomor PRK</label>
                            <input type="text" class="form-control" name="NOMOR_PRK" value="<?= htmlentities($rekomposisi['NOMOR_PRK'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>

                        <!-- Nomor SKK IO -->
                        <div class="col-md-6">
                            <label class="form-label">Nomor SKK IO</label>
                            <input type="text" class="form-control" name="NOMOR_SKK_IO" value="<?= htmlentities($rekomposisi['NOMOR_SKK_IO'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>

                        <!-- PRK -->
                        <div class="col-md-6">
                            <label class="form-label">PRK</label>
                            <input type="text" class="form-control" name="PRK" value="<?= htmlentities($rekomposisi['PRK'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <!-- SKKI O -->
                        <div class="col-md-6">
                            <label class="form-label">SKKI O</label>
                            <input type="text" class="form-control" name="SKKI_O" value="<?= htmlentities($rekomposisi['SKKI_O'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <!-- Rekomposisi -->
                        <div class="col-md-12">
                            <label class="form-label">Rekomposisi</label>
                            <textarea name="REKOMPOSISI" class="form-control" rows="3"><?= htmlentities($rekomposisi['REKOMPOSISI'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>

                        <!-- Judul DRP -->
                        <div class="col-md-12">
                            <label class="form-label">Judul DRP</label>
                            <input type="text" name="JUDUL_DRP" class="form-control" value="<?= htmlentities($rekomposisi['JUDUL_DRP'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <!-- Tombol Submit -->
                        <div class="col-12 mt-4">
                            <a href="<?= base_url('rekomposisi'); ?>" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</main>