<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>

    <!-- Content -->
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
                <h6 class="mb-0 d-flex align-items-center text-white"><i class="fas fa-newspaper me-2"></i>Tabel Data SPLN</h6>
                <div class="d-flex align-items-center" style="padding-top: 16px;">
                    <?php if (can_create()): ?>
                        <a href="<?= base_url('Spln/tambah') ?>" class="btn btn-sm btn-light text-primary me-2 d-flex align-items-center no-anim">
                            <i class="fas fa-plus me-1"></i> Tambah
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body px-0 pt-0 pb-2 bg-white">
                <div class="px-3 mt-3 mb-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <label class="mb-0 me-2 text-sm">Tampilkan:</label>
                        <select id="perPageSelectSpln" class="form-select form-select-sm" style="width: 80px; padding-right: 2rem;" onchange="changePerPageSpln(this.value)">
                            <option value="5" <?= ($per_page == 5) ? 'selected' : ''; ?>>5</option>
                            <option value="10" <?= ($per_page == 10) ? 'selected' : ''; ?>>10</option>
                            <option value="25" <?= ($per_page == 25) ? 'selected' : ''; ?>>25</option>
                            <option value="50" <?= ($per_page == 50) ? 'selected' : ''; ?>>50</option>
                            <option value="100" <?= ($per_page == 100) ? 'selected' : ''; ?>>100</option>
                            <option value="500" <?= ($per_page == 500) ? 'selected' : ''; ?>>500</option>
                        </select>
                        <span class="ms-3 text-sm">dari <?= $total_rows ?? 0; ?> data</span>
                    </div>
                    <input type="text" id="searchInputSpln" onkeyup="searchTableSpln()" class="form-control form-control-sm rounded-3" style="max-width: 300px;" placeholder="Cari SPLN...">
                </div>

                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="splnTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Dokumen</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created By</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama File</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($spln)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-secondary py-4">Belum ada data SPLN</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = isset($start_no) ? $start_no : 1;
                                foreach ($spln as $row): ?>
                                    <tr class="<?= ($no % 2 == 0) ? 'table-row-even' : 'table-row-odd'; ?>">
                                        <td class="text-sm"><?= $no++; ?></td>
                                        <td class="text-sm"><?= htmlentities($row['NAMA_FILE'] ?? '-'); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['CREATED_BY'] ?? '-'); ?></td>
                                        <td class="text-sm">
                                            <?php if (!empty($row['FILE_SPLN'])): ?>
                                                <span class="badge bg-gradient-info text-white p-2"><?= htmlentities($row['FILE_SPLN']); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted fst-italic">Tidak ada file</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if (!empty($row['FILE_SPLN'])): ?>
                                                <a href="<?= base_url('uploads/spln/' . $row['FILE_SPLN']); ?>" target="_blank" class="btn btn-info btn-xs text-white me-1" title="Lihat File">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= base_url('uploads/spln/' . $row['FILE_SPLN']); ?>" download class="btn btn-success btn-xs text-white me-1" title="Unduh File">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (can_edit()): ?>
                                                <a href="<?= base_url('Spln/edit/' . ($row['ID_SPLN'] ?? '')); ?>" class="btn btn-warning btn-xs text-white me-1" title="Edit">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (can_delete()): ?>
                                                <a href="javascript:void(0);" onclick="confirmDelete('<?= base_url('Spln/hapus/' . ($row['ID_SPLN'] ?? '')); ?>')" class="btn btn-danger btn-xs" title="Hapus">
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

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(url) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "File SPLN ini akan dihapus secara permanen!",
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

    function changePerPageSpln(perPage) {
        const base = "<?= site_url('spln/index/1'); ?>";
        changePerPageGlobal(base, perPage);
    }

    (function() {
        const input = document.getElementById('searchInputSpln');
        if (!input) return;
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchSubmit("<?= site_url('spln/index/1'); ?>", 'searchInputSpln', 'search');
            }
        });
    })();
</script>