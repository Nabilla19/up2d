<div class="main-content position-relative border-radius-lg">
    <section class="section">
        <div class="section-body">
            <div class="container-fluid py-4">

                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card shadow border-0 rounded-4">

                            <div class="card-header bg-gradient-primary text-white text-center rounded-top-4">
                                <h6 class="mb-0 text-white">
                                    <i class="fas fa-folder-open me-2 text-warning"></i> Detail Rekap PRK
                                </h6>
                            </div>

                            <div class="card-body">

                                <?php
                                $fields = [
                                    'JENIS_ANGGARAN' => 'Jenis Anggaran',
                                    'NOMOR_PRK'      => 'Nomor PRK',
                                    'URAIAN_PRK'     => 'Uraian PRK',
                                    'PAGU_SKK_IO'    => 'Pagu SKK IO',
                                    'RENC_KONTRAK'   => 'Rencana Kontrak',
                                    'NODIN_SRT'      => 'NODIN / Surat',
                                    'KONTRAK'        => 'Kontrak',
                                    'SISA'           => 'Sisa',
                                    'RENCANA_BAYAR'  => 'Rencana Bayar',
                                    'TERBAYAR'       => 'Terbayar',
                                    'KE_TAHUN_2026'  => 'Ke Tahun 2026'
                                ];
                                ?>

                                <div class="row">
                                    <?php foreach ($fields as $key => $label): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="detail-item">
                                                <span class="label"><?= $label; ?></span>
                                                <p class="value">
                                                    <?= htmlspecialchars($rekap[$key] ?? '-'); ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                            </div>

                            <div class="card-footer text-center bg-light border-top">
                                <a href="<?= base_url('rekap_prk'); ?>" class="btn btn-danger px-4">
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
        word-break: break-word;
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