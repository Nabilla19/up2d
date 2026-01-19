<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>


    <div class="container-fluid py-4">

        <!-- Login counter widget (separate from notifications) -->
        <div class="row mb-3">
            <div class="col-12 col-md-6 col-lg-5">
                <div class="card login-count-card">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <!-- Left Section: Icon & Badge -->
                            <div class="col-auto">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="icon icon-shape bg-gradient-info shadow-info rounded-circle d-flex align-items-center justify-content-center mb-2" style="width:64px; height:64px;">
                                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img">
                                            <path d="M8 12h8" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M12 8l4 4-4 4" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M3 3v18a2 2 0 0 0 2 2h8" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <?php if (isset($user_role) && $user_role):
                                        $badge_color = 'bg-gradient-secondary';
                                        $role_lower = strtolower($user_role);
                                        if (strpos($role_lower, 'admin') !== false) {
                                            $badge_color = 'bg-gradient-danger';
                                        } elseif (strpos($role_lower, 'perencanaan') !== false) {
                                            $badge_color = 'bg-gradient-primary';
                                        } elseif (strpos($role_lower, 'operasi') !== false) {
                                            $badge_color = 'bg-gradient-success';
                                        } elseif (strpos($role_lower, 'pemeliharaan') !== false) {
                                            $badge_color = 'bg-gradient-warning';
                                        } elseif (strpos($role_lower, 'fasilitas') !== false) {
                                            $badge_color = 'bg-gradient-info';
                                        }
                                    ?>
                                        <span class="badge <?php echo $badge_color; ?>" style="font-size: 0.7rem; padding: 0.4em 0.7em;">
                                            <i class="fas fa-user-tag" style="font-size: 0.65rem;"></i>
                                            <span class="ms-1"><?php echo strtoupper(htmlspecialchars($user_role)); ?></span>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Right Section: Info -->
                            <div class="col">
                                <div class="ps-2">
                                    <p class="text-xs text-uppercase text-secondary font-weight-bold mb-1 opacity-7">Login Count</p>
                                    <h4 class="font-weight-bolder mb-2" style="font-size: 2rem; line-height: 1;">
                                        <?php echo isset($login_count) ? intval($login_count) : '—'; ?>
                                    </h4>
                                    <p class="text-sm mb-0 text-secondary" style="line-height: 1.4;">
                                        <i class="far fa-clock me-1 text-info"></i>
                                        <span class="font-weight-normal">Last login:</span>
                                    </p>
                                    <p class="text-sm mb-0 font-weight-bold" style="line-height: 1.2;">
                                        <?php echo isset($last_login) && $last_login ? $last_login : '—'; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        /**
         * VIEW ONLY
         * - Terbayar tidak dibuat kartu terpisah (karena sudah representasi Realisasi Bayar)
         * - KPI dibuat pas untuk desktop (tidak ada baris "nyisa" di bawah)
         * - AO/AI tetap 1 card (AI atas, AO bawah) dengan bubble ukuran ideal
         */

        // Fallback aman jika controller belum kirim variabel AO/AI
        $terkontrak_ai    = isset($terkontrak_ai) ? (float)$terkontrak_ai : null;
        $terkontrak_ao    = isset($terkontrak_ao) ? (float)$terkontrak_ao : null;

        $real_bayar_ai    = isset($real_bayar_ai) ? (float)$real_bayar_ai : null;
        $real_bayar_ao    = isset($real_bayar_ao) ? (float)$real_bayar_ao : null;

        $rencana_bayar_ai = isset($rencana_bayar_ai) ? (float)$rencana_bayar_ai : null;
        $rencana_bayar_ao = isset($rencana_bayar_ao) ? (float)$rencana_bayar_ao : null;

        $sisa_anggaran_ai = isset($sisa_anggaran_ai) ? (float)$sisa_anggaran_ai : null;
        $sisa_anggaran_ao = isset($sisa_anggaran_ao) ? (float)$sisa_anggaran_ao : null;

        // Chart AO/AI (kalau belum ada, array kosong)
        $chart_labels_local = isset($chart_labels) ? $chart_labels : ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $chart_values_ao    = isset($chart_values_ao) ? $chart_values_ao : [];
        $chart_values_ai    = isset($chart_values_ai) ? $chart_values_ai : [];

        function rupiah_or_dash($val) {
            if ($val === null) return '—';
            return 'Rp ' . number_format((float)$val, 0, ',', '.');
        }
        ?>

        <!-- ===== KPI GRID: dibuat 5 kartu saja supaya desktop rapih (tidak ada nyisa baris) ===== -->
        <div class="row kpi-grid-row">

            <!-- TERKONTRAK (AI/AO) -->
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card kpi-ideal">
                    <div class="card-body kpi-body">
                        <div class="kpi-head">
                            <div>
                                <div class="kpi-title">TERKONTRAK</div>
                                <div class="kpi-sub">AI / AO</div>
                            </div>
                            <div class="kpi-ic bg-gradient-primary">
                                <i class="ni ni-money-coins"></i>
                            </div>
                        </div>

                        <div class="kpi-duo">
                            <div class="kpi-line">
                                <span class="kpi-badge badge-ai">AI</span>
                                <span class="kpi-val"><?= rupiah_or_dash($terkontrak_ai); ?></span>
                            </div>
                            <div class="kpi-cut"></div>
                            <div class="kpi-line">
                                <span class="kpi-badge badge-ao">AO</span>
                                <span class="kpi-val"><?= rupiah_or_dash($terkontrak_ao); ?></span>
                            </div>
                        </div>

                        <div class="kpi-footnote">AI=Investasi, AO=Operasi</div>
                    </div>
                </div>
            </div>

            <!-- PRK AKTIF (gabungan) -->
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card kpi-ideal">
                    <div class="card-body kpi-body">
                        <div class="kpi-head">
                            <div>
                                <div class="kpi-title">PRK AKTIF</div>
                                <div class="kpi-sub">gabungan</div>
                            </div>
                            <div class="kpi-ic bg-gradient-info">
                                <i class="ni ni-collection"></i>
                            </div>
                        </div>

                        <div class="kpi-big">
                            <?= number_format((int)($prk_aktif ?? 0), 0, ',', '.'); ?>
                        </div>

                        <div class="kpi-footnote">kontrak / rencana / realisasi</div>
                    </div>
                </div>
            </div>

            <!-- REALISASI BAYAR (AI/AO) -->
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card kpi-ideal">
                    <div class="card-body kpi-body">
                        <div class="kpi-head">
                            <div>
                                <div class="kpi-title">REALISASI BAYAR</div>
                                <div class="kpi-sub">AI / AO</div>
                            </div>
                            <div class="kpi-ic bg-gradient-success">
                                <i class="ni ni-check-bold"></i>
                            </div>
                        </div>

                        <div class="kpi-duo">
                            <div class="kpi-line">
                                <span class="kpi-badge badge-ai">AI</span>
                                <span class="kpi-val"><?= rupiah_or_dash($real_bayar_ai); ?></span>
                            </div>
                            <div class="kpi-cut"></div>
                            <div class="kpi-line">
                                <span class="kpi-badge badge-ao">AO</span>
                                <span class="kpi-val"><?= rupiah_or_dash($real_bayar_ao); ?></span>
                            </div>
                        </div>

                        <div class="kpi-footnote">menggantikan “Terbayar”</div>
                    </div>
                </div>
            </div>

            <!-- RENCANA BAYAR (AI/AO) -->
            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="card kpi-ideal">
                    <div class="card-body kpi-body">
                        <div class="kpi-head">
                            <div>
                                <div class="kpi-title">RENCANA BAYAR</div>
                                <div class="kpi-sub">AI / AO</div>
                            </div>
                            <div class="kpi-ic bg-gradient-warning">
                                <i class="ni ni-time-alarm"></i>
                            </div>
                        </div>

                        <div class="kpi-duo">
                            <div class="kpi-line">
                                <span class="kpi-badge badge-ai">AI</span>
                                <span class="kpi-val"><?= rupiah_or_dash($rencana_bayar_ai); ?></span>
                            </div>
                            <div class="kpi-cut"></div>
                            <div class="kpi-line">
                                <span class="kpi-badge badge-ao">AO</span>
                                <span class="kpi-val"><?= rupiah_or_dash($rencana_bayar_ao); ?></span>
                            </div>
                        </div>

                        <div class="kpi-footnote">plan pembayaran tahun ini</div>
                    </div>
                </div>
            </div>

            <!-- SISA ANGGARAN (AI/AO) -->
            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="card kpi-ideal">
                    <div class="card-body kpi-body">
                        <div class="kpi-head">
                            <div>
                                <div class="kpi-title">SISA ANGGARAN</div>
                                <div class="kpi-sub">AI / AO</div>
                            </div>
                            <div class="kpi-ic bg-gradient-danger">
                                <i class="ni ni-chart-bar-32"></i>
                            </div>
                        </div>

                        <div class="kpi-duo">
                            <div class="kpi-line">
                                <span class="kpi-badge badge-ai">AI</span>
                                <span class="kpi-val"><?= rupiah_or_dash($sisa_anggaran_ai); ?></span>
                            </div>
                            <div class="kpi-cut"></div>
                            <div class="kpi-line">
                                <span class="kpi-badge badge-ao">AO</span>
                                <span class="kpi-val"><?= rupiah_or_dash($sisa_anggaran_ao); ?></span>
                            </div>
                        </div>

                        <div class="kpi-footnote">sisa dari pagu - terpakai</div>
                    </div>
                </div>
            </div>

        </div>
        <!-- ===== END KPI GRID ===== -->

        <!-- ====== GRAFIK: 2 CARD (AO & AI) + CAROUSEL tetap ====== -->
        <div class="row mt-1">

            <div class="col-lg-7 mb-lg-0 mb-4">

                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card z-index-2 chart-ideal">
                            <div class="card-header pb-0 pt-3 bg-transparent">
                                <h6 class="text-capitalize mb-1">Sales overview - AO</h6>
                                <p class="text-sm mb-0">
                                    <i class="fa fa-arrow-up text-success"></i>
                                    <span class="font-weight-bold">TERBAYAR AO</span>
                                </p>
                            </div>
                            <div class="card-body p-3">
                                <div class="chart">
                                    <canvas id="chart-line-ao" class="chart-canvas" height="210"></canvas>
                                </div>
                                <?php if (empty($chart_values_ao)): ?>
                                    <p class="text-xs text-muted mb-0 mt-2">
                                        Data chart AO belum tersedia (belum ada <code>$chart_values_ao</code> dari controller).
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card z-index-2 chart-ideal">
                            <div class="card-header pb-0 pt-3 bg-transparent">
                                <h6 class="text-capitalize mb-1">Sales overview - AI</h6>
                                <p class="text-sm mb-0">
                                    <i class="fa fa-arrow-up text-success"></i>
                                    <span class="font-weight-bold">TERBAYAR AI</span>
                                </p>
                            </div>
                            <div class="card-body p-3">
                                <div class="chart">
                                    <canvas id="chart-line-ai" class="chart-canvas" height="210"></canvas>
                                </div>
                                <?php if (empty($chart_values_ai)): ?>
                                    <p class="text-xs text-muted mb-0 mt-2">
                                        Data chart AI belum tersedia (belum ada <code>$chart_values_ai</code> dari controller).
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Slide Show -->
            <div class="col-lg-5">
                <div class="card card-carousel overflow-hidden h-100 p-0">
                    <div id="carouselExampleCaptions" class="carousel slide h-100" data-bs-ride="carousel">
                        <div class="carousel-inner border-radius-lg h-100">
                            <div class="carousel-item h-100 active" style="background-image: url('assets/assets/img/p2tl_pln.png'); background-size: cover;">
                                <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3" style="width:25px; height:25px; line-height:25px;">
                                        <i class="ni ni-camera-compact text-dark opacity-10" style="font-size:12px;"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="carousel-item h-100" style="background-image: url('assets/assets/img/Pln_stop_listrik_ilegal.png'); background-size: cover;">
                                <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                                    <div class="icon icon-shape bg-white border-radius-md mb-3"
                                        style=" width: 25px; height: 25px; display: flex; align-items: center; justify-content: center; line-height: 0; ">
                                        <i class="ni ni-bulb-61 text-dark opacity-10"
                                            style="font-size: 14px; margin: 0; padding: 0; position: relative; top: -1px;"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="carousel-item h-100" style="background-image: url('assets/assets/img/penertiban.png'); background-size: cover;">
                                <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                                    <div class="icon icon-shape bg-white border-radius-md mb-3"
                                        style="width: 25px; height: 25px; display: flex; align-items: center; justify-content: center; line-height: 1; padding-top: 1px;">
                                        <i class="ni ni-trophy text-dark opacity-10"
                                            style="font-size: 14px; margin: 0; padding: 0; position: relative; top: -1px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Script Chart (AO + AI) -->
        <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (typeof Chart === "undefined") return;

            const labels   = <?= json_encode($chart_labels_local ?? []); ?>;
            const valuesAO = <?= json_encode($chart_values_ao ?? []); ?>;
            const valuesAI = <?= json_encode($chart_values_ai ?? []); ?>;

            const elAO = document.getElementById("chart-line-ao");
            if (elAO && valuesAO.length) {
                new Chart(elAO, {
                    type: "line",
                    data: {
                        labels: labels,
                        datasets: [{
                            label: "TERBAYAR AO",
                            data: valuesAO,
                            tension: 0.35
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }

            const elAI = document.getElementById("chart-line-ai");
            if (elAI && valuesAI.length) {
                new Chart(elAI, {
                    type: "line",
                    data: {
                        labels: labels,
                        datasets: [{
                            label: "TERBAYAR AI",
                            data: valuesAI,
                            tension: 0.35
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }
        });
        </script>

        <!-- ====== Rekap Anggaran per Jenis (tetap) ====== -->
        <div class="row mt-4">
            <div class="col-lg-7 mb-lg-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0 p-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-2">Rekap Anggaran per Jenis</h6>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Jenis Anggaran</th>
                                    <th class="text-end">Pagu</th>
                                    <th class="text-end">Terkontrak</th>
                                    <th class="text-end">Terbayar</th>
                                    <th class="text-end">Sisa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($rekap_anggaran)): ?>
                                    <?php foreach ($rekap_anggaran as $r): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1 align-items-center">
                                                    <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle"
                                                         style="width: 28px; height: 28px;">
                                                        <i class="ni ni-money-coins text-white opacity-10" aria-hidden="true" style="font-size: 14px;"></i>
                                                    </div>
                                                    <div class="ms-3">
                                                        <p class="text-xs font-weight-bold mb-0">Jenis:</p>
                                                        <h6 class="text-sm mb-0"><?php echo strtoupper(htmlspecialchars($r['jenis_anggaran'] ?? '—')); ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <p class="text-xs font-weight-bold mb-0">Pagu</p>
                                                <h6 class="text-sm mb-0">
                                                    Rp <?php echo number_format((float)($r['pagu'] ?? 0), 0, ',', '.'); ?>
                                                </h6>
                                            </td>
                                            <td class="text-end">
                                                <p class="text-xs font-weight-bold mb-0">Terkontrak</p>
                                                <h6 class="text-sm mb-0">
                                                    Rp <?php echo number_format((float)($r['terkontrak'] ?? 0), 0, ',', '.'); ?>
                                                </h6>
                                            </td>
                                            <td class="text-end">
                                                <p class="text-xs font-weight-bold mb-0">Terbayar</p>
                                                <h6 class="text-sm mb-0">
                                                    Rp <?php echo number_format((float)($r['terbayar'] ?? 0), 0, ',', '.'); ?>
                                                </h6>
                                            </td>
                                            <td class="text-end">
                                                <p class="text-xs font-weight-bold mb-0">Sisa</p>
                                                <h6 class="text-sm mb-0">
                                                    Rp <?php echo number_format((float)($r['sisa'] ?? 0), 0, ',', '.'); ?>
                                                </h6>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">
                                            <i class="ni ni-chart-bar-32 text-muted" style="font-size: 48px;"></i>
                                            <p class="mt-3">Belum ada data rekap dari vw_rkp_prk</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ====== Riwayat Aktivitas (tetap) ====== -->
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header pb-0 p-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Riwayat Aktivitas</h6>
                        <a href="<?= base_url('Notifikasi'); ?>" class="btn btn-sm btn-outline-primary mb-0">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="card-body p-3">
                        <?php if (!empty($riwayat_aktivitas)): ?>

                            <div class="activity-scroll">
                                <ul class="list-group">
                                    <?php foreach ($riwayat_aktivitas as $n): ?>
                                        <?php
                                            $jenis = strtolower($n['jenis_aktivitas'] ?? '');
                                            $icon = 'ni ni-bell-55';
                                            $badge = 'bg-dark';
                                            $badgeText = ucfirst($jenis);

                                            if ($jenis === 'login') { $icon = 'ni ni-check-bold'; $badge = 'bg-success'; $badgeText = 'Login'; }
                                            else if ($jenis === 'logout') { $icon = 'ni ni-button-power'; $badge = 'bg-secondary'; $badgeText = 'Logout'; }
                                            else if ($jenis === 'create') { $icon = 'ni ni-fat-add'; $badge = 'bg-success'; $badgeText = 'Tambah'; }
                                            else if ($jenis === 'update') { $icon = 'ni ni-settings'; $badge = 'bg-warning text-dark'; $badgeText = 'Edit'; }
                                            else if ($jenis === 'delete') { $icon = 'ni ni-fat-remove'; $badge = 'bg-danger'; $badgeText = 'Hapus'; }
                                            else if ($jenis === 'import') { $icon = 'ni ni-cloud-upload-96'; $badge = 'bg-info'; $badgeText = 'Import'; }

                                            $timeText = (!empty($n['tanggal_waktu'])) ? date('d M Y H:i', strtotime($n['tanggal_waktu'])) : '—';
                                            $emailText = htmlspecialchars($n['email'] ?? '—');
                                            $roleText = htmlspecialchars($n['role'] ?? '—');
                                            $descText = htmlspecialchars($n['deskripsi'] ?? '—');

                                            $link = '';
                                            if (!empty($n['module']) && !empty($n['record_id']) && $jenis !== 'delete') {
                                                $link = base_url('Notifikasi/read/' . $n['id']);
                                            }
                                        ?>

                                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                            <div class="d-flex align-items-center">
                                                <div class="icon bg-gradient-dark shadow text-center d-flex align-items-center justify-content-center me-3"
                                                    style="width: 28px; height: 28px; border-radius: 50%;">
                                                    <i class="<?= $icon; ?> text-white opacity-10" style="font-size: 12px;"></i>
                                                </div>

                                                <div class="d-flex flex-column">
                                                    <h6 class="mb-1 text-dark text-sm">
                                                        <?= $emailText; ?>
                                                        <span class="badge bg-info text-white ms-1" style="font-size:10px;">
                                                            <?= $roleText; ?>
                                                        </span>
                                                        <span class="badge <?= $badge; ?> ms-1" style="font-size:10px;">
                                                            <?= $badgeText; ?>
                                                        </span>
                                                    </h6>

                                                    <?php if (!empty($link)): ?>
                                                        <a href="<?= $link; ?>" class="text-xs text-primary text-decoration-none">
                                                            <?= $descText; ?>
                                                            <i class="ni ni-bold-right ms-1"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-xs"><?= $descText; ?></span>
                                                    <?php endif; ?>

                                                    <span class="text-xs text-muted"><?= $timeText; ?></span>
                                                </div>
                                            </div>

                                            <button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto">
                                                <i class="ni ni-bold-right" aria-hidden="true"></i>
                                            </button>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                        <?php else: ?>
                            <div class="text-center text-muted py-5">
                                <i class="ni ni-notification-70 text-muted" style="font-size: 48px;"></i>
                                <p class="mt-3">Belum ada aktivitas terbaru</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal: Login Activity Monitor (Admin Only) -->
    <div class="modal fade" id="loginActivityModal" tabindex="-1" aria-labelledby="loginActivityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginActivityModalLabel">
                        <i class="fa fa-users me-2"></i>Login Activity Monitor
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="roleSelector" class="form-label">Select Role:</label>
                        <select class="form-select" id="roleSelector">
                            <option value="">-- All Roles Summary --</option>
                            <option value="Perencanaan">Perencanaan</option>
                            <option value="Admin">Admin</option>
                            <option value="Operasi Sistem Distribusi">Operasi Sistem Distribusi</option>
                            <option value="Fasilitas Operasi">Fasilitas Operasi</option>
                            <option value="Pemeliharaan">Pemeliharaan</option>
                            <option value="K3L & KAM">K3L & KAM</option>
                        </select>
                    </div>

                    <div id="loadingSpinner" class="text-center my-4" style="display:none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading data...</p>
                    </div>

                    <div id="summaryView" style="display:none;">
                        <h6 class="mb-3" style="font-size: 0.95rem;">Login Summary by Role</h6>
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 35%;">Role</th>
                                        <th style="width: 18%;" class="text-center">Users</th>
                                        <th style="width: 18%;" class="text-center">Logins</th>
                                        <th style="width: 29%;">Latest</th>
                                    </tr>
                                </thead>
                                <tbody id="summaryTableBody"></tbody>
                            </table>
                        </div>
                    </div>

                    <div id="detailView" style="display:none;">
                        <h6 class="mb-3" style="font-size: 0.95rem;">Users in <span id="selectedRoleName" class="text-primary"></span> Role</h6>
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 8%;" class="text-center">#</th>
                                        <th style="width: 28%;">Name</th>
                                        <th style="width: 32%;">Email</th>
                                        <th style="width: 12%;" class="text-center">Count</th>
                                        <th style="width: 20%;">Last Login</th>
                                    </tr>
                                </thead>
                                <tbody id="detailTableBody"></tbody>
                            </table>
                        </div>
                    </div>

                    <div id="errorMessage" class="alert alert-danger" style="display:none;" role="alert">
                        <i class="fa fa-exclamation-triangle me-2"></i>
                        <span id="errorText"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</main>

<style>
/* Login Count Card */
.login-count-card { transition: transform 0.2s ease, box-shadow 0.2s ease !important; }
.login-count-card:hover { transform: translateY(-2px) !important; box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important; }
.login-count-card .icon-shape { transition: transform 0.2s ease !important; }
.login-count-card:hover .icon-shape { transform: scale(1.05) !important; }
.login-count-card .badge { white-space: nowrap; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
@media (max-width: 768px) { .login-count-card .col-auto { margin-bottom: 0.5rem; } .login-count-card h4 { font-size: 1.5rem !important; } }

/* KPI ideal: tidak terlalu besar, tidak terlalu kecil */
.kpi-ideal{
    height: 168px;
    border-radius: 14px;
    overflow: hidden;
    transition: transform .18s ease, box-shadow .18s ease;
}
.kpi-ideal:hover{
    transform: translateY(-2px);
    box-shadow: 0 10px 22px rgba(0,0,0,.08);
}
.kpi-body{
    padding: 16px !important;
    display:flex;
    flex-direction:column;
    height:100%;
}
.kpi-head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin-bottom: 10px;
}
.kpi-title{
    font-size: 12px;
    letter-spacing: .6px;
    font-weight: 900;
    color:#344767;
    text-transform: uppercase;
    line-height:1.1;
}
.kpi-sub{
    font-size: 11px;
    color:#7b8ca4;
    margin-top:3px;
    line-height:1.1;
}
.kpi-ic{
    width: 38px;
    height: 38px;
    border-radius: 14px;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow: 0 6px 14px rgba(0,0,0,.12);
}
.kpi-ic i{ color:#fff; font-size: 17px; }

/* AI/AO line */
.kpi-duo{
    flex: 1;
    display:flex;
    flex-direction:column;
    justify-content:center;
    gap: 7px;
}
.kpi-line{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap: 10px;
}
.kpi-badge{
    font-size: 11px;
    font-weight: 900;
    padding: 4px 10px;
    border-radius: 999px;
    line-height:1;
}
.badge-ai{ background: rgba(17,205,239,.16); color:#11cdef; }
.badge-ao{ background: rgba(45,206,137,.16); color:#2dce89; }
.kpi-val{
    font-size: 13px;
    font-weight: 900;
    color:#344767;
    white-space: nowrap;
    text-align:right;
}
.kpi-cut{
    height:1px;
    width:100%;
    background: rgba(0,0,0,.06);
}

/* PRK big */
.kpi-big{
    font-size: 30px;
    font-weight: 900;
    color:#344767;
    line-height:1;
    margin-top: 4px;
}
.kpi-footnote{
    font-size: 11px;
    color:#7b8ca4;
    margin-top:auto;
    line-height:1.2;
}

/* Chart ideal */
.chart-ideal{
    border-radius: 14px;
}
.chart-ideal .card-body{
    padding: 14px 16px !important;
}

/* Activity scroll */
.activity-scroll{
    max-height: 355px;
    overflow-y: auto;
    padding-right: 6px;
}
.activity-scroll::-webkit-scrollbar{ width: 6px; }
.activity-scroll::-webkit-scrollbar-thumb{ background: rgba(0,0,0,0.15); border-radius: 10px; }
.activity-scroll::-webkit-scrollbar-track{ background: transparent; }
</style>

