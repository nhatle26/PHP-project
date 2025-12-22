<?php
require_once('connect.php');
error_reporting(2);
?>

<div class="slider-section wow zoomIn">
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
                ?>
                <div class="item <?= $active ?>">
                    <div class="slide-img">
                        <img src="<?= htmlspecialchars($kq['image']) ?>" alt="Slide">
                    </div>
                </div>
                <?php
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
