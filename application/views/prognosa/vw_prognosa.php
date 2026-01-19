<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
if (!function_exists('e')) {
    function e($v)
    {
        return htmlentities((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
    }
}
if (!function_exists('nf')) {
    function nf($v)
    {
        return number_format((float)($v ?? 0), 0, ',', '.');
    }
}
function sum12($r)
{
    $months = ['jan_25', 'feb_25', 'mar_25', 'apr_25', 'mei_25', 'jun_25', 'jul_25', 'aug_25', 'sep_25', 'okt_25', 'nov_25', 'des_25'];
    $t = 0;
    foreach ($months as $m) {
        $t += (float)($r[$m] ?? 0);
    }
    return $t;
}
?>

<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>

    <div class="container-fluid py-4">

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success text-white"><?= $this->session->flashdata('success'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger text-white"><?= $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <div class="card mb-4 shadow border-0 rounded-4">

            <div class="card-header py-2 d-flex justify-content-between align-items-center bg-gradient-primary text-white rounded-top-4">
                <h6 class="mb-0 d-flex align-items-center text-white"><i class="fas fa-chart-line me-2"></i>Tabel Prognosa (VW_PROGNOSA)</h6>

                <div class="d-flex align-items-center" style="padding-top: 16px;">
                    <a href="<?= base_url('prognosa/export_csv?' . http_build_query($_GET)); ?>"
                        class="btn btn-sm btn-light text-secondary ms-2 d-flex align-items-center no-anim">
                        <i class="fas fa-file-csv me-1"></i> Download CSV
                    </a>
                </div>
            </div>

            <div class="card-body px-0 pt-0 pb-2 bg-white">

                <div class="px-3 mt-3 mb-3 d-flex justify-content-between align-items-center" style="gap:12px;">
                    <div class="d-flex align-items-center" style="gap:10px;">
                        <label class="mb-0 me-2 text-sm">Tampilkan:</label>
                        <select class="form-select form-select-sm" style="width: 90px;" onchange="changePerPage(this.value)">
                            <?php foreach ([5, 10, 25, 50, 100] as $pp): ?>
                                <option value="<?= $pp ?>" <?= ($per_page == $pp) ? 'selected' : '' ?>><?= $pp ?></option>
                            <?php endforeach; ?>
                        </select>

                        <span class="ms-2 text-sm">dari <?= (int)($total_rows ?? 0); ?> data</span>
                    </div>

                    <form method="get" action="<?= base_url('prognosa'); ?>" class="d-flex align-items-center" style="gap:8px;">
                        <input type="hidden" name="per_page" value="<?= (int)$per_page; ?>">

                        <select name="jenis_anggaran" class="form-select form-select-sm" style="max-width:160px;">
                            <option value="">Semua Jenis</option>
                            <?php foreach (($jenis_list ?? []) as $j): ?>
                                <option value="<?= e($j['jenis_anggaran']); ?>"
                                    <?= (($jenis_anggaran ?? '') === $j['jenis_anggaran']) ? 'selected' : '' ?>>
                                    <?= e($j['jenis_anggaran']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <select name="rekap" class="form-select form-select-sm" style="max-width:180px;">
                            <option value="">Semua Rekap</option>
                            <?php foreach (($rekap_list ?? []) as $r): ?>
                                <option value="<?= e($r['rekap']); ?>"
                                    <?= (($rekap ?? '') === $r['rekap']) ? 'selected' : '' ?>>
                                    <?= e($r['rekap']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <input type="text" name="keyword" value="<?= e($keyword ?? '') ?>"
                            class="form-control form-control-sm rounded-3"
                            style="max-width: 280px;"
                            placeholder="Cari jenis / rekap...">

                        <button class="btn btn-sm btn-secondary">Cari</button>
                    </form>
                </div>

                <div class="table-responsive p-0" style="overflow-x:auto;">
                    <table class="table align-items-center mb-0" id="prognosaTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rekap</th>

                                <?php
                                $bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                                foreach ($bulan as $b):
                                ?>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end"><?= $b ?> 25</th>
                                <?php endforeach; ?>

                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">TOTAL</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (empty($rows)): ?>
                                <tr>
                                    <td colspan="20" class="text-center text-secondary py-4">Belum ada data</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = (int)($start_no ?? 1); ?>
                                <?php foreach ($rows as $row): ?>
                                    <tr class="<?= ($no % 2 == 0) ? 'table-row-even' : 'table-row-odd'; ?>">
                                        <td class="text-sm"><?= $no++; ?></td>
                                        <td class="text-sm"><?= e($row['jenis_anggaran']); ?></td>
                                        <td class="text-sm fw-semibold"><?= e($row['rekap']); ?></td>

                                        <td class="text-sm text-end"><?= nf($row['jan_25']); ?></td>
                                        <td class="text-sm text-end"><?= nf($row['feb_25']); ?></td>
                                        <td class="text-sm text-end"><?= nf($row['mar_25']); ?></td>
                                        <td class="text-sm text-end"><?= nf($row['apr_25']); ?></td>
                                        <td class="text-sm text-end"><?= nf($row['mei_25']); ?></td>
                                        <td class="text-sm text-end"><?= nf($row['jun_25']); ?></td>
                                        <td class="text-sm text-end"><?= nf($row['jul_25']); ?></td>
                                        <td class="text-sm text-end"><?= nf($row['aug_25']); ?></td>
                                        <td class="text-sm text-end"><?= nf($row['sep_25']); ?></td>
                                        <td class="text-sm text-end"><?= nf($row['okt_25']); ?></td>
                                        <td class="text-sm text-end"><?= nf($row['nov_25']); ?></td>
                                        <td class="text-sm text-end"><?= nf($row['des_25']); ?></td>

                                        <td class="text-sm text-end fw-semibold"><?= nf(sum12($row)); ?></td>

                                        <td class="text-center">
                                            <?php
                                            $detail_url = base_url('prognosa/detail?') . http_build_query([
                                                'jenis' => $row['jenis_anggaran'],
                                                'rekap' => $row['rekap'],
                                            ]);
                                            ?>
                                            <a href="<?= $detail_url; ?>" class="btn btn-info btn-xs text-white me-1" title="Detail">
                                                <i class="fas fa-info-circle"></i>
                                            </a>
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
        const base = "<?= site_url('prognosa'); ?>"; // uses page query string
        const url = new URL(base, window.location.origin);
        url.searchParams.set('per_page', perPage);
        url.searchParams.set('page', 0);
        window.location.href = url.toString();
    }
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

    #prognosaTable tbody tr:hover {
        background-color: #e9ecef !important;
        transition: none !important;
    }

    .btn,
    .btn-xs {
        transition: none !important;
        transform: none !important;
    }

    .btn-xs {
        padding: 2px 6px;
        font-size: 11px;
        border-radius: 4px;
    }

    #prognosaTable tbody tr td {
        padding-top: 2px !important;
        padding-bottom: 2px !important;
        font-size: 13px !important;
    }

    #prognosaTable thead th {
        padding-top: 8px !important;
        padding-bottom: 8px !important;
        font-size: 12px !important;
    }
</style>