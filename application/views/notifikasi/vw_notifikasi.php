<main class="main-content position-relative border-radius-lg">
    <?php $this->load->view('layout/navbar'); ?>
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
        <div class="container-fluid py-1 px-3 d-flex justify-content-between align-items-center">
            <h6 class="font-weight-bolder mb-0">
                <i class="ni ni-bell-55 text-primary text-sm opacity-10 me-2"></i>
                Notifikasi Aktivitas
            </h6>
            <div>
                <a href="<?= base_url('dashboard'); ?>" class="btn btn-sm btn-secondary me-2 d-flex align-items-center no-anim">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <?php if (!empty($notifikasi) && $unread_count > 0): ?>
                    <a href="<?= base_url('Notifikasi/mark_all_read'); ?>" class="btn btn-sm btn-primary">
                        <i class="ni ni-check-bold"></i> Tandai Semua Dibaca
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ni ni-check-bold"></i> <?= $this->session->flashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-lg border-radius-xl">
            <div class="card-header bg-gradient-primary text-white d-flex align-items-center justify-content-between">
                <h6 class="mb-0">
                    <i class="ni ni-notification-70 me-2 text-white"></i> 
                    Riwayat Aktivitas Sistem
                </h6>
                <span class="badge bg-white text-primary">
                    <?= $unread_count ?> Belum Dibaca
                </span>
            </div>

            <div class="card-body px-4 py-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="notifTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th width="15%">Waktu</th>
                                <th width="15%">User</th>
                                <th width="12%">Role</th>
                                <th width="10%">Aktivitas</th>
                                <th width="33%">Deskripsi</th>
                                <th class="text-center" width="10%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($notifikasi)): ?>
                                <?php $no = 1; foreach ($notifikasi as $n): ?>
                                    <tr class="<?= $n['status_baca'] == 0 ? 'table-warning' : '' ?>">
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td>
                                            <small><?= date('d M Y', strtotime($n['tanggal_waktu'])) ?></small><br>
                                            <strong><?= date('H:i:s', strtotime($n['tanggal_waktu'])) ?></strong>
                                        </td>
                                        <td>
                                            <i class="ni ni-single-02 text-primary me-1"></i>
                                            <?= htmlspecialchars($n['email']) ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-white">
                                                <?= htmlspecialchars($n['role']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($n['jenis_aktivitas'] == 'login'): ?>
                                                <span class="badge bg-success">
                                                    <i class="ni ni-check-bold"></i> Login
                                                </span>
                                            <?php elseif ($n['jenis_aktivitas'] == 'logout'): ?>
                                                <span class="badge bg-secondary">
                                                    <i class="ni ni-button-power"></i> Logout
                                                </span>
                                            <?php elseif ($n['jenis_aktivitas'] == 'create'): ?>
                                                <span class="badge bg-success">
                                                    <i class="ni ni-fat-add"></i> Tambah
                                                </span>
                                            <?php elseif ($n['jenis_aktivitas'] == 'update'): ?>
                                                <span class="badge bg-warning text-dark">
                                                    <i class="ni ni-settings"></i> Edit
                                                </span>
                                            <?php elseif ($n['jenis_aktivitas'] == 'delete'): ?>
                                                <span class="badge bg-danger">
                                                    <i class="ni ni-fat-remove"></i> Hapus
                                                </span>
                                            <?php elseif ($n['jenis_aktivitas'] == 'import'): ?>
                                                <span class="badge bg-info">
                                                    <i class="ni ni-cloud-upload-96"></i> Import
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-dark"><?= ucfirst($n['jenis_aktivitas']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php 
                                            // Jika ada module dan record_id, buat link yang mark as read dulu
                                            if (!empty($n['module']) && !empty($n['record_id']) && $n['jenis_aktivitas'] != 'delete'): 
                                                $read_url = base_url('Notifikasi/read/' . $n['id']);
                                            ?>
                                                <a href="<?= $read_url ?>" class="text-primary text-decoration-none">
                                                    <?= htmlspecialchars($n['deskripsi']) ?>
                                                    <i class="ni ni-bold-right ms-1"></i>
                                                </a>
                                            <?php else: ?>
                                                <?= htmlspecialchars($n['deskripsi']) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($n['status_baca'] == 0): ?>
                                                <span class="badge bg-danger">
                                                    <i class="ni ni-bell-55"></i> Baru
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">
                                                    <i class="ni ni-check-bold"></i> Dibaca
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="ni ni-notification-70 text-muted" style="font-size: 48px;"></i>
                                        <p class="mt-3">Belum ada aktivitas yang tercatat</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- 🔁 AJAX Auto Refresh Notifikasi -->
<script>
    function updateNotifikasi() {
        fetch('<?= base_url("Notifikasi/get_latest"); ?>')
            .then(response => response.json())
            .then(data => {
                let tbody = '';
                let no = 1;
                
                if (data.latest && data.latest.length > 0) {
                    data.latest.forEach(n => {
                        let rowClass = n.status_baca == 0 ? 'table-warning' : '';
                        let aktivitasBadge = '';
                        
                        if (n.jenis_aktivitas === 'login') {
                            aktivitasBadge = '<span class="badge bg-success"><i class="ni ni-check-bold"></i> Login</span>';
                        } else if (n.jenis_aktivitas === 'logout') {
                            aktivitasBadge = '<span class="badge bg-secondary"><i class="ni ni-button-power"></i> Logout</span>';
                        } else if (n.jenis_aktivitas === 'create') {
                            aktivitasBadge = '<span class="badge bg-success"><i class="ni ni-fat-add"></i> Tambah</span>';
                        } else if (n.jenis_aktivitas === 'update') {
                            aktivitasBadge = '<span class="badge bg-warning text-dark"><i class="ni ni-settings"></i> Edit</span>';
                        } else if (n.jenis_aktivitas === 'delete') {
                            aktivitasBadge = '<span class="badge bg-danger"><i class="ni ni-fat-remove"></i> Hapus</span>';
                        } else if (n.jenis_aktivitas === 'import') {
                            aktivitasBadge = '<span class="badge bg-info"><i class="ni ni-cloud-upload-96"></i> Import</span>';
                        } else {
                            aktivitasBadge = `<span class="badge bg-dark">${n.jenis_aktivitas.charAt(0).toUpperCase() + n.jenis_aktivitas.slice(1)}</span>`;
                        }
                        
                        // Build deskripsi dengan link jika ada module dan record_id
                        let deskripsi = n.deskripsi;
                        if (n.module && n.record_id && n.jenis_aktivitas !== 'delete') {
                            let readUrl = '<?= base_url(); ?>Notifikasi/read/' + n.id;
                            deskripsi = `<a href="${readUrl}" class="text-primary text-decoration-none">${n.deskripsi} <i class="ni ni-bold-right ms-1"></i></a>`;
                        }
                        
                        let statusBadge = n.status_baca == 0 
                            ? '<span class="badge bg-danger"><i class="ni ni-bell-55"></i> Baru</span>'
                            : '<span class="badge bg-secondary"><i class="ni ni-check-bold"></i> Dibaca</span>';
                        
                        let date = new Date(n.tanggal_waktu);
                        let dateStr = date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                        let timeStr = date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                        
                        tbody += `
                            <tr class="${rowClass}">
                                <td class="text-center">${no++}</td>
                                <td>
                                    <small>${dateStr}</small><br>
                                    <strong>${timeStr}</strong>
                                </td>
                                <td>
                                    <i class="ni ni-single-02 text-primary me-1"></i>
                                    ${n.email}
                                </td>
                                <td>
                                    <span class="badge bg-info text-white">${n.role}</span>
                                </td>
                                <td>${aktivitasBadge}</td>
                                <td>${deskripsi}</td>
                                <td class="text-center">${statusBadge}</td>
                            </tr>
                        `;
                    });
                } else {
                    tbody = `
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="ni ni-notification-70 text-muted" style="font-size: 48px;"></i>
                                <p class="mt-3">Belum ada aktivitas yang tercatat</p>
                            </td>
                        </tr>
                    `;
                }

                document.querySelector('#notifTable tbody').innerHTML = tbody;
            })
            .catch(error => console.error('Error updating notifications:', error));
    }

    // Refresh otomatis tiap 15 detik
    setInterval(updateNotifikasi, 15000);
</script>

<style>
    .card-header {
        font-weight: 600;
        font-size: 15px;
    }

    .badge {
        font-size: 11px;
        padding: 5px 10px;
    }

    table tr:hover {
        background-color: #f5f7fa !important;
    }

    .table-warning {
        background-color: #fff3cd !important;
    }

    .table-warning:hover {
        background-color: #ffe69c !important;
    }
</style>