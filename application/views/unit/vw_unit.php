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
                <h6 class="mb-0 d-flex align-items-center">Tabel Data Unit</h6>
                <div class="d-flex align-items-center" style="padding-top: 16px;">
                    <?php if (can_create()): ?>
                        <a href="<?= base_url('Unit/tambah') ?>" class="btn btn-sm btn-light text-primary me-2 d-flex align-items-center no-anim">
                            <i class="fas fa-plus me-1"></i> Tambah
                        </a>
                        <a href="<?= base_url('import/unit') ?>" class="btn btn-sm btn-light text-success d-flex align-items-center no-anim">
                            <i class="fas fa-file-import me-1"></i> Import
                        </a>
                    <?php endif; ?>
                    <a href="<?= base_url('Unit/export_csv') ?>" class="btn btn-sm btn-light text-secondary ms-2 d-flex align-items-center no-anim">
                        <i class="fas fa-file-csv me-1"></i> Download CSV
                    </a>
                </div>
            </div>

            <div class="card-body px-0 pt-0 pb-2 bg-white">
                <div class="px-3 mt-3 mb-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <label class="mb-0 me-2 text-sm">Tampilkan:</label>
                        <select id="perPageSelectUnit" class="form-select form-select-sm" style="width: 80px; padding-right: 2rem;" onchange="changePerPageUnit(this.value)">
                            <option value="5" <?= ($per_page == 5) ? 'selected' : ''; ?>>5</option>
                            <option value="10" <?= ($per_page == 10) ? 'selected' : ''; ?>>10</option>
                            <option value="25" <?= ($per_page == 25) ? 'selected' : ''; ?>>25</option>
                            <option value="50" <?= ($per_page == 50) ? 'selected' : ''; ?>>50</option>
                            <option value="100" <?= ($per_page == 100) ? 'selected' : ''; ?>>100</option>
                            <option value="500" <?= ($per_page == 500) ? 'selected' : ''; ?>>500</option>
                        </select>
                        <span class="ms-3 text-sm">dari <?= $total_rows ?? 0; ?> data</span>
                    </div>
                    <input type="text" id="searchInputUnit" class="form-control form-control-sm rounded-3" style="max-width: 300px;" placeholder="Cari data Unit..." value="<?= isset($q) ? html_escape($q) : '' ?>">
                </div>

                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="unitTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit Pelaksana</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit Layanan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Longitude (X)</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Latitude (Y)</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alamat</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($unit)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-secondary py-4">Belum ada data</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($unit as $i => $row): ?>
                                    <?php
                                        // Tentukan nomor tampil dari map posisi atau fallback
                                        $displayNo = (!empty($q) && !empty($positions) && isset($positions[$row['ID_UNIT']])) 
                                            ? $positions[$row['ID_UNIT']] 
                                            : ($start_no + $i);
                                    ?>
                                    <tr class="<?= ($displayNo % 2 == 0) ? 'table-row-even' : 'table-row-odd'; ?>">
                                        <td class="text-sm"><?= $displayNo; ?></td>
                                        <td class="text-sm"><?= htmlentities($row['UNIT_PELAKSANA']); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['UNIT_LAYANAN']); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['LONGITUDEX']); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['LATITUDEY']); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['ADDRESS']); ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url('Unit/detail/' . $row['ID_UNIT']); ?>" class="btn btn-info btn-xs text-white me-1" title="Detail"><i class="fas fa-info-circle"></i></a>
                                            <?php if (can_edit()): ?>
                                                <a href="<?= base_url('Unit/edit/' . $row['ID_UNIT']); ?>" class="btn btn-warning btn-xs text-white me-1" title="Edit"><i class="fas fa-pen"></i></a>
                                            <?php endif; ?>
                                            <?php if (can_delete()): ?>
                                                <a href="<?= base_url('Unit/hapus/' . $row['ID_UNIT']); ?>" class="btn btn-danger btn-xs btn-hapus" title="Hapus"><i class="fas fa-trash"></i></a>
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
    function changePerPageUnit(perPage) {
        const url = new URL(window.location.href);
        const q = document.getElementById('searchInputUnit').value.trim();
        url.searchParams.set('per_page', perPage);
        url.searchParams.set('page', '1');
        if (q) {
            url.searchParams.set('q', q);
        } else {
            url.searchParams.delete('q'); // hapus q jika kosong
        }
        window.location.href = url.toString();
    }

    (function(){
        const input = document.getElementById('searchInputUnit');
        if (!input) return;
        input.addEventListener('keydown', function(e){
            if (e.key === 'Enter') {
                e.preventDefault();
                const q = input.value.trim();
                const url = new URL(window.location.href);
                if (q) {
                    url.searchParams.set('q', q);
                    url.searchParams.set('page', '1');
                } else {
                    url.searchParams.delete('q'); // hapus q jika kosong
                }
                window.location.href = url.toString();
            }
        });
    })();
</script>

<!-- Style (sama persis seperti halaman Gardu Induk) -->
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

    #unitTable tbody tr:hover {
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

    #unitTable tbody tr td {
        padding-top: 2px !important;
        padding-bottom: 2px !important;
        font-size: 13px !important;
    }

    #unitTable tbody td.text-center {
        vertical-align: middle !important;
        text-align: center !important;
        padding-top: 6px !important;
        padding-bottom: 6px !important;
    }

    #unitTable tbody td.text-center .btn {
        margin: 2px 3px;
    }

    #unitTable thead th {
        padding-top: 8px !important;
        padding-bottom: 8px !important;
        font-size: 12px !important;
    }

    #unitTable tbody tr {
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