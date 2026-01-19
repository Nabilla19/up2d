<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
        <div class="container-fluid py-1 px-3">
            <h6 class="font-weight-bolder text-white mb-0">
                <i class="fas fa-building me-2 text-success"></i> Edit Unit
            </h6>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-gradient-primary text-white"><strong>Form Edit Unit</strong></div>
            <div class="card-body">

                <form action="<?= base_url('Unit/edit/' . urlencode($unit['ID_UNIT'] ?? '')); ?>" method="post">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Unit Pelaksana</label>
                            <select class="form-control" name="UNIT_PELAKSANA" required>
                                <option value="" disabled>-- Pilih Unit Pelaksana --</option>

                                <option value="TANJUNG PINANG"
                                    <?= (isset($unit['UNIT_PELAKSANA']) && $unit['UNIT_PELAKSANA'] == 'TANJUNG PINANG') ? 'selected' : '' ?>>
                                    TANJUNG PINANG
                                </option>

                                <option value="PEKANBARU"
                                    <?= (isset($unit['UNIT_PELAKSANA']) && $unit['UNIT_PELAKSANA'] == 'PEKANBARU') ? 'selected' : '' ?>>
                                    PEKANBARU
                                </option>

                                <option value="DUMAI"
                                    <?= (isset($unit['UNIT_PELAKSANA']) && $unit['UNIT_PELAKSANA'] == 'DUMAI') ? 'selected' : '' ?>>
                                    DUMAI
                                </option>

                                <option value="RENGAT"
                                    <?= (isset($unit['UNIT_PELAKSANA']) && $unit['UNIT_PELAKSANA'] == 'RENGAT') ? 'selected' : '' ?>>
                                    RENGAT
                                </option>

                                <option value="BANGKINANG"
                                    <?= (isset($unit['UNIT_PELAKSANA']) && $unit['UNIT_PELAKSANA'] == 'BANGKINANG') ? 'selected' : '' ?>>
                                    BANGKINANG
                                </option>
                            </select>
                        </div>

                        <!-- Unit Layanan (Dropdown) -->
                        <div class="col-md-6">
                            <label class="form-label">Unit Layanan</label>
                            <select class="form-control" name="UNIT_LAYANAN" required>
                                <option value="" disabled <?= empty($unit['UNIT_LAYANAN']) ? 'selected' : '' ?>>-- Pilih Unit Layanan --</option>

                                <?php
                                $unit_layanan_list = [
                                    "BAGAN BATU",
                                    "DUMAI KOTA",
                                    "DURI",
                                    "BENGKALIS",
                                    "SELATPANJANG",
                                    "KUALA ENOK",
                                    "AIR MOLEK",
                                    "TEMBILAHAN",
                                    "RENGAT KOTA",
                                    "TALUK KUANTAN",
                                    "TANJUNGPINANG KOTA",
                                    "KIJANG",
                                    "TANJUNG UBAN",
                                    "TANJUNG BALAI KARIMUN",
                                    "TANJUNG BATU",
                                    "DABO SINGKEP",
                                    "RANAI",
                                    "ANAMBAS",
                                    "PEKANBARU KOTA BARAT",
                                    "PANAM",
                                    "SIMPANG TIGA",
                                    "BANGKINANG",
                                    "KAMPAR",
                                    "SIAK SRI INDRAPURA",
                                    "PANGKALAN KERINCI",
                                    "UJUNG BATU",
                                    "PASIR PANGARAIAN",
                                    "RUMBAI",
                                    "PEKANBARU KOTA TIMUR",
                                    "LIPAT KAIN",
                                    "PERAWANG",
                                    "BAGAN SIAPI-API",
                                    "BELAKANGPADANG",
                                    "BINTAN CENTER",
                                    "NATUNA",
                                    "BELAKANG PADANG",
                                    "TANJUNG PINANG KOTA",
                                    "TANJUNG PINANG KOTA",
                                ];

                                $selected_unit_layanan = $unit['UNIT_LAYANAN'] ?? '';
                                foreach ($unit_layanan_list as $val) :
                                    $safeVal = htmlentities($val, ENT_QUOTES, 'UTF-8');
                                    $isSelected = ($selected_unit_layanan === $val) ? 'selected' : '';
                                ?>
                                    <option value="<?= $safeVal; ?>" <?= $isSelected; ?>><?= $safeVal; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Longitude (X)</label>
                            <input type="text" class="form-control" name="LONGITUDEX" value="<?= htmlentities($unit['LONGITUDEX'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Latitude (Y)</label>
                            <input type="text" class="form-control" name="LATITUDEY" value="<?= htmlentities($unit['LATITUDEY'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Alamat</label>
                            <input type="text" class="form-control" name="ADDRESS" value="<?= htmlentities($unit['ADDRESS'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                    </div>

                    <div class="mt-4">
                        <a href="<?= base_url('Unit'); ?>" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</main>
