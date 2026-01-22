<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Helper lokal agar view kebal null & beda nama kolom (UPPERCASE/lowercase).
 * Pakai: v($row,'KEY',default)
 */
if (!function_exists('v')) {
    function v($row, $key, $default = '')
    {
        if (!is_array($row)) return $default;
        if (array_key_exists($key, $row) && $row[$key] !== null) return $row[$key];

        $alt = strtolower($key);
        if (array_key_exists($alt, $row) && $row[$alt] !== null) return $row[$alt];

        $alt2 = strtoupper($key);
        if (array_key_exists($alt2, $row) && $row[$alt2] !== null) return $row[$alt2];

        return $default;
    }
}

if (!function_exists('h')) {
    function h($val)
    {
        return htmlspecialchars((string)($val ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('nf')) {
    function nf($val, $dec = 0)
    {
        $n = (float)($val ?? 0);
        return number_format($n, $dec, ',', '.');
    }
}
?>

<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>

    <div class="container-fluid py-4">

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success text-white">
                <?= h($this->session->flashdata('success')); ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger text-white">
                <?= h($this->session->flashdata('error')); ?>
            </div>
        <?php endif; ?>

        <div class="card mb-4 shadow border-0 rounded-4">

            <!-- HEADER -->
            <div class="card-header py-2 d-flex justify-content-between align-items-center bg-gradient-primary text-white rounded-top-4">
                <h6 class="mb-0 d-flex align-items-center text-white"><i class="fas fa-list-check me-2"></i>Tabel Rekap PRK</h6>
                <div class="d-flex align-items-center" style="padding-top: 16px;">
                    <!-- <?php if (function_exists('can_create') && can_create()): ?>
                        <a href="<?= base_url('rekap_prk/tambah') ?>" class="btn btn-sm btn-light text-primary me-2 d-flex align-items-center no-anim">
                            <i class="fas fa-plus me-1"></i> Tambah
                        </a>
                    <?php endif; ?> -->
                    <a href="<?= base_url('rekap_prk/export_csv') ?>" class="btn btn-sm btn-light text-secondary ms-2 d-flex align-items-center no-anim">
                        <i class="fas fa-file-csv me-1"></i> Download CSV
                    </a>
                </div>
            </div>

            <div class="card-body px-0 pt-0 pb-2 bg-white">

                <!-- CONTROLS -->
                <div class="px-3 mt-3 mb-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <label class="mb-0 me-2 text-sm">Tampilkan:</label>
                        <select id="perPageSelectPRK" class="form-select form-select-sm" style="width: 90px;" onchange="changePerPageGlobal(this.value)">
                            <option value="5" <?= ((int)($per_page ?? 5) == 5)  ? 'selected' : ''; ?>>5</option>
                            <option value="10" <?= ((int)($per_page ?? 5) == 10) ? 'selected' : ''; ?>>10</option>
                            <option value="25" <?= ((int)($per_page ?? 5) == 25) ? 'selected' : ''; ?>>25</option>
                            <option value="50" <?= ((int)($per_page ?? 5) == 50) ? 'selected' : ''; ?>>50</option>
                            <option value="100" <?= ((int)($per_page ?? 5) == 100) ? 'selected' : ''; ?>>100</option>
                        </select>
                        <span class="ms-3 text-sm">dari <?= (int)($total_rows ?? 0); ?> data</span>
                    </div>

                    <form method="get" action="<?= site_url('rekap_prk'); ?>" class="d-flex" onsubmit="event.preventDefault(); searchSubmit('<?= site_url('rekap_prk'); ?>', 'searchInputPRK', 'search');">
                        <input type="text"
                            id="searchInputPRK"
                            name="search"
                            class="form-control form-control-sm rounded-3"
                            style="max-width: 300px;"
                            placeholder="Cari data..."
                            value="<?= htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        <button type="submit" class="btn btn-sm btn-primary ms-2">Cari</button>
                        <?php if (!empty($search)): ?>
                            <a href="<?= base_url('rekap_prk?per_page=' . (int)($per_page ?? 5)); ?>" class="btn btn-sm btn-outline-secondary ms-2">Reset</a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- TABLE -->
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="prkTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis Anggaran</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nomor PRK</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Uraian PRK</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Pagu SKK-IO</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Renc Kontrak</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">NODIN/SRT</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Kontrak</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Sisa</th>

                                <!-- Tambahan: agar tidak error walau key tidak ada -->
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Rencana Bayar</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Terbayar</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Ke Thn 2026</th>

                                <!-- kolom yang kamu error-kan -->
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Rencana Bayar Thn Ini</th>

                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (empty($prk_data)): ?>
                                <tr>
                                    <td colspan="50" class="text-center text-secondary py-4">Belum ada data</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = (int)($start_no ?? 1); ?>
                                <?php foreach ($prk_data as $row): ?>
                                    <tr class="<?= ($no % 2 == 0) ? 'table-row-even' : 'table-row-odd'; ?>">
                                        <td class="text-sm"><?= $no++; ?></td>

                                        <td class="text-sm"><?= h(v($row, 'JENIS_ANGGARAN', v($row, 'jenis_anggaran', ''))); ?></td>
                                        <td class="text-sm"><?= h(v($row, 'NOMOR_PRK', v($row, 'nomor_prk', ''))); ?></td>
                                        <td class="text-sm"><?= h(v($row, 'URAIAN_PRK', v($row, 'uraian_prk', ''))); ?></td>

                                        <td class="text-sm text-end"><?= nf(v($row, 'PAGU_SKK_IO', v($row, 'pagu_skk_io', 0)), 0); ?></td>
                                        <td class="text-sm text-end"><?= nf(v($row, 'RENC_KONTRAK', v($row, 'renc_kontrak', 0)), 0); ?></td>
                                        <td class="text-sm text-end"><?= nf(v($row, 'NODIN_SRT', v($row, 'nodin_srt', 0)), 0); ?></td>
                                        <td class="text-sm text-end"><?= nf(v($row, 'KONTRAK', v($row, 'kontrak', 0)), 0); ?></td>
                                        <td class="text-sm text-end"><?= nf(v($row, 'SISA', v($row, 'sisa', 0)), 0); ?></td>

                                        <td class="text-sm text-end"><?= nf(v($row, 'RENCANA_BAYAR', v($row, 'rencana_bayar', 0)), 0); ?></td>
                                        <td class="text-sm text-end"><?= nf(v($row, 'TERBAYAR', v($row, 'terbayar', 0)), 0); ?></td>
                                        <td class="text-sm text-end"><?= nf(v($row, 'KE_TAHUN_2026', v($row, 'ke_thn_2026', v($row, 'ke_tahun_2026', 0))), 0); ?></td>

                                        <!-- FIX: tidak lagi error -->
                                        <td class="text-sm text-end"><?= nf(v($row, 'rencana_bayar_thn_ini', 0), 0); ?></td>

                                        <td class="text-center">
                                            <?php
                                            $id = v($row, 'ID_PRK', v($row, 'id', ''));
                                            ?>
                                            <a href="<?= base_url('rekap_prk/detail/' . $id) ?>" class="btn btn-info btn-xs text-white me-1" title="Detail">
                                                <i class="fas fa-info-circle"></i>
                                            </a>

                                            <?php if (function_exists('can_edit') && can_edit()): ?>
                                                <a href="<?= base_url('rekap_prk/edit/' . $id) ?>" class="btn btn-warning btn-xs text-white me-1" title="Edit">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (function_exists('can_delete') && can_delete()): ?>
                                                <a href="<?= base_url('rekap_prk/hapus/' . $id) ?>" class="btn btn-danger btn-xs btn-hapus" title="Hapus"
                                                    onclick="return confirm('Yakin hapus data ini?')">
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
                    <?= $pagination ?? ''; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    function changePerPagePRK(perPage) {
        const base = "<?= site_url('rekap_prk/index/1'); ?>";
        changePerPageGlobal(base, perPage);
    }

    (function() {
        const input = document.getElementById('searchInputPRK');
        if (!input) return;
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchSubmit("<?= site_url('rekap_prk/index/1'); ?>", 'searchInputPRK', 'q');
            }
        });
    })();
</script>

<style>
    .bg-gradient-primary {
        background: linear-gradient(90deg, #005C99, #0099CC);
    }

    .table-row-odd {
        background-color: #ffffff;
    }

    .table-row-even {
        background-color: #f5f7fa;
    }

    #prkTable tbody tr:hover {
        background-color: #e9ecef !important;
        transition: none !important;
    }

    .btn,
    .btn-xs {
        transition: none !important;
        transform: none !important;
    }

    .btn:hover,
    .btn:focus,
    .btn:active,
    .btn-xs:hover,
    .btn-xs:focus,
    .btn-xs:active {
        transform: none !important;
        box-shadow: none !important;
    }

    .btn-xs {
        padding: 2px 6px;
        font-size: 11px;
        border-radius: 4px;
    }

    .btn-xs i {
        font-size: 12px;
    }

    #prkTable tbody tr td {
        padding-top: 2px !important;
        padding-bottom: 2px !important;
        font-size: 13px !important;
    }

    #prkTable thead th {
        padding-top: 8px !important;
        padding-bottom: 8px !important;
        font-size: 12px !important;
    }

    #prkTable tbody tr {
        line-height: 1.15;
    }

    .card-header h6 {
        color: #fff;
        margin: 0;
        font-weight: 600;
    }

    .no-anim,
    .no-anim * {
        transition: none !important;
        animation: none !important;
        transform: none !important;
        box-shadow: none !important;
        outline: none !important;
    }
</style>