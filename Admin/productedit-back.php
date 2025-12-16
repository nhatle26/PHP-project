<?php
// admin/productedit-back.php
session_start();
require_once '../model/connect.php';
require_once 'auth.php';
requireAdmin();

$id = intval($_GET['idProduct'] ?? 0);
if ($id <= 0) {
    $_SESSION['error'] = "ID không hợp lệ.";
    header("Location: product-list.php");
    exit();
}

if (!isset($_POST['editProduct'])) {
    header("Location: product-edit.php?idProduct=$id");
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
    header("Location: product-edit.php?idProduct=$id");
    exit();
}

// Lấy current image (nếu cần gỡ)
$stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$current = $stmt->get_result()->fetch_assoc();
$stmt->close();
$currentImage = $current['image'] ?? '';

// Xử lý file ảnh (nếu upload mới)
$imagePath = $currentImage;
if (isset($_FILES['FileImage']) && $_FILES['FileImage']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['FileImage'];
    $maxSize = 3 * 1024 * 1024;
    if ($file['size'] > $maxSize) {
        $_SESSION['error'] = "Ảnh quá lớn (<=3MB).";
        header("Location: product-edit.php?idProduct=$id");
        exit();
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    $allowed = ['image/jpeg','image/png','image/gif'];
    if (!in_array($mime, $allowed)) {
        $_SESSION['error'] = "Chỉ chấp nhận JPG/PNG/GIF.";
        header("Location: product-edit.php?idProduct=$id");
        exit();
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8));
    $targetDir = __DIR__ . '/../uploads/products/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
    $targetName = $basename . '.' . $ext;
    $targetPath = $targetDir . $targetName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        $_SESSION['error'] = "Lưu file ảnh thất bại.";
        header("Location: product-edit.php?idProduct=$id");
        exit();
    }

    // xóa file cũ nếu có
    if (!empty($currentImage) && file_exists(__DIR__ . '/../' . $currentImage)) {
        @unlink(__DIR__ . '/../' . $currentImage);
    }

    $imagePath = 'uploads/products/' . $targetName;
}

// Update query
$sql = "UPDATE products SET category_id = ?, image = ?, name = ?, price = ?, saleprice = ?, quantity = ?, keyword = ?, description = ?, status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    $_SESSION['error'] = "Lỗi hệ thống (prepare).";
    header("Location: product-edit.php?idProduct=$id");
    exit();
}
$stmt->bind_param("issdiissii", $category, $imagePath, $name, $price, $sale, $quantity, $keyword, $description, $status, $id);
$ok = $stmt->execute();
$stmt->close();

if ($ok) {
    $_SESSION['success'] = "Cập nhật sản phẩm thành công.";
    header("Location: product-list.php");
    exit();
} else {
    $_SESSION['error'] = "Cập nhật thất bại.";
    header("Location: product-edit.php?idProduct=$id");
    exit();
}
