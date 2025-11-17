<?php // Form Add Rekomposisi 
?>
<main class="main-content position-relative border-radius-lg">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm">
                        <a class="opacity-5 text-white" href="<?= base_url('dashboard'); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item text-sm">
                        <a class="opacity-5 text-white" href="<?= base_url('rekomposisi'); ?>">Data Rekomposisi</a>
                    </li>
                    <li class="breadcrumb-item text-sm text-white active" aria-current="page">Tambah Data</li>
                </ol>
                <h6 class="font-weight-bolder text-white mb-0">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Data Rekomposisi
                </h6>
            </nav>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-gradient-primary text-white">
                <h6 class="mb-0">Form Tambah Data Rekomposisi</h6>
            </div>

            <div class="card-body">
                <form id="addRekomposisiForm" action="<?= base_url('rekomposisi/tambah'); ?>" method="POST">
                    <div class="row g-3">

                        <!-- Jenis Anggaran -->
                        <div class="col-md-6">
                            <label class="form-label">Jenis Anggaran</label>
                            <input type="text" name="JENIS_ANGGARAN" class="form-control" required>
                        </div>

                        <!-- Nomor PRK -->
                        <div class="col-md-6">
                            <label class="form-label">Nomor PRK</label>
                            <input type="text" name="NOMOR_PRK" class="form-control" required>
                        </div>

                        <!-- Nomor SKK IO -->
                        <div class="col-md-6">
                            <label class="form-label">Nomor SKK IO</label>
                            <input type="text" name="NOMOR_SKK_IO" class="form-control" required>
                        </div>

                        <!-- PRK -->
                        <div class="col-md-6">
                            <label class="form-label">PRK</label>
                            <input type="text" name="PRK" class="form-control">
                        </div>

                        <!-- SKKI O -->
                        <div class="col-md-6">
                            <label class="form-label">SKKI O</label>
                            <input type="text" name="SKKI_O" class="form-control">
                        </div>

                        <!-- Rekomposisi -->
                        <div class="col-md-12">
                            <label class="form-label">Rekomposisi</label>
                            <textarea name="REKOMPOSISI" class="form-control" rows="3"></textarea>
                        </div>

                        <!-- Judul DRP -->
                        <div class="col-md-12">
                            <label class="form-label">Judul DRP</label>
                            <input type="text" name="JUDUL_DRP" class="form-control">
                        </div>

                        <!-- Tombol Submit -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Data
                            </button>
                            <a href="<?= base_url('rekomposisi'); ?>" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>