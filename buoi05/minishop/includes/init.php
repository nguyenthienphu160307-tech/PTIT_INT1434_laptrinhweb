<?php

require_once __DIR__ . '/../config/config.php';

error_reporting(E_ALL);
ini_set('display_errors', APP_DEBUG ? '1' : '0');
ini_set('log_errors', '1');
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../config/db.php';

// Mọi yêu cầu POST đều phải kèm mã CSRF hợp lệ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
}
