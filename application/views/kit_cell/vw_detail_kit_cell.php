<div class="main-content position-relative border-radius-lg">
    <section class="section">
        <div class="section-body">
            <div class="container-fluid py-4">
                <h1 class="h3 mb-4 text-gray-800"><?= $title ?? 'Detail Penyulang'; ?></h1>

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="card shadow border-0 rounded-4">
                            <div class="card-header bg-gradient-primary text-white text-center rounded-top-4">
                                <h6 class="mb-0 text-white">
                                    <i class="fas fa-microchip me-2 text-white"></i> Detail Penyulang
                                </h6>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <?php
                                    $fields = [
                                        'SSOTNUMBER' => 'SSOT Number',
                                        'CXUNIT' => 'Kode Unit',
                                        'UNITNAME' => 'Nama Unit',
                                        'LOCATION' => 'Lokasi',
                                        'DESCRIPTION' => 'Deskripsi',
                                        'VENDOR' => 'Vendor',
                                        'MANUFACTURER' => 'Manufacturer',
                                        'INSTALLDATE' => 'Tanggal Pasang',
                                        'PRIORITY' => 'Prioritas',
                                        'STATUS' => 'Status',
                                        'TUJDNUMBER' => 'Nomor TUJD',
                                        'CHANGEBY' => 'Diubah Oleh',
                                        'CHANGEDATE' => 'Tanggal Perubahan',
                                        'CXCLASSIFICATIONDESC' => 'Klasifikasi',
                                        'CXPENYULANG' => 'Kode Penyulang',
                                        'NAMA_LOCATION' => 'Nama Lokasi',
                                        'LONGITUDEX' => 'Longitude (X)',
                                        'LATITUDEY' => 'Latitude (Y)',
                                        'ISASSET' => 'Status Aset',
                                        'STATUS_KEPEMILIKAN' => 'Status Kepemilikan',
                                        'BURDEN' => 'Burden',
                                        'FAKTOR_KALI' => 'Faktor Kali',
                                        'JENIS_CT' => 'Jenis CT',
                                        'KELAS_CT' => 'Kelas CT',
                                        'KELAS_PROTEKSI' => 'Kelas Proteksi',
                                        'PRIMER_SEKUNDER' => 'Primer/Sekunder',
                                        'TIPE_CT' => 'Tipe CT',
                                        'OWNERSYSID' => 'Owner Sys ID',
                                        'ISOLASI_KUBIKEL' => 'Isolasi Kubikel',
                                        'JENIS_MVCELL' => 'Jenis MV Cell',
                                        'TH_BUAT' => 'Tahun Buat',
                                        'TYPE_MVCELL' => 'Type MV Cell',
                                        'CELL_TYPE' => 'Cell Type'
                                    ];

                                    $i = 0;
                                    foreach ($fields as $key => $label):
                                        // buat layout 2 kolom
                                        if ($i % 2 == 0) echo '<div class="row mb-3">';
                                    ?>
                                        <div class="col-md-6">
                                            <div class="detail-item">
                                                <span class="label"><?= $label; ?></span>
                                                <p class="value"><?= htmlspecialchars($kit_cell[$key] ?? '-', ENT_QUOTES, 'UTF-8'); ?></p>
                                            </div>
                                        </div>
                                    <?php
                                        if ($i % 2 == 1) echo '</div>';
                                        $i++;
                                    endforeach;

                                    // tutup baris ganjil terakhir jika belum ditutup
                                    if ($i % 2 != 0) echo '</div>';
                                    ?>

                                    <!-- Relasi ke Pembangkit -->
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <div class="detail-item">
                                                <span class="label">ID Pembangkit (Relasi)</span>
                                                <p class="value">
                                                    <?php if (!empty($kit_cell['ID_PEMBANGKIT'])): ?>
                                                        <a href="<?= base_url('Pembangkit/detail/' . urlencode($kit_cell['ID_PEMBANGKIT'])); ?>" class="text-primary">
                                                            [<?= htmlspecialchars($kit_cell['ID_PEMBANGKIT']); ?>] Lihat Pembangkit
                                                        </a>
                                                    <?php else: ?>
                                                        <em class="text-muted">Tidak terhubung</em>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-center bg-light border-top">
                                <a href="<?= base_url('Kit_cell') ?>" class="btn btn-danger px-4">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- STYLE -->
<style>
    .form-label,
    .label {
        font-weight: 600;
        color: #2c3e50;
    }

    .value {
        color: #333;
        margin: 0;
    }

    .detail-item {
        background: #f8f9fc;
        border-radius: 10px;
        padding: 10px 15px;
        margin-bottom: 10px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .card-header {
        background: linear-gradient(90deg, #007bff, #0056d2);
        font-weight: 600;
        font-size: 17px;
        padding: 14px;
        border-top-left-radius: 14px;
        border-top-right-radius: 14px;
    }

    .card-footer {
        border-top: 1px solid #e0e0e0;
        padding: 18px;
        border-radius: 0 0 14px 14px;
    }

    .btn {
        border-radius: 10px;
    }

    .btn-secondary {
        background-color: #6c757d;
        border: none;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }
</style>