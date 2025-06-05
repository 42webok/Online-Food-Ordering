<?php 
include("../config.php");
include("../function.inc.php");
include("../auth.php");

$user = authRequired();

// $data = json_decode(file_get_contents("php://input"), true);
$dish_id = '';
if(isset($_GET['dish_id']) && !empty($_GET['dish_id'])){
    $dish_id = get_safe_value($_GET['dish_id']);
}

if($dish_id == '' || !is_numeric($dish_id) || $dish_id <= 0){
    echo json_encode([
        "message" => "Dish ID is required !"
    ]);
    exit;
}


$check = "SELECT * FROM dish WHERE id = '$dish_id' AND status = 1";
$check_result = mysqli_query($conn , $check);

if(mysqli_num_rows($check_result) > 0){
    $dish = mysqli_fetch_all($check_result , MYSQLI_ASSOC);
    $query = "SELECT * FROM dish_details WHERE dish_id = '$dish_id' AND status = 1";
    $query_result = mysqli_query($conn , $query);
    $dish_details = mysqli_fetch_all($query_result , MYSQLI_ASSOC);
    echo json_encode([
        "dish" => $dish
    ]);
    echo json_encode([
        "dish_details" => $dish_details
    ]);
}else{
    echo json_encode([
        "message" => "No Data Found !"
    ]);
}





?>