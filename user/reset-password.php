<?php
session_start();
require_once "../model/connect.php";

if (!isset($_SESSION['otp_verified'], $_SESSION['reset_email'])) {
    header("Location: forgot-password.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass1 = trim($_POST['password']);
    $pass2 = trim($_POST['confirm_password']);

    if ($pass1 !== $pass2) {
        $_SESSION['error'] = "Mật khẩu không khớp";
        header("Location: reset-password.php");
        exit();
    }

    // ❌ BỎ password_hash
    // $hash = password_hash($pass1, PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "UPDATE users SET password = ? WHERE email = ?"
    );
    // ✅ truyền ĐÚNG 2 biến
    $stmt->bind_param("ss", $pass1, $_SESSION['reset_email']);
    $stmt->execute();

    unset($_SESSION['otp_verified'], $_SESSION['reset_email']);
    $_SESSION['success'] = "Đổi mật khẩu thành công, hãy đăng nhập";
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt lại mật khẩu</title>
</head>
<body>
<h2>Đặt lại mật khẩu</h2>

<?php
if (isset($_SESSION['error'])) {
    echo "<p style='color:red'>{$_SESSION['error']}</p>";
    unset($_SESSION['error']);
}
?>

<form method="post">
    <input type="password" name="password" placeholder="Mật khẩu mới" required>
    <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
    <button type="submit">Đổi mật khẩu</button>
</form>
</body>
</html>
