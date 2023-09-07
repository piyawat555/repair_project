<?php
    include('../connect_db.php');
    $code = $_GET['code'];
    $sql_technician = "SELECT u.id,u.first_name,u.last_name,u.email,u.phone_number FROM users u 
        INNER JOIN role_user ru ON ru.user_id = u.id 
            INNER JOIN roles r ON r.id = ru.role_id
                WHERE r.title ='technician'";
    $technicians = mysqli_query($conn,$sql_technician);

?>
    <section class="content">

        <div class="card card-solid">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit"></i>
                    จัดการช่าง
                </h3>
            </div>
            <div class="card-body pb-0">
                <div class="row">
                        <?php foreach($technicians as $list) {?>
                            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                <div class="card bg-light d-flex flex-fill">
                                    <div class="card-header text-muted border-bottom-0">
                                    ข้อมูลรายละเอียดช่าง
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-7">
                                                <h2 class="lead"><b> <?php echo $list["first_name"]. ' '.  $list["last_name"]?> </b></h2>
                                                <ul class="ml-4 mb-0 fa-ul text-muted">
                                                    <li class="small">
                                                        <span class="fa-li">
                                                            <i class="fas fa-lg fa-building">
                                                            </i>
                                                        </span> 
                                                        Email Address: <?php echo $list["email"]?>
                                                    </li>
                                                    <li class="small">
                                                        <span class="fa-li">
                                                            <i class="fas fa-lg fa-phone">

                                                            </i>
                                                        </span> Phone #:  <?php echo $list["phone_number"]?>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- <div class="col-5 text-center">
                                                <img src="upload/" alt="user-avatar"
                                                    class="img-circle img-fluid">
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="text-right">
                                            <a href="index.php?tec_id=&act=worker" class="btn btn-sm bg-warning">
                                                <i class="fas fa-comments"></i> ตารางงานช่าง
                                            </a>
                                            <a href="#" class="btn btn-sm btn-success">
                                                <i class="fas fa-user"></i> ดูข้อมูล
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <?php include("../layouts/script.php"); ?>