<?php 
include("../config.php");
include("../function.inc.php");
include("../auth.php");

$user = authRequired();

$data = json_decode(file_get_contents("php://input"), true);


$name = get_safe_value($data['name']);
$mobile = get_safe_value($data['mobile']);
$password = get_safe_value($data['password']);

if($name == '' || $mobile == '' || $password == ''){
    echo json_encode([
        "message" => "Please fill all fields !"
    ]);
    exit;
}

$added_on=date('Y-m-d h:i:s');
$status = 1;
$check = "SELECT * FROM delivery_boy WHERE mobile = '$mobile'";
$check_result = mysqli_query($conn , $check);
if(mysqli_num_rows($check_result) > 0){
    echo json_encode([
        "message" => "Delivery Boy already exist !"
    ]);
}else{
    $query = "INSERT INTO delivery_boy (name , mobile, password , added_on , status) VALUES ('$name' , '$mobile' , '$password' , '$added_on' , '$status')";
    $result = mysqli_query($conn , $query);
    if($result){
        echo json_encode([
            "success" => "true",
            "message" => "Delivery Boy Inserted Succcessfully !"
        ]);
    }else{
        echo json_encode([
            "error" => "true",
            "message" => "Delivery Boy not Inserted !"
        ]);
    }
}





?>