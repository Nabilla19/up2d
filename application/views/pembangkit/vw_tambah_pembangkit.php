<main class="main-content position-relative border-radius-lg ">
	<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
		<div class="container-fluid py-1 px-3">
			<h6 class="font-weight-bolder text-white mb-0">
				<i class="fas fa-industry me-2 text-danger"></i> Tambah Pembangkit
			</h6>
		</div>
	</nav>
	<div class="container-fluid py-4">
		<div class="card shadow border-0 rounded-4">
			<div class="card-header bg-gradient-primary text-white"><strong>Form Tambah Pembangkit</strong></div>
			<div class="card-body">
				<form action="<?= base_url('Pembangkit/tambah'); ?>" method="post">
					<div class="row g-3">
						<div class="col-md-6">
							<label class="form-label">Unit Layanan</label>
							<input type="text" class="form-control" name="UNIT_LAYANAN" required>
						</div>
						<div class="col-md-6">
							<label class="form-label">Nama Pembangkit</label>
							<input type="text" class="form-control" name="PEMBANGKIT" required>
						</div>
						<div class="col-md-3">
							<label class="form-label">Longitude (X)</label>
							<input type="text" class="form-control" name="LONGITUDEX">
						</div>
						<div class="col-md-3">
							<label class="form-label">Latitude (Y)</label>
							<input type="text" class="form-control" name="LATITUDEY">
						</div>

						<div class="col-md-6">
							<label class="form-label">Status Operasi</label>
							<select name="STATUS_OPERASI" class="form-control" required>
								<option value="" disabled selected>-- Pilih Status Operasi --</option>
								<option value="OPERATING">OPERATING</option>
								<option value="NOT READY">NOT READY</option>
							</select>
						</div>

						<div class="col-md-2">
							<label class="form-label">INC</label>
							<input type="text" class="form-control" name="INC">
						</div>
						<div class="col-md-2">
							<label class="form-label">OGF</label>
							<input type="text" class="form-control" name="OGF">
						</div>
						<div class="col-md-2">
							<label class="form-label">Spare</label>
							<input type="text" class="form-control" name="SPARE">
						</div>
						<div class="col-md-2">
							<label class="form-label">Couple</label>
							<input type="text" class="form-control" name="COUPLE">
						</div>

						<div class="col-md-6">
							<label class="form-label">Status SCADA</label>
							<select name="STATUS_SCADA" class="form-control" required>
								<option value="" disabled selected>-- Pilih Status SCADA --</option>
								<option value="NON INTEGRASI">NON INTEGRASI</option>
								<option value="INTEGRASI">INTEGRASI</option>
							</select>
						</div>

						<div class="col-md-6">
							<label class="form-label">IP Gateway</label>
							<input type="text" class="form-control" name="IP_GATEWAY">
						</div>
						<div class="col-md-6">
							<label class="form-label">IP RTU</label>
							<input type="text" class="form-control" name="IP_RTU">
						</div>

						<div class="col-md-6">
							<label class="form-label">Merk RTU</label>
							<!-- Dropdown -->
							<select name="MERK_RTU" id="merkRTU" class="form-control">
								<option value="" selected>-- Pilih Merk RTU --</option>
								<option value="Micom C264">Micom C264</option>
								<option value="Broderson">Broderson</option>
								<option value="Syntek">Syntek</option>
								<option value="Scout (Survalent)">Scout (Survalent)</option>
								<option value="Intek">Intek</option>
								<option value="Gadisa (Inovasi)">Gadisa (Inovasi)</option>
								<option value="Siemens">Siemens</option>
							</select>
							<!-- Link Input Manual -->
							<small class="text-muted">
								Atau <a href="#" id="inputManualRTU">input manual</a>
							</small>
							<!-- Field Manual -->
							<input type="text" name="MERK_RTU_MANUAL" id="merkRTUManual"
								class="form-control mt-2" placeholder="Input Merk RTU Manual"
								style="display:none;">
						</div>

						<div class="col-md-6">
							<label class="form-label">SN RTU</label>
							<input type="text" class="form-control" name="SN_RTU">
						</div>
						<div class="col-md-6">
							<label class="form-label">Tahun Integrasi</label>
							<input type="text" class="form-control" name="THN_INTEGRASI">
						</div>
					</div>
					<div class="mt-4">
						<a href="<?= base_url('Pembangkit'); ?>" class="btn btn-secondary">Batal</a>
						<button type="submit" class="btn btn-primary">Simpan</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</main>


<script>
	// Toggle input manual
	document.getElementById('inputManualRTU').addEventListener('click', function(e) {
		e.preventDefault();

		let dropdown = document.getElementById('merkRTU');
		let manualInput = document.getElementById('merkRTUManual');

		if (manualInput.style.display === "none") {
			manualInput.style.display = "block";
			dropdown.disabled = true;
		} else {
			manualInput.style.display = "none";
			dropdown.disabled = false;
			manualInput.value = "";
		}
	});
</script>