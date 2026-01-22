# Fix Asset Pages - Search and Reset Functionality

## Problem
All asset pages have duplicate or broken reset buttons due to:
1. Using wrong variable (`$search` vs `$q`)
2. Duplicate reset button blocks
3. Using `<a>` tag instead of `<button>` with onclick

## Solution Applied

### Files Fixed:
1. `/application/views/unit/vw_unit.php` ✅
2. `/application/views/gardu_induk/vw_gardu_induk.php` - PENDING
3. `/application/views/gardu_hubung/vw_gardu_hubung.php` - PENDING
4. `/application/views/gh_cell/vw_gh_cell.php` - PENDING
5. `/application/views/pembangkit/vw_pembangkit.php` - PENDING
6. `/application/views/kit_cell/vw_kit_cell.php` - PENDING
7. `/application/views/pemutus/vw_pemutus.php` - PENDING
8. `/application/views/gi_cell/vw_gi_cell.php` - CHECK

### Pattern Applied:
```php
<button type="submit" class="btn btn-sm btn-primary ms-2">Cari</button>
<?php if (!empty($q)): ?>  <!-- or $search, depending on controller -->
    <button type="button" class="btn btn-sm btn-outline-secondary ms-2" 
        onclick="window.location.replace('<?= base_url('ModuleName/index?per_page=' . (int)$per_page); ?>')">
        Reset
    </button>
<?php endif; ?>
```

### Next Steps:
Need to check each controller to determine correct variable name (`$q` or `$search`)
