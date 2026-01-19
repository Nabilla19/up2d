<?php
/**
 * Mass Protection Injector
 * Run once to inject protection code to all controllers
 * 
 * Usage: php mass_inject_protection.php
 */

$controllers = [
    'Gh_cell.php',
    'Pembangkit.php',
    'Kit_cell.php',
    'Pemutus.php',
    'Sop.php',
    'Bpm.php',
    'Ik.php',
    'Road_map.php',
    'Spln.php',
    'Single_line_diagram.php',
    'Pengaduan.php',
];

$controller_dir = dirname(__DIR__) . '/application/controllers/';

foreach ($controllers as $file) {
    $path = $controller_dir . $file;
    
    if (!file_exists($path)) {
        echo "⚠️  File not found: $file\n";
        continue;
    }
    
    $content = file_get_contents($path);
    $modified = false;
    
    // Inject can_create() protection
    if (preg_match('/public function (tambah|create)\(\)\s*\{/', $content)) {
        $content = preg_replace(
            '/(public function (tambah|create)\(\)\s*\{)/',
            "$1\n        if (!can_create()) {\n            \$this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menambah data');\n            redirect(\$this->router->fetch_class());\n        }\n",
            $content,
            1,
            $count
        );
        if ($count > 0) {
            echo "✅ Injected can_create() in $file\n";
            $modified = true;
        }
    }
    
    // Inject can_edit() protection
    if (preg_match('/public function (edit|update)\([^)]*\)\s*\{/', $content)) {
        $content = preg_replace(
            '/(public function (edit|update)\(([^)]*)\)\s*\{)/',
            "$1\n        if (!can_edit()) {\n            \$this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengubah data');\n            redirect(\$this->router->fetch_class());\n        }\n",
            $content,
            -1,
            $count
        );
        if ($count > 0) {
            echo "✅ Injected can_edit() in $file ($count methods)\n";
            $modified = true;
        }
    }
    
    // Inject can_delete() protection
    if (preg_match('/public function (hapus|delete)\([^)]*\)\s*\{/', $content)) {
        $content = preg_replace(
            '/(public function (hapus|delete)\(([^)]*)\)\s*\{)/',
            "$1\n        if (!can_delete()) {\n            \$this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menghapus data');\n            redirect(\$this->router->fetch_class());\n        }\n",
            $content,
            1,
            $count
        );
        if ($count > 0) {
            echo "✅ Injected can_delete() in $file\n";
            $modified = true;
        }
    }
    
    if ($modified) {
        file_put_contents($path, $content);
        echo "💾 Saved $file\n\n";
    } else {
        echo "ℹ️  No changes needed for $file\n\n";
    }
}

echo "\n✨ Mass injection completed!\n";
