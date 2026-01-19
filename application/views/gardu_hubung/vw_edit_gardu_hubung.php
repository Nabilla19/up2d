<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>
	<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
		<div class="container-fluid py-1 px-3">
			<h6 class="font-weight-bolder text-white mb-0">
				<i class="fas fa-network-wired me-2"></i> Edit Gardu Hubung
			</h6>
		</div>
	</nav>
	<div class="container-fluid py-4">
		<div class="card shadow border-0 rounded-4">
			<div class="card-header bg-gradient-primary text-white"><strong>Form Edit Gardu Hubung</strong></div>
			<div class="card-body">
				<form action="<?= base_url('Gardu_hubung/edit/' . urlencode($gardu_hubung['SSOTNUMBER'] ?? $gardu_hubung['SSOTNUMBER_GH'] ?? '')); ?>" method="post">
					<input type="hidden" name="original_SSOTNUMBER" value="<?= htmlentities($gardu_hubung['SSOTNUMBER'] ?? $gardu_hubung['SSOTNUMBER_GH'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
					<div class="row g-3">
						<div class="col-md-4">
							<label class="form-label">UP3_2D</label>
							<select class="form-control" name="UP3_2D">
								<option value="" disabled <?= empty($gardu_hubung['UP3_2D']) ? 'selected' : ''; ?>>-- Pilih UP3_2D --</option>
								<option value="UP2D.6456" <?= (($gardu_hubung['UP3_2D'] ?? '') === 'UP2D.6456') ? 'selected' : ''; ?>>UP2D.6456</option>
								<option value="UP3.6411" <?= (($gardu_hubung['UP3_2D'] ?? '') === 'UP3.6411') ? 'selected' : ''; ?>>UP3.6411</option>
								<option value="UP3.6412" <?= (($gardu_hubung['UP3_2D'] ?? '') === 'UP3.6412') ? 'selected' : ''; ?>>UP3.6412</option>
								<option value="UP3.6413" <?= (($gardu_hubung['UP3_2D'] ?? '') === 'UP3.6413') ? 'selected' : ''; ?>>UP3.6413</option>
								<option value="UP3.6414" <?= (($gardu_hubung['UP3_2D'] ?? '') === 'UP3.6414') ? 'selected' : ''; ?>>UP3.6414</option>
								<option value="UP3.6415" <?= (($gardu_hubung['UP3_2D'] ?? '') === 'UP3.6415') ? 'selected' : ''; ?>>UP3.6415</option>
								<option value="UPT.3217" <?= (($gardu_hubung['UP3_2D'] ?? '') === 'UPT.3217') ? 'selected' : ''; ?>>UPT.3217</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">UNITNAME_UP3</label>
							<select class="form-control" name="UNITNAME_UP3">
								<option value="" disabled <?= empty($gardu_hubung['UNITNAME_UP3']) ? 'selected' : ''; ?>>-- Pilih UNITNAME_UP3 --</option>
								<option value="UP2D RIAU" <?= (($gardu_hubung['UNITNAME_UP3'] ?? '') === 'UP2D RIAU') ? 'selected' : ''; ?>>UP2D RIAU</option>
								<option value="PEKANBARU" <?= (($gardu_hubung['UNITNAME_UP3'] ?? '') === 'PEKANBARU') ? 'selected' : ''; ?>>PEKANBARU</option>
								<option value="DUMAI" <?= (($gardu_hubung['UNITNAME_UP3'] ?? '') === 'DUMAI') ? 'selected' : ''; ?>>DUMAI</option>
								<option value="TANJUNG PINANG" <?= (($gardu_hubung['UNITNAME_UP3'] ?? '') === 'TANJUNG PINANG') ? 'selected' : ''; ?>>TANJUNG PINANG</option>
								<option value="RENGAT" <?= (($gardu_hubung['UNITNAME_UP3'] ?? '') === 'RENGAT') ? 'selected' : ''; ?>>RENGAT</option>
								<option value="BANGKINANG" <?= (($gardu_hubung['UNITNAME_UP3'] ?? '') === 'BANGKINANG') ? 'selected' : ''; ?>>BANGKINANG</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">CXUNIT</label>
							<input type="text" class="form-control" name="CXUNIT" value="<?= htmlentities($gardu_hubung['CXUNIT'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>

						<div class="col-md-4">
							<label class="form-label">UNITNAME</label>
							<select class="form-control" name="UNITNAME">
								<option value="" disabled <?= empty($gardu_hubung['UNITNAME']) ? 'selected' : ''; ?>>-- Pilih UNITNAME --</option>
								<option value="UP2D RIAU" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'UP2D RIAU') ? 'selected' : ''; ?>>UP2D RIAU</option>
								<option value="PANAM" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'PANAM') ? 'selected' : ''; ?>>PANAM</option>
								<option value="PANGKALAN KERINCI" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'PANGKALAN KERINCI') ? 'selected' : ''; ?>>PANGKALAN KERINCI</option>
								<option value="PEKANBARU KOTA BARAT" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'PEKANBARU KOTA BARAT') ? 'selected' : ''; ?>>PEKANBARU KOTA BARAT</option>
								<option value="PEKANBARU KOTA TIMUR" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'PEKANBARU KOTA TIMUR') ? 'selected' : ''; ?>>PEKANBARU KOTA TIMUR</option>
								<option value="PERAWANG" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'PERAWANG') ? 'selected' : ''; ?>>PERAWANG</option>
								<option value="RUMBAI" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'RUMBAI') ? 'selected' : ''; ?>>RUMBAI</option>
								<option value="SIAK SRI INDRAPURA" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'SIAK SRI INDRAPURA') ? 'selected' : ''; ?>>SIAK SRI INDRAPURA</option>
								<option value="SIMPANG TIGA" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'SIMPANG TIGA') ? 'selected' : ''; ?>>SIMPANG TIGA</option>
								<option value="BAGAN BATU" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'BAGAN BATU') ? 'selected' : ''; ?>>BAGAN BATU</option>
								<option value="BAGAN SIAPI-API" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'BAGAN SIAPI-API') ? 'selected' : ''; ?>>BAGAN SIAPI-API</option>
								<option value="BENGKALIS" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'BENGKALIS') ? 'selected' : ''; ?>>BENGKALIS</option>
								<option value="DUMAI KOTA" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'DUMAI KOTA') ? 'selected' : ''; ?>>DUMAI KOTA</option>
								<option value="DURI" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'DURI') ? 'selected' : ''; ?>>DURI</option>
								<option value="SELATPANJANG" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'SELATPANJANG') ? 'selected' : ''; ?>>SELATPANJANG</option>
								<option value="ANAMBAS" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'ANAMBAS') ? 'selected' : ''; ?>>ANAMBAS</option>
								<option value="BELAKANGPADANG" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'BELAKANGPADANG') ? 'selected' : ''; ?>>BELAKANGPADANG</option>
								<option value="BINTAN CENTER" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'BINTAN CENTER') ? 'selected' : ''; ?>>BINTAN CENTER</option>
								<option value="DABO SINGKEP" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'DABO SINGKEP') ? 'selected' : ''; ?>>DABO SINGKEP</option>
								<option value="KIJANG" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'KIJANG') ? 'selected' : ''; ?>>KIJANG</option>
								<option value="RANAI" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'RANAI') ? 'selected' : ''; ?>>RANAI</option>
								<option value="TANJUNG BALAI KARIMUN" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'TANJUNG BALAI KARIMUN') ? 'selected' : ''; ?>>TANJUNG BALAI KARIMUN</option>
								<option value="TANJUNG BATU" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'TANJUNG BATU') ? 'selected' : ''; ?>>TANJUNG BATU</option>
								<option value="TANJUNG UBAN" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'TANJUNG UBAN') ? 'selected' : ''; ?>>TANJUNG UBAN</option>
								<option value="TANJUNGPINANG KOTA" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'TANJUNGPINANG KOTA') ? 'selected' : ''; ?>>TANJUNGPINANG KOTA</option>
								<option value="AIR MOLEK" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'AIR MOLEK') ? 'selected' : ''; ?>>AIR MOLEK</option>
								<option value="KUALA ENOK" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'KUALA ENOK') ? 'selected' : ''; ?>>KUALA ENOK</option>
								<option value="RENGAT" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'RENGAT') ? 'selected' : ''; ?>>RENGAT</option>
								<option value="RENGAT KOTA" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'RENGAT KOTA') ? 'selected' : ''; ?>>RENGAT KOTA</option>
								<option value="TALUK KUANTAN" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'TALUK KUANTAN') ? 'selected' : ''; ?>>TALUK KUANTAN</option>
								<option value="TEMBILAHAN" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'TEMBILAHAN') ? 'selected' : ''; ?>>TEMBILAHAN</option>
								<option value="BANGKINANG" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'BANGKINANG') ? 'selected' : ''; ?>>BANGKINANG</option>
								<option value="KAMPAR" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'KAMPAR') ? 'selected' : ''; ?>>KAMPAR</option>
								<option value="LIPAT KAIN" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'LIPAT KAIN') ? 'selected' : ''; ?>>LIPAT KAIN</option>
								<option value="PASIR PANGARAIAN" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'PASIR PANGARAIAN') ? 'selected' : ''; ?>>PASIR PANGARAIAN</option>
								<option value="UJUNG BATU" <?= (($gardu_hubung['UNITNAME'] ?? '') === 'UJUNG BATU') ? 'selected' : ''; ?>>UJUNG BATU</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">LOCATION</label>
							<input type="text" class="form-control" name="LOCATION" value="<?= htmlentities($gardu_hubung['LOCATION'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">SSOT Number</label>
							<input type="text" class="form-control" name="SSOTNUMBER" value="<?= htmlentities($gardu_hubung['SSOTNUMBER'] ?? $gardu_hubung['SSOTNUMBER_GH'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>

						<div class="col-md-6">
							<label class="form-label">DESCRIPTION</label>
							<input type="text" class="form-control" name="DESCRIPTION" value="<?= htmlentities($gardu_hubung['DESCRIPTION'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-6">
							<label class="form-label">STATUS</label>
							<select class="form-control" name="STATUS">
								<option value="" disabled <?= empty($gardu_hubung['STATUS']) ? 'selected' : ''; ?>>-- Pilih STATUS --</option>
								<option value="OPERATING" <?= (($gardu_hubung['STATUS'] ?? '') === 'OPERATING') ? 'selected' : ''; ?>>OPERATING</option>
								<option value="INACTIVE" <?= (($gardu_hubung['STATUS'] ?? '') === 'INACTIVE') ? 'selected' : ''; ?>>INACTIVE</option>
								<option value="NOT READY" <?= (($gardu_hubung['STATUS'] ?? '') === 'NOT READY') ? 'selected' : ''; ?>>NOT READY</option>
								<option value="REQOPERATING" <?= (($gardu_hubung['STATUS'] ?? '') === 'REQOPERATING') ? 'selected' : ''; ?>>REQOPERATING</option>
							</select>
						</div>

						<div class="col-md-4">
							<label class="form-label">TUJDNUMBER</label>
							<input type="text" class="form-control" name="TUJDNUMBER" value="<?= htmlentities($gardu_hubung['TUJDNUMBER'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">ASSETCLASSHI</label>
							<input type="text" class="form-control" name="ASSETCLASSHI" value="<?= htmlentities($gardu_hubung['ASSETCLASSHI'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">SADDRESSCODE</label>
							<input type="text" class="form-control" name="SADDRESSCODE" value="<?= htmlentities($gardu_hubung['SADDRESSCODE'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>

						<div class="col-md-6">
							<label class="form-label">CXCLASSIFICATIONDESC</label>
							<input type="text" class="form-control" name="CXCLASSIFICATIONDESC" value="<?= htmlentities($gardu_hubung['CXCLASSIFICATIONDESC'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-6">
							<label class="form-label">PENYULANG</label>
							<input type="text" class="form-control" name="PENYULANG" value="<?= htmlentities($gardu_hubung['PENYULANG'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>

						<div class="col-md-6">
							<label class="form-label">PARENT</label>
							<input type="text" class="form-control" name="PARENT" value="<?= htmlentities($gardu_hubung['PARENT'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-6">
							<label class="form-label">PARENT_DESCRIPTION</label>
							<input type="text" class="form-control" name="PARENT_DESCRIPTION" value="<?= htmlentities($gardu_hubung['PARENT_DESCRIPTION'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>

						<div class="col-md-4">
							<label class="form-label">INSTALLDATE</label>
							<input type="text" class="form-control" name="INSTALLDATE" value="<?= htmlentities($gardu_hubung['INSTALLDATE'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">ACTUALOPRDATE</label>
							<input type="text" class="form-control" name="ACTUALOPRDATE" value="<?= htmlentities($gardu_hubung['ACTUALOPRDATE'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">CHANGEDATE</label>
							<input type="text" class="form-control" name="CHANGEDATE" value="<?= htmlentities($gardu_hubung['CHANGEDATE'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>

						<div class="col-md-4">
							<label class="form-label">CHANGEBY</label>
							<input type="text" class="form-control" name="CHANGEBY" value="<?= htmlentities($gardu_hubung['CHANGEBY'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>

						<div class="col-md-4">
							<label class="form-label">Latitude (Y)</label>
							<input type="text" class="form-control" name="LATITUDEY" value="<?= htmlentities($gardu_hubung['LATITUDEY'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">Longitude (X)</label>
							<input type="text" class="form-control" name="LONGITUDEX" value="<?= htmlentities($gardu_hubung['LONGITUDEX'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>

						<div class="col-md-6">
							<label class="form-label">FORMATTEDADDRESS</label>
							<input type="text" class="form-control" name="FORMATTEDADDRESS" value="<?= htmlentities($gardu_hubung['FORMATTEDADDRESS'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-6">
							<label class="form-label">STREETADDRESS</label>
							<input type="text" class="form-control" name="STREETADDRESS" value="<?= htmlentities($gardu_hubung['STREETADDRESS'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>

						<div class="col-md-4">
							<label class="form-label">CITY</label>
							<input type="text" class="form-control" name="CITY" value="<?= htmlentities($gardu_hubung['CITY'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">ISASSET</label>
							<input type="text" class="form-control" name="ISASSET" value="<?= htmlentities($gardu_hubung['ISASSET'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">STATUS_KEPEMILIKAN</label>
							<select class="form-control" name="STATUS_KEPEMILIKAN">
								<option value="" disabled <?= empty($gardu_hubung['STATUS_KEPEMILIKAN']) ? 'selected' : ''; ?>>-- Pilih STATUS_KEPEMILIKAN --</option>
								<option value="PLN" <?= (($gardu_hubung['STATUS_KEPEMILIKAN'] ?? '') === 'PLN') ? 'selected' : ''; ?>>PLN</option>
								<option value="NON PLN" <?= (($gardu_hubung['STATUS_KEPEMILIKAN'] ?? '') === 'NON PLN') ? 'selected' : ''; ?>>NON PLN</option>
							</select>
						</div>

						<div class="col-md-4">
							<label class="form-label">EXTERNALREFID</label>
							<input type="text" class="form-control" name="EXTERNALREFID" value="<?= htmlentities($gardu_hubung['EXTERNALREFID'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">JENIS_PELAYANAN</label>
							<select class="form-control" name="JENIS_PELAYANAN">
								<option value="" disabled <?= empty($gardu_hubung['JENIS_PELAYANAN']) ? 'selected' : ''; ?>>-- Pilih JENIS_PELAYANAN --</option>
								<option value="Umum" <?= (($gardu_hubung['JENIS_PELAYANAN'] ?? '') === 'Umum') ? 'selected' : ''; ?>>Umum</option>
								<option value="Campuran" <?= (($gardu_hubung['JENIS_PELAYANAN'] ?? '') === 'Campuran') ? 'selected' : ''; ?>>Campuran</option>
								<option value="Distribusi" <?= (($gardu_hubung['JENIS_PELAYANAN'] ?? '') === 'Distribusi') ? 'selected' : ''; ?>>Distribusi</option>
								<option value="Khusus" <?= (($gardu_hubung['JENIS_PELAYANAN'] ?? '') === 'Khusus') ? 'selected' : ''; ?>>Khusus</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">NO_SLO</label>
							<input type="text" class="form-control" name="NO_SLO" value="<?= htmlentities($gardu_hubung['NO_SLO'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>

						<div class="col-md-4">
							<label class="form-label">OWNERSYSID</label>
							<input type="text" class="form-control" name="OWNERSYSID" value="<?= htmlentities($gardu_hubung['OWNERSYSID'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">SLOACTIVEDATE</label>
							<input type="text" class="form-control" name="SLOACTIVEDATE" value="<?= htmlentities($gardu_hubung['SLOACTIVEDATE'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">STATUS_RC</label>
							<select class="form-control" name="STATUS_RC">
								<option value="" disabled <?= empty($gardu_hubung['STATUS_RC']) ? 'selected' : ''; ?>>-- Pilih STATUS_RC --</option>
								<option value="ADA" <?= (($gardu_hubung['STATUS_RC'] ?? '') === 'ADA') ? 'selected' : ''; ?>>ADA</option>
								<option value="TIDAK ADA" <?= (($gardu_hubung['STATUS_RC'] ?? '') === 'TIDAK ADA') ? 'selected' : ''; ?>>TIDAK ADA</option>
							</select>
						</div>

						<div class="col-md-6">
							<label class="form-label">TYPE_GARDU</label>
							<input type="text" class="form-control" name="TYPE_GARDU" value="<?= htmlentities($gardu_hubung['TYPE_GARDU'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
					</div>
					<div class="mt-4">
						<a href="<?= base_url('Gardu_hubung'); ?>" class="btn btn-secondary">Batal</a>
						<button type="submit" class="btn btn-primary">Simpan Perubahan</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</main>
