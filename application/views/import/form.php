<?php $this->load->view('layout/header'); ?>
<main class="main-content position-relative border-radius-lg">
    <?php $this->load->view('layout/navbar'); ?>
  <?php $this->load->view('layout/navbar'); ?>

  <div class="container-fluid py-4">

    <?php if ($this->session->flashdata('error')): ?>
      <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Import <?= isset($target_table) ? strtoupper($target_table) : 'GI'; ?> - Phase 1</h5>
      </div>

      <div class="card-body">

        <div class="alert alert-info">
          <div class="fw-bold mb-1">Informasi sebelum upload</div>
          <ul class="mb-0">
            <li><b>Wajib file CSV</b> (.csv). File selain CSV akan ditolak.</li>
            <li>Baris pertama CSV harus <b>header kolom</b>.</li>
            <li>Header CSV harus <b>sesuai nama & jumlah kolom</b> dengan tabel target: <b><?= strtoupper($target_table ?? 'GI'); ?></b>.</li>
            <li>Pemisah boleh <b>,</b> atau <b>;</b> (otomatis terdeteksi).</li>
          </ul>
        </div>

        <p class="text-sm text-muted mb-3">Unggah file CSV (maks 10 MB).</p>

        <?php if (isset($expected_columns) && is_array($expected_columns) && count($expected_columns) > 0): ?>
          <div class="mb-3">
            <div class="text-sm fw-bold mb-1">Kolom yang diharapkan (CSV header harus match):</div>
            <div class="d-flex flex-wrap gap-1">
              <?php foreach ($expected_columns as $c): ?>
                <span class="badge bg-secondary"><?= htmlspecialchars($c); ?></span>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

        <form action="<?= base_url('import/preview/' . ($entity ?? 'gi')); ?>" method="post" enctype="multipart/form-data">
          <input type="hidden" name="return_to" value="<?= isset($return_to) ? html_escape($return_to) : '' ?>">
          <div class="mb-2">
            <label class="form-label">File CSV</label>
            <input type="file" class="form-control" name="file" accept=".csv" required>
            <div class="form-text">
              Sebelum upload: pastikan <b>format CSV</b> dan <b>kolom header</b> sudah sesuai dengan tabel <b><?= strtoupper($target_table ?? 'GI'); ?></b>.
            </div>
          </div>

          <button class="btn btn-primary">Preview</button>

          <?php if (($entity ?? 'gi') === 'gi'): ?>
            <a href="<?= base_url('assets/sample/gi_sample.csv'); ?>" class="btn btn-info ms-2">Download Sample</a>
          <?php endif; ?>
        </form>

      </div>
    </div>
  </div>
</main>
<?php $this->load->view('layout/footer'); ?>
