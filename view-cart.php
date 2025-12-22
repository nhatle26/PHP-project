<?php
session_start();
require_once "model/connect.php";

$cart = $_SESSION['cart'] ?? [];
$total = 0;

// Xóa sản phẩm
if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: view-cart.php");
    exit();
}

// Cập nhật số lượng
if (isset($_POST['update'])) {
    foreach ($_POST['quantity'] as $id => $qty) {
        $qty = max(1, (int)$qty);
        if (isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id]['quantity'] = $qty;
    }
    header("Location: view-cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng - MyLiShop</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
      /* local small tweaks for cart */
      .cart-actions { display:flex; gap:10px; justify-content:flex-end; margin-top:12px; }
      .qty-input { width:80px; }
      @media (max-width:768px){ .cart-table th, .cart-table td{ font-size:14px; } .qty-input{width:60px;} }
    </style>
</head>
<body>
<?php include "model/header.php"; ?>
<div class="container" style="margin-top:30px;">
    <h2 class="mb-3">Giỏ hàng của bạn</h2>

    <?php if (empty($cart)): ?>
        <div class="alert alert-info text-center">
            Giỏ hàng đang trống!  
            <a href="index.php">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <form method="post">
            <div class="card">
              <div class="card-body p-0">
                <table class="cart-table" style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th style="width:90px;">Ảnh</th>
                            <th>Sản phẩm</th>
                            <th style="width:120px;">Giá</th>
                            <th style="width:130px;">Số lượng</th>
                            <th style="width:140px;">Thành tiền</th>
                            <th style="width:90px;">Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart as $id => $item):
                            $qty = $item['quantity'];
                            $subtotal = $item['price'] * $qty;
                            $total += $subtotal;
                        ?>
                        <tr>
                            <td><img src="<?= htmlspecialchars($item['image'] ?? 'images/no-image.png') ?>" style="width:80px;height:80px;object-fit:cover;border-radius:6px;"></td>
                            <td style="text-align:left; padding:12px;"><a href="detail.php?id=<?= $id ?>"><?= htmlspecialchars($item['name']) ?></a></td>
                            <td><?= number_format($item['price']) ?> đ</td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center">
                                    <button type="button" class="btn btn-sm btn-outline-secondary qty-dec" data-id="<?= $id ?>">-</button>
                                    <input type="number" name="quantity[<?= $id ?>]" value="<?= $qty ?>" min="1" class="form-control qty-input mx-2 text-center">
                                    <button type="button" class="btn btn-sm btn-outline-secondary qty-inc" data-id="<?= $id ?>">+</button>
                                </div>
                            </td>
                            <td style="color:#c82333;font-weight:700"><?= number_format($subtotal) ?> đ</td>
                            <td>
                                <a href="?remove=<?= $id ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Xóa sản phẩm này?');">Xóa</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
              </div>
              <div class="card-footer bg-light d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="mb-2 mb-md-0">
                  <strong>Tổng cộng:</strong>
                  <span class="text-danger h5 ms-2"><?= number_format($total) ?> đ</span>
                </div>
                <div class="cart-actions">
                  <button class="btn btn-outline-primary" type="submit" name="update">Cập nhật giỏ hàng</button>
                  <a href="index.php" class="btn btn-secondary">Tiếp tục mua sắm</a>
                  <a href="checkout.php" class="btn btn-success">Thanh toán</a>
                </div>
              </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include "model/footer.php"; ?>

<script>
// quantity buttons behavior
document.addEventListener('click', function(e){
  if(e.target.matches('.qty-inc') || e.target.matches('.qty-dec')){
    var row = e.target.closest('tr');
    var input = row.querySelector('input[type="number"]');
    var val = parseInt(input.value) || 1;
    if(e.target.classList.contains('qty-inc')){ input.value = val + 1; }
    else { if(val>1) input.value = val - 1; }
  }
});
</script>

</body>
</html>
