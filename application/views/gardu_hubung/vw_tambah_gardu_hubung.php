<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>
	<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
		<div class="container-fluid py-1 px-3">
			<h6 class="font-weight-bolder text-white mb-0">
				<i class="fas fa-network-wired me-2"></i> Tambah Gardu Hubung
			</h6>
		</div>
	</nav>
	<div class="container-fluid py-4">
		<div class="card shadow border-0 rounded-4">
			<div class="card-header bg-gradient-primary text-white"><strong>Form Tambah Gardu Hubung</strong></div>
			<div class="card-body">
				<form action="<?= base_url('Gardu_hubung/tambah'); ?>" method="post">
					<div class="row g-3">
						<!-- Fields matching database structure (same as edit form) -->
						<div class="col-md-4">
							<label class="form-label">UP3_2D</label>
							<select class="form-control" name="UP3_2D">
								<option value="" disabled selected>-- Pilih UP3_2D --</option>
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
							<label class="form-label">UNITNAME_UP3</label>
							<select class="form-control" name="UNITNAME_UP3">
								<option value="" disabled selected>-- Pilih UNITNAME_UP3 --</option>
								<option value="UP2D RIAU">UP2D RIAU</option>
								<option value="PEKANBARU">PEKANBARU</option>
								<option value="DUMAI">DUMAI</option>
								<option value="TANJUNG PINANG">TANJUNG PINANG</option>
								<option value="RENGAT">RENGAT</option>
								<option value="BANGKINANG">BANGKINANG</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">CXUNIT</label>
							<input type="text" class="form-control" name="CXUNIT">
						</div>

						<div class="col-md-4">
							<label class="form-label">UNITNAME</label>
							<select class="form-control" name="UNITNAME">
								<option value="" disabled selected>-- Pilih UNITNAME --</option>
								<option value="UP2D RIAU">UP2D RIAU</option>
								<option value="PANAM">PANAM</option>
								<option value="PANGKALAN KERINCI">PANGKALAN KERINCI</option>
								<option value="PEKANBARU KOTA BARAT">PEKANBARU KOTA BARAT</option>
								<option value="PEKANBARU KOTA TIMUR">PEKANBARU KOTA TIMUR</option>
								<option value="PERAWANG">PERAWANG</option>
								<option value="RUMBAI">RUMBAI</option>
								<option value="SIAK SRI INDRAPURA">SIAK SRI INDRAPURA</option>
								<option value="SIMPANG TIGA">SIMPANG TIGA</option>
								<option value="BAGAN BATU">BAGAN BATU</option>
								<option value="BAGAN SIAPI-API">BAGAN SIAPI-API</option>
								<option value="BENGKALIS">BENGKALIS</option>
								<option value="DUMAI KOTA">DUMAI KOTA</option>
								<option value="DURI">DURI</option>
								<option value="SELATPANJANG">SELATPANJANG</option>
								<option value="ANAMBAS">ANAMBAS</option>
								<option value="BELAKANGPADANG">BELAKANGPADANG</option>
								<option value="BINTAN CENTER">BINTAN CENTER</option>
								<option value="DABO SINGKEP">DABO SINGKEP</option>
								<option value="KIJANG">KIJANG</option>
								<option value="RANAI">RANAI</option>
								<option value="TANJUNG BALAI KARIMUN">TANJUNG BALAI KARIMUN</option>
								<option value="TANJUNG BATU">TANJUNG BATU</option>
								<option value="TANJUNG UBAN">TANJUNG UBAN</option>
								<option value="TANJUNGPINANG KOTA">TANJUNGPINANG KOTA</option>
								<option value="AIR MOLEK">AIR MOLEK</option>
								<option value="KUALA ENOK">KUALA ENOK</option>
								<option value="RENGAT">RENGAT</option>
								<option value="RENGAT KOTA">RENGAT KOTA</option>
								<option value="TALUK KUANTAN">TALUK KUANTAN</option>
								<option value="TEMBILAHAN">TEMBILAHAN</option>
								<option value="BANGKINANG">BANGKINANG</option>
								<option value="KAMPAR">KAMPAR</option>
								<option value="LIPAT KAIN">LIPAT KAIN</option>
								<option value="PASIR PANGARAIAN">PASIR PANGARAIAN</option>
								<option value="UJUNG BATU">UJUNG BATU</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">LOCATION</label>
							<input type="text" class="form-control" name="LOCATION">
						</div>
						<div class="col-md-4">
							<label class="form-label">SSOTNUMBER <span class="text-danger">*</span></label>
							<input type="text" class="form-control" name="SSOTNUMBER" required>
						</div>

						<div class="col-md-6">
							<label class="form-label">DESCRIPTION</label>
							<input type="text" class="form-control" name="DESCRIPTION">
						</div>
						<div class="col-md-6">
							<label class="form-label">STATUS</label>
							<select class="form-control" name="STATUS">
								<option value="" disabled selected>-- Pilih STATUS --</option>
								<option value="OPERATING">OPERATING</option>
								<option value="INACTIVE">INACTIVE</option>
								<option value="NOT READY">NOT READY</option>
								<option value="REQOPERATING">REQOPERATING</option>
							</select>
						</div>

						<div class="col-md-4">
							<label class="form-label">TUJDNUMBER</label>
							<input type="text" class="form-control" name="TUJDNUMBER">
						</div>
						<div class="col-md-4">
							<label class="form-label">ASSETCLASSHI</label>
							<input type="text" class="form-control" name="ASSETCLASSHI">
						</div>
						<div class="col-md-4">
							<label class="form-label">SADDRESSCODE</label>
							<input type="text" class="form-control" name="SADDRESSCODE">
						</div>

						<div class="col-md-6">
							<label class="form-label">CXCLASSIFICATIONDESC</label>
							<input type="text" class="form-control" name="CXCLASSIFICATIONDESC">
						</div>
						<div class="col-md-6">
							<label class="form-label">PENYULANG</label>
							<input type="text" class="form-control" name="PENYULANG">
						</div>

						<div class="col-md-6">
							<label class="form-label">PARENT</label>
							<input type="text" class="form-control" name="PARENT">
						</div>
						<div class="col-md-6">
							<label class="form-label">PARENT_DESCRIPTION</label>
							<input type="text" class="form-control" name="PARENT_DESCRIPTION">
						</div>

						<div class="col-md-4">
							<label class="form-label">INSTALLDATE</label>
							<input type="date" class="form-control" name="INSTALLDATE">
						</div>
						<div class="col-md-4">
							<label class="form-label">ACTUALOPRDATE</label>
							<input type="date" class="form-control" name="ACTUALOPRDATE">
						</div>
						<div class="col-md-4">
							<label class="form-label">CHANGEDATE</label>
							<input type="text" class="form-control" name="CHANGEDATE">
						</div>

						<div class="col-md-4">
							<label class="form-label">CHANGEBY</label>
							<input type="text" class="form-control" name="CHANGEBY">
						</div>

						<div class="col-md-4">
							<label class="form-label">LATITUDEY</label>
							<input type="text" class="form-control" name="LATITUDEY" placeholder="Contoh: -6.2088">
						</div>
						<div class="col-md-4">
							<label class="form-label">LONGITUDEX</label>
							<input type="text" class="form-control" name="LONGITUDEX" placeholder="Contoh: 106.8456">
						</div>

						<div class="col-md-6">
							<label class="form-label">FORMATTEDADDRESS</label>
							<input type="text" class="form-control" name="FORMATTEDADDRESS">
						</div>
						<div class="col-md-6">
							<label class="form-label">STREETADDRESS</label>
							<input type="text" class="form-control" name="STREETADDRESS">
						</div>

						<div class="col-md-4">
							<label class="form-label">CITY</label>
							<input type="text" class="form-control" name="CITY">
						</div>
						<div class="col-md-4">
							<label class="form-label">ISASSET</label>
							<input type="text" class="form-control" name="ISASSET">
						</div>
						<div class="col-md-4">
							<label class="form-label">STATUS_KEPEMILIKAN</label>
							<select class="form-control" name="STATUS_KEPEMILIKAN">
								<option value="" disabled selected>-- Pilih STATUS_KEPEMILIKAN --</option>
								<option value="PLN">PLN</option>
								<option value="NON PLN">NON PLN</option>
							</select>
						</div>

						<div class="col-md-4">
							<label class="form-label">EXTERNALREFID</label>
							<input type="text" class="form-control" name="EXTERNALREFID">
						</div>
						<div class="col-md-4">
							<label class="form-label">JENIS_PELAYANAN</label>
							<select class="form-control" name="JENIS_PELAYANAN">
								<option value="" disabled selected>-- Pilih JENIS_PELAYANAN --</option>
								<option value="Umum">Umum</option>
								<option value="Campuran">Campuran</option>
								<option value="Distribusi">Distribusi</option>
								<option value="Khusus">Khusus</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">NO_SLO</label>
							<input type="text" class="form-control" name="NO_SLO">
						</div>

						<div class="col-md-4">
							<label class="form-label">OWNERSYSID</label>
							<input type="text" class="form-control" name="OWNERSYSID">
						</div>
						<div class="col-md-4">
							<label class="form-label">SLOACTIVEDATE</label>
							<input type="date" class="form-control" name="SLOACTIVEDATE">
						</div>
						<div class="col-md-4">
							<label class="form-label">STATUS_RC</label>
							<select class="form-control" name="STATUS_RC">
								<option value="" disabled selected>-- Pilih STATUS_RC --</option>
								<option value="ADA">ADA</option>
								<option value="TIDAK ADA">TIDAK ADA</option>
							</select>
						</div>

						<div class="col-md-6">
							<label class="form-label">TYPE_GARDU</label>
							<input type="text" class="form-control" name="TYPE_GARDU">
						</div>
					</div>
					<div class="mt-4">
						<a href="<?= base_url('Gardu_hubung'); ?>" class="btn btn-secondary">Batal</a>
						<button type="submit" class="btn btn-primary">Simpan</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</main>
