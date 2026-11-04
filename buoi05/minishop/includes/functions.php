<?php

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
function format_price($amount)
{
    return number_format((int) $amount, 0, ',', '.') . ' đ';
}

function url($path = '')
{
    return BASE_URL . ltrim($path, '/');
}

function redirect($path)
{
    header('Location: ' . url($path));
    exit;
}

// $type là 'success' hoặc 'error'
function set_flash($type, $message)
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash()
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

// ===== 3. Tài khoản =====

function current_user()
{
    return $_SESSION['user'] ?? null;
}

function is_logged_in()
{
    return current_user() !== null;
}

function is_admin()
{
    return is_logged_in() && current_user()['role'] === 'admin';
}

function require_login()
{
    if (!is_logged_in()) {
        set_flash('error', 'Vui lòng đăng nhập để tiếp tục.');
        redirect('login.php');
    }
}

function cart_count()
{
    return array_sum($_SESSION['cart'] ?? []);
}

function get_cart_items($pdo)
{
    $cart = $_SESSION['cart'] ?? [];
    if (!$cart) {
        return [[], 0];
    }

    $placeholders = implode(',', array_fill(0, count($cart), '?'));
    $stmt = $pdo->prepare("SELECT id, name, price, image, stock FROM products WHERE id IN ($placeholders)");
    $stmt->execute(array_keys($cart));

    $items = [];
    $total = 0;
    foreach ($stmt->fetchAll() as $product) {
        $quantity = $cart[$product['id']];
        $product['quantity'] = $quantity;
        $product['line_total'] = $product['price'] * $quantity;
        $total += $product['line_total'];
        $items[] = $product;
    }
    return [$items, $total];
}

// ===== 5. Đơn hàng =====

function order_statuses()
{
    return [
        'pending'   => 'Chờ xử lý',
        'shipping'  => 'Đang giao',
        'completed' => 'Hoàn tất',
        'cancelled' => 'Đã hủy',
    ];
}

function order_status_label($status)
{
    return order_statuses()[$status] ?? $status;
}

// ===== 6. Quản trị =====
function require_admin()
{
    require_login();
    if (!is_admin()) {
        http_response_code(403);
        exit('Bạn không có quyền truy cập trang này.');
    }
}

// ===== 7. Tải ảnh lên =====
function upload_image($file)
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Tải tệp lên thất bại (mã lỗi ' . $file['error'] . ').');
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        throw new RuntimeException('Ảnh không được lớn hơn 2 MB.');
    }
    $info = getimagesize($file['tmp_name']);
    $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if ($info === false || !isset($extensions[$info['mime']])) {
        throw new RuntimeException('Chỉ chấp nhận ảnh JPG, PNG hoặc WebP.');
    }
    $name = bin2hex(random_bytes(8)) . '.' . $extensions[$info['mime']];
    if (!move_uploaded_file($file['tmp_name'], __DIR__ . '/../uploads/' . $name)) {
        throw new RuntimeException('Không lưu được tệp. Kiểm tra quyền ghi của thư mục uploads.');
    }
    return 'uploads/' . $name;
}

// ===== 8. Chống giả mạo yêu cầu (CSRF) =====

function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function csrf_verify()
{
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals(csrf_token(), $token)) {
        http_response_code(403);
        exit('Yêu cầu không hợp lệ. Vui lòng quay lại, tải lại trang và thử lại.');
    }
}
