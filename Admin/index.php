<?php
session_start();
require_once '../model/connect.php';
require_once 'header.php';

// Kiểm tra login
$role = $_SESSION['role'] ?? 0; // 1 = admin, 0 = user
if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/login.php");
    exit();
}

// Lấy thống kê
$pr = $conn->query("SELECT COUNT(*) AS c FROM products")->fetch_assoc()['c'] ?? 0;
$us = $conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'] ?? 0;
$or = $conn->query("SELECT COUNT(*) AS c FROM orders")->fetch_assoc()['c'] ?? 0;

// Lấy danh sách user
$userList = $conn->query("SELECT id, username, role FROM users")->fetch_all(MYSQLI_ASSOC);
?>

<h1 class="h2">Bảng điều khiển</h1>
<p class="mb-4">Tổng quan nhanh</p>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Sản phẩm</h5>
                <p class="card-text fs-3"><?= $pr ?></p>
                <?php if($role == 1): ?>
                    <a href="product-list.php" class="btn btn-primary btn-sm">Quản lý</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Người dùng</h5>
                <p class="card-text fs-3"><?= $us ?></p>
                <a href="users.php" class="btn btn-primary btn-sm">Quản lý</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Đơn hàng</h5>
                <p class="card-text fs-3"><?= $or ?></p>
                <?php if($role == 1): ?>
                    <a href="orders.php" class="btn btn-primary btn-sm">Quản lý</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<h3>Danh sách người dùng</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Username</th>
            <th>Role</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($userList as $i => $u): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td>
                    <?php if($role == 1): ?>
                        <!-- Admin click username để quản lý user -->
                        <a href="edit-user.php?id=<?= $u['id'] ?>"><?= htmlspecialchars($u['username']) ?></a>
                    <?php else: ?>
                        <!-- User chỉ vào profile của mình -->
                        <a href="profile.php"><?= htmlspecialchars($u['username']) ?></a>
                    <?php endif; ?>
                </td>
                <td><?= $u['role'] == 1 ? 'Admin' : 'User' ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
require_once 'footer.php';
?>
