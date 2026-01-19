<?php
if (file_exists($_SERVER['SCRIPT_FILENAME'])) {
    return false;
}
$_SERVER['SCRIPT_NAME'] = '/index.php';
include __DIR__ . '/index.php';
