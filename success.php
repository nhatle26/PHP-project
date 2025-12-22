<?php
require_once "model/connect.php";
$order_id = $_GET['order_id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM orders WHERE id=?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8">
<title>Đặt hàng thành công</title>

<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
.success-box {
    max-width: 600px;
    margin: 60px auto;
    background: #fff;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,.1);
    text-align: center;
}
.success-box i {
    font-size: 80px;
    color: #28a745;
}
.success-box h2 {
    margin-top: 15px;
    font-weight: 600;
}
.order-info {
    text-align: left;
    margin-top: 25px;
}
.order-info p {
    font-size: 16px;
}
</style>
</head>

<body>

<?php include "model/header.php"; ?>

<div class="success-box">
    <i class="fa fa-check-circle"></i>
    <h2>Đặt hàng thành công</h2>
    <p>Cảm ơn bạn đã mua sắm tại <b>Fashion MyLiShop</b></p>

    <div class="order-info">
        <p><b>Mã đơn:</b> #<?= $order['id'] ?></p>
        <p><b>Tổng tiền:</b> <?= number_format($order['total']) ?> đ</p>
        <p><b>Thanh toán:</b> <?= strtoupper($order['payment_method']) ?></p>
    </div>

    <a href="index.php" class="btn btn-yellow" style="margin-top:20px;">
        Về trang chủ
    </a>
</div>

<?php include "model/footer.php"; ?>
</body>
</html>
