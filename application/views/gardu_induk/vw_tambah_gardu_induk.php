<main class="main-content position-relative border-radius-lg ">
    <!-- NAVBAR -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" 
         id="navbarBlur" data-scroll="false">
        <div class="container-fluid py-1 px-3">
            <h6 class="font-weight-bolder text-white mb-0">
                <i class="fas fa-bolt me-2 text-warning"></i> Tambah Gardu Induk
            </h6>
        </div>
    </nav>

    <!-- CONTENT -->
    <div class="container-fluid py-4">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-gradient-primary text-white">
                <strong>Form Tambah Gardu Induk</strong>
            </div>

            <div class="card-body">
                <form action="<?= base_url('Gardu_induk/tambah'); ?>" method="POST">

                    <div class="row g-3">

                        <!-- Unit Layanan -->
                        <div class="col-md-6">
                            <label class="form-label">Unit Layanan</label>
                            <input type="text" name="UNIT_LAYANAN" class="form-control" required>
                        </div>

                        <!-- Nama Gardu -->
                        <div class="col-md-6">
                            <label class="form-label">Nama Gardu Induk</label>
                            <input type="text" name="GARDU_INDUK" class="form-control" required>
                        </div>

                        <!-- Koordinat -->
                        <div class="col-md-3">
                            <label class="form-label">Longitude (X)</label>
                            <input type="text" name="LONGITUDEX" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Latitude (Y)</label>
                            <input type="text" name="LATITUDEY" class="form-control">
                        </div>

                        <!-- Status Operasi -->
                        <div class="col-md-6">
                            <label class="form-label">Status Operasi</label>
                            <select name="STATUS_OPERASI" class="form-control">
                                <option value="">-- Pilih Status --</option>
                                <option value="OPERATING">OPERATING</option>
                                <option value="NOT READY">NOT READY</option>
                                <option value="INACTIVE">INACTIVE</option>
                                <option value="REQOPERATING">REQOPERATING</option>
                            </select>
                        </div>

                        <!-- Data teknis -->
                        <div class="col-md-2">
                            <label class="form-label">Jumlah TD</label>
                            <input type="number" name="JML_TD" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">INC</label>
                            <input type="number" name="INC" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">OGF</label>
                            <input type="number" name="OGF" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Spare</label>
                            <input type="number" name="SPARE" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Couple</label>
                            <input type="number" name="COUPLE" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Bus Riser</label>
                            <input type="number" name="BUS_RISER" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">BBVT</label>
                            <input type="number" name="BBVT" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">PS</label>
                            <input type="number" name="PS" class="form-control">
                        </div>

                        <!-- Status SCADA -->
                        <div class="col-md-4">
                            <label class="form-label">Status SCADA</label>
                            <select name="STATUS_SCADA" class="form-control">
                                <option value="">-- Pilih Status SCADA --</option>
                                <option value="INTEGRASI">INTEGRASI</option>
                                <option value="NON INTEGRASI">NON INTEGRASI</option>
                            </select>
                        </div>

                        <!-- IP & RTU -->
                        <div class="col-md-4">
                            <label class="form-label">IP Gateway</label>
                            <input type="text" name="IP_GATEWAY" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">IP RTU</label>
                            <input type="text" name="IP_RTU" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Merk RTU</label>
                            <input type="text" name="MERK_RTU" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">SN RTU</label>
                            <input type="text" name="SN_RTU" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tahun Integrasi</label>
                            <input type="text" name="THN_INTEGRASI" class="form-control">
                        </div>
                    </div>

                    <!-- BUTTON -->
                    <div class="mt-4">
                        <a href="<?= base_url('Gardu_induk'); ?>" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Data
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</main>

<style>
    .form-control {
        border-radius: 8px;
    }
    .btn {
        border-radius: 6px;
        padding: 8px 18px;
    }
</style>
