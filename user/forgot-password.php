<?php
session_start();
require_once "../model/connect.php";
require_once "../model/send_otp.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (!$email) {
        $_SESSION['error'] = "Vui lòng nhập email";
        header("Location: forgot-password.php");
        exit();
    }

    // kiểm tra email tồn tại
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user) {
        $_SESSION['error'] = "Email không tồn tại";
        header("Location: forgot-password.php");
        exit();
    }

    $otp = rand(100000, 999999);

    $_SESSION['otp_type'] = 'forgot';
    $_SESSION['forgot_otp'] = [
        'email' => $email,
        'otp' => $otp,
        'expire' => time() + 300
    ];

    sendOTP($email, $otp);

    $_SESSION['success'] = "Mã OTP đã được gửi về email";
    header("Location: verify-otp.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div class="auth-container">
        <div class="auth-box">
            <h2 class="auth-title">Quên mật khẩu</h2>

            <?php if (isset($_SESSION['error'])): ?>
                <p style="color:red; text-align:center; margin-bottom:10px;">
                    <?= $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </p>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <p style="color:green; text-align:center; margin-bottom:10px;">
                    <?= $_SESSION['success'];
                    unset($_SESSION['success']); ?>
                </p>
            <?php endif; ?>

            <form method="post">
                <div class="form-group-auth">
                    <i class="fa fa-envelope"></i>
                    <input type="email" name="email" placeholder="Nhập email đã đăng ký" required>
                </div>

                <button type="submit" class="auth-btn">
                    <i class="fa fa-paper-plane"></i> Gửi mã OTP
                </button>

                <div class="auth-link back-login">
                    <a href="login.php">
                        <i class="fa fa-arrow-left"></i> Quay lại đăng nhập
                    </a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>