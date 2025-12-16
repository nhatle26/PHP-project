<?php
session_start();
require_once "../model/connect.php";

// Kiểm tra login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Nếu là admin → chuyển sang quản lý sản phẩm trong Admin
if ($_SESSION['role'] == 1) {
    header("Location: ../Admin/product-list.php");
    exit();
}

// Lấy thông tin user hiện tại
$stmt = $conn->prepare("SELECT username, fullname, email, phone, address FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang cá nhân</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

<?php include "../model/header.php"; ?>

<div class="container" style="max-width: 700px; margin: 40px auto;">

    <div class="panel panel-default" style="border-radius: 12px; box-shadow: 0 0 18px rgba(0,0,0,.15)">
        <div class="panel-heading" style="background:#fbdd12; font-weight:bold; font-size:18px;">
            <i class="fa fa-user-circle"></i> Trang cá nhân
        </div>

        <div class="panel-body">

            <?php if (isset($_SESSION['msg'])): ?>
                <div class="alert alert-success">
                    <?= $_SESSION['msg']; unset($_SESSION['msg']); ?>
                </div>
            <?php endif; ?>

            <form method="post" action="profile-back.php">

                <div class="form-group">
                    <label>Tên đăng nhập</label>
                    <input type="text"
                           class="form-control"
                           value="<?= htmlspecialchars($user['username']) ?>"
                           disabled>
                </div>

                <div class="form-group">
                    <label>Họ và tên</label>
                    <input type="text"
                           name="fullname"
                           class="form-control"
                           value="<?= htmlspecialchars($user['fullname'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           value="<?= htmlspecialchars($user['email']) ?>">
                </div>

                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="text"
                           name="phone"
                           class="form-control"
                           value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Địa chỉ</label>
                    <input type="text"
                           name="address"
                           class="form-control"
                           value="<?= htmlspecialchars($user['address'] ?? '') ?>">
                </div>

                <div style="display:flex; gap:10px; margin-top:20px;">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Lưu thay đổi
                    </button>

                    <a href="../index.php" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i> Quay về
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

<?php include "../model/footer.php"; ?>
</body>
</html>
