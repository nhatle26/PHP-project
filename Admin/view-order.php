<?php
require_once 'header.php';

if (!isset($_GET['id'])) {
    echo '<p class="alert alert-danger">Đơn hàng không tồn tại!</p>';
    exit();
}

$orderId = (int) $_GET['id'];

/* ===== LẤY THÔNG TIN ĐƠN + USER ===== */
$stmt = $conn->prepare("
    SELECT o.*, u.username
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    WHERE o.id = ?
");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo '<p class="alert alert-danger">Đơn hàng không tồn tại!</p>';
    exit();
}

/* ===== LẤY SẢN PHẨM TRONG ĐƠN ===== */
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

<div class="d-flex justify-content-between align-items-center mb-4">
  <h3>Chi tiết đơn hàng #<?= $orderId ?></h3>
</div>

<?php
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">'.htmlspecialchars($_SESSION['success']).'<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'.htmlspecialchars($_SESSION['error']).'<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    unset($_SESSION['error']);
}
?>

<div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col-md-6">
        <p><strong>User:</strong> <?= htmlspecialchars($order['username'] ?? 'N/A') ?></p>
        <p><strong>Ngày đặt:</strong> <?= $order['date_order'] ?></p>
        <p><strong>Thanh toán:</strong>
            <?php if ($order['payment_method'] === 'bank'): ?>
                <span class="badge bg-info">
                    Chuyển khoản (<?= htmlspecialchars($order['bank_name']) ?>)
                </span>
            <?php else: ?>
                <span class="badge bg-secondary">COD</span>
            <?php endif; ?>
        </p>
        <p><strong>Trạng thái:</strong>
            <?php
            if ($order['status'] == 0) echo '<span class="badge bg-warning">Chờ xử lý</span>';
            elseif ($order['status'] == 1) echo '<span class="badge bg-success">Hoàn thành</span>';
            else echo '<span class="badge bg-danger">Hủy</span>';
            ?>
        </p>
      </div>
      
      <div class="col-md-6">
        <?php if ($order['payment_method'] === 'bank' && !empty($order['transfer_image'])): ?>
            <p><strong>Ảnh chuyển khoản:</strong></p>
            <img src="../<?= htmlspecialchars($order['transfer_image']) ?>"
                 class="img-fluid"
                 style="max-height:200px">
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<hr>

<h5>Sản phẩm trong đơn</h5>
<table class="table table-bordered align-middle">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Sản phẩm</th>
            <th>SL</th>
            <th>Giá</th>
            <th>Thành tiền</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        $sum = 0;
        while ($row = $items->fetch_assoc()):
            $sub = $row['quantity'] * $row['price'];
            $sum += $sub;
        ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= $row['quantity'] ?></td>
            <td><?= number_format($row['price']) ?> ₫</td>
            <td><?= number_format($sub) ?> ₫</td>
        </tr>
        <?php endwhile; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4" class="text-end">Tổng cộng (tính lại)</th>
            <th class="text-danger"><?= number_format($sum) ?> ₫</th>
        </tr>
        <tr>
            <th colspan="4" class="text-end">Tổng lưu trong orders</th>
            <th><?= number_format($order['total']) ?> ₫</th>
        </tr>
    </tfoot>
</table>

<!-- Nút Đồng ý / Từ chối -->
<?php if ($order['status'] == 0): ?>
<div class="mt-4 d-flex gap-2">
    <form method="POST" action="update-order-status.php" style="display:inline;">
        <input type="hidden" name="order_id" value="<?= $orderId ?>">
        <input type="hidden" name="status" value="1">
        <button type="submit" class="btn btn-success btn-lg">✓ Đồng ý</button>
    </form>
    
    <form method="POST" action="update-order-status.php" style="display:inline;">
        <input type="hidden" name="order_id" value="<?= $orderId ?>">
        <input type="hidden" name="status" value="2">
        <button type="submit" class="btn btn-danger btn-lg" onclick="return confirm('Bạn chắc chắn từ chối đơn hàng này?');">✗ Từ chối</button>
    </form>
</div>
<?php else: ?>
<div class="mt-4">
    <p class="alert alert-info">Đơn hàng này đã được xử lý, không thể thay đổi trạng thái.</p>
</div>
<?php endif; ?>

<?php require_once 'footer.php'; ?>