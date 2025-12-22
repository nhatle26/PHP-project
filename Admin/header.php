<?php
// admin/header.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../model/connect.php';

// Kiểm tra đăng nhập và quyền admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? null) !== 'admin') {
    header("Location: ../user/login.php");
    exit();
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - MyLiShop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { padding-top: 56px; }
    .sidebar { min-height: 100vh; }
    .product-thumb { width: 80px; height: 80px; object-fit: cover; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">MyLiShop Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navAdmin">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navAdmin">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <span class="nav-link">Xin chào, <strong><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></strong></span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../index.php">Xem website</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../user/logout.php">Đăng xuất</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">
    <nav class="col-md-2 d-none d-md-block bg-light sidebar py-4">
      <div class="position-sticky">
        <ul class="nav flex-column">
          <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="product-list.php">Sản phẩm</a></li>
          <li class="nav-item"><a class="nav-link" href="product-add.php">Thêm sản phẩm</a></li>
          <li class="nav-item"><a class="nav-link" href="users.php">Người dùng</a></li>
          <li class="nav-item"><a class="nav-link" href="orders.php">Đơn hàng</a></li>
        </ul>
      </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      <!-- Nội dung chính bắt đầu từ đây -->
