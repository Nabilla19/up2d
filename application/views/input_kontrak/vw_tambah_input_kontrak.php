<?php // Form Add Progress Kontrak Operasi 
?>
<main class="main-content position-relative border-radius-lg">
    <?php $this->load->view('layout/navbar'); ?>
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm">
                        <a class="opacity-5 text-white" href="<?= base_url('dashboard'); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item text-sm">
                        <a class="opacity-5 text-white" href="<?= base_url('anggaran/operasi/progress_kontrak'); ?>">Progress Kontrak Operasi</a>
                    </li>
                    <li class="breadcrumb-item text-sm text-white active" aria-current="page">Tambah Data</li>
                </ol>
                <h6 class="font-weight-bolder text-white mb-0">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Progress Kontrak Operasi
                </h6>
            </nav>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> <?= $this->session->flashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (validation_errors()): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Perhatian!</strong>
                <?= validation_errors(); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-gradient-primary text-white">
                <h6 class="mb-0">Form Tambah Data Input Kontrak</h6>
            </div>

            <div class="card-body">
                <form id="addProgressKontrakOpForm" action="<?= base_url('Input_kontrak/store'); ?>" method="POST">
                    <div class="row g-3">

                        <!-- Jenis Anggaran -->
                        <div class="col-md-6">
                            <label class="form-label">Jenis Anggaran <span class="text-danger">*</span></label>
                            <select name="JENIS_ANGGARAN" id="jenisAnggaran" class="form-control" required>
                                <option value="">-- Pilih Jenis Anggaran --</option>
                                <option value="Operasi">Operasi</option>
                                <option value="Investasi">Investasi</option>
                            </select>
                        </div>

                        <!-- Nomor PRK -->
                        <div class="col-md-6">
                            <label class="form-label">Nomor PRK <span class="text-danger">*</span></label>
                            <select name="NOMOR_PRK" id="nomorPRK" class="form-control" required disabled>
                                <option value="">-- Pilih Jenis Anggaran Terlebih Dahulu --</option>
                            </select>
                            <small class="text-muted">Atau <a href="#" id="inputManualPRK">input manual</a></small>
                            <input type="text" id="nomorPRKManual" class="form-control mt-2" placeholder="Input Nomor PRK Manual" style="display:none;">
                        </div>

                        <!-- PRK Description (Auto-fill) -->
                        <div class="col-md-6">
                            <label class="form-label">Deskripsi PRK</label>
                            <input type="text" name="PRK_DESCRIPTION" id="prkDescription" class="form-control" readonly>
                        </div>

                        <!-- Nomor SKK -->
                        <div class="col-md-6">
                            <label class="form-label">Nomor SKK/IO <span class="text-danger">*</span></label>
                            <select name="SKKO" id="nomorSKK" class="form-control" required disabled>
                                <option value="">-- Pilih PRK Terlebih Dahulu --</option>
                            </select>
                            <small class="text-muted">Atau <a href="#" id="inputManualSKK">input manual</a></small>
                            <input type="text" id="nomorSKKManual" class="form-control mt-2" placeholder="Input Nomor SKK Manual" style="display:none;">
                        </div>

                        <!-- SKK Value (Auto-fill) -->
                        <div class="col-md-6">
                            <label class="form-label">Nilai SKK</label>
                            <input type="text" name="SKK_VALUE" id="skkValue" class="form-control" readonly>
                        </div>

                        <!-- Judul DRP -->
                        <div class="col-md-6">
                            <label class="form-label">Judul DRP</label>
                            <select name="DRP" id="judulDRP" class="form-control" disabled>
                                <option value="">-- Pilih PRK Terlebih Dahulu --</option>
                            </select>
                            <small class="text-muted">Atau <a href="#" id="inputManualDRP">input manual</a></small>
                            <input type="text" id="judulDRPManual" class="form-control mt-2" placeholder="Input Judul DRP Manual" style="display:none;">
                        </div>

                        <!-- Sumber Dana -->
                        <div class="col-md-6">
                            <label class="form-label">Sumber Dana</label>
                            <textarea name="SUMBER_DANA" class="form-control" rows="2"></textarea>
                        </div>

                        <!-- Sub Pos -->
                        <div class="col-md-6">
                            <label class="form-label">Sub Pos</label>
                            <input type="text" name="SUB_POS" class="form-control">
                        </div>

                        <!-- Uraian Kontrak/Pekerjaan -->
                        <div class="col-md-12">
                            <label class="form-label">Uraian Kontrak / Pekerjaan</label>
                            <textarea name="URAIAN_KONTRAK_PEKERJAAN" class="form-control" rows="3" required></textarea>
                        </div>

                        <!-- User -->
                        <div class="col-md-4">
                            <label class="form-label">User</label>
                            <select name="USER" class="form-control" required>
                                <option value="">-- Pilih User --</option>
                                <option value="FASOP">FASOP</option>
                                <option value="HAR">HAR</option>
                                <option value="OPDIST">OPDIST</option>
                                <option value="K3">K3</option>
                            </select>
                        </div>

                        <!-- Pagu Ang/RAB User -->
                        <div class="col-md-4">
                            <label class="form-label">Pagu Ang/RAB User</label>
                            <input type="text" name="PAGU_ANG_RAB_USER" class="form-control">
                        </div>

                        <!-- Komitmen ND -->
                        <div class="col-md-4">
                            <label class="form-label">Komitmen ND</label>
                            <input type="text" name="KOMITMENT_ND" class="form-control">
                        </div>

                        <!-- Renc Akhir Kontrak -->
                        <div class="col-md-4">
                            <label class="form-label">Renc Akhir Kontrak</label>
                            <input type="date" name="RENC_AKHIR_KONTRAK" class="form-control">
                        </div>

                        <!-- Tgl ND/AMS -->
                        <div class="col-md-4">
                            <label class="form-label">Tgl ND/AMS</label>
                            <input type="date" name="TGL_ND_AMS" class="form-control">
                        </div>

                        <!-- Nomor ND/AMS -->
                        <div class="col-md-4">
                            <label class="form-label">Nomor ND / AMS</label>
                            <input type="text" name="NOMOR_ND_AMS" class="form-control">
                        </div>

                        <!-- Keterangan -->
                        <div class="col-md-6">
                            <label class="form-label">Keterangan</label>
                            <input type="text" name="KETERANGAN" class="form-control">
                        </div>

                        <!-- Tahap Kontrak -->
                        <div class="col-md-6">
                            <label class="form-label">Tahap Kontrak</label>
                            <input type="text" name="TAHAP_KONTRAK" class="form-control">
                        </div>

                        <!-- Prognosa -->
                        <div class="col-md-4">
                            <label class="form-label">Prognosa</label>
                            <input type="date" name="PROGNOSA" class="form-control">
                        </div>

                        <!-- No SPK/SPB/Kontrak -->
                        <div class="col-md-4">
                            <label class="form-label">No SPK / SPB / Kontrak</label>
                            <input type="text" name="NO_SPK_SPB_KONTRAK" class="form-control">
                        </div>

                        <!-- Rekanan -->
                        <div class="col-md-4">
                            <label class="form-label">Rekanan</label>
                            <input type="text" name="REKANAN" class="form-control">
                        </div>

                        <!-- Tgl Kontrak -->
                        <div class="col-md-4">
                            <label class="form-label">Tgl Kontrak</label>
                            <input type="date" name="TGL_KONTRAK" class="form-control">
                        </div>

                        <!-- Tgl Akhir Kontrak -->
                        <div class="col-md-4">
                            <label class="form-label">Tgl Akhir Kontrak</label>
                            <input type="date" name="TGL_AKHIR_KONTRAK" class="form-control">
                        </div>

                        <!-- Nilai Kontrak Total -->
                        <div class="col-md-4">
                            <label class="form-label">Nilai Kontrak Total</label>
                            <input type="text" name="NILAI_KONTRAK_TOTAL" class="form-control">
                        </div>

                        <!-- Nilai Kontrak Tahun Berjalan -->
                        <div class="col-md-4">
                            <label class="form-label">Nilai Kontrak Tahun Berjalan</label>
                            <input type="text" name="NILAI_KONTRAK_TAHUN_BERJALAN" class="form-control">
                        </div>

                        <!-- Tgl Bayar -->
                        <div class="col-md-4">
                            <label class="form-label">Tgl Bayar</label>
                            <input type="date" name="TGL_BAYAR" class="form-control">
                        </div>

                        <!-- Anggaran Terpakai -->
                        <div class="col-md-4">
                            <label class="form-label">Anggaran Terpakai</label>
                            <input type="text" name="ANGGARAN_TERPAKAI" class="form-control">
                        </div>

                        <!-- Sisa Anggaran -->
                        <div class="col-md-4">
                            <label class="form-label">Sisa Anggaran</label>
                            <input type="text" name="SISA_ANGGARAN" class="form-control">
                        </div>

                        <!-- Status Kontrak -->
                        <div class="col-md-4">
                            <label class="form-label">Status Kontrak</label>
                            <select name="STATUS_KONTRAK" class="form-control">
                                <option value="">-- Pilih Status --</option>
                                <option value="NODIN/SRT">NODIN/SRT</option>
                                <option value="TERKONTRAK">TERKONTRAK</option>
                                <option value="TERBAYAR">TERBAYAR</option>
                                <option value="SELESAI">SELESAI</option>
                                <option value="HUTANG USAHA 2024">HUTANG USAHA 2024</option>
                                <option value="MULTIYEARS">MULTIYEARS</option>
                                <option value="KONTRAK 2025">KONTRAK 2025</option>
                            </select>
                        </div>

                        <!-- Bulan Kontrak 1-12 -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary">Data Bulan Kontrak</h6>
                            <hr>
                        </div>

                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <div class="col-md-3">
                                <label class="form-label">Bulan Kontrak <?= $i; ?></label>
                                <input type="text" name="BLN_KTRK<?= $i; ?>" class="form-control" placeholder="Nilai bulan <?= $i; ?>">
                            </div>
                        <?php endfor; ?>

                        <!-- Bulan Renc Bayar -->
                        <div class="col-md-6">
                            <label class="form-label">Bulan Renc Bayar</label>
                            <input type="month" name="BULAN_RENC_BAYAR" class="form-control">
                        </div>

                        <!-- Bulan Bayar -->
                        <div class="col-md-6">
                            <label class="form-label">Bulan Bayar</label>
                            <input type="month" name="BULAN_BAYAR" class="form-control">
                        </div>

                        <!-- Tombol Submit -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Data
                            </button>
                            <a href="<?= base_url('Input_kontrak'); ?>" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
// Data PRK dari PHP
const listPRKOperasi = <?= json_encode($list_prk_operasi ?? []); ?>;
const listPRKInvestasi = <?= json_encode($list_prk_investasi ?? []); ?>;

console.log('PRK Operasi:', listPRKOperasi);
console.log('PRK Investasi:', listPRKInvestasi);

// Jenis Anggaran Change
document.getElementById('jenisAnggaran').addEventListener('change', function() {
    const jenis = this.value;
    const prkSelect = document.getElementById('nomorPRK');
    
    console.log('Jenis Anggaran dipilih:', jenis);
    
    prkSelect.innerHTML = '<option value="">-- Pilih Nomor PRK --</option>';
    prkSelect.disabled = !jenis;
    
    // Reset dependent fields
    document.getElementById('nomorSKK').innerHTML = '<option value="">-- Pilih PRK Terlebih Dahulu --</option>';
    document.getElementById('nomorSKK').disabled = true;
    document.getElementById('judulDRP').innerHTML = '<option value="">-- Pilih PRK Terlebih Dahulu --</option>';
    document.getElementById('judulDRP').disabled = true;
    document.getElementById('prkDescription').value = '';
    document.getElementById('skkValue').value = '';
    
    if (jenis) {
        const listPRK = (jenis === 'Operasi') ? listPRKOperasi : listPRKInvestasi;
        console.log('List PRK yang akan ditampilkan:', listPRK);
        
        if (listPRK && listPRK.length > 0) {
            listPRK.forEach(prk => {
                const option = new Option(prk.NOMOR_PRK + ' - ' + prk.PRK, prk.NOMOR_PRK);
                prkSelect.add(option);
            });
        } else {
            const option = new Option('-- Belum ada data PRK ' + jenis + ' --', '');
            prkSelect.add(option);
        }
    }
});

// PRK Change - Load SKK & DRP
document.getElementById('nomorPRK').addEventListener('change', function() {
    const nomorPRK = this.value;
    const selectedOption = this.options[this.selectedIndex];
    const prkDesc = selectedOption.text.split(' - ')[1] || '';
    
    document.getElementById('prkDescription').value = prkDesc;
    
    if (nomorPRK) {
        // Load SKK
        fetch('<?= base_url("Input_kontrak/ajax_get_skk_by_prk"); ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'nomor_prk=' + encodeURIComponent(nomorPRK)
        })
        .then(res => res.json())
        .then(data => {
            const skkSelect = document.getElementById('nomorSKK');
            skkSelect.innerHTML = '<option value="">-- Pilih Nomor SKK --</option>';
            skkSelect.disabled = false;
            
            data.forEach(skk => {
                const option = new Option(
                    skk.NOMOR_SKK_IO + ' - Rp ' + (skk.SKKI_O || '0'),
                    skk.NOMOR_SKK_IO
                );
                skkSelect.add(option);
            });
        });
        
        // Load DRP
        fetch('<?= base_url("Input_kontrak/ajax_get_drp_by_prk"); ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'nomor_prk=' + encodeURIComponent(nomorPRK)
        })
        .then(res => res.json())
        .then(data => {
            const drpSelect = document.getElementById('judulDRP');
            drpSelect.innerHTML = '<option value="">-- Pilih Judul DRP --</option>';
            drpSelect.disabled = false;
            
            data.forEach(drp => {
                const option = new Option(drp.JUDUL_DRP, drp.JUDUL_DRP);
                drpSelect.add(option);
            });
        });
    }
});

// SKK Change - Load SKK Value
document.getElementById('nomorSKK').addEventListener('change', function() {
    const nomorSKK = this.value;
    
    if (nomorSKK) {
        fetch('<?= base_url("Input_kontrak/ajax_get_skk_value"); ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'nomor_skk=' + encodeURIComponent(nomorSKK)
        })
        .then(res => res.json())
        .then(data => {
            if (data) {
                document.getElementById('skkValue').value = data.SKKI_O || '';
            }
        });
    }
});

// Toggle Manual Input PRK
document.getElementById('inputManualPRK').addEventListener('click', function(e) {
    e.preventDefault();
    const manualInput = document.getElementById('nomorPRKManual');
    const dropdown = document.getElementById('nomorPRK');
    const jenisAnggaran = document.getElementById('jenisAnggaran');
    
    if (manualInput.style.display === 'none' || manualInput.style.display === '') {
        // Aktifkan manual input
        manualInput.style.display = 'block';
        manualInput.value = '';
        manualInput.setAttribute('name', 'NOMOR_PRK');
        manualInput.setAttribute('required', 'required');
        
        // Nonaktifkan dropdown
        dropdown.style.display = 'none';
        dropdown.removeAttribute('name');
        dropdown.removeAttribute('required');
        dropdown.disabled = true;
        
        this.textContent = 'gunakan dropdown';
    } else {
        // Aktifkan dropdown
        dropdown.style.display = 'block';
        dropdown.setAttribute('name', 'NOMOR_PRK');
        dropdown.setAttribute('required', 'required');
        dropdown.disabled = !jenisAnggaran.value;
        
        // Nonaktifkan manual input
        manualInput.style.display = 'none';
        manualInput.removeAttribute('name');
        manualInput.removeAttribute('required');
        manualInput.value = '';
        
        this.textContent = 'input manual';
    }
});

// Toggle Manual Input SKK
document.getElementById('inputManualSKK').addEventListener('click', function(e) {
    e.preventDefault();
    const manualInput = document.getElementById('nomorSKKManual');
    const dropdown = document.getElementById('nomorSKK');
    const nomorPRK = document.getElementById('nomorPRK').value;
    
    if (manualInput.style.display === 'none' || manualInput.style.display === '') {
        // Aktifkan manual input
        manualInput.style.display = 'block';
        manualInput.value = '';
        manualInput.setAttribute('name', 'SKKO');
        manualInput.setAttribute('required', 'required');
        
        // Nonaktifkan dropdown
        dropdown.style.display = 'none';
        dropdown.removeAttribute('name');
        dropdown.removeAttribute('required');
        dropdown.disabled = true;
        
        this.textContent = 'gunakan dropdown';
    } else {
        // Aktifkan dropdown
        dropdown.style.display = 'block';
        dropdown.setAttribute('name', 'SKKO');
        dropdown.setAttribute('required', 'required');
        dropdown.disabled = !nomorPRK;
        
        // Nonaktifkan manual input
        manualInput.style.display = 'none';
        manualInput.removeAttribute('name');
        manualInput.removeAttribute('required');
        manualInput.value = '';
        
        this.textContent = 'input manual';
    }
});

// Toggle Manual Input DRP
document.getElementById('inputManualDRP').addEventListener('click', function(e) {
    e.preventDefault();
    const manualInput = document.getElementById('judulDRPManual');
    const dropdown = document.getElementById('judulDRP');
    const nomorPRK = document.getElementById('nomorPRK').value;
    
    if (manualInput.style.display === 'none' || manualInput.style.display === '') {
        // Aktifkan manual input
        manualInput.style.display = 'block';
        manualInput.value = '';
        manualInput.setAttribute('name', 'DRP');
        
        // Nonaktifkan dropdown
        dropdown.style.display = 'none';
        dropdown.removeAttribute('name');
        dropdown.disabled = true;
        
        this.textContent = 'gunakan dropdown';
    } else {
        // Aktifkan dropdown
        dropdown.style.display = 'block';
        dropdown.setAttribute('name', 'DRP');
        dropdown.disabled = !nomorPRK;
        
        // Nonaktifkan manual input
        manualInput.style.display = 'none';
        manualInput.removeAttribute('name');
        manualInput.value = '';
        
        this.textContent = 'input manual';
    }
});
</script>