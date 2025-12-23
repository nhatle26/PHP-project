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

?>

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
    <?php if (!empty($error_msg)) echo '<div class="alert alert-danger">'.htmlspecialchars($error_msg).'</div>'; ?>
    <?php if (!empty($_GET['success'])) echo '<div class="alert alert-success">Cập nhật thông tin thành công!</div>'; ?>

    <div style="text-align: center; margin-bottom: 30px;">
        <h3 style="display: inline-block; font-size: 28px; color: #333; background: #fbdd12; padding: 15px 25px; border-radius: 6px;"><i class="fa fa-user-circle"></i> Thông tin cá nhân</h3>
    </div>
    
    <div class="profile-main">
        <div class="profile-card" style="max-width: 550px; margin: 0 auto;">
            <form action="" method="post">
                <div class="form-group">
                    <label><strong>Họ và tên</strong></label>
                    <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label><strong>Email</strong></label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label><strong>Số điện thoại</strong></label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" class="form-control">
                </div>
                
                <div class="form-group">
                    <label><strong>Địa chỉ</strong></label>
                    <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>" class="form-control">
                </div>
                
                <div style="margin-top: 30px; text-align: center;">
                    <button type="submit" name="update_profile" class="btn btn-success btn-lg" style="width: 200px;"><i class="fa fa-save"></i> Lưu thay đổi</button>
                </div>
            </form>
            
            <div style="margin-top: 20px; text-align: center; border-top: 1px solid #ddd; padding-top: 20px;">
                <a href="<?= ($role==='admin')?$base.'/admin/index.php':$base.'/index.php' ?>" class="btn btn-secondary"><i class="fa fa-home"></i> Về trang chủ</a>
            </div>
        </div>
    </div>
</div>

<?php include "../model/footer.php"; ?>
</body>
</html>
