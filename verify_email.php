<?php 
include("header.php");
include( 'function_inc.php' );
if(isset($_GET['id']) && $_GET['id'] != '') {
    $id = $_GET['id'];
    $sql = "SELECT * FROM user WHERE rand_str = '$id'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $up_id = $row['id'];
    if(mysqli_num_rows($result) > 0) {
        $query = "UPDATE user SET email_verify = '1' WHERE id = '$up_id'";
        $result = mysqli_query($con, $query);
    }else{
        redirect('index.php');
    }
}else{
    redirect('index.php');
}
?>

<!-- Main code of body start here -->


        <div class="contact-area pt-100 pb-100 mb-5">
            <div class="container">
                 <h4>Your Email verification is successfull 🤗 !</h4>
            </div>
        </div>

<!-- footer start here -->
<?php 
include("footer.php");
?>
