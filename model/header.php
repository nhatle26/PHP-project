<?php
// Bật session và kết nối DB
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL ^ E_DEPRECATED); // Tắt cảnh báo deprecated
require_once('connect.php');

// Lấy số lượng sản phẩm trong giỏ hàng
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Hiển thị thông báo khi đăng nhập thành công
if (isset($_GET['ls'])) {
    echo "<script>alert('Bạn đã đăng nhập thành công!');</script>";
}

// Lấy thông tin người dùng nếu đã đăng nhập
$username = $_SESSION['username'] ?? null;
?>

<header class="site-header">

    <!-- TOP BAR -->
    <div class="header-top wow fadeInDown">
        <div class="container">
            <div class="header-top-right">
                <a href="https://facebook.com" target="_blank"><i class="fa fa-facebook"></i></a>
                <a href="https://twitter.com" target="_blank"><i class="fa fa-twitter"></i></a>
                <a href="https://rss.com" target="_blank"><i class="fa fa-rss"></i></a>
                <a href="https://youtube.com" target="_blank"><i class="fa fa-youtube"></i></a>
                <a href="https://google.com" target="_blank"><i class="fa fa-google-plus"></i></a>
                <a href="https://linkedin.com" target="_blank"><i class="fa fa-linkedin"></i></a>
            </div>
        </div>
    </div>

    <!-- MAIN HEADER -->
    <div class="header-main">
        <div class="container">
            <!-- ACCOUNT -->
            <div class="account-area">
                <?php if ($username): ?>
                    <div class="user-info">
                        <i class="fa fa-user"></i>
                        <a href="user/profile.php" style="color: inherit; text-decoration: none;">
                            <?= htmlspecialchars($username) ?>
                        </a>
                    </div>

                    <a class="logout" href="user/logout.php">
                        <i class="fa fa-sign-out"></i> Đăng xuất
                    </a>
                <?php else: ?>
                    <a href="user/login.php">
                        <i class="fa fa-user"></i> Đăng nhập
                    </a>
                    <a href="user/register.php">
                        <i class="fa fa-users"></i> Đăng ký
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <!-- NAVIGATION -->
    <nav class="navbar navbar-default">
        <div class="container">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-menu">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="main-menu">

                <ul class="nav navbar-nav">
                    <li><a href="index.php">Trang Chủ</a></li>
                    <li><a href="introduceshop.php">Dịch Vụ</a></li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sản Phẩm <b
                                class="fa fa-caret-down"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="fashionboy.php">Thời Trang Nam</a></li>
                            <li><a href="fashiongirl.php">Thời Trang Nữ</a></li>
                            <li><a href="newproduct.php">Hàng Mới Về</a></li>
                        </ul>
                    </li>

                    <li><a href="lienhe.php">Liên Hệ</a></li>
                </ul>

                <!-- SEARCH + CART -->
                <div class="nav-right">
                    <form action="search.php" method="POST" class="header-search">
                        <input type="text" name="search" maxlength="50" class="form-control search-input"
                            placeholder="Nhập từ khóa...">
                        <button class="btn-search"><i class="fa fa-search"></i></button>
                    </form>

                    <div class="header-cart">
                        <a href="view-cart.php">
                            <i class="fa fa-shopping-cart"></i>
                            <span class="count"><?= $cart_count ?></span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </nav>

</header>