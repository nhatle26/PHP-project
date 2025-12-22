<?php
session_start();
require_once "../model/connect.php";

$base = "/php_project";

// Kiểm tra đăng nhập
$user_id = $_SESSION['user_id'] ?? null;
$role = $_SESSION['role'] ?? null;

if (!$user_id || $role === 'admin') {
    header("Location: ../user/login.php");
    exit();
}

// Xử lý update profile
$error_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $address  = trim($_POST['address'] ?? '');

    if ($fullname && $email) {
        $stmt = $conn->prepare("UPDATE users SET fullname=?, email=?, phone=?, address=? WHERE id=?");
        $stmt->bind_param("ssssi", $fullname, $email, $phone, $address, $user_id);
        $stmt->execute();

        $_SESSION['username'] = $fullname;
        header("Location: profile.php?success=1");
        exit();
    } else {
        $error_msg = "Họ tên và email không được để trống!";
    }
}

// Lấy thông tin user
$stmt = $conn->prepare("SELECT fullname, email, phone, address FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Lấy sản phẩm nổi bật
$products = $conn->query("SELECT id, image, name, price FROM products LIMIT 8");

// Hàm render sản phẩm
function renderProduct($p){
    $img = !empty($p['image']) ? $p['image'] : "../images/no-image.png"; ?>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="product-card">
            <div class="product-image"><img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($p['name']) ?>"></div>
            <div class="product-body">
                <h4 class="product-name"><?= htmlspecialchars($p['name']) ?></h4>
                <div class="product-price"><?= number_format($p['price']) ?> đ</div>
                <div class="product-actions">
                    <a href="../addcart.php?id=<?= $p['id'] ?>" class="btn btn-buy"><i class="fa fa-shopping-cart"></i> Mua</a>
                    <a href="../detail.php?id=<?= $p['id'] ?>" class="btn btn-detail">Chi tiết</a>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Profile - <?= ucfirst($role) ?></title>
<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<?php include "../model/header.php"; ?>

<div class="container" style="margin-top: 20px;">
    <div style="margin-bottom: 20px;">
        <a href="<?= ($role==='admin')?$base.'/admin/index.php':$base.'/index.php' ?>" class="btn btn-primary"><i class="fa fa-home"></i> Về trang chủ</a>
    </div>

    <?php if (!empty($error_msg)) echo '<div class="alert alert-danger">'.htmlspecialchars($error_msg).'</div>'; ?>
    <?php if (!empty($_GET['success'])) echo '<div class="alert alert-success">Cập nhật thông tin thành công!</div>'; ?>

    <div class="profile-main">
        <h3 class="section-title">Thông tin cá nhân</h3>
        <div class="row">
            <div class="col-md-4">
                <div class="profile-card">
                    <form action="" method="post">
                        <p><strong>Họ và tên:</strong><input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" class="form-control"></p>
                        <p><strong>Email:</strong><input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control"></p>
                        <p><strong>Số điện thoại:</strong><input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" class="form-control"></p>
                        <p><strong>Địa chỉ:</strong><input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>" class="form-control"></p>
                        <button type="submit" name="update_profile" class="btn btn-success">Lưu thay đổi</button>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <h4>Sản phẩm nổi bật</h4>
                <div class="row">
                    <?php while ($p = $products->fetch_assoc()) renderProduct($p); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../model/footer.php"; ?>
</body>
</html>
