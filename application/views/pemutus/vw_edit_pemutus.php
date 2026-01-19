<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>
	<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
		<div class="container-fluid py-1 px-3">
			<h6 class="font-weight-bolder text-white mb-0">
				<i class="fas fa-toggle-on me-2 text-warning"></i> Edit Pemutus (LBS - RECLOSER)
			</h6>
		</div>
	</nav>
	<div class="container-fluid py-4">
		<div class="card shadow border-0 rounded-4">
			<div class="card-header bg-gradient-primary text-white"><strong>Form Edit Pemutus</strong></div>
			<div class="card-body">
				<form action="<?= base_url('Pemutus/edit/' . ($pemutus['SSOTNUMBER'] ?? '')); ?>" method="post">
					<input type="hidden" name="original_SSOTNUMBER" value="<?= htmlentities($pemutus['SSOTNUMBER'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
					<div class="row g-3">
						<!-- 31 database columns from lbs_recloser table (correct structure) -->
						<div class="col-md-4">
							<label class="form-label">CXUNIT</label>
							<input type="text" class="form-control" name="CXUNIT" value="<?= htmlentities($pemutus['CXUNIT'] ?? ''); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">UNITNAME</label>
							<select class="form-select" name="UNITNAME">
								<option value="">-- Pilih UNITNAME --</option>
								<option value="PEKANBARU KOTA TIMUR" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'PEKANBARU KOTA TIMUR') ? 'selected' : ''; ?>>PEKANBARU KOTA TIMUR</option>
								<option value="PEKANBARU KOTA BARAT" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'PEKANBARU KOTA BARAT') ? 'selected' : ''; ?>>PEKANBARU KOTA BARAT</option>
								<option value="SIMPANG TIGA" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'SIMPANG TIGA') ? 'selected' : ''; ?>>SIMPANG TIGA</option>
								<option value="RUMBAI" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'RUMBAI') ? 'selected' : ''; ?>>RUMBAI</option>
								<option value="PANAM" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'PANAM') ? 'selected' : ''; ?>>PANAM</option>
								<option value="PERAWANG" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'PERAWANG') ? 'selected' : ''; ?>>PERAWANG</option>
								<option value="SIAK SRI INDRAPURA" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'SIAK SRI INDRAPURA') ? 'selected' : ''; ?>>SIAK SRI INDRAPURA</option>
								<option value="PANGKALAN KERINCI" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'PANGKALAN KERINCI') ? 'selected' : ''; ?>>PANGKALAN KERINCI</option>
								<option value="DURI" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'DURI') ? 'selected' : ''; ?>>DURI</option>
								<option value="BAGAN SIAPI-API" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'BAGAN SIAPI-API') ? 'selected' : ''; ?>>BAGAN SIAPI-API</option>
								<option value="BENGKALIS" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'BENGKALIS') ? 'selected' : ''; ?>>BENGKALIS</option>
								<option value="SELATPANJANG" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'SELATPANJANG') ? 'selected' : ''; ?>>SELATPANJANG</option>
								<option value="DUMAI KOTA" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'DUMAI KOTA') ? 'selected' : ''; ?>>DUMAI KOTA</option>
								<option value="BAGAN BATU" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'BAGAN BATU') ? 'selected' : ''; ?>>BAGAN BATU</option>
								<option value="KIJANG" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'KIJANG') ? 'selected' : ''; ?>>KIJANG</option>
								<option value="TANJUNG UBAN" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'TANJUNG UBAN') ? 'selected' : ''; ?>>TANJUNG UBAN</option>
								<option value="TANJUNG BALAI KARIMUN" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'TANJUNG BALAI KARIMUN') ? 'selected' : ''; ?>>TANJUNG BALAI KARIMUN</option>
								<option value="TANJUNG BATU" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'TANJUNG BATU') ? 'selected' : ''; ?>>TANJUNG BATU</option>
								<option value="DABO SINGKEP" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'DABO SINGKEP') ? 'selected' : ''; ?>>DABO SINGKEP</option>
								<option value="RANAI" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'RANAI') ? 'selected' : ''; ?>>RANAI</option>
								<option value="TANJUNGPINANG KOTA" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'TANJUNGPINANG KOTA') ? 'selected' : ''; ?>>TANJUNGPINANG KOTA</option>
								<option value="ANAMBAS" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'ANAMBAS') ? 'selected' : ''; ?>>ANAMBAS</option>
								<option value="RENGAT KOTA" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'RENGAT KOTA') ? 'selected' : ''; ?>>RENGAT KOTA</option>
								<option value="TALUK KUANTAN" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'TALUK KUANTAN') ? 'selected' : ''; ?>>TALUK KUANTAN</option>
								<option value="KUALA ENOK" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'KUALA ENOK') ? 'selected' : ''; ?>>KUALA ENOK</option>
								<option value="TEMBILAHAN" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'TEMBILAHAN') ? 'selected' : ''; ?>>TEMBILAHAN</option>
								<option value="AIR MOLEK" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'AIR MOLEK') ? 'selected' : ''; ?>>AIR MOLEK</option>
								<option value="BANGKINANG" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'BANGKINANG') ? 'selected' : ''; ?>>BANGKINANG</option>
								<option value="KAMPAR" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'KAMPAR') ? 'selected' : ''; ?>>KAMPAR</option>
								<option value="LIPAT KAIN" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'LIPAT KAIN') ? 'selected' : ''; ?>>LIPAT KAIN</option>
								<option value="PASIR PANGARAIAN" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'PASIR PANGARAIAN') ? 'selected' : ''; ?>>PASIR PANGARAIAN</option>
								<option value="UJUNG BATU" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'UJUNG BATU') ? 'selected' : ''; ?>>UJUNG BATU</option>
								<option value="UP2D RIAU" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'UP2D RIAU') ? 'selected' : ''; ?>>UP2D RIAU</option>
								<option value="BELAKANGPADANG" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'BELAKANGPADANG') ? 'selected' : ''; ?>>BELAKANGPADANG</option>
								<option value="DUMAI" <?= (isset($pemutus['UNITNAME']) && $pemutus['UNITNAME'] == 'DUMAI') ? 'selected' : ''; ?>>DUMAI</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">UP3_2D</label>
							<select class="form-select" name="UP3_2D">
								<option value="">-- Pilih UP3_2D --</option>
								<option value="UP2D.6456" <?= (isset($pemutus['UP3_2D']) && $pemutus['UP3_2D'] == 'UP2D.6456') ? 'selected' : ''; ?>>UP2D.6456</option>
								<option value="UP3.6411" <?= (isset($pemutus['UP3_2D']) && $pemutus['UP3_2D'] == 'UP3.6411') ? 'selected' : ''; ?>>UP3.6411</option>
								<option value="UP3.6412" <?= (isset($pemutus['UP3_2D']) && $pemutus['UP3_2D'] == 'UP3.6412') ? 'selected' : ''; ?>>UP3.6412</option>
								<option value="UP3.6413" <?= (isset($pemutus['UP3_2D']) && $pemutus['UP3_2D'] == 'UP3.6413') ? 'selected' : ''; ?>>UP3.6413</option>
								<option value="UP3.6414" <?= (isset($pemutus['UP3_2D']) && $pemutus['UP3_2D'] == 'UP3.6414') ? 'selected' : ''; ?>>UP3.6414</option>
								<option value="UP3.6415" <?= (isset($pemutus['UP3_2D']) && $pemutus['UP3_2D'] == 'UP3.6415') ? 'selected' : ''; ?>>UP3.6415</option>
								<option value="UPT.3217" <?= (isset($pemutus['UP3_2D']) && $pemutus['UP3_2D'] == 'UPT.3217') ? 'selected' : ''; ?>>UPT.3217</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">ASSETNUM</label>
							<input type="text" class="form-control" name="ASSETNUM" value="<?= htmlentities($pemutus['ASSETNUM'] ?? ''); ?>">
						</div>
						<div class="col-md-8">
							<label class="form-label">SSOTNUMBER <span class="text-danger">*</span></label>
							<input type="text" class="form-control" name="SSOTNUMBER" value="<?= htmlentities($pemutus['SSOTNUMBER'] ?? ''); ?>" required>
						</div>
						<div class="col-md-6">
							<label class="form-label">LOCATION</label>
							<input type="text" class="form-control" name="LOCATION" value="<?= htmlentities($pemutus['LOCATION'] ?? ''); ?>">
						</div>
						<div class="col-md-6">
							<label class="form-label">NAMA_LOCATION</label>
							<input type="text" class="form-control" name="NAMA_LOCATION" value="<?= htmlentities($pemutus['NAMA_LOCATION'] ?? ''); ?>">
						</div>
						<div class="col-md-12">
							<label class="form-label">DESCRIPTION</label>
							<textarea class="form-control" name="DESCRIPTION" rows="2"><?= htmlentities($pemutus['DESCRIPTION'] ?? ''); ?></textarea>
						</div>
						<div class="col-md-4">
							<label class="form-label">VENDOR</label>
							<input type="text" class="form-control" name="VENDOR" value="<?= htmlentities($pemutus['VENDOR'] ?? ''); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">MANUFACTURER</label>
							<input type="text" class="form-control" name="MANUFACTURER" value="<?= htmlentities($pemutus['MANUFACTURER'] ?? ''); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">INSTALLDATE</label>
							<input type="date" class="form-control" name="INSTALLDATE" value="<?= htmlentities($pemutus['INSTALLDATE'] ?? ''); ?>">
						</div>
						<div class="col-md-3">
							<label class="form-label">PRIORITY</label>
							<input type="number" class="form-control" name="PRIORITY" value="<?= htmlentities($pemutus['PRIORITY'] ?? ''); ?>">
						</div>
						<div class="col-md-3">
							<label class="form-label">STATUS</label>
							<select class="form-select" name="STATUS">
								<option value="">-- Pilih STATUS --</option>
								<option value="OPERATING" <?= (isset($pemutus['STATUS']) && $pemutus['STATUS'] == 'OPERATING') ? 'selected' : ''; ?>>OPERATING</option>
								<option value="INACTIVE" <?= (isset($pemutus['STATUS']) && $pemutus['STATUS'] == 'INACTIVE') ? 'selected' : ''; ?>>INACTIVE</option>
								<option value="NOT READY" <?= (isset($pemutus['STATUS']) && $pemutus['STATUS'] == 'NOT READY') ? 'selected' : ''; ?>>NOT READY</option>
								<option value="REQOPERATING" <?= (isset($pemutus['STATUS']) && $pemutus['STATUS'] == 'REQOPERATING') ? 'selected' : ''; ?>>REQOPERATING</option>
							</select>
						</div>
						<div class="col-md-3">
							<label class="form-label">TUJDNUMBER</label>
							<input type="text" class="form-control" name="TUJDNUMBER" value="<?= htmlentities($pemutus['TUJDNUMBER'] ?? ''); ?>">
						</div>
						<div class="col-md-3">
							<label class="form-label">CHANGEBY</label>
							<input type="text" class="form-control" name="CHANGEBY" value="<?= htmlentities($pemutus['CHANGEBY'] ?? ''); ?>">
						</div>
						<div class="col-md-6">
							<label class="form-label">CHANGEDATE</label>
							<input type="datetime-local" class="form-control" name="CHANGEDATE" value="<?= htmlentities($pemutus['CHANGEDATE'] ?? ''); ?>">
						</div>
						<div class="col-md-6">
							<label class="form-label">CXCLASSIFICATIONDESC</label>
							<input type="text" class="form-control" name="CXCLASSIFICATIONDESC" value="<?= htmlentities($pemutus['CXCLASSIFICATIONDESC'] ?? ''); ?>">
						</div>
						<div class="col-md-3">
							<label class="form-label">LONGITUDEX</label>
							<input type="text" class="form-control" name="LONGITUDEX" value="<?= htmlentities($pemutus['LONGITUDEX'] ?? ''); ?>">
						</div>
						<div class="col-md-3">
							<label class="form-label">LATITUDEY</label>
							<input type="text" class="form-control" name="LATITUDEY" value="<?= htmlentities($pemutus['LATITUDEY'] ?? ''); ?>">
						</div>
						<div class="col-md-3">
							<label class="form-label">ISASSET</label>
							<select class="form-select" name="ISASSET">
								<option value="">-- Pilih --</option>
								<option value="1" <?= (isset($pemutus['ISASSET']) && $pemutus['ISASSET'] == '1') ? 'selected' : ''; ?>>Ya</option>
								<option value="0" <?= (isset($pemutus['ISASSET']) && $pemutus['ISASSET'] == '0') ? 'selected' : ''; ?>>Tidak</option>
							</select>
						</div>
						<div class="col-md-3">
							<label class="form-label">PEREDAM</label>
							<select class="form-select" name="PEREDAM">
								<option value="">-- Pilih --</option>
								<option value="VACUUM" <?= (isset($pemutus['PEREDAM']) && $pemutus['PEREDAM'] == 'VACUUM') ? 'selected' : ''; ?>>VACUUM</option>
								<option value="SF6" <?= (isset($pemutus['PEREDAM']) && $pemutus['PEREDAM'] == 'SF6') ? 'selected' : ''; ?>>SF6</option>
								<option value="MINYAK" <?= (isset($pemutus['PEREDAM']) && $pemutus['PEREDAM'] == 'MINYAK') ? 'selected' : ''; ?>>MINYAK</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">STATUS_KEPEMILIKAN</label>
							<select class="form-select" name="STATUS_KEPEMILIKAN">
								<option value="">-- Pilih STATUS_KEPEMILIKAN --</option>
								<option value="PLN" <?= (isset($pemutus['STATUS_KEPEMILIKAN']) && $pemutus['STATUS_KEPEMILIKAN'] == 'PLN') ? 'selected' : ''; ?>>PLN</option>
								<option value="NON PLN" <?= (isset($pemutus['STATUS_KEPEMILIKAN']) && $pemutus['STATUS_KEPEMILIKAN'] == 'NON PLN') ? 'selected' : ''; ?>>NON PLN</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">CXPENYULANG</label>
							<input type="text" class="form-control" name="CXPENYULANG" value="<?= htmlentities($pemutus['CXPENYULANG'] ?? ''); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">TH_BUAT</label>
							<input type="text" class="form-control" name="TH_BUAT" value="<?= htmlentities($pemutus['TH_BUAT'] ?? ''); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">TYPE_LBS</label>
							<select class="form-select" name="TYPE_LBS">
								<option value="">-- Pilih --</option>
								<option value="BERBEBAN" <?= (isset($pemutus['TYPE_LBS']) && $pemutus['TYPE_LBS'] == 'BERBEBAN') ? 'selected' : ''; ?>>BERBEBAN</option>
								<option value="TIDAK BERBEBAN" <?= (isset($pemutus['TYPE_LBS']) && $pemutus['TYPE_LBS'] == 'TIDAK BERBEBAN') ? 'selected' : ''; ?>>TIDAK BERBEBAN</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">MODE_OPERASI (Recloser)</label>
							<input type="text" class="form-control" name="MODE_OPERASI" value="<?= htmlentities($pemutus['MODE_OPERASI'] ?? ''); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">TYPE_RECLOSER</label>
							<input type="text" class="form-control" name="TYPE_RECLOSER" value="<?= htmlentities($pemutus['TYPE_RECLOSER'] ?? ''); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">MODE_OPR (Sectio)</label>
							<select class="form-select" name="MODE_OPR">
								<option value="">-- Pilih --</option>
								<option value="Remote" <?= (isset($pemutus['MODE_OPR']) && $pemutus['MODE_OPR'] == 'Remote') ? 'selected' : ''; ?>>Remote</option>
								<option value="Manual" <?= (isset($pemutus['MODE_OPR']) && $pemutus['MODE_OPR'] == 'Manual') ? 'selected' : ''; ?>>Manual</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">TYPE_OPERASI</label>
							<select class="form-select" name="TYPE_OPERASI">
								<option value="">-- Pilih --</option>
								<option value="BERTEGANGAN" <?= (isset($pemutus['TYPE_OPERASI']) && $pemutus['TYPE_OPERASI'] == 'BERTEGANGAN') ? 'selected' : ''; ?>>BERTEGANGAN</option>
								<option value="TIDAK BERTEGANGAN" <?= (isset($pemutus['TYPE_OPERASI']) && $pemutus['TYPE_OPERASI'] == 'TIDAK BERTEGANGAN') ? 'selected' : ''; ?>>TIDAK BERTEGANGAN</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">TYPE_SECTIONALIZER</label>
							<select class="form-select" name="TYPE_SECTIONALIZER">
								<option value="">-- Pilih --</option>
								<option value="BERBEBAN" <?= (isset($pemutus['TYPE_SECTIONALIZER']) && $pemutus['TYPE_SECTIONALIZER'] == 'BERBEBAN') ? 'selected' : ''; ?>>BERBEBAN</option>
								<option value="TIDAK BERBEBAN" <?= (isset($pemutus['TYPE_SECTIONALIZER']) && $pemutus['TYPE_SECTIONALIZER'] == 'TIDAK BERBEBAN') ? 'selected' : ''; ?>>TIDAK BERBEBAN</option>
							</select>
						</div>
						<div class="col-md-12">
							<label class="form-label">PEMUTUS_TYPE <span class="text-danger">*</span></label>
							<select class="form-select" name="PEMUTUS_TYPE" required>
								<option value="">-- Pilih Type Pemutus --</option>
								<option value="LBS" <?= (isset($pemutus['PEMUTUS_TYPE']) && $pemutus['PEMUTUS_TYPE'] == 'LBS') ? 'selected' : ''; ?>>LBS</option>
								<option value="RECLOSER" <?= (isset($pemutus['PEMUTUS_TYPE']) && $pemutus['PEMUTUS_TYPE'] == 'RECLOSER') ? 'selected' : ''; ?>>RECLOSER</option>
								<option value="SECTIONALIZER" <?= (isset($pemutus['PEMUTUS_TYPE']) && $pemutus['PEMUTUS_TYPE'] == 'SECTIONALIZER') ? 'selected' : ''; ?>>SECTIONALIZER</option>
							</select>
						</div>
					</div>
					<div class="mt-4">
						<a href="<?= base_url('Pemutus'); ?>" class="btn btn-secondary">Batal</a>
						<button type="submit" class="btn btn-primary">Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</main>
