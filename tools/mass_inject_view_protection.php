<?php
/**
 * Mass View Protection Injector
 * Wrap CRUD buttons with permission checks
 * 
 * Usage: php mass_inject_view_protection.php
 */

$views = [
    'pemutus/vw_pemutus.php',
    'sop/vw_sop.php',
    'spln/vw_spln.php',
    'single_line_diagram/vw_single_line_diagram.php',
    'road_map/vw_road_map.php',
    'pembangkit/vw_pembangkit.php',
    'pengaduan/vw_pengaduan.php',
    'kit_cell/vw_kit_cell.php',
    'gi_cell/vw_gi_cell.php',
    'ik/vw_ik.php',
    'gardu_induk/vw_gardu_induk.php',
    'gh_cell/vw_gh_cell.php',
    'gardu_hubung/vw_gardu_hubung.php',
    'bpm/vw_bpm.php',
];

$view_dir = dirname(__DIR__) . '/application/views/';

foreach ($views as $file) {
    $path = $view_dir . $file;
    
    if (!file_exists($path)) {
        echo "⚠️  File not found: $file\n";
        continue;
    }
    
    $content = file_get_contents($path);
    $original = $content;
    $modified = false;
    
    // Pattern 1: Wrap Tambah button with can_create()
    $pattern_tambah = '/<a href="<\?= base_url\(\'([^\']+)\/tambah\'\) \?>" class="btn btn-sm btn-light text-primary(.*?)>\s*<i class="fas fa-plus(.*?)<\/i> Tambah\s*<\/a>/s';
    if (preg_match($pattern_tambah, $content) && !preg_match('/\<\?php if \(can_create\(\)\)/', $content)) {
        $content = preg_replace(
            $pattern_tambah,
            '<?php if (can_create()): ?>
                    <a href="<?= base_url(\'$1/tambah\') ?>" class="btn btn-sm btn-light text-primary$2>
                        <i class="fas fa-plus$3</i> Tambah
                    </a>
                    <?php endif; ?>',
            $content
        );
        echo "✅ Wrapped Tambah button in $file\n";
        $modified = true;
    }
    
    // Pattern 2: Wrap Import button with can_create()
    $pattern_import = '/<a href="<\?= base_url\(\'import\/([^\']+)\'\) \?>" class="btn btn-sm btn-light text-success(.*?)>\s*<i class="fas fa-file-import(.*?)<\/i> Import\s*<\/a>/s';
    if (preg_match($pattern_import, $content) && strpos($content, '<?php if (can_create()): ?>') === false) {
        $content = preg_replace(
            $pattern_import,
            '<?php if (can_create()): ?>
                    <a href="<?= base_url(\'import/$1\') ?>" class="btn btn-sm btn-light text-success$2>
                        <i class="fas fa-file-import$3</i> Import
                    </a>
                    <?php endif; ?>',
            $content
        );
        echo "✅ Wrapped Import button in $file\n";
        $modified = true;
    }
    
    // Pattern 3: Wrap Edit button in table rows
    $pattern_edit = '/<a href="<\?= base_url\(\'([^\']+)\/edit\/\' \. \$([^\)]+)\[\'([^\']+)\'\]\); \?>" class="btn btn-warning btn-xs(.*?)title="Edit">\s*<i class="fas fa-pen(.*?)<\/i>\s*<\/a>/s';
    if (preg_match($pattern_edit, $content) && !preg_match('/\<\?php if \(can_edit\(\)\): \?>.*?btn-warning/s', $content)) {
        $content = preg_replace(
            $pattern_edit,
            '<?php if (can_edit()): ?>
                                            <a href="<?= base_url(\'$1/edit/\' . $$2[\'$3\']); ?>" class="btn btn-warning btn-xs$4title="Edit">
                                                <i class="fas fa-pen$5</i>
                                            </a>
                                            <?php endif; ?>',
            $content
        );
        echo "✅ Wrapped Edit button in $file\n";
        $modified = true;
    }
    
    // Pattern 4: Wrap Delete button in table rows
    $pattern_delete = '/<a href="<\?= base_url\(\'([^\']+)\/hapus\/\' \. \$([^\)]+)\[\'([^\']+)\'\]\); \?>" class="btn btn-danger btn-xs(.*?)title="Hapus">\s*<i class="fas fa-trash(.*?)<\/i>\s*<\/a>/s';
    if (preg_match($pattern_delete, $content) && !preg_match('/\<\?php if \(can_delete\(\)\): \?>.*?btn-danger/s', $content)) {
        $content = preg_replace(
            $pattern_delete,
            '<?php if (can_delete()): ?>
                                            <a href="<?= base_url(\'$1/hapus/\' . $$2[\'$3\']); ?>" class="btn btn-danger btn-xs$4title="Hapus">
                                                <i class="fas fa-trash$5</i>
                                            </a>
                                            <?php endif; ?>',
            $content
        );
        echo "✅ Wrapped Delete button in $file\n";
        $modified = true;
    }
    
    if ($modified && $content !== $original) {
        file_put_contents($path, $content);
        echo "💾 Saved $file\n\n";
    } else {
        echo "ℹ️  No changes needed for $file\n\n";
    }
}

echo "\n✨ Mass view protection completed!\n";
