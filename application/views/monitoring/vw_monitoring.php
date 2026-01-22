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
?>

<main class="main-content position-relative border-radius-lg">
    <?php $this->load->view('layout/navbar'); ?>

    <div class="container-fluid py-4">

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success text-white"><?= e($this->session->flashdata('success')); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger text-white"><?= $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <div class="card mb-4 shadow border-0 rounded-4">

            <div class="card-header py-2 d-flex justify-content-between align-items-center bg-gradient-primary text-white rounded-top-4">
                <h6 class="mb-0 text-white"><i class="fas fa-tachometer-alt me-2"></i>Tabel Monitoring (VW_MONITORING_FINAL)</h6>
                <div class="d-flex align-items-center" style="padding-top: 16px;">
                    <a href="<?= base_url('entry_kontrak'); ?>" class="btn btn-sm btn-light text-primary me-2 d-flex align-items-center no-anim">
                        <i class="fas fa-plus me-1"></i> Entry Kontrak
                    </a>

                    <?php
                    // ✅ Export bawa semua query string (keyword + f[] + per_page + dll)
                    $qs = $_GET ?? [];
                    $export_url = base_url('monitoring/export_csv') . (empty($qs) ? '' : ('?' . http_build_query($qs)));
                    ?>
                    <?php if (!is_guest()): ?>
                        <a href="<?= $export_url; ?>"
                            class="btn btn-sm btn-light text-primary d-flex align-items-center no-anim">
                            <i class="fas fa-file-csv me-1"></i> Export CSV
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body px-0 pt-0 pb-2 bg-white">

                <div class="px-3 mt-3 mb-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <label class="mb-0 me-2 text-sm">Tampilkan:</label>
                        <select class="form-select form-select-sm" style="width: 90px;" onchange="changePerPage(this.value)">
                            <?php foreach ([5, 10, 25, 50, 100] as $pp): ?>
                                <option value="<?= $pp ?>" <?= ((int)$per_page === (int)$pp) ? 'selected' : '' ?>><?= $pp ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="ms-3 text-sm">dari <?= (int)($total_rows ?? 0); ?> data</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <form method="get" action="<?= base_url('monitoring'); ?>" class="d-flex align-items-center" onsubmit="event.preventDefault(); searchSubmit('<?= base_url('monitoring'); ?>', 'searchInputMonitoring', 'search');">
                            <input type="text" id="searchInputMonitoring" name="search" value="<?= htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8'); ?>" class="form-control form-control-sm rounded-3" style="max-width: 300px;" placeholder="Cari data monitoring...">
                            <button type="submit" class="btn btn-sm btn-primary ms-2">Cari</button>
                            <?php if (!empty($search)): ?>
                                <a href="<?= base_url('monitoring?per_page=' . (int)($per_page ?? 5)); ?>" class="btn btn-sm btn-outline-secondary ms-2">Reset</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <div class="table-responsive p-0" style="max-height: 650px; overflow:auto;">
                    <table class="table align-items-center mb-0" id="monitoringTable">
                        <thead class="bg-light">
                            <?php
                            // ====== KOLOM DASAR (MASTER & KONTRAK) ======
                            $baseCols = [
                                'No',
                                'ID',
                                'Jenis Anggaran',
                                'Nomor PRK',
                                'Nomor SKK IO',
                                'Pagu SKK IO',
                                'Uraian PRK',
                                'Judul DRP',
                                'DRP',
                                'Uraian Pekerjaan',
                                'User Pengusul',
                                'RAB User',
                                'Renc HK',
                                'Tgl ND/AMS',
                                'Nomor ND/AMS',
                                'Keterangan',

                                // status auto dari entry_kontrak (read-only di monitoring)
                                'Status',

                                // kontrak utama
                                'No Kontrak',
                                'Vendor',
                                'Tgl Kontrak',
                                'End Kontrak',
                                'Nilai Kontrak',
                                'Kendala',

                                // tambahan baru entry kontrak
                                'No RKS',
                                'KAK',
                                'Metode Pengadaan',
                                'Harga HPE',
                                'Harga HPS',
                                'Harga Nego',
                                'Tahapan Pengadaan',
                                'Prognosa Kontrak',

                                // pembayaran
                                'Tahapan Bayar',
                                'Nilai Bayar',
                                'Tgl Tahapan',
                            ];

                            // ====== Kolom Real Bayar (12) ======
                            $realCols = [];
                            for ($i = 1; $i <= 12; $i++) $realCols[] = "Real $i";

                            // ====== Kolom Ringkasan (sesuai view kamu) ======
                            $sumCols = [
                                'Jml Byr',
                                'Terbayar Thn Ini',
                                'Overpay Thn Ini',
                                'Ke Thn 2026',
                                'Terpakai Thn Ini',
                                'Sisa Anggaran',
                                'Total Bulan',
                                'Bulan 2025',
                                'Nilai/Blm',
                                'Alokasi Kontrak Thn Ini',
                                'Anggaran Terpakai',
                                'Jml Renc'
                            ];

                            // ====== Kolom Kontrak Bulanan (12) ======
                            $ktrkCols = [];
                            for ($i = 1; $i <= 12; $i++) $ktrkCols[] = "KTRK $i";

                            // ====== Kolom Rencana Bayar Bulanan (12) ======
                            $rencCols = [];
                            for ($i = 1; $i <= 12; $i++) $rencCols[] = "RENC $i";

                            $allCols = array_merge($baseCols, $realCols, $sumCols, $ktrkCols, $rencCols, ['Aksi']);

                            // hitung colspan group header
                            $col_master_kontrak = count($baseCols);
                            $col_pembayaran = count($realCols) + count($sumCols); // pembayaran + ringkasan
                            $col_ktrk = count($ktrkCols);
                            $col_renc = count($rencCols);
                            $col_aksi = 1;

                            // ==========================
                            // ✅ FIELD MAP (urutan harus sama dengan $allCols)
                            // ==========================
                            $baseFields = [
                                '',            // No
                                'id',          // ID
                                'jenis_anggaran',
                                'nomor_prk',
                                'nomor_skk_io',
                                'pagu_skk_io',
                                'uraian_prk',
                                'judul_drp',
                                'drp',
                                'uraian_pekerjaan',
                                'user_pengusul',
                                'rab_user',
                                'renc_hari_kerja',
                                'tgl_nd_ams',
                                'nomor_nd_ams',
                                'keterangan',
                                'status_kontrak',
                                'no_kontrak',
                                'vendor',
                                'tgl_kontrak',
                                'end_kontrak',
                                'nilai_kontrak',
                                'kendala_kontrak',
                                'no_rks',
                                'kak',
                                'metode_pengadaan',
                                'harga_hpe',
                                'harga_hps',
                                'harga_nego',
                                'tahapan_pengadaan',
                                'prognosa_kontrak',
                                'tahapan_pembayaran',
                                'nilai_bayar',
                                'tgl_tahapan',
                            ];

                            $realFields = [];
                            for ($i = 1; $i <= 12; $i++) $realFields[] = "real_byr_bln{$i}";

                            $sumFields = [
                                'jml_byr',
                                'terbayar_thn_ini',
                                'overpay_thn_ini',
                                'ke_thn_2026',
                                'terpakai_thn_ini',
                                'sisa_anggaran',
                                'total_bulan',
                                'bulan_2025',
                                'nilai_per_bulan',
                                'alokasi_kontrak_thn_ini',
                                'anggaran_terpakai',
                                'jml_renc'
                            ];

                            $ktrkFields = [];
                            for ($i = 1; $i <= 12; $i++) $ktrkFields[] = "ktrk_bln{$i}";

                            $rencFields = [];
                            for ($i = 1; $i <= 12; $i++) $rencFields[] = "renc_byr_bln{$i}";

                            $allFields = array_merge($baseFields, $realFields, $sumFields, $ktrkFields, $rencFields, ['']); // Aksi
                            ?>

                            <!-- Group header -->
                            <tr>
                                <th colspan="<?= (int)$col_master_kontrak; ?>" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    MASTER & KONTRAK
                                </th>
                                <th colspan="<?= (int)$col_pembayaran; ?>" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    PEMBAYARAN
                                </th>
                                <th colspan="<?= (int)$col_ktrk; ?>" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    KTRK BULANAN
                                </th>
                                <th colspan="<?= (int)$col_renc; ?>" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    RENC BAYAR BULANAN
                                </th>
                                <th colspan="<?= (int)$col_aksi; ?>" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    AKSI
                                </th>
                            </tr>

                            <!-- Column header dengan filter di atas nama kolom -->
                            <tr>
                                <?php foreach ($allCols as $idx => $c): ?>
                                    <?php
                                    $field = $allFields[$idx] ?? '';
                                    $val = '';
                                    if ($field !== '') {
                                        $val = (string)($column_filters[$field] ?? '');
                                    }
                                    ?>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                        data-field="<?= e($field); ?>"
                                        style="vertical-align: top;">
                                        <input type="text"
                                            class="form-control form-control-sm monitoring-col-filter"
                                            placeholder="Filter..."
                                            <?= ($field === '') ? 'disabled' : '' ?>
                                            value="<?= e($val); ?>">
                                        <div class="monitoring-col-title" style="margin-top:4px;">
                                            <?= e($c); ?>
                                        </div>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (empty($monitoring)): ?>
                                <tr>
                                    <td colspan="200" class="text-center text-secondary py-4">Belum ada data</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = (int)$start_no;
                                foreach ($monitoring as $row): ?>
                                    <tr class="<?= ($no % 2 == 0) ? 'table-row-even' : 'table-row-odd'; ?>">

                                        <!-- BASE -->
                                        <td class="text-sm"><?= $no++; ?></td>
                                        <td class="text-sm"><?= (int)($row['id'] ?? 0); ?></td>

                                        <td class="text-sm"><?= e($row['jenis_anggaran'] ?? ''); ?></td>
                                        <td class="text-sm"><?= e($row['nomor_prk'] ?? ''); ?></td>
                                        <td class="text-sm"><?= e($row['nomor_skk_io'] ?? ''); ?></td>
                                        <td class="text-sm"><?= nf($row['pagu_skk_io'] ?? 0); ?></td>
                                        <td class="text-sm"><?= e($row['uraian_prk'] ?? ''); ?></td>
                                        <td class="text-sm"><?= e($row['judul_drp'] ?? ''); ?></td>
                                        <td class="text-sm"><?= e($row['drp'] ?? ''); ?></td>

                                        <td class="text-sm"><?= e($row['uraian_pekerjaan'] ?? ''); ?></td>
                                        <td class="text-sm"><?= e($row['user_pengusul'] ?? ''); ?></td>
                                        <td class="text-sm"><?= nf($row['rab_user'] ?? 0); ?></td>
                                        <td class="text-sm"><?= e($row['renc_hari_kerja'] ?? ''); ?></td>

                                        <td class="text-sm"><?= e($row['tgl_nd_ams'] ?? ''); ?></td>
                                        <td class="text-sm"><?= e($row['nomor_nd_ams'] ?? ''); ?></td>
                                        <td class="text-sm"><?= e($row['keterangan'] ?? ''); ?></td>

                                        <td class="text-sm"><?= e($row['status_kontrak'] ?? ''); ?></td>

                                        <td class="text-sm"><?= e($row['no_kontrak'] ?? ''); ?></td>
                                        <td class="text-sm"><?= e($row['vendor'] ?? ''); ?></td>
                                        <td class="text-sm"><?= e($row['tgl_kontrak'] ?? ''); ?></td>
                                        <td class="text-sm"><?= e($row['end_kontrak'] ?? ''); ?></td>
                                        <td class="text-sm"><?= nf($row['nilai_kontrak'] ?? 0); ?></td>
                                        <td class="text-sm"><?= e($row['kendala_kontrak'] ?? ''); ?></td>

                                        <!-- TAMBAHAN ENTRY KONTRAK -->
                                        <td class="text-sm"><?= e($row['no_rks'] ?? ''); ?></td>
                                        <td class="text-sm"><?= e($row['kak'] ?? ''); ?></td>
                                        <td class="text-sm"><?= e($row['metode_pengadaan'] ?? ''); ?></td>
                                        <td class="text-sm"><?= nf($row['harga_hpe'] ?? 0); ?></td>
                                        <td class="text-sm"><?= nf($row['harga_hps'] ?? 0); ?></td>
                                        <td class="text-sm"><?= nf($row['harga_nego'] ?? 0); ?></td>
                                        <td class="text-sm"><?= e($row['tahapan_pengadaan'] ?? ''); ?></td>
                                        <td class="text-sm"><?= e($row['prognosa_kontrak'] ?? ''); ?></td>

                                        <!-- KKU / PEMBAYARAN -->
                                        <td class="text-sm"><?= e($row['tahapan_pembayaran'] ?? ''); ?></td>
                                        <td class="text-sm"><?= nf($row['nilai_bayar'] ?? 0); ?></td>
                                        <td class="text-sm"><?= e($row['tgl_tahapan'] ?? ''); ?></td>

                                        <!-- REAL BAYAR 1..12 -->
                                        <?php for ($i = 1; $i <= 12; $i++): ?>
                                            <td class="text-sm"><?= nf($row["real_byr_bln{$i}"] ?? 0); ?></td>
                                        <?php endfor; ?>

                                        <!-- RINGKASAN -->
                                        <td class="text-sm"><?= nf($row['jml_byr'] ?? 0); ?></td>
                                        <td class="text-sm"><?= nf($row['terbayar_thn_ini'] ?? 0); ?></td>
                                        <td class="text-sm"><?= nf($row['overpay_thn_ini'] ?? 0); ?></td>
                                        <td class="text-sm"><?= nf($row['ke_thn_2026'] ?? 0); ?></td>
                                        <td class="text-sm"><?= nf($row['terpakai_thn_ini'] ?? 0); ?></td>
                                        <td class="text-sm"><?= nf($row['sisa_anggaran'] ?? 0); ?></td>

                                        <td class="text-sm"><?= e($row['total_bulan'] ?? ''); ?></td>
                                        <td class="text-sm"><?= e($row['bulan_2025'] ?? ''); ?></td>
                                        <td class="text-sm"><?= nf($row['nilai_per_bulan'] ?? 0); ?></td>
                                        <td class="text-sm"><?= nf($row['alokasi_kontrak_thn_ini'] ?? 0); ?></td>
                                        <td class="text-sm"><?= nf($row['anggaran_terpakai'] ?? 0); ?></td>
                                        <td class="text-sm"><?= nf($row['jml_renc'] ?? 0); ?></td>

                                        <!-- KTRK BULANAN 1..12 -->
                                        <?php for ($i = 1; $i <= 12; $i++): ?>
                                            <td class="text-sm"><?= nf($row["ktrk_bln{$i}"] ?? 0); ?></td>
                                        <?php endfor; ?>

                                        <!-- RENC BAYAR BULANAN 1..12 -->
                                        <?php for ($i = 1; $i <= 12; $i++): ?>
                                            <td class="text-sm"><?= nf($row["renc_byr_bln{$i}"] ?? 0); ?></td>
                                        <?php endfor; ?>

                                        <td class="text-center">
                                            <a href="<?= base_url('monitoring/detail/' . (int)($row['id'] ?? 0)); ?>"
                                                class="btn btn-info btn-xs text-white" title="Detail">
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer d-flex justify-content-end">
                    <?= $pagination ?? ''; ?>
                </div>

            </div>
        </div>
    </div>
</main>

<style>
/* title kecil rapi di bawah input */
#monitoringTable thead .monitoring-col-title{
    font-size: 11px;
    font-weight: 700;
    color: #6c757d;
    white-space: nowrap;
}
#monitoringTable thead .monitoring-col-filter{
    min-width: 90px;
}
</style>

<script>
    // Script DataTables Anda tetap ada, namun dibuat tidak mengubah paging/filter server-side.
    // Tujuan: tidak ada menu filter DataTables di luar tabel, dan filter kolom tetap server-side.
    $(document).ready(function() {
        try {
            if ($.fn.DataTable) {
                // Jangan aktifkan fitur DT yang membuat pagination/search sendiri (karena kita pakai pagination CI + server filtering).
                // DOM 't' hanya tabel, tanpa search box/length/info DT.
                $('#monitoringTable').DataTable({
                    dom: 't',
                    paging: false,
                    searching: false,
                    info: false,
                    ordering: false,
                    pageLength: 10,
                    lengthMenu: [5, 10, 25, 50, 100]
                });
            }
        } catch (e) {
            console && console.error && console.error(e);
        }
    });
</script>

<script>
(function () {
    function debounce(fn, wait) {
        var t;
        return function () {
            var ctx = this, args = arguments;
            clearTimeout(t);
            t = setTimeout(function () { fn.apply(ctx, args); }, wait);
        };
    }

    function setQSParam(urlObj, key, value) {
        if (!value || String(value).trim() === '') {
            urlObj.searchParams.delete(key);
        } else {
            urlObj.searchParams.set(key, String(value).trim());
        }
    }

    // ✅ Dropdown "Tampilkan" tetap preserve semua query string (keyword + f[]), dan reset page
    window.changePerPage = function (pp) {
        var u = new URL(window.location.href);
        u.searchParams.set('per_page', pp);
        u.searchParams.set('page', 0);
        window.location.href = u.toString();
    };

    // ✅ Filter kolom server-side: update query f[field]=value dan reset page=0
    function applyColumnFilter(field, value) {
        var u = new URL(window.location.href);
        var key = 'f[' + field + ']';
        setQSParam(u, key, value);
        u.searchParams.set('page', 0);
        window.location.href = u.toString();
    }

    $(document).ready(function () {
        var handler = debounce(function () {
            var th = $(this).closest('th');
            var field = th.data('field') || '';
            if (!field) return;
            applyColumnFilter(field, $(this).val());
        }, 500);

        // bind untuk semua input filter kolom
        $('#monitoringTable thead .monitoring-col-filter').on('keyup', handler);
        $('#monitoringTable thead .monitoring-col-filter').on('change', function () {
            var th = $(this).closest('th');
            var field = th.data('field') || '';
            if (!field) return;
            applyColumnFilter(field, $(this).val());
        });
    });
})();
</script>
