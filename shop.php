<?php 
include("header.php");
$path = "admin/assets/uploads/";
?>

<!-- Main code of body start here -->

<div class="breadcrumb-area gray-bg">
    <div class="container">
        <div class="breadcrumb-content">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li class="active">Shop Grid Style </li>
            </ul>
        </div>
    </div>
</div>
<div class="shop-page-area pt-100 pb-100">
    <div class="container">
        <div class="row flex-row-reverse">
            <div class="col-lg-9">
                <!-- <div class="banner-area pb-30">
                    <a href="product-details.html"><img alt="" src="assets/img/banner/banner-49.jpg"></a>
                </div> -->

                <div class="grid-list-product-wrapper">
                    <div class="product-grid product-view pb-20">
                        <?php 
                        $cat_id = 0 ;
                        $p_q = "SELECT * FROM dish WHERE status = 1 ";
                        if(isset($_GET['cat_id']) && isset($_GET['cat_id']) > 0){
                            $cat_id = $_GET['cat_id'];
                            $p_q.= "And category_id = $cat_id " ;
                        }
                        $p_q.= " order by id desc ";

                         $product_query = mysqli_query($con , $p_q)
                          
                        ?>
                        <div class="row">
                            
                            <?php 
                            if(mysqli_num_rows($product_query) > 0){

                             while($product_row = mysqli_fetch_assoc($product_query)){

                             
                            ?>
                            <div class="product-width col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12 mb-30">
                                <div class="product-wrapper">
                                    <div class="product-img">
                                        <a href="javascript:void(0);">
                                            <img src="<?php echo $path.$product_row['image'] ?>" alt="">
                                        </a>
                                    </div>
                                    <div class="product-content">
                                        <h4>
                                            <a href="javascript:void(0);"><?php echo $product_row['dish'] ?> </a>
                                        </h4>
                                        <div class="product-price-wrapper">
                                            <span>$100.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php }
                              }else{
                                echo "<h3 class='text-center'>No Product Exit</h3>";
                              } ?>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-3">
                <div class="shop-sidebar-wrapper gray-bg-7 shop-sidebar-mrg">
                    <div class="shop-widget">
                        <h4 class="shop-sidebar-title">Shop By Categories</h4>
                        <div class="shop-catigory">
                            <ul id="faq">
                                <?php 
                                 $cat_sql = "SELECT * FROM category ORDER BY order_number DESC";
                                 $cat_res = mysqli_query($con , $cat_sql);
                                //  echo $cat_id;
                                 
                                 while($cat_row = mysqli_fetch_assoc($cat_res)){
                                    $class = '';
                                   if($cat_row['id'] == $cat_id){
                                    $class = 'active';
                                   }
                                ?>
                                <li> <a class="<?php echo $class ?>" href="shop.php?cat_id=<?php echo $cat_row['id'] ?>"><?php echo $cat_row['category'] ?></a> </li>
                                <?php
                                  }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- footer start here -->

<?php 
include("footer.php");
?>