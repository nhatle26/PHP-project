<?php
session_start();
require_once __DIR__ . '/../model/connect.php';

// Kiểm tra đăng nhập và vai trò admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? null) !== 'admin') {
    header("Location: ../user/login.php");
    exit();
}

// Kiểm tra dữ liệu
if (!isset($_POST['order_id']) || !isset($_POST['status'])) {
    $_SESSION['error'] = "Dữ liệu không hợp lệ!";
    header("Location: orders.php");
    exit();
}

$order_id = (int)$_POST['order_id'];
$status = (int)$_POST['status'];

// Kiểm tra status hợp lệ (0=chờ, 1=hoàn thành, 2=hủy)
if (!in_array($status, [0, 1, 2])) {
    $_SESSION['error'] = "Trạng thái không hợp lệ!";
    header("Location: view-order.php?id=$order_id");
    exit();
}

// Update trạng thái đơn hàng
$stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
$stmt->bind_param("ii", $status, $order_id);

if ($stmt->execute()) {
    // Thông báo theo trạng thái
    if ($status == 1) {
        $_SESSION['success'] = "Đơn hàng đã được duyệt!";
    } elseif ($status == 2) {
        $_SESSION['success'] = "Đơn hàng đã bị từ chối!";
    } else {
        $_SESSION['success'] = "Trạng thái đã cập nhật!";
    }
} else {
    $_SESSION['error'] = "Lỗi cập nhật trạng thái!";
}

header("Location: view-order.php?id=$order_id");
exit();
