<?php 
include("../config.php");
include("../function.inc.php");
include("../auth.php");

$user = authRequired();

$data = json_decode(file_get_contents("php://input"), true);


$category = get_safe_value($data['category']);
$order_number = get_safe_value($data['order_number']);

if($category == '' || $order_number == ''){
    echo json_encode([
        "message" => "Please fill all fields !"
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
    $query = "INSERT INTO category (category , order_number , added_on , status) VALUES ('$category' , '$order_number' , '$added_on' , '$status')";
    $result = mysqli_query($conn , $query);
    if($result){
        echo json_encode([
            "success" => "true",
            "message" => "Category Inserted Succcessfully !"
        ]);
    }else{
        echo json_encode([
            "error" => "true",
            "message" => "Category not Inserted !"
        ]);
    }
}





?>