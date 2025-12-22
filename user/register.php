<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div class="auth-container">
        <div class="auth-box">
            <h2 class="auth-title">Đăng ký</h2>

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

            <form action="register-back.php" method="POST">
                <div class="form-group-auth">
                    <i class="fa fa-user"></i>
                    <input type="text" name="fullname" placeholder="Họ và tên" required>
                </div>

                <div class="form-group-auth">
                    <i class="fa fa-user-circle"></i>
                    <input type="text" name="username" placeholder="Tài khoản" required>
                </div>

                <div class="form-group-auth">
                    <i class="fa fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email" required>
                </div>

                <div class="form-group-auth">
                    <i class="fa fa-lock"></i>
                    <input type="password" name="password" placeholder="Mật khẩu" required>
                </div>

                <button type="submit" name="submit" class="auth-btn">Đăng ký</button>

                <div class="auth-link">
                    <span>Đã có tài khoản?</span>
                    <a href="login.php" class="highlight">Đăng nhập ngay</a>
                </div>

            </form>
        </div>
    </div>

</body>

</html>