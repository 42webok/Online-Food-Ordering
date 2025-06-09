<?php 
include("header.php");
?>

<!-- Main code of body start here -->

<div class="breadcrumb-area gray-bg">
            <div class="container">
                <div class="breadcrumb-content">
                    <ul>
                        <li><a href="shop.html">Home</a></li>
                        <li class="active"> Forget Password </li>
                    </ul>
                </div>
            </div>
        </div>
       
          <div class="login-register-area pt-95 pb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 col-md-12 ml-auto mr-auto">
                        <div class="login-register-wrapper">
                            
                            <h3 class="text-center mb-2 text-danger">Forget Password</h3>
                                    <div class="login-form-container">
                                        <div class="login-register-form">
                                            <form id="forget_frm">
                                                <input type="email" required name="user-email" placeholder="Email">
                                                <input type="hidden" name="type" value="forget">
                                                <div class="button-box">
                                                    <button id="forget_btn" type="submit"><span>submit</span></button>
                                                </div>
                                                <h6 class="text-danger log_message mt-2"></h6>
                                            </form>
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
    $(document).ready(function(){
        $("#forget_frm").on('submit' , function(e){
            e.preventDefault();
            var form_data = $(this).serialize();
            $('#forget_btn').attr("disabled" , true);
             $('.log_message').html("Please wait ...");
            $.ajax({
                type: 'POST',
                url: 'login.php',
                data: form_data,
                success: function(response){
                    // console.log(response)
                    $('#forget_btn').attr("disabled" , false);
                    let parse_data = JSON.parse(response);
                    if(parse_data.status == "false"){
                        $('.log_message').html(parse_data.message);
                    }else if(parse_data.status == 'true'){
                         $('.log_message').html(parse_data.message);
                         $("#forget_frm")[0].reset();
                         setTimeout(() => {
                            window.location.href = "login_register.php";
                         }, 2000);
                    }
                }
            })
        })
    })
</script>