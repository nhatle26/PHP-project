<?php
session_start();
require_once "model/connect.php";

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Lấy thông tin sản phẩm
    $stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if ($product) {
        // Nếu sản phẩm đã có trong giỏ, tăng số lượng
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1
            ];
        }
    }
}

header("Location: view-cart.php");
exit();
?>
