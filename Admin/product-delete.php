<?php
// admin/product-delete.php
session_start();
require_once '../model/connect.php';
require_once '../model/auth.php';

$id = intval($_GET['idProducts'] ?? 0);
if ($id <= 0) {
    $_SESSION['error'] = "ID không hợp lệ.";
    header("Location: product-list.php");
    exit();
}

// lấy image để xóa file
$stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$ok = $stmt->execute();
$stmt->close();

if ($ok) {
    if (!empty($row['image']) && file_exists(__DIR__ . '/../' . $row['image'])) {
        @unlink(__DIR__ . '/../' . $row['image']);
    }
    $_SESSION['success'] = "Xóa sản phẩm thành công.";
} else {
    $_SESSION['error'] = "Xóa sản phẩm thất bại.";
}
header("Location: product-list.php");
exit();
