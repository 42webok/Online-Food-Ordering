<?php 
include("config.php");
include("function.inc.php");

    $data = json_decode(file_get_contents("php://input"), true);

    $username = get_safe_value($data['username']);
    $password = get_safe_value($data['password']);
    if($username == " " || $password == " "){
        echo "Please fill in all fields";
    }else{
        $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $token = bin2hex(random_bytes(16));
            $conn->query("UPDATE admin SET token = '$token' WHERE id = {$user['id']}");
            echo json_encode(["token" => $token]);
        } else {
            echo json_encode(["error" => "Invalid credentials"]);
        }
    }

?>