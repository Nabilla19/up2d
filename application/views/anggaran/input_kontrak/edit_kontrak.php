<?php
// view: Anggaran/input_kontrak/edit_kontrak.php
defined('BASEPATH') or exit('No direct script access allowed');

function v($val, $dash = '')
{
    return ($val === null || $val === '') ? $dash : html_escape($val);
}
$is_manual = empty($kontrak->prk_id) && !empty($kontrak->nomor_prk_text);

// ====== ROLE LOGIC (SAMA DENGAN CONTROLLER) ======
$role = strtolower(trim(
    $this->session->userdata('user_role') ??
        $this->session->userdata('role') ??
        ''
));

$is_admin = in_array($role, ['admin', 'administrator'], true);

$can_master_user = in_array($role, [
    'admin',
    'administrator',
    'pemeliharaan',
    'fasilitas operasi',
    'har',
], true);

$can_perencanaan = in_array($role, [
    'admin',
    'administrator',
    'perencanaan',
], true);

$can_pengadaan = in_array($role, [
    'admin',
    'administrator',
    'pengadaan keuangan',
], true);

$can_kku = in_array($role, [
    'admin',
    'administrator',
    'kku',
], true);

// flag untuk view
$disable_master_user = !($is_admin || $can_master_user);
$disable_perencanaan = !($is_admin || $can_perencanaan);
$disable_pengadaan   = !($is_admin || $can_pengadaan);
$disable_kku         = !($is_admin || $can_kku);
?>

<main class="main-content position-relative border-radius-lg">
    <?php $this->load->view('layout/navbar'); ?>

    <!-- Content -->
    <div class="container-fluid py-4">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header py-3 bg-gradient-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-pen me-2"></i>Edit Data Kontrak</h6>
                <a href="<?= base_url('data_kontrak'); ?>" class="btn btn-light text-primary btn-sm no-anim">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <div class="card-body bg-white p-4">
                <form action="<?= base_url('data_kontrak/update/' . $kontrak->id); ?>" method="post">

                    <!-- MASTER -->
                    <h6 class="mb-2">Master (Dropdown)</h6>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Jenis Anggaran</label>
                            <select name="jenis_anggaran_id" id="jenis_anggaran_id"
                                class="form-select rounded-3"
                                <?= $disable_master_user ? 'disabled' : ''; ?>>
                                <option value="">- Pilih -</option>
                                <?php foreach ($jenis_anggaran as $j): ?>
                                    <option value="<?= $j->id; ?>" <?= ((int)$kontrak->jenis_anggaran_id === (int)$j->id) ? 'selected' : ''; ?>>
                                        <?= html_escape($j->nama); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-5 mb-3">
                            <label class="form-label">PRK</label>
                            <select name="prk_id" id="prk_id"
                                class="form-select rounded-3"
                                <?= $disable_master_user ? 'disabled' : ''; ?>>
                                <option value="">- Loading -</option>
                            </select>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">SKK-IO</label>
                            <select name="skk_id" id="skk_id"
                                class="form-select rounded-3"
                                <?= $disable_master_user ? 'disabled' : ''; ?>>
                                <option value="">- Loading -</option>
                            </select>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">DRP</label>
                            <select name="drp_id" id="drp_id"
                                class="form-select rounded-3"
                                <?= $disable_master_user ? 'disabled' : ''; ?>>
                                <option value="">- Loading -</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Sisa Anggaran</label>
                            <input type="number" name="sisa_anggaran"
                                class="form-control rounded-3"
                                value="<?= v($kontrak->sisa_anggaran, 0); ?>"
                                <?= $disable_master_user ? 'readonly' : ''; ?>>
                        </div>

                        <div class="col-md-8 mb-2">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" value="1"
                                    id="manual_master" name="manual_master"
                                    <?= $is_manual ? 'checked' : ''; ?>
                                    <?= $disable_master_user ? 'disabled' : ''; ?>>
                                <label class="form-check-label" for="manual_master">
                                    Input manual (jika master berubah / belum ada)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="manualBox"
                        class="border rounded-3 p-3 mb-3"
                        style="<?= $is_manual ? 'display:block;' : 'display:none;'; ?>">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Jenis Anggaran (Text)</label>
                                <input type="text" name="jenis_anggaran_text"
                                    class="form-control rounded-3"
                                    value="<?= v($kontrak->jenis_anggaran_text); ?>"
                                    <?= $disable_master_user ? 'readonly' : ''; ?>>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Nomor PRK (Text)</label>
                                <input type="text" name="nomor_prk_text"
                                    class="form-control rounded-3"
                                    value="<?= v($kontrak->nomor_prk_text); ?>"
                                    <?= $disable_master_user ? 'readonly' : ''; ?>>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Uraian PRK (Text)</label>
                                <input type="text" name="uraian_prk_text"
                                    class="form-control rounded-3"
                                    value="<?= v($kontrak->uraian_prk_text); ?>"
                                    <?= $disable_master_user ? 'readonly' : ''; ?>>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Nomor SKK-IO (Text)</label>
                                <input type="text" name="nomor_skk_io_text"
                                    class="form-control rounded-3"
                                    value="<?= v($kontrak->nomor_skk_io_text); ?>"
                                    <?= $disable_master_user ? 'readonly' : ''; ?>>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">DRP (Text)</label>
                                <input type="text" name="drp_text"
                                    class="form-control rounded-3"
                                    value="<?= v($kontrak->drp_text); ?>"
                                    <?= $disable_master_user ? 'readonly' : ''; ?>>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Pagu SKK-I/O</label>
                                <input type="number" name="pagu_skk_io"
                                    class="form-control rounded-3"
                                    value="<?= v($kontrak->pagu_skk_io, 0); ?>"
                                    <?= $disable_master_user ? 'readonly' : ''; ?>>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- USER -->
                    <h6 class="mb-2">User</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Uraian Pekerjaan</label>
                            <input type="text" name="uraian_pekerjaan"
                                class="form-control rounded-3"
                                value="<?= v($kontrak->uraian_pekerjaan); ?>"
                                <?= $disable_master_user ? 'readonly' : ''; ?>
                                required>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">User</label>
                            <?php
                            $user_options = ['Fasop', 'KKU', 'k3l & kam', 'Perencanaan', 'HAR'];
                            $cur = strtolower(trim($kontrak->user_pengusul ?? ''));
                            ?>
                            <select name="user_pengusul" id="user_pengusul" class="form-select rounded-3" <?= $disable_master_user ? 'disabled' : ''; ?>>
                                <option value="">- Pilih -</option>
                                <?php foreach ($user_options as $opt):
                                    $val = $opt;
                                    $sel = ($cur !== '' && strtolower(trim($val)) === $cur) ? 'selected' : '';
                                ?>
                                    <option value="<?= html_escape($val) ?>" <?= $sel; ?>><?= html_escape($opt) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">RAB User</label>
                            <input type="number" name="rab_user"
                                class="form-control rounded-3"
                                value="<?= v($kontrak->rab_user, 0); ?>"
                                <?= $disable_master_user ? 'readonly' : ''; ?>>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Rencana HK</label>
                            <input type="number" name="rencana_hari_kerja"
                                class="form-control rounded-3"
                                value="<?= v($kontrak->rencana_hari_kerja, 0); ?>"
                                <?= $disable_master_user ? 'readonly' : ''; ?>>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Jenis Penagihan</label>
                            <?php $jp = strtolower(trim($kontrak->jenis_penagihan ?? '')); ?>
                            <select name="jenis_penagihan" class="form-select rounded-3"
                                <?= $disable_master_user ? 'disabled' : ''; ?>>
                                <option value="">- Pilih -</option>
                                <option value="kontrak berjalan" <?= $jp == 'kontrak berjalan' ? 'selected' : ''; ?>>Kontrak Berjalan</option>
                                <option value="luncuran" <?= $jp == 'luncuran' ? 'selected' : ''; ?>>Luncuran</option>
                                <option value="hutang usaha" <?= $jp == 'hutang usaha' ? 'selected' : ''; ?>>Hutang Usaha</option>
                                <option value="termin" <?= $jp == 'termin' ? 'selected' : ''; ?>>Termin</option>
                                <option value="multi years" <?= $jp == 'multi years' ? 'selected' : ''; ?>>Multi Years</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tanggal BASTP</label>
                            <input type="date" name="tanggal_bastp"
                                class="form-control rounded-3"
                                value="<?= v($kontrak->tanggal_bastp); ?>"
                                <?= $disable_master_user ? 'readonly' : ''; ?>>
                        </div>
                    </div>

                    <hr>

                    <!-- Perencanaan -->
                    <h6 class="mb-2">Perencanaan</h6>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tgl ND/AMS</label>
                            <input type="date" name="tgl_nd_ams"
                                class="form-control rounded-3"
                                value="<?= v($kontrak->tgl_nd_ams); ?>"
                                <?= $disable_perencanaan ? 'readonly' : ''; ?>>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label">Nomor ND/AMS</label>
                            <input type="text" name="nomor_nd_ams"
                                class="form-control rounded-3"
                                value="<?= v($kontrak->nomor_nd_ams); ?>"
                                <?= $disable_perencanaan ? 'readonly' : ''; ?>>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status Kontrak</label>
                            <?php $st = strtoupper(trim($kontrak->status_kontrak ?? '')); ?>
                            <select name="status_kontrak" class="form-select rounded-3"
                                <?= $disable_perencanaan ? 'disabled' : ''; ?>>
                                <option value="">- Pilih -</option>
                                <option value="PROSES" <?= ($st === 'PROSES') ? 'selected' : ''; ?>>PROSES</option>
                                <option value="RENCANA" <?= ($st === 'RENCANA') ? 'selected' : ''; ?>>RENCANA</option>
                                <option value="TERKONTRAK" <?= ($st === 'TERKONTRAK') ? 'selected' : ''; ?>>TERKONTRAK</option>
                                <option value="SELESAI" <?= ($st === 'SELESAI') ? 'selected' : ''; ?>>SELESAI</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan"
                                class="form-control rounded-3"
                                rows="2"
                                <?= $disable_perencanaan ? 'readonly' : ''; ?>><?= v($kontrak->keterangan); ?></textarea>
                        </div>
                    </div>

                    <hr>

                    <!-- Pengadaan -->
                    <h6 class="mb-2">Pengadaan</h6>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">No RKS</label>
                            <input type="text" name="no_rks"
                                class="form-control rounded-3"
                                value="<?= v($kontrak->no_rks); ?>"
                                <?= $disable_pengadaan ? 'readonly' : ''; ?>>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Metode</label>
                            <?php $mp = $kontrak->metode_pengadaan ?? ''; ?>
                            <select name="metode_pengadaan" class="form-select rounded-3"
                                <?= $disable_pengadaan ? 'disabled' : ''; ?>>
                                <option value="">- Pilih -</option>
                                <option value="SPBJ UPL" <?= $mp == 'SPBJ UPL' ? 'selected' : ''; ?>>SPBJ UPL</option>
                                <option value="Kontrak Rinci" <?= $mp == 'Kontrak Rinci' ? 'selected' : ''; ?>>Kontrak Rinci</option>
                                <option value="SPK" <?= $mp == 'SPK' ? 'selected' : ''; ?>>SPK</option>
                                <option value="Tender" <?= $mp == 'Tender' ? 'selected' : ''; ?>>Tender</option>
                                <option value="Penunjukan langsung" <?= $mp == 'Penunjukan langsung' ? 'selected' : ''; ?>>Penunjukan langsung</option>
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tahapan Pengadaan</label>
                            <?php $tpeng = trim($kontrak->tahapan_pengadaan ?? ''); ?>
                            <select name="tahapan_pengadaan" class="form-select rounded-3"
                                <?= $disable_pengadaan ? 'disabled' : ''; ?>>
                                <option value="">- Pilih -</option>
                                <option value="Proses CDA" <?= ($tpeng === 'Proses CDA') ? 'selected' : ''; ?>>Proses CDA</option>
                                <option value="Proses TTD Vendor" <?= ($tpeng === 'Proses TTD Vendor') ? 'selected' : ''; ?>>Proses TTD Vendor</option>
                                <option value="Proses TTD Pengguna" <?= ($tpeng === 'Proses TTD Pengguna') ? 'selected' : ''; ?>>Proses TTD Pengguna</option>
                                <option value="Pengadaan Selesai" <?= ($tpeng === 'Pengadaan Selesai') ? 'selected' : ''; ?>>Pengadaan Selesai</option>
                                <option value="Pending & Menunggu Konfirmasi Anggaran" <?= ($tpeng === 'Pending & Menunggu Konfirmasi Anggaran') ? 'selected' : ''; ?>>Pending & Menunggu Konfirmasi Anggaran</option>
                                <option value="Menunggu Nota Dinas" <?= ($tpeng === 'Menunggu Nota Dinas') ? 'selected' : ''; ?>>Menunggu Nota Dinas</option>
                                <option value="Proses Kelengkapan Administrasi Calon Penyedia / Pendaftaran" <?= ($tpeng === 'Proses Kelengkapan Administrasi Calon Penyedia / Pendaftaran') ? 'selected' : ''; ?>>Proses Kelengkapan Administrasi Calon Penyedia / Pendaftaran</option>
                                <option value="Proses Inisiasi Efroc" <?= ($tpeng === 'Proses Inisiasi Efroc') ? 'selected' : ''; ?>>Proses Inisiasi Efroc</option>
                                <option value="Proses Penjilidan" <?= ($tpeng === 'Proses Penjilidan') ? 'selected' : ''; ?>>Proses Penjilidan</option>
                                <option value="Pemberian Penjelasan" <?= ($tpeng === 'Pemberian Penjelasan') ? 'selected' : ''; ?>>Pemberian Penjelasan</option>
                                <option value="Upload Dokumen" <?= ($tpeng === 'Upload Dokumen') ? 'selected' : ''; ?>>Upload Dokumen</option>
                                <option value="Konfirmasi User" <?= ($tpeng === 'Konfirmasi User') ? 'selected' : ''; ?>>Konfirmasi User</option>
                                <option value="Evaluasi Dokumen Penawaran" <?= ($tpeng === 'Evaluasi Dokumen Penawaran') ? 'selected' : ''; ?>>Evaluasi Dokumen Penawaran</option>
                                <option value="Usulan Calon Pemenang" <?= ($tpeng === 'Usulan Calon Pemenang') ? 'selected' : ''; ?>>Usulan Calon Pemenang</option>
                                <option value="Penetapan Calon Pemenang" <?= ($tpeng === 'Penetapan Calon Pemenang') ? 'selected' : ''; ?>>Penetapan Calon Pemenang</option>
                                <option value="Masa Sanggah" <?= ($tpeng === 'Masa Sanggah') ? 'selected' : ''; ?>>Masa Sanggah</option>
                                <option value="Gagal Lelang" <?= ($tpeng === 'Gagal Lelang') ? 'selected' : ''; ?>>Gagal Lelang</option>
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Prognosa Kontrak</label>
                            <input type="date" name="prognosa_kontrak_tgl"
                                class="form-control rounded-3"
                                value="<?= v($kontrak->prognosa_kontrak_tgl); ?>"
                                <?= $disable_pengadaan ? 'readonly' : ''; ?>>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">HPE</label>
                            <input type="number" name="harga_hpe"
                                class="form-control rounded-3"
                                value="<?= v($kontrak->harga_hpe, 0); ?>"
                                <?= $disable_pengadaan ? 'readonly' : ''; ?>>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">HPS</label>
                            <input type="number" name="harga_hps"
                                class="form-control rounded-3"
                                value="<?= v($kontrak->harga_hps, 0); ?>"
                                <?= $disable_pengadaan ? 'readonly' : ''; ?>>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nego</label>
                            <input type="number" name="harga_nego"
                                class="form-control rounded-3"
                                value="<?= v($kontrak->harga_nego, 0); ?>"
                                <?= $disable_pengadaan ? 'readonly' : ''; ?>>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">KAK</label>
                            <textarea name="kak"
                                class="form-control rounded-3"
                                rows="2"
                                <?= $disable_pengadaan ? 'readonly' : ''; ?>><?= v($kontrak->kak); ?></textarea>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">No Kontrak</label>
                            <input type="text" name="no_kontrak"
                                class="form-control rounded-3"
                                value="<?= v($kontrak->no_kontrak); ?>"
                                <?= $disable_pengadaan ? 'readonly' : ''; ?>>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Vendor</label>
                            <input type="text" name="pelaksana_vendor"
                                class="form-control rounded-3"
                                value="<?= v($kontrak->pelaksana_vendor); ?>"
                                <?= $disable_pengadaan ? 'readonly' : ''; ?>>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Tgl Kontrak</label>
                            <input type="date" name="tgl_kontrak"
                                class="form-control rounded-3"
                                value="<?= v($kontrak->tgl_kontrak); ?>"
                                <?= $disable_pengadaan ? 'readonly' : ''; ?>>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">End Kontrak</label>
                            <input type="date" name="end_kontrak"
                                class="form-control rounded-3"
                                value="<?= v($kontrak->end_kontrak); ?>"
                                <?= $disable_pengadaan ? 'readonly' : ''; ?>>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Nilai Kontrak</label>
                            <input type="number" name="nilai_kontrak"
                                class="form-control rounded-3"
                                value="<?= v($kontrak->nilai_kontrak, 0); ?>"
                                <?= $disable_pengadaan ? 'readonly' : ''; ?>>
                        </div>
                        <div class="col-md-9 mb-3">
                            <label class="form-label">Kendala Kontrak</label>
                            <textarea name="kendala_kontrak"
                                class="form-control rounded-3"
                                rows="2"
                                <?= $disable_pengadaan ? 'readonly' : ''; ?>><?= v($kontrak->kendala_kontrak); ?></textarea>
                        </div>
                    </div>

                    <hr>

                    <!-- Pembayaran -->
                    <h6 class="mb-2">KKU / Pembayaran</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tahapan Pembayaran</label>
                            <?php $tp = $kontrak->tahapan_pembayaran ?? ''; ?>
                            <select name="tahapan_pembayaran"
                                class="form-select rounded-3"
                                <?= $disable_kku ? 'disabled' : ''; ?>>
                                <option value="">- Pilih -</option>
                                <option value="adm tagih & vendor" <?= $tp == 'adm tagih & vendor' ? 'selected' : ''; ?>>Adm Tagih & Vendor</option>
                                <option value="Submit VIP" <?= $tp == 'Submit VIP' ? 'selected' : ''; ?>>Submit VIP</option>
                                <option value="Approval 1" <?= $tp == 'Approval 1' ? 'selected' : ''; ?>>Approval 1</option>
                                <option value="Approval 2" <?= $tp == 'Approval 2' ? 'selected' : ''; ?>>Approval 2</option>
                                <option value="Approval Pusat" <?= $tp == 'Approval Pusat' ? 'selected' : ''; ?>>Approval Pusat</option>
                                <option value="Pembayaran" <?= $tp == 'Pembayaran' ? 'selected' : ''; ?>>Pembayaran</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nilai Bayar</label>
                            <input type="number" name="nilai_bayar"
                                class="form-control rounded-3"
                                value="<?= v($kontrak->nilai_bayar, 0); ?>"
                                <?= $disable_kku ? 'readonly' : ''; ?>>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tgl Tahapan</label>
                            <input type="date" name="tgl_tahapan"
                                class="form-control rounded-3"
                                value="<?= v($kontrak->tgl_tahapan); ?>"
                                <?= $disable_kku ? 'readonly' : ''; ?>>
                        </div>

                        <?php for ($m = 1; $m <= 12; $m++): $f = "real_byr_bln{$m}"; ?>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">BLN<?= $m; ?></label>
                                <input type="number" name="<?= $f; ?>"
                                    class="form-control rounded-3"
                                    value="<?= v($kontrak->$f ?? 0, 0); ?>"
                                    readonly>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <?php if (can_edit('data_kontrak')): ?>
                            <button class="btn btn-primary px-4 me-2" type="submit">
                                <i class="fas fa-save me-1"></i>Update
                            </button>
                        <?php endif; ?>
                        <a href="<?= base_url('data_kontrak'); ?>" class="btn btn-secondary px-4">Batal</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</main>

<script>
    const baseUrl = "<?= site_url('data_kontrak'); ?>";
    const existing = {
        prk_id: "<?= (int)($kontrak->prk_id ?? 0); ?>",
        skk_id: "<?= (int)($kontrak->skk_id ?? 0); ?>",
        drp_id: "<?= (int)($kontrak->drp_id ?? 0); ?>"
    };

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

    const CAN_EDIT_MASTER = <?= $disable_master_user ? 'false' : 'true'; ?>;

    async function loadPrk() {
        const id = jenis.value;
        const prkList = await fetchJson(`${baseUrl}/prk_by_jenis/${id}`);
        setOptions(prk, prkList, '- Pilih PRK -');
        if (existing.prk_id && existing.prk_id !== "0") prk.value = existing.prk_id;
        prk.dispatchEvent(new Event('change'));
    }

    if (CAN_EDIT_MASTER) {
        prk.addEventListener('change', async () => {
            const id = prk.value;
            if (!id) {
                setOptions(skk, [], '- Pilih PRK dulu -');
                setOptions(drp, [], '- Pilih PRK dulu -');
                return;
            }
            const skkList = await fetchJson(`${baseUrl}/skk_by_prk/${id}`);
            const drpList = await fetchJson(`${baseUrl}/drp_by_prk/${id}`);

            setOptions(skk, skkList, '- Pilih SKK -');
            setOptions(drp, drpList, '- Pilih DRP -');

            if (existing.skk_id && existing.skk_id !== "0") skk.value = existing.skk_id;
            if (existing.drp_id && existing.drp_id !== "0") drp.value = existing.drp_id;

            if (skkList.length === 1) skk.value = skkList[0].id;
            if (drpList.length === 1) drp.value = drpList[0].id;
        });

        jenis.addEventListener('change', loadPrk);
    }

    // manual override toggle
    const manual = document.getElementById('manual_master');
    const box = document.getElementById('manualBox');

    if (CAN_EDIT_MASTER) {
        function setManualState(on) {
            box.style.display = on ? 'block' : 'none';
            jenis.disabled = on;
            prk.disabled = on;
            skk.disabled = on;
            drp.disabled = on;
        }
        manual.addEventListener('change', () => setManualState(manual.checked));

        // init
        setManualState(manual.checked);
        if (!manual.checked && jenis.value) {
            loadPrk();
        }
    } else {
        // ROLE selain master-user: jangan utak-atik dropdown via JS
        // manualBox tetap ditampilkan sesuai state awal dari PHP
    }

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

            const bulan = d.getMonth() + 1;
            const target = document.querySelector(`input[name="real_byr_bln${bulan}"]`);
            if (target) target.value = nilai;
        }

        // hanya aktif kalau role boleh edit KKU
        const CAN_EDIT_KKU = <?= $disable_kku ? 'false' : 'true'; ?>;

        if (CAN_EDIT_KKU) {
            ['change', 'input'].forEach(ev => {
                tahapEl && tahapEl.addEventListener(ev, recalcBulan);
                tglEl && tglEl.addEventListener(ev, recalcBulan);
                nilaiEl && nilaiEl.addEventListener(ev, recalcBulan);
            });

            recalcBulan();
        }
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