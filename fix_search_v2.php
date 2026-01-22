<?php
/**
 * Script V2 untuk memperbaiki Input Kontrak dan Pengaduan
 */

// 1. INPUT KONTRAK
$fileKontrak = '/Users/nia/Documents/pln_up2d_ci/application/views/input_kontrak/vw_input_kontrak.php';
echo "Processing $fileKontrak...\n";

if (file_exists($fileKontrak)) {
    $content = file_get_contents($fileKontrak);
    
    // Target string (Form existing)
    $targetKontrak = '<form method="get" action="<?= site_url(\'input_kontrak/index/1\'); ?>" class="d-flex align-items-center" onsubmit="event.preventDefault(); searchSubmit(\'<?= site_url(\'input_kontrak/index/1\'); ?>\', \'searchInputKontrak\', \'search\');">
                        <input type="text" id="searchInputKontrak" name="search" value="<?= htmlspecialchars($search ?? \'\', ENT_QUOTES, \'UTF-8\'); ?>" class="form-control form-control-sm rounded-3" style="max-width: 300px;" placeholder="Cari data kontrak...">
                        <button type="submit" class="btn btn-sm btn-primary ms-2">Cari</button>
                    </form>';
    
    // Normalize newlines for matching
    $targetKontrak = preg_replace('/\s+/', ' ', $targetKontrak);
    $contentNormalized = preg_replace('/\s+/', ' ', $content);
    
    // Replacement (Form with Reset)
    $replaceKontrak = '<form method="get" action="<?= site_url(\'input_kontrak/index/1\'); ?>" class="d-flex align-items-center" onsubmit="event.preventDefault(); searchSubmit(\'<?= site_url(\'input_kontrak/index/1\'); ?>\', \'searchInputKontrak\', \'search\');">
                        <input type="text" id="searchInputKontrak" name="search" value="<?= htmlspecialchars($search ?? \'\', ENT_QUOTES, \'UTF-8\'); ?>" class="form-control form-control-sm rounded-3" style="max-width: 300px;" placeholder="Cari data kontrak...">
                        <button type="submit" class="btn btn-sm btn-primary ms-2">Cari</button>
                        <?php if (!empty($search)): ?>
                            <a href="<?= base_url(\'input_kontrak/index/1?per_page=\' . (int)($per_page ?? 5)); ?>" class="btn btn-sm btn-outline-secondary ms-2">Reset</a>
                        <?php endif; ?>
                    </form>';

    // Since exact string match might fail due to whitespace in file vs string, 
    // I'll use a regex replacement on the file content.
    
    // Regex pattern for the form
    $pattern = '/<form method="get" action="<\?= site_url\(\'input_kontrak\/index\/1\'\); \?>" class="d-flex align-items-center".*?<\/form>/s';
    
    if (preg_match($pattern, $content)) {
        $newContent = preg_replace($pattern, $replaceKontrak, $content);
        if ($newContent !== null && $newContent !== $content) {
            file_put_contents($fileKontrak, $newContent);
            echo "✅ Input Kontrak updated successfully.\n";
        } else {
            echo "⚠️  Input Kontrak matched but replacement failed/unchanged.\n";
        }
    } else {
        echo "❌ Input Kontrak pattern not found. Check indentation/content.\n";
    }
} else {
    echo "❌ Input Kontrak file not found.\n";
}

// 2. PENGADUAN
$filePengaduan = '/Users/nia/Documents/pln_up2d_ci/application/views/pengaduan/vw_pengaduan.php';
echo "\nProcessing $filePengaduan...\n";

if (file_exists($filePengaduan)) {
    $content = file_get_contents($filePengaduan);
    
    // Target: Just the input, needs wrapping
    // <div class="px-3 mt-3 mb-3 d-flex justify-content-between align-items-center">
    // ... select ...
    // input is AFTER the div for select which is specific.
    
    // Pattern: Find the div containing the select, then the input after it.
    // The input line:
    // <input type="text" id="searchInputPengaduan" value="<?= htmlentities((string)($q ?? ''), ENT_QUOTES, 'UTF-8'); ?>" class="form-control form-control-sm rounded-3" style="max-width: 300px;" placeholder="Cari data pengaduan...">
    
    // Replacement: Wrap in form and add buttons
    $replacePengaduan = '
                    <form method="get" action="<?= site_url(\'pengaduan/index/1\'); ?>" class="d-flex align-items-center" onsubmit="event.preventDefault(); searchSubmit(\'<?= site_url(\'pengaduan/index/1\'); ?>\', \'searchInputPengaduan\', \'q\');">
                        <input type="text" id="searchInputPengaduan" name="q" value="<?= htmlentities((string)($q ?? \'\'), ENT_QUOTES, \'UTF-8\'); ?>" class="form-control form-control-sm rounded-3" style="max-width: 300px;" placeholder="Cari data pengaduan...">
                        <button type="submit" class="btn btn-sm btn-primary ms-2">Cari</button>
                        <?php if (!empty($q)): ?>
                            <a href="<?= base_url(\'pengaduan/index/1\'); ?>" class="btn btn-sm btn-outline-secondary ms-2">Reset</a>
                        <?php endif; ?>
                    </form>';
    
    $pattern = '/<input type="text" id="searchInputPengaduan"[^>]*?>/';
    
    if (preg_match($pattern, $content)) {
        $newContent = preg_replace($pattern, $replacePengaduan, $content);
        file_put_contents($filePengaduan, $newContent);
        echo "✅ Pengaduan updated successfully.\n";
    } else {
        echo "❌ Pengaduan input pattern not found.\n";
    }
} else {
    echo "❌ Pengaduan file not found.\n";
}
