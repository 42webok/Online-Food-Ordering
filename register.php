<?php
include( 'admin/database.inc.php' );
include( 'function_inc.php' );
?>
<?php

if ( isset( $_POST[ 'type' ] ) && $_POST[ 'type' ] == 'register' ) {
    $name = $_POST[ 'user-name' ];
    $email = $_POST[ 'user-email' ];
    $password = $_POST[ 'user-password' ];
    $mobile = $_POST[ 'user-mobile' ];
    $rand_str = generateRandomString();
    $query  = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query( $con, $query );
    if ( mysqli_num_rows( $result ) > 0 ) {
        echo json_encode(
            array( 'status' => 'false',
            'message' => 'Email already exists'
        ) );
    }else {
        $query = "INSERT INTO user (name, email, password, mobile , status , email_verify , rand_str) VALUES ('$name' , '$email' , '$password' , '$mobile' , '1' , '0' , '$rand_str')";
        $result = mysqli_query( $con, $query );
        $id = $rand_str;
        if ( $result ) {
            $body = "Please open This link to verify email address http://localhost/food/verify_email.php?id=".$id;
            send_mail($email , "Verify Email" ,  $body);
            echo json_encode(
                array( 'status' => 'true',
                'message' => 'Thanks For register , please check your email for verify'
            ) );
        }
    }

}

?>