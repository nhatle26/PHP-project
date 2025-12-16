<?php require_once('model/connect.php'); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Fashion MyLiShop</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Fashion MyLiShop - fashion mylishop">
    <meta name="description" content="Fashion MyLiShop - fashion mylishop">
    <meta name="keywords" content="Fashion MyLiShop, thời trang nữ, quần áo nữ">
    <meta name="author" content="Hôih My, Y Blir">

    <link rel="icon" type="image/png" href="images/logohong.png">

    <!-- CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="js/wow.js"></script>
    <script src="js/mylishop.js"></script>
</head>

<body>

<a href="#" class="back-to-top"><i class="fa fa-arrow-up"></i></a>

<?php include("model/header.php"); ?>

<div class="main">
    <div class="container">

        <div class="product-main">

            <h3 class="section-title">Thời Trang Nữ</h3>

            <div class="content-product-main">
                <div class="row">

                    <?php
                    $stmt = $conn->prepare("SELECT id, image, name, price FROM products WHERE category_id = ?");
                    $cat = 2;
                    $stmt->bind_param("i", $cat);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($p = $result->fetch_assoc()):
                        $img = !empty($p['image']) ? $p['image'] : "images/no-image.png";
                    ?>

                        <div class="col-md-3 col-sm-6 text-center wow fadeInUp" data-wow-delay="0.1s">
                            <div class="thumbnail">

                                <div class="hoverimage1">
                                    <img src="<?= htmlspecialchars($img) ?>"
                                         alt="<?= htmlspecialchars($p['name']) ?>"
                                         class="img-responsive"
                                         style="height:300px; width:100%; object-fit:cover;">
                                </div>

                                <div class="name-product">
                                    <?= htmlspecialchars($p['name']) ?>
                                </div>

                                <div class="price">
                                    Giá: <?= number_format($p['price']) ?> đ
                                </div>

                                <div class="product-info">

                                    <a href="addcart.php?id=<?= $p['id'] ?>">
                                        <button class="btn btn-primary">
                                            <span style="color:red;">❤</span> Mua hàng <span style="color:red;">❤</span>
                                        </button>
                                    </a>

                                    <a href="detail.php?id=<?= $p['id'] ?>">
                                        <button class="btn btn-primary">
                                            <span style="color:red;">❤</span> Chi tiết <span style="color:red;">❤</span>
                                        </button>
                                    </a>

                                </div>

                            </div>
                        </div>

                    <?php endwhile; ?>

                </div>
            </div>

        </div>

    </div>
</div>

<div class="container">
    <h3 class="section-title">Hãng Thời Trang Nổi Tiếng</h3>
    <?php include("model/partner.php"); ?>
</div>

<div class="container">
    <?php include("model/footer.php"); ?>
</div>

<script> new WOW().init(); </script>

</body>
</html>
