<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>

    <!-- Content -->
    <div class="container-fluid py-4">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success text-white">
                <?= $this->session->flashdata('success'); ?>
            </div>
        <?php elseif ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger text-white">
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <div class="card mb-4 shadow border-0 rounded-4">
            <div class="card-header py-2 d-flex justify-content-between align-items-center bg-gradient-primary text-white rounded-top-4">
                <h6 class="mb-0 d-flex align-items-center text-white"><i class="fas fa-exclamation-circle me-2"></i>Tabel Data Pengaduan</h6>
                <div class="d-flex align-items-center" style="padding-top: 16px;">
                    <?php if (can_create()): ?>
                        <a href="<?= base_url('Pengaduan/tambah') ?>" class="btn btn-sm btn-light text-primary me-2 d-flex align-items-center no-anim">
                            <i class="fas fa-plus me-1"></i> Tambah
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body px-0 pt-0 pb-2 bg-white">
                <div class="px-3 mt-3 mb-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <label class="mb-0 me-2 text-sm">Tampilkan:</label>
                        <select id="perPageSelectPengaduan" class="form-select form-select-sm" style="width: 80px; padding-right: 2rem;" onchange="changePerPagePengaduan(this.value)">
                            <option value="5" <?= ($per_page == 5) ? 'selected' : ''; ?>>5</option>
                            <option value="10" <?= ($per_page == 10) ? 'selected' : ''; ?>>10</option>
                            <option value="25" <?= ($per_page == 25) ? 'selected' : ''; ?>>25</option>
                            <option value="50" <?= ($per_page == 50) ? 'selected' : ''; ?>>50</option>
                            <option value="100" <?= ($per_page == 100) ? 'selected' : ''; ?>>100</option>
                        </select>
                        <span class="ms-3 text-sm">dari <?= $total_rows ?? 0; ?> data</span>
                    </div>

                    <form method="get" action="<?= site_url('pengaduan/index/1'); ?>" class="d-flex align-items-center" onsubmit="event.preventDefault(); searchSubmit('<?= site_url('pengaduan/index/1'); ?>', 'searchInputPengaduan', 'q');">
                        <input type="text" id="searchInputPengaduan" name="q" value="<?= htmlspecialchars((string)($q ?? ''), ENT_QUOTES, 'UTF-8'); ?>" class="form-control form-control-sm rounded-3" style="max-width: 300px;" placeholder="Cari data pengaduan...">
                        <button type="submit" class="btn btn-sm btn-primary ms-2">Cari</button>
                        <?php if (!empty($q)): ?>
                            <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="window.location.replace('<?= base_url('pengaduan/index/1'); ?>')">Reset</button>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="pengaduanTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama UP3</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Pengaduan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis Pengaduan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Item Pengaduan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">PIC</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Catatan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pengaduan)): ?>
                                <tr>
                                    <td colspan="9" class="text-center text-secondary py-4">Belum ada data pengaduan</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = $start_no;
                                foreach ($pengaduan as $row): ?>
                                    <tr class="<?= ($no % 2 == 0) ? 'table-row-even' : 'table-row-odd'; ?>">
                                        <td class="text-sm"><?= $no++; ?></td>
                                        <td class="text-sm"><?= htmlentities((string)($row['NAMA_UP3'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="text-sm"><?= htmlentities((string)($row['TANGGAL_PENGADUAN'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="text-sm"><?= htmlentities((string)($row['JENIS_PENGADUAN'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="text-sm"><?= htmlentities((string)($row['ITEM_PENGADUAN'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="text-sm">
                                            <span class="badge 
                                                <?= (($row['STATUS'] ?? '') == 'Selesai') ? 'bg-success' : ((($row['STATUS'] ?? '') == 'Diproses') ? 'bg-warning text-dark' : 'bg-secondary'); ?>">
                                                <?= ((($row['STATUS'] ?? '') == 'Menunggu') || (($row['STATUS'] ?? '') == 'Lapor')) ? 'Lapor' : htmlentities((string)($row['STATUS'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                        </td>
                                        <td class="text-sm"><?= htmlentities((string)($row['PIC'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="text-sm"><?= htmlentities((string)($row['CATATAN'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url('Pengaduan/detail/' . ($row['ID_PENGADUAN'] ?? '')); ?>" class="btn btn-info btn-xs text-white me-1" title="Detail"><i class="fas fa-info-circle"></i></a>
                                            <?php if (can_edit()): ?>
                                                <a href="<?= base_url('Pengaduan/edit/' . ($row['ID_PENGADUAN'] ?? '')); ?>" class="btn btn-warning btn-xs text-white me-1" title="Edit"><i class="fas fa-pen"></i></a>
                                            <?php endif; ?>
                                            <?php if (can_delete()): ?>
                                                <a href="javascript:void(0);" onclick="confirmDelete('<?= base_url('Pengaduan/hapus/' . ($row['ID_PENGADUAN'] ?? '')); ?>')" class="btn btn-danger btn-xs" title="Hapus"><i class="fas fa-trash"></i></a>
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
    function confirmDelete(url) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data pengaduan ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }

    function changePerPagePengaduan(perPage) {
        const base = "<?= site_url('pengaduan/index/1'); ?>";
        changePerPageGlobal(perPage, base);
    }

    (function() {
        const input = document.getElementById('searchInputPengaduan');
        if (!input) return;
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchSubmit("<?= site_url('pengaduan/index/1'); ?>", 'searchInputPengaduan', 'q');
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

    #pengaduanTable tbody tr:hover {
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
</style>
