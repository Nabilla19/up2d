<div class="container-fluid py-4 print-area">
    <?php
    // Helper kecil di view
    function v($val, $dash = '-')
    {
        return ($val === null || $val === '') ? $dash : html_escape($val);
    }
    function rupiah($n)
    {
        $n = (float)($n ?? 0);
        return 'Rp ' . number_format($n, 0, ',', '.');
    }
    function fmt_date($d)
    {
        if (!$d) return '-';
        $t = strtotime($d);
        return $t ? date('d/m/Y', $t) : html_escape($d);
    }

    // Ambil preferensi data manual jika ada
    $nomorPrk  = $kontrak->nomor_prk_text    ?? ($kontrak->nomor_prk ?? null);
    $nomorSkk  = $kontrak->nomor_skk_io_text ?? ($kontrak->nomor_skk_io ?? null);
    $jenisAng  = $kontrak->jenis_anggaran_text ?? ($kontrak->jenis_anggaran_nama ?? null);
    $uraianPrk = $kontrak->uraian_prk_text   ?? ($kontrak->uraian_prk ?? null);
    $drp       = $kontrak->drp_text          ?? ($kontrak->judul_drp ?? null);

    $totalRealisasi =
        (int)($kontrak->real_byr_bln1 ?? 0) + (int)($kontrak->real_byr_bln2 ?? 0) + (int)($kontrak->real_byr_bln3 ?? 0) +
        (int)($kontrak->real_byr_bln4 ?? 0) + (int)($kontrak->real_byr_bln5 ?? 0) + (int)($kontrak->real_byr_bln6 ?? 0) +
        (int)($kontrak->real_byr_bln7 ?? 0) + (int)($kontrak->real_byr_bln8 ?? 0) + (int)($kontrak->real_byr_bln9 ?? 0) +
        (int)($kontrak->real_byr_bln10 ?? 0) + (int)($kontrak->real_byr_bln11 ?? 0) + (int)($kontrak->real_byr_bln12 ?? 0);

    $nilaiKontrak = (int)($kontrak->nilai_kontrak ?? 0);
    $outstanding  = $nilaiKontrak - $totalRealisasi;
    ?>

    <!-- ROW: SEKARANG DIBUAT LEBIH KE TENGAH -->
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-9 col-md-10 detail-kontrak-wrapper">
            <div class="card shadow border-0 rounded-4">

                <!-- HEADER NORMAL (LAYAR) -->
                <div class="card-header bg-gradient-primary text-white rounded-top-4 d-flex justify-content-between align-items-center no-print">
                    <div>
                        <h6 class="mb-1 text-white">
                            <i class="fas fa-file-contract me-2 text-warning"></i> Detail Kontrak
                        </h6>
                        <div class="small mt-1">
                            <?php if ($kontrak->no_kontrak): ?>
                                <span class="badge bg-light text-primary me-1">No Kontrak: <?= v($kontrak->no_kontrak); ?></span>
                            <?php endif; ?>
                            <?php if ($nomorPrk): ?>
                                <span class="badge bg-light text-primary me-1">PRK: <?= v($nomorPrk); ?></span>
                            <?php endif; ?>
                            <?php if ($nomorSkk): ?>
                                <span class="badge bg-light text-primary me-1">SKK-IO: <?= v($nomorSkk); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="<?= base_url('data_kontrak') ?>" class="btn btn-danger btn-sm px-3 no-anim">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="button"
                            class="btn btn-light btn-sm text-primary px-3 no-anim"
                            onclick="window.print();">
                            <i class="fas fa-print me-1"></i> Print
                        </button>
                    </div>
                </div>

                <!-- HEADER UNTUK CETAK (LEBIH SIMPLE) -->
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center only-print">
                    <div>
                        <h6 class="mb-1">Detail Kontrak</h6>
                        <div class="small mt-1 text-muted">
                            <?php if ($kontrak->no_kontrak): ?>
                                <span class="me-2">No Kontrak: <?= v($kontrak->no_kontrak); ?></span>
                            <?php endif; ?>
                            <?php if ($nomorPrk): ?>
                                <span class="me-2">PRK: <?= v($nomorPrk); ?></span>
                            <?php endif; ?>
                            <?php if ($nomorSkk): ?>
                                <span class="me-2">SKK-IO: <?= v($nomorSkk); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="small text-muted">
                        Dicetak: <?= date('d/m/Y H:i'); ?>
                    </div>
                </div>

                <!-- BODY -->
                <div class="card-body">

                    <!-- RINGKASAN 3 KOLOM -->
                    <div class="row mb-4 g-3">
                        <div class="col-md-4">
                            <div class="summary-box">
                                <span class="label">No Kontrak</span>
                                <p class="value"><?= v($kontrak->no_kontrak); ?></p>
                                <span class="label mt-2">Vendor</span>
                                <p class="value"><?= v($kontrak->pelaksana_vendor); ?></p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="summary-box">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="label">Nilai Kontrak</span>
                                    <span class="badge bg-primary-subtle text-primary small">Kontrak</span>
                                </div>
                                <p class="value fw-bold fs-6"><?= rupiah($nilaiKontrak); ?></p>
                                <span class="label mt-2">Total Realisasi</span>
                                <p class="value"><?= rupiah($totalRealisasi); ?></p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="summary-box">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="label">Outstanding</span>
                                    <span class="badge bg-danger-subtle text-danger small">
                                        <?= $outstanding < 0 ? 'Over' : 'Sisa'; ?>
                                    </span>
                                </div>
                                <p class="value fw-bold fs-6"><?= rupiah($outstanding); ?></p>
                                <span class="label mt-2">Status Kontrak</span>
                                <p class="value">
                                    <?php if ($kontrak->status_kontrak): ?>
                                        <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill">
                                            <?= v($kontrak->status_kontrak); ?>
                                        </span>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- BAGIAN: DATA MASTER & USER -->
                    <div class="mb-4">
                        <h6 class="section-title">
                            <span class="section-bar bg-primary"></span>
                            <span>Data Master & User</span>
                        </h6>
                        <div class="row">
                            <?php
                            $data_master_user = [
                                'Jenis Anggaran'      => v($jenisAng),
                                'Nomor PRK'           => v($nomorPrk),
                                'Nomor SKK-IO'        => v($nomorSkk),
                                'Pagu SKK-I/O'        => rupiah($kontrak->pagu_skk_io ?? 0),
                                'Sisa Anggaran'       => rupiah($kontrak->sisa_anggaran ?? 0),
                                'Uraian PRK'          => v($uraianPrk),
                                'DRP'                 => v($drp),
                                'Uraian Pekerjaan'    => v($kontrak->uraian_pekerjaan),
                                'User Pengusul'       => v($kontrak->user_pengusul),
                                'RAB User'            => rupiah($kontrak->rab_user ?? 0),
                                'Rencana Hari Kerja'  => v($kontrak->rencana_hari_kerja) . ' hari',
                                'Jenis Penagihan'     => v($kontrak->jenis_penagihan),
                                'Tanggal BASTP'       => fmt_date($kontrak->tanggal_bastp),
                            ];
                            foreach ($data_master_user as $label => $val): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="detail-item">
                                        <span class="label"><?= $label; ?></span>
                                        <p class="value"><?= $val; ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- BAGIAN: PERENCANAAN -->
                    <div class="mb-4">
                        <h6 class="section-title">
                            <span class="section-bar bg-info"></span>
                            <span>Perencanaan (ND / AMS)</span>
                        </h6>
                        <div class="row">
                            <?php
                            $data_perencanaan = [
                                'Tgl ND/AMS'  => fmt_date($kontrak->tgl_nd_ams),
                                'No ND/AMS'   => v($kontrak->nomor_nd_ams),
                                'Keterangan'  => v($kontrak->keterangan),
                            ];
                            foreach ($data_perencanaan as $label => $val): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="detail-item">
                                        <span class="label"><?= $label; ?></span>
                                        <p class="value"><?= $val; ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- BAGIAN: PENGADAAN -->
                    <div class="mb-4">
                        <h6 class="section-title">
                            <span class="section-bar bg-warning"></span>
                            <span>Pengadaan</span>
                        </h6>
                        <div class="row">
                            <?php
                            $data_pengadaan = [
                                'No RKS'             => v($kontrak->no_rks),
                                'Metode Pengadaan'   => v($kontrak->metode_pengadaan),
                                'Tahapan Pengadaan'  => v($kontrak->tahapan_pengadaan),
                                'Prognosa Kontrak'   => fmt_date($kontrak->prognosa_kontrak_tgl),
                                'HPE / HPS / Nego'   => rupiah($kontrak->harga_hpe ?? 0) . ' / ' .
                                    rupiah($kontrak->harga_hps ?? 0) . ' / ' .
                                    rupiah($kontrak->harga_nego ?? 0),
                                'KAK'                => v($kontrak->kak),
                                'Vendor'             => v($kontrak->pelaksana_vendor),
                                'Tgl Kontrak'        => fmt_date($kontrak->tgl_kontrak),
                                'End Kontrak'        => fmt_date($kontrak->end_kontrak),
                                'Nilai Kontrak'      => rupiah($kontrak->nilai_kontrak ?? 0),
                                'Kendala Kontrak'    => v($kontrak->kendala_kontrak),
                            ];
                            foreach ($data_pengadaan as $label => $val): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="detail-item">
                                        <span class="label"><?= $label; ?></span>
                                        <p class="value"><?= $val; ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- BAGIAN: KKU / PEMBAYARAN -->
                    <div class="mb-2">
                        <h6 class="section-title">
                            <span class="section-bar bg-success"></span>
                            <span>KKU / Pembayaran</span>
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="detail-item">
                                    <span class="label">Tahapan Pembayaran</span>
                                    <p class="value"><?= v($kontrak->tahapan_pembayaran); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="detail-item">
                                    <span class="label">Nilai Bayar Terakhir</span>
                                    <p class="value"><?= rupiah($kontrak->nilai_bayar ?? 0); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="detail-item">
                                    <span class="label">Tanggal Tahapan</span>
                                    <p class="value"><?= fmt_date($kontrak->tgl_tahapan); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="detail-item">
                                    <span class="label">Total Realisasi</span>
                                    <p class="value"><?= rupiah($totalRealisasi); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="detail-item">
                                    <span class="label">Outstanding</span>
                                    <p class="value"><?= rupiah($outstanding); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Realisasi Bulanan -->
                        <div class="table-responsive mt-2">
                            <table class="table table-sm table-bordered align-middle mb-0">
                                <thead class="bg-light text-center small">
                                    <tr>
                                        <th>BLN1</th>
                                        <th>BLN2</th>
                                        <th>BLN3</th>
                                        <th>BLN4</th>
                                        <th>BLN5</th>
                                        <th>BLN6</th>
                                        <th>BLN7</th>
                                        <th>BLN8</th>
                                        <th>BLN9</th>
                                        <th>BLN10</th>
                                        <th>BLN11</th>
                                        <th>BLN12</th>
                                    </tr>
                                </thead>
                                <tbody class="small text-end">
                                    <tr>
                                        <td><?= rupiah($kontrak->real_byr_bln1 ?? 0); ?></td>
                                        <td><?= rupiah($kontrak->real_byr_bln2 ?? 0); ?></td>
                                        <td><?= rupiah($kontrak->real_byr_bln3 ?? 0); ?></td>
                                        <td><?= rupiah($kontrak->real_byr_bln4 ?? 0); ?></td>
                                        <td><?= rupiah($kontrak->real_byr_bln5 ?? 0); ?></td>
                                        <td><?= rupiah($kontrak->real_byr_bln6 ?? 0); ?></td>
                                        <td><?= rupiah($kontrak->real_byr_bln7 ?? 0); ?></td>
                                        <td><?= rupiah($kontrak->real_byr_bln8 ?? 0); ?></td>
                                        <td><?= rupiah($kontrak->real_byr_bln9 ?? 0); ?></td>
                                        <td><?= rupiah($kontrak->real_byr_bln10 ?? 0); ?></td>
                                        <td><?= rupiah($kontrak->real_byr_bln11 ?? 0); ?></td>
                                        <td><?= rupiah($kontrak->real_byr_bln12 ?? 0); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(90deg, #005C99, #0099CC);
    }

    /* kotak di-tengah dan lebih kecil */
    .detail-kontrak-wrapper {
        margin-top: 0.5rem;
        margin-bottom: 1rem;
        max-width: 950px;
        margin-left: auto;
        margin-right: auto;
    }

    .summary-box {
        background: #f8f9fc;
        border-radius: 12px;
        padding: 10px 14px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
        height: 100%;
    }

    .detail-item {
        background: #f8f9fc;
        border-radius: 10px;
        padding: 8px 14px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .label {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.8rem;
    }

    .value {
        color: #333;
        margin: 0;
        font-size: 0.9rem;
    }

    .section-title {
        font-weight: 600;
        font-size: 0.9rem;
        color: #2c3e50;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .section-bar {
        width: 6px;
        height: 18px;
        border-radius: 10px;
        display: inline-block;
    }

    .bg-primary-subtle {
        background: rgba(0, 92, 153, 0.08);
    }

    .bg-danger-subtle {
        background: rgba(220, 53, 69, 0.08);
    }

    .bg-success-subtle {
        background: rgba(25, 135, 84, 0.08);
    }

    .no-anim,
    .no-anim * {
        transition: none !important;
        animation: none !important;
        transform: none !important;
        box-shadow: none !important;
        outline: none !important;
    }

    .only-print {
        display: none;
    }

    /* =====================================
       PRINT MODE
       ===================================== */
    @media print {

        #sidenav-main,
        .navbar,
        .fixed-plugin,
        .no-print {
            display: none !important;
        }

        body {
            background: #ffffff !important;
        }

        .g-sidenav-show .main-content {
            margin-left: 0 !important;
        }

        .print-area {
            margin: 0 !important;
            padding: 0.5cm 0.8cm !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        .card {
            box-shadow: none !important;
            border-radius: 0 !important;
            border: 1px solid #000 !important;
        }

        .only-print {
            display: flex !important;
        }
    }
</style>