<?php
session_start();

if (
    !isset($_SESSION['otp_type']) ||
    $_SESSION['otp_type'] !== 'forgot' ||
    !isset($_SESSION['forgot_otp'])
) {
    header("Location: forgot-password.php");
    exit();
}

$data = $_SESSION['forgot_otp'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputOtp = trim($_POST['otp']);

    if (time() > $data['expire']) {
        unset($_SESSION['forgot_otp'], $_SESSION['otp_type']);
        $_SESSION['error'] = "OTP đã hết hạn";
        header("Location: forgot-password.php");
        exit();
    }

    if ($inputOtp != $data['otp']) {
        $_SESSION['error'] = "OTP không đúng";
    } else {
        $_SESSION['otp_verified'] = true;
        $_SESSION['reset_email'] = $data['email'];

        unset($_SESSION['forgot_otp'], $_SESSION['otp_type']);
        header("Location: reset-password.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận OTP</title>

    <!-- DÙNG CHUNG CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-box">
        <h2 class="auth-title">Xác nhận OTP</h2>

        <?php
        if (isset($_SESSION['error'])) {
            echo '<p style="color:red;text-align:center;margin-bottom:10px">'
                . $_SESSION['error'] .
                '</p>';
            unset($_SESSION['error']);
        }
        ?>

        <form method="post">
            <div class="form-group-auth">
                <i class="fa fa-key"></i>
                <input type="text"
                       name="otp"
                       placeholder="Nhập mã OTP"
                       required>
            </div>

            <button type="submit" class="auth-btn">
                Xác nhận
            </button>

            <div class="auth-link">
                <a href="forgot-password.php">Gửi lại OTP</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
