<?php $this->load->view('layout/header'); ?>
<main class="main-content position-relative border-radius-lg">
    <?php $this->load->view('layout/navbar'); ?>
  <?php $this->load->view('layout/navbar'); ?>

  <div class="container-fluid py-4">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Preview 100 Baris Pertama</h5>

        <form action="<?= base_url('import/process/' . ($entity ?? 'gi')); ?>" method="post" class="mb-0">
          <input type="hidden" name="job_id" value="<?= (int)$job_id; ?>">
          <input type="hidden" name="return_to" value="<?= isset($return_to) ? html_escape($return_to) : '' ?>">
          <?php $isGi = (($entity ?? 'gi') === 'gi'); ?>
          <button class="btn btn-success">
            <?= $isGi ? 'Mulai Import ke Staging' : 'Mulai Import (Direct Append)'; ?>
          </button>
        </form>
      </div>

      <div class="card-body">

        <div class="alert alert-info">
          <div class="fw-bold mb-1">Validasi Import</div>
          <ul class="mb-0">
            <li>File sudah lolos validasi: <b>CSV</b> dan header kolom sesuai tabel <b><?= strtoupper($target_table ?? 'GI'); ?></b>.</li>
            <li>Jika GI: data masuk ke <b>staging</b> dulu (commit dilakukan setelah status done).</li>
            <li>Jika selain GI: data akan <b>langsung append</b> ke tabel target.</li>
          </ul>
        </div>

        <?php if (isset($expected_columns) && is_array($expected_columns) && count($expected_columns) > 0): ?>
          <div class="mb-3">
            <div class="text-sm fw-bold mb-1">Kolom yang diharapkan:</div>
            <div class="d-flex flex-wrap gap-1">
              <?php foreach ($expected_columns as $c): ?>
                <span class="badge bg-secondary"><?= htmlspecialchars($c); ?></span>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0">
            <thead>
              <tr>
                <?php foreach ($headers as $h): ?>
                  <th><?= htmlspecialchars($h); ?></th>
                <?php endforeach; ?>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($rows as $r): ?>
                <tr>
                  <?php foreach ($r as $c): ?>
                    <td><?= htmlspecialchars($c); ?></td>
                  <?php endforeach; ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</main>
<?php $this->load->view('layout/footer'); ?>
