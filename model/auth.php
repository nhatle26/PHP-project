<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Nếu chưa login hoặc không phải admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? 0) != 1) {
    header("Location: ../user/login.php");
    exit();
}
