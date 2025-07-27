<?php
include( 'admin/database.inc.php' );
// include( 'function_inc.php' );
session_start();
// $cart_data_array = [];


?>

<!doctype html>
<html class='no-js' lang='zxx'>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='x-ua-compatible' content='ie=edge'>
    <title>Billy - Food & Drink </title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' href='assets/css/bootstrap.min.css'>
    <link rel='stylesheet' href='assets/css/animate.css'>
    <link rel='stylesheet' href='assets/css/owl.carousel.min.css'>
    <link rel='stylesheet' href='assets/css/slick.css'>
    <link rel='stylesheet' href='assets/css/chosen.min.css'>
    <link rel='stylesheet' href='assets/css/ionicons.min.css'>
    <link rel='stylesheet' href='assets/css/font-awesome.min.css'>
    <link rel='stylesheet' href='assets/css/simple-line-icons.css'>
    <link rel='stylesheet' href='assets/css/jquery-ui.css'>
    <link rel='stylesheet' href='assets/css/meanmenu.min.css'>
    <link rel='stylesheet' href='assets/css/style.css'>
    <link rel='stylesheet' href='assets/css/responsive.css'>
    <script src='assets/js/vendor/modernizr-2.8.3.min.js'></script>
</head>

<body>
    <!-- header start -->
    <header class='header-area'>
        <div class='header-top black-bg'>
            <div class='container'>
                <div class='row'>
                    <?php
if ( isset( $_SESSION[ 'FOOD_USERNAME' ] ) ) {
    ?>
                    <div class='col-lg-4 col-md-4 col-12 col-sm-4'>
                        <div class='welcome-area'>
                            <p>Welcome <b><?php echo $_SESSION[ 'FOOD_USERNAME' ] ?></b> </p>
                        </div>
                    </div>
                    <?php
}

?>
                    <div class='col-lg-8 col-md-8 col-12 col-sm-8'>
                        <div class='account-curr-lang-wrap f-right'>
                            <?php
if ( isset( $_SESSION[ 'FOOD_USERNAME' ] ) ) {
    ?>
                            <ul>
                                <li class='top-hover'><a href='#'>Setting <i class='ion-chevron-down'></i></a>
                                    <ul>
                                        <li><a href='wishlist.html'>My Cart </a></li>
                                        <li><a href='profile.php'>My Profile</a></li>
                                        <li><a href='logout.php'>Logout</a></li>
                                    </ul>
                                </li>
                            </ul>
                            <?php
}
?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class='header-middle'>
            <div class='container'>
                <div class='row'>
                    <div class='col-lg-3 col-md-4 col-12 col-sm-4'>
                        <div class='logo'>
                            <a href='index.php'>
                                <img alt='logo' src='assets/img/logo/logo.png'>
                            </a>
                        </div>
                    </div>
                    <div class='col-lg-9 col-md-8 col-12 col-sm-8'>
                        <div class='header-middle-right f-right'>
                            <?php
if ( !isset( $_SESSION[ 'FOOD_USERNAME' ] ) || !isset( $_SESSION[ 'FOOD_ID' ] ) ) {
    ?>
                            <div class='header-login'>
                                <a href='login_register.php'>
                                    <div class='header-icon-style'>
                                        <i class='icon-user icons'></i>
                                    </div>
                                    <div class='login-text-content'>
                                        <p>Register <br> or <span>Sign in</span></p>
                                    </div>
                                </a>
                            </div>
                            <?php
}
?>
                            <div class='header-wishlist'>
                                &nbsp;
                            </div>
                            <div class='header-cart'>
                                <a href='#'>
                                    <div class='header-icon-style'>
                                        <i class='icon-handbag icons'></i>
                                        <span class='count-style'>02</span>
                                    </div>
                                    <div class='cart-text'>
                                        <span class='digit'>My Cart</span>
                                        <span class='cart-digit-bold'>$209.00</span>
                                    </div>
                                </a>
                                <div class='shopping-cart-content'>
                                    <ul>
                                        <li class='single-shopping-cart'>
                                            <div class='shopping-cart-img'>
                                                <a href='#'><img alt='' src='assets/img/cart/cart-1.jpg'></a>
                                            </div>
                                            <div class='shopping-cart-title'>
                                                <h4><a href='#'>Phantom Remote </a></h4>
                                                <h6>Qty: 02</h6>
                                                <span>$260.00</span>
                                            </div>
                                            <div class='shopping-cart-delete'>
                                                <a href='#'><i class='ion ion-close'></i></a>
                                            </div>
                                        </li>
                                        <li class='single-shopping-cart'>
                                            <div class='shopping-cart-img'>
                                                <a href='#'><img alt='' src='assets/img/cart/cart-2.jpg'></a>
                                            </div>
                                            <div class='shopping-cart-title'>
                                                <h4><a href='#'>Phantom Remote</a></h4>
                                                <h6>Qty: 02</h6>
                                                <span>$260.00</span>
                                            </div>
                                            <div class='shopping-cart-delete'>
                                                <a href='#'><i class='ion ion-close'></i></a>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class='shopping-cart-total'>
                                        <h4>Shipping : <span>$20.00</span></h4>
                                        <h4>Total : <span class='shop-total'>$260.00</span></h4>
                                    </div>
                                    <div class='shopping-cart-btn'>
                                        <a href='cart-page.php'>view cart</a>
                                        <a href='checkout.php'>checkout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class='header-bottom transparent-bar black-bg'>
            <div class='container'>
                <div class='row'>
                    <div class='col-lg-12 col-md-12 col-12'>
                        <div class='main-menu'>
                            <nav>
                                <ul>
                                    <li><a href='index.php'>Home</a></li>
                                    <li><a href='about-us.php'>About</a></li>
                                    <li><a href='contact-us.php'>Contact us</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- mobile-menu-area-start -->
        <div class='mobile-menu-area'>
            <div class='container'>
                <div class='row'>
                    <div class='col-lg-12'>
                        <div class='mobile-menu'>
                            <nav id='mobile-menu-active'>
                                <ul class='menu-overflow' id='nav'>
                                    <li><a href='index.php'>Home</a></li>
                                    <li><a href='about-us.php'>About</a></li>
                                    <li><a href='contact-us.php'>Contact Us</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- mobile-menu-area-end -->
    </header>