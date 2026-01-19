<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>
	<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
		<div class="container-fluid py-1 px-3">
			<h6 class="font-weight-bolder text-white mb-0">
				<i class="fas fa-toggle-on me-2 text-warning"></i> Tambah Pemutus (LBS - RECLOSER)
			</h6>
		</div>
	</nav>
	<div class="container-fluid py-4">
		<div class="card shadow border-0 rounded-4">
			<div class="card-header bg-gradient-primary text-white"><strong>Form Tambah Pemutus</strong></div>
			<div class="card-body">
				<form action="<?= base_url('Pemutus/tambah'); ?>" method="post">
					<div class="row g-3">
						<!-- 31 database columns from lbs_recloser table (correct structure) -->
						<div class="col-md-4">
							<label class="form-label">CXUNIT</label>
							<input type="text" class="form-control" name="CXUNIT">
						</div>
						<div class="col-md-4">
							<label class="form-label">UNITNAME</label>
							<select class="form-select" name="UNITNAME">
								<option value="">-- Pilih UNITNAME --</option>
								<option value="PEKANBARU KOTA TIMUR">PEKANBARU KOTA TIMUR</option>
								<option value="PEKANBARU KOTA BARAT">PEKANBARU KOTA BARAT</option>
								<option value="SIMPANG TIGA">SIMPANG TIGA</option>
								<option value="RUMBAI">RUMBAI</option>
								<option value="PANAM">PANAM</option>
								<option value="PERAWANG">PERAWANG</option>
								<option value="SIAK SRI INDRAPURA">SIAK SRI INDRAPURA</option>
								<option value="PANGKALAN KERINCI">PANGKALAN KERINCI</option>
								<option value="DURI">DURI</option>
								<option value="BAGAN SIAPI-API">BAGAN SIAPI-API</option>
								<option value="BENGKALIS">BENGKALIS</option>
								<option value="SELATPANJANG">SELATPANJANG</option>
								<option value="DUMAI KOTA">DUMAI KOTA</option>
								<option value="BAGAN BATU">BAGAN BATU</option>
								<option value="KIJANG">KIJANG</option>
								<option value="TANJUNG UBAN">TANJUNG UBAN</option>
								<option value="TANJUNG BALAI KARIMUN">TANJUNG BALAI KARIMUN</option>
								<option value="TANJUNG BATU">TANJUNG BATU</option>
								<option value="DABO SINGKEP">DABO SINGKEP</option>
								<option value="RANAI">RANAI</option>
								<option value="TANJUNGPINANG KOTA">TANJUNGPINANG KOTA</option>
								<option value="ANAMBAS">ANAMBAS</option>
								<option value="RENGAT KOTA">RENGAT KOTA</option>
								<option value="TALUK KUANTAN">TALUK KUANTAN</option>
								<option value="KUALA ENOK">KUALA ENOK</option>
								<option value="TEMBILAHAN">TEMBILAHAN</option>
								<option value="AIR MOLEK">AIR MOLEK</option>
								<option value="BANGKINANG">BANGKINANG</option>
								<option value="KAMPAR">KAMPAR</option>
								<option value="LIPAT KAIN">LIPAT KAIN</option>
								<option value="PASIR PANGARAIAN">PASIR PANGARAIAN</option>
								<option value="UJUNG BATU">UJUNG BATU</option>
								<option value="UP2D RIAU">UP2D RIAU</option>
								<option value="BELAKANGPADANG">BELAKANGPADANG</option>
								<option value="DUMAI">DUMAI</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">UP3_2D</label>
							<select class="form-select" name="UP3_2D">
								<option value="">-- Pilih UP3_2D --</option>
								<option value="UP2D.6456">UP2D.6456</option>
								<option value="UP3.6411">UP3.6411</option>
								<option value="UP3.6412">UP3.6412</option>
								<option value="UP3.6413">UP3.6413</option>
								<option value="UP3.6414">UP3.6414</option>
								<option value="UP3.6415">UP3.6415</option>
								<option value="UPT.3217">UPT.3217</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">ASSETNUM</label>
							<input type="text" class="form-control" name="ASSETNUM">
						</div>
						<div class="col-md-8">
							<label class="form-label">SSOTNUMBER <span class="text-danger">*</span></label>
							<input type="text" class="form-control" name="SSOTNUMBER" required>
						</div>
						<div class="col-md-6">
							<label class="form-label">LOCATION</label>
							<input type="text" class="form-control" name="LOCATION">
						</div>
						<div class="col-md-6">
							<label class="form-label">NAMA_LOCATION</label>
							<input type="text" class="form-control" name="NAMA_LOCATION">
						</div>
						<div class="col-md-12">
							<label class="form-label">DESCRIPTION</label>
							<textarea class="form-control" name="DESCRIPTION" rows="2"></textarea>
						</div>
						<div class="col-md-4">
							<label class="form-label">VENDOR</label>
							<input type="text" class="form-control" name="VENDOR">
						</div>
						<div class="col-md-4">
							<label class="form-label">MANUFACTURER</label>
							<input type="text" class="form-control" name="MANUFACTURER">
						</div>
						<div class="col-md-4">
							<label class="form-label">INSTALLDATE</label>
							<input type="date" class="form-control" name="INSTALLDATE">
						</div>
						<div class="col-md-3">
							<label class="form-label">PRIORITY</label>
							<input type="number" class="form-control" name="PRIORITY">
						</div>
						<div class="col-md-3">
							<label class="form-label">STATUS</label>
							<select class="form-select" name="STATUS">
								<option value="">-- Pilih STATUS --</option>
								<option value="OPERATING">OPERATING</option>
								<option value="INACTIVE">INACTIVE</option>
								<option value="NOT READY">NOT READY</option>
								<option value="REQOPERATING">REQOPERATING</option>
							</select>
						</div>
						<div class="col-md-3">
							<label class="form-label">TUJDNUMBER</label>
							<input type="text" class="form-control" name="TUJDNUMBER">
						</div>
						<div class="col-md-3">
							<label class="form-label">CHANGEBY</label>
							<input type="text" class="form-control" name="CHANGEBY">
						</div>
						<div class="col-md-6">
							<label class="form-label">CHANGEDATE</label>
							<input type="datetime-local" class="form-control" name="CHANGEDATE">
						</div>
						<div class="col-md-6">
							<label class="form-label">CXCLASSIFICATIONDESC</label>
							<input type="text" class="form-control" name="CXCLASSIFICATIONDESC">
						</div>
						<div class="col-md-3">
							<label class="form-label">LONGITUDEX</label>
							<input type="text" class="form-control" name="LONGITUDEX">
						</div>
						<div class="col-md-3">
							<label class="form-label">LATITUDEY</label>
							<input type="text" class="form-control" name="LATITUDEY">
						</div>
						<div class="col-md-3">
							<label class="form-label">ISASSET</label>
							<select class="form-select" name="ISASSET">
								<option value="">-- Pilih --</option>
								<option value="1">Ya</option>
								<option value="0">Tidak</option>
							</select>
						</div>
						<div class="col-md-3">
							<label class="form-label">PEREDAM</label>
							<select class="form-select" name="PEREDAM">
								<option value="">-- Pilih --</option>
								<option value="VACUUM">VACUUM</option>
								<option value="SF6">SF6</option>
								<option value="MINYAK">MINYAK</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">STATUS_KEPEMILIKAN</label>
							<select class="form-select" name="STATUS_KEPEMILIKAN">
								<option value="">-- Pilih STATUS_KEPEMILIKAN --</option>
								<option value="PLN">PLN</option>
								<option value="NON PLN">NON PLN</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">CXPENYULANG</label>
							<input type="text" class="form-control" name="CXPENYULANG">
						</div>
						<div class="col-md-4">
							<label class="form-label">TH_BUAT</label>
							<input type="text" class="form-control" name="TH_BUAT">
						</div>
						<div class="col-md-4">
							<label class="form-label">TYPE_LBS</label>
							<select class="form-select" name="TYPE_LBS">
								<option value="">-- Pilih --</option>
								<option value="BERBEBAN">BERBEBAN</option>
								<option value="TIDAK BERBEBAN">TIDAK BERBEBAN</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">MODE_OPERASI (Recloser)</label>
							<input type="text" class="form-control" name="MODE_OPERASI">
						</div>
						<div class="col-md-4">
							<label class="form-label">TYPE_RECLOSER</label>
							<input type="text" class="form-control" name="TYPE_RECLOSER">
						</div>
						<div class="col-md-4">
							<label class="form-label">MODE_OPR (Sectio)</label>
							<select class="form-select" name="MODE_OPR">
								<option value="">-- Pilih --</option>
								<option value="Remote">Remote</option>
								<option value="Manual">Manual</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">TYPE_OPERASI</label>
							<select class="form-select" name="TYPE_OPERASI">
								<option value="">-- Pilih --</option>
								<option value="BERTEGANGAN">BERTEGANGAN</option>
								<option value="TIDAK BERTEGANGAN">TIDAK BERTEGANGAN</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">TYPE_SECTIONALIZER</label>
							<select class="form-select" name="TYPE_SECTIONALIZER">
								<option value="">-- Pilih --</option>
								<option value="BERBEBAN">BERBEBAN</option>
								<option value="TIDAK BERBEBAN">TIDAK BERBEBAN</option>
							</select>
						</div>
						<div class="col-md-12">
							<label class="form-label">PEMUTUS_TYPE <span class="text-danger">*</span></label>
							<select class="form-select" name="PEMUTUS_TYPE" required>
								<option value="">-- Pilih Type Pemutus --</option>
								<option value="LBS">LBS</option>
								<option value="RECLOSER">RECLOSER</option>
								<option value="SECTIONALIZER">SECTIONALIZER</option>
							</select>
						</div>
					</div>
					<div class="mt-4">
						<a href="<?= base_url('Pemutus'); ?>" class="btn btn-secondary">Batal</a>
						<button type="submit" class="btn btn-primary">Simpan</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</main>
