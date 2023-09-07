<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เข้าสู่ระบบ</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">

    <link rel="stylesheet" href="dist/css/adminlte.min.css?v=3.2.0">
</head>
<?php
include('register_db.php');
?>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <b>สมัครสมาชิก</b>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">สมัครสมาชิก</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <code><?php echo $firstnameErr;?></code>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="firstname" placeholder="ชื่อ" 
                        value="<?php echo($oldfirstname == "") ? '' : $oldfirstname; ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>

                    <code><?php echo $lastnameErr;?></code>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="lastname" placeholder="สกุล" 
                        value="<?php echo($oldlastname == "") ? '' : $oldlastname; ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                  
                    <code><?php echo $phone_numberErr;?></code>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="phone_number" placeholder="เบอร์โทร" 
                        value="<?php echo($oldphone_number == "") ? '' : $oldphone_number; ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-phone"></span>
                            </div>
                        </div>
                    </div>
                  
                    <code id="checkemail"><?php echo $emailErr;?></code>
                    <code id="checkemail"></code>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="email" onchange="checkemail(this.value)" placeholder="อีเมลล์" 
                        value="<?php echo($oldemail == "") ? '' : $oldemail; ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    <code><?php echo $passwordErr;?></code>
                    
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="รหัสผ่าน">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                   
                    <div class="row">
                        <div class="col-6">

                        </div>

                        <div class="col-6">
                            <button type="submit" id="register_form" name="register_submit" class="btn btn-primary btn-block">สมัครสมาชิก</button>
                        </div>

                    </div>
                </form>

                <p class="mt-2">
                    <a href="login.php" class="text-center">เข้าสู่ระบบ</a>
                </p>
            </div>

        </div>
    </div>

    <script src="plugins/jquery/jquery.min.js"></script>

    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="dist/js/adminlte.min.js?v=3.2.0"></script>

    <script>
        function checkemail(val){
            var testEmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if(testEmail.test(val)){
                $.ajax({
                    type:'POST',
                    url:'check_email_db.php',
                    data:'email='+val,
                    success:function(data){
                        $("#checkemail").html(data);
                    }
                });
            }else{
                $("#checkemail").html("");
                $("#checkemail").html("รูปแบบอีเมลล์ไม่ถูกต้อง");
                $('#register_form').prop('disabled',true);
            }
        }
    </script>
</body>

</html>