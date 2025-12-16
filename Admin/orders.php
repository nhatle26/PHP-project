<?php
require_once 'header.php';
require_once __DIR__ . '/../model/connect.php'; // kết nối database

// Lấy danh sách đơn hàng
$stm = $conn->prepare("
    SELECT o.id, u.username, o.total, o.status, o.date_order
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    ORDER BY o.id DESC
");
$stm->execute();
$res = $stm->get_result();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Danh sách đơn hàng</h3>
</div>

<?php
if (isset($_SESSION['success'])) { 
    echo '<div class="alert alert-success">'.htmlspecialchars($_SESSION['success']).'</div>'; 
    unset($_SESSION['success']); 
}
if (isset($_SESSION['error'])) { 
    echo '<div class="alert alert-danger">'.htmlspecialchars($_SESSION['error']).'</div>'; 
    unset($_SESSION['error']); 
}
?>

<table class="table table-striped table-bordered align-middle">
  <thead>
    <tr>
      <th>#</th>
      <th>User</th>
      <th>Tổng tiền</th>
      <th>Trạng thái</th>
      <th>Ngày tạo</th>
      <th>Hành động</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['id']) ?></td>
        <td><?= htmlspecialchars($row['username']) ?></td>
        <td><?= number_format($row['total']) ?> ₫</td>
        <td>
            <?php
                if ($row['status'] == 0) echo '<span class="badge bg-warning">Chờ xử lý</span>';
                elseif ($row['status'] == 1) echo '<span class="badge bg-success">Hoàn thành</span>';
                elseif ($row['status'] == 2) echo '<span class="badge bg-danger">Hủy</span>';
            ?>
        </td>
        <td><?= $row['date_order'] ?></td>
        <td>
          <button class="btn btn-sm btn-info view-order-btn" data-id="<?= $row['id'] ?>">Xem chi tiết</button>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<!-- Modal -->
<div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="orderDetailModalLabel">Chi tiết đơn hàng</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.view-order-btn').click(function() {
        var orderId = $(this).data('id');
        $.ajax({
            url: 'view-order.php',
            method: 'GET',
            data: { id: orderId },
            success: function(response) {
                $('#orderDetailModal .modal-body').html(response);
                var myModal = new bootstrap.Modal(document.getElementById('orderDetailModal'));
                myModal.show();
            },
            error: function() {
                alert('Không thể tải chi tiết đơn hàng!');
            }
        });
    });
});
</script>

<?php require_once 'footer.php'; ?>
