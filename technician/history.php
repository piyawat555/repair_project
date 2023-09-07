<?php
    include('../connect_db.php');

    if(isset($_GET['act'])=='history' && isset($_GET['code'])){
        $code = $_GET['code'];
            $sql = "SELECT r.*,tbs.status_name,
                            ut.first_name as t_f_name,ut.last_name as t_l_name,ut.email as t_email,ut.phone_number as t_phone_number,
                                u.first_name as u_report_first_name,u.last_name as u_report_last_name,u.email as u_report_email,u.phone_number as u_report_phone_number,
                                    ua.first_name as a_f_name,ua.last_name as a_l_name,ua.email as a_email,ua.phone_number as a_phone_number
                FROM tbl_reports r 
                    INNER JOIN tbl_status tbs ON tbs.status_id = r.status
                        LEFT JOIN users ut ON ut.id = r.technician_id
                            LEFT JOIN users u ON u.id = r.user_id
                                LEFT JOIN users ua ON ua.id = r.assign_by 
                WHERE r.code ='$code'";

        $result = mysqli_query($conn,$sql);
        $history = mysqli_fetch_array($result);

    }

?>
<section class="col-lg-12 connectedSortable">
    <div class="card">
        <div class="card-header">
            <div class="text-left">
                <h3 class="card-title pt-2">
                    <i class="fas fa-history"></i>
                    ประวัติการซ่อม <?php echo " เลขที่ใบแจ้งซ่อม : ". $code ?>
                </h3>
            </div>
            <div class="text-right">
            <a class="btn btn-primary btn-sm" href="javascript:history.back();">
                    กลับ
                </a>
            </div>
          
        </div><!-- /.card-header -->
        <div class="card-body">
            <div class="timeline">
                <div class="time-label">
                    <span class="bg-red"> <?php echo "แจ้งเมื่อ : ".DateThai($history['date_report']); ?></span>
                </div>
                <div>
                    <i class="fas fa-envelope bg-blue"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock"></i> <?php echo DateThai($history['date_report']); ?></span>
                        <h3 class="timeline-header"><a href="#"><?php echo $history['u_report_first_name'] . ' ' .$history['u_report_last_name']; ?></a>  แจ้งปัญหา: <?php echo $history["title"]?></h3>
                        <div class="timeline-body">
                            <ul class="ml-4 mb-0 fa-ul text-muted pt-3">
                                    <li class="small">
                                        <span class="fa-li">
                                                <i class="fas fa-book">
                                                </i>
                                        </span> 
                                            รายละเอียด:  <?php echo $history["description"]?>
                                    </li><br>
                                    <li class="small">
                                        <span class="fa-li">
                                                <i class="fas fa-location-arrow">
                                                </i>
                                        </span> 
                                            สถานที่:  <?php echo $history["location"]?>
                                    </li><br>
                                    <li class="small">
                                        <span class="fa-li">
                                            <i class="fas fa-mail-bulk">
                                            </i>
                                        </span> 
                                            อีเมลล์: <?php echo $history["u_report_email"]?>
                                    </li> <br>
                                    <li class="small">
                                        <span class="fa-li">
                                                <i class="fas fa-lg fa-phone">
                                                </i>
                                        </span> 
                                            เบอร์ติดต่อ #:  <?php echo $history["u_report_phone_number"]?>
                                    </li>
                            </ul>
                        </div>
                    </div>
                </div>

            <?php if($history["assign_by"] != null) {?> 
                <div class="time-label">
                    <span class="bg-green"> <?php echo "มอบหมายงานเมื่อ : ".DateThai($history['date_assign']); ?></span>
                </div>
                <div>
                    <i class="fas fa-user bg-green"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock"></i><?php echo DateThai($history['date_assign']); ?></span>
                        <h3 class="timeline-header no-border"><a href="#"><?php echo $history['a_f_name'] . ' ' .$history['a_l_name']; ?></a> ผู้มอบหมายงาน
                        </h3>
                        <div class="timeline-body">
                            <ul class="ml-4 mb-0 fa-ul text-muted pt-3">
                                <li class="small">
                                    <span class="fa-li">
                                        <i class="fas fa-mail-bulk">
                                        </i>
                                    </span> 
                                        อีเมลล์: <?php echo $history["a_email"]?>
                                </li> <br>
                                <li class="small">
                                    <span class="fa-li">
                                            <i class="fas fa-lg fa-phone">
                                             </i>
                                    </span> 
                                        เบอร์ติดต่อ #:  <?php echo $history["a_phone_number"]?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div>
                    <i class="fas fa-user-plus bg-green"></i>
                    <div class="timeline-item">
                        <!-- <span class="time"><i class="fas fa-clock"></i> 27 mins ago</span> -->
                        <h3 class="timeline-header"><a href="#"><?php echo $history['t_f_name'] . ' ' .$history['t_l_name']; ?></a> ช่างที่ถูกมอบหมาย</h3>
                        <div class="timeline-body">
                            <ul class="ml-4 mb-0 fa-ul text-muted pt-3">
                                <li class="small">
                                    <span class="fa-li">
                                        <i class="fas fa-mail-bulk">
                                        </i>
                                    </span> 
                                        อีเมลล์: <?php echo $history["t_email"]?>
                                </li> <br>
                                <li class="small">
                                    <span class="fa-li">
                                            <i class="fas fa-lg fa-phone">
                                             </i>
                                    </span> 
                                        เบอร์ติดต่อ #:  <?php echo $history["t_phone_number"]?>
                                </li>
                            </ul>
                        </div>
                        
                    </div>
                </div>
                    <?php if($history["date_working"] != null) {?> 
                        <div class="time-label">
                            <span class="bg-green"> <?php echo "รับงานเมื่อ : ".DateThai($history['date_working']); ?></span>
                        </div>
                        <div>
                    <i class="fas fa-users-cog bg-yellow"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock"></i> <?php echo DateThai($history['date_working']); ?></span>
                        <h3 class="timeline-header"><a href="#"><?php echo $history['t_f_name'] . ' ' .$history['t_l_name']; ?></a> รับงาน && เรื่มทำงาน </h3>
                      
                        </div>
                    </div>
                    <?php }?>
                        <?php if($history["date_close"] != null) {?> 
                            <div class="time-label">
                            <span class="bg-green"> <?php echo "ดำเนินการสำเร็จเมื่อ : ".DateThai($history['date_close']); ?></span>
                        </div>
                        <div>
                        <i class="fas fa-user-check bg-green"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i> <?php echo DateThai($history['date_close']); ?></span>
                                <h3 class="timeline-header"><a href="#"><?php echo $history['t_f_name'] . ' ' .$history['t_l_name']; ?></a> ดำเนินการสำเร็จ </h3>
                                <div class="timeline-body">
                                    <ul class="ml-4 mb-0 fa-ul text-muted pt-3">
                                        <li class="small">
                                            <span class="fa-li">
                                                <i class="fas fa-info-circle">
                                                </i>
                                            </span> 
                                                สาเหตุที่พบ: <?php echo $history["cause"]?>
                                        </li> <br>
                                        <li class="small">
                                            <span class="fa-li">
                                                    <i class="far fa-lightbulb">
                                                    </i>
                                            </span> 
                                                วิธีแก้ไข:  <?php echo $history["solution"]?>
                                        </li>
                                    </ul>
                                </div>
                                
                                </div>
                            </div>
                                <?php 
                                    $history["date_working"];
                                    $history["date_close"];
                                    $start_date = new DateTime($history["date_working"]);
                                    $since_start = $start_date->diff(new DateTime($history["date_close"]));
                                    $days = $since_start->d == 0 ? '' : $since_start->d . ' วัน ';
                                    $hours = $since_start->h == 0 ? '' : $since_start->h . ' ชั่วโมง ';
                                    $minutes = $since_start->i == 0 ? '' : $since_start->i . ' นาที ';
                                    $seconds = $since_start->s == 0 ? '' : $since_start->s . ' วินาที ';
                                ?>
                            <div class="time-label">
                                <span class="bg-danger">
                                    <?php echo "ระยะเวลาที่ใช้ในการดำเนินการหลังจากการรับงาน : "
                                        .$days.' '
                                        .$hours.' '
                                        .$minutes.' '
                                        .$seconds
                                    ?>
                                </span>
                            </div>
                        <?php }?>
            <?php }?>
            <?php if($history["date_working"] == null || $history["date_close"] == null) {?> 
                <div>
                    <i class="fas fa-clock bg-gray"></i>
                </div>
            <?php }else{?>
                <div>
                    <i class="fas fa-check bg-green"></i>
                </div>
            <?php }?>
               
            </div>

        </div><!-- /.card-body -->
    </div>

</section>


<?php include("../layouts/script.php"); ?>