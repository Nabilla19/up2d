<main class="main-content position-relative border-radius-lg">
    <?php $this->load->view('layout/navbar'); ?>
    <div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Security Check-In (Berangkat)</h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('transport/security_checkin/'.$request['id']) ?>" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    <span class="alert-icon"><i class="fas fa-camera"></i></span>
                                    <span class="alert-text">
                                        <strong>Info:</strong> Untuk mengambil foto langsung dari kamera, buka halaman ini di <strong>perangkat mobile</strong> (HP/Tablet). 
                                        Di desktop, Anda bisa pilih file gambar dari komputer.
                                    </span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">KM Awal</label>
                                    <input class="form-control" type="number" name="km_awal" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Jam Berangkat</label>
                                    <input class="form-control" type="time" name="jam_berangkat" value="<?= date('H:i') ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Foto Driver + Mobil (Berangkat)</label>
                            <input class="form-control" type="file" name="foto_driver_berangkat" 
                                   id="foto_driver_berangkat"
                                   accept="image/*" 
                                   capture="environment" 
                                   required>
                            <small class="form-text text-muted">Tap untuk mengambil foto atau pilih dari galeri</small>
                            <div id="preview_driver_berangkat" class="mt-2" style="display:none;">
                                <img id="img_driver_berangkat" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Foto KM (Berangkat)</label>
                            <input class="form-control" type="file" name="foto_km_berangkat" 
                                   id="foto_km_berangkat"
                                   accept="image/*" 
                                   capture="environment" 
                                   required>
                            <small class="form-text text-muted">Tap untuk mengambil foto atau pilih dari galeri</small>
                            <div id="preview_km_berangkat" class="mt-2" style="display:none;">
                                <img id="img_km_berangkat" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-danger btn-sm">Simpan & Berangkatkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</main>

<script>
// Image preview functionality for Security check-in form
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
setupImagePreview('foto_driver_berangkat', 'preview_driver_berangkat', 'img_driver_berangkat');
setupImagePreview('foto_km_berangkat', 'preview_km_berangkat', 'img_km_berangkat');
</script>
