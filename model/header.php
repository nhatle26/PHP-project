<?php
// header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__.'/connect.php';

$base = "/php_project";

$username = $_SESSION['username'] ?? null;
$role = $_SESSION['role'] ?? null; // 'admin' hoặc 'user'
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<header class="header-main">
    <div class="container header-main-inner">

        <!-- ACCOUNT AREA -->
        <div class="account-area">

            <?php if ($username): ?>
                <div class="user-info">
                    <i class="fa fa-user"></i>
                    <a href="<?= ($role==='admin')?$base.'/admin/index.php':$base.'/user/profile.php' ?>">
                        <?= htmlspecialchars($username) ?> 
                    </a>
                </div>
                <a href="<?= $base ?>/user/logout.php" class="logout">
                    <i class="fa fa-sign-out"></i> Đăng xuất
                </a>
            <?php else: ?>
                <a href="<?= $base ?>/user/login.php"><i class="fa fa-sign-in"></i> Đăng nhập</a>
                <a href="<?= $base ?>/user/register.php"><i class="fa fa-user-plus"></i> Đăng ký</a>
            <?php endif; ?>

            <!-- CART -->
            <a href="<?= $base ?>/view-cart.php" class="cart">
                <i class="fa fa-shopping-cart"></i> <?= $cart_count ?>
            </a>

        </div>
        <!-- SEARCH -->
        <div class="header-search">
            <form action="<?= $base ?>/search.php" method="get" class="search-form">
                <input type="search" name="q" placeholder="Tìm sản phẩm..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" class="search-input">
                <button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
            </form>
        </div>
    </div>
</header>
