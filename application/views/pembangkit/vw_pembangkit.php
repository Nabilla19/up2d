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
            <div class="card-header py-2 d-flex justify-content-between align-items-center bg-gradient-primary text-white rounded-top-4">
                <h6 class="mb-0 d-flex align-items-center text-white"><i class="fas fa-industry me-2"></i>Tabel Data Pembangkit</h6>
                <div class="d-flex align-items-center" style="padding-top: 16px;">
                    <?php if (can_create()): ?>
                        <a href="<?= base_url('Pembangkit/tambah') ?>" class="btn btn-sm btn-light text-primary me-2 d-flex align-items-center no-anim">
                            <i class="fas fa-plus me-1"></i> Tambah
                        </a>
                        <a href="<?= base_url('import/pembangkit?return_to=' . urlencode(current_url())); ?>" class="btn btn-sm btn-light text-success d-flex align-items-center no-anim">
                            <i class="fas fa-file-import me-1"></i> Import
                        </a>
                    <?php endif; ?>
                    <?php if (!is_guest()): ?>
                        <a href="<?= base_url('pembangkit/export_csv') ?>" class="btn btn-sm btn-light text-secondary ms-2 d-flex align-items-center no-anim">
                            <i class="fas fa-file-csv me-1"></i> Download CSV
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body px-0 pt-0 pb-2 bg-white">
                <div class="px-3 mt-3 mb-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <label class="mb-0 me-2 text-sm">Tampilkan:</label>

                        <select id="perPageSelect" class="form-select form-select-sm" style="width: 80px; padding-right: 2rem;" onchange="changePerPage(this.value)">
                            <option value="5" <?= ((int)($per_page ?? 10) == 5) ? 'selected' : ''; ?>>5</option>
                            <option value="10" <?= ((int)($per_page ?? 10) == 10) ? 'selected' : ''; ?>>10</option>
                            <option value="25" <?= ((int)($per_page ?? 10) == 25) ? 'selected' : ''; ?>>25</option>
                            <option value="50" <?= ((int)($per_page ?? 10) == 50) ? 'selected' : ''; ?>>50</option>
                            <option value="100" <?= ((int)($per_page ?? 10) == 100) ? 'selected' : ''; ?>>100</option>
                            <option value="500" <?= ((int)($per_page ?? 10) == 500) ? 'selected' : ''; ?>>500</option>
                        </select>

                        <span class="ms-3 text-sm">dari <?= (int)($total_rows ?? 0); ?> data</span>
                    </div>

                    <!-- ✅ Search server-side (uses shared helper) -->
                    <form method="get" action="<?= site_url('pembangkit/index'); ?>" class="d-flex align-items-center" onsubmit="event.preventDefault(); searchSubmit('<?= site_url('pembangkit/index'); ?>', 'searchInputPembangkit', 'search');">
                        <input type="hidden" name="per_page" value="<?= (int)($per_page ?? 10); ?>">
                        <input type="text"
                               id="searchInputPembangkit"
                               name="search"
                               value="<?= htmlentities($search ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                               class="form-control form-control-sm rounded-3"
                               style="max-width: 300px;"
                               placeholder="Cari data pembangkit...">
                        <button type="submit" class="btn btn-sm btn-primary ms-2">Cari</button>

                        <?php if (!empty($search)): ?>
                            <a href="<?= site_url('pembangkit/index/1?per_page=' . (int)($per_page ?? 10)); ?>" class="btn btn-sm btn-secondary ms-2">Reset</a>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="pembangkitTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit Layanan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pembangkit</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status SCADA</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Merk RTU</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pembangkit)): ?>
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
                                <?php $no = (int)($start_no ?? 1); ?>
                                <?php foreach ($pembangkit as $row): ?>
                                    <tr class="<?= ($no % 2 == 0) ? 'table-row-even' : 'table-row-odd'; ?>">
                                        <td class="text-sm"><?= $no++; ?></td>
                                        <td class="text-sm"><?= htmlentities($row['UNIT_LAYANAN'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['PEMBANGKIT'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['STATUS_SCADA'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['MERK_RTU'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url('Pembangkit/detail/' . (int)($row['ID_PEMBANGKIT'] ?? 0)); ?>" class="btn btn-info btn-xs text-white me-1" title="Detail">
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                            <?php if (can_edit()): ?>
                                                <a href="<?= base_url('Pembangkit/edit/' . (int)($row['ID_PEMBANGKIT'] ?? 0)); ?>" class="btn btn-warning btn-xs text-white me-1" title="Edit">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (can_delete()): ?>
                                                <a href="<?= base_url('Pembangkit/hapus/' . (int)($row['ID_PEMBANGKIT'] ?? 0)); ?>" class="btn btn-danger btn-xs btn-hapus" title="Hapus">
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
                    <?= $pagination ?? ''; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    function changePerPage(perPage) {
        const base = "<?= site_url('pembangkit/index'); ?>";
        const url = new URL(base, window.location.origin);
        url.searchParams.set('per_page', perPage);
        
        const input = document.getElementById('searchInputPembangkit');
        if (input) {
            const search = input.value.trim();
            if (search) url.searchParams.set('search', search);
        }
        window.location.href = url.toString();
    }
</script>

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

    #pembangkitTable tbody tr:hover {
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

    #pembangkitTable tbody tr td {
        padding-top: 2px !important;
        padding-bottom: 2px !important;
        font-size: 13px !important;
    }

    #pembangkitTable tbody td.text-center {
        vertical-align: middle !important;
        text-align: center !important;
        padding-top: 6px !important;
        padding-bottom: 6px !important;
    }

    #pembangkitTable tbody td.text-center .btn {
        margin: 2px 3px;
    }

    #pembangkitTable thead th {
        padding-top: 8px !important;
        padding-bottom: 8px !important;
        font-size: 12px !important;
    }

    #pembangkitTable tbody tr {
        line-height: 1.15;
    }

    .no-anim,
    .no-anim * { transition: none !important; animation: none !important; transform: none !important; box-shadow: none !important; outline: none !important; }
    .no-anim:active, .no-anim:focus, .no-anim *:active, .no-anim *:focus { transform: none !important; box-shadow: none !important; outline: none !important; }
    .no-anim .ripple, .no-anim .waves-ripple, .no-anim .wave, .no-anim .ink { display: none !important; }
</style>
