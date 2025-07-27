<?php 
include("admin/database.inc.php");
session_start();
include( 'function_inc.php');
if(isset($_SESSION['FOOD_ID']) && isset($_SESSION['FOOD_ID']) > 0){
    $user_id = $_SESSION['FOOD_ID'];
    $quanty = $_POST['qnty'];
    $at_id = $_POST['at_id'];
    $type = $_POST['type'];

    if($type == 'add'){
        $query = "SELECT * FROM dish_cart where user_id = '$user_id' and dish_detail_id = '$at_id'";
        $result = mysqli_query($con, $query);
        if(mysqli_num_rows($result) > 0){
            $sql = mysqli_query($con, "update dish_cart set qnty = '$quanty' where user_id = '$user_id' and dish_detail_id = '$at_id'");
            if($sql){
                 echo json_encode(array("msg" => "Dish quantity updated successfully !",
                "status" => "success"));
            }else{
                echo json_encode(array("msg" => "Error updating dish quantity !",
            "status" => "error"));
            }
        }else{
            $sql = mysqli_query($con, "insert into dish_cart (user_id, dish_detail_id , qnty) values ('$user_id', '$at_id', '$quanty')");
            if($sql){
                echo json_encode(array("msg" => "Dish added to cart successfully !",
            "status" => "success"));
            }else{
                echo json_encode(array("msg" => "Error adding dish to cart !",
            "status" => "error"));
            }
        }
    }
    
    
}
else{
   echo json_encode(array("msg" => "no_login"));
}





?>