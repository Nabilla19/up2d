<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>
    <!-- NAVBAR -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl"
        id="navbarBlur" data-scroll="false">
        <div class="container-fluid py-1 px-3">
            <h6 class="font-weight-bolder text-white mb-0">
                <i class="fas fa-building me-2 text-success"></i> Tambah Gardu Induk
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

                        <!-- Kolom sesuai tabel -->

                        <div class="col-md-6">
                            <label class="form-label">UP3 2D</label>
                            <select name="UP3_2D" class="form-control" required>
                                <option value="" disabled selected>-- Pilih UP3 2D --</option>
                                <option value="UP2D.6456">UP2D.6456</option>
                                <option value="UP3.6411">UP3.6411</option>
                                <option value="UP3.6412">UP3.6412</option>
                                <option value="UP3.6413">UP3.6413</option>
                                <option value="UP3.6414">UP3.6414</option>
                                <option value="UP3.6415">UP3.6415</option>
                                <option value="UPT.3217">UPT.3217</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Unit Name UP3</label>
                            <select name="UNITNAME_UP3" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Unit Name UP3 --</option>
                                <option value="TANJUNG PINANG">TANJUNG PINANG</option>
                                <option value="PEKANBARU">PEKANBARU</option>
                                <option value="DUMAI">DUMAI</option>
                                <option value="RENGAT">RENGAT</option>
                                <option value="BANGKINANG">BANGKINANG</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">CX Unit</label>
                            <input type="text" name="CXUNIT" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Unit Name</label>
                            <!-- DIGANTI: UNITNAME dropdown khusus GI -->
                            <select name="UNITNAME" class="form-control">
                                <option value="" disabled selected>-- Pilih Unit Name --</option>
                                <option value="UP2D RIAU">UP2D RIAU</option>
                                <option value="PEKANBARU">PEKANBARU</option>
                                <option value="SIAK SRI INDRAPURA">SIAK SRI INDRAPURA</option>
                                <option value="DUMAI">DUMAI</option>
                                <option value="BELAKANGPADANG">BELAKANGPADANG</option>
                                <option value="KIJANG">KIJANG</option>
                                <option value="TANJUNG BALAI KARIMUN">TANJUNG BALAI KARIMUN</option>
                                <option value="TANJUNG BATU">TANJUNG BATU</option>
                                <option value="TANJUNG PINANG">TANJUNG PINANG</option>
                                <option value="RENGAT">RENGAT</option>
                                <option value="BANGKINANG">BANGKINANG</option>
                                <option value="UNIT PELAKSANA TRANSMISI PEKANBARU">UNIT PELAKSANA TRANSMISI PEKANBARU</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Location</label>
                            <input type="text" name="LOCATION" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">SSOT Number</label>
                            <input type="text" name="SSOTNUMBER" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Description</label>
                            <input type="text" name="DESCRIPTION" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <!-- DIGANTI: dropdown STATUS GI -->
                            <select name="STATUS" class="form-control">
                                <option value="" disabled selected>-- Pilih Status --</option>
                                <option value="OPERATING">OPERATING</option>
                                <option value="INACTIVE">INACTIVE</option>
                                <option value="NOT READY">NOT READY</option>
                                <option value="REQOPERATING">REQOPERATING</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">TUJD Number</label>
                            <input type="text" name="TUJDNUMBER" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Asset Class HI</label>
                            <input type="text" name="ASSETCLASSHI" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">S Address Code</label>
                            <input type="text" name="SADDRESSCODE" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Classification Desc</label>
                            <input type="text" name="CXCLASSIFICATIONDESC" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Penyulang</label>
                            <input type="text" name="PENYULANG" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Parent</label>
                            <input type="text" name="PARENT" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Parent Description</label>
                            <input type="text" name="PARENT_DESCRIPTION" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Install Date</label>
                            <input type="text" name="INSTALLDATE" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Actual Opr Date</label>
                            <input type="text" name="ACTUALOPRDATE" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Change Date</label>
                            <input type="text" name="CHANGEDATE" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Change By</label>
                            <input type="text" name="CHANGEBY" class="form-control">
                        </div>

                        <!-- Koordinat -->
                        <div class="col-md-3">
                            <label class="form-label">Latitude (Y)</label>
                            <input type="text" name="LATITUDEY" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Longitude (X)</label>
                            <input type="text" name="LONGITUDEX" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Formatted Address</label>
                            <input type="text" name="FORMATTEDADDRESS" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Street Address</label>
                            <input type="text" name="STREETADDRESS" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" name="CITY" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Is Asset</label>
                            <input type="text" name="ISASSET" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Status Kepemilikan</label>
                            <!-- DIGANTI: dropdown STATUS_KEPEMILIKAN -->
                            <select name="STATUS_KEPEMILIKAN" class="form-control">
                                <option value="" disabled selected>-- Pilih Status Kepemilikan --</option>
                                <option value="PLN">PLN</option>
                                <option value="NON PLN">NON PLN</option>
                            </select>
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
