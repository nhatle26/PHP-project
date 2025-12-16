<?php
session_start();
require_once "../model/connect.php";

if (!isset($_POST['submit'])) {
    header("Location: login.php");
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if (!$username || !$password) {
    $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
    header("Location: login.php");
    exit();
}

// Lấy user từ DB
$stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// So sánh trực tiếp (password thường)
if (!$user || $password !== $user['password']) {
    $_SESSION['error'] = "Sai tài khoản hoặc mật khẩu!";
    header("Location: login.php");
    exit();
}

// Lưu session
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'];

// Redirect sau login
header("Location: ../index.php");
exit();
