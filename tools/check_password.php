<?php
// Quick password verification helper
$password = 'perencanaan123';
$hash = '$2y$10$wH6YQf1yGk4c8Qp0qW8/1e8k2b0xJQY5mQp0lH5zI7KfXx1aYfO8K';

echo "Checking password verification...\n";
$ok = password_verify($password, $hash);
echo $ok ? "MATCH\n" : "NO MATCH\n";
echo "Hash: $hash\n";
