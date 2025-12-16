<?php
session_start();
require_once "../model/connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$fullname = trim($_POST['fullname']);
$email    = trim($_POST['email']);
$phone    = trim($_POST['phone']);
$address  = trim($_POST['address']);
$user_id  = $_SESSION['user_id'];

$stmt = $conn->prepare("
    UPDATE users
    SET fullname = ?, email = ?, phone = ?, address = ?
    WHERE id = ?
");
$stmt->bind_param("ssssi", $fullname, $email, $phone, $address, $user_id);
$stmt->execute();

$_SESSION['msg'] = "Cập nhật thông tin thành công!";
header("Location: profile.php");
exit();
