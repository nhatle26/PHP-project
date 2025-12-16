<?php
require_once 'header.php';

$id = intval($_GET['idProduct'] ?? 0);
if ($id <= 0) {
    $_SESSION['error'] = "ID sản phẩm không hợp lệ.";
    header("Location: product-list.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    $_SESSION['error'] = "Không tìm thấy sản phẩm.";
    header("Location: product-list.php");
    exit();
}

$cats = $conn->query("SELECT id, name FROM categories ORDER BY name")->fetch_all(MYSQLI_ASSOC);
?>

<h3>Chỉnh sửa sản phẩm</h3>

<form action="productedit-back.php?idProduct=<?= $product['id'] ?>" method="post" enctype="multipart/form-data" class="mt-3">
  <div class="row">
    <div class="col-md-8">
      <div class="mb-3">
        <label class="form-label">Tên sản phẩm</label>
        <input type="text" name="txtName" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Mô tả</label>
        <textarea name="txtDescript" rows="4" class="form-control"><?= htmlspecialchars($product['description']) ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Từ khóa (keyword)</label>
        <input type="text" name="txtKeyword" class="form-control" value="<?= htmlspecialchars($product['keyword']) ?>">
      </div>
    </div>

    <div class="col-md-4">
      <div class="mb-3">
        <label class="form-label">Danh mục</label>
        <select name="category" class="form-select" required>
          <?php foreach ($cats as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $c['id'] == $product['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Giá</label>
        <input type="number" name="txtPrice" min="0" class="form-control" value="<?= htmlspecialchars($product['price']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Giảm (%)</label>
        <input type="number" name="txtSalePrice" min="0" max="100" class="form-control" value="<?= htmlspecialchars($product['saleprice']) ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Số lượng</label>
        <input type="number" name="txtNumber" min="0" class="form-control" value="<?= htmlspecialchars($product['quantity']) ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Ảnh hiện tại</label><br>
        <?php if (!empty($product['image'])): ?>
          <img src="../<?= htmlspecialchars($product['image']) ?>" class="product-thumb border mb-2">
        <?php else: ?> ---
        <?php endif; ?>
        <input type="file" name="FileImage" accept="image/*" class="form-control mt-2">
        <small class="text-muted">Bỏ trống nếu không muốn thay ảnh.</small>
      </div>

      <div class="mb-3">
        <label class="form-label">Trạng thái</label>
        <select name="status" class="form-select">
          <option value="0" <?= $product['status'] == 0 ? 'selected' : '' ?>>Còn hàng</option>
          <option value="1" <?= $product['status'] == 1 ? 'selected' : '' ?>>Hết hàng</option>
        </select>
      </div>

      <div class="d-grid">
        <button type="submit" name="editProduct" class="btn btn-primary">Cập nhật</button>
      </div>
    </div>
  </div>
</form>

<?php require_once 'footer.php'; ?>
