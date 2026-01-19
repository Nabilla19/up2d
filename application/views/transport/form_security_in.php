<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Security Check-In (Berangkat)</h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('transport/security_checkin/'.$request['id']) ?>" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">KM Awal</label>
                                    <input class="form-control" type="number" name="km_awal" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Jam Berangkat</label>
                                    <input class="form-control" type="time" name="jam_berangkat" value="<?= date('H:i') ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Foto Driver + Mobil (Berangkat)</label>
                            <input class="form-control" type="file" name="foto_driver_berangkat" accept="image/*" required>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Foto KM (Berangkat)</label>
                            <input class="form-control" type="file" name="foto_km_berangkat" accept="image/*" required>
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-danger btn-sm">Simpan & Berangkatkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
