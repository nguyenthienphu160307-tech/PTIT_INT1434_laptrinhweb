<?php
require __DIR__ . '/includes/init.php';

unset($_SESSION['user']);
session_regenerate_id(true);

set_flash('success', 'Bạn đã đăng xuất.');
redirect('index.php');
