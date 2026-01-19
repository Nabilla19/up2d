<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>

    <!-- Content -->
    <div class="container-fluid py-4">

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success text-white">
                <?= $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>

        <div class="card mb-4 shadow border-0 rounded-4">
            <div class="card-header py-2 d-flex justify-content-between align-items-center bg-gradient-primary text-white rounded-top-4">
                <h6 class="mb-0 d-flex align-items-center text-white"><i class="fas fa-code-branch me-2"></i>Tabel Data GH Penyulang</h6>
                <div class="d-flex align-items-center" style="padding-top: 16px;">
                    <?php if (can_create()): ?>
                        <a href="<?= base_url('Gh_cell/tambah') ?>" class="btn btn-sm btn-light text-primary me-2 d-flex align-items-center no-anim">
                            <i class="fas fa-plus me-1"></i> Tambah
                        </a>
                        <a href="<?= base_url('import/gh_cell?return_to=' . urlencode(current_url())); ?>" class="btn btn-sm btn-light text-success d-flex align-items-center no-anim">
                            <i class="fas fa-file-import me-1"></i> Import
                        </a>
                    <?php endif; ?>
                    <?php if (!is_guest()): ?>
                        <a href="<?= base_url('gh_cell/export_csv') ?>" class="btn btn-sm btn-light text-secondary ms-2 d-flex align-items-center no-anim">
                            <i class="fas fa-file-csv me-1"></i> Download CSV
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body px-0 pt-0 pb-2 bg-white">
                <div class="px-3 mt-3 mb-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <label class="mb-0 me-2 text-sm">Tampilkan:</label>
                        <select id="perPageSelectGH" class="form-select form-select-sm" style="width: 80px; padding-right: 2rem;" onchange="changePerPageGH(this.value)">
                            <option value="5" <?= ((int)($per_page ?? 10) == 5) ? 'selected' : ''; ?>>5</option>
                            <option value="10" <?= ((int)($per_page ?? 10) == 10) ? 'selected' : ''; ?>>10</option>
                            <option value="25" <?= ((int)($per_page ?? 10) == 25) ? 'selected' : ''; ?>>25</option>
                            <option value="50" <?= ((int)($per_page ?? 10) == 50) ? 'selected' : ''; ?>>50</option>
                            <option value="100" <?= ((int)($per_page ?? 10) == 100) ? 'selected' : ''; ?>>100</option>
                            <option value="500" <?= ((int)($per_page ?? 10) == 500) ? 'selected' : ''; ?>>500</option>
                        </select>
                        <span class="ms-3 text-sm">dari <?= (int)($total_rows ?? 0); ?> data</span>
                    </div>

                    <!-- ✅ SEARCH SERVER-SIDE (masuk ke controller via GET search=...) -->
                    <form method="get" action="<?= base_url('Gh_cell/index/1'); ?>" class="d-flex align-items-center">
                        <!-- keep per_page saat submit search -->
                        <input type="hidden" name="per_page" value="<?= (int)($per_page ?? 10); ?>">

                        <input type="text"
                               id="searchInputGH"
                               name="search"
                               value="<?= htmlentities($search ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                               class="form-control form-control-sm rounded-3"
                               style="max-width: 300px;"
                               placeholder="Cari data GH Cell...">

                        <button type="submit" class="btn btn-sm btn-primary ms-2">Cari</button>

                        <?php if (!empty($search)): ?>
                            <a href="<?= base_url('Gh_cell/index/1?per_page=' . (int)($per_page ?? 10)); ?>" class="btn btn-sm btn-secondary ms-2">Reset</a>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="ghCellTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">UNITNAME</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">SSOTNUMBER</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">DESCRIPTION</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NAMA_LOCATION</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($gh_cell)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-secondary py-4">
                                        <?php if (!empty($search)): ?>
                                            Tidak ada data yang cocok dengan pencarian: <b><?= htmlentities($search, ENT_QUOTES, 'UTF-8'); ?></b>
                                        <?php else: ?>
                                            Belum ada data
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php $no = (int)($start_no ?? 1);
                                foreach ($gh_cell as $row): ?>
                                    <tr class="<?= ($no % 2 == 0) ? 'table-row-even' : 'table-row-odd'; ?>">
                                        <td class="text-sm"><?= $no++; ?></td>
                                        <td class="text-sm"><?= htmlentities($row['UNITNAME'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['SSOTNUMBER'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['DESCRIPTION'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['NAMA_LOCATION'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url('Gh_cell/detail/' . urlencode($row['SSOTNUMBER'] ?? '')); ?>" class="btn btn-info btn-xs text-white me-1" title="Detail">
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                            <?php if (can_edit()): ?>
                                                <a href="<?= base_url('Gh_cell/edit/' . urlencode($row['SSOTNUMBER'] ?? '')); ?>" class="btn btn-warning btn-xs text-white me-1" title="Edit">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (can_delete()): ?>
                                                <a href="<?= base_url('Gh_cell/hapus/' . urlencode($row['SSOTNUMBER'] ?? '')); ?>" class="btn btn-danger btn-xs btn-hapus" title="Hapus">
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

                <div class="card-footer d-flex justify-content-end">
                    <?= $pagination; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Script -->
<script>
    function changePerPageGH(perPage) {
        // ✅ pertahankan search ketika ganti per_page
        const baseUrl = "<?= base_url('Gh_cell/index/1'); ?>";
        const url = new URL(baseUrl, window.location.origin);

        const current = new URL(window.location.href);
        const search = current.searchParams.get('search') || '';

        url.searchParams.set('per_page', perPage);
        if (search) {
            url.searchParams.set('search', search);
        }

        window.location.href = url.toString();
    }

    // ✅ searchTableGH tidak dipakai lagi (diganti server-side via form submit)
    // function searchTableGH() { ... }
</script>

<!-- Style -->
<style>
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        padding: 0.75rem 1rem;
    }

    .card-header h6 {
        color: #fff;
        margin: 0;
        font-weight: 600;
    }

    .breadcrumb .breadcrumb-item.active,
    .breadcrumb .breadcrumb-item a.opacity-5,
    .breadcrumb .breadcrumb-item.text-white {
        color: #ffffff !important;
    }

    .bg-gradient-primary {
        background: linear-gradient(90deg, #005C99, #0099CC);
    }

    .table-row-odd {
        background-color: #ffffff;
    }

    .table-row-even {
        background-color: #f5f7fa;
    }

    #ghCellTable tbody tr:hover {
        background-color: #e9ecef !important;
        transition: 0.2s ease-in-out;
    }

    .btn-xs {
        padding: 2px 6px;
        font-size: 11px;
        border-radius: 4px;
    }

    .btn-xs i {
        font-size: 12px;
    }

    /* padding sel tabel */
    #ghCellTable tbody tr td {
        padding-top: 2px !important;
        padding-bottom: 2px !important;
        font-size: 13px !important;
    }

    #ghCellTable tbody td.text-center {
        vertical-align: middle !important;
        text-align: center !important;
        padding-top: 6px !important;
        padding-bottom: 6px !important;
    }

    #ghCellTable tbody td.text-center .btn {
        margin: 2px 3px;
    }

    #ghCellTable thead th {
        padding-top: 8px !important;
        padding-bottom: 8px !important;
        font-size: 12px !important;
    }

    #ghCellTable tbody tr {
        line-height: 1.15;
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
    /* Hide common ripple elements if a JS plugin adds them */
    .no-anim .ripple,
    .no-anim .waves-ripple,
    .no-anim .wave,
    .no-anim .ink {
        display: none !important;
    }
</style>
