<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banner PNV 27</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <!-- Animate -->
    <link rel="stylesheet" href="../css/animate.css">
    <!-- Custom -->
    <link rel="stylesheet" href="../css/style.css">

    <style>
        /* Làm thumbnail nhìn “hiện đại” hơn */
        .banner-item {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .banner-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.18);
        }

        .banner-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .title {
            margin: 30px 0 20px;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <h3 class="title text-center wow fadeInDown">BANNER - PNV 27</h3>

        <div class="row g-4"> <!-- g-4 tạo khoảng cách đều và đẹp -->
            <?php
            include("connect.php");

            $sql = "SELECT image FROM slides WHERE status = 2";
            $result = mysqli_query($conn, $sql);

            while ($kq = mysqli_fetch_assoc($result)) { ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="banner-item wow zoomIn">
                        <img src="<?php echo "./" . $kq['image']; ?>" class="banner-img" alt="Banner">
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/wow.min.js"></script>
    <script>
        new WOW().init();
    </script>
</body>

</html>
