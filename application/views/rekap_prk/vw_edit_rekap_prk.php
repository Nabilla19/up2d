<main class="main-content position-relative border-radius-lg">
    <!-- Header -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl">
        <div class="container-fluid py-1 px-3">
            <h6 class="font-weight-bolder text-white mb-0">
                <i class="fas fa-edit me-2 text-warning"></i> Edit Rekap PRK
            </h6>
        </div>
    </nav>

    <div class="container-fluid py-4">

        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-gradient-primary text-white rounded-top-4">
                <strong>Form Edit Rekap PRK</strong>
            </div>

            <div class="card-body">
                <form action="<?= base_url('rekap_prk/edit/' . $rekap['ID_PRK']); ?>" method="POST">

                    <input type="hidden" name="original_id" value="<?= $rekap['ID_PRK']; ?>">

                    <div class="row g-4">

                        <!-- Jenis Anggaran -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jenis Anggaran</label>
                            <select name="JENIS_ANGGARAN" id="jenisAnggaran" class="form-select modern-select" required>
                                <option value="">-- Pilih Jenis Anggaran --</option>
                                <option value="INVESTASI" <?= $rekap['JENIS_ANGGARAN'] == 'INVESTASI' ? 'selected' : '' ?>>INVESTASI</option>
                                <option value="OPERASI" <?= $rekap['JENIS_ANGGARAN'] == 'OPERASI' ? 'selected' : '' ?>>OPERASI</option>
                            </select>
                        </div>

                        <!-- Nomor PRK -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nomor PRK</label>
                            <select name="NOMOR_PRK" id="nomorPRK" class="form-select modern-select" required>
                                <option value="">Memuat data...</option>
                            </select>
                        </div>

                        <!-- Uraian PRK -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Uraian PRK</label>
                            <textarea name="URAIAN_PRK" id="uraianPRK" class="form-control modern-input" rows="2" required></textarea>
                        </div>

                        <!-- Pagu SKK IO -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pagu SKK-IO</label>
                            <input type="text" name="PAGU_SKK_IO" value="<?= $rekap['PAGU_SKK_IO']; ?>" class="form-control modern-input money-input">
                        </div>

                        <!-- Rencana Kontrak -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Rencana Kontrak</label>
                            <input type="text" name="RENC_KONTRAK" value="<?= $rekap['RENC_KONTRAK']; ?>" class="form-control modern-input money-input">
                        </div>

                        <!-- NODIN -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NODIN / Surat</label>
                            <input type="text" name="NODIN_SRT" value="<?= $rekap['NODIN_SRT']; ?>" class="form-control modern-input">
                        </div>

                        <!-- Kontrak -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kontrak</label>
                            <input type="text" name="KONTRAK" value="<?= $rekap['KONTRAK']; ?>" class="form-control modern-input money-input">
                        </div>

                        <!-- Sisa -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Sisa</label>
                            <input type="text" name="SISA" value="<?= $rekap['SISA']; ?>" class="form-control modern-input money-input">
                        </div>

                        <!-- Rencana Bayar -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Rencana Bayar</label>
                            <input type="text" name="RENCANA_BAYAR" value="<?= $rekap['RENCANA_BAYAR']; ?>" class="form-control modern-input money-input">
                        </div>

                        <!-- Terbayar -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Terbayar</label>
                            <input type="text" name="TERBAYAR" value="<?= $rekap['TERBAYAR']; ?>" class="form-control modern-input money-input">
                        </div>

                        <!-- Ke Tahun 2026 -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ke Tahun 2026</label>
                            <input type="text" name="KE_TAHUN_2026" value="<?= $rekap['KE_TAHUN_2026']; ?>" class="form-control modern-input money-input">
                        </div>

                        <!-- Buttons -->
                        <div class="col-12 mt-4">
                            <a href="<?= base_url('rekap_prk'); ?>" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>

                            <button type="submit" class="btn btn-primary ms-2">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    </div>
</main>


<style>
    .modern-select,
    .modern-input {
        border-radius: 10px;
        border: 1px solid #d0d7e3;
        padding: 10px 12px;
        transition: .2s ease;
    }

    .modern-select:focus,
    .modern-input:focus {
        border-color: #4e73df !important;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.25);
    }

    .bg-gradient-primary {
        background: linear-gradient(90deg, #005C99, #0099CC);
    }
</style>

<script>
    $(document).ready(function() {

        let jenisAwal = "<?= $rekap['JENIS_ANGGARAN']; ?>";
        let nomorAwal = "<?= $rekap['NOMOR_PRK']; ?>";

        // === LOAD NOMOR PRK SAAT HALAMAN EDIT DIBUKA ===
        loadNomorPRK(jenisAwal, nomorAwal);

        $("#jenisAnggaran").change(function() {
            let jenis = $(this).val();
            $("#nomorPRK").html('<option value="">Memuat...</option>');
            loadNomorPRK(jenis, "");
        });

        // Load Uraian PRK
        $("#nomorPRK").on("change", function() {
            loadUraian($(this).val());
        });

    });


    function loadNomorPRK(jenis, nomorDipilih = "") {

        $.ajax({
            url: "<?= base_url('rekap_prk/get_prk'); ?>",
            type: "POST",
            data: {
                jenis: jenis
            },
            dataType: "json",
            success: function(response) {

                if (response.length === 0) {
                    $("#nomorPRK").html('<option value="">Tidak ada PRK</option>');
                    return;
                }

                let options = '<option value="">-- Pilih Nomor PRK --</option>';

                response.forEach(function(row) {
                    let selected = (row.NOMOR_PRK === nomorDipilih) ? "selected" : "";
                    options += `<option value="${row.NOMOR_PRK}" ${selected}>${row.NOMOR_PRK}</option>`;
                });

                $("#nomorPRK").html(options);

                if (nomorDipilih !== "") {
                    loadUraian(nomorDipilih);
                }
            }
        });
    }


    // === GET URAIAN PRK ===
    function loadUraian(nomorPRK) {
        $.ajax({
            url: "<?= base_url('rekap_prk/get_uraian'); ?>",
            type: "POST",
            data: {
                nomor_prk: nomorPRK
            },
            dataType: "json",
            success: function(response) {
                if (response && response.PRK) {
                    $("#uraianPRK").val(response.PRK);
                } else {
                    $("#uraianPRK").val('');
                }
            }
        });
    }
</script>