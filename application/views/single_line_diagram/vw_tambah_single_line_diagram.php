<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
        data-scroll="false">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm">
                        <a class="opacity-5 text-white" href="<?= base_url('Single_Line_Diagram'); ?>">Single Line Diagram</a>
                    </li>
                    <li class="breadcrumb-item text-sm text-white active" aria-current="page">Tambah Single Line Diagram</li>
                </ol>
                <h6 class="font-weight-bolder text-white mb-0">
                    <i class="fas fa-project-diagram me-2"></i> Form Tambah Single Line Diagram
                </h6>
            </nav>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger text-white">
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-gradient-primary text-white rounded-top-4 py-3">
                <h6 class="mb-0 text-white"><i class="fas fa-plus-circle me-2"></i> Tambah Data Single Line Diagram</h6>
            </div>

            <div class="card-body bg-white px-4 py-4">
                <!-- ✅ Action diarahkan ke fungsi tambah -->
                <form action="<?= base_url('Single_Line_Diagram/tambah'); ?>" method="POST" enctype="multipart/form-data">

                    <!-- Unit Pelaksana -->
                    <div class="mb-3">
                        <label for="ID_UNIT" class="form-label fw-bold">Unit Pelaksana</label>
                        <select name="ID_UNIT" id="ID_UNIT" class="form-select border rounded-3" required>
                            <option value="">-- Pilih Unit Pelaksana --</option>
                            <?php
                            // tampilkan hanya UNIT_PELAKSANA dan hilangkan duplikat label
                            $seen_names = [];
                            foreach ($unit as $u) {
                                $name = isset($u['UNIT_PELAKSANA']) ? trim($u['UNIT_PELAKSANA']) : '';
                                if ($name === '') continue;
                                $key = strtolower($name);
                                if (in_array($key, $seen_names)) continue; // sudah ada, lewati
                                $seen_names[] = $key;
                                echo '<option value="' . htmlentities($u['ID_UNIT'], ENT_QUOTES, 'UTF-8') . '">' . htmlentities($name, ENT_QUOTES, 'UTF-8') . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Nama GI -->
                    <div class="mb-3">
                        <label for="NAMA_GI" class="form-label fw-bold">Nama GI</label>
                        <input type="text" class="form-control border rounded-3" id="NAMA_GI" name="NAMA_GI"
                            placeholder="Masukkan nama Gardu Induk..." required>
                    </div>

                    <!-- Nama Penyulang -->
                    <div class="mb-3">
                        <label for="NAMA_PENYULANG" class="form-label fw-bold">Nama Penyulang</label>
                        <input type="text" class="form-control border rounded-3" id="NAMA_PENYULANG" name="NAMA_PENYULANG"
                            placeholder="Masukkan nama penyulang..." required>
                    </div>

                    <!-- Upload File Diagram -->
                    <div class="mb-3">
                        <label for="FILE_PDF" class="form-label fw-bold">Upload File Diagram (PDF)</label>
                        <input type="file" class="form-control border rounded-3" id="FILE_PDF" name="FILE_PDF"
                            accept=".pdf" required>
                        <small class="text-muted fst-italic">
                            Format yang diizinkan: <strong>PDF</strong> (maksimum 10 MB)
                        </small>
                    </div>

                    <!-- Tombol -->
                    <div class="d-flex justify-content-start align-items-center gap-2 mt-4">
                        <a href="<?= base_url('Single_Line_Diagram'); ?>" class="btn btn-secondary px-4">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<style>
    .bg-gradient-primary {
        background: linear-gradient(90deg, #00416A, #0099CC);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0099CC;
        box-shadow: 0 0 0 0.2rem rgba(0, 153, 204, 0.25);
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn {
        border-radius: 8px;
    }
</style>