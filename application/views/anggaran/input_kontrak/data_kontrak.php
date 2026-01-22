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
                <h6 class="mb-0 d-flex align-items-center text-white"><i class="fas fa-file-contract me-2"></i>Tabel Data Kontrak</h6>
                <div class="d-flex align-items-center" style="padding-top: 16px;">
                    <a href="<?= base_url('data_kontrak/export_csv?search=' . urlencode($search ?? '')); ?>" class="btn btn-sm btn-light text-primary me-2 d-flex align-items-center no-anim">
                        <i class="fas fa-file-csv me-1"></i> Export CSV
                    </a>
                </div>
            </div>

            <div class="card-body px-0 pt-0 pb-2 bg-white">

                <div class="px-3 mt-3 mb-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <label class="mb-0 me-2 text-sm">Tampilkan:</label>
                        <select id="perPageSelectKontrak" class="form-select form-select-sm" style="width: 90px; padding-right: 2rem;" onchange="changePerPageGlobal(this.value)">
                            <?php foreach ([5, 10, 25, 50, 100, 500] as $n): ?>
                                <option value="<?= $n ?>" <?= ($per_page == $n) ? 'selected' : ''; ?>><?= $n ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="ms-3 text-sm">dari <?= (int)($total_rows ?? 0); ?> data</span>
                    </div>

                    <form method="get" action="<?= base_url('data_kontrak/index'); ?>" class="d-flex align-items-center" onsubmit="event.preventDefault(); searchSubmit('<?= base_url('data_kontrak/index'); ?>', 'searchInputKontrak', 'search');">
                        <input type="text" id="searchInputKontrak" name="search" value="<?= html_escape($search ?? ''); ?>" class="form-control form-control-sm rounded-3" style="max-width: 300px;" placeholder="Cari data kontrak...">
                        <button type="submit" class="btn btn-sm btn-primary ms-2">Cari</button>
                        <?php if (!empty($search)): ?>
                            <a href="<?= base_url('data_kontrak/index/1?per_page=' . (int)($per_page ?? 5)); ?>" class="btn btn-sm btn-outline-secondary ms-2">Reset</a>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="kontrakTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis Anggaran</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nomor PRK</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Judul DRP</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No Kontrak</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pelaksana</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tgl Kontrak</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nilai Kontrak</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($kontrak)): ?>
                                <tr>
                                    <td colspan="10" class="text-center text-secondary py-4">Belum ada data</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($kontrak as $i => $row): ?>
                                    <?php
                                    $jenis = $row->jenis_anggaran_nama ?? ($row->jenis_anggaran_text ?? '-');
                                    $prk   = !empty($row->nomor_prk_text) ? $row->nomor_prk_text : ($row->nomor_prk ?? '-');
                                    $drp   = !empty($row->drp_text) ? $row->drp_text : ($row->judul_drp ?? '-');
                                    ?>
                                    <tr class="<?= (($start_no + $i) % 2 == 0) ? 'table-row-even' : 'table-row-odd'; ?>">
                                        <td class="text-sm"><?= $start_no + $i; ?></td>
                                        <td class="text-sm"><?= html_escape($jenis); ?></td>
                                        <td class="text-sm"><?= html_escape($prk); ?></td>
                                        <td class="text-sm"><?= html_escape($drp); ?></td>
                                        <td class="text-sm"><?= html_escape($row->no_kontrak); ?></td>
                                        <td class="text-sm"><?= html_escape($row->pelaksana_vendor); ?></td>
                                        <td class="text-sm"><?= fmt_date($row->tgl_kontrak); ?></td>
                                        <td class="text-sm"><?= rupiah($row->nilai_kontrak); ?></td>
                                        <td class="text-sm"><?= html_escape($row->status_kontrak); ?></td>

                                        <td class="text-center">
                                            <a href="<?= base_url('data_kontrak/detail/' . $row->id); ?>"
                                                class="btn btn-info btn-xs text-white me-1">
                                                <i class="fas fa-info-circle"></i>
                                            </a>

                                            <?php if ($can_edit_local): ?>
                                                <a href="<?= base_url('data_kontrak/edit/' . $row->id); ?>"
                                                    class="btn btn-warning btn-xs text-white me-1">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($can_delete_local): ?>
                                                <a href="<?= base_url('data_kontrak/hapus/' . $row->id); ?>"
                                                    class="btn btn-danger btn-xs btn-hapus">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confirmation for delete
        document.querySelectorAll('.btn-hapus').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data kontrak ini akan dihapus secara permanen!",
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
            });
        });
    });
</script>

<style>
    .bg-gradient-primary {
        background: linear-gradient(87deg, #005C99 0, #0099CC 100%) !important;
    }

    .table-row-odd {
        background-color: #ffffff;
    }

    .table-row-even {
        background-color: #f8f9fa;
    }

    #kontrakTable tbody tr:hover {
        background-color: #f2f2f2 !important;
        transition: 0.2s;
    }

    .btn-xs {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    .no-anim {
        transition: none !important;
    }
</style>