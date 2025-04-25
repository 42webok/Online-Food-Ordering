<?php 
include("../config.php");
include("../function.inc.php");
include("../auth.php");

$user = authRequired();

// $data = json_decode(file_get_contents("php://input"), true);


$check = "SELECT * FROM category";
$check_result = mysqli_query($conn , $check);
if(mysqli_num_rows($check_result) > 0){
    $rows = mysqli_fetch_all($check_result , MYSQLI_ASSOC);
    echo json_encode($rows);
}else{
    echo json_encode([
        "message" => "No Data Found !"
    ]);
}





?>