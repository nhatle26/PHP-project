<?php
session_start();
require_once "../model/connect.php";

if (!isset($_SESSION['register_otp'])) {
    header("Location: register.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputOtp = trim($_POST['otp']);
    $data = $_SESSION['register_otp'];

    if (time() > $data['expire']) {
        unset($_SESSION['register_otp']);
        $_SESSION['error'] = "OTP đã hết hạn!";
        header("Location: register.php");
        exit();
    }

    if ($inputOtp == $data['otp']) {
        $stmt = $conn->prepare(
            "INSERT INTO users (fullname, username, email, password, role, is_verified, created)
             VALUES (?, ?, ?, ?, 'user', 1, NOW())"
        );
        $stmt->bind_param("ssss", $data['fullname'], $data['username'], $data['email'], $data['password']);
        $stmt->execute();

        unset($_SESSION['register_otp']);
        $_SESSION['success'] = "Đăng ký thành công! Hãy đăng nhập";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "OTP không đúng!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận OTP</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="auth-container">
    <div class="auth-box">
        <h2 class="auth-title">Nhập mã OTP</h2>

        <?php
        if(isset($_SESSION['error'])) {
            echo '<p style="color:red;text-align:center">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])) {
            echo '<p style="color:green;text-align:center">' . $_SESSION['success'] . '</p>';
            unset($_SESSION['success']);
        }
        ?>

        <form method="POST">
            <div class="form-group-auth">
                <input type="text" name="otp" placeholder="Nhập mã OTP" required>
            </div>
            <button type="submit" class="auth-btn">Xác nhận</button>
        </form>
    </div>
</div>
</body>
</html>
