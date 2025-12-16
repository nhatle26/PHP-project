<?php
// admin/productadd-back.php
session_start();
require_once '../model/connect.php';
require_once 'auth.php';
requireAdmin();

if (!isset($_POST['addProduct'])) {
    header("Location: product-add.php");
    exit();
}

// Sanitize
$name       = trim($_POST['txtName'] ?? '');
$category   = intval($_POST['category'] ?? 0);
$price      = floatval($_POST['txtPrice'] ?? 0);
$sale       = intval($_POST['txtSalePrice'] ?? 0);
$quantity   = intval($_POST['txtNumber'] ?? 0);
$keyword    = trim($_POST['txtKeyword'] ?? '');
$description= trim($_POST['txtDescript'] ?? '');
$status     = intval($_POST['status'] ?? 0);

if ($name === '' || $category <= 0 || $price <= 0) {
    $_SESSION['error'] = "Tên, danh mục và giá là bắt buộc.";
    header("Location: product-add.php");
    exit();
}

// Image handling
if (!isset($_FILES['FileImage']) || $_FILES['FileImage']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['error'] = "Vui lòng chọn ảnh hợp lệ.";
    header("Location: product-add.php");
    exit();
}

$file = $_FILES['FileImage'];
$maxSize = 3 * 1024 * 1024; // 3MB
if ($file['size'] > $maxSize) {
    $_SESSION['error'] = "Ảnh quá lớn (<=3MB).";
    header("Location: product-add.php");
    exit();
}

// MIME check
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);
$allowed = ['image/jpeg','image/png','image/gif'];
if (!in_array($mime, $allowed)) {
    $_SESSION['error'] = "Chỉ chấp nhận JPG/PNG/GIF.";
    header("Location: product-add.php");
    exit();
}

$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$basename = bin2hex(random_bytes(8));
$targetDir = __DIR__ . '/../uploads/products/';
if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
$targetName = $basename . '.' . $ext;
$targetPath = $targetDir . $targetName;

if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    $_SESSION['error'] = "Lỗi lưu file ảnh.";
    header("Location: product-add.php");
    exit();
}
$imagePath = 'uploads/products/' . $targetName;

// Insert
$sql = "INSERT INTO products (category_id, image, name, price, saleprice, quantity, keyword, description, status, created)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    $_SESSION['error'] = "Lỗi hệ thống (prepare).";
    header("Location: product-add.php");
    exit();
}
$stmt->bind_param("issdiissi", $category, $imagePath, $name, $price, $sale, $quantity, $keyword, $description, $status);
$ok = $stmt->execute();
$stmt->close();

if ($ok) {
    $_SESSION['success'] = "Thêm sản phẩm thành công.";
    header("Location: product-list.php");
    exit();
} else {
    // xóa file nếu insert fail
    @unlink($targetPath);
    $_SESSION['error'] = "Thêm sản phẩm thất bại.";
    header("Location: product-add.php");
    exit();
}
