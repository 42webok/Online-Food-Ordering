<?php
include( 'admin/database.inc.php' );
?>

<?php
if ( isset( $_SERVER[ 'REQUEST_METHOD' ] ) && $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {

    $name = $_POST[ 'name' ];

    $email = $_POST[ 'email' ];

    $phone = $_POST[ 'phone' ];

    $subject = $_POST[ 'subject' ];

    $message = $_POST[ 'message' ];

    $query = "INSERT INTO contact_us (name , email , phone , subject , message , status) VALUES ('$name' , '$email' , '$phone' , '$subject' , '$message' , '1')";

    $result = mysqli_query( $con, $query );

    if ( $result ) {
        echo 'Message Post Successfully !' ;
    } else {
        echo 'Message Post Fail !' ;
    }
}
?>