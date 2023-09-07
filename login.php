<?php
session_start();
error_reporting(0);

if(!empty($_SESSION['user_id']) && $_SESSION["role_name"] == "admin"){
  echo '<script>
        window.location = "admin/index.php";
      </script>';
  exit();
}else if(!empty($_SESSION['user_id']) && $_SESSION["role_name"] == "employee"){
    echo '<script>
        window.location = "employee/index.php";
    </script>';
    exit();
}else if(!empty($_SESSION['user_id']) && $_SESSION["role_name"] == "technician"){
    echo '<script>
    window.location = "technician/index.php";
    </script>';
    exit();
}

?>
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
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
</head>

<?php
include('login_db.php');
?>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <b>แจ้งซ่อม</b>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">เข้าสู่ระบบ</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <code><?php echo $emailErr;?></code>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="email" placeholder="อีเมลล์" 
                        value="<?php echo($oldemail == "") ? '' : $oldemail; ?> <?php if(isset($_COOKIE['user_email'])){ echo $_COOKIE['user_email'];} ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    <code><?php echo $passwordErr;?></code>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="รหัสผ่าน" value="<?php if(isset($_COOKIE['user_password'])){ echo $_COOKIE['user_password'];} ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember" <?php if(isset($_COOKIE['user_email'])) {  ?> checked <?php }?>>
                                <label for="remember">
                                   จดจำฉัน
                                </label>
                            </div>
                        </div>

                        <div class="col-4">
                            <button type="submit" name="login_submit" class="btn btn-primary btn-block">เข้าสู่ระบบ</button>
                        </div>

                    </div>
                </form>
               
                <p class="mt-2">
                    <a href="register.php" class="text-center">สมัครสมาชิก</a>
                </p>
            </div>

        </div>
    </div>

    <script src="plugins/jquery/jquery.min.js"></script>

    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="dist/js/adminlte.min.js?v=3.2.0"></script>
    <script src="plugins/toastr/toastr.min.js"></script>

    <?php
    
        if($_GET['do']=='success'){  
            echo '
            <script>
            setTimeout(() => {
                window.location.href = "login.php";
            }, "2000");
            toastr.success("สมัครสมาชิกสำเร็จ");
            </script>';
        }


        if($_GET['logout']=='done'){  
            echo '
            <script>
            setTimeout(() => {
                window.location.href = "login.php";
            }, "2000");
            toastr.success("ออกจากระบบเรียบร้อยแล้ว");
            </script>';
        }


        if($_GET['email_false']=='false'){  
            echo '
            <script>
            setTimeout(() => {
                window.location.href = "login.php";
            }, "4000");
            toastr.error("ไม่พบ Email นี้ กรุณาสมัคร Email");
            </script>';
        }


        if($_GET['password_false']=='false'){  
            echo '
            <script>
            setTimeout(() => {
                window.location.href = "login.php";
            }, "4000");
            toastr.error("รหัสผ่านผิดพลาดกรุณากรอกใหม่อีกครั้ง");
            </script>';
        }
    ?>

</body>
</html>