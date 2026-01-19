<?php $this->load->view('layout/header'); ?>
<main class="main-content position-relative border-radius-lg">
    <?php $this->load->view('layout/navbar'); ?>
  <?php $this->load->view('layout/navbar'); ?>

  <div class="container-fluid py-4">
    <?php if ($this->session->flashdata('success')): ?>
      <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
      <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <div class="card">
      <div class="card-header"><h5 class="mb-0">Status Import</h5></div>
      <div class="card-body">
        <ul class="list-group">
          <li class="list-group-item d-flex justify-content-between"><span>Job ID</span><strong>#<?= (int)$job->id; ?></strong></li>
          <li class="list-group-item d-flex justify-content-between"><span>File</span><span><?= htmlspecialchars($job->original_filename); ?></span></li>
          <li class="list-group-item d-flex justify-content-between"><span>Tabel Target</span><strong><?= htmlspecialchars($job->target_table); ?></strong></li>
          <li class="list-group-item d-flex justify-content-between"><span>Total Rows</span><strong><?= (int)$job->total_rows; ?></strong></li>
          <li class="list-group-item d-flex justify-content-between"><span>Inserted</span><strong><?= (int)$job->inserted; ?></strong></li>
          <li class="list-group-item d-flex justify-content-between"><span>Failed</span><strong><?= (int)$job->failed; ?></strong></li>
          <li class="list-group-item d-flex justify-content-between">
            <span>Status</span>
            <span class="badge bg-primary"><?= htmlspecialchars($job->status); ?></span>
          </li>
        </ul>

        <div class="mt-3 d-flex gap-2">
          <a href="<?= isset($return_to) && $return_to ? html_escape($return_to) : base_url('import'); ?>" class="btn btn-secondary">Kembali</a>

          <?php if ($job->status === 'done'): ?>
            <a href="<?= base_url('import/commit/'.$job->id) . (isset($return_to) && $return_to ? '?return_to=' . urlencode($return_to) : ''); ?>"
               class="btn btn-success"
               onclick="return confirm('Pindahkan data dari staging ke tabel <?= htmlspecialchars($job->target_table); ?>?');">
              Commit ke Tabel <?= htmlspecialchars(strtoupper($job->target_table)); ?>
            </a>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </div>
</main>
<?php $this->load->view('layout/footer'); ?>
