# Mass View Protection Script
# Adds permission checks to CRUD buttons in views

$views = @(
    "bpm\vw_bpm.php",
    "gardu_hubung\vw_gardu_hubung.php",
    "gh_cell\vw_gh_cell.php",
    "gi_cell\vw_gi_cell.php",
    "ik\vw_ik.php",
    "kit_cell\vw_kit_cell.php",
    "pembangkit\vw_pembangkit.php",
    "pemutus\vw_pemutus.php",
    "pengaduan\vw_pengaduan.php",
    "road_map\vw_road_map.php",
    "single_line_diagram\vw_single_line_diagram.php",
    "sop\vw_sop.php",
    "spln\vw_spln.php"
)

$basePath = "C:\laragon\www\pln_up2d_ci\application\views"

foreach ($view in $views) {
    $filePath = Join-Path $basePath $view
    
    if (!(Test-Path $filePath)) {
        Write-Host "⚠️  File not found: $view" -ForegroundColor Yellow
        continue
    }
    
    $content = Get-Content $filePath -Raw
    $modified = $false
    
    # Pattern 1: Wrap Tambah + Import buttons with can_create()
    # Look for pattern: <a href ... tambah ...> immediately followed by <a href ... import ...>
    $pattern1 = '(<a href="<\?= base_url\([^>]+tambah[^>]+>\s+<i class="fas fa-plus[^<]+</i> Tambah\s+</a>)\s+(<a href="<\?= base_url\([^>]+import[^>]+>\s+<i class="fas fa-file-import[^<]+</i> Import\s+</a>)'
    
    if ($content -match $pattern1 -and $content -notmatch '\<\?php if \(can_create\(\)\): \?>') {
        $content = $content -replace $pattern1, '<?php if (can_create()): ?>
                    $1
                    $2
                    <?php endif; ?>'
        Write-Host "✅ Wrapped Tambah+Import buttons in $view" -ForegroundColor Green
        $modified = $true
    }
    
    # Pattern 2: Wrap Edit button in table
    $pattern2 = '(<a href="<\?= base_url\([^>]+/edit/[^>]+class="btn btn-warning[^>]+>\s+<i class="fas fa-pen[^<]+</i>\s+</a>)'
    
    if ($content -match $pattern2 -and $content -notmatch 'if \(can_edit\(\)\): \?>.*?btn-warning') {
        $content = $content -replace $pattern2, '<?php if (can_edit()): ?>
                                            $1
                                            <?php endif; ?>'
        Write-Host "✅ Wrapped Edit button in $view" -ForegroundColor Green
        $modified = $true
    }
    
    # Pattern 3: Wrap Delete button in table  
    $pattern3 = '(<a href="<\?= base_url\([^>]+/hapus/[^>]+class="btn btn-danger[^>]+>\s+<i class="fas fa-trash[^<]+</i>\s+</a>)'
    
    if ($content -match $pattern3 -and $content -notmatch 'if \(can_delete\(\)\): \?>.*?btn-danger') {
        $content = $content -replace $pattern3, '<?php if (can_delete()): ?>
                                            $1
                                            <?php endif; ?>'
        Write-Host "✅ Wrapped Delete button in $view" -ForegroundColor Green
        $modified = $true
    }
    
    if ($modified) {
        Set-Content -Path $filePath -Value $content -NoNewline
        Write-Host "💾 Saved $view`n" -ForegroundColor Cyan
    } else {
        Write-Host "ℹ️  No changes needed for $view`n" -ForegroundColor Gray
    }
}

Write-Host "`n✨ Mass view protection completed!" -ForegroundColor Green
