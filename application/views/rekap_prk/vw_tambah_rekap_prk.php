<main class="main-content position-relative border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm">
                        <a class="opacity-5 text-white" href="<?= base_url('dashboard'); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item text-sm">
                        <a class="opacity-5 text-white" href="<?= base_url('rekap_prk'); ?>">Rekap PRK</a>
                    </li>
                    <li class="breadcrumb-item text-sm text-white active" aria-current="page">Tambah Data</li>
                </ol>
                <h6 class="font-weight-bolder text-white mb-0">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Rekap PRK
                </h6>
            </nav>
        </div>
    </nav>

    <!-- Content -->
    <div class="container-fluid py-4">

        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-gradient-primary text-white rounded-top-4">
                <h6 class="mb-0">Form Tambah Rekap PRK</h6>
            </div>

            <div class="card-body">
                <form action="<?= base_url('rekap_prk/tambah'); ?>" method="POST">
                    <div class="row g-4">

                        <!-- Jenis Anggaran -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jenis Anggaran</label>
                            <select name="JENIS_ANGGARAN" id="jenisAnggaran" class="form-select modern-select" required onchange="loadNomorPRK()">
                                <option value="">-- Pilih Jenis Anggaran --</option>
                                <option value="INVESTASI">INVESTASI</option>
                                <option value="OPERASI">OPERASI</option>
                            </select>
                        </div>

                        <!-- Nomor PRK -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nomor PRK</label>
                            <select name="NOMOR_PRK" id="nomorPRK" class="form-select modern-select" required>
                                <option value="">-- Pilih Jenis Anggaran Dulu --</option>
                            </select>
                        </div>

                        <!-- Uraian PRK -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Uraian PRK</label>
                            <textarea name="URAIAN_PRK" class="form-control modern-input" rows="2" required></textarea>
                        </div>

                        <!-- Pagu SKK-IO -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pagu SKK-IO</label>
                            <input type="text" name="PAGU_SKK_IO" class="form-control modern-input money-input" required>
                        </div>

                        <!-- Rencana Kontrak -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Rencana Kontrak</label>
                            <input type="text" name="RENC_KONTRAK" class="form-control modern-input money-input" required>
                        </div>

                        <!-- NODIN / Surat -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NODIN / Surat</label>
                            <input type="text" name="NODIN_SRT" class="form-control modern-input">
                        </div>

                        <!-- Kontrak -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kontrak</label>
                            <input type="text" name="KONTRAK" class="form-control modern-input money-input">
                        </div>

                        <!-- Sisa -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Sisa</label>
                            <input type="text" name="SISA" class="form-control modern-input money-input">
                        </div>

                        <!-- Rencana Bayar -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Rencana Bayar</label>
                            <input type="text" name="RENCANA_BAYAR" class="form-control modern-input money-input">
                        </div>

                        <!-- Terbayar -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Terbayar</label>
                            <input type="text" name="TERBAYAR" class="form-control modern-input money-input">
                        </div>

                        <!-- Ke Tahun 2026 -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ke Tahun 2026</label>
                            <input type="text" name="KE_TAHUN_2026" class="form-control modern-input money-input">
                        </div>

                        <!-- Buttons -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Data
                            </button>
                            <a href="<?= base_url('rekap_prk'); ?>" class="btn btn-secondary ms-2">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
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

        // Saat jenis anggaran berubah
        $("select[name='JENIS_ANGGARAN']").change(function() {
            let jenisDipilih = $(this).val();

            // Reset PRK dropdown
            $("#nomorPRK").html('<option value="">Memuat...</option>');

            // Request ke controller
            $.ajax({
                url: "<?= base_url('rekap_prk/get_prk'); ?>",
                type: "POST",
                data: {
                    jenis: jenisDipilih
                },
                dataType: "json",
                success: function(response) {

                    // Jika tidak ada data
                    if (response.length === 0) {
                        $("#nomorPRK").html('<option value="">Tidak ada PRK</option>');
                        return;
                    }

                    let options = '<option value="">-- Pilih Nomor PRK --</option>';

                    response.forEach(function(row) {
                        options += `<option value="${row.NOMOR_PRK}">${row.NOMOR_PRK}</option>`;
                    });

                    $("#nomorPRK").html(options);
                }
            });
        });

    });

    // Saat nomor PRK dipilih
    $("#nomorPRK").on("change", function() {
        let nomor = $(this).val();

        $("textarea[name='URAIAN_PRK']").val("Memuat...");

        $.ajax({
            url: "<?= base_url('rekap_prk/get_uraian'); ?>",
            type: "POST",
            data: {
                nomor_prk: nomor
            },
            dataType: "json",
            success: function(response) {
                if (response && response.PRK) {
                    $("textarea[name='URAIAN_PRK']").val(response.PRK);
                } else {
                    $("textarea[name='URAIAN_PRK']").val("");
                }
            }
        });
    });
</script>