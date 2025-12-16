<?php
session_start();

// Nếu giỏ hàng chưa tồn tại thì tạo mảng rỗng
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Xóa sản phẩm
if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
    // Chuyển về trang giỏ hàng
    header("Location: view-cart.php");
    exit();
}

// Cập nhật số lượng
if (isset($_POST['update'])) {
    foreach ($_POST['quantity'] as $id => $qty) {
        $qty = max(1, (int)$qty); // Số lượng tối thiểu là 1
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] = $qty;
        }
    }
    // Chuyển về trang giỏ hàng
    header("Location: view-cart.php");
    exit();
}

// Nếu không phải xóa hay cập nhật thì cũng trở về view-cart
header("Location: view-cart.php");
exit();
