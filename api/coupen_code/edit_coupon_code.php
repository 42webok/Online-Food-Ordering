<?php 
include("../config.php");
include("../function.inc.php");
include("../auth.php");
header("Access-Control-Allow-Methods: PUT");

$user = authRequired();

$data = json_decode(file_get_contents("php://input"), true);


$edit_id = get_safe_value($data['edit_id']);
$coupon_code = get_safe_value($data['coupon_code']);
$coupon_type = get_safe_value($data['coupon_type']);
$coupon_value = get_safe_value($data['coupon_value']);
$cart_min_value = get_safe_value($data['cart_min_value']);
$expired_on = get_safe_value($data['expired_on']);

if($coupon_code == '' || $coupon_type == '' || $coupon_value == '' || $cart_min_value == '' || $edit_id == '' || $edit_id == "0"){
    echo json_encode([
        "message" => "parameters are empity and wrong !"
    ]);
    exit;
}


if($coupon_type  == 'P' || $coupon_type == 'F'){
$added_on=date('Y-m-d h:i:s');
$status = 1;
$check = "SELECT * FROM coupon_code WHERE coupon_code = '$coupon_code'";
$check_result = mysqli_query($conn , $check);
if(mysqli_num_rows($check_result) > 0){
    echo json_encode([
        "message" => "Coupon already exist !"
    ]);
}else{
    $query = "UPDATE coupon_code SET coupon_code = '$coupon_code' , coupon_type = '$coupon_type' , coupon_value = '$coupon_value' , cart_min_value = '$cart_min_value' , expired_on = '$expired_on' ,  status = '$status' , added_on = '$added_on' WHERE id = '$edit_id'";
    $result = mysqli_query($conn , $query);
    if($result){
        echo json_encode([
            "success" => "true",
            "message" => "Coupon  Updated Succcessfully !"
        ]);
    }else{
        echo json_encode([
            "error" => "true",
            "message" => "Coupon not Updated !"
        ]);
    }
}
}else{
    echo json_encode([
        "message" => "Wrong Coupen Type  !"
    ]);
    exit;
}





?>