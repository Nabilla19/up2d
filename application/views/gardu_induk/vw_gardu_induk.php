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
                <h6 class="mb-0 d-flex align-items-center">Tabel Data Gardu Induk</h6>
                <div class="d-flex align-items-center" style="padding-top: 16px;">
                    <?php if (can_create()): ?>
                        <a href="<?= base_url('Gardu_induk/tambah') ?>" class="btn btn-sm btn-light text-primary me-2 d-flex align-items-center no-anim">
                            <i class="fas fa-plus me-1"></i> Tambah
                        </a>
                        <a href="<?= base_url('import/gardu_induk') ?>" class="btn btn-sm btn-light text-success d-flex align-items-center no-anim">
                            <i class="fas fa-file-import me-1"></i> Import
                        </a>
                    <?php endif; ?>
                    <a href="<?= base_url('Gardu_induk/export_csv') ?>" class="btn btn-sm btn-light text-secondary ms-2 d-flex align-items-center no-anim">
                        <i class="fas fa-file-csv me-1"></i> Download CSV
                    </a>
                </div>
            </div>

            <div class="card-body px-0 pt-0 pb-2 bg-white">
                <div class="px-3 mt-3 mb-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <label class="mb-0 me-2 text-sm">Tampilkan:</label>
                        <select id="perPageSelectGI" class="form-select form-select-sm" style="width: 80px; padding-right: 2rem;" onchange="changePerPageGI(this.value)">
                            <option value="5" <?= ($per_page == 5) ? 'selected' : ''; ?>>5</option>
                            <option value="10" <?= ($per_page == 10) ? 'selected' : ''; ?>>10</option>
                            <option value="25" <?= ($per_page == 25) ? 'selected' : ''; ?>>25</option>
                            <option value="50" <?= ($per_page == 50) ? 'selected' : ''; ?>>50</option>
                            <option value="100" <?= ($per_page == 100) ? 'selected' : ''; ?>>100</option>
                            <option value="500" <?= ($per_page == 500) ? 'selected' : ''; ?>>500</option>
                        </select>
                        <span class="ms-3 text-sm">dari <?= $total_rows ?? 0; ?> data</span>
                    </div>

                    <!-- Search (server-side seperti Unit, Enter untuk submit) -->
                    <input type="text" id="searchInputGI" class="form-control form-control-sm rounded-3"
                        style="max-width: 300px;" placeholder="Cari data Gardu Induk..."
                        value="<?= isset($q) ? html_escape($q) : '' ?>">
                </div>

                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="giTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">UNITNAME_UP3</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">UNITNAME</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">SSOTNUMBER</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">DESCRIPTION</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">CITY</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($gardu_induk)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-secondary py-4">Belum ada data</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($gardu_induk as $i => $row): ?>
                                    <?php
                                    // Nomor tampil: posisi global saat search, fallback nomor pagination
                                    $displayNo = (!empty($q) && !empty($positions) && isset($positions[$row['SSOTNUMBER']]))
                                        ? $positions[$row['SSOTNUMBER']]
                                        : ($start_no + $i);
                                    ?>
                                    <tr class="<?= ($displayNo % 2 == 0) ? 'table-row-even' : 'table-row-odd'; ?>">
                                        <td class="text-sm"><?= $displayNo; ?></td>
                                        <td class="text-sm"><?= htmlentities($row['UNITNAME_UP3'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['UNITNAME'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['SSOTNUMBER'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['DESCRIPTION'] ?? ''); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['CITY'] ?? ''); ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url('Gardu_induk/detail/' . urlencode($row['SSOTNUMBER'] ?? '')); ?>" class="btn btn-info btn-xs text-white me-1" title="Detail">
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                            <?php if (can_edit()): ?>
                                                <a href="<?= base_url('Gardu_induk/edit/' . urlencode($row['SSOTNUMBER'] ?? '')); ?>" class="btn btn-warning btn-xs text-white me-1" title="Edit">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (can_delete()): ?>
                                                <a href="<?= base_url('Gardu_induk/hapus/' . urlencode($row['SSOTNUMBER'] ?? '')); ?>" class="btn btn-danger btn-xs btn-hapus" title="Hapus">
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
    // Per-page: reset ke halaman 1 via URI segment (bukan ?page=1)
    function changePerPageGI(perPage) {
        const url = new URL("<?= site_url('gardu_induk/index/1'); ?>");
        const q = document.getElementById('searchInputGI').value.trim();

        url.searchParams.set('per_page', perPage);

        if (q) url.searchParams.set('q', q);
        else url.searchParams.delete('q');

        window.location.href = url.toString();
    }

    // Search: Enter untuk submit (server-side)
    (function() {
        const input = document.getElementById('searchInputGI');
        if (!input) return;

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const q = input.value.trim();

                const url = new URL("<?= site_url('gardu_induk/index/1'); ?>");

                // pertahankan per_page yang aktif
                const current = new URL(window.location.href);
                const per = current.searchParams.get('per_page');
                if (per) url.searchParams.set('per_page', per);

                if (q) url.searchParams.set('q', q);
                else url.searchParams.delete('q');

                window.location.href = url.toString();
            }
        });
    })();
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

    #giTable tbody tr:hover {
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
    #giTable tbody tr td {
        padding-top: 2px !important;
        padding-bottom: 2px !important;
        font-size: 13px !important;
    }

    #giTable tbody td.text-center {
        vertical-align: middle !important;
        text-align: center !important;
        padding-top: 6px !important;
        padding-bottom: 6px !important;
    }

    #giTable tbody td.text-center .btn {
        margin: 2px 3px;
    }

    #giTable thead th {
        padding-top: 8px !important;
        padding-bottom: 8px !important;
        font-size: 12px !important;
    }

    #giTable tbody tr {
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