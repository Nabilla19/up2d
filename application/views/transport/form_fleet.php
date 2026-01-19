<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Form Input Fleet / Surat Jalan</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info py-2 text-white text-xs">
                        <strong>Pemohon:</strong> <?= $request['nama'] ?> (<?= $request['bagian'] ?>)<br>
                        <strong>Tujuan:</strong> <?= $request['tujuan'] ?><br>
                        <strong>Pilihan Brand:</strong> <span class="badge bg-white text-info"><?= $request['macam_kendaraan'] ?></span>
                    </div>
                    <form action="<?= base_url('transport/fleet_process/'.$request['id']) ?>" method="post">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label class="form-control-label">Nama Mobil</label>
                                    <select class="form-control" name="mobil" id="mobil_select" required onchange="syncPlat()">
                                        <option value="" selected disabled>-- Pilih Mobil --</option>
                                        <?php if(empty($vehicles)): ?>
                                            <option value="" disabled>TIDAK ADA MOBIL TERSEDIA</option>
                                        <?php else: ?>
                                            <?php foreach($vehicles as $v): ?>
                                                <option value="<?= $v['nama_mobil'] ?>"><?= $v['nama_mobil'] ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-control-label">Plat Nomor</label>
                                    <select class="form-control" name="plat_nomor" id="plat_select" required onchange="syncMobil()">
                                        <option value="" selected disabled>-- Plat --</option>
                                        <?php if(empty($vehicles)): ?>
                                            <option value="" disabled>TIDAK ADA MOBIL TERSEDIA</option>
                                        <?php else: ?>
                                            <?php foreach($vehicles as $v): ?>
                                                <option value="<?= $v['plat_nomor'] ?>"><?= $v['plat_nomor'] ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <script>
                            const vehicleMap = {
                                <?php foreach($vehicles as $v): ?>
                                    "<?= $v['nama_mobil'] ?>": "<?= $v['plat_nomor'] ?>",
                                <?php endforeach; ?>
                            };

                            const plateMap = {
                                <?php foreach($vehicles as $v): ?>
                                    "<?= $v['plat_nomor'] ?>": "<?= $v['nama_mobil'] ?>",
                                <?php endforeach; ?>
                            };

                            function syncPlat() {
                                const mobil = document.getElementById("mobil_select").value;
                                if (vehicleMap[mobil]) {
                                    document.getElementById("plat_select").value = vehicleMap[mobil];
                                }
                            }

                            function syncMobil() {
                                const plat = document.getElementById("plat_select").value;
                                if (plateMap[plat]) {
                                    document.getElementById("mobil_select").value = plateMap[plat];
                                }
                            }
                        </script>
                        <div class="form-group">
                            <label class="form-control-label">Nama Pengemudi</label>
                            <input class="form-control" type="text" name="pengemudi" required>
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <a href="<?= base_url('transport/fleet') ?>" class="btn btn-secondary btn-sm me-2">Batal</a>
                            <button type="submit" class="btn btn-primary btn-sm">Simpan Data Fleet</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
