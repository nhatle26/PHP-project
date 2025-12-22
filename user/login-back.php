<?php
session_start();
require_once "../model/connect.php";

if(!isset($_POST['submit'])){
    header("Location: login.php");
    exit();
}

$username = trim($_POST['username']);
$password = trim($_POST['password']);

$stmt = $conn->prepare("SELECT id, username, password, role, is_locked FROM users WHERE username=? LIMIT 1");
$stmt->bind_param("s",$username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if(!$user || $password !== $user['password']){
    $_SESSION['error'] = "Sai tài khoản hoặc mật khẩu!";
    header("Location: login.php");
    exit();
}

// Kiểm tra tài khoản bị khóa
if ($user['is_locked']) {
    $_SESSION['error'] = "Tài khoản này đã bị khóa! Vui lòng liên hệ quản trị viên.";
    header("Location: login.php");
    exit();
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = ((int)$user['role'] === 1)?'admin':'user';

// Điều hướng dựa trên vai trò
if ($_SESSION['role'] === 'admin') {
    header("Location: ../admin/index.php");
} else {
    header("Location: ../user/profile.php");
}
exit();
