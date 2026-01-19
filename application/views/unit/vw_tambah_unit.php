<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>
	<!-- NAVBAR -->
	<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl"
		id="navbarBlur" data-scroll="false">
		<div class="container-fluid py-1 px-3">
			<h6 class="font-weight-bolder text-white mb-0">
				<i class="fas fa-building me-2 text-warning"></i> Tambah Data Unit
			</h6>
		</div>
	</nav>

	<!-- CONTENT -->
	<div class="container-fluid py-4">
		<div class="card shadow border-0 rounded-4">
			<div class="card-header bg-gradient-primary text-white">
				<strong>Form Tambah Unit</strong>
			</div>

			<div class="card-body">
				<form action="<?= base_url('Unit/tambah'); ?>" method="POST">

					<div class="row g-3">

						<!-- Unit Pelaksana -->
						<div class="col-md-6">
							<label class="form-label">Unit Pelaksana</label>
							<select name="UNIT_PELAKSANA" class="form-control" required>
								<option value="" disabled selected>-- Pilih Unit Pelaksana --</option>
								<option value="TANJUNG PINANG">TANJUNG PINANG</option>
								<option value="PEKANBARU">PEKANBARU</option>
								<option value="DUMAI">DUMAI</option>
								<option value="RENGAT">RENGAT</option>
								<option value="BANGKINANG">BANGKINANG</option>
							</select>
						</div>

						<!-- Unit Layanan (Dropdown) -->
						<div class="col-md-6">
							<label class="form-label">Unit Layanan</label>
							<select name="UNIT_LAYANAN" class="form-control" required>
								<option value="" disabled selected>-- Pilih Unit Layanan --</option>
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

								foreach ($unit_layanan_list as $val) :
									$safeVal = htmlentities($val, ENT_QUOTES, 'UTF-8');
								?>
									<option value="<?= $safeVal; ?>"><?= $safeVal; ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<!-- Longitude -->
						<div class="col-md-6">
							<label class="form-label">Longitude (X)</label>
							<input type="text" name="LONGITUDEX" class="form-control">
						</div>

						<!-- Latitude -->
						<div class="col-md-6">
							<label class="form-label">Latitude (Y)</label>
							<input type="text" name="LATITUDEY" class="form-control">
						</div>

						<!-- Address -->
						<div class="col-md-12">
							<label class="form-label">Alamat</label>
							<input type="text" name="ADDRESS" class="form-control">
						</div>

					</div>

					<!-- BUTTON -->
					<div class="mt-4">
						<a href="<?= base_url('Unit'); ?>" class="btn btn-secondary">
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
