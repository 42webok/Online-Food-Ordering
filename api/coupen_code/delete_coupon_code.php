<?php 
include("../config.php");
include("../function.inc.php");
include("../auth.php");
header("Access-Control-Allow-Methods: DELETE");

$user = authRequired();

$data = json_decode(file_get_contents("php://input"), true);
$del_id = $data['del_id'];

if($del_id == 0 || $del_id == ""){
    echo json_encode([
        "message" => "your parameters are Wrong"
    ]);
    exit;
}


$check = "DELETE FROM coupon_code WHERE id = '$del_id'";
$check_result = mysqli_query($conn , $check);
if($check_result){
    echo json_encode([
        "message" => "Coupon  deleted successfully !"
    ]);
}else{
    echo json_encode([
        "message" => "Coupon Not deleted !"
    ]);
}





?>