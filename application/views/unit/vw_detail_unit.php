<div class="main-content position-relative border-radius-lg">
    <section class="section">
        <div class="section-body">
            <div class="container-fluid py-4">
                <h1 class="h3 mb-4 text-gray-800"><?= $title ?? 'Detail Unit'; ?></h1>

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="card shadow border-0 rounded-4">
                            <div class="card-header bg-gradient-primary text-white text-center rounded-top-4">
                                <h6 class="mb-0 text-white">
                                    <i class="fas fa-building me-2 text-white"></i> Detail Unit
                                </h6>
                            </div>

                            <div class="card-body">
                                <!-- UNIT PELAKSANA & UNIT LAYANAN -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <span class="label">Unit Pelaksana</span>
                                            <p class="value"><?= htmlspecialchars($unit['UNIT_PELAKSANA'] ?? '-'); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <span class="label">Unit Layanan</span>
                                            <p class="value"><?= htmlspecialchars($unit['UNIT_LAYANAN'] ?? '-'); ?></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- KOORDINAT -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <span class="label">Longitude (X)</span>
                                            <p class="value"><?= htmlspecialchars($unit['LONGITUDEX'] ?? '-'); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <span class="label">Latitude (Y)</span>
                                            <p class="value"><?= htmlspecialchars($unit['LATITUDEY'] ?? '-'); ?></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- ALAMAT -->
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="detail-item">
                                            <span class="label">Alamat</span>
                                            <p class="value"><?= nl2br(htmlspecialchars($unit['ADDRESS'] ?? '-')); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-center bg-light border-top">
                                <a href="<?= base_url('Unit') ?>" class="btn btn-danger px-4">
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