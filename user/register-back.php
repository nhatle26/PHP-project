<?php
session_start();
require_once "../model/connect.php";
require_once "../model/send_otp.php";

if (!isset($_POST['submit'])) {
    header("Location: register.php");
    exit();
}

$fullname = trim($_POST['fullname'] ?? '');
$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if (!$fullname || !$username || !$email || !$password) {
    $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
    header("Location: register.php");
    exit();
}

// Kiểm tra trùng username/email
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['error'] = "Tài khoản hoặc email đã tồn tại!";
    header("Location: register.php");
    exit();
}

// Tạo OTP 6 số
$otp = rand(100000, 999999);

// Lưu tạm vào session
$_SESSION['register_otp'] = [
    'fullname' => $fullname,
    'username' => $username,
    'email'    => $email,
    'password' => $password,  // lưu dạng thường
    'otp'      => $otp,
    'expire'   => time() + 300
];

// Gửi OTP
if (!sendOTP($email, $otp)) {
    $_SESSION['error'] = "Gửi OTP thất bại!";
    header("Location: register.php");
    exit();
}

$_SESSION['success'] = "Mã OTP đã được gửi đến email của bạn";
header("Location: verify-otp.php");
exit();
?>