<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="theme-color" content="#0d6efd" />

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('assets/assets/img/apple-icon.png'); ?>">
  <link rel="icon" type="image/png" href="<?= base_url('assets/assets/img/logo_pln.png'); ?>">

  <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' - PLN UP2D RIAU' : 'PLN UP2D RIAU'; ?></title>

  <!-- Preconnect + Fonts -->
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&display=swap" rel="stylesheet" />

  <!-- Core CSS (local) -->
  <link href="<?= base_url('assets/assets/css/nucleo-icons.css'); ?>" rel="stylesheet" />
  <link href="<?= base_url('assets/assets/css/nucleo-svg.css'); ?>" rel="stylesheet" />
  <link id="pagestyle" href="<?= base_url('assets/assets/css/argon-dashboard.css?v=2.1.0'); ?>" rel="stylesheet" />
  <link rel="stylesheet" href="<?= base_url('assets/assets/css/sidebar.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/assets/css/sidebar-collapsed.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/assets/css/pln-theme.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/assets/css/responsive.css'); ?>">>>

  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <!-- Font Awesome (dipakai untuk semua ikon menu supaya tidak kotak-kotak) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous">

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

  <style>
    /* 1) Pastikan bullet/marker default tidak tampil di sidebar */
    .sidenav ul,
    .sidenav li {
      list-style: none !important;
      margin: 0;
      padding: 0;
    }

    /* 2) Matikan pseudo-element (before/after) yang menambahkan panah/marker */
    .sidenav .nav-link::before,
    .sidenav .nav-link::after,
    .sidenav .nav-item::before,
    .sidenav .nav-item::after,
    .sidenav .nav-link[data-bs-toggle="collapse"]::after {
      content: none !important;
      display: none !important;
      background-image: none !important;
    }

    /* 3) Pastikan panah manual (fa-chevron-down) yang ada di markup tetap di kanan */
    .sidenav .nav-link {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .sidenav .nav-link .fa-chevron-down {
      margin-left: auto;
      order: 2;
    }

    /* 4) Sedikit spacing supaya ikon & teks rapi */
    .sidenav .nav-link i {
      margin-right: 0.6rem;
    }

    .sidenav .nav-link .nav-link-text {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    /* 5) Matikan ::marker */
    .sidenav li::marker {
      content: none !important;
      display: none !important;
    }

    /* Tambahan: pastikan submenu bisa diklik (tidak ketutup overlay collapse) */
    .submenu-list .nav-link,
    #menuAnggaran .nav-link {
      position: relative;
      z-index: 5;
      pointer-events: auto;
    }
  </style>

</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100" aria-hidden="true"></div>

  <?php
  // =========================
  // Role + segment helper
  // =========================
  $role = null;
  if (isset($this) && isset($this->session)) {
    $r = $this->session->userdata('user_role') ?: $this->session->userdata('role');
    $role = $r ? strtolower(trim($r)) : null;
  } elseif (isset($_SESSION['user_role'])) {
    $role = strtolower(trim($_SESSION['user_role']));
  }

  $role_json = json_encode($role);
  $seg1 = strtolower((string)($this->uri->segment(1) ?? ''));

  // Role UP3 (lebih aman pakai "contains", karena kadang role bisa "UP3 Pekanbaru", dll)
  $is_up3 = ($role !== null) && (strpos($role, 'up3') !== false);

  // Route groups (lowercase)
  $asset_routes   = ['unit', 'ulp', 'gardu_induk', 'gi_cell', 'gardu_hubung', 'gh_cell', 'pembangkit', 'kit_cell', 'pemutus', 'assets'];
  $pustaka_routes = ['sop', 'bpm', 'ik', 'road_map', 'spln'];
  $operasi_routes = ['operasi', 'single_line_diagram'];
  $anggaran_routes = ['anggaran', 'data_kontrak', 'monitoring', 'rekomposisi', 'rekap_prk', 'entry_kontrak', 'prognosa'];
  $transport_routes = ['transport'];
  
  $role_id = $this->session->userdata('role_id');
  ?>

  <!-- Sidebar -->
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main" role="navigation" aria-label="Sidebar utama">
    <div class="sidenav-header">
      <button class="btn btn-icon btn-sm text-secondary d-xl-none" id="iconSidenav" aria-label="Tutup sidebar">
        <i class="fas fa-times"></i>
      </button>

      <a class="navbar-brand m-0" href="<?= base_url('dashboard'); ?>">
        <img src="<?= base_url('assets/assets/img/logo_pln.png'); ?>" alt="Logo PLN" class="navbar-brand-img h-100" style="height:55px; width:auto;">
        <span class="ms-2 font-weight-bold text-dark">PLN UP2D RIAU</span>
      </a>
    </div>

    <hr class="horizontal dark mt-0">

    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <!-- Dashboard -->
        <li class="nav-item">
          <a class="nav-link <?= ($seg1 == 'dashboard') ? 'active' : '' ?>" href="<?= base_url('dashboard'); ?>" aria-current="<?= ($seg1 == 'dashboard') ? 'page' : 'false' ?>">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <!-- Pakai Font Awesome agar tidak kotak -->
              <i class="fas fa-gauge text-primary text-sm opacity-10" aria-hidden="true"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>

        <!-- Asset -->
        <?php if ($role !== 'k3l & kam' && $role_id != 19): ?>
          <?php $asset_active = in_array($seg1, $asset_routes, true); ?>
          <li class="nav-item">
            <a href="#menuAsset"
              class="nav-link d-flex align-items-center justify-content-between <?= $asset_active ? 'active text-dark bg-light' : 'text-secondary' ?>"
              data-bs-toggle="collapse" role="button" aria-expanded="<?= $asset_active ? 'true' : 'false' ?>" aria-controls="menuAsset" style="font-weight: 600;">
              <div class="d-flex align-items-center">
                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                  <i class="fas fa-boxes-stacked text-dark text-sm opacity-10" aria-hidden="true"></i>
                </div>
                <span class="nav-link-text">Asset</span>
              </div>
              <i class="fas fa-chevron-down text-xs me-2"></i>
            </a>

            <!-- FIX: collapse HARUS di dalam <li> -->
            <div class="collapse <?= $asset_active ? 'show' : '' ?>" id="menuAsset" role="region" aria-label="Submenu Asset">
              <ul class="nav flex-column submenu-list">
                <li class="nav-item">
                  <a class="nav-link <?= ($seg1 == 'unit') ? 'active bg-primary text-white' : '' ?>" href="<?= base_url('unit'); ?>">
                    <i class="fas fa-building me-2 text-success"></i><span class="nav-link-text">Unit</span>
                  </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link <?= ($seg1 == 'gardu_induk') ? 'active bg-primary text-white' : '' ?>" href="<?= base_url('gardu_induk'); ?>">
                    <i class="fas fa-bolt me-2 text-warning"></i><span class="nav-link-text">Gardu Induk</span>
                  </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link <?= ($seg1 == 'gi_cell') ? 'active bg-primary text-white' : '' ?>" href="<?= base_url('gi_cell'); ?>">
                    <i class="fas fa-wave-square me-2 text-info"></i><span class="nav-link-text">GI Cell</span>
                  </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link <?= ($seg1 == 'gardu_hubung') ? 'active bg-primary text-white' : '' ?>" href="<?= base_url('gardu_hubung'); ?>">
                    <i class="fas fa-network-wired me-2 text-primary"></i><span class="nav-link-text">Gardu Hubung</span>
                  </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link <?= ($seg1 == 'gh_cell') ? 'active bg-primary text-white' : '' ?>" href="<?= base_url('gh_cell'); ?>">
                    <i class="fas fa-square me-2 text-secondary"></i><span class="nav-link-text">GH Cell</span>
                  </a>
                </li>

                <li class="nav-item">
                  <!-- URL tetap mengikuti yang kamu pakai -->
                  <a class="nav-link <?= ($seg1 == 'pembangkit') ? 'active bg-primary text-white' : '' ?>" href="<?= base_url('Pembangkit'); ?>">
                    <i class="fas fa-industry me-2 text-danger"></i><span class="nav-link-text">Pembangkit</span>
                  </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link <?= ($seg1 == 'kit_cell') ? 'active bg-primary text-white' : '' ?>" href="<?= base_url('Kit_cell'); ?>">
                    <i class="fas fa-microchip me-2 text-primary"></i><span class="nav-link-text">Kit Cell</span>
                  </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link <?= ($seg1 == 'pemutus') ? 'active bg-primary text-white' : '' ?>" href="<?= base_url('Pemutus'); ?>">
                    <i class="fas fa-toggle-on me-2 text-warning"></i><span class="nav-link-text">Pemutus</span>
                  </a>
                </li>

              </ul>
            </div>
          </li>
        <?php endif; ?>

        <!-- Pengaduan -->
        <?php if ($role !== 'operasi sistem distribusi' && $role !== 'k3l & kam' && $role_id != 19): ?>
          <li class="nav-item">
            <a class="nav-link <?= ($seg1 == 'pengaduan') ? 'active' : '' ?>" href="<?= base_url('pengaduan'); ?>">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fas fa-file-alt text-dark text-sm opacity-10" aria-hidden="true"></i>
              </div>
              <span class="nav-link-text ms-1">Pengaduan</span>
            </a>
          </li>
        <?php endif; ?>

        <!-- Pustaka -->
        <?php if ($role_id != 19): ?>
        <?php $pustaka_active = in_array($seg1, $pustaka_routes, true); ?>
        <li class="nav-item">
          <a href="#menuPustaka" class="nav-link d-flex align-items-center justify-content-between <?= $pustaka_active ? 'active' : '' ?>"
            data-bs-toggle="collapse" role="button" aria-expanded="<?= $pustaka_active ? 'true' : 'false' ?>" aria-controls="menuPustaka">
            <div class="d-flex align-items-center">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fas fa-book text-dark text-sm opacity-10" aria-hidden="true"></i>
              </div>
              <span class="nav-link-text ms-1">Pustaka</span>
            </div>
            <i class="fas fa-chevron-down text-xs me-2"></i>
          </a>

          <!-- FIX: collapse HARUS di dalam <li> -->
          <div class="collapse <?= $pustaka_active ? 'show' : '' ?>" id="menuPustaka">
            <ul class="nav flex-column submenu-list">
              <li class="nav-item">
                <a class="nav-link <?= ($seg1 == 'sop') ? 'active' : '' ?>" href="<?= base_url('sop'); ?>">
                  <i class="fas fa-file-alt text-dark text-sm opacity-10"></i><span class="nav-link-text"> SOP</span>
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link <?= ($seg1 == 'bpm') ? 'active' : '' ?>" href="<?= base_url('bpm'); ?>">
                  <i class="fas fa-project-diagram text-dark text-sm opacity-10"></i><span class="nav-link-text"> BPM</span>
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link <?= ($seg1 == 'ik') ? 'active' : '' ?>" href="<?= base_url('ik'); ?>">
                  <i class="fas fa-info-circle text-dark text-sm opacity-10"></i><span class="nav-link-text"> IK</span>
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link <?= ($seg1 == 'road_map') ? 'active' : '' ?>" href="<?= base_url('road_map'); ?>">
                  <i class="fas fa-road text-dark text-sm opacity-10"></i><span class="nav-link-text"> Road Map</span>
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link <?= ($seg1 == 'spln') ? 'active' : '' ?>" href="<?= base_url('spln'); ?>">
                  <i class="fas fa-bolt text-dark text-sm opacity-10"></i><span class="nav-link-text"> SPLN</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        <?php endif; ?>

        <!-- Operasi -->
        <?php if ($role !== 'perencanaan' && $role !== 'pemeliharaan' && $role !== 'fasilitas operasi' && $role !== 'k3l & kam' && $role_id != 19): ?>
          <?php $operasi_active = in_array($seg1, $operasi_routes, true); ?>
          <li class="nav-item">
            <a href="#menuOperasi" class="nav-link d-flex align-items-center justify-content-between <?= $operasi_active ? 'active' : '' ?>"
              data-bs-toggle="collapse" role="button" aria-expanded="<?= $operasi_active ? 'true' : 'false' ?>" aria-controls="menuOperasi">
              <div class="d-flex align-items-center">
                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                  <i class="fas fa-globe text-dark text-sm opacity-10" aria-hidden="true"></i>
                </div>
                <span class="nav-link-text ms-1">Operasi</span>
              </div>
              <i class="fas fa-chevron-down text-xs me-2"></i>
            </a>

            <!-- FIX: collapse HARUS di dalam <li> -->
            <div class="collapse <?= $operasi_active ? 'show' : '' ?>" id="menuOperasi">
              <ul class="nav flex-column submenu-list">
                <li class="nav-item">
                  <a class="nav-link <?= ($seg1 == 'single_line_diagram') ? 'active' : '' ?>" href="<?= base_url('single_line_diagram'); ?>">
                    <i class="fas fa-project-diagram text-dark text-sm opacity-10"></i><span class="nav-link-text"> Single Line Diagram</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        <?php endif; ?>

        <!-- Anggaran (DISIMPANHILANGKAN UNTUK ROLE UP3) -->
        <?php if (!$is_up3 && $role_id != 19): ?>
          <?php $anggaran_active = in_array($seg1, $anggaran_routes, true); ?>
          <li class="nav-item">
            <a href="#menuAnggaran" class="nav-link d-flex align-items-center justify-content-between <?= $anggaran_active ? 'active text-dark bg-light' : '' ?>"
              data-bs-toggle="collapse" role="button" aria-expanded="<?= $anggaran_active ? 'true' : 'false' ?>" aria-controls="menuAnggaran">
              <div class="d-flex align-items-center">

                <div class=" icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                  <i class="fas fa-credit-card text-dark text-sm opacity-10" aria-hidden="true"></i>
                </div>
                <span class="nav-link-text ms-1">Anggaran</span>
              </div>
              <i class="fas fa-chevron-down text-xs me-2"></i>
            </a>

            <div class="collapse <?= $anggaran_active ? 'show' : '' ?>" id="menuAnggaran">
              <ul class="nav ms-4">
                <li class="nav-item">
                  <a class="nav-link <?= ($seg1 == 'rekomposisi') ? 'active' : '' ?>" href="<?= base_url('rekomposisi'); ?>">
                    <i class="fas fa-random me-2"></i> Rekomposisi
                  </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link <?= ($seg1 == 'entry_kontrak') ? 'active' : '' ?>" href="<?= base_url('entry_kontrak'); ?>">
                    <i class="fas fa-file-signature me-2"></i> Entry Kontrak
                  </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link <?= ($seg1 == 'monitoring') ? 'active' : '' ?>" href="<?= base_url('monitoring'); ?>">
                    <i class="fas fa-chart-line me-2"></i> Monitoring
                  </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link <?= ($seg1 == 'rekap_prk') ? 'active' : '' ?>" href="<?= base_url('rekap_prk'); ?>">
                    <i class="fas fa-clipboard-list me-2"></i> Rekap PRK
                  </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link <?= ($seg1 == 'prognosa') ? 'active' : '' ?>" href="<?= base_url('prognosa'); ?>">
                    <i class="fas fa-chart-pie me-2"></i> Prognosa
                  </a>
                </li>
              </ul>
            </div>
          </li>
        <?php endif; ?>

        <!-- E-Transport -->
        <?php $transport_active = in_array($seg1, $transport_routes, true); ?>
        <li class="nav-item">
          <a href="#menuTransport" class="nav-link d-flex align-items-center justify-content-between <?= $transport_active ? 'active' : '' ?>"
            data-bs-toggle="collapse" role="button" aria-expanded="<?= $transport_active ? 'true' : 'false' ?>" aria-controls="menuTransport">
            <div class="d-flex align-items-center">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fas fa-car text-dark text-sm opacity-10" aria-hidden="true"></i>
              </div>
              <span class="nav-link-text ms-1">E-Transport</span>
            </div>
            <i class="fas fa-chevron-down text-xs me-2"></i>
          </a>

          <div class="collapse <?= $transport_active ? 'show' : '' ?>" id="menuTransport">
            <ul class="nav flex-column submenu-list">
              <li class="nav-item">
                <a class="nav-link <?= ($this->uri->segment(2) == 'ajukan') ? 'active' : '' ?>" href="<?= base_url('transport/ajukan'); ?>">
                  <i class="fas fa-plus-circle text-primary text-sm opacity-10"></i><span class="nav-link-text"> Ajukan Permohonan</span>
                </a>
              </li>

                <li class="nav-item">
                  <a class="nav-link <?= ($this->uri->segment(2) == 'semua_daftar') ? 'active' : '' ?>" href="<?= base_url('transport/semua_daftar'); ?>">
                    <i class="fas fa-briefcase text-info text-sm opacity-10"></i><span class="nav-link-text"> Daftar Permohonan Unit</span>
                  </a>
                </li>



              <?php if (in_array($role_id, [15, 16, 17, 18, 6]) || $role === 'kku'): // Asmen + Admin + KKU ?>
                <li class="nav-item">
                  <a class="nav-link <?= ($seg1 == 'transport' && $this->uri->segment(2) == 'approval') ? 'active' : '' ?>" href="<?= base_url('transport/approval'); ?>">
                    <i class="fas fa-check-double text-success text-sm opacity-10"></i><span class="nav-link-text"> Persetujuan Asmen / KKU</span>
                  </a>
                </li>
              <?php endif; ?>

              <?php if ($role === 'kku' || $role_id == 6): // KKU + Admin ?>
                <li class="nav-item">
                  <a class="nav-link <?= ($seg1 == 'transport' && $this->uri->segment(2) == 'fleet') ? 'active' : '' ?>" href="<?= base_url('transport/fleet'); ?>">
                    <i class="fas fa-truck-moving text-warning text-sm opacity-10"></i><span class="nav-link-text"> Manajemen Fleet (KKU)</span>
                  </a>
                </li>
              <?php endif; ?>

              <?php if ($role_id == 19 || $role_id == 6): // Security + Admin ?>
                <li class="nav-item">
                  <a class="nav-link <?= ($seg1 == 'transport' && $this->uri->segment(2) == 'security') ? 'active' : '' ?>" href="<?= base_url('transport/security'); ?>">
                    <i class="fas fa-shield-halved text-danger text-sm opacity-10"></i><span class="nav-link-text"> Pos Security</span>
                  </a>
                </li>
              <?php endif; ?>
            </ul>
          </div>
        </li>

        <!-- Account Pages -->
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account Pages</h6>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('login'); ?>">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-right-to-bracket text-dark text-sm opacity-10" aria-hidden="true"></i>
            </div>
            <span class="nav-link-text ms-1">Sign In</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('pages/sign-up'); ?>">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-user-plus text-dark text-sm opacity-10" aria-hidden="true"></i>
            </div>
            <span class="nav-link-text ms-1">Sign Up</span>
          </a>
        </li>

        <?php if (isset($this->session) && $this->session->userdata('logged_in')): ?>
          <li class="nav-item">
            <a class="nav-link btn-logout" href="<?= base_url('logout'); ?>">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fas fa-power-off text-danger text-sm opacity-10" aria-hidden="true"></i>
              </div>
              <span class="nav-link-text ms-1">Logout</span>
            </a>
          </li>
        <?php endif; ?>

      </ul>
    </div>
  </aside>

  </aside>
