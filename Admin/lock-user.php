<?php
session_start();
require_once "../model/connect.php";

// Kiểm tra đăng nhập và vai trò admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? null) !== 'admin') {
    header("Location: ../user/login.php");
    exit();
}

// Kiểm tra dữ liệu
if (!isset($_POST['user_id']) || !isset($_POST['action'])) {
    $_SESSION['error'] = "Dữ liệu không hợp lệ!";
    header("Location: users.php");
    exit();
}

$user_id = (int)$_POST['user_id'];
$action = $_POST['action'];

// Kiểm tra action hợp lệ
if (!in_array($action, ['lock', 'unlock'])) {
    $_SESSION['error'] = "Hành động không hợp lệ!";
    header("Location: users.php");
    exit();
}

// Không cho phép khóa chính mình
if ($user_id == $_SESSION['user_id']) {
    $_SESSION['error'] = "Bạn không thể khóa tài khoản của chính mình!";
    header("Location: users.php");
    exit();
}

// Xác định giá trị is_locked
$is_locked = ($action === 'lock') ? 1 : 0;

// Update trạng thái khóa
$stmt = $conn->prepare("UPDATE users SET is_locked = ? WHERE id = ?");
$stmt->bind_param("ii", $is_locked, $user_id);

if ($stmt->execute()) {
    if ($action === 'lock') {
        $_SESSION['success'] = "✓ Tài khoản đã bị khóa!";
    } else {
        $_SESSION['success'] = "✓ Tài khoản đã được mở khóa!";
    }
} else {
    $_SESSION['error'] = "Lỗi cập nhật trạng thái!";
}

header("Location: users.php");
exit();
