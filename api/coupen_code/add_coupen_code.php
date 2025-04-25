<?php 
include("../config.php");
include("../function.inc.php");
include("../auth.php");

$user = authRequired();

$data = json_decode(file_get_contents("php://input"), true);


$coupen_code = get_safe_value($data['coupon_code']);
$coupen_type = get_safe_value($data['coupon_type']);
$coupen_value = get_safe_value($data['coupon_value']);
$cart_min_value = get_safe_value($data['cart_min_value']);
$expired_on = get_safe_value($data['expired_on']);

if($coupen_code == '' || $coupen_type == '' || $coupen_value == '' || $cart_min_value == ''){
    echo json_encode([
        "message" => "Please fill all fields !"
    ]);
    exit;
}

if($coupen_type  == 'P' || $coupen_type == 'F'){
    $added_on=date('Y-m-d h:i:s');
    $status = 1;
    $check = "SELECT * FROM coupon_code WHERE coupon_code = '$coupen_code'";
    $check_result = mysqli_query($conn , $check);
    if(mysqli_num_rows($check_result) > 0){
        echo json_encode([
            "message" => "Coupen Code already exist !"
        ]);
    }else{
        $query = "INSERT INTO coupon_code (coupon_code , coupon_type , coupon_value , cart_min_value , expired_on , added_on , status) VALUES ('$coupen_code' , '$coupen_type' , '$coupen_value' , '$cart_min_value' , '$expired_on' , '$added_on' , '$status')";
        $result = mysqli_query($conn , $query);
        if($result){
            echo json_encode([
                "success" => "true",
                "message" => "Coupen Code Inserted Succcessfully !"
            ]);
        }else{
            echo json_encode([
                "error" => "true",
                "message" => "Coupen Code not Inserted !"
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