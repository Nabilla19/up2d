<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
        <div class="container-fluid py-1 px-3">
            <h6 class="font-weight-bolder text-white mb-0">
                <i class="fas fa-industry me-2 text-danger"></i> Edit Pembangkit
            </h6>
        </div>
    </nav>
    <div class="container-fluid py-4">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-gradient-primary text-white"><strong>Form Edit Pembangkit</strong></div>
            <div class="card-body">
                <form action="<?= base_url('Pembangkit/edit/' . urlencode($pembangkit['ID_PEMBANGKIT'] ?? '')); ?>" method="post">
                    <input type="hidden" name="ID_PEMBANGKIT" value="<?= htmlentities($pembangkit['ID_PEMBANGKIT'] ?? ''); ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Unit Layanan</label>
                            <input type="text" class="form-control" name="UNIT_LAYANAN" value="<?= htmlentities($pembangkit['UNIT_LAYANAN'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pembangkit</label>
                            <input type="text" class="form-control" name="PEMBANGKIT" value="<?= htmlentities($pembangkit['PEMBANGKIT'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Longitude (X)</label>
                            <input type="text" class="form-control" name="LONGITUDEX" value="<?= htmlentities($pembangkit['LONGITUDEX'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Latitude (Y)</label>
                            <input type="text" class="form-control" name="LATITUDEY" value="<?= htmlentities($pembangkit['LATITUDEY'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status Operasi</label>
                            <input type="text" class="form-control" name="STATUS_OPERASI" value="<?= htmlentities($pembangkit['STATUS_OPERASI'] ?? ''); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">INC</label>
                            <input type="text" class="form-control" name="INC" value="<?= htmlentities($pembangkit['INC'] ?? ''); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">OGF</label>
                            <input type="text" class="form-control" name="OGF" value="<?= htmlentities($pembangkit['OGF'] ?? ''); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Spare</label>
                            <input type="text" class="form-control" name="SPARE" value="<?= htmlentities($pembangkit['SPARE'] ?? ''); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Couple</label>
                            <input type="text" class="form-control" name="COUPLE" value="<?= htmlentities($pembangkit['COUPLE'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status SCADA</label>
                            <input type="text" class="form-control" name="STATUS_SCADA" value="<?= htmlentities($pembangkit['STATUS_SCADA'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">IP Gateway</label>
                            <input type="text" class="form-control" name="IP_GATEWAY" value="<?= htmlentities($pembangkit['IP_GATEWAY'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">IP RTU</label>
                            <input type="text" class="form-control" name="IP_RTU" value="<?= htmlentities($pembangkit['IP_RTU'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Merk RTU</label>
                            <input type="text" class="form-control" name="MERK_RTU" value="<?= htmlentities($pembangkit['MERK_RTU'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">SN RTU</label>
                            <input type="text" class="form-control" name="SN_RTU" value="<?= htmlentities($pembangkit['SN_RTU'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tahun Integrasi</label>
                            <input type="text" class="form-control" name="THN_INTEGRASI" value="<?= htmlentities($pembangkit['THN_INTEGRASI'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="<?= base_url('Pembangkit'); ?>" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
