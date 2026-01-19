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
function badge_status($s)
{
    $s = strtoupper(trim((string)$s));
    $map = [
        'SELESAI'     => 'bg-success',
        'TERKONTRAK'  => 'bg-info',
        'PROSES'      => 'bg-warning text-dark',
        'RENCANA'     => 'bg-secondary'
    ];
    $cls = $map[$s] ?? 'bg-light text-dark';
    return '<span class="badge ' . $cls . '" style="font-size:12px;">' . e($s) . '</span>';
}

$row = $row ?? [];
$id  = (int)($row['id'] ?? 0);

$bulan = [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'];
?>

<main class="main-content position-relative border-radius-lg">
    <?php $this->load->view('layout/navbar'); ?>

    <div class="container-fluid py-4">
        <div class="card shadow border-0 rounded-4 overflow-hidden">

            <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-sm opacity-8">Detail Monitoring</div>
                    <h6 class="mb-0">#<?= $id; ?></h6>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= base_url('monitoring'); ?>" class="btn btn-sm btn-light">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                    <a href="<?= base_url('entry_kontrak/detail/' . $id); ?>" class="btn btn-sm btn-light">
                        <i class="fas fa-file-alt me-1"></i> Detail Kontrak
                    </a>
                </div>
            </div>

            <div class="card-body">

                <!-- RINGKASAN -->
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <div class="box-lite">
                            <div class="label">Status Kontrak</div>
                            <div class="value"><?= badge_status($row['status_kontrak'] ?? ''); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="box-lite">
                            <div class="label">Nilai Kontrak</div>
                            <div class="value"><?= nf($row['nilai_kontrak'] ?? 0); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="box-lite">
                            <div class="label">Terbayar Tahun Ini</div>
                            <div class="value"><?= nf($row['terbayar_thn_ini'] ?? 0); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="box-lite">
                            <div class="label">Sisa Anggaran</div>
                            <div class="value"><?= nf($row['sisa_anggaran'] ?? 0); ?></div>
                        </div>
                    </div>
                </div>

                <!-- MASTER -->
                <div class="section-title">
                    <i class="fas fa-database me-2"></i> MASTER
                </div>
                <div class="row g-3 section-card">
                    <div class="col-md-3">
                        <div class="label">Jenis Anggaran</div>
                        <div class="value"><?= e($row['jenis_anggaran'] ?? ''); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="label">Nomor PRK</div>
                        <div class="value"><?= e($row['nomor_prk'] ?? ''); ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="label">Uraian PRK</div>
                        <div class="value"><?= e($row['uraian_prk'] ?? ''); ?></div>
                    </div>

                    <div class="col-md-6">
                        <div class="label">Nomor SKK IO</div>
                        <div class="value"><?= e($row['nomor_skk_io'] ?? ''); ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="label">Pagu SKK IO</div>
                        <div class="value"><?= nf($row['pagu_skk_io'] ?? 0); ?></div>
                    </div>

                    <div class="col-md-6">
                        <div class="label">Judul DRP</div>
                        <div class="value"><?= e($row['judul_drp'] ?? ''); ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="label">DRP</div>
                        <div class="value"><?= e($row['drp'] ?? ''); ?></div>
                    </div>
                </div>

                <!-- USER -->
                <div class="section-title mt-4">
                    <i class="fas fa-user me-2"></i> USER
                </div>
                <div class="row g-3 section-card">
                    <div class="col-md-6">
                        <div class="label">Uraian Pekerjaan</div>
                        <div class="value"><?= e($row['uraian_pekerjaan'] ?? ''); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="label">User Pengusul</div>
                        <div class="value"><?= e($row['user_pengusul'] ?? ''); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="label">RAB User</div>
                        <div class="value"><?= nf($row['rab_user'] ?? 0); ?></div>
                    </div>

                    <div class="col-md-3">
                        <div class="label">Rencana Hari Kerja</div>
                        <div class="value"><?= e($row['renc_hari_kerja'] ?? ''); ?></div>
                    </div>

                    <div class="col-md-3">
                        <div class="label">Tgl ND/AMS</div>
                        <div class="value"><?= e($row['tgl_nd_ams'] ?? ''); ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="label">Nomor ND/AMS</div>
                        <div class="value"><?= e($row['nomor_nd_ams'] ?? ''); ?></div>
                    </div>

                    <div class="col-md-12">
                        <div class="label">Keterangan</div>
                        <div class="value"><?= e($row['keterangan'] ?? ''); ?></div>
                    </div>
                </div>

                <!-- KONTRAK -->
                <div class="section-title mt-4">
                    <i class="fas fa-file-contract me-2"></i> KONTRAK
                </div>
                <div class="row g-3 section-card">
                    <div class="col-md-3">
                        <div class="label">No Kontrak</div>
                        <div class="value"><?= e($row['no_kontrak'] ?? ''); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="label">Vendor</div>
                        <div class="value"><?= e($row['vendor'] ?? ''); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="label">Tgl Kontrak</div>
                        <div class="value"><?= e($row['tgl_kontrak'] ?? ''); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="label">End Kontrak</div>
                        <div class="value"><?= e($row['end_kontrak'] ?? ''); ?></div>
                    </div>

                    <div class="col-md-12">
                        <div class="label">Kendala Kontrak</div>
                        <div class="value"><?= e($row['kendala_kontrak'] ?? ''); ?></div>
                    </div>
                </div>

                <!-- PENGADAAN (BARU) -->
                <div class="section-title mt-4">
                    <i class="fas fa-shopping-cart me-2"></i> PENGADAAN (Tambahan)
                </div>
                <div class="row g-3 section-card">
                    <div class="col-md-3">
                        <div class="label">No RKS</div>
                        <div class="value"><?= e($row['no_rks'] ?? ''); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="label">KAK</div>
                        <div class="value"><?= e($row['kak'] ?? ''); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="label">Metode Pengadaan</div>
                        <div class="value"><?= e($row['metode_pengadaan'] ?? ''); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="label">Tahapan Pengadaan</div>
                        <div class="value"><?= e($row['tahapan_pengadaan'] ?? ''); ?></div>
                    </div>

                    <div class="col-md-3">
                        <div class="label">Harga HPE</div>
                        <div class="value"><?= nf($row['harga_hpe'] ?? 0); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="label">Harga HPS</div>
                        <div class="value"><?= nf($row['harga_hps'] ?? 0); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="label">Harga Nego</div>
                        <div class="value"><?= nf($row['harga_nego'] ?? 0); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="label">Prognosa Kontrak</div>
                        <div class="value"><?= e($row['prognosa_kontrak'] ?? ''); ?></div>
                    </div>
                </div>

                <!-- KKU / PEMBAYARAN -->
                <div class="section-title mt-4">
                    <i class="fas fa-credit-card me-2"></i> KKU / PEMBAYARAN
                </div>
                <div class="row g-3 section-card">
                    <div class="col-md-4">
                        <div class="label">Tahapan Pembayaran</div>
                        <div class="value"><?= e($row['tahapan_pembayaran'] ?? ''); ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="label">Nilai Bayar</div>
                        <div class="value"><?= nf($row['nilai_bayar'] ?? 0); ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="label">Tgl Tahapan</div>
                        <div class="value"><?= e($row['tgl_tahapan'] ?? ''); ?></div>
                    </div>
                </div>

                <!-- PERHITUNGAN -->
                <div class="section-title mt-4">
                    <i class="fas fa-calculator me-2"></i> PERHITUNGAN (VIEW)
                </div>
                <div class="row g-3 section-card">
                    <div class="col-md-3">
                        <div class="label">Total Bulan</div>
                        <div class="value"><?= e($row['total_bulan'] ?? ''); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="label">Bulan 2025</div>
                        <div class="value"><?= e($row['bulan_2025'] ?? ''); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="label">Nilai per Bulan</div>
                        <div class="value"><?= nf($row['nilai_per_bulan'] ?? 0); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="label">Alokasi Kontrak Thn Ini</div>
                        <div class="value"><?= nf($row['alokasi_kontrak_thn_ini'] ?? 0); ?></div>
                    </div>

                    <div class="col-md-3">
                        <div class="label">Anggaran Terpakai</div>
                        <div class="value"><?= nf($row['anggaran_terpakai'] ?? 0); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="label">Jml Byr</div>
                        <div class="value"><?= nf($row['jml_byr'] ?? 0); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="label">Overpay Thn Ini</div>
                        <div class="value"><?= nf($row['overpay_thn_ini'] ?? 0); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="label">Ke Thn 2026</div>
                        <div class="value"><?= nf($row['ke_thn_2026'] ?? 0); ?></div>
                    </div>

                    <div class="col-md-3">
                        <div class="label">Terbayar Thn Ini</div>
                        <div class="value"><?= nf($row['terbayar_thn_ini'] ?? 0); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="label">Terpakai Thn Ini</div>
                        <div class="value"><?= nf($row['terpakai_thn_ini'] ?? 0); ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="label">Sisa Anggaran</div>
                        <div class="value"><?= nf($row['sisa_anggaran'] ?? 0); ?></div>
                    </div>

                    <div class="col-md-6">
                        <div class="label">Jml Rencana Bayar</div>
                        <div class="value"><?= nf($row['jml_renc'] ?? 0); ?></div>
                    </div>
                </div>

                <!-- BULANAN -->
                <div class="section-title mt-4">
                    <i class="fas fa-calendar-alt me-2"></i> BULANAN
                </div>

                <div class="table-responsive section-card p-0">
                    <table class="table table-sm mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th style="width:90px;">Bulan</th>
                                <th class="text-end">Real Bayar</th>
                                <th class="text-end">KTRK</th>
                                <th class="text-end">Rencana Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <tr>
                                    <td><?= e($bulan[$i]); ?></td>
                                    <td class="text-end"><?= nf($row["real_byr_bln{$i}"] ?? 0); ?></td>
                                    <td class="text-end"><?= nf($row["ktrk_bln{$i}"] ?? 0); ?></td>
                                    <td class="text-end"><?= nf($row["renc_byr_bln{$i}"] ?? 0); ?></td>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <th>Total</th>
                                <th class="text-end"><?= nf($row['jml_byr'] ?? 0); ?></th>
                                <th class="text-end"><?= nf(array_sum(array_map(function ($i) use ($row) {
                                                            return (float)($row["ktrk_bln{$i}"] ?? 0);
                                                        }, range(1, 12)))); ?></th>
                                <th class="text-end"><?= nf($row['jml_renc'] ?? 0); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
</main>

<style>
    .bg-gradient-primary {
        background: linear-gradient(90deg, #005C99, #0099CC);
    }

    .section-title {
        font-weight: 700;
        font-size: 13px;
        letter-spacing: .3px;
        color: #344767;
        padding: 10px 0 8px 0;
        border-bottom: 1px solid rgba(0, 0, 0, .08);
        margin-bottom: 10px;
    }

    .section-card {
        background: #fff;
        border: 1px solid rgba(0, 0, 0, .06);
        border-radius: 14px;
        padding: 14px;
    }

    .label {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 4px;
    }

    .value {
        font-size: 14px;
        font-weight: 600;
        color: #212529;
        word-break: break-word;
    }

    .box-lite {
        background: #f8f9fa;
        border: 1px solid rgba(0, 0, 0, .06);
        border-radius: 14px;
        padding: 12px;
    }

    .badge {
        padding: 6px 10px;
        border-radius: 999px;
    }
</style>