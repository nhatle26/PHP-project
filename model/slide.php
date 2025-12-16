<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slider</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/animate.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
<?php
require_once('connect.php');
error_reporting(2);
?>

<div class="container slider-section wow zoomIn">
    <div id="myCarousel" class="carousel slide" data-ride="carousel">

        <!-- Indicators -->
        <ol class="carousel-indicators">
            <?php
            $sql = "SELECT image FROM slides WHERE status=1";
            $result = mysqli_query($conn, $sql);
            $count = mysqli_num_rows($result);
            for ($i = 0; $i < $count; $i++) {
                echo '<li data-target="#myCarousel" data-slide-to="'.$i.'" '.($i==0 ? 'class="active"' : '').'></li>';
            }
            ?>
        </ol>

        <!-- Slides -->
        <div class="carousel-inner">
            <?php
            mysqli_data_seek($result, 0);
            $index = 0;

            while ($kq = mysqli_fetch_assoc($result)) {
                $active = ($index == 0) ? "active" : "";
                echo '
                    <div class="item '.$active.'">
                        <img src="./'.$kq['image'].'" alt="Slide" class="carousel-img">
                    </div>
                ';
                $index++;
            }
            ?>
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
        </a>

        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
    </div>
</div>

<script src="../js/mylishop.js"></script>
</body>
</html>
