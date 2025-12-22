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
            <div class="card">
                <img src="<?= htmlspecialchars($img) ?>" class="card-img-top" alt="<?= htmlspecialchars($prd['name'] ?? '') ?>" style="object-fit:cover; height:450px;">
            </div>
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="col-md-7">
            <h2><?= htmlspecialchars($prd['name'] ?? 'Sản phẩm') ?></h2>
            <p class="text-muted">Mã sản phẩm: #<?= $prd['id'] ?></p>
            <hr>

            <!-- Giá sản phẩm -->
            <?php if ($gia_giam !== null): ?>
                <p class="price mb-1">
                    <small class="text-muted">Giá cũ</small>
                    <span class="d-block text-decoration-line-through"><?= number_format($price) ?> đ</span>
                </p>
                <p class="price mb-2">
                    <small class="text-muted">Giá bán</small>
                    <span class="d-block h4 text-danger"><?= number_format($gia_giam) ?> đ</span>
                    <small class="text-success">(Giảm <?= (int)$saleprice ?>%)</small>
                </p>
            <?php else: ?>
                <p class="price mb-3">
                    <span class="d-block h4 text-dark"><?= number_format($price) ?> đ</span>
                </p>
            <?php endif; ?>

            <!-- Mô tả ngắn -->
            <?php $desc = $prd['description'] ?? $prd['content'] ?? ''; ?>
            <?php if (!empty($desc)): ?>
                <div class="mb-3">
                    <?= nl2br(htmlspecialchars($desc)) ?>
                </div>
            <?php endif; ?>

            <form method="GET" action="addcart.php" class="d-flex align-items-center gap-2">
                <input type="hidden" name="id" value="<?= $prd['id'] ?>">
                <div class="input-group" style="width:140px;">
                    <button type="button" class="btn btn-outline-secondary" id="decBtn">-</button>
                    <input type="number" min="1" max="<?= intval($prd['quantity'] ?? 9999) ?>" name="qty" value="1" class="form-control text-center" id="qtyInput">
                    <button type="button" class="btn btn-outline-secondary" id="incBtn">+</button>
                </div>

                <button type="submit" class="btn btn-warning btn-lg">Thêm vào giỏ</button>
            </form>

            <p class="mt-3 text-muted">
                <i class="fa fa-truck"></i> Giao hàng toàn quốc &nbsp; • &nbsp;
                <i class="fa fa-shield"></i> Thanh toán khi nhận hàng &nbsp; • &nbsp;
                <i class="fa fa-exchange-alt"></i> Đổi hàng trong 15 ngày
            </p>
        </div>
    </div>
</div>

<?php include "model/footer.php"; ?>

</body>
</html>

<script>
// Simple quantity buttons
document.addEventListener('DOMContentLoaded', function(){
    var dec = document.getElementById('decBtn');
    var inc = document.getElementById('incBtn');
    var input = document.getElementById('qtyInput');
    if(dec && inc && input){
        dec.addEventListener('click', function(){
            var v = parseInt(input.value) || 1; if(v>1) input.value = v-1;
        });
        inc.addEventListener('click', function(){
            var v = parseInt(input.value) || 1; var max = parseInt(input.max) || 9999; if(v<max) input.value = v+1;
        });
    }
});
</script>
