<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>

    <div class="container-fluid py-4">

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success text-white">
                <?= $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>

        <div class="card mb-4 shadow border-0 rounded-4">

            <!-- HEADER -->
            <div class="card-header py-2 d-flex justify-content-between align-items-center bg-gradient-primary text-white rounded-top-4">
                <h6 class="mb-0 d-flex align-items-center text-white"><i class="fas fa-file-signature me-2"></i>Tabel Data Input Kontrak</h6>
                <div class="d-flex align-items-center" style="padding-top: 16px;">
                    <?php 
                    // Debug
                    $CI =& get_instance();
                    $role = $CI->session->userdata('role');
                    $module = $CI->router->fetch_class();
                    echo "<!-- Role: $role | Module: $module | Can Create: " . (can_create() ? 'YES' : 'NO') . " -->";
                    ?>
                    <?php if (can_create()): ?>
                        <a href="<?= base_url('Input_kontrak/tambah') ?>" class="btn btn-sm btn-light text-primary me-2 d-flex align-items-center no-anim">
                            <i class="fas fa-plus me-1"></i> Tambah
                        </a>
                    <?php endif; ?>
                    <?php if (!is_guest()): ?>
                        <a href="<?= base_url('input_kontrak/export_csv') ?>" class="btn btn-sm btn-light text-secondary ms-2 d-flex align-items-center no-anim">
                            <i class="fas fa-file-csv me-1"></i> Download CSV
                        </a>
                    <?php endif; ?>

                </div>
            </div>

            <div class="card-body px-0 pt-0 pb-2 bg-white">

                <!-- CONTROLS -->
                <div class="px-3 mt-3 mb-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <label class="mb-0 me-2 text-sm">Tampilkan:</label>
                        <select id="perPageSelectKontrak" class="form-select form-select-sm" style="width: 80px; padding-right: 2rem;" onchange="changePerPageKontrak(this.value)">
                            <option value="5" <?= ($per_page == 5) ? 'selected' : ''; ?>>5</option>
                            <option value="10" <?= ($per_page == 10) ? 'selected' : ''; ?>>10</option>
                            <option value="25" <?= ($per_page == 25) ? 'selected' : ''; ?>>25</option>
                            <option value="50" <?= ($per_page == 50) ? 'selected' : ''; ?>>50</option>
                            <option value="100" <?= ($per_page == 100) ? 'selected' : ''; ?>>100</option>
                            <option value="500" <?= ($per_page == 500) ? 'selected' : ''; ?>>500</option>
                        </select>
                        <span class="ms-3 text-sm">dari <?= $total_rows ?? 0; ?> data</span>
                    </div>
                    <form method="get" action="<?= site_url('input_kontrak/index/1'); ?>" class="d-flex align-items-center" onsubmit="event.preventDefault(); searchSubmit('<?= site_url('input_kontrak/index/1'); ?>', 'searchInputKontrak', 'search');">
                        <input type="text" id="searchInputKontrak" name="search" value="<?= htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8'); ?>" class="form-control form-control-sm rounded-3" style="max-width: 300px;" placeholder="Cari data kontrak...">
                        <button type="submit" class="btn btn-sm btn-primary ms-2">Cari</button>
                        <?php if (!empty($search)): ?>
                            <a href="<?= base_url('input_kontrak/index/1?per_page=' . (int)($per_page ?? 5)); ?>" class="btn btn-sm btn-outline-secondary ms-2">Reset</a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- TABLE -->
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="kontrakTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">SUMBER DANA</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">SKKO</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">SUB POS</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">DRP</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">URAIAN KONTRAK / PEKERJAAN</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">USER</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">PAGU ANG/RAB USER</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">KOMITMENT ND</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">RENC AKHIR KONTRAK</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">TGL ND/AMS</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NOMOR ND / AMS</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">KETERANGAN</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">TAHAP KONTRAK</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">PROGNOSA</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NO SPK / SPB / KONTRAK</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">REKANAN</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">TGL KONTRAK</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">TGL AKHIR KONTRAK</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NILAI KONTRAK TOTAL</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NILAI KONTRAK TAHUN BERJALAN</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">TGL BAYAR</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ANGGARAN TERPAKAI</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">SISA ANGGARAN</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">STATUS KONTRAK</th>
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">BLN KTRK<?= $i ?></th>
                                <?php endfor; ?>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">BULAN RENC BAYAR</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">BULAN BAYAR</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($input_kontrak)): ?>
                                <tr>
                                    <td colspan="100" class="text-center text-secondary py-4">Belum ada data</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = $start_no;
                                foreach ($input_kontrak as $row): ?>
                                    <tr class="<?= ($no % 2 == 0) ? 'table-row-even' : 'table-row-odd'; ?>">
                                        <td class="text-sm"><?= $no++; ?></td>
                                        <td class="text-sm"><?= htmlentities($row['SUMBER DANA'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['SKKO'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['SUB POS'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['DRP'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['URAIAN KONTRAK / PEKERJAAN'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['USER'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['PAGU ANG/RAB USER'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['KOMITMENT ND'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['RENC AKHIR KONTRAK'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['TGL ND/AMS'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['NOMOR ND / AMS'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['KETERANGAN'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['TAHAP KONTRAK'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['PROGNOSA'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['NO SPK / SPB / KONTRAK'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['REKANAN'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['TGL KONTRAK'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['TGL AKHIR KONTRAK'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['NILAI KONTRAK TOTAL'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['NILAI KONTRAK TAHUN BERJALAN'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['TGL BAYAR'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['ANGGARAN TERPAKAI'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['SISA ANGGARAN'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['STATUS KONTRAK'] ?? ''); ?></td>
                                        <?php for ($i = 1; $i <= 12; $i++): ?>
                                            <td class="text-sm"><?= htmlentities($row["BLN KTRK$i"] ?? ''); ?></td>
                                        <?php endfor; ?>
                                        <td class="text-sm"><?= htmlentities($row['BULAN RENC BAYAR'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['BULAN BAYAR'] ?? ''); ?></td>
                                        <td class="text-center">
                                            <!-- Tombol Detail -->
                                            <a href="<?= base_url('Input_kontrak/detail/' . $row['ID']) ?>" class="btn btn-info btn-xs text-white me-1" title="Detail">
                                                <i class="fas fa-info-circle"></i>
                                            </a>

                                            <!-- Tombol Edit -->
                                            <?php if (can_edit()): ?>
                                                <a href="<?= base_url('Input_kontrak/edit/' . $row['ID']) ?>" class="btn btn-warning btn-xs text-white me-1" title="Edit">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                            <?php endif; ?>

                                            <!-- Tombol Hapus -->
                                            <?php if (can_delete()): ?>
                                                <a href="<?= base_url('Input_kontrak/hapus/' . $row['ID']) ?>" class="btn btn-danger btn-xs btn-hapus" title="Hapus">
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

<!-- Script -->
<script>
    function changePerPageKontrak(perPage) {
        const base = "<?= site_url('input_kontrak/index/1'); ?>";
        changePerPageGlobal(base, perPage);
    }
</script>

<!-- Style -->
<style>
    /* Header gradient */
    .bg-gradient-primary {
        background: linear-gradient(90deg, #005C99, #0099CC);
    }

    /* Table row warna */
    .table-row-odd {
        background-color: #ffffff;
    }

    .table-row-even {
        background-color: #f5f7fa;
    }

    /* Hover tabel tanpa animasi */
    #kontrakTable tbody tr:hover {
        background-color: #e9ecef !important;
        transition: none !important;
        /* hapus animasi */
    }

    /* Semua tombol tanpa animasi */
    .btn,
    .btn-xs,
    .btn-sm {
        transition: none !important;
        /* hapus animasi */
        transform: none !important;
        /* hapus efek membesar */
        box-shadow: none !important;
        /* hapus shadow saat klik/fokus */
    }

    /* Hapus efek hover/fokus pada tombol */
    .btn:hover,
    .btn:focus,
    .btn:active,
    .btn:focus-visible,
    .btn-xs:hover,
    .btn-xs:focus,
    .btn-xs:active,
    .btn-xs:focus-visible,
    .btn-sm:hover,
    .btn-sm:focus,
    .btn-sm:active,
    .btn-sm:focus-visible {
        transform: none !important;
        box-shadow: none !important;
    }

    /* Tombol kecil */
    .btn-xs {
        padding: 2px 6px;
        font-size: 11px;
        border-radius: 4px;
    }

    .btn-xs i {
        font-size: 12px;
    }

    /* Padding dan font table */
    #kontrakTable tbody tr td {
        padding-top: 2px !important;
        padding-bottom: 2px !important;
        font-size: 13px !important;
    }

    #kontrakTable tbody td.text-center {
        vertical-align: middle !important;
        text-align: center !important;
        padding-top: 6px !important;
        padding-bottom: 6px !important;
    }

    #kontrakTable tbody td.text-center .btn {
        margin: 2px 3px;
    }

    #kontrakTable thead th {
        padding-top: 8px !important;
        padding-bottom: 8px !important;
        font-size: 12px !important;
    }

    #kontrakTable tbody tr {
        line-height: 1.15;
    }

    .card-header h6 {
        color: #fff;
        margin: 0;
        font-weight: 600;
    }

    /* Disable click/hover animations for elements with .no-anim */
    .no-anim,
    .no-anim * {
        transition: none !important;
        -webkit-transition: none !important;
        -moz-transition: none !important;
        -o-transition: none !important;
        animation: none !important;
        -webkit-animation: none !important;
        transform: none !important;
        -webkit-transform: none !important;
        box-shadow: none !important;
        outline: none !important;
    }
    .no-anim:active,
    .no-anim:focus,
    .no-anim *:active,
    .no-anim *:focus {
        transform: none !important;
        -webkit-transform: none !important;
        box-shadow: none !important;
        outline: none !important;
    }
    .no-anim .ripple,
    .no-anim .waves-ripple,
    .no-anim .wave,
    .no-anim .ink {
        display: none !important;
    }
</style>