<?php
require_once 'header.php';

// load categories
$cats = $conn->query("SELECT id, name FROM categories ORDER BY name")->fetch_all(MYSQLI_ASSOC);
?>

<h3>Thêm sản phẩm</h3>

<form action="productadd-back.php" method="post" enctype="multipart/form-data" class="mt-3">
  <div class="row">
    <div class="col-md-8">
      <div class="mb-3">
        <label class="form-label">Tên sản phẩm</label>
        <input type="text" name="txtName" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Mô tả</label>
        <textarea name="txtDescript" rows="4" class="form-control"></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Từ khóa (keyword)</label>
        <input type="text" name="txtKeyword" class="form-control">
      </div>
    </div>

    <div class="col-md-4">
      <div class="mb-3">
        <label class="form-label">Danh mục</label>
        <select name="category" class="form-select" required>
          <option value="">-- Chọn --</option>
          <?php foreach ($cats as $c): ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Giá</label>
        <input type="number" name="txtPrice" min="0" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Giảm (%)</label>
        <input type="number" name="txtSalePrice" min="0" max="100" class="form-control" value="0">
      </div>

      <div class="mb-3">
        <label class="form-label">Số lượng</label>
        <input type="number" name="txtNumber" min="0" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Ảnh sản phẩm</label>
        <input type="file" name="FileImage" accept="image/*" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Trạng thái</label>
        <select name="status" class="form-select">
          <option value="0">Còn hàng</option>
          <option value="1">Hết hàng</option>
        </select>
      </div>

      <div class="d-grid">
        <button type="submit" name="addProduct" class="btn btn-success">Thêm sản phẩm</button>
      </div>
    </div>
  </div>
</form>

<?php require_once 'footer.php'; ?>
