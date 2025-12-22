<?php
// Số SP trong giỏ
$prd = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Hàm lấy danh sách sản phẩm
function getProducts($conn, $category, $limit = 12, $status = null) {
    $sql = "SELECT id, image, name, price FROM products WHERE category_id = ?";

    if ($status !== null) {
        $sql .= " AND status = ?";
    }

    $sql .= " LIMIT ?";

    $stmt = $conn->prepare($sql);

    if ($status !== null) {
        $stmt->bind_param("iii", $category, $status, $limit);
    } else {
        $stmt->bind_param("ii", $category, $limit);
    }

    $stmt->execute();
    return $stmt->get_result();
}

// Hàm render 1 sản phẩm
function renderProduct($p) {
    $img = !empty($p['image']) ? $p['image'] : "images/no-image.png";
    ?>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="product-card">

            <div class="product-image">
                <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
            </div>

            <div class="product-body">
                <h4 class="product-name">
                    <?= htmlspecialchars($p['name']) ?>
                </h4>

                <div class="product-price">
                    <?= number_format($p['price']) ?> đ
                </div>

                <div class="product-actions">
                    <a href="addcart.php?id=<?= $p['id'] ?>" class="btn btn-buy">
                        <i class="fa fa-shopping-cart"></i> Mua
                    </a>
                    <a href="detail.php?id=<?= $p['id'] ?>" class="btn btn-detail">
                        Chi tiết
                    </a>
                </div>
            </div>

        </div>
    </div>
    <?php
}

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Fashion MyLiShop</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="images/logohong.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>

<?php include "model/header.php"; ?>
<?php include "model/slide.php"; ?>
<?php include "model/banner.php"; ?>

<div class="container">
    <div class="product-main">

        <!-- SẢN PHẨM MỚI -->
        <h3 class="section-title">Sản phẩm mới</h3>
        <div class="row">
            <?php
            $newProducts = getProducts($conn, 3, 12, 0);
            while ($p = $newProducts->fetch_assoc()) {
                renderProduct($p);
            }
            ?>
        </div>

        <!-- NAM -->
        <h3 class="section-title">Thời Trang Nam</h3>
        <div class="row">
            <?php
            $men = getProducts($conn, 1, 8);
            while ($p = $men->fetch_assoc()) {
                renderProduct($p);
            }
            ?>
        </div>

        <!-- NỮ -->
        <h3 class="section-title">Thời Trang Nữ</h3>
        <div class="row">
            <?php
            $women = getProducts($conn, 2, 8);
            while ($p = $women->fetch_assoc()) {
                renderProduct($p);
            }
            ?>
        </div>

    </div>
</div>

<?php include "model/partner.php"; ?>
<?php include "model/footer.php"; ?>

</body>
</html>
