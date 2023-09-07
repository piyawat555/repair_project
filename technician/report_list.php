<?php
    include('../connect_db.php');

    $sql_user_report = "SELECT tr.*,ts.status_name,u.first_name,u.last_name,u.phone_number FROM tbl_reports tr 
                            INNER JOIN tbl_status ts ON ts.status_id = tr.status 
                                INNER JOIN users u ON u.id = tr.user_id
                                    WHERE tr.status=2 AND tr.technician_id=$user_id AND tr.is_active = 'Y'";
    $reports = mysqli_query($conn,$sql_user_report);

?>
<section class="col-lg-12 connectedSortable">
    <!-- Custom tabs (Charts with tabs)-->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-tachometer-alt"></i>
                รายการแจ้งซ่อม
            </h3>
        </div><!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped dataTable">
                    <thead>
                        <tr role="row" class="info">
                            <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">รหัสการแจ้ง</th>
                            <th tabindex="0" rowspan="1" colspan="1" style="width: 10%;">วันที่แจ้ง</th>
                            <th tabindex="0" rowspan="1" colspan="1" style="width: 10%;">ปัญหาที่พบ - รายละเอียดปัญหา</th>
                            <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">สถานที่</th>
                            <th tabindex="0" rowspan="1" colspan="1" style="width: 10%;">ผู้แจ้ง</th>
                            <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">สถานะ</th>
                            <th tabindex="0" rowspan="1" colspan="1" style="width: 10%;">--</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                            foreach($reports as $list_report) {
                    ?>
                        <tr>
                            <td>
                                <?php echo $list_report['code'] ?>
                            </td>
                            <td>
                                <?php echo DateThai($list_report['date_report']); ?>
                            </td>
                            <td> 
                                <?php echo $list_report['title'] ?> <br>
                                <small> <?php echo $list_report['description'] ?> </small> 
                            </td>
                            <td> 
                                <?php echo $list_report['location'] ?>
                            </td>
                            <td>
                                <?php echo $list_report['first_name'] . ' ' .$list_report['last_name'] ?> <br>
                                <small> <?php echo "เบอร์ติดต่อ : " .$list_report['phone_number'] ?> </small> 
                            </td>
                            <td>
                                <span class="badge badge-primary">
                                    <?php echo $list_report['status_name'] ?>
                                </span>
                            </td>
                            <td class="project-actions text-right">
                                <a class="btn btn-primary btn-sm" href="index.php?act=history&code=<?php echo $list_report['code']; ?>">
                                    <i class="fas fa-folder">
                                    </i>
                                    ดูข้อมูล
                                </a>
                                <a class="btn btn-info btn-sm" href="technician_db.php?act=accept&code=<?php echo $list_report['code']; ?>">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                    รับงาน
                                </a>
                            </td>
                        </tr>
                        <?php  }?>
                    </tbody>
                </table>
            </div><!-- /.card-body -->
        </div><!-- /.card-body -->
    </div>
    <!-- /.card -->
</section>


<?php include("../layouts/script.php"); ?>

