<?php
include('../connect_db.php');


if(isset($_POST['report_delete'])){
    $code = $_POST['code'];

    $sql_update = "UPDATE tbl_reports SET is_active='N' WHERE code='$code'";
    $result_update = mysqli_query($conn,$sql_update);

     if($result_update){
        $return_arr = array("status_code" => 200,"msg" => "ลบข้อมูลสำเร็จ");
        echo json_encode($return_arr);
     }else{
        $return_arr = array("status_code" => 200,"msg" => "ลบข้อมูลสำเร็จ");
        echo json_encode($return_arr);
     }
 }

?>