<?php
        include('connect_db.php');
        $email = $_POST['email'];
        $sql_check_email = "SELECT email FROM users WHERE email='$email'";
        $email_result = mysqli_query($conn,$sql_check_email);

        if(mysqli_num_rows($email_result)>0){
            echo "<code>มีอีเมลล์นี้อยู่ในระบบแล้ว</code>";
            echo "<script>$('#register_form').prop('disabled',true)</script>";
        }else{
            echo "<code style='color: green;'>อีเมลล์นี้สามารถใช้งานได้</code>";
            echo "<script>$('#register_form').prop('disabled',false)</script>";
        }

?>