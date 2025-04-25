<?php 
include("../config.php");
include("../function.inc.php");
include("../auth.php");
header("Access-Control-Allow-Methods: PUT");

$user = authRequired();

$data = json_decode(file_get_contents("php://input"), true);


$edit_id = get_safe_value($data['edit_id']);
$category = get_safe_value($data['category']);
$order_number = get_safe_value($data['order_number']);

if($category == '' || $order_number == '' || $edit_id == '' || $edit_id == "0"){
    echo json_encode([
        "message" => "parameters are empity and wrong !"
    ]);
    exit;
}

$added_on=date('Y-m-d h:i:s');
$status = 1;
$check = "SELECT * FROM category WHERE category = '$category'";
$check_result = mysqli_query($conn , $check);
if(mysqli_num_rows($check_result) > 0){
    echo json_encode([
        "message" => "Category already exist !"
    ]);
}else{
    $query = "UPDATE category SET category = '$category' , order_number = '$order_number' , status = '$status' , added_on = '$added_on' WHERE id = '$edit_id'";
    $result = mysqli_query($conn , $query);
    if($result){
        echo json_encode([
            "success" => "true",
            "message" => "Category Updated Succcessfully !"
        ]);
    }else{
        echo json_encode([
            "error" => "true",
            "message" => "Category not Updated !"
        ]);
    }
}





?>