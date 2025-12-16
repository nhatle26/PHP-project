<div class="container partner-section">
    <h3 class="title text-center">PARTNER - PNV 27</h3>

    <div class="row partner-list wow lightSpeedIn">
        <?php
        include("connect.php");
        $sql = "SELECT image FROM slides WHERE status=3";
        $result = mysqli_query($conn, $sql);

        while ($kq = mysqli_fetch_assoc($result)) {
        ?>
            <div class="col-md-2 col-sm-4 col-xs-6 partner-item">
                <div class="partner-box">
                    <img src="<?php echo "./" . $kq['image']; ?>" alt="partner-logo">
                </div>
            </div>
        <?php } ?>
    </div>
</div>
