<?php 
include("header.php");
include("function_inc.php");
$path = "admin/assets/uploads/";
$dish_array = array("Veg" , "Non-Veg");
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
        <div class="row mb-5 pb-3">
            <div class="col-10">
            </div>
            <div class="col-2">
                <label for="Dish_type">Dish Type</label>
                <select class="form-control dish-type-select">
                    <option value="" selected disabled>Select Dish Type</option>
                    <option value="">Both</option>
                    <?php
                    foreach($dish_array as $dish){
                        echo '<option vaue="'.$dish.'">'.$dish.'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row flex-row-reverse ">
            <div class="col-lg-9">
                <!-- <div class="banner-area pb-30">
                    <a href="product-details.html"><img alt="" src="assets/img/banner/banner-49.jpg"></a>
                </div> -->

                <div class="grid-list-product-wrapper">
                    <div class="product-grid product-view pb-20">
                        <?php 
                        $cat_id = 0 ;
                        $p_q = "SELECT * FROM dish WHERE status = 1 ";
                        $p_q.= " order by id desc ";

                         $product_query = mysqli_query($con , $p_q)
                          
                        ?>
                        <div class="row" id="sort_data">

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
                                <!-- <li> <a class="<?php echo $class ?>" href="shop.php?cat_id=<?php echo $cat_row['id'] ?>"><?php echo $cat_row['category'] ?></a> </li> -->
                                <li class="d-flex align-items-center flex-row gap-2 custom_li"> <input
                                        value="<?php echo $cat_row['id'] ?>" class="form-control custom_check_box"
                                        type="checkbox" name="category"><?php echo $cat_row['category'] ?> </li>
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


<script>
$(document).ready(function() {

    // Trigger it once when the page loads
    getData();

    // Trigger it whenever a checkbox is changed
    $('.custom_check_box').on('change', function() {
        getData();
    });

    $('.dish-type-select').on('change', function() {
        getData();
    });

    function getData() {
        let checkedValues = [];
        $('.custom_check_box:checked').each(function() {
            checkedValues.push($(this).val());
        });

        let dishType = $('.dish-type-select').val();

        $.ajax({
            type: "POST",
            url: "cat_data.php",
            data: {
                dish_type: dishType,
                cat_id: checkedValues
            },
            success: function(data) {
                let response = JSON.parse(data);
                console.log(response);

                let path = 'admin/assets/uploads/';
                let html = '';

                let cartIds = response.cart_ids; 
                let items = response.products;

                
                if (Array.isArray(items) && items.length > 0) {
                    items.forEach(function(item) {
                        let attrHTML = '';
                        item.attributes.forEach(function(attr , index) {
                             let addedText = attr.dish_detail_id in cartIds ? '(Added)' : '';
                             let addedQnty = attr.dish_detail_id in cartIds ? cartIds[attr.dish_detail_id] : '';
                              
                            attrHTML +=
                                `<label><input class="radio_size" value="${attr.dish_detail_id}"  type="radio" name="attr_${item.id}"> ${attr.attribute} - ${attr.price}</label><sub class="text-success" id="add_data_${attr.dish_detail_id}">${addedText} ${addedQnty} </sub><br>`;
                        })
                        html += `
                            <div class="product-width col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12 mb-30">
                                <div class="product-wrapper">
                                    <div class="product-img">
                                        <a href="javascript:void(0);">
                                            <img src="${path}${item.image}" alt="${item.dish}">
                                        </a>
                                    </div>
                                    <div class="product-content">
                                        <h4><a href="javascript:void(0);">${item.dish}</a></h4>
                                        <div class="product-price-wrapper">
                                            <span class="d-flex gap-2 flex-wrap">${attrHTML}</span>
                                        </div>
                                       <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                           <div class="btn btn-sm btn-dark" onclick="counter(-1 , ${item.id})">-</div>
                                           <input type="number" value="0" width="60px" readonly  id="custom_counter${item.id}">
                                           <div class="btn btn-sm btn-dark" onclick="counter(1 , ${item.id})">+</div>
                                        </div>
                                         <div class="btn btn-sm btn-danger" onclick="add_to_cart(${item.id})">Add to Cart</div>
                                       </div>
                                    </div>
                                </div>
                            </div> 
                        `;
                    });
                    $('#sort_data').html(html);
                } else {
                    $('#sort_data').html(`<p>No data found</p>`);
                }
            }
        });
    }

});


function counter(amount, id) {
    let counter = document.getElementById('custom_counter' + id);
    let val = Number(counter.value) + amount;
    if (val < 0) val = 0;
    counter.value = val;
}

function add_to_cart(item_id) {
    let quantity = $('#custom_counter' + item_id).val();
    let attr_id = $(`input[name="attr_${item_id}"]:checked`).val();
    let added_div = $('#add_data_'+attr_id);


    $.ajax({
        type: "POST",
        url: "save_cart_data.php",
        data: {
            qnty: quantity,
            at_id: attr_id,
            type: 'add',
        },
        success: function(data) {
            let dt = JSON.parse(data);
            if (dt.msg == 'no_login') {
                window.location.href = 'login.php';
            } else if (dt.status == 'success') {
                added_div.html('(Added) ' + quantity);
                Swal.fire({
                    title: "Good job!",
                    text: dt.msg,
                    icon: "success"
                });
            } else if (dt.status == 'error') {
                Swal.fire({
                    title: "Good job!",
                    text: dt.msg,
                    icon: "error"
                });
            }
        }
    })
}
</script>