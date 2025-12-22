<?php
session_start();
require_once "../model/connect.php";

// Kiểm tra đăng nhập và vai trò admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? null) !== 'admin') {
    header("Location: ../user/login.php");
    exit();
}
require_once 'header.php';

// Lấy thống kê
$stats_orders = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc();
$stats_revenue = $conn->query("SELECT SUM(total) as revenue FROM orders WHERE status = 1")->fetch_assoc();
$stats_products = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc();
$stats_users = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 0")->fetch_assoc();
$stats_pending = $conn->query("SELECT COUNT(*) as total FROM orders WHERE status = 0")->fetch_assoc();

// Lấy danh sách đơn hàng mới
$orders=$conn->query("SELECT o.*, u.fullname FROM orders o JOIN users u ON o.user_id=u.id ORDER BY o.id DESC LIMIT 5");
?>

<!-- Thống kê Dashboard -->
<div class="row mb-4">
  <div class="col-md-3">
    <div class="card bg-primary text-white">
      <div class="card-body">
        <h5 class="card-title">Tổng đơn hàng</h5>
        <h2><?= $stats_orders['total'] ?? 0 ?></h2>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="card bg-success text-white">
      <div class="card-body">
        <h5 class="card-title">Doanh thu</h5>
        <h2><?= number_format($stats_revenue['revenue'] ?? 0) ?> ₫</h2>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="card bg-warning text-white">
      <div class="card-body">
        <h5 class="card-title">Đơn chờ xử lý</h5>
        <h2><?= $stats_pending['total'] ?? 0 ?></h2>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="card bg-info text-white">
      <div class="card-body">
        <h5 class="card-title">Sản phẩm</h5>
        <h2><?= $stats_products['total'] ?? 0 ?></h2>
      </div>
    </div>
  </div>
</div>

<!-- Danh sách đơn hàng mới -->
<div class="card mt-4">
  <div class="card-header bg-dark text-white">
    <h5 class="mb-0">Đơn hàng gần đây</h5>
  </div>
  <div class="card-body">
    <table class="table table-striped table-bordered align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Khách hàng</th>
          <th>Tổng tiền</th>
          <th>Ngày tạo</th>
          <th>Trạng thái</th>
          <th>Chi tiết</th>
        </tr>
      </thead>
      <tbody>
      <?php while($o=$orders->fetch_assoc()): ?>
      <tr>
        <td><?=$o['id']?></td>
        <td><?=htmlspecialchars($o['fullname'])?></td>
        <td><?=number_format($o['total'] ?? 0)?> ₫</td>
        <td><?=$o['created_at'] ?? 'N/A'?></td>
        <td><?=($o['status']==1?'<span class="badge bg-success">Hoàn thành</span>':($o['status']==0?'<span class="badge bg-warning">Chờ xử lý</span>':'<span class="badge bg-danger">Hủy</span>'))?></td>
        <td><a href="view-order.php?id=<?=$o['id']?>" class="btn btn-sm btn-info">Xem</a></td>
      </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
    <a href="orders.php" class="btn btn-primary">Xem tất cả đơn hàng</a>
  </div>
</div>

<?php require_once 'footer.php'; ?>
