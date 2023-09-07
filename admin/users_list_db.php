<?php
include('../connect_db.php');

$sql_users = "SELECT u.id,u.first_name,u.last_name,u.phone_number,u.email,u.is_active,r.id as role_id,r.title FROM users u 
                INNER JOIN role_user ru on u.id = ru.user_id 
                    INNER JOIN roles r ON r.id = ru.role_id";
$result_users = mysqli_query($conn,$sql_users);
$count=1;

if(isset($_GET['get_submit'])){

   $sql_roles = "SELECT * FROM roles";
   $result_roles = mysqli_query($conn,$sql_roles);

   $id = $_GET['id'];
   $sql = "SELECT id,first_name,last_name,phone_number,email FROM users WHERE id=$id";

   $result_user = mysqli_query($conn,$sql);
   $return_arr = array();
   $return_arr_roles = array();
    if(mysqli_num_rows($result_user)>0){
        while ($row = mysqli_fetch_array($result_user)) 
        {
            $return_arr = array("id" => $row['id'],
                    "first_name" => $row['first_name'],
                    "last_name" => $row['last_name'],
                    "phone_number" => $row['phone_number'],
                    "email" => $row['email']);
        }
        $row_r_count=0;
        while($row_r = $result_roles->fetch_assoc()) {
            $return_arr_roles[$row_r_count++] = array("id" =>$row_r['id'],"title"=>$row_r['title']);
        }
        echo json_encode(array($return_arr,$return_arr_roles));
    }
}

if(isset($_POST['user_submit'])){
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $id = $_POST['id'];
    $role_id = $_POST['role'];

    $sql_update = "UPDATE users 
    SET first_name='$first_name',
    last_name='$last_name',
    phone_number='$phone_number',
    email='$email' WHERE id=$id";

    $result_update = mysqli_query($conn,$sql_update);

    $sql_update_role = "UPDATE role_user 
    SET role_id='$role_id' WHERE user_id=$id";
    $result_update_role = mysqli_query($conn,$sql_update_role);
    if($result_update&&$result_update_role){
        $return_arr = array("status_code" => 200,"msg" => "อัพเดทข้อมูลสำเร็จ");
        echo json_encode($return_arr);
    }else{
        $return_arr = array("status_code" => 500,"msg" => "อัพเดทข้อมูลผิดพลาด");
        echo json_encode($return_arr);
    }
}


if(isset($_POST['update_status'])){
    $id = $_POST["id"];

    $sql_find = "SELECT is_active FROM users WHERE id=$id";
    $result_status = mysqli_query($conn,$sql_find);
    
    $row = mysqli_fetch_array($result_status);
    $st;
    if($row["is_active"] != "Y"){
        $st = "Y";
        $msg = "เปิดใช้การงานสำเร็จ";
    }
    
    if($row["is_active"] != "N"){
        $st = "N";
        $msg = "ปิดใช้การงานแล้ว";
    }
    
    $sql_update = "UPDATE users SET is_active ='".$st."' WHERE id=$id";
    $result_update = mysqli_query($conn,$sql_update);
    
    if($result_update){
        echo $msg;
    }
}


if(isset($_POST['user_delete'])){
    $id = $_POST['id'];

    $sql_delete = "DELETE FROM users WHERE id=$id";
    $result_delete = mysqli_query($conn,$sql_delete);

     if($result_delete){
        $return_arr = array("status_code" => 200,"msg" => "ลบผู้ใช้ข้อมูลสำเร็จ");
        echo json_encode($return_arr);
     }else{
        $return_arr = array("status_code" => 200,"msg" => "ลบผู้ใช้ข้อมูลสำเร็จ");
        echo json_encode($return_arr);
     }
 }

?>