<?php // Investasi: progress_kontrak view (independent copy) ?>
<main class="main-content position-relative border-radius-lg ">
	<?php $this->load->view('layout/navbar'); ?>

	<!-- Content -->
	<div class="container-fluid py-4">
		<?php if (!empty($error)): ?>
			<div class="alert alert-danger text-white"><?= htmlentities($error) ?></div>
		<?php endif; ?>

		<div class="card mb-4 shadow border-0 rounded-4">
			<div class="card-header py-2 d-flex justify-content-between align-items-center bg-gradient-primary text-white rounded-top-4">
				<h6 class="mb-0 text-white"><i class="fas fa-file-contract me-2"></i>Data Progress Kontrak</h6>
				<div class="d-flex align-items-center">
					<a href="<?= base_url('anggaran/investasi/add_progress_kontrak'); ?>" class="btn btn-sm btn-light text-primary me-2 d-flex align-items-center no-anim">
						<i class="fas fa-plus me-1"></i> Tambah
					</a>
					<a href="#" class="btn btn-sm btn-light text-secondary d-flex align-items-center no-anim" onclick="downloadCSVProgressKontrak()">
						<i class="fas fa-file-csv me-1"></i> Download CSV
					</a>
				</div>
			</div>

			<div class="card-body px-0 pt-0 pb-2 bg-white">
				<div class="px-3 mt-3 mb-3 d-flex justify-content-between align-items-center">
					<div class="d-flex align-items-center">
						<label class="mb-0 me-2 text-sm">Tampilkan:</label>
						<select id="perPageSelectProgressKontrak" class="form-select form-select-sm" style="width: 80px; padding-right: 2rem;" onchange="changePerPageProgressKontrak(this.value)">
							<option value="5">5</option>
							<option value="10">10</option>
							<option value="25">25</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="200">200</option>
						</select>
						<span class="ms-3 text-sm">dari <?= count($rows) ?> data</span>
					</div>
					<input type="text" id="searchInputProgressKontrak" onkeyup="searchTableProgressKontrak()" class="form-control form-control-sm rounded-3" style="max-width: 300px;" placeholder="Cari data Progress Kontrak...">
				</div>

				<div class="table-responsive p-0">
					<table class="table align-items-center mb-0" id="progressKontrakTable">
						<thead class="bg-light">
							<tr>
								<?php if (!empty($fields)): ?>
									<?php foreach ($fields as $f): ?>
										<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"><?= htmlentities($f) ?></th>
									<?php endforeach; ?>
								<?php else: ?>
									<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Belum ada kolom</th>
								<?php endif; ?>
							</tr>
						</thead>
						<tbody>
							<?php if (empty($rows)): ?>
								<tr><td colspan="<?= max(1, count($fields)) ?>" class="text-center text-secondary py-4">Belum ada data</td></tr>
							<?php else: ?>
								<?php foreach ($rows as $r): ?>
									<tr>
										<?php foreach ($fields as $f): ?>
											<td class="text-sm"><?= isset($r[$f]) ? htmlentities((string)$r[$f]) : '' ?></td>
										<?php endforeach; ?>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
				<div class="card-footer d-flex justify-content-end">
					<nav>
						<ul class=\"pagination pagination-sm mb-0\" id=\"paginationProgressKontrak\"></ul>
					</nav>
				</div>
			</div>
		</div>
	</div>
</main>

<?php // Reuse same scripts as progress_kontrak; they exist globally when included via layout/footer or page -> if not, they will be loaded on the progress_kontrak page. ?>
