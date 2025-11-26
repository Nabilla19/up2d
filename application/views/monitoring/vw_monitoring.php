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
                <h6 class="mb-0 d-flex align-items-center text-white">Tabel Monitoring</h6>
                <div class="d-flex align-items-center" style="padding-top: 16px;">
                    <?php if (can_create()): ?>
                        <a href="<?= base_url('Monitoring/tambah') ?>" class="btn btn-sm btn-light text-primary me-2 d-flex align-items-center">
                            <i class="fas fa-plus me-1"></i> Tambah
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body px-0 pt-0 pb-2 bg-white">

                <!-- CONTROLS -->
                <div class="px-3 mt-3 mb-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <label class="mb-0 me-2 text-sm">Tampilkan:</label>
                        <select id="perPageSelect" class="form-select form-select-sm" style="width: 80px;" onchange="changePerPage(this.value)">
                            <option value="5" <?= ($per_page == 5) ? 'selected' : ''; ?>>5</option>
                            <option value="10" <?= ($per_page == 10) ? 'selected' : ''; ?>>10</option>
                            <option value="25" <?= ($per_page == 25) ? 'selected' : ''; ?>>25</option>
                            <option value="50" <?= ($per_page == 50) ? 'selected' : ''; ?>>50</option>
                        </select>
                        <span class="ms-3 text-sm">dari <?= $total_rows ?? 0; ?> data</span>
                    </div>

                    <input type="text" id="searchInputMonitoring" onkeyup="searchTableMonitoring()"
                        class="form-control form-control-sm rounded-3"
                        style="max-width: 300px;" placeholder="Cari data...">
                </div>

                <!-- TABLE -->
                <div class="table-responsive p-0" style="max-height: 600px; overflow-y:auto;">
                    <table class="table align-items-center mb-0" id="monitoringTable">
                        <thead class="bg-light">
                            <tr>
                                <?php
                                $headers = [
                                    'No',
                                    'Nomor PRK',
                                    'Nomor SKK-IO',
                                    'Pagu SKK-IO',
                                    'Sisa Anggaran',
                                    'Jenis Anggaran',
                                    'Uraian PRK',
                                    'DRP',
                                    'Uraian Pekerjaan',
                                    'User',
                                    'RAB User',
                                    'Renc HK',
                                    'Tgl ND/AMS',
                                    'No ND/AMS',
                                    'Keterangan',
                                    'Status Kontrak',
                                    'No Kontrak',
                                    'Pelaksana',
                                    'Tgl Kontrak',
                                    'End Kontrak',
                                    'Nilai Kontrak',
                                    'Kendala'
                                ];
                                ?>

                                <?php foreach ($headers as $h): ?>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"><?= $h ?></th>
                                <?php endforeach; ?>

                                <!-- REAL BAYAR 12 bulan -->
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Real Bayar Bln <?= $i ?>
                                    </th>
                                <?php endfor; ?>

                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alokasi Kontrak Tahun Ini</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah Renc</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah Bayar 2026</th>

                                <!-- KTRK 12 bulan -->
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        KTRK Bln <?= $i ?>
                                    </th>
                                <?php endfor; ?>

                                <!-- Rencana Bayar 12 bulan -->
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Renc Bayar Bln <?= $i ?>
                                    </th>
                                <?php endfor; ?>

                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (empty($monitoring)): ?>
                                <tr>
                                    <td colspan="200" class="text-center text-secondary py-4">Belum ada data</td>
                                </tr>
                            <?php else: ?>

                                <?php $no = $start_no; ?>
                                <?php foreach ($monitoring as $row): ?>

                                    <tr class="<?= ($no % 2 == 0) ? 'table-row-even' : 'table-row-odd'; ?>">
                                        <td class="text-sm"><?= $no++; ?></td>

                                        <td class="text-sm"><?= $row['NOMOR_PRK'] ?></td>
                                        <td class="text-sm"><?= $row['NOMOR_SKK_IO'] ?></td>
                                        <td class="text-sm"><?= number_format($row['PAGU_SKK_IO']) ?></td>
                                        <td class="text-sm"><?= number_format($row['SISA_ANGGARAN']) ?></td>
                                        <td class="text-sm"><?= $row['JENIS_ANGGARAN'] ?></td>
                                        <td class="text-sm"><?= $row['URAIAN_PRK'] ?></td>
                                        <td class="text-sm"><?= $row['DRP'] ?></td>
                                        <td class="text-sm"><?= $row['URAIAN_PEKERJAAN'] ?></td>
                                        <td class="text-sm"><?= $row['USER'] ?></td>
                                        <td class="text-sm"><?= number_format($row['RAB_USER']) ?></td>
                                        <td class="text-sm"><?= $row['RENC_HARI_KERJA'] ?></td>
                                        <td class="text-sm"><?= $row['TGL_ND_AMS'] ?></td>
                                        <td class="text-sm"><?= $row['NOMOR_ND_AMS'] ?></td>
                                        <td class="text-sm"><?= $row['KETERANGAN'] ?></td>
                                        <td class="text-sm"><?= $row['STATUS_KONTRAK'] ?></td>
                                        <td class="text-sm"><?= $row['NO_KONTRAK'] ?></td>
                                        <td class="text-sm"><?= $row['PELAKSANA_VENDOR'] ?></td>
                                        <td class="text-sm"><?= $row['TGL_KONTRAK'] ?></td>
                                        <td class="text-sm"><?= $row['END_KONTRAK'] ?></td>
                                        <td class="text-sm"><?= number_format($row['NILAI_KONTRAK']) ?></td>
                                        <td class="text-sm"><?= $row['KENDALA_KONTRAK'] ?></td>

                                        <?php for ($i = 1; $i <= 12; $i++): ?>
                                            <td class="text-sm"><?= number_format($row["REAL_BYR_BLN$i"]) ?></td>
                                        <?php endfor; ?>

                                        <td class="text-sm"><?= number_format($row['ALOKASI_KONTRAK_THN_INI']) ?></td>
                                        <td class="text-sm"><?= number_format($row['JML_RENC']) ?></td>
                                        <td class="text-sm"><?= number_format($row['JML_BYR_2026']) ?></td>

                                        <?php for ($i = 1; $i <= 12; $i++): ?>
                                            <td class="text-sm"><?= number_format($row["KTRK_BLN$i"]) ?></td>
                                        <?php endfor; ?>

                                        <?php for ($i = 1; $i <= 12; $i++): ?>
                                            <td class="text-sm"><?= number_format($row["RENC_BYR_BLN$i"]) ?></td>
                                        <?php endfor; ?>

                                        <td class="text-center">
                                            <a href="<?= base_url('Monitoring/detail/' . $row['ID_MONITORING']) ?>" class="btn btn-info btn-xs text-white">Detail</a>
                                            <?php if (can_edit()): ?>
                                                <a href="<?= base_url('Monitoring/edit/' . $row['ID_MONITORING']) ?>" class="btn btn-warning btn-xs text-white">Edit</a>
                                            <?php endif; ?>
                                            <?php if (can_delete()): ?>
                                                <a href="<?= base_url('Monitoring/hapus/' . $row['ID_MONITORING']) ?>" class="btn btn-danger btn-xs btn-hapus">Hapus</a>
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
                    <?= $pagination; ?>
                </div>

            </div>
        </div>
    </div>
</main>

<style>
    .bg-gradient-primary {
        background: linear-gradient(90deg, #005C99, #0099CC);
    }

    .table-row-even {
        background: #f9f9f9;
    }

    .table-row-odd {
        background: #ffffff;
    }

    table thead th {
        position: sticky;
        top: 0;
        background: #e9f7fb !important;
        z-index: 5;
    }

    .btn-xs {
        padding: 2px 6px;
        font-size: 11px;
    }
</style>