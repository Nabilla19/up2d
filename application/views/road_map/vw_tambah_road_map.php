<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
        data-scroll="false">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
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
                <h6 class="mb-0 text-white"><i class="fas fa-road me-2"></i> Form Tambah Road Map</h6>
            </div>

            <div class="card-body bg-white px-4 py-4">
                <form action="<?= base_url('road_map/tambah'); ?>" method="POST" enctype="multipart/form-data">

                    <!-- Nama File -->
                    <div class="mb-3">
                        <label for="NAMA_FILE" class="form-label fw-bold">Nama File Road Map</label>
                        <input type="text" class="form-control border rounded-3" id="NAMA_FILE" name="NAMA_FILE"
                            placeholder="Masukkan nama file Road Map..." required>
                    </div>

                    <!-- Created By -->
                    <div class="mb-3">
                        <label for="CREATED_BY" class="form-label fw-bold">Dibuat Oleh</label>
                        <input type="text" class="form-control border rounded-3" id="CREATED_BY" name="CREATED_BY"
                            placeholder="Masukkan nama pembuat Road Map..." required>
                    </div>

                    <!-- Upload File -->
                    <div class="mb-3">
                        <label for="FILE_ROADMAP" class="form-label fw-bold">Upload File Road Map</label>
                        <input type="file" class="form-control border rounded-3" id="FILE_ROADMAP" name="FILE_ROADMAP"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" required>
                        <small class="text-muted fst-italic">
                            Format yang diizinkan: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX
                        </small>
                    </div>

                    <!-- Tombol -->
                    <div class="d-flex justify-content-start align-items-center gap-2 mt-4">
                        <a href="<?= base_url('road_map'); ?>" class="btn btn-secondary px-4">
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

<!-- Style tambahan -->
<style>
    .bg-gradient-primary {
        background: linear-gradient(90deg, #00695c, #26a69a);
    }

    .form-control:focus {
        border-color: #26a69a;
        box-shadow: 0 0 0 0.2rem rgba(38, 166, 154, 0.25);
    }

    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn {
        border-radius: 8px;
    }

    .gap-2 {
        gap: 0.75rem !important;
    }
</style>