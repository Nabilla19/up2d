<?php
defined('BASEPATH') or exit('No direct script access allowed');
function e($v)
{
    return htmlentities((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
}
?>

<main class="main-content position-relative border-radius-lg">
    <?php $this->load->view('layout/navbar'); ?>

    <div class="container-fluid py-4">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-gradient-primary text-white">
                <h6 class="mb-0">DETAIL ENTRY KONTRAK</h6>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <?php foreach ($row as $k => $v): ?>
                        <div class="col-md-4">
                            <label class="text-xs text-secondary"><?= e($k); ?></label>
                            <input type="text" class="form-control" readonly value="<?= e($v); ?>">
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="text-end mt-4">
                    <a href="<?= base_url('entry_kontrak'); ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</main>