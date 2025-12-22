<?php
session_start();
require_once __DIR__ . '/../model/connect.php';

$user_id = $_SESSION['user_id'] ?? null;
$orderId = (int)($_GET['id'] ?? 0);

if (!$user_id || !$orderId) {
    header("Location: orders.php");
    exit();
}

/* LẤY ĐƠN HÀNG – CHỈ CỦA USER */
$stmt = $conn->prepare("
    SELECT *
    FROM orders
    WHERE id = ? AND user_id = ?
");
$stmt->bind_param("ii", $orderId, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo "Đơn hàng không tồn tại";
    exit();
}

/* SẢN PHẨM */
$stmtItems = $conn->prepare("
    SELECT p.name, oi.quantity, oi.price
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmtItems->bind_param("i", $orderId);
$stmtItems->execute();
$items = $stmtItems->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn #<?= $orderId ?></title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>

<?php include "../model/header.php"; ?>

<div class="container mt-4">
    <h3>Chi tiết đơn #<?= $orderId ?></h3>

    <p><strong>Thanh toán:</strong>
        <?= $order['payment_method'] === 'bank' ? 'Chuyển khoản' : 'COD' ?>
    </p>

    <?php if ($order['payment_method'] === 'bank' && $order['transfer_image']): ?>
        <p><strong>Ảnh chuyển khoản:</strong></p>
        <img src="../uploads/<?= htmlspecialchars($order['transfer_image']) ?>"
             style="max-width:300px"
             class="img-fluid rounded mb-3">
    <?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>SL</th>
                <th>Giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
        <?php $sum = 0; while ($row = $items->fetch_assoc()):
            $sub = $row['quantity'] * $row['price'];
            $sum += $sub;
        ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= $row['quantity'] ?></td>
                <td><?= number_format($row['price']) ?> ₫</td>
                <td><?= number_format($sub) ?> ₫</td>
            </tr>
        <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Tổng</th>
                <th class="text-danger"><?= number_format($sum) ?> ₫</th>
            </tr>
        </tfoot>
    </table>
</div>

<?php include "../model/footer.php"; ?>
</body>
</html>
