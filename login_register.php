<?php 
include("header.php");
include("function_inc.php");
if(isset($_SESSION['FOOD_USERNAME'])){
    redirect("shop.php");
}

if (!isset($_SESSION['FOOD_ID']) && isset($_COOKIE['remember_token'])) {
    $token = mysqli_real_escape_string($con, $_COOKIE['remember_token']);
    $sql = "SELECT * FROM user WHERE remember_token = '$token'";
    $result = mysqli_query($con, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $_SESSION['FOOD_ID'] = $user['id'];
        $_SESSION['FOOD_USERNAME'] = $user['name'];
    }
}
?>

<!-- Main code of body start here -->

<div class="breadcrumb-area gray-bg">
            <div class="container">
                <div class="breadcrumb-content">
                    <ul>
                        <li><a href="shop.html">Home</a></li>
                        <li class="active"> Login/Register </li>
                    </ul>
                </div>
            </div>
        </div>
       
          <div class="login-register-area pt-95 pb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 col-md-12 ml-auto mr-auto">
                        <div class="login-register-wrapper">
                            <div class="login-register-tab-list nav">
                                <a class="active" href="javascript:void(0);" data-toggle="tab" id="tab_1">
                                    <h4> login </h4>
                                </a>
                                <a data-toggle="tab" href="javascript:void(0);" id="tab_2">
                                    <h4> register </h4>
                                </a>
                            </div>
                            <div class="tab-content">
                                <div id="lg1" class="tab-pane active">
                                    <div class="login-form-container">
                                        <div class="login-register-form">
                                            <form id="login_frm">
                                                <input type="email" name="user-email" placeholder="Email" required>
                                                <input type="password" name="user-password" required placeholder="Password">
                                                <input type="hidden" name="type" value="login">
                                                <div class="button-box">
                                                    <div class="login-toggle-btn">
                                                        <input type="checkbox" name="remember">
                                                        <label>Remember me</label>
                                                        <a href="forget_password.php">Forgot Password?</a>
                                                    </div>
                                                    <button id="login_btn" type="submit"><span>Login</span></button>
                                                </div>
                                                <h6 class="text-danger log_message mt-2"></h6>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div id="lg2" style="display:none;" class="tab-pane">
                                    <div class="login-form-container">
                                        <div class="login-register-form">
                                            <form id="reg_frm">
                                                <input type="text" name="user-name" placeholder="Name">
                                                <input name="user-email" height="100%" placeholder="Email" type="email">
                                                <div class="email_alert d-none text-danger">Abc</div>
                                                <input type="password" name="user-password" placeholder="Password">
                                                <input type="mobile" name="user-mobile" placeholder="Mobile">
                                                <input type="hidden" name="type" value="register">
                                                <div class="button-box">
                                                    <button type="submit" id="register_btn"><span>Register</span></button>
                                                </div>
                                                <h6 class="mt-2 text-danger d-none mail_check"></h6>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<!-- footer start here -->
<?php 
include("footer.php");
?>
<script>
    let tab_1 = document.getElementById('tab_1');
    let tab_2 = document.getElementById('tab_2');

    let lg1 = document.getElementById('lg1');
    let lg2 = document.getElementById('lg2');

    tab_1.onclick = ()=>{
        tab_1.classList.add('active');
        tab_2.classList.remove('active');
        lg1.style.display = "block";
        lg2.style.display = "none";
    }

    tab_2.onclick = ()=>{
        tab_2.classList.add('active');
        tab_1.classList.remove('active');
        lg2.style.display = "block";
        lg1.style.display = "none";
    }
</script>
<script>
    $(document).ready(function(){
        $("#reg_frm").on('submit' , function(e){
            e.preventDefault();
            var form_data = $(this).serialize();
            $('#register_btn').attr("disabled" , true);
            $('.mail_check').removeClass("d-none");
            $('.mail_check').html("Please Wait ...");
            $.ajax({
                type: 'POST',
                url: 'register.php',
                data: form_data,
                success: function(response){
                    $('#register_btn').attr("disabled" , false);
                    let parse_data = JSON.parse(response);
                    if(parse_data.status == "false"){
                        $('.mail_check').addClass("d-none");
                       $('.email_alert').html(parse_data.message);
                       $('.email_alert').removeClass("d-none");
                    }else if(parse_data.status == 'true'){
                        $('.email_alert').addClass("d-none");
                        $('.mail_check').html(parse_data.message);
                        $("#reg_frm")[0].reset();
                    }
                }
            })
        })
    })
</script>
<script>
    $(document).ready(function(){
        $("#login_frm").on('submit' , function(e){
            e.preventDefault();
            var form_data = $(this).serialize();
            $('#login_btn').attr("disabled" , true);
            $.ajax({
                type: 'POST',
                url: 'login.php',
                data: form_data,
                success: function(response){
                    // console.log(response)
                    $('#login_btn').attr("disabled" , false);
                    let parse_data = JSON.parse(response);
                    if(parse_data.status == "false"){
                        $('.log_message').html(parse_data.message);
                    }else if(parse_data.status == 'true'){
                         $('.log_message').html(parse_data.message);
                         $("#login_frm")[0].reset();
                         window.location.href = "shop.php";
                    }
                }
            })
        })
    })
</script>