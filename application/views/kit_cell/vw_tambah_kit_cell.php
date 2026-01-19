<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>
	<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
		<div class="container-fluid py-1 px-3">
			<h6 class="font-weight-bolder text-white mb-0">
				<i class="fas fa-microchip me-2 text-primary"></i> Tambah Penyulang
			</h6>
		</div>
	</nav>
	<div class="container-fluid py-4">
		<div class="card shadow border-0 rounded-4">
			<div class="card-header bg-gradient-primary text-white"><strong>Form Tambah KIT Cell</strong></div>
			<div class="card-body">
				<?php if ($this->session->flashdata('error')): ?>
					<div class="alert alert-danger text-white">
						<?= $this->session->flashdata('error'); ?>
					</div>
				<?php endif; ?>
				<form action="<?= base_url('Kit_cell/tambah'); ?>" method="post">
					<div class="row g-3">
						<!-- 34 database columns from kit_cell table -->
						<div class="col-md-4">
							<label class="form-label">CXUNIT</label>
							<input type="text" class="form-control" name="CXUNIT">
						</div>
						<div class="col-md-4">
							<label class="form-label">UNITNAME</label>
							<select class="form-control" name="UNITNAME">
								<option value="" disabled selected>-- Pilih UNITNAME --</option>
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
							<label class="form-label">ASSETNUM</label>
							<input type="text" class="form-control" name="ASSETNUM">
						</div>
						<div class="col-md-6">
							<label class="form-label">SSOTNUMBER <span class="text-danger">*</span></label>
							<input type="text" class="form-control" name="SSOTNUMBER" required>
						</div>
						<div class="col-md-6">
							<label class="form-label">LOCATION</label>
							<input type="text" class="form-control" name="LOCATION">
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
							<input type="text" class="form-control" name="PRIORITY">
						</div>
						<div class="col-md-3">
							<label class="form-label">STATUS</label>
							<select class="form-control" name="STATUS">
								<option value="" disabled selected>-- Pilih STATUS --</option>
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
						<div class="col-md-4">
							<label class="form-label">CHANGEDATE</label>
							<input type="date" class="form-control" name="CHANGEDATE">
						</div>
						<div class="col-md-4">
							<label class="form-label">CXCLASSIFICATIONDESC</label>
							<input type="text" class="form-control" name="CXCLASSIFICATIONDESC">
						</div>
						<div class="col-md-4">
							<label class="form-label">CXPENYULANG</label>
							<input type="text" class="form-control" name="CXPENYULANG">
						</div>
						<div class="col-md-6">
							<label class="form-label">NAMA_LOCATION</label>
							<input type="text" class="form-control" name="NAMA_LOCATION">
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
							<input type="text" class="form-control" name="ISASSET">
						</div>
						<div class="col-md-3">
							<label class="form-label">STATUS_KEPEMILIKAN</label>
							<select class="form-control" name="STATUS_KEPEMILIKAN">
								<option value="" disabled selected>-- Pilih STATUS_KEPEMILIKAN --</option>
								<option value="PLN">PLN</option>
								<option value="NON PLN">NON PLN</option>
							</select>
						</div>
						<div class="col-md-3">
							<label class="form-label">BURDEN</label>
							<input type="text" class="form-control" name="BURDEN">
						</div>
						<div class="col-md-3">
							<label class="form-label">FAKTOR_KALI</label>
							<input type="text" class="form-control" name="FAKTOR_KALI">
						</div>
						<div class="col-md-3">
							<label class="form-label">JENIS_CT</label>
							<input type="text" class="form-control" name="JENIS_CT">
						</div>
						<div class="col-md-3">
							<label class="form-label">KELAS_CT</label>
							<input type="text" class="form-control" name="KELAS_CT">
						</div>
						<div class="col-md-4">
							<label class="form-label">KELAS_PROTEKSI</label>
							<input type="text" class="form-control" name="KELAS_PROTEKSI">
						</div>
						<div class="col-md-4">
							<label class="form-label">PRIMER_SEKUNDER</label>
							<input type="text" class="form-control" name="PRIMER_SEKUNDER">
						</div>
						<div class="col-md-4">
							<label class="form-label">TIPE_CT</label>
							<input type="text" class="form-control" name="TIPE_CT">
						</div>
						<div class="col-md-4">
							<label class="form-label">OWNERSYSID</label>
							<input type="text" class="form-control" name="OWNERSYSID">
						</div>
						<div class="col-md-4">
							<label class="form-label">ISOLASI_KUBIKEL</label>
							<select class="form-control" name="ISOLASI_KUBIKEL">
								<option value="" disabled selected>-- Pilih ISOLASI_KUBIKEL --</option>
								<option value="Full Insulated">Full Insulated</option>
								<option value="Air Insulated">Air Insulated</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">JENIS_MVCELL</label>
							<select class="form-control" name="JENIS_MVCELL">
								<option value="" disabled selected>-- Pilih JENIS_MVCELL --</option>
								<option value="Pemutus Tenaga">Pemutus Tenaga</option>
								<option value="Pemisah">Pemisah</option>
								<option value="Interface / Terminal Kabel">Interface / Terminal Kabel</option>
								<option value="Pemutus">Pemutus</option>
								<option value="Pengaman Trafo">Pengaman Trafo</option>
								<option value="Pengukuran">Pengukuran</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">TH_BUAT</label>
							<input type="text" class="form-control" name="TH_BUAT">
						</div>
						<div class="col-md-4">
							<label class="form-label">TYPE_MVCELL</label>
							<input type="text" class="form-control" name="TYPE_MVCELL">
						</div>
						<div class="col-md-4">
							<label class="form-label">CELL_TYPE</label>
							<select class="form-select" name="CELL_TYPE">
								<option value="">-- Pilih --</option>
								<option value="CT">CT</option>
								<option value="MVCELL">MVCELL</option>
							</select>
						</div>
					</div>
					<div class="mt-4">
						<a href="<?= base_url('Kit_cell'); ?>" class="btn btn-secondary">Batal</a>
						<button type="submit" class="btn btn-primary">Simpan</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</main>
