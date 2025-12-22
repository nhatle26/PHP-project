<?php
session_start();
require_once "model/connect.php";

$order_id = $_GET['vnp_TxnRef'] ?? 0;
$responseCode = $_GET['vnp_ResponseCode'] ?? '';

if ($responseCode === '00') {
    // Thành công
    $stmt = $conn->prepare("
        UPDATE orders 
        SET status = 1 
        WHERE id = ?
    ");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();

    unset($_SESSION['cart']);
    header("Location: success.php?order_id=$order_id");
} else {
    header("Location: checkout.php?error=payment_failed");
}
exit();
