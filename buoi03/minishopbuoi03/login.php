<?php
include 'config/db.php';

$title = "Đăng nhập";
include 'includes/header.php';
?>

<h2>Đăng nhập</h2>

<form action="login.php" method="post" class="form">
    <p>
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" required>
    </p>
    <p>
        <label for="password">Mật khẩu</label><br>
        <input type="password" id="password" name="password" required>
    </p>
    <p>
        <input type="checkbox" id="remember" name="remember" value="1">
        <label for="remember">Ghi nhớ đăng nhập</label>
    </p>
    <p>
        <button type="submit" class="btn">Đăng nhập</button>
    </p>
    <p>
        Chưa có tài khoản? <a href="register.php">Đăng ký</a>
    </p>
</form>

<?php include 'includes/footer.php'; ?>
