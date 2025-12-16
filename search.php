<?php
    require_once('model/connect.php');
    $prd = 0;
    if (isset($_SESSION['cart']))
    {
        $prd = count($_SESSION['cart']);
    }

    // Search products
    $message = "Không thể tìm kiếm được, vui lòng kiểm tra lại!";
    if(isset($_POST['search']))
    {
        $searchKeyword = $_POST['search'];
        $sql = "SELECT id,image,name,price FROM products WHERE (name LIKE '%$searchKeyword%')";
    }
    else {
        echo $message;
    }

    $resultSearch = mysqli_query($conn,$sql);
    if($resultSearch)
    {
        $totalnumber = mysqli_num_rows($resultSearch);
    }
    else {
        $totalnumber = 0;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Fashion MyLiShop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Fashion MyLiShop - fashion mylishop"/>
    <meta name="description" content="Fashion MyLiShop - fashion mylishop" />
    <meta name="keywords" content="Fashion MyLiShop - fashion mylishop" />
    <meta name="author" content="Hôih My" />
    <meta name="author" content="Y Blir" />
    <link rel="icon" type="image/png" href="images/logohong.png">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" type="text/css" href="admin/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js" charset="utf-8"></script> -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css'> -->

    <!-- customer js -->
    <script src='js/wow.js'></script>
    <script type="text/javascript" src="js/mylishop.js"></script>
    <!-- customer css -->
    <link rel="stylesheet" type="text/css" href="css/animate.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">

</head>
<body>
    <!-- button top -->
    <a href="#" class="back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- Header -->
    <?php include("model/header.php"); ?>
    <!-- /header -->

    <div class="container">
        <ul class="breadcrumb">
            <li><a href="index.php">Trang chủ</a></li>
            <li>Tìm kiếm sản phẩm</li>
        </ul><!-- /breadcrumb -->
        <!-- Content -->
        <div class="container">
        	<div class="row">
            	<div class="col-md-12 col-sm-12 col-xs-12">
                	<div class="product-main">
                    	<div class="title-product-main">
                        	<h3 class="section-title"> Kết Quả Tìm Kiếm </h3>
                            <p style="color: black; margin-left: 10px;">Có <?php echo $totalnumber; ?> sản phẩm được tìm thấy</p>
                    	</div>
                        <div class="content-product-main">
                            <div class="row">
                                <?php
                                    
                                    $i = 0;
                                    while ($kq = mysqli_fetch_assoc($resultSearch))
                                    {
                                        $i++;
                                ?>                        
                                        <div class="col-md-3 col-sm-6 text-center">
                                            <div class="thumbnail">
                                                <div class="hoverimage1">
                                                    <img src= "<?php echo $kq['image']; ?>" alt="Generic placeholder thumbnail" width="100%" height="300">
                                                </div>
                                                <div class="name-product">
                                                    <?php echo $kq['name']; ?>
                                                </div>
                                                <div class="price">
                                                    Giá: <?php echo $kq['price']; ?><sup> đ</sup>
                                                </div>
                                                <div class="product-info">
                                                    <!-- <a href="addcart.php?id=<?php echo $kq['id']; ?>">
                                                        <button type="button" class="btn btn-primary">
                                                            <label style="color: red;">&hearts;</label> Mua hàng  <label style="color: red;">&hearts;</label>
                                                        </button>
                                                    </a> -->
                                                    <a href="detail.php?id=<?php echo $kq['id']; ?>">
                                                        <button type="button" class="btn btn-primary">
                                                            <label style="color: red;">&hearts;</label> Chi Tiết <label style="color: red;">&hearts;</label>
                                                        </button>
                                                    </a>
                                                </div><!-- /product-info -->
                                            </div><!-- /thumbnail -->
                                        </div><!-- /col -->
                                <?php
                                    }
                                ?>
                                    <div class="error-search" style="color: #FF0000; font-weight: bold; margin-left: 15px;">
                                <?php
                                        if($i <= 0)
                                        {
                                            echo "KÍNH CHÀO QUÝ KHÁCH VÀ XIN LỖI VÌ SẢN PHẨM BẠN TÌM KHÔNG TỒN TẠI!";
                                        }
                                ?>
                                    </div>

                            </div><!-- /row -->
                        </div><!-- /tìm kiếm sản phẩm -->
                    </div><!-- /.product-main -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container -->
        

    <!-- footer -->
    <div class="container">
        <?php include("model/footer.php"); ?>
    </div>
    <!-- /footer -->

<script>
    new WOW().init();
</script>
</body>
</html>