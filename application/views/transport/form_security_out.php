<main class="main-content position-relative border-radius-lg">
    <?php $this->load->view('layout/navbar'); ?>
    <div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Security Check-Out (Kembali)</h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('transport/security_checkout/'.$request['id']) ?>" method="post" enctype="multipart/form-data">
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <span class="alert-icon"><i class="fas fa-camera"></i></span>
                            <span class="alert-text">
                                <strong>Info:</strong> Untuk mengambil foto langsung dari kamera, buka halaman ini di <strong>perangkat mobile</strong> (HP/Tablet). 
                                Di desktop, Anda bisa pilih file gambar dari komputer.
                            </span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">KM Akhir</label>
                                    <input class="form-control" type="number" name="km_akhir" id="km_akhir" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Jam Kembali</label>
                                    <input class="form-control" type="time" name="jam_kembali" id="jam_kembali" value="<?= date('H:i') ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Jarak Tempuh (KM)</label>
                                    <input class="form-control" type="text" name="jarak_tempuh" id="jarak_tempuh" placeholder="Otomatis terisi">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Lama Waktu</label>
                                    <input class="form-control" type="text" name="lama_waktu" id="lama_waktu" placeholder="Otomatis terisi">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Foto Driver + Mobil (Kembali)</label>
                            <input class="form-control" type="file" name="foto_driver_kembali" 
                                   id="foto_driver_kembali"
                                   accept="image/*" 
                                   capture="environment" 
                                   required>
                            <small class="form-text text-muted">Tap untuk mengambil foto atau pilih dari galeri</small>
                            <div id="preview_driver_kembali" class="mt-2" style="display:none;">
                                <img id="img_driver_kembali" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Foto KM (Kembali)</label>
                            <input class="form-control" type="file" name="foto_km_kembali" 
                                   id="foto_km_kembali"
                                   accept="image/*" 
                                   capture="environment" 
                                   required>
                            <small class="form-text text-muted">Tap untuk mengambil foto atau pilih dari galeri</small>
                            <div id="preview_km_kembali" class="mt-2" style="display:none;">
                                <img id="img_km_kembali" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan & Selesaikan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</main>

<script>
function calculateValues() {
    const kmAwal = parseInt("<?= $log['km_awal'] ?>") || 0;
    const kmAkhir = parseInt(document.getElementById('km_akhir').value) || 0;
    
    // Calculate Distance
    if (kmAkhir > 0) {
        document.getElementById('jarak_tempuh').value = (kmAkhir - kmAwal) + ' KM';
    }

    // Calculate Duration
    const jamBerangkatStr = "<?= $log['jam_berangkat'] ?>";
    const jamKembaliStr = document.getElementById('jam_kembali').value;

    if (jamBerangkatStr && jamKembaliStr) {
        const [h1, m1] = jamBerangkatStr.split(':').map(Number);
        const [h2, m2] = jamKembaliStr.split(':').map(Number);
        
        let diffMinutes = (h2 * 60 + m2) - (h1 * 60 + m1);
        if (diffMinutes < 0) diffMinutes += 1440; // Over Midnight

        const hours = Math.floor(diffMinutes / 60);
        const minutes = diffMinutes % 60;
        
        let durationText = '';
        if (hours > 0) durationText += hours + ' Jam ';
        durationText += minutes + ' Menit';
        
        document.getElementById('lama_waktu').value = durationText;
    }
}

document.getElementById('km_akhir').addEventListener('input', calculateValues);
document.getElementById('jam_kembali').addEventListener('input', calculateValues);

// Initial call
window.onload = calculateValues;

// Image preview functionality for Security check-out form
function setupImagePreview(inputId, previewId, imgId) {
    const input = document.getElementById(inputId);
    if (!input) return;
    
    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            if (!file.type.match('image.*')) {
                alert('File harus berupa gambar!');
                this.value = '';
                return;
            }
            
            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB!');
                this.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById(imgId).src = event.target.result;
                document.getElementById(previewId).style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
}

// Initialize preview for both photo inputs
setupImagePreview('foto_driver_kembali', 'preview_driver_kembali', 'img_driver_kembali');
setupImagePreview('foto_km_kembali', 'preview_km_kembali', 'img_km_kembali');
</script>
