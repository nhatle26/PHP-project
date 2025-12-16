<?php
// D:\PHP\php project\Admin\view-order.php
require_once __DIR__ . '/../model/connect.php'; // đảm bảo đúng đường dẫn

if (!isset($_GET['id'])) {
    echo "<p>Đơn hàng không tồn tại!</p>";
    exit();
}

$orderId = (int)$_GET['id'];

// Lấy thông tin đơn hàng + user
$stmt = $conn->prepare("
    SELECT o.id, o.user_id, o.total, o.status, o.date_order, u.username
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    WHERE o.id = ?
");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo "<p>Đơn hàng không tồn tại!</p>";
    exit();
}

// Lấy chi tiết sản phẩm
$stmtItems = $conn->prepare("
    SELECT p.name, oi.quantity, oi.price
    FROM order_items oi
    LEFT JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmtItems->bind_param("i", $orderId);
$stmtItems->execute();
$items = $stmtItems->get_result();
?>

<div>
    <p><strong>User:</strong> <?= htmlspecialchars($order['username']) ?></p>
    <p><strong>Ngày đặt:</strong> <?= $order['date_order'] ?></p>
    <p><strong>Tổng tiền:</strong> <?= number_format($order['total']) ?> ₫</p>
    <p><strong>Trạng thái:</strong> 
        <?php
            if ($order['status'] == 0) echo '<span class="badge bg-warning">Chờ xử lý</span>';
            elseif ($order['status'] == 1) echo '<span class="badge bg-success">Hoàn thành</span>';
            elseif ($order['status'] == 2) echo '<span class="badge bg-danger">Hủy</span>';
        ?>
    </p>

    <h5>Sản phẩm trong đơn</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php $i=1; while($row=$items->fetch_assoc()): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= $row['quantity'] ?></td>
                <td><?= number_format($row['price']) ?> ₫</td>
                <td><?= number_format($row['quantity'] * $row['price']) ?> ₫</td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
