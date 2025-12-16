<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "model/connect.php";

$user_id = $_SESSION['user_id'] ?? null;
$cart = $_SESSION['cart'] ?? [];

if (!$user_id) {
    header("Location: auth/login.php");
    exit();
}

/* LẤY THÔNG TIN USER */
$stmt = $conn->prepare("
    SELECT fullname, phone, address, email
    FROM users
    WHERE id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$error = '';
$success = '';

/* XỬ LÝ ĐẶT HÀNG */
if (isset($_POST['checkout'])) {

    if (empty($cart)) {
        $error = "Giỏ hàng trống!";
    } else {

        // Lấy dữ liệu từ form
        $fullname = trim($_POST['fullname']);
        $phone    = trim($_POST['phone']);
        $address  = trim($_POST['address']);

        if ($fullname === '' || $phone === '' || $address === '') {
            $error = "Vui lòng nhập đầy đủ thông tin giao hàng!";
        } else {

            /* UPDATE USER */
            $stmt = $conn->prepare("
                UPDATE users
                SET fullname = ?, phone = ?, address = ?
                WHERE id = ?
            ");
            $stmt->bind_param("sssi", $fullname, $phone, $address, $user_id);
            $stmt->execute();

            /* TÍNH TỔNG TIỀN */
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            $date_order = date('Y-m-d H:i:s');
            $status = 1;

            /* LƯU ĐƠN HÀNG */
            $stmt = $conn->prepare("
                INSERT INTO orders (user_id, total, date_order, status)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->bind_param("idsi", $user_id, $total, $date_order, $status);
            $stmt->execute();

            $order_id = $conn->insert_id;

            unset($_SESSION['cart']);

            $success = "Đặt hàng thành công! Mã đơn: #$order_id";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include "model/header.php"; ?>

<div class="container" style="margin-top:30px; max-width:900px">

    <h3>Thanh toán</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
        <a href="index.php" class="btn btn-primary">Tiếp tục mua sắm</a>
    <?php else: ?>

    <form method="post">

        <!-- THÔNG TIN GIAO HÀNG -->
        <h4>Thông tin giao hàng</h4>

        <div class="row">
            <div class="col-md-6">
                <label>Họ tên</label>
                <input type="text" name="fullname" class="form-control"
                       value="<?= htmlspecialchars($user['fullname'] ?? '') ?>">
            </div>

            <div class="col-md-6">
                <label>Số điện thoại</label>
                <input type="text" name="phone" class="form-control"
                       value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
            </div>

            <div class="col-md-12" style="margin-top:10px">
                <label>Địa chỉ</label>
                <input type="text" name="address" class="form-control"
                       value="<?= htmlspecialchars($user['address'] ?? '') ?>">
            </div>
        </div>

        <!-- GIỎ HÀNG -->
        <h4 style="margin-top:25px">Giỏ hàng</h4>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Sản phẩm</th>
                <th width="120">SL</th>
                <th width="150">Giá</th>
                <th width="150">Thành tiền</th>
            </tr>
            </thead>
            <tbody>
            <?php $total = 0; ?>
            <?php foreach ($cart as $item): ?>
                <?php
                $sub = $item['price'] * $item['quantity'];
                $total += $sub;
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= number_format($item['price']) ?> đ</td>
                    <td><?= number_format($sub) ?> đ</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <th colspan="3" class="text-right">Tổng cộng</th>
                <th style="color:red"><?= number_format($total) ?> đ</th>
            </tr>
            </tfoot>
        </table>

        <button name="checkout" class="btn btn-success btn-lg">
            Xác nhận đặt hàng
        </button>

    </form>
    <?php endif; ?>
</div>

<?php include "model/footer.php"; ?>
</body>
</html>
