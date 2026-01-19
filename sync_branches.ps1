# Script untuk sync perubahan dari branch rombak-database-dan-import ke main
# Run this from PowerShell in project root: .\sync_branches.ps1

Write-Host "=== Syncing changes from rombak-database-dan-import to main ===" -ForegroundColor Green

# Files yang perlu di-copy
$filesToCopy = @(
    "application\models\ImportJob_model.php",
    "application\models\Gardu_induk_model.php",
    "application\models\Gardu_hubung_model.php",
    "application\models\Gi_cell_model.php",
    "application\models\Gh_cell_model.php",
    "application\models\Kit_cell_model.php",
    "application\models\Pemutus_model.php",
    "application\controllers\Import.php",
    "application\controllers\Gardu_induk.php",
    "application\controllers\Gardu_hubung.php",
    "application\controllers\Gi_cell.php",
    "application\controllers\Gh_cell.php",
    "application\controllers\Kit_cell.php",
    "application\controllers\Pemutus.php",
    "application\views\import\status.php",
    "application\views\gardu_induk\vw_gardu_induk.php",
    "application\views\gardu_hubung\vw_gardu_hubung.php",
    "application\views\gi_cell\vw_gi_cell.php",
    "application\views\gh_cell\vw_gh_cell.php",
    "application\views\kit_cell\vw_kit_cell.php",
    "application\views\pemutus\vw_pemutus.php"
)

# Checkout ke branch rombak-database-dan-import
Write-Host "Switching to rombak-database-dan-import branch..." -ForegroundColor Yellow
git checkout rombak-database-dan-import

# Copy files ke temporary location
$tempDir = "C:\temp\pln_sync"
if (!(Test-Path $tempDir)) {
    New-Item -ItemType Directory -Path $tempDir | Out-Null
}

Write-Host "Copying files from rombak-database-dan-import..." -ForegroundColor Yellow
foreach ($file in $filesToCopy) {
    $source = Join-Path (Get-Location) $file
    $dest = Join-Path $tempDir $file
    $destDir = Split-Path $dest -Parent
    
    if (!(Test-Path $destDir)) {
        New-Item -ItemType Directory -Path $destDir -Force | Out-Null
    }
    
    if (Test-Path $source) {
        Copy-Item $source $dest -Force
        Write-Host "  ✓ Copied: $file" -ForegroundColor Green
    } else {
        Write-Host "  ✗ Not found: $file" -ForegroundColor Red
    }
}

# Checkout ke branch main
Write-Host "`nSwitching to main branch..." -ForegroundColor Yellow
git checkout main

# Copy files from temp to main
Write-Host "Applying changes to main branch..." -ForegroundColor Yellow
foreach ($file in $filesToCopy) {
    $source = Join-Path $tempDir $file
    $dest = Join-Path (Get-Location) $file
    
    if (Test-Path $source) {
        Copy-Item $source $dest -Force
        Write-Host "  ✓ Applied: $file" -ForegroundColor Green
    }
}

# Cleanup
Remove-Item $tempDir -Recurse -Force

Write-Host "`n=== Sync completed! ===" -ForegroundColor Green
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "1. Review changes: git status" -ForegroundColor White
Write-Host "2. Commit changes: git add . && git commit -m 'Sync from rombak-database-dan-import'" -ForegroundColor White
Write-Host "3. Push to remote: git push origin main" -ForegroundColor White
