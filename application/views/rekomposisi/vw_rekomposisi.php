<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>

    <div class="container-fluid py-4">

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success text-white">
                <?= $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger text-white">
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <div class="card mb-4 shadow border-0 rounded-4">

            <!-- HEADER -->
            <div class="card-header py-2 d-flex justify-content-between align-items-center bg-gradient-primary text-white rounded-top-4">
                <h6 class="mb-0 d-flex align-items-center text-white"><i class="fas fa-chart-line me-2"></i>Tabel Data Rekomposisi</h6>

                <div class="d-flex align-items-center" style="padding-top: 16px;">

                    <?php if (function_exists('can_create') && can_create()): ?>
                        <a href="<?= base_url('rekomposisi/tambah') ?>"
                            class="btn btn-sm btn-light text-primary me-2 d-flex align-items-center no-anim">
                            <i class="fas fa-plus me-1"></i> Tambah
                        </a>

                        <!-- ✅ TAMBAHAN: tombol Import (mengarah ke controller Import) -->
                        <a href="<?= base_url('import/rekomposisi?return_to=' . urlencode(current_url())); ?>"
                            class="btn btn-sm btn-light text-success d-flex align-items-center no-anim">
                            <i class="fas fa-file-import me-1"></i> Import
                        </a>
                    <?php endif; ?>

                    <?php if (!is_guest()): ?>
                        <a href="<?= base_url('rekomposisi/export_csv') ?>"
                            class="btn btn-sm btn-light text-secondary ms-2 d-flex align-items-center no-anim">
                            <i class="fas fa-file-csv me-1"></i> Download CSV
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body px-0 pt-0 pb-2 bg-white">

                <!-- CONTROLS -->
                <div class="px-3 mt-3 mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <label class="mb-0 me-2 text-sm">Tampilkan:</label>

                        <select id="perPageSelect"
                            class="form-select form-select-sm"
                            style="width: 90px; padding-right: 2rem;"
                            onchange="changePerPageGlobal(this.value)">
                            <option value="5" <?= ((int)$per_page === 5) ? 'selected' : ''; ?>>5</option>
                            <option value="10" <?= ((int)$per_page === 10) ? 'selected' : ''; ?>>10</option>
                            <option value="25" <?= ((int)$per_page === 25) ? 'selected' : ''; ?>>25</option>
                            <option value="50" <?= ((int)$per_page === 50) ? 'selected' : ''; ?>>50</option>
                            <option value="100" <?= ((int)$per_page === 100) ? 'selected' : ''; ?>>100</option>
                        </select>

                        <span class="ms-2 text-sm">dari <?= (int)$total_rows; ?> data</span>
                    </div>

                    <!-- SERVER-SIDE SEARCH -->
                    <div class="d-flex align-items-center gap-2">
                        <form method="get" action="<?= base_url('rekomposisi'); ?>" class="d-flex" id="searchFormRekomposisi" onsubmit="event.preventDefault(); searchSubmit('<?= base_url('rekomposisi'); ?>', 'searchInputRekomposisi', 'search');">
                            <input type="text"
                                id="searchInputRekomposisi"
                                name="search"
                                class="form-control form-control-sm rounded-3"
                                style="max-width: 320px;"
                                placeholder="Cari data..."
                                value="<?= htmlentities($search ?? '') ?>">
                            <button type="submit" class="btn btn-sm btn-primary ms-2">Cari</button>
                            <?php if (!empty($search)): ?>
                                <button type="button" class="btn btn-sm btn-outline-secondary ms-2" 
                                    onclick="window.location.replace('<?= base_url('rekomposisi?per_page=' . (int)($per_page ?? 5)); ?>')">
                                    Reset
                                </button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <!-- TABLE -->
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="rekomposisiTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis Anggaran</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nomor PRK</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nomor SKK IO</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">PRK</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pagu SKK IO</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Judul DRP</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php if (empty($rekomposisi)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-secondary py-4">
                                        Belum ada data / data tidak ditemukan.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php $no = (int)$start_no; ?>
                                <?php foreach ($rekomposisi as $row): ?>
                                    <tr>
                                        <td class="text-sm"><?= $no++; ?></td>
                                        <td class="text-sm"><?= htmlentities($row['jenis_anggaran']); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['nomor_prk']); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['nomor_skk_io']); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['uraian_prk']); ?></td>
                                        <td class="text-sm"><?= number_format((float)$row['pagu_skk_io'], 0, ',', '.'); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['judul_drp']); ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url('rekomposisi/detail/' . $row['id']); ?>"
                                                class="btn btn-info btn-xs text-white me-1" title="Detail">
                                                <i class="fas fa-info-circle"></i>
                                            </a>

                                            <?php if (function_exists('can_edit') && can_edit()): ?>
                                                <a href="<?= base_url('rekomposisi/edit/' . $row['id']); ?>"
                                                    class="btn btn-warning btn-xs text-white me-1" title="Edit">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (function_exists('can_delete') && can_delete()): ?>
                                                <a href="<?= base_url('rekomposisi/hapus/' . $row['id']); ?>"
                                                    class="btn btn-danger btn-xs btn-hapus" title="Hapus"
                                                    onclick="return confirm('Yakin hapus data ini?');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                <div class="card-footer d-flex justify-content-end">
                    <?= $pagination; ?>
                </div>

            </div>
        </div>
    </div>
</main>
