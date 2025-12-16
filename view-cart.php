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
        $_SESSION['cart'][$id]['quantity'] = $qty;
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
</head>
<body>
<div class="container" style="margin-top:30px;">
    <h2>Giỏ hàng của bạn</h2>
    <hr>

    <?php if (empty($cart)): ?>
        <div class="alert alert-info text-center">
            Giỏ hàng đang trống!  
            <a href="index.php">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <form method="post">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Ảnh</th>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $id => $item):
                        $qty = $item['quantity'];
                        $subtotal = $item['price'] * $qty;
                        $total += $subtotal;
                    ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($item['image'] ?? 'images/no-image.png') ?>" width="80" height="80" style="object-fit:cover;"></td>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= number_format($item['price']) ?> đ</td>
                        <td>
                            <input type="number" name="quantity[<?= $id ?>]" value="<?= $qty ?>" min="1" class="form-control" style="width:70px;">
                        </td>
                        <td><?= number_format($subtotal) ?> đ</td>
                        <td>
                            <a href="?remove=<?= $id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xóa sản phẩm này?');">Xóa</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Tổng cộng:</th>
                        <th colspan="2" style="color:red;"><?= number_format($total) ?> đ</th>
                    </tr>
                </tfoot>
            </table>

            <button class="btn btn-primary" type="submit" name="update">Cập nhật giỏ hàng</button>
            <a href="checkout.php" class="btn btn-success">Thanh toán</a>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
