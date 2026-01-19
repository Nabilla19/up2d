<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>
	<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
		<div class="container-fluid py-1 px-3">
			<h6 class="font-weight-bolder text-white mb-0">
				<i class="fas fa-microchip me-2 text-primary"></i> Edit Penyulang
			</h6>
		</div>
	</nav>
	<div class="container-fluid py-4">
		<div class="card shadow border-0 rounded-4">
			<div class="card-header bg-gradient-primary text-white"><strong>Form Edit KIT Cell</strong></div>
			<div class="card-body">
				<?php if ($this->session->flashdata('error')): ?>
					<div class="alert alert-danger text-white">
						<?= $this->session->flashdata('error'); ?>
					</div>
				<?php endif; ?>
				<form action="<?= base_url('Kit_cell/edit/' . urlencode($kit_cell['SSOTNUMBER'] ?? '')); ?>" method="post">
					<input type="hidden" name="original_SSOTNUMBER" value="<?= htmlentities($kit_cell['SSOTNUMBER'] ?? ''); ?>">
					<div class="row g-3">
						<!-- 34 database columns from kit_cell table -->
						<div class="col-md-4">
							<label class="form-label">CXUNIT</label>
							<input type="text" class="form-control" name="CXUNIT" value="<?= htmlentities($kit_cell['CXUNIT'] ?? ''); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">UNITNAME</label>
							<select class="form-control" name="UNITNAME">
								<option value="" disabled <?= empty($kit_cell['UNITNAME']) ? 'selected' : ''; ?>>-- Pilih UNITNAME --</option>
								<option value="PEKANBARU KOTA TIMUR" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'PEKANBARU KOTA TIMUR') ? 'selected' : ''; ?>>PEKANBARU KOTA TIMUR</option>
								<option value="PEKANBARU KOTA BARAT" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'PEKANBARU KOTA BARAT') ? 'selected' : ''; ?>>PEKANBARU KOTA BARAT</option>
								<option value="SIMPANG TIGA" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'SIMPANG TIGA') ? 'selected' : ''; ?>>SIMPANG TIGA</option>
								<option value="RUMBAI" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'RUMBAI') ? 'selected' : ''; ?>>RUMBAI</option>
								<option value="PANAM" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'PANAM') ? 'selected' : ''; ?>>PANAM</option>
								<option value="PERAWANG" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'PERAWANG') ? 'selected' : ''; ?>>PERAWANG</option>
								<option value="SIAK SRI INDRAPURA" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'SIAK SRI INDRAPURA') ? 'selected' : ''; ?>>SIAK SRI INDRAPURA</option>
								<option value="PANGKALAN KERINCI" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'PANGKALAN KERINCI') ? 'selected' : ''; ?>>PANGKALAN KERINCI</option>
								<option value="DURI" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'DURI') ? 'selected' : ''; ?>>DURI</option>
								<option value="BAGAN SIAPI-API" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'BAGAN SIAPI-API') ? 'selected' : ''; ?>>BAGAN SIAPI-API</option>
								<option value="BENGKALIS" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'BENGKALIS') ? 'selected' : ''; ?>>BENGKALIS</option>
								<option value="SELATPANJANG" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'SELATPANJANG') ? 'selected' : ''; ?>>SELATPANJANG</option>
								<option value="DUMAI KOTA" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'DUMAI KOTA') ? 'selected' : ''; ?>>DUMAI KOTA</option>
								<option value="BAGAN BATU" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'BAGAN BATU') ? 'selected' : ''; ?>>BAGAN BATU</option>
								<option value="KIJANG" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'KIJANG') ? 'selected' : ''; ?>>KIJANG</option>
								<option value="TANJUNG UBAN" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'TANJUNG UBAN') ? 'selected' : ''; ?>>TANJUNG UBAN</option>
								<option value="TANJUNG BALAI KARIMUN" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'TANJUNG BALAI KARIMUN') ? 'selected' : ''; ?>>TANJUNG BALAI KARIMUN</option>
								<option value="TANJUNG BATU" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'TANJUNG BATU') ? 'selected' : ''; ?>>TANJUNG BATU</option>
								<option value="DABO SINGKEP" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'DABO SINGKEP') ? 'selected' : ''; ?>>DABO SINGKEP</option>
								<option value="RANAI" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'RANAI') ? 'selected' : ''; ?>>RANAI</option>
								<option value="TANJUNGPINANG KOTA" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'TANJUNGPINANG KOTA') ? 'selected' : ''; ?>>TANJUNGPINANG KOTA</option>
								<option value="ANAMBAS" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'ANAMBAS') ? 'selected' : ''; ?>>ANAMBAS</option>
								<option value="RENGAT KOTA" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'RENGAT KOTA') ? 'selected' : ''; ?>>RENGAT KOTA</option>
								<option value="TALUK KUANTAN" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'TALUK KUANTAN') ? 'selected' : ''; ?>>TALUK KUANTAN</option>
								<option value="KUALA ENOK" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'KUALA ENOK') ? 'selected' : ''; ?>>KUALA ENOK</option>
								<option value="TEMBILAHAN" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'TEMBILAHAN') ? 'selected' : ''; ?>>TEMBILAHAN</option>
								<option value="AIR MOLEK" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'AIR MOLEK') ? 'selected' : ''; ?>>AIR MOLEK</option>
								<option value="BANGKINANG" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'BANGKINANG') ? 'selected' : ''; ?>>BANGKINANG</option>
								<option value="KAMPAR" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'KAMPAR') ? 'selected' : ''; ?>>KAMPAR</option>
								<option value="LIPAT KAIN" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'LIPAT KAIN') ? 'selected' : ''; ?>>LIPAT KAIN</option>
								<option value="PASIR PANGARAIAN" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'PASIR PANGARAIAN') ? 'selected' : ''; ?>>PASIR PANGARAIAN</option>
								<option value="UJUNG BATU" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'UJUNG BATU') ? 'selected' : ''; ?>>UJUNG BATU</option>
								<option value="UP2D RIAU" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'UP2D RIAU') ? 'selected' : ''; ?>>UP2D RIAU</option>
								<option value="BELAKANGPADANG" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'BELAKANGPADANG') ? 'selected' : ''; ?>>BELAKANGPADANG</option>
								<option value="DUMAI" <?= (isset($kit_cell['UNITNAME']) && $kit_cell['UNITNAME'] == 'DUMAI') ? 'selected' : ''; ?>>DUMAI</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">ASSETNUM</label>
							<input type="text" class="form-control" name="ASSETNUM" value="<?= htmlentities($kit_cell['ASSETNUM'] ?? ''); ?>">
						</div>
						<div class="col-md-6">
							<label class="form-label">SSOTNUMBER <span class="text-danger">*</span></label>
							<input type="text" class="form-control" name="SSOTNUMBER" value="<?= htmlentities($kit_cell['SSOTNUMBER'] ?? ''); ?>" required>
						</div>
						<div class="col-md-6">
							<label class="form-label">LOCATION</label>
							<input type="text" class="form-control" name="LOCATION" value="<?= htmlentities($kit_cell['LOCATION'] ?? ''); ?>">
						</div>
						<div class="col-md-12">
							<label class="form-label">DESCRIPTION</label>
							<textarea class="form-control" name="DESCRIPTION" rows="2"><?= htmlentities($kit_cell['DESCRIPTION'] ?? ''); ?></textarea>
						</div>
						<div class="col-md-4">
							<label class="form-label">VENDOR</label>
							<input type="text" class="form-control" name="VENDOR" value="<?= htmlentities($kit_cell['VENDOR'] ?? ''); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">MANUFACTURER</label>
							<input type="text" class="form-control" name="MANUFACTURER" value="<?= htmlentities($kit_cell['MANUFACTURER'] ?? ''); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">INSTALLDATE</label>
							<input type="date" class="form-control" name="INSTALLDATE" value="<?= htmlentities($kit_cell['INSTALLDATE'] ?? ''); ?>">
						</div>
						<div class="col-md-3">
							<label class="form-label">PRIORITY</label>
							<input type="text" class="form-control" name="PRIORITY" value="<?= htmlentities($kit_cell['PRIORITY'] ?? ''); ?>">
						</div>
						<div class="col-md-3">
							<label class="form-label">STATUS</label>
							<select class="form-control" name="STATUS">
								<option value="" disabled <?= empty($kit_cell['STATUS']) ? 'selected' : ''; ?>>-- Pilih STATUS --</option>
								<option value="OPERATING" <?= (isset($kit_cell['STATUS']) && $kit_cell['STATUS'] == 'OPERATING') ? 'selected' : ''; ?>>OPERATING</option>
								<option value="INACTIVE" <?= (isset($kit_cell['STATUS']) && $kit_cell['STATUS'] == 'INACTIVE') ? 'selected' : ''; ?>>INACTIVE</option>
								<option value="NOT READY" <?= (isset($kit_cell['STATUS']) && $kit_cell['STATUS'] == 'NOT READY') ? 'selected' : ''; ?>>NOT READY</option>
								<option value="REQOPERATING" <?= (isset($kit_cell['STATUS']) && $kit_cell['STATUS'] == 'REQOPERATING') ? 'selected' : ''; ?>>REQOPERATING</option>
							</select>
						</div>
						<div class="col-md-3">
							<label class="form-label">TUJDNUMBER</label>
							<input type="text" class="form-control" name="TUJDNUMBER" value="<?= htmlentities($kit_cell['TUJDNUMBER'] ?? ''); ?>">
						</div>
						<div class="col-md-3">
							<label class="form-label">CHANGEBY</label>
							<input type="text" class="form-control" name="CHANGEBY" value="<?= htmlentities($kit_cell['CHANGEBY'] ?? ''); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">CHANGEDATE</label>
							<input type="date" class="form-control" name="CHANGEDATE" value="<?= htmlentities($kit_cell['CHANGEDATE'] ?? ''); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">CXCLASSIFICATIONDESC</label>
							<input type="text" class="form-control" name="CXCLASSIFICATIONDESC" value="<?= htmlentities($kit_cell['CXCLASSIFICATIONDESC'] ?? ''); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">CXPENYULANG</label>
							<input type="text" class="form-control" name="CXPENYULANG" value="<?= htmlentities($kit_cell['CXPENYULANG'] ?? ''); ?>">
						</div>
						<div class="col-md-6">
							<label class="form-label">NAMA_LOCATION</label>
							<input type="text" class="form-control" name="NAMA_LOCATION" value="<?= htmlentities($kit_cell['NAMA_LOCATION'] ?? ''); ?>">
						</div>
						<div class="col-md-3">
							<label class="form-label">LONGITUDEX</label>
							<input type="text" class="form-control" name="LONGITUDEX" value="<?= htmlentities($kit_cell['LONGITUDEX'] ?? ''); ?>">
						</div>
						<div class="col-md-3">
							<label class="form-label">LATITUDEY</label>
							<input type="text" class="form-control" name="LATITUDEY" value="<?= htmlentities($kit_cell['LATITUDEY'] ?? ''); ?>">
						</div>
						<div class="col-md-3">
							<label class="form-label">ISASSET</label>
							<input type="text" class="form-control" name="ISASSET" value="<?= htmlentities($kit_cell['ISASSET'] ?? ''); ?>">
						</div>
						<div class="col-md-3">
							<label class="form-label">STATUS_KEPEMILIKAN</label>
							<select class="form-control" name="STATUS_KEPEMILIKAN">
								<option value="" disabled <?= empty($kit_cell['STATUS_KEPEMILIKAN']) ? 'selected' : ''; ?>>-- Pilih STATUS_KEPEMILIKAN --</option>
								<option value="PLN" <?= (isset($kit_cell['STATUS_KEPEMILIKAN']) && $kit_cell['STATUS_KEPEMILIKAN'] == 'PLN') ? 'selected' : ''; ?>>PLN</option>
								<option value="NON PLN" <?= (isset($kit_cell['STATUS_KEPEMILIKAN']) && $kit_cell['STATUS_KEPEMILIKAN'] == 'NON PLN') ? 'selected' : ''; ?>>NON PLN</option>
							</select>
						</div>
						<div class="col-md-3">
							<label class="form-label">BURDEN</label>
							<input type="text" class="form-control" name="BURDEN" value="<?= htmlentities($kit_cell['BURDEN'] ?? ''); ?>">
						</div>
						<div class="col-md-3">
							<label class="form-label">FAKTOR_KALI</label>
							<input type="text" class="form-control" name="FAKTOR_KALI" value="<?= htmlentities($kit_cell['FAKTOR_KALI'] ?? ''); ?>">
						</div>
						<div class="col-md-3">
							<label class="form-label">JENIS_CT</label>
							<input type="text" class="form-control" name="JENIS_CT" value="<?= htmlentities($kit_cell['JENIS_CT'] ?? ''); ?>">
						</div>
						<div class="col-md-3">
							<label class="form-label">KELAS_CT</label>
							<input type="text" class="form-control" name="KELAS_CT" value="<?= htmlentities($kit_cell['KELAS_CT'] ?? ''); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">KELAS_PROTEKSI</label>
							<input type="text" class="form-control" name="KELAS_PROTEKSI" value="<?= htmlentities($kit_cell['KELAS_PROTEKSI'] ?? ''); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">PRIMER_SEKUNDER</label>
							<input type="text" class="form-control" name="PRIMER_SEKUNDER" value="<?= htmlentities($kit_cell['PRIMER_SEKUNDER'] ?? ''); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">TIPE_CT</label>
							<input type="text" class="form-control" name="TIPE_CT" value="<?= htmlentities($kit_cell['TIPE_CT'] ?? ''); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">OWNERSYSID</label>
							<input type="text" class="form-control" name="OWNERSYSID" value="<?= htmlentities($kit_cell['OWNERSYSID'] ?? ''); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">ISOLASI_KUBIKEL</label>
							<select class="form-control" name="ISOLASI_KUBIKEL">
								<option value="" disabled <?= empty($kit_cell['ISOLASI_KUBIKEL']) ? 'selected' : ''; ?>>-- Pilih ISOLASI_KUBIKEL --</option>
								<option value="Full Insulated" <?= (isset($kit_cell['ISOLASI_KUBIKEL']) && $kit_cell['ISOLASI_KUBIKEL'] == 'Full Insulated') ? 'selected' : ''; ?>>Full Insulated</option>
								<option value="Air Insulated" <?= (isset($kit_cell['ISOLASI_KUBIKEL']) && $kit_cell['ISOLASI_KUBIKEL'] == 'Air Insulated') ? 'selected' : ''; ?>>Air Insulated</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">JENIS_MVCELL</label>
							<select class="form-control" name="JENIS_MVCELL">
								<option value="" disabled <?= empty($kit_cell['JENIS_MVCELL']) ? 'selected' : ''; ?>>-- Pilih JENIS_MVCELL --</option>
								<option value="Pemutus Tenaga" <?= (isset($kit_cell['JENIS_MVCELL']) && $kit_cell['JENIS_MVCELL'] == 'Pemutus Tenaga') ? 'selected' : ''; ?>>Pemutus Tenaga</option>
								<option value="Pemisah" <?= (isset($kit_cell['JENIS_MVCELL']) && $kit_cell['JENIS_MVCELL'] == 'Pemisah') ? 'selected' : ''; ?>>Pemisah</option>
								<option value="Interface / Terminal Kabel" <?= (isset($kit_cell['JENIS_MVCELL']) && $kit_cell['JENIS_MVCELL'] == 'Interface / Terminal Kabel') ? 'selected' : ''; ?>>Interface / Terminal Kabel</option>
								<option value="Pemutus" <?= (isset($kit_cell['JENIS_MVCELL']) && $kit_cell['JENIS_MVCELL'] == 'Pemutus') ? 'selected' : ''; ?>>Pemutus</option>
								<option value="Pengaman Trafo" <?= (isset($kit_cell['JENIS_MVCELL']) && $kit_cell['JENIS_MVCELL'] == 'Pengaman Trafo') ? 'selected' : ''; ?>>Pengaman Trafo</option>
								<option value="Pengukuran" <?= (isset($kit_cell['JENIS_MVCELL']) && $kit_cell['JENIS_MVCELL'] == 'Pengukuran') ? 'selected' : ''; ?>>Pengukuran</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">TH_BUAT</label>
							<input type="text" class="form-control" name="TH_BUAT" value="<?= htmlentities($kit_cell['TH_BUAT'] ?? ''); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">TYPE_MVCELL</label>
							<input type="text" class="form-control" name="TYPE_MVCELL" value="<?= htmlentities($kit_cell['TYPE_MVCELL'] ?? ''); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">CELL_TYPE</label>
							<select class="form-select" name="CELL_TYPE">
								<option value="">-- Pilih --</option>
								<option value="CT" <?= (isset($kit_cell['CELL_TYPE']) && $kit_cell['CELL_TYPE'] == 'CT') ? 'selected' : ''; ?>>CT</option>
								<option value="MVCELL" <?= (isset($kit_cell['CELL_TYPE']) && $kit_cell['CELL_TYPE'] == 'MVCELL') ? 'selected' : ''; ?>>MVCELL</option>
							</select>
						</div>
					</div>
					<div class="mt-4">
						<a href="<?= base_url('Kit_cell'); ?>" class="btn btn-secondary">Batal</a>
						<button type="submit" class="btn btn-primary">Simpan Perubahan</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</main>
