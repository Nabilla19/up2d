<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Profil Pengguna</h6>
        </div>
        <div class="card-body">
            <p>Username: <strong><?= htmlentities($this->session->userdata('username') ?: 'User') ?></strong></p>
            <p>Role: <strong><?= htmlentities($this->session->userdata('user_role') ?: '-') ?></strong></p>
            <p>Mail: <strong><?= htmlentities($this->session->userdata('email') ?: '-') ?></strong></p>
            <a class="btn btn-secondary" href="<?= base_url('dashboard') ?>">Kembali</a>
        </div>
    </div>
</div>
