<?php
    include('../connect_db.php');
    $code = $_GET['code'];

     $sql_technician_working = "SELECT working.technician_id from (SELECT tbl_r.technician_id from (SELECT u.id,u.first_name,u.last_name,u.email,u.phone_number,tr.* FROM users u 
        INNER JOIN role_user ru ON ru.user_id = u.id 
         INNER JOIN roles r ON r.id = ru.role_id
             LEFT JOIN tbl_reports tr ON tr.technician_id = u.id
                WHERE r.title ='technician') tbl_r 
                WHERE tbl_r.date_close IS NULL) working
                INNER JOIN users u ON u.id = working.technician_id
                INNER JOIN role_user ru ON ru.user_id = u.id
                INNER JOIN roles r ON r.id = ru.role_id";
                
    $technicians_working = mysqli_query($conn,$sql_technician_working);

    
    if(mysqli_num_rows($technicians_working) > 0){
        while($row = mysqli_fetch_array($technicians_working))
        {
            $technician_working[] = $row['technician_id'];
        }

        $sql_technician = "SELECT u.id,u.first_name,u.last_name,u.email,u.phone_number,u.profile_img FROM users u 
        INNER JOIN role_user ru ON ru.user_id = u.id 
            INNER JOIN roles r ON r.id = ru.role_id
                WHERE r.title ='technician' AND u.id NOT IN ( '" . implode( "', '" , $technician_working ) . "' )";
 
    }else{
        $sql_technician = "SELECT u.id,u.first_name,u.last_name,u.email,u.phone_number,u.profile_img FROM users u 
        INNER JOIN role_user ru ON ru.user_id = u.id 
            INNER JOIN roles r ON r.id = ru.role_id
                WHERE r.title ='technician'";
    }
    $technicians = mysqli_query($conn,$sql_technician);
    $rowtechnicians=mysqli_num_rows($technicians);
 
?>
    <section class="content">

        <div class="card card-solid">
            <div class="card-header">
                    <div class="text-left">
                        <h3 class="card-title pt-2">
                         <i class="fas fa-comment-medical"></i>
                        ช่างที่กำลังว่างงาน  
                        </h3>
                    </div>
                    <div class="text-right">
                        <a class="btn btn-primary btn-sm" href="javascript:history.back();">
                            <!-- <i class="fas fa-folder">
                            </i> -->
                            กลับ
                        </a>
                    </div>
            </div>
            <div class="card-body pb-0">
                <div class="row">
                        <?php if($rowtechnicians>0) {?>
                            <?php foreach($technicians as $list) {?>
                                <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                    <div class="card bg-light d-flex flex-fill">
                                        <div class="card-header">
                                        ข้อมูลช่าง
                                        </div>
                                        <div class="card-body pt-3">
                                            <div class="row">
                                                <div class="col-7">
                                                    <h2 class="lead"><b> <?php echo $list["first_name"]. ' '.  $list["last_name"]?> </b></h2>
                                                
                                                    <ul class="ml-4 mb-0 fa-ul text-muted pt-3">
                                                        <li class="small">
                                                            <span class="fa-li">
                                                                <i class="fas fa-lg fa-building">
                                                                </i>
                                                            </span> 
                                                            Email Address: <?php echo $list["email"]?>
                                                        </li> <br>
                                                        <li class="small">
                                                            <span class="fa-li">
                                                                <i class="fas fa-lg fa-phone">
                                                                </i>
                                                            </span> Phone #:  <?php echo $list["phone_number"]?>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-5 text-center">
                                                    <img src="<?php echo $list["profile_img"] ? $list["profile_img"] : '../dist/img/user2-160x160.jpg' ?>" alt="user-avatar"
                                                        class="img-circle img-fluid">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="text-right">
                                                <a href="technician_db.php?technician_id=<?php echo $list["id"] ?>&code_id=<?php echo $code ?>&user_id=<?php echo $user_id ?>" class="btn btn-sm btn-success">
                                                    <i class="fas fa-plus-circle"></i> เลือกช่าง
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php }else{  ?>
                            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                  <p>ไม่มีช่างที่กำลังว่างงาน</p>
                                </div>
                        <?php } ?>
                           

                </div>
            </div>
        </div>
    </section>
