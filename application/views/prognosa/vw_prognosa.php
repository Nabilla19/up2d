<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>

    <div class="container-fluid py-4">

        <div class="card mb-4 shadow border-0 rounded-4">
            <div class="card-header py-2 d-flex justify-content-between align-items-center bg-gradient-primary text-white rounded-top-4">
                <h6 class="mb-0 d-flex align-items-center text-white"><i class="fas fa-chart-line me-2"></i>Tabel Prognosa</h6>
            </div>

            <div class="card-body px-0 pt-0 pb-2 bg-white">

                <div class="px-3 mt-3 mb-3 d-flex justify-content-between align-items-center" style="gap:12px;">
                    <div class="d-flex align-items-center" style="gap:10px;">
                        <label class="mb-0 me-2 text-sm">Tampilkan:</label>
                        <select class="form-select form-select-sm" style="width: 90px;" onchange="changePerPageGlobal(this.value)">
                            <?php foreach ([5, 10, 25, 50, 100] as $pp): ?>
                                <option value="<?= $pp ?>" <?= ($per_page == $pp) ? 'selected' : '' ?>><?= $pp ?></option>
                            <?php endforeach; ?>
                        </select>

                        <span class="ms-2 text-sm">dari <?= (int)($total_rows ?? 0); ?> data</span>
                    </div>

                    <form method="get" action="<?= base_url('prognosa'); ?>" class="d-flex align-items-center" style="gap:8px;" onsubmit="event.preventDefault(); searchSubmitMulti('<?= base_url('prognosa'); ?>', ['perPageHiddenPrognosa', 'jenisSelect', 'rekapSelect', 'searchInputPrognosa'], ['per_page', 'jenis', 'rekap', 'search']);">
                        <input type="hidden" id="perPageHiddenPrognosa" name="per_page" value="<?= (int)$per_page; ?>">

                        <select id="jenisSelect" name="jenis" class="form-select form-select-sm" style="max-width:160px;">
                            <option value="">Semua Jenis</option>
                            <?php foreach (($jenis_list ?? []) as $j): ?>
                                <option value="<?= html_escape($j['jenis_anggaran']); ?>"
                                    <?= (($filter_jenis ?? '') === $j['jenis_anggaran']) ? 'selected' : '' ?>>
                                    <?= html_escape($j['jenis_anggaran']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <select id="rekapSelect" name="rekap" class="form-select form-select-sm" style="max-width:180px;">
                            <option value="">Semua Rekap</option>
                            <?php foreach (($rekap_list ?? []) as $r): ?>
                                <option value="<?= html_escape($r['rekap']); ?>"
                                    <?= (($filter_rekap ?? '') === $r['rekap']) ? 'selected' : '' ?>>
                                    <?= html_escape($r['rekap']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <input type="text" id="searchInputPrognosa" name="search" value="<?= html_escape($search ?? '') ?>"
                            class="form-control form-control-sm rounded-3"
                            style="max-width: 280px;"
                            placeholder="Cari jenis / rekap...">

                        <button type="submit" class="btn btn-sm btn-primary ms-2">Cari</button>
                        <?php if (!empty($search) || !empty($filter_jenis) || !empty($filter_rekap)): ?>
                            <a href="<?= base_url('prognosa?per_page=' . (int)($per_page ?? 5)); ?>" class="btn btn-sm btn-outline-secondary ms-2">Reset</a>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="table-responsive p-0" style="overflow-x:auto;">
                    <table class="table align-items-center mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 5%">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis Anggaran</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rekap PRK</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Besaran Anggaran</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bulan 1</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bulan 2</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bulan 3</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bulan 4</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bulan 5</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bulan 6</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bulan 7</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bulan 8</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bulan 9</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bulan 10</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bulan 11</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bulan 12</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Prognosa</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Deviasi (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($prognosa)): ?>
                                <tr>
                                    <td colspan="18" class="text-center text-sm py-4">Tidak ada data prognosa.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($prognosa as $i => $row): ?>
                                    <tr class="<?= (($start_no + $i) % 2 == 0) ? 'table-row-even' : 'table-row-odd'; ?>">
                                        <td class="text-sm text-center"><?= $start_no + $i; ?></td>
                                        <td class="text-sm"><?= html_escape($row['jenis_anggaran']); ?></td>
                                        <td class="text-sm"><?= html_escape($row['rekap'] ?? ''); ?></td>
                                        <td class="text-sm text-end"><?= number_format($row['pagu_skk_io'] ?? 0, 0, ',', '.'); ?></td>
                                        <?php for ($m = 1; $m <= 12; $m++): ?>
                                            <?php $month_key = ['jan_25','feb_25','mar_25','apr_25','mei_25','jun_25','jul_25','aug_25','sep_25','okt_25','nov_25','des_25'][$m-1]; ?>
                                            <td class="text-sm text-end"><?= number_format($row[$month_key] ?? 0, 0, ',', '.'); ?></td>
                                        <?php endfor; ?>
                                        <?php $total = array_sum(array_map(function($k) use ($row) { return $row[$k] ?? 0; }, ['jan_25','feb_25','mar_25','apr_25','mei_25','jun_25','jul_25','aug_25','sep_25','okt_25','nov_25','des_25'])); ?>
                                        <td class="text-sm text-end font-weight-bold"><?= number_format($total, 0, ',', '.'); ?></td>
                                        <td class="text-sm text-center">
                                            <?php
                                            $dev = 0; // Deviasi calculation can be added later
                                            $color = ($dev > 0) ? 'text-danger' : (($dev < 0) ? 'text-success' : '');
                                            ?>
                                            <span class="<?= $color ?>"><?= number_format($dev, 2); ?>%</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer d-flex justify-content-end p-3">
                    <?= $pagination; ?>
                </div>
            </div>
        </div>
    </div>
</main>

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

    tr:hover {
        background-color: #f2f2f2 !important;
    }
</style>