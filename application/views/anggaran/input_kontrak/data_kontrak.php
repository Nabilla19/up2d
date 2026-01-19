<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>

    <!-- Content -->
    <div class="container-fluid py-4">

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success text-white">
                <?= $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>

        <?php
        // ===============================
        // FALLBACK AGAR TIDAK WARNING
        // ===============================
        $per_page   = isset($per_page) ? (int)$per_page : (int)($this->input->get('per_page') ?? 10);
        $start_no   = isset($start_no) ? (int)$start_no : 1;
        $q          = isset($q) ? $q : (string)($this->input->get('q') ?? '');
        $pagination = isset($pagination) ? $pagination : '';
        $total_rows = isset($total_rows) ? (int)$total_rows : (is_array($kontrak) ? count($kontrak) : 0);

        function v($val, $dash = '-')
        {
            return ($val === null || $val === '') ? $dash : html_escape($val);
        }
        function rupiah($n)
        {
            return 'Rp ' . number_format((float)($n ?? 0), 0, ',', '.');
        }
        function fmt_date($d)
        {
            if (!$d) return '-';
            $t = strtotime($d);
            return $t ? date('d/m/Y', $t) : html_escape($d);
        }

        // ===============================
        // ROLE → HAK TOMBOL LOKAL
        // ===============================
        $role = null;
        if (isset($this) && isset($this->session)) {
            $r    = $this->session->userdata('user_role') ?: $this->session->userdata('role');
            $role = strtolower(trim((string)$r));
        }

        $role_full_access = in_array($role, [
            'admin',
            'administrator',
            'pemeliharaan',
            'fasilitas operasi',
            'pengadaan keuangan',
            'kku',
            'perencanaan'
        ], true);

        $can_create_local = $role_full_access;
        $can_edit_local   = $role_full_access;
        $can_delete_local = $role_full_access;

        if (function_exists('can_create') && can_create()) {
            $can_create_local = true;
        }
        if (function_exists('can_edit') && can_edit()) {
            $can_edit_local = true;
        }
        if (function_exists('can_delete') && can_delete()) {
            $can_delete_local = true;
        }
        ?>

        <div class="card mb-4 shadow border-0 rounded-4">
            <div class="card-header py-2 d-flex justify-content-between align-items-center bg-gradient-primary text-white rounded-top-4">
                <h6 class="mb-0 d-flex align-items-center">
                    <i class="fas fa-file-contract me-2"></i> Tabel Data Kontrak
                </h6>

                <div class="d-flex align-items-center" style="padding-top: 16px;">
                    <?php if ($can_create_local): ?>
                        <a href="<?= base_url('data_kontrak/tambah') ?>"
                            class="btn btn-sm btn-light text-primary me-2 d-flex align-items-center no-anim">
                            <i class="fas fa-plus me-1"></i> Tambah
                        </a>

                        <a href="<?= base_url('import/data_kontrak?return_to=' . urlencode(current_url())); ?>"
                            class="btn btn-sm btn-light text-success me-2 d-flex align-items-center no-anim">
                            <i class="fas fa-file-import me-1"></i> Import
                        </a>
                    <?php endif; ?>

                    <a href="<?= base_url('data_kontrak/export_csv') ?>"
                        class="btn btn-sm btn-light text-secondary d-flex align-items-center no-anim">
                        <i class="fas fa-file-csv me-1"></i> Download CSV
                    </a>
                </div>
            </div>

            <div class="card-body px-0 pt-0 pb-2 bg-white">

                <div class="px-3 mt-3 mb-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <label class="mb-0 me-2 text-sm">Tampilkan:</label>
                        <select id="perPageSelectKontrak" class="form-select form-select-sm" style="width: 90px; padding-right: 2rem;" onchange="changePerPageKontrak(this.value)">
                            <?php foreach ([5, 10, 25, 50, 100, 500] as $n): ?>
                                <option value="<?= $n ?>" <?= ($per_page == $n) ? 'selected' : ''; ?>><?= $n ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="ms-3 text-sm">dari <?= $total_rows; ?> data</span>
                    </div>

                    <input type="text"
                        id="searchInputKontrak"
                        class="form-control form-control-sm rounded-3"
                        style="max-width: 300px;"
                        placeholder="Cari data kontrak..."
                        value="<?= html_escape($q); ?>">
                </div>

                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="kontrakTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">PRK</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">DRP</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No Kontrak</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vendor</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tgl Kontrak</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nilai</th>
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
                                        <td class="text-sm"><?= v($jenis); ?></td>
                                        <td class="text-sm"><?= v($prk); ?></td>
                                        <td class="text-sm"><?= v($drp); ?></td>
                                        <td class="text-sm"><?= v($row->no_kontrak); ?></td>
                                        <td class="text-sm"><?= v($row->pelaksana_vendor); ?></td>
                                        <td class="text-sm"><?= fmt_date($row->tgl_kontrak); ?></td>
                                        <td class="text-sm"><?= rupiah($row->nilai_kontrak); ?></td>
                                        <td class="text-sm"><?= v($row->status_kontrak); ?></td>

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
    function changePerPageKontrak(perPage) {
        // Reset to page 1 (offset 0) when changing limit
        const url = new URL("<?= base_url('data_kontrak/index'); ?>");
        const q = document.getElementById('searchInputKontrak').value.trim();
        url.searchParams.set('per_page', perPage);
        if (q) url.searchParams.set('q', q);
        window.location.href = url.toString();
    }

    (function() {
        const input = document.getElementById('searchInputKontrak');
        if (!input) return;

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                // Reset to page 1 (offset 0) when searching
                const url = new URL("<?= base_url('data_kontrak/index'); ?>");
                const current = new URL(window.location.href);
                const per = current.searchParams.get('per_page') || "<?= $per_page ?>"; // use PHP value as fallback
                if (per) url.searchParams.set('per_page', per);
                const q = input.value.trim();
                if (q) url.searchParams.set('q', q);
                window.location.href = url.toString();
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

    #kontrakTable tbody tr:hover {
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

    #kontrakTable tbody tr td {
        padding-top: 2px !important;
        padding-bottom: 2px !important;
        font-size: 13px !important;
    }

    #kontrakTable thead th {
        padding-top: 8px !important;
        padding-bottom: 8px !important;
        font-size: 12px !important;
    }

    .no-anim,
    .no-anim * {
        transition: none !important;
        animation: none !important;
    }
</style>