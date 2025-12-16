<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "fashion_mylishop";

// Tạo kết nối
$conn = mysqli_connect($host, $user, $password, $database);
mysqli_set_charset($conn, 'UTF8');

// Kiểm tra kết nối
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// echo "Connected successfully";
?>
