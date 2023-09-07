<?php
    include('../connect_db.php');

    $sql_user_report = "SELECT tr.*,ts.status_name,u.first_name,u.last_name,u.email,u.phone_number
                         FROM tbl_reports tr 
                            INNER JOIN tbl_status ts ON ts.status_id = tr.status
                                LEFT JOIN users u ON u.id = tr.user_id 
                                            WHERE tr.status>1 AND tr.is_active = 'Y'";
    $reports = mysqli_query($conn,$sql_user_report);

?>
<section class="col-lg-12 connectedSortable">
    <!-- Custom tabs (Charts with tabs)-->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-solid fa-table"></i>
                การดำเนินการของช่าง
            </h3>
        </div><!-- /.card-header -->
        <div class="card-body">
        <table id="working_tbl" class="table table-bordered table-striped dataTable">
                    <thead>
                        <tr role="row" class="info">
                            <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">รหัสการแจ้ง</th>
                            <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">วันที่แจ้ง</th>
                            <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">วันที่มอบหมาย</th>
                            <th tabindex="0" rowspan="1" colspan="1" style="width: 10%;">ปัญหาที่พบ - รายละเอียดปัญหา</th>
                            <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">ผู้แจ้ง</th>
                            <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">สถานที่</th>
                            <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">สถานะ</th>
                            <th tabindex="0" rowspan="1" colspan="1" style="width: 5%;">--</th>
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
                                <?php echo DateThai($list_report['date_assign']); ?>
                            </td>
                            <td> 
                                <?php echo $list_report['title'] ?> <br>
                                <small> <?php echo $list_report['description'] ?> </small> 
                            </td>
                            <td> 
                                <?php echo $list_report['first_name'].' '. $list_report['last_name']?> <br>
                                <small><?php echo "เบอร์ติดต่อ: ".$list_report['phone_number'] ?></small>
                            </td>
                            <td> 
                                <?php echo $list_report['location'] ?>
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
                                <a class="btn btn-danger btn-sm" onclick="delete_report('<?= $list_report['code']?>')">
                                    <i class="fas fa-trash">
                                    </i>
                                    ลบ
                                </a>
                            </td>
                        </tr>
                        <?php  }?>
                    </tbody>
                </table>
            </div>
        </div><!-- /.card-body -->
    </div>
    <!-- /.card -->
</section>

<?php include("../layouts/script.php"); ?>
<?php

if($_GET['act']=='working'&&$_GET['add']=='success'){  
    echo '
    <script>
    setTimeout(() => {
        window.location.href = "index.php?act=working";
    }, "3000");
    toastr.success("มอบหมายงานสำเร็จ");
    </script>';
}



?>

<script>
     function delete_report(code){
            Swal.fire({
                title: 'ต้องการลบข้อมูลนี้ใช่หรือไม่ ?',
                showDenyButton: true,
                confirmButtonText: 'ใช่',
                denyButtonText: `ไม่ใช่`,
                }).then((result) => {
                if (result.isConfirmed) {
                                $.ajax({
                                    method: 'POST',
                                    url: 'delete_rp_db.php',
                                    data: {
                                    report_delete:code,
                                    code:code
                                    },
                                    dataType: 'JSON',
                                    success: function(data){
                                        if(data.status_code == 200){
                                            show_notify(data.msg);
                                            setTimeout(function(){location.href="index.php?act=working"} ,3000);    
                                        }else{
                                            show_notify_erors(data.msg)
                                            setTimeout(function(){location.href="index.php?act=working"} ,3000);  
                                        }
                                    },error: function(err){
                                        console.log(err);
                                    }
                            });
                    } else if (result.isDenied) {
                        show_notify_info("ยกเลิกเรียบร้อยแล้ว");
                    }
                })
         }
</script>