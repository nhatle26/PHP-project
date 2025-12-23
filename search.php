<?php
require_once('model/connect.php');
if (session_status() == PHP_SESSION_NONE)
    session_start();

$prd = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Load categories for filter
$cats = $conn->query("SELECT id,name FROM categories ORDER BY name")->fetch_all(MYSQLI_ASSOC);

// Build search query (supports q, category, status)
$q = trim($_GET['q'] ?? $_POST['search'] ?? '');
$category = isset($_GET['category']) ? intval($_GET['category']) : 0;
$status = isset($_GET['status']) && $_GET['status'] !== '' ? intval($_GET['status']) : null;

// Build WHERE parts and params
$where = [];
$params = [];
$types = '';

if ($q !== '') {
    $where[] = 'name LIKE ?';
    $params[] = "%{$q}%";
    $types .= 's';
}
if ($category > 0) {
    $where[] = 'category_id = ?';
    $params[] = $category;
    $types .= 'i';
}
if ($status !== null && ($status === 0 || $status === 1)) {
    $where[] = 'status = ?';
    $params[] = $status;
    $types .= 'i';
}

$whereSql = '';
if (!empty($where))
    $whereSql = ' WHERE ' . implode(' AND ', $where);

// Count total for pagination
$countSql = "SELECT COUNT(*) as total FROM products" . $whereSql;
$totalnumber = 0;
$cntStmt = $conn->prepare($countSql);
if ($cntStmt) {
    if (!empty($params)) {
        $bind_names = [];
        $bind_names[] = $types;
        for ($i = 0; $i < count($params); $i++) {
            $bind_names[] = &$params[$i];
        }
        call_user_func_array([$cntStmt, 'bind_param'], $bind_names);
    }
    $cntStmt->execute();
    $cntRes = $cntStmt->get_result();
    $totalnumber = $cntRes ? (int) $cntRes->fetch_assoc()['total'] : 0;
    $cntStmt->close();
}

// Pagination
$perPage = 12;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

// Data query with limit
$sql = "SELECT id,image,name,price FROM products" . $whereSql . " ORDER BY id DESC LIMIT ?, ?";

$stm = $conn->prepare($sql);
if ($stm) {
    // bind params + offset, perPage
    $bind_values = $params;
    $bind_types = $types;
    $bind_values[] = $offset;
    $bind_values[] = $perPage;
    $bind_types .= 'ii';

    $bind_names = [];
    $bind_names[] = $bind_types;
    for ($i = 0; $i < count($bind_values); $i++) {
        $bind_names[] = &$bind_values[$i];
    }
    call_user_func_array([$stm, 'bind_param'], $bind_names);
    $stm->execute();
    $resultSearch = $stm->get_result();
} else {
    $resultSearch = false;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Fashion MyLiShop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Fashion MyLiShop - fashion mylishop" />
    <meta name="description" content="Fashion MyLiShop - fashion mylishop" />
    <meta name="keywords" content="Fashion MyLiShop - fashion mylishop" />
    <meta name="author" content="Hôih My" />
    <meta name="author" content="Y Blir" />
    <link rel="icon" type="image/png" href="images/logohong.png">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" type="text/css" href="admin/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js" charset="utf-8"></script> -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css'> -->

    <!-- customer js -->
    <script src='js/wow.js'></script>
    <script type="text/javascript" src="js/mylishop.js"></script>
    <!-- customer css -->
    <link rel="stylesheet" type="text/css" href="css/animate.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">

</head>

<body>
    <!-- button top -->
    <a href="#" class="back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- Header -->
    <?php include("model/header.php"); ?>
    <!-- /header -->

    <div class="container">
        <ul class="breadcrumb">
            <li><a href="index.php">Trang chủ</a></li>
            <li>Tìm kiếm sản phẩm</li>
        </ul><!-- /breadcrumb -->
        <!-- Content -->
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="product-main">
                        <div class="title-product-main">
                            <h3 class="section-title"> Kết Quả Tìm Kiếm </h3>
                            <p style="color: black; margin-left: 10px;">Có <?= htmlspecialchars($totalnumber) ?> sản
                                phẩm được tìm thấy</p>
                            <!-- Filter UI -->
                            <form method="get" class="row g-2 mb-3" style="margin-left:10px; margin-right:10px;">
                                <div class="col-md-6">
                                    <input type="search" name="q" class="form-control" placeholder="Từ khóa"
                                        value="<?= htmlspecialchars($q ?? '') ?>">
                                </div>
                                <div class="col-md-3">
                                    <select name="category" class="form-select">
                                        <option value="0">-- Tất cả danh mục --</option>
                                        <?php foreach ($cats as $c): ?>
                                            <option value="<?= $c['id'] ?>" <?= ($category == $c['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($c['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="status" class="form-select">
                                        <option value="">-- Trạng thái --</option>
                                        <option value="0" <?= ($status === 0) ? 'selected' : '' ?>>Còn hàng</option>
                                        <option value="1" <?= ($status === 1) ? 'selected' : '' ?>>Hết hàng</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-primary" type="submit">Lọc</button>
                                </div>
                            </form>
                        </div>
                        <div class="content-product-main">
                            <div class="row">
                                <?php

                                $i = 0;
                                if ($resultSearch) {
                                    while ($kq = $resultSearch->fetch_assoc()) {
                                        $i++;
                                        // normalize image path
                                        $img = $kq['image'] ?? '';
                                        if ($img && strpos($img, '://') === false && strpos($img, '/') !== 0) {
                                            $img = '/php_project/' . ltrim($img, '/');
                                        }
                                        ?>
                                        <div class="col-md-3 col-sm-6 text-center">
                                            <div class="thumbnail">
                                                <div class="hoverimage1">
                                                    <img src="<?= htmlspecialchars($img) ?>"
                                                        alt="<?= htmlspecialchars($kq['name']) ?>" width="100%" height="300">
                                                </div>
                                                <div class="name-product">
                                                    <?php echo $kq['name']; ?>
                                                </div>
                                                <div class="price">
                                                    Giá: <?php echo $kq['price']; ?><sup> đ</sup>
                                                </div>
                                                <div class="product-info">
                                                    <!-- <a href="addcart.php?id=<?php echo $kq['id']; ?>">
                                                        <button type="button" class="btn btn-primary">
                                                            <label style="color: red;">&hearts;</label> Mua hàng  <label style="color: red;">&hearts;</label>
                                                        </button>
                                                    </a> -->
                                                    <a href="detail.php?id=<?php echo $kq['id']; ?>">
                                                        <button type="button" class="btn btn-primary">
                                                            <label style="color: red;">&hearts;</label> Chi Tiết <label
                                                                style="color: red;">&hearts;</label>
                                                        </button>
                                                    </a>
                                                </div><!-- /product-info -->
                                            </div><!-- /thumbnail -->
                                        </div><!-- /col -->
                                    <?php }
                                } ?>
                                <div class="error-search" style="color: #FF0000; font-weight: bold; margin-left: 15px;">
                                    <?php
                                    if ($i <= 0) {
                                        echo "KÍNH CHÀO QUÝ KHÁCH VÀ XIN LỖI VÌ SẢN PHẨM BẠN TÌM KHÔNG TỒN TẠI!";
                                    }
                                    ?>
                                </div>

                            </div><!-- /row -->
                            <!-- Pagination -->
                            <?php if ($totalnumber > $perPage): ?>
                                <?php $totalPages = ceil($totalnumber / $perPage); ?>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="justify-content:center; margin-top:20px;">
                                        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                            <li class="page-item <?= $p == $page ? 'active' : '' ?>">
                                                <a class="page-link"
                                                    href="?<?= http_build_query(array_merge($_GET, ['page' => $p])) ?>"><?= $p ?></a>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </nav>
                            <?php endif; ?>
                        </div><!-- /tìm kiếm sản phẩm -->
                    </div><!-- /.product-main -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container -->


        <!-- footer -->
        <div class="container">
            <?php include("model/footer.php"); ?>
        </div>
        <!-- /footer -->

        <script>
            new WOW().init();
        </script>
</body>

</html>