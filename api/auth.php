<?php

include("config.php");

function getUserFromToken($token){
    global $conn;
    $result = $conn->query("SELECT * FROM admin WHERE token = '$token'");
    return $result->fetch_assoc();
}

function authRequired(){
    $headers = getallheaders(); 

    if (isset($headers['Authorization'])) {
        $authHeader = $headers['Authorization'];
    } elseif (isset($headers['authorization'])) {
        $authHeader = $headers['authorization'];
    } else {
        echo json_encode(["error" => "No token provided"]);
        exit;
    }

    // Extract token after "Bearer "
    $token = str_replace('Bearer ', '', $authHeader);

    $user = getUserFromToken($token);

    if (!$user) {
        echo json_encode(["error" => "Invalid token"]);
        exit;
    }

    return $user;
}

?>