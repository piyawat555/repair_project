<?php

$emailErr="";
$firstnameErr ="";
$lastnameErr="";
$phone_numberErr="";
$passwordErr="";
$oldemail="";
$oldfirstname ="";
$oldlastname="";
$oldphone_number="";

    if(isset($_POST['register_submit'])){
        include('connect_db.php');
    
        if(empty($_POST["email"])){
            $emailErr = "กรุณากรอกอีเมลล์";  
        }else{
            $email = test_input($_POST["email"]);
            $oldemail=$email;
            $emailErr="";
        }

        if(empty($_POST["firstname"])){
            $firstnameErr = "กรุณากรอกชื่อ";  
        }else{
            $firstname = test_input($_POST["firstname"]);
            $oldfirstname=$firstname;
            $firstnameErr ="";
        }

        if(empty($_POST["lastname"])){
            $lastnameErr = "กรุณากรอกนามสกุล";  
        }else{
            $lastname = test_input($_POST["lastname"]);
            $oldlastname=$lastname;
            $lastnameErr="";
        }

        if(empty($_POST["phone_number"])){
            $phone_numberErr = "กรุณากรอกเบอร์โทรศัพท์";  
        }else{
            $phone_number = test_input($_POST["phone_number"]);
            $oldphone_number=$phone_number;
            $phone_numberErr="";
        }

        if(empty($_POST["password"])){
            $passwordErr = "กรุณากรอกพาสเวิร์ด";  
        }else{
            $password = test_input($_POST["password"]);
            $passwordErr="";
        }

        if($emailErr=="" && $firstnameErr =="" && $lastnameErr == "" && $phone_numberErr =="" && $passwordErr==""){
            $password_hash=password_hash($password,PASSWORD_DEFAULT);
            $date = date('Y-m-d H:i:s');
            $query_register = "INSERT INTO users (first_name,last_name,phone_number,email,password,is_active,created_at)
            VALUES ('$firstname','$lastname','$phone_number','$email','$password_hash','Y','$date')";
            $result = mysqli_query($conn,$query_register);
            $return_arr = array();
            $user_id =mysqli_insert_id($conn);
            //กำหนดให้สมัครสมาชิกเป็น role emp ก่อนเสมอ
            $sql_insert_role = "INSERT INTO role_user (user_id,role_id) VALUES ('$user_id',2)";
            $result_role = mysqli_query($conn,$sql_insert_role);
 
            if($result&&$result_role){
                echo '<script>';
                echo "window.location='login.php?do=success';";
                echo '</script>';
            }else{
                echo '<script>';
                echo "window.location='register.php?do=f';";
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