<?php
include('../connect_db.php');


if(isset($_POST['title_role'])){
        $title = $_POST['title_role'];
        $sql_role = "INSERT INTO roles (title) VALUES ('$title')";
        $result_role = mysqli_query($conn,$sql_role);
        if($result_role){
            echo 'เพิ่มสำเร็จ';
        }else{
            echo '<script>';
            echo "window.location='index.php?act=roles&do=f';";
            echo '</script>';
        }
}

if(isset($_POST['update_title'])){
    $id = $_POST['id'];
    $sql_update_title = "UPDATE roles SET title='".$_POST['update_title']."'WHERE id =$id";
    $result_update = mysqli_query($conn,$sql_update_title);
    if($result_update){
        $msg = "อัพเดทสำเร็จ";
    }else{
        $msg = "อัพเดทผิดพลาด";
    }
    echo json_encode($msg);
}


if(isset($_POST['update_role'])){
    $id = $_POST["id"];

    $sql_find = "SELECT status FROM roles WHERE id=$id";
    $result_status = mysqli_query($conn,$sql_find);

    $row = mysqli_fetch_array($result_status);
    $st;
    if($row["status"] != "Y"){
        $st = "Y";
        $msg = "เปิดใช้การงานสำเร็จ";
    }

    if($row["status"] != "N"){
        $st = "N";
        $msg = "ปิดใช้การงานแล้ว";
    }

    $sql_update = "UPDATE roles SET status ='".$st."' WHERE id=$id";
    $result_update = mysqli_query($conn,$sql_update);

    if($result_update){
        echo $msg;
    }
}


if(isset($_GET['role_id'])){
    $role_id = $_GET['role_id'];
    $sql_role = "SELECT * FROM roles WHERE id =$role_id";
    $result_role = mysqli_query($conn,$sql_role);
    if($result_role){
        while ($row = mysqli_fetch_array($result_role)) 
        {
            $return_arr = array("id" => $row['id'],
                    "title" => $row['title']);
        }
        echo json_encode($return_arr);
    }else{
       
    }
}

if(isset($_POST['role_delete'])){
    $role_id = $_POST['id'];
    $sql_role = "DELETE FROM roles WHERE id =$role_id";
    $result_role = mysqli_query($conn,$sql_role);
    if($result_role){
        $return_arr = array("status_code" => 200,"msg" => "ลบข้อมูลสำเร็จ");
        echo json_encode($return_arr);
    }else{
        $return_arr = array("status_code" => 200,"msg" => "ลบข้อมูลสำเร็จ");
        echo json_encode($return_arr);
    }
}



?>