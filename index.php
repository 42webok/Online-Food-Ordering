<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Billy - Food & Drink eCommerce Bootstrap4 Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <script src="assets/js/vendor/modernizr-2.8.3.min.js"></script>
</head>
<?php
include( 'admin/database.inc.php' );
?>
<body>
    <div class="slider-area">
        <div class="slider-active owl-dot-style owl-carousel">
            <?php 
             $query = "SELECT * FROM manage_banner WHERE status = '1'";
             $result = mysqli_query($con , $query);
             while($row = mysqli_fetch_assoc($result)){
            ?>
            <div class="single-slider active pt-210 pb-220 bg-img"
                style="background-image:url(admin/assets/uploads/<?php echo $row['image'] ?>);">
                <div class="container">
                    <div class="slider-content slider-animated-1">
                        <h1 class="animated"><?php echo $row['heading'] ?></h1>
                        <h3 class="animated"><?php echo $row['sub_heading'] ?></h3>
                        <div class="slider-btn mt-90">
                            <a class="animated" href="<?php echo $row['link'] ?>"><?php echo $row['button_txt'] ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
             }
            ?>
        </div>
    </div>

    <script src="assets/js/vendor/jquery-1.12.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/imagesloaded.pkgd.min.js"></script>
    <script src="assets/js/isotope.pkgd.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>