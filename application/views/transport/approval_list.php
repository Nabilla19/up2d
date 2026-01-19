<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-radius-xl">
                <div class="card-header pb-0 p-3">
                    <div class="row">
                        <div class="col-6 d-flex align-items-center">
                            <h6 class="mb-0 text-uppercase font-weight-bolder">Antrian Persetujuan Asmen / KKU</h6>
                        </div>
                        <div class="col-6 text-end">
                            <span class="badge badge-sm bg-gradient-primary">Menunggu: <?= count($requests) ?></span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <?php if (empty($requests)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                            <h6 class="text-secondary">Semua permohonan telah diproses.</h6>
                        </div>
                    <?php else: ?>
                        <div class="row">
                        <?php foreach ($requests as $r): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card border border-radius-lg shadow-none h-100">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="icon icon-shape icon-sm bg-gradient-info shadow text-center border-radius-md me-2">
                                                    <i class="fas fa-user text-white opacity-10"></i>
                                                </div>
                                                <div>
                                                    <h6 class="text-sm font-weight-bold mb-0"><?= $r['nama'] ?></h6>
                                                    <p class="text-xxs text-secondary mb-0"><?= $r['bagian'] ?></p>
                                                </div>
                                            </div>
                                            <span class="badge badge-xs bg-gradient-warning">Pending</span>
                                        </div>
                                        
                                        <div class="bg-gray-100 border-radius-lg p-3 mb-3">
                                            <div class="d-flex mb-2">
                                                <span class="text-xxs font-weight-bold text-uppercase text-secondary me-2" style="min-width: 60px;">Tujuan</span>
                                                <span class="text-xs font-weight-bolder text-dark"><?= $r['tujuan'] ?></span>
                                            </div>
                                            <div class="d-flex mb-2">
                                                <span class="text-xxs font-weight-bold text-uppercase text-secondary me-2" style="min-width: 60px;">Keperluan</span>
                                                <span class="text-xs text-dark"><?= $r['keperluan'] ?></span>
                                            </div>
                                            <div class="d-flex">
                                                <span class="text-xxs font-weight-bold text-uppercase text-secondary me-2" style="min-width: 60px;">Waktu</span>
                                                <span class="text-xs font-weight-bolder text-primary">
                                                    <i class="fas fa-calendar-alt me-1"></i><?= date('d M Y, H:i', strtotime($r['tanggal_jam_berangkat'])) ?>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end align-items-center">
                                            <a href="<?= base_url('transport/detail/'.$r['id']) ?>" class="btn btn-outline-info btn-sm mb-0">
                                                <i class="fas fa-search me-1"></i> Review Permohonan
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
