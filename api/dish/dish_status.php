<?php 
include("../config.php");
include("../function.inc.php");
include("../auth.php");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json");

$user = authRequired();


$input = file_get_contents("php://input");


$status_id = get_safe_value($_POST['status_id']);

if($status_id == '' || $status_id == "0"){
    echo json_encode([
        "message" => "parameters are empity and wrong !"
    ]);
    exit;
}

$status = '';
$check = "SELECT * FROM dish WHERE id = '$status_id'";
$check_result = mysqli_query($conn , $check);
$row = mysqli_fetch_assoc($check_result);
$status_val = $row['status'];

if($status_val == 1){
    $status = 0;
}else{
    $status = 1;
}
    $query = "UPDATE dish SET status = '$status' WHERE id = '$status_id'";
    $result = mysqli_query($conn , $query);
    if($result){
        echo json_encode([
            "success" => "true",
            "message" => "Status Updated Succcessfully !"
        ]);
    }else{
        echo json_encode([
            "error" => "true",
            "message" => "Status not Updated !"
        ]);
    }






?>