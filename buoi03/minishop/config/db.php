<?php
// kết nối database
$conn = mysqli_connect("localhost", "root", "", "minishop");

if (!$conn) {
    die("Lỗi kết nối: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
