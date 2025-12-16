<?php
require_once "../model/connect.php";

$token = $_GET['token'] ?? '';

$stmt = $conn->prepare(
    "UPDATE users 
     SET is_verified = 1, verify_token = NULL 
     WHERE verify_token = ?"
);
$stmt->bind_param("s", $token);
$stmt->execute();

echo ($stmt->affected_rows > 0)
    ? "Xác minh thành công. Bạn có thể đăng nhập."
    : "Link không hợp lệ hoặc đã xác minh.";
