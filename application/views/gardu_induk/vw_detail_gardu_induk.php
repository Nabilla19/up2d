<div class="main-content position-relative border-radius-lg">
    <section class="section">
        <div class="section-body">
            <div class="container-fluid py-4">
                <h1 class="h3 mb-4 text-gray-800"><?= $judul; ?></h1>

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="card shadow border-0 rounded-4">
                            <div class="card-header bg-gradient-primary text-white text-center rounded-top-4">
                                <h6 class="mb-0 text-white">
                                    <i class="fas fa-bolt me-2 text-warning"></i> Detail Gardu Induk
                                </h6>
                            </div>

                            <div class="card-body">
                                <?php
                                $fields = [
                                    'UP3_2D' => 'UP3 2D',
                                    'UNITNAME_UP3' => 'Unit UP3',
                                    'CXUNIT' => 'Kode Unit',
                                    'UNITNAME' => 'Nama Unit',
                                    'LOCATION' => 'Lokasi',
                                    'SSOTNUMBER' => 'Nomor SSOT',
                                    'DESCRIPTION' => 'Deskripsi',
                                    'STATUS' => 'Status',
                                    'TUJDNUMBER' => 'Nomor TUJD',
                                    'ASSETCLASSHI' => 'Kelas Aset',
                                    'SADDRESSCODE' => 'Kode Alamat',
                                    'CXCLASSIFICATIONDESC' => 'Klasifikasi',
                                    'PENYULANG' => 'Penyulang',
                                    'PARENT' => 'Induk (Parent)',
                                    'PARENT_DESCRIPTION' => 'Deskripsi Induk',
                                    'INSTALLDATE' => 'Tanggal Pasang',
                                    'ACTUALOPRDATE' => 'Tanggal Operasi',
                                    'CHANGEDATE' => 'Tanggal Perubahan',
                                    'CHANGEBY' => 'Diubah Oleh',
                                    'LATITUDEY' => 'Latitude (Y)',
                                    'LONGITUDEX' => 'Longitude (X)',
                                    'FORMATTEDADDRESS' => 'Alamat Lengkap',
                                    'STREETADDRESS' => 'Alamat Jalan',
                                    'CITY' => 'Kota',
                                    'ISASSET' => 'Status Aset',
                                    'STATUS_KEPEMILIKAN' => 'Status Kepemilikan'
                                ];
                                ?>

                                <!-- Menampilkan data dengan tampilan rapi -->
                                <div class="row">
                                    <?php foreach ($fields as $key => $label): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="detail-item">
                                                <span class="label"><?= $label; ?></span>
                                                <p class="value"><?= htmlspecialchars($gardu_induk[$key] ?? '-'); ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="card-footer text-center bg-light border-top">
                                <a href="<?= base_url('Gardu_induk') ?>" class="btn btn-danger px-4">
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
    .detail-item {
        background: #f8f9fc;
        border-radius: 10px;
        padding: 12px 15px;
        margin-bottom: 10px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .label {
        font-weight: 600;
        color: #2c3e50;
        display: block;
        margin-bottom: 3px;
    }

    .value {
        color: #333;
        margin: 0;
    }

    .card-header {
        background: linear-gradient(90deg, #007bff, #0056d2);
        font-weight: 600;
        font-size: 17px;
        padding: 14px;
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