<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
        <div class="container-fluid py-1 px-3">
            <h6 class="font-weight-bolder text-white mb-0">
                <i class="fas fa-bolt me-2"></i> Ubah Gardu Induk
            </h6>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-gradient-primary text-white">
                <strong>Form Ubah Data Gardu Induk</strong>
            </div>
            <div class="card-body">
                <form action="<?= base_url('gardu_induk/update'); ?>" method="POST">
                    <input type="hidden" name="ID_GI" value="<?= htmlentities($gardu_induk['ID_GI'] ?? $gardu_induk['SSOTNUMBER'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="original_SSOTNUMBER" value="<?= htmlentities($gardu_induk['SSOTNUMBER'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">SSOTNUMBER</label>
                            <input type="text" class="form-control" name="SSOTNUMBER" value="<?= htmlentities($gardu_induk['SSOTNUMBER'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">UP3_2D</label>
                            <input type="text" class="form-control" name="UP3_2D" value="<?= htmlentities($gardu_induk['UP3_2D'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">UNITNAME_UP3</label>
                            <input type="text" class="form-control" name="UNITNAME_UP3" value="<?= htmlentities($gardu_induk['UNITNAME_UP3'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">CXUNIT</label>
                            <input type="text" class="form-control" name="CXUNIT" value="<?= htmlentities($gardu_induk['CXUNIT'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">UNITNAME</label>
                            <input type="text" class="form-control" name="UNITNAME" value="<?= htmlentities($gardu_induk['UNITNAME'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">LOCATION</label>
                            <input type="text" class="form-control" name="LOCATION" value="<?= htmlentities($gardu_induk['LOCATION'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">DESCRIPTION</label>
                            <input type="text" class="form-control" name="DESCRIPTION" value="<?= htmlentities($gardu_induk['DESCRIPTION'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">STATUS</label>
                            <input type="text" class="form-control" name="STATUS" value="<?= htmlentities($gardu_induk['STATUS'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">TUJDNUMBER</label>
                            <input type="text" class="form-control" name="TUJDNUMBER" value="<?= htmlentities($gardu_induk['TUJDNUMBER'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">ASSETCLASSHI</label>
                            <input type="text" class="form-control" name="ASSETCLASSHI" value="<?= htmlentities($gardu_induk['ASSETCLASSHI'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">SADDRESSCODE</label>
                            <input type="text" class="form-control" name="SADDRESSCODE" value="<?= htmlentities($gardu_induk['SADDRESSCODE'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">CXCLASSIFICATIONDESC</label>
                            <input type="text" class="form-control" name="CXCLASSIFICATIONDESC" value="<?= htmlentities($gardu_induk['CXCLASSIFICATIONDESC'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">PENYULANG</label>
                            <input type="text" class="form-control" name="PENYULANG" value="<?= htmlentities($gardu_induk['PENYULANG'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">PARENT</label>
                            <input type="text" class="form-control" name="PARENT" value="<?= htmlentities($gardu_induk['PARENT'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">PARENT_DESCRIPTION</label>
                            <input type="text" class="form-control" name="PARENT_DESCRIPTION" value="<?= htmlentities($gardu_induk['PARENT_DESCRIPTION'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">INSTALLDATE</label>
                            <input type="date" class="form-control" name="INSTALLDATE" value="<?= htmlentities($gardu_induk['INSTALLDATE'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">ACTUALOPRDATE</label>
                            <input type="date" class="form-control" name="ACTUALOPRDATE" value="<?= htmlentities($gardu_induk['ACTUALOPRDATE'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">CHANGEDATE</label>
                            <input type="text" class="form-control" name="CHANGEDATE" value="<?= htmlentities($gardu_induk['CHANGEDATE'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">CHANGEBY</label>
                            <input type="text" class="form-control" name="CHANGEBY" value="<?= htmlentities($gardu_induk['CHANGEBY'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">LONGITUDEX</label>
                            <input type="text" class="form-control" name="LONGITUDEX" placeholder="Contoh: 106.8456" value="<?= htmlentities($gardu_induk['LONGITUDEX'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">LATITUDEY</label>
                            <input type="text" class="form-control" name="LATITUDEY" placeholder="Contoh: -6.2088" value="<?= htmlentities($gardu_induk['LATITUDEY'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">FORMATTEDADDRESS</label>
                            <input type="text" class="form-control" name="FORMATTEDADDRESS" value="<?= htmlentities($gardu_induk['FORMATTEDADDRESS'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">STREETADDRESS</label>
                            <input type="text" class="form-control" name="STREETADDRESS" value="<?= htmlentities($gardu_induk['STREETADDRESS'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">CITY</label>
                            <input type="text" class="form-control" name="CITY" value="<?= htmlentities($gardu_induk['CITY'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">ISASSET</label>
                            <input type="text" class="form-control" name="ISASSET" value="<?= htmlentities($gardu_induk['ISASSET'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">STATUS_KEPEMILIKAN</label>
                            <input type="text" class="form-control" name="STATUS_KEPEMILIKAN" value="<?= htmlentities($gardu_induk['STATUS_KEPEMILIKAN'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="<?= base_url('gardu_induk') ?>" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary float-end">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>