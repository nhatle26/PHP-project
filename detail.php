<?php
require_once "model/connect.php";

// Lấy id sản phẩm từ URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die("Sản phẩm không hợp lệ.");
}

// Truy vấn chi tiết sản phẩm
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$prd = $result->fetch_assoc();

if (!$prd) {
    die("Không tìm thấy sản phẩm.");
}

// Hình ảnh sản phẩm
$img = !empty($prd['image']) ? $prd['image'] : "images/no-image.png";

// Lấy giá và saleprice, đảm bảo kiểu số
$price = isset($prd['price']) ? (float)$prd['price'] : 0;
$saleprice = isset($prd['saleprice']) ? (float)$prd['saleprice'] : 0;

// Tính giá giảm nếu có
$gia_giam = ($saleprice > 0) ? $price - ($price * $saleprice / 100) : null;
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($prd['name'] ?? 'Chi tiết sản phẩm') ?></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<?php include "model/header.php"; ?>

<div class="container" style="margin-top: 20px;">
    <div class="row">

        <!-- Hình ảnh sản phẩm -->
        <div class="col-md-5">
            <img src="<?= htmlspecialchars($img) ?>" width="100%" height="450" alt="<?= htmlspecialchars($prd['name'] ?? '') ?>">
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="col-md-7">
            <h2><?= htmlspecialchars($prd['name'] ?? 'Sản phẩm') ?></h2>
            <hr>

            <!-- Giá sản phẩm -->
            <?php if ($gia_giam !== null): ?>
                <p class="price">
                    Giá cũ:
                    <span style="text-decoration: line-through;">
                        <?= number_format($price) ?> đ
                    </span>
                </p>

                <p class="price">
                    Giá giảm:
                    <strong><?= number_format($gia_giam) ?> đ</strong>
                    <small>(Giảm <?= (int)$saleprice ?>%)</small>
                </p>
            <?php else: ?>
                <p class="price">
                    Giá:
                    <strong><?= number_format($price) ?> đ</strong>
                </p>
            <?php endif; ?>

            <hr>

            <!-- Nút đặt mua -->
            <a href="addcart.php?id=<?= $prd['id'] ?? 0 ?>">
                <button class="btn btn-warning btn-md">Đặt mua</button>
            </a>

            <p style="margin-top: 20px;">
                <i class="fa fa-check-circle"></i> Giao hàng toàn quốc<br>
                <i class="fa fa-check-circle"></i> Thanh toán khi nhận hàng<br>
                <i class="fa fa-check-circle"></i> Đổi hàng trong 15 ngày
            </p>
        </div>
    </div>
</div>

<?php include "model/footer.php"; ?>

</body>
</html>
