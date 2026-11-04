<?php
require __DIR__ . '/includes/init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('cart.php');
}

if (isset($_POST['clear'])) {
    unset($_SESSION['cart']);
} elseif (isset($_POST['remove'])) {
    unset($_SESSION['cart'][(int) $_POST['remove']]);
} else {
    $quantities = $_POST['qty'] ?? [];
    if (is_array($quantities)) {
        foreach ($quantities as $productId => $quantity) {
            $productId = (int) $productId;
            $quantity = (int) $quantity;
            if (!isset($_SESSION['cart'][$productId])) {
                continue;
            }
            if ($quantity < 1) {
                unset($_SESSION['cart'][$productId]);
            } else {
                $_SESSION['cart'][$productId] = min($quantity, 99);
            }
        }
    }
}

set_flash('success', 'Đã cập nhật giỏ hàng.');
redirect('cart.php');
