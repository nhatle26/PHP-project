<?php
session_start();
require_once "model/connect.php";

/* =========================
   KIỂM TRA ĐĂNG NHẬP
========================= */
$user_id = $_SESSION['user_id'] ?? 0;
$cart    = $_SESSION['cart'] ?? [];

if ($user_id <= 0) {
    header("Location: auth/login.php");
    exit();
}

if (empty($cart)) {
    header("Location: view-cart.php");
    exit();
}

/* =========================
   LẤY THÔNG TIN USER
========================= */
$stmtU = $conn->prepare("
    SELECT fullname, phone, address 
    FROM users 
    WHERE id = ?
");
$stmtU->bind_param("i", $user_id);
$stmtU->execute();
$user = $stmtU->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Thanh toán | Fashion MyLiShop</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="images/logohong.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

<?php include "model/header.php"; ?>

<div class="container" style="margin-top:30px;">
    <h3 class="section-title">Thanh toán</h3>

    <div class="row">

        <!-- =====================
             THÔNG TIN KHÁCH HÀNG
        ====================== -->
        <div class="col-md-6">
            <h4>Thông tin nhận hàng</h4>

            <form method="post" action="payment_vnpay.php">

                <div class="form-group">
                    <label>Họ tên</label>
                    <input type="text" name="fullname" class="form-control"
                           value="<?= htmlspecialchars($user['fullname'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="text" name="phone" class="form-control"
                           value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label>Địa chỉ</label>
                    <textarea name="address" class="form-control" required><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label>Ghi chú</label>
                    <textarea name="note" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <label>Phương thức thanh toán</label>
                    <select name="payment_method" class="form-control">
                        <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                        <option value="vnpay">Thanh toán qua VNPAY</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-danger btn-block">
                    <i class="fa fa-credit-card"></i> Đặt hàng
                </button>

            </form>
        </div>

        <!-- =====================
             ĐƠN HÀNG CỦA BẠN
        ====================== -->
        <div class="col-md-6">
            <h4>Đơn hàng của bạn</h4>

            <?php
            $total = 0;
            ?>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>SL</th>
                        <th>Giá</th>
                    </tr>
                </thead>
                <tbody>

                <?php foreach ($cart as $item): 
                    $qty = isset($item['qty']) && $item['qty'] > 0 ? (int)$item['qty'] : 1;
                    $price = (float)($item['price'] ?? 0);
                    $subtotal = $price * $qty;
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name'] ?? '') ?></td>
                        <td><?= $qty ?></td>
                        <td><?= number_format($subtotal) ?> đ</td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">Tổng cộng</th>
                        <th><?= number_format($total) ?> đ</th>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
</div>

<?php include "model/footer.php"; ?>

</body>
</html>
