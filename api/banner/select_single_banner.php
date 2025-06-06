<?php 
include("../config.php");
include("../function.inc.php");
include("../auth.php");

$user = authRequired();

// $data = json_decode(file_get_contents("php://input"), true);
$banner_id = '';
if(isset($_GET['banner_id']) && !empty($_GET['banner_id'])){
    $banner_id = get_safe_value($_GET['banner_id']);
}

if($banner_id == '' || !is_numeric($banner_id) || $banner_id <= 0){
    echo json_encode([
        "message" => "Banner ID is required !"
    ]);
    exit;
}


$check = "SELECT * FROM manage_banner WHERE id = '$banner_id' AND status = 1";
$check_result = mysqli_query($conn , $check);

if(mysqli_num_rows($check_result) > 0){
    $dish = mysqli_fetch_all($check_result , MYSQLI_ASSOC);
    echo json_encode([
        "dish" => $dish
    ]);
}else{
    echo json_encode([
        "message" => "No Data Found !"
    ]);
}





?>