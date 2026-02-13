<?php // Form Add Rekomposisi Investasi ?>
<main class="main-content position-relative border-radius-lg">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm">
                        <a class="opacity-5 text-white" href="<?= base_url('dashboard'); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item text-sm">
                        <a class="opacity-5 text-white" href="<?= base_url('anggaran/investasi/rekomposisi'); ?>">Rekomposisi Investasi</a>
                    </li>
                    <li class="breadcrumb-item text-sm text-white active" aria-current="page">Tambah Data</li>
                </ol>
                <h6 class="font-weight-bolder text-white mb-0">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Rekomposisi Investasi
                </h6>
            </nav>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-gradient-primary text-white">
                <h6 class="mb-0 text-white"><i class="fas fa-chart-line me-2"></i>Form Tambah Data Rekomposisi Investasi</h6>
            </div>

            <div class="card-body">
                <form id="addRekomposisiInvForm" action="<?= base_url('anggaran/investasi/store_rekomposisi'); ?>" method="POST">
                    <div class="row g-3">

                        <!-- No -->
                        <div class="col-md-3">
                            <label class="form-label">No</label>
                            <input type="text" name="NO" class="form-control" placeholder="Nomor urut">
                        </div>

                        <!-- Unit Detail -->
                        <div class="col-md-9">
                            <label class="form-label">Unit Detail</label>
                            <input type="text" name="UNIT_DETAIL" class="form-control" required>
                        </div>

                        <!-- Nomor SKKO -->
                        <div class="col-md-12">
                            <label class="form-label">Nomor SKKO</label>
                            <textarea name="NOMOR_SKKO" class="form-control" rows="2" required></textarea>
                        </div>

                        <!-- Uraian Kegiatan -->
                        <div class="col-md-12">
                            <label class="form-label">Uraian Kegiatan</label>
                            <textarea name="URAIAN_KEGIATAN" class="form-control" rows="3" required></textarea>
                        </div>

                        <!-- SKKO (Sebelum) -->
                        <div class="col-md-6">
                            <label class="form-label">SKKO (Sebelum)</label>
                            <input type="text" name="SKKO_SEBELUM" class="form-control" placeholder="Nilai SKKO sebelum rekomposisi">
                        </div>

                        <!-- SKKO (Sesudah) -->
                        <div class="col-md-6">
                            <label class="form-label">SKKO (Sesudah)</label>
                            <input type="text" name="SKKO_SESUDAH" class="form-control" placeholder="Nilai SKKO sesudah rekomposisi">
                        </div>

                        <!-- Keterangan -->
                        <div class="col-md-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="KET" class="form-control" rows="3" placeholder="Keterangan tambahan"></textarea>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Data
                            </button>
                            <a href="<?= base_url('anggaran/investasi/rekomposisi'); ?>" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
