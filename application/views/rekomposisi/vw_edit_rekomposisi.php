<main class="main-content position-relative border-radius-lg">
    <?php $this->load->view('layout/navbar'); ?>

    <div class="container-fluid py-4">
        <div class="card shadow border-0 rounded-4">

            <div class="card-header bg-gradient-primary text-white">
                <h6 class="mb-0">Form Edit Data Rekomposisi</h6>
            </div>

            <div class="card-body">
                <form action="<?= base_url('rekomposisi/edit/' . $rekomposisi['id']); ?>" method="post">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label>Jenis Anggaran</label>
                            <select name="JENIS_ANGGARAN" class="form-control" required>
                                <option value="OPERASI" <?= $rekomposisi['jenis_anggaran'] == 'OPERASI' ? 'selected' : ''; ?>>OPERASI</option>
                                <option value="INVESTASI" <?= $rekomposisi['jenis_anggaran'] == 'INVESTASI' ? 'selected' : ''; ?>>INVESTASI</option>
                            </select>
                        </div>


                        <div class="col-md-6">
                            <label>Nomor PRK</label>
                            <input type="text" name="NOMOR_PRK" class="form-control"
                                value="<?= htmlentities($rekomposisi['nomor_prk']); ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label>Nomor SKK IO</label>
                            <input type="text" name="NOMOR_SKK_IO" class="form-control"
                                value="<?= htmlentities($rekomposisi['nomor_skk_io']); ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label>PRK</label>
                            <input type="text" name="PRK" class="form-control"
                                value="<?= htmlentities($rekomposisi['uraian_prk']); ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label>Pagu SKK IO</label>
                            <input type="number" name="SKKI_O" class="form-control"
                                value="<?= $rekomposisi['pagu_skk_io']; ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label>Judul DRP</label>
                            <input type="text" name="JUDUL_DRP" class="form-control"
                                value="<?= htmlentities($rekomposisi['judul_drp']); ?>">
                        </div>

                        <div class="col-md-12">
                            <label>LKAO Usulan</label>
                            <input type="text" name="LKAO_USULAN" class="form-control"
                                value="<?= htmlentities($rekomposisi['lkao_usulan']); ?>">
                        </div>

                        <div class="col-12 mt-4">
                            <a href="<?= base_url('rekomposisi'); ?>" class="btn btn-secondary">Batal</a>
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