<?php
require_once 'header.php';

// Lấy danh sách đơn hàng
$stm = $conn->prepare("
    SELECT o.id, o.total, o.status, o.payment_method, o.bank_name, o.date_order,
           u.username
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    ORDER BY o.id DESC
");
$stm->execute();
$res = $stm->get_result();
?>

<div class="container mt-4">
    <h3 class="mb-3">Danh sách đơn hàng</h3>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Tổng tiền</th>
                <th>Thanh toán</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $res->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['username'] ?? 'N/A') ?></td>
                <td><?= number_format($row['total']) ?> ₫</td>
                <td>
                    <?php if ($row['payment_method'] === 'bank'): ?>
                        <span class="badge bg-info">
                            CK (<?= htmlspecialchars($row['bank_name']) ?>)
                        </span>
                    <?php else: ?>
                        <span class="badge bg-secondary">COD</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php
                    if ($row['status'] == 0) echo '<span class="badge bg-warning">Chờ xử lý</span>';
                    elseif ($row['status'] == 1) echo '<span class="badge bg-success">Hoàn thành</span>';
                    else echo '<span class="badge bg-danger">Hủy</span>';
                    ?>
                </td>
                <td><?= $row['date_order'] ?></td>
                <td>
                    <button class="btn btn-sm btn-info view-order"
                            data-id="<?= $row['id'] ?>">
                        Xem
                    </button>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="orderModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Chi tiết đơn hàng</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="text-center">Đang tải...</p>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
    $('.view-order').click(function () {
        let id = $(this).data('id');

        $('#orderModal .modal-body').html('Đang tải...');
        $('#orderModal').modal('show');

        $.get('view-order.php', {id: id}, function (html) {
            $('#orderModal .modal-body').html(html);
        }).fail(function () {
            $('#orderModal .modal-body').html(
                '<p class="text-danger">Không tải được dữ liệu</p>'
            );
        });
    });
});
</script>

<?php require_once 'footer.php'; ?>
