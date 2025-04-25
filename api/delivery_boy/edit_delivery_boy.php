<?php 
include("../config.php");
include("../function.inc.php");
include("../auth.php");
header("Access-Control-Allow-Methods: PUT");

$user = authRequired();

$data = json_decode(file_get_contents("php://input"), true);


$edit_id = get_safe_value($data['edit_id']);
$name = get_safe_value($data['name']);
$mobile = get_safe_value($data['mobile']);

if($name == '' || $mobile == '' || $edit_id == '' || $edit_id == "0"){
    echo json_encode([
        "message" => "parameters are empity and wrong !"
    ]);
    exit;
}

$added_on=date('Y-m-d h:i:s');
$status = 1;
$check = "SELECT * FROM delivery_boy WHERE mobile = '$mobile'";
$check_result = mysqli_query($conn , $check);
if(mysqli_num_rows($check_result) > 0){
    echo json_encode([
        "message" => "Deliver Boy already exist !"
    ]);
}else{
    $query = "UPDATE delivery_boy SET name = '$name' , mobile = '$mobile' , status = '$status' , added_on = '$added_on' WHERE id = '$edit_id'";
    $result = mysqli_query($conn , $query);
    if($result){
        echo json_encode([
            "success" => "true",
            "message" => "Deliver Boy Updated Succcessfully !"
        ]);
    }else{
        echo json_encode([
            "error" => "true",
            "message" => "Deliver Boy not Updated !"
        ]);
    }
}





?>