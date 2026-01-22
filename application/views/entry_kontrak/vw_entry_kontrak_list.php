<?php
defined('BASEPATH') or exit('No direct script access allowed');

function e($v)
{
    return htmlentities((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
}
?>

<main class="main-content position-relative border-radius-lg">
    <?php $this->load->view('layout/navbar'); ?>

    <div class="container-fluid py-4">

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success text-white"><?= e($this->session->flashdata('success')); ?></div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger text-white"><?= $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <div class="card mb-4 shadow border-0 rounded-4">
            <div class="card-header py-2 d-flex justify-content-between align-items-center bg-gradient-primary text-white rounded-top-4">
                <h6 class="mb-0 text-white"><i class="fas fa-file-signature me-2"></i>Tabel Entry Kontrak</h6>

                <div class="d-flex align-items-center" style="padding-top: 16px;">

                    <?php
                    // EXPORT selalu tampil untuk semua role
                    $qs = $_GET ?? [];
                    $qs_str = !empty($qs) ? ('?' . http_build_query($qs)) : '';
                    ?>
                    <div class="btn-group me-2">
                        <button type="button"
                            class="btn btn-sm btn-light text-primary dropdown-toggle no-anim"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fas fa-file-export me-1"></i> Export
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if (!is_guest()): ?>
                                <li>
                                    <a class="dropdown-item" href="<?= base_url('entry_kontrak/export_csv') . $qs_str; ?>">
                                        <i class="fas fa-file-csv me-2"></i> Export to CSV
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= base_url('entry_kontrak/export_gsheet') . $qs_str; ?>">
                                        <i class="fas fa-table me-2"></i> Export to Google Spreadsheet
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <?php if (!empty($can_create)): ?>
                        <a href="<?= base_url('entry_kontrak/tambah'); ?>"
                            class="btn btn-sm btn-light text-primary me-2 d-flex align-items-center no-anim">
                            <i class="fas fa-plus me-1"></i> Tambah
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body px-0 pt-0 pb-2 bg-white">
                <div class="px-3 mt-3 mb-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <label class="mb-0 me-2 text-sm">Tampilkan:</label>
                        <select class="form-select form-select-sm" style="width:80px; padding-right:2rem;" onchange="changePerPageGlobal(this.value)">
                            <?php foreach ([5, 10, 25, 50, 100] as $n): ?>
                                <option value="<?= $n ?>" <?= ((int)$per_page === $n) ? 'selected' : '' ?>><?= $n ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="ms-3 text-sm">dari <?= (int)$total_rows; ?> data</span>
                    </div>

                    <form method="get" action="<?= base_url('entry_kontrak'); ?>" class="d-flex" id="searchFormEntryKontrak" onsubmit="event.preventDefault(); searchSubmit('<?= base_url('entry_kontrak'); ?>', 'searchInputEntryKontrak', 'search');">
                        <input type="text" id="searchInputEntryKontrak" name="search" class="form-control form-control-sm rounded-3"
                            style="max-width:300px;" placeholder="Cari entry kontrak..."
                            value="<?= e($search ?? ''); ?>">
                        <button type="submit" class="btn btn-sm btn-primary ms-2">Cari</button>
                        <?php if (!empty($search)): ?>
                            <button type="button" class="btn btn-sm btn-outline-secondary ms-2" 
                                onclick="window.location.replace('<?= base_url('entry_kontrak?per_page=' . (int)($per_page ?? 10)); ?>')">
                                Reset
                            </button>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="kontrakTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pengusul</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nomor PRK</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">PRK</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Judul DRP</th>

                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Metode</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tahapan Pengadaan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Prognosa</th>

                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">RAB User</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nilai Kontrak</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vendor</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No Kontrak</th>

                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (empty($rows)): ?>
                                <tr>
                                    <td colspan="15" class="text-center text-secondary py-4">Belum ada data</td>
                                </tr>
                            <?php else: ?>
                                <?php
                                $no = (int)$start_no;
                                foreach ($rows as $row):
                                    $id = (int)($row['id'] ?? 0);

                                    // ✅ Ambil izin edit dari controller (termasuk KKU sesuai rule kamu)
                                    $allow_edit = (!empty($can_edit_row) && $id > 0 && !empty($can_edit_row[$id]));
                                ?>
                                    <tr>
                                        <td class="text-sm"><?= $no++; ?></td>
                                        <td class="text-sm"><?= e($row['user_pengusul'] ?? ''); ?></td>
                                        <td class="text-sm"><?= e($row['jenis_anggaran'] ?? ''); ?></td>
                                        <td class="text-sm"><?= e($row['nomor_prk'] ?? ''); ?></td>
                                        <td class="text-sm"><?= e($row['uraian_prk'] ?? ''); ?></td>
                                        <td class="text-sm"><?= e($row['judul_drp'] ?? ''); ?></td>

                                        <td class="text-sm"><?= e($row['metode_pengadaan'] ?? ''); ?></td>
                                        <td class="text-sm"><?= e($row['tahapan_pengadaan'] ?? ''); ?></td>
                                        <td class="text-sm"><?= e($row['prognosa_kontrak'] ?? ''); ?></td>

                                        <td class="text-sm"><?= e($row['status_kontrak'] ?? ''); ?></td>
                                        <td class="text-sm"><?= number_format((float)($row['rab_user'] ?? 0), 0, ',', '.'); ?></td>
                                        <td class="text-sm"><?= number_format((float)($row['nilai_kontrak'] ?? 0), 0, ',', '.'); ?></td>
                                        <td class="text-sm"><?= e($row['vendor'] ?? ''); ?></td>
                                        <td class="text-sm"><?= e($row['no_kontrak'] ?? ''); ?></td>

                                        <td class="text-center">
                                            <a href="<?= base_url('entry_kontrak/detail/' . $id); ?>"
                                                class="btn btn-info btn-xs text-white me-1" title="Detail">
                                                <i class="fas fa-info-circle"></i>
                                            </a>

                                            <?php if (!empty($allow_edit)): ?>
                                                <a href="<?= base_url('entry_kontrak/edit/' . $id); ?>"
                                                    class="btn btn-warning btn-xs text-white me-1" title="Edit">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (!empty($is_admin)): ?>
                                                <a href="<?= base_url('entry_kontrak/hapus/' . $id); ?>"
                                                    class="btn btn-danger btn-xs text-white" title="Hapus"
                                                    onclick="return confirm('Yakin hapus data ini?');">
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

                <div class="card-footer d-flex justify-content-end"><?= $pagination; ?></div>
            </div>
        </div>
    </div>
</main>

<script>
    function changePerPage(perPage) {
        const base = "<?= site_url('entry_kontrak'); ?>";
        const url = new URL(base, window.location.origin);
        url.searchParams.set('per_page', perPage);
        url.searchParams.set('page', 0);
        window.location.href = url.toString();
    }

    (function(){
        const input = document.getElementById('searchInput');
        if (!input) return;
        input.addEventListener('keydown', function(e){
            if (e.key === 'Enter') { e.preventDefault();
                const base = "<?= site_url('entry_kontrak'); ?>";
                const url = new URL(base, window.location.origin);
                url.searchParams.set('keyword', (input.value||'').trim());
                url.searchParams.set('page', 0);
                const per = (new URL(window.location.href)).searchParams.get('per_page');
                if (per) url.searchParams.set('per_page', per);
                window.location.href = url.toString();
            }
        });
    })();

    function searchTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toUpperCase();
        const table = document.getElementById('kontrakTable');
        const tr = table.getElementsByTagName('tr');
        for (let i = 1; i < tr.length; i++) {
            let txtValue = tr[i].textContent || tr[i].innerText;
            tr[i].style.display = (txtValue.toUpperCase().indexOf(filter) > -1) ? '' : 'none';
        }
    }
</script>
