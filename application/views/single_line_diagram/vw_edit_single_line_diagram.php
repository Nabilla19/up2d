<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
        data-scroll="false">
        <div class="container-fluid py-1 px-3"></div>
    </nav>

    <!-- Content -->
    <div class="container-fluid py-4">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-gradient-primary text-white rounded-top-4">
                <strong><i class="fas fa-edit me-2"></i> Form Edit Single Line Diagram</strong>
            </div>

            <div class="card-body">
                <form action="<?= base_url('Single_Line_Diagram/edit/' . urlencode($sld['ID_SLD'])); ?>" method="POST" enctype="multipart/form-data">
                    <div class="row g-3">

                        <!-- Unit Pelaksana (optional - rendered if $unit available) -->
                        <?php if (!empty($unit) && is_array($unit)): ?>
                        <div class="col-md-6">
                            <label class="form-label">Unit Pelaksana</label>
                            <select name="ID_UNIT" class="form-control">
                                <option value="">-- Pilih Unit Pelaksana --</option>
                                <?php
                                $seen = [];
                                foreach ($unit as $u) {
                                    $name = isset($u['UNIT_PELAKSANA']) ? trim($u['UNIT_PELAKSANA']) : '';
                                    if ($name === '') continue;
                                    $key = strtolower($name);
                                    if (in_array($key, $seen)) continue;
                                    $seen[] = $key;
                                    $selected = (isset($sld['ID_UNIT']) && $sld['ID_UNIT'] == $u['ID_UNIT']) ? 'selected' : '';
                                    echo '<option value="' . htmlentities($u['ID_UNIT'], ENT_QUOTES, 'UTF-8') . '" ' . $selected . '>' . htmlentities($name, ENT_QUOTES, 'UTF-8') . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <!-- Nama GI -->
                        <div class="col-md-6">
                            <label class="form-label">Nama GI</label>
                            <input type="text" class="form-control" name="NAMA_GI"
                                value="<?= htmlentities($sld['NAMA_GI'] ?? ''); ?>" required>
                        </div>

                        <!-- Nama Penyulang -->
                        <div class="col-md-6">
                            <label class="form-label">Nama Penyulang</label>
                            <input type="text" class="form-control" name="NAMA_PENYULANG"
                                value="<?= htmlentities($sld['NAMA_PENYULANG'] ?? ''); ?>" required>
                        </div>

                        <!-- File Diagram -->
                        <div class="col-md-12">
                            <label class="form-label">File Diagram (PDF)</label>
                            <?php if (!empty($sld['FILE_PDF'])): ?>
                                <div class="mt-2 mb-3">
                                    <p class="mb-1">
                                        <i class="fas fa-file-pdf text-danger me-1"></i>
                                        <strong>File saat ini:</strong> <?= htmlentities($sld['FILE_PDF']); ?>
                                    </p>
                                    <a href="<?= base_url('uploads/sld/' . $sld['FILE_PDF']); ?>" target="_blank" class="btn btn-sm btn-info text-white me-2">
                                        <i class="fas fa-eye me-1"></i> Lihat File
                                    </a>
                                    <a href="<?= base_url('uploads/sld/' . $sld['FILE_PDF']); ?>" download class="btn btn-sm btn-success text-white">
                                        <i class="fas fa-download me-1"></i> Unduh
                                    </a>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Belum ada file diunggah.</p>
                            <?php endif; ?>

                            <input type="file" name="FILE_PDF" class="form-control" accept=".pdf" onchange="previewFileName(this)">
                            <small class="text-muted">Kosongkan jika tidak ingin mengganti file.</small>

                            <div class="mt-2" id="file-preview" style="display:none;">
                                <i class="fas fa-file-upload text-success me-1"></i>
                                <span id="file-name"></span>
                            </div>
                        </div>

                    </div>

                    <div class="mt-4">
                        <a href="<?= base_url('Single_Line_Diagram'); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SCRIPT -->
    <script>
        // Preview nama file yang diunggah
        function previewFileName(input) {
            const filePreview = document.getElementById('file-preview');
            const fileName = document.getElementById('file-name');

            if (input.files && input.files[0]) {
                fileName.textContent = input.files[0].name;
                filePreview.style.display = 'block';
            } else {
                filePreview.style.display = 'none';
            }
        }
    </script>

    <!-- STYLE -->
    <style>
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 600;
        }

        .bg-gradient-primary {
            background: linear-gradient(90deg, #00416A, #0099CC);
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
        }

        input.form-control {
            height: 40px !important;
            font-size: 0.9rem;
        }

        #file-preview {
            font-size: 0.9rem;
        }

        .btn i {
            font-size: 0.85rem;
        }

        .btn {
            border-radius: 8px;
        }
    </style>
</main>