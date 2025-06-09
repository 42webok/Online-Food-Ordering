<?php
include( 'admin/database.inc.php' );
include( 'function_inc.php' );
session_start();
?>
<?php

if ( isset( $_POST[ 'type' ] ) && $_POST[ 'type' ] == 'login' ) {
    // print_r($_POST);
    // exit;
    $email = mysqli_real_escape_string($con , $_POST[ 'user-email' ]);
    $password = mysqli_real_escape_string($con , $_POST[ 'user-password' ]);
    $remember = isset($_POST[ 'remember' ]);
    $query  = "SELECT * FROM user WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query( $con, $query );
    if ( mysqli_num_rows( $result ) > 0 ) {
        $emQuery = mysqli_fetch_assoc($result);
        $verify_data = $emQuery['email_verify'];
        if($verify_data == 1){
            $_SESSION['FOOD_USERNAME'] = $emQuery['name'];
            $_SESSION['FOOD_ID'] = $emQuery['id'];
            if($remember){
                 $token = bin2hex(random_bytes(16));
                 $expiry = time() + (86400 * 30); // 30 days
                 $update = "UPDATE user SET remember_token = '$token' WHERE id = {$emQuery['id']}";
                 mysqli_query($con, $update);
                 setcookie("remember_token", $token, $expiry, "/", "", false, true);
            }
            echo json_encode(
            array( 'status' => 'true',
            'message' => 'You are Login !'
        ));
        }else{
            echo json_encode(
                array( 'status' => 'false',
                'message' => 'Your Account are not verified'
            ));
        }
    }else {
       
            echo json_encode(
                array( 'status' => 'false',
                'message' => 'Wrong login details !'
            ) );
    }

}




// Forget password code start here

if(isset($_POST['type']) && $_POST['type'] == 'forget'){
    $email = mysqli_real_escape_string($con , $_POST['user-email']);
    $query = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($con, $query);
    if(mysqli_num_rows($result) > 0){
        $emQuery = mysqli_fetch_assoc($result);
        $verify_data = $emQuery['email_verify'];
        if($verify_data == 1){
            $otp = rand(100000, 999999);
            $query = "UPDATE user SET password = '$otp' WHERE email = '$email'";
            $result = mysqli_query($con, $query);
            $to = $emQuery['email'];
            $subject = "Reset Password";
            $message = "Your new password is $otp";
            send_mail($to , $subject , $message);
            echo json_encode(
                array( 'status' => 'true',
                'message' => 'Please check your email'
            ));
        }else{
             echo json_encode(
                array( 'status' => 'false',
                'message' => 'Your Account is not veified'
            ));
        }
    }else{
        echo json_encode(
                array( 'status' => 'false',
                'message' => 'Not a Valid Email'
            ));
    }
}






?>