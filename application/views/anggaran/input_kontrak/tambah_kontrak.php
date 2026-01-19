<?php
// view: Anggaran/input_kontrak/tambah_kontrak.php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<main class="main-content position-relative border-radius-lg">
    <?php $this->load->view('layout/navbar'); ?>

    <!-- Content -->
    <div class="container-fluid py-4">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header py-3 bg-gradient-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-file-signature me-2"></i>Tambah Data Kontrak</h6>
                <a href="<?= base_url('data_kontrak'); ?>" class="btn btn-light text-primary btn-sm no-anim">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <div class="card-body bg-white p-4">
                <form action="<?= base_url('data_kontrak/tambah_aksi'); ?>" method="post">

                    <h6 class="mb-2">Master (Dropdown)</h6>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Jenis Anggaran</label>
                            <select name="jenis_anggaran_id" id="jenis_anggaran_id" class="form-select rounded-3">
                                <option value="">- Pilih -</option>
                                <?php foreach ($jenis_anggaran as $j): ?>
                                    <option value="<?= $j->id; ?>"><?= html_escape($j->nama); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-5 mb-3">
                            <label class="form-label">PRK</label>
                            <select name="prk_id" id="prk_id" class="form-select rounded-3" disabled>
                                <option value="">- Pilih jenis dulu -</option>
                            </select>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">SKK-IO</label>
                            <select name="skk_id" id="skk_id" class="form-select rounded-3" disabled>
                                <option value="">- Pilih PRK dulu -</option>
                            </select>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">DRP</label>
                            <select name="drp_id" id="drp_id" class="form-select rounded-3" disabled>
                                <option value="">- Pilih PRK dulu -</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Sisa Anggaran</label>
                            <input type="number" name="sisa_anggaran" class="form-control rounded-3" value="0">
                        </div>

                        <div class="col-md-8 mb-2">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" value="1" id="manual_master" name="manual_master">
                                <label class="form-check-label" for="manual_master">
                                    Input manual (jika master berubah / belum ada)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="manualBox" class="border rounded-3 p-3 mb-3" style="display:none;">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Jenis Anggaran (Text)</label>
                                <input type="text" name="jenis_anggaran_text" class="form-control rounded-3">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Nomor PRK (Text)</label>
                                <input type="text" name="nomor_prk_text" class="form-control rounded-3">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Uraian PRK (Text)</label>
                                <input type="text" name="uraian_prk_text" class="form-control rounded-3">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Nomor SKK-IO (Text)</label>
                                <input type="text" name="nomor_skk_io_text" class="form-control rounded-3">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">DRP (Text)</label>
                                <input type="text" name="drp_text" class="form-control rounded-3">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Pagu SKK-I/O</label>
                                <input type="number" name="pagu_skk_io" class="form-control rounded-3" value="0">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-2">User</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Uraian Pekerjaan</label>
                            <input type="text" name="uraian_pekerjaan" class="form-control rounded-3" required>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">User</label>
                            <?php
                            $user_options = ['Fasop', 'KKU', 'k3l & kam', 'Perencanaan', 'HAR'];
                            $selected_role = isset($role_name) ? strtolower(trim($role_name)) : '';
                            ?>
                            <select name="user_pengusul" id="user_pengusul" class="form-select rounded-3">
                                <option value="">- Pilih -</option>
                                <?php foreach ($user_options as $opt):
                                    $val = $opt;
                                    $sel = ($selected_role !== '' && strtolower(trim($val)) === $selected_role) ? 'selected' : '';
                                ?>
                                    <option value="<?= html_escape($val) ?>" <?= $sel; ?>><?= html_escape($opt) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">RAB User</label>
                            <input type="number" name="rab_user" class="form-control rounded-3" value="0">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Rencana HK</label>
                            <input type="number" name="rencana_hari_kerja" class="form-control rounded-3" value="0">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Jenis Penagihan</label>
                            <select name="jenis_penagihan" class="form-select rounded-3">
                                <option value="">- Pilih -</option>
                                <option value="kontrak berjalan">Kontrak Berjalan</option>
                                <option value="luncuran">Luncuran</option>
                                <option value="hutang usaha">Hutang Usaha</option>
                                <option value="termin">Termin</option>
                                <option value="multi years">Multi Years</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tanggal BASTP</label>
                            <input type="date" name="tanggal_bastp" class="form-control rounded-3">
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-2">Perencanaan</h6>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tgl ND/AMS</label>
                            <input type="date" name="tgl_nd_ams" class="form-control rounded-3">
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label">Nomor ND/AMS</label>
                            <input type="text" name="nomor_nd_ams" class="form-control rounded-3">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status Kontrak</label>
                            <select name="status_kontrak" class="form-select rounded-3">
                                <option value="">- Pilih -</option>
                                <option value="PROSES">PROSES</option>
                                <option value="RENCANA">RENCANA</option>
                                <option value="TERKONTRAK">TERKONTRAK</option>
                                <option value="SELESAI">SELESAI</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control rounded-3" rows="2"></textarea>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-2">Pengadaan</h6>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">No RKS</label>
                            <input type="text" name="no_rks" class="form-control rounded-3">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Metode</label>
                            <select name="metode_pengadaan" class="form-select rounded-3">
                                <option value="">- Pilih -</option>
                                <option value="SPBJ UPL">SPBJ UPL</option>
                                <option value="Kontrak Rinci">Kontrak Rinci</option>
                                <option value="SPK">SPK</option>
                                <option value="Tender">Tender</option>
                                <option value="Penunjukan langsung">Penunjukan langsung</option>
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tahapan Pengadaan</label>
                            <select name="tahapan_pengadaan" class="form-select rounded-3">
                                <option value="">- Pilih -</option>
                                <option value="Proses CDA">Proses CDA</option>
                                <option value="Proses TTD Vendor">Proses TTD Vendor</option>
                                <option value="Proses TTD Pengguna">Proses TTD Pengguna</option>
                                <option value="Pengadaan Selesai">Pengadaan Selesai</option>
                                <option value="Pending & Menunggu Konfirmasi Anggaran">Pending & Menunggu Konfirmasi Anggaran</option>
                                <option value="Menunggu Nota Dinas">Menunggu Nota Dinas</option>
                                <option value="Proses Kelengkapan Administrasi Calon Penyedia / Pendaftaran">Proses Kelengkapan Administrasi Calon Penyedia / Pendaftaran</option>
                                <option value="Proses Inisiasi Efroc">Proses Inisiasi Efroc</option>
                                <option value="Proses Penjilidan">Proses Penjilidan</option>
                                <option value="Pemberian Penjelasan">Pemberian Penjelasan</option>
                                <option value="Upload Dokumen">Upload Dokumen</option>
                                <option value="Konfirmasi User">Konfirmasi User</option>
                                <option value="Evaluasi Dokumen Penawaran">Evaluasi Dokumen Penawaran</option>
                                <option value="Usulan Calon Pemenang">Usulan Calon Pemenang</option>
                                <option value="Penetapan Calon Pemenang">Penetapan Calon Pemenang</option>
                                <option value="Masa Sanggah">Masa Sanggah</option>
                                <option value="Gagal Lelang">Gagal Lelang</option>
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Prognosa Kontrak</label>
                            <input type="date" name="prognosa_kontrak_tgl" class="form-control rounded-3">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">HPE</label>
                            <input type="number" name="harga_hpe" class="form-control rounded-3" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">HPS</label>
                            <input type="number" name="harga_hps" class="form-control rounded-3" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nego</label>
                            <input type="number" name="harga_nego" class="form-control rounded-3" value="0">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">KAK</label>
                            <textarea name="kak" class="form-control rounded-3" rows="2"></textarea>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">No Kontrak</label>
                            <input type="text" name="no_kontrak" class="form-control rounded-3">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Vendor</label>
                            <input type="text" name="pelaksana_vendor" class="form-control rounded-3">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Tgl Kontrak</label>
                            <input type="date" name="tgl_kontrak" class="form-control rounded-3">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">End Kontrak</label>
                            <input type="date" name="end_kontrak" class="form-control rounded-3">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Nilai Kontrak</label>
                            <input type="number" name="nilai_kontrak" class="form-control rounded-3" value="0">
                        </div>
                        <div class="col-md-9 mb-3">
                            <label class="form-label">Kendala Kontrak</label>
                            <textarea name="kendala_kontrak" class="form-control rounded-3" rows="2"></textarea>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-2">KKU / Pembayaran</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tahapan Pembayaran</label>
                            <select name="tahapan_pembayaran" class="form-select rounded-3">
                                <option value="">- Pilih -</option>
                                <option value="adm tagih & vendor">Adm Tagih & Vendor</option>
                                <option value="Submit VIP">Submit VIP</option>
                                <option value="Approval 1">Approval 1</option>
                                <option value="Approval 2">Approval 2</option>
                                <option value="Approval Pusat">Approval Pusat</option>
                                <option value="Pembayaran">Pembayaran</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nilai Bayar</label>
                            <input type="number" name="nilai_bayar" class="form-control rounded-3" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tgl Tahapan</label>
                            <input type="date" name="tgl_tahapan" class="form-control rounded-3">
                        </div>

                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">BLN<?= $m; ?></label>
                                <input type="number" name="real_byr_bln<?= $m; ?>" class="form-control rounded-3" value="0" readonly>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button class="btn btn-primary px-4 me-2" type="submit"><i class="fas fa-save me-1"></i>Simpan</button>
                        <a href="<?= base_url('data_kontrak'); ?>" class="btn btn-secondary px-4">Batal</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</main>

<script>
    const baseUrl = "<?= site_url('data_kontrak'); ?>";

    async function fetchJson(url) {
        const res = await fetch(url, {
            headers: {
                'Accept': 'application/json'
            }
        });
        return await res.json();
    }

    function setOptions(selectEl, items, placeholder) {
        selectEl.innerHTML = '';
        const opt0 = document.createElement('option');
        opt0.value = '';
        opt0.textContent = placeholder;
        selectEl.appendChild(opt0);
        items.forEach(it => {
            const opt = document.createElement('option');
            opt.value = it.id;
            opt.textContent = it.label;
            selectEl.appendChild(opt);
        });
    }

    const jenis = document.getElementById('jenis_anggaran_id');
    const prk = document.getElementById('prk_id');
    const skk = document.getElementById('skk_id');
    const drp = document.getElementById('drp_id');

    jenis.addEventListener('change', async () => {
        const id = jenis.value;
        prk.disabled = true;
        skk.disabled = true;
        drp.disabled = true;

        if (!id) {
            setOptions(prk, [], '- Pilih jenis dulu -');
            setOptions(skk, [], '- Pilih PRK dulu -');
            setOptions(drp, [], '- Pilih PRK dulu -');
            return;
        }

        const prkList = await fetchJson(`${baseUrl}/prk_by_jenis/${id}`);
        setOptions(prk, prkList, '- Pilih PRK -');
        prk.disabled = false;

        if (prkList.length === 1) {
            prk.value = prkList[0].id;
            prk.dispatchEvent(new Event('change'));
        } else {
            setOptions(skk, [], '- Pilih PRK dulu -');
            setOptions(drp, [], '- Pilih PRK dulu -');
        }
    });

    prk.addEventListener('change', async () => {
        const id = prk.value;
        skk.disabled = true;
        drp.disabled = true;

        if (!id) {
            setOptions(skk, [], '- Pilih PRK dulu -');
            setOptions(drp, [], '- Pilih PRK dulu -');
            return;
        }

        const skkList = await fetchJson(`${baseUrl}/skk_by_prk/${id}`);
        const drpList = await fetchJson(`${baseUrl}/drp_by_prk/${id}`);

        setOptions(skk, skkList, '- Pilih SKK -');
        setOptions(drp, drpList, '- Pilih DRP -');
        skk.disabled = false;
        drp.disabled = false;

        if (skkList.length === 1) skk.value = skkList[0].id;
        if (drpList.length === 1) drp.value = drpList[0].id;
    });

    // manual override toggle
    const manual = document.getElementById('manual_master');
    const box = document.getElementById('manualBox');

    manual.addEventListener('change', () => {
        box.style.display = manual.checked ? 'block' : 'none';
        jenis.disabled = manual.checked;
        prk.disabled = manual.checked;
        skk.disabled = manual.checked;
        drp.disabled = manual.checked;
    });

    // PREVIEW RUMUS BLN1..12
    (function() {
        const tahapEl = document.querySelector('select[name="tahapan_pembayaran"]');
        const tglEl = document.querySelector('input[name="tgl_tahapan"]');
        const nilaiEl = document.querySelector('input[name="nilai_bayar"]');

        function recalcBulan() {
            for (let m = 1; m <= 12; m++) {
                const el = document.querySelector(`input[name="real_byr_bln${m}"]`);
                if (el) el.value = 0;
            }

            const tahap = (tahapEl?.value || '').trim().toLowerCase();
            const tgl = (tglEl?.value || '').trim();
            const nilai = (nilaiEl?.value || 0);

            if (tahap !== 'pembayaran') return;
            if (!tgl) return;

            const d = new Date(tgl + 'T00:00:00');
            if (isNaN(d.getTime())) return;

            const bulan = d.getMonth() + 1; // 1..12
            const target = document.querySelector(`input[name="real_byr_bln${bulan}"]`);
            if (target) target.value = nilai;
        }

        ['change', 'input'].forEach(ev => {
            tahapEl && tahapEl.addEventListener(ev, recalcBulan);
            tglEl && tglEl.addEventListener(ev, recalcBulan);
            nilaiEl && nilaiEl.addEventListener(ev, recalcBulan);
        });

        recalcBulan();
    })();
</script>

<style>
    .bg-gradient-primary {
        background: linear-gradient(90deg, #005C99, #0099CC);
    }

    .form-label {
        font-weight: 600;
    }

    .no-anim,
    .no-anim * {
        transition: none !important;
        animation: none !important;
    }
</style>