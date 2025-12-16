<?php
    require_once('model/connect.php');

    // Thông báo kết quả gửi liên hệ
    if (isset($_GET['cs'])) {
        echo "<script>alert('Gửi liên hệ thành công!');</script>";
    } elseif (isset($_GET['cf'])) {
        echo "<script>alert('Gửi liên hệ thất bại!');</script>";
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fashion MyLiShop</title>

    <meta name="title" content="Fashion MyLiShop - fashion mylishop">
    <meta name="description" content="Fashion MyLiShop - fashion mylishop">
    <meta name="keywords" content="Fashion MyLiShop - fashion mylishop">
    <meta name="author" content="Hôih My, Y Blir">

    <link rel="icon" type="image/png" href="../images/logohong.png">

    <!-- Bootstrap + FontAwesome -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="../js/mylishop.js"></script>

    <!-- Effects -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <script src="../js/wow.js"></script>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- Nút lên đầu trang -->
    <a href="#" class="back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- Header -->
    <?php include 'model/header.php'; ?>

    <div class="container">
        <ul class="breadcrumb">
            <li><a href="../index.php">Trang chủ</a></li>
            <li>Liên hệ</li>
        </ul>

        <div class="row">
            <div class="col-md-12">
                <div class="titles text-center">
                    <h3><strong>THÔNG TIN LIÊN HỆ</strong></h3>
                </div>

                <form action="lienhe_back.php" method="POST" accept-charset="utf-8">
                    <div class="contents">

                        <div class="form-group">
                            <label>Họ và tên: <span style="color:#f00">*</span></label>
                            <input type="text" name="contact-name" id="contact-name"
                                   class="form-control"
                                   placeholder="Nhập họ tên đầy đủ" required maxlength="255">
                        </div>

                        <div class="form-group">
                            <label>Email: <span style="color:#f00">*</span></label>
                            <input type="email" name="contact-email" id="contact-email"
                                   class="form-control"
                                   placeholder="Nhập email của bạn" required maxlength="255">
                        </div>

                        <div class="form-group">
                            <label>Tiêu đề: <span style="color:#f00">*</span></label>
                            <input type="text" name="contact-subject" id="contact-subject"
                                   class="form-control"
                                   placeholder="Nhập tiêu đề của bạn" required maxlength="255">
                        </div>

                        <div class="form-group">
                            <label>Nội dung: <span style="color:#f00">*</span></label>
                            <textarea name="contact-content" id="ContactContent"
                                      class="form-control" rows="5"
                                      placeholder="Nhập thông tin cần liên hệ..." required></textarea>
                        </div>

                        <div class="text-center">
                            <button type="submit" name="sendcontact" class="btn btn-info">
                                Gửi liên hệ
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Google Maps -->
    <div class="container-fluid">
        <div class="row">
            <div class="map">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d958.5247181884388!2d108.24206672970746!3d16.060358250494478!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31421836ed15dfc9%3A0x99c3cc369a33576c!2sPasserelles+num%C3%A9riques+Vietnam!5e0!3m2!1sen!2s!4v1513938605489"
                    width="100%" height="450" frameborder="0" allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>

    <script> new WOW().init(); </script>
</body>
</html>
