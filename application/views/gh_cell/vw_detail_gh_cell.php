<div class="main-content position-relative border-radius-lg">
	<section class="section">
		<div class="section-body">
			<div class="container-fluid py-4">
				<div class="row justify-content-center">
					<div class="col-lg-10">
						<div class="card shadow border-0 rounded-4">
							<div class="card-header bg-gradient-primary text-white text-center rounded-top-4">
								<h6 class="mb-0 text-white">
									<i class="fas fa-bolt me-2 text-warning"></i> Detail GH Cell
								</h6>
							</div>

							<div class="card-body">
								<?php
								// Sesuaikan dengan struktur tabel gh_cell
								$fields = [
									'CXUNIT' => 'Unit CX',
									'UNITNAME' => 'Nama Unit',
									'ASSETNUM' => 'Nomor Aset',
									'SSOTNUMBER' => 'SSOT Number',
									'LOCATION' => 'Lokasi',
									'DESCRIPTION' => 'Deskripsi',
									'VENDOR' => 'Vendor',
									'MANUFACTURER' => 'Manufacturer',
									'INSTALLDATE' => 'Tanggal Instalasi',
									'PRIORITY' => 'Prioritas',
									'STATUS' => 'Status',
									'TUJDNUMBER' => 'TUJD Number',
									'CHANGEBY' => 'Diubah Oleh',
									'CHANGEDATE' => 'Tanggal Diubah',
									'CXCLASSIFICATIONDESC' => 'Klasifikasi',
									'CXPENYULANG' => 'Penyulang',
									'NAMA_LOCATION' => 'Nama Lokasi',
									'LONGITUDEX' => 'Longitude (X)',
									'LATITUDEY' => 'Latitude (Y)',
									'ISASSET' => 'Is Asset',
									'STATUS_KEPEMILIKAN' => 'Status Kepemilikan',
									'BURDEN' => 'Burden',
									'FAKTOR_KALI' => 'Faktor Kali',
									'JENIS_CT' => 'Jenis CT',
									'KELAS_CT' => 'Kelas CT',
									'KELAS_PROTEKSI' => 'Kelas Proteksi',
									'PRIMER_SEKUNDER' => 'Primer / Sekunder',
									'TIPE_CT' => 'Tipe CT',
									'OWNERSYSID' => 'Owner Sys ID',
									'ISOLASI_KUBIKEL' => 'Isolasi Kubikel',
									'JENIS_MVCELL' => 'Jenis MV Cell',
									'TH_BUAT' => 'Tahun Buat',
									'TYPE_MVCELL' => 'Tipe MV Cell',
									'CELL_TYPE' => 'Cell Type',
								];
								?>

								<div class="row">
									<?php foreach ($fields as $key => $label): ?>
										<div class="col-md-6 mb-3">
											<div class="detail-item">
												<span class="label"><?= $label; ?></span>
												<p class="value"><?= htmlspecialchars($gh_cell[$key] ?? '-'); ?></p>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>

							<div class="card-footer text-center bg-light border-top">
								<a href="<?= base_url('Gh_cell') ?>" class="btn btn-danger px-4">
									<i class="fas fa-arrow-left me-1"></i> Kembali
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<!-- STYLE -->
<style>
	.detail-item {
		background: #f8f9fc;
		border-radius: 10px;
		padding: 12px 15px;
		margin-bottom: 10px;
		box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
	}

	.label {
		font-weight: 600;
		color: #2c3e50;
		display: block;
		margin-bottom: 3px;
	}

	.value {
		color: #333;
		margin: 0;
	}

	.card-header {
		background: linear-gradient(90deg, #007bff, #0056d2);
		font-weight: 600;
		font-size: 17px;
		padding: 14px;
	}

	.card-footer {
		border-top: 1px solid #e0e0e0;
		padding: 18px;
		border-radius: 0 0 14px 14px;
	}

	.btn {
		border-radius: 10px;
	}
</style>