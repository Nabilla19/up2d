# Dokumentasi Sistem Permohonan Angkutan Kendaraan
## PLN UP2D - Transport Request Management System

---

## 📋 Daftar Isi

1. [Gambaran Umum](#gambaran-umum)
2. [Fitur Utama](#fitur-utama)
3. [Teknologi yang Digunakan](#teknologi-yang-digunakan)
4. [Arsitektur Sistem](#arsitektur-sistem)
5. [Database Schema](#database-schema)
6. [Alur Kerja Sistem](#alur-kerja-sistem)
7. [Komponen Sistem](#komponen-sistem)
8. [Panduan Penggunaan](#panduan-penggunaan)
9. [API Endpoints](#api-endpoints)
10. [Validasi dan Keamanan](#validasi-dan-keamanan)

---

## 🎯 Gambaran Umum

Sistem Permohonan Angkutan Kendaraan adalah aplikasi berbasis web yang dibangun untuk mengelola peminjaman dan penggunaan kendaraan dinas di lingkungan PLN UP2D. Sistem ini memfasilitasi proses pengajuan, persetujuan, penugasan kendaraan, dan monitoring penggunaan kendaraan secara digital.

### Tujuan Sistem
- Mempermudah karyawan dalam mengajukan permohonan kendaraan dinas
- Mengotomatisasi proses approval oleh Asisten Manager (Asmen)
- Mengelola penugasan kendaraan dan pengemudi oleh Fleet Management
- Monitoring keluar-masuk kendaraan oleh Security
- Membuat laporan dan dokumentasi perjalanan dinas

---

## ✨ Fitur Utama

### 1. **Pengajuan Permohonan Kendaraan**
- Form pengajuan yang user-friendly
- Input informasi pemohon, tujuan, dan jadwal perjalanan
- Pilihan jenis kendaraan sesuai kebutuhan
- Generate barcode otomatis untuk tracking

### 2. **Sistem Approval Multi-Level**
- Approval oleh Asisten Manager (Asmen)
- Catatan approval/rejection dengan alasan
- Notifikasi status permohonan
- Barcode approval untuk validasi

### 3. **Fleet Management**
- Penugasan kendaraan dan pengemudi
- Tracking status kendaraan (Available/In Use)
- Manajemen data kendaraan
- Barcode fleet assignment

### 4. **Security Monitoring**
- Pencatatan keluar kendaraan
- Pencatatan kembali kendaraan
- Upload foto dokumentasi (driver & odometer)
- Kalkulasi jarak tempuh dan durasi

### 6. **Digital Validation & Security**
- QR Code Signature yang tertaut langsung ke detail digital permohonan.
- Tanda tangan digital menampilkan **Nama User** dan **Timestamp** real-time untuk validasi yang lebih kuat.
- Penghapusan fitur pendaftaran (Sign-Up) mandiri untuk menjaga integritas database user.
- Filter kendaraan berdasarkan Brand (Toyota/Daihatsu) untuk mempermudah operasional.

---

## 🛠️ Teknologi yang Digunakan

- **Framework**: CodeIgniter 3.x
- **Programming Language**: PHP 7.x+
- **Database**: MySQL/MariaDB
- **Frontend**: 
  - HTML5
  - CSS3 (Bootstrap/Material Design)
  - JavaScript/jQuery
  - Font Awesome Icons
- **Architecture Pattern**: MVC (Model-View-Controller)

---

## 🏗️ Arsitektur Sistem

### Model-View-Controller (MVC)

```
application/
├── controllers/
│   └── Transport_request.php    # Controller utama
├── models/
│   └── Transport_model.php      # Model untuk database operations
└── views/
    └── transport/
        ├── form_request.php     # Form pengajuan
        ├── my_requests.php      # Daftar permohonan user
        ├── all_requests.php     # Daftar semua permohonan
        ├── detail_request.php   # Detail permohonan
        ├── approval_list.php    # Daftar approval
        ├── form_fleet.php       # Form penugasan kendaraan
        ├── fleet_list.php       # Daftar fleet
        ├── form_security_in.php # Form keluar kendaraan
        ├── form_security_out.php# Form masuk kendaraan
        ├── security_list.php    # Daftar log security
        └── export_view.php      # Template export PDF
```

---

## 🗄️ Database Schema

### Tabel: `transport_requests`
Menyimpan data permohonan kendaraan

| Field | Type | Description |
|-------|------|-------------|
| `id` | INT (PK, AI) | ID unik permohonan |
| `user_id` | INT | ID user pemohon |
| `nama` | VARCHAR | Nama pemohon |
| `jabatan` | VARCHAR | Jabatan pemohon |
| `bagian` | VARCHAR | Bagian/bidang pemohon |
| `macam_kendaraan` | VARCHAR | Jenis kendaraan yang diminta |
| `jumlah_penumpang` | INT | Jumlah penumpang |
| `tujuan` | TEXT | Lokasi tujuan |
| `keperluan` | TEXT | Deskripsi keperluan |
| `tanggal_jam_berangkat` | DATETIME | Waktu keberangkatan |
| `lama_pakai` | VARCHAR | Estimasi durasi penggunaan |
| `status` | VARCHAR | Status permohonan |
| `barcode_pemohon` | VARCHAR | Barcode unik untuk pemohon |
| `created_at` | TIMESTAMP | Waktu dibuat |
| `updated_at` | TIMESTAMP | Waktu diupdate |

**Status yang tersedia:**
- `Pending Asmen` - Menunggu approval
- `Approved` - Disetujui
- `Rejected` - Ditolak
- `Assigned` - Kendaraan sudah ditugaskan
- `On Trip` - Sedang perjalanan
- `Completed` - Selesai

### Tabel: `transport_approvals`
Menyimpan data approval dari Asmen

| Field | Type | Description |
|-------|------|-------------|
| `id` | INT (PK, AI) | ID approval |
| `request_id` | INT (FK) | ID permohonan |
| `is_approved` | BOOLEAN | Status approval (1=approved, 0=rejected) |
| `catatan` | TEXT | Catatan dari Asmen |
| `approved_by` | INT | ID user yang approve |
| `approved_at` | TIMESTAMP | Waktu approval |
| `barcode_asmen` | VARCHAR | Barcode approval |

### Tabel: `transport_fleet`
Menyimpan penugasan kendaraan dan pengemudi

| Field | Type | Description |
|-------|------|-------------|
| `id` | INT (PK, AI) | ID fleet assignment |
| `request_id` | INT (FK) | ID permohonan |
| `mobil` | VARCHAR | Tipe/merk mobil |
| `plat_nomor` | VARCHAR | Nomor plat kendaraan |
| `pengemudi` | VARCHAR | Nama pengemudi |
| `assigned_by` | INT | ID user yang assign |
| `barcode_fleet` | VARCHAR | Barcode fleet |
| `created_at` | TIMESTAMP | Waktu penugasan |

### Tabel: `transport_security_logs`
Menyimpan log keluar-masuk kendaraan

| Field | Type | Description |
|-------|------|-------------|
| `id` | INT (PK, AI) | ID log |
| `request_id` | INT (FK) | ID permohonan |
| `km_awal` | INT | Odometer awal |
| `km_akhir` | INT | Odometer akhir |
| `jam_berangkat` | DATETIME | Waktu berangkat actual |
| `jam_kembali` | DATETIME | Waktu kembali |
| `lama_waktu` | VARCHAR | Durasi perjalanan |
| `jarak_tempuh` | INT | Jarak yang ditempuh (km) |
| `foto_driver_berangkat` | VARCHAR | Path foto driver saat berangkat |
| `foto_km_berangkat` | VARCHAR | Path foto odometer saat berangkat |
| `foto_driver_kembali` | VARCHAR | Path foto driver saat kembali |
| `foto_km_kembali` | VARCHAR | Path foto odometer saat kembali |
| `logged_by` | INT | ID user security |
| `created_at` | TIMESTAMP | Waktu log dibuat |
| `updated_at` | TIMESTAMP | Waktu log diupdate |

### Tabel: `transport_vehicles`
Menyimpan data kendaraan

| Field | Type | Description |
|-------|------|-------------|
| `id` | INT (PK, AI) | ID kendaraan |
| `brand` | VARCHAR | Merk kendaraan |
| `plat_nomor` | VARCHAR (Unique) | Nomor plat |
| `model` | VARCHAR | Model kendaraan |
| `tahun` | INT | Tahun pembuatan |
| `status` | VARCHAR | Status (Available/In Use) |
| `last_request_id` | INT | ID request terakhir |

### Tabel: `transport_vehicle_types`
Menyimpan jenis-jenis kendaraan

| Field | Type | Description |
|-------|------|-------------|
| `id` | INT (PK, AI) | ID tipe |
| `type_name` | VARCHAR | Nama jenis kendaraan |
| `description` | TEXT | Deskripsi |

**Contoh jenis kendaraan:**
- Toyota
- Daihatsu

---

## 🔄 Alur Kerja Sistem

### Flow Diagram

```
┌─────────────────┐
│  User/Pemohon   │
└────────┬────────┘
         │
         ▼
┌─────────────────────────────┐
│ 1. Mengisi Form Permohonan  │
│    - Data pemohon           │
│    - Jenis kendaraan        │
│    - Tujuan & keperluan     │
│    - Jadwal                 │
└────────┬────────────────────┘
         │
         ▼
┌─────────────────────────────┐
│ Status: Pending Asmen       │
│ Barcode: Generated          │
└────────┬────────────────────┘
         │
         ▼
┌─────────────────────────────┐
│  Asisten Manager (Asmen)    │
│  2. Review & Approval       │
│     - Approve ✓             │
│     - Reject ✗              │
│     - Catatan               │
└────────┬────────────────────┘
         │
         ├─── Rejected ───────┐
         │                    │
         │ Approved           │
         ▼                    ▼
┌─────────────────────────┐  ┌─────────────────┐
│  Fleet Management       │  │ End: Ditolak    │
│  3. Assign Vehicle      │  └─────────────────┘
│     - Pilih kendaraan   │
│     - Pilih pengemudi   │
└────────┬────────────────┘
         │
         ▼
┌─────────────────────────────┐
│ Status: Assigned            │
└────────┬────────────────────┘
         │
         ▼
┌─────────────────────────────┐
│  Security (Keluar)          │
│  4. Log Keberangkatan       │
│     - Foto driver           │
│     - Foto odometer (KM)    │
│     - Jam berangkat         │
└────────┬────────────────────┘
         │
         ▼
┌─────────────────────────────┐
│ Status: On Trip             │
└────────┬────────────────────┘
         │
         ▼
┌─────────────────────────────┐
│  Security (Kembali)         │
│  5. Log Kepulangan          │
│     - Foto driver           │
│     - Foto odometer (KM)    │
│     - Jam kembali           │
│     - Hitung jarak & durasi │
└────────┬────────────────────┘
         │
         ▼
┌─────────────────────────────┐
│ Status: Completed           │
│ Generate Report PDF         │
└─────────────────────────────┘
```

---

## 🧩 Komponen Sistem

### 1. Controller: `Transport_request.php`

#### Method-method Utama:

##### `__construct()`
Inisialisasi controller, load model, library, dan cek autentikasi

##### `index()`
Redirect ke halaman my_requests

##### `my_requests()`
Menampilkan daftar permohonan milik user yang sedang login

```php
public function my_requests() {
    $user_id = $this->session->userdata('user_id');
    $data['requests'] = $this->Transport_model->get_my_requests($user_id);
    // Load views...
}
```

##### `all_requests()`
Menampilkan semua permohonan dengan detail lengkap (untuk admin/manager)

##### `ajukan()` / `create()`
Menampilkan form pengajuan permohonan baru

##### `store()`
Menyimpan permohonan baru ke database dengan validasi

**Validasi yang dilakukan:**
- Nama (required)
- Jabatan (required)
- Bagian (required)
- Tujuan (required)
- Keperluan (required)
- Tanggal/Jam Berangkat (required)

**Data yang disimpan:**
```php
$data = [
    'user_id' => $user_id,
    'nama' => $this->input->post('nama'),
    'jabatan' => $this->input->post('jabatan'),
    'bagian' => $this->input->post('bagian'),
    'macam_kendaraan' => $this->input->post('macam_kendaraan'),
    'jumlah_penumpang' => $this->input->post('jumlah_penumpang'),
    'tujuan' => $this->input->post('tujuan'),
    'keperluan' => $this->input->post('keperluan'),
    'tanggal_jam_berangkat' => $this->input->post('tanggal_jam_berangkat'),
    'lama_pakai' => $this->input->post('lama_pakai'),
    'status' => 'Pending Asmen',
    'barcode_pemohon' => md5('PEMOHON-'.$user_id.'-'.uniqid().'-'.time())
];
```

##### `detail($id)`
Menampilkan detail lengkap permohonan beserta approval, fleet, dan security log

##### `export_pdf($id = null)`
Generate laporan PDF untuk permohonan tertentu atau semua permohonan

---

### 2. Model: `Transport_model.php`

#### Method untuk Requests:

##### `get_requests($id = null)`
Mengambil data request, jika ada ID maka ambil satu record, jika tidak ambil semua

##### `get_my_requests($user_id)`
Mengambil semua request milik user tertentu

##### `create_request($data)`
Insert request baru dan return ID-nya

##### `update_request($id, $data)`
Update data request berdasarkan ID

#### Method untuk Approvals:

##### `get_approval($request_id)`
Mengambil data approval untuk request tertentu

##### `add_approval($data)`
Insert data approval baru

#### Method untuk Fleet:

##### `get_fleet($request_id)`
Mengambil data fleet assignment untuk request tertentu

##### `add_fleet($data)`
Insert fleet assignment baru

#### Method untuk Security Logs:

##### `get_security_log($request_id)`
Mengambil security log untuk request tertentu

##### `add_security_log($data)`
Insert security log baru

##### `update_security_log($request_id, $data)`
Update security log (untuk mencatat kepulangan)

#### Method untuk Vehicles:

##### `get_vehicles()`
Mengambil semua data kendaraan

##### `get_available_vehicles($brand = null)`
Mengambil kendaraan yang tersedia, bisa difilter berdasarkan merk

##### `update_vehicle_status($plat_nomor, $status, $request_id = null)`
Update status kendaraan (Available/In Use)

##### `get_vehicle_types()`
Mengambil semua jenis kendaraan

#### Method untuk Dashboard:

##### `get_all_requests_detailed()`
Mengambil semua request dengan JOIN ke tabel approval, fleet, dan security log

```sql
SELECT 
    tr.*,
    tf.mobil, tf.plat_nomor, tf.pengemudi, tf.created_at as fleet_assigned_at,
    ts.km_awal, ts.km_akhir, ts.jam_berangkat, ts.jam_kembali,
    ts.lama_waktu, ts.jarak_tempuh,
    ts.foto_driver_berangkat, ts.foto_km_berangkat,
    ts.foto_driver_kembali, ts.foto_km_kembali,
    ta.is_approved, ta.catatan, ta.approved_at,
    tr.barcode_pemohon, ta.barcode_asmen, tf.barcode_fleet
FROM transport_requests tr
LEFT JOIN transport_approvals ta ON ta.request_id = tr.id
LEFT JOIN transport_fleet tf ON tf.request_id = tr.id
LEFT JOIN transport_security_logs ts ON ts.request_id = tr.id
ORDER BY tr.created_at DESC
```

---

### 3. View: `form_request.php`

Form pengajuan permohonan dengan fitur:

#### Section 1: Informasi Pemohon
- **Nama Lengkap**: Auto-fill dari session user
- **Jabatan**: Input manual (contoh: Manager / Supervisor)
- **Bagian/Bidang**: Input manual (contoh: Operasi / Pemeliharaan)
- **Macam Kendaraan**: Dropdown dari database (Sedan, Minibus, Pick Up, dll)
- **Jumlah Penumpang**: Input number

#### Section 2: Detail Perjalanan & Keperluan
- **Lokasi Tujuan**: Input text (contoh: Kantor Wilayah / PLN ULP)
- **Deskripsi Keperluan**: Textarea
- **Waktu Berangkat**: DateTime picker
- **Estimasi Durasi Pakai**: Input text (contoh: 3 Jam / 1 Hari)

#### UI/UX Features:
- Desain modern dengan gradient header
- Icons untuk setiap field menggunakan Font Awesome
- Responsive layout (Bootstrap grid)
- Input validation (HTML5 required attributes)
- Submit button dengan icon
- Cancel/Back button

#### Form Action:
```php
<form action="<?= base_url('transport/simpan') ?>" method="post">
```

---

## 📖 Panduan Penggunaan

### Untuk Pemohon/User:

#### 1. Mengajukan Permohonan Baru
1. Login ke sistem
2. Akses menu "Pengajuan Kendaraan" atau URL: `/transport/ajukan`
3. Isi form dengan lengkap:
   - Data pribadi (nama, jabatan, bagian)
   - Jenis kendaraan yang dibutuhkan
   - Jumlah penumpang
   - Tujuan perjalanan
   - Keperluan/alasan perjalanan
   - Jadwal keberangkatan
   - Estimasi durasi penggunaan
4. Klik "Kirim Permohonan"
5. Sistem akan generate barcode dan status "Pending Asmen"

#### 2. Melihat Status Permohonan
1. Akses "/transport/my_requests"
2. Lihat daftar permohonan dengan status masing-masing
3. Klik detail untuk informasi lengkap

#### 3. Mencetak Surat Perjalanan
1. Buka detail permohonan yang sudah approved
2. Klik tombol "Export PDF"
3. PDF akan berisi semua informasi perjalanan

### Untuk Asisten Manager (Asmen):

#### 1. Review Permohonan
1. Akses "/transport/approval_list"
2. Lihat daftar permohonan yang menunggu approval
3. Klik detail untuk melihat informasi lengkap

#### 2. Approve/Reject
1. Pada halaman detail, cek informasi pemohon
2. Pilih Approve atau Reject
3. Masukkan catatan (wajib jika reject)
4. Submit - sistem akan generate barcode approval

### Untuk Fleet Management:

#### 1. Assign Kendaraan & Driver
1. Akses "/transport/fleet_list"
2. Lihat permohonan yang sudah approved
3. Klik "Assign Vehicle"
4. Pilih kendaraan yang tersedia
5. Input nama pengemudi
6. Submit - status kendaraan berubah menjadi "In Use"

### Untuk Security:

#### 1. Log Keberangkatan
1. Akses "/transport/security_in"
2. Scan atau input barcode permohonan
3. Upload foto driver
4. Upload foto odometer (KM awal)
5. Input odometer number
6. Catat jam berangkat
7. Submit

#### 2. Log Kepulangan
1. Akses "/transport/security_out"
2. Scan atau input barcode permohonan
3. Upload foto driver saat kembali
4. Upload foto odometer (KM akhir)
5. Input odometer number
6. Catat jam kembali
7. Sistem otomatis hitung:
   - Jarak tempuh = KM akhir - KM awal
   - Lama waktu = Jam kembali - Jam berangkat
8. Submit - status berubah "Completed"

---

## 🔌 API Endpoints

### Request Management

| Method | Endpoint | Description | Access |
|--------|----------|-------------|--------|
| GET | `/transport/my_requests` | Daftar permohonan user | User |
| GET | `/transport/all_requests` | Semua permohonan | Admin |
| GET | `/transport/ajukan` | Form permohonan baru | User |
| POST | `/transport/simpan` | Simpan permohonan baru | User |
| GET | `/transport/detail/{id}` | Detail permohonan | User |
| GET | `/transport/export_pdf/{id}` | Export PDF | User |

### Approval Management

| Method | Endpoint | Description | Access |
|--------|----------|-------------|--------|
| GET | `/transport/approval_list` | Daftar menunggu approval | Asmen |
| POST | `/transport/approve/{id}` | Approve permohonan | Asmen |
| POST | `/transport/reject/{id}` | Reject permohonan | Asmen |

### Fleet Management

| Method | Endpoint | Description | Access |
|--------|----------|-------------|--------|
| GET | `/transport/fleet_list` | Daftar fleet assignment | Fleet |
| POST | `/transport/assign_fleet` | Assign kendaraan | Fleet |
| GET | `/transport/vehicles` | Daftar kendaraan | Fleet |

### Security Management

| Method | Endpoint | Description | Access |
|--------|----------|-------------|--------|
| GET | `/transport/security_list` | Daftar log security | Security |
| POST | `/transport/log_departure` | Log keberangkatan | Security |
| POST | `/transport/log_arrival` | Log kepulangan | Security |

---

## 🔒 Validasi dan Keamanan

### Session Management
- Semua halaman dilindungi dengan session check
- Redirect ke login jika belum login
- User ID disimpan dalam session

```php
if (!$this->session->userdata('logged_in')) {
    redirect('login');
}
```

### Form Validation
Menggunakan CodeIgniter Form Validation:

```php
$this->form_validation->set_rules('nama', 'Nama', 'required');
$this->form_validation->set_rules('jabatan', 'Jabatan', 'required');
$this->form_validation->set_rules('bagian', 'Bagian', 'required');
// ... dll
```

### Barcode Security
Generate barcode unik menggunakan MD5 hash:

```php
// Barcode Pemohon
md5('PEMOHON-' . $user_id . '-' . uniqid() . '-' . time())

// Barcode Asmen
md5('ASMEN-' . $request_id . '-' . uniqid() . '-' . time())

// Barcode Fleet
md5('FLEET-' . $request_id . '-' . uniqid() . '-' . time())
```

### Digital Signature & QR Verification
- QR Code pada PDF tidak lagi menggunakan kode acak (hash), melainkan teks deskriptif: *"Validasi Digital: Permohonan [Action] oleh [Nama] pada [Waktu]"*.
- Data ini diambil langsung dari database saat scan dilakukan untuk memastikan keaslian dokumen.
- QR Code utama pada header (jika diaktifkan) tertaut langsung ke URL detail permohonan.

### File Upload Security
- Validasi tipe file (hanya image)
- Validasi ukuran file
- Rename file dengan unique name
- Simpan di folder terproteksi

---

## 📊 Status Permohonan

| Status | Keterangan | Aksi Berikutnya |
|--------|------------|-----------------|
| **Pending Asmen** | Menunggu approval | Asmen review & approve/reject |
| **Approved** | Disetujui Asmen | Fleet assign kendaraan |
| **Rejected** | Ditolak Asmen | End - bisa ajukan ulang |
| **Assigned** | Kendaraan ditugaskan | Security log keberangkatan |
| **On Trip** | Sedang perjalanan | Menunggu kembali |
| **Completed** | Selesai | Generate report |

---

## 📝 Catatan Pengembangan

### Fitur yang Bisa Ditambahkan:
1. **Notifikasi Email/SMS** saat status berubah
2. **Dashboard Analytics** dengan chart dan statistik
3. **Integration dengan GPS** untuk tracking real-time
4. **Rating & Feedback** untuk driver
5. **Reminder Otomatis** sebelum jadwal keberangkatan
6. **API Mobile App** untuk kemudahan akses
7. **QR Code Scanner** untuk security
8. **Digital Signature** untuk approval
9. **Fuel Management** tracking konsumsi BBM
10. **Maintenance Schedule** tracking service kendaraan

### Best Practices:
- Selalu validasi input dari user
- Gunakan prepared statements untuk query
- Implementasi logging untuk audit trail
- Backup database secara berkala
- Monitor performance query
- Implement caching untuk data yang sering diakses

---

## 🐛 Troubleshooting

### Masalah Umum:

#### 1. Permohonan tidak tersimpan
- Cek validasi form
- Cek koneksi database
- Lihat error log CodeIgniter

#### 2. Barcode tidak ter-generate
- Cek fungsi MD5
- Pastikan uniqid() dan time() berfungsi
- Cek field barcode di database (VARCHAR cukup panjang)

#### 3. PDF tidak ter-export
- Cek library PDF (TCPDF/mPDF)
- Cek permission folder upload
- Cek memory limit PHP

#### 4. Upload foto gagal
- Cek permission folder upload
- Cek max file size di php.ini
- Cek allowed file types

---

## 📞 Kontak & Support

Untuk pertanyaan atau bantuan lebih lanjut mengenai sistem ini, silakan hubungi:
- **Developer Team**: PLN UP2D IT Division
- **Email**: support@plnup2d.co.id
- **Internal Ticket**: Gunakan sistem ticketing internal

---

**Dokumentasi ini terakhir diupdate**: 26 Januari 2026 (Refinement & Digital Signature Update)  
**Versi Sistem**: 1.1.0  
**Framework**: CodeIgniter 3.x  

---

© 2026 PLN UP2D - Transport Request Management System
