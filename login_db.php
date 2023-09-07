<?php
session_start();
$emailErr="";
$passwordErr="";
$oldemail="";

    if(isset($_POST['login_submit'])){
        include('connect_db.php');

        if(empty($_POST["email"])){
            $emailErr = "กรุณากรอกอีเมลล์";  
        }else{
            $email = test_input($_POST["email"]);
            $oldemail=$email;
            $emailErr="";
        }

        if(empty($_POST["password"])){
            $passwordErr = "กรุณากรอกพาสเวิร์ด";  
        }else{
            $password = test_input($_POST["password"]);
            $passwordErr="";
        }

        if(!empty($_POST["remember"])){
            setcookie('user_email',$_POST["email"],time()+(10*365*24*60*60));
            setcookie('user_password',$_POST["password"],time()+(10*365*24*60*60));
        }else{
            if(isset($_COOKIE['user_email'])){
                setcookie('user_email','');
                if(isset($_COOKIE['user_password'])){
                    setcookie('user_password','');
                }
            }
        }

        if($emailErr=="" && $passwordErr==""){

            $sql_check_email = "SELECT id,first_name,last_name,profile_img,password FROM users WHERE email='$email'";
            $result_email = mysqli_query($conn,$sql_check_email);
            

            if(mysqli_num_rows($result_email)>0){
                        $row = mysqli_fetch_array($result_email);
                        $user_id = $row["id"];
                            if(password_verify($password,$row["password"])){
                                $sql_role_user  = "SELECT r.title FROM role_user ru 
                                INNER JOIN roles r ON ru.role_id = r.id 
                                WHERE ru.user_id = $user_id";
                                    $result_role = mysqli_query($conn,$sql_role_user);
                                    $row_role = mysqli_fetch_array($result_role);
                                    $_SESSION["user_id"] = $user_id;
                                    $_SESSION["user_name"] = $row["first_name"]." ".$row["last_name"];
                                    $_SESSION["role_name"] = $row_role["title"];
                                    $_SESSION["img_profile"] = $row["profile_img"];

                                        if($row_role["title"] == "admin"){
                                            Header("Location: admin/index.php?login=done");
                                        }else if($row_role["title"] == "employee"){
                                            Header("Location: employee/index.php?login=done");
                                        }else if($row_role["title"] == "technician"){
                                            Header("Location: technician/index.php?login=done");
                                        }else{

                                        }

                            }else{
                                echo '<script>';
                                echo "window.location='login.php?password_false=false';";
                                echo '</script>';
                            }
                
            }else{
                echo '<script>';
                echo "window.location='login.php?email_false=false';";
                echo '</script>';
            }
        }
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
     }

?>