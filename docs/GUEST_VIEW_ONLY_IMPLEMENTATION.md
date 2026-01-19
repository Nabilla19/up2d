# Guest View-Only Implementation Guide

## ✅ Completed Implementation

### Controllers Protected (15 controllers)
All CRUD methods in these controllers now check permissions:
- Unit, Gardu_induk, Gi_cell, Gardu_hubung, Gh_cell
- Pembangkit, Kit_cell, Pemutus
- Sop, Bpm, Ik, Road_map, Spln
- Single_line_diagram, Pengaduan

**Protection Pattern:**
```php
public function tambah() {
    if (!can_create()) {
        $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menambah data');
        redirect($this->router->fetch_class());
    }
    // ... rest of code
}
```

### Views Protected (3 views completed)
- ✅ Unit (vw_unit.php)
- ✅ Gardu_induk (vw_gardu_induk.php)  
- ✅ Gi_cell (vw_gi_cell.php)

---

## 📋 Pattern untuk Update Views yang Tersisa

### Pattern 1: Wrap Tambah + Import Buttons
**Before:**
```php
<div class="d-flex align-items-center">
    <a href="<?= base_url('Controller/tambah') ?>" class="btn btn-sm btn-light text-primary me-2">
        <i class="fas fa-plus me-1"></i> Tambah
    </a>
    <a href="<?= base_url('import/module') ?>" class="btn btn-sm btn-light text-success">
        <i class="fas fa-file-import me-1"></i> Import
    </a>
    <a href="<?= base_url('Controller/export_csv') ?>" class="btn btn-sm btn-light text-secondary ms-2">
        <i class="fas fa-file-csv me-1"></i> Download CSV
    </a>
</div>
```

**After:**
```php
<div class="d-flex align-items-center">
    <?php if (can_create()): ?>
    <a href="<?= base_url('Controller/tambah') ?>" class="btn btn-sm btn-light text-primary me-2">
        <i class="fas fa-plus me-1"></i> Tambah
    </a>
    <a href="<?= base_url('import/module') ?>" class="btn btn-sm btn-light text-success">
        <i class="fas fa-file-import me-1"></i> Import
    </a>
    <?php endif; ?>
    <a href="<?= base_url('Controller/export_csv') ?>" class="btn btn-sm btn-light text-secondary ms-2">
        <i class="fas fa-file-csv me-1"></i> Download CSV
    </a>
</div>
```

### Pattern 2: Wrap Edit & Delete Buttons in Table
**Before:**
```php
<td class="text-center">
    <a href="<?= base_url('Controller/detail/' . $row['ID']); ?>" class="btn btn-info btn-xs text-white me-1" title="Detail">
        <i class="fas fa-info-circle"></i>
    </a>
    <a href="<?= base_url('Controller/edit/' . $row['ID']); ?>" class="btn btn-warning btn-xs text-white me-1" title="Edit">
        <i class="fas fa-pen"></i>
    </a>
    <a href="<?= base_url('Controller/hapus/' . $row['ID']); ?>" class="btn btn-danger btn-xs btn-hapus" title="Hapus">
        <i class="fas fa-trash"></i>
    </a>
</td>
```

**After:**
```php
<td class="text-center">
    <a href="<?= base_url('Controller/detail/' . $row['ID']); ?>" class="btn btn-info btn-xs text-white me-1" title="Detail">
        <i class="fas fa-info-circle"></i>
    </a>
    <?php if (can_edit()): ?>
    <a href="<?= base_url('Controller/edit/' . $row['ID']); ?>" class="btn btn-warning btn-xs text-white me-1" title="Edit">
        <i class="fas fa-pen"></i>
    </a>
    <?php endif; ?>
    <?php if (can_delete()): ?>
    <a href="<?= base_url('Controller/hapus/' . $row['ID']); ?>" class="btn btn-danger btn-xs btn-hapus" title="Hapus">
        <i class="fas fa-trash"></i>
    </a>
    <?php endif; ?>
</td>
```

---

## 🎯 Views yang Perlu Diupdate

Apply pattern di atas ke views berikut:

### Asset Module Views
- [ ] gh_cell/vw_gh_cell.php
- [ ] gardu_hubung/vw_gardu_hubung.php
- [ ] pembangkit/vw_pembangkit.php
- [ ] kit_cell/vw_kit_cell.php
- [ ] pemutus/vw_pemutus.php

### Pustaka Module Views
- [ ] sop/vw_sop.php
- [ ] bpm/vw_bpm.php
- [ ] ik/vw_ik.php
- [ ] road_map/vw_road_map.php
- [ ] spln/vw_spln.php

### Other Module Views
- [ ] single_line_diagram/vw_single_line_diagram.php
- [ ] pengaduan/vw_pengaduan.php

---

## 🧪 Testing Checklist

### As Guest User:
1. ✅ Login via "Lihat sebagai Tamu"
2. ✅ Can view all modules and data
3. ✅ NO Tambah button visible
4. ✅ NO Import button visible
5. ✅ NO Edit button visible in tables
6. ✅ NO Delete button visible in tables
7. ✅ Download CSV button still visible (read-only export)
8. ✅ Cannot access CRUD URLs directly (redirected with error)
9. ✅ Can logout successfully

### As Admin/Other Roles:
1. ✅ All CRUD buttons visible
2. ✅ Can create/edit/delete data
3. ✅ All features work normally

---

## 📝 Notes
- Guest role is set via `login/guest_login` method
- All permission checks use `authorization_helper.php`
- Controller protection is MANDATORY (already done)
- View button hiding is for UX improvement
- Export/Download features remain accessible for all roles
