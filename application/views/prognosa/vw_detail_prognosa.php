<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
if (!function_exists('e')) {
    function e($v)
    {
        return htmlentities((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
    }
}
if (!function_exists('nf')) {
    function nf($v)
    {
        return number_format((float)($v ?? 0), 0, ',', '.');
    }
}
$row = $row ?? [];
$months = [
    'jan_25' => 'Januari 2025',
    'feb_25' => 'Februari 2025',
    'mar_25' => 'Maret 2025',
    'apr_25' => 'April 2025',
    'mei_25' => 'Mei 2025',
    'jun_25' => 'Juni 2025',
    'jul_25' => 'Juli 2025',
    'aug_25' => 'Agustus 2025',
    'sep_25' => 'September 2025',
    'okt_25' => 'Oktober 2025',
    'nov_25' => 'November 2025',
    'des_25' => 'Desember 2025'
];
$total = 0;
foreach ($months as $k => $label) $total += (float)($row[$k] ?? 0);
?>

<main class="main-content position-relative border-radius-lg">
    <?php $this->load->view('layout/navbar'); ?>

    <div class="container-fluid py-4">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Detail Prognosa</h6>
                <a href="<?= base_url('prognosa'); ?>" class="btn btn-sm btn-light">Kembali</a>
            </div>

            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <small>Jenis Anggaran</small>
                        <div class="fw-semibold"><?= e($row['jenis_anggaran']); ?></div>
                    </div>
                    <div class="col-md-3">
                        <small>Rekap</small>
                        <div class="fw-semibold"><?= e($row['rekap']); ?></div>
                    </div>
                    <div class="col-md-3">
                        <small>Total 2025</small>
                        <div class="fw-semibold"><?= nf($total); ?></div>
                    </div>
                </div>

                <hr>

                <div class="row g-3">
                    <?php foreach ($months as $k => $label): ?>
                        <div class="col-md-3">
                            <small><?= e($label); ?></small>
                            <div class="fw-semibold"><?= nf($row[$k] ?? 0); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <hr>

                <div class="alert alert-secondary mb-0">
                    Data ini berasal dari <b>VIEW DB (vw_prognosa)</b> sesuai rumus ERD. Jadi tidak bisa diubah manual dari menu ini.
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .bg-gradient-primary {
        background: linear-gradient(90deg, #005C99, #0099CC);
    }
</style>