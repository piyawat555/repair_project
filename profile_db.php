<?php

include('../connect_db.php');


    if(isset($_POST['user_update']) && isset($_POST['id'])){

        $user_id = $_POST["id"];
		$first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];
		$password = $_POST["password"];
		$phone_number = $_POST["phone_number"];
		$email = $_POST["email"];

        if(empty($password)){
            $query_update_profile = "UPDATE users SET first_name = '$first_name',
            last_name = '$last_name',
            phone_number = '$phone_number',
            email = '$email'
            WHERE id = '$user_id'";
        }else{
            $password_md = md5($password);
            $query_update_profile = "UPDATE users SET first_name = '$first_name',
            last_name = '$last_name',
            phone_number = '$phone_number',
            password = '$password_md',
            email = '$email'
            WHERE id = '$user_id'";
        }

        $result_update = mysqli_query($conn,$query_update_profile);

        if($result_update){

            $return_arr = array("status_code" => 200,"msg" => "อัพเดทข้อมูลสำเร็จ");
            echo json_encode($return_arr);
        }else{
            $return_arr = array("status_code" => 500,"msg" => "อัพเดทข้อมูลผิดพลาด");
            echo json_encode($return_arr);
        }
    }

?>