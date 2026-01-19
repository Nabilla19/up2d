<div class="main-content position-relative border-radius-lg">
    <section class="section">
        <div class="section-body">
            <div class="container-fluid py-4">

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="card shadow border-0 rounded-4">
                            <div class="card-header bg-gradient-primary text-white text-center rounded-top-4">
                                <h6 class="mb-0 text-white">
                                    <i class="fas fa-file-signature me-2 text-info"></i> Detail Input Kontrak
                                </h6>
                            </div>

                            <div class="card-body">
                                <?php
                                $fields = [
                                    'SUMBER_DANA' => 'Sumber Dana',
                                    'SKKO' => 'SKKO',
                                    'SUB_POS' => 'Sub Pos',
                                    'DRP' => 'DRP',
                                    'URAIAN_KONTRAK_PEKERJAAN' => 'Uraian Kontrak / Pekerjaan',
                                    'USER' => 'User',
                                    'PAGU_ANG_RAB_USER' => 'Pagu Ang/RAB User',
                                    'KOMITMENT_ND' => 'Komitment ND',
                                    'RENC_AKHIR_KONTRAK' => 'Rencana Akhir Kontrak',
                                    'TGL_ND_AMS' => 'Tanggal ND/AMS',
                                    'NOMOR_ND_AMS' => 'Nomor ND / AMS',
                                    'KETERANGAN' => 'Keterangan',
                                    'TAHAP_KONTRAK' => 'Tahap Kontrak',
                                    'PROGNOSA' => 'Prognosa',
                                    'NO_SPK_SPB_KONTRAK' => 'No SPK / SPB / Kontrak',
                                    'REKANAN' => 'Rekanan',
                                    'TGL_KONTRAK' => 'Tanggal Kontrak',
                                    'TGL_AKHIR_KONTRAK' => 'Tanggal Akhir Kontrak',
                                    'NILAI_KONTRAK_TOTAL' => 'Nilai Kontrak Total',
                                    'NILAI_KONTRAK_TAHUN_BERJALAN' => 'Nilai Kontrak Tahun Berjalan',
                                    'TGL_BAYAR' => 'Tanggal Bayar',
                                    'ANGGARAN_TERPAKAI' => 'Anggaran Terpakai',
                                    'SISA_ANGGARAN' => 'Sisa Anggaran',
                                    'STATUS_KONTRAK' => 'Status Kontrak',
                                    'BULAN_RENC_BAYAR' => 'Bulan Rencana Bayar',
                                    'BULAN_BAYAR' => 'Bulan Bayar'
                                ];

                                // Tambahkan BLN KTRK1-12
                                for ($i = 1; $i <= 12; $i++) {
                                    $fields["BLN_KTRK$i"] = "Bulan KTRK$i";
                                }
                                ?>

                                <div class="row">
                                    <?php foreach ($fields as $key => $label): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="detail-item">
                                                <span class="label"><?= $label; ?></span>
                                                <p class="value"><?= htmlspecialchars($input_kontrak[$key] ?? '-'); ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="card-footer text-center bg-light border-top">
                                <a href="<?= base_url('Input_kontrak'); ?>" class="btn btn-danger px-4">
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