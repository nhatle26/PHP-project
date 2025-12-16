<?php
require_once 'header.php';

$stm = $conn->prepare("SELECT id, name, category_id, image, price, saleprice, quantity, status FROM products ORDER BY id DESC");
$stm->execute();
$res = $stm->get_result();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Danh sách sản phẩm</h3>
  <a href="product-add.php" class="btn btn-success">+ Thêm sản phẩm</a>
</div>

<?php
if (isset($_SESSION['success'])) { echo '<div class="alert alert-success">'.htmlspecialchars($_SESSION['success']).'</div>'; unset($_SESSION['success']); }
if (isset($_SESSION['error'])) { echo '<div class="alert alert-danger">'.htmlspecialchars($_SESSION['error']).'</div>'; unset($_SESSION['error']); }
?>

<table class="table table-striped table-bordered align-middle">
  <thead>
    <tr>
      <th>#</th>
      <th>Tên</th>
      <th>Danh mục</th>
      <th>Ảnh</th>
      <th>Giá</th>
      <th>Giảm</th>
      <th>Số lượng</th>
      <th>Trạng thái</th>
      <th>Hành động</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['id']) ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['category_id']) ?></td>
        <td>
          <?php if (!empty($row['image'])): ?>
            <img src="../<?= htmlspecialchars($row['image']) ?>" class="product-thumb border">
          <?php else: ?>
            ---
          <?php endif; ?>
        </td>
        <td><?= number_format($row['price']) ?> ₫</td>
        <td><?= htmlspecialchars($row['saleprice']) ?>%</td>
        <td><?= htmlspecialchars($row['quantity']) ?></td>
        <td><?= $row['status'] == 0 ? '<span class="badge bg-success">Còn hàng</span>' : '<span class="badge bg-secondary">Hết hàng</span>' ?></td>
        <td>
          <a href="product-edit.php?idProduct=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Sửa</a>
          <a href="product-delete.php?idProducts=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
             onclick="return confirm('Bạn chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php require_once 'footer.php'; ?>
