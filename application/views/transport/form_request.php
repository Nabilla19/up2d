<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary pb-5 pt-4">
                    <div class="d-flex align-items-center justify-content-between text-white">
                        <div>
                            <h5 class="mb-0 text-white font-weight-bolder">Pengajuan Kendaraan</h5>
                            <p class="text-sm opacity-8">Lengkapi data perjalanan Anda dengan benar.</p>
                        </div>
                        <i class="fas fa-car-side fa-3x opacity-5"></i>
                    </div>
                </div>
                <div class="card-body mt-n5">
                    <div class="card shadow-sm border-0 border-radius-xl">
                        <div class="card-body">
                            <form action="<?= base_url('transport/simpan') ?>" method="post">
                                <h6 class="text-uppercase text-primary text-xs font-weight-bolder mb-3">Informasi Pemohon</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Nama Lengkap</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                <input class="form-control" type="text" name="nama" value="<?= $this->session->userdata('user_name') ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Jabatan</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                                <input class="form-control" type="text" name="jabatan" placeholder="Contoh: Manager / Supervisor" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Bagian / Bidang</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-users-gear"></i></span>
                                                <input class="form-control" type="text" name="bagian" placeholder="Contoh: Operasi / Pemeliharaan" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Macam Kendaraan</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-car-side"></i></span>
                                                <select class="form-control" name="macam_kendaraan" required>
                                                    <option value="" selected disabled>-- Pilih Jenis --</option>
                                                    <?php foreach($vehicle_types as $vt): ?>
                                                        <option value="<?= $vt['type_name'] ?>"><?= $vt['type_name'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Jumlah Penumpang</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-users"></i></span>
                                                <input class="form-control" type="number" name="jumlah_penumpang" placeholder="0" min="0" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="horizontal dark my-4">
                                <h6 class="text-uppercase text-primary text-xs font-weight-bolder mb-3">Detail Perjalanan & Keperluan</h6>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Lokasi Tujuan</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-location-arrow"></i></span>
                                                <input class="form-control" type="text" name="tujuan" placeholder="Contoh: Kantor Wilayah / PLN ULP..." required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Deskripsi Keperluan</label>
                                            <textarea class="form-control" name="keperluan" rows="3" placeholder="Sebutkan alasan atau aktivitas yang akan dilakukan..." required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Waktu Berangkat</label>
                                            <input class="form-control" type="datetime-local" name="tanggal_jam_berangkat" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Estimasi Durasi Pakai</label>
                                            <input class="form-control" type="text" name="lama_pakai" placeholder="Contoh: 3 Jam / 1 Hari">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                    <a href="<?= base_url('transport/semua_daftar') ?>" class="text-secondary text-sm font-weight-bold">
                                        <i class="fas fa-arrow-left me-1"></i> Batal & Kembali
                                    </a>
                                    <button type="submit" class="btn bg-gradient-primary shadow-primary px-5 mb-0">Kirim Permohonan <i class="fas fa-paper-plane ms-2"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
