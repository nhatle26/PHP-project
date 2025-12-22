<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div class="auth-container">
        <div class="auth-box">
            <h2 class="auth-title">Đăng nhập</h2>

            <?php
            if (isset($_SESSION['error'])):
                echo '<p style="color:red; text-align:center; margin-bottom:10px;">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            endif;

            if (isset($_SESSION['success'])):
                echo '<p style="color:green; text-align:center; margin-bottom:10px;">' . $_SESSION['success'] . '</p>';
                unset($_SESSION['success']);
            endif;
            ?>

            <form action="login-back.php" method="POST">
                <div class="form-group-auth">
                    <i class="fa fa-user"></i>
                    <input type="text" name="username" placeholder="Tên đăng nhập" required>
                </div>

                <div class="form-group-auth">
                    <i class="fa fa-lock"></i>
                    <input type="password" name="password" placeholder="Mật khẩu" required>
                </div>

                <!-- 🔹 QUÊN MẬT KHẨU -->
                <div class="forgot-password">
                    <a href="forgot-password.php">
                        <i class="fa fa-key"></i> Quên mật khẩu?
                    </a>
                </div>

                <button type="submit" name="submit" class="auth-btn">Đăng nhập</button>

                <div class="auth-link">
                    <span>Chưa có tài khoản?</span>
                    <a href="register.php" class="highlight">Đăng ký ngay</a>
                </div>

            </form>
        </div>
    </div>

</body>

</html>