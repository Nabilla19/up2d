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
                <h6 class="mb-0 d-flex align-items-center">Tabel Rekap PRK</h6>
                <div class="d-flex align-items-center" style="padding-top: 16px;">
                    <?php if (can_create()): ?>
                        <a href="<?= base_url('rekap_prk/tambah') ?>" class="btn btn-sm btn-light text-primary me-2 d-flex align-items-center">
                            <i class="fas fa-plus me-1"></i> Tambah
                        </a>
                    <?php endif; ?>
                    <a href="<?= base_url('rekap_prk/export_csv') ?>" class="btn btn-sm btn-light text-secondary ms-2">
                        <i class="fas fa-file-csv me-1"></i> Download CSV
                    </a>
                </div>

            </div>

            <div class="card-body px-0 pt-0 pb-2 bg-white">

                <!-- CONTROLS -->
                <div class="px-3 mt-3 mb-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <label class="mb-0 me-2 text-sm">Tampilkan:</label>
                        <select id="perPageSelectPRK" class="form-select form-select-sm" style="width: 80px;" onchange="changePerPagePRK(this.value)">
                            <option value="5" <?= ($per_page == 5) ? 'selected' : ''; ?>>5</option>
                            <option value="10" <?= ($per_page == 10) ? 'selected' : ''; ?>>10</option>
                            <option value="25" <?= ($per_page == 25) ? 'selected' : ''; ?>>25</option>
                            <option value="50" <?= ($per_page == 50) ? 'selected' : ''; ?>>50</option>
                        </select>
                        <span class="ms-3 text-sm">dari <?= $total_rows ?? 0; ?> data</span>
                    </div>
                    <input type="text" id="searchInputPRK" onkeyup="searchTablePRK()" class="form-control form-control-sm rounded-3" style="max-width: 300px;" placeholder="Cari data...">
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
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pagu SKK-IO</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Renc Kontrak</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NODIN/SRT</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kontrak</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sisa</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rencana Bayar</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Terbayar</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ke Thn 2026</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($prk_data)): ?>
                                <tr>
                                    <td colspan="100" class="text-center text-secondary py-4">Belum ada data</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = $start_no ?? 1; ?>
                                <?php foreach ($prk_data as $row): ?>
                                    <tr class="<?= ($no % 2 == 0) ? 'table-row-even' : 'table-row-odd'; ?>">
                                        <td class="text-sm"><?= $no++; ?></td>
                                        <td class="text-sm"><?= htmlentities($row['JENIS_ANGGARAN']); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['NOMOR_PRK']); ?></td>
                                        <td class="text-sm"><?= htmlentities($row['URAIAN_PRK']); ?></td>
                                        <td class="text-sm"><?= number_format($row['PAGU_SKK_IO'], 2, ',', '.'); ?></td>
                                        <td class="text-sm"><?= number_format($row['RENC_KONTRAK'], 2, ',', '.'); ?></td>
                                        <td class="text-sm"><?= number_format($row['NODIN_SRT'], 0, ',', '.'); ?></td>
                                        <td class="text-sm"><?= number_format($row['KONTRAK'], 2, ',', '.'); ?></td>
                                        <td class="text-sm"><?= number_format($row['SISA'], 2, ',', '.'); ?></td>
                                        <td class="text-sm"><?= number_format($row['RENCANA_BAYAR'], 2, ',', '.'); ?></td>
                                        <td class="text-sm"><?= number_format($row['TERBAYAR'], 2, ',', '.'); ?></td>
                                        <td class="text-sm"><?= number_format($row['KE_TAHUN_2026'], 2, ',', '.'); ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url('rekap_prk/detail/' . $row['ID_PRK']) ?>" class="btn btn-info btn-xs text-white me-1" title="Detail"><i class="fas fa-info-circle"></i></a>
                                            <?php if (can_edit()): ?>
                                                <a href="<?= base_url('rekap_prk/edit/' . $row['ID_PRK']) ?>" class="btn btn-warning btn-xs text-white me-1" title="Edit"><i class="fas fa-pen"></i></a>
                                            <?php endif; ?>
                                            <?php if (can_delete()): ?>
                                                <a href="<?= base_url('rekap_prk/hapus/' . $row['ID_PRK']) ?>" class="btn btn-danger btn-xs btn-hapus" title="Hapus"><i class="fas fa-trash"></i></a>
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

<!-- SCRIPT -->
<script>
    function changePerPagePRK(perPage) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    }

    function searchTablePRK() {
        const input = document.getElementById('searchInputPRK');
        const filter = input.value.toUpperCase();
        const table = document.getElementById('prkTable');
        const tr = table.getElementsByTagName('tr');
        for (let i = 1; i < tr.length; i++) {
            let txtValue = tr[i].textContent || tr[i].innerText;
            tr[i].style.display = (txtValue.toUpperCase().indexOf(filter) > -1) ? '' : 'none';
        }
    }
</script>

<!-- STYLE -->
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

    /* Hapus animasi hover tabel */
    #prkTable tbody tr:hover {
        background-color: #e9ecef !important;
        transition: none !important;
    }

    /* Hapus semua animasi dan efek tombol */
    .btn,
    .btn-xs {
        transition: none !important;
        transform: none !important;
    }

    .btn:hover,
    .btn:focus,
    .btn:active,
    .btn:focus-visible,
    .btn-xs:hover,
    .btn-xs:focus,
    .btn-xs:active,
    .btn-xs:focus-visible {
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
    #prkTable tbody tr td {
        padding-top: 2px !important;
        padding-bottom: 2px !important;
        font-size: 13px !important;
    }

    #prkTable tbody td.text-center {
        vertical-align: middle !important;
        text-align: center !important;
        padding-top: 6px !important;
        padding-bottom: 6px !important;
    }

    #prkTable tbody td.text-center .btn {
        margin: 2px 3px;
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
</style>