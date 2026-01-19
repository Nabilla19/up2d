<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>
	<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
		<div class="container-fluid py-1 px-3">
			<h6 class="font-weight-bolder text-white mb-0">
				<i class="fas fa-wave-square me-2 text-info"></i> Edit GI Cell
			</h6>
		</div>
	</nav>
	<div class="container-fluid py-4">
		<div class="card shadow border-0 rounded-4">
			<div class="card-header bg-gradient-primary text-white"><strong>Form Edit GI Cell</strong></div>
			<div class="card-body">
				<form action="<?= base_url('Gi_cell/edit/' . urlencode($gi_cell['SSOTNUMBER'] ?? $gi_cell['SSOTNUMBER_GI_CELL'] ?? '')); ?>" method="post">
					<input type="hidden" name="original_SSOTNUMBER" value="<?= htmlentities($gi_cell['SSOTNUMBER'] ?? $gi_cell['SSOTNUMBER_GI_CELL'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
					<div class="row g-3">
						<!-- Only fields that exist in database -->
						<div class="col-md-4">
							<label class="form-label">CXUNIT</label>
							<input type="text" class="form-control" name="CXUNIT" value="<?= htmlentities($gi_cell['CXUNIT'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">UNITNAME</label>
							<select class="form-control" name="UNITNAME">
								<option value="" disabled <?= empty($gi_cell['UNITNAME']) ? 'selected' : ''; ?>>-- Pilih UNITNAME --</option>
								<option value="UP2D RIAU" <?= (isset($gi_cell['UNITNAME']) && $gi_cell['UNITNAME'] == 'UP2D RIAU') ? 'selected' : ''; ?>>UP2D RIAU</option>
								<option value="PEKANBARU" <?= (isset($gi_cell['UNITNAME']) && $gi_cell['UNITNAME'] == 'PEKANBARU') ? 'selected' : ''; ?>>PEKANBARU</option>
								<option value="SIAK SRI INDRAPURA" <?= (isset($gi_cell['UNITNAME']) && $gi_cell['UNITNAME'] == 'SIAK SRI INDRAPURA') ? 'selected' : ''; ?>>SIAK SRI INDRAPURA</option>
								<option value="DUMAI" <?= (isset($gi_cell['UNITNAME']) && $gi_cell['UNITNAME'] == 'DUMAI') ? 'selected' : ''; ?>>DUMAI</option>
								<option value="BELAKANGPADANG" <?= (isset($gi_cell['UNITNAME']) && $gi_cell['UNITNAME'] == 'BELAKANGPADANG') ? 'selected' : ''; ?>>BELAKANGPADANG</option>
								<option value="KIJANG" <?= (isset($gi_cell['UNITNAME']) && $gi_cell['UNITNAME'] == 'KIJANG') ? 'selected' : ''; ?>>KIJANG</option>
								<option value="TANJUNG BALAI KARIMUN" <?= (isset($gi_cell['UNITNAME']) && $gi_cell['UNITNAME'] == 'TANJUNG BALAI KARIMUN') ? 'selected' : ''; ?>>TANJUNG BALAI KARIMUN</option>
								<option value="TANJUNG BATU" <?= (isset($gi_cell['UNITNAME']) && $gi_cell['UNITNAME'] == 'TANJUNG BATU') ? 'selected' : ''; ?>>TANJUNG BATU</option>
								<option value="TANJUNG PINANG" <?= (isset($gi_cell['UNITNAME']) && $gi_cell['UNITNAME'] == 'TANJUNG PINANG') ? 'selected' : ''; ?>>TANJUNG PINANG</option>
								<option value="RENGAT" <?= (isset($gi_cell['UNITNAME']) && $gi_cell['UNITNAME'] == 'RENGAT') ? 'selected' : ''; ?>>RENGAT</option>
								<option value="BANGKINANG" <?= (isset($gi_cell['UNITNAME']) && $gi_cell['UNITNAME'] == 'BANGKINANG') ? 'selected' : ''; ?>>BANGKINANG</option>
								<option value="UNIT PELAKSANA TRANSMISI PEKANBARU" <?= (isset($gi_cell['UNITNAME']) && $gi_cell['UNITNAME'] == 'UNIT PELAKSANA TRANSMISI PEKANBARU') ? 'selected' : ''; ?>>UNIT PELAKSANA TRANSMISI PEKANBARU</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">ASSETNUM</label>
							<input type="number" class="form-control" name="ASSETNUM" value="<?= htmlentities($gi_cell['ASSETNUM'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-6">
							<label class="form-label">SSOTNUMBER</label>
							<input type="text" class="form-control" name="SSOTNUMBER" value="<?= htmlentities($gi_cell['SSOTNUMBER'] ?? $gi_cell['SSOTNUMBER_GI_CELL'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
						</div>
						<div class="col-md-6">
							<label class="form-label">LOCATION</label>
							<input type="text" class="form-control" name="LOCATION" value="<?= htmlentities($gi_cell['LOCATION'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-12">
							<label class="form-label">DESCRIPTION</label>
							<input type="text" class="form-control" name="DESCRIPTION" value="<?= htmlentities($gi_cell['DESCRIPTION'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-6">
							<label class="form-label">VENDOR</label>
							<input type="text" class="form-control" name="VENDOR" value="<?= htmlentities($gi_cell['VENDOR'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-6">
							<label class="form-label">MANUFACTURER</label>
							<input type="text" class="form-control" name="MANUFACTURER" value="<?= htmlentities($gi_cell['MANUFACTURER'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">INSTALLDATE</label>
							<input type="date" class="form-control" name="INSTALLDATE" value="<?= htmlentities($gi_cell['INSTALLDATE'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">PRIORITY</label>
							<input type="text" class="form-control" name="PRIORITY" value="<?= htmlentities($gi_cell['PRIORITY'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">STATUS</label>
							<select class="form-control" name="STATUS">
								<option value="" disabled <?= empty($gi_cell['STATUS']) ? 'selected' : ''; ?>>-- Pilih STATUS --</option>
								<option value="OPERATING" <?= (isset($gi_cell['STATUS']) && $gi_cell['STATUS'] == 'OPERATING') ? 'selected' : ''; ?>>OPERATING</option>
								<option value="INACTIVE" <?= (isset($gi_cell['STATUS']) && $gi_cell['STATUS'] == 'INACTIVE') ? 'selected' : ''; ?>>INACTIVE</option>
								<option value="NOT READY" <?= (isset($gi_cell['STATUS']) && $gi_cell['STATUS'] == 'NOT READY') ? 'selected' : ''; ?>>NOT READY</option>
								<option value="REQOPERATING" <?= (isset($gi_cell['STATUS']) && $gi_cell['STATUS'] == 'REQOPERATING') ? 'selected' : ''; ?>>REQOPERATING</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">TUJDNUMBER</label>
							<input type="text" class="form-control" name="TUJDNUMBER" value="<?= htmlentities($gi_cell['TUJDNUMBER'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">CHANGEBY</label>
							<input type="text" class="form-control" name="CHANGEBY" value="<?= htmlentities($gi_cell['CHANGEBY'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">CHANGEDATE</label>
							<input type="text" class="form-control" name="CHANGEDATE" value="<?= htmlentities($gi_cell['CHANGEDATE'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-6">
							<label class="form-label">CXCLASSIFICATIONDESC</label>
							<input type="text" class="form-control" name="CXCLASSIFICATIONDESC" value="<?= htmlentities($gi_cell['CXCLASSIFICATIONDESC'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-6">
							<label class="form-label">CXPENYULANG</label>
							<input type="text" class="form-control" name="CXPENYULANG" value="<?= htmlentities($gi_cell['CXPENYULANG'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-12">
							<label class="form-label">NAMA_LOCATION</label>
							<input type="text" class="form-control" name="NAMA_LOCATION" value="<?= htmlentities($gi_cell['NAMA_LOCATION'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-6">
							<label class="form-label">LONGITUDEX</label>
							<input type="text" class="form-control" name="LONGITUDEX" value="<?= htmlentities($gi_cell['LONGITUDEX'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-6">
							<label class="form-label">LATITUDEY</label>
							<input type="text" class="form-control" name="LATITUDEY" value="<?= htmlentities($gi_cell['LATITUDEY'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">ISASSET</label>
							<input type="text" class="form-control" name="ISASSET" value="<?= htmlentities($gi_cell['ISASSET'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">STATUS_KEPEMILIKAN</label>
							<select class="form-control" name="STATUS_KEPEMILIKAN">
								<option value="" disabled <?= empty($gi_cell['STATUS_KEPEMILIKAN']) ? 'selected' : ''; ?>>-- Pilih STATUS_KEPEMILIKAN --</option>
								<option value="PLN" <?= (isset($gi_cell['STATUS_KEPEMILIKAN']) && $gi_cell['STATUS_KEPEMILIKAN'] == 'PLN') ? 'selected' : ''; ?>>PLN</option>
								<option value="NON PLN" <?= (isset($gi_cell['STATUS_KEPEMILIKAN']) && $gi_cell['STATUS_KEPEMILIKAN'] == 'NON PLN') ? 'selected' : ''; ?>>NON PLN</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">BURDEN</label>
							<input type="number" class="form-control" name="BURDEN" value="<?= htmlentities($gi_cell['BURDEN'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">FAKTOR_KALI</label>
							<input type="text" class="form-control" name="FAKTOR_KALI" value="<?= htmlentities($gi_cell['FAKTOR_KALI'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">JENIS_CT</label>
							<input type="text" class="form-control" name="JENIS_CT" value="<?= htmlentities($gi_cell['JENIS_CT'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">KELAS_CT</label>
							<input type="text" class="form-control" name="KELAS_CT" value="<?= htmlentities($gi_cell['KELAS_CT'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">KELAS_PROTEKSI</label>
							<input type="text" class="form-control" name="KELAS_PROTEKSI" value="<?= htmlentities($gi_cell['KELAS_PROTEKSI'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">PRIMER_SEKUNDER</label>
							<input type="text" class="form-control" name="PRIMER_SEKUNDER" value="<?= htmlentities($gi_cell['PRIMER_SEKUNDER'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">TIPE_CT</label>
							<input type="text" class="form-control" name="TIPE_CT" value="<?= htmlentities($gi_cell['TIPE_CT'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">OWNERSYSID</label>
							<input type="text" class="form-control" name="OWNERSYSID" value="<?= htmlentities($gi_cell['OWNERSYSID'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">ISOLASI_KUBIKEL</label>
							<select class="form-control" name="ISOLASI_KUBIKEL">
								<option value="" disabled <?= empty($gi_cell['ISOLASI_KUBIKEL']) ? 'selected' : ''; ?>>-- Pilih ISOLASI_KUBIKEL --</option>
								<option value="Full Insulated" <?= (isset($gi_cell['ISOLASI_KUBIKEL']) && $gi_cell['ISOLASI_KUBIKEL'] == 'Full Insulated') ? 'selected' : ''; ?>>Full Insulated</option>
								<option value="Air Insulated" <?= (isset($gi_cell['ISOLASI_KUBIKEL']) && $gi_cell['ISOLASI_KUBIKEL'] == 'Air Insulated') ? 'selected' : ''; ?>>Air Insulated</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">JENIS_MVCELL</label>
							<select class="form-control" name="JENIS_MVCELL">
								<option value="" disabled <?= empty($gi_cell['JENIS_MVCELL']) ? 'selected' : ''; ?>>-- Pilih JENIS_MVCELL --</option>
								<option value="Pemutus Tenaga" <?= (isset($gi_cell['JENIS_MVCELL']) && $gi_cell['JENIS_MVCELL'] == 'Pemutus Tenaga') ? 'selected' : ''; ?>>Pemutus Tenaga</option>
								<option value="Pemisah" <?= (isset($gi_cell['JENIS_MVCELL']) && $gi_cell['JENIS_MVCELL'] == 'Pemisah') ? 'selected' : ''; ?>>Pemisah</option>
								<option value="Interface / Terminal Kabel" <?= (isset($gi_cell['JENIS_MVCELL']) && $gi_cell['JENIS_MVCELL'] == 'Interface / Terminal Kabel') ? 'selected' : ''; ?>>Interface / Terminal Kabel</option>
								<option value="Pemutus" <?= (isset($gi_cell['JENIS_MVCELL']) && $gi_cell['JENIS_MVCELL'] == 'Pemutus') ? 'selected' : ''; ?>>Pemutus</option>
								<option value="Pengaman Trafo" <?= (isset($gi_cell['JENIS_MVCELL']) && $gi_cell['JENIS_MVCELL'] == 'Pengaman Trafo') ? 'selected' : ''; ?>>Pengaman Trafo</option>
								<option value="Pengukuran" <?= (isset($gi_cell['JENIS_MVCELL']) && $gi_cell['JENIS_MVCELL'] == 'Pengukuran') ? 'selected' : ''; ?>>Pengukuran</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">TH_BUAT</label>
							<input type="text" class="form-control" name="TH_BUAT" value="<?= htmlentities($gi_cell['TH_BUAT'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">TYPE_MVCELL</label>
							<input type="text" class="form-control" name="TYPE_MVCELL" value="<?= htmlentities($gi_cell['TYPE_MVCELL'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">CELL_TYPE</label>
							<select class="form-control" name="CELL_TYPE">
								<option value="">-- Pilih --</option>
								<option value="CT" <?= (isset($gi_cell['CELL_TYPE']) && $gi_cell['CELL_TYPE'] == 'CT') ? 'selected' : ''; ?>>CT</option>
								<option value="MVCELL" <?= (isset($gi_cell['CELL_TYPE']) && $gi_cell['CELL_TYPE'] == 'MVCELL') ? 'selected' : ''; ?>>MVCELL</option>
							</select>
						</div>
					</div>
					<div class="mt-4">
						<a href="<?= base_url('Gi_cell'); ?>" class="btn btn-secondary">Batal</a>
						<button type="submit" class="btn btn-primary">Simpan Perubahan</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</main>
